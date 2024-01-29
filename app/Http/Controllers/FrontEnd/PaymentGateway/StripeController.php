<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\Shop\Product;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class StripeController extends Controller
{
  public function index(Request $request)
  {
    // card validation start
    $rules = [
      'stripeToken' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }
    // card validation end

    // get the products from session
    if ($request->session()->has('productCart')) {
      $productList = $request->session()->get('productCart');
    } else {
      Session::flash('error', 'Something went wrong!');

      return redirect()->route('shop.checkout')->withInput();
    }

    $purchaseProcess = new PurchaseProcessController();

    // do calculation
    $calculatedData = $purchaseProcess->calculation($request, $productList);

    $currencyInfo = $this->getCurrencyInfo();

    // changing the currency before redirect to Stripe
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = round(($calculatedData['grandTotal'] / $rate), 2);
    }

    $stripeTotal = $currencyInfo->base_currency_text === 'USD' ? $calculatedData['grandTotal'] : $convertedTotal;

    $arrData = array(
      'billing_name' => $request['billing_name'],
      'billing_email' => $request['billing_email'],
      'billing_phone' => $request['billing_phone'],
      'billing_city' => $request['billing_city'],
      'billing_state' => $request['billing_state'],
      'billing_country' => $request['billing_country'],
      'billing_address' => $request['billing_address'],

      'shipping_name' => $request->checkbox == 1 ? $request['shipping_name'] : $request['billing_name'],

      'shipping_email' => $request->checkbox == 1 ? $request['shipping_email'] : $request['billing_email'],

      'shipping_phone' => $request->checkbox == 1 ? $request['shipping_phone'] : $request['billing_phone'],

      'shipping_city' => $request->checkbox == 1 ? $request['shipping_city'] : $request['billing_city'],

      'shipping_state' => $request->checkbox == 1 ? $request['shipping_state'] : $request['billing_state'],

      'shipping_country' => $request->checkbox == 1 ? $request['shipping_country'] : $request['billing_country'],

      'shipping_address' => $request->checkbox == 1 ? $request['shipping_address'] : $request['billing_address'],

      'total' => $calculatedData['total'],
      'discount' => $calculatedData['discount'],
      'productShippingChargeId' => $request->exists('shipping_method') ? $request['shipping_method'] : null,
      'shippingCharge' => $calculatedData['shippingCharge'],
      'tax' => $calculatedData['tax'],
      'grandTotal' => $calculatedData['grandTotal'],
      'currencyText' => $currencyInfo->base_currency_text,
      'currencyTextPosition' => $currencyInfo->base_currency_text_position,
      'currencySymbol' => $currencyInfo->base_currency_symbol,
      'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
      'paymentMethod' => 'Stripe',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
      'orderStatus' => 'pending'
    );

    try {
      // initialize stripe
      $stripe = new Stripe();
      $stripe = Stripe::make(Config::get('services.stripe.secret'));

      try {

        // generate charge
        $charge = $stripe->charges()->create([
          'source' => $request->stripeToken,
          'currency' => 'USD',
          'amount'   => $stripeTotal
        ]);

        if ($charge['status'] == 'succeeded') {
          // store product order information in database
          $orderInfo = $purchaseProcess->storeData($productList, $arrData);

          // then subtract each product quantity from respective product stock
          foreach ($productList as $key => $item) {
            $product = Product::query()->find($key);

            if ($product->product_type == 'physical') {
              $stock = $product->stock - intval($item['quantity']);

              $product->update(['stock' => $stock]);
            }
          }

          // generate an invoice in pdf format
          $invoice = $purchaseProcess->generateInvoice($orderInfo, $productList);

          // then, update the invoice field info in database
          $orderInfo->update(['invoice' => $invoice]);

          // send a mail to the customer with the invoice
          $purchaseProcess->prepareMail($orderInfo);

          // remove all session data
          $request->session()->forget('productCart');
          $request->session()->forget('discount');

          return redirect()->route('shop.purchase_product.complete');
        } else {
          // remove session data
          $request->session()->forget('productCart');
          $request->session()->forget('discount');

          return redirect()->route('shop.purchase_product.cancel');
        }
      } catch (CardErrorException $e) {
        Session::flash('error', $e->getMessage());

        // remove session data
        $request->session()->forget('productCart');
        $request->session()->forget('discount');

        return redirect()->route('shop.purchase_product.cancel');
      }
    } catch (UnauthorizedException $e) {
      Session::flash('error', $e->getMessage());

      // remove session data
      $request->session()->forget('productCart');
      $request->session()->forget('discount');

      return redirect()->route('shop.purchase_product.cancel');
    }
  }
}
