<?php

namespace App\Http\Controllers\FrontEnd\Instrument;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Controllers\FrontEnd\PaymentGateway\FlutterwaveController;
use App\Http\Controllers\FrontEnd\PaymentGateway\InstamojoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MercadoPagoController;
use App\Http\Controllers\FrontEnd\PaymentGateway\MollieController;
use App\Http\Controllers\FrontEnd\PaymentGateway\OfflineController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PayPalController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaystackController;
use App\Http\Controllers\FrontEnd\PaymentGateway\PaytmController;
use App\Http\Controllers\FrontEnd\PaymentGateway\RazorpayController;
use App\Http\Controllers\FrontEnd\PaymentGateway\StripeController;
use App\Http\Helpers\BasicMailer;
use App\Http\Requests\Instrument\BookingProcessRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingProcessController extends Controller
{
  public function index(BookingProcessRequest $request)
  {
    if (!$request->exists('gateway')) {
      $request->session()->flash('error', 'Please select a payment method.');

      return redirect()->back()->withInput();
    } else if ($request['gateway'] == 'paypal') {
      $paypal = new PayPalController();

      return $paypal->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'instamojo') {
      $instamojo = new InstamojoController();

      return $instamojo->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'paystack') {
      $paystack = new PaystackController();

      return $paystack->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'flutterwave') {
      $flutterwave = new FlutterwaveController();

      return $flutterwave->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'razorpay') {
      $razorpay = new RazorpayController();

      return $razorpay->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'mercadopago') {
      $mercadopago = new MercadoPagoController();

      return $mercadopago->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'mollie') {
      $mollie = new MollieController();

      return $mollie->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'stripe') {
      $stripe = new StripeController();

      return $stripe->index($request, 'equipment booking');
    } else if ($request['gateway'] == 'paytm') {
      $paytm = new PaytmController();

      return $paytm->index($request, 'equipment booking');
    } else {
      $offline = new OfflineController();

      return $offline->index($request, 'equipment booking');
    }
  }

  public function calculation(Request $request)
  {
    if ($request->session()->has('totalPrice')) {
      $total = $request->session()->get('totalPrice');
    }

    if ($request->session()->has('equipmentDiscount')) {
      $discountVal = $request->session()->get('equipmentDiscount');
    }

    $discount = isset($discountVal) ? floatval($discountVal) : 0.00;
    $subtotal = $total - $discount;

    $taxData = Basic::select('equipment_tax_amount')->first();
    $taxAmount = floatval($taxData->equipment_tax_amount);
    $calculatedTax = $subtotal * ($taxAmount / 100);

    $shippingCharge = 0.00;

    if ($request['shipping_method'] == 'two way delivery') {
      $locationId = $request['location'];

      $location = Location::query()->find($locationId);
      $shippingCharge = floatval($location->charge);
    }

    $grandTotal = $subtotal + $calculatedTax + $shippingCharge;

    $calculatedData = array(
      'total' => $total,
      'discount' => $discount,
      'subtotal' => $subtotal,
      'shippingCharge' => $request['shipping_method'] == 'two way delivery' ? $shippingCharge : null,
      'tax' => $calculatedTax,
      'grandTotal' => $grandTotal
    );

    return $calculatedData;
  }

  public function getDates($dateString)
  {
    $arrOfDate = explode(' ', $dateString);
    $date_1 = $arrOfDate[0];
    $date_2 = $arrOfDate[2];

    $dates = array(
      'startDate' => date_create($date_1),
      'endDate' => date_create($date_2)
    );

    return $dates;
  }

  public function getLocation($locationId)
  {
    $location = Location::query()->find($locationId);
    $locationName = $location->name;

    return $locationName;
  }

  public function storeData($arrData)
  {
    $equipment = Equipment::findOrFail($arrData['equipmentId']);
    if (!empty($equipment)) {
      if ($equipment->vendor_id != NULL) {
        $vendor_id = $equipment->vendor_id;
      } else {
        $vendor_id = NULL;
      }
    } else {
      $vendor_id = NULL;
    }
    //generate 8 digit booking number
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $booking_number = substr(str_shuffle(str_repeat($pool, 5)), 0, 8);

    $bookingInfo = EquipmentBooking::query()->create([
      'user_id' => Auth::guard('web')->check() == true ? Auth::guard('web')->user()->id : null,
      'booking_number' => $booking_number,
      'name' => $arrData['name'],
      'contact_number' => $arrData['contactNumber'],
      'email' => $arrData['email'],
      'vendor_id' => $vendor_id,
      'equipment_id' => $arrData['equipmentId'],
      'start_date' => $arrData['startDate'],
      'end_date' => $arrData['endDate'],
      'shipping_method' => $arrData['shippingMethod'],
      'location' => $arrData['location'],
      'total' => array_key_exists('total', $arrData) ? $arrData['total'] : null,
      'discount' => array_key_exists('discount', $arrData) ? $arrData['discount'] : null,
      'shipping_cost' => array_key_exists('shippingCost', $arrData) ? $arrData['shippingCost'] : null,
      'tax' => array_key_exists('tax', $arrData) ? $arrData['tax'] : null,
      'grand_total' => array_key_exists('grandTotal', $arrData) ? $arrData['grandTotal'] : null,
      'currency_symbol' => array_key_exists('currencySymbol', $arrData) ? $arrData['currencySymbol'] : null,
      'currency_symbol_position' => array_key_exists('currencySymbolPosition', $arrData) ? $arrData['currencySymbolPosition'] : null,
      'currency_text' => array_key_exists('currencyText', $arrData) ? $arrData['currencyText'] : null,
      'currency_text_position' => array_key_exists('currencyTextPosition', $arrData) ? $arrData['currencyTextPosition'] : null,
      'booking_type' => array_key_exists('bookingType', $arrData) ? $arrData['bookingType'] : null,
      'price_message' => array_key_exists('priceMessage', $arrData) ? $arrData['priceMessage'] : null,
      'payment_method' => array_key_exists('paymentMethod', $arrData) ? $arrData['paymentMethod'] : null,
      'gateway_type' => array_key_exists('gatewayType', $arrData) ? $arrData['gatewayType'] : null,
      'payment_status' => $arrData['paymentStatus'],
      'shipping_status' => $arrData['shippingStatus'],
      'attachment' => array_key_exists('attachment', $arrData) ? $arrData['attachment'] : null
    ]);

    return $bookingInfo;
  }

  public function generateInvoice($bookingInfo)
  {
    $fileName = $bookingInfo->booking_number . '.pdf';

    $data['bookingInfo'] = $bookingInfo;

    $directory = config('dompdf.public_path') . 'equipment/';
    @mkdir($directory, 0775, true);

    $fileLocated = $directory . $fileName;

    $data['taxData'] = Basic::select('equipment_tax_amount')->first();

    Pdf::loadView('frontend.equipment.invoice', $data)->save($fileLocated);

    return $fileName;
  }

  public function prepareMail($bookingInfo)
  {
    // get the mail template info from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'equipment_booking')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $info = Basic::select('website_title')->first();

    // preparing dynamic data
    $customerName = $bookingInfo->name;
    $bookingNumber = $bookingInfo->booking_number;
    $bookingDate = date_format($bookingInfo->created_at, 'M d, Y');

    $equipmentId = $bookingInfo->equipment_id;
    $equipment = Equipment::query()->find($equipmentId);

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $equipmentInfo = $equipment->content()->where('language_id', $language->id)->first();
    $equipmentTitle = $equipmentInfo->title;

    $startDate = date_format($bookingInfo->start_date, 'M d, Y');
    $endDate = date_format($bookingInfo->end_date, 'M d, Y');
    $websiteTitle = $info->website_title;

    if (Auth::guard('web')->check() == true) {
      $bookingLink = '<p>Booking Details: <a href=' . url("user/equipment-booking/" . $bookingInfo->id . "/details") . '>Click Here</a></p>';
    } else {
      $bookingLink = '';
    }

    // replacing with actual data
    $mailBody = str_replace('{customer_name}', $customerName, $mailBody);
    $mailBody = str_replace('{booking_number}', $bookingNumber, $mailBody);
    $mailBody = str_replace('{booking_date}', $bookingDate, $mailBody);
    $mailBody = str_replace('{equipment_name}', $equipmentTitle, $mailBody);
    $mailBody = str_replace('{start_date}', $startDate, $mailBody);
    $mailBody = str_replace('{end_date}', $endDate, $mailBody);
    $mailBody = str_replace('{website_title}', $websiteTitle, $mailBody);
    $mailBody = str_replace('{booking_link}', $bookingLink, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $bookingInfo->email;

    $mailData['invoice'] = public_path('assets/file/invoices/equipment/') . $bookingInfo->invoice;

    BasicMailer::sendMail($mailData);

    return;
  }

  public function complete($type = null)
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['bookingType'] = $type;

    if (session()->has('shippingMethod')) {
      session()->forget('shippingMethod');
    }

    return view('frontend.payment.booking-success', $queryResult);
  }

  public function cancel(Request $request)
  {
    $request->session()->flash('error', 'Sorry, an error has occured!');

    return redirect()->route('all_equipment');
  }
}
