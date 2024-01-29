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
        <a href="#">{{ __('Advertisements') }}</a>
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
        <form action="{{ route('admin.advertise.update_settings') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update Settings') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group">
                  <label>{{ __('Google Adsense Publisher ID') . '*' }}</label>
                  <input type="text" class="form-control" name="google_adsense_publisher_id" value="{{ $data->google_adsense_publisher_id != null ? $data->google_adsense_publisher_id : '' }}" placeholder="Enter Google Adsense Publisher ID">
                  @if ($errors->has('google_adsense_publisher_id'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('google_adsense_publisher_id') }}</p>
                  @endif
                  <p class="mt-2 mb-0">
                    <a href="//prnt.sc/1uvqtdw" target="_blank" class="redirect-link">{{ __('Click Here') }}</a> {{ __('to find the punlisher id in your google adsense account') . '.' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
