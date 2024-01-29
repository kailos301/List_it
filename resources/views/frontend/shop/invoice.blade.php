<!DOCTYPE html>
<html>

<head lang="{{ $currentLanguageInfo->code }}" @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>
  {{-- required meta tags --}}
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  {{-- title --}}
  <title>{{ 'Product Invoice | ' . config('app.name') }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  {{-- styles --}}
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
</head>


<body>
  @php
    $mb = '35px';
    $width = '50%';
    $ml = '18px';
    $pl = '15px';
    $pr = '15px';
    $float = 'right';
    $floatL = 'left';
  @endphp
  <div class="purchase-product-invoice my-5">
    <div class="logo text-center ml-auto mr-auto" style="margin-bottom: {{ $mb }}; max-width: 180px;">
      <img class="img-fluid" src="{{ public_path('assets/img/' . $websiteInfo->logo) }}" alt="website logo">
    </div>

    <div class="bg-primary">
      <h2 class="text-center text-light pt-2">
        {{ __('PRODUCT ORDER INVOICE') }}
      </h2>
    </div>

    @php
      $position = $orderInfo->currency_text_position;
      $currency = $orderInfo->currency_text;
    @endphp

    <div class="row clearfix">
      {{-- order details start --}}
      <div style="width: {{ $width }}; float: {{ $floatL }}; padding-left: {{ $pl }}">
        <div class="mt-4 mb-1">
          <h4><strong>{{ __('Order Details') }}</strong></h4>
        </div>

        <p>
          <strong>{{ __('Order No') . ': ' }}</strong>{{ '#' . $orderInfo->order_number }}
        </p>

        <p>
          <strong>{{ __('Order Date') . ': ' }}</strong>{{ date_format($orderInfo->created_at, 'M d, Y') }}
        </p>

        <p>
          <strong>{{ __('Price') . ': ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($orderInfo->total, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
        </p>

        <p>
          <strong>{{ __('Discount') . ': ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($orderInfo->discount, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
        </p>

        <p>
          @php
            $total = floatval($orderInfo->total);
            $discount = floatval($orderInfo->discount);
            $subtotal = $total - $discount;
          @endphp

          <strong>{{ __('Subtotal') . ': ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($subtotal, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
        </p>

        <p>
          <strong>{{ __('Shipping Cost') . ': ' }}</strong>
          @if (is_null($orderInfo->shipping_cost))
            -
          @else
            {{ $position == 'left' ? $currency . ' ' : '' }}{{ $orderInfo->shipping_cost }}{{ $position == 'right' ? ' ' . $currency : '' }}
          @endif
        </p>

        <p>
          <strong>{{ __('Tax') . ' (' . $taxData->product_tax_amount . '%): ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($orderInfo->tax, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
        </p>

        <p>
          <strong>{{ __('Grand Total') . ': ' }}</strong>{{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($orderInfo->grand_total, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
        </p>

        <p>
          <strong>{{ __('Payment Method') . ': ' }}</strong>{{ $orderInfo->payment_method }}
        </p>

        <p>
          <strong>{{ __('Payment Status') . ': ' }}</strong>{{ ucfirst($orderInfo->payment_status) }}
        </p>
      </div>
      {{-- order details end --}}

      {{-- billing details start --}}
      <div style="width: {{ $width }}; float: {{ $float }}; padding-right: {{ $pr }}">
        <div class="mt-4 mb-1">
          <h4><strong>{{ __('Billing Details') }}</strong></h4>
        </div>

        <p>
          <strong>{{ __('Name') . ': ' }}</strong>{{ $orderInfo->billing_name }}
        </p>

        <p>
          <strong>{{ __('Email') . ': ' }}</strong>{{ $orderInfo->billing_email }}
        </p>

        <p>
          <strong>{{ __('Contact Number') . ': ' }}</strong>{{ $orderInfo->billing_phone }}
        </p>

        <p>
          <strong>{{ __('Address') . ': ' }}</strong>{{ $orderInfo->billing_address }}
        </p>

        <p>
          <strong>{{ __('City') . ': ' }}</strong>{{ $orderInfo->billing_city }}
        </p>

        <p>
          <strong>{{ __('State') . ': ' }}</strong>{{ is_null($orderInfo->billing_state) ? '-' : $orderInfo->billing_state }}
        </p>

        <p>
          <strong>{{ __('Country') . ': ' }}</strong>{{ $orderInfo->billing_country }}
        </p>
      </div>
      {{-- billing details end --}}
    </div>
  </div>
  <div class="purchase-product-invoice">
    {{-- purchased items start --}}
    <div class="mt-4">
      <h4><strong>{{ __('Ordered Products') }}</strong></h4>
    </div>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th scope="col">{{ __('Name') }}</th>
          <th scope="col">{{ __('Unit Price') }}</th>
          <th scope="col">{{ __('Quantity') }}</th>
          <th scope="col">{{ __('Total Price') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($productList as $product)
          <tr>
            <td>{{ $product['title'] }}</td>
            <td>
              @php
                $price = $product['price'];
                $quantity = intval($product['quantity']);
                $unitPrice = $price / $quantity;
              @endphp

              {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($unitPrice, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
            </td>
            <td>{{ $product['quantity'] }}</td>
            <td>
              @php $totalPrice = $product['price']; @endphp

              {{ $position == 'left' ? $currency . ' ' : '' }}{{ number_format($totalPrice, 2) }}{{ $position == 'right' ? ' ' . $currency : '' }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{-- purchased items end --}}
  </div>
</body>

</html>
