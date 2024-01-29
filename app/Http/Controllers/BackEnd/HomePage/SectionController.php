<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\HomePage\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
  public function index()
  {
    $sectionInfo = Section::query()->first();

    $themeVersion = Basic::query()->pluck('theme_version')->first();

    return view('backend.home-page.section-customization', compact('sectionInfo', 'themeVersion'));
  }

  public function update(Request $request)
  {
    $sectionInfo = Section::query()->first();

    $sectionInfo->update($request->all());

    $request->session()->flash('success', 'Section status updated successfully!');

    return redirect()->back();
  }
}
