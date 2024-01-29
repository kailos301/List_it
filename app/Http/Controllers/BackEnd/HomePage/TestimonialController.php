<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Testimonial\StoreRequest;
use App\Http\Requests\Testimonial\UpdateRequest;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Testimony\Testimonial;
use App\Models\HomePage\Testimony\TestimonialSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestimonialController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->testimonialSection()->first();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version', 'testimonial_section_image')->first();

    $information['testimonials'] = $language->testimonial()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.home-page.testimonial-section.index', $information);
  }

  public function updateSectionBackground(Request $request)
  {

    $request->validate([
      'testimonial_section_image' => 'required'
    ]);

    if ($request->hasFile('testimonial_section_image')) {
      $imgName = UploadFile::store(public_path('assets/img/'), $request->file('testimonial_section_image'));
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['testimonial_section_image' =>  $imgName]
    );
    $request->session()->flash('success', 'Testimonial section Background Image updated successfully!');

    return redirect()->back();
  }
  public function updateSectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    $testimonialSection = TestimonialSection::where('language_id', $language->id)->first();

    if ($testimonialSection) {
      $testimonialSection->update(
        [
          'subtitle' => $request->subtitle,
          'title' => $request->title,
          'clients' => $request->clients,
        ]
      );
    } else {
      TestimonialSection::create(
        [
          'language_id' => $language->id,
          'subtitle' => $request->subtitle,
          'title' => $request->title,
          'clients' => $request->clients,
        ]
      );
    }



    $request->session()->flash('success', 'Testimonial section updated successfully!');

    return redirect()->back();
  }

  public function storeTestimonial(StoreRequest $request)
  {
    // store image in storage
    $imgName = UploadFile::store(public_path('assets/img/clients/'), $request->file('image'));
    Testimonial::query()->create($request->except('language', 'image') + [
      'image' => $request->hasFile('image') ? $imgName : NULL
    ]);

    $request->session()->flash('success', 'New testimonial added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function updateTestimonial(UpdateRequest $request)
  {
    $testimonial = Testimonial::query()->find($request->id);

    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $testimonial->image;
      $imgName = UploadFile::update(public_path('assets/img/clients/'), $newImage, $oldImage);
    }

    $testimonial->update($request->except('language', 'image') + [
      'image' => $request->hasFile('image') ? $imgName : $testimonial->image
    ]);

    $request->session()->flash('success', 'Testimonial updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroyTestimonial($id)
  {
    $testimonial = Testimonial::query()->find($id);

    @unlink(public_path('assets/img/clients/') . $testimonial->image);

    $testimonial->delete();

    return redirect()->back()->with('success', 'Testimonial deleted successfully!');
  }

  public function bulkDestroyTestimonial(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $testimonial = Testimonial::query()->find($id);

      @unlink(public_path('assets/img/clients/') . $testimonial->image);

      $testimonial->delete();
    }

    $request->session()->flash('success', 'Testimonials deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
