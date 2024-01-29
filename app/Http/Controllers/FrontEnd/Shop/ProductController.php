<?php

namespace App\Http\Controllers\FrontEnd\Shop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Shop\Coupon;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ProductReview;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_products', 'meta_description_products')->first();

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['categories'] = $language->productCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $product_name = $category = $rating = $min = $max = $sort = null;

    if ($request->filled('product_name')) {
      $product_name = $request['product_name'];
    }
    if ($request->filled('category')) {
      $category = $request['category'];
    }
    if ($request->filled('rating')) {
      $rating = floatval($request['rating']);
    }
    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }
    if ($request->filled('sort')) {
      $sort = $request['sort'];
    }



    $queryResult['products'] = Product::join('product_contents', 'products.id', '=', 'product_contents.product_id')
      ->where('products.status', '=', 'show')
      ->where('product_contents.language_id', '=', $language->id)
      ->when($product_name, function ($query, $product_name) {
        return $query->where('product_contents.title', 'like', '%' . $product_name . '%');
      })
      ->when($category, function ($query, $category) {
        $categoryId = ProductCategory::query()->where('slug', '=', $category)->pluck('id')->first();

        return $query->where('product_contents.product_category_id', '=', $categoryId);
      })
      ->when($rating, function ($query, $rating) {
        return $query->where('products.average_rating', '>=', $rating);
      })
      ->when(($min && $max), function ($query) use ($min, $max) {
        return $query->where('products.current_price', '>=', $min)->where('products.current_price', '<=', $max);
      })
      ->select('products.id', 'products.featured_image', 'products.average_rating', 'product_contents.title', 'product_contents.slug', 'products.current_price', 'products.previous_price')
      ->when($sort, function ($query, $sort) {
        if ($sort == 'newest') {
          return $query->orderBy('products.created_at', 'desc');
        } else if ($sort == 'old') {
          return $query->orderBy('products.created_at', 'asc');
        } else if ($sort == 'high-to-low') {
          return $query->orderBy('products.current_price', 'desc');
        } else if ($sort == 'low-to-high') {
          return $query->orderBy('products.current_price', 'asc');
        }
      }, function ($query) {
        return $query->orderByDesc('products.created_at');
      })
      ->paginate(9);

    $queryResult['total_products'] = Product::join('product_contents', 'products.id', '=', 'product_contents.product_id')
      ->where('products.status', '=', 'show')
      ->where('product_contents.language_id', '=', $language->id)
      ->when($product_name, function ($query, $product_name) {
        return $query->where('product_contents.title', 'like', '%' . $product_name . '%');
      })
      ->when($category, function ($query, $category) {
        $categoryId = ProductCategory::query()->where('slug', '=', $category)->pluck('id')->first();

        return $query->where('product_contents.product_category_id', '=', $categoryId);
      })
      ->when($rating, function ($query, $rating) {
        return $query->where('products.average_rating', '>=', $rating);
      })
      ->when(($min && $max), function ($query) use ($min, $max) {
        return $query->where('products.current_price', '>=', $min)->where('products.current_price', '<=', $max);
      })
      ->select('products.id', 'products.featured_image', 'products.average_rating', 'product_contents.title', 'product_contents.slug', 'products.current_price', 'products.previous_price')
      ->when($sort, function ($query, $sort) {
        if ($sort == 'newest') {
          return $query->orderBy('products.created_at', 'desc');
        } else if ($sort == 'old') {
          return $query->orderBy('products.created_at', 'asc');
        } else if ($sort == 'high-to-low') {
          return $query->orderBy('products.current_price', 'desc');
        } else if ($sort == 'low-to-high') {
          return $query->orderBy('products.current_price', 'asc');
        }
      }, function ($query) {
        return $query->orderByDesc('products.created_at');
      })->get()->count();

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $queryResult['min'] = Product::where('status', 'show')->min('current_price');
    $queryResult['max'] = Product::where('status', 'show')->max('current_price');

    return view('frontend.shop.products', $queryResult);
  }

  public function show($slug, Request $request)
  {
    $request->session()->put('redirectTo', url()->current());

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $details = Product::join('product_contents', 'products.id', '=', 'product_contents.product_id')
      ->join('product_categories', 'product_categories.id', '=', 'product_contents.product_category_id')
      ->where('product_contents.language_id', '=', $language->id)
      ->where('product_contents.slug', '=', $slug)
      ->select('products.id', 'products.slider_images', 'product_contents.title', 'products.average_rating', 'products.current_price', 'products.previous_price', 'product_contents.summary', 'product_contents.content', 'product_categories.name as categoryName', 'product_categories.slug as categorySlug', 'product_categories.id as categoryId', 'product_contents.meta_keywords', 'product_contents.meta_description')
      ->firstOrFail();
    $queryResult['details'] = $details;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $productId = ProductContent::query()->where('language_id', '=', $language->id)
      ->where('slug', '=', $slug)
      ->pluck('product_id')
      ->first();

    $reviews = ProductReview::query()->where('product_id', '=', $productId)->orderByDesc('id')->get();

    $reviews->map(function ($review) {
      $review['user'] = $review->userInfo()->first();
    });

    $queryResult['reviews'] = $reviews;

    $queryResult['related_products'] = Product::join('product_contents', 'products.id', '=', 'product_contents.product_id')
      ->where('products.status', '=', 'show')
      ->where('product_contents.language_id', '=', $language->id)
      ->where('product_contents.product_category_id', '=', $details->categoryId)
      ->where('products.id', '!=', $details->id)
      ->select('products.id', 'products.featured_image', 'products.average_rating', 'product_contents.title', 'product_contents.slug', 'products.current_price', 'products.previous_price')
      ->orderByDesc('products.created_at')
      ->get();
    return view('frontend.shop.product-details', $queryResult);
  }

  public function addToCart(Request $request, $id, $quantity)
  {
    // get product cart data from session
    if ($request->session()->has('productCart')) {
      $productCart = $request->session()->get('productCart');
    } else {
      $productCart = [];
    }

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    // get the information of selected product
    $productInfo = ProductContent::with('product')->where('language_id', $language->id)
      ->where('product_id', $id)
      ->first();

    // check whether the selected product has enough stock or not
    if ($productInfo->product->product_type == 'physical') {
      if (empty($productCart) || !isset($productCart[$id])) {
        // product cart is empty or, product is not exist in that cart
        if ($productInfo->product->stock < $quantity) {
          return response()->json(['error' => 'Sorry, product out of stock!']);
        }
      } else {
        // product cart is not empty and, product is exist in that cart
        if ($productInfo->product->stock < $productCart[$id]['quantity'] + $quantity) {
          return response()->json(['error' => 'Sorry, product out of stock!']);
        }
      }
    }

    // add product in the cart if product has enough stock
    if (empty($productCart)) {
      // first case, cart is totally empty
      $productCart = [
        $id => [
          'type' => $productInfo->product->product_type,
          'image' => $productInfo->product->featured_image,
          'title' => $productInfo->title,
          'slug' => $productInfo->slug,
          'quantity' => $quantity,
          'price' => $productInfo->product->current_price * $quantity
        ]
      ];
    } else if (!empty($productCart) && !isset($productCart[$id])) {
      // second case, cart is not empty but product is not exist in this cart
      $productCart[$id] = [
        'type' => $productInfo->product->product_type,
        'image' => $productInfo->product->featured_image,
        'title' => $productInfo->title,
        'slug' => $productInfo->slug,
        'quantity' => $quantity,
        'price' => $productInfo->product->current_price * $quantity
      ];
    } else if (!empty($productCart) && isset($productCart[$id])) {
      // third case, cart is not empty and product is also exist in this cart
      $productCart[$id]['quantity'] += intval($quantity);
      $productCart[$id]['price'] += $productInfo->product->current_price * intval($quantity);
    }

    $request->session()->put('productCart', $productCart);

    $numOfProducts = count($productCart);

    return response()->json([
      'success' => 'Product added into the cart.',
      'numOfProducts' => $numOfProducts
    ]);
  }

  public function cart(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    // get product cart data from session
    if ($request->session()->has('productCart')) {
      $queryResult['productCart'] = $request->session()->get('productCart');

      $queryResult['currencyInfo'] = $this->getCurrencyInfo();
    } else {
      $queryResult['productCart'] = [];
    }

    return view('frontend.shop.cart', $queryResult);
  }

  public function updateCart(Request $request)
  {
    // get the products from session
    if ($request->session()->has('productCart')) {
      $productList = $request->session()->get('productCart');
    }

    // get data from ajax request
    $data = $request->all();

    for ($i = 0; $i < sizeof($data['id']); $i++) {
      $productId = intval($data['id'][$i]);

      // get the information of product
      $productInfo = Product::where('id', $productId)->first();

      $newQuantity = intval($data['quantity'][$i]);
      $unitPrice = floatval($data['unitPrice'][$i]);

      // first, check whether the selected product has enough stock or not
      if (($productInfo->product_type == 'physical') && ($productInfo->stock < $newQuantity)) {
        return Response::json([
          'error' => 'Sorry, product out of stock!'
        ], 400);
      }

      // second, if product is not out of stock then update product in cart
      if ($newQuantity < 1) {
        unset($productList[$productId]);
      } else {
        $productList[$productId]['type'] = $productList[$productId]['type'];
        $productList[$productId]['image'] = $productList[$productId]['image'];
        $productList[$productId]['title'] = $productList[$productId]['title'];
        $productList[$productId]['slug'] = $productList[$productId]['slug'];
        $productList[$productId]['quantity'] = $newQuantity;
        $productList[$productId]['price'] = $unitPrice * $newQuantity;
      }
    }

    // return $productList;

    $request->session()->put('productCart', $productList);

    $total_products = count($productList);

    return Response::json([
      'success' => 'Cart updated successfully!',
      'total_products' => $total_products
    ], 200);
  }

  public function removeProduct(Request $request, $id)
  {
    // get the products from session
    if ($request->session()->has('productCart')) {
      $productList = $request->session()->get('productCart');
    }

    // check whether the product (id) exist in productList array
    if (array_key_exists($id, $productList)) {
      // delete that product info from productList array
      unset($productList[$id]);

      // get cart total of remaining products
      $cartTotal = 0;

      $totalQuantity = 0;
      foreach ($productList as $key => $product) {
        $cartTotal += $product['price'];
        $totalQuantity += $product['quantity'];
      }

      $request->session()->put('productCart', $productList);

      return response()->json([
        'success' => 'Product removed successfully!',
        'numOfProducts' => $totalQuantity,
        'cartTotal' => $cartTotal
      ]);
    } else {
      return response()->json(['error' => 'Something went wrong!']);
    }
  }

  public function checkout(Request $request)
  {
    if ($request->session()->has('productCart')) {
      $cart = $request->session()->get('productCart');

      if (empty($cart)) {
        Session::flash('message', 'Your cart is empty!');

        return redirect()->route('shop.products');
      } else {
        $productItems = $cart;
        $queryResult['productItems'] = $productItems;
      }
    } else {
      Session::flash('message', 'Your cart is empty!');

      return redirect()->route('shop.products');
    }

    // check for 'user authentication' and check for 'checkout not as a guest'
    if (Auth::guard('web')->check() == false && empty($request->input('checkout_as'))) {
      $request->session()->put('redirectTo', route('shop.checkout'));

      if ($this->hasSingleDigitalProduct($request) == true) {
        return redirect()->route('user.login', ['redirect_path' => 'checkout', 'digital_item' => 'yes']);
      } else {
        return redirect()->route('user.login', ['redirect_path' => 'checkout', 'digital_item' => 'no']);
      }
    }

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['language'] = $language;

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['authUser'] = Auth::guard('web')->check() == true ? Auth::guard('web')->user() : null;

    // check for existence of digital item in cart
    $queryResult['status'] = $this->isAllDigitalProduct($request);

    $queryResult['charges'] = $language->shippingCharge()->orderBy('serial_number', 'asc')->get();

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $cart_total = 0;
    foreach ($productItems as $item) {
      $cart_total += $item['price'];
    }
    Session::put('cart_total', $cart_total);

    $tax = Basic::select('product_tax_amount')->first();
    $queryResult['tax'] = $tax;
    Session::put('product_tax_amount', $tax->product_tax_amount);

    $queryResult['onlineGateways'] = OnlineGateway::where('status', 1)->get();

    $queryResult['offline_gateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();
    $stripe = OnlineGateway::where('keyword', 'stripe')->first();
    $stripe_info = json_decode($stripe->information, true);
    $queryResult['stripe_key'] = $stripe_info['key'];

    return view('frontend.shop.checkout', $queryResult);
  }

  public function isAllDigitalProduct($request)
  {
    if ($request->session()->has('productCart')) {
      $cartItems = $request->session()->get('productCart');

      foreach ($cartItems as $item) {
        if ($item['type'] == 'physical') {
          $isAllDigitalProductStaus = false;
          break;
        } else {
          $isAllDigitalProductStaus = true;
        }
      }

      return $isAllDigitalProductStaus;
    }
  }

  public function hasSingleDigitalProduct($request)
  {
    if ($request->session()->has('productCart')) {
      $cartItems = $request->session()->get('productCart');

      foreach ($cartItems as $item) {
        if ($item['type'] == 'digital') {
          $hasSingleDigitalProductStatus = true;
          break;
        } else {
          $hasSingleDigitalProductStatus = false;
        }
      }

      return $hasSingleDigitalProductStatus;
    }
  }

  public function applyCoupon(Request $request)
  {
    try {
      $coupon = Coupon::where('code', $request->coupon)->firstOrFail();

      $startDate = Carbon::parse($coupon->start_date);
      $endDate = Carbon::parse($coupon->end_date);
      $todayDate = Carbon::now();

      // first, check coupon is valid or not
      if ($todayDate->between($startDate, $endDate) == false) {
        return response()->json(['error' => 'Sorry, coupon has expired!']);
      }

      if ($request->session()->has('productCart')) {
        $cartItems = $request->session()->get('productCart');

        $total = 0;

        foreach ($cartItems as $item) {
          $total += $item['price'];
        }

        // second, check for minimum cart total
        if (!empty($coupon->minimum_spend)) {
          $minimumSpend = floatval($coupon->minimum_spend);

          if ($total < $minimumSpend) {
            $currencyInfo = $this->getCurrencyInfo();
            $position = $currencyInfo->base_currency_symbol_position;
            $symbol = $currencyInfo->base_currency_symbol;

            $spendData = ($position == 'left' ? $symbol : '') . $coupon->minimum_spend . ($position == 'right' ? $symbol : '');

            return response()->json([
              'error' => 'You have to purchase atleast ' . $spendData . ' of product.'
            ]);
          }
        }

        // check for existence of digital item & send it through response
        $status = $this->isAllDigitalProduct($request);

        if ($coupon->type == 'fixed') {
          $request->session()->put('discount', $coupon->value);

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'amount' => $coupon->value,
            'digitalProductStatus' => $status
          ]);
        } else {
          $couponAmount = $total * ($coupon->value / 100);

          $request->session()->put('discount', $couponAmount);

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'amount' => $couponAmount,
            'digitalProductStatus' => $status
          ]);
        }
      } else {
        return response()->json(['error' => 'Sorry, something went wrong!']);
      }
    } catch (ModelNotFoundException $e) {
      return response()->json(['error' => 'Coupon is not valid!']);
    }
  }

  public function checkAttachment($id)
  {
    try {
      $offlineGateway = OfflineGateway::findOrFail($id);

      return response()->json([
        'id' => $offlineGateway->id,
        'status' => $offlineGateway->has_attachment
      ]);
    } catch (ModelNotFoundException $e) {
      return response()->json(['errorMsg' => 'Sorry, something went wrong!']);
    }
  }
  public function put_shipping_method($id)
  {
    Session::put('shipping_id', $id);
  }

  public function storeReview(Request $request, $id)
  {
    $rule = ['rating' => 'required'];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()
        ->with('error', 'The rating field is required for product review.')
        ->withInput();
    }

    $productPurchased = false;

    // get the authenticate user
    $user = Auth::guard('web')->user();

    // then, get the purchases of that user
    $orders = $user->productOrder()->where('payment_status', 'completed')->get();

    if (count($orders) > 0) {
      foreach ($orders as $order) {
        // then, get the purchased items of each order
        $items = $order->item()->get();

        foreach ($items as $item) {
          // check whether selected product has bought or not 
          if ($item->product_id == $id) {
            $productPurchased = true;
            break 2;
          }
        }
      }

      if ($productPurchased == true) {
        ProductReview::updateOrCreate(
          ['user_id' => $user->id, 'product_id' => $id],
          ['comment' => $request->comment, 'rating' => $request->rating]
        );

        // now, get the average rating of this product
        $reviews = ProductReview::where('product_id', $id)->get();

        $totalRating = 0;

        foreach ($reviews as $review) {
          $totalRating += $review->rating;
        }

        $numOfReview = count($reviews);

        $averageRating = $totalRating / $numOfReview;

        // finally, store the average rating of this product
        Product::find($id)->update(['average_rating' => $averageRating]);

        Session::flash('success', 'Your review submitted successfully.');
      } else {
        Session::flash('error', 'You have not bought this product yet!');
      }
    } else {
      Session::flash('error', 'You have not bought anything yet!');
    }

    return redirect()->back();
  }
}
