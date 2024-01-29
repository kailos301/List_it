<?php

namespace App\Http\Controllers\BackEnd\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\Guest;
use App\Notifications\PushNotification;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
  public function settings()
  {
    $data = DB::table('basic_settings')->select('notification_image')->first();

    return view('backend.end-user.push-notification.settings', ['data' => $data]);
  }

  public function updateSettings(Request $request)
  {
    $data = DB::table('basic_settings')->select('notification_image')->first();

    $rules = [];

    if (is_null($data->notification_image)) {
      $rules['notification_image'] = 'required';
    }
    if ($request->hasFile('notification_image')) {
      $rules['notification_image'] = new ImageMimeTypeRule();
    }

    if (env('VAPID_PUBLIC_KEY') == null) {
      $rules['vapid_public_key'] = 'required';
    }
    if (env('VAPID_PRIVATE_KEY') == null) {
      $rules['vapid_private_key'] = 'required';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('notification_image')) {
      $newImage = $request->file('notification_image');
      $oldImage = $data->notification_image;
      $imageName = UploadFile::update(public_path('assets/img/'), $newImage, $oldImage);

      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['notification_image' => $imageName]
      );
    }

    if ($request->filled('vapid_public_key') && $request->filled('vapid_private_key')) {
      $array = [
        'VAPID_PUBLIC_KEY' => $request->vapid_public_key,
        'VAPID_PRIVATE_KEY' => $request->vapid_private_key
      ];

      setEnvironmentValue($array);
      Artisan::call('config:clear');
    }

    $request->session()->flash('success', 'Settings updated successfully!');

    return redirect()->back();
  }


  public function writeNotification()
  {
    return view('backend.end-user.push-notification.write-notification');
  }

  public function sendNotification(Request $request)
  {
    $rules = [
      'title' => 'required',
      'button_name' => 'required',
      'button_url' => 'required|url'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $title = $request['title'];
    $message = $request['message'];
    $buttonName = $request['button_name'];
    $buttonURL = $request['button_url'];

    // send push notification
    $guests = Guest::all();

    Notification::send($guests, new PushNotification($title, $message, $buttonName, $buttonURL));

    $request->session()->flash('success', 'Notification has been sent.');

    return redirect()->back();
  }
}
