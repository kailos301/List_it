<?php

namespace App\Http\Controllers\BackEnd\HomePage\Hero;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Hero\Slider;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['sliders'] = $language->sliderInfo()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    $information['basic'] = Basic::where('uniqid', 12345)->select('hero_section_video_url')->first();

    return view('backend.home-page.hero-section.slider-version.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'background_image' => [
        'required',
        new ImageMimeTypeRule()
      ]
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

    // store image in storage
    $imgName = UploadFile::store(public_path('assets/img/hero/sliders/'), $request->file('background_image'));

    Slider::query()->create($request->except('background_image') + [
      'background_image' => $imgName
    ]);

    $request->session()->flash('success', 'New slider added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rule = [
      'background_image' => $request->hasFile('background_image') ? new ImageMimeTypeRule() : ''
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $slider = Slider::query()->find($request['id']);

    if ($request->hasFile('background_image')) {
      $newImage = $request->file('background_image');
      $oldImage = $slider->background_image;
      $imgName = UploadFile::update(public_path('assets/img/hero/sliders/'), $newImage, $oldImage);
    }

    $slider->update($request->except('background_image') + [
      'background_image' => $request->hasFile('background_image') ? $imgName : $slider->background_image
    ]);

    $request->session()->flash('success', 'Slider updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update_video_url(Request $request)
  {
    $information['basic'] = Basic::where('uniqid', 12345)->update([
      'hero_section_video_url' => $request->video_url
    ]);
    Session::flash('success', 'Update hero section button video url successfully..!');
    return back();
  }

  public function destroy($id)
  {
    $slider = Slider::query()->find($id);

    @unlink(public_path('assets/img/hero/sliders/') . $slider->background_image);

    $slider->delete();

    return redirect()->back()->with('success', 'Slider deleted successfully!');
  }
}
