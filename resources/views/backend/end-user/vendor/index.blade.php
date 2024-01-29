@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Registered Sellers') }}</h4>
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
        <a href="#">{{ __('Sellers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Sellers') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Sellers') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.vendor_management.bulk_delete_vendor') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="{{ route('admin.vendor_management.registered_vendor') }}" method="GET">
                <input name="info" type="text" class="form-control min-230"
                  placeholder="Search By Username or Email ID"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($vendors) == 0)
                <h3 class="text-center">{{ __('NO Sellers FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($vendors as $vendor)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $vendor->id }}">
                          </td>
                          <td>{{ $vendor->username }}</td>
                          <td>{{ $vendor->email }}</td>
                          <td>{{ empty($vendor->phone) ? '-' : $vendor->phone }}</td>
                          <td>
                            <form id="accountStatusForm-{{ $vendor->id }}" class="d-inline-block"
                              action="{{ route('admin.vendor_management.vendor.update_account_status', ['id' => $vendor->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $vendor->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="account_status"
                                onchange="document.getElementById('accountStatusForm-{{ $vendor->id }}').submit()">
                                <option value="1" {{ $vendor->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $vendor->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <form id="emailStatusForm-{{ $vendor->id }}" class="d-inline-block"
                              action="{{ route('admin.vendor_management.vendor.update_email_status', ['id' => $vendor->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $vendor->email_verified_at != null ? 'bg-success' : 'bg-danger' }}"
                                name="email_status"
                                onchange="document.getElementById('emailStatusForm-{{ $vendor->id }}').submit()">
                                <option value="1" {{ $vendor->email_verified_at != null ? 'selected' : '' }}>
                                  {{ __('Verified') }}
                                </option>
                                <option value="0" {{ $vendor->email_verified_at == null ? 'selected' : '' }}>
                                  {{ __('Unverified') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.vendor_management.vendor_details', ['id' => $vendor->id, 'language' => $defaultLang->code]) }}"
                                  class="dropdown-item">
                                  {{ __('Details & Package') }}
                                </a>

                                <a href="{{ route('admin.edit_management.vendor_edit', ['id' => $vendor->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <a href="{{ route('admin.vendor_management.vendor.change_password', ['id' => $vendor->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Change Password') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.vendor_management.vendor.delete', ['id' => $vendor->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                                <a target="_blank"
                                  href="{{ route('admin.vendor_management.vendor.secret_login', ['id' => $vendor->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Secret Login') }}
                                </a>
                              </div>
                            </div>
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
              {{ $vendors->appends(['info' => request()->input('info')])->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
