@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@php
  $title = strlen($details->title) > 40 ? mb_substr($details->title, 0, 40, 'UTF-8') . '...' : $details->title;
@endphp
@section('pageHeading')
  @if (!empty($title))
    {{ $title ? $title : $pageHeading->blog_page_title }}
  @endif
@endsection

@section('metaKeywords')
  {{ $details->meta_keywords }}
@endsection

@section('metaDescription')
  {{ $details->meta_description }}
@endsection

@section('content')
  <!-- Page title start-->
  <div
    class="page-title-area ptb-100 bg-img {{ $basicInfo->theme_version == 2 || $basicInfo->theme_version == 3 ? 'has_header_2' : '' }}"
    @if (!empty($bgImg)) data-bg-image="{{ asset('assets/img/' . $bgImg->breadcrumb) }}" @endif
    src="{{ asset('assets/front/imagesplaceholder.png') }}">
    <div class="container">
      <div class="content">
        <h2>
          {{ $title }}
        </h2>
        <ul class="list-unstyled">
          <li class="d-inline"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
          <li class="d-inline">/</li>
          <li class="d-inline active opacity-75">{{ __('Blog Details') }}</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Page title end-->

  <!--====== Start Blog Section ======-->
  <div class="blog-details-area pt-100 pb-60">
    <div class="container">
      <div class="row justify-content-center gx-xl-5">
        <div class="col-lg-8">
          <div class="blog-description mb-40">
            <article class="item-single">
              <div class="image radius-md">
                <div class="lazy-container ratio ratio-16-9">
                  <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                    data-src="{{ asset('assets/img/blogs/' . $details->image) }}" alt="Blog Image">
                </div>
              </div>
              <div class="content">
                <ul class="info-list">
                  <li><i class="fal fa-user"></i>{{ __('Admin') }}</li>
                  <li><i class="fal fa-calendar"></i>{{ date_format($details->created_at, 'M d, Y') }}</li>
                  <li><i class="fal fa-tag"></i>{{ $details->categoryName }}</li>
                </ul>
                <h4 class="title">{{ $details->title }}</h4>
                <div>{!! replaceBaseUrl($details->content, 'summernote') !!}</div>
              </div>
            </article>
            @if (!empty(showAd(3)))
              <div class="text-center mt-4">
                {!! showAd(3) !!}
              </div>
            @endif
          </div>
          <div class="comments mb-40">
            <h4 class="mb-20">{{ __('Comments') }}</h4>
            @if ($disqusInfo->disqus_status == 1)
              <div id="disqus_thread"></div>
            @endif
          </div>
        </div>
        <div class="col-lg-4">
          <aside class="widget-area mb-10">
            <div class="widget widget-search radius-md bg-light mb-30">
              <h4 class="title mb-15">{{ __('Search Posts') }}</h4>
              <form class="search-form radius-md" action="{{ route('blog') }}" method="GET">
                <input type="search" class="search-input"placeholder="{{ __('Search By Title') }}" name="title"
                  value="{{ !empty(request()->input('title')) ? request()->input('title') : '' }}">

                @if (!empty(request()->input('category')))
                  <input type="hidden" name="category" value="{{ request()->input('category') }}">
                @endif
                <button class="btn-search" type="submit">
                  <i class="far fa-search"></i>
                </button>
              </form>
            </div>
            <div class="widget widget-post radius-md bg-light mb-30">
              <h4 class="title mb-15">{{ __('Recent Posts') }}</h4>

              @foreach ($recent_blogs as $blog)
                <article class="article-item mb-30">
                  <div class="image">
                    <a href="blog-details.html" class="lazy-container ratio ratio-1-1">
                      <img class="lazyload" src="{{ asset('assets/front/images/placeholder.png') }}"
                        data-src="{{ asset('assets/img/blogs/' . $blog->image) }}" alt="Blog Image">
                    </a>
                  </div>
                  <div class="content">
                    <h6>
                      <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                        {{ strlen($blog->title) > 40 ? mb_substr($blog->title, 0, 40, 'UTF-8') . '...' : $blog->title }}
                      </a>
                    </h6>
                    <ul class="info-list">
                      <li><i class="fal fa-user"></i>{{ __('Admin') }}</li>
                      <li><i class="fal fa-calendar"></i>{{ date_format($details->created_at, 'M d, Y') }}</li>
                    </ul>
                  </div>
                </article>
              @endforeach

            </div>
            <div class="widget widget-blog-categories radius-md bg-light mb-30">
              <h4 class="title mb-15">{{ __('Categories') }}</h4>
              <ul class="list-unstyled m-0">
                @foreach ($categories as $category)
                  <li class="d-flex align-items-center justify-content-between">
                    <a href="{{ route('blog', ['category' => $category->slug]) }}"><i class="fal fa-folder"></i>
                      {{ $category->name }}</a>
                    <span class="tqy">({{ $category->blogCount }})</span>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="widget widget-social-link radius-md bg-light mb-30">
              <h4 class="title mb-15">{{ __('Share') }}</h4>
              <div class="social-link">
                <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="facebook"
                  target="_blank">
                  <i class="fab fa-facebook-f"></i>{{ __('Share') }}
                </a>
                <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"
                  class="twitter" target="_blank">
                  <i class="fab fa-twitter"></i>{{ __('Tweet') }}
                </a>
                <a href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"
                  class="linkedin" target="_blank">
                  <i class="fab fa-linkedin-in"></i>{{ __('Share') }}
                </a>
              </div>
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
  <!--====== End Blog Section ======-->
@endsection
@section('script')
  @if ($disqusInfo->disqus_status == 1)
    <script>
      'use strict';
      const shortName = '{{ $disqusInfo->disqus_short_name }}';
    </script>
    <script src="{{ asset('assets/front/js/blog.js') }}"></script>
  @endif
@endsection
