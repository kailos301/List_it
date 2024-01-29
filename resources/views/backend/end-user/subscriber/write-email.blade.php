@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Send Email') }}</h4>
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
        <a href="#">{{ __('Subscribers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Send Email') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.user_management.subscribers.send_email') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Send Email') }}</div>
            <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.user_management.subscribers') }}">
              <span class="btn-label">
                <i class="fas fa-backward"></i>
              </span>
              {{ __('Back') }}
            </a>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 offset-lg-2">
                <div class="form-group">
                  <label for="">{{ __('Subject') . '*' }}</label>
                  <input type="text" class="form-control" name="subject" placeholder="Enter Mail Subject">
                  @if ($errors->has('subject'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('subject') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label for="">{{ __('Message') . '*' }}</label>
                  <textarea class="summernote" name="message" data-height="300" placeholder="Write Your Mail"></textarea>
                  @if ($errors->has('message'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('message') }}</p>
                  @endif
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
