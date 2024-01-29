@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->blog_page_title }}
  @else
    {{ __('Posts') }}
  @endif
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_blog }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_blog }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->blog_page_title : __('Posts'),
  ])

  <!--====== Start Blog Section ======-->
  <section class="blog-area blog-1 ptb-100">
    <div class="container">
      <div class="row">
        <div class="col-12">
          @if (count($blogs) == 0)
            <h3 class="text-center mt-3">{{ __('No Post Found') . '!' }}</h3>
          @else
            <div class="row gx-xl-5 justify-content-center">
              @foreach ($blogs as $blog)
                <div class="col-md-6 col-lg-4" data-aos="fade-up">
                  <article class="card mb-30">
                    <div class="card-img fancy radius-0 mb-25">
                      <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                        class="lazy-container ratio ratio-5-4">
                        <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                          data-src="{{ asset('assets/img/blogs/' . $blog->image) }}" alt="Blog Image">
                      </a>
                    </div>
                    <div class="content">
                      <h4 class="card-title">
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                          {{ @$blog->title }}
                        </a>
                      </h4>
                      <p class="card-text">
                        {{ strlen(strip_tags($blog->content)) > 90 ? mb_substr(strip_tags($blog->content), 0, 90, 'UTF-8') . '...' : $blog->content }}
                      </p>
                      <div class="mt-20">
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}"
                          class="btn btn-lg btn-primary">{{ __('Read More') }}</a>
                      </div>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>
          @endif
          @if (!empty(showAd(3)))
            <div class="text-center mt-4">
              {!! showAd(3) !!}
            </div>
          @endif
        </div>
        <div class="pagination mt-20 justify-content-center" data-aos="fade-up">
          {{ $blogs->links() }}
        </div>
      </div>
    </div>
  </section>
  <!--====== End Blog Section ======-->
@endsection
