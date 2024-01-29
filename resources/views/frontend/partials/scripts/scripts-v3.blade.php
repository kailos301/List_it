<script>
  'use strict';
  const baseURL = "{{ url('/') }}";
  const all_model = "{{ __('All') }}";
  const read_more = "{{ __('Read More') }}";
  const read_less = "{{ __('Read Less') }}";
  const show_more = "{{ __('Show More') . '+' }}";
  const show_less = "{{ __('Show Less') . '-' }}";
  const langDir = "{{ $currentLanguageInfo->direction }}";
  var vapid_public_key = "{!! env('VAPID_PUBLIC_KEY') !!}";
</script>
<!-- Jquery JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('assets/front/js/vendors/popper.min.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/front/js/vendors/bootstrap.min.js') }}"></script>
<!-- Data Tables JS -->
<script src="{{ asset('assets/front/js/vendors/datatables.min.js') }}"></script>
<!-- Counter JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.counterup.min.js') }}"></script>
<!-- Nice Select JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.nice-select.min.js') }}"></script>
<!-- Magnific Popup JS -->
<script src="{{ asset('assets/front/js/vendors/jquery.magnific-popup.min.js') }}"></script>
{{-- syotimer js --}}
<script src="{{ asset('assets/js/jquery-syotimer.min.js') }}"></script>
<!-- Swiper Slider JS -->
<script src="{{ asset('assets/front/js/vendors/swiper-bundle.min.js') }}"></script>
<!-- Lazysizes -->
<script src="{{ asset('assets/front/js/vendors/lazysizes.min.js') }}"></script>
<!-- Noui Range Slider JS -->
<script src="{{ asset('assets/front/js/vendors/nouislider.min.js') }}"></script>
<!-- AOS JS -->
<script src="{{ asset('assets/front/js/vendors/aos.min.js') }}"></script>
<!-- Mouse Hover JS -->
<script src="{{ asset('assets/front/js/vendors/mouse-hover-move.js') }}"></script>
<!-- Svg Loader JS -->
<script src="{{ asset('assets/front/js/vendors/svg-loader.min.js') }}"></script>
{{-- tinymce script js --}}
<script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
{{-- whatsapp js --}}
<script src="{{ asset('assets/js/floating-whatsapp.js') }}"></script>
{{-- toastr --}}
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
{{-- push notification js --}}
<script src="{{ asset('assets/js/push-notification.js') }}"></script>
<!-- Main script JS -->
<script src="{{ asset('assets/front/js/script.js') }}"></script>
<script src="{{ asset('assets/js/pwa.js') }}"></script>
<!--End of Tawk.to Script-->
{{-- whatsapp init code --}}
@if ($basicInfo->whatsapp_status == 1)
  <script type="text/javascript">
    var whatsapp_popup = "{{ $basicInfo->whatsapp_popup_status }}";
    var whatsappImg = "{{ asset('assets/img/whatsapp.svg') }}";

    $(function() {
      $('#WAButton').floatingWhatsApp({
        phone: "{{ $basicInfo->whatsapp_number }}", //WhatsApp Business phone number
        headerTitle: "{{ $basicInfo->whatsapp_header_title }}", //Popup Title
        popupMessage: `{!! nl2br($basicInfo->whatsapp_popup_message) !!}`, //Popup Message
        showPopup: whatsapp_popup == 1 ? true : false, //Enables popup display
        buttonImage: '<img src="' + whatsappImg + '" />', //Button Image
        position: "right" //Position: left | right
      });
    });
  </script>
@endif

<!--Start of Tawk.to Script-->
@if ($basicInfo->tawkto_status)
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();
    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = "{{ $basicInfo->tawkto_direct_chat_link }}";
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>
@endif
<!--End of Tawk.to Script-->

@yield('script')
@if (session()->has('success'))
  <script>
    "use strict";
    toastr['success']("{{ __(session('success')) }}");
  </script>
@endif

@if (session()->has('error'))
  <script>
    "use strict";
    toastr['error']("{{ __(session('error')) }}");
  </script>
@endif
@if (session()->has('warning'))
  <script>
    "use strict";
    toastr['warning']("{{ __(session('warning')) }}");
  </script>
@endif
