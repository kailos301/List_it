@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->faq_page_title : __('FAQ') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_faq }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_faq }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->faq_page_title : __('FAQ'),
  ])

  <!-- Faq-area start -->
  <div class="faq-area pt-100 pb-75">
    <div class="container">
      <div class="accordion" id="faqAccordion">
        <div class="row justify-content-center">
          <div class="col-lg-8" data-aos="fade-up">
            @if (count($faqs) == 0)
              <h3 class="text-center mt-3">{{ __('No Faq Found') . '!' }}</h3>
            @else
              @foreach ($faqs as $faq)
                <div class="accordion-item mb-30">
                  <h6 class="accordion-header" id="headingOne_{{ $faq->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapseOne_{{ $faq->id }}" aria-expanded="true"
                      aria-controls="collapseOne_{{ $faq->id }}">
                      {{ $faq->question }}
                    </button>
                  </h6>
                  <div id="collapseOne_{{ $faq->id }}"
                    class="accordion-collapse collapse {{ $loop->iteration == 1 ? 'show' : '' }}"
                    aria-labelledby="headingOne_{{ $faq->id }}" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      <p>
                        {{ $faq->answer }}
                      </p>
                    </div>
                  </div>
                </div>
              @endforeach
            @endif

            @if (!empty(showAd(3)))
              <div class="text-center mt-4 mb-25">
                {!! showAd(3) !!}
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Faq-area end -->
@endsection
