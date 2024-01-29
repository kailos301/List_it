@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Cookie Alert') }}</h4>
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
        <a href="#">{{ __('Cookie Alert') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Cookie Alert') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body pt-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="ajaxForm" action="{{ route('admin.basic_settings.update_cookie_alert', ['language' => request()->input('language')]) }}" method="post">
                @csrf
                <div class="form-group">
                  <label>{{ __('Cookie Alert Status') . '*' }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="cookie_alert_status" value="1" class="selectgroup-input" {{ $data != null && $data->cookie_alert_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="radio" name="cookie_alert_status" value="0" class="selectgroup-input" {{ $data != null && $data->cookie_alert_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  <p id="err_cookie_alert_status" class="em text-danger mt-2 mb-0"></p>
                </div>

                <div class="form-group">
                  <label>{{ __('Cookie Alert Button Text') . '*' }}</label>
                  <input type="text" class="form-control" name="cookie_alert_btn_text" value="{{ $data != null ? $data->cookie_alert_btn_text : '' }}">
                  <p id="err_cookie_alert_btn_text" class="em text-danger mt-2 mb-0"></p>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Cookie Alert Text') . '*' }}</label>
                  <textarea class="form-control" name="cookie_alert_text" rows="4">{{ $data != null ? $data->cookie_alert_text : '' }}</textarea>
                  <p id="err_cookie_alert_text" class="em text-danger mt-2 mb-0"></p>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="submitBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
