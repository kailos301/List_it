@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Report') }}</h4>
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
        <a href="#">{{ __('Report') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form action="{{ route('admin.shop_management.report') }}" method="GET">
                <div class="row no-gutters">
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('From') }}</label>
                      <input name="from" type="text" class="form-control datepicker" placeholder="Select Start Date"
                        value="{{ !empty(request()->input('from')) ? request()->input('from') : '' }}" readonly
                        autocomplete="off">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('To') }}</label>
                      <input name="to" type="text" class="form-control datepicker" placeholder="Select To Date"
                        value="{{ !empty(request()->input('to')) ? request()->input('to') : '' }}" readonly
                        autocomplete="off">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment Gateways') }}</label>
                      <select class="form-control h-42" name="payment_gateway">
                        <option value="" {{ empty(request()->input('payment_gateway')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>

                        @if (count($onlineGateways) > 0)
                          @foreach ($onlineGateways as $onlineGateway)
                            <option value="{{ $onlineGateway->keyword }}"
                              {{ request()->input('payment_gateway') == $onlineGateway->keyword ? 'selected' : '' }}>
                              {{ $onlineGateway->name }}
                            </option>
                          @endforeach
                        @endif

                        @if (count($offlineGateways) > 0)
                          @foreach ($offlineGateways as $offlineGateway)
                            <option value="{{ $offlineGateway->name }}"
                              {{ request()->input('payment_gateway') == $offlineGateway->name ? 'selected' : '' }}>
                              {{ $offlineGateway->name }}
                            </option>
                          @endforeach
                        @endif
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment Status') }}</label>
                      <select class="form-control h-42" name="payment_status">
                        <option value="" {{ empty(request()->input('payment_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('payment_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="pending" {{ request()->input('payment_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="rejected"
                          {{ request()->input('payment_status') == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Order Status') }}</label>
                      <select class="form-control h-42" name="order_status">
                        <option value="" {{ empty(request()->input('order_status')) ? 'selected' : '' }}>
                          {{ __('All') }}
                        </option>
                        <option value="pending" {{ request()->input('order_status') == 'pending' ? 'selected' : '' }}>
                          {{ __('Pending') }}
                        </option>
                        <option value="processing"
                          {{ request()->input('order_status') == 'processing' ? 'selected' : '' }}>
                          {{ __('Processing') }}
                        </option>
                        <option value="completed"
                          {{ request()->input('order_status') == 'completed' ? 'selected' : '' }}>
                          {{ __('Completed') }}
                        </option>
                        <option value="rejected" {{ request()->input('order_status') == 'rejected' ? 'selected' : '' }}>
                          {{ __('Rejected') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <button type="submit" class="btn btn-primary btn-sm ml-lg-3 card-header-button">
                      {{ __('Submit') }}
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <a href="{{ route('admin.shop_management.export_report') }}"
                class="btn btn-success btn-sm float-lg-right card-header-button">
                <i class="fas fa-file-export"></i> {{ __('Export') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) == 0)
                <h3 class="text-center mt-3">{{ __('NO ORDER FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-2">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Order No.') }}</th>
                        <th scope="col">{{ __('Billing Name') }}</th>
                        <th scope="col">{{ __('Billing Email') }}</th>
                        <th scope="col">{{ __('Billing Phone') }}</th>
                        <th scope="col">{{ __('Billing Address') }}</th>
                        <th scope="col">{{ __('Billing City') }}</th>
                        <th scope="col">{{ __('Billing State') }}</th>
                        <th scope="col">{{ __('Billing Country') }}</th>
                        <th scope="col">{{ __('Shipping Name') }}</th>
                        <th scope="col">{{ __('Shipping Email') }}</th>
                        <th scope="col">{{ __('Shipping Phone') }}</th>
                        <th scope="col">{{ __('Shipping Address') }}</th>
                        <th scope="col">{{ __('Shipping City') }}</th>
                        <th scope="col">{{ __('Shipping State') }}</th>
                        <th scope="col">{{ __('Shipping Country') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Shipping Method') }}</th>
                        <th scope="col">{{ __('Shipping Cost') }}</th>
                        <th scope="col">{{ __('Tax') }}</th>
                        <th scope="col">{{ __('Grand Total') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Order Status') }}</th>
                        <th scope="col">{{ __('Order Date') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td>{{ '#' . $order->order_number }}</td>
                          <td>{{ $order->billing_name }}</td>
                          <td>{{ $order->billing_email }}</td>
                          <td>{{ $order->billing_phone }}</td>
                          <td>{{ $order->billing_address }}</td>
                          <td>{{ $order->billing_city }}</td>
                          <td>{{ is_null($order->billing_state) ? '-' : $order->billing_state }}</td>
                          <td>{{ $order->billing_country }}</td>
                          <td>{{ $order->shipping_name }}</td>
                          <td>{{ $order->shipping_email }}</td>
                          <td>{{ $order->shipping_phone }}</td>
                          <td>{{ $order->shipping_address }}</td>
                          <td>{{ $order->shipping_city }}</td>
                          <td>{{ is_null($order->shipping_state) ? '-' : $order->shipping_state }}</td>
                          <td>{{ $order->shipping_country }}</td>
                          <td>
                            {{ $order->currency_text_position == 'left' ? $order->currency_text . ' ' : '' }}{{ $order->total }}{{ $order->currency_text_position == 'right' ? ' ' . $order->currency_text : '' }}
                          </td>
                          <td>
                            @if (is_null($order->discount))
                              -
                            @else
                              {{ $order->currency_text_position == 'left' ? $order->currency_text . ' ' : '' }}{{ $order->discount }}{{ $order->currency_text_position == 'right' ? ' ' . $order->currency_text : '' }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($order->product_shipping_charge_id))
                              -
                            @else
                              {{ $order->shippingMethod }}
                            @endif
                          </td>
                          <td>
                            @if (is_null($order->shipping_cost))
                              -
                            @else
                              {{ $order->currency_text_position == 'left' ? $order->currency_text . ' ' : '' }}{{ $order->shipping_cost }}{{ $order->currency_text_position == 'right' ? ' ' . $order->currency_text : '' }}
                            @endif
                          </td>
                          <td>
                            {{ $order->currency_text_position == 'left' ? $order->currency_text . ' ' : '' }}{{ $order->tax }}{{ $order->currency_text_position == 'right' ? ' ' . $order->currency_text : '' }}
                          </td>
                          <td>
                            {{ $order->currency_text_position == 'left' ? $order->currency_text . ' ' : '' }}{{ $order->grand_total }}{{ $order->currency_text_position == 'right' ? ' ' . $order->currency_text : '' }}
                          </td>
                          <td>{{ $order->payment_method }}</td>
                          <td>
                            @if ($order->payment_status == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @elseif ($order->payment_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Rejected') }}</span>
                            @endif
                          </td>
                          <td>
                            @if ($order->order_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($order->order_status == 'processing')
                              <span class="badge badge-primary">{{ __('Processing') }}</span>
                            @elseif ($order->order_status == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Rejected') }}</span>
                            @endif
                          </td>
                          <td>{{ $order->createdAt }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="mt-3 text-center">
            <div class="d-inline-block mx-auto">
              @if (count($orders) > 0)
                {{ $orders->appends([
                        'from' => request()->input('from'),
                        'to' => request()->input('to'),
                        'payment_gateway' => request()->input('payment_gateway'),
                        'payment_status' => request()->input('payment_status'),
                        'order_status' => request()->input('order_status'),
                    ])->links() }}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
