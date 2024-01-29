@extends("frontend.layouts.layout-v$settings->theme_version")
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->vendor_signup_page_title ? $pageHeading->vendor_signup_page_title : __('Signup') }}
  @else
    {{ __('Signup') }}
  @endif
@endsection
@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keywords_vendor_signup }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_vendor_signup }}
  @endif
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->vendor_signup_page_title : __('Signup'),
  ])
  <div class="user-dashboard pt-20 pb-60">
    <div class="container">
      
  
      
  <div class="row gx-xl-5">
  
       @includeIf('vendors.partials.side-custom')
   

    
    <div class="col-md-9">
  @php
    $current_package = App\Http\Helpers\VendorPermissionHelper::packagePermission(Auth::guard('vendor')->user()->id);
  @endphp
  @if ($current_package != '[]')
    @if (vendorTotalAddedCar() > $current_package->number_of_car_add)
      @php
        $car_add = 'over';
      @endphp
      <div class="mt-2 mb-4">
        <div class="alert alert-danger text-dark">
          <ul>
            <li>{{ __('You have added total ') . vendorTotalAddedCar() }} {{ __(' cars.') }}</li>
            <li>{{ __('Your current package supports') . ' ' . $current_package->number_of_car_add . ' cars.' }} </li>
            <li>{{ __('You have to remove ') }}
              {{ vendorTotalAddedCar() - $current_package->number_of_car_add . __(' cars  to enable car editing.') }}</li>
          </ul>
        </div>
      </div>
    @else
      @php
        $car_add = '';
      @endphp
    @endif
    @if (vendorTotalFeaturedCar() > $current_package->number_of_car_featured)
      @php
        $car_featured = 'over';
      @endphp
      <div class="mt-2 mb-4">
        <div class="alert alert-danger text-dark">
          <ul>
            <li>{{ __('You have total  ') . vendorTotalFeaturedCar() . ' featured cars.' }}</li>
            <li>
              {{ __('With your current package you can feature ') . $current_package->number_of_car_featured . __(' cars.') }}
            </li>
            <li>{{ __('Your cars has been removed from featured cars section of our website.') }}
            </li>
            <li>{{ __('You have to unfeature ') }}
              {{ vendorTotalFeaturedCar() - $current_package->number_of_car_featured . __(' cars  to show your cars in featured cars section of our website.') }}
            </li>
          </ul>

        </div>
      </div>
    @else
      @php
        $car_featured = '';
      @endphp
    @endif
  @else
    @php
      $can_car_add = 0;
      $car_add = '';
      $car_featured = 'over';
      
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
    @if (Session::get('vendor_profcompleted')==0)
    <div class="alert alert-warning text-dark">
        {{ __('Your profile is not completed, please complete profile.') }} <a href="{{ route('vendor.edit.profile') }}">
                    <strong style="color: red;">{{ __('Update profile') }}</strong>
                  </a>
      </div>
      @endif
  @endif



  <!-- <div class="page-header">
    <h4 class="page-title">{{ __('Cars') }}</h4>
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
        <a href="#">{{ __('Cars Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Cars') }}</a>
      </li>
    </ul>
  </div> -->

  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Ads') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
            @if (count($cars) != 0)  <a href="{{ route('vendor.cars_management.create_car') }}" class="btn btn-primary btn-sm float-right"><i
                  class="fas fa-plus"></i> {{ __('Place an Ad') }}</a> @endif

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('vendor.car_management.bulk_delete.car') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($cars) == 0)
                <h3 class="">{{ __('You have not placed any Ads yet') }}</h3>
                <small class="text-center">What are you waiting for? Start selling today</small><br>
                <a href="{{ route('vendor.cars_management.create_car') }}" class="btn btn-primary btn-sm float-right"><i
                  class="fas fa-plus"></i> {{ __('Place an Ad') }}</a>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Brand') }}</th>
                        <th scope="col">{{ __('Model') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($cars as $car)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $car->id }}">
                          </td>
                          <td>
                            @php
                              $car_content = $car->car_content;
                              if (is_null($car_content)) {
                                  $car_content = $car->car_content()->first();
                              }
                             
                            @endphp
                            <a href="{{ route('frontend.car.details', ['slug' => $car_content->slug, 'id' => $car->id]) }}"
                              target="_blank">
                              {{ strlen(@$car_content->title) > 50 ? mb_substr(@$car_content->title, 0, 50, 'utf-8') . '...' : @$car_content->title }}
                            </a>
                          </td>
                          <td>
                            @php
                              if ($car->car_content) {
                                  $brand = $car->car_content->brand()->first();
                              } else {
                                  $brand = null;
                              }
                            @endphp
                            {{ $brand != null ? $brand['name'] : '-' }}
                          </td>
                          <td>
                            @php
                              if ($car->car_content) {
                                  $model = $car->car_content->model()->first();
                              } else {
                                  $model = null;
                              }
                            @endphp
                            {{ $model != null ? $model['name'] : '-' }}
                          </td>
                          <td>
                            <form id="featureForm{{ $car->id }}" class="d-inline-block"
                              action="{{ route('vendor.cars_management.update_featured_car') }}" method="post">
                              @csrf
                              <input type="hidden" name="carId" value="{{ $car->id }}">
                              <input type="hidden" name="car_featured" value="{{ $car_featured }}">
                              <select
                                class="form-control {{ $car->is_featured == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
                                name="is_featured"
                                onchange="document.getElementById('featureForm{{ $car->id }}').submit();">
                                <option value="1" {{ $car->is_featured == 1 ? 'selected' : '' }}>
                                  {{ __('Yes') }}
                                </option>
                                <option value="0" {{ $car->is_featured == 0 ? 'selected' : '' }}>
                                  {{ __('No') }}
                                </option>
                              </select>
                            </form>
                          </td>

                          <td>
                            <form id="statusForm{{ $car->id }}" class="d-inline-block"
                              action="{{ $car_add != 'over' ? route('vendor.cars_management.update_car_status') : '#' }} "
                              method="post">
                              @csrf
                              <input type="hidden" name="carId" value="{{ $car->id }}">

                              <select
                                class="form-control {{ $car->status == 1 ? 'bg-success' : 'bg-danger' }} form-control-sm"
                                name="status"
                                onchange="document.getElementById('statusForm{{ $car->id }}').submit();"
                                {{ $car_add == 'over' ? 'disabled' : '#' }}>
                                <option value="1" {{ $car->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $car->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>

                          <td>
                            <a class="btn btn-secondary btn-sm mr-1 mt-1"
                              href="{{ $car_add != 'over' ? route('vendor.cars_management.edit_car', $car->id) : '#' }}"
                              disabled>
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('vendor.cars_management.delete_car') }}" method="post">
                              @csrf
                              <input type="hidden" name="car_id" value="{{ $car->id }}">

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

      </div>
    </div>
  </div>
</div>
</div></div>
</div>
@endsection
