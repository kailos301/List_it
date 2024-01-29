<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Advertisement\StoreRequest;
use App\Http\Requests\Advertisement\UpdateRequest;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
  public function advertiseSettings()
  {
    $data = DB::table('basic_settings')->select('google_adsense_publisher_id')->first();

    return view('backend.advertisement.settings', ['data' => $data]);
  }

  public function updateAdvertiseSettings(Request $request)
  {
    $rule = [
      'google_adsense_publisher_id' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['google_adsense_publisher_id' => $request->google_adsense_publisher_id]
    );

    $request->session()->flash('success', 'Advertise settings updated successfully!');

    return redirect()->back();
  }


  public function index()
  {
    $ads = Advertisement::orderBy('id', 'desc')->get();

    return view('backend.advertisement.index', compact('ads'));
  }

  public function store(StoreRequest $request)
  {
    if ($request->hasFile('image')) {
      $imageName = UploadFile::store(public_path('assets/img/advertisements/'), $request->file('image'));
    }

    Advertisement::create($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : null
    ]);

    $request->session()->flash('success', 'New advertisement added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(UpdateRequest $request)
  {
    $ad = Advertisement::find($request->id);

    if ($request->hasFile('image')) {
      $imageName = UploadFile::update(public_path('assets/img/advertisements/'), $request->file('image'), $ad->image);
    }

    if ($request->ad_type == 'adsense') {
      // if ad type change to google adsense then delete the image from local storage.
      @unlink(public_path('assets/img/advertisements/') . $ad->image);
    }

    $ad->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $ad->image
    ]);

    $request->session()->flash('success', 'Advertisement updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $ad = Advertisement::find($id);

    if ($ad->ad_type == 'banner') {
      @unlink(public_path('assets/img/advertisements/') . $ad->image);
    }

    $ad->delete();

    return redirect()->back()->with('success', 'Advertisement deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $ad = Advertisement::find($id);

      if ($ad->ad_type == 'banner') {
        @unlink(public_path('assets/img/advertisements/') . $ad->image);
      }

      $ad->delete();
    }

    $request->session()->flash('success', 'Advertisements deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
