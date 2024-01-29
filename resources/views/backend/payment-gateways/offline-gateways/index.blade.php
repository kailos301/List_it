@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Offline Gateways') }}</h4>
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
        <a href="#">{{ __('Offline Gateways') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Offline Gateways') }}</div>
            </div>

            <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Gateway') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($offlineGateways) == 0)
                <h3 class="text-center">{{ __('NO OFFLINE PAYMENT GATEWAY FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Gateway Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($offlineGateways as $offlineGateway)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $offlineGateway->name }}</td>
                          <td>
                            <form id="statusForm-{{ $offlineGateway->id }}" class="d-inline-block" action="{{ route('admin.payment_gateways.update_status', ['id' => $offlineGateway->id]) }}" method="post">
                              @csrf
                              <select class="form-control form-control-sm {{ $offlineGateway->status == 1 ? 'bg-success' : 'bg-danger' }}" name="status" onchange="document.getElementById('statusForm-{{ $offlineGateway->id }}').submit();">
                                <option value="1" {{ $offlineGateway->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $offlineGateway->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>{{ $offlineGateway->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm mt-1 editBtn mr-1" href="#editModal" data-toggle="modal" data-id="{{ $offlineGateway->id }}" data-name="{{ $offlineGateway->name }}" data-short_description="{{ $offlineGateway->short_description }}" data-instructions="{{ replaceBaseUrl($offlineGateway->instructions, 'summernote') }}" data-has_attachment="{{ $offlineGateway->has_attachment }}" data-serial_number="{{ $offlineGateway->serial_number }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.payment_gateways.delete_offline_gateway', ['id' => $offlineGateway->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
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

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.payment-gateways.offline-gateways.create')

  {{-- edit modal --}}
  @include('backend.payment-gateways.offline-gateways.edit')
@endsection
