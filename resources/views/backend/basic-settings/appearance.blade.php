@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Website Appearance') }}</h4>
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
        <a href="#">{{ __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Website Appearance') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.basic_settings.update_appearance') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update Website Appearance') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group">
                  <label>{{ __('Primary Color') . '*' }}</label>
                  <input class="jscolor form-control ltr" name="primary_color" value="{{ $data->primary_color }}">
                  @if ($errors->has('primary_color'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('primary_color') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Secondary Color') . '*' }}</label>
                  <input class="jscolor form-control ltr" name="secondary_color" value="{{ $data->secondary_color }}">
                  @if ($errors->has('secondary_color'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('secondary_color') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Breadcrumb Section Overlay Color') . '*' }}</label>
                  <input class="jscolor form-control ltr" name="breadcrumb_overlay_color" value="{{ $data->breadcrumb_overlay_color }}">
                  @if ($errors->has('breadcrumb_overlay_color'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb_overlay_color') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Breadcrumb Section Overlay Opacity') . '*' }}</label>
                  <input class="form-control ltr" type="number" step="0.01" name="breadcrumb_overlay_opacity" value="{{ $data->breadcrumb_overlay_opacity }}">
                  @if ($errors->has('breadcrumb_overlay_opacity'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb_overlay_opacity') }}</p>
                  @endif
                  <p class="mt-2 mb-0 text-warning">{{ __('This will decide the transparency level of the overlay color.') }}<br>
                    {{ __('Value must be between 0 to 1.') }}<br>
                    {{ __('Transparency level will be lower with the increment of the value.') }}
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
