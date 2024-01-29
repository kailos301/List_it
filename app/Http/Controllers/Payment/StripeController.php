<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UserPermissionHelper;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Package;
use PHPMailer\PHPMailer\Exception;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use App\Models\PaymentGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\VendorInfo;
use Carbon\Carbon;
use Config;
use Session;

class StripeController extends Controller
{
    public function __construct()
    {
        //Set Spripe Keys
        $stripe = OnlineGateway::where('keyword', 'stripe')->first();
        $stripeConf = json_decode($stripe->information, true);
        Config::set('services.stripe.key', $stripeConf["key"]);
        Config::set('services.stripe.secret', $stripeConf["secret"]);
    }

    public function paymentProcess(Request $request, $_amount, $_title, $_success_url, $_cancel_url)
    {

        $title = $_title;
        $price = $_amount;
        $price = round($price, 2);
        $cancel_url = $_cancel_url;

        Session::put('request', $request->all());

        $stripe = Stripe::make(Config::get('services.stripe.secret'));
        try {

            $token = $request->stripeToken;

            if (!isset($token)) {
                return back()->with('error', 'Token Problem With Your Token.');
            }
            $vendorInfo = VendorInfo::where('vendor_id', $request->vendor_id)->first();

            $charge = $stripe->charges()->create([
                'source' => $token,
                'currency' =>  "USD",
                'amount' => $price,
                'description' => $title,
                'receipt_email' => $request->email,
                'metadata' => [
                    'customer_name' => $vendorInfo != null ? $vendorInfo->name : '',
                ]
            ]);


            if ($charge['status'] == 'succeeded') {
                $paymentFor = Session::get('paymentFor');
                $package = Package::find($request->package_id);
                $transaction_id = VendorPermissionHelper::uniqidReal(8);
                $transaction_details = json_encode($charge);

                $bs = Basic::first();

                if ($paymentFor == "membership") {
                    $amount = $request->price;
                    $password = $request->password;
                    $checkout = new VendorCheckoutController();
                    $user = $checkout->store($request, $transaction_id, $transaction_details, $amount, $bs, $password);


                    $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                    $activation = Carbon::parse($lastMemb->start_date);
                    $expire = Carbon::parse($lastMemb->expire_date);
                    $file_name = $this->makeInvoice($request, "membership", $user, $password, $amount, "Stripe", $request['phone'], $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

                    $mailer = new MegaMailer();
                    $data = [
                        'toMail' => $user->email,
                        'toName' => $user->fname,
                        'username' => $user->username,
                        'package_title' => $package->title,
                        'package_price' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'discount' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $lastMemb->discount . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'total' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $lastMemb->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'activation_date' => $activation->toFormattedDateString(),
                        'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                        'membership_invoice' => $file_name,
                        'website_title' => $bs->website_title,
                        'templateType' => 'registration_with_premium_package',
                        'type' => 'registrationWithPremiumPackage'
                    ];
                    $mailer->mailFromAdmin($data);
                    @unlink(public_path('assets/front/invoices/' . $file_name));

                    session()->flash('success', __('successful_payment'));
                    Session::forget('request');
                    Session::forget('paymentFor');
                    return redirect()->route('success.page');
                } elseif ($paymentFor == "extend") {
                    $amount = $request['price'];
                    $password = uniqid('qrcode');
                    $checkout = new VendorCheckoutController();
                    $user = $checkout->store($request, $transaction_id, $transaction_details, $amount, $bs, $password);

                    $lastMemb = $user->memberships()->orderBy('id', 'DESC')->first();
                    $activation = Carbon::parse($lastMemb->start_date);
                    $expire = Carbon::parse($lastMemb->expire_date);
                    $file_name = $this->makeInvoice($request, "extend", $user, $password, $amount, $request["payment_method"], $user->phone, $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

                    $mailer = new MegaMailer();
                    $data = [
                        'toMail' => $user->email,
                        'toName' => $user->fname,
                        'username' => $user->username,
                        'package_title' => $package->title,
                        'package_price' => ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : ''),
                        'activation_date' => $activation->toFormattedDateString(),
                        'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                        'membership_invoice' => $file_name,
                        'website_title' => $bs->website_title,
                        'templateType' => 'membership_extend',
                        'type' => 'membershipExtend'
                    ];
                    $mailer->mailFromAdmin($data);
                    @unlink(public_path('assets/front/invoices/' . $file_name));

                    session()->flash('success', 'Your payment has been completed.');
                    Session::forget('request');
                    Session::forget('paymentFor');
                    return redirect()->route('success.page');
                }
            }
        } catch (Exception $e) {
            return redirect($cancel_url)->with('error', $e->getMessage());
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return redirect($cancel_url)->with('error', $e->getMessage());
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return redirect($cancel_url)->with('error', $e->getMessage());
        }
        return redirect($cancel_url)->with('error', 'Please Enter Valid Credit Card Informations.');
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('error', 'Payment has been canceled');
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
