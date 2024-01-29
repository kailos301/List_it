<!DOCTYPE html>
<html>

<head>
  {{-- required meta tags --}}
  <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  {{-- csrf-token for ajax request --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- title --}}
  <title>{{ __('Vendor') . ' | ' . $websiteInfo->website_title }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- include styles --}}
  @includeIf('vendors.partials.styles')

  {{-- additional style --}}
  @yield('style')
</head>

<body data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white2' : 'white2' }}">
  {{-- loader start --}}
  
  {{-- loader end --}}

  <div class="wrapper user-dashboard">
    {{-- top navbar area start 
    @includeIf('vendors.partials.top-navbar')--}}
    {{-- top navbar area end --}}

 {{--@extends("frontend.layouts.layout-v2")
    side navbar area start --}}
    @includeIf('vendors.partials.side-navbar')
    {{-- side navbar area end 
  @includeIf('frontend.user.side-navbar')--}}
    <div class="main-panel">
      <div class="content">
        <div class="page-inner">
          @yield('content') 
        </div>
      </div>

      {{-- footer area start --}}
       {{--@includeIf('vendors.partials.footer')
      footer area end --}}
    </div>
  </div>

  {{-- include scripts --}}
  @includeIf('vendors.partials.scripts')

  {{-- additional script --}}
  @yield('variables')
  @yield('script')
</body>

</html>
