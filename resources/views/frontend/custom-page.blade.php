@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
  {{ $pageInfo->title }}
@endsection

@section('metaKeywords')
  {{ $pageInfo->meta_keywords }}
@endsection

@section('metaDescription')
  {{ $pageInfo->meta_description }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => $pageInfo->title,
  ])

  <!--====== Start FAQ Section ======-->
  <section class="blog-area blog-1 ptb-100">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="tinymce-content">
            {!! $pageInfo->content !!}
          </div>
        </div>
      </div>
      @if (!empty(showAd(3)))
        <div class="text-center">
          {!! showAd(3) !!}
        </div>
      @endif
    </div>
  </section>
  <!--====== End FAQ Section ======-->
@endsection
