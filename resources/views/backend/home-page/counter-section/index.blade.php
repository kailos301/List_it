@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Counter Section') }}</h4>
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
        <a href="#">{{ __('Counter Section') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col">
                  <div class="card-title">{{ __('Update Counter Section Image') }}</div>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="counterImgForm" action="{{ route('admin.home_page.update_counter_section_image') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                      <label for="">{{ __('Background Image') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if (!empty($info->counter_section_image))
                          <img src="{{ asset('assets/img/' . $info->counter_section_image) }}" alt="image"
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif
                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="counter_section_image">
                        </div>
                      </div>
                      @error('counter_section_image')
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
                  <button type="submit" form="counterImgForm" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-md-8">
                  <div class="card-title">{{ __('Update Counter Section Info') }}</div>
                </div>
                <div class="col-lg-4">
                  @includeIf('backend.partials.languages')
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="counterInfoForm" action="{{ route('admin.home_page.update_counter_section_info') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="lang_code" value="{{ request()->input('language') }}">
                    <div class="form-group">
                      <label for="">{{ __('Title') . '*' }}</label>
                      <input type="text" name="title" class="form-control" placeholder="{{ __('Enter Title') }}"
                        value="{{ @$counterInfo->title }}">

                      @error('title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="">{{ __('Subtitle') . '*' }}</label>

                      <textarea name="subtitle" class="form-control" placeholder="{{ __('Enter Sub Title') }}">{{ @$counterInfo->subtitle }}</textarea>

                      @error('subtitle')
                        <p class="mt-2
                        mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" form="counterInfoForm" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('Counter Informations') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.home_page.bulk_delete_counter') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($counters) == 0)
                <h3 class="text-center mt-2">{{ __('NO INFORMATION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($counters as $counter)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $counter->id }}">
                          </td>
                          <td><i class="{{ $counter->icon }}"></i></td>
                          <td>{{ $counter->amount }}</td>
                          <td>
                            {{ strlen($counter->title) > 20 ? mb_substr($counter->title, 0, 20, 'UTF-8') . '...' : $counter->title }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $counter->id }}" data-icon="{{ $counter->icon }}"
                              data-amount="{{ $counter->amount }}" data-title="{{ $counter->title }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.delete_counter', ['id' => $counter->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm  mt-1 deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.home-page.counter-section.create')

  {{-- edit modal --}}
  @include('backend.home-page.counter-section.edit')
@endsection
