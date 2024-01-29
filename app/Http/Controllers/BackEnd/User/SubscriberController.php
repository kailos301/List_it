<?php

namespace App\Http\Controllers\BackEnd\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
  public function index(Request $request)
  {
    $searchKey = null;

    if ($request->filled('email')) {
      $searchKey = $request['email'];
    }

    $subscribers = Subscriber::query()->when($searchKey, function ($query, $searchKey) {
      return $query->where('email_id', 'like', '%' . $searchKey . '%');
    })
      ->orderByDesc('id')
      ->paginate(10);

    return view('backend.end-user.subscriber.index', compact('subscribers'));
  }

  public function destroy($id)
  {
    try {
      Subscriber::query()->findOrFail($id)->delete();

      return redirect()->back()->with('success', 'Email address deleted successfully!');
    } catch (ModelNotFoundException $e) {
      return redirect()->back()->with('warning', 'Sorry, email not found!');
    }
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      Subscriber::query()->find($id)->delete();
    }

    $request->session()->flash('success', 'Email addresses deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function writeEmail()
  {
    return view('backend.end-user.subscriber.write-email');
  }

  public function prepareEmail(Request $request)
  {
    $subscribers = Subscriber::all();

    if (count($subscribers) == 0) {
      $request->session()->flash('warning', 'No subscriber found!');

      return redirect()->back();
    }

    $rules = [
      'subject' => 'required',
      'message' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $mailData['subject'] = $request['subject'];
    $mailData['body'] = $request['message'];

    foreach ($subscribers as $subscriber) {
      $mailData['recipient'] = $subscriber->email_id;

      BasicMailer::sendMail($mailData);
    }

    $request->session()->flash('success', 'Mail has been sent to all the subscribers.');

    return redirect()->back();
  }
}
