@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Email Settings') }}</h4>
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
        <a href="#">{{ __('Email Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Mail From Admin') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.basic_settings.update_mail_from_admin') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Mail From Admin') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body py-5">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="alert alert-warning text-center" role="alert">
                  <strong class="text-dark">{{ __('This mail address will be used to send all mails from this website.') }}</strong>
                </div>

                <div class="form-group">
                  <label>{{ __('SMTP Status') . '*' }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="smtp_status" value="1" class="selectgroup-input" {{ $data->smtp_status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="smtp_status" value="0" class="selectgroup-input" {{ $data->smtp_status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('smtp_status'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('smtp_status') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('SMTP Host') . '*' }}</label>
                  <input type="text" class="form-control" name="smtp_host" value="{{ $data->smtp_host }}">
                  @if ($errors->has('smtp_host'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('smtp_host') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('SMTP Port') . '*' }}</label>
                  <input class="form-control" name="smtp_port" value="{{ $data->smtp_port }}">
                  @if ($errors->has('smtp_port'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('smtp_port') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Encryption') . '*' }}</label>
                  <input type="text" class="form-control" name="encryption" value="{{ $data->encryption }}">
                  @if ($errors->has('encryption'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('encryption') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('SMTP Username') . '*' }}</label>
                  <input type="text" class="form-control" name="smtp_username" value="{{ $data->smtp_username }}">
                  @if ($errors->has('smtp_username'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('smtp_username') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('SMTP Password') . '*' }}</label>
                  <input type="password" class="form-control" name="smtp_password" value="{{ $data->smtp_password }}">
                  @if ($errors->has('smtp_password'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('smtp_password') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('From Email') . '*' }}</label>
                  <input type="email" class="form-control" name="from_mail" value="{{ $data->from_mail }}">
                  @if ($errors->has('from_mail'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('from_mail') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('From Name') . '*' }}</label>
                  <input type="text" class="form-control" name="from_name" value="{{ $data->from_name }}">
                  @if ($errors->has('from_name'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('from_name') }}</p>
                  @endif
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
