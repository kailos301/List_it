"use strict";
$(document).ready(function () {
  var data = {
    car_id: car_id
  }

  $.get(visitor_store_url, data, function () { });
})
