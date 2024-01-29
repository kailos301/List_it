<?php

namespace App\Http\Controllers\FrontEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\Shop\PurchaseProcessController;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalController extends Controller
{
  private $api_context;

  public function __construct()
  {
    $data = OnlineGateway::whereKeyword('paypal')->first();
    $paypalData = json_decode($data->information, true);

    $paypal_conf = Config::get('paypal');
    $paypal_conf['client_id'] = $paypalData['client_id'];
    $paypal_conf['secret'] = $paypalData['client_secret'];
    $paypal_conf['settings']['mode'] = $paypalData['sandbox_status'] == 1 ? 'sandbox' : 'live';

    $this->api_context = new ApiContext(
      new OAuthTokenCredential(
        $paypal_conf['client_id'],
        $paypal_conf['secret']
      )
    );

    $this->api_context->setConfig($paypal_conf['settings']);
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

    // changing the currency before redirect to PayPal
    if ($currencyInfo->base_currency_text !== 'USD') {
      $rate = floatval($currencyInfo->base_currency_rate);
      $convertedTotal = $calculatedData['grandTotal'] / $rate;
    }

    $paypalTotal = $currencyInfo->base_currency_text === 'USD' ? $calculatedData['grandTotal'] : $convertedTotal;

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
      'paymentMethod' => 'PayPal',
      'gatewayType' => 'online',
      'paymentStatus' => 'completed',
      'orderStatus' => 'pending'
    );

    $title = 'Purchase Product';
    $notifyURL = route('shop.purchase_product.paypal.notify');
    $cancelURL = route('shop.purchase_product.cancel');

    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $item_1 = new Item();
    $item_1->setName($title)
      /** item name **/
      ->setCurrency('USD')
      ->setQuantity(1)
      ->setPrice($paypalTotal);
    /** unit price **/
    $item_list = new ItemList();
    $item_list->setItems(array($item_1));
    $amount = new Amount();
    $amount->setCurrency('USD')
      ->setTotal($paypalTotal);
    $transaction = new Transaction();
    $transaction->setAmount($amount)
      ->setItemList($item_list)
      ->setDescription($title . ' via PayPal');
    $redirect_urls = new RedirectUrls();
    $redirect_urls->setReturnUrl($notifyURL)
      /** Specify return URL **/
      ->setCancelUrl($cancelURL);
    $payment = new Payment();
    $payment->setIntent('Sale')
      ->setPayer($payer)
      ->setRedirectUrls($redirect_urls)
      ->setTransactions(array($transaction));

    try {
      $payment->create($this->api_context);
    } catch (\PayPal\Exception\PPConnectionException $ex) {
      return redirect($cancelURL)->with('error', $ex->getMessage());
    }

    foreach ($payment->getLinks() as $link) {
      if ($link->getRel() == 'approval_url') {
        $redirectURL = $link->getHref();
        break;
      }
    }

    // put some data in session before redirect to paypal url
    $request->session()->put('paymentFor', $paymentFor);
    $request->session()->put('arrData', $arrData);
    $request->session()->put('paymentId', $payment->getId());

    if (isset($redirectURL)) {
      /** redirect to paypal **/
      return Redirect::away($redirectURL);
    }
  }

  public function notify(Request $request)
  {
    // get the information from session
    $paymentPurpose = $request->session()->get('paymentFor');

    $productList = $request->session()->get('productCart');

    $arrData = $request->session()->get('arrData');
    $paymentId = $request->session()->get('paymentId');

    $urlInfo = $request->all();

    if (empty($urlInfo['token']) || empty($urlInfo['PayerID'])) {
      if ($paymentPurpose == 'product purchase') {
        return redirect()->route('shop.purchase_product.cancel');
      }
    }

    /** Execute The Payment **/
    $payment = Payment::get($paymentId, $this->api_context);
    $execution = new PaymentExecution();
    $execution->setPayerId($urlInfo['PayerID']);
    $result = $payment->execute($execution, $this->api_context);

    if ($result->getState() == 'approved') {
      // remove this session datas
      $request->session()->forget('paymentFor');
      $request->session()->forget('arrData');
      $request->session()->forget('paymentId');

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
      $request->session()->forget('paymentId');

      if ($paymentPurpose == 'product purchase') {
        // remove session data
        $request->session()->forget('productCart');
        $request->session()->forget('discount');

        return redirect()->route('shop.purchase_product.cancel');
      }
    }
  }
}
