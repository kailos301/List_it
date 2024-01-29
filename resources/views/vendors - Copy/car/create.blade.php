@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Car') }}</h4>
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
        <a href="#">{{ __('Add Car') }}</a>
      </li>
    </ul>
  </div>

  @php
    $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission(Auth::guard('vendor')->user()->id);
  @endphp

  <div class="row">
    <div class="col-md-12">
      @if ($current_package != '[]')
        @if (vendorTotalAddedCar() >= $current_package->number_of_car_add)
          <div class="alert alert-danger">
            {{ __("You can't add more car. Please buy/extend a plan to add car") }}
          </div>
          @php
            $can_car_add = 2;
          @endphp
        @else
          @php
            $can_car_add = 1;
          @endphp
        @endif
      @else
        @php
          $pendingMemb = \App\Models\Membership::query()
              ->where([['vendor_id', '=', Auth::id()], ['status', 0]])
              ->whereYear('start_date', '<>', '9999')
              ->orderBy('id', 'DESC')
              ->first();
          $pendingPackage = isset($pendingMemb) ? \App\Models\Package::query()->findOrFail($pendingMemb->package_id) : null;
        @endphp
        @if ($pendingPackage)
          <div class="alert alert-warning text-dark">
            {{ __('You have requested a package which needs an action (Approval / Rejection) by Admin. You will be notified via mail once an action is taken.') }}
          </div>
          <div class="alert alert-warning text-dark">
            <strong>{{ __('Pending Package') . ':' }} </strong> {{ $pendingPackage->title }}
            <span class="badge badge-secondary">{{ $pendingPackage->term }}</span>
            <span class="badge badge-warning">{{ __('Decision Pending') }}</span>
          </div>
        @else
          <div class="alert alert-warning text-dark">
            {{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}
          </div>
        @endif
        @php
          $can_car_add = 0;
        @endphp
      @endif



      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Car') }}</div>
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
                <form action="{{ route('vendor.car.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata"
                  class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
              </div>
              <form id="carForm" action="{{ route('vendor.car_management.store_car') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div id="sliders"></div>
                <input type="hidden" name="can_car_add" value="{{ $can_car_add }}">
                <div class="form-group">
                  <label for="">{{ __('Feature Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="feature_image">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Current Price') }} *</label>
                      <input type="number" class="form-control" name="price" placeholder="Enter Current Price">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Previous Price') }}</label>
                      <input type="number" class="form-control" name="previous_price" placeholder="Enter Previous Price">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Top Speed (KMH)') }} *</label>
                      <input type="text" class="form-control" name="speed" placeholder="Enter  Speed">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Year') }} *</label>
                      <input type="text" class="form-control" name="year" placeholder="Enter Year">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Mileage') }} *</label>
                      <input type="text" class="form-control" name="mileage" placeholder="Enter Mileage">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Status') }} *</label>
                      <select name="status" id="" class="form-control">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Latitude') }} *</label>
                      <input type="text" class="form-control" name="latitude" placeholder="Enter Latitude">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Longitude') }} *</label>
                      <input type="text" class="form-control" name="longitude" placeholder="Enter Longitude">
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
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
                                <label>{{ __('Title') }} *</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Title">
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
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
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
                                  class="form-control js-example-basic-single3 " data-code="{{ $language->code }}">
                                  <option selected disabled>{{ __('Select brand') }}</option>

                                  @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">

                                <label>{{ __('Model') }} *</label>
                                <select name="{{ $language->code }}_car_model_id"
                                  class="form-control js-example-basic-single4 {{ $language->code }}_car_brand_model_id">
                                  <option selected disabled>{{ __('Select model') }}</option>
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
                                    <option value="{{ $fuel_type->id }}">{{ $fuel_type->name }}</option>
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
                                    <option value="{{ $transmission_type->id }}">{{ $transmission_type->name }}
                                    </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Address') }} *</label>
                                <input type="text" name="{{ $language->code }}_address" class="form-control">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') }} *</label>
                                <textarea id="{{ $language->code }}_description" class="form-control summernote"
                                  name="{{ $language->code }}_description" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }} </label>
                                <input class="form-control" name="{{ $language->code }}_meta_keyword"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              @php $currLang = $language; @endphp

                              @foreach ($languages as $language)
                                @continue($language->id == $currLang->id)

                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }} <strong
                                        class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
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
                          <th><a href="" class="btn btn-sm btn-success addRow"><i
                                class="fas fa-plus-circle"></i></a></th>
                        </tr>
                      <tbody id="tbody">
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
              <button type="submit" id="CarSubmit" data-can_car_add="{{ $can_car_add }}" class="btn btn-success">
                {{ __('Save') }}
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
    'use strict';
    var storeUrl = "{{ route('vendor.car.imagesstore') }}";
    var removeUrl = "{{ route('vendor.car.imagermv') }}";
    var getBrandUrl = "{{ route('vendor.get-car.brand.model') }}";
  </script>

  <script src="{{ asset('assets/js/car.js') }}"></script>
  <script>
    var labels = "{!! $labels !!}";
    var values = "{!! $values !!}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-dropzone.js') }}"></script>
@endsection
