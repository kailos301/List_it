@php
  $version = $basicInfo->theme_version;
@endphp
@extends('frontend.layouts.layout-v' . $version)

@section('pageHeading')
  {{ __('Listit | Isle of Man Largest  Classifieds Marketplace') }}
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
  <section class="hero-banner hero-banner-2">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="swiper home-slider" id="home-slider-2" data-aos="fade-up">
            <!-- <div class="swiper-wrapper">
              @foreach ($sliderInfos as $slider)
                <div class="swiper-slide">
                  <div class="banner-content">
                    <span class="subtitle">{{ $slider->title }}</span>
                    <h1 class="title mb-lg-2">{{ $slider->text }}</h1>
                  </div>
                </div>
              @endforeach
            </div> -->
          </div>
        </div>
        <div class="col-12">
          <!--<div class="right-content">
            <div class="swiper home-img-slider" id="home-img-slider-2"
              data-aos="fade-{{ $currentLanguageInfo->direction == 1 ? 'left' : 'right' }}">
               <div class="swiper-wrapper">
                @foreach ($sliderInfos as $slider)
                  <div class="swiper-slide">
                    <img class="lazyload" data-src="{{ asset('assets/img/hero/sliders/' . $slider->background_image) }}"
                      alt="Image">
                  </div>
                @endforeach
              </div> -->
              <!-- If we need pagination -->
              <!-- <div class="swiper-pagination position-static mt-20 d-lg-none" id="home-img-slider-2-pagination"></div>
            </div>
            <div class="overlap get-color"></div>
          </div> -->
        </div>
        <div class="col-lg-6 col-xl-6  ">
          <div class="banner-filter-form mw-100 " data-aos="fade-up">
            <p class="text font-lg justify-content-center"></p>
            <ul class="nav nav-tabs border-0">
             <!--  <li class="nav-item">
                <button class="nav-link active car_condition" data-id="" data-bs-toggle="tab" data-bs-target="#all"
                  type="button">{{ __('All') }}</button>
              </li> -->
        {{--  @foreach ($car_conditions as $condition) 
                @if($condition->name =='Farming')
                @else
                 @endif  
                      {{ $condition->name }}
        --}}
                      
                  <li class="nav-item">
                    <button class="nav-link car_condition active" data-id="24" data-bs-toggle="tab"
                      data-bs-target="#all" type="button">
                            
                       <!-- <i class="fas fa-car fa-fw me-2"></i> -->
                    Cars
                    </button>
                  </li>
                   <li class="nav-item">
                    <button class="nav-link car_condition" data-id="0" data-bs-toggle="tab"
                      data-bs-target="#all" type="button">
                            
                       <!-- <i class="fas fa-home fa-fw me-2"></i> -->
                    Marketplace
                    </button>
                  </li>
                   <li class="nav-item">
                    <button class="nav-link car_condition" data-id="39" data-bs-toggle="tab"
                      data-bs-target="#all" type="button">
                            
                       <!-- <i class="far fa-building fa-fw me-2"></i> -->
                    Property
                    </button>
                  </li>
                
             {{--  @endforeach --}}
            </ul>
            <div class="tab-content form-wrapper shadow-lg p-20">
              <div class="tab-pane fade active show" id="all">
                <input type="hidden" name="getModel" id="getModel" value="{{ route('fronted.get-car.brand.model') }}">
                <form action="{{ route('frontend.cars') }}" method="GET">
                  
                  <div class="row align-items-center gx-xl-3">
                    <div class="col-lg-12">
                      <div class="row ">
                       <!--  <div class="col-md col-sm-6">
                          <div class="form-group border-end">
                            <label for="" class="font-sm">{{ __('Category') }}</label>
                            <select class="nice-select" id="category" name="category">
                              <option value="">{{ __('All') }}</option>
                              @foreach ($categories as $category)
                                <option value="{{ $category->slug }}">{{ $category->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div> -->
                       <!--  <div class="col-md col-sm-6">
                          <div class="form-group border-end">
                            <label for="car_brand" class="font-sm">{{ __('Location') }}</label>
                            <input type="text" class="form-control" name="location"
                              placeholder="{{ __('Enter Location') }}">
                          </div>
                        </div> -->
                        <div class="col-md col-sm-6 carform">
                          <div class="mb-20">
                            <!-- <label for="car_brand" class="font-lg">{{ __('Brand') }}</label> -->
                            <select class="form-control js-example-basic-single1" id="car_brand" name="brands[]" required>
                              <option value="">{{ __('All Make') }}</option>
                              @foreach ($brands as $brand)
                                <option value="{{ $brand->slug }}">{{ $brand->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-md col-sm-6 carform">
                          <div class="mb-20">
                           <!--  <label for="model" class="font-lg">{{ __('Model') }}</label> -->
                            <select class="form-select form-control js-example-basic-single1" id="model" name="models[]">
                              <option value="">{{ __('All model') }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                        <div class="row ">
                       <div class="col-6 col-sm-3 carform">
                          <div class="mb-20">
                           <!--  <label for="model" class="font-lg">{{ __('Model') }}</label> -->
                            <select class="form-select form-control js-example-basic-single1" id="year_min" name="year_min">
                              <option value="">{{ __('Min Year') }}</option>
                              @foreach ($caryear as $year)
                                  <option 
                                    value="{{ $year->name }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6 col-sm-3 carform">
                          <div class="mb-20">
                           <!--  <label for="model" class="font-lg">{{ __('Model') }}</label> -->
                            <select class="form-select form-control js-example-basic-single1" id="year_max" name="year_max">
                              <option value="">{{ __('Max Year') }}</option>
                               @foreach ($caryear as $year)
                                  <option 
                                    value="{{ $year->name }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6 col-sm-3 carform">
                          <div class="mb-20">
                           <!--  <label for="model" class="font-lg">{{ __('Model') }}</label> -->
                            <select class="form-select form-control js-example-basic-single1" id="min" name="min">
                              <option value="">{{ __('Min Price') }}</option>
                               @foreach ($adsprices as $prices)
                                  <option 
                                    value="{{ $prices->name }}">{{ symbolPrice($prices->name) }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="col-6 col-sm-3 carform">
                          <div class="mb-20">
                           <!--  <label for="model" class="font-lg">{{ __('Model') }}</label> -->
                            <select class="form-select form-control js-example-basic-single1" id="max" name="max">
                              <option value="">{{ __('Max Price') }}</option>
                              @foreach ($adsprices as $prices)
                                  <option 
                                    value="{{ $prices->name }}">{{ symbolPrice($prices->name) }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                      </div>


                        <div class="col-12 carformtxt"  style="display: none;">
                          <div class="form-group">
                             <!-- <label for="model" class="font-sm">{{ __('Search') }}</label> -->
                           <input type="text" class="form-control" id="searchByTitle" name="title" value="" placeholder="Search By Title">
                           <div id="suggesstion-box" class="col-12 p-3 bg-white" style="display: none;"></div>
                          </div>
                        </div>


                       <!--  <input type="hidden" value="{{ request()->filled('min') ? request()->input('min') : $min }}"
                          name="min" id="min">
                        <input value="{{ request()->filled('max') ? request()->input('max') : $max }}" type="hidden"
                          name="max" id="max"> -->
                        <input class="form-control" type="hidden" value="{{ $min }}" id="o_min">
                        <input class="form-control" type="hidden" value="{{ $max }}" id="o_max">
                        <input type="hidden" id="currency_symbol" value="{{ $currencyInfo->base_currency_symbol }}">
                       <!--  <div class="col-md col-sm-12">
                          <div class="form-group">
                            <label for="priceSlider" class="font-sm mb-2">{{ __('Price') }}</label>
                            <div class="price-slider mb-2" data-range-slider="priceSlider"></div>
                            <span class="filter-price-range" data-range-value="priceSliderValue"></span>
                          </div>
                        </div> -->
                      
                    </div>
                     <div class="col-lg-12 text-md-end">
                      <button type="submit" class="btn btn-primary bg-primary color-white w-100">
                        {{ __('Search Now') }}</span>
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
<!-- Category-area start -->
  @if ($secInfo->category_section_status == 1)
    <section class="category-area category-1 pt-40">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-inline mb-50" data-aos="fade-up">
              <h3 class="title mb-0">{{ @$catgorySecInfo->title }}</h3>
            </div>
          </div>
          <div class="col-12">
            <div class="row tabsHtmlData">
              @foreach ($car_categories as $category)
                <div class="col-lg-3 col-sm-6" data-aos="fade-up">
                  <a href="{{ route('frontend.cars', ['category' => $category->slug]) }}">
                    <div class="category-item">

                       
                     
                      <div class="d-flex flex-wrep justify-content-between mb-10">

                        <h6 class="category-title mb-10">
                           <img class="lazyload blur-up"
                          data-src="{{ asset('assets/admin/img/car-category/' . $category->image) }}"
                          alt="{{ $category->name }}" title="{{ $category->name }}">  {{ $category->name }}
                        </h6>
                         
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
  <!-- Steps-area start -->
  @if ($secInfo->work_process_section_status == 1)
    <section class="steps-area pt-60 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-50" data-aos="fade-up">
              <span class="subtitle mb-10">{{ @$workProcessSecInfo->title }}</span>
              <h2 class="title">{{ @$workProcessSecInfo->subtitle }}</span></h2>
            </div>
          </div>
          <div class="col-12">
            <div class="row">
              @foreach ($processes as $process)
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                  <div class="card align-items-center text-center radius-md p-25 mb-30">
                    <div class="card-icon radius-md mb-25">
                      <i class="{{ $process->icon }}"></i>
                    </div>
                    <div class="card-content">
                      <h4 class="card-title mb-20">{{ $process->title }}</h4>
                      <p class="card-text lc-3">
                        {{ $process->text }}
                      </p>
                    </div>
                    <!-- <span class="text-stroke h2">0{{ $loop->iteration }}</span> -->
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- Steps-area end -->

  <!-- Category-area start -->
  <!-- @if ($secInfo->category_section_status == 1)
    <section class="category-area category-2 pt-60 pb-20 bg-img"
      data-bg-image="{{ asset('assets/img/' . $categorySectionImage) }}">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-lg-5" data-aos="fade-up">
            <div class="fluid-left">
              <div class="content-title mb-40">
                <span class="subtitle color-light mb-10">{{ @$catgorySecInfo->title }}</span>
                <h2 class="title color-white mb-20 mt-0">{{ @$catgorySecInfo->subtitle }}</h2>
                <p class="text color-light">{{ @$catgorySecInfo->text }}</p>
                <div class="slider-navigation mt-30">
                  <button type="button" title="Slide prev" class="slider-btn" id="category-slider-2-prev">
                    <i class="fal fa-angle-left"></i>
                  </button>
                  <button type="button" title="Slide next" class="slider-btn" id="category-slider-2-next">
                    <i class="fal fa-angle-right"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-7" data-aos="fade-up">
            <div class="swiper category-slider-2 mb-40" data-slide-view="3">
              <div class="swiper-wrapper">
                @foreach ($car_categories as $category)
                  <div class="swiper-slide">
                    <a href="{{ route('frontend.cars', ['category' => $category->slug]) }}">
                      <div class="category-item radius-md">
                        <div class="category-header d-flex flex-wrep justify-content-between">
                          <h5 class="category-title mb-10">
                            {{ $category->name }}
                          </h5>
                          <span
                            class="category-qty h5 mb-10">({{ $category->car_contents()->where('language_id', $currentLanguageInfo->id)->get()->count() }})</span>
                          <img class="shape lazyload" data-src="{{ asset('assets/front/images/shape-1.png') }}"
                            alt="Shape">
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
      </div>
    </section>
  @endif -->
  <!-- Category-area end -->

  <!-- featured section start -->
  @if ($secInfo->feature_section_status == 1)
    <section class="product-area pt-60 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <div class="section-title title-inline mb-30" data-aos="fade-up">
              <h2 class="title mb-20">
                {{ !empty($featuredSecInfo) ? $featuredSecInfo->title : __('New Featured Vehicles') }}
              </h2>
              <div class="tabs-navigation mb-20">
                <ul class="nav nav-tabs" data-hover="fancyHover">
                  <li class="nav-item active">
                    <button class="nav-link hover-effect active btn-md radius-sm" data-bs-toggle="tab"
                      data-bs-target="#forAll" type="button">{{ __('All Cars') }}</button>
                  </li>
                  @foreach ($categories as $category)
                    <li class="nav-item">
                      <button class="nav-link hover-effect btn-md radius-sm" data-bs-toggle="tab"
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
                        <div class="col-lg-6">
                          <div
                            class="row g-0 product-default product-column border radius-md mb-30 align-items-center p-15">
                            <figure class="product-img col-xl-5">
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="lazy-container radius-sm ratio ratio-2-3">
                                <img class="lazyload"
                                  data-src="{{ asset('assets/admin/img/car-gallery/' . $car->feature_image) }}" alt="Product">
                              </a>
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="btn btn-sm btn-primary radius-sm hover-show"
                                title="{{ __('More Details') }}">{{ __('More Details') }}</a>
                            </figure>
                            <div class="product-details col-xl-7">
                              <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                                {{ carModel($car->car_model_id) }}</span>
                              <h5 class="product-title mb-10">
                                <a
                                  href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                              </h5>
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
                              <ul class="product-icon-list mt-15 list-unstyled d-flex align-items-center">
                                @if ($car->year != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Model Year') }}">
                                  <i class="fal fa-calendar-alt"></i>
                                  <span>{{ $car->year }}</span>
                                </li>
                                 @endif
                                  @if ($car->mileage != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('mileage') }}">
                                  <i class="fal fa-road"></i>
                                  <span>{{ $car->mileage }}</span>
                                </li>
                                @endif
                                @if ($car->speed != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Top Speed') }}">
                                  <i class="fal fa-tachometer-fast"></i>
                                  <span>{{ $car->speed }}</span>
                                </li>
                                @endif
                              </ul>
                              <div class="product-price mt-10">
                                <h6 class="new-price color-primary">{{ symbolPrice($car->price) }}</h6>
                                @if (!is_null($car->previous_price))
                                  <span class="old-price font-sm">
                                    {{ symbolPrice($car->previous_price) }}
                                  </span>
                                @endif
                              </div>
                            </div>
                            @if (Auth::guard('vendor')->check())
                              @php
                                $user_id = Auth::guard('vendor')->user()->id;
                                $checkWishList = checkWishList($car->id, $user_id);
                              @endphp
                            @else
                              @php
                                $checkWishList = false;
                              @endphp
                            @endif
                            @if (Auth::guard('vendor')->check())
                            <a href="{{ $checkWishList == false ? route('addto.wishlist', $car->id) : route('remove.wishlist', $car->id) }}"
                              class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                              data-tooltip="tooltip" data-bs-placement="right"
                              title="{{ $checkWishList == false ? __('Save Ads') : __('Saved') }}">
                              <i class="fal fa-heart"></i>
                            </a>
                             @else
                             <a href="{{  route('vendor.login')  }}"
                              class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                              data-tooltip="tooltip" data-bs-placement="right"
                              title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                              <i class="fal fa-heart"></i>
                            </a>
                             @endif
                          </div><!-- product-default -->
                        </div>
                      @endif
                    @else
                      <div class="col-lg-6">
                        <div
                          class="row g-0 product-default product-column border radius-md mb-30 align-items-center p-15">
                          <figure class="product-img col-xl-5">
                            <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                              class="lazy-container radius-sm ratio ratio-2-3">
                              <img class="lazyload"
                                data-src="{{ asset('assets/admin/img/car-gallery/' . $car->feature_image) }}" alt="Product">
                            </a>
                            <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                              class="btn btn-sm btn-primary radius-sm hover-show"
                              title="{{ __('More Details') }}">{{ __('More Details') }}</a>
                          </figure>
                          <div class="product-details col-xl-7">
                            <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                              {{ carModel($car->car_model_id) }}</span>
                            <h5 class="product-title mb-10">
                              <a
                                href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                            </h5>
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
                            <ul class="product-icon-list mt-15 list-unstyled d-flex align-items-center">
                             @if ($car->year != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Model Year') }}">
                                  <i class="fal fa-calendar-alt"></i>
                                  <span>{{ $car->year }}</span>
                                </li>
                                 @endif
                                  @if ($car->mileage != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('mileage') }}">
                                  <i class="fal fa-road"></i>
                                  <span>{{ $car->mileage }}</span>
                                </li>
                                @endif
                                @if ($car->speed != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Top Speed') }}">
                                  <i class="fal fa-tachometer-fast"></i>
                                  <span>{{ $car->speed }}</span>
                                </li>
                                @endif
                            </ul>
                            <div class="product-price mt-10">
                              <h6 class="new-price color-primary">{{ symbolPrice($car->price) }}</h6>
                              @if (!is_null($car->previous_price))
                                <span class="old-price font-sm">
                                  {{ symbolPrice($car->previous_price) }}
                                </span>
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
                            class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
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
                          <div class="col-lg-6">
                            <div
                              class="row g-0 product-default product-column border radius-md mb-30 align-items-center p-15">
                              <figure class="product-img col-xl-5">
                                <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                  class="lazy-container radius-sm ratio ratio-2-3">
                                  <img class="lazyload"
                                    data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}"
                                    alt="Product">
                                </a>
                                <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                  class="btn btn-sm btn-primary radius-sm hover-show"
                                  title="{{ __('More Details') }}">{{ __('More Details') }}</a>
                              </figure>
                              <div class="product-details col-xl-7">
                                <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                                  {{ carModel($car->car_model_id) }}</span>
                                <h5 class="product-title mb-10">
                                  <a
                                    href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                                </h5>
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
                                <ul class="product-icon-list mt-15 list-unstyled d-flex align-items-center">
                                  @if ($car->year != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Model Year') }}">
                                  <i class="fal fa-calendar-alt"></i>
                                  <span>{{ $car->year }}</span>
                                </li>
                                 @endif
                                  @if ($car->mileage != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('mileage') }}">
                                  <i class="fal fa-road"></i>
                                  <span>{{ $car->mileage }}</span>
                                </li>
                                @endif
                                @if ($car->speed != null)
                                <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                  title="{{ __('Top Speed') }}">
                                  <i class="fal fa-tachometer-fast"></i>
                                  <span>{{ $car->speed }}</span>
                                </li>
                                @endif
                                </ul>
                                <div class="product-price mt-10">
                                  <h6 class="new-price color-primary">{{ symbolPrice($car->price) }}</h6>
                                  @if (!is_null($car->previous_price))
                                    <span class="old-price font-sm">
                                      {{ symbolPrice($car->previous_price) }}
                                    </span>
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
                                class="btn btn-icon {{ $checkWishList == false ? '' : 'active' }}"
                                data-tooltip="tooltip" data-bs-placement="right"
                                title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                                <i class="fal fa-heart"></i>
                              </a>
                            </div><!-- product-default -->
                          </div>
                        @endif
                      @else
                        <div class="col-lg-6">
                          <div
                            class="row g-0 product-default product-column border radius-md mb-30 align-items-center p-15">
                            <figure class="product-img col-xl-5">
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="lazy-container radius-sm ratio ratio-2-3">
                                <img class="lazyload"
                                  data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}"
                                  alt="Product">
                              </a>
                              <a href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}"
                                class="btn btn-sm btn-primary radius-sm hover-show"
                                title="{{ __('More Details') }}">{{ __('More Details') }}</a>
                            </figure>
                            <div class="product-details col-xl-7">
                              <span class="product-category font-sm">{{ carBrand($car->brand_id) }}
                                {{ carModel($car->car_model_id) }}</span>
                              <h5 class="product-title mb-10">
                                <a
                                  href="{{ route('frontend.car.details', ['slug' => $car->slug, 'id' => $car->id]) }}">{{ $car->title }}</a>
                              </h5>
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
                                    {{ symbolPrice($car->previous_price) }}
                                  </span>
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
                              class="btn btn-icon {{ $checkWishList == false ? '' : 'active' }}"
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
                class="btn btn-lg radius-md btn-primary">{{ __('All Ads') }}</a>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- featured section end -->

  <!-- counter section start -->
  @if ($secInfo->counter_section_status == 1)
    <section class="choose-area choose-2 pb-60">
      <div class="container">
        <div class="row align-items-center gx-xl-5">
          <div class="col-lg-6" data-aos="fade-up">
            <div class="content-title mb-40">
              <div class="w-lg-80">
                <h2 class="title mb-20 mt-0">{{ @$counterSectionInfo->title }}</h2>
                <p>{{ @$counterSectionInfo->subtitle }}</p>
              </div>
              <div class="info-list">
                <div class="row align-items-center">
                  @foreach ($counters as $counter)
                    <div class="col-sm-6">
                      <div class="card mt-30">
                        <div class="d-flex align-items-center">
                          <div class="card-icon radius-md bg-primary-light"><i class="{{ $counter->icon }}"></i>
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
          <div class="col-lg-6" data-aos="fade-{{ $currentLanguageInfo->direction == 1 ? 'right' : 'left' }}">
            <div class="image img-right mb-40">
              <img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $counterSectionImage) }}"
                alt="Image">
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- counter section end -->

  <!-- Testimonial-area start -->
  @if ($secInfo->testimonial_section_status == 1)
    <section class="testimonial-area testimonial-2 mb-60">
      <div class="container">
        <div class="section-title title-inline mb-30" data-aos="fade-up">
          <div class="col-lg-5">
            <span class="subtitle">{{ !empty($testimonialSecInfo->title) ? $testimonialSecInfo->title : '' }}</span>
            <h2 class="title mb-20 mt-0">
              {{ !empty($testimonialSecInfo->subtitle) ? $testimonialSecInfo->subtitle : '' }}</h2>
          </div>
          <div class="col-lg-6">
            <!-- Slider navigation buttons -->
            <div class="slider-navigation text-end mb-20">
              <button type="button" title="Slide prev" class="slider-btn" id="testimonial-slider-btn-prev">
                <i class="fal fa-angle-left"></i>
              </button>
              <button type="button" title="Slide next" class="slider-btn" id="testimonial-slider-btn-next">
                <i class="fal fa-angle-right"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="row align-items-center justify-content-end" data-aos="fade-up">
          <div class="col-lg-9">
            <div class="swiper pt-xl-5" id="testimonial-slider-1">
              <div class="swiper-wrapper">
                @foreach ($testimonials as $testimonial)
                  <div class="swiper-slide pb-25">
                    <div class="slider-item radius-md">
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
                          <span class="ratings-total">{{ $testimonial->rating }} {{ __('star') }}</span>
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
      <div class="img-content d-none d-lg-block"
        data-aos="fade-{{ $currentLanguageInfo->direction == 1 ? 'left' : 'right' }}">
        <div class="img">
          <img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $testimonialSecImage) }}" alt="Image">
        </div>
      </div>
    </section>
  @endif
  <!-- Testimonial-area end -->

  <!-- call to action section start -->
  @if ($secInfo->call_to_action_section_status == 1)
    <section class="video-banner pt-60 pb-20 bg-img"
      data-bg-image="{{ asset('assets/img/' . $callToActionSectionImage) }}">
      <!-- Bg overlay -->
      <div class="overlay opacity-50"></div>

      <div class="container">
        <div class="row align-items-center gx-xl-5">
          <div class="col-lg-5 col-md-7" data-aos="fade-up">
            <div class="content-title mb-40">
              <span class="subtitle color-light mb-10">{{ @$callToActionSecInfo->title }}</span>
              <h2 class="title color-white mb-20 mt-0">{{ @$callToActionSecInfo->subtitle }}</h2>
              <p class="text color-light">{{ @$callToActionSecInfo->text }}</p>
              <div class="cta-btn mt-30">
                @if (!empty($callToActionSecInfo))
                  @if (!is_null($callToActionSecInfo->button_url))
                    <a href="{{ @$callToActionSecInfo->button_url }}" class="btn btn-lg radius-md btn-primary"
                      title="{{ @$callToActionSecInfo->button_name }}"
                      target="_self">{{ @$callToActionSecInfo->button_name }}</a>
                  @endif
                @endif
              </div>
            </div>
          </div>
          <div class="col-lg-7 col-md-5" data-aos="fade-up">
            <div class="d-flex align-items-center justify-content-center justify-content-md-end mb-40">
              @if (!empty($callToActionSecInfo))
                @if (!is_null($callToActionSecInfo->video_url))
                  <a href="{{ @$callToActionSecInfo->video_url }}" class="video-btn youtube-popup">
                    <i class="fas fa-play"></i>
                  </a>
                @endif
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- call to action section end -->

  <!-- Blog-area start -->
  @if ($secInfo->blog_section_status == 1)
    <section class="blog-area blog-2 pt-70 pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12" data-aos="fade-up">
            <div class="section-title title-center mb-50" data-aos="fade-up">
              <span class="subtitle">{{ !empty($blogSecInfo->subtitle) ? $blogSecInfo->subtitle : '' }}</span>
              <h2 class="title mb-0 mt-0">{{ !empty($blogSecInfo->title) ? $blogSecInfo->title : '' }}</h2>
            </div>
          </div>
          <div class="col-12">
            <div class="row justify-content-center">
              @foreach ($blogs as $blog)
                <div class="col-sm-6 col-lg-4" data-aos="fade-up">
                  <article class="card mb-30">
                    <div class="card-img radius-0 mb-25">
                      <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                        class="lazy-container radius-md ratio">
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
