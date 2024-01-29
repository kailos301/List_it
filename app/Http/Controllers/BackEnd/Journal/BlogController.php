<?php

namespace App\Http\Controllers\BackEnd\Journal;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use App\Models\Journal\Blog;
use App\Models\Journal\BlogInformation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();

    $information['blogs'] = Blog::query()->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
      ->where('blog_informations.language_id', '=', $language->id)
      ->select('blogs.id', 'blogs.serial_number', 'blogs.created_at', 'blog_informations.title', 'blog_categories.name AS categoryName')
      ->orderByDesc('blogs.id')
      ->get();

    $information['langs'] = Language::all();

    return view('backend.journal.blog.index', $information);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // get all the languages from db
    $languages = Language::all();

    // get all the categories of each language from db
    $languages->map(function ($language) {
      $language['categories'] = $language->blogCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;

    return view('backend.journal.blog.create', $information);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    // store image in storage
    $imgName = UploadFile::store(public_path('assets/img/blogs/'), $request->file('image'));

    // store data in db
    $blog = Blog::create($request->except('image') + [
      'image' => $imgName
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $blogInformation = new BlogInformation();
      $blogInformation->language_id = $language->id;
      $blogInformation->blog_category_id = $request[$language->code . '_category_id'];
      $blogInformation->blog_id = $blog->id;
      $blogInformation->title = $request[$language->code . '_title'];
      $blogInformation->slug = createSlug($request[$language->code . '_title']);
      $blogInformation->author = $request[$language->code . '_author'];
      $blogInformation->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $blogInformation->meta_keywords = $request[$language->code . '_meta_keywords'];
      $blogInformation->meta_description = $request[$language->code . '_meta_description'];
      $blogInformation->save();
    }

    $request->session()->flash('success', 'New blog added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $blog = Blog::findOrFail($id);
    $information['blog'] = $blog;

    // get all the languages from db
    $languages = Language::all();

    $languages->map(function ($language) use ($blog) {
      // get blog information of each language from db
      $language['blogData'] = $language->blogInformation()->where('blog_id', $blog->id)->first();

      // get all the categories of each language from db
      $language['categories'] = $language->blogCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;

    return view('backend.journal.blog.edit', $information);
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
    $blog = Blog::find($id);

    // store new image in storage
    if ($request->hasFile('image')) {
      $imgName = UploadFile::update(public_path('assets/img/blogs/'), $request->file('image'), $blog->image);
    }

    // update data in db
    $blog->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imgName : $blog->image
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $blogInformation = BlogInformation::where('blog_id', $id)->where('language_id', $language->id)->first();

      $blogInformation->update([
        'blog_category_id' => $request[$language->code . '_category_id'],
        'title' => $request[$language->code . '_title'],
        'slug' => createSlug($request[$language->code . '_title']),
        'author' => $request[$language->code . '_author'],
        'content' => Purifier::clean($request[$language->code . '_content'], 'youtube'),
        'meta_keywords' => $request[$language->code . '_meta_keywords'],
        'meta_description' => $request[$language->code . '_meta_description']
      ]);
    }

    $request->session()->flash('success', 'Blog updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $blog = Blog::find($id);

    // delete the image
    @unlink(public_path('assets/img/blogs/') . $blog->image);

    $blogInformations = $blog->information()->get();

    foreach ($blogInformations as $blogInformation) {
      $blogInformation->delete();
    }

    $blog->delete();

    return redirect()->back()->with('success', 'Blog deleted successfully!');
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
      $blog = Blog::find($id);

      // delete the image
      @unlink(public_path('assets/img/blogs/') . $blog->image);

      $blogInformations = $blog->information()->get();

      foreach ($blogInformations as $blogInformation) {
        $blogInformation->delete();
      }

      $blog->delete();
    }

    $request->session()->flash('success', 'Blogs deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
