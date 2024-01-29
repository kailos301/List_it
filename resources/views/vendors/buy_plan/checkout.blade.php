@extends('vendors.layout')
@php
  Config::set('app.timezone', App\Models\BasicSettings\Basic::first()->timezone);
@endphp
@section('content')
  @if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <strong>{{ $message }}</strong>
    </div>
  @endif
  @if (!empty($membership) && ($membership->package->term == 'lifetime' || $membership->is_trial == 1))
    <div class="alert bg-warning alert-warning text-white text-center">
      <h3>{{ __('If you purchase this package') }} <strong class="text-dark">({{ $package->title }})</strong>,
        {{ __('then your current package') }} <strong class="text-dark">({{ $membership->package->title }}@if ($membership->is_trial == 1)
            <span class="badge badge-secondary">{{ __('Trial') }}</span>
          @endif)</strong>
        {{ __('will be replaced immediately') }}
      </h3>
    </div>
  @endif
  <div class="row justify-content-center align-items-center mb-1">
    <div class="col-md-1 pl-md-0">
    </div>
    <div class="col-md-6 pl-md-0 pr-md-0">
      <div class="card card-pricing card-pricing-focus card-secondary">
        <form id="my-checkout-form" action="{{ route('vendor.plan.checkout') }}" method="post"
          enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="package_id" value="{{ $package->id }}">
          <input type="hidden" name="vendor_id" value="{{ auth()->id() }}">
          <input type="hidden" name="payment_method" id="payment" value="{{ old('payment_method') }}">
          <div class="card-header">
            <h4 class="card-title">{{ $package->title }}</h4>
            <div class="card-price">
              <span class="price">{{ $package->price == 0 ? 'Free' : format_price($package->price) }}</span>
              <span class="text">/{{ $package->term }}</span>
            </div>
          </div>
          <div class="card-body">
            <ul class="specification-list">
              <li>
                <span class="name-specification">{{ __('Membership') }}</span>
                <span class="status-specification">{{ __('Yes') }}</span>
              </li>
              <li>
                <span class="name-specification">{{ __('Start Date') }}</span>
                @if (
                    (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                        (!empty($membership) && $membership->is_trial == 1))
                  <input type="hidden" name="start_date"
                    value="{{ \Illuminate\Support\Carbon::yesterday()->format('d-m-Y') }}">
                  <span class="status-specification">{{ \Illuminate\Support\Carbon::today()->format('d-m-Y') }}</span>
                @else
                  <input type="hidden" name="start_date"
                    value="{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}">
                  <span
                    class="status-specification">{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? \Carbon\Carbon::yesterday())->addDay()->format('d-m-Y') }}</span>
                @endif
              </li>
              <li>
                <span class="name-specification">{{ __('Expire Date') }}</span>
                <span class="status-specification">
                  @if ($package->term == 'monthly')
                    @if (
                        (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                            (!empty($membership) && $membership->is_trial == 1))
                      {{ \Illuminate\Support\Carbon::parse(now())->addMonth()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Illuminate\Support\Carbon::parse(now())->addMonth()->format('d-m-Y') }}">
                    @else
                      {{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addMonth()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addMonth()->format('d-m-Y') }}">
                    @endif
                  @elseif($package->term == 'lifetime')
                    {{ __('Lifetime') }}
                    <input type="hidden" name="expire_date"
                      value="{{ \Illuminate\Support\Carbon::maxValue()->format('d-m-Y') }}">
                  @else
                    @if (
                        (!empty($previousPackage) && $previousPackage->term == 'lifetime') ||
                            (!empty($membership) && $membership->is_trial == 1))
                      {{ \Illuminate\Support\Carbon::parse(now())->addYear()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Illuminate\Support\Carbon::parse(now())->addYear()->format('d-m-Y') }}">
                    @else
                      {{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addYear()->format('d-m-Y') }}
                      <input type="hidden" name="expire_date"
                        value="{{ \Illuminate\Support\Carbon::parse($membership->expire_date ?? now())->addYear()->format('d-m-Y') }}">
                    @endif
                  @endif
                </span>
              </li>
              <li>
                <span class="name-specification">{{ __('Total Cost') }}</span>
                <input type="hidden" name="price" value="{{ $package->price }}">
                <span class="status-specification">
                  {{ $package->price == 0 ? 'Free' : format_price($package->price) }}
                </span>
              </li>
              @if ($package->price != 0)
                <li>
                  <div class="form-group px-0">
                    <label class="text-white">{{ __('Payment Method') }}</label>
                    <select name="payment_method" class="form-control input-solid" id="payment-gateway" required>
                      <option value="" disabled selected>{{ __('Select a Payment Method') }}</option>
                      @foreach ($payment_methods as $payment_method)
                        <option value="{{ $payment_method->name }}"
                          {{ old('payment_method') == $payment_method->name ? 'selected' : '' }}>
                          {{ $payment_method->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </li>
              @endif
              <div id="instructions" class="text-left"></div>
              <input type="hidden" name="is_receipt" value="0" id="is_receipt">

              <div id="stripe-element">
                <!-- A Stripe Element will be inserted here. -->
              </div>
              <!-- Used to display form errors -->
              <div id="stripe-errors" class="pb-2 text-danger text-left" role="alert"></div>

              {{-- START: Authorize.net Card Details Form --}}
              <div class="row gateway-details pt-3" id="tab-anet" style="display: none;">
                <div class="col-lg-6">
                  <div class="form-group mb-3">
                    <input class="form-control" type="text" id="anetCardNumber" placeholder="Card Number" disabled />
                  </div>
                </div>
                <div class="col-lg-6 mb-3">
                  <div class="form-group">
                    <input class="form-control" type="text" id="anetExpMonth" placeholder="Expire Month" disabled />
                  </div>
                </div>
                <div class="col-lg-6 ">
                  <div class="form-group">
                    <input class="form-control" type="text" id="anetExpYear" placeholder="Expire Year" disabled />
                  </div>
                </div>
                <div class="col-lg-6 ">
                  <div class="form-group">
                    <input class="form-control" type="text" id="anetCardCode" placeholder="Card Code" disabled />
                  </div>
                </div>
                <input type="hidden" name="opaqueDataValue" id="opaqueDataValue" disabled />
                <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor" disabled />
                <ul id="anetErrors" style="display: none;"></ul>
              </div>
              {{-- END: Authorize.net Card Details Form --}}
            </ul>
          </div>
          <div class="card-footer">
            <button class="btn btn-light btn-block" type="submit"><b>{{ __('Checkout Now') }}</b></button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    "use strict";
    $(document).ready(function() {
      $("#payment-gateway").on('change', function() {
        let offline = @php echo json_encode($offline) @endphp;
        let data = [];
        offline.map(({
          id,
          name
        }) => {
          data.push(name);
        });
        let paymentMethod = $("#payment-gateway").val();

        $(".gateway-details").hide();
        $(".gateway-details input").attr('disabled', true);

        // show or hide stripe card inputs
        if (paymentMethod == 'Stripe') {
          $('#stripe-element').removeClass('d-none');
        } else {
          $('#stripe-element').addClass('d-none');
        }

        if (paymentMethod == 'Authorize.net') {
          $("#tab-anet").show();
          $("#tab-anet input").removeAttr('disabled');
        }

        if (data.indexOf(paymentMethod) != -1) {
          let formData = new FormData();
          formData.append('name', paymentMethod);
          $.ajax({
            url: '{{ route('vendor.payment.instructions') }}',
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            success: function(data) {
              let instruction = $("#instructions");
              let instructions = `<div class="gateway-desc">${data.instructions}</div>`;
              if (data.description != null) {
                var description = `<div class="gateway-desc"><p>${data.description}</p></div>`;
              } else {
                var description = `<div></div>`;
              }
              let receipt = `<div class="form-element mb-2">
                                               <label>Receipt<span>*</span></label><br>
                                               <input type="file" name="receipt" value="" class="file-input" required>
                                               <p class="mb-0 text-warning">** Receipt image must be .jpg / .jpeg / .png</p>
                                            </div>`;
              if (data.has_attachment == 1) {
                $("#is_receipt").val(1);
                let finalInstruction = instructions + description + receipt;
                instruction.html(finalInstruction);
              } else {
                $("#is_receipt").val(0);
                let finalInstruction = instructions + description;
                instruction.html(finalInstruction);
              }
              $('#instructions').fadeIn();
            },
            error: function(data) {}
          })
        } else {
          $('#instructions').fadeOut();
        }
      });
    });
  </script>

  {{-- START: Authorize.net Scripts --}}
  @php
    $anet = App\Models\PaymentGateway\OnlineGateway::find(21);
    $anerInfo = $anet->convertAutoData();
    $anetTest = $anerInfo['sandbox_check'];

    if ($anetTest == 1) {
        $anetSrc = 'https://jstest.authorize.net/v1/Accept.js';
    } else {
        $anetSrc = 'https://js.authorize.net/v1/Accept.js';
    }
  @endphp
  <script type="text/javascript" src="{{ $anetSrc }}" charset="utf-8"></script>
  {{-- END: Authorize.net Scripts --}}

  <script>
    let stripe_key = "{{ $stripe_key }}";
    let public_key = "{{ $anerInfo['public_key'] }}";
    let login_id = "{{ $anerInfo['login_id'] }}";
  </script>

  <script src="{{ asset('assets/js/vendor-checkout.js') }}"></script>
@endsection
