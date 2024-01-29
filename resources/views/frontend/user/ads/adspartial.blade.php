
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Car') }}</h4>
    <!-- <ul class="breadcrumbs">
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
    </ul> -->
  </div>

  @php
    $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission(Auth::guard('web')->user()->id);
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
          <!-- <div class="alert alert-warning text-dark">
            {{ __('Your membership is expired. Please purchase a new package / extend the current package.') }}
          </div> -->
        @endif
        @php
          $can_car_add = 0;
        @endphp
      @endif



      <div class="card">
        
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12 ">
              <div class="alert alert-danger pb-1 dis-none" id="carErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') }} **</strong></label>
                <form action="{{ route('car.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata"
                  class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
              </div>
              <form id="carForm" action="{{ route('vendor.cars_management.store_Data') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div id="sliders"></div>
                <input type="hidden" name="can_car_add" value="1">
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
                  <div class="col-lg-8 ">
                    <div class="form-group">
                      <label>{{ __('Optional YouTube Video') }} </label>
                  <input type="text" class="form-control" name="youtube_video"
                                  placeholder="Enter youtube Video URL">
                    </div>
                  </div>
                  <div class="col-lg-4"></div>
                </div>
                <div class="row">
                  <div class="col-lg-8">
                              <div class="form-group ">
                                @php
                               $categories = App\Models\Car\Category::where('parent_id', 0)->where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Category') }} *</label>
                                <select name="en_category_id"
                                  class="form-control js-example-basic-single2" id="adsMaincat">
                                  <option selected disabled>{{ __('Select a Category') }}</option>

                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                  <div class="col-lg-4"></div>
                </div>
                <div class="row">
                  <div class="col-lg-8 ">
                    <div class="form-group">
                      <label>{{ __('Select a Sub Category') }} *</label>
                                <select disabled name="en_sub_category_id"
                                  class="form-control js-example-basic-single2 subhidden"  id="adsSubcat">
                                  <option selected disabled>{{ __('Select sub Category') }}</option>

                                 
                                    
                                 
                                </select>
                    </div>
                  </div>
                  <div class="col-lg-4"></div>
                </div>
                 <div class="row">
                   <div class="col-lg-6">
                    <div class="form-group">
                      <div class="form-check form-check-inline">
  
  <label class="form-check-label" for="inlineRadio3">Ad Type</label>
</div>
                      <div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="adType" id="inlineRadio1" value="For Sale">
  <label class="form-check-label" for="adType">For Sale</label>
</div>
<div class="form-check form-check-inline">
  <input class="form-check-input" type="radio" name="adType" id="inlineRadio2" value="Wanted">
  <label class="form-check-label" for="adType">Wanted</label>
