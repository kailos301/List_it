@if (count($popupInfos) > 0)
  @foreach ($popupInfos as $popupInfo)
    @php $type = $popupInfo->type; @endphp

    @if ($type == 1)
      <div data-popup_delay="{{ $popupInfo->delay }}" data-popup_id="{{ $popupInfo->id }}"
        id="modal-popup-{{ $popupInfo->id }}" class="popup-wrapper">
        <div>
          <img class="lazyload" data-src="{{ asset('assets/img/popups/' . $popupInfo->image) }}" class="lazy" alt="Popup Image"
            width="100%">
        </div>
      </div>
    @elseif ($type == 2)
      <div data-popup_delay="{{ $popupInfo->delay }}" data-popup_id="{{ $popupInfo->id }}"
        id="modal-popup-{{ $popupInfo->id }}" class="popup-wrapper">
        <div class="popup-one bg-img bg-cover" data-bg-image="{{ asset('assets/img/popups/' . $popupInfo->image) }}">
          <div class="popup_main-content"
            style="background-color: {{ '#' . $popupInfo->background_color }}; opacity: {{ $popupInfo->background_color_opacity }};">
            <h1>{{ $popupInfo->title }}</h1>
            <p>{{ $popupInfo->text }}</p>
            <a href="{{ $popupInfo->button_url }}" class="popup-main-btn"
              style="background-color: {{ '#' . $popupInfo->button_color }};">{{ $popupInfo->button_text }}</a>
          </div>
        </div>
      </div>
    @elseif ($type == 3)
      <div data-popup_delay="{{ $popupInfo->delay }}" data-popup_id="{{ $popupInfo->id }}"
        id="modal-popup-{{ $popupInfo->id }}" class="popup-wrapper">
        <div class="popup-two bg-img bg-cover" data-bg-image="{{ asset('assets/img/popups/' . $popupInfo->image) }}">
          <div class="popup_main-content"
            style="background-color: {{ '#' . $popupInfo->background_color }}; opacity: {{ $popupInfo->background_color_opacity }};">
            <h1>{{ $popupInfo->title }}</h1>
            <p>{{ $popupInfo->text }}</p>

            <div class="subscribe-form">
              <form class="subscriptionForm" action="{{ route('store_subscriber') }}" method="POST">
                @csrf
                <div class="form_group">
                  <input type="email" class="form_control" placeholder="{{ __('Enter Your Email Address') }}"
                    name="email_id">
                </div>

                <div class="form_group">
                  <button type="submit" class="popup-main-btn"
                    style="background-color: {{ '#' . $popupInfo->button_color }};">
                    {{ $popupInfo->button_text }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    @elseif ($type == 4)
      <div data-popup_delay="{{ $popupInfo->delay }}" data-popup_id="{{ $popupInfo->id }}"
        id="modal-popup-{{ $popupInfo->id }}" class="popup-wrapper">
        <div class="popup-three">
          <div class="popup_main-content">
            <div class="left-bg bg-img bg-cover" data-bg-image="{{ asset('assets/img/popups/' . $popupInfo->image) }}">
            </div>
            <div class="right-content">
              <h1>{{ $popupInfo->title }}</h1>
              <p>{{ $popupInfo->text }}</p>
              <a href="{{ $popupInfo->button_url }}" class="popup-main-btn"
                style="background-color: {{ '#' . $popupInfo->button_color }};">{{ $popupInfo->button_text }}</a>
            </div>
          </div>
        </div>
      </div>
    @elseif ($type == 5)
      <div data-popup_delay="{{ $popupInfo->delay }}" data-popup_id="{{ $popupInfo->id }}"
        id="modal-popup-{{ $popupInfo->id }}" class="popup-wrapper">
        <div class="popup-four">
          <div class="popup_main-content">
            <div class="left-bg bg-img bg-cover" data-bg-image="{{ asset('assets/img/popups/' . $popupInfo->image) }}">
            </div>
            <div class="right-content">
              <h1>{{ $popupInfo->title }}</h1>
              <p>{{ $popupInfo->text }}</p>

              <div class="subscribe-form">
                <form class="subscriptionForm" action="{{ route('store_subscriber') }}" method="POST">
                  @csrf
                  <div class="form_group">
                    <input type="email" class="form_control" placeholder="{{ __('Enter Your Email Address') }}"
                      name="email_id">
                  </div>

                  <div class="form_group">
                    <button type="submit" class="popup-main-btn"
                      style="background-color: {{ '#' . $popupInfo->button_color }};">
                      {{ $popupInfo->button_text }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    @elseif ($type == 6)
      <div data-popup_delay="{{ $popupInfo->delay }}" data-popup_id="{{ $popupInfo->id }}"
        id="modal-popup-{{ $popupInfo->id }}" class="popup-wrapper">
        <div class="popup-five bg-img bg-cover" data-bg-image="{{ asset('assets/img/popups/' . $popupInfo->image) }}">
          <div class="popup_main-content">
            <h1>{{ $popupInfo->title }}</h1>
            <h4>{{ $popupInfo->text }}</h4>

            <div class="offer-timer" data-end_date="{{ $popupInfo->end_date }}"
              data-end_time="{{ $popupInfo->end_time }}"></div>

            <a href="{{ $popupInfo->button_url }}" class="popup-main-btn"
              style="background-color: {{ '#' . $popupInfo->button_color }};">{{ $popupInfo->button_text }}</a>
          </div>
        </div>
      </div>
    @else
      <div data-popup_delay="{{ $popupInfo->delay }}" data-popup_id="{{ $popupInfo->id }}"
        id="modal-popup-{{ $popupInfo->id }}" class="popup-wrapper">
        <div class="popup-six">
          <div class="popup_main-content">
            <div class="left-bg bg-img bg-cover" data-bg-image="{{ asset('assets/img/popups/' . $popupInfo->image) }}">
            </div>

            <div class="right-content bg_cover"
              style="background-color: {{ '#' . $popupInfo->background_color }}; background-image: url({{ asset('assets/img/popups/right-bg.png') }});">
              <h1>{{ $popupInfo->title }}</h1>
              <h4>{{ $popupInfo->text }}</h4>

              <div class="offer-timer" data-end_date="{{ $popupInfo->end_date }}"
                data-end_time="{{ $popupInfo->end_time }}"></div>

              <a href="{{ $popupInfo->button_url }}" class="popup-main-btn"
                style="background-color: {{ '#' . $popupInfo->button_color }};">{{ $popupInfo->button_text }}</a>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endforeach
@endif
