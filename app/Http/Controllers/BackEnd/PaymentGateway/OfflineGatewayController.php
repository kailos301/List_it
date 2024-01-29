<?php

namespace App\Http\Controllers\BackEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway\OfflineGateway;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class OfflineGatewayController extends Controller
{
  public function index()
  {
    $offlineGateways = OfflineGateway::orderBy('id', 'desc')->get();

    return view('backend.payment-gateways.offline-gateways.index', compact('offlineGateways'));
  }

  public function store(Request $request)
  {
    $rules = [
      'name' => 'required',
      'has_attachment' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'has_attachment.required' => 'The attachment field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    OfflineGateway::create($request->except('instructions') + [
      'instructions' => Purifier::clean($request->instructions, 'youtube')
    ]);

    $request->session()->flash('success', 'New offline payment gateway added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateStatus(Request $request, $id)
  {
    try {
      $offlineGateway = OfflineGateway::findOrFail($id);

      if ($request->status == 1) {
        $offlineGateway->update(['status' => 1]);
      } else {
        $offlineGateway->update(['status' => 0]);
      }

      $request->session()->flash('success', 'Status updated successfully!');
    } catch (ModelNotFoundException $e) {
      $request->session()->flash('warning', 'No record found in database!');
    }

    return redirect()->back();
  }

  public function update(Request $request)
  {
    $rules = [
      'name' => 'required',
      'has_attachment' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'has_attachment.required' => 'The attachment field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    try {
      $gateway = OfflineGateway::findOrFail($request->id);

      $gateway->update($request->except('instructions') + [
        'instructions' => Purifier::clean($request->instructions, 'youtube')
      ]);

      $request->session()->flash('success', 'Offline payment gateway updated successfully!');
    } catch (ModelNotFoundException $e) {
      $request->session()->flash('warning', 'No record found in database!');
    }

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    try {
      OfflineGateway::findOrFail($id)->delete();

      return redirect()->back()->with('success', 'Offline payment gateway deleted successfully!');
    } catch (ModelNotFoundException $e) {
      return redirect()->back()->with('warning', 'No record found in database!');
    }
  }
}
