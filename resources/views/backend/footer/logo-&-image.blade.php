@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Logo & Image') }}</h4>
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
        <a href="#">{{ __('Footer') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Logo & Image') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Update Footer Logo') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="logoForm" action="{{ route('admin.footer.update_logo') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Logo') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (!empty($data->footer_logo))
                      <img src="{{ asset('assets/img/' . $data->footer_logo) }}" alt="logo" class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="footer_logo">
                    </div>
                  </div>
                  @if ($errors->has('footer_logo'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('footer_logo') }}</p>
                  @endif
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="logoForm" class="btn btn-success">
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
            <div class="col-lg-12">
              <div class="card-title">{{ __('Update Footer Background Image') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <form id="imageForm" action="{{ route('admin.footer.update_background_image') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Background Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if (!empty($data->footer_background_image))
                      <img src="{{ asset('assets/img/' . $data->footer_background_image) }}" alt="background image"
                        class="uploaded-background-img">
                    @else
                      <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-background-img">
                    @endif
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="background-img-input" name="footer_background_image">
                    </div>
                  </div>
                  @if ($errors->has('footer_background_image'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('footer_background_image') }}</p>
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
  </div>
@endsection
