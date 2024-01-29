<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\BasicSettings\Basic;
use Mollie\Laravel\Facades\Mollie;
use App\Models\Language;
use App\Models\Membership;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class MollieController extends Controller
{
    public $public_key;

    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('mollie')->first();
        $paydata = $data->convertAutoData();
        $this->public_key = $paydata['key'];
        Config::set('mollie.key', $paydata['key']);
    }

    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bex)
    {
        $notify_url = $_success_url;
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => $bex->base_currency_text,
                'value' => '' . sprintf('%0.2f', $_amount) . '', // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'description' => $_title,
            'redirectUrl' => $notify_url,
        ]);

        /** add payment ID to session **/
        Session::put('request', $request->all());
        Session::put('payment_id', $payment->id);
        Session::put('success_url', $_success_url);

        $payment = Mollie::api()->payments()->get($payment->id);
        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function successPayment(Request $request)
    {
        $requestData = Session::get('request');
        $bs = Basic::first();
        $cancel_url = Session::get('cancel_url');
        $payment_id = Session::get('payment_id');
        /** Get the payment ID before session clear **/

        $payment = Mollie::api()->payments()->get($payment_id);

        if ($payment->status == 'paid') {
            $paymentFor = Session::get('paymentFor');
            $package = Package::find($requestData['package_id']);
            $transaction_id = VendorPermissionHelper::uniqidReal(8);
            $transaction_details = json_encode($payment);
            if ($paymentFor == "membership") {
                $amount = $requestData['price'];
                $password = $requestData['password'];
                $checkout = new VendorCheckoutController();

                $vendor = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

                $lastMemb = $vendor->memberships()->orderBy('id', 'DESC')->first();

                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);
                $file_name = $this->makeInvoice($requestData, "membership", $vendor, $password, $amount, "Paypal", $requestData['phone'], $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $vendor->email,
                    'toName' => $vendor->fname,
                    'username' => $vendor->username,
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

                session()->flash('success', 'Your payment has been completed.');
                Session::forget('request');
                Session::forget('paymentFor');
                return redirect()->route('success.page');
            } elseif ($paymentFor == "extend") {
                $amount = $requestData['price'];
                $password = uniqid('qrcode');
                $checkout = new VendorCheckoutController();
                $vendor = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

                $lastMemb = Membership::where('vendor_id', $vendor->id)->orderBy('id', 'DESC')->first();
                $activation = Carbon::parse($lastMemb->start_date);
                $expire = Carbon::parse($lastMemb->expire_date);

                $file_name = $this->makeInvoice($requestData, "extend", $vendor, $password, $amount, $requestData["payment_method"], $vendor->phone, $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);

                $mailer = new MegaMailer();
                $data = [
                    'toMail' => $vendor->email,
                    'toName' => $vendor->fname,
                    'username' => $vendor->username,
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

                Session::forget('request');
                Session::forget('paymentFor');
                return redirect()->route('success.page');
            }
        }
        return redirect($cancel_url);
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', __('cancel_payment'));
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
