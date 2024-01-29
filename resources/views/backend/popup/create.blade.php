@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Popup') }}</h4>
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
        <a href="#">{{ __('Add Popup') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Add Popup') . ' (' . __('Type') . ' - ' . $popupType . ')' }}
          </div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.announcement_popups.select_popup_type') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <form id="ajaxForm" class="create" action="{{ route('admin.announcement_popups.store_popup') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $popupType }}">

                <div class="form-group">
                  <label for="">{{ __('Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="image">
                    </div>
                  </div>
                  <p id="err_image" class="mt-2 mb-0 text-danger em"></p>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>{{ __('Language') . '*' }}</label>
                      <select name="language_id" class="form-control">
                        <option selected disabled>{{ __('Select a Language') }}</option>
                        @foreach ($languages as $language)
                          <option value="{{ $language->id }}">{{ $language->name }}</option>
                        @endforeach
                      </select>
                      <p id="err_language_id" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>{{ __('Name') . '*' }}</label>
                      <input type="text" class="form-control" name="name" placeholder="Enter Popup Name">
                      <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                      <p class="text-warning mt-2 mb-0">
                        <small>{{ __('This name will not appear in UI. Rather then, it will help the admin to identify the popup.') }}</small>
                      </p>
                    </div>
                  </div>
                </div>

                @if ($popupType == 2 || $popupType == 3 || $popupType == 7)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Background Color Code') . '*' }}</label>
                        <input class="jscolor form-control ltr" name="background_color">
                        <p id="err_background_color" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popupType == 2 || $popupType == 3)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Background Color Opacity') . '*' }}</label>
                        <input type="number" class="form-control ltr" step="0.01" name="background_color_opacity">
                        <p id="err_background_color_opacity" class="mt-2 mb-0 text-danger em"></p>
                        <p class="mt-2 mb-0 text-warning">
                          {{ __('This will decide the transparency level of the color.') }}<br>
                          {{ __('Value must be between 0 to 1.') }}<br>
                          {{ __('Transparency level will be lower with the increment of the value.') }}
                        </p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popupType == 2 || $popupType == 3 || $popupType == 4 || $popupType == 5 || $popupType == 6 || $popupType == 7)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Title') . '*' }}</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter Popup Title">
                        <p id="err_title" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Text') . '*' }}</label>
                        <textarea class="form-control" name="text" placeholder="Enter Popup Text" rows="5"></textarea>
                        <p id="err_text" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Button Text') . '*' }}</label>
                        <input type="text" class="form-control" name="button_text" placeholder="Enter Button Text">
                        <p id="err_button_text" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Button Color Code') . '*' }}</label>
                        <input class="jscolor form-control ltr" name="button_color">
                        <p id="err_button_color" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popupType == 2 || $popupType == 4 || $popupType == 6 || $popupType == 7)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Button URL') . '*' }}</label>
                        <input type="url" class="form-control ltr" name="button_url" placeholder="Enter Button URL">
                        <p id="err_button_url" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popupType == 6 || $popupType == 7)
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('End Date') . '*' }}</label>
                        <input type="text" class="form-control datepicker ltr" name="end_date" placeholder="Enter End Date" readonly autocomplete="off">
                        <p id="err_end_date" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('End Time') . '*' }}</label>
                        <input type="text" class="form-control timepicker ltr" name="end_time" placeholder="Enter End Time" readonly autocomplete="off">
                        <p id="err_end_time" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>{{ __('Delay') . ' (' . __('milliseconds') . ')*' }}</label>
                      <input type="number" class="form-control ltr" name="delay" placeholder="Enter Popup Delay">
                      <p id="err_delay" class="mt-2 mb-0 text-danger em"></p>
                      <p class="text-warning mt-2 mb-0">
                        <small>{{ __('Popup will appear in UI after this delay time.') }}</small>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>{{ __('Serial Number') . '*' }}</label>
                      <input type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number">
                      <p id="err_serial_number" class="mt-2 mb-0 text-danger em"></p>
                      <p class="mt-2 mb-0 text-warning">
                        {{ __('If there are multiple active popups, then popups will be shown in UI according to serial number.') }}<br>
                        {{ __('The higher the serial number is, the later the Popup will be shown.') }}
                      </p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" id="submitBtn">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
