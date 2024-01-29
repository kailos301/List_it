<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ __('Invoice') }}</title>
  <link rel="stylesheet" href="{{ asset('assets/css/pdf.css') }}">
</head>

<body>
  <div class="main">
    <table class="heading">
      <tr>
        <td>
          @if (!empty($websiteInfo->logo))
            <img loading="lazy" src="{{ asset('assets/img/' . $websiteInfo->logo) }}" height="40"
              class="d-inline-block">
          @else
            <img loading="lazy" src="{{ asset('assets/admin/img/noimage.jpg') }}" height="40" class="d-inline-block">
          @endif
        </td>
        <td class="text-right strong invoice-heading">{{ __('INVOICE') }}</td>
      </tr>
    </table>
    <div class="header">
      <div class="ml-20">
        <table class="text-left">
          <tr>
            <td class="strong small gry-color">{{ __('Bill to') . ':' }}</td>
          </tr>
          <tr>
            <td class="strong">{{ ucfirst($member['first_name']) . ' ' . ucfirst($member['last_name']) }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>{{ __('Username') . ':' }} </strong>{{ $member['username'] }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>{{ __('Email') . ':' }} </strong> {{ $member['email'] }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>{{ __('Phone') . ':' }} </strong> {{ $phone }}</td>
          </tr>
        </table>
      </div>
      <div class="order-details">
        <table class="text-right">
          <tr>
            <td class="strong">{{ __('Order Details') . ':' }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>{{ __('Order ID') . ':' }}</strong> #{{ $order_id }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>Total:</strong>
              {{ $websiteInfo->base_currency_text_position == 'left' ? $websiteInfo->base_currency_text : '' }}
              {{ $amount }}
              {{ $websiteInfo->base_currency_text_position == 'right' ? $websiteInfo->base_currency_text : '' }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>{{ __('Payment Method') . ':' }}</strong>
              {{ $request['payment_method'] }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>{{ __('Payment Status') . ':' }}</strong>{{ __('Completed') }}</td>
          </tr>
          <tr>
            <td class="gry-color small"><strong>{{ __('Order Date') . ':' }}</strong>
              {{ \Carbon\Carbon::now()->format('d/m/Y') }}</td>
          </tr>
        </table>
      </div>
    </div>

    <div class="package-info">
      <table class="padding text-left small border-bottom">
        <thead>
          <tr class="gry-color info-titles">
            <th width="20%">{{ __('Package Title') }}</th>
            <th width="20%">{{ __('Start Date') }}</th>
            <th width="20%">{{ __('Expire Date') }}</th>
            <th width="20%">{{ __('Currency') }}</th>
            <th width="20%">{{ __('Total') }}</th>
          </tr>
        </thead>
        <tbody class="strong">

          <tr class="text-center">
            <td>{{ $package_title }}</td>
            <td>{{ $request['start_date'] }}</td>
            <td>
              {{ \Carbon\Carbon::parse($request['expire_date'])->format('Y') == '9999' ? 'Lifetime' : $request['expire_date'] }}
            </td>
            <td>{{ $base_currency_text }}</td>
            <td>
              {{ $amount == 0 ? 'Free' : $amount }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <table class="mt-80">
      <tr>
        <td class="text-right regards">{{ __('Thanks & Regards') . ',' }}</td>
      </tr>
      <tr>
        <td class="text-right strong regards">{{ $websiteInfo->website_title }}</td>
      </tr>
    </table>
  </div>


</body>

</html>
