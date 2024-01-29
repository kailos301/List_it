@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Settings') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Push Notification') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col">
              <div class="card-title">{{ __('Update Settings') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="settingsForm" action="{{ route('admin.user_management.push_notification.update_settings') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Push Notification Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (!is_null($data->notification_image))
                      <img src="{{ asset('assets/img/' . $data->notification_image) }}" alt="image" class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="notification_image">
                    </div>
                  </div>
                  @error('notification_image')
                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                  @enderror
                </div>

                @if (env('VAPID_PUBLIC_KEY') == null && env('VAPID_PRIVATE_KEY') == null)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="">{{ __('VAPID Public Key') . '*' }}</label>
                        <input type="text" class="form-control" name="vapid_public_key" placeholder="Enter VAPID Public Key">
                        @error('vapid_public_key')
                          <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                      </div>

                      <div class="form-group">
                        <label for="">{{ __('VAPID Private Key') . '*' }}</label>
                        <input type="text" class="form-control" name="vapid_private_key" placeholder="Enter VAPID Private Key">
                        @error('vapid_private_key')
                          <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                        @enderror
                      </div>

                      <p class="ml-3 mb-0 text-light">
                        <a href="//www.attheminute.com/vapid-key-generator/" target="_blank" class="text-decoration-none">
                          {{ __('Click Here') }}
                        </a> {{ __('to generate the VAPID Public Key & the VAPID Private Key') . '.' }}
                      </p>
                    </div>
                  </div>
                @endif
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="settingsForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
