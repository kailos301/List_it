<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Interface Routes
|--------------------------------------------------------------------------
*/

Route::post('/push-notification/store-endpoint', 'FrontEnd\PushNotificationController@store');

// cron job for sending expiry mail
Route::get('/subcheck', 'CronJobController@expired')->name('cron.expired');

Route::get('/change-language', 'FrontEnd\MiscellaneousController@changeLanguage')->name('change_language');

Route::post('/store-subscriber', 'FrontEnd\MiscellaneousController@storeSubscriber')->name('store_subscriber');

Route::get('/offline', 'FrontEnd\HomeController@offline')->middleware('change.lang');

Route::middleware('change.lang')->group(function () {
  Route::get('/', 'FrontEnd\HomeController@index')->name('index');
  Route::get('/get-model', 'FrontEnd\HomeController@get_model')->name('fronted.get-car.brand.model');

  Route::prefix('cars')->group(function () {
    Route::get('/', 'FrontEnd\CarController@index')->name('frontend.cars');
    Route::get('/store-visitor', 'FrontEnd\CarController@store_visitor')->name('frontend.store_visitor');

    Route::post('/contact-message', 'FrontEnd\CarController@contact')->name('frontend.car.contact_message');
  });

  Route::get('car/{slug}/{id}', 'FrontEnd\CarController@details')->name('frontend.car.details');

  Route::get('addto/wishlist/{id}', 'FrontEnd\UserController@add_to_wishlist')->name('addto.wishlist');
  Route::get('remove/wishlist/{id}', 'FrontEnd\UserController@remove_wishlist')->name('remove.wishlist');

  //products routes are goes here
  Route::get('/products', 'FrontEnd\Shop\ProductController@index')->name('shop.products')->middleware('shop.status');

  Route::prefix('/product')->middleware(['shop.status'])->group(function () {
    Route::get('/{slug}', 'FrontEnd\Shop\ProductController@show')->name('shop.product_details');

    Route::get('/{id}/add-to-cart/{quantity}', 'FrontEnd\Shop\ProductController@addToCart')->name('shop.product.add_to_cart');
  });

  Route::prefix('/shop')->middleware(['shop.status'])->group(function () {
    Route::get('/cart', 'FrontEnd\Shop\ProductController@cart')->name('shop.cart');

    Route::post('/update-cart', 'FrontEnd\Shop\ProductController@updateCart')->name('shop.update_cart');

    Route::get('/cart/remove-product/{id}', 'FrontEnd\Shop\ProductController@removeProduct')->name('shop.cart.remove_product');

    Route::get('put-shipping-method-id/{id}', 'FrontEnd\Shop\ProductController@put_shipping_method')->name('put-shipping-method-id');

    Route::prefix('/checkout')->group(function () {
      Route::get('', 'FrontEnd\Shop\ProductController@checkout')->name('shop.checkout');

      Route::post('/apply-coupon', 'FrontEnd\Shop\ProductController@applyCoupon');

      Route::get('/offline-gateway/{id}/check-attachment', 'FrontEnd\Shop\ProductController@checkAttachment');
    });

    Route::prefix('/purchase-product')->group(function () {
      Route::post('', 'FrontEnd\Shop\PurchaseProcessController@index')->name('shop.purchase_product');

      Route::get('/paypal/notify', 'FrontEnd\PaymentGateway\PayPalController@notify')->name('shop.purchase_product.paypal.notify');

      Route::get('/instamojo/notify', 'FrontEnd\PaymentGateway\InstamojoController@notify')->name('shop.purchase_product.instamojo.notify');

      Route::get('/paystack/notify', 'FrontEnd\PaymentGateway\PaystackController@notify')->name('shop.purchase_product.paystack.notify');

      Route::get('/flutterwave/notify', 'FrontEnd\PaymentGateway\FlutterwaveController@notify')->name('shop.purchase_product.flutterwave.notify');

      Route::post('/razorpay/notify', 'FrontEnd\PaymentGateway\RazorpayController@notify')->name('shop.purchase_product.razorpay.notify');

      Route::get('/mercadopago/notify', 'FrontEnd\PaymentGateway\MercadoPagoController@notify')->name('shop.purchase_product.mercadopago.notify');

      Route::get('/mollie/notify', 'FrontEnd\PaymentGateway\MollieController@notify')->name('shop.purchase_product.mollie.notify');

      Route::post('/paytm/notify', 'FrontEnd\PaymentGateway\PaytmController@notify')->name('shop.purchase_product.paytm.notify');

      Route::get('/complete/{type?}', 'FrontEnd\Shop\PurchaseProcessController@complete')->name('shop.purchase_product.complete')->middleware('change.lang');

      Route::get('/cancel', 'FrontEnd\Shop\PurchaseProcessController@cancel')->name('shop.purchase_product.cancel');
    });

    Route::post('/product/{id}/store-review', 'FrontEnd\Shop\ProductController@storeReview')->name('shop.product_details.store_review');
  });

  Route::prefix('vendors')->group(function () {
    Route::get('/', 'FrontEnd\VendorController@index')->name('frontend.vendors');
    Route::post('contact/message', 'FrontEnd\VendorController@contact')->name('vendor.contact.message');
  });
  Route::get('vendor/{username}', 'FrontEnd\VendorController@details')->name('frontend.vendor.details');

  Route::prefix('/blog')->group(function () {
    Route::get('', 'FrontEnd\BlogController@index')->name('blog');

    Route::get('/{slug}', 'FrontEnd\BlogController@show')->name('blog_details');
  });
  //Route::get('/store-data', 'FrontEnd\HomeController@storeData');
  Route::get('/faq', 'FrontEnd\FaqController@faq')->name('faq');
  Route::get('/about-us', 'FrontEnd\HomeController@about')->name('about_us');
  Route::get('/tabs-data/{catid}', 'FrontEnd\HomeController@tabsData');
  Route::get('/vehicle-data/{vehiclereg}', 'FrontEnd\HomeController@vehicleData');
  Route::get('/autocomplete/suggestions', 'FrontEnd\HomeController@suggestions');
//We offer a monthly CSV download for all makes and models including power details etc for £99 per calendar month (minimum six months)  
//You can download a sample here: 
  Route::prefix('/contact')->group(function () {
    Route::get('', 'FrontEnd\ContactController@contact')->name('contact');

    Route::post('/send-mail', 'FrontEnd\ContactController@sendMail')->name('contact.send_mail')->withoutMiddleware('change.lang');
  });
});

