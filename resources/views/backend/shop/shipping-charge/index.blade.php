@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Shipping Charges') }}</h4>
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
        <a href="#">{{ __('Shipping Charges') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Shipping Charges') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i> {{ __('Add') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($charges) == 0)
                <h3 class="text-center mt-2">{{ __('NO SHIPPING CHARGE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Short Text') }}</th>
                        <th scope="col">{{ __('Charge') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($charges as $charge)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            {{ strlen($charge->title) > 30 ? mb_substr($charge->title, 0, 30, 'UTF-8') . '...' : $charge->title }}
                          </td>
                          <td>
                            {{ strlen($charge->short_text) > 50 ? mb_substr($charge->short_text, 0, 50, 'UTF-8') . '...' : $charge->short_text }}
                          </td>
                          <td>
                            {{ $currencyInfo->base_currency_symbol_position == 'left' ? $currencyInfo->base_currency_symbol : '' }}{{ $charge->shipping_charge }}{{ $currencyInfo->base_currency_symbol_position == 'right' ? $currencyInfo->base_currency_symbol : '' }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 btn-sm editBtn" href="#" data-toggle="modal" data-target="#editModal" data-id="{{ $charge->id }}" data-title="{{ $charge->title }}" data-short_text="{{ $charge->short_text }}" data-shipping_charge="{{ $charge->shipping_charge }}" data-serial_number="{{ $charge->serial_number }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.shop_management.delete_charge', ['id' => $charge->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-sm btn-sm deleteBtn">
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
  @include('backend.shop.shipping-charge.create')

  {{-- edit modal --}}
  @include('backend.shop.shipping-charge.edit')
@endsection
