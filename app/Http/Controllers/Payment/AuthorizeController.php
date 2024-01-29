<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\VendorCheckoutController;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OnlineGateway;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Session;

class AuthorizeController extends Controller
{
    private $gateway;
    public function __construct()
    {
        $data = OnlineGateway::query()->whereKeyword('authorize.net')->first();
        $authorizeNetData = json_decode($data->information, true);
        $this->gateway = Omnipay::create('AuthorizeNetApi_Api');
        $this->gateway->setAuthName($authorizeNetData['login_id']);
        $this->gateway->setTransactionKey($authorizeNetData['transaction_key']);
        if ($authorizeNetData['sandbox_check'] == 1) {
            $this->gateway->setTestMode(true);
        }
    }
    public function paymentProcess(Request $request, $_amount, $_success_url, $_cancel_url, $_title, $bs)
    {
        try {
            $allowedCurrencies = array('USD', 'CAD', 'CHF', 'DKK', 'EUR', 'GBP', 'NOK', 'PLN', 'SEK', 'AUD', 'NZD');
            $currencyInfo = $this->getCurrencyInfo();
            // checking whether the base currency is allowed or not
            if (!in_array($currencyInfo->base_currency_text, $allowedCurrencies)) {
                return redirect()->back()->with('error', 'Invalid currency for authorize.net payment.')->withInput();
            }
            Session::put('request', $request->all());
            $requestData = $request->all();
            $bs = Basic::first();

            if ($request->filled('opaqueDataValue') && $request->filled('opaqueDataDescriptor')) {
                // generate a unique merchant site transaction ID
                $transactionId = rand(100000000, 999999999);
                // dd(sprintf('%0.2f', $_amount), $currencyInfo->base_currency_text, 
                $response = $this->gateway->authorize([
                    'amount' => sprintf('%0.2f', $_amount),
                    'currency' => $currencyInfo->base_currency_text,
                    'transactionId' => $transactionId,
                    'opaqueDataDescriptor' => $request->opaqueDataDescriptor,
                    'opaqueDataValue' => $request->opaqueDataValue
                ])->send();

                if ($response->isSuccessful()) {
                    //success process will be go here
                    $paymentFor = Session::get('paymentFor');
                    $response = json_encode($response, true);
                    $package = Package::find($requestData['package_id']);

                    $transaction_id = VendorPermissionHelper::uniqidReal(8);
                    $transaction_details = $response;
                    if ($paymentFor == "membership") {
                        $amount = $requestData['price'];
                        $password = $requestData['password'];
                        $checkout = new VendorCheckoutController();

                        $vendor = $checkout->store($requestData, $transaction_id, $transaction_details, $amount, $bs, $password);

                        $lastMemb = $vendor->memberships()->orderBy(
                            'id',
                            'DESC'
                        )->first();

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
                } else {
                    //cancel payment
                    return redirect($_cancel_url);
                }
            } else {
                //return cancel url 
                return redirect($_cancel_url);
            }
        } catch (\Exception $th) {
        }
    }

    public function cancelPayment()
    {
        $requestData = Session::get('request');
        $paymentFor = Session::get('paymentFor');
        session()->flash('warning', 'Your payment has been cancel.');
        if ($paymentFor == "membership") {
            return redirect()->route('front.register.view', ['status' => $requestData['package_type'], 'id' => $requestData['package_id']])->withInput($requestData);
        } else {
            return redirect()->route('vendor.plan.extend.checkout', ['package_id' => $requestData['package_id']])->withInput($requestData);
        }
    }
}
