@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Car Details') }}
@endsection

@section('metaKeywords')
  @if ($car->car_content)
    {{ $car->car_content->meta_keyword }}
  @endif
@endsection

@section('metaDescription')
  @if ($car->car_content)
    {{ $car->car_content->meta_description }}
  @endif
@endsection
@section('content')
  {{-- breadcrub start --}}
  <div
    class="page-title-area ptb-40 bg-img {{ $basicInfo->theme_version == 2 || $basicInfo->theme_version == 3 ? 'has_header_2' : '' }}"
    @if (!empty($bgImg)) data-bg-image="{{ asset('assets/img/' . $bgImg->breadcrumb) }}" @endif
    src="{{ asset('assets/front/images/placeholder.png') }}">
    <div class="container">
      <div class="content">
        <h2>
          {{ strlen(@$car->car_content->title) > 40 ? substr(@$car->car_content->title, 0, 40) . '...' : @$car->car_content->title }}
        </h2>
        <ul class="list-unstyled">
          <li class="d-inline"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
          <li class="d-inline">/</li>
          <li class="d-inline active opacity-75">{{ __('Ads Details') }}</li>
        </ul>
      </div>
    </div>
  </div>
  {{-- breadcrub end --}}

  <!-- Listing-single-area start -->
  <div class="listing-single-area pt-40 pb-60">
    <div class="container">
      @php
        $admin = App\Models\Admin::first();
        $car_id = $car->id;
        $carid = $car->id; 
        $ctitle = $car->car_content->title;
      @endphp
      @if ($car->vendor_id != 0)
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form action="{{ route('vendor.support_ticket.store') }}" enctype="multipart/form-data" method="POST">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ $car->vendor->vendor_info->name }}</h5>
        <input type="hidden" value="{{ $carid }}" name="car_id">
        <input type="hidden" value="{{ $ctitle }}" name="subject">
        <input type="hidden" value="{{ $car->vendor_id }}" name="admin_id">
        @csrf
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           
            <div class="user mb-20">
               <div class="row">
              <div class="col-2">
                  <div class="user-img" style="max-width: 80px">
                    <div class="lazy-container ratio ratio-1-1 rounded-pill">
                      @if ($car->vendor->photo != null)
                        <img class="lazyload"
                          data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                          alt="">
                      @else
                        <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="">
                      @endif
                    </div>
                  </div>
                </div> 
                 <div class="col-8"> 
                  <div class="user-info">
                    <h6 class="mb-1">
                      <a href="{{ route('frontend.vendor.details', ['username' => $car->vendor->username]) }}"
                        title="{{ $car->vendor->username }}">{{ $car->vendor->vendor_info->name }}</a>
                    </h6>
                     @if ($car->vendor->trader==0)
                     <p>{{ @$car->vendor->vendor_info->city }}. Private Seller, </p>
                      @else
                      <p>{{ @$car->vendor->vendor_info->city }}. Trader, </p>
                      @endif
                    <!-- <a href="tel:{{ $car->vendor->phone }}">{{ $car->vendor->phone }}</a>
                    <br> -->
                    
                    
                      <!-- {{ @$car->vendor->vendor_info->country }} -->
                  </div>
            </div>

            </div>
           <div class="row">
              <div class="col-10">  
            <textarea id="en_description" class="form-control " name="description" data-height="200">Hi, is this still available? Is the price negotiable?

Thanks</textarea>
            </div>
          </div> </div> 
      </div>
      <div class="modal-footer justify-content-between">
        
        <button type="submit" value="Submit" class="btn btn-primary">Send </button>
      </div>
    </div>
  </form>
  </div>
