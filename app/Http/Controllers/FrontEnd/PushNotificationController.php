<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      'endpoint' => 'required',
      'keys.p256dh' => 'required',
      'keys.auth' => 'required'
    ]);

    $endpoint = $request->endpoint;
    $key = $request->keys['p256dh'];
    $token = $request->keys['auth'];

    $guest = Guest::query()->firstOrCreate([
      'endpoint' => $endpoint
    ]);

    $guest->updatePushSubscription($endpoint, $key, $token);

    return response()->json(['status' => 'Success'], 200);
  }
}
