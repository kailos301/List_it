@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Products') }}</h4>
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
        <a href="#">{{ __('Manage Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Products') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('admin.shop_management.select_product_type') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> {{ __('Add Product') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete" data-href="{{ route('admin.shop_management.bulk_delete_product') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($products) == 0)
                <h3 class="text-center mt-2">{{ __('NO PRODUCT FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Featured Image') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Product Type') }}</th>
                        <th scope="col">
                          @php $currencyText = $currencyInfo->base_currency_text; @endphp

                          {{ __('Price') . ' (' . $currencyText . ')' }}
                        </th>

                        @if ($themeInfo->theme_version == 2)
                          <th scope="col">{{ __('Featured') }}</th>
                        @endif

                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($products as $product)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $product->id }}">
                          </td>
                          <td>
                            <img src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}" alt="product image" width="40">
                          </td>
                          <td>
                            {{ strlen($product->title) > 50 ? mb_substr($product->title, 0, 50, 'UTF-8') . '...' : $product->title }}
                          </td>
                          <td>{{ $product->categoryName }}</td>
                          <td class="text-capitalize">{{ $product->product_type }}</td>
                          <td>{{ $product->current_price }}</td>

                          @if ($themeInfo->theme_version == 2)
                            <td>
                              <form id="featuredForm-{{ $product->id }}" class="d-inline-block" action="{{ route('admin.shop_management.product.update_featured_status', ['id' => $product->id]) }}" method="post">
                                @csrf
                                <select class="form-control form-control-sm @if ($product->is_featured == 'yes') bg-success @else bg-danger @endif" name="is_featured" onchange="document.getElementById('featuredForm-{{ $product->id }}').submit()">
                                  <option value="yes" {{ $product->is_featured == 'yes' ? 'selected' : '' }}>
                                    {{ __('Yes') }}
                                  </option>
                                  <option value="no" {{ $product->is_featured == 'no' ? 'selected' : '' }}>
                                    {{ __('No') }}
                                  </option>
                                </select>
                              </form>
                            </td>
                          @endif

                          <td>
                            <a class="btn btn-secondary mt-1 btn-sm mr-1" href="{{ route('admin.shop_management.edit_product', ['id' => $product->id, 'type' => $product->product_type]) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.shop_management.delete_product', ['id' => $product->id]) }}" method="post">
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
