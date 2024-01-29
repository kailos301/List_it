<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\CounterSection;
use App\Models\HomePage\CounterInformation;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CounterController extends Controller
{
  public function index(Request $request)
  {
    $information['info'] = DB::table('basic_settings')->select('counter_section_image')->first();

    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    $information['counters'] = $language->counterInfo()->orderByDesc('id')->get();

    $information['counterInfo'] = CounterSection::where('language_id', $language->id)->first();

    $information['langs'] = Language::all();

    return view('backend.home-page.counter-section.index', $information);
  }

  public function updateImage(Request $request)
  {
    $data = DB::table('basic_settings')->select('counter_section_image')->first();

    $rules = [];

    if (empty($data->counter_section_image)) {
      $rules['counter_section_image'] = 'required';
    }
    if ($request->hasFile('counter_section_image')) {
      $rules['counter_section_image'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('counter_section_image')) {
      $newImage = $request->file('counter_section_image');
      $oldImage = $data->counter_section_image;

      $imgName = UploadFile::update(public_path('assets/img/'), $newImage, $oldImage);

      // finally, store the image into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['counter_section_image' => $imgName]
      );

      $request->session()->flash('success', 'Image updated successfully!');
    }

    return redirect()->back();
  }

  public function updateInfo(Request $request)
  {
    $request->validate([
      'lang_code' => 'required',
      'title' => 'required',
      'subtitle' => 'required',
    ]);

    $language = Language::query()->where('code', '=', $request->lang_code)->first();

    $info = CounterSection::where('language_id', $language->id)->first();
    if (empty($info)) {
      $info = new CounterSection();
      $info->language_id = $language->id;
    }
    $info->title = $request->title;
    $info->subtitle = $request->subtitle;
    $info->save();
    Session::flash('success', 'Image updated successfully!');
    return back();
  }


  public function storeCounter(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'icon' => 'required',
      'amount' => 'required|numeric',
      'title' => 'required'
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

    CounterInformation::query()->create($request->except('language'));

    $request->session()->flash('success', 'Information stored successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateCounter(Request $request)
  {
    $rules = [
      'amount' => 'required|numeric',
      'title' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $counterInfo = CounterInformation::query()->find($request->id);

    $counterInfo->update($request->except('language'));

    $request->session()->flash('success', 'Information updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroyCounter($id)
  {
    $counterInfo = CounterInformation::query()->find($id);

    $counterInfo->delete();

    return redirect()->back()->with('success', 'Information deleted successfully!');
  }

  public function bulkDestroyCounter(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $counterInfo = CounterInformation::query()->find($id);

      $counterInfo->delete();
    }

    $request->session()->flash('success', 'Selected Informations deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
