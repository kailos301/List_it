@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Custom Pages') }}</h4>
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
        <a href="#">{{ __('Custom Pages') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Pages') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('admin.custom_pages.create_page') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Page') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete" data-href="{{ route('admin.custom_pages.bulk_delete_page') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($pages) == 0)
                <h3 class="text-center mt-2">{{ __('NO CUSTOM PAGE FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($pages as $page)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $page->page_id }}">
                          </td>
                          <td>{{ $page->title }}</td>
                          <td>
                            @if ($page->status == 1)
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Active') }}</span></h2>
                            @else
                              <h2 class="d-inline-block"><span class="badge badge-danger">{{ __('Deactive') }}</span></h2>
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm  mt-1 mr-1" href="{{ route('admin.custom_pages.edit_page', ['id' => $page->page_id]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.custom_pages.delete_page', ['id' => $page->page_id]) }}" method="post">
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
