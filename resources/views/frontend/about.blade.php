@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->about_us_title : __('About Us') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_about_page }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_about_page }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->about_us_title : __('About Us'),
  ])

  <!-- counter area start -->
  @if ($secInfo->counter_section_status == 1)
    <section class="choose-area pt-100 pb-60">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7" data-aos="slide-{{ $currentLanguageInfo->direction == 1 ? 'left' : 'right' }}"
            data-aos-duration="1000">
            <div class="image mb-40">
              <img class="lazyload blur-up"
                @if (!empty($counterSectionImage)) data-src="{{ asset('assets/img/' . $counterSectionImage) }}" @endif
                alt="Image">
            </div>
          </div>
          <div class="col-lg-5" data-aos="fade-up">
            <div class="content-title mb-40">
              <h2 class="title mb-20">
                {{ @$counterSectionInfo->title }}
              </h2>
              <p>{{ @$counterSectionInfo->subtitle }}</p>
              <div class="info-list mt-30">
                <div class="row align-items-center pb-30">
                  @foreach ($counters as $counter)
                    <div class="col-sm-6">
                      <div class="card mt-30">
                        <div class="d-flex align-items-center">
                          <div class="card-icon"><i class="{{ $counter->icon }}"></i></div>
                          <div class="card-content">
                            <span class="h3 mb-1"><span class="counter">{{ $counter->amount }}</span>+</span>
                            <p class="card-text">{{ $counter->title }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="shape">
        <img class="lazyload blur-up" data-src="{{ asset('assets/front/images/dark-bg.png') }}" alt="Image">
      </div>
    </section>
  @endif
  <!-- counter area end -->

  <!-- Steps-area start -->
  @if ($secInfo->work_process_section_status == 1)
    <section class="steps-area pb-70">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="section-title title-center mb-50" data-aos="fade-up">
              <span class="subtitle mb-10">{{ @$workProcessSecInfo->title }}</span>
              <h2 class="title">{{ @$workProcessSecInfo->subtitle }}</span></h2>
            </div>
          </div>
          <div class="col-12">
            <div class="row">
              @foreach ($processes as $process)
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                  <div class="card align-items-center text-center radius-md p-25 mb-30">
                    <div class="card-icon radius-md mb-25">
                      <i class="{{ $process->icon }}"></i>
                    </div>
                    <div class="card-content">
                      <h4 class="card-title mb-20">{{ $process->title }}</h4>
                      <p class="card-text lc-3">
                        {{ $process->text }}
                      </p>
                    </div>
                    <span class="text-stroke h2">0{{ $loop->iteration }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        @if (!empty(showAd(3)))
          <div class="text-center mt-4">
            {!! showAd(3) !!}
          </div>
        @endif
      </div>
    </section>
  @endif
  <!-- Steps-area end -->


  <!-- Testimonial-area start -->
  @if ($secInfo->testimonial_section_status == 1)
    <section class="testimonial-area testimonial-1 pb-100">
      <div class="container">
        <div class="content">
          <div class="row gx-xl-5 align-items-center">
            <div class="col-md-6 col-lg-5 border-end border-sm-0" data-aos="fade-up">
              <h2 class="title mb-20">{{ !empty($testimonialSecInfo->title) ? $testimonialSecInfo->title : '' }}</h2>
            </div>
            <div class="col-md-6 col-lg-5" data-aos="fade-up" data-aos-delay="100">
              <p class="text mb-20">{{ !empty($testimonialSecInfo->subtitle) ? $testimonialSecInfo->subtitle : '' }}
              </p>
            </div>
            <div class="col-lg-2" data-aos="fade-up" data-aos-delay="150">
              <!-- Slider navigation buttons -->
              <div class="slider-navigation text-end mb-20">
                <button type="button" title="Slide prev" class="slider-btn radius-0" id="testimonial-slider-btn-prev">
                  <i class="fal fa-angle-left"></i>
                </button>
                <button type="button" title="Slide next" class="slider-btn radius-0" id="testimonial-slider-btn-next">
                  <i class="fal fa-angle-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="row align-items-center" data-aos="fade-up">
          <div class="col-lg-9">
            <div class="swiper pt-30 mb-15" id="testimonial-slider-1">
              <div class="swiper-wrapper">
                @foreach ($testimonials as $testimonial)
                  <div class="swiper-slide pb-25" data-aos="fade-up">
                    <div class="slider-item">
                      <div class="client mb-25">
                        <div class="client-info d-flex align-items-center">
                          <div class="client-img">
                            <div class="lazy-container rounded-pill ratio ratio-1-1">
                              @if (is_null($testimonial->image))
                                <img data-src="{{ asset('assets/img/profile.jpg') }}" alt="image" class="lazyload">
                              @else
                                <img class="lazyload" data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                                  alt="Person Image">
                              @endif
                            </div>
                          </div>
                          <div class="content">
                            <h6 class="name">{{ $testimonial->name }}</h6>
                            <span class="designation">{{ $testimonial->occupation }}</span>
                          </div>
                        </div>
                        <div class="ratings">
                          <div class="rate">
                            <div class="rating-icon" style="width: {{ $testimonial->rating * 20 }}%"></div>
                          </div>
                          <span class="ratings-total">{{ $testimonial->rating }} {{ __('star') }}</span>
                        </div>
                      </div>
                      <div class="quote mb-25">
                        <span class="icon"><i class="fal fa-quote-right"></i></span>
                        <p class="text mb-0">
                          {{ $testimonial->comment }}
                        </p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="clients d-flex align-items-center" data-aos="fade-up">
              <div class="client-img">
                @foreach ($testimonials as $testimonial)
                  @if ($loop->iteration < 5)
                    @if (is_null($testimonial->image))
                      <img data-src="{{ asset('assets/img/profile.jpg') }}" alt="image" class="lazyload">
                    @else
                      <img class="lazyload" data-src="{{ asset('assets/img/clients/' . $testimonial->image) }}"
                        alt="Person Image">
                    @endif
                  @endif
                @endforeach
              </div>
              <span>{{ @$testimonialSecInfo->clients }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="img-content d-none d-lg-block" data-aos="fade-left">
        <div class="img">
          <img class="lazyload blur-up" data-src="{{ asset('assets/img/' . $testimonialSecImage) }}" alt="Image">
        </div>
      </div>
      <!-- Shape -->
      <div class="shape"></div>
    </section>
    @if (!empty(showAd(3)))
      <div class="text-center mt-4">
        {!! showAd(3) !!}
      </div>
      {{-- Spacer --}}
      <div class="pb-100"></div>
    @endif
  @endif
  <!-- Testimonial-area end -->


@endsection
