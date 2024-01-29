@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Call To Action Section') }}</h4>
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
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Call To Action Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col">
              <div class="card-title">{{ __('Update Call To Action Section Image') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="actionImgForm" action="{{ route('admin.home_page.update_call_to_action_section_image') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Background Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (!empty($info->call_to_action_section_image))
                      <img src="{{ asset('assets/img/' . $info->call_to_action_section_image) }}" alt="image"
                        class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="call_to_action_section_image">
                    </div>
                  </div>
                  @error('call_to_action_section_image')
                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="actionImgForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title">{{ __('Update Call To Action Section') }}</div>
            </div>

            <div class="col-lg-4">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="actionForm"
                action="{{ route('admin.home_page.update_call_to_action_section', ['language' => request()->input('language')]) }}"
                method="POST">
                @csrf
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Title') }}</label>
                      <input type="text" class="form-control" name="title"
                        value="{{ empty($data->title) ? '' : $data->title }}" placeholder="Enter Title">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Subtitle') }}</label>
                      <input type="text" class="form-control" name="subtitle"
                        value="{{ empty($data->subtitle) ? '' : $data->subtitle }}" placeholder="Enter Subtitle">
                    </div>
                  </div>


                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Button Name') }}</label>
                      <input type="text" class="form-control" name="button_name"
                        value="{{ empty($data->button_name) ? '' : $data->button_name }}"
                        placeholder="Enter Button Name">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Button URL') }}</label>
                      <input type="url" class="form-control ltr" name="button_url"
                        value="{{ empty($data->button_url) ? '' : $data->button_url }}" placeholder="Enter Button URL">
                    </div>
                  </div>
                  @if ($settings->theme_version == 2)
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="">{{ __('Video URL') }}</label>
                        <input type="url" class="form-control ltr" name="video_url"
                          value="{{ empty($data->video_url) ? '' : $data->video_url }}" placeholder="Enter Video URL">
                      </div>
                    </div>
                  @endif


                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Text') }}</label>
                      <textarea name="text" class="form-control" rows="1" placeholder="Enter Text">{{ empty($data->text) ? '' : $data->text }}</textarea>
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
              <button type="submit" form="actionForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
