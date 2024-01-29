@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Popup Type') }}</h4>
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
        <a href="#">{{ __('Announcement Popups') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Popup Type') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Select Popup Type') }}</div>
            </div>

            <div class="col-lg-4 mt-2 mt-lg-0">
              <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                {{ __('Back') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="popup-type">
    <div class="row">
      <div class="col-lg-3">
        <a href="{{ route('admin.announcement_popups.create_popup', ['type' => 1]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/img/popup-samples/1.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ __('Type - 1') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('admin.announcement_popups.create_popup', ['type' => 2]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/img/popup-samples/2.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ __('Type - 2') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('admin.announcement_popups.create_popup', ['type' => 3]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/img/popup-samples/3.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ __('Type - 3') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('admin.announcement_popups.create_popup', ['type' => 4]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/img/popup-samples/4.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ __('Type - 4') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('admin.announcement_popups.create_popup', ['type' => 5]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/img/popup-samples/5.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ __('Type - 5') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('admin.announcement_popups.create_popup', ['type' => 6]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/img/popup-samples/6.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ __('Type - 6') }}</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-3">
        <a href="{{ route('admin.announcement_popups.create_popup', ['type' => 7]) }}" class="d-block">
          <div class="card card-stats">
            <div class="card-body">
              <img src="{{ asset('assets/img/popup-samples/7.jpg') }}" alt="popup image" width="100%">
              <h5 class="text-center text-white mt-3 mb-0">{{ __('Type - 7') }}</h5>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
@endsection
