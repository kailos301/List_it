@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Create a Support Ticket') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading)
          ? $pageHeading->support_ticket_create_page_title
          : __('Create a Support Ticket'),
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
                <h4>{{ __('Create a Support Ticket') }}</h4>
              </div>
              <div class="edit-info-area mt-30">
                @if (Session::has('success'))
                  <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                <form action="{{ route('user.support_ticket.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group mb-20">
                        <label for="" class="mb-1">{{ __('Email Address') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('Enter Email') }}" name="email"
                          required value="{{ Auth::guard('web')->user()->email }}">
                        @error('email')
                          <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group mb-20">
                        <label for="" class="mb-1">{{ __('Subject') }}</label>
                        <input type="text" class="form-control" placeholder="{{ __('Enter Subject') }}" name="subject"
                          required>
                        @error('subject')
                          <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group mb-20">
                        <label for="" class="mb-1">{{ __('Description') }}</label>
                        <textarea name="description" class="form-control tinyMce"></textarea>
                        @error('description')
                          <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group mb-20">
                        <label for="" class="mb-1">{{ __('Attachment') }}</label>
                        <input type="file" accept=".zip" name="attachment" class="form-control">
                        @error('attachment')
                          <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-warning">{{ __('Max upload size is 20 MB') }} &amp;
                          {{ __('only .zip file is allowed.') }}</p>
                      </div>
                    </div>

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
