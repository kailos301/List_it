@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Dashboard') }}
@endsection


@section('content')
  @includeIf('frontend.partials.breadcrumb', [
      'breadcrumb' => !empty($bgImg) ? $bgImg->breadcrumb : '',
      'title' => !empty($pageHeading) ? $pageHeading->dashboard_page_title : __('Dashboard'),
  ])


  <!--====== Start Dashboard Section ======-->
  <div class="user-dashboard pt-20 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('frontend.user.side-navbar')
        <div class="col-lg-9">
          <div class="user-profile-details mb-30">
            <div class="account-info radius-md">
              <div class="title">
                <h4>{{ __('Account Information') }}</h4>
              </div>
              <div class="main-info">
                <ul class="list">
                  <li><span>{{ __('Name') . ':' }}</span> <span>{{ $authUser->name }}</span></li>
                  <li><span>{{ __('Username') . ':' }}</span> <span>{{ $authUser->username }}</span></li>
                  <li><span>{{ __('Email') . ':' }}</span> <span>{{ $authUser->email }}</span></li>
                  <li><span>{{ __('Phone') . ':' }}</span> <span>{{ $authUser->phone }}</span></li>
                  <li><span>{{ __('City') . ':' }}</span> <span>{{ $authUser->city }}</span></li>
                  <li><span>{{ __('Country') . ':' }}</span> <span>{{ $authUser->country }}</span></li>
                  <li><span>{{ __('State') . ':' }}</span> <span>{{ $authUser->state }}</span></li>
                  <li><span>{{ __('Zip Code') . ':' }}</span> <span>{{ $authUser->zip_code }}</span></li>
                  <li><span>{{ __('Address') . ':' }}</span> <span>{{ $authUser->address }}</span></li>
                </ul>
              </div>
            </div>
          </div>

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
                      @php
                        $i = 1;
                      @endphp
                      @foreach ($wishlists as $item)
                        @php
                          $content = DB::table('car_contents')
                              ->where([['car_id', $item->car_id], ['language_id', $language->id]])
                              ->select('title', 'slug')
                              ->first();
                        @endphp

                        @if ($content)
                          <tr>
                            <td>#{{ $i }}</td>
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
                          @php
                            $i++;
                          @endphp
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

