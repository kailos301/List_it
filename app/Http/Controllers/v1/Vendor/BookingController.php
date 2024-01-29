<?php

namespace App\Http\Controllers\Vendor;

use App\Exports\EquipmentBookingsExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Language;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class BookingController extends Controller
{
    public function bookings(Request $request)
    {
        $information['basicData'] = Basic::select('self_pickup_status', 'two_way_delivery_status')->first();

        $language = Language::where('code', $request->language)->first();

        $bookingNumber = $paymentStatus = $shippingType = $shippingStatus = null;

        if ($request->filled('booking_no')) {
            $bookingNumber = $request['booking_no'];
        }
        if ($request->filled('payment_status')) {
            $paymentStatus = $request['payment_status'];
        }
        if ($request->filled('shipping_type')) {
            $shippingType = $request['shipping_type'];
        }
        if ($request->filled('shipping_status')) {
            $shippingStatus = $request['shipping_status'];
        }

        $bookings = EquipmentBooking::query()->where('vendor_id', Auth::guard('vendor')->user()->id)->when($bookingNumber, function ($query, $bookingNumber) {
            return $query->where('booking_number', 'like', '%' . $bookingNumber . '%');
        })
            ->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', '=', $paymentStatus);
            })
            ->when($shippingType, function ($query, $shippingType) {
                return $query->where('shipping_method', '=', $shippingType);
            })
            ->when($shippingStatus, function ($query, $shippingStatus) {
                return $query->where('shipping_status', '=', $shippingStatus);
            })
            ->orderByDesc('id')
            ->paginate(10);

        $bookings->map(function ($booking) use ($language) {
            $equipment = $booking->equipmentInfo()->first();
            $booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->pluck('title')->first();
        });

        $information['bookings'] = $bookings;

        return view('vendors.booking.index', $information);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = EquipmentBooking::find($id);

        if ($request['payment_status'] == 'completed') {
            $booking->update([
                'payment_status' => 'completed'
            ]);

            $statusMsg = 'Your payment is complete.';

            // generate an invoice in pdf format
            $invoice = $this->generateInvoice($booking);

            // then, update the invoice field info in database
            $booking->update([
                'invoice' => $invoice
            ]);
        } else if ($request['payment_status'] == 'pending') {
            $booking->update([
                'payment_status' => 'pending'
            ]);

            $statusMsg = 'Your payment is pending.';
        } else {
            $booking->update([
                'payment_status' => 'rejected'
            ]);

            $statusMsg = 'Your payment has been rejected.';
        }

        $mailData = [];

        if (isset($invoice)) {
            $mailData['invoice'] = public_path(public_path('assets/file/invoices/equipment/')) . $invoice;
        }

        $mailData['subject'] = 'Notification of payment status';

        $mailData['body'] = 'Hi ' . $booking->name . ',<br/><br/>This email is to notify the payment status of your equipment booking. ' . $statusMsg;

        $mailData['recipient'] = $booking->email;

        $mailData['sessionMessage'] = 'Payment status updated & mail has been sent successfully!';

        BasicMailer::sendMail($mailData);

        return redirect()->back();
    }

    public function generateInvoice($bookingInfo)
    {
        $fileName = $bookingInfo->booking_number . '.pdf';

        $data['bookingInfo'] = $bookingInfo;

        $directory = public_path('assets/file/invoices/equipment/');
        @mkdir($directory, 0775, true);

        $fileLocated = $directory . $fileName;

        $data['taxData'] = Basic::select('equipment_tax_amount')->first();

        PDF::loadView('frontend.equipment.invoice', $data)->save($fileLocated);

        return $fileName;
    }

    public function updateShippingStatus(Request $request, $id)
    {
        $booking = EquipmentBooking::find($id);

        if ($request['shipping_status'] == 'pending') {
            $booking->update([
                'shipping_status' => 'pending'
            ]);

            $statusMsg = 'The shipping status of your booked equipment is pending.';
        } else if ($request['shipping_status'] == 'taken') {
            $booking->update([
                'shipping_status' => 'taken'
            ]);

            $statusMsg = 'We want to inform you that you have taken your booked equipment.<br/><br/>Thank you.';
        } else if ($request['shipping_status'] == 'delivered') {
            $booking->update([
                'shipping_status' => 'delivered'
            ]);

            $statusMsg = 'The equipment you have booked has been successfully delivered to your location.';
        } else {
            $booking->update([
                'shipping_status' => 'returned'
            ]);

            $statusMsg = 'You have returned your booked equipment.<br/><br/>Thank you.';
        }

        $mailData['subject'] = 'Notification of shipping status';

        $mailData['body'] = 'Hi ' . $booking->name . ',<br/><br/>This email is to notify the shipping status of your booked equipment. ' . $statusMsg;

        $mailData['recipient'] = $booking->email;

        $mailData['sessionMessage'] = 'Shipping status updated & mail has been sent successfully!';

        BasicMailer::sendMail($mailData);

        return redirect()->back();
    }

    public function show($id, Request $request)
    {
        $details = EquipmentBooking::find($id);

        $information['details'] = $details;

        if ($details->vendor_id != Auth::guard('vendor')->user()->id) {
            return redirect()->route('vendor.dashboard');
        }

        $information['language'] = Language::where('code', $request->language)->first();

        $information['tax'] = Basic::select('equipment_tax_amount')->first();

        return view('vendors.booking.details', $information);
    }

    public function destroy($id)
    {
        $booking = EquipmentBooking::find($id);

        // delete the attachment
        @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);

        // delete the invoice
        @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $booking = EquipmentBooking::find($id);

            // delete the attachment
            @unlink(public_path('assets/file/attachments/equipment/') . $booking->attachment);

            // delete the invoice
            @unlink(public_path('assets/file/invoices/equipment/') . $booking->invoice);

            $booking->delete();
        }

        $request->session()->flash('success', 'Bookings deleted successfully!');

        return response()->json(['status' => 'success'], 200);
    }


    public function report(Request $request)
    {
        $queryResult['onlineGateways'] = OnlineGateway::query()->where('status', '=', 1)->get();
        $queryResult['offlineGateways'] = OfflineGateway::query()->where('status', '=', 1)->orderBy('serial_number', 'asc')->get();

        $from = $to = $paymentGateway = $paymentStatus = $shippingStatus = null;

        if ($request->filled('payment_gateway')) {
            $paymentGateway = $request->payment_gateway;
        }
        if ($request->filled('payment_status')) {
            $paymentStatus = $request->payment_status;
        }
        if ($request->filled('shipping_status')) {
            $shippingStatus = $request->shipping_status;
        }

        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->toDateString();
            $to = Carbon::parse($request->to)->toDateString();

            $records = EquipmentBooking::query()
                ->where('vendor_id', Auth::guard('vendor')->user()->id)
                ->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to)
                ->when($paymentGateway, function (Builder $query, $paymentGateway) {
                    return $query->where('payment_method', '=', $paymentGateway);
                })
                ->when($paymentStatus, function (Builder $query, $paymentStatus) {
                    return $query->where('payment_status', '=', $paymentStatus);
                })
                ->when($shippingStatus, function (Builder $query, $shippingStatus) {
                    return $query->where('shipping_status', '=', $shippingStatus);
                })
                ->select('booking_number', 'name', 'contact_number', 'email', 'equipment_id', 'start_date', 'end_date', 'shipping_method', 'location', 'total', 'discount', 'shipping_cost', 'tax', 'grand_total', 'received_amount', 'vendor_id', 'comission', 'currency_symbol', 'currency_symbol_position', 'payment_method', 'payment_status', 'shipping_status', 'created_at')
                ->orderByDesc('id');

            $collection_1 = $this->manipulateCollection($records->get());
            Session::put('equipment_bookings', $collection_1);

            $collection_2 = $this->manipulateCollection($records->paginate(10));
            $queryResult['bookings'] = $collection_2;
        } else {
            Session::put('equipment_bookings', null);
            $queryResult['bookings'] = [];
        }

        return view('vendors.booking.report', $queryResult);
    }

    public function manipulateCollection($bookings)
    {
        $language = Language::query()->where('is_default', '=', 1)->first();

        $bookings->map(function ($booking) use ($language) {
            // equipment title
            $equipment = $booking->equipmentInfo()->first();
            $booking['equipmentTitle'] = $equipment->content()->where('language_id', $language->id)->pluck('title')->first();

            // format booking start date
            $startDateObj = Carbon::parse($booking->start_date);
            $booking['startDate'] = $startDateObj->format('M d, Y');

            // format booking end date
            $endDateObj = Carbon::parse($booking->end_date);
            $booking['endDate'] = $endDateObj->format('M d, Y');

            // format booking create date
            $createDateObj = Carbon::parse($booking->created_at);
            $booking['createdAt'] = $createDateObj->format('M d, Y');
        });

        return $bookings;
    }

    public function exportReport()
    {
        if (Session::has('equipment_bookings')) {
            $equipmentBookings = Session::get('equipment_bookings');

            if (count($equipmentBookings) == 0) {
                Session::flash('warning', 'No booking found to export!');

                return redirect()->back();
            } else {
                return Excel::download(new EquipmentBookingsExport($equipmentBookings), 'equipment-bookings.csv');
            }
        } else {
            Session::flash('error', 'There has no booking to export.');

            return redirect()->back();
        }
    }
}
