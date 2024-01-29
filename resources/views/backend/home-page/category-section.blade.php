@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Category Section') }}</h4>
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
        <a href="#">{{ __('Category Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    @if ($settings->theme_version == 2)
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                <div class="card-title">{{ __('Update Category Section Image') }}</div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <form id="categoryImgForm" action="{{ route('admin.home_page.update_category_section_image') }}"
                  method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <label for="">{{ __('Background Image') . '*' }}</label>
                    <br>
                    <div class="thumb-preview">
                      @if (!empty($info->category_section_background))
                        <img src="{{ asset('assets/img/' . $info->category_section_background) }}" alt="image"
                          class="uploaded-img">
                      @else
                        <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      @endif
                    </div>

                    <div class="mt-3">
                      <div role="button" class="btn btn-primary btn-sm upload-btn">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="category_section_background">
                      </div>
                    </div>
                    @error('category_section_background')
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
                <button type="submit" form="categoryImgForm" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    @if ($settings->theme_version == 2)
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <div class="card-title">{{ __('Update Category Section') }}</div>
              </div>

              <div class="col-lg-4">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <form id="categoryForm"
                  action="{{ route('admin.home_page.update_category_section', ['language' => request()->input('language')]) }}"
                  method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="">{{ __('Title') }}</label>
                        <input type="text" class="form-control" name="title"
                          value="{{ empty($data->title) ? '' : $data->title }}" placeholder="Enter Title">
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="">{{ __('Subtitle') }}</label>
                        <input type="text" class="form-control" name="subtitle"
                          value="{{ empty($data->subtitle) ? '' : $data->subtitle }}" placeholder="Enter Subtitle">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="form-group">
                        <label for="">{{ __('Text') }}</label>
                        <textarea class="form-control" name="text" rows="5" placeholder="Enter Text">{{ empty($data->text) ? '' : $data->text }}</textarea>
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
                <button type="submit" form="categoryForm" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-lg-8">
                <div class="card-title">{{ __('Update Category Section') }}</div>
              </div>

              <div class="col-lg-4">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <form id="categoryForm"
                  action="{{ route('admin.home_page.update_category_section', ['language' => request()->input('language')]) }}"
                  method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-md-6 mx-auto">
                      <div class="form-group">
                        <label for="">{{ __('Title') }}</label>
                        <input type="text" class="form-control" name="title"
                          value="{{ empty($data->title) ? '' : $data->title }}" placeholder="Enter Title">
                      </div>
                      <div class="form-group">
                        <label for="">{{ __('Button Text') }}</label>
                        <input type="text" class="form-control" name="button_text"
                          value="{{ empty($data->button_text) ? '' : $data->button_text }}"
                          placeholder="Enter Button Text">
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
                <button type="submit" form="categoryForm" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection
