<!-- Footer-area start -->
@if ($footerSectionStatus == 1)
  <footer class="footer-area bg-img"
    @if (!empty($basicInfo->footer_background_image)) data-bg-image="{{ asset('assets/img/' . $basicInfo->footer_background_image) }}" @endif>
    <div class="overlay opacity-70"></div>
    <div class="footer-top pt-100 pb-70">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="footer-widget" data-aos="fade-up">
              <div class="navbar-brand">
                @if (!empty($basicInfo->footer_logo))
                  <a href="{{ route('index') }}">
                    <img src="{{ asset('assets/img/' . $basicInfo->footer_logo) }}" alt="Logo">
                  </a>
                @endif
              </div>
              <p>{{ !empty($footerInfo) ? $footerInfo->about_company : '' }}</p>
              @if (count($socialMediaInfos) > 0)
                <div class="social-link">
                  @foreach ($socialMediaInfos as $socialMediaInfo)
                    <a href="{{ $socialMediaInfo->url }}" target="_blank"><i
                        class="{{ $socialMediaInfo->icon }}"></i></a>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
          <div class="col-xl-2 col-lg-3 col-md-3 col-sm-5">
            <div class="footer-widget" data-aos="fade-up">
              <h4>{{ __('Useful Links') }}</h4>
              @if (count($quickLinkInfos) == 0)
                <h6 class="text-light">{{ __('No Link Found') . '!' }}</h6>
              @else
                <ul class="footer-links">
                  @foreach ($quickLinkInfos as $quickLinkInfo)
                    <li>
                      <a href="{{ $quickLinkInfo->url }}">{{ $quickLinkInfo->title }}</a>
                    </li>
                  @endforeach
                </ul>
              @endif
            </div>
          </div>
          <div class="col-xl-3 col-lg-4 col-md-5 col-sm-7">
            <div class="footer-widget" data-aos="fade-up">
              <h4>{{ __('Contact Us') }}</h4>
              <ul class="info-list">
                <li>
                  <i class="fal fa-map-marker-alt"></i>
                  @if (!empty($basicInfo->address))
                    <span>{{ $basicInfo->address }}</span>
                  @endif
                </li>
                @if (!empty($basicInfo->contact_number))
                  <li>
                    <i class="fal fa-phone-plus"></i>
                    <a href="tel:{{ $basicInfo->contact_number }}">{{ $basicInfo->contact_number }}</a>
                  </li>
                @endif
                @if (!empty($basicInfo->email_address))
                  <li>
                    <i class="fal fa-envelope"></i>
                    <a href="mailto:{{ $basicInfo->email_address }}">{{ $basicInfo->email_address }}</a>
                  </li>
                @endif
              </ul>
            </div>
          </div>
          <div class="col-xl-3 col-lg-6 col-md-7 col-sm-12">
            <div class="footer-widget" data-aos="fade-up">
              <h4>{{ __('Subscribe Us') }}</h4>
              <p class="lh-1 mb-20">{{ __('Stay update with us and get offer') . '!' }}</p>
              <div class="newsletter-form">
                <form id="newsletterForm" class="subscription-form" action="{{ route('store_subscriber') }}"
                  method="POST">
                  @csrf
                  <div class="form-group">
                    <input class="form-control radius-0" placeholder="{{ __('Enter email') }}" type="text"
                      name="email_id" required="" autocomplete="off">
                    <button class="btn btn-md btn-primary" type="submit">{{ __('Subscribe') }}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if (!empty($footerInfo))
      <div class="copy-right-area border-top">
        <div class="container">
          <div class="copy-right-content">
            <span>
              {!! @$footerInfo->copyright_text !!}
            </span>
          </div>
        </div>
      </div>
    @endif
  </footer>
@endif
<!-- Footer-area end-->

<!-- Go to Top -->
<div class="go-top"><i class="fal fa-angle-up"></i></div>
<!-- Go to Top -->
