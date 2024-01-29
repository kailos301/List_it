@if ($basicInfo->preloader_status == 11)
  <!-- Preloader start -->
  <div id="preLoader">
    <img src="{{ asset('assets/img/' . $basicInfo->preloader) }}" alt="">
  </div>
@endif
<!-- Preloader end -->
<div class="request-loader">
  <img src="{{ asset('assets/img/loader.gif') }}" alt="loader">
</div>

<!-- Header-area start -->
<header class="header-area header-2 is-sticky" data-aos="slide-down">
  <!-- Start mobile menu -->
  <div class="mobile-menu">
    <div class="container">
      <div class="mobile-menu-wrapper"></div>
    </div>
  </div>
  <!-- End mobile menu -->

  <div class="main-responsive-nav">
    <div class="container">
      <!-- Mobile Logo -->
      <div class="logo">
        @if (!empty($websiteInfo->logo))
          <a href="{{ route('index') }}">
            <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo">
          </a>
        @endif
      </div>
      <!-- Menu toggle button -->
      <button class="menu-toggler" type="button">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </div>

  <div class="main-navbar">
    <div class="container custom-container">
      <nav class="navbar navbar-expand-lg">
        <!-- Logo -->
        @if (!empty($websiteInfo->logo))
          <a href="{{ route('index') }}" class="navbar-brand">
            <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo">
          </a>
        @endif

        <!-- Navigation items -->
        <div class="collapse navbar-collapse">
          @php $menuDatas = json_decode($menuInfos); @endphp
          <ul id="mainMenu" class="navbar-nav mobile-item mx-auto">
            @foreach ($menuDatas as $menuData)
              @php $href = get_href($menuData); @endphp
              @if (!property_exists($menuData, 'children'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ $href }}">{{ $menuData->text }}</a>
                </li>
              @else
                <li class="nav-item">
                  <a class="nav-link toggle" href="{{ $href }}">{{ $menuData->text }}<i
                      class="fal fa-plus"></i></a>
                  <ul class="menu-dropdown">
                    @php $childMenuDatas = $menuData->children; @endphp
                    @foreach ($childMenuDatas as $childMenuData)
                      @php $child_href = get_href($childMenuData); @endphp
                      <li class="nav-item">
                        <a class="nav-link" href="{{ $child_href }}">{{ $childMenuData->text }}</a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @endif
            @endforeach
          </ul>
        </div>

        <div class="more-option mobile-item">
         <!--  <div class="item item-language">
            <div class="language">
              <form action="{{ route('change_language') }}" method="GET">
                <select class="nice-select" name="lang_code" onchange="this.form.submit()">
                  @foreach ($allLanguageInfos as $languageInfo)
                    <option value="{{ $languageInfo->code }}"
                      {{ $languageInfo->code == $currentLanguageInfo->code ? 'selected' : '' }}>
                      {{ $languageInfo->name }}
                    </option>
                  @endforeach
                </select>
              </form>
            </div>
          </div> -->
          
          <div class="item">
            <div class="dropdown">
             <a class="dropdown-item" href="{{ route('vendor.cars_management.create_car') }}"> <button class="btn btn-primary btn-md " type="button" 
                aria-expanded="false">
                {{ __('Create Ad') }}
                <!-- @if (!Auth::guard('web')->check())
                  {{ __('Seller') }}
                @else
                  {{ Auth::guard('web')->user()->username }}
                @endif -->
              </button> </a>
               <!-- <ul class="dropdown-menu radius-sm text-transform-normal">
               
                  <li><a class="dropdown-item" target="_blank" href="{{ route('vendor.cars_management.create_car') }}">{{ __('Create an Add') }}</a></li>
              
              </ul>  -->
            </div>
          </div> 
          <div class="item">
            <div class="dropdown">
              <button class="btn btn-outline btn-md radius-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                @if (!Auth::guard('vendor')->check())
                  {{ __('Login/Sign up') }}
                @else
                  {{ Auth::guard('vendor')->user()->username }}
                @endif
              </button>
              <ul class="dropdown-menu radius-sm text-transform-normal">
                @if (!Auth::guard('vendor')->check())
                  <li><a class="dropdown-item" href="{{ route('vendor.login') }}">{{ __('Login') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('vendor.signup') }}">{{ __('Signup') }}</a></li>
                @else
                  <li><a class="dropdown-item" href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('vendor.logout') }}">{{ __('Logout') }}</a></li>
                @endif
              </ul> 
            </div>
          </div>
          <!-- <div class="item">
            <div class="dropdown">
              <button class="btn btn-outline btn-md radius-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                @if (!Auth::guard('web')->check())
                  {{ __('Seller') }}
                @else
                  {{ Auth::guard('web')->user()->username }}
                @endif
              </button>
              <ul class="dropdown-menu radius-sm text-transform-normal">
                @if (!Auth::guard('web')->check())
                  <li><a class="dropdown-item" href="{{ route('user.login') }}">{{ __('Login') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('user.signup') }}">{{ __('Signup') }}</a></li>
                @else
                  <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('user.logout') }}">{{ __('Logout') }}</a></li>
                @endif
              </ul> 
            </div>
          </div> -->
          
        </div>
      </nav>
    </div>
  </div>
</header>
<!-- Header-area end -->
