@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Checkout') }}
@endsection

@section('metaKeywords')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_keyword_home }}
  @endif
@endsection

@section('metaDescription')
  @if (!empty($seoInfo))
    {{ $seoInfo->meta_description_home }}
  @endif
@endsection
@section('style')
  <link rel="stylesheet" href="{{ asset('assets/css/shop.css') }}">
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->checkout_page_title : __('Checkout'),
  ])

  <!-- Checkout-area start -->
  <div class="shopping-area pt-100 pb-60">
    <div class="container">
      <form action="{{ route('shop.purchase_product') }}" method="POST" enctype="multipart/form-data" id="payment-form">
        @csrf
        <div class="row gx-xl-5">
          <div class="col-lg-8">
            <div class="billing-details">
              <h4 class="mb-20">{{ __('Billing Details') }}</h4>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="firstName">{{ __('Name') . '*' }}</label>
                    <input type="text" class="form-control" name="billing_name"
                      placeholder="{{ __('Enter Full Name') }}"
                      value="{{ !empty($authUser) ? $authUser->name : old('billing_name') }}">
                    @error('billing_name')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="">{{ __('Phone Number') . '*' }}</label>
                    <input id="" type="text" class="form-control" name="billing_phone"
                      placeholder="{{ __('Phone Number') }}"
                      value="{{ !empty($authUser) ? $authUser->phone : old('billing_phone') }}">
                    @error('billing_phone')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="">{{ __('Email Address') }}</label>
                    <input type="email" class="form-control" name="billing_email"
                      placeholder="{{ __('Email Address') }}"
                      value="{{ !empty($authUser) ? $authUser->email : Auth::guard('web')->user()->email }}">
                    @error('billing_email')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="">{{ __('City') . '*' }}</label>
                    <input type="text" class="form-control" name="billing_city" placeholder="{{ __('City') }}"
                      value="{{ !empty($authUser) ? $authUser->city : old('billing_city') }}">
                    @error('billing_city')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="">{{ __('State') }}</label>
                    <input id="" type="text" class="form-control" name="billing_state"
                      placeholder="{{ __('State') }}"
                      value="{{ !empty($authUser) ? $authUser->state : old('billing_state') }}">

                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <label for="">{{ __('Country') }}</label>
                    <input id="" type="text" class="form-control" name="billing_country"
                      placeholder="{{ __('Country') }}"
                      value="{{ !empty($authUser) ? $authUser->country : old('billing_country') }}">
                    @error('billing_country')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group mb-3">
                    <label for="address">{{ __('Address') }}</label>
                    <textarea name="billing_address" class="form-control" rows="2">{{ !empty($authUser) ? $authUser->address : old('billing_address') }}</textarea>
                    @error('billing_address')
                      <p class="mt-2 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="ship-details mb-10">
              <div class="form-group mb-20">
                <div class="custom-checkbox">
                  <input class="input-checkbox" type="checkbox" name="checkbox" id="differentaddress" value="1"
                    {{ old('checkbox') == 1 ? ' checked' : '' }}>
                  <label class="form-check-label" data-bs-toggle="collapse" data-target="#collapseAddress"
                    href="#collapseAddress" aria-controls="collapseAddress"
                    for="differentaddress"><span>{{ __('Ship to a different address') . '?' }}</span></label>
                </div>
              </div>
              <div id="collapseAddress" class="collapse {{ old('checkbox') == 1 ? 'show' : '' }}">
                <h4 class="mb-20">{{ __('Shipping Details') }}</h4>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="firstName">{{ __('Name') . '*' }}</label>
                      <input type="text" class="form-control" name="shipping_name" placeholder="{{ __('Name') }}"
                        value="{{ Auth::guard('web')->user()->name }}">
                      @error('shipping_name')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="phone">{{ __('Phone Number') . '*' }}</label>
                      <input id="phone" type="text" class="form-control" name="shipping_phone"
                        placeholder="{{ __('Phone Number') }}"
                        value="{{ !empty($authUser) ? $authUser->phone : old('shipping_phone') }}">
                      @error('shipping_phone')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="email">{{ __('Email Address') . '*' }}</label>
                      <input id="email" type="email" class="form-control" name="shipping_email"
                        placeholder="{{ __('Email Address') }}"
                        value="{{ !empty($authUser) ? $authUser->email : Auth::guard('web')->user()->email }}">
                      @error('shipping_email')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="city">{{ __('City') . '*' }}</label>
                      <input id="city" type="text" class="form-control" name="shipping_city"
                        placeholder="{{ __('City') }}"
                        value="{{ !empty($authUser) ? $authUser->city : old('shipping_city') }}">
                      @error('shipping_city')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="state">{{ __('State') }}</label>
                      <input id="state" type="text" class="form-control" name="shipping_state"
                        placeholder="{{ __('State') }}"
                        value="{{ !empty($authUser) ? $authUser->state : old('shipping_state') }}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group mb-3">
                      <label for="country">{{ __('Country') . '*' }}</label>
                      <input id="country" type="text" class="form-control" name="shipping_country"
                        placeholder="{{ __('Country') }}"
                        value="{{ !empty($authUser) ? $authUser->country : old('shipping_country') }}">
                      @error('shipping_country')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group mb-3">
                      <label for="address">{{ __('Address') . '*' }}</label>
                      <textarea name="shipping_address" class="form-control" rows="2" placeholder="{{ __('Address') }}">{{ !empty($authUser) ? $authUser->address : old('shipping_address') }}</textarea>
                      @error('shipping_address')
                        <p class="mt-2 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @if (!onlyDigitalItemsInCart())
              <div class="ship-details mb-10">
                <h4 class="mb-20">{{ __('Shipping Method') }}</h4>
                <table class="shopping-table table-responsive">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>{{ __('Method') }}</th>
                      <th>{{ __('Charge') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($charges as $charge)
                      <tr>
                        <td>
                          <input type="radio" id="shipping_{{ $charge->id }}" name="shipping_method"
                            value="{{ $charge->id }}" class="shipping_method"
                            data-shipping_charge="{{ $charge->shipping_charge }}"
                            {{ Session::get('shipping_id') == $charge->id ? 'checked' : '' }}>
                        </td>
                        <td>
                          <label for="shipping_{{ $charge->id }}">{{ $charge->title }}
                            <br>
                            <small>{{ $charge->short_text }}</small></label>
                        </td>
                        <td>{{ symbolPrice($charge->shipping_charge) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif

          </div>
          <div class="col-lg-4">
            <div class="order-summery form-block border radius-md mb-30">
              <h4 class="pb-15 mb-15 border-bottom">{{ __('Order Summary') }}</h4>
              @php
                $total = 0;
              @endphp
              @foreach ($productItems as $key => $item)
                @php
                  $product = App\Models\Shop\Product::where('id', $key)->first();
                  $total += $item['price'];
                  // calculate tax
                  $taxAmount = $tax->product_tax_amount;

                @endphp
                <div class="item">
                  <div class="product-img">
                    <div class="image">
                      <a target="_blank" href="{{ route('shop.product_details', ['slug' => @$item['slug']]) }}"
                        class="lazy-container radius-md ratio ratio-1-1">
                        <img class="lazyload"
                          data-src="{{ asset('assets/img/products/featured-images/' . $item['image']) }}"
                          alt="Product">
                      </a>
                    </div>
                  </div>
                  <div class="product-desc">
                    <h6 class="mb-1">
                      <a class="product-title" href="{{ route('shop.product_details', ['slug' => @$item['slug']]) }}">
                        {{ strlen(@$item['title']) > 60 ? mb_substr(@$item['title'], 0, 60, 'UTF-8') . '...' : @$item['title'] }}
                      </a>
                    </h6>
                    <div class="ratings mb-10">
                      <div class="rate">
                        <div class="rating-icon" style="width: {{ $product->average_rating * 20 . '%;' }}"></div>
                      </div>
                      <span class="ratings-total">({{ $product->average_rating }})</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 font-sm">
                      <span class="color-primary">{{ $item['quantity'] }} x
                        {{ symbolPrice($product->current_price) }}</span>
                      <h6 class="font-sm mb-0">{{ symbolPrice($item['quantity'] * $product->current_price) }}</h6>
                    </div>
                  </div>
                </div>
              @endforeach
              <hr>
              <div id="couponReload">
                @php
                  $position = $currencyInfo->base_currency_symbol_position;
                  $symbol = $currencyInfo->base_currency_symbol;
                @endphp
                <div class="sub-total d-flex justify-content-between">
                  <h6 class="font-medium color-dark mb-0">{{ __('Cart Total') }}</h6>
                  <span class="price">{{ $position == 'left' ? $symbol : '' }}<span
                      id="subtotal-amount">{{ $total }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                </div>
                @if (Session::has('discount'))
                  <div class="sub-total d-flex justify-content-between">
                    <h6 class="font-medium color-dark mb-0">{{ __('Discount') }}</h6>
                    <span class="price">- {{ $position == 'left' ? $symbol : '' }}<span
                        id="discount">{{ Session::get('discount') }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                  </div>

                  <div class="sub-total d-flex justify-content-between">
                    <h6 class="font-medium color-dark mb-0">{{ __('Subtotal ') }}</h6>
                    <span class="price">{{ $position == 'left' ? $symbol : '' }}<span
                        id="subtotal-amount">{{ $total - Session::get('discount') }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                  </div>
                @endif

                @php
                  $tax_amount = ($total - Session::get('discount')) * ($taxAmount / 100);
                @endphp

                <div class="sub-total d-flex justify-content-between">
                  <h6 class="font-medium color-dark mb-0">{{ __('Tax') }} <span
                      dir="ltr">{{ $tax->product_tax_amount . '%' }}</span></h6>
                  <span class="price">+ {{ $position == 'left' ? $symbol : '' }}<span
                      id="tax-amount">{{ number_format($tax_amount, 2, '.', ',') }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                </div>
                @if (!onlyDigitalItemsInCart())
                  @php
                    $shipping_id = Session::get('shipping_id');
                    if ($shipping_id != null) {
                        $charge = App\Models\Shop\ShippingCharge::where('id', $shipping_id)->first();
                        $shipping_charge = $charge->shipping_charge;
                    } else {
                        $charge = App\Models\Shop\ShippingCharge::first();
                        $shipping_charge = $charge->shipping_charge;
                    }
                  @endphp
                  <div class="sub-total d-flex justify-content-between">
                    <h6 class="font-medium color-dark mb-0">{{ __('Shipping Charge') }}</h6>
                    <span class="price">+ {{ $position == 'left' ? $symbol : '' }}<span
                        class="shipping-charge-amount">{{ $shipping_charge }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                  </div>
                @else
                  @php
                    $shipping_charge = 0;
                  @endphp
                @endif
                <hr>

                @php
                  // calculate grand total
                  $grandTotal = $total - Session::get('discount') + $shipping_charge + $tax_amount;
                @endphp
                <div class="total d-flex justify-content-between">
                  <h6>{{ __('Total') }}</h6>
                  <span class="price" dir="ltr">{{ $position == 'left' ? $symbol : '' }}
                    <span id="grandtotal-amount">{{ number_format($grandTotal, 2, '.', ',') }} </span>
                    {{ $position == 'right' ? $symbol : '' }}
                  </span>
                </div>
              </div>
            </div>

            <div class="form-inline mb-40">
              @csrf
              <div class="input-group radius-sm border">
                <input type="text" class="form-control" placeholder="{{ __('Enter Coupon Code') }}"
                  id="coupon-code">
                <button class="btn btn-lg btn-primary radius-sm"
                  onclick="applyCoupon(event)">{{ __('Apply') }}</button>
              </div>
            </div>

            <div class="order-payment form-block border radius-md mb-30">
              <h4 class="mb-20">{{ __('Payment Method') }}</h4>
              <div class="form-group mb-30">
                <select name="gateway" id="gateway" class="nice-select form-control payment-gateway">
                  <option value="" selected="" disabled>{{ __('Choose a Payment Method') }}</option>
                  @foreach ($onlineGateways as $onlineGateway)
                    <option @selected(old('gateway') == $onlineGateway->keyword) value="{{ $onlineGateway->keyword }}">
                      {{ __($onlineGateway->name) }}</option>
                  @endforeach

                  @if (count($offline_gateways) > 0)
                    @foreach ($offline_gateways as $offlineGateway)
                      <option @selected(old('gateway') == $offlineGateway->id) value="{{ $offlineGateway->id }}">
                        {{ __($offlineGateway->name) }}</option>
                    @endforeach
                  @endif

                </select>
                @if (Session::has('error'))
                  <p class="mt-2 text-danger">{{ Session::get('error') }}</p>
                @endif
              </div>


              <div id="stripe-element" class="mb-2">
                <!-- A Stripe Element will be inserted here. -->
              </div>
              <!-- Used to display form errors -->
              <div id="stripe-errors" class="pb-2" role="alert"></div>

              @foreach ($offline_gateways as $offlineGateway)
                <div class="@if (
                    $errors->has('attachment') &&
                        request()->session()->get('gatewayId') == $offlineGateway->id) d-block @else d-none @endif offline-gateway-info"
                  id="{{ 'offline-gateway-' . $offlineGateway->id }}">
                  @if (!is_null($offlineGateway->short_description))
                    <div class="form-group mb-4">
                      <label>{{ __('Description') }}</label>
                      <p>{{ $offlineGateway->short_description }}</p>
                    </div>
                  @endif

                  @if (!is_null($offlineGateway->instructions))
                    <div class="form-group mb-4">
                      <label>{{ __('Instructions') }}</label>
                      {!! replaceBaseUrl($offlineGateway->instructions, 'summernote') !!}
                    </div>
                  @endif

                  @if ($offlineGateway->has_attachment == 1)
                    <div class="form-group mb-4">
                      <label>{{ __('Attachment') . '*' }}</label>
                      <br>
                      <input type="file" name="attachment">
                      @error('attachment')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  @endif

                </div>
              @endforeach

              <div class="text-center">
                <button class="btn btn-lg btn-primary radius-md w-100" type="submit">{{ __('Place Order') }}
                </button>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Checkout-area end -->
@endsection
@section('script')
  <script src="https://js.stripe.com/v3/"></script>
  <script src="{{ asset('assets/js/shop.js') }}"></script>
  <script>
    let stripe_key = "{{ $stripe_key }}";
  </script>
  <script src="{{ asset('assets/front/js/product_checkout.js') }}"></script>
  <script>
    @if (old('gateway') == 'stripe')
      $(document).ready(function() {
        $('#stripe-element').removeClass('d-none');
      })
    @endif
  </script>
@endsection
