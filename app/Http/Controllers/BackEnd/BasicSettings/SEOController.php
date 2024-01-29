<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\SEO;
use App\Models\Language;
use Illuminate\Http\Request;

class SEOController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the seo info of that language from db
    $information['data'] = $language->seoInfo()->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.basic-settings.seo', $information);
  }

  public function update(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->first();

    // then, get the seo info of that language from db
    $seoInfo = $language->seoInfo()->first();

    if (empty($seoInfo)) {
      SEO::query()->create($request->except('language_id') + [
        'language_id' => $language->id
      ]);
    } else {
      $seoInfo->update($request->all());
    }

    $request->session()->flash('success', 'SEO Informations updated successfully!');

    return redirect()->back();
  }
}
