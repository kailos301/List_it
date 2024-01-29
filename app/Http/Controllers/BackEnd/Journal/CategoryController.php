<?php

namespace App\Http\Controllers\BackEnd\Journal;

use App\Http\Controllers\Controller;
use App\Models\Journal\BlogCategory;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['categories'] = $language->blogCategory()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.journal.category.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'name' => 'required|unique:blog_categories|max:255',
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
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

    BlogCategory::create($request->except('slug') + [
      'slug' => createSlug($request->name)
    ]);

    $request->session()->flash('success', 'New blog category added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'name' => [
        'required',
        'max:255',
        Rule::unique('blog_categories', 'name')->ignore($request->id, 'id')
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $category = BlogCategory::find($request->id);

    $category->update($request->except('slug') + [
      'slug' => createSlug($request->name)
    ]);

    $request->session()->flash('success', 'Blog category updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $category = BlogCategory::find($id);
    $blogInformations = $category->blogInfo()->get();

    if (count($blogInformations) > 0) {
      return redirect()->back()->with('warning', 'First delete all the blog of this category!');
    } else {
      $category->delete();

      return redirect()->back()->with('success', 'Blog category deleted successfully!');
    }
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    $errorOccured = false;

    foreach ($ids as $id) {
      $category = BlogCategory::find($id);
      $blogInformations = $category->blogInfo()->get();

      if (count($blogInformations) > 0) {
        $errorOccured = true;
        break;
      } else {
        $category->delete();
      }
    }

    if ($errorOccured == true) {
      $request->session()->flash('warning', 'First delete all the blog of these categories!');
    } else {
      $request->session()->flash('success', 'Blog categories deleted successfully!');
    }

    return Response::json(['status' => 'success'], 200);
  }
}
