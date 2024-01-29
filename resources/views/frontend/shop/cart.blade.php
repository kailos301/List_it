@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Cart') }}
@endsection

@section('style')
  <link rel="stylesheet" href="{{ asset('assets/front/css/shop.css') }}">
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->cart_page_title : __('Cart'),
  ])

  <!-- Cart-area start -->
  <div class="shopping-area cart user-dashboard pt-100 pb-60">
    <div class="container">
      @if (count($productCart) == 0)
        <div class="row text-center">
          <div class="col">
            <h3>{{ __('Cart is Empty') . '!' }}</h3>
          </div>
        </div>
      @else
        <div class="row text-center">
          <div class="col">
            <h3 id="cart-message"></h3>
          </div>
        </div>
        @php
          $totalItems = count($productCart);
          $position = $currencyInfo->base_currency_symbol_position;
          $symbol = $currencyInfo->base_currency_symbol;

          $totalPrice = 0;

          foreach ($productCart as $key => $product) {
              $totalPrice += $product['price'];
          }

          $totalPrice = number_format($totalPrice, 2, '.', '');
        @endphp
        <div class="row justify-content-center gx-xl-5" id="cart-table">
          <div class="col-lg-9">
            <form action="#">
              <div class="item-list border radius-md mb-30 table-responsive">
                <table class="shopping-table table table-borderless">
                  <thead>
                    <tr class="table-heading">
                      <th scope="col" colspan="2" class="first">{{ __('Product') }}</th>
                      <th scope="col">{{ __('Quantity') }}</th>
                      <th scope="col">{{ __('Stock') }}</th>
                      <th scope="col">{{ __('Price') }}</th>
                      <th scope="col">{{ __('Total') }}</th>
                      <th scope="col" class="last">{{ __('Remove') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $cart_total_qty = 0;
                      $cart_total_price = 0;
                    @endphp
                    @foreach ($productCart as $key => $c_product)
                      @php
                        $product = App\Models\Shop\Product::where('id', $key)->first();
                      @endphp

                      <input type="hidden" class="product-id" id="{{ 'in-product-id' . $key }}"
                        value="{{ $key }}">
                      <tr class="item" id="cart-product-item{{ $key }}">

                        <td class="product-img">
                          <div class="image">
                            <a href="{{ route('shop.product_details', ['slug' => @$c_product['slug']]) }}"
                              class="lazy-container radius-md ratio ratio-1-1">
                              <img class="lazyload" src="assets/images/placeholder.png"
                                data-src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}"
                                alt="Product">
                            </a>
                          </div>
                        </td>
                        <td class="product-desc">
                          <h6>
                            <a class="product-title mb-10"
                              href="{{ route('shop.product_details', ['slug' => $c_product['slug']]) }}">
                              {{ strlen(@$c_product['title']) > 50 ? mb_substr(@$c_product['title'], 0, 50, 'UTF-8') . '...' : @$c_product['title'] }}
                            </a>
                          </h6>
                          <div class="ratings">
                            <div class="rate">
                              <div class="rating-icon" style="width: {{ $product->average_rating * 20 . '%;' }}"></div>
                            </div>
                            <span class="ratings-total">({{ $product->average_rating }})</span>
                          </div>
                        </td>
                        <td class="qty">
                          <div class="quantity-input">
                            <div class="quantity-down">
                              <i class="fal fa-minus"></i>
                            </div>
                            <input type="text" name="quantity" spellcheck="false" data-ms-editor="true"
                              value="{{ $c_product['quantity'] }}" class="product-qty">
                            <div class="quantity-up">
                              <i class="fal fa-plus"></i>
                            </div>
                          </div>
                        </td>
                        <td class="product-availability">
                          @if ($c_product['type'] == 'digital')
                            <span class="badge bg-success">{{ __('Available Now') }}</span>
                          @else
                            @if ($product->stock >= $c_product['quantity'])
                              <span class="badge bg-success">{{ __('In Stock') }}</span>
                            @else
                              <span class="badge bg-danger">{{ __('Out Of Stock') }}</span>
                            @endif
                          @endif
                        </td>
                        <td class="product-price">
                          <h6 dir="ltr" class="m-0">{{ $position == 'left' ? $symbol : '' }}
                            <span
                              class="product-unit-price">{{ $product->current_price }}</span>{{ $position == 'right' ? $symbol : '' }}
                          </h6>
                        </td>
                        <td>
                          <h6 dir="ltr" class="m-0">{{ $position == 'left' ? $symbol : '' }}
                            <span class="per-product-total">{{ $product->current_price * $c_product['quantity'] }}</span>
                            {{ $position == 'right' ? $symbol : '' }}
                          </h6>
                        </td>
                        <td class="text-center">
                          <a href="{{ route('shop.cart.remove_product', ['id' => $key]) }}"
                            class="btn btn-remove rounded-pill mx-auto remove-product-icon"
                            data-product_id="{{ $key }}">
                            <i class="fal fa-trash-alt"></i>
                          </a>
                        </td>
                      </tr>
                      @php
                        $cart_total_qty += $c_product['quantity'];
                        $cart_total_price += $product->current_price * $c_product['quantity'];
                      @endphp
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="btn-groups text-end mb-20">
                <h6>{{ __('Total Quantity') . ' :' }} <span id="cart_total_qty">{{ $cart_total_qty }}</span></h6>
                <h6>{{ __('Total Price') . ' :' }} <span dir="ltr">{{ $position == 'left' ? $symbol : '' }}<span
                      id="cart_total_price">{{ $cart_total_price }}</span>{{ $position == 'right' ? $symbol : '' }}</span>
                </h6>
              </div>
              <div class="btn-groups text-end mb-40">
                <a href="{{ route('shop.update_cart') }}" class="btn btn-md btn-primary" title="{{ __('Update Cart') }}"
                  id="update-cart-btn">{{ __('Update Cart') }}</a>
                <a href="{{ route('shop.checkout') }}" class="btn btn-md btn-primary" title="{{ __('Checkout') }}"
                  target="_self">{{ __('Checkout') }}</a>
              </div>
            </form>
          </div>
        </div>
      @endif
    </div>
  </div>
  <!-- Cart-area end -->
@endsection

@section('script')
  <script>
    'use strict';
    let cartEmptyTxt = "{{ __('Cart is Empty') . '!' }}";
  </script>
  <script src="{{ asset('assets/front/js/shop.js') }}"></script>
@endsection
