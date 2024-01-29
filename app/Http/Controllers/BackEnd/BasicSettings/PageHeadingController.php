<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageHeadingRequest;
use App\Models\BasicSettings\PageHeading;
use App\Models\Language;
use Illuminate\Http\Request;

class PageHeadingController extends Controller
{
  public function pageHeadings(Request $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the page headings info of that language from db
    $information['data'] = $language->pageName()->first();

    // get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.basic-settings.page-headings', $information);
  }

  public function updatePageHeadings(PageHeadingRequest $request)
  {
    // first, get the language info from db
    $language = Language::query()->where('code', '=', $request->language)->first();

    // then, get the page heading info of that language from db
    $heading = $language->pageName()->first();

    if (empty($heading)) {
      PageHeading::query()->create($request->except('language_id') + [
        'language_id' => $language->id
      ]);
    } else {
      $heading->update($request->all());
    }

    $request->session()->flash('success', 'Page headings updated successfully!');

    return redirect()->back();
  }
}
