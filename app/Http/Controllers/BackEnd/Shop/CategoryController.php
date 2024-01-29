<?php

namespace App\Http\Controllers\BackEnd\Shop;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Shop\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    // first, get the language info from db
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    // then, get the product categories of that language from db
    $information['categories'] = $language->productCategory()->orderByDesc('id')->get();

    // also, get all the languages from db
    $information['langs'] = Language::all();

    return view('backend.shop.category.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'name' => 'required|unique:product_categories|max:255',
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

    ProductCategory::create($request->except('slug') + [
      'slug' => createSlug($request->name)
    ]);

    $request->session()->flash('success', 'New product category added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'name' => [
        'required',
        'max:255',
        Rule::unique('product_categories', 'name')->ignore($request->id, 'id')
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

    $category = ProductCategory::find($request->id);

    $category->update($request->except('slug') + [
      'slug' => createSlug($request->name)
    ]);

    $request->session()->flash('success', 'Product category updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $category = ProductCategory::find($id);
    $productContents = $category->productContent()->get();

    if (count($productContents) > 0) {
      return redirect()->back()->with('warning', 'First delete all the products of this category!');
    } else {
      $category->delete();

      return redirect()->back()->with('success', 'Category deleted successfully!');
    }
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    $errorOccured = false;

    foreach ($ids as $id) {
      $category = ProductCategory::find($id);
      $productContents = $category->productContent()->get();

      if (count($productContents) > 0) {
        $errorOccured = true;
        break;
      } else {
        $category->delete();
      }
    }

    if ($errorOccured == true) {
      $request->session()->flash('warning', 'First delete all the product of these categories!');
    } else {
      $request->session()->flash('success', 'Product categories deleted successfully!');
    }

    return Response::json(['status' => 'success'], 200);
  }
}
