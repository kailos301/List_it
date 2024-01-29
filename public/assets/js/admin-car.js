"use strict";
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$(document).ready(function () {
  $('.js-example-basic-single1').select2();
  $('.js-example-basic-single2').select2();
  $('.js-example-basic-single3').select2();
  $('.js-example-basic-single4').select2();
  $('.js-example-basic-single5').select2();
  $('.js-example-basic-single6').select2();
  $('.js-example-basic-single7').select2();
});

$('body').on('change', '.js-example-basic-single3', function () {
  var id = $(this).val();
  var lang = $(this).attr('data-code');
  var added = lang + "_car_brand_model_id";

  $('.' + added + ' option').remove();
  $.ajax({
    type: 'POST',
    url: getModelUrl,
    data: {
      id: id,
      lang: lang
    },
    success: function (data) {
      $.each(data, function (key, value) {
        $('.' + added).append($('<option></option>').val(value.id).html(value
          .name));
      });
    }
  });
});
