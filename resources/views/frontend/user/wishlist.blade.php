@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Wishlist') }}
@endsection


@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => $bgImg->breadcrumb,
      'title' => !empty($pageHeading) ? $pageHeading->wishlist_page_title : __('Wishlist'),
  ])


  <!--====== Start Dashboard Section ======-->
  <div class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('frontend.user.side-navbar')
        <div class="col-lg-9">
          <div class="account-info radius-md mb-40">
            <div class="title">
              <h4>{{ __('Wishlists') }}</h4>
            </div>
            <div class="main-info">
              <div class="main-table">
                <div class="table-responsive">
                  <table id="myTable" class="table table-striped w-100">
                    <thead>
                      <tr>
                        <th>{{ __('Serial') }}</th>
                        <th>{{ __('Car title') }}</th>
                        <th>{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($wishlists as $item)
                        @php
                          $content = DB::table('car_contents')
                              ->where([['car_id', $item->car_id], ['language_id', $language->id]])
                              ->select('title', 'slug')
                              ->first();
                        @endphp
                        @if (!is_null($content))
                          <tr>
                            <td>#{{ $loop->iteration }}</td>
                            <td><a
                                href="{{ route('frontend.car.details', [$content->slug, $item->car_id]) }}">{{ $content->title }}</a>
                            </td>
                            <td>
                              <a href="{{ route('frontend.car.details', [$content->slug, $item->car_id]) }}"
                                class="btn"><i class="fas fa-eye"></i> {{ __('View') }}</a>
                              <a href="{{ route('remove.wishlist', $item->car_id) }}" class="btn"><i
                                  class="fas fa-times"></i>
                                {{ __('Remove') }}</a>
                            </td>
                          </tr>
                        @endif
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
