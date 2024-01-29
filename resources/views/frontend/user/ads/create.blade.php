@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Dashboard') }}
@endsection


@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => !empty($bgImg) ? $bgImg->breadcrumb : '',
      'title' => !empty($pageHeading) ? $pageHeading->dashboard_page_title : __('Dashboard'),
  ])


  <!--====== Start Dashboard Section ======-->
  <div class="user-dashboard pt-20 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('frontend.user.side-navbar')
        <div class="col-lg-9">
          
               @includeIf('frontend.user.ads.adspartial')
            

          
        </div>
      </div>
    </div>
  </div>
  <!--====== End Dashboard Section ======-->
@endsection

