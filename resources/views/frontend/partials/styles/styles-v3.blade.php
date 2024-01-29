<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/bootstrap.min.css') }}">
<!-- Data Tables CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/datatables.min.css') }}">
<!-- Fontawesome Icon CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/fonts/fontawesome/css/all.min.css') }}">
<!-- Icomoon Icon CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/fonts/icomoon/style.css') }}">
<!-- NoUi Range Slider -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/nouislider.min.css') }}">
<!-- Magnific Popup CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/magnific-popup.min.css') }}">
<!-- Swiper Slider -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/swiper-bundle.min.css') }}">
<!-- Nice Select -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/nice-select.css') }}">
<!-- AOS Animation CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/aos.min.css') }}">
<!-- Animate CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/vendors/animate.min.css') }}">
{{-- whatsapp css --}}
<link rel="stylesheet" href="{{ asset('assets/css/floating-whatsapp.css') }}">
{{-- toast css --}}
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
{{-- tinymce-content custom css --}}
<link rel="stylesheet" href="{{ asset('assets/front/css/tinymce-content.css') }}">

<!-- Main Style CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">
<!-- Responsive CSS -->
<link rel="stylesheet" href="{{ asset('assets/front/css/responsive.css') }}">

{{-- rtl css are goes here --}}
@if ($currentLanguageInfo->direction == 1)
  <link rel="stylesheet" href="{{ asset('assets/front/css/rtl.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/front/css/rtl-responsive.css') }}">
@endif

@yield('style')
