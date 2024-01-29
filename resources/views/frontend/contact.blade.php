@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")

@section('pageHeading')
  {{ !empty($pageHeading) ? $pageHeading->contact_page_title : __('Contact') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_contact }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_contact }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->contact_page_title : __('Contact'),
  ])

  <!--====================================================-->
  <!--============== Start Contact Section ===============-->
  <!--====================================================-->
  <div class="contact-area ptb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6">
          <div class="card mb-30 color-1" data-aos="fade-up" data-aos-delay="100">
            <div class="icon">
              <i class="fal fa-phone-plus"></i>
            </div>
            <div class="card-text">
              @if (!empty($info->contact_number))
                <p><a href="tel:{{ $info->contact_number }}">{{ $info->contact_number }}</a></p>
              @endif
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card mb-30 color-2" data-aos="fade-up" data-aos-delay="200">
            <div class="icon">
              <i class="fal fa-envelope"></i>
            </div>
            <div class="card-text">
              @if (!empty($info->address))
                <p><a href="javascript:void(0)">{{ $info->address }}</a></p>
              @endif
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="card mb-30 color-3" data-aos="fade-up" data-aos-delay="300">
            <div class="icon">
              <i class="fal fa-map-marker-alt"></i>
            </div>
            <div class="card-text">
              @if (!empty($info->email_address))
                <p><a href="mailTo:{{ $info->email_address }}">{{ $info->email_address }}</a></p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="pb-70"></div>

      <div class="row gx-xl-5">
        <div class="col-lg-6 mb-30" data-aos="fade-left">
          @if (!empty($info->latitude) && !empty($info->longitude))
            <iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
              src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $info->latitude }},%20{{ $info->longitude }}+({{ $websiteInfo->website_title }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
          @endif
        </div>
        <div class="col-lg-6 mb-30 order-lg-first" data-aos="fade-right">
          @if (Session::has('success'))
            <div class="alert alert-success">{{ __(Session::get('success')) }}</div>
          @endif
          @if (Session::has('error'))
            <div class="alert alert-success">{{ __(Session::get('error')) }}</div>
          @endif
          <form id="contactForm" action="{{ route('contact.send_mail') }}" method="post">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-20">
                  <input type="text" name="name" class="form-control" id="name"
                    placeholder="{{ __('Enter Your Full Name') }}" />
                  @error('name')
                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group mb-20">
                  <input type="email" name="email" class="form-control" id="email" required
                    data-error="Enter your email" placeholder="{{ __('Enter Your Email') }}" />
                  @error('email')
                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group mb-20">
                  <input type="text" name="subject" class="form-control" id="" required
                    placeholder="{{ __('Enter Email Subject') }}" />
                  @error('subject')
                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group mb-20">
                  <textarea name="message" id="message" class="form-control" cols="30" rows="8" required
                    placeholder="{{ __('Write Your Message') }}"></textarea>
                  @error('message')
                    <div class="help-block with-errors text-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              @if ($info->google_recaptcha_status == 1)
                <div class="col-md-12">
                  <div class="form-group mb-20">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                      <div class="help-block with-errors text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              @endif

              <div class="col-md-12">
                <button type="submit" class="btn btn-lg btn-primary"
                  title="{{ __('Send message') }}">{{ __('Send') }}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="pb-70"></div>
    </div>

    @if (!empty(showAd(3)))
      <div class="text-center">
        {!! showAd(3) !!}
      </div>
    @endif
  </div>
  <!--============ End Contact Section =============-->
@endsection
