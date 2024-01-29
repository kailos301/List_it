    <!-- Page title start-->
    <div class="page-title-area pt-100 bg-img {{ $basicInfo->theme_version == 2 || $basicInfo->theme_version == 3 ? 'has_header_2' : '' }}"
      @if (!empty($breadcrumb)) data-bg-image="{{ asset('assets/img/' . $breadcrumb) }}" @endif
      src="{{ asset('assets/front/imagesplaceholder.png') }}">
      <div class="container">
        <div class="content pb-20">
          <h2>
            {{ !empty($title) ? $title : '' }}
          </h2>
          <ul class="list-unstyled">
            <li class="d-inline"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="d-inline">/</li>
            <li class="d-inline active opacity-75">{{ !empty($title) ? $title : '' }}</li>
          </ul>
        </div>
      </div>
    </div>
    <!-- Page title end-->
