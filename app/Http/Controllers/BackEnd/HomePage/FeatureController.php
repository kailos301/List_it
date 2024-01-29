<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Prominence\Feature;
use App\Models\Language;
use App\Models\Prominence\FeatureSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class FeatureController extends Controller
{
  public function sectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->featureSection()->first();

    $information['langs'] = Language::all();

    return view('backend.home-page.featured-section.index', $information);
  }

  public function updateSectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();
    $featured_section = FeatureSection::where('language_id', $language->id)->first();
    $in = $request->all();
    $in['language_id'] = $language->id;
    if ($featured_section) {
      $featured_section->update($in);
    } else {
      FeatureSection::create($in);
    }

    $request->session()->flash('success', 'Feature section updated successfully!');

    return redirect()->back();
  }
}