</div>

                   </div>
                    </div>
                 </div>
                
                
                <div class="row cararea" style="display: none;">
                  <div class="col-lg-8 ">
                    <div class="form-group">
                      <h3>{{ __('Vehicle Details') }} </h3>
                      <label>Get all your vehicle details instantly</label>
                  
                    </div>
                  </div>
                  
                </div>
                <div class="row cararea" style="display: none;">
                  <div class="col-lg-8 ">
                    <div class="form-group">
                      <label>{{ __('Enter vehicle registration') }} *</label>
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Vehicle registrtion" aria-label="vehicle registrtion" aria-describedby="basic-addon2" name="vregNo" id="vregNo">
                        <div class="input-group-append">
                          <button id="getVehData" class="btn btn-secondary" type="button">Find</button>
                        </div>
                      </div>
                  
                    </div>
                  </div>
                  
                </div>
                
                <div class="row cararea" style="display: none;">
                  <div class="col-lg-4">
                              <div class="form-group">
                                @php
                                  $brands = App\Models\Car\Brand::where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Make') }} *</label>
                                <select name="en_brand_id"
                                  class="form-control js-example-basic-single3 carmake" data-code="en">
                                  <option >{{ __('Select brand') }}</option>

                                  @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group">

                                <label>{{ __('Model') }} *</label>
                                <select name="en_car_model_id"
                                  class="form-control js-example-basic-single4 en_car_brand_model_id"   id="carModel">
                                  
                                </select>
                              </div>
                            </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Year') }} *</label>
                      <input type="text" class="form-control" name="year" placeholder="Enter Year" id="carYear" >
                    </div>
                  </div>
                  <div class="col-lg-4">
                              <div class="form-group">
                                @php
                                  $fuel_types = App\Models\Car\FuelType::where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Fuel Type') }} *</label>
                                <select name="en_fuel_type_id" id="fuelType" class="form-control" >
                                  

                                  @foreach ($fuel_types as $fuel_type)
                                    <option value="{{ $fuel_type->id }}">{{ $fuel_type->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                 

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label> Engine Size  *</label>
                      <input type="text" class="form-control" name="engineCapacity" placeholder="Enter Size" id="engineCapacity" >
                    </div>
                  </div>
                  </div>
                   <div class="row cararea" style="display: none;">
                  
                   <!-- editable --> 
                   <div class="col-lg-4">
                              <div class="form-group">
                                @php
                                  $transmission_types = App\Models\Car\TransmissionType::where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Transmission Type') }} *</label>
                                <select name="en_transmission_type_id" class="form-control" id="transmissionType">
                                 

                                  @foreach ($transmission_types as $transmission_type)
                                    <option value="{{ $transmission_type->id }}">{{ $transmission_type->name }}
                                    </option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                    <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Body Type') }} *</label>
                      <select name="BodyType" id="bodyType" class="form-control">
                        <option value="">Please select..</option>
                        <option value="8">Convertible</option>
                        <option value="14">Coupe</option>
                        <option value="15">Saloon</option>
                        <option value="9">Hatchback</option>
                        <option value="16">Estate</option>
                        <option value="17">MPV</option>
                        <option value="18">SUV</option>
                        <option value="19">Van</option>
                        <option value="20">Pick Up</option>
                      </select>
                    </div>
                  </div>
                   <div class="col-lg-4">
                    <div class="form-group">
                   <label>{{ __('Doors') }} *</label>
                   <select name="doors" id="" class="form-control" id="carDoors" required>
                    <option value="">Please select...</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                              <div class="form-group ">
                                @php
                                  $colour = App\Models\Car\CarColor::where('status', 1)
                                      ->get();
                                @endphp

                                <label>{{ __('Colour') }} *</label>
                                <select name="en_car_condition_id" class="form-control" id="carColour">
                                  <option selected >{{ __('Select Colour') }}</option>

                                  @foreach ($colour as $colour)
                                    <option value="{{ $colour->id }}">{{ $colour->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                  <!--  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Previous Price') }}</label>
                      <input type="number" class="form-control" name="previous_price" placeholder="Enter Previous Price">
                    </div>
                  </div> -->
                  <div class="col-lg-4">
                    <div class="form-group">
                   <label>{{ __('Seats') }} *</label>
                    <select name="seats" id="" class="form-control">
                    <option value="">Please select...</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    </select>
                    </div>
                  </div>
                </div>
                 <div class="row cararea" style="display: none;">
                   <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Add your mileage') }} (km) *</label>
                      <input type="text" class="form-control" name="mileage" placeholder="Enter Mileage"> 
                    </div>
                  </div>
                  <!-- </div>
                   <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Status') }} *</label>
                      <select name="status" id="" class="form-control">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
                      </select>
                    </div>
                  </div> -->
             
                 
                </div>

                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    <div class="">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <!-- <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button> -->
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

                            <div class="col-lg-12">
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
                                <textarea id="{{ $language->code }}_description" class="form-control "
                                  name="{{ $language->code }}_description" data-height="300"></textarea>
                              </div>
                            </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Price') }}</label>
                      <input type="number" class="form-control" name="price" placeholder="Enter  Price">
                    </div>
                  </div> 
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Status') }} *</label>
                      <select name="status" id="" class="form-control">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
                      </select>
                    </div>
                  </div> 
                </div>

                         <!--  <div class="row">
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
                            </div> -->
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
{{-- dropzone css --}}
<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}">

{{-- atlantis css --}}
<link rel="stylesheet" href="{{ asset('assets/css/atlantis_user.css') }}">
{{-- select2 css --}}
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/front/css/pages/inner-pages.css') }}">
{{-- admin-main css --}}
<link rel="stylesheet" href="{{ asset('assets/css/admin-main.css') }}">
<style type="">
  #carForm .form-control {
    display: block;
    width: 80%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 14px !important;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out
}
 #carForm .btn-secondary{
  line-height: 13px !important;
 }
</style>
<script>
  'use strict';

  const baseUrl = "{{ url('/') }}";
</script>

{{-- core js files --}}



{{-- jQuery ui --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>

{{-- jQuery time-picker --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>

{{-- jQuery scrollbar --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.scrollbar.min.js') }}"></script>

{{-- bootstrap notify --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

{{-- sweet alert --}}
<script type="text/javascript" src="{{ asset('assets/js/sweet-alert.min.js') }}"></script>

{{-- bootstrap tags input --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-tagsinput.min.js') }}"></script>

{{-- bootstrap date-picker --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
{{-- js color --}}
<script type="text/javascript" src="{{ asset('assets/js/jscolor.min.js') }}"></script>

{{-- fontawesome icon picker js --}}
<script type="text/javascript" src="{{ asset('assets/js/fontawesome-iconpicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables-1.10.23.min.js') }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables.bootstrap4.min.js') }}"></script>


{{-- dropzone js --}}
<script type="text/javascript" src="{{ asset('assets/js/dropzone.min.js') }}"></script>

{{-- atlantis js --}}
<script type="text/javascript" src="{{ asset('assets/js/atlantis.js') }}"></script>

{{-- fonts and icons script --}}
<script type="text/javascript" src="{{ asset('assets/js/webfont.min.js') }}"></script>

@if (session()->has('success'))
  <script>
    'use strict';
    var content = {};

    content.message = '{{ session('success') }}';
    content.title = 'Success';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'success',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

@if (session()->has('warning'))
  <script>
    'use strict';
    var content = {};

    content.message = '{{ session('warning') }}';
    content.title = 'Warning!';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'warning',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif



{{-- select2 js --}}
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>

{{-- admin-main js --}}
<script type="text/javascript" src="{{ asset('assets/js/admin-main.js') }}"></script>

  <script>
    'use strict';
    var storeUrl = "{{ route('car.imagesstore') }}";
    var removeUrl = "{{ route('user.car.imagermv') }}";
    var getBrandUrl = "{{ route('user.get-car.brand.model') }}";
    const account_status = "{{ Auth::guard('web')->user()->status }}";
    const secret_login = "{{ Session::get('secret_login') }}";
  </script>

  <script src="{{ asset('assets/js/car.js') }}"></script>
  <script>
    var labels = "{!! $labels !!}";
    var values = "{!! $values !!}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-dropzone.js') }}"></script>
@endsection
