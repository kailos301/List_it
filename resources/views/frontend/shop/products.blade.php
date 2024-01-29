@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Products') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_products }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_products }}
  @endif
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('assets/front/css/shop.css') }}">
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->products_page_title : __('Products'),
  ])

  <!-- Shop-area start -->
  <div class="shop-area pt-100 pb-60">
    <div class="container">
      <div class="row gx-xl-5">

        @includeIf('frontend.shop.side-bar')

        <div class="col-lg-8 col-xl-9">
          <div class="product-sort-area" data-aos="fade-up">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <h4 class="mb-20">{{ $total_products }} {{ $total_products > 1 ? __('Products') : __('Product') }}
                  {{ __('Found') }}</h4>
              </div>
              <div class="col-4 d-lg-none">
                <button class="btn btn-sm btn-outline icon-end radius-sm mb-20" type="button" data-bs-toggle="offcanvas"
                  data-bs-target="#widgetOffcanvas" aria-controls="widgetOffcanvas">
                  {{ __('Filter') }} <i class="fal fa-filter"></i>
                </button>
              </div>
              <div class="col-8 col-lg-6">
                <ul class="product-sort-list list-unstyled mb-20">
                  <li class="item">
                    <div class="sort-item d-flex align-items-center">
                      <label class="me-2 font-sm">{{ __('Sort By') }}:</label>
                      <form action="{{ route('shop.products') }}" method="get" id="SortForm">
                        @if (!empty(request()->input('category')))
                          <input type="hidden" name="category" value="{{ request()->input('category') }}">
                        @endif

                        @if (!empty(request()->input('min')))
                          <input type="hidden" name="min" value="{{ request()->input('min') }}">
                        @endif
                        @if (!empty(request()->input('max')))
                          <input type="hidden" name="max" value="{{ request()->input('max') }}">
                        @endif
                        <select name="sort" class="nice-select right color-dark"
                          onchange="document.getElementById('SortForm').submit()">
                          <option {{ request()->input('newest') == 'default' ? 'selected' : '' }} value="newest">
                            {{ __('Date : Newest on top') }}
                          </option>
                          <option {{ request()->input('sort') == 'oldest' ? 'selected' : '' }} value="oldest">
                            {{ __('Date : Oldest on top') }}
                          </option>
                          <option {{ request()->input('sort') == 'high-to-low' ? 'selected' : '' }} value="high-to-low">
                            {{ __('Price : High to Low') }}</option>
                          <option {{ request()->input('sort') == 'low-to-high' ? 'selected' : '' }} value="low-to-high">
                            {{ __('Price : Low to High') }}</option>
                        </select>
                      </form>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row">
            @foreach ($products as $product)
              <div class="col-xl-4 col-sm-6" data-aos="fade-up">
                <div class="product-default shadow-none text-center mb-25">
                  <figure class="product-img mb-15">
                    <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}"
                      class="lazy-container ratio ratio-1-1">
                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}"
                        alt="{{$product->title}}">
                    </a>
                    <div class="product-overlay">
                      <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}" target="_self"
                        title="{{ __('View Details') }}" class="icon">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('shop.product.add_to_cart', ['id' => $product->id, 'quantity' => 1]) }}"
                        target="_self" title="{{ __('Add to Cart') }}" class="icon cart-btn add-to-cart-btn">
                        <i class="fas fa-shopping-cart"></i>
                      </a>
                    </div>
                  </figure>
                  <div class="product-details">
                    <div class="ratings justify-content-center mb-10">
                      <div class="rate">
                        <div class="rating-icon" style="width: {{ $product->average_rating * 20 . '%;' }}"></div>
                      </div>
                    </div>
                    <h5 class="product-title mb-2">
                      <a href="{{ route('shop.product_details', ['slug' => $product->slug]) }}">{{ strlen($product->title) > 50 ? mb_substr($product->title, 0, 50, 'UTF-8') . '...' : $product->title }}</a>
                    </h5>
                    <div class="product-price justify-content-center">
                      <h6 class="new-price">{{ symbolPrice($product->current_price) }}</h6>
                      @if (!empty($product->previous_price))
                        <span class="old-price font-sm">{{ symbolPrice($product->previous_price) }}</span>
                      @endif
                    </div>
                  </div>
                </div><!-- product-default -->
              </div>
            @endforeach

          </div>
          <div class="pagination mt-20 mb-40 justify-content-center" data-aos="fade-up">
            {{ $products->appends([
                    'keyword' => request()->input('keyword'),
                    'category' => request()->input('category'),
                    'rating' => request()->input('rating'),
                    'min' => request()->input('min'),
                    'max' => request()->input('max'),
                    'sort' => request()->input('sort'),
                ])->links() }}
          </div>

          @if (!empty(showAd(3)))
            <div class="text-center mb-40">
              {!! showAd(3) !!}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- Shop-area end -->
@endsection

@section('script')
  <script src="{{ asset('assets/js/shop.js') }}"></script>
@endsection
