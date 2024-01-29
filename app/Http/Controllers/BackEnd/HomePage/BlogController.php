<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\BlogSection;
use App\Models\Language;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->blogSection()->first();

    $information['langs'] = Language::all();

    return view('backend.home-page.blog-section', $information);
  }

  public function update(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    BlogSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'subtitle' => $request->subtitle,
        'title' => $request->title
      ]
    );

    $request->session()->flash('success', 'Blog section updated successfully!');

    return redirect()->back();
  }
}
