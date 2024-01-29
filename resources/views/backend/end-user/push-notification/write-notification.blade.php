@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Send Notification') }}</h4>
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
        <a href="#">{{ __('Send Notification') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.user_management.push_notification.send') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Send Notification') }}</div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 offset-lg-2">
                <div class="form-group">
                  <label for="">{{ __('Title') . '*' }}</label>
                  <input type="text" class="form-control" name="title" placeholder="Enter Push Notification Title">
                  @if ($errors->has('title'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('title') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('Message') }}</label>
                  <textarea name="message" class="form-control" rows="5" placeholder="Write Notification Message"></textarea>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Button Name') . '*' }}</label>
                  <input type="text" class="form-control" name="button_name" placeholder="Enter Button Name">
                  @if ($errors->has('button_name'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('button_name') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('Button URL') . '*' }}</label>
                  <input type="url" class="form-control" name="button_url" placeholder="Enter Button URL">
                  @if ($errors->has('button_url'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('button_url') }}</p>
                  @endif

                  <p class="mt-2 mb-0 text-warning">
                    {{ __('Only those people will receive this notification, who has allowed it.') }}<br>
                    {{ __('Push notification won\'t work for \'http\' protocol, it needs \'https\' protocol.') }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Send') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
