@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Testimonial Section') }}</h4>
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
        <a href="#">{{ __('Testimonial Section') }}</a>
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
                <div class="col-lg-12">
                  <div class="card-title">{{ __('Update Testimonial Section Backgroud Image') }}</div>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="testimonialFormBg"
                    action="{{ route('admin.home_page.update_testimonial_section_background', ['language' => request()->input('language')]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="">{{ __('Image') . '*' }}</label>
                          <br>
                          <div class="thumb-preview">
                            @if (@$themeInfo->testimonial_section_image != null)
                              <img src="{{ asset('assets/img/' . $themeInfo->testimonial_section_image) }}"
                                alt="..."class="uploaded-img">
                            @else
                              <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                            @endif
                          </div>

                          <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm upload-btn">
                              {{ __('Choose Image') }}
                              <input type="file" class="img-input" name="testimonial_section_image">
                            </div>
                          </div>
                          <p id="err_testimonial_section_image" class="mt-2 mb-0 text-danger em"></p>
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
                  <button type="submit" form="testimonialFormBg" class="btn btn-success">
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
                <div class="col-lg-10">
                  <div class="card-title">{{ __('Update Testimonial Section') }}</div>
                </div>

                <div class="col-lg-2">
                  @includeIf('backend.partials.languages')
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="testimonialForm"
                    action="{{ route('admin.home_page.update_testimonial_section', ['language' => request()->input('language')]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf


                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="">{{ __('Title') }}</label>
                          <input type="text" class="form-control" name="title"
                            value="{{ empty($data->title) ? '' : $data->title }}" placeholder="Enter Title">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label for="">{{ __('Subtitle') }}</label>
                          <input type="text" class="form-control" name="subtitle"
                            value="{{ empty($data->subtitle) ? '' : $data->subtitle }}" placeholder="Enter Subtitle">
                        </div>
                      </div>
                    </div>
                    @if ($settings->theme_version != 2)
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="">{{ __('Clients') }}</label>
                            <input type="text" class="form-control" name="clients"
                              value="{{ empty($data->clients) ? '' : $data->clients }}" placeholder="Enter Clients">
                          </div>
                        </div>
                      </div>
                    @endif
                  </form>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" form="testimonialForm" class="btn btn-success">
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
              <div class="card-title">{{ __('Testimonials') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.home_page.bulk_delete_testimonial') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($testimonials) == 0)
                <h3 class="text-center mt-2">{{ __('NO TESTIMONIAL FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>

                        @if ($themeInfo->theme_version == 2)
                          <th scope="col">{{ __('Image') }}</th>
                        @endif

                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Occupation') }}</th>
                        <th scope="col">{{ __('Comment') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($testimonials as $testimonial)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $testimonial->id }}">
                          </td>

                          @if ($themeInfo->theme_version == 2)
                            <td>
                              @if (is_null($testimonial->image))
                                -
                              @else
                                <img src="{{ asset('assets/img/clients/' . $testimonial->image) }}" alt="client image"
                                  width="45">
                              @endif
                            </td>
                          @endif

                          <td>{{ $testimonial->name }}</td>
                          <td>{{ $testimonial->occupation }}</td>
                          <td>
                            {{ strlen($testimonial->comment) > 50 ? mb_substr($testimonial->comment, 0, 50, 'UTF-8') . '...' : $testimonial->comment }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $testimonial->id }}"
                              data-image="{{ is_null($testimonial->image) ? asset('assets/img/noimage.jpg') : asset('assets/img/clients/' . $testimonial->image) }}"
                              data-name="{{ $testimonial->name }}" data-occupation="{{ $testimonial->occupation }}"
                              data-rating="{{ $testimonial->rating }}" data-comment="{{ $testimonial->comment }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.delete_testimonial', ['id' => $testimonial->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-sm deleteBtn">
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
  @include('backend.home-page.testimonial-section.create')

  {{-- edit modal --}}
  @include('backend.home-page.testimonial-section.edit')
@endsection
