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
  <section class="hero-banner hero-banner-3">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="swiper home-slider" id="home-slider-3" data-aos="fade-up">
            <div class="swiper-wrapper">
              @foreach ($sliderInfos as $slider)
                <div class="swiper-slide">
                  <div class="banner-content">
                    <span class="subtitle">{{ $slider->title }}</span>
                    <h1 class="title mb-lg-2">{{ $slider->text }}</h1>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="right-content bg-primary get-color">
            <div class="swiper home-img-slider" id="home-img-slider-3">
              <div class="swiper-wrapper">
                @foreach ($sliderInfos as $slider)
                  <div class="swiper-slide">
                    <img class="lazyload" src="{{ asset('assets/img/hero/sliders/' . $slider->background_image) }}"
                      data-src="{{ asset('assets/img/hero/sliders/' . $slider->background_image) }}" alt="Image">
                    <div class="overlay opacity-50"></div>
                  </div>
                @endforeach
              </div>
              <!-- If we need pagination -->
              <div class="swiper-pagination mb-10 d-lg-none" id="home-img-slider-3-pagination"></div>
            </div>
            @if (!empty($basicInfo))
              @if (!is_null($basicInfo->hero_section_video_url))
                <a href="{{ $basicInfo->hero_section_video_url }}"
                  class="video-btn youtube-popup position-absolute top-50 start-50 translate-middle">
                  <i class="fas fa-play"></i>
                </a>
              @endif
            @endif
          </div>
        </div>
        <div class="col-lg-11 col-xl-10">
          <div class="banner-filter-form mw-100 mt-40" data-aos="fade-up">
            <p class="text font-lg">{{ __('I’m Looking for') }}</p>
            <ul class="nav nav-tabs border-0">
              <li class="nav-item">
                <button class="nav-link active car_condition" data-id="" data-bs-toggle="tab" data-bs-target="#all"
                  type="button">{{ __('All') }}</button>
              </li>
              @foreach ($car_conditions as $condition)
                <li class="nav-item">
                  <button class="nav-link car_condition" data-id="{{ $condition->id }}" data-bs-toggle="tab"
                    data-bs-target="#all" type="button">{{ $condition->name }}</button>
                </li>
              @endforeach
            </ul>
            <div class="tab-content form-wrapper shadow-lg p-30">
              <div class="tab-pane fade active show" id="all">
                <input type="hidden" name="getModel" id="getModel" value="{{ route('fronted.get-car.brand.model') }}">
                <form action="{{ route('frontend.cars') }}" method="GET">
                  <input type="hidden" name="condition" class="condition">
                  <div class="row align-items-center gx-xl-3">
                    <div class="col-lg-11">
                      <div class="row">
                        <div class="col-md col-sm-6">
                          <div class="form-group border-end">
                            <label for="" class="font-sm">{{ __('Category') }}</label>
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
                            <label for="car_brand" class="font-sm">{{ __('Location') }}</label>
                            <input type="text" class="form-control" name="location"
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
                            <select class="nice-select" id="model" name="models">
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
                      <button type="submit" class="btn btn-icon bg-primary color-white rounded-circle">
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
      </div>
    </div>
  </section>
  <!-- Home-area end -->

  <!-- counter section start -->
  @if ($secInfo->counter_section_status == 1)
    <section class="choose-area choose-3 pt-100 pb-60">
      <div class="container">
        <div class="row align-items-center gx-xl-5">
          <div class="col-lg-6" data-aos="fade-{{ $currentLanguageInfo->direction == 1 ? 'left' : 'right' }}">
            <div class="image img-left mb-40">
              <img class="lazyload blur-up"
                @if (!empty($counterSectionImage)) data-src="{{ asset('assets/img/' . $counterSectionImage) }}" @endif
                alt="Image">
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-up">
            <div class="content-title mb-40">
              <h2 class="title mb-20 mt-0">{{ @$counterSectionInfo->title }}</h2>
              <p>{{ @$counterSectionInfo->subtitle }}</p>
              <div class="info-list radius-md mt-30">
                <div class="row align-items-center">
                  @foreach ($counters as $counter)
                    <div class="col-sm-6">
                      <div class="card mt-30">
                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle bg-primary-light"><i class="{{ $counter->icon }}"></i>
                          </div>
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
    </section>
  @endif
  <!-- counter section end -->

  <!-- Category-area start -->
  @if ($secInfo->category_section_status == 1)
    <section class="category-area category-3 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-30" data-aos="fade-up">
              <h2 class="title mb-20">{{ @$catgorySecInfo->title }} <span>.</span> </h2>
              <a href="{{ route('frontend.cars') }}" class="btn btn-lg rounded-pill btn-primary mb-20" title="Title"
                target="_self">{{ $catgorySecInfo ? $catgorySecInfo->button_text : __('View All') }}</a>
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
                          alt="Product Image" title="Product Image">
                      </div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Category-area end -->

  <!-- call to action start -->
  @if ($secInfo->call_to_action_section_status == 1)
    <section class="video-banner ptb-60 bg-img" data-bg-image="{{ asset('assets/img/' . $callToActionSectionImage) }}">
      <!-- Bg overlay -->
      <div class="overlay opacity-50"></div>

      <div class="container">
        <div class="row">
          <div class="col-lg-5" data-aos="fade-up">
            <div class="content-title">
              <span class="subtitle color-light mb-10">{{ @$callToActionSecInfo->title }}</span>
              <h2 class="title color-white mb-20 mt-0">{{ @$callToActionSecInfo->subtitle }}</h2>
              <p class="text color-light">{{ @$callToActionSecInfo->text }}</p>

              <div class="cta-btn mt-30">
                @if (!empty($callToActionSecInfo))
                  @if (!is_null($callToActionSecInfo->button_url))
                    <a href="{{ @$callToActionSecInfo->button_url }}" class="btn btn-lg rounded-pill btn-primary"
                      title="{{ @$callToActionSecInfo->button_name }}"
                      target="_self">{{ @$callToActionSecInfo->button_name }}</a>
                  @endif
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- call to action end -->

  <!-- featured section start -->
  @if ($secInfo->feature_section_status == 1)
    <section class="product-area product-area-2 pt-100 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <div class="section-title title-inline mb-30" data-aos="fade-up">
              <h2 class="title mb-20">
                {{ !empty($featuredSecInfo) ? $featuredSecInfo->title : __('New Featured Vehicles') }}</h2>
              <div class="tabs-navigation tabs-navigation-3 mb-20">
                <ul class="nav nav-tabs" data-hover="fancyHover">
                  <li class="nav-item active">
                    <button class="nav-link hover-effect active btn-md rounded-pill" data-bs-toggle="tab"
                      data-bs-target="#forAll" type="button">{{ __('All Cars') }}</button>
                  </li>
                  @foreach ($categories as $category)
                    <li class="nav-item">
                      <button class="nav-link hover-effect btn-md rounded-pill" data-bs-toggle="tab"
                        data-bs-target="#tabName{{ $category->id }}" type="button">{{ $category->name }}</button>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="tab-content" data-aos="fade-up">
              @php
                $admin = App\Models\Admin::first();
              @endphp
              <div class="tab-pane fade active show" id="forAll">
                <div class="row">
                  @foreach ($cars as $car)
                    @if ($car->vendor_id != 0)
                      @php
                        $vendor_featured_car = vendorTotalFeaturedCarHome($car->vendor_id);
                        $vendor_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($car->vendor_id);
                      @endphp
                      @if ($vendor_featured_car <= $vendor_package->number_of_car_featured)
                        <div class="col-md-6">
                          <div class="row g-0 product-default product-column border radius-md mb-30 align-items-center">
                            <figure class="product-img col-xl-5">
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="lazy-container radius-sm ratio ratio-5-4">
                                <img class="lazyload"
                                  data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}" alt="Product">
                              </a>
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="btn btn-sm btn-primary radius-sm hover-show"
                                title="View Details">{{ __('More Details') }}</a>
                            </figure>
                            <div class="product-details col-xl-7 py-2">
                              <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                                {{ carModel($car->car_model_id) }}</span>
                              <h5 class="product-title mb-10"><a
                                  href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                              </h5>
                              <div class="author mb-10">
                                @if ($car->vendor_id != 0)
                                  @if ($car->vendor->photo != null)
                                    <img class="lazyload blur-up"
                                      data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                      alt="Image">
                                  @else
                                    <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                      alt="Image">
                                  @endif
                                  <span>{{ __('By') }} {{ $vendor = optional($car->vendor)->username }}</span>
                                @else
                                  <a
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                    <img class="lazyload blur-up"
                                      src="{{ asset('assets/front/images/placeholder.png') }}"
                                      data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                  </a>
                                  <span><a
                                      href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                      {{ $admin->username }}</a></span>
                                @endif
                              </div>
                              <ul class="product-icon-list mt-15 list-unstyled d-flex align-items-center">
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
                              <div class="product-price mt-10">
                                <h6 class="new-price color-primary">{{ symbolPrice($car->price) }}</h6>
                                @if (!is_null($car->previous_price))
                                  <span class="old-price font-sm">
                                    {{ symbolPrice($car->previous_price) }}</span>
                                @endif
                              </div>
                            </div>
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
                              class="btn btn-icon radius-sm {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                              data-tooltip="tooltip" data-bs-placement="right"
                              title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                              <i class="fal fa-heart"></i>
                            </a>
                          </div><!-- product-default -->
                        </div>
                      @endif
                    @else
                      <div class="col-md-6">
                        <div class="row g-0 product-default product-column border radius-md mb-30 align-items-center">
                          <figure class="product-img col-xl-5">
                            <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                              class="lazy-container radius-sm ratio ratio-5-4">
                              <img class="lazyload"
                                data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}" alt="Product">
                            </a>
                            <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                              class="btn btn-sm btn-primary radius-sm hover-show"
                              title="View Details">{{ __('More Details') }}</a>
                          </figure>
                          <div class="product-details col-xl-7 py-2">
                            <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                              {{ carModel($car->car_model_id) }}</span>
                            <h5 class="product-title mb-10"><a
                                href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                            </h5>
                            <div class="author mb-10">
                              @if ($car->vendor_id != 0)
                                @if ($car->vendor->photo != null)
                                  <img class="lazyload blur-up"
                                    data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                    alt="Image">
                                @else
                                  <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                    alt="Image">
                                @endif
                                <span>{{ __('By') }} {{ $vendor = optional($car->vendor)->username }}</span>
                              @else
                                <a
                                  href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                  <img class="lazyload blur-up"
                                    src="{{ asset('assets/front/images/placeholder.png') }}"
                                    data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                </a>
                                <span><a
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                    {{ $admin->username }}</a></span>
                              @endif
                            </div>
                            <ul class="product-icon-list mt-15 list-unstyled d-flex align-items-center">
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
                            <div class="product-price mt-10">
                              <h6 class="new-price color-primary">{{ symbolPrice($car->price) }}</h6>
                              @if (!is_null($car->previous_price))
                                <span class="old-price font-sm">
                                  {{ symbolPrice($car->previous_price) }}</span>
                              @endif
                            </div>
                          </div>
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
                            class="btn btn-icon radius-sm {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                            data-tooltip="tooltip" data-bs-placement="right"
                            title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                            <i class="fal fa-heart"></i>
                          </a>
                        </div><!-- product-default -->
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
              @foreach ($categories as $category)
                <div class="tab-pane fade" id="tabName{{ $category->id }}">
                  @php
                    $category_id = $category->id;

                    $cars = App\Models\Car::join('car_contents', 'car_contents.car_id', 'cars.id')
                        ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
                        ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
                        ->where([['memberships.status', '=', 1], ['memberships.start_date', '<=', \Carbon\Carbon::now()->format('Y-m-d')], ['memberships.expire_date', '>=', \Carbon\Carbon::now()->format('Y-m-d')]])
                        ->where([['vendors.status', 1], ['cars.is_featured', 1], ['cars.status', 1]])
                        ->where([['car_contents.language_id', $language->id], ['car_contents.category_id', $category_id]])
                        ->select('cars.*', 'car_contents.slug', 'car_contents.title', 'car_contents.car_model_id', 'car_contents.brand_id')
                        ->inRandomOrder()
                        ->limit(8)
                        ->get();
                  @endphp
                  <div class="row">
                    @foreach ($cars as $car)
                      @if ($car->vendor_id != 0)
                        @php
                          $vendor_featured_car = vendorTotalFeaturedCarHome($car->vendor_id);
                          $vendor_package = App\Http\Helpers\VendorPermissionHelper::packagePermission($car->vendor_id);
                        @endphp
                        @if ($vendor_featured_car <= $vendor_package->number_of_car_featured)
                          <div class="col-md-6">
                            <div
                              class="row g-0 product-default product-column border radius-md mb-30 align-items-center">
                              <figure class="product-img col-xl-5">
                                <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                  class="lazy-container radius-sm ratio ratio-5-4">
                                  <img class="lazyload"
                                    data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}"
                                    alt="Product">
                                </a>
                                <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                  class="btn btn-sm btn-primary radius-sm hover-show"
                                  title="View Details">{{ __('More Details') }}</a>
                              </figure>
                              <div class="product-details col-xl-7 py-2">
                                <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                                  {{ carModel($car->car_model_id) }}</span>
                                <h5 class="product-title mb-10"><a
                                    href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                                </h5>
                                <div class="author mb-10">
                                  @if ($car->vendor_id != 0)
                                    @if ($car->vendor->photo != null)
                                      <img class="lazyload blur-up"
                                        data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                        alt="Image">
                                    @else
                                      <img class="lazyload blur-up"
                                        data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Image">
                                    @endif
                                    <span>{{ __('By') }} {{ $vendor = optional($car->vendor)->username }}</span>
                                  @else
                                    <a
                                      href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                      <img class="lazyload blur-up"
                                        src="{{ asset('assets/front/images/placeholder.png') }}"
                                        data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                    </a>
                                    <span><a
                                        href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                        {{ $admin->username }}</a></span>
                                  @endif
                                </div>
                                <ul class="product-icon-list mt-15 list-unstyled d-flex align-items-center">
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
                                <div class="product-price mt-10">
                                  <h6 class="new-price color-primary">{{ symbolPrice($car->price) }}</h6>
                                  @if (!is_null($car->previous_price))
                                    <span class="old-price font-sm">
                                      {{ symbolPrice($car->previous_price) }}</span>
                                  @endif
                                </div>
                              </div>
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
                                class="btn btn-icon radius-sm {{ $checkWishList == false ? '' : 'active' }}"
                                data-tooltip="tooltip" data-bs-placement="right"
                                title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                                <i class="fal fa-heart"></i>
                              </a>
                            </div><!-- product-default -->
                          </div>
                        @endif
                      @else
                        <div class="col-md-6">
                          <div class="row g-0 product-default product-column border radius-md mb-30 align-items-center">
                            <figure class="product-img col-xl-5">
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="lazy-container radius-sm ratio ratio-5-4">
                                <img class="lazyload"
                                  data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}"
                                  alt="Product">
                              </a>
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="btn btn-sm btn-primary radius-sm hover-show"
                                title="View Details">{{ __('More Details') }}</a>
                            </figure>
                            <div class="product-details col-xl-7 py-2">
                              <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                                {{ carModel($car->car_model_id) }}</span>
                              <h5 class="product-title mb-10"><a
                                  href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                              </h5>
                              <div class="author mb-10">
                                @if ($car->vendor_id != 0)
                                  @if ($car->vendor->photo != null)
                                    <img class="lazyload blur-up"
                                      data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                                      alt="Image">
                                  @else
                                    <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                      alt="Image">
                                  @endif
                                  <span>{{ __('By') }} {{ $vendor = optional($car->vendor)->username }}</span>
                                @else
                                  <a
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">
                                    <img class="lazyload blur-up"
                                      src="{{ asset('assets/front/images/placeholder.png') }}"
                                      data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                  </a>
                                  <span><a
                                      href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                      {{ $admin->username }}</a></span>
                                @endif
                              </div>
                              <ul class="product-icon-list mt-15 list-unstyled d-flex align-items-center">
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
                              <div class="product-price mt-10">
                                <h6 class="new-price color-primary">{{ symbolPrice($car->price) }}</h6>
                                @if (!is_null($car->previous_price))
                                  <span class="old-price font-sm">
                                    {{ symbolPrice($car->previous_price) }}</span>
                                @endif
                              </div>
                            </div>
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
                              class="btn btn-icon radius-sm {{ $checkWishList == false ? '' : 'active' }}"
                              data-tooltip="tooltip" data-bs-placement="right"
                              title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                              <i class="fal fa-heart"></i>
                            </a>
                          </div><!-- product-default -->
                        </div>
                      @endif
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
            <div class="text-center mt-20 mb-30" data-aos="fade-up">
              <a href="{{ route('frontend.cars') }}"
                class="btn btn-lg rounded-pill btn-primary">{{ __('All Vehicles') }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- featured section end -->

  <!-- Testimonial-area start -->
  @if ($secInfo->testimonial_section_status == 1)
    {{-- divided testimonials into 2 array --}}
    <section class="testimonial-area testimonial-2 pb-60">
      <div class="container">
        <div class="row align-items-center justify-content-end" data-aos="fade-up">
          <div class="col-lg-4">
            <div class="content-title mb-40" data-aos="fade-up">
              <span class="subtitle">{{ !empty($testimonialSecInfo->title) ? $testimonialSecInfo->title : '' }}</span>
              <h2 class="title mb-30 mt-0">
                {{ !empty($testimonialSecInfo->subtitle) ? $testimonialSecInfo->subtitle : '' }}</h2>
              <div class="clients d-flex align-items-center mb-30" data-aos="fade-up">
                <div class="client-img">
                  @foreach ($testimonials as $testimonial)
                    @if ($loop->iteration < 5)
                      @if (is_null($testimonial->image))
                        <img data-src="{{ asset('assets/img/profile.jpg') }}" alt="image"
                          class="lazyload blur-up">
                      @else
                        <img class="lazyload blur-up"
                          data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}" alt="Person Image">
                      @endif
                    @endif
                  @endforeach
                </div>
                <span>{{ @$testimonialSecInfo->clients }}</span>
              </div>
              <div class="img" data-aos="fade-{{ $currentLanguageInfo->direction == 1 ? 'left' : 'right' }}">
                <img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $testimonialSecImage) }}"
                  alt="Image">
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="swiper mb-40" id="testimonial-slider-2">
              <div class="swiper-wrapper">
                @foreach ($testimonials->chunk(4) as $chunk)
                  <div class="swiper-slide" data-aos="fade-up">
                    <div class="row">
                      @foreach ($chunk as $testimonial)
                        <div class="col-lg-6">
                          <div class="slider-item shadow-md radius-md mb-25">
                            <div class="quote">
                              <span class="icon"><i class="fal fa-quote-right"></i></span>
                              <p class="text mb-0">
                                {{ $testimonial->comment }}
                              </p>
                            </div>
                            <div class="client">
                              <div class="client-info d-flex align-items-center">
                                <div class="client-img">
                                  <div class="lazy-container rounded-pill ratio ratio-1-1">
                                    @if (is_null($testimonial->image))
                                      <img class="lazyload" data-src="{{ asset('assets/front/images/avatar-1.jpg') }}"
                                        alt="Person Image">
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
                                <span class="ratings-total">{{ $testimonial->rating }} {{ __('Star') }}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="swiper-pagination position-static" id="testimonial-slider-2-pagination"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Testimonial-area end -->

  <!-- Blog-area start -->
  @if ($secInfo->blog_section_status == 1)
    <section class="blog-area blog-2 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <div class="section-title title-inline mb-30" data-aos="fade-up">
              <div class="mb-20">
                <span class="subtitle">{{ !empty($blogSecInfo->subtitle) ? $blogSecInfo->subtitle : '' }}</span>
                <h2 class="title mb-0 mt-0">{{ !empty($blogSecInfo->title) ? $blogSecInfo->title : '' }}</h2>
              </div>
              <a href="{{ route('blog') }}" class="btn btn-lg rounded-pill btn-primary mb-20" title="Title"
                target="_self">{{ __('View More') }}</a>
            </div>
          </div>
          <div class="col-12">
            <div class="row justify-content-center">
              @foreach ($blogs as $blog)
                <div class="col-sm-6 col-lg-4" data-aos="fade-up">
                  <article class="card border radius-md mb-30">
                    <div class="card-img">
                      <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                        class="lazy-container radius-md ratio">
                        <img class="lazyload" data-src="{{ asset('assets/img/blogs/' . $blog->image) }}"
                          alt="Blog Image">
                      </a>
                    </div>
                    <div class="content p-20">
                      <h4 class="card-title">
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                          {{ @$blog->title }}
                        </a>
                      </h4>
                      <p class="card-text">
                        {{ strlen(strip_tags($blog->content)) > 120 ? mb_substr(strip_tags($blog->content), 0, 120, 'UTF-8') . '...' : $blog->content }}
                      </p>
                      <div class="mt-10">
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                          class="btn-text">{{ __('Read More') }}</a>
                      </div>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Blog-area end -->
@endsection
