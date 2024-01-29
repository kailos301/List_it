@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Orders') }}
@endsection

@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->orders_page_title : __('Orders'),
  ])


  <!--====== Start Dashboard Section ======-->
  <div class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('frontend.user.side-navbar')
        <div class="col-lg-9">
          <div class="account-info radius-md mb-40">
            <div class="title">
              <h4>{{ __('Orders') }}</h4>
            </div>
            <div class="main-info">
              <div class="main-table">
                <div class="table-responsive">
                  <table id="myTable" class="table table-striped w-100">
                    <thead>
                      <tr>
                        <th>{{ __('Order Number') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Payment Status') }}</th>
                        <th>{{ __('Order Status') }}</th>
                        <th>{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $item)
                        <tr>
                          <td>#{{ $item->order_number }}</td>
                          <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                          @php
                            if ($item->payment_status == 'pending') {
                                $payment_bg = 'bg-warning';
                            } elseif ($item->payment_status == 'completed') {
                                $payment_bg = 'bg-success';
                            } elseif ($item->payment_status == 'rejected') {
                                $payment_bg = 'bg-danger';
                            }
                            
                            if ($item->order_status == 'pending') {
                                $order_bg = 'bg-warning';
                            } elseif ($item->order_status == 'processing') {
                                $order_bg = 'bg-info';
                            } elseif ($item->order_status == 'completed') {
                                $order_bg = 'bg-success';
                            } elseif ($item->order_status == 'rejected') {
                                $order_bg = 'bg-danger';
                            }
                            
                            //order status
                            
                          @endphp
                          <td><span class="badge {{ $payment_bg }}">{{ __($item->payment_status) }}</span></td>

                          <td><span class="badge {{ $order_bg }}">{{ __($item->order_status) }}</span></td>

                          <td>
                            <a href="{{ route('user.order.details', $item->id) }}" class="btn"><i
                                class="fas fa-eye"></i> {{ __('Details') }}</a>
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
      </div>
    </div>
  </div>
  <!--====== End Dashboard Section ======-->
@endsection
