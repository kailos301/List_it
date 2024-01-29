@extends("frontend.layouts.layout-v$settings->theme_version")
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_signup_page_title ? $pageHeading->vendor_signup_page_title : __('Signup') }}
  @else
    {{ __('Signup') }}
  @endif
@endsection
@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_signup }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_signup }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->vendor_signup_page_title : __('Signup'),
  ])
  <div class="user-dashboard pt-20 pb-60">
    <div class="container">
      
  
      
  <div class="row gx-xl-5">
  
       @includeIf('vendors.partials.side-custom')
   

    
    <div class="col-md-9">
  <!-- <div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Profile') }}</a>
      </li>
    </ul>
  </div> -->

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Edit Profile') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 mx-auto">
              @php
                            $vendor_info = App\Models\VendorInfo::where('vendor_id', $vendor->id)
                                ->where('language_id', $language->id)
                                ->first();
                          @endphp
                          @foreach ($languages as $language)
              <form id="ajaxEditForm" action="{{ route('vendor.update_profile') }}" method="post">
                @csrf
                
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($vendor->photo != null)
                          <img src="{{ asset('assets/admin/img/vendor-photo/' . $vendor->photo) }}" alt="..."
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                        <p class="mt-2 mb-0 text-warning">{{ __('Image Size 80x80') }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12">
                  <div class="form-group">
                      <h5>i am a</h5>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input traderradio" type="radio" name="traderstatus" value="1" id="flexRadioDefault1" @if ($vendor->trader == 1) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault1">
                          Trader
                        </label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input traderradio" type="radio" name="traderstatus" value="0" id="flexRadioDefault2"
                        @if ($vendor->trader == 0) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault2">
                          Private Seller
                        </label>
                      </div>
                  </div>
                </div>
                
                <div class="col-lg-4 chkbox" @if ($vendor->trader == 0) style="display: none;" @endif>
                    <div class="form-group">
                      <label>{{ __('Business Name') }}</label>
                      <input type="text" value="{{ $vendor_info->business_name }}" class="form-control" name="business_name">
                      <p id="editErr_business_name" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4 chkbox" @if ($vendor->trader == 0) style="display: none;" @endif>
                    <div class="form-group">
                      <label>{{ __('VAT Number (if applicable)') }}</label>
                      <input type="text" value="{{ $vendor_info->vat_number }}" class="form-control" name="vat_number">
                      <p id="editErr_vat_number" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4 chkbox" @if ($vendor->trader == 0) style="display: none;" @endif>
                    <div class="form-group">
                      <label>{{ __('Business Address') }}</label>
                      <input type="text" value="{{ $vendor_info->business_address }}" class="form-control" name="business_address">
                      <p id="editErr_business_address" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Username*') }}</label>
                      <input type="text" value="{{ $vendor->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Email*') }}</label>
                      <input type="text" value="{{ $vendor->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <label style="margin-left:8px; font-weight: 600;">{{ __('Phone') }}</label>
                    <div class="form-group input-group">
                      
                      <input  type="tel" value="{{ $vendor->phone }}" class="form-control" name="phone" required> 
                       @if ($vendor->phone_verified == 1)
                        <button disabled   class="btn btn-outline" type="button">Verified</button>
                         @else
                        <button  id="verifyPhone" class="btn btn-outline-secondary" type="button">Verify</button>
                        @endif
                     <small>Verify your phone number and help reduce fraud and scam on Listit</small>
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>


                  

                  <div class="col-lg-12">
                    <div id="accordion" class="">
                      
                        <div class="version" style="border: 0px !important;">
                          <!-- <div class="version-header" id="heading{{ $language->id }}">
                            <h5 class="mb-0">
                              <button type="button"
                                class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : '' }}"
                                data-toggle="collapse" data-target="#collapse{{ $language->id }}"
                                aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $language->id }}">
                                {{ $language->name . __(' Language') }}
                                {{ $language->is_default == 1 ? '(Default)' : '' }}
                              </button>
                            </h5>
                          </div> -->

                          

                          <div id="collapse{{ $language->id }}"
                            class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                            <div class="version-body" >
                              <div class="row">
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Name*') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->name : '' }}"
                                      class="form-control" name="{{ $language->code }}_name" placeholder="Enter Name" required>
                                    <p id="editErr_{{ $language->code }}_name" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('County') }}</label>
                                    <input type="text"
                                      value="isle of man"
                                      class="form-control" name="{{ $language->code }}_country"
                                      placeholder="Enter County" disabled >
                                    <!-- <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->country : '' }}"
                                      class="form-control" name="{{ $language->code }}_country"
                                      placeholder="Enter County" required> -->
                                    <p id="editErr_{{ $language->code }}_country" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('City/Area')  }}</label>
                                    <select name="{{ $language->code }}_city" id="" class="form-control">
                    <option value="">Please select...</option>
                                     @foreach ($countryArea as $area)
                      <option value="{{ $area->slug }}" {{ $area->slug == $vendor_info->city ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                     </select>
                                   <!--  <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->city : '' }}"
                                      class="form-control" name="{{ $language->code }}_city" placeholder="Enter City" required> -->
                                    <p id="editErr_{{ $language->code }}_city" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <!-- <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('State') }}</label>
                                    <input type="text" value="{{ !empty($vendor_info) ? $vendor_info->state : '' }}"
                                      class="form-control" name="{{ $language->code }}_state"
                                      placeholder="Enter State" required>
                                    <p id="editErr_{{ $language->code }}_state" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text"
                                      value="{{ !empty($vendor_info) ? $vendor_info->zip_code : '' }}"
                                      class="form-control" name="{{ $language->code }}_zip_code"
                                      placeholder="Enter Zip Code" required>
                                    <p id="editErr_{{ $language->code }}_zip_code" class="mt-1 mb-0 text-danger em">
                                    </p>
                                  </div>
                                </div> -->
                                <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Address') }}</label>
                                    <input type="text" name="{{ $language->code }}_address" class="form-control" placeholder="Enter Address" value="{{ !empty($vendor_info) ? $vendor_info->address : '' }}">
                                    <p id="editErr_{{ $language->code }}_address" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div>
                                <!-- <div class="col-lg-12">
                                  <div class="form-group">
                                    <label>{{ __('Details') }}</label>
                                    <textarea name="{{ $language->code }}_details" class="form-control" rows="5" placeholder="Enter Details">{{ !empty($vendor_info) ? $vendor_info->details : '' }}</textarea>
                                    <p id="editErr_{{ $language->code }}_details" class="mt-1 mb-0 text-danger em"></p>
                                  </div>
                                </div> -->
                              </div>
                            </div>
                          </div>
                        </div>
                      
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_email_addresss == 1 ? 'checked' : '' }}
                              name="show_email_addresss" class="custom-control-input" id="show_email_addresss">
                            <label class="custom-control-label"
                              for="show_email_addresss">{{ __('Show Email Address in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_phone_number == 1 ? 'checked' : '' }}
                              name="show_phone_number" class="custom-control-input" id="show_phone_number">
                            <label class="custom-control-label"
                              for="show_phone_number">{{ __('Show Phone Number in Profile Page') }}</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="form-group">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" {{ $vendor->show_contact_form == 1 ? 'checked' : '' }}
                              name="show_contact_form" class="custom-control-input" id="show_contact_form">
                            <label class="custom-control-label"
                              for="show_contact_form">{{ __('Show  Contact Form') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </form>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="updateBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
     </div>
  </div>
  
  </div>
</div>

  @endsection
  @php
  $languages = App\Models\Language::get();
  $labels = '';
  $values = '';
  foreach ($languages as $language) {
      $label_name = $language->code . '_label[]';
      $value_name = $language->code . '_value[]';
      if ($language->direction == 1) {
          $direction = 'form-group rtl text-right';
      } else {
          $direction = 'form-group';
      }
  
      $labels .= "<div class='$direction'><input type='text' name='" . $label_name . "' class='form-control' placeholder='Label ($language->name)'></div>";
      $values .= "<div class='$direction'><input type='text' name='$value_name' class='form-control' placeholder='Value ($language->name)'></div>";
  }
@endphp

@section('script')
{{-- dropzone css --}}
<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}">

{{-- atlantis css --}}
<link rel="stylesheet" href="{{ asset('assets/css/atlantis_user.css') }}">
{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/pages/inner-pages.css') }}">
{{-- admin-main css --}}
<link rel="stylesheet" href="{{ asset('assets/css/admin-main.css') }}">
<style type="">
  #carForm .form-control {
    display: block;
    width: 80%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 14px !important;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out
}
 #carForm .btn-secondary{
  line-height: 13px !important;
 }
</style>
<script>
  'use strict';

  const baseUrl = "{{ url('/') }}";
</script>

{{-- core js files --}}



{{-- jQuery ui --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>

{{-- jQuery time-picker --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>

{{-- jQuery scrollbar --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.scrollbar.min.js') }}"></script>

{{-- bootstrap notify --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

{{-- sweet alert --}}
<script type="text/javascript" src="{{ asset('assets/js/sweet-alert.min.js') }}"></script>

{{-- bootstrap tags input --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-tagsinput.min.js') }}"></script>

{{-- bootstrap date-picker --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
{{-- js color --}}
<script type="text/javascript" src="{{ asset('assets/js/jscolor.min.js') }}"></script>

{{-- fontawesome icon picker js --}}
<script type="text/javascript" src="{{ asset('assets/js/fontawesome-iconpicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables-1.10.23.min.js') }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables.bootstrap4.min.js') }}"></script>


{{-- dropzone js --}}
<script type="text/javascript" src="{{ asset('assets/js/dropzone.min.js') }}"></script>

{{-- atlantis js --}}
<script type="text/javascript" src="{{ asset('assets/js/atlantis.js') }}"></script>

{{-- fonts and icons script --}}
<script type="text/javascript" src="{{ asset('assets/js/webfont.min.js') }}"></script>
<!-- 
@if (session()->has('success'))
  <script>
    'use strict';
    var content = {};

    content.message = '{{ session('success') }}';
    content.title = 'Success';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'success',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

@if (session()->has('warning'))
  <script>
    'use strict';
    var content = {};

    content.message = '{{ session('warning') }}';
    content.title = 'Warning!';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'warning',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif
 -->


{{-- select2 js --}}
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>

{{-- admin-main js --}}
<script type="text/javascript" src="{{ asset('assets/js/admin-main.js') }}"></script>

  <script>
    'use strict';
    var storeUrl = "{{ route('car.imagesstore') }}";
    var removeUrl = "{{ route('user.car.imagermv') }}";
    var getBrandUrl = "{{ route('user.get-car.brand.model') }}";
    const account_status = "{{ Auth::guard('vendor')->user()->status }}";
    const secret_login = "{{ Session::get('secret_login') }}";
  </script>

  
  <script>
    var labels = "{!! $labels !!}";
    var values = "{!! $values !!}";
  </script>
  
  
@endsection
