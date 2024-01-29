<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Popup\StoreRequest;
use App\Http\Requests\Popup\UpdateRequest;
use App\Models\Language;
use App\Models\Popup;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PopupController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['popups'] = $language->announcementPopup()->orderByDesc('id')->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.popup.index', $information);
  }

  /**
   * Show the popup type page to select one of them.
   *
   * @return \Illuminate\Http\Response
   */
  public function popupType()
  {
    return view('backend.popup.popup-type');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($type)
  {
    $information['popupType'] = $type;

    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.popup.create', $information);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    $imageName = UploadFile::store(public_path('assets/img/popups/'), $request->file('image'));

    Popup::query()->create($request->except('image', 'end_date', 'end_time') + [
      'image' => $imageName,
      'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
      'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null
    ]);

    $request->session()->flash('success', 'New popup added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Update the status of specified resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateStatus(Request $request, $id)
  {
    $popup = Popup::query()->find($id);

    if ($request->status == 1) {
      $popup->update(['status' => 1]);

      $request->session()->flash('success', 'Popup activated successfully!');
    } else {
      $popup->update(['status' => 0]);

      $request->session()->flash('success', 'Popup deactivated successfully!');
    }

    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $popup = Popup::query()->findOrFail($id);

    return view('backend.popup.edit', compact('popup'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request, $id)
  {
    $popup = Popup::query()->find($id);

    if ($request->hasFile('image')) {
      $imageName = UploadFile::update(public_path('assets/img/popups/'), $request->file('image'), $popup->image);
    }

    $popup->update($request->except('image', 'end_date', 'end_time') + [
      'image' => $request->hasFile('image') ? $imageName : $popup->image,
      'end_date' => $request->has('end_date') ? Carbon::parse($request['end_date']) : null,
      'end_time' => $request->has('end_time') ? date('h:i', strtotime($request['end_time'])) : null
    ]);

    $request->session()->flash('success', 'Popup updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $popup = Popup::query()->find($id);

    @unlink(public_path('assets/img/popups/') . $popup->image);

    $popup->delete();

    return redirect()->back()->with('success', 'Popup deleted successfully!');
  }

  /**
   * Remove the selected or all resources from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $popup = Popup::query()->find($id);

      @unlink(public_path('assets/img/popups/') . $popup->image);

      $popup->delete();
    }

    $request->session()->flash('success', 'Popups deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
