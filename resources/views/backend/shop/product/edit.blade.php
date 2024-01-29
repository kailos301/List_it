@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Product') }}</h4>
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
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Product') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Edit Product') . ' (' . __('Type') . ' - ' . ucfirst($productType) . ')' }}
          </div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1 dis-none" id="productErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              <div class="ml-2">
                <label for=""><strong>{{ __('Slider Images') . '*' }}</strong></label>

                @php $sliderImages = json_decode($product->slider_images); @endphp

                @if (count($sliderImages) > 0)
                  <div id="reload-slider-div">
                    <div class="row mt-2">
                      <div class="col">
                        <table class="table" id="img-table">
                          @foreach ($sliderImages as $key => $sliderImage)
                            <tr class="table-row" id="{{ 'slider-image-' . $key }}">
                              <td>
                                <img class="thumb-preview wf-150" src="{{ asset('assets/img/products/slider-images/' . $sliderImage) }}" alt="slider image">
                              </td>
                              <td>
                                <i class="fa fa-times-circle" onclick="rmvStoredImg({{ $product->id }}, {{ $key }})"></i>
                              </td>
                            </tr>
                          @endforeach
                        </table>
                      </div>
                    </div>
                  </div>
                @endif

                <form id="slider-dropzone" enctype="multipart/form-data" class="dropzone mt-2 mb-0">
                  @csrf
                  <div class="fallback"></div>
                </form>
                <p class="em text-danger mt-3 mb-0" id="err_slider_image"></p>
              </div>

              <form id="productForm" action="{{ route('admin.shop_management.update_product', ['id' => $product->id]) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="product_type" value="{{ $productType }}">

                <div id="slider-image-id"></div>

                <div class="form-group">
                  <label for="">{{ __('Featured Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/img/products/featured-images/' . $product->featured_image) }}" alt="image" class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="featured_image">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Status') . '*' }}</label>
                      <select name="status" class="form-control">
                        <option disabled>{{ __('Select a Status') }}</option>
                        <option value="show" {{ $product->status == 'show' ? 'selected' : '' }}>
                          {{ __('Show') }}
                        </option>
                        <option value="hide" {{ $product->status == 'hide' ? 'selected' : '' }}>
                          {{ __('Hide') }}
                        </option>
                      </select>
                    </div>
                  </div>

                  @if ($productType == 'digital')
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Input Type') . '*' }}</label>
                        <select name="input_type" class="form-control">
                          <option disabled>{{ __('Select a Type') }}</option>
                          <option value="upload" {{ $product->input_type == 'upload' ? 'selected' : '' }}>
                            {{ __('File Upload') }}
                          </option>
                          <option value="link" {{ $product->input_type == 'link' ? 'selected' : '' }}>
                            {{ __('File Download Link') }}
                          </option>
                        </select>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-group {{ $product->input_type == 'upload' ? '' : 'd-none' }}" id="file-input">
                        <label>{{ __('File') }}</label>
                        <br>
                        <input type="file" name="file">
                        <p class="text-warning mt-2 mb-0">
                          <small>{{ __('Only .zip file is allowed.') }}</small>
                        </p>
                      </div>

                      <div class="form-group {{ $product->input_type == 'link' ? '' : 'd-none' }}" id="link-input">
                        <label>{{ __('Link') }}</label>
                        <input type="url" class="form-control" name="link" placeholder="Enter Download Link" value="{{ $product->link }}">
                      </div>
                    </div>
                  @endif

                  @if ($productType == 'physical')
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Stock') . '*' }}</label>
                        <input type="number" class="form-control" name="stock" placeholder="Enter Product Stock" value="{{ $product->stock }}">
                      </div>
                    </div>
                  @endif

                  @php $currencyText = $currencyInfo->base_currency_text; @endphp

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Current Price') . '* (' . $currencyText . ')' }}</label>
                      <input type="number" step="0.01" class="form-control" name="current_price" placeholder="Enter Product Current Price" value="{{ $product->current_price }}">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Previous Price') . ' (' . $currencyText . ')' }}</label>
                      <input type="number" step="0.01" class="form-control" name="previous_price" placeholder="Enter Product Previous Price" value="{{ $product->previous_price }}">
                    </div>
                  </div>
                </div>

                <div id="accordion" class="mt-5">
                  @foreach ($languages as $language)
                    @php $productData = $language->productData; @endphp

                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link {{ $language->direction == 1 ? 'rtl text-right' : '' }}" data-toggle="collapse" data-target="#collapse{{ $language->id }}" aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}" aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}" class="collapse {{ $language->is_default == 1 ? 'show' : '' }}" aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title" placeholder="Enter Title" value="{{ is_null($productData) ? '' : $productData->title }}">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php $categories = $language->categories; @endphp

                                <label>{{ __('Category') }}</label>
                                <select name="{{ $language->code }}_category_id" class="form-control">
                                  @if (is_null($productData))
                                    <option selected disabled>{{ __('Select a Category') }}</option>
                                  @else
                                    <option disabled>{{ __('Select a Category') }}</option>

                                    @foreach ($categories as $category)
                                      <option value="{{ $category->id }}" {{ $category->id == $productData->product_category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                      </option>
                                    @endforeach
                                  @endif
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Summary') . '*' }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_summary" placeholder="Enter Summary" rows="4">{{ is_null($productData) ? '' : $productData->summary }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Content') . '*' }}</label>
                                <textarea class="form-control summernote" name="{{ $language->code }}_content" data-height="300">{{ is_null($productData) ? '' : replaceBaseUrl($productData->content, 'summernote') }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords" placeholder="Enter Meta Keywords" data-role="tagsinput" value="{{ is_null($productData) ? '' : $productData->meta_keywords }}">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5" placeholder="Enter Meta Description">{{ is_null($productData) ? '' : $productData->meta_description }}</textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="productForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    'use strict';
    const imgUpUrl = "{{ route('admin.shop_management.upload_slider_image') }}";
    const imgRmvUrl = "{{ route('admin.shop_management.remove_slider_image') }}";
    const imgDetachUrl = "{{ route('admin.shop_management.detach_slider_image') }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/slider-image.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/admin-partial.js') }}"></script>
@endsection
