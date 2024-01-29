<?php

namespace App\Http\Controllers\BackEnd\Shop;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Shop\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ShippingChargeController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the shipping charge of that language from db
    $information['charges'] = $language->shippingCharge()->orderByDesc('id')->get();

    // get all the languages from db
    $information['langs'] = Language::all();

    // also, get the currency information from db
    $information['currencyInfo'] = $this->getCurrencyInfo();

    return view('backend.shop.shipping-charge.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'title' => 'required',
      'short_text' => 'required',
      'shipping_charge' => 'required',
      'serial_number' => 'required'
    ];

    $message = [
      'language_id.required' => 'The language field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    ShippingCharge::query()->create($request->all());

    $request->session()->flash('success', 'New shipping charge added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'title' => 'required',
      'short_text' => 'required',
      'shipping_charge' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $shippingCharge = ShippingCharge::query()->find($request->id);

    $shippingCharge->update($request->all());

    $request->session()->flash('success', 'Shipping charge updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $shippingCharge = ShippingCharge::query()->find($id);

    $shippingCharge->delete();

    return redirect()->back()->with('success', 'Shipping charge deleted successfully!');
  }
}
