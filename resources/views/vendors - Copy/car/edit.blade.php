@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Car') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Car Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Car') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Car') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.car_management.car', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
          @php
            $dCarContent = App\Models\Car\CarContent::where('car_id', $car->id)
                ->where('language_id', $defaultLang->id)
                ->first();
          @endphp
          <a class="mr-2 btn btn-success btn-sm float-right d-inline-block"
            href="{{ route('frontend.car.details', ['slug' => $dCarContent->slug, 'id' => $car->id]) }}" target="_blank">
            <span class="btn-label">
              <i class="fas fa-eye"></i>
            </span>
            {{ __('Preview') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-10 offset-lg-1">
              <div class="alert alert-danger pb-1 dis-none" id="carErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') }} **</strong></label>
                <div class="row">
                  <div class="col-12">
                    <table class="table table-striped" id="imgtable">
                      @foreach ($car->galleries as $item)
                        <tr class="trdb table-row" id="trdb{{ $item->id }}">
                          <td>
                            <div class="">
                              <img class="thumb-preview wf-150"
                                src="{{ asset('assets/admin/img/car-gallery/' . $item->image) }}" alt="Ad Image">
                            </div>
                          </td>
                          <td>
                            <i class="fa fa-times rmvbtndb" data-indb="{{ $item->id }}"></i>
                          </td>
                        </tr>
                      @endforeach
                    </table>
                  </div>
                </div>
                <form action="{{ route('vendor.car.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata"
                  class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                  <input type="hidden" value="{{ $car->id }}" name="car_id">
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
              </div>

              <form id="carForm" action="{{ route('vendor.car_management.update_car', $car->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <div class="form-group">
                  <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img
                      src="{{ $car->feature_image ? asset('assets/admin/img/car/' . $car->feature_image) : asset('assets/admin/img/noimage.jpg') }}"
                      alt="..." class="uploaded-img">
                  </div>
                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="thumbnail">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Current Price') }} *</label>
                      <input type="text" class="form-control" name="price" placeholder="{{ __('Current Price') }}"
                        value="{{ $car->price }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Previous Price') }} </label>
                      <input type="number" class="form-control" name="previous_price" placeholder="Enter Previous Price"
                        value="{{ $car->previous_price }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Top Speed (KMH)') }} *</label>
                      <input type="text" class="form-control" name="speed" placeholder="Enter Top Speed"
                        value="{{ $car->speed }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Year') }} *</label>
                      <input type="text" class="form-control" value="{{ $car->year }}" name="year"
                        placeholder="Enter Year">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Mileage') }} *</label>
                      <input type="text" class="form-control" value="{{ $car->mileage }}" name="mileage"
                        placeholder="Enter Mileage">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Status') }} *</label>
                      <select name="status" id="" class="form-control">
                        <option {{ $car->status == 1 ? 'selected' : '' }} value="1">{{ __('Active') }}</option>
                        <option {{ $car->status == 0 ? 'selected' : '' }} value="0">{{ __('Deactive') }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Latitude') }} *</label>
                      <input type="text" class="form-control" value="{{ $car->latitude }}" name="latitude"
                        placeholder="Enter Latitude">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Longitude') }} *</label>
                      <input type="text" class="form-control" value="{{ $car->longitude }}" name="longitude"
                        placeholder="Enter Longitude">
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    @php
                      $carContent = App\Models\Car\CarContent::where('car_id', $car->id)
                          ->where('language_id', $language->id)
                          ->first();
                    @endphp
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title*') }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Title" value="{{ $carContent ? $carContent->title : '' }}">
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $categories = App\Models\Car\Category::where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Category') }} *</label>
                                <select name="{{ $language->code }}_category_id"
                                  class="form-control js-example-basic-single2">
                                  <option selected disabled>{{ __('Select a Category') }}</option>

                                  @foreach ($categories as $category)
                                    <option
                                      {{ ($carContent ? $carContent->category_id : '' == $category->id) ? 'selected' : '' }}
                                      value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $conditions = App\Models\Car\CarColor::where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Condition') }} *</label>
                                <select name="{{ $language->code }}_car_condition_id" class="form-control">
                                  <option selected disabled>{{ __('Select Condition') }}</option>

                                  @foreach ($conditions as $condition)
                                    <option
                                      {{ ($carContent ? $carContent->car_condition_id : '' == $condition->id) ? 'selected' : '' }}
                                      value="{{ $condition->id }}">{{ $condition->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $brands = App\Models\Car\Brand::where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Brand') }} *</label>
                                <select name="{{ $language->code }}_brand_id"
                                  class="form-control js-example-basic-single3" data-code="{{ $language->code }}">
                                  <option selected disabled>{{ __('Select brand') }}</option>

                                  @foreach ($brands as $brand)
                                    <option @selected($carContent->brand_id == $brand->id) value="{{ $brand->id }}">
                                      {{ $brand->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $brand_models = App\Models\Car\CarModel::where('brand_id', $carContent ? $carContent->brand_id : 0)
                                      ->where('status', 1)
                                      ->get();
                                @endphp
                                <label>{{ __('Model') }} *</label>
                                <select name="{{ $language->code }}_car_model_id"
                                  class="form-control js-example-basic-single4 {{ $language->code }}_car_brand_model_id">
                                  <option selected disabled>{{ __('Select model') }}</option>

                                  @foreach ($brand_models as $brand_model)
                                    <option
                                      {{ ($carContent ? $carContent->car_model_id : '' == $brand_model->id) ? 'selected' : '' }}
                                      value="{{ $brand_model->id }}">{{ $brand_model->name }} </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $fuel_types = App\Models\Car\FuelType::where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Fuel Type') }} *</label>
                                <select name="{{ $language->code }}_fuel_type_id" class="form-control">
                                  <option selected disabled>{{ __('Select Fuel type') }}</option>

                                  @foreach ($fuel_types as $fuel_type)
                                    <option
                                      {{ ($carContent ? $carContent->fuel_type_id : '' == $fuel_type->id) ? 'selected' : '' }}
                                      value="{{ $fuel_type->id }}">{{ $fuel_type->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $transmission_types = App\Models\Car\TransmissionType::where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Transmission Type') }} *</label>
                                <select name="{{ $language->code }}_transmission_type_id" class="form-control">
                                  <option selected disabled>{{ __('Select Transmission Type') }}</option>

                                  @foreach ($transmission_types as $transmission_type)
                                    <option
                                      {{ ($carContent ? $carContent->transmission_type_id : '' == $transmission_type->id) ? 'selected' : '' }}
                                      value="{{ $transmission_type->id }}">{{ $transmission_type->name }}
                                    </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Address') }} *</label>
                                <input type="text" name="{{ $language->code }}_address" class="form-control"
                                  value="{{ @$carContent->address }}">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') }} *</label>
                                <textarea class="form-control summernote" id="{{ $language->code }}_description"
                                  name="{{ $language->code }}_description" data-height="300">{{ replaceBaseUrl($carContent ? $carContent->description : '', 'summernote') }}</textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }} </label>
                                <input class="form-control" name="{{ $language->code }}_meta_keyword"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput"
                                  value="{{ $carContent ? $carContent->meta_keyword : '' }}">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description">{{ $carContent ? $carContent->meta_description : '' }}</textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="row">
                  <div class="col-lg-12" id="variation_pricing">
                    <h4 for="">{{ __('Additional Specifications (Optional)') }}</h4>
                    <table class="table table-bordered ">
                      <thead>
                        <tr>
                          <th>{{ __('Label') }}</th>
                          <th>{{ __('Value') }}</th>
                          <th><a href="javascrit:void(0)" class="btn  btn-sm btn-success addRow"><i
                                class="fas fa-plus-circle"></i></a></th>
                        </tr>
                      <tbody id="tbody">

                        @if (count($specifications) > 0)
                          @foreach ($specifications as $specification)
                            <tr>
                              <td>
                                @foreach ($languages as $language)
                                  @php
                                    $sp_content = App\Models\Car\CarSpecificationContent::where([['language_id', $language->id], ['car_specification_id', $specification->id]])->first();
                                  @endphp
                                  <div class="form-group">
                                    <input type="text" name="{{ $language->code }}_label[]"
                                      value="{{ @$sp_content->label }}" class="form-control"
                                      placeholder="Label ({{ $language->name }})">
                                  </div>
                                @endforeach
                              </td>
                              <td>
                                @foreach ($languages as $language)
                                  @php
                                    $sp_content = App\Models\Car\CarSpecificationContent::where([['language_id', $language->id], ['car_specification_id', $specification->id]])->first();
                                  @endphp
                                  <div class="form-group">
                                    <input type="text" name="{{ $language->code }}_value[]"
                                      value="{{ @$sp_content->value }}" class="form-control"
                                      placeholder="Value ({{ $language->name }})">
                                  </div>
                                @endforeach
                              </td>
                              <td>
                                <a href="javascript:void(0)" class="btn  btn-sm btn-danger deleteRow">
                                  <i class="fas fa-minus"></i></a>
                              </td>
                            </tr>
                          @endforeach
                        @else
                          <tr>
                            <td>
                              @foreach ($languages as $language)
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <input type="text" name="{{ $language->code }}_label[]" class="form-control"
                                    placeholder="Label ({{ $language->name }})">
                                </div>
                              @endforeach
                            </td>
                            <td>
                              @foreach ($languages as $language)
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <input type="text" name="{{ $language->code }}_value[]" class="form-control"
                                    placeholder="Value ({{ $language->name }})">
                                </div>
                              @endforeach
                            </td>
                            <td>
                              <a href="javascript:void(0)" class="btn btn-danger  btn-sm deleteRow">
                                <i class="fas fa-minus"></i></a>
                            </td>
                          </tr>
                        @endif
                      </tbody>
                      </thead>
                    </table>
                  </div>
                </div>

                <div id="sliders"></div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="CarSubmit" class="btn btn-primary">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@php
  $languages = App\Models\Language::get();
  $labels = '';
  $values = '';
  foreach ($languages as $language) {
      $label_name = $language->code . '_label[]';
      $value_name = $language->code . '_value[]';
      if ($language->direction == 1) {
          $direction = 'form-group rtl text-right';
      } else {
          $direction = 'form-group';
      }
  
      $labels .= "<div class='$direction'><input type='text' name='" . $label_name . "' class='form-control' placeholder='Label ($language->name)'></div>";
      $values .= "<div class='$direction'><input type='text' name='$value_name' class='form-control' placeholder='Value ($language->name)'></div>";
  }
@endphp
@section('script')
  <script>
    var labels = "{!! $labels !!}";
    var values = "{!! $values !!}";
    var getBrandUrl = "{{ route('vendor.get-car.brand.model') }}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-dropzone.js') }}"></script>
  <script src="{{ asset('assets/js/car.js') }}"></script>
@endsection

@section('variables')
  <script>
    "use strict";
    var storeUrl = "{{ route('vendor.car.imagesstore') }}";
    var removeUrl = "{{ route('vendor.car.imagermv') }}";
    var rmvdbUrl = "{{ route('vendor.car.imgdbrmv') }}";
  </script>
@endsection
