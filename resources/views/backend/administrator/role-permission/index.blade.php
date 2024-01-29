@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Role & Permissions') }}</h4>
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
        <a href="#">{{ __('Admin Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Role & Permissions') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Roles') }}</div>
            </div>

            <div class="col-lg-8 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add Role') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($roles) == 0)
                <h3 class="text-center mt-2">{{ __('NO ROLE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Permissions') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($roles as $role)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $role->name }}</td>
                          <td>
                            <a class="btn btn-info btn-sm"
                              href="{{ route('admin.admin_management.role.permissions', ['id' => $role->id]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                              {{ __('Manage') }}
                            </a>
                          </td>
                          <td>
                            <a class="btn btn-secondary  mt-1 btn-sm mr-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $role->id }}" data-name="{{ $role->name }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.admin_management.delete_role', ['id' => $role->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn  mt-1 btn-danger btn-sm deleteBtn">
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
  @include('backend.administrator.role-permission.create')

  {{-- edit modal --}}
  @include('backend.administrator.role-permission.edit')
@endsection
