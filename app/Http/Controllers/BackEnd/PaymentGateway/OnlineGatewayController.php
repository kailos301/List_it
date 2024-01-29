<?php

namespace App\Http\Controllers\BackEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class OnlineGatewayController extends Controller
{
  public function index()
  {
    $gatewayInfo['paypal'] = OnlineGateway::where('keyword', 'paypal')->first();
    $gatewayInfo['instamojo'] = OnlineGateway::where('keyword', 'instamojo')->first();
    $gatewayInfo['paystack'] = OnlineGateway::where('keyword', 'paystack')->first();
    $gatewayInfo['flutterwave'] = OnlineGateway::where('keyword', 'flutterwave')->first();
    $gatewayInfo['razorpay'] = OnlineGateway::where('keyword', 'razorpay')->first();
    $gatewayInfo['mercadopago'] = OnlineGateway::where('keyword', 'mercadopago')->first();
    $gatewayInfo['mollie'] = OnlineGateway::where('keyword', 'mollie')->first();
    $gatewayInfo['stripe'] = OnlineGateway::where('keyword', 'stripe')->first();
    $gatewayInfo['paytm'] = OnlineGateway::where('keyword', 'paytm')->first();
    $gatewayInfo['anet'] = OnlineGateway::where('keyword', 'authorize.net')->first();

    return view('backend.payment-gateways.online-gateways', $gatewayInfo);
  }

