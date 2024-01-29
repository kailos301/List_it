@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Breadcrumb') }}</h4>
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
        <a href="#">{{ __('Breadcrumb') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update Breadcrumb') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="imageForm" action="{{ route('admin.basic_settings.update_breadcrumb') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Breadcrumb') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (!empty($data->breadcrumb))
                      <img src="{{ asset('assets/img/' . $data->breadcrumb) }}" alt="breadcrumb" class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="breadcrumb">
                    </div>
                  </div>
                  @if ($errors->has('breadcrumb'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('breadcrumb') }}</p>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="imageForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
