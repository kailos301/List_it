<script>
  @if (Session::has('message'))

    var type = "{{ Session::get('alert-type') }}";
    if (type) {
      type = type
    } else {
      var type = "{{ Session::get('alert-type', 'info') }}";
    }
    switch (type) {
      case 'info':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.info("{{ Session::get('message') }}");
        break;
      case 'success':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut ": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.success("{{ Session::get('message') }}");
        break;
      case 'warning':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut ": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.warning("{{ Session::get('message') }}");
        break;
      case 'error':
        toastr.options = {
          "closeButton": true,
          "progressBar": true,
          "timeOut ": 10000,
          "extendedTimeOut": 10000,
          "positionClass": "toast-top-right",
        }
        toastr.error("{{ Session::get('message') }}");
        break;
    }
  @endif
</script>
