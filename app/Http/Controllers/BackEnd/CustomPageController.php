<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Page\StoreRequest;
use App\Http\Requests\Page\UpdateRequest;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Language;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class CustomPageController extends Controller
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

    // then, get the custom pages of that language from db
    $information['pages'] = Page::query()->join('page_contents', 'pages.id', '=', 'page_contents.page_id')
      ->where('page_contents.language_id', '=', $language->id)
      ->orderByDesc('pages.id')
      ->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.custom-page.index', $information);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.custom-page.create', $information);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    $page = new Page();

    $page->status = $request->status;
    $page->save();

    $languages = Language::all();

    foreach ($languages as $language) {
      $pageContent = new PageContent();
      $pageContent->language_id = $language->id;
      $pageContent->page_id = $page->id;
      $pageContent->title = $request[$language->code . '_title'];
      $pageContent->slug = createSlug($request[$language->code . '_title']);
      $pageContent->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $pageContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $pageContent->meta_description = $request[$language->code . '_meta_description'];
      $pageContent->save();
    }

    $request->session()->flash('success', 'New page added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $information['page'] = Page::query()->findOrFail($id);

    // get all the languages from db
    $information['languages'] = Language::all();

    return view('backend.custom-page.edit', $information);
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
    $page = Page::query()->findOrFail($id);

    $page->update([
      'status' => $request->status
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $pageContent = PageContent::query()->where('page_id', '=', $id)
        ->where('language_id', '=', $language->id)
        ->first();

      $pageContent->update([
        'title' => $request[$language->code . '_title'],
        'slug' => createSlug($request[$language->code . '_title']),
        'content' => Purifier::clean($request[$language->code . '_content'], 'youtube'),
        'meta_keywords' => $request[$language->code . '_meta_keywords'],
        'meta_description' => $request[$language->code . '_meta_description']
      ]);
    }

    $request->session()->flash('success', 'Page updated successfully!');

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
    $page = Page::query()->findOrFail($id);

    $pageContents = $page->content()->get();

    foreach ($pageContents as $pageContent) {
      $pageContent->delete();
    }

    $page->delete();

    return redirect()->back()->with('success', 'Page deleted successfully!');
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
      $page = Page::query()->findOrFail($id);

      $pageContents = $page->content()->get();

      foreach ($pageContents as $pageContent) {
        $pageContent->delete();
      }

      $page->delete();
    }

    $request->session()->flash('success', 'Pages deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }
}
