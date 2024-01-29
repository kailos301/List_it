@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Ads') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_cars }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_cars }}
  @endif
@endsection
@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->car_page_title : __('Ads'),
  ])

  <!-- Listing-grid-area start -->
  <div class="listing-grid-area pt-40 pb-40">
    <div class="container">
      <div class="row gx-xl-5">
        <div class="col-lg-4 col-xl-3">
          <div class="widget-offcanvas offcanvas-lg offcanvas-start" tabindex="-1" id="widgetOffcanvas"
            aria-labelledby="widgetOffcanvas">
            <div class="offcanvas-header px-20">
              <h4 class="offcanvas-title">{{ __('Filter') }}</h4>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#widgetOffcanvas"
                aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-3 p-lg-0">
              <form action="{{ route('frontend.cars') }}" method="get" id="searchForm" class="w-100">
                 @if (!empty(request()->input('category')))
                          <input type="hidden" name="category" value="{{ request()->input('category') }}">
                        @endif
                       
                <aside class="widget-area" data-aos="fade-up">
                  <div class="widget widget-select p-0 mb-20">
                    <div class="row">
                      <div class="col-12 pb-40"><a href="{{ route('frontend.cars') }}" class="btn btn-lg btn-outline active icon-start w-100" style="cursor: not-allowed;" ><i class="fal fa-star fa-lg" ></i>{{ __('Save Search') }}</a></div>
                      <div class="col-6"><h4>Filters</h4></div>
                      <div class="col-6 text-right"> <div class="cta">
                    <a href="{{ route('frontend.cars') }}" class="btn btn-sm btn-primary icon-start"><i class="fal fa-sync-alt"></i>{{ __('Reset All') }}</a>
                  </div></div>
                    </div>                    
                 
                </div>
                @if (count($categories) >0)
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#select"
                        aria-expanded="true" aria-controls="select">
                        {{ __('Category') }}
                      </button> 
                    </h5>
                  
                    <div id="select" class="collapse show">
                      <div class="accordion-body  ">
                        <div class="row gx-sm-3">
                          <div class="col-12">
                            <div class="form-group">
                              
                                <div class="list-group list-group-flush">
                                   @foreach ($categories as $category)

                                  <a href="{{ route('frontend.cars', ['category' => $category->slug, 'pid' => $category->parent_id]) }}" class="mt-2 " @if (request()->input('category') == $category->slug) style="font-weight: bold; color: #EE2C7B; "   @endif> {{ $category->name }} </a>

                                  <!--  class="list-group-item">{{ $category->name }} <span class="float-right badge badge-light round">142</span> </a> -->
                                   @endforeach
                                   </div>  <!-- list-group .// -->
                         

                              <!-- <select name="category" id="" class="form-control" onchange="updateUrl()">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($categories as $category)
                                  <option @selected(request()->input('category') == $category->slug) value="{{ $category->slug }}">{{ $category->name }}
                                  </option>
                                @endforeach
                              </select> -->
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  <!-- <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#select"
                        aria-expanded="true" aria-controls="select">
                        {{ __('Search by Title') }}
                      </button>
                    </h5>
                    <div id="select" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row gx-sm-3">
                          <div class="col-12">
                            <div class="form-group">
                              <input type="text" class="form-control" id="searchByTitle" name="title"
                                value="{{ request()->input('title') }}" placeholder="{{ __('Search By  Title') }}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->

                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#location"
                        aria-expanded="true" aria-controls="location">
                        {{ __('Location') }}
                      </button>
                    </h5>
                    <div id="location" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row gx-sm-3">
                          <div class="col-12">
                             <div class="form-group">
                             <div class="col-12 float-start"> 
                              <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="location">
                                <option value="">{{ __('Any') }}</option>
                                
                                 @foreach ($carlocation as $clocation)
                                  <option value="{{ $clocation->slug }}" @selected(request()->input('location') == $clocation->slug)>{{ $clocation->name }}</option>
                                @endforeach
                              </select>

                             
                            </div>
                          <!--   <div class="form-group">
                              <input type="text" name="location" placeholder="{{ __('Search By Location') }}"
                                class="form-control" id="searchByLocation" value="{{ request()->input('location') }}">
                            </div> -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="widget widget-price p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#price" aria-expanded="true" aria-controls="price">
                        {{ __('Pricing') }}
                      </button>
                    </h5>
                    <div id="price" class="collapse show">
                      <div class="accordion-body scroll-y mt-20">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-6 float-start"float-start> 
                              <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="min">
                                <option value="">{{ __('Min Price') }}</option>
                                @foreach ($adsprices as $prices)
                                  <option 
                                    value="{{ $prices->name }}" @selected(request()->input('min') == $prices->name)>{{ symbolPrice($prices->name) }}</option>
                                @endforeach
                              </select>

                             
                            </div>
                        <div class="col-6 float-end">
                        <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="max">
                                <option value="">{{ __('Max Price') }}</option>
                                @foreach ($adsprices as $prices)
                                  <option 
                                    value="{{ $prices->name }}" @selected(request()->input('max') == $prices->name)>{{ symbolPrice($prices->name) }}</option>
                                @endforeach
                              </select>
                            </div>
                            </div>
                          </div>
                        </div>
                       <!--  <div class="row gx-sm-3 d-none">
                          <div class="col-md-6">
                            <div class="form-group mb-30">
                              <input class="form-control" type="hidden"
                                value="{{ request()->filled('min') ? request()->input('min') : $min }}" name="min"
                                id="min">

                              <input class="form-control" type="hidden" value="{{ $min }}" id="o_min">
                              <input class="form-control" type="hidden" value="{{ $max }}" id="o_max">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group mb-20">
                              <input class="form-control"
                                value="{{ request()->filled('max') ? request()->input('max') : $max }}" type="hidden"
                                name="max" id="max">
                            </div>
                          </div>
                        </div> -->
                        <input type="hidden" id="currency_symbol" value="{{ $basicInfo->base_currency_symbol }}">
                        <!-- <div class="price-item mt-10">
                          <div class="price-slider" data-range-slider='filterPriceSlider'></div>
                          <div class="price-value">
                            <span class="color-dark">{{ __('Price') . ':' }}
                              <span class="filter-price-range" data-range-value='filterPriceSliderValue'></span>
                            </span>
                          </div>
                        </div> -->
                      </div>
                    </div>
                  </div>
                  <!-- Car filters only start here --> 
                  @if( request()->get('category') != "carsuu" )
                     @includeIf('frontend.car.carfilter')
                  @endif
                  <!-- Car filters end here -->
                    <!-- Seller Type -->
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Seller Type') }}
                      </button>
                    </h5>

                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-4 float-start"float-start> 
                              <input type="hidden" id="seller_type"  name="stype" value="">
                                <button name="sellertype" style="border:1px solid #EE2C7B !important;" value="" type="button" class="cumButton w-100" >{{ __('Any') }}</button>
                             
                            </div>
                           
                            <div class="col-4 float-end">
                              <button type="button" class="cumButton w-100" style="cursor: not-allowed;">{{ __('Dealer') }}</button>
                            </div>
                             <div class="col-4 float-end">
                             <button type="button" class="cumButton w-100" d >{{ __('Private') }}</button>
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Aad Type -->
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Ad Type') }}
                      </button>
                    </h5>
                    @php
                    $classS= '';
                    $classW= '';
                    if(request()->input('adtype') == 'sale'){
                    $classS ='active';
                  }
                    else if(request()->input('adtype') == 'wanted') {
                    $classW ='active';
                   }
                    @endphp
                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-6 float-start"float-start> 
              <input type="hidden" id="ad_type"  name="adtype" value="{{ request()->filled('adtype') ? request()->input('adtype') : ''}}">
                                <button id="adtypeSale"  value="sale" type="button" class="cumButton{{$classS}} w-100 ">{{ __('For Sale') }}</button>
                             
                            </div>
                        <div class="col-6 float-end">
                             <button id="adtypeWanted"  value="wanted" type="button" class="cumButton{{$classW}} w-100 " >{{ __('Wanted') }}</button>
                        </div>
                            
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  

                  @if (!empty(showAd(1)))
                    <div class="text-center mt-40">
                      {!! showAd(1) !!}
                    </div>
                  @endif
                  <!-- Spacer -->
                  <div class="pb-40"></div>
                </aside>
              </form>
            </div>
          </div>

        </div>
        <div class="col-lg-8 col-xl-9" id="ajaxcall">
          <div class="product-sort-area" data-aos="fade-up">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <h4 class="mb-20">{{ $total_cars }} {{ $total_cars > 1 ? __('Ads') : __('Ads') }}
                  {{ __('Found') }}
                  @if (!empty(request()->input('category')))
                  {{ __('in') }}  {{ ucfirst(str_replace("-"," ",(request()->input('category')))) }}
                   @endif
                </h4>
              </div>
              <div class="col-4 d-lg-none">
                <button class="btn btn-sm btn-outline icon-end radius-sm mb-20" type="button"
                  data-bs-toggle="offcanvas" data-bs-target="#widgetOffcanvas" aria-controls="widgetOffcanvas">
                  {{ __('Filter') }} <i class="fal fa-filter"></i>
                </button>
              </div>
              <div class="col-8 col-lg-6">
                <ul class="product-sort-list list-unstyled mb-20">
                  <li class="item me-4">
                    <div class="sort-item d-flex align-items-center">
                      <label class="me-2 font-sm">{{ __('Sort By') }}:</label>
                      <form action="{{ route('frontend.cars') }}" method="get" id="SortForm">
                        @if (!empty(request()->input('category')))
                          <input type="hidden" name="category" value="{{ request()->input('category') }}">
                        @endif
                        @if (!empty(request()->input('title')))
                          <input type="hidden" name="title" value="{{ request()->input('title') }}">
                        @endif
                        @if (!empty(request()->input('location')))
                          <input type="hidden" name="location" value="{{ request()->input('location') }}">
                        @endif
                        @if (!empty(request()->input('brands')))
                          @foreach (request()->input('brands') as $brand)
                            <input type="hidden" name="brands[]" value="{{ $brand }}">
                          @endforeach
                        @endif
                        @if (!empty(request()->input('models')))
                          @foreach (request()->input('models') as $model)
                            <input type="hidden" name="models[]" value="{{ $model }}">
                          @endforeach
                        @endif
                        @if (!empty(request()->input('fuel_type')))
                          <input type="hidden" name="fuel_type" value="{{ request()->input('fuel_type') }}">
                        @endif
                        @if (!empty(request()->input('transmission')))
                          <input type="hidden" name="transmission" value="{{ request()->input('transmission') }}">
                        @endif
                        @if (!empty(request()->input('condition')))
                          <input type="hidden" name="condition" value="{{ request()->input('condition') }}">
                        @endif
                        @if (!empty(request()->input('min')))
                          <input type="hidden" name="min" value="{{ request()->input('min') }}">
                        @endif
                        @if (!empty(request()->input('max')))
                          <input type="hidden" name="max" value="{{ request()->input('max') }}">
                        @endif
                        <select name="sort" class="nice-select right color-dark" onchange="updateUrl2()">
                          <option {{ request()->input('sort') == 'new' ? 'selected' : '' }} value="new">
                            {{ __('Date : Newest on top') }}
                          </option>
                          <option {{ request()->input('sort') == 'old' ? 'selected' : '' }} value="old">
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
                  <li class="item">
                    <a href="" class="btn-icon active view_type" data-tooltip="tooltip" data-type="grid"
                      data-bs-placement="top" title="{{ __('Grid View') }}">
                      <i class="fas fa-th-large"></i>
                    </a>
                  </li>
                  <li class="item">
                    <a href="" class="btn-icon view_type" data-tooltip="tooltip" data-type='list'
                      data-bs-placement="top" title="{{ __('List View') }}">
                      <i class="fas fa-th-list"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row">
            @php
              $admin = App\Models\Admin::first();
            @endphp
            @foreach ($car_contents as $car_content)
              <div class="col-xl-4 col-md-6" data-aos="fade-up">
                <div class="product-default border p-15 mb-25">
                  <figure class="product-img mb-15">
                    <a href="{{ route('frontend.car.details', ['slug' => $car_content->slug, 'id' => $car_content->id]) }}"
                      class="lazy-container ratio ratio-2-3">
                      <img class="lazyload"
                        data-src="{{ asset('assets/admin/img/car-gallery/' . $car_content->feature_image) }}"
                        alt="{{ optional($car_content)->title }}">
                    </a>
                  </figure>
                  <div class="product-details">
                    <span class="product-category font-xsm">{{ carBrand($car_content->brand_id) }}
                      {{ carModel($car_content->car_model_id) }}</span>
                    <div class="d-flex align-items-center justify-content-between mb-10">
                      <h5 class="product-title mb-0">
                        <a href="{{ route('frontend.car.details', ['slug' => $car_content->slug, 'id' => $car_content->id]) }}"
                          title="{{ optional($car_content)->title }}">{{ optional($car_content)->title }}</a>
                      </h5>
                      @if (Auth::guard('web')->check())
                        @php
                          $user_id = Auth::guard('web')->user()->id;
                          $checkWishList = checkWishList($car_content->id, $user_id);
                        @endphp
                      @else
                        @php
                          $checkWishList = false;
                        @endphp
                      @endif
                      
                      <a href="{{ $checkWishList == false ? route('addto.wishlist', $car_content->id) : route('remove.wishlist', $car_content->id) }}"
                        class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                        data-tooltip="tooltip" data-bs-placement="right"
                        title="{{ $checkWishList == false ? __('Save Ads') : __('Saved') }}">
                        <i class="fal fa-heart"></i>
                      </a>
                     
                       
                    </div>
                    <div class="author mb-15">
                      @if ($car_content->vendor_id != 0)
                        <a class="color-medium"
                          href="{{ route('frontend.vendor.details', ['username' => ($vendor = @$car_content->vendor->username)]) }}"
                          target="_self" title="{{ $vendor = @$car_content->vendor->username }}">
                          @if ($car_content->vendor->photo != null)
                            <img class="lazyload blur-up"
                              data-src="{{ asset('assets/admin/img/vendor-photo/' . $car_content->vendor->photo) }}"
                              alt="Image">
                          @else
                            <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                              alt="Image">
                          @endif
                          <span>{{ __('By') }} {{ $vendor = optional($car_content->vendor)->username }}</span>
                        </a>
                      @else
                        <img class="lazyload blur-up" data-src="{{ asset('assets/img/admins/' . $admin->image) }}"
                          alt="Image">
                        <span><a
                            href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                            {{ $admin->username }}</a></span>
                      @endif
                    </div>
                    <ul class="product-icon-list p-0 mb-15 list-unstyled d-flex align-items-center">
                      @if ($car_content->year != null)
                      <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                        title="{{ __('Model Year') }}">
                        <i class="fal fa-calendar-alt"></i>
                        <span>{{ $car_content->year }}</span>
                      </li>
                      @endif
                       @if ($car_content->mileage != null)
                      <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                        title="{{ __('mileage') }}">
                        <i class="fal fa-road"></i>
                        <span>{{ $car_content->mileage }}</span>
                      </li>
                      @endif
                       @if ($car_content->speed != null)
                      <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                        title="{{ __('Top Speed') }}">
                        <i class="fal fa-tachometer-fast"></i>
                        <span>{{ $car_content->speed }}</span>
                      </li>
                      @endif
                    </ul>
                    <div class="product-price mb-10">
                      <h6 class="new-price color-primary">
                        {{ symbolPrice($car_content->price) }}
                      </h6>
                      @if (!is_null($car_content->previous_price))
                        <span class="old-price font-sm">
                          {{ symbolPrice($car_content->previous_price) }}</span>
                      @endif
                    </div>
                  </div>
                </div><!-- product-default -->
              </div>
            @endforeach
          </div>
          <div class="pagination mb-40 justify-content-center" data-aos="fade-up">
            {{ $car_contents->appends([
                    'title' => request()->input('title'),
                    'location' => request()->input('location'),
                    'brands' => request()->input('brands'),
                    'models' => request()->input('models'),
                    'fuel_type' => request()->input('fuel_type'),
                    'transmission' => request()->input('transmission'),
                    'condition' => request()->input('condition'),
                    'min' => request()->input('min'),
                    'max' => request()->input('max'),
                    'sort' => request()->input('sort'),
                ])->links() }}
          </div>

          @if (!empty(showAd(3)))
            <div class="text-center mt-4 mb-40">
              {!! showAd(3) !!}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- Listing-grid-area end -->
@endsection
@section('script')
<script>
  'use strict';

  const baseURL = "{{ url('/') }}";
</script>
@endsection