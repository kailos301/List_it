<div class="col-lg-3 sidebar22">
 <aside class="widget-area mb-40">
    <div class="widget radius-md">
      <ul class="links">
        
        {{-- dashboard --}}
        <li class=" @if (request()->routeIs('vendor.dashboard')) active @endif">
          <a href="{{ route('vendor.dashboard') }}">
           
           {{ __('Dashboard') }}
          </a>
        </li>

        <li class=" {{ request()->routeIs('vendor.cars_management.create_car') ? 'active' : '' }}">
          <a href="{{ route('vendor.cars_management.create_car', ['language' => $defaultLang->code]) }}">
            
            {{ __('Place an Ad') }}
          </a>
        </li>

        <li
          class=" @if (request()->routeIs('vendor.car_management.car')) active  
            @elseif (request()->routeIs('vendor.cars_management.edit_car')) active @endif">
          <a href="{{ route('vendor.car_management.car', ['language' => $defaultLang->code]) }}">
           
            {{ __('Manage Ads') }}
          </a>
        </li>

        @php
          $support_status = DB::table('support_ticket_statuses')->first();
        @endphp
        @if ($support_status->support_ticket_status == 'active')
          {{-- Support Ticket --}}
         <!--  <li
            class="@if (request()->routeIs('vendor.support_tickets')) active
            @elseif (request()->routeIs('vendor.support_tickets.message')) active
            @elseif (request()->routeIs('vendor.support_ticket.create')) active @endif">
            <a data-toggle="collapse" href="#support_ticket">
            
              {{ __('Support Tickets') }}
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
          </li> -->
          <li class="{{ request()->routeIs('vendor.support_ticket') ? 'active' : '' }}">
                  <a href="{{ route('vendor.support_tickets') }}">
                    <span class="sub-item">{{ __('Messages') }}</span>
                  </a>
                </li>
        @endif
        {{-- dashboard --}}

        @if (Session::get('is_dealer')==1)
        <li
          class="nav-item 
        @if (request()->routeIs('vendor.plan.extend.index')) active 
        @elseif (request()->routeIs('vendor.plan.extend.checkout')) active @endif">
          <a href="{{ route('vendor.plan.extend.index') }}">
          
            {{ __('Buy Plan') }}
          </a>
        </li>

        <li class="nav-item @if (request()->routeIs('vendor.payment_log')) active @endif">
          <a href="{{ route('vendor.payment_log') }}">
            
            {{ __('Payment Logs') }}
          </a>
        </li>
        @endif
        <li class="nav-item @if (request()->routeIs('vendor.wishlist')) active @endif">
          <a href="{{ route('vendor.wishlist') }}">
            
            {{ __('Saved Ads') }}
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('vendor.edit.profile')) active @endif">
          <a href="{{ route('vendor.edit.profile') }}">
            
            {{ __('Edit Profile') }}
          </a>
        </li>
        <li class="nav-item @if (request()->routeIs('vendor.change_password')) active @endif">
          <a href="{{ route('vendor.change_password') }}">
           
           {{ __('Change Password') }}
          </a>
        </li>

        <li class="nav-item @if (request()->routeIs('vendor.logout')) active @endif">
          <a href="{{ route('vendor.logout') }}">
          
            {{ __('Logout') }}
          </a>
        </li>
        </ul>
    
  </aside>
  </div>