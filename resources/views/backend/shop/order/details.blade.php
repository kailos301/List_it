@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Order Details') }}</h4>
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
        <a href="#">{{ __('Orders') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Order Details') }}</a>
      </li>
    </ul>
  </div>
  <div class="text-right mb-3">
    <a href="{{ route('admin.shop_management.orders') }}" class="btn btn-primary">{{ __('Back') }}</a>
  </div>

  <div class="row">
    @php
      $position = $details->currency_text_position;
      $currency = $details->currency_text;
    @endphp

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Order No.') . ' ' . '#' . $details->order_number }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Order Date') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                {{ date_format($details->created_at, 'M d, Y') }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Cart Total') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->total, 2, '.', ',') }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Discount') }} <span class="text-success">(<i class="far fa-minus"></i>)</span> :</strong>
              </div>

              <div class="col-lg-6">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Subtotal') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                @php
                  $total = floatval($details->total);
                  $discount = floatval($details->discount);
                  $subtotal = $total - $discount;
                @endphp

                {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($subtotal, 2, '.', ',') }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Shipping Cost') }} <span class="text-danger">(<i class="far fa-plus"></i>)</span>
                  :</strong>
              </div>

              <div class="col-lg-6">
                @if (is_null($details->shipping_cost))
                  -
                @else
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->shipping_cost }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Tax') }} {{ '(' . $tax->product_tax_amount . '%)' }} <span class="text-danger">(<i
                      class="far fa-plus"></i>)</span> :</strong>
              </div>

              <div class="col-lg-6">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $details->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Grand Total') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($details->grand_total, 2, '.', ',') }}{{ $position == 'right' ? ' ' . $currency : '' }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Shipping Type') . ' :' }}</strong>
              </div>

              @php $shippingMethod = $details->shippingMethod()->first(); @endphp

              <div class="col-lg-6">
                {{ is_null($shippingMethod) ? '-' : $shippingMethod->title }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Paid via') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                {{ $details->payment_method }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-6">
                <strong>{{ __('Payment Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                @if ($details->payment_status == 'completed')
                  <span class="badge badge-success">{{ __('Completed') }}</span>
                @elseif ($details->payment_status == 'pending')
                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                @else
                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                @endif
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-lg-6">
                <strong>{{ __('Order Status') . ' :' }}</strong>
              </div>

              <div class="col-lg-6">
                @if ($details->order_status == 'processing')
                  <span class="badge badge-primary">{{ __('Processing') }}</span>
                @elseif ($details->order_status == 'pending')
                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                @elseif ($details->order_status == 'completed')
                  <span class="badge badge-success">{{ __('Completed') }}</span>
                @else
                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Billing Details') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->billing_name }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->billing_email }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Phone') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->billing_phone }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Address') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->billing_address }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('City') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->billing_city }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('State') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ is_null($details->billing_state) ? '-' : $details->billing_state }}
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-lg-4">
                <strong>{{ __('Country') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->billing_country }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Shipping Details') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->shipping_name }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->shipping_email }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Phone') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->shipping_phone }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Address') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->shipping_address }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('City') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->shipping_city }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('State') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ is_null($details->shipping_state) ? '-' : $details->shipping_state }}
              </div>
            </div>

            <div class="row mb-1">
              <div class="col-lg-4">
                <strong>{{ __('Country') . ' :' }}</strong>
              </div>

              <div class="col-lg-8">
                {{ $details->shipping_country }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ __('Ordered Products') }}</h4>
        </div>

        <div class="card-body">
          <div class="table-responsive product-list">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('Image') }}</th>
                  <th>{{ __('Title') }}</th>
                  <th>{{ __('Quantity') }}</th>
                  <th>{{ __('Unit Price') }}</th>
                  <th>{{ __('Total Price') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($items as $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                      <img src="{{ asset('assets/img/products/featured-images/' . $item->featured_image) }}"
                        alt="image" width="50">
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ $item->current_price }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </td>
                    <td>
                      @php $eachItemTotal = floatval($item->current_price) * $item->quantity; @endphp

                      {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($eachItemTotal, 2, '.', ',') }}{{ $position == 'right' ? ' ' . $currency : '' }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