Route::post('/advertisement/{id}/count-view', 'FrontEnd\MiscellaneousController@countAdView');

Route::prefix('login')->middleware(['guest:web', 'change.lang'])->group(function () {
  // user login via facebook route
  Route::prefix('/user/facebook')->group(function () {
    Route::get('', 'FrontEnd\UserController@redirectToFacebook')->name('user.login.facebook');

    Route::get('/callback', 'FrontEnd\UserController@handleFacebookCallback');
  });

  // user login via google route
  Route::prefix('/google')->group(function () {
    Route::get('', 'FrontEnd\UserController@redirectToGoogle')->name('user.login.google');

    Route::get('/callback', 'FrontEnd\UserController@handleGoogleCallback');
  });
});

Route::prefix('/user')->middleware(['guest:web', 'change.lang'])->group(function () {
  Route::prefix('/login')->group(function () {
    // user redirect to login page route
    Route::get('', 'FrontEnd\UserController@login')->name('user.login');
  });
  // user login submit route
  Route::post('/login-submit', 'FrontEnd\UserController@loginSubmit')->name('user.login_submit')->withoutMiddleware('change.lang');

  // user forget password route
  Route::get('/forget-password', 'FrontEnd\UserController@forgetPassword')->name('user.forget_password');

   
    Route::post('/img-remove', 'FrontEnd\UserController@imagermv')->name('user.car.imagermv');
    Route::post('/img-db-remove', 'FrontEnd\UserController@imagedbrmv')->name('user.car.imgdbrmv');
     Route::post('/get-car-brand-model', 'FrontEnd\UserController@get_brand_model')->name('user.get-car.brand.model');
     
  

  // send mail to user for forget password route
  Route::post('/send-forget-password-mail', 'FrontEnd\UserController@forgetPasswordMail')->name('user.send_forget_password_mail')->withoutMiddleware('change.lang');

  // reset password route
  Route::get('/reset-password', 'FrontEnd\UserController@resetPassword');

  // user reset password submit route
  Route::post('/reset-password-submit', 'FrontEnd\UserController@resetPasswordSubmit')->name('user.reset_password_submit')->withoutMiddleware('change.lang');

  // user redirect to signup page route
  Route::get('/signup', 'FrontEnd\UserController@signup')->name('user.signup');

  // user signup submit route
  Route::post('/signup-submit', 'FrontEnd\UserController@signupSubmit')->name('user.signup_submit')->withoutMiddleware('change.lang');

  // signup verify route
  Route::get('/signup-verify/{token}', 'FrontEnd\UserController@signupVerify')->withoutMiddleware('change.lang');
});
Route::post('/img-store', 'FrontEnd\UserController@imagesstore')->name('car.imagesstore');
Route::prefix('/user')->middleware(['auth:web', 'account.status', 'change.lang'])->group(function () {
  // user redirect to dashboard route
  Route::get('/dashboard', 'FrontEnd\UserController@redirectToDashboard')->name('user.dashboard');
  Route::get('/wishlist', 'FrontEnd\UserController@wishlist')->name('user.wishlist');

  Route::get('order', 'FrontEnd\OrderController@index')->name('user.order.index')->middleware('shop.status');
  Route::get('/order/details/{id}', 'FrontEnd\OrderController@details')->name('user.order.details')->middleware('shop.status');

  // edit profile route
  Route::get('/edit-profile', 'FrontEnd\UserController@editProfile')->name('user.edit_profile');

  // update profile route
  Route::post('/update-profile', 'FrontEnd\UserController@updateProfile')->name('user.update_profile')->withoutMiddleware('change.lang');

  // change password route
  Route::get('/change-password', 'FrontEnd\UserController@changePassword')->name('user.change_password');

  // user Ads  route
  Route::get('/user-ads', 'FrontEnd\UserController@user_ads')->name('user.ads.user_ads');

  

  // update password route
  Route::post('/update-password', 'FrontEnd\UserController@updatePassword')->name('user.update_password')->withoutMiddleware('change.lang');

  Route::prefix('support-ticket')->group(function () {
    Route::get('/', 'FrontEnd\SupportTicketController@index')->name('user.support_ticket');
    Route::get('/create', 'FrontEnd\SupportTicketController@create')->name('user.support_ticket.create');
    Route::post('store', 'FrontEnd\SupportTicketController@store')->name('user.support_ticket.store');
    Route::get('message/{id}', 'FrontEnd\SupportTicketController@message')->name('user.support_ticket.message');
    Route::post('reply/{id}', 'FrontEnd\SupportTicketController@reply')->name('user.support_ticket.reply');
  });


  // user logout attempt route
  Route::get('/logout', 'FrontEnd\UserController@logoutSubmit')->name('user.logout')->withoutMiddleware('change.lang');
});

// service unavailable route
Route::get('/service-unavailable', 'FrontEnd\MiscellaneousController@serviceUnavailable')->name('service_unavailable')->middleware('exists.down');

/*
|--------------------------------------------------------------------------
| admin frontend route
|--------------------------------------------------------------------------
*/

Route::prefix('/admin')->middleware('guest:admin')->group(function () {
  // admin redirect to login page route
  Route::get('/', 'BackEnd\AdminController@login')->name('admin.login');

  // admin login attempt route
  Route::post('/auth', 'BackEnd\AdminController@authentication')->name('admin.auth');

  // admin forget password route
  Route::get('/forget-password', 'BackEnd\AdminController@forgetPassword')->name('admin.forget_password');

  // send mail to admin for forget password route
  Route::post('/mail-for-forget-password', 'BackEnd\AdminController@forgetPasswordMail')->name('admin.mail_for_forget_password');
});


/*
|--------------------------------------------------------------------------
| Custom Page Route For UI
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', 'FrontEnd\PageController@page')->name('dynamic_page')->middleware('change.lang');

// fallback route
Route::fallback(function () {
  return view('errors.404');
})->middleware('change.lang');
