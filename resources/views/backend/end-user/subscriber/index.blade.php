@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Subscribers') }}</h4>
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
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Subscribers') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Subscribers') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete ml-3 mt-1" data-href="{{ route('admin.user_management.bulk_delete_subscriber') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <a href="{{ route('admin.user_management.mail_for_subscribers') }}" class="btn btn-primary btn-sm float-right ml-3 mt-1 mb-1">
                <i class="fal fa-paper-plane"></i> {{ __('Send Mail') }}
              </a>

              <form class="float-right" action="{{ route('admin.user_management.subscribers') }}" method="GET">
                <input name="email" type="text" class="form-control minw-230" placeholder="Search By Email ID" value="{{ !empty(request()->input('email')) ? request()->input('email') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($subscribers) == 0)
                <h3 class="text-center mt-2">{{ __('NO SUBSCRIBER FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($subscribers as $subscriber)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $subscriber->id }}">
                          </td>
                          <td>{{ $subscriber->email_id }}</td>
                          <td>
                            <form class="deleteForm d-inline-block" action="{{ route('admin.user_management.subscriber.delete', ['id' => $subscriber->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                                {{ __('Delete') }}
                              </button>
                            </form>
                          </td>
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
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $subscribers->appends(['email' => request()->input('email')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