  public function updatePayPalInfo(Request $request)
  {
    $rules = [
      'paypal_status' => 'required',
      'paypal_sandbox_status' => 'required',
      'paypal_client_id' => 'required',
      'paypal_client_secret' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->paypal_sandbox_status;
    $information['client_id'] = $request->paypal_client_id;
    $information['client_secret'] = $request->paypal_client_secret;

    $paypalInfo = OnlineGateway::where('keyword', 'paypal')->first();

    $paypalInfo->update([
      'information' => json_encode($information),
      'status' => $request->paypal_status
    ]);

    $request->session()->flash('success', 'PayPal\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateInstamojoInfo(Request $request)
  {
    $rules = [
      'instamojo_status' => 'required',
      'instamojo_sandbox_status' => 'required',
      'instamojo_key' => 'required',
      'instamojo_token' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->instamojo_sandbox_status;
    $information['key'] = $request->instamojo_key;
    $information['token'] = $request->instamojo_token;

    $instamojoInfo = OnlineGateway::where('keyword', 'instamojo')->first();

    $instamojoInfo->update([
      'information' => json_encode($information),
      'status' => $request->instamojo_status
    ]);

    $request->session()->flash('success', 'Instamojo\'s information updated successfully!');

    return redirect()->back();
  }

  public function updatePaystackInfo(Request $request)
  {
    $rules = [
      'paystack_status' => 'required',
      'paystack_key' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->paystack_key;

    $paystackInfo = OnlineGateway::where('keyword', 'paystack')->first();

    $paystackInfo->update([
      'information' => json_encode($information),
      'status' => $request->paystack_status
    ]);

    $request->session()->flash('success', 'Paystack\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateFlutterwaveInfo(Request $request)
  {
    $rules = [
      'flutterwave_status' => 'required',
      'flutterwave_public_key' => 'required',
      'flutterwave_secret_key' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['public_key'] = $request->flutterwave_public_key;
    $information['secret_key'] = $request->flutterwave_secret_key;

    $flutterwaveInfo = OnlineGateway::where('keyword', 'flutterwave')->first();

    $flutterwaveInfo->update([
      'information' => json_encode($information),
      'status' => $request->flutterwave_status
    ]);

    $array = [
      'FLW_PUBLIC_KEY' => $request->flutterwave_public_key,
      'FLW_SECRET_KEY' => $request->flutterwave_secret_key
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Flutterwave\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateRazorpayInfo(Request $request)
  {
    $rules = [
      'razorpay_status' => 'required',
      'razorpay_key' => 'required',
      'razorpay_secret' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->razorpay_key;
    $information['secret'] = $request->razorpay_secret;

    $razorpayInfo = OnlineGateway::where('keyword', 'razorpay')->first();

    $razorpayInfo->update([
      'information' => json_encode($information),
      'status' => $request->razorpay_status
    ]);

    $request->session()->flash('success', 'Razorpay\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateMercadoPagoInfo(Request $request)
  {
    $rules = [
      'mercadopago_status' => 'required',
      'mercadopago_sandbox_status' => 'required',
      'mercadopago_token' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['sandbox_status'] = $request->mercadopago_sandbox_status;
    $information['token'] = $request->mercadopago_token;

    $mercadopagoInfo = OnlineGateway::where('keyword', 'mercadopago')->first();

    $mercadopagoInfo->update([
      'information' => json_encode($information),
      'status' => $request->mercadopago_status
    ]);

    $request->session()->flash('success', 'MercadoPago\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateMollieInfo(Request $request)
  {
    $rules = [
      'mollie_status' => 'required',
      'mollie_key' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->mollie_key;

    $mollieInfo = OnlineGateway::where('keyword', 'mollie')->first();

    $mollieInfo->update([
      'information' => json_encode($information),
      'status' => $request->mollie_status
    ]);

    $array = ['MOLLIE_KEY' => $request->mollie_key];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Mollie\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateStripeInfo(Request $request)
  {
    $rules = [
      'stripe_status' => 'required',
      'stripe_key' => 'required',
      'stripe_secret' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['key'] = $request->stripe_key;
    $information['secret'] = $request->stripe_secret;

    $stripeInfo = OnlineGateway::where('keyword', 'stripe')->first();

    $stripeInfo->update([
      'information' => json_encode($information),
      'status' => $request->stripe_status
    ]);

    $array = [
      'STRIPE_KEY' => $request->stripe_key,
      'STRIPE_SECRET' => $request->stripe_secret
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Stripe\'s information updated successfully!');

    return redirect()->back();
  }

  public function updatePaytmInfo(Request $request)
  {
    $rules = [
      'paytm_status' => 'required',
      'paytm_environment' => 'required',
      'paytm_merchant_key' => 'required',
      'paytm_merchant_mid' => 'required',
      'paytm_merchant_website' => 'required',
      'paytm_industry_type' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $information['environment'] = $request->paytm_environment;
    $information['merchant_key'] = $request->paytm_merchant_key;
    $information['merchant_mid'] = $request->paytm_merchant_mid;
    $information['merchant_website'] = $request->paytm_merchant_website;
    $information['industry_type'] = $request->paytm_industry_type;

    $paytmInfo = OnlineGateway::where('keyword', 'paytm')->first();

    $paytmInfo->update([
      'information' => json_encode($information),
      'status' => $request->paytm_status
    ]);

    $array = [
      'PAYTM_ENVIRONMENT' => $request->paytm_environment,
      'PAYTM_MERCHANT_KEY' => $request->paytm_merchant_key,
      'PAYTM_MERCHANT_ID' => $request->paytm_merchant_mid,
      'PAYTM_MERCHANT_WEBSITE' => $request->paytm_merchant_website,
      'PAYTM_INDUSTRY_TYPE' => $request->paytm_industry_type
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Paytm\'s information updated successfully!');

    return redirect()->back();
  }

  public function updateAnetInfo(Request $request)
  {
    $anet = OnlineGateway::find(21);
    $anet->status = $request->status;

    $information = [];
    $information['login_id'] = $request->login_id;
    $information['transaction_key'] = $request->transaction_key;
    $information['public_key'] = $request->public_key;
    $information['sandbox_check'] = $request->sandbox_check;
    $information['text'] = "Pay via your Authorize.net account.";

    $anet->information = json_encode($information);

    $anet->save();

    $request->session()->flash('success', "Authorize.net informations updated successfully!");

    return back();
  }
}
