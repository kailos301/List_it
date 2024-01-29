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
    <h4 class="page-title">{{ __('Change Password') }}</h4>
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
        <a href="#">{{ __('Password Settings') }}</a>
      </li>
    </ul>
  </div> -->

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Change Password') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="ajaxEditForm" action="{{ route('vendor.update_password') }}" method="post">
                @csrf
                <div class="form-group">
                  <label>{{ __('Current Password*') }}</label>
                  <input type="password" class="form-control" name="current_password">
                  <p id="editErr_current_password" class="mt-1 mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label>{{ __('New Password*') }}</label>
                  <input type="password" class="form-control" name="new_password">
                  <p id="editErr_new_password" class="mt-1 mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label>{{ __('Confirm New Password*') }}</label>
                  <input type="password" class="form-control" name="new_password_confirmation">
                  <p id="editErr_new_password_confirmation" class="mt-1 mb-0 text-danger em"></p>
                </div>
              </form>
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

