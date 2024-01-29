@extends("frontend.layouts.layout-v$settings->theme_version")
@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->vendor_login_page_title : __('Login') }}
@endsection
@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_login }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_login }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->vendor_login_page_title : __('Login'),
  ])
  <!-- Authentication-area start -->
  <div class="authentication-area ptb-100">
    <div class="container">
      <div class="auth-form border radius-md">
        @if (Session::has('success'))
          <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
        @endif
        @if (Session::has('error'))
          <div class="alert alert-danger">{{ __(Session::get('error')) }}</div>
        @endif
        <form action="{{ route('vendor.login_submit') }}" method="POST">
          @csrf
          <div class="title">
            <h4 class="mb-20">{{ __('Login') }}</h4>
          </div>
          <div class="form-group mb-30">
            <input type="text" class="form-control" name="username" placeholder="{{ __('Username') }}" required>
            @error('username')
              <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group mb-30">
            <input type="password" class="form-control" name="password" placeholder="{{ __('Password') }}" required>
            @error('password')
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
          <div class="row align-items-center mb-20">
            <div class="col-4 col-xs-12">
              <div class="link">
                <a href="{{ route('vendor.forget.password') }}">{{ __('Forgot password') . '?' }}</a>
              </div>
            </div>
            <div class="col-8 col-xs-12">
              <div class="link go-signup">
                {{ __("don't have an account") . '?' }} <a
                  href="{{ route('vendor.signup') }}">{{ __('Click Here') }}</a>
                {{ __('to Signup') }}
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-lg btn-primary radius-md w-100"> {{ __('Login') }} </button>
        </form>
      </div>
    </div>
  </div>
  <!-- Authentication-area end -->
@endsection
