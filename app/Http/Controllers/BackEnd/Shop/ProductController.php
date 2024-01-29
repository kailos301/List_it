<?php

namespace App\Http\Controllers\BackEnd\Shop;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Shop\ProductStoreRequest;
use App\Http\Requests\Shop\ProductUpdateRequest;
use App\Models\Language;
use App\Models\Shop\Product;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ProductOrder;
use App\Models\Shop\ProductPurchaseItem;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();

    $information['langs'] = Language::all();

    $information['products'] = Product::query()->join('product_contents', 'products.id', '=', 'product_contents.product_id')
      ->join('product_categories', 'product_categories.id', '=', 'product_contents.product_category_id')
      ->where('product_contents.language_id', '=', $language->id)
      ->select('products.id', 'products.product_type', 'products.featured_image', 'products.current_price', 'product_contents.title', 'product_categories.name as categoryName', 'products.is_featured')
      ->orderByDesc('products.id')
      ->get();

    $information['currencyInfo'] = $this->getCurrencyInfo();

    $information['themeInfo'] = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.shop.product.index', $information);
  }

  /**
   * Show the product type page to select one of them.
   *
   * @return \Illuminate\Http\Response
   */
  public function productType()
  {
    $information['digitalProductCount'] = Product::where('product_type', 'digital')->count();

    $information['physicalProductCount'] = Product::where('product_type', 'physical')->count();

    return view('backend.shop.product.product-type', $information);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create($type)
  {
    $information['productType'] = $type;

    $information['currencyInfo'] = $this->getCurrencyInfo();

    $languages = Language::all();

    $languages->map(function ($language) {
      $language['categories'] = $language->productCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;

    return view('backend.shop.product.create', $information);
  }

  /**
   * Store a new slider image in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function uploadImage(Request $request)
  {
    $rules = [
      'slider_image' => new ImageMimeTypeRule()
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'error' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $imageName = UploadFile::store(public_path('assets/img/products/slider-images/'), $request->file('slider_image'));

    return Response::json(['uniqueName' => $imageName], 200);
  }

  /**
   * Remove a slider image from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function removeImage(Request $request)
  {
    if (empty($request['imageName'])) {
      return Response::json(['error' => 'The request has no file name.'], 400);
    } else {
      @unlink(public_path('assets/img/products/slider-images/') . $request['imageName']);

      return Response::json(['success' => 'The file has been deleted.'], 200);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ProductStoreRequest $request)
  {
    // store featured image in storage
    $featuredImgName = UploadFile::store(public_path('assets/img/products/featured-images/'), $request->file('featured_image'));

    // store product zip file in storage
    if ($request->hasFile('file')) {
      $fileName = UploadFile::store(public_path('assets/file/products/'), $request->file('file'));
    }

    // store data in db
    $product = Product::create($request->except('featured_image', 'slider_images', 'file') + [
      'featured_image' => $featuredImgName,
      'slider_images' => json_encode($request['slider_images']),
      'file' => $request->hasFile('file') ? $fileName : null
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $productContent = new ProductContent();
      $productContent->language_id = $language->id;
      $productContent->product_category_id = $request[$language->code . '_category_id'];
      $productContent->product_id = $product->id;
      $productContent->title = $request[$language->code . '_title'];
      $productContent->slug = createSlug($request[$language->code . '_title']);
      $productContent->summary = $request[$language->code . '_summary'];
      $productContent->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $productContent->meta_keywords = $request[$language->code . '_meta_keywords'];
      $productContent->meta_description = $request[$language->code . '_meta_description'];
      $productContent->save();
    }

    $request->session()->flash('success', 'New product added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Update the 'featured' status of a specified resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateFeaturedStatus(Request $request, $id)
  {
    $product = Product::find($id);

    if ($request['is_featured'] == 'yes') {
      $product->update([
        'is_featured' => 'yes'
      ]);

      $request->session()->flash('success', 'Product featured successfully!');
    } else {
      $product->update([
        'is_featured' => 'no'
      ]);

      $request->session()->flash('success', 'Product unfeatured successfully!');
    }

    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id, $type)
  {
    $information['productType'] = $type;

    $product = Product::findOrFail($id);
    $information['product'] = $product;

    // get the currency information from db
    $information['currencyInfo'] = $this->getCurrencyInfo();

    // get all the languages from db
    $languages = Language::all();

    $languages->map(function ($language) use ($product) {
      // get product information of each language from db
      $language['productData'] = $language->productContent()->where('product_id', $product->id)->first();

      // get all the categories of each language from db
      $language['categories'] = $language->productCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;

    return view('backend.shop.product.edit', $information);
  }

  /**
   * Remove 'stored' slider image form storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function detachImage(Request $request)
  {
    $id = $request['id'];
    $key = $request['key'];

    $product = Product::find($id);

    if (empty($product)) {
      return Response::json(['message' => 'Product not found!'], 400);
    } else {
      $sliderImages = json_decode($product->slider_images);

      if (count($sliderImages) == 1) {
        return Response::json(['message' => 'Sorry, the last image cannot be delete.'], 400);
      } else {
        $image = $sliderImages[$key];

        @unlink(public_path('assets/img/products/slider-images/') . $image);

        array_splice($sliderImages, $key, 1);

        $product->update([
          'slider_images' => json_encode($sliderImages)
        ]);

        return Response::json(['message' => 'Slider image removed successfully!'], 200);
      }
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(ProductUpdateRequest $request, $id)
  {
    $product = Product::find($id);

    // store featured image in storage
    if ($request->hasFile('featured_image')) {
      $newImage = $request->file('featured_image');
      $oldImage = $product->featured_image;
      $featuredImgName = UploadFile::update(public_path('assets/img/products/featured-images/'), $newImage, $oldImage);
    }

    // merge slider images with existing images if request has new slider image
    if ($request->filled('slider_images')) {
      $prevImages = json_decode($product->slider_images);
      $newImages = $request['slider_images'];
      $imgArr = array_merge($prevImages, $newImages);
    }

    // store product zip file in storage
    if ($request->hasFile('file')) {
      $newFile = $request->file('file');
      $oldFile = $product->file;
      $fileName = UploadFile::update(public_path('assets/file/products/'), $newFile, $oldFile);
    }

    // if input type change from zip file to downloadable link, then delete the existing zip file from local storage.
    $productType = $request->product_type;

    if ($productType == 'digital' && $request->input_type == 'link' && !empty($product->file)) {
      @unlink(public_path('assets/file/products/') . $product->file);
    }

    // store data in db
    $product->update($request->except('featured_image', 'slider_images', 'file') + [
      'featured_image' => $request->hasFile('featured_image') ? $featuredImgName : $product->featured_image,
      'slider_images' => isset($imgArr) ? json_encode($imgArr) : $product->slider_images,
      'file' => $request->hasFile('file') ? $fileName : $product->file
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $productContent = ProductContent::where('product_id', $id)->where('language_id', $language->id)->first();

      $productContent->update([
        'product_category_id' => $request[$language->code . '_category_id'],
        'title' => $request[$language->code . '_title'],
        'slug' => createSlug($request[$language->code . '_title']),
        'summary' => $request[$language->code . '_summary'],
        'content' => Purifier::clean($request[$language->code . '_content'], 'youtube'),
        'meta_keywords' => $request[$language->code . '_meta_keywords'],
        'meta_description' => $request[$language->code . '_meta_description']
      ]);
    }

    $request->session()->flash('success', 'Product updated successfully!');

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
    $product = Product::find($id);

    // delete the featured image
    @unlink(public_path('assets/img/products/featured-images/') . $product->featured_image);

    // delete the slider images
    $sliderImages = json_decode($product->slider_images);

    foreach ($sliderImages as $sliderImage) {
      @unlink(public_path('assets/img/products/slider-images/') . $sliderImage);
    }

    // delete the product zip file
    @unlink(public_path('assets/file/products/') . $product->file);

    // delete contents of this product
    $productContents = $product->content()->get();

    foreach ($productContents as $productContent) {
      $productContent->delete();
    }

    // delete purchase item records of this product
    $purchaseInfos = $product->purchase()->get();

    if (count($purchaseInfos) > 0) {
      foreach ($purchaseInfos as $purchaseData) {
        $purchaseInfo = $purchaseData;
        $purchaseData->delete();

        // delete the order if, this order does not contain any other items
        $otherPurchaseItems = ProductPurchaseItem::query()->where('product_id', '<>', $product->id)
          ->where('product_order_id', '=', $purchaseInfo->product_order_id)
          ->get();

        if (count($otherPurchaseItems) == 0) {
          $order = ProductOrder::query()->find($purchaseInfo->product_order_id);

          // delete order receipt
          @unlink(public_path('assets/file/attachments/product/') . $order->receipt);

          // delete order invoice
          @unlink(public_path('assets/file/invoices/product/') . $order->invoice);

          $order->delete();
        }
      }
    }

    // delete all the reviews of this product
    $reviews = $product->review()->get();

    if (count($reviews) > 0) {
      foreach ($reviews as $review) {
        $review->delete();
      }
    }

    $product->delete();

    return redirect()->back()->with('success', 'Product deleted successfully!');
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
      $product = Product::find($id);

      // delete the featured image
      @unlink(public_path('assets/img/products/featured-images/') . $product->featured_image);

      // delete the slider images
      $sliderImages = json_decode($product->slider_images);

      foreach ($sliderImages as $sliderImage) {
        @unlink(public_path('assets/img/products/slider-images/') . $sliderImage);
      }

      // delete the product zip file
      @unlink(public_path('assets/file/products/') . $product->file);

      // delete contents of this product
      $productContents = $product->content()->get();

      foreach ($productContents as $productContent) {
        $productContent->delete();
      }

      // delete purchase item records of this product
      $purchaseInfos = $product->purchase()->get();

      if (count($purchaseInfos) > 0) {
        foreach ($purchaseInfos as $purchaseData) {
          $purchaseInfo = $purchaseData;
          $purchaseData->delete();

          // delete the order if, this order does not contain any other items
          $otherPurchaseItems = ProductPurchaseItem::query()->where('product_id', '<>', $product->id)
            ->where('product_order_id', '=', $purchaseInfo->product_order_id)
            ->get();

          if (count($otherPurchaseItems) == 0) {
            $order = ProductOrder::query()->find($purchaseInfo->product_order_id);

            // delete order receipt
            @unlink(public_path('assets/file/attachments/product/') . $order->receipt);

            // delete order invoice
            @unlink(public_path('assets/file/invoices/product/') . $order->invoice);

            $order->delete();
          }
        }
      }

      // delete all the reviews of this product
      $reviews = $product->review()->get();

      if (count($reviews) > 0) {
        foreach ($reviews as $review) {
          $review->delete();
        }
      }

      $product->delete();
    }

    $request->session()->flash('success', 'Products deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
