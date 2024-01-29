<script src="{{asset('assets/js/razorpay.js')}}"></script>

<form name="razorpayform" action="{{ $notifyURL }}" method="POST">
  <input type="hidden" name="razorpayPaymentId" id="razorpay_payment_id">

  <input type="hidden" name="razorpaySignature" id="razorpay_signature">
</form>

<script>
  'use strict';
  // checkout details as json format
  var options = {!! json_decode(json_encode($jsonData, true)) !!};;


  options.handler = function (response) {
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;

    document.razorpayform.submit();
  };

  options.modal = {
    ondismiss: function() {
      window.location.assign('{{ url()->previous() }}');
    },

    // Boolean indicating whether pressing escape key
    // should close the checkout form. (default: true)
    escape: true,

    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
  };

  let rzp = new Razorpay(options);
  rzp.open();
</script>
