@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
  {{ $vendor->username }}
@endsection
@section('metaKeywords')
  {{ $vendor->username }}, {{ !request()->filled('admin') ? @$vendorInfo->name : '' }}
@endsection

@section('metaDescription')
  {{ !request()->filled('admin') ? @$vendorInfo->details : '' }}
@endsection

@section('content')
  <!-- Page title start-->
  <div
    class="page-title-area ptb-100 bg-img {{ $basicInfo->theme_version == 2 || $basicInfo->theme_version == 3 ? 'has_header_2' : '' }}"
    @if (!empty($bgImg)) data-bg-image="{{ asset('assets/img/' . $bgImg->breadcrumb) }}" @endif
    src="{{ asset('assets/front/images/placeholder.png') }}">
    <div class="container">
      <div class="content">
        <div class="vendor mb-15">
          <figure class="vendor-img">
            <a href="javaScript:void(0)" class="lazy-container ratio ratio-1-1">
              @if ($vendor->photo != null)
                <img class="lazyload" src="assets/images/placeholder.png"
                  data-src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}" alt="Vendor">
              @else
                <img class="lazyload" src="assets/images/placeholder.png"
                  data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Vendor">
              @endif
            </a>
          </figure>
          <div class="vendor-info">
            <h4 class="mb-2 color-white">{{ $vendor->username }}</h4>
            <span class="text-light">{{ __('Member since') }}
              {{ \Carbon\Carbon::parse($vendor->created_at)->format('F Y') }}</span>
            <span class="text-light d-block mt-2">{{ __('Total Cars') . ' : ' }}
              @if (request()->filled('admin'))
                @php
                  $total_cars = App\Models\Car::where('vendor_id', 0)
                      ->get()
                      ->count();
                @endphp
                {{ $total_cars }}
              @else
                {{ count($vendor->cars()->get()) }}
              @endif
            </span>
          </div>
        </div>
        <ul class="list-unstyled">
          <li class="d-inline"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
          <li class="d-inline">/</li>
          <li class="d-inline active opacity-75">
            {{ __('Vendor Details') }}</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Page title end-->

  <!-- Vendor-area start -->
  <div class="vendor-area pt-100 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        <div class="col-lg-8">
          <h4 class="title mb-20">{{ __('All Ads') }}</h4>
          <div class="tabs-navigation tabs-navigation-2 mb-20">
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <button class="nav-link active btn-md" data-bs-toggle="tab" data-bs-target="#tab_all"
                  type="button">{{ __('All Ads') }}</button>
              </li>
              @php
                if (request()->filled('admin')) {
                    $vendor_id = 0;
                } else {
                    $vendor_id = $vendor->id;
                }
              @endphp
              @foreach ($categories as $category)
                @php
                  $category_id = $category->id;
                  $cars_count = App\Models\Car::join('car_contents', 'car_contents.car_id', 'cars.id')
                      ->where([['vendor_id', $vendor_id], ['cars.status', 1]])
                      ->where('car_contents.language_id', $language->id)
                      ->where('car_contents.category_id', $category_id)
                      ->get()
                      ->count();
                @endphp
                @if ($cars_count > 0)
                  <li class="nav-item">
                    <button class="nav-link btn-md" data-bs-toggle="tab" data-bs-target="#tab_{{ $category->id }}"
                      type="button">{{ $category->name }}</button>
                  </li>
                @endif
              @endforeach
            </ul>
          </div>
          <div class="tab-content" data-aos="fade-up">
            <div class="tab-pane fade show active" id="tab_all">
              <div class="row">
                @if (count($all_cars) > 0)
                  @foreach ($all_cars as $all_car)
                    @php
                      $car_content = App\Models\Car\CarContent::where([['language_id', $language->id], ['car_id', $all_car->id]])->first();
                    @endphp
                    @if (!empty($car_content))
                      <div class="col-md-6" data-aos="fade-up">
                        <div class="product-default border p-20 mb-25">
                          <figure class="product-img mb-15">
                            <a href="{{ route('frontend.car.details', ['slug' => $car_content->slug, 'id' => $car_content->car_id]) }}"
                              class="lazy-container ratio ratio-2-3">
                              <img class="lazyload" src="assets/images/placeholder.png"
                                data-src="{{ asset('assets/admin/img/car/' . $all_car->feature_image) }}" alt="Product">
                            </a>
                          </figure>
                          <div class="product-details">
                            <span class="product-category font-xsm">{{ @$car_content->brand->name }}
                              {{ @$car_content->model->name }}</span>
                            <div class="d-flex align-items-center justify-content-between mb-10">
                              <h5 class="product-title mb-0"><a
                                  href="{{ route('frontend.car.details', ['slug' => $car_content->slug, 'id' => $car_content->car_id]) }}">{{ $car_content->title }}</a>
                              </h5>
                              @if (Auth::guard('web')->check())
                                @php
                                  $user_id = Auth::guard('web')->user()->id;
                                  $checkWishList = checkWishList($all_car->id, $user_id);
                                @endphp
                              @else
                                @php
                                  $checkWishList = false;
                                @endphp
                              @endif
                              <a href="{{ $checkWishList == false ? route('addto.wishlist', $car_content->id) : route('remove.wishlist', $car_content->id) }}"
                                class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                                data-tooltip="tooltip" data-bs-placement="right"
                                title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                                <i class="fal fa-heart"></i>
                              </a>
                            </div>
                            <ul class="product-icon-list p-0 mb-15 list-unstyled d-flex align-items-center">
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('Model Year') }}">
                                <i class="fal fa-calendar-alt"></i>
                                <span>{{ $all_car->year }}</span>
                              </li>
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('mileage') }}">
                                <i class="fal fa-road"></i>
                                <span>{{ $all_car->mileage }}</span>
                              </li>
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('Top Speed') }}">
                                <i class="fal fa-tachometer-fast"></i>
                                <span>{{ $all_car->speed }}</span>
                              </li>
                            </ul>
                            <div class="product-price">
                              <h6 class="new-price">{{ symbolPrice($all_car->price) }}</h6>
                              @if (!is_null($all_car->previous_price))
                                <span class="old-price font-sm">{{ symbolPrice($all_car->previous_price) }}</span>
                              @endif
                            </div>
                          </div>
                        </div><!-- product-default -->
                      </div>
                    @endif
                  @endforeach
                @else
                  <h4 class="text-center mt-4 mb-4">{{ __('No Ads Found') }}</h4>
                @endif
              </div>
            </div>
            @foreach ($categories as $category)
              @php
                $category_id = $category->id;
                $cars = App\Models\Car::join('car_contents', 'car_contents.car_id', 'cars.id')
                    ->where([['vendor_id', $vendor_id], ['cars.status', 1]])
                    ->where('car_contents.language_id', $language->id)
                    ->where('car_contents.category_id', $category_id)
                    ->select('cars.*', 'car_contents.slug', 'car_contents.title', 'car_contents.car_model_id', 'car_contents.brand_id')
                    ->orderBy('id', 'desc')
                    ->get();
              @endphp
              @if (count($cars) > 0)
                <div class="tab-pane fade" id="tab_{{ $category->id }}">
                  <div class="row">
                    @foreach ($cars as $car)
                      @php
                        $car_content = App\Models\Car\CarContent::where([['language_id', $language->id], ['car_id', $car->id]])->first();
                      @endphp
                      <div class="col-md-6" data-aos="fade-up">
                        <div class="product-default border p-20 mb-25">
                          <figure class="product-img mb-15">
                            <a href="{{ route('frontend.car.details', ['slug' => $car_content->slug, 'id' => $car_content->car_id]) }}"
                              class="lazy-container ratio ratio-2-3">
                              <img class="lazyload" src="assets/images/placeholder.png"
                                data-src="{{ asset('assets/admin/img/car/' . $car->feature_image) }}" alt="Product">
                            </a>
                          </figure>
                          <div class="product-details">
                            <span class="product-category font-xsm">{{ carBrand($car->brand_id) }}
                              {{ carModel($car->car_model_id) }}</span>
                            <div class="d-flex align-items-center justify-content-between mb-10">
                              <h5 class="product-title mb-0"><a
                                  href="{{ route('frontend.car.details', ['slug' => $car_content->slug, 'id' => $car_content->car_id]) }}">{{ $car_content->title }}</a>
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
                              <a href="{{ $checkWishList == false ? route('addto.wishlist', $car_content->id) : route('remove.wishlist', $car_content->id) }}"
                                class="btn btn-icon {{ $checkWishList == false ? '' : 'active' }}"
                                data-tooltip="tooltip" data-bs-placement="right"
                                title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                                <i class="fal fa-heart"></i>
                              </a>
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
                              <h6 class="new-price">{{ symbolPrice($car->price) }}</h6>
                              @if (!is_null($car->previous_price))
                                <span class="old-price font-sm">{{ symbolPrice($car->previous_price) }}</span>
                              @endif
                            </div>
                          </div>
                        </div><!-- product-default -->
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif
            @endforeach
          </div>

          @if (!empty(showAd(3)))
            <div class="text-center mt-4">
              {!! showAd(3) !!}
            </div>
          @endif
        </div>
        <div class="col-lg-4">
          <aside class="widget-area" data-aos="fade-up">
            <div class="widget-vendor mb-40 border p-20">
              <div class="vendor mb-20 text-center">
                <figure class="vendor-img mx-auto mb-15">
                  <div class="lazy-container ratio ratio-1-1">
                    @if ($vendor->photo != null)
                      <img class="lazyload" data-src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}"
                        alt="Vendor">
                    @else
                      <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Vendor">
                    @endif
                  </div>
                </figure>
                <div class="vendor-info">
                  <h5>{{ $vendor->username }}</h5>
                  <span class="verification">
                    {{ request()->input('admin') == true ? @$vendor->first_name : @$vendorInfo->name }}
                  </span>
                </div>
              </div>
              <!-- about text -->
              @if (request()->input('admin') == true)
                @if (!is_null($vendor->details))
                  <div class="font-sm">
                    <div class="click-show">
                      <p class="text">
                        <span class="color-dark"><b>{{ __('About') . ':' }}</b></span>
                        {{ $vendor->details }}
                      </p>
                    </div>
                    <div class="read-more-btn"><span>{{ __('Read more') }}</span></div>
                  </div>
                @endif
              @else
                @if (!is_null(@$vendorInfo->details))
                  <div class="font-sm">
                    <div class="click-show">
                      <p class="text">
                        <span class="color-dark"><b>{{ __('About') . ':' }}</b></span>
                        {{ @$vendorInfo->details }}
                      </p>
                    </div>
                    <div class="read-more-btn"><span>{{ __('Read more') }}</span></div>
                  </div>
                @endif
              @endif
              <hr>
              <!-- Toggle list start -->
              <ul class="toggle-list list-unstyled mt-15" id="toggleList" data-toggle-show="6">
                <li>
                  <span class="first">{{ __('Total Cars') . ':' }}</span>
                  <span
                    class="last">{{ request()->input('admin') == true? $total_cars: $vendor->cars()->get()->count() }}</span>
                </li>

               <!--  @if ($vendor->show_email_addresss == 1)
                  <li>
                    <span class="first">{{ __('Email') . ':' }}</span>
                    <span class="last"><a href="mailto:{{ $vendor->email }}">{{ $vendor->email }}</a></span>
                  </li>
                @endif -->

                @if ($vendor->show_phone_number == 1)
                  <li>
                    <span class="first">{{ __('Phone') }}</span>
                    <span class="last"><a href="tel:{{ $vendor->phone }}">{{ $vendor->phone }}</a></span>
                  </li>
                @endif

                @if (request()->input('admin') != true)
                  @if (!is_null(@$vendorInfo->city))
                    <li>
                      <span class="first">{{ __('City') . ':' }}</span>
                      <span class="last">{{ @$vendorInfo->city }}</span>
                    </li>
                  @endif

                  @if (!is_null(@$vendorInfo->state))
                    <li>
                      <span class="first">{{ __('State') . ':' }}</span>
                      <span class="last">{{ @$vendorInfo->state }}</span>
                    </li>
                  @endif

                 <!--  @if (!is_null(@$vendorInfo->country))
                    <li>
                      <span class="first">{{ __('Country') . ':' }}</span>
                      <span class="last">{{ @$vendorInfo->country }}</span>
                    </li>
                  @endif -->
                @endif

                @if (request()->input('admin') == true)
                  <li>
                    <span class="first">{{ __('Address') . ' : ' }}</span>
                    <span class="last">{{ $vendor->address != null ? $vendor->address : '-' }}</span>
                  </li>
                @else
                  <li>
                    <span class="first">{{ __('Address') . ' : ' }}</span>
                    <span class="last">{{ @$vendorInfo->address != null ? @$vendorInfo->address : '-' }}</span>
                  </li>
                @endif

                @if (request()->input('admin') != true)
                  @if (!is_null(@$vendorInfo->zip_code))
                    <li>
                      <span class="first">{{ __('Zip Code') . ':' }}</span>
                      <span class="last">{{ @$vendorInfo->zip_code }}</span>
                    </li>
                  @endif
                @endif


                @if (request()->input('admin') != true)
                  <li>
                    <span class="first">{{ __('Member since') . ':' }}</span>
                    <span class="last font-sm">{{ \Carbon\Carbon::parse($vendor->created_at)->format('F Y') }}</span>
                  </li>
                @endif

              </ul>
              <span class="show-more-btn" data-toggle-btn="toggleListBtn">
                {{ __('Show More') . ' +' }}
              </span>
              <hr>
              <!-- Toggle list end -->
              @if ($vendor->show_contact_form == 1)
                <div class="cta-btn mt-20">
                  <button class="btn btn-lg btn-primary w-100" data-bs-toggle="modal" data-bs-target="#contactModal"
                    type="button" aria-label="button">{{ __('Contact Now') }}</button>
                </div>
              @endif
            </div>

            @if (!empty(showAd(1)))
              <div class="text-center mb-40">
                {!! showAd(1) !!}
              </div>
            @endif

            @if (!empty(showAd(2)))
              <div class="text-center mb-40">
                {!! showAd(2) !!}
              </div>
            @endif
          </aside>
        </div>
      </div>
    </div>
  </div>
  <!-- Vendor-area end -->

  <!-- Contact Modal -->
  <div class="modal contact-modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title mb-0" id="contactModalLabel">{{ __('Contact Now') }}</h1>
            <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close">X</button>
        </div>
        <div class="modal-body">
          <form action="{{ route('vendor.contact.message') }}" method="POST" id="vendorContactForm">
            @csrf
            <input type="hidden" name="vendor_email" value="{{ $vendor->email }}">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group mb-20">
                  <input type="text" class="form-control" placeholder="{{ __('Enter Your Full Name') }}"
                    name="name">
                  <p class="text-danger em" id="err_name"></p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group mb-20">
                  <input type="email" class="form-control" placeholder="{{ __('Enter Your Email') }}"
                    name="email">
                  <p class="text-danger em" id="err_email"></p>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group mb-20">
                  <input type="text" class="form-control" placeholder="{{ __('Enter Subject') }}" name="subject">
                  <p class="text-danger em" id="err_subject"></p>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group mb-20">
                  <textarea name="message" class="form-control" placeholder="{{ __('Message') }}"></textarea>
                  <p class="text-danger em" id="err_message"></p>
                </div>
              </div>
              @if ($info->google_recaptcha_status == 1)
                <div class="col-md-12">
                  <div class="form-group mb-20">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    <p class="text-danger em" id="err_g-recaptcha-response"></p>
                  </div>
                </div>
              @endif
              <div class="col-lg-12 text-center">
                <button class="btn btn-lg btn-primary" id="vendorSubmitBtn" type="submit"
                  aria-label="button">{{ __('Send message') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
