@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Hero Section') }}</h4>
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
        <a href="#">{{ __('Hero Section') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Slider Version') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    @if ($settings->theme_version == 3)
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Hero Section Button Video Url') }}</div>
          </div>
          <div class="car-body">
            <div class="row">
              <div class="col-md-12">
                <form action="{{ route('admin.home_page.hero_section.update.video-url') }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <label for="">{{ __('Video Url') }}</label>
                    <input type="url" name="video_url" value="{{ $basic->hero_section_video_url }}"
                      class="form-control">
                    <p class="text-warning">{{ __('Enter only youtube video url') }}</p>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-info">{{ __('Update') }}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
    <div class="{{ $settings->theme_version == 3 ? 'col-md-8' : 'col-md-12' }}">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Sliders') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>
            </div>
          </div>
        </div>



        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              @if (count($sliders) == 0)
                <h3 class="text-center mt-2">{{ __('NO SLIDER FOUND') . '!' }}</h3>
              @else
                <div class="row">
                  @foreach ($sliders as $slider)
                    <div class="col-md-3">
                      <div class="card">
                        <div class="card-body">
                          <img src="{{ asset('assets/img/hero/sliders/' . $slider->background_image) }}" alt="image"
                            class="w-100">
                        </div>

                        <div class="card-footer text-center">
                          <a class="editBtn btn btn-secondary btn-sm mr-2 mb-1" href="#" data-toggle="modal"
                            data-target="#editModal" data-id="{{ $slider->id }}"
                            data-image="{{ asset('assets/img/hero/sliders/' . $slider->background_image) }}"
                            data-title="{{ $slider->title }}" data-text="{{ $slider->text }}">
                            <span class="btn-label">
                              <i class="fas fa-edit"></i>
                            </span>
                          </a>

                          <form class="deleteForm d-inline-block"
                            action="{{ route('admin.home_page.hero_section.slider_version.delete', ['id' => $slider->id]) }}"
                            method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm deleteBtn mb-1">
                              <span class="btn-label">
                                <i class="fas fa-trash"></i>
                              </span>
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.home-page.hero-section.slider-version.create')

  {{-- edit modal --}}
  @include('backend.home-page.hero-section.slider-version.edit')
@endsection
