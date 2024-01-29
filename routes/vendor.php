<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| vendor Interface Routes
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->middleware('change.lang')->group(function () {
  Route::get('/dashboard', 'Vendor\VendorController@index')->name('vendor.index');
  Route::get('/signup', 'Vendor\VendorController@signup')->name('vendor.signup');
  Route::post('/signup/submit', 'Vendor\VendorController@create')->name('vendor.signup_submit');
  Route::get('/login', 'Vendor\VendorController@login')->name('vendor.login');
  Route::post('/login/submit', 'Vendor\VendorController@authentication')->name('vendor.login_submit');

  Route::get('/email/verify', 'Vendor\VendorController@confirm_email');

  Route::get('/forget-password', 'Vendor\VendorController@forget_passord')->name('vendor.forget.password');
  Route::post('/send-forget-mail', 'Vendor\VendorController@forget_mail')->name('vendor.forget.mail');
  Route::get('/paynow', 'Vendor\VendorController@sendPayment');
  Route::get('/reset-password', 'Vendor\VendorController@reset_password')->name('vendor.reset.password');
  Route::post('/update-forget-password', 'Vendor\VendorController@update_password')->name('vendor.update-forget-password');
});


Route::prefix('vendor')->middleware('auth:vendor', 'Deactive')->group(function () {
  Route::get('dashboard', 'Vendor\VendorController@dashboard')->name('vendor.dashboard');
  Route::get('/change-password', 'Vendor\VendorController@change_password')->name('vendor.change_password');
  Route::post('/update-password', 'Vendor\VendorController@updated_password')->name('vendor.update_password');
  Route::get('/edit-profile', 'Vendor\VendorController@edit_profile')->name('vendor.edit.profile');

  Route::get('/my-wishlist', 'Vendor\VendorController@mywishlist')->name('vendor.wishlist');
  Route::post('/profile/update', 'Vendor\VendorController@update_profile')->name('vendor.update_profile');
  Route::get('/logout', 'Vendor\VendorController@logout')->name('vendor.logout');
  Route::get('/verify-phone/{phone}', 'Vendor\VendorController@verifyPhone');

  // change admin-panel theme (dark/light) route
  Route::post('/change-theme', 'Vendor\VendorController@changeTheme')->name('vendor.change_theme');
  Route::get('/payment-log', 'Vendor\VendorController@payment_log')->name('vendor.payment_log');

  //vendor package extend route
  Route::get('/package-list', 'Vendor\BuyPlanController@index')->name('vendor.plan.extend.index');
  Route::get('/package/checkout/{package_id}', 'Vendor\BuyPlanController@checkout')->name('vendor.plan.extend.checkout');
  Route::post('/package/checkout', 'Vendor\VendorCheckoutController@checkout')->name('vendor.plan.checkout');

  Route::post('/payment/instructions', 'Vendor\VendorCheckoutController@paymentInstruction')->name('vendor.payment.instructions');


  //checkout payment gateway routes
  Route::prefix('membership')->group(function () {
    Route::get('paypal/success', "Payment\PaypalController@successPayment")->name('membership.paypal.success');
    Route::get('paypal/cancel', "Payment\PaypalController@cancelPayment")->name('membership.paypal.cancel');
    Route::get('stripe/cancel', "Payment\StripeController@cancelPayment")->name('membership.stripe.cancel');
    Route::post('paytm/payment-status', "Payment\PaytmController@paymentStatus")->name('membership.paytm.status');
    Route::get('paystack/success', 'Payment\PaystackController@successPayment')->name('membership.paystack.success');
    Route::post('mercadopago/cancel', 'Payment\paymenMercadopagoController@cancelPayment')->name('membership.mercadopago.cancel');
    Route::get('mercadopago/success', 'Payment\MercadopagoController@successPayment')->name('membership.mercadopago.success');
    Route::post('razorpay/success', 'Payment\RazorpayController@successPayment')->name('membership.razorpay.success');
    Route::post('razorpay/cancel', 'Payment\RazorpayController@cancelPayment')->name('membership.razorpay.cancel');
    Route::get('instamojo/success', 'Payment\InstamojoController@successPayment')->name('membership.instamojo.success');
    Route::post('instamojo/cancel', 'Payment\InstamojoController@cancelPayment')->name('membership.instamojo.cancel');
    Route::post('flutterwave/success', 'Payment\FlutterWaveController@successPayment')->name('membership.flutterwave.success');
    Route::post('flutterwave/cancel', 'Payment\FlutterWaveController@cancelPayment')->name('membership.flutterwave.cancel');
    Route::get('/mollie/success', 'Payment\MollieController@successPayment')->name('membership.mollie.success');
    Route::post('mollie/cancel', 'Payment\MollieController@cancelPayment')->name('membership.mollie.cancel');
    Route::get('anet/cancel', 'Payment\AuthorizeController@cancelPayment')->name('membership.anet.cancel');
    Route::get('/offline/success', 'Front\CheckoutController@offlineSuccess')->name('membership.offline.success');
    Route::get('/trial/success', 'Front\CheckoutController@trialSuccess')->name('membership.trial.success');

    Route::get('/online/success', 'Vendor\VendorCheckoutController@onlineSuccess')->name('success.page');
  });

  Route::prefix('car-management')->group(function () {
    Route::get('/', 'Vendor\CarController@index')->name('vendor.car_management.car');
    Route::get('/create', 'Vendor\CarController@create')->name('vendor.cars_management.create_car');
    Route::post('store-data', 'Vendor\CarController@storeData')->name('vendor.cars_management.store_Data');

    Route::post('store', 'Vendor\CarController@store')->name('vendor.car_management.store_car');
    Route::post('update_featured', 'Vendor\CarController@updateFeatured')->name('vendor.cars_management.update_featured_car');
    Route::post('update_status', 'Vendor\CarController@updateStatus')->name('vendor.cars_management.update_car_status');
    Route::get('edit_car/{id}', 'Vendor\CarController@edit')->name('vendor.cars_management.edit_car');
    Route::get('slider-images/{id}', 'Vendor\CarController@getSliderImages');
    Route::post('update/{id}', 'Vendor\CarController@update')->name('vendor.car_management.update_car');
    Route::post('delete', 'Vendor\CarController@delete')->name('vendor.cars_management.delete_car');

    Route::post('bulk_delete', 'Vendor\CarController@bulkDelete')->name('vendor.car_management.bulk_delete.car');

    //==========car slider image
    Route::post('/img-store', 'Vendor\CarController@imagesstore')->name('vendor.car.imagesstore');
    Route::post('/img-remove', 'Vendor\CarController@imagermv')->name('vendor.car.imagermv');
    Route::post('/img-db-remove', 'Vendor\CarController@imagedbrmv')->name('vendor.car.imgdbrmv');
    //==========car slider image end
    // user Ads  route
  Route::get('/ads-subcat/{id}', 'Vendor\CarController@subCat');



    Route::post('/bult-delete', 'Vendor\CarController@bulk_delete')->name('vendor.cars_management.bulk_delete_car');
    Route::post('/get-car-brand-model', 'Vendor\CarController@get_brand_model')->name('vendor.get-car.brand.model');
  });


  // equipment route start
  Route::prefix('/equipment-management')->group(function () {
    // equipment route
    Route::get('/all-equipment', 'Vendor\EquipmentController@index')->name('vendor.equipment_management.all_equipment');

    Route::get('/create-equipment', 'Vendor\EquipmentController@create')->name('vendor.equipment_management.create_equipment');

    Route::post('/upload-slider-image', 'Vendor\EquipmentController@uploadImage')->name('vendor.equipment_management.upload_slider_image');

    Route::post('/remove-slider-image', 'Vendor\EquipmentController@removeImage')->name('vendor.equipment_management.remove_slider_image');

    Route::post('/store-equipment', 'Vendor\EquipmentController@store')->name('vendor.equipment_management.store_equipment');

    Route::post('/{id}/update-featured', 'Vendor\EquipmentController@updateFeatured')->name('vendor.equipment_management.update_featured');

    Route::get('/edit-equipment/{id}', 'Vendor\EquipmentController@edit')->name('vendor.equipment_management.edit_equipment');

    Route::post('/detach-slider-image', 'Vendor\EquipmentController@detachImage')->name('vendor.equipment_management.detach_slider_image');

    Route::post('/update-equipment/{id}', 'Vendor\EquipmentController@update')->name('vendor.equipment_management.update_equipment');

    Route::post('/delete-equipment/{id}', 'Vendor\EquipmentController@destroy')->name('vendor.equipment_management.delete_equipment');

    Route::post('/bulk-delete-equipment', 'Vendor\EquipmentController@bulkDestroy')->name('vendor.equipment_management.bulk_delete_equipment');
  });
  // equipment route end

  // equipment-booking route start
  Route::prefix('/equipment-booking')->group(function () {
    Route::prefix('/settings')->group(function () {
      // location route
      Route::get('/locations', 'Vendor\LocationController@index')->name('vendor.equipment_booking.settings.locations');

      Route::post('/store-location', 'Vendor\LocationController@store')->name('vendor.equipment_booking.settings.store_location');

      Route::post('/update-location', 'Vendor\LocationController@update')->name('vendor.equipment_booking.settings.update_location');

      Route::post('/delete-location/{id}', 'Vendor\LocationController@destroy')->name('vendor.equipment_booking.settings.delete_location');

      Route::post('/bulk-delete-location', 'Vendor\LocationController@bulkDestroy')->name('vendor.equipment_booking.settings.bulk_delete_location');
    });

    // booking route
    Route::get('/bookings', 'Vendor\BookingController@bookings')->name('vendor.equipment_booking.bookings');

    Route::post('/{id}/update-payment-status', 'Vendor\BookingController@updatePaymentStatus')->name('vendor.equipment_booking.update_payment_status');

    Route::post('/{id}/update-shipping-status', 'Vendor\BookingController@updateShippingStatus')->name('vendor.equipment_booking.update_shipping_status');

    Route::get('/{id}/details', 'Vendor\BookingController@show')->name('vendor.equipment_booking.details');

    Route::post('/{id}/delete', 'Vendor\BookingController@destroy')->name('vendor.equipment_booking.delete');

    Route::post('/bulk-delete', 'Vendor\BookingController@bulkDestroy')->name('vendor.equipment_booking.bulk_delete');

    // report route
    Route::get('/report', 'Vendor\BookingController@report')->name('vendor.equipment_booking.report')->middleware('Deactive');

    Route::get('/export-report', 'Vendor\BookingController@exportReport')->name('vendor.equipment_booking.export_report');
  });
  // equipment-booking route end

  // shipping-method route
  Route::get('/shipping-methods', 'Vendor\VendorController@methodSettings')->name('vendor.equipment_booking.settings.shipping_methods');

  Route::post('/update-method-settings', 'Vendor\VendorController@updateMethodSettings')->name('vendor.equipment_booking.settings.update_method_settings');

  Route::prefix('withdraw')->middleware('Deactive')->group(function () {
    Route::get('/', 'Vendor\VendorWithdrawController@index')->name('vendor.withdraw');
    Route::get('/create', 'Vendor\VendorWithdrawController@create')->name('vendor.withdraw.create');
    Route::get('/get-method/input/{id}', 'Vendor\VendorWithdrawController@get_inputs');

    Route::get('/balance-calculation/{method}/{amount}', 'Vendor\VendorWithdrawController@balance_calculation');

    Route::post('/send-request', 'Vendor\VendorWithdrawController@send_request')->name('vendor.withdraw.send-request');
    Route::post('/witdraw/bulk-delete', 'Vendor\VendorWithdrawController@bulkDelete')->name('vendor.witdraw.bulk_delete_withdraw');
    Route::post('/witdraw/delete', 'Vendor\VendorWithdrawController@Delete')->name('vendor.witdraw.delete_withdraw');
  });

  Route::get('/transcation', 'Vendor\VendorController@transcation')->name('vendor.transcation');
  Route::post('/transcation/delete', 'Vendor\VendorController@destroy')->name('vendor.transcation.delete');
  Route::post('/transcation/bulk-delete', 'Vendor\VendorController@bulk_destroy')->name('vendor.transcation.bulk_delete');

  #====support tickets ============
  Route::get('support/ticket/create', 'Vendor\SupportTicketController@create')->name('vendor.support_ticket.create');
  Route::post('support/ticket/store', 'Vendor\SupportTicketController@store')->name('vendor.support_ticket.store');
  Route::get('support/tickets', 'Vendor\SupportTicketController@index')->name('vendor.support_tickets');
  Route::get('support/message/{id}', 'Vendor\SupportTicketController@message')->name('vendor.support_tickets.message');
  Route::post('support-ticket/zip-upload', 'Vendor\SupportTicketController@zip_file_upload')->name('vendor.support_ticket.zip_file.upload');
  Route::post('support-ticket/reply/{id}', 'Vendor\SupportTicketController@ticketreply')->name('vendor.support_ticket.reply');

  Route::post('support-ticket/delete/{id}', 'Vendor\SupportTicketController@delete')->name('vendor.support_tickets.delete');
});
