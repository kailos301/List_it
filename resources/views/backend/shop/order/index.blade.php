@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Orders') }}</h4>
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
    </ul>

  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <form id="searchForm" action="{{ route('admin.shop_management.orders') }}" method="GET">
                <div class="row">
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Order Number') }}</label>
                      <input name="order_no" type="text" class="form-control" placeholder="Search Here..."
                        value="{{ !empty(request()->input('order_no')) ? request()->input('order_no') : '' }}">
                    </div>
                  </div>

                  <div class="col-lg-2">
                    <div class="form-group">
                      <label>{{ __('Payment') }}</label>
                      <select class="form-control h-42" name="payment_status"
                        onchange="document.getElementById('searchForm').submit()">
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
                      <label>{{ __('Order') }}</label>
                      <select class="form-control h-42" name="order_status"
                        onchange="document.getElementById('searchForm').submit()">
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
                </div>
              </form>
            </div>

            <div class="col-lg-2">
              <button class="btn btn-danger btn-sm d-none bulk-delete float-lg-right"
                data-href="{{ route('admin.shop_management.bulk_delete_order') }}" class="card-header-button">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
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
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Order No.') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Order Status') }}</th>
                        <th scope="col">{{ __('Receipt') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $order->id }}">
                          </td>
                          <td>{{ '#' . $order->order_number }}</td>
                          <td>{{ $order->payment_method }}</td>
                          <td>
                            @if ($order->gateway_type == 'online')
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Completed') }}</span>
                              </h2>
                            @else
                              @if ($order->payment_status == 'pending')
                                <form id="paymentStatusForm-{{ $order->id }}" class="d-inline-block"
                                  action="{{ route('admin.shop_management.order.update_payment_status', ['id' => $order->id]) }}"
                                  method="post">
                                  @csrf
                                  <select
                                    class="form-control form-control-sm @if ($order->payment_status == 'pending') bg-warning text-dark @elseif ($order->payment_status == 'completed') bg-success @else bg-danger @endif"
                                    name="payment_status"
                                    onchange="document.getElementById('paymentStatusForm-{{ $order->id }}').submit()">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>
                                    <option value="completed"
                                      {{ $order->payment_status == 'completed' ? 'selected' : '' }}>
                                      {{ __('Completed') }}
                                    </option>
                                    <option value="rejected"
                                      {{ $order->payment_status == 'rejected' ? 'selected' : '' }}>
                                      {{ __('Rejected') }}
                                    </option>
                                  </select>
                                </form>
                              @else
                                <h2 class="d-inline-block"><span
                                    class="badge badge-{{ $order->payment_status == 'completed' ? 'success' : 'danger' }}">{{ ucfirst($order->payment_status) }}</span>
                                </h2>
                              @endif
                            @endif
                          </td>
                          <td>
                            <form id="orderStatusForm-{{ $order->id }}" class="d-inline-block"
                              action="{{ route('admin.shop_management.order.update_order_status', ['id' => $order->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm @if ($order->order_status == 'pending') bg-warning text-dark @elseif ($order->order_status == 'processing') bg-primary @elseif ($order->order_status == 'completed') bg-success @else bg-danger @endif"
                                name="order_status"
                                onchange="document.getElementById('orderStatusForm-{{ $order->id }}').submit()">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                  {{ __('Pending') }}
                                </option>
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                  {{ __('Processing') }}
                                </option>
                                <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>
                                  {{ __('Completed') }}
                                </option>
                                <option value="rejected" {{ $order->order_status == 'rejected' ? 'selected' : '' }}>
                                  {{ __('Rejected') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            @if (!empty($order->attachment))
                              <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                data-target="#receiptModal-{{ $order->id }}">
                                {{ __('Show') }}
                              </a>
                            @else
                              -
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.shop_management.order.details', ['id' => $order->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                @if (!is_null($order->invoice))
                                  <a href="{{ asset('assets/file/invoices/product/' . $order->invoice) }}"
                                    class="dropdown-item" target="_blank">
                                    {{ __('Invoice') }}
                                  </a>
                                @endif

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.shop_management.order.delete', ['id' => $order->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>

                        @includeIf('backend.shop.order.show-receipt')
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
              {{ $orders->appends([
                      'order_no' => request()->input('order_no'),
                      'payment_status' => request()->input('payment_status'),
                      'order_status' => request()->input('order_status'),
                  ])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
