<!DOCTYPE html>
<html lang="xxx" dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="KreativDev">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="manifest" crossorigin="use-credentials" href="{{ asset('manifest.json') }}">
  {{-- title --}}
  <title>@yield('pageHeading') {{ '| ' . $websiteInfo->website_title }}</title>
  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">

  @php
    $primaryColor = $basicInfo->primary_color;
    // check, whether color has '#' or not, will return 0 or 1
    function checkColorCode($color)
    {
        return preg_match('/^#[a-f0-9]{6}/i', $color);
    }
    
    // if, primary color value does not contain '#', then add '#' before color value
    if (isset($primaryColor) && checkColorCode($primaryColor) == 0) {
        $primaryColor = '#' . $primaryColor;
    }
    
    // change decimal point into hex value for opacity
    function rgb($color = null)
    {
        if (!$color) {
            echo '';
        }
        $hex = htmlspecialchars($color);
        [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
        echo "$r, $g, $b";
    }
  @endphp
  @includeIf('frontend.partials.styles.styles-v3')
  <style>
    :root {
      --color-primary: {{ $primaryColor }};
      --color-primary-rgb: {{ rgb(htmlspecialchars($primaryColor)) }};
    }
  </style>


</head>

<body dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">
  @includeIf('frontend.partials.header.header-v3')

  @yield('content')

  @includeIf('frontend.partials.popups')
  @includeIf('frontend.partials.footer.footer-v3')

  {{-- cookie alert --}}
  @if (!is_null($cookieAlertInfo) && $cookieAlertInfo->cookie_alert_status == 1)
    @include('cookie-consent::index')
  @endif

  {{-- WhatsApp Chat Button --}}
  <div id="WAButton"></div>
  
  @if ($basicInfo->shop_status == 1)
    <!-- Floating Cart Button -->
    <div id="cartIconWrapper" class="cartIconWrapper">
      @php
        $position = $basicInfo->base_currency_symbol_position;
        $symbol = $basicInfo->base_currency_symbol;
        $totalPrice = 0;
        if (session()->has('productCart')) {
            $productCarts = session()->get('productCart');
            foreach ($productCarts as $key => $product) {
                $totalPrice += $product['price'];
            }
        }
        $totalPrice = number_format($totalPrice);
        $productCartQuantity = 0;
        if (session()->has('productCart')) {
            foreach (session()->get('productCart') as $value) {
                $productCartQuantity = $productCartQuantity + $value['quantity'];
            }
        }
      @endphp
      <a href="{{ route('shop.cart') }} " class="d-block" id="cartIcon">
        <div class="cart-length">
          <i class="fal fa-shopping-bag"></i>
          <span class="length totalItems">
            {{ $productCartQuantity }} {{ __('Items') }}
          </span>
        </div>
        <div class="cart-total">
          {{ $position == 'left' ? $symbol : '' }}<span
            class="totalPrice">{{ $totalPrice }}</span>{{ $position == 'right' ? $symbol : '' }}
        </div>
      </a>
    </div>
    <!-- Floating Cart Button End-->
  @endif

  @includeIf('frontend.partials.scripts.scripts-v3')
  @includeIf('frontend.partials.toastr')

</body>

</html>
