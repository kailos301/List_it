@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Forget Password') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_forget_password }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_forget_password }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->forget_password_page_title : __('Forget Password'),
  ])


  <!-- Authentication-area start -->
  <div class="authentication-area ptb-100">
    <div class="container">
      <div class="auth-form border radius-md">
        @if (Session::has('success'))
          <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
        @endif
        @if (Session::has('warning'))
          <div class="alert alert-success">{{ __(Session::get('warning')) }}</div>
        @endif
        <form action="{{ route('user.send_forget_password_mail') }}" method="POST">
          @csrf
          <div class="title">
            <h4 class="mb-20">{{ __('Forget Password') }}</h4>
          </div>
          <div class="form-group mb-30">
            <input type="text" class="form-control" name="email" placeholder="{{ __('Email Address') }}">
            @error('email')
              <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
          </div>
          @if ($bs->google_recaptcha_status == 1)
            <div class="form-group mb-30">
              {!! NoCaptcha::renderJs() !!}
              {!! NoCaptcha::display() !!}

              @error('g-recaptcha-response')
                <p class="mt-1 text-danger">{{ $message }}</p>
              @enderror
            </div>
          @endif
          <button type="submit"
            class="btn btn-lg btn-primary radius-md w-100">{{ __('Send me a recovery link') }}</button>
        </form>
      </div>
    </div>
  </div>
  <!-- Authentication-area end -->
@endsection