</div>
 @endif
      <div class="row gx-xl-5">
        <div class="col-lg-8">
          <div class="product-single-gallery mb-40">
            <div class="swiper product-single-slider">
              <div class="swiper-wrapper">
                @foreach ($car->galleries as $gallery)
                  <div class="swiper-slide">
                    <figure class="lazy-container ratio ratio-5-3">
                      <a href="{{ asset('assets/admin/img/car-gallery/' . $gallery->image) }}" class="lightbox-single">
                        <img class="lazyload" data-src="{{ asset('assets/admin/img/car-gallery/' . $gallery->image) }}"
                          alt="product image" />
                      </a>
                    </figure>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="product-thumb">
              <div class="swiper slider-thumbnails">
                <div class="swiper-wrapper">
                  @foreach ($car->galleries as $gallery)
                    <div class="swiper-slide">
                      <div class="thumbnail-img lazy-container ratio ratio-5-3">
                        <img class="lazyload" data-src="{{ asset('assets/admin/img/car-gallery/' . $gallery->image) }}"
                          alt="product image" />
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
              <!-- Slider navigation buttons -->
              <div class="slider-navigation">
                <button type="button" title="Slide prev" class="slider-btn slider-btn-prev radius-0">
                  <i class="fal fa-angle-left"></i>
                </button>
                <button type="button" title="Slide next" class="slider-btn slider-btn-next radius-0">
                  <i class="fal fa-angle-right"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="product-single-details">
            <div class="row" data-aos="fade-up">
              <div class="col-md-8">
                <span class="product-category"><a
                    href="{{ route('frontend.cars', ['category' => @$car->car_content->category->slug]) }}">{{ @$car->car_content->category->name }}</a></span>
                <h3 class="product-title mt-10 mb-20">{{ @$car->car_content->title }}</h3>
                Price<h4 class="new-price color-primary">
                    {{ symbolPrice($car->price) }}
                  </h4>
              </div>
              <div class="col-md-4">
                <div class="product-price mb-20">
                   <a href="{{ route('addto.wishlist', $car->car_content) }}"
                        class="btn btn-icon "
                        data-tooltip="tooltip" data-bs-placement="right"
                        title="{{  __('Saved') }}">
                        <i class="fal fa-heart"></i>
                      </a>
                  <!-- @if (!is_null($car->previous_price))
                    <span class="old-price h4 color-medium">
                      {{ symbolPrice($car->previous_price) }}
                    </span>
                  @endif -->
                </div>
                <div class="author mb-20 align-items-start">
                  @if ($car->vendor_id != 0)
                    <div class="image">
                      @if ($car->vendor && $car->vendor->photo != null)
                        <img class="lazyload blur-up"
                          data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}" alt="">
                      @else
                        <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="">
                      @endif
                    </div>
                    <div class="author-info">
                      <h5 class="mb-10 lh-1">
                        @if ($car->vendor)
                          <a href="{{ route('frontend.vendor.details', ['username' => ($vendor = @$car->vendor->username)]) }}"
                            target="_self" title="{{ $vendor = @$car->vendor->username }}">
                            {{ __('By') }} {{ $vendor = optional($car->vendor)->username }}
                          </a>
                        @endif
                      </h5>
                      <p class="mb-0 lh-1 font-sm">{{ __('Total Cars') . ' : ' }}{{ $car->vendor->cars()->count() }}</p>
                      <p class="mb-0 font-sm icon-start"><i class="fas fa-eye"></i>{{ __('Views') . ' : ' }}
                        {{ $car->visitors()->get()->count() }}</p>
                    </div>
                  @else
                    <div class="image">
                      <img class="lazyload blur-up" data-src="{{ asset('assets/img/admins/' . $admin->image) }}"
                        alt="">
                    </div>
                    <div class="author-info">
                      <h6 class="mb-2 lh-1"><a
                          href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                          {{ $admin->username }}</a>
                      </h6>
                      <p class="mb-0 font-sm">{{ __('Total Cars') . ' : ' }}{{ $car->vendor->cars()->count() }}</p>
                    </div>
                  @endif

                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-11">
                <!-- Product description -->
                <div class="product-desc pt-40" data-aos="fade-up">
                  <h4 class="mb-20">{{ __('Description') }}</h4>
                  <div class="tinymce-content">
                    {!! optional($car->car_content)->description !!}
                  </div>
                </div>

                <!-- Product specification -->
                <div class="product-spec pt-60" data-aos="fade-up">
                  <h4 class="mb-20">{{ __('Specifications') }}</h4>
                  <div class="row">
                     @if ($car->year != null)
                    <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                      <h6 class="mb-1">{{ __('Model Year') }}</h6>
                      <span>{{ $car->year }}</span>
                    </div>
                    @endif
                    @if ($car->mileage != null)
                    <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                      <h6 class="mb-1">{{ __('Mileage') }}</h6>
                      <span>{{ $car->mileage }}</span>
                    </div>
                    @endif
                    @if ($car->speed != null)
                    <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                      <h6 class="mb-1">{{ __('Top Speed') }}</h6>
                      <span>{{ $car->speed }}({{ __('KMH') }})</span>
                    </div>
                    @endif
                    @if ($car->car_content->brand != null)
                    <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                      <h6 class="mb-1">{{ __('Brand') }}</h6>
                      <span>{{ optional($car->car_content->brand)->name }}</span>
                    </div>
                    @endif
                     @if ($car->car_content->model != null)
                    <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                      <h6 class="mb-1">{{ __('Model') }}</h6>
                      <span>{{ optional($car->car_content->model)->name }}</span>
                    </div>
                    @endif
                    @if ($car->car_content->fuel_type != null)
                    <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                      <h6 class="mb-1">{{ __('Fuel Type') }}</h6>
                      <span>{{ optional($car->car_content->fuel_type)->name }}</span>
                    </div>
                    @endif
                    @if ($car->car_content->transmission_type != null)
                    <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                      <h6 class="mb-1">{{ __('Transmission Type') }}</h6>
                      <span>{{ optional($car->car_content->transmission_type)->name }}</span>
                    </div>
                    @endif
                    @if (count($specifications) > 0)
                      @foreach ($specifications as $specification)
                        @php
                          $car_specification_content = App\Models\Car\CarSpecificationContent::where([['car_specification_id', $specification->id], ['language_id', $language->id]])->first();
                        @endphp
                        <div class="col-lg-3 col-sm-6 col-md-4 mb-20">
                          <h6 class="mb-1">{{ $car_specification_content->label }}</h6>
                          <span>{{ $car_specification_content->value }}</span>
                        </div>
                      @endforeach
                    @endif
                  </div>
                </div>

                <!-- Product description -->
                <div class="product-desc pt-40" data-aos="fade-up">
                  <h4 class="mb-20">{{ __('Location') }}</h4>
                  <p>{{ @$car->car_content->address }}</p>

                 <!--  <div class="w-100">
                    <iframe
                      src="//www.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $car->latitude ?? 36.7783 }},%20{{ $car->longitude ?? 119.4179 }}+(My%20Business%20Name)&amp;t=&amp;z=12&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
                      class="border-0 map-iframe" allowfullscreen="" aria-hidden="false" tabindex=""></iframe>
                  </div> -->
                </div>
              </div>
              @if (!empty(showAd(3)))
                <div class="text-center mb-3 mt-3">
                  {!! showAd(3) !!}
                </div>
              @endif
            </div>
            @if (count($related_cars) > 0)
              <!-- Product-area start -->
              <div class="product-area pt-60">
                <div class="section-title title-inline mb-30" data-aos="fade-up">
                  <h3 class="title mb-20">{{ __('Related Cars') }}</h3>
                  <!-- Slider navigation buttons -->
                  <div class="slider-navigation mb-20">
                    <button type="button" title="Slide prev" class="slider-btn slider-btn-prev btn-outline radius-0"
                      id="product-slider-1-prev">
                      <i class="fal fa-angle-left"></i>
                    </button>
                    <button type="button" title="Slide next" class="slider-btn slider-btn-next btn-outline radius-0"
                      id="product-slider-1-next">
                      <i class="fal fa-angle-right"></i>
                    </button>
                  </div>
                </div>
                <div class="swiper product-slider mb-40" id="product-slider-1">

                  <div class="swiper-wrapper">
                    @foreach ($related_cars as $related_car)
                      <div class="swiper-slide" data-aos="fade-up">
                        <div class="product-default border p-15 mb-25">
                          <figure class="product-img mb-15">
                            <a href="{{ route('frontend.car.details', ['slug' => $related_car->slug, 'id' => $related_car->id]) }}"
                              class="lazy-container ratio ratio-2-3">
                              <img class="lazyload"
                                data-src="{{ asset('assets/admin/img/car/' . $related_car->feature_image) }}"
                                alt="{{ $related_car->title }}">
                            </a>
                          </figure>
                          <div class="product-details">
                            <span class="product-category font-xsm">
                              {{ carBrand($related_car->brand_id) }} {{ carModel($related_car->car_model_id) }}
                            </span>
                            <div class="d-flex align-items-center justify-content-between mb-10">
                              <h5 class="product-title mb-0"><a
                                  href="{{ route('frontend.car.details', ['slug' => $related_car->slug, 'id' => $related_car->id]) }}"
                                  title="{{ $related_car->title }}">{{ $related_car->title }}</a>
                              </h5>
                              @if (Auth::guard('web')->check())
                                @php
                                  $user_id = Auth::guard('web')->user()->id;
                                  $checkWishList = checkWishList($related_car->id, $user_id);
                                @endphp
                              @else
                                @php
                                  $checkWishList = false;
                                @endphp
                              @endif
                              <a href="{{ $checkWishList == false ? route('addto.wishlist', $related_car->id) : route('remove.wishlist', $related_car->id) }}"
                                class="btn btn-icon {{ $checkWishList == false ? '' : 'wishlist-active' }}"
                                data-tooltip="tooltip" data-bs-placement="right"
                                title="{{ $checkWishList == false ? __('Save to Wishlist') : __('Saved') }}">
                                <i class="fal fa-heart"></i>
                              </a>
                            </div>
                            <div class="author mb-15">
                              @if ($related_car->vendor_id != 0)
                                @if ($related_car->vendor)
                                  <a class="color-medium"
                                    href="{{ route('frontend.vendor.details', ['username' => @$related_car->vendor->username]) }}"
                                    target="_self" title="{{ $vendor = @$related_car->vendor->username }}">
                                    @if ($related_car->vendor->photo != null)
                                      <img class="lazyload blur-up"
                                        data-src="{{ asset('assets/admin/img/vendor-photo/' . $related_car->vendor->photo) }}"
                                        alt="Image">
                                    @else
                                      <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                        alt="Image">
                                    @endif
                                    <span>{{ __('By') }}
                                      {{ $vendor = optional($related_car->vendor)->username }}</span>
                                  </a>
                                @endif
                              @else
                                <img class="lazyload blur-up"
                                  data-src="{{ asset('assets/img/admins/' . $admin->image) }}" alt="Image">
                                <span><a
                                    href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                    {{ $admin->username }}</a></span>
                              @endif
                            </div>
                            <ul class="product-icon-list p-0 mb-15 list-unstyled d-flex align-items-center">
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('Model Year') }}">
                                <i class="fal fa-calendar-alt"></i>
                                <span>{{ $related_car->year }}</span>
                              </li>
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('mileage') }}">
                                <i class="fal fa-tachometer-alt-slowest"></i>
                                <span>{{ $related_car->mileage }}</span>
                              </li>
                              <li class="icon-start" data-tooltip="tooltip" data-bs-placement="top"
                                title="{{ __('Top Speed') }}">
                                <i class="fal fa-tachometer-fast"></i>
                                <span>{{ $related_car->speed }}</span>
                              </li>
                            </ul>
                            <div class="product-price">
                              <h6 class="new-price">
                                {{ symbolPrice($related_car->price) }}
                              </h6>
                              @if (!is_null($related_car->previous_price))
                                <span class="old-price font-sm">
                                  {{ symbolPrice($related_car->previous_price) }}
                                </span>
                              @endif
                            </div>
                          </div>
                        </div><!-- product-default -->
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
              @if (!empty(showAd(3)))
                <div class="text-center mb-40">
                  {!! showAd(3) !!}
                </div>
              @endif
              <!-- Product-area end -->
            @endif
          </div>
        </div>
        <div class="col-lg-4">
          <aside class="widget-area" data-aos="fade-up">
            <!-- Widget form -->
            <div class="widget widget-form bg-light mb-30">
              @if (Session::has('success'))
                <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
              @endif
              @if (Session::has('error'))
                <div class="alert alert-success">{{ __(Session::get('error')) }}</div>
              @endif
              <h5 class="title mb-20">
                {{ __('Contact Vendor') }}
              </h5>
              @if ($car->vendor_id != 0)
                <div class="user mb-20">
                  <div class="user-img">
                    <div class="lazy-container ratio ratio-1-1 rounded-pill">
                      @if ($car->vendor->photo != null)
                        <img class="lazyload"
                          data-src="{{ asset('assets/admin/img/vendor-photo/' . $car->vendor->photo) }}"
                          alt="">
                      @else
                        <img class="lazyload" data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="">
                      @endif
                    </div>
                  </div>
                  <div class="user-info">
                    <h6 class="mb-1">
                      <a href="{{ route('frontend.vendor.details', ['username' => $car->vendor->username]) }}"
                        title="{{ $car->vendor->username }}">{{ $car->vendor->vendor_info->name }}</a>
                    </h6>
                     @if ($car->vendor->trader==0)
                     <p>{{ @$car->vendor->vendor_info->city }}. Private Seller</p>
                      @else
                      <p>Trader</p>
                      @endif
                    <!-- <a href="tel:{{ $car->vendor->phone }}">{{ $car->vendor->phone }}</a>
                    <br> -->
                    <!-- <a href="mailto:{{ $car->vendor->email }}">{{ $car->vendor->email }}</a> -->
                    <a href="">{{ @$car->vendor->vendor_info->city }}
                      <!-- {{ @$car->vendor->vendor_info->country }} --></a>
                  </div>
                </div>
              @else
                <div class="user mb-20">
                  <div class="user-img">
                    <div class="lazy-container ratio ratio-1-1 rounded-pill">
                      <img class="lazyload" data-src="{{ asset('assets/img/admins/' . $admin->image) }}"
                        alt="">
                    </div>
                  </div>
                  <div class="user-info">
                    <h6 class="mb-1"><a
                        href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ $admin->username }}</a>
                    </h6>
                    <a href="tel:123456789">{{ $admin->phone }}</a>
                    <br>
                    <!-- <a href="mailto:{{ $admin->email }}">{{ $admin->email }}</a> -->
                  </div>
                </div>
              @endif
              <input type="hidden" name="userphone" value="{{ $car->vendor->phone }}">
              <button id="userphonebutton" type="submit"
                  class="btn btn-md btn-outline w-100 showLoader mb-3">{{ __('Show Phone Number') }}</button></br>
                 <!-- <a href="mailto:{{ $admin->email }}">  <button type="submit" id="showform2" 
                  class="btn btn-md btn-primary w-100 showLoader">{{ __('Send message') }}</button></a> -->
                @if (Auth::guard('vendor')->check())  
                <button type="button" class="btn btn-md btn-primary w-100" data-toggle="modal" data-target="#exampleModal">
  {{ __('Send message') }}
