@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Change Password') }}
@endsection


@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->change_password_page_title : __('Change Password'),
  ])


  <!--====== Start Dashboard Section ======-->
  <div class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('frontend.user.side-navbar')
        <div class="col-lg-9">
          <div class="user-profile-details mb-40">
            <div class="account-info radius-md">
              <div class="title">
                <h4>{{ __('Change Password') }}</h4>
              </div>
              <div class="edit-info-area mt-30">
                @if (Session::has('success'))
                  <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                <form action="{{ route('user.update_password') }}" method="POST">
                  @csrf
                  <div class="form-group mb-20">
                    <input type="password" class="form-control" placeholder="{{ __('Current Password') }}"
                      name="current_password" required>
                    @error('current_password')
                      <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group mb-20">
                    <input type="password" class="form-control" placeholder="{{ __('New Password') }}" name="new_password"
                      required>
                    @error('new_password')
                      <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group mb-20">
                    <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}"
                      name="new_password_confirmation" required>
                  </div>
                  <div class="mb-15">
                    <div class="form-button">
                      <button type="submit" class="btn btn-lg btn-primary">{{ __('Submit') }}</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--====== End Dashboard Section ======-->
@endsection
