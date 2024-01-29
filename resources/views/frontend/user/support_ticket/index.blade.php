@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Support Tickets') }}
@endsection


@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->support_ticket_page_title : __('Support Tickets'),
  ])

  <!--====== Start Dashboard Section ======-->
  <div class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('frontend.user.side-navbar')
        <div class="col-lg-9">
          <div class="account-info radius-md mb-40">
            <div class="title d-flex flex-wrap gap-3 justify-content-between align-items-center">
              <h4 class="mb-0">{{ __('Support Tickets') }}</h4>
              <a href="{{ route('user.support_ticket.create') }}" target="_self"
                class="btn btn-md btn-primary">{{ __('Submit a Ticket') }}</a>
            </div>
            <div class="main-info">
              <div class="main-table">
                <div class="table-responsive">
                  <table id="myTable" class="table table-striped w-100">
                    <thead>
                      <tr>
                        <th>{{ __('Ticket ID') }}</th>
                        <th>{{ __('Subject') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Message') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $item)
                        <tr>
                          <td>{{ $item->id }}</td>
                          <td>{{ $item->subject }}</td>
                          @if ($item->status == 1)
                            <td><span class="badge bg-warning">{{ __('Pending') }}</span></td>
                          @elseif($item->status == 2)
                            <td><span class="badge bg-success">{{ __('Open') }}</span></td>
                          @else
                            <td><span class="badge bg-danger">{{ __('Closed') }}</span></td>
                          @endif
                          <td>
                            @php
                              $status = App\Models\SupportTicketStatus::where('id', 1)->first();
                            @endphp

                            <a href="{{ $status->support_ticket_status == 'active' ? route('user.support_ticket.message', $item->id) : '' }}"
                              class="btn btn-primary mb-1"><i class="fas fa-envelope"></i></a>
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
