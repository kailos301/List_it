<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $username = $request->username;
        $data['memberships'] = Membership::query()->when($search, function ($query, $search) {
            return $query->where('transaction_id', 'like', '%' . $search . '%');
        })->whereHas('vendor', function (Builder $query) use ($username) {
            $query->when($username, function ($query, $username) {
                return $query->where('username', 'like', '%' . $username . '%');
            });
        })
            ->where('vendor_id', '!=', 0)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('backend.payment_log.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function transaction(Request $request)
    {
        $search = $request->search;
        $data['memberships'] = Membership::query()
            ->where('admin_id', Auth::guard('web')->user()->id)
            ->when($search, function ($query, $search) {
                return $query->where('transaction_id', $search);
            })
            ->orderBy('expire_date', 'DESC')
            ->paginate(10);
        return view('admin.transaction.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     */
    public function update(Request $request)
    {
        $bs = Basic::first();
        $membership = Membership::query()->where('id', $request->id)->first();
        $vendor = Vendor::query()->where('id', $membership->vendor_id)->first();
        $package = Package::query()->where('id', $membership->package_id)->first();
        $count_membership = Membership::query()->where('vendor_id', $membership->vendor_id)->count();
        if ($request->status === "1") {
            $member['first_name'] = $vendor->first_name;
            $member['last_name'] = $vendor->last_name;
            $member['username'] = $vendor->username;
            $member['email'] = $vendor->email;
            $data['payment_method'] = $membership->payment_method;

            //comparison date
            $date1 = Carbon::createFromFormat('m/d/Y', \Carbon\Carbon::parse($membership->start_date)->format('m/d/Y'));
            $date2 = Carbon::createFromFormat('m/d/Y', \Carbon\Carbon::now()->format('m/d/Y'));
            $result = $date1->gte($date2);
            if ($result) {
                $data['start_date'] = $membership->start_date;
                $data['expire_date'] = $membership->expire_date;
            } else {
                $data['start_date'] = Carbon::today()->format('d-m-Y');
                if ($package->term === "daily") {
                    $data['expire_date'] = Carbon::today()->addDay()->format('d-m-Y');
                } elseif ($package->term === "weekly") {
                    $data['expire_date'] = Carbon::today()->addWeek()->format('d-m-Y');
                } elseif ($package->term === "monthly") {
                    $data['expire_date'] = Carbon::today()->addMonth()->format('d-m-Y');
                } elseif ($package->term === "lifetime") {
                    $data['expire_date'] = Carbon::maxValue()->format('d-m-Y');
                } else {
                    $data['expire_date'] = Carbon::today()->addYear()->format('d-m-Y');
                }
                $membership->update(['start_date' =>  Carbon::parse($data['start_date'])]);
                $membership->update(['expire_date' =>  Carbon::parse($data['expire_date'])]);
            }

            // if previous membership package is lifetime, then exipre that membership
            $previousMembership = Membership::query()
                ->where([
                    ['vendor_id', $vendor->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])
                ->where('status', 1)
                ->orderBy('created_at', 'DESC')
                ->first();
            if (!is_null($previousMembership)) {
                $previousPackage = Package::query()
                    ->select('term')
                    ->where('id', $previousMembership->package_id)
                    ->first();
                if ($previousPackage->term === 'lifetime' || $previousMembership->is_trial == 1) {
                    $yesterday = Carbon::yesterday()->format('d-m-Y');
                    $previousMembership->expire_date = Carbon::parse($yesterday);
                    $previousMembership->save();
                }
            }

            if ($count_membership > 1) {

                $mailTemplate = 'payment_accepted_for_membership_extension_offline_gateway';
                $mailType = 'paymentAcceptedForMembershipExtensionOfflineGateway';
            } else {

                $mailTemplate = 'payment_accepted_for_registration_offline_gateway';
                $mailType = 'paymentAcceptedForRegistrationOfflineGateway';

                $vendor->update([
                    'status' => 1
                ]);
            }
            $filename = $this->makeInvoice($data, "membership", $member, null, $membership->price, "offline", $vendor->phone, $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $membership->transaction_id, $package->title, $membership);

            $mailer = new MegaMailer();
            $data = [
                'toMail' => $vendor->email,
                'toName' => $vendor->fname,
                'username' => $vendor->username,
                'package_title' => $package->title,
                'package_price' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                'activation_date' => $data['start_date'],
                'expire_date' => $package->term == "lifetime" ? 'Lifetime' : $data['expire_date'],
                'membership_invoice' => $filename,
                'website_title' => $bs->website_title,
                'templateType' => $mailTemplate,
                'type' => $mailType
            ];
            $mailer->mailFromAdmin($data);
            @unlink(public_path('assets/front/invoices/' . $filename));
        } elseif ($request->status == 2) {
            if ($count_membership > 1) {

                $mailTemplate = 'payment_rejected_for_membership_extension_offline_gateway';
                $mailType = 'paymentRejectedForMembershipExtensionOfflineGateway';
            } else {

                $mailTemplate = 'payment_rejected_for_registration_offline_gateway';
                $mailType = 'paymentRejectedForRegistrationOfflineGateway';
            }

            $mailer = new MegaMailer();
            $data = [
                'toMail' => $vendor->email,
                'toName' => $vendor->fname,
                'username' => $vendor->username,
                'package_title' => $package->title,
                'package_price' => ($bs->base_currency_symbol_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_symbol_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                'website_title' => $bs->website_title,
                'templateType' => $mailTemplate,
                'type' => $mailType
            ];
            $mailer->mailFromAdmin($data);
        }


        $membership->update(['status' => $request->status]);

        session()->flash('success', "Membership status changed successfully!");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
