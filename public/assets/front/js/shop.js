'use strict';

$(document).on("click", ".quantity-down", function () {
    var numProduct = Number($(this).next().val());
    if (numProduct > 0) $(this).next().val(numProduct - 1);
});
$(document).on("click", ".quantity-up", function () {
    var numProduct = Number($(this).prev().val());
    $(this).prev().val(numProduct + 1);
});

// Shop single slider
var shopSingleThumb = new Swiper(".shop-thumbnails", {
    loop: true,
    speed: 1000,
    spaceBetween: 20,
    slidesPerView: 4,
    centerSlide: true
});
var shopSingleSlider = new Swiper(".shop-single-slider", {
    loop: true,
    speed: 1000,
    autoplay: {
        delay: 3000
    },
    watchSlidesProgress: true,
    thumbs: {
        swiper: shopSingleThumb,
    },

    // Navigation arrows
    navigation: {
        nextEl: ".slider-btn-next",
        prevEl: ".slider-btn-prev",
    },
});

// Shop Slider
var swiper = new Swiper(".shop-slider", {
    speed: 400,
    spaceBetween: 25,
    loop: true,
    slidesPerView: 4,

    // Navigation arrows
    navigation: {
        nextEl: "#shop-slider-next",
        prevEl: "#shop-slider-prev",
    },

    breakpoints: {
        320: {
            slidesPerView: 1
        },
        576: {
            slidesPerView: 2
        },
        992: {
            slidesPerView: 3
        },
        1200: {
            slidesPerView: 4
        },
    }
});

$('body').on('click', '#reviewSubmitBtn', function () {
    $('#reviewSubmitForm').submit();
})


/****************************** */

/************** shop add cart, update cart remove cart & checkout  **************** */

/****************************** */


// add item to the cart by clicking on shop icon


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
$('.add-to-cart-btn').on('click', function (event) {
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
$('body').on('click', '#update-cart-btn', function (event) {
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
            let cart_total_qty = 0;

            $('.per-product-total').each(function (index) {
                let totalPrice = productUnitPrice[index] * productQuantity[index];
                cartTotal += totalPrice;
                cart_total_qty += productQuantity[index];

                $(this).text(totalPrice.toFixed(2));

                //check if qty is 0 then remove product
                if (productQuantity[index] < 1) {
                    $('#in-product-id' + productId).remove();
                    $('#cart-product-item' + productId).remove();
                }
            });

            $('#cart_total_price').text(cartTotal.toFixed(2));
            $('#cart_total_qty').text(cart_total_qty);

            if (response.total_products < 1) {
                $('#cart-table').empty();
                // then, show a message in div tag

                $('#cart-message').html(cartEmptyTxt);
            }

            toastr['success'](response.success);
            $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
        },
        error: function (errorData) {
            toastr['error'](errorData.responseJSON.error);
        }
    });
});


// remove product(s) by clicking on cross icon
$('body').on('click', '.remove-product-icon', function (event) {
    event.preventDefault();

    let removeProductURL = $(this).attr('href');

    // get the product-id from the url to use it later.
    let productId = $(this).data('product_id');
    let cartItem = 'cart-product-item' + productId;

    $.get(removeProductURL, function (response) {
        if ('success' in response) {
            if (response.numOfProducts > 0) {
                // remove only the selected product from DOM
                $('#' + cartItem).remove();
                $('#in-product-id' + productId).remove();

                $('#cart_total_price').text(response.cartTotal);
                $('#cart_total_qty').text(response.numOfProducts);
            } else {
                // remove cart info, cart table and buttons(upadate cart, checkout) from DOM
                $('#cart-table').remove();

                // then, show a message in div tag
                const markUp = `<div class="text-center">
              <h3>${cartEmptyTxt}</h3>
            </div>`;

                $('#cart-message').html(markUp);
            }

            toastr['success'](response.success);
            $("#cartIconWrapper").load(location.href + " #cartIconWrapper");
        } else if ('error' in response) {
            toastr['error'](response.error);
        }
    });
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
