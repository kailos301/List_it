<div class="sidebar sidebar-style-2"
  data-background-color="{{ Session::get('vendor_theme_version') == 'light' ? 'white' : 'dark2' }} offset-lg-1">
  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <div class="user">
        <div class="avatar-sm float-left mr-2">
          @if (Auth::guard('vendor')->user()->photo != null)
            <img src="{{ asset('assets/admin/img/vendor-photo/' . Auth::guard('vendor')->user()->photo) }}"
              alt="Vendor Image" class="avatar-img rounded-circle">
          @else
            <img src="{{ asset('assets/img/blank-user.jpg') }}" alt="" class="avatar-img rounded-circle">
          @endif
        </div>

        <div class="info">
          <a data-toggle="collapse" href="#adminProfileMenu" aria-expanded="true">
            <span>
              {{ Auth::guard('vendor')->user()->username }}
              <span class="user-level">{{ __('Vendor') }}</span>
              <span class="caret"></span>
            </span>
          </a>

          <div class="clearfix"></div>

          <div class="collapse in" id="adminProfileMenu">
            <ul class="nav">
              <li>
                <a href="{{ route('vendor.edit.profile') }}">
                  <span class="link-collapse">{{ __('Edit Profile') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('vendor.change_password') }}">
                  <span class="link-collapse">{{ __('Change Password') }}</span>
                </a>
              </li>

              <li>
                <a href="{{ route('vendor.logout') }}">
                  <span class="link-collapse">{{ __('Logout') }}</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>


      <ul class="nav nav-primary">
        {{-- search --}}
        <div class="row mb-3">
          <div class="col-12">
            <form>
              <div class="form-group py-0">
                <input name="term" type="text" class="form-control sidebar-search ltr"
                  placeholder="Search Menu Here...">
              </div>
            </form>
          </div>
        </div>

        {{-- dashboard --}}
        <li class="nav-item @if (request()->routeIs('vendor.dashboard')) active @endif">
          <a href="{{ route('vendor.dashboard') }}">
            <i class="la flaticon-paint-palette"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>

        <li class="nav-item  {{ request()->routeIs('vendor.cars_management.create_car') ? 'active' : '' }}">
          <a href="{{ route('vendor.cars_management.create_car', ['language' => $defaultLang->code]) }}">
            <i class="far fa-car"></i>
            <p>{{ __('Add Car') }}</p>
          </a>
        </li>

        <li
          class="nav-item  @if (request()->routeIs('vendor.car_management.car')) active  
            @elseif (request()->routeIs('vendor.cars_management.edit_car')) active @endif">
          <a href="{{ route('vendor.car_management.car', ['language' => $defaultLang->code]) }}">
            <i class="far fa-car-garage"></i>
            <p>{{ __('Manage Cars') }}</p>
          </a>
        </li>

        @php
          $support_status = DB::table('support_ticket_statuses')->first();
        @endphp
        @if ($support_status->support_ticket_status == 'active')
          {{-- Support Ticket --}}
          <li
            class="nav-item @if (request()->routeIs('vendor.support_tickets')) active
            @elseif (request()->routeIs('vendor.support_tickets.message')) active
            @elseif (request()->routeIs('vendor.support_ticket.create')) active @endif">
            <a data-toggle="collapse" href="#support_ticket">
              <i class="la flaticon-web-1"></i>
              <p>{{ __('Support Tickets') }}</p>
              <span class="caret"></span>
            </a>

            <div id="support_ticket"
              class="collapse
              @if (request()->routeIs('vendor.support_tickets')) show
              @elseif (request()->routeIs('vendor.support_tickets.message')) show
              @elseif (request()->routeIs('vendor.support_ticket.create')) show @endif">
              <ul class="nav nav-collapse">

                <li
                  class="{{ request()->routeIs('vendor.support_tickets') && empty(request()->input('status')) ? 'active' : '' }}">
                  <a href="{{ route('vendor.support_tickets') }}">
                    <span class="sub-item">{{ __('All Tickets') }}</span>
                  </a>
                </li>
                <li class="{{ request()->routeIs('vendor.support_ticket.create') ? 'active' : '' }}">
                  <a href="{{ route('vendor.support_ticket.create') }}">
                    <span class="sub-item">{{ __('Add a Ticket') }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        @endif
        {{-- dashboard --}}
        <li
          class="nav-item 
        @if (request()->routeIs('vendor.plan.extend.index')) active 
        @elseif (request()->routeIs('vendor.plan.extend.checkout')) active @endif">
          <a href="{{ route('vendor.plan.extend.index') }}">
            <i class="fal fa-lightbulb-dollar"></i>
            <p>{{ __('Buy Plan') }}</p>
          </a>
        </li>

        <li class="nav-item @if (request()->routeIs('vendor.payment_log')) active @endif">
          <a href="{{ route('vendor.payment_log') }}">
            <i class="fas fa-list-ol"></i>
            <p>{{ __('Payment Logs') }}</p>
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('vendor.edit.profile')) active @endif">
          <a href="{{ route('vendor.edit.profile') }}">
            <i class="fal fa-user-edit"></i>
            <p>{{ __('Edit Profile') }}</p>
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('vendor.change_password')) active @endif">
          <a href="{{ route('vendor.change_password') }}">
            <i class="fal fa-key"></i>
            <p>{{ __('Change Password') }}</p>
          </a>
        </li>

        <li class="nav-item @if (request()->routeIs('vendor.logout')) active @endif">
          <a href="{{ route('vendor.logout') }}">
            <i class="fal fa-sign-out"></i>
            <p>{{ __('Logout') }}</p>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
