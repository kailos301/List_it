<div class="main-header">
  <!-- Logo Header Start -->
  <div class="logo-header" data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white' : 'dark2' }}">
    @if (!empty($websiteInfo->logo))
      <a href="{{ route('index') }}" class="logo" target="_blank">
        <img src="{{ asset('assets/img/' . $websiteInfo->logo) }}" alt="logo" class="navbar-brand" width="">
      </a>
    @endif


    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon">
        <i class="icon-menu"></i>
      </span>
    </button>
    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>

    <div class="nav-toggle">
      <button class="btn btn-toggle toggle-sidebar">
        <i class="icon-menu"></i>
      </button>
    </div>
  </div>
  <!-- Logo Header End -->

  <!-- Navbar Header Start -->
  <nav class="navbar navbar-header navbar-expand-lg"
    data-background-color="{{ $settings->admin_theme_version == 'light' ? 'white2' : 'dark' }}">
    <div class="container-fluid">
      <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
       <!--  <form action="{{ route('admin.change_theme') }}" class="form-inline mr-3" method="GET">
          <div class="form-group">
            <div class="selectgroup selectgroup-secondary selectgroup-pills">
              <label class="selectgroup-item">
                <input type="radio" name="admin_theme_version" value="light" class="selectgroup-input"
                  {{ $settings->admin_theme_version == 'light' ? 'checked' : '' }} onchange="this.form.submit()">
                <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-sun"></i></span>
              </label>

              <label class="selectgroup-item">
                <input type="radio" name="admin_theme_version" value="dark" class="selectgroup-input"
                  {{ $settings->admin_theme_version == 'dark' ? 'checked' : '' }} onchange="this.form.submit()">
                <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-moon"></i></span>
              </label>
            </div>
          </div>
        </form> -->

        <li class="nav-item dropdown hidden-caret">
          <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
            <div class="avatar-sm">
              @if (Auth::guard('admin')->user()->image != null)
                <img src="{{ asset('assets/img/admins/' . Auth::guard('admin')->user()->image) }}" alt="Admin Image"
                  class="avatar-img rounded-circle">
              @else
                <img src="{{ asset('assets/img/blank_user.jpg') }}" alt="" class="avatar-img rounded-circle">
              @endif
            </div>
          </a>

          <ul class="dropdown-menu dropdown-user animated fadeIn">
            <div class="dropdown-user-scroll scrollbar-outer">
              <li>
                <div class="user-box">
                  <div class="avatar-lg">
                    @if (Auth::guard('admin')->user()->image != null)
                      <img src="{{ asset('assets/img/admins/' . Auth::guard('admin')->user()->image) }}"
                        alt="Admin Image" class="avatar-img rounded-circle">
                    @else
                      <img src="{{ asset('assets/img/blank_user.jpg') }}" alt=""
                        class="avatar-img rounded-circle">
                    @endif
                  </div>

                  <div class="u-text">
                    <h4>
                      {{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name }}
                    </h4>
                    <p class="text-muted">{{ Auth::guard('admin')->user()->email }}</p>
                  </div>
                </div>
              </li>

              <li>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.edit_profile') }}">
                  {{ __('Edit Profile') }}
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.change_password') }}">
                  {{ __('Change Password') }}
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.logout') }}">
                  {{ __('Logout') }}
                </a>
              </li>
            </div>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Navbar Header End -->
</div>
