'use strict';

let objOfData = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
let prevGatewayId;

$(document).ready(function () {

  // remove empty input field from search-form and, then submit the search form
  function filterInputs() {
    $('input[type="hidden"]').each(function () {
      if (!$(this).val()) {
        $(this).remove();
      }
    });

    $('#submitBtn').trigger('click');
  }

  // search product by typing in the input field
  $('#input-search').on('keypress', function (e) {
    if (e.which == 13) {
      let value = $(this).val();

      if (value == '') {
        alert('Please enter something.');
      } else {
        $('#keyword-id').val(value);
        filterInputs();
      }
    }
  });


  // search product by click on category
  $('.category-search').on('click', function (e) {
    e.preventDefault();

    $('#keyword-id').remove();
    $('#rating-id').remove();
    $('#min-id').remove();
    $('#max-id').remove();
    $('#sort-id').remove();

    let value = $(this).data('category_slug');

    $('#category-id').val(value);
    $('#submitBtn').trigger('click');
  });


  // search product by filtering the rating
  $('.rating-search').on('change', function () {
    let value = $(this).val();

    $('#rating-id').val(value);
    filterInputs();
  });


  // range slider init
  if (
    typeof position != 'undefined' && typeof symbol != 'undefined' &&
    typeof min_price != 'undefined' && typeof max_price != 'undefined' &&
    typeof curr_min != 'undefined' && typeof curr_max != 'undefined'
  ) {
    // initialization is here
    $('#range-slider').slider({
      range: true,
      min: min_price,
      max: max_price,
      values: [curr_min, curr_max],
      slide: function (event, ui) {
        //while the slider moves, then this function will show that range value
        $('#amount').val((position == 'left' ? symbol : '') + ui.values[0] + (position == 'right' ? symbol : '') + ' - ' + (position == 'left' ? symbol : '') + ui.values[1] + (position == 'right' ? symbol : ''));
      }
    });

    // initially this is showing the price range value
    $('#amount').val((position == 'left' ? symbol : '') + $('#range-slider').slider('values', 0) + (position == 'right' ? symbol : '') + ' - ' + (position == 'left' ? symbol : '') + $('#range-slider').slider('values', 1) + (position == 'right' ? symbol : ''));

    // search product by filtering the price
    $('#range-slider').on('slidestop', function () {
      let value = $('#amount').val();

      let priceArray = value.split('-');
      let minPrice = parseFloat(priceArray[0].replace(symbol, ' '));
      let maxPrice = parseFloat(priceArray[1].replace(symbol, ' '));

      $('#min-id').val(minPrice);
      $('#max-id').val(maxPrice);
      filterInputs();
    });
  }


  // search product by sorting
  $('#sort-search').on('change', function () {
    let value = $(this).val();

    $('#sort-id').val(value);
    filterInputs();
  });


  // show stored session data in 'charge summary' table
  if (sessionStorage.getItem('discount')) {
    let discountAmount = sessionStorage.getItem('discount');
    discountAmount = parseFloat(discountAmount);

    $('#discount-amount').text(discountAmount.toFixed(2));
  }

  if (sessionStorage.getItem('newSubtotal')) {
    let subtotalAmount = sessionStorage.getItem('newSubtotal');
    subtotalAmount = parseFloat(subtotalAmount);

    $('#subtotal-amount').text(subtotalAmount.toLocaleString(undefined, objOfData));
  }

  if (sessionStorage.getItem('charge') && sessionStorage.getItem('chargeId')) {
    let id = sessionStorage.getItem('chargeId');
    let chargeAmount = sessionStorage.getItem('charge');

    $('#shipping-charge-' + id).prop('checked', true);
    $('#shipping-charge-amount').text(chargeAmount);
  }

  if (sessionStorage.getItem('calculatedTax')) {
    let taxAmount = sessionStorage.getItem('calculatedTax');
    taxAmount = parseFloat(taxAmount);

    $('#tax-amount').text(taxAmount.toFixed(2));
  }

  if (sessionStorage.getItem('grandTotal')) {
    let grandTotalAmount = sessionStorage.getItem('grandTotal');
    grandTotalAmount = parseFloat(grandTotalAmount);

    $('#grandtotal-amount').text(grandTotalAmount.toLocaleString(undefined, objOfData));
  }


  // add item to the cart by clicking on shop icon
  $('.add-to-cart-icon').on('click', function (e) {
    e.preventDefault();

    let url = $(this).attr('href');

    $.get(url, function (response) {
      if ('success' in response) {
        $('#product-count').text(response.numOfProducts);

        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  });


  // set the product quantity by clicking on (+) or (-) button
  $('.add-btn').on('click', function () {
    let quantity = $(this).prev().val();

    $(this).prev().val(parseInt(quantity) + 1);
  });

  $('.sub-btn').on('click', function () {
    let quantity = $(this).next().val();

    if (parseInt(quantity) > 1) {
      $(this).next().val(parseInt(quantity) - 1);
    }
  });


  // add item to the cart by clicking on 'Add To Cart' button
  $('#add-to-cart-btn').on('click', function (event) {
    event.preventDefault();

    let url = $(this).attr('href');
    let amount = $('#product-quantity').val();

    // replace 'qty' string with value
    url = url.replace('qty', amount);

    $.get(url, function (response) {
      if ('success' in response) {
        $('#product-count').text(response.numOfProducts);

        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  });


  // update the cart by clicking on 'Update Cart' button
  $('#update-cart-btn').on('click', function (event) {
    event.preventDefault();

    let updateCartURL = $(this).attr('href');

    // initialize empty array
    let productId = [];
    let productUnitPrice = [];
    let productQuantity = [];

    // using each() function to get all the values of same class
    $('.product-id').each(function () {
      productId.push($(this).val());
    });

    $('.product-unit-price').each(function () {
      let price = $(this).text();

      // convert string to number then push to array
      productUnitPrice.push(parseFloat(price));
    });

    $('.product-qty').each(function () {
      let quantity = $(this).val();

      // convert string to number then push to array
      productQuantity.push(parseInt(quantity));
    });

    // initialize a formData
    let formData = new FormData();

    // now, append all the array's value in formData key to send it to the controller
    for (let index = 0; index < productId.length; index++) {
      formData.append('id[]', productId[index]);
      formData.append('unitPrice[]', productUnitPrice[index]);
      formData.append('quantity[]', productQuantity[index]);
    }

    $.ajax({
      method: 'POST',
      url: updateCartURL,
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (response) {
        // update the total price of each product and the cart total
        let cartTotal = 0;

        $('.per-product-total').each(function (index) {
          let totalPrice = productUnitPrice[index] * productQuantity[index];
          cartTotal += totalPrice;

          $(this).text(totalPrice.toFixed(2));
        });

        $('#cart-total').text(cartTotal.toFixed(2));

        toastr['success'](response.success);
      },
      error: function (errorData) {
        toastr['error'](errorData.responseJSON.error);
      }
    });
  });


  // remove product(s) by clicking on cross icon
  $('.remove-product-icon').on('click', function (event) {
    event.preventDefault();

    let removeProductURL = $(this).attr('href');

    // get the product-id from the url to use it later.
    let productId = $(this).data('product_id');

    $.get(removeProductURL, function (response) {
      if ('success' in response) {
        if (response.numOfProducts > 0) {
          // show new data
          $('#total-item').text(response.numOfProducts);
          $('#cart-total').text(response.cartTotal);

          // remove only the selected product from DOM
          $('#cart-product-item' + productId).remove();
          $('#in-product-id' + productId).remove();
        } else {
          // remove cart info, cart table and buttons(upadate cart, checkout) from DOM
          $('.total-item-info').remove();
          $('#cart-table').remove();
          $('#cart-buttons').remove();

          // then, show a message in div tag
          const markUp = `<div class="text-center">
              <h3>${cartEmptyTxt}</h3>
            </div>`;

          $('#cart-message').html(markUp);
        }

        $('#product-count').text(response.numOfProducts);

        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  });


  // copy billing details values to shipping details
  $('#shipping-check').on('click', function () {
    if ($(this).prop('checked')) {
      let firstName = $('input[name="billing_first_name"]').val();
      $('input[name="shipping_first_name"]').val(firstName);

      let lastName = $('input[name="billing_last_name"]').val();
      $('input[name="shipping_last_name"]').val(lastName);

      let email = $('input[name="billing_email"]').val();
      $('input[name="shipping_email"]').val(email);

      let phone = $('input[name="billing_contact_number"]').val();
      $('input[name="shipping_contact_number"]').val(phone);

      let address = $('input[name="billing_address"]').val();
      $('input[name="shipping_address"]').val(address);

      let city = $('input[name="billing_city"]').val();
      $('input[name="shipping_city"]').val(city);

      let state = $('input[name="billing_state"]').val();
      $('input[name="shipping_state"]').val(state);

      let country = $('input[name="billing_country"]').val();
      $('input[name="shipping_country"]').val(country);
    } else {
      $('input[name="shipping_first_name"]').val('');
      $('input[name="shipping_last_name"]').val('');
      $('input[name="shipping_email"]').val('');
      $('input[name="shipping_contact_number"]').val('');
      $('input[name="shipping_address"]').val('');
      $('input[name="shipping_city"]').val('');
      $('input[name="shipping_state"]').val('');
      $('input[name="shipping_country"]').val('');
    }
  });


  // get shipping charge by clicking on radio button
  $('input[name="shipping_charge"]').on('change', function () {
    let chargeId = $('input[name="shipping_charge"]:checked').val();
    let charge = $('input[name="shipping_charge"]:checked').data('shipping_charge');
    sessionStorage.setItem('chargeId', chargeId);
    sessionStorage.setItem('charge', charge);

    // set the amount of selected shipping charge in 'charge summary' table
    $('#shipping-charge-amount').text(charge);

    // set value to a hidden input field
    $('#shipping-charge-id').val(chargeId);

    let subTotal = $('#subtotal-amount').text();
    let tax = $('#tax-amount').text();

    // get the new grand total
    subTotal = subTotal.replace(',', '');
    subTotal = parseFloat(subTotal);
    charge = parseFloat(charge);
    tax = parseFloat(tax);

    let grandTotal = subTotal + charge + tax;
    sessionStorage.setItem('grandTotal', grandTotal);

    $('#grandtotal-amount').text(grandTotal.toLocaleString(undefined, objOfData));
  });


  /**
   * show or hide stripe gateway input fields,
   * also show or hide offline gateway informations according to checked payment gateway
   */
  $('.single-radio').on('change', function () {
    let radioBtnVal = $('input[name="gateway"]:checked').val();
    let dataType = parseInt(radioBtnVal);

    if (isNaN(dataType)) {
      // add 'd-none' class for previously selected gateway.
      if (prevGatewayId) {
        $('#gateway-attachment-' + prevGatewayId).addClass('d-none');
        $('#gateway-description-' + prevGatewayId).addClass('d-none');
        $('#gateway-instructions-' + prevGatewayId).addClass('d-none');
      }

      // show or hide 'stripe' form
      if (radioBtnVal == 'stripe') {
        $('#stripe-form').removeClass('d-none');
      } else {
        $('#stripe-form').addClass('d-none');
      }
    } else {
      let url = `${baseURL}/shop/checkout/offline-gateway/${radioBtnVal}/check-attachment`;

      $.get(url, function (response) {
        if ('status' in response) {
          // add 'd-none' class for stripe form
          if (!$('#stripe-form').hasClass('d-none')) {
            $('#stripe-form').addClass('d-none');
          }

          // add 'd-none' class for previously selected gateway.
          if (prevGatewayId) {
            $('#gateway-attachment-' + prevGatewayId).addClass('d-none');
            $('#gateway-description-' + prevGatewayId).addClass('d-none');
            $('#gateway-instructions-' + prevGatewayId).addClass('d-none');
          }

          // show attachment input field, description & instructions of offline gateway
          if (response.status == 1) {
            $('#gateway-attachment-' + radioBtnVal).removeClass('d-none');
          }

          $('#gateway-description-' + radioBtnVal).removeClass('d-none');
          $('#gateway-instructions-' + radioBtnVal).removeClass('d-none');

          prevGatewayId = response.id;
        } else if ('errorMsg' in response) {
          toastr['error'](response.errorMsg);
        }
      });
    }
  });


  // get the rating (star) value in integer
  $('.review-value span').on('click', function () {
    let ratingValue = $(this).attr('data-ratingVal');

    // first, remove '#FBA31C' color and add '#777777' color to the star
    $('.review-value span').css('color', '#777777');

    // second, add '#FBA31C' color to the selected parent class
    let parentClass = 'review-' + ratingValue;
    $('.' + parentClass + ' span').css('color', '#FBA31C');

    // finally, set the rating value to a hidden input field
    $('#rating-id').val(ratingValue);
  });
});

function applyCoupon(event) {
  event.preventDefault();

  let code = $('#coupon-code').val();

  if (code) {
    let url = `${baseURL}/shop/checkout/apply-coupon`;

    let data = {
      coupon: code,
      _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    $.post(url, data, function (response) {
      if ('success' in response) {
        $('#coupon-code').val('');

        let discount = response.amount;
        sessionStorage.setItem('discount', discount);

        if (typeof discount == 'number') {
          $('#discount-amount').text(discount.toFixed(2));
        } else {
          $('#discount-amount').text(discount);
        }

        let total = $('#total-amount').text();
        total = total.replace(',', '');

        let newSubtotal = parseFloat(total) - parseFloat(discount);
        sessionStorage.setItem('newSubtotal', newSubtotal);

        $('#subtotal-amount').text(newSubtotal.toLocaleString(undefined, objOfData));

        let shippingCharge;

        if (response.digitalProductStatus == false) {
          shippingCharge = $('#shipping-charge-amount').text();
        } else {
          shippingCharge = 0;
        }

        let calculatedTax = newSubtotal * (tax / 100);
        sessionStorage.setItem('calculatedTax', calculatedTax);

        $('#tax-amount').text(calculatedTax.toFixed(2));

        let newGrandTotal = newSubtotal + parseFloat(shippingCharge) + calculatedTax;
        sessionStorage.setItem('grandTotal', newGrandTotal);

        $('#grandtotal-amount').text(newGrandTotal.toLocaleString(undefined, objOfData));

        toastr['success'](response.success);
      } else if ('error' in response) {
        toastr['error'](response.error);
      }
    });
  } else {
    alert('Please enter your coupon code.');
  }
}

// validate the card number for stripe payment gateway
function checkCard(cardNumber) {
  let status = Stripe.card.validateCardNumber(cardNumber);

  if (status == false) {
    $('#card-error').html('Invalid card number!');
  } else {
    $('#card-error').html('');
  }
}

// validate the cvc number for stripe payment gateway
function checkCVC(cvcNumber) {
  let status = Stripe.card.validateCVC(cvcNumber);

  if (status == false) {
    $('#cvc-error').html('Invalid cvc number!');
  } else {
    $('#cvc-error').html('');
  }
}
