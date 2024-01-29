@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Announcement Popups') }}</h4>
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
        <a href="#">{{ __('Announcement Popups') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Popups') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('admin.announcement_popups.select_popup_type') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Popup') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete" data-href="{{ route('admin.announcement_popups.bulk_delete_popup') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($popups) == 0)
                <h3 class="text-center mt-3">{{ __('NO POPUP FOUND') . '!' }}</h3>
              @else
                <div class="alert alert-warning text-center" role="alert">
                  <strong class="text-dark">{{ __('All activated popups will be appear in UI according to serial number.') }}</strong>
                </div>

                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Type') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($popups as $popup)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $popup->id }}">
                          </td>
                          <td>
                            <img src="{{ asset('assets/img/popup-samples/' . $popup->type . '.jpg') }}" alt="popup type image" class="pt-4" width="55">
                            <p class="mt-1 text-muted">{{ __('Type') . ' - ' . $popup->type }}</p>
                          </td>
                          <td>{{ $popup->name }}</td>
                          <td>
                            <img src="{{ asset('assets/img/popups/' . $popup->image) }}" alt="popup image" width="55">
                          </td>
                          <td>
                            <form id="statusForm-{{ $popup->id }}" class="d-inline-block" action="{{ route('admin.announcement_popups.update_popup_status', ['id' => $popup->id]) }}" method="post">
                              @csrf
                              <select class="form-control form-control-sm {{ $popup->status == 1 ? 'bg-success' : 'bg-danger' }}" name="status" onchange="document.getElementById('statusForm-{{ $popup->id }}').submit()">
                                <option value="1" {{ $popup->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $popup->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>{{ $popup->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm  mt-1 mr-1" href="{{ route('admin.announcement_popups.edit_popup', ['id' => $popup->id]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.announcement_popups.delete_popup', ['id' => $popup->id]) }}" method="post">
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
@endsection
