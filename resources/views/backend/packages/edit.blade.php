@extends('backend.layout')

@php
  use App\Models\Language;
  $selLang = Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang->language) && $selLang->language->rtl == 1)
  @section('styles')
    <style>
      form input,
      form textarea,
      form select {
        direction: rtl;
      }

      form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
      }
    </style>
  @endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit package') }}</h4>
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
        <a href="#">{{ __('Packages') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit package') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.package.index') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>
        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="ajaxForm" class="" action="{{ route('admin.package.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">
                <div class="form-group">
                  <label for="title">{{ __('Package title') }}*</label>
                  <input id="title" type="text" class="form-control" name="title" value="{{ $package->title }}"
                    placeholder="{{ __('Enter name') }}">
                  <p id="err_title" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="price">{{ __('Price') }} ({{ $settings->base_currency_text }})*</label>
                  <input id="price" type="number" class="form-control" name="price"
                    placeholder="{{ __('Enter Package price') }}" value="{{ $package->price }}">
                  <p class="text-warning">
                    <small>{{ __('If price is 0 , than it will appear as free') }}</small>
                  </p>
                  <p id="err_price" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label for="plan_term">{{ __('Package term') }}*</label>
                  <select id="plan_term" name="term" class="form-control">
                    <option value="" selected disabled>{{ __('Select a Term') }}</option>
                    <option value="monthly" {{ $package->term == 'monthly' ? 'selected' : '' }}>
                      {{ __('monthly') }}</option>
                    <option value="yearly" {{ $package->term == 'yearly' ? 'selected' : '' }}>
                      {{ __('yearly') }}</option>
                    <option value="lifetime" {{ $package->term == 'lifetime' ? 'selected' : '' }}>
                      {{ 'lifetime' }}</option>
                  </select>
                  <p id="err_term" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label class="form-label">{{ __('How many cars can the vendor add') }} *</label>
                  <input type="text" class="form-control" name="number_of_car_add"
                    placeholder="{{ __('Enter How many cars can the vendor add') }}"
                    value="{{ $package->number_of_car_add }}">
                  <p id="err_number_of_car_add" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                  <label class="form-label">{{ __('How many cars does the vendor make  featured') }} *</label>
                  <input type="text" name="number_of_car_featured" class="form-control"
                    placeholder="{{ __('Enter how many cars does the vendor make featured') }}"
                    value="{{ $package->number_of_car_featured }}">
                  <p id="err_number_of_car_featured" class="mb-0 text-danger em"></p>
                </div>

                <p id="err_trial_days" class="mb-0 text-danger em"></p>
                <div class="form-group">
                  <label for="status">{{ __('Status') }}*</label>
                  <select id="status" class="form-control ltr" name="status">
                    <option value="" selected disabled>{{ __('Select a status') }}</option>
                    <option value="1" {{ $package->status == '1' ? 'selected' : '' }}>
                      {{ __('Active') }}</option>
                    <option value="0" {{ $package->status == '0' ? 'selected' : '' }}>
                      {{ __('Deactive') }}</option>
                  </select>
                  <p id="err_status" class="mb-0 text-danger em"></p>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('script')
  <script src="{{ asset('assets/js/packages.js') }}"></script>
  <script src="{{ asset('assets/admin/js/edit-package.js') }}"></script>
@endsection
