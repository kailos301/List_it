"use strict";

$(document).ready(function () {
  $('#stripe-element').addClass('d-none');
})
// Set your Stripe public key
var stripe = Stripe(stripe_key);

// Create a Stripe Element for the card field
var elements = stripe.elements();
var cardElement = elements.create('card', {
  style: {
    base: {
      iconColor: '#454545',
      color: '#454545',
      fontWeight: '500',
      lineHeight: '50px',
      fontSmoothing: 'antialiased',
      backgroundColor: '#f2f2f2',
      ':-webkit-autofill': {
        color: '#454545',
      },
      '::placeholder': {
        color: '#454545',
      },
    }
  },
});

// Add an instance of the card Element into the `card-element` div
cardElement.mount('#stripe-element');

// Handle form submission
var form = document.getElementById('my-checkout-form');
form.addEventListener('submit', function (event) {
  event.preventDefault();
  if ($('#payment-gateway').val() == 'Stripe') {
    stripe.createToken(cardElement).then(function (result) {
      if (result.error) {
        // Display errors to the customer
        var errorElement = document.getElementById('stripe-errors');
        errorElement.textContent = result.error.message;
      } else {
        // Send the token to your server
        stripeTokenHandler(result.token);
      }
    });
  } else if ($('#payment-gateway').val() == 'Authorize.net') {
    sendPaymentDataToAnet();
  } else {
    $('#my-checkout-form').submit();
  }
});

// Send the token to your server
function stripeTokenHandler(token) {
  // Add the token to the form data before submitting to the server
  var form = document.getElementById('my-checkout-form');
  var hiddenInput = document.createElement('input');
  hiddenInput.setAttribute('type', 'hidden');
  hiddenInput.setAttribute('name', 'stripeToken');
  hiddenInput.setAttribute('value', token.id);
  form.appendChild(hiddenInput);

  // Submit the form to your server
  form.submit();
}

//authorize.net functions

function sendPaymentDataToAnet() {
  // Set up authorisation to access the gateway.
  var authData = {};
  authData.clientKey = public_key;
  authData.apiLoginID = login_id;

  var cardData = {};
  cardData.cardNumber = document.getElementById("anetCardNumber").value;
  cardData.month = document.getElementById("anetExpMonth").value;
  cardData.year = document.getElementById("anetExpYear").value;
  cardData.cardCode = document.getElementById("anetCardCode").value;

  // Now send the card data to the gateway for tokenisation.
  // The responseHandler function will handle the response.
  var secureData = {};
  secureData.authData = authData;
  secureData.cardData = cardData;
  Accept.dispatchData(secureData, responseHandler);
}

function responseHandler(response) {
  if (response.messages.resultCode === "Error") {
    var i = 0;
    let errorLists = ``;
    while (i < response.messages.message.length) {
      errorLists += `<li class="text-danger">${response.messages.message[i].text}</li>`;

      i = i + 1;
    }
    $("#anetErrors").show();
    $("#anetErrors").html(errorLists);
  } else {
    paymentFormUpdate(response.opaqueData);
  }
}

function paymentFormUpdate(opaqueData) {
  document.getElementById("opaqueDataDescriptor").value = opaqueData.dataDescriptor;
  document.getElementById("opaqueDataValue").value = opaqueData.dataValue;
  document.getElementById("my-checkout-form").submit();
}
