@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Success') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_home }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_home }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', ['breadcrumb' => $bgImg->breadcrumb, 'title' => __('Success')])

  <!-- Start Purchase Success Section -->
  <div class="purchase-message ptb-100">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="purchase-success">
            <div class="icon text-success"><i class="far fa-check-circle"></i></div>
            <h2>{{ __('Success') . '!' }}</h2>

            @if ($purchaseType == 'offline_purchase')
              <p>{{ __('Your transaction request was received and sent for review') . '.' }}</p>
              <p>{{ __('We answer every request as quickly as we can') . ', ' . __('usually within 24–48 hours') . '.' }}
              </p>
            @else
              <p>{{ __('Your transaction was successful') . '.' }}</p>
              <p>{{ __('We have sent you a mail with an invoice') . '.' }}</p>
            @endif

            <p class="mt-4">{{ __('Thank you') . '.' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Purchase Success Section -->
@endsection
