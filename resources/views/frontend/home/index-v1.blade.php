@php
  $version = $basicInfo->theme_version;
@endphp
@extends('frontend.layouts.layout-v' . $version)
@section('pageHeading')
  {{ __('Home') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_home }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_home }}
  @endif
@endsection
@section('content')
  <!-- Home-area start-->
  <section class="hero-banner hero-banner-1">
    <div class="container">
      <div class="swiper home-slider" id="home-slider-1">
        <div class="swiper-wrapper">
          @foreach ($sliderInfos as $slider)
            <div class="swiper-slide" data-aos="fade-up">
              <div class="banner-content" data-swiper-parallax="-300">
                <h1 class="title color-white mb-20">{{ $slider->title }}</h1>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <div class="row">
        <div class="col-xl-10">
          <div class="banner-filter-form mw-100 mt-40" data-aos="fade-up" data-aos-delay="100">
            <p class="text font-lg color-white">{{ __('I’m Looking for') }}</p>
            <ul class="nav nav-tabs border-0">
              <li class="nav-item">
                <button class="nav-link radius-0 active car_condition" data-id="" data-bs-toggle="tab"
                  data-bs-target="#all" type="button">{{ __('All') }}</button>
              </li>
              @foreach ($car_conditions as $condition)
                <li class="nav-item">
                  <button class="nav-link radius-0 car_condition" data-id="{{ $condition->id }}" data-bs-toggle="tab"
                    data-bs-target="#all" type="button">{{ $condition->name }}</button>
                </li>
              @endforeach
            </ul>
            <div class="tab-content form-wrapper p-30">
              <input type="hidden" name="getModel" id="getModel" value="{{ route('fronted.get-car.brand.model') }}">
              <div class="tab-pane fade active show" id="all">
                <form action="{{ route('frontend.cars') }}" method="GET">
                  <input type="hidden" name="condition" class="condition">
                  <div class="row align-items-center gx-xl-3">
                    <div class="col-lg-11">
                      <div class="row">
                        <div class="col-md col-sm-6">
                          <div class="form-group border-end">
                            <label for="location" class="font-sm">{{ __('Category') }}</label>
                            <select class="nice-select" id="category" name="category">
                              <option value="">{{ __('All') }}</option>
                              @foreach ($categories as $category)
                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md col-sm-6">
                          <div class="form-group border-end">
                            <label for="" class="font-sm">{{ __('Location') }}</label>
                            <input type="text" name="location" class="form-control"
                              placeholder="{{ __('Enter Location') }}">
                          </div>
                        </div>
                        <div class="col-md col-sm-6">
                          <div class="form-group border-end">
                            <label for="car_brand" class="font-sm">{{ __('Brand') }}</label>
                            <select class="nice-select" id="car_brand" name="brands">
                              <option value="">{{ __('All') }}</option>
                              @foreach ($brands as $brand)
                                <option value="{{ $brand->slug }}">{{ $brand->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md col-sm-6">
                          <div class="form-group border-end">
                            <label for="model" class="font-sm">{{ __('Model') }}</label>
                            <select class="nice-select" name="models" id="model">
                              <option value="">{{ __('All') }}</option>
                            </select>
                          </div>
                        </div>
                        <input type="hidden" value="{{ request()->filled('min') ? request()->input('min') : $min }}"
                          name="min" id="min">
                        <input value="{{ request()->filled('max') ? request()->input('max') : $max }}" type="hidden"
                          name="max" id="max">
                        <input class="form-control" type="hidden" value="{{ $min }}" id="o_min">
                        <input class="form-control" type="hidden" value="{{ $max }}" id="o_max">
                        <input type="hidden" id="currency_symbol" value="{{ $currencyInfo->base_currency_symbol }}">

                        <div class="col-md col-sm-12">
                          <div class="form-group">
                            <label for="priceSlider" class="font-sm mb-2">{{ __('Price') }}</label>
                            <div class="price-slider mb-2" data-range-slider="priceSlider"></div>
                            <span class="filter-price-range" data-range-value="priceSliderValue"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-1 text-md-end">
                      <button type="submit" class="btn btn-icon bg-primary color-white">
                        <i class="fal fa-search"></i>
                        <span class="d-inline-block d-lg-none">&nbsp;{{ __('Find Now') }}</span>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 align-self-end" data-aos="fade-up" data-aos-delay="100">
          <div class="slider-navigation position-static text-end mt-3 mt-xl-0">
            <button type="button" title="Slide prev" class="slider-btn radius-0" id="home-slider-1-prev">
              <i class="fal fa-angle-left"></i>
            </button>
            <button type="button" title="Slide next" class="slider-btn radius-0" id="home-slider-1-next">
              <i class="fal fa-angle-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="swiper home-img-slider" id="home-img-slider-1">
      <div class="swiper-wrapper">
        @foreach ($sliderInfos as $slider)
          <div class="swiper-slide">
            <div class="bg-img" data-bg-image="{{ asset('assets/img/hero/sliders/' . $slider->background_image) }}">
            </div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="swiper-pagination pagination-fraction" id="home-slider-1-pagination"></div>
  </section>
  <!-- Home-area end -->

  <!-- Category-area start -->
  @if ($secInfo->category_section_status == 1)
    <section class="category-area category-1 pt-100">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-50" data-aos="fade-up">
              <h2 class="title mb-0">{{ @$catgorySecInfo->title }}</h2>
            </div>
          </div>
          <div class="col-12">
            <div class="row">
              @foreach ($car_categories as $category)
                <div class="col-lg-3 col-sm-6 mb-30" data-aos="fade-up">
                  <a href="{{ route('frontend.cars', ['category' => $category->slug]) }}">
                    <div class="category-item">
                      <div class="d-flex flex-wrep justify-content-between mb-10">
                        <h4 class="category-title mb-10">
                          {{ $category->name }}
                        </h4>
                        <span
                          class="category-qty h4 mb-10">({{ $category->car_contents()->where('language_id', $currentLanguageInfo->id)->get()->count() }})</span>
                      </div>
                      <div class="category-img">
                        <img class="lazyload blur-up"
                          data-src="{{ asset('assets/admin/img/car-category/' . $category->image) }}"
                          alt="{{ $category->name }}" title="{{ $category->name }}">
                      </div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
            <div class="text-center text-center mt-20">
              <a href="{{ route('frontend.cars') }}"
                class="btn btn-lg btn-primary">{{ !empty($catgorySecInfo) ? $catgorySecInfo->button_text : __('View All') }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Category-area end -->

  <!-- counter area start -->
  @if ($secInfo->counter_section_status == 1)
    <section class="choose-area pt-100 pb-60">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7" data-aos="slide-{{ $currentLanguageInfo->direction == 1 ? 'left' : 'right' }}"
            data-aos-duration="1000">
            <div class="image mb-40">
              <img class="lazyload blur-up"
                @if (!empty($counterSectionImage)) data-src="{{ asset('assets/img/' . $counterSectionImage) }}" @endif
                alt="Image">
            </div>
          </div>
          <div class="col-lg-5" data-aos="fade-up">
            <div class="content-title mb-40">
              <h2 class="title mb-20">
                {{ @$counterSectionInfo->title }}
              </h2>
              <p>{{ @$counterSectionInfo->subtitle }}</p>
              <div class="info-list mt-30">
                <div class="row align-items-center pb-30">
                  @foreach ($counters as $counter)
                    <div class="col-sm-6">
                      <div class="card mt-30">
                        <div class="d-flex align-items-center">
                          <div class="card-icon"><i class="{{ $counter->icon }}"></i></div>
                          <div class="card-content">
                            <span class="h3 mb-1"><span class="counter">{{ $counter->amount }}</span>+</span>
                            <p class="card-text">{{ $counter->title }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="shape">
        <img class="lazyload blur-up" data-src="{{ asset('assets/front/images/dark-bg.png') }}" alt="Image">
      </div>
    </section>
  @endif
  <!-- counter area end -->

  <!-- car-area start -->
  @if ($secInfo->feature_section_status == 1)
    <section class="product-area pb-75">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <div class="section-title title-center mb-30" data-aos="fade-up">
              <h2 class="title mw-100 mb-30">
                {{ !empty($featuredSecInfo) ? $featuredSecInfo->title : __('Our Top Featured Vehicles') }}</h2>
              <div class="tabs-navigation mb-20">
                <ul class="nav nav-tabs" data-hover="fancyHover">
                  <li class="nav-item active">
                    <button class="nav-link hover-effect active btn-md" data-bs-toggle="tab" data-bs-target="#forAll"
                      type="button">{{ __('All Cars') }}</button>
                  </li>
                  @foreach ($categories as $category)
                    <li class="nav-item">
                      <button class="nav-link hover-effect btn-md" data-bs-toggle="tab"
                        data-bs-target="#tabName{{ $category->id }}" type="button">{{ $category->name }}</button>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="tab-content" data-aos="fade-up">
              <div class="tab-pane fade active show" id="forAll">
                <div class="row">
                  @php
                    $admin = App\Models\Admin::first();
                  @endphp
                  @foreach ($cars as $car)
                    @if ($car->vendor_id != 0)
                      @php
                        $vendor_featured_car = vendorTotalFeaturedCarHome($car->vendor_id);
                        $vendor_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($car->vendor_id);
                      @endphp
                      @if ($vendor_featured_car <= $vendor_package->number_of_car_featured)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                          <div class="product-default border p-15 mb-25">
                            <figure class="product-img mb-15">
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="lazy-container ratio ratio-2-3">
                                <img class="lazyload"
                                  data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}" alt="Product">
                              </a>
                            </figure>
                            <div class="product-details">
                              <span class="product-category font-xsm">{{ carBrand($car->brand_id) }}
                                {{ carModel($car->car_model_id) }}</span>
                              <div class="d-flex align-items-center justify-content-between mb-10">
                                <h5 class="product-title mb-0">
                                  <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                    title="{{ $car->title }}">{{ $car->title }}</a>
                                </h5>
                                @if (Auth::guard('web')->check())
                                  @php
                                    $user_id = Auth::guard('web')->user()->id;
                                    $checkWishList = checkWishList($car->id, $user_id);
                                  @endphp
                                @else
                                  @php
                                    $checkWishList = false;
                                  @endphp
                                @endif
                                <a href="{{ $checkWishList == false ? route('addto.wishlist', $car->id) : route('remove.wishlist', $car->id) }}"
                                  class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                                  data-tooltip="tooltip" data-bs-placement="right"
                                  title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                                  <i class="fal fa-heart"></i>
                                </a>
                              </div>
                              <div class="author mb-15">
                                @if ($car->vendor_id != 0)
                                  <a class="color-medium"
                                    href="{{ route('frontend.vendor.details', ['username' => ($vendor = @$car->vendor->username)]) }}"
                                    target="_self" title="{{ $vendor = @$car->vendor->username }}">
                                    @if ($car->vendor->photo != null)
                                      <img class="lazyload blur-up"
                                        data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                        alt="Image">
                                    @else
                                      <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                        alt="Image">
                                    @endif
                                    <span>{{ __('By') }} {{ $vendor = @$car->vendor->username }}</span>
                                  </a>
                                @else
                                  <a
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                    <img class="lazyload blur-up"
                                      data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                  </a>

                                  <span><a
                                      href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                      {{ $admin->username }}</a></span>
                                @endif
                              </div>
                              <ul class="product-icon-list p-0 mb-15 list-unstyled d-flex align-items-center">
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Model Year') }}">
                                  <i class="fal fa-calendar-alt"></i>
                                  <span>{{ $car->year }}</span>
                                </li>
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('mileage') }}">
                                  <i class="fal fa-road"></i>
                                  <span>{{ $car->mileage }}</span>
                                </li>
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Top Speed') }}">
                                  <i class="fal fa-tachometer-fast"></i>
                                  <span>{{ $car->speed }}</span>
                                </li>
                              </ul>
                              <div class="product-price">
                                <h6 class="new-price">
                                  {{ symbolPrice($car->price) }}
                                </h6>
                                @if (!is_null($car->previous_price))
                                  <span class="old-price font-sm">
                                    {{ symbolPrice($car->previous_price) }}</span>
                                @endif
                              </div>
                            </div>
                          </div><!-- product-default -->
                        </div>
                      @endif
                    @else
                      <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="product-default border p-15 mb-25">
                          <figure class="product-img mb-15">
                            <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                              class="lazy-container ratio ratio-2-3">
                              <img class="lazyload"
                                data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}"
                                alt="{{ $car->title }}">
                            </a>
                          </figure>
                          <div class="product-details">
                            <span class="product-category font-xsm">{{ carBrand($car->brand_id) }}
                              {{ carModel($car->car_model_id) }}</span>
                            <div class="d-flex align-items-center justify-content-between mb-10">
                              <h5 class="product-title mb-0">
                                <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                  title="{{ $car->title }}">{{ $car->title }}</a>
                              </h5>
                              @if (Auth::guard('web')->check())
                                @php
                                  $user_id = Auth::guard('web')->user()->id;
                                  $checkWishList = checkWishList($car->id, $user_id);
                                @endphp
                              @else
                                @php
                                  $checkWishList = false;
                                @endphp
                              @endif
                              <a href="{{ $checkWishList == false ? route('addto.wishlist', $car->id) : route('remove.wishlist', $car->id) }}"
                                class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                                data-tooltip="tooltip" data-bs-placement="right"
                                title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                                <i class="fal fa-heart"></i>
                              </a>
                            </div>
                            <div class="author mb-15">
                              @if ($car->vendor_id != 0)
                                <a class="color-medium"
                                  href="{{ route('frontend.vendor.details', ['username' => ($vendor = @$car->vendor->username)]) }}"
                                  target="_self" title="{{ $vendor = @$car->vendor->username }}">
                                  @if ($car->vendor->photo != null)
                                    <img class="lazyload blur-up"
                                      data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                      alt="Image">
                                  @else
                                    <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                      alt="Image">
                                  @endif
                                  <span>{{ __('By') }} {{ $vendor = @$car->vendor->username }}</span>
                                </a>
                              @else
                                <a
                                  href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                  <img class="lazyload blur-up"
                                    data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                </a>
                                <span><a
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                    {{ $admin->username }}</a></span>
                              @endif
                            </div>
                            <ul class="product-icon-list p-0 mb-15 list-unstyled d-flex align-items-center">
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('Model Year') }}">
                                <i class="fal fa-calendar-alt"></i>
                                <span>{{ $car->year }}</span>
                              </li>
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('mileage') }}">
                                <i class="fal fa-road"></i>
                                <span>{{ $car->mileage }}</span>
                              </li>
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('Top Speed') }}">
                                <i class="fal fa-tachometer-fast"></i>
                                <span>{{ $car->speed }}</span>
                              </li>
                            </ul>
                            <div class="product-price">
                              <h6 class="new-price">
                                {{ symbolPrice($car->price) }}
                              </h6>
                              @if (!is_null($car->previous_price))
                                <span class="old-price font-sm">
                                  {{ symbolPrice($car->previous_price) }}</span>
                              @endif
                            </div>
                          </div>
                        </div><!-- product-default -->
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
              @foreach ($categories as $category)
                <div class="tab-pane fade" id="tabName{{ $category->id }}">
                  <div class="row">
                    @php
                      $car_category_id = $category->id;

                      $cars = App\Models\Car::join('car_contents', 'car_contents.car_id', 'cars.id')
                          ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
                          ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
                          ->where([['memberships.status', '=', 1], ['memberships.start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d')], ['memberships.expire_date', '>=', \Carbon\Carbon::now()->format('Y-m-d')]])
                          ->where([['vendors.status', 1], ['cars.is_featured', 1], ['cars.status', 1]])
                          ->where([['car_contents.language_id', $language->id], ['car_contents.category_id', $car_category_id]])
                          ->select('cars.*', 'car_contents.slug', 'car_contents.title', 'car_contents.car_model_id', 'car_contents.brand_id')
                          ->inRandomOrder()
                          ->limit(8)
                          ->get();
                    @endphp
                    @foreach ($cars as $car)
                      @if ($car->vendor_id != 0)
                        @php
                          $vendor_featured_car = vendorTotalFeaturedCarHome($car->vendor_id);
                          $vendor_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($car->vendor_id);
                        @endphp
                        @if ($vendor_featured_car <= $vendor_package->number_of_car_featured)
                          <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="product-default border p-15 mb-25">
                              <figure class="product-img mb-15">
                                <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                  class="lazy-container ratio ratio-2-3">
                                  <img class="lazyload"
                                    data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}"
                                    alt="Product">
                                </a>
                              </figure>
                              <div class="product-details">
                                <span class="product-category font-xsm">{{ carBrand($car->brand_id) }}
                                  {{ carModel($car->car_model_id) }}</span>
                                <div class="d-flex align-items-center justify-content-between mb-10">
                                  <h5 class="product-title mb-0">
                                    <a
                                      href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                                  </h5>
                                  <a href="javaScript:void(0)" class="btn btn-icon" data-tooltip="tooltip"
                                    data-bs-placement="right" title="Save to Wishlist">
                                    <i class="fal fa-heart"></i>
                                  </a>
                                </div>
                                <div class="author mb-10">
                                  @if ($car->vendor_id != 0)
                                    <a class="color-medium"
                                      href="{{ route('frontend.vendor.details', ['username' => ($vendor = @$car->vendor->username)]) }}"
                                      target="_self" title="{{ $vendor = @$car->vendor->username }}">
                                      @if ($car->vendor->photo != null)
                                        <img class="lazyload blur-up"
                                          data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                          alt="Image">
                                      @else
                                        <img class="lazyload blur-up"
                                          data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Image">
                                      @endif
                                      <span>{{ __('By') }} {{ $vendor = @$car->vendor->username }}</span>
                                    </a>
                                  @else
                                    <a
                                      href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                      <img class="lazyload blur-up"
                                        data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                    </a>
                                    <span><a
                                        href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                        {{ $admin->username }}</a></span>
                                  @endif
                                </div>
                                <ul class="product-icon-list p-0 mb-15 list-unstyled d-flex align-items-center">
                                  <li class="icon-start">
                                    <i class="fal fa-tachometer-alt"></i>
                                    <span>{{ $car->speed }}</span>
                                  </li>
                                  <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                    title="{{ __('mileage') }}">
                                    <i class="fal fa-road"></i>
                                    <span>{{ $car->mileage }}</span>
                                  </li>
                                  <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                    title="{{ __('Top Speed') }}">
                                    <i class="fal fa-tachometer-fast"></i>
                                    <span>{{ $car->speed }}</span>
                                  </li>
                                </ul>
                                <div class="product-price">
                                  <h6 class="new-price">
                                    {{ symbolPrice($car->price) }}
                                  </h6>
                                  @if (!is_null($car->previous_price))
                                    <span class="old-price font-sm">{{ symbolPrice($car->previous_price) }}</span>
                                  @endif
                                </div>
                              </div>
                            </div><!-- product-default -->
                          </div>
                        @endif
                      @else
                        <div class="col-xl-3 col-lg-4 col-md-6">
                          <div class="product-default border p-15 mb-25">
                            <figure class="product-img mb-15">
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="lazy-container ratio ratio-2-3">
                                <img class="lazyload"
                                  data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}"
                                  alt="Product">
                              </a>
                            </figure>
                            <div class="product-details">
                              <span class="product-category font-xsm">{{ carBrand($car->brand_id) }}
                                {{ carModel($car->car_model_id) }}</span>
                              <div class="d-flex align-items-center justify-content-between mb-10">
                                <h5 class="product-title mb-0">
                                  <a
                                    href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                                </h5>
                                <a href="javaScript:void(0)" class="btn btn-icon" data-tooltip="tooltip"
                                  data-bs-placement="right" title="Save to Wishlist">
                                  <i class="fal fa-heart"></i>
                                </a>
                              </div>
                              <div class="author mb-10">
                                @if ($car->vendor_id != 0)
                                  <a class="color-medium"
                                    href="{{ route('frontend.vendor.details', ['username' => ($vendor = @$car->vendor->username)]) }}"
                                    target="_self" title="{{ $vendor = @$car->vendor->username }}">
                                    @if ($car->vendor->photo != null)
                                      <img class="lazyload blur-up"
                                        data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                        alt="Image">
                                    @else
                                      <img class="lazyload blur-up"
                                        data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Image">
                                    @endif
                                    <span>{{ __('By') }} {{ $vendor = @$car->vendor->username }}</span>
                                  </a>
                                @else
                                  <a
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                    <img class="lazyload blur-up"
                                      data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                  </a>
                                  <span><a
                                      href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                      {{ $admin->username }}</a></span>
                                @endif
                              </div>
                              <ul class="product-icon-list p-0 mb-15 list-unstyled d-flex align-items-center">
                                <li class="icon-start">
                                  <i class="fal fa-tachometer-alt"></i>
                                  <span>{{ $car->speed }}</span>
                                </li>
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('mileage') }}">
                                  <i class="fal fa-road"></i>
                                  <span>{{ $car->mileage }}</span>
                                </li>
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Top Speed') }}">
                                  <i class="fal fa-tachometer-fast"></i>
                                  <span>{{ $car->speed }}</span>
                                </li>
                              </ul>
                              <div class="product-price">
                                <h6 class="new-price">
                                  {{ symbolPrice($car->price) }}
                                </h6>
                                @if (!is_null($car->previous_price))
                                  <span class="old-price font-sm">{{ symbolPrice($car->previous_price) }}</span>
                                @endif
                              </div>
                            </div>
                          </div><!-- product-default -->
                        </div>
                      @endif
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
            <div class="text-center mt-20 mb-25" data-aos="fade-up">
              <a href="{{ route('frontend.cars') }}"
                class="btn btn-lg btn-primary">{{ __('View All Vehicles') }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Product-area end -->

  <!-- Banner collection start -->
  @if ($secInfo->banner_section == 1)
    <div class="banner-collection pb-75">
      <div class="container">
        <div class="row">
          @foreach ($banners as $banner)
            <div class="col-sm-6" data-aos="fade-up">
              <a href="{{ $banner->url }}">
                <div class="banner-sm content-middle bg-img mb-25 ratio ratio-21-9"
                  data-bg-image="{{ asset('assets/img/banners/' . $banner->image) }}">
                </div>
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
  <!-- Banner collection end -->

  <!-- Testimonial-area start -->
  @if ($secInfo->testimonial_section_status == 1)
    <section class="testimonial-area testimonial-1">
      <div class="container">
        <div class="content">
          <div class="row gx-xl-5 align-items-center">
            <div class="col-md-6 col-lg-5 border-end border-sm-0" data-aos="fade-up">
              <h2 class="title mb-20">{{ !empty($testimonialSecInfo->title) ? $testimonialSecInfo->title : '' }}</h2>
            </div>
            <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="100">
              <p class="text mb-20">{{ !empty($testimonialSecInfo->subtitle) ? $testimonialSecInfo->subtitle : '' }}
              </p>
            </div>
            <div class="col-lg-2" data-aos="fade-up" data-aos-delay="150">
              <!-- Slider navigation buttons -->
              <div class="slider-navigation text-end mb-20">
                <button type="button" title="Slide prev" class="slider-btn radius-0"
                  id="testimonial-slider-btn-prev">
                  <i class="fal fa-angle-left"></i>
                </button>
                <button type="button" title="Slide next" class="slider-btn radius-0"
                  id="testimonial-slider-btn-next">
                  <i class="fal fa-angle-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row align-items-center" data-aos="fade-up">
          <div class="col-lg-9">
            <div class="swiper pt-30 mb-15" id="testimonial-slider-1">
              <div class="swiper-wrapper">
                @foreach ($testimonials as $testimonial)
                  <div class="swiper-slide pb-25" data-aos="fade-up">
                    <div class="slider-item">
                      <div class="client mb-25">
                        <div class="client-info d-flex align-items-center">
                          <div class="client-img">
                            <div class="lazy-container rounded-pill ratio ratio-1-1">
                              @if (is_null($testimonial->image))
                                <img data-src="{{ asset('assets/img/profile.jpg') }}" alt="image"
                                  class="lazyload">
                              @else
                                <img class="lazyload"
                                  data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                  alt="Person Image">
                              @endif
                            </div>
                          </div>
                          <div class="content">
                            <h6 class="name">{{ $testimonial->name }}</h6>
                            <span class="designation">{{ $testimonial->occupation }}</span>
                          </div>
                        </div>
                        <div class="ratings">
                          <div class="rate">
                            <div class="rating-icon" style="width: {{ $testimonial->rating * 20 }}%"></div>
                          </div>
                          <span class="ratings-total">{{ $testimonial->rating }} {{ __('star') }}</span>
                        </div>
                      </div>
                      <div class="quote mb-25">
                        <span class="icon"><i class="fal fa-quote-right"></i></span>
                        <p class="text mb-0">
                          {{ $testimonial->comment }}
                        </p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="clients d-flex align-items-center" data-aos="fade-up">
              <div class="client-img">
                @foreach ($testimonials as $testimonial)
                  @if ($loop->iteration < 5)
                    @if (is_null($testimonial->image))
                      <img data-src="{{ asset('assets/img/profile.jpg') }}" alt="image" class="lazyload">
                    @else
                      <img class="lazyload" data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                        alt="Person Image">
                    @endif
                  @endif
                @endforeach
              </div>
              <span>{{ @$testimonialSecInfo->clients }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="img-content d-none d-lg-block"
        data-aos="slide-{{ $currentLanguageInfo->direction == 1 ? 'right' : 'left' }}">
        <div class="img">
          <img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $testimonialSecImage) }}" alt="Image">
        </div>
      </div>
      <!-- Shape -->
      <div class="shape"></div>
    </section>
  @endif
  <!-- Testimonial-area end -->

  <!-- Blog-area start -->
  @if ($secInfo->blog_section_status == 1)
    <section class="blog-area blog-1 pt-100 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <div class="section-title title-inline mb-30" data-aos="fade-up">
              <h2 class="title mb-20">{{ !empty($blogSecInfo->title) ? $blogSecInfo->title : '' }}</h2>
              <a href="{{ route('blog') }}" class="btn btn-lg btn-primary mb-20">{{ __('All Posts') }}</a>
            </div>
          </div>
          <div class="col-12">
            <div class="row gx-xl-5 justify-content-center">
              @foreach ($blogs as $blog)
                @if ($loop->iteration == 1)
                  <div class="col-lg-4" data-aos="fade-up">
                    <article class="card mb-30">
                      <div class="card-img fancy radius-0 mb-25">
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                          class="lazy-container ratio ratio-5-4">
                          <img class="lazyload" data-src="{{ asset('assets/img/blogs/' . $blog->image) }}"
                            alt="Blog Image">
                        </a>
                      </div>
                      <div class="content">
                        <h4 class="card-title">
                          <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                            {{ @$blog->title }}
                          </a>
                        </h4>
                        <p class="card-text">
                          {{ strlen(strip_tags($blog->content)) > 120 ? mb_substr(strip_tags($blog->content), 0, 120, 'UTF-8') . '...' : $blog->content }}
                        </p>
                        <div class="mt-20">
                          <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                            class="btn btn-lg btn-primary">{{ __('Read More') }}</a>
                        </div>
                      </div>
                    </article>
                  </div>
                @endif
              @endforeach
              <div class="col-lg-8">
                <div class="row">
                  @foreach ($blogs as $blog)
                    @if ($loop->iteration != 1)
                      <div class="col-lg-12 col-md-6" data-aos="fade-up">
                        <article class="card mb-25">
                          <div class="row align-items-center">
                            <div class="col-lg-5 mb-20 mb-lg-0">
                              <div class="card-img radius-0">
                                <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                                  class="lazy-container ratio ratio-5-4">
                                  <img class="lazyload" data-src="{{ asset('assets/img/blogs/' . $blog->image) }}"
                                    alt="Blog Image">
                                </a>
                              </div>
                            </div>
                            <div class="col-lg-7 order-lg-first">
                              <div class="content">
                                <h4 class="card-title">
                                  <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                                    {{ $blog->title }}
                                  </a>
                                </h4>
                                <p class="card-text">
                                  {{ strlen(strip_tags($blog->content)) > 90 ? mb_substr(strip_tags($blog->content), 0, 90, 'UTF-8') . '...' : $blog->content }}
                                </p>
                                <div class="mt-20">
                                  <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                                    class="btn btn-lg btn-outline">{{ __('Read More') }}</a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </article>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Blog-area end -->
@endsection
