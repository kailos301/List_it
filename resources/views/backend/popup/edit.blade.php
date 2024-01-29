@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Popup') }}</h4>
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
        <a href="#">{{ __('Edit Popup') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Edit Popup') . ' (' . __('Type') . ' - ' . $popup->type . ')' }}
          </div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.announcement_popups', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <form id="ajaxEditForm" action="{{ route('admin.announcement_popups.update_popup', ['id' => $popup->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $popup->type }}">

                <div class="form-group">
                  <label for="">{{ __('Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/popups/' . $popup->image) }}" alt="popup image" class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="image">
                    </div>
                  </div>
                  <p id="editErr_image" class="mt-2 mb-0 text-danger em"></p>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>{{ __('Name') . '*' }}</label>
                      <input type="text" class="form-control" name="name" placeholder="Enter Popup Name" value="{{ $popup->name }}">
                      <p id="editErr_name" class="mt-2 mb-0 text-danger em"></p>
                      <p class="text-warning mt-2 mb-0">
                        <small>{{ __('This name will not appear in UI. Rather then, it will help the admin to identify the popup.') }}</small>
                      </p>
                    </div>
                  </div>
                </div>

                @if ($popup->type == 2 || $popup->type == 3 || $popup->type == 7)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Background Color Code') . '*' }}</label>
                        <input class="jscolor form-control ltr" name="background_color" value="{{ $popup->background_color }}">
                        <p id="editErr_background_color" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popup->type == 2 || $popup->type == 3)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Background Color Opacity') . '*' }}</label>
                        <input type="number" class="form-control ltr" step="0.01" name="background_color_opacity" value="{{ $popup->background_color_opacity }}">
                        <p id="editErr_background_color_opacity" class="mt-2 mb-0 text-danger em"></p>
                        <p class="mt-2 mb-0 text-warning">
                          {{ __('This will decide the transparency level of the color.') }}<br>
                          {{ __('Value must be between 0 to 1.') }}<br>
                          {{ __('Transparency level will be lower with the increment of the value.') }}
                        </p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popup->type == 2 || $popup->type == 3 || $popup->type == 4 || $popup->type == 5 || $popup->type == 6 || $popup->type == 7)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Title') . '*' }}</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter Popup Title" value="{{ $popup->title }}">
                        <p id="editErr_title" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Text') . '*' }}</label>
                        <textarea class="form-control" name="text" placeholder="Enter Popup Text" rows="5">{{ $popup->text }}</textarea>
                        <p id="editErr_text" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Button Text') . '*' }}</label>
                        <input type="text" class="form-control" name="button_text" placeholder="Enter Button Text" value="{{ $popup->button_text }}">
                        <p id="editErr_button_text" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Button Color Code') . '*' }}</label>
                        <input class="jscolor form-control ltr" name="button_color" value="{{ $popup->button_color }}">
                        <p id="editErr_button_color" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popup->type == 2 || $popup->type == 4 || $popup->type == 6 || $popup->type == 7)
                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label>{{ __('Button URL') . '*' }}</label>
                        <input type="url" class="form-control ltr" name="button_url" placeholder="Enter Button URL" value="{{ $popup->button_url }}">
                        <p id="editErr_button_url" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                @if ($popup->type == 6 || $popup->type == 7)
                  @php
                    $endDate = Carbon\Carbon::parse($popup->end_date);
                    $endDate = date_format($endDate, 'm/d/Y');

                    $endTime = date('h:i A', strtotime($popup->end_time));
                  @endphp

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('End Date') . '*' }}</label>
                        <input type="text" class="form-control datepicker ltr" name="end_date" placeholder="Enter End Date" readonly autocomplete="off" value="{{ $endDate }}">
                        <p id="editErr_end_date" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('End Time') . '*' }}</label>
                        <input type="text" class="form-control timepicker ltr" name="end_time" placeholder="Enter End Time" readonly autocomplete="off" value="{{ $endTime }}">
                        <p id="editErr_end_time" class="mt-2 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                @endif

                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>{{ __('Delay') . ' (' . __('milliseconds') . ')*' }}</label>
                      <input type="number" class="form-control ltr" name="delay" placeholder="Enter Popup Delay" value="{{ $popup->delay }}">
                      <p id="editErr_delay" class="mt-2 mb-0 text-danger em"></p>
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
                      <input type="number" class="form-control ltr" name="serial_number" placeholder="Enter Serial Number" value="{{ $popup->serial_number }}">
                      <p id="editErr_serial_number" class="mt-2 mb-0 text-danger em"></p>
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
              <button type="submit" class="btn btn-success" id="updateBtn">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
