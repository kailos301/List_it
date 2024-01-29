<script>
  'use strict';

  const baseUrl = "{{ url('/') }}";
</script>

{{-- core js files --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

{{-- jQuery ui --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>

{{-- jQuery time-picker --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.timepicker.min.js') }}"></script>

{{-- jQuery scrollbar --}}
<script type="text/javascript" src="{{ asset('assets/js/jquery.scrollbar.min.js') }}"></script>

{{-- bootstrap notify --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

{{-- sweet alert --}}
<script type="text/javascript" src="{{ asset('assets/js/sweet-alert.min.js') }}"></script>

{{-- bootstrap tags input --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-tagsinput.min.js') }}"></script>

{{-- bootstrap date-picker --}}
<script type="text/javascript" src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
{{-- js color --}}
<script type="text/javascript" src="{{ asset('assets/js/jscolor.min.js') }}"></script>

{{-- fontawesome icon picker js --}}
<script type="text/javascript" src="{{ asset('assets/js/fontawesome-iconpicker.min.js') }}"></script>

{{-- datatables js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables-1.10.23.min.js') }}"></script>

{{-- datatables bootstrap js --}}
<script type="text/javascript" src="{{ asset('assets/js/datatables.bootstrap4.min.js') }}"></script>

{{-- tinymce editor --}}
<script src="{{ asset('assets/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

{{-- dropzone js --}}
<script type="text/javascript" src="{{ asset('assets/js/dropzone.min.js') }}"></script>

{{-- atlantis js --}}
<script type="text/javascript" src="{{ asset('assets/js/atlantis.js') }}"></script>

{{-- fonts and icons script --}}
<script type="text/javascript" src="{{ asset('assets/js/webfont.min.js') }}"></script>

@if (session()->has('success'))
  <script>
    'use strict';
    var content = {};

    content.message = '{{ session('success') }}';
    content.title = 'Success';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'success',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

@if (session()->has('warning'))
  <script>
    'use strict';
    var content = {};

    content.message = '{{ session('warning') }}';
    content.title = 'Warning!';
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: 'warning',
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      delay: 4000
    });
  </script>
@endif

<script>
  'use strict';
  const account_status = "{{ Auth::guard('vendor')->user()->status }}";
  const secret_login = "{{ Session::get('secret_login') }}";
</script>

{{-- select2 js --}}
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>

{{-- admin-main js --}}
<script type="text/javascript" src="{{ asset('assets/js/admin-main.js') }}"></script>
