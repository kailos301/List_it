<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\CookieAlert;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CookieAlertController extends Controller
{
  public function cookieAlert(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the cookie alert info of that language from db
    $information['data'] = $language->cookieAlertInfo()->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.basic-settings.cookie-alert', $information);
  }

  public function updateCookieAlert(Request $request)
  {
    $rules = [
      'cookie_alert_status' => 'required',
      'cookie_alert_btn_text' => 'required',
      'cookie_alert_text' => 'required'
    ];

    $message = [
      'cookie_alert_btn_text.required' => 'The cookie alert button text field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->first();

    // then, get the cookie alert info of that language from db
    $data = $language->cookieAlertInfo()->first();

    if (empty($data)) {
      CookieAlert::query()->create($request->except(['language_id', 'cookie_alert_text']) + [
        'language_id' => $language->id,
        'cookie_alert_text' => clean($request->cookie_alert_text)
      ]);
    } else {
      $data->update($request->all());
    }

    $request->session()->flash('success', 'Cookie alert info updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
