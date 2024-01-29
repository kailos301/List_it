@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Online Gateways') }}</h4>
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
        <a href="#">{{ __('Payment Gateways') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Online Gateways') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_paypal_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Paypal') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Paypal Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="paypal_status" value="1" class="selectgroup-input"
                        {{ $paypal->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="paypal_status" value="0" class="selectgroup-input"
                        {{ $paypal->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('paypal_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paypal_status') }}</p>
                  @endif
                </div>

                @php $paypalInfo = json_decode($paypal->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Paypal Test Mode') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="paypal_sandbox_status" value="1" class="selectgroup-input"
                        {{ $paypalInfo['sandbox_status'] == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="paypal_sandbox_status" value="0" class="selectgroup-input"
                        {{ $paypalInfo['sandbox_status'] == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('paypal_sandbox_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paypal_sandbox_status') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paypal Client ID') }}</label>
                  <input type="text" class="form-control" name="paypal_client_id"
                    value="{{ $paypalInfo['client_id'] }}">
                  @if ($errors->has('paypal_client_id'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paypal_client_id') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paypal Client Secret') }}</label>
                  <input type="text" class="form-control" name="paypal_client_secret"
                    value="{{ $paypalInfo['client_secret'] }}">
                  @if ($errors->has('paypal_client_secret'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paypal_client_secret') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_instamojo_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Instamojo') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Instamojo Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="instamojo_status" value="1" class="selectgroup-input"
                        {{ $instamojo->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="instamojo_status" value="0" class="selectgroup-input"
                        {{ $instamojo->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('instamojo_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('instamojo_status') }}</p>
                  @endif
                </div>

                @php $instamojoInfo = json_decode($instamojo->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Instamojo Test Mode') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="instamojo_sandbox_status" value="1" class="selectgroup-input"
                        {{ $instamojoInfo['sandbox_status'] == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="instamojo_sandbox_status" value="0" class="selectgroup-input"
                        {{ $instamojoInfo['sandbox_status'] == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('instamojo_sandbox_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('instamojo_sandbox_status') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Instamojo API Key') }}</label>
                  <input type="text" class="form-control" name="instamojo_key"
                    value="{{ $instamojoInfo['key'] }}">
                  @if ($errors->has('instamojo_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('instamojo_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Instamojo Auth Token') }}</label>
                  <input type="text" class="form-control" name="instamojo_token"
                    value="{{ $instamojoInfo['token'] }}">
                  @if ($errors->has('instamojo_token'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('instamojo_token') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- paytm --}}
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_paytm_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Paytm') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Paytm Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="paytm_status" value="1" class="selectgroup-input"
                        {{ $paytm->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="paytm_status" value="0" class="selectgroup-input"
                        {{ $paytm->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('paytm_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paytm_status') }}</p>
                  @endif
                </div>

                @php $paytmInfo = json_decode($paytm->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Paytm Environment') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="paytm_environment" value="local" class="selectgroup-input"
                        {{ $paytmInfo['environment'] == 'local' ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Local') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="paytm_environment" value="production" class="selectgroup-input"
                        {{ $paytmInfo['environment'] == 'production' ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Production') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('paytm_environment'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paytm_environment') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paytm Merchant Key') }}</label>
                  <input type="text" class="form-control" name="paytm_merchant_key"
                    value="{{ $paytmInfo['merchant_key'] }}">
                  @if ($errors->has('paytm_merchant_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paytm_merchant_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paytm Merchant MID') }}</label>
                  <input type="text" class="form-control" name="paytm_merchant_mid"
                    value="{{ $paytmInfo['merchant_mid'] }}">
                  @if ($errors->has('paytm_merchant_mid'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paytm_merchant_mid') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Paytm Merchant Website') }}</label>
                  <input type="text" class="form-control" name="paytm_merchant_website"
                    value="{{ $paytmInfo['merchant_website'] }}">
                  @if ($errors->has('paytm_merchant_website'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paytm_merchant_website') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Industry Type') }}</label>
                  <input type="text" class="form-control" name="paytm_industry_type"
                    value="{{ $paytmInfo['industry_type'] }}">
                  @if ($errors->has('paytm_industry_type'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paytm_industry_type') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>



    {{-- stripe --}}
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_stripe_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Stripe') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Stripe Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="stripe_status" value="1" class="selectgroup-input"
                        {{ $stripe->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="stripe_status" value="0" class="selectgroup-input"
                        {{ $stripe->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('stripe_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('stripe_status') }}</p>
                  @endif
                </div>

                @php $stripeInfo = json_decode($stripe->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Stripe Key') }}</label>
                  <input type="text" class="form-control" name="stripe_key" value="{{ $stripeInfo['key'] }}">
                  @if ($errors->has('stripe_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('stripe_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Stripe Secret') }}</label>
                  <input type="text" class="form-control" name="stripe_secret"
                    value="{{ $stripeInfo['secret'] }}">
                  @if ($errors->has('stripe_secret'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('stripe_secret') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    {{-- flutterwave --}}
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_flutterwave_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Flutterwave') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Flutterwave Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="flutterwave_status" value="1" class="selectgroup-input"
                        {{ $flutterwave->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="flutterwave_status" value="0" class="selectgroup-input"
                        {{ $flutterwave->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('flutterwave_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('flutterwave_status') }}</p>
                  @endif
                </div>

                @php $flutterwaveInfo = json_decode($flutterwave->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Flutterwave Public Key') }}</label>
                  <input type="text" class="form-control" name="flutterwave_public_key"
                    value="{{ $flutterwaveInfo['public_key'] }}">
                  @if ($errors->has('flutterwave_public_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('flutterwave_public_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Flutterwave Secret Key') }}</label>
                  <input type="text" class="form-control" name="flutterwave_secret_key"
                    value="{{ $flutterwaveInfo['secret_key'] }}">
                  @if ($errors->has('flutterwave_secret_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('flutterwave_secret_key') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Razorpay --}}
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_razorpay_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Razorpay') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Razorpay Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="razorpay_status" value="1" class="selectgroup-input"
                        {{ $razorpay->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="razorpay_status" value="0" class="selectgroup-input"
                        {{ $razorpay->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('razorpay_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('razorpay_status') }}</p>
                  @endif
                </div>

                @php $razorpayInfo = json_decode($razorpay->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Razorpay Key') }}</label>
                  <input type="text" class="form-control" name="razorpay_key" value="{{ $razorpayInfo['key'] }}">
                  @if ($errors->has('razorpay_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('razorpay_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>{{ __('Razorpay Secret') }}</label>
                  <input type="text" class="form-control" name="razorpay_secret"
                    value="{{ $razorpayInfo['secret'] }}">
                  @if ($errors->has('razorpay_secret'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('razorpay_secret') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    {{-- mollie --}}
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_mollie_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Mollie') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Mollie Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="mollie_status" value="1" class="selectgroup-input"
                        {{ $mollie->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="mollie_status" value="0" class="selectgroup-input"
                        {{ $mollie->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('mollie_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('mollie_status') }}</p>
                  @endif
                </div>

                @php $mollieInfo = json_decode($mollie->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Mollie API Key') }}</label>
                  <input type="text" class="form-control" name="mollie_key" value="{{ $mollieInfo['key'] }}">
                  @if ($errors->has('mollie_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('mollie_key') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- paystack --}}
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_paystack_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('Paystack') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label>{{ __('Paystack Status') }}</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="paystack_status" value="1" class="selectgroup-input"
                        {{ $paystack->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Active') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="paystack_status" value="0" class="selectgroup-input"
                        {{ $paystack->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">{{ __('Deactive') }}</span>
                    </label>
                  </div>
                  @if ($errors->has('paystack_status'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paystack_status') }}</p>
                  @endif
                </div>

                @php $paystackInfo = json_decode($paystack->information, true); @endphp

                <div class="form-group">
                  <label>{{ __('Paystack Secret Key') }}</label>
                  <input type="text" class="form-control" name="paystack_key" value="{{ $paystackInfo['key'] }}">
                  @if ($errors->has('paystack_key'))
                    <p class="mt-1 mb-0 text-danger">{{ $errors->first('paystack_key') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- mercadopago --}}
    <div class="col-lg-4">
      <div class="card">
        <form action="{{ route('admin.payment_gateways.update_mercadopago_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">{{ __('MercadoPago') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="form-group">
              <label>{{ __('MercadoPago Status') }}</label>
              <div class="selectgroup w-100">
                <label class="selectgroup-item">
                  <input type="radio" name="mercadopago_status" value="1" class="selectgroup-input"
                    {{ $mercadopago->status == 1 ? 'checked' : '' }}>
                  <span class="selectgroup-button">{{ __('Active') }}</span>
                </label>
                <label class="selectgroup-item">
                  <input type="radio" name="mercadopago_status" value="0" class="selectgroup-input"
                    {{ $mercadopago->status == 0 ? 'checked' : '' }}>
                  <span class="selectgroup-button">{{ __('Deactive') }}</span>
                </label>
              </div>
              @if ($errors->has('mercadopago_status'))
                <p class="mt-1 mb-0 text-danger">{{ $errors->first('mercadopago_status') }}</p>
              @endif
            </div>

            @php $mercadopagoInfo = json_decode($mercadopago->information, true); @endphp

            <div class="form-group">
              <label>{{ __('MercadoPago Test Mode') }}</label>
              <div class="selectgroup w-100">
                <label class="selectgroup-item">
                  <input type="radio" name="mercadopago_sandbox_status" value="1" class="selectgroup-input"
                    {{ $mercadopagoInfo['sandbox_status'] == 1 ? 'checked' : '' }}>
                  <span class="selectgroup-button">{{ __('Active') }}</span>
                </label>
                <label class="selectgroup-item">
                  <input type="radio" name="mercadopago_sandbox_status" value="0" class="selectgroup-input"
                    {{ $mercadopagoInfo['sandbox_status'] == 0 ? 'checked' : '' }}>
                  <span class="selectgroup-button">{{ __('Deactive') }}</span>
                </label>
              </div>
              @if ($errors->has('mercadopago_sandbox_status'))
                <p class="mt-1 mb-0 text-danger">{{ $errors->first('mercadopago_sandbox_status') }}</p>
              @endif
            </div>

            <div class="form-group">
              <label>{{ __('MercadoPago Token') }}</label>
              <input type="text" class="form-control" name="mercadopago_token"
                value="{{ $mercadopagoInfo['token'] }}">
              @if ($errors->has('mercadopago_token'))
                <p class="mt-1 mb-0 text-danger">{{ $errors->first('mercadopago_token') }}</p>
              @endif
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>



    {{-- authorize.net --}}
    <div class="col-lg-4">
      <div class="card">
        <form class="" action="{{ route('admin.payment_gateways.update_anet_info') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-title">Authorize.Net</div>
              </div>
            </div>
          </div>


          <div class="card-body pt-5 pb-5">
            <div class="row">
              <div class="col-lg-12">
                @csrf
                @php
                  $anetInfo = json_decode($anet->information, true);
                @endphp
                <div class="form-group">
                  <label>Authorize.Net</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="status" value="1" class="selectgroup-input"
                        {{ $anet->status == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">Active</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="status" value="0" class="selectgroup-input"
                        {{ $anet->status == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">Deactive</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>Authorize.Net Test Mode</label>
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="sandbox_check" value="1" class="selectgroup-input"
                        {{ $anetInfo['sandbox_check'] == 1 ? 'checked' : '' }}>
                      <span class="selectgroup-button">Active</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="sandbox_check" value="0" class="selectgroup-input"
                        {{ $anetInfo['sandbox_check'] == 0 ? 'checked' : '' }}>
                      <span class="selectgroup-button">Deactive</span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <label>API Login ID</label>
                  <input class="form-control" name="login_id" value="{{ $anetInfo['login_id'] }}">
                  @if ($errors->has('login_id'))
                    <p class="mb-0 text-danger">{{ $errors->first('login_id') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>Transaction Key</label>
                  <input class="form-control" name="transaction_key" value="{{ $anetInfo['transaction_key'] }}">
                  @if ($errors->has('transaction_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('transaction_key') }}</p>
                  @endif
                </div>

                <div class="form-group">
                  <label>Public Client Key</label>
                  <input class="form-control" name="public_key" value="{{ $anetInfo['public_key'] }}">
                  @if ($errors->has('public_key'))
                    <p class="mb-0 text-danger">{{ $errors->first('public_key') }}</p>
                  @endif
                </div>

              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="form-group from-show-notify row">
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-success">Update</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection
