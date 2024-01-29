
"use strict";
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$(document).ready(function () {
  /*$('.js-example-basic-single1').select2();
  $('.js-example-basic-single2').select2();
  $('.js-example-basic-single3').select2();
  $('.js-example-basic-single4').select2();
  $('.js-example-basic-single5').select2();
  $('.js-example-basic-single6').select2();
  $('.js-example-basic-single7').select2();*/
  //$('.subhidden').addClass('hidden');
});
// get sub category
$("#adsMaincat").change(function(event) {
    // alert( "Change" );
    var adsmainCatId = $('#adsMaincat').val();
     $('.cararea').hide();
    //alert(caryear);
   // exit;
     event.preventDefault();
      var url = 'ads-subcat/'+adsmainCatId;
         $.ajax({
                type:'GET',
                url: url,
              
                success:function(data){
                    $('.subhidden').removeAttr('disabled');
                    $("#adsSubcat").html(data);
                }
            });
  });

// get sub category
$("#adsSubcat").change(function(event) {
    //  alert( "Change" );
    var adsmainCatId = $('#adsSubcat').val();
    if(adsmainCatId==44){

      $('.cararea').show();
    } else{

      $('.cararea').hide();
    }
    
  });



$("#getVehData").click(function(event) {
    //  alert( "Change" );
   var reg = $('#vregNo').val();
   if(reg.trim() == '') {
   alert("hello");
   } else {
    
     event.preventDefault();
      var url = '/vehicle-data/'+reg;
         $.ajax({
                type:'GET',
                url: url,
              
                success:function(data){
                  // alert(data.data.response);
                   $(".carmake").val(data.data.makeID).attr("selected","selected");
                   $('select[name="en_brand_id"]').find('option[value="'+data.data.makeID+'"]').attr("selected",true);
                   $('#carModel').append($('<option></option>').val(data.data.modelID).html(data.data.Model));
                   $('#carYear').val(data.data.BuildYear);
                   $('select[name="en_fuel_type_id"]').find('option:contains("'+data.data.FuelType+'")').attr("selected",true);
                    const   word = data.data.BodyType.charAt(0) + data.data.BodyType.substring(1).toLowerCase();
                   $('select[name="en_transmission_type_id"]').find('option:contains("'+data.data.Transmission+'")').attr("selected",true);
                    const   colour = data.data.Colour.charAt(0) + data.data.Colour.substring(1).toLowerCase();
                   $('select[name="en_car_condition_id"]').find('option:contains("'+colour+'")').attr("selected",true);
                   $('select[name="BodyType"]').find('option:contains("'+word+'")').attr("selected",true);
                   $('#engineCapacity').val(data.data.EngineCapacity);
                   // $('.subhidden').removeAttr('disabled');
                    //$("#adsSubcat").html(data);
                }
            });
       }
  });





$('body').on('change', '.js-example-basic-single3', function () {
  $('.request-loader').addClass('show');
  var id = $(this).val();
  var lang = $(this).attr('data-code');
  var added = lang + "_car_brand_model_id";

  $('.' + added + ' option').remove();
  $.ajax({
    type: 'POST',
    url: getBrandUrl,
    data: {
      id: id,
      lang: lang
    },
    success: function (data) {
      $.each(data, function (key, value) {
        $('.' + added).append($('<option></option>').val(value.id).html(value
          .name));
      });

      $('.request-loader').removeClass('show');
    }
  });

});
