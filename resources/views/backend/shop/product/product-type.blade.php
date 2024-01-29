@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Product Type') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Manage Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Product Type') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Select Product Type') }}</div>
            </div>

            <div class="col-lg-4 mt-2 mt-lg-0">
              <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                {{ __('Back') }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="product-type">
    <div class="row">
      <div class="col-lg-6">
        <a href="{{ route('admin.shop_management.create_product', ['type' => 'digital']) }}" class="d-block">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-12">
                  <div class="col-icon mx-auto">
                    <div class="icon-big text-center icon-success bubble-shadow-small">
                      <i class="icon-screen-desktop"></i>
                    </div>
                  </div>
                </div>

                <div class="col col-stats ml-3 ml-sm-0">
                  <div class="numbers mx-auto text-center">
                    <h2 class="card-title mt-2 mb-4 text-uppercase">{{ __('Digital Product') }}</h2>
                    <p class="card-category"><strong>{{ __('Total') . ': ' }}</strong>{{ $digitalProductCount . ' ' . __('Items') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>

      <div class="col-lg-6">
        <a href="{{ route('admin.shop_management.create_product', ['type' => 'physical']) }}" class="d-block">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-12">
                  <div class="col-icon mx-auto">
                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                      <i class="icon-present"></i>
                    </div>
                  </div>
                </div>

                <div class="col col-stats ml-3 ml-sm-0">
                  <div class="numbers mx-auto text-center">
                    <h2 class="card-title mt-2 mb-4 text-uppercase">{{ __('Physical Product') }}</h2>
                    <p class="card-category"><strong>{{ __('Total') . ': ' }}</strong>{{ $physicalProductCount . ' ' . __('Items') }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
@endsection
