@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Menu Builder') }}</h4>
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
        <a href="#">{{ __('Menu Builder') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Menu Builder') }}</div>
            </div>

            <div class="col-lg-2">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-4">
              <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">{{ __('Built-In Menus') }}</div>

                <div class="card-body">
                  <ul class="list-group">
                    <li class="list-group-item">
                      {{ __('Home') }} <a href="" data-text="{{ __('Home') }}" data-type="home"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('Cars') }} <a href="" data-text="{{ __('Cars') }}" data-type="cars"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>
                    <li class="list-group-item">
                      {{ __('Cars') }} {{ __('(list view)') }} <a href="" data-text="{{ __('Cars') }}" data-type="cars list"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>
                    <li class="list-group-item">
                      {{ __('Cars') }} {{ __('(grid view)') }} <a href="" data-text="{{ __('Cars') }}" data-type="cars grid"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('Vendors') }} <a href="" data-text="{{ __('Vendors') }}" data-type="vendors"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('Shop') }} <a href="" data-text="{{ __('Shop') }}" data-type="shop"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('Cart') }} <a href="" data-text="{{ __('Cart') }}" data-type="cart"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>
                    <li class="list-group-item">
                      {{ __('Checkout') }} <a href="" data-text="{{ __('Checkout') }}" data-type="checkout"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('Blog') }} <a href="" data-text="{{ __('Blog') }}" data-type="blog"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('FAQ') }} <a href="" data-text="{{ __('FAQ') }}" data-type="faq"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('Contact') }} <a href="" data-text="{{ __('Contact') }}" data-type="contact"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    <li class="list-group-item">
                      {{ __('About Us') }} <a href="" data-text="{{ __('About Us') }}" data-type="about-us"
                        class="addToMenus btn btn-primary btn-sm float-right">{{ __('Add To Menus') }}</a>
                    </li>

                    @foreach ($customPages as $customPage)
                      <li class="list-group-item">
                        {{ $customPage->title }} <span class="badge badge-warning ml-1">{{ __('Custom Page') }}</span>
                        <a href="" data-text="{{ $customPage->title }}" data-type="{{ $customPage->slug }}"
                          data-custom="yes"
                          class="addToMenus btn btn-primary btn-sm float-right mt-3 mt-md-0">{{ __('Add To Menus') }}</a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">{{ __('Add') . '/' . __('Edit Menu') }}</div>

                <div class="card-body">
                  <form id="menu-builder-form" class="form-horizontal">
                    <input type="hidden" class="item-menu" name="type">

                    <div id="withUrl">
                      <div class="form-group">
                        <label for="text">{{ __('Text') }}</label>
                        <input type="text" class="form-control item-menu" name="text" placeholder="Enter Menu Name">
                      </div>

                      <div class="form-group">
                        <label for="href">{{ __('URL') }}</label>
                        <input type="url" class="form-control item-menu ltr" name="href"
                          placeholder="Enter Menu URL">
                      </div>

                      <div class="form-group">
                        <label for="target">{{ __('Target') }}</label>
                        <select name="target" id="target" class="form-control item-menu">
                          <option value="_self">{{ __('Self') }}</option>
                          <option value="_blank">{{ __('Blank') }}</option>
                          <option value="_top">{{ __('Top') }}</option>
                        </select>
                      </div>
                    </div>

                    <div id="withoutUrl" class="dis-none">
                      <div class="form-group">
                        <label for="text">{{ __('Text') }}</label>
                        <input type="text" class="form-control item-menu" name="text"
                          placeholder="Enter Menu Name">
                      </div>

                      <div class="form-group">
                        <label for="target">{{ __('Target') }}</label>
                        <select name="target" class="form-control item-menu">
                          <option value="_self">{{ __('Self') }}</option>
                          <option value="_blank">{{ __('Blank') }}</option>
                          <option value="_top">{{ __('Top') }}</option>
                        </select>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="card-footer">
                  <button type="button" id="btn-add" class="btn btn-primary btn-sm mr-2"><i
                      class="fas fa-plus"></i> {{ __('Add') }}</button>
                  <button type="button" id="btn-update" class="btn btn-success btn-sm" disabled><i
                      class="fas fa-sync-alt"></i> {{ __('Update') }}</button>
                </div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">{{ __('Website Menus') }}</div>

                <div class="card-body">
                  <ul id="myMenuEditor" class="sortableLists list-group"></ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button id="btn-menu-update" class="btn btn-success">
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
  <script type="text/javascript" src="{{ asset('assets/js/jquery-menu-editor.min.js') }}"></script>

  <script>
    'use strict';

    let allMenus = {!! json_encode($menuData) !!};
    let langId = {{ $language->id }};
    const menuBuilderUrl = "{{ route('admin.menu_builder.update_menus') }}";
  </script>

  <script type="text/javascript" src="{{ asset('assets/js/menu-builder.js') }}"></script>
@endsection