</button>
 @else
 <a href="{{ route('vendor.login') }}"> <button type="submit" id="showform2ee" 
                  class="btn btn-md btn-primary w-100 showLoader">{{ __('Send message') }}</button> </a>
 @endif
              <form class="contactForm" style="display: none;" action="{{ route('frontend.car.contact_message') }}" method="POST">
                @csrf    
                <div class="row" >
                  <input type="hidden" name="car_id" value="{{ $car->id }}">

                  <div class="col-12">
                    <div class="form-group mb-20">
                      <input type="text" class="form-control" name="name"
                        placeholder="{{ __('Name') . ' *' }}" required>
                      @error('name')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group mb-20">
                      <input type="email" name="email" class="form-control"
                        placeholder="{{ __('Email Address') . ' *' }}" required>
                      @error('email')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group mb-20">
                      <input type="number" name="phone" class="form-control"
                        placeholder="{{ __('Phone Number') . ' *' }}">
                      @error('phone')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-group mb-20">
                      <textarea name="message" id="message" class="form-control" cols="30" rows="8"
                        data-error="Please enter your message" placeholder="{{ __('Message') . '...' }}"></textarea>
                      @error('message')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                </div>
                @if ($info->google_recaptcha_status == 1)
                  <div class="col-12">
                    <div class="form-group captcha mb-20">
                      {!! NoCaptcha::renderJs() !!}
                      {!! NoCaptcha::display() !!}
                      @error('g-recaptcha-response')
                        <div class="help-block with-errors text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                @endif
                <button  type="submit"
                  class="btn btn-md btn-primary w-100 showLoader">{{ __('Send message') }}</button>
              </form>
            </div>
            <!-- Widget share -->
            <div class="widget widget-share bg-light mb-30">
              <h5 class="title">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#share"
                  aria-expanded="true" aria-controls="share">
                  {{ __('Share Now') }}
                </button>
              </h5>
              <div id="share" class="collapse show">
                <div class="accordion-body">
                  <div class="social-link style-2 mb-20">
                    <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                      target="_blank"><i class="fab fa-facebook-f"></i></a>

                    <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                      target="_blank"><i class="fab fa-twitter"></i></a>

                    <a href="//wa.me/?text={{ urlencode(url()->current()) }}&amp;title="
                      target="_blank"><i class="fab fa-whatsapp"></i></a>
                      <a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title="
                      target="_blank"><i class="fab fa-linkedin-in"></i></a>
                      <a href="mailto:?subject=I wanted you to see this site&amp;body=Check out this site {{ urlencode(url()->current()) }}&amp;title="
                      target="_blank"><i class="fas fa-envelope"></i></a>
                      <a href="mailto:?subject=I wanted you to see this site&amp;body=Check out this site {{ urlencode(url()->current()) }}&amp;title="
                      target="_blank"><i class="fas fa-envelope"></i></a>
                  </div>
                </div>
              </div>
            </div>
            <!-- widget product -->
            <div class="widget widget-product bg-light mb-30">
              <h5 class="title">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#products">
                  {{ __('Latest Cars') }}
                </button>
              </h5>
              <div id="products" class="collapse show">
                <div class="accordion-body">
                  @foreach ($latest_cars as $car)
                    <div class="product-default product-inline">
                      <figure class="product-img">
                        <a href="{{ route('frontend.car.details', ['slug' => @$car->car_content->slug, 'id' => $car->id]) }}"
                          class="lazy-container ratio ratio-1-1">
                          <img class="lazyload" data-src="{{ asset('assets/admin/img/car-gallery/' . $car->feature_image) }}"
                            alt="Product">
                        </a>
                      </figure>
                      <div class="product-details">
                        <h6 class="product-title mb-1">
                          <a
                            href="{{ route('frontend.car.details', ['slug' => @$car->slug, 'id' => $car->id]) }}">{{ @$car->title }}</a>
                        </h6>
                        <div class="author mb-2">
                          @if (@$car->vendor)
                            @if ($car->vendor->photo != null)
                              <img class="lazyload blur-up"
                                data-src="{{ asset('assets/admin/img/vendor-photo/' . @$car->vendor->photo) }}"
                                alt="">
                            @else
                              <img class="lazyload blur-up" data-src="{{ asset('assets/img/blank-user.jpg') }}"
                                alt="">
                            @endif

                            <span class="font-xsm"><a href="">{{ __('By') }}
                                {{ $vendor = @$car->vendor->username }}</a></span>
                          @else
                            <img class="lazyload blur-up" data-src="{{ asset('assets/img/admins/' . $admin->image) }}"
                              alt="">
                            <span class="font-xsm"><a
                                href="{{ route('frontend.vendor.details', ['username' => $admin->username, 'admin' => 'true']) }}">{{ __('By') }}
                                {{ $admin->username }}</a></span>
                          @endif
                        </div>
                        <div class="product-price">
                          <h6 class="new-price">{{ symbolPrice($car->price) }}</h6>
                          @if (!is_null($car->previous_price))
                            <span class="old-price font-xsm">{{ symbolPrice($car->previous_price) }}</span>
                          @endif
                        </div>
                      </div>
                    </div><!-- product-default -->
                  @endforeach

                </div>
              </div>
            </div>
            <!-- Widget banner -->
            <div class="widget-banner">
              @if (!empty(showAd(1)))
                <div class="text-center mb-4">
                  {!! showAd(1) !!}
                </div>
              @endif
              @if (!empty(showAd(2)))
                <div class="text-center">
                  {!! showAd(2) !!}
                </div>
              @endif
            </div>
            <!-- Spacer -->
            <div class="pb-40"></div>
          </aside>
        </div>
      </div>
    </div>
  </div>
  </div>
  
  <!-- Listing-single-area start -->
@endsection
@section('script')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
  <script>
    var visitor_store_url = "{{ route('frontend.store_visitor') }}";
    var car_id = "{{ $car_id }}";
  </script>
  <script src="{{ asset('assets/js/store-visitor.js') }}"></script>

@endsection
