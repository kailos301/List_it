@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->vendor_page_title : __('Vendors') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_page }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_page }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->vendor_page_title : __('Vendors'),
  ])

  <!-- Vendor-area start -->
  <div class="vendor-area pt-100 pb-75">
    <div class="container">
      <div class="product-sort-area pb-20" data-aos="fade-up">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h4 class="mb-20">{{ count($vendors) }}
              {{ count($vendors) > 1 ? __('Vendors') : __('Vendor') }} {{ __('Found') }}</h4>
          </div>
          <div class="col-lg-6">
            <form action="{{ route('frontend.vendors') }}" method="GET">
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group icon-start mb-20">
                    <span class="icon color-primary">
                      <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="name" value="{{ request()->input('name') }}"
                      class="form-control radius-0 border-primary" placeholder="{{ __('Vendor name/username') }}">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group icon-start mb-20">
                    <span class="icon color-primary">
                      <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <input type="text" name="location" class="form-control radius-0 border-primary"
                      value="{{ request()->input('location') }}" placeholder="{{ __('Enter location') }}">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group icon-start">
                    <button type="submit" class="btn btn-icon bg-primary color-white w-100">
                      <i class="fal fa-search"></i>
                      <span class="d-inline-block d-md-none">&nbsp;{{ __('Search') }}</span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Products -->
      <div class="row">
        @foreach ($vendors as $vendor)
          <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up">
            <div class="card mb-25 shadow-md radius-0">
              <div class="card-info-area bg-primary-light p-20">
                <div class="card-img">
                  <a href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}"
                    class="lazy-container ratio ratio-1-1 rounded-circle">
                    @if ($vendor->photo != null)
                      <img class="lazyload" src="assets/images/placeholder.png"
                        data-src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}" alt="Vendor">
                    @else
                      <img class="lazyload" src="assets/images/placeholder.png"
                        data-src="{{ asset('assets/img/blank-user.jpg') }}" alt="Vendor">
                    @endif
                  </a>
                </div>
                <div class="card-info">
                  <h6 class="title mb-1">
                    <a href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}" target="_self"
                      title="Vendor">{{ $vendor->username }}
                    </a>
                  </h6>
                  <div>
                    @php
                      $vendor_info = App\Models\VendorInfo::where([['vendor_id', $vendor->vendorId], ['language_id', $language->id]])
                          ->select('name')
                          ->first();
                    @endphp
                    <span class="text-muted mb-1"><a
                        href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}">{{ @$vendor_info->name }}</a>
                    </span>
                  </div>
                  <div>
                    @php
                      $total_cars = App\Models\Car::where('vendor_id', $vendor->vendorId)
                          ->get()
                          ->count();
                    @endphp
                    <span>{{ __('Total Cars') . ':' }}&nbsp;</span>
                    <span class="color-dark">{{ $total_cars }}</span>
                  </div>
                </div>
              </div>
              <div class="card-details p-20">
                <ul class="card-list list-unstyled">
                  @php
                    $vendorInfo = App\Models\VendorInfo::where([['vendor_id', $vendor->vendorId], ['language_id', $language->id]])->first();
                  @endphp
                  @if ($vendorInfo)
                    @if ($vendorInfo->address != null)
                      <li class="icon-start font-sm">
                        <i class="fal fa-map-marker-alt"></i>
                        <span>{{ $vendorInfo->address }}</span>
                      </li>
                    @endif
                  @endif

                  @if ($vendor->show_phone_number == 1)
                    @if (!is_null($vendor->phone))
                      <li class="icon-start font-sm">
                        <i class="fal fa-phone-plus"></i>
                        <span><a href="tel:{{ $vendor->phone }}">{{ $vendor->phone }}</a></span>
                      </li>
                    @endif
                  @endif

                 <!--  @if ($vendor->show_email_addresss == 1)
                    <li class="icon-start font-sm">
                      <i class="fal fa-envelope"></i>
                      <span><a href="mailto:{{ $vendor->email }}">{{ $vendor->email }}</a></span>
                    </li>
                  @endif -->

                </ul>
                <div class="btn-groups d-flex gap-3 flex-wrap mt-20">
                  <a href="{{ route('frontend.vendor.details', ['username' => $vendor->username]) }}"
                    class="btn btn-md btn-primary w-100" title="{{ __('Visit Profile') }}"
                    target="_self">{{ __('Visit Profile') }}</a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="pagination mt-20 mb-25 justify-content-center" data-aos="fade-up">
        {{ $vendors->links() }}
      </div>

      @if (!empty(showAd(3)))
        <div class="text-center mt-4">
          {!! showAd(3) !!}
        </div>
      @endif
    </div>
  </div>
  <!-- Vendor-area end -->
@endsection
