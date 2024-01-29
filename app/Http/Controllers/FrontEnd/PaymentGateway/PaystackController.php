<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaystackController extends Controller
{
  private $api_key;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('paystack')->first();
    $paystackData = json_decode($data->information, true);

    $this->api_key = $paystackData['key'];
  }

  public function index(Request $request, $paymentFor)
  {
    // get the products from session
    if ($request->session()->has('productCart')) {
      $productList = $request->session()->get('productCart');
    } else {
      Session::flash('error', 'Something went wrong!');

      return redirect()->route('shop.products');
    }

    $purchaseProcess = new PurchaseProcessController();

    // do calculation
    $calculatedData = $purchaseProcess->calculation($request, $productList);

    $currencyInfo = $this->getCurrencyInfo();

    // checking whether the currency is set to 'NGN' or not
    if ($currencyInfo->base_currency_text !== 'NGN') {
      return redirect()->back()->with('error', 'Invalid currency for paystack payment.')->withInput();
    }

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
      'paymentMethod' => 'Paystack',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
      'orderStatus' => 'pending'
    );

    $notifyURL = route('shop.purchase_product.paystack.notify');

    $customerEmail = $request['billing_email'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL            => 'https://api.paystack.co/transaction/initialize',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST  => 'POST',
      CURLOPT_POSTFIELDS     => json_encode([
        'amount'       => intval($calculatedData['grandTotal']) * 100,
        'email'        => $customerEmail,
        'callback_url' => $notifyURL
      ]),
      CURLOPT_HTTPHEADER     => [
        'authorization: Bearer ' . $this->api_key,
        'content-type: application/json',
        'cache-control: no-cache'
      ]
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $transaction = json_decode($response, true);

    // put some data in session before redirect to paystack url
    $request->session()->put('paymentFor', $paymentFor);
    $request->session()->put('arrData', $arrData);

    if ($transaction['status'] == true) {
      return redirect($transaction['data']['authorization_url']);
    } else {
      return redirect()->back()->with('error', 'Error: ' . $transaction['message'])->withInput();
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentPurpose = $request->session()->get('paymentFor');

    if ($paymentPurpose == 'product purchase') {
      $productList = $request->session()->get('productCart');
    }

    $arrData = $request->session()->get('arrData');

    $urlInfo = $request->all();

    if ($urlInfo['trxref'] === $urlInfo['reference']) {
      // remove this session datas
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');

      $purchaseProcess = new PurchaseProcessController();

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
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');

      // remove session data
      $request->session()->forget('productCart');
      $request->session()->forget('discount');

      return redirect()->route('shop.purchase_product.cancel');
    }
  }
}
