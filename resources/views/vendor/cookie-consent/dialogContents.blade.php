<div class="js-cookie-consent cookie-consent">
  <div class="container">
    <div class="cookie-container">
      <p class="cookie-consent__message">
        {!! nl2br($cookieAlertInfo->cookie_alert_text) !!}
      </p>

      <button class="js-cookie-consent-agree cookie-consent__agree">
        {{ $cookieAlertInfo->cookie_alert_btn_text }}
      </button>
    </div>
  </div>
</div>
