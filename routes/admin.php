<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/



Route::prefix('/admin')->middleware('auth:admin')->group(function () {
  // admin redirect to dashboard route
  Route::get('/dashboard', 'BackEnd\AdminController@redirectToDashboard')->name('admin.dashboard');
  Route::get('/membership-request', 'BackEnd\AdminController@membershipRequest')->name('admin.membership-request');
  Route::post('/membership-request/update/{id}', 'BackEnd\AdminController@membershipRequestUpdate')->name('admin.payment-log.update');
  Route::get('/monthly-profit', 'BackEnd\AdminController@monthly_profit')->name('admin.monthly_profit');
  Route::get('/monthly-earning', 'BackEnd\AdminController@monthly_earning')->name('admin.monthly_earning');

  // change admin-panel theme (dark/light) route
  Route::get('/change-theme', 'BackEnd\AdminController@changeTheme')->name('admin.change_theme');

  // admin profile settings route start
  Route::get('/edit-profile', 'BackEnd\AdminController@editProfile')->name('admin.edit_profile');

  Route::post('/update-profile', 'BackEnd\AdminController@updateProfile')->name('admin.update_profile');

  Route::get('/change-password', 'BackEnd\AdminController@changePassword')->name('admin.change_password');

  Route::post('/update-password', 'BackEnd\AdminController@updatePassword')->name('admin.update_password');
  // admin profile settings route end

  // admin logout attempt route
  Route::get('/logout', 'BackEnd\AdminController@logout')->name('admin.logout');

  // menu-builder route start
  Route::prefix('/menu-builder')->middleware('permission:Menu Builder')->group(function () {
    Route::get('', 'BackEnd\MenuBuilderController@index')->name('admin.menu_builder');

    Route::post('/update-menus', 'BackEnd\MenuBuilderController@update')->name('admin.menu_builder.update_menus');
  });
  // menu-builder route end

  // Payment Log
  Route::get('/payment-log', 'BackEnd\PaymentLogController@index')->name('admin.payment-log.index');
  Route::post('/payment-log/update', 'BackEnd\PaymentLogController@update')->name('admin.payment-log.update');

  Route::prefix('package')->group(function () {
    // Package Settings routes
    Route::get('/settings', 'BackEnd\PackageController@settings')->name('admin.package.settings');
    Route::post('/settings', 'BackEnd\PackageController@updateSettings')->name('admin.package.settings');
    // Package routes
    Route::get('packages', 'BackEnd\PackageController@index')->name('admin.package.index');
    Route::post('package/upload', 'BackEnd\PackageController@upload')->name('admin.package.upload');
    Route::post('package/store', 'BackEnd\PackageController@store')->name('admin.package.store');
    Route::get('package/{id}/edit', 'BackEnd\PackageController@edit')->name('admin.package.edit');
    Route::post('package/update', 'BackEnd\PackageController@update')->name('admin.package.update');
    Route::post('package/{id}/uploadUpdate', 'BackEnd\PackageController@uploadUpdate')->name('admin.package.uploadUpdate');
    Route::post('package/delete', 'BackEnd\PackageController@delete')->name('admin.package.delete');
    Route::post('package/bulk-delete', 'BackEnd\PackageController@bulkDelete')->name('admin.package.bulk.delete');
  });

  //car specification route are goes here
  Route::prefix('car-specification')->group(function () {
    // car category route
    Route::get('/categories', 'BackEnd\Car\CategoryController@index')->name('admin.car_specification.categories');
    Route::get('/categories/{pid}', 'BackEnd\Car\CategoryController@index')->name('admin.car_specification.categoriesp');
    Route::get('/form-fields', 'BackEnd\Car\CategoryController@formView')->name('admin.car_specification.formView');
    Route::post('/categoryData', 'BackEnd\Car\CategoryController@categoryDataAJAX')->name('admin.car_specification.categoryDataAJAX');
    Route::post('/fetch-subcategory-data', 'BackEnd\Car\CategoryController@fetchSubCategoryDataAJAX')->name('admin.car_specification.fetchSubCategoryDataAJAX');
    Route::post('/fetchForm', 'BackEnd\Car\CategoryController@fetchFormAJAX')->name('admin.car_specification.fetchFormAJAX');
    Route::post('/save-form-structure', 'BackEnd\Car\CategoryController@saveFormStructureAJAX')->name('admin.car_specification.saveFormStructureAJAX');

    Route::post('/fetchOptions', 'BackEnd\Car\CategoryController@fetchOptionsAJAX')->name('admin.car_specification.fetchOptionsAJAX');


    Route::post('/store-category', 'BackEnd\Car\CategoryController@store')->name('admin.car_specification.store_category');
    Route::post('/update-category', 'BackEnd\Car\CategoryController@update')->name('admin.car_specification.update_category');
    Route::post('/delete-category/{id}', 'BackEnd\Car\CategoryController@destroy')->name('admin.car_specification.delete_category');
    Route::post('/bulk-delete-category', 'BackEnd\Car\CategoryController@bulkDestroy')->name('admin.car_specification.bulk_delete_category');

    // car category route
    Route::get('/condition', 'BackEnd\Car\ConditionController@index')->name('admin.car_specification.condition');
    Route::post('/store-condition', 'BackEnd\Car\ConditionController@store')->name('admin.car_specification.store_condition');
    Route::post('/update-condition', 'BackEnd\Car\ConditionController@update')->name('admin.car_specification.update_condition');
    Route::post('/delete-condition/{id}', 'BackEnd\Car\ConditionController@destroy')->name('admin.car_specification.delete_condition');
    Route::post('/bulk-delete-condition', 'BackEnd\Car\ConditionController@bulkDestroy')->name('admin.car_specification.bulk_delete_condition');

    // car brand route
    Route::get('/brand', 'BackEnd\Car\BrandController@index')->name('admin.car_specification.brand');
    Route::post('/store-brand', 'BackEnd\Car\BrandController@store')->name('admin.car_specification.store_brand');
    Route::post('/update-brand', 'BackEnd\Car\BrandController@update')->name('admin.car_specification.update_brand');
    Route::post('/delete-brand/{id}', 'BackEnd\Car\BrandController@destroy')->name('admin.car_specification.delete_brand');
    Route::post('/bulk-delete-brand', 'BackEnd\Car\BrandController@bulkDestroy')->name('admin.car_specification.bulk_delete_brand');

    // car model route
    Route::get('/model', 'BackEnd\Car\ModelController@index')->name('admin.car_specification.model');
    Route::post('/store-model', 'BackEnd\Car\ModelController@store')->name('admin.car_specification.store_model');
    Route::post('/update-model', 'BackEnd\Car\ModelController@update')->name('admin.car_specification.update_model');
    Route::post('/delete-model/{id}', 'BackEnd\Car\ModelController@destroy')->name('admin.car_specification.delete_model');
    Route::post('/bulk-delete-model', 'BackEnd\Car\ModelController@bulkDestroy')->name('admin.car_specification.bulk_delete_model');


    // car model route
    Route::get('/fuel', 'BackEnd\Car\FuelTypeController@index')->name('admin.car_specification.fuel');
    Route::post('/store-fuel', 'BackEnd\Car\FuelTypeController@store')->name('admin.car_specification.store_fuel');
    Route::post('/update-fuel', 'BackEnd\Car\FuelTypeController@update')->name('admin.car_specification.update_fuel');
    Route::post('/delete-fuel/{id}', 'BackEnd\Car\FuelTypeController@destroy')->name('admin.car_specification.delete_fuel');
    Route::post('/bulk-delete-fuel', 'BackEnd\Car\FuelTypeController@bulkDestroy')->name('admin.car_specification.bulk_delete_fuel');

    // car transmission route
    Route::get('/transmission', 'BackEnd\Car\TransmissionTypeController@index')->name('admin.car_specification.transmission');
    Route::post('/store-transmission', 'BackEnd\Car\TransmissionTypeController@store')->name('admin.car_specification.store_transmission');
    Route::post('/update-transmission', 'BackEnd\Car\TransmissionTypeController@update')->name('admin.car_specification.update_transmission');
    Route::post('/delete-transmission/{id}', 'BackEnd\Car\TransmissionTypeController@destroy')->name('admin.car_specification.delete_transmission');
    Route::post('/bulk-delete-transmission', 'BackEnd\Car\TransmissionTypeController@bulkDestroy')->name('admin.car_specification.bulk_delete_transmission');
  });

  Route::prefix('car-management')->group(function () {
    Route::get('/', 'BackEnd\Car\CarController@index')->name('admin.car_management.car');
    Route::get('/create', 'BackEnd\Car\CarController@create')->name('admin.cars_management.create_car');
    Route::post('store', 'BackEnd\Car\CarController@store')->name('admin.car_management.store_car');
    Route::post('update_featured', 'BackEnd\Car\CarController@updateFeatured')->name('admin.cars_management.update_featured_car');
    Route::post('update_status', 'BackEnd\Car\CarController@updateStatus')->name('admin.cars_management.update_car_status');
    Route::get('edit_car/{id}', 'BackEnd\Car\CarController@edit')->name('admin.cars_management.edit_car');
    Route::get('slider-images/{id}', 'BackEnd\Car\CarController@getSliderImages');
    Route::post('update/{id}', 'BackEnd\Car\CarController@update')->name('admin.car_management.update_car');
    Route::post('delete', 'BackEnd\Car\CarController@delete')->name('admin.cars_management.delete_car');

    Route::post('bulk_delete', 'BackEnd\Car\CarController@bulkDelete')->name('admin.car_management.bulk_delete.car');

    //#==========car slider image
    Route::post('/img-store', 'BackEnd\Car\CarController@imagesstore')->name('admin.car.imagesstore');
    Route::post('/img-remove', 'BackEnd\Car\CarController@imagermv')->name('admin.car.imagermv');
    Route::post('/img-db-remove', 'BackEnd\Car\CarController@imagedbrmv')->name('admin.car.imgdbrmv');
    //#==========car slider image end



    Route::post('/bult-delete', 'BackEnd\Car\CarController@bulk_delete')->name('admin.cars_management.bulk_delete_car');
    Route::post('/get-car-brand-model', 'BackEnd\Car\CarController@get_brand_model')->name('admin.get-car.brand.model');
  });

  // shop route start
  Route::prefix('/shop-management')->middleware('permission:Shop Management')->group(function () {
    // tax route
    Route::get('/tax-amount', 'BackEnd\BasicSettings\BasicController@productTaxAmount')->name('admin.shop_management.tax_amount');

    Route::post('/update-tax-amount', 'BackEnd\BasicSettings\BasicController@updateProductTaxAmount')->name('admin.shop_management.update_tax_amount');

    Route::get('/settings', 'BackEnd\BasicSettings\BasicController@settings')->name('admin.shop_management.settings');

    Route::post('/update-settings', 'BackEnd\BasicSettings\BasicController@updateSettings')->name('admin.shop_management.update_settings');

    // shipping charge route
    Route::get('/shipping-charges', 'BackEnd\Shop\ShippingChargeController@index')->name('admin.shop_management.shipping_charges');

    Route::post('/store-charge', 'BackEnd\Shop\ShippingChargeController@store')->name('admin.shop_management.store_charge');

    Route::post('/update-charge', 'BackEnd\Shop\ShippingChargeController@update')->name('admin.shop_management.update_charge');

    Route::post('/delete-charge/{id}', 'BackEnd\Shop\ShippingChargeController@destroy')->name('admin.shop_management.delete_charge');

    // coupon route
    Route::get('/coupons', 'BackEnd\Shop\CouponController@index')->name('admin.shop_management.coupons');

    Route::post('/store-coupon', 'BackEnd\Shop\CouponController@store')->name('admin.shop_management.store_coupon');

    Route::post('/update-coupon', 'BackEnd\Shop\CouponController@update')->name('admin.shop_management.update_coupon');

    Route::post('/delete-coupon/{id}', 'BackEnd\Shop\CouponController@destroy')->name('admin.shop_management.delete_coupon');

    // product category route
    Route::prefix('/product')->group(function () {
      Route::get('/categories', 'BackEnd\Shop\CategoryController@index')->name('admin.shop_management.product.categories');

      Route::post('/store-category', 'BackEnd\Shop\CategoryController@store')->name('admin.shop_management.product.store_category');

      Route::post('/update-category', 'BackEnd\Shop\CategoryController@update')->name('admin.shop_management.product.update_category');

      Route::post(
        '/delete-category/{id}',
        'BackEnd\Shop\CategoryController@destroy'
      )->name('admin.shop_management.product.delete_category');

      Route::post(
        '/bulk-delete-category',
        'BackEnd\Shop\CategoryController@bulkDestroy'
      )->name('admin.shop_management.product.bulk_delete_category');
    });

    // product route
    Route::get('/products', 'BackEnd\Shop\ProductController@index')->name('admin.shop_management.products');

    Route::get('/select-product-type', 'BackEnd\Shop\ProductController@productType')->name('admin.shop_management.select_product_type');

    Route::get(
      '/create-product/{type}',
      'BackEnd\Shop\ProductController@create'
    )->name('admin.shop_management.create_product');

    Route::post('/upload-slider-image', 'BackEnd\Shop\ProductController@uploadImage')->name('admin.shop_management.upload_slider_image');

    Route::post('/remove-slider-image', 'BackEnd\Shop\ProductController@removeImage')->name('admin.shop_management.remove_slider_image');

    Route::post('/store-product', 'BackEnd\Shop\ProductController@store')->name('admin.shop_management.store_product');

    Route::post('/product/{id}/update-featured-status', 'BackEnd\Shop\ProductController@updateFeaturedStatus')->name('admin.shop_management.product.update_featured_status');

    Route::get(
      '/edit-product/{id}/{type}',
      'BackEnd\Shop\ProductController@edit'
    )->name('admin.shop_management.edit_product');

    Route::post('/detach-slider-image', 'BackEnd\Shop\ProductController@detachImage')->name('admin.shop_management.detach_slider_image');

    Route::post('/update-product/{id}', 'BackEnd\Shop\ProductController@update')->name('admin.shop_management.update_product');

    Route::post('/delete-product/{id}', 'BackEnd\Shop\ProductController@destroy')->name('admin.shop_management.delete_product');

    Route::post('/bulk-delete-product', 'BackEnd\Shop\ProductController@bulkDestroy')->name('admin.shop_management.bulk_delete_product');

    // order route
    Route::get('/orders', 'BackEnd\Shop\OrderController@orders')->name('admin.shop_management.orders');

    Route::prefix('/order/{id}')->group(function () {
      Route::post('/update-payment-status', 'BackEnd\Shop\OrderController@updatePaymentStatus')->name('admin.shop_management.order.update_payment_status');

      Route::post('/update-order-status', 'BackEnd\Shop\OrderController@updateOrderStatus')->name('admin.shop_management.order.update_order_status');

      Route::get('/details', 'BackEnd\Shop\OrderController@show')->name('admin.shop_management.order.details');

      Route::post('/delete', 'BackEnd\Shop\OrderController@destroy')->name('admin.shop_management.order.delete');
    });

    Route::post('/bulk-delete-order', 'BackEnd\Shop\OrderController@bulkDestroy')->name('admin.shop_management.bulk_delete_order');

    // report route
    Route::get('/report', 'BackEnd\Shop\OrderController@report')->name('admin.shop_management.report');

    Route::get('/export-report', 'BackEnd\Shop\OrderController@exportReport')->name('admin.shop_management.export_report');
  });
  // shop route end

  // user management route start
  Route::prefix('/user-management')->middleware('permission:User Management')->group(function () {
    // registered user route
    Route::get('/registered-users', 'BackEnd\User\UserController@index')->name('admin.user_management.registered_users');

    Route::get('/create', 'BackEnd\User\UserController@create')->name('admin.user_management.registered_user.create');
    Route::post('/store', 'BackEnd\User\UserController@store')->name('admin.user_management.registered_user.store');

    Route::prefix('/user/{id}')->group(function () {

      Route::get('/edit', 'BackEnd\User\UserController@edit')->name('admin.user_management.registered_user.edit');
      Route::post('/update', 'BackEnd\User\UserController@update')->name('admin.user_management.registered_user.update');

      Route::post('/update-account-status', 'BackEnd\User\UserController@updateAccountStatus')->name('admin.user_management.user.update_account_status');

      Route::post('/update-email-status', 'BackEnd\User\UserController@updateEmailStatus')->name('admin.user_management.user.update_email_status');

      Route::get('/change-password', 'BackEnd\User\UserController@changePassword')->name('admin.user_management.user.change_password');

      Route::post('/update-password', 'BackEnd\User\UserController@updatePassword')->name('admin.user_management.user.update_password');

      Route::post('/delete', 'BackEnd\User\UserController@destroy')->name('admin.user_management.user.delete');
      Route::get('/secret-login', 'BackEnd\User\UserController@secret_login')->name('admin.user_management.user.secret-login');
    });

    Route::post('/bulk-delete-user', 'BackEnd\User\UserController@bulkDestroy')->name('admin.user_management.bulk_delete_user');

    // subscriber route
    Route::get('/subscribers', 'BackEnd\User\SubscriberController@index')->name('admin.user_management.subscribers');

    Route::post('/subscriber/{id}/delete', 'BackEnd\User\SubscriberController@destroy')->name('admin.user_management.subscriber.delete');

    Route::post(
      '/bulk-delete-subscriber',
      'BackEnd\User\SubscriberController@bulkDestroy'
    )->name('admin.user_management.bulk_delete_subscriber');

    Route::get('/mail-for-subscribers', 'BackEnd\User\SubscriberController@writeEmail')->name('admin.user_management.mail_for_subscribers');

    Route::post(
      '/subscribers/send-email',
      'BackEnd\User\SubscriberController@prepareEmail'
    )->name('admin.user_management.subscribers.send_email');

    // push notification route
    Route::prefix('/push-notification')->group(function () {
      Route::get('/settings', 'BackEnd\User\PushNotificationController@settings')->name('admin.user_management.push_notification.settings');

      Route::post('/update-settings', 'BackEnd\User\PushNotificationController@updateSettings')->name('admin.user_management.push_notification.update_settings');

      Route::get('/notification-for-visitors', 'BackEnd\User\PushNotificationController@writeNotification')->name('admin.user_management.push_notification.notification_for_visitors');

      Route::post('/send', 'BackEnd\User\PushNotificationController@sendNotification')->name('admin.user_management.push_notification.send');
    });
  });
  // user management route end

  // vendor management route start
  Route::prefix('/vendor-management')->middleware('permission:User Management')->group(function () {
    Route::get('/settings', 'BackEnd\VendorManagementController@settings')->name('admin.vendor_management.settings');
    Route::post('/settings/update', 'BackEnd\VendorManagementController@update_setting')->name('admin.vendor_management.setting.update');

    Route::get('/add-vendor', 'BackEnd\VendorManagementController@add')->name('admin.vendor_management.add_vendor');
    Route::post('/save-vendor', 'BackEnd\VendorManagementController@create')->name('admin.vendor_management.save-vendor');

    Route::get('/registered-vendors', 'BackEnd\VendorManagementController@index')->name('admin.vendor_management.registered_vendor');

    Route::prefix('/vendor/{id}')->group(function () {

      Route::post(
        '/update-account-status',
        'BackEnd\VendorManagementController@updateAccountStatus'
      )->name('admin.vendor_management.vendor.update_account_status');

      Route::post(
        '/update-email-status',
        'BackEnd\VendorManagementController@updateEmailStatus'
      )->name('admin.vendor_management.vendor.update_email_status');

      Route::get('/details', 'BackEnd\VendorManagementController@show')->name('admin.vendor_management.vendor_details');

      Route::get('/edit', 'BackEnd\VendorManagementController@edit')->name('admin.edit_management.vendor_edit');

      Route::post('/update', 'BackEnd\VendorManagementController@update')->name('admin.vendor_management.vendor.update_vendor');

      Route::post(
        '/update/vendor/balance',
        'BackEnd\VendorManagementController@update_vendor_balance'
      )->name('admin.vendor_management.update_vendor_balance');

      Route::get('/change-password', 'BackEnd\VendorManagementController@changePassword')->name('admin.vendor_management.vendor.change_password');

      Route::post('/update-password', 'BackEnd\VendorManagementController@updatePassword')->name('admin.vendor_management.vendor.update_password');

      Route::post('/delete', 'BackEnd\VendorManagementController@destroy')->name('admin.vendor_management.vendor.delete');
    });

    Route::post('/vendor/current-package/remove', 'BackEnd\VendorManagementController@removeCurrPackage')->name('vendor.currPackage.remove');

    Route::post('/vendor/current-package/change', 'BackEnd\VendorManagementController@changeCurrPackage')->name('vendor.currPackage.change');

    Route::post('/vendor/current-package/add', 'BackEnd\VendorManagementController@addCurrPackage')->name('vendor.currPackage.add');

    Route::post('/vendor/next-package/remove', 'BackEnd\VendorManagementController@removeNextPackage')->name('vendor.nextPackage.remove');

    Route::post('/vendor/next-package/change', 'BackEnd\VendorManagementController@changeNextPackage')->name('vendor.nextPackage.change');

    Route::post(
      '/vendor/next-package/add',
      'BackEnd\VendorManagementController@addNextPackage'
    )->name('vendor.nextPackage.add');


    Route::post(
      '/bulk-delete-vendor',
      'BackEnd\VendorManagementController@bulkDestroy'
    )->name('admin.vendor_management.bulk_delete_vendor');

    Route::get('/secret-login/{id}', 'BackEnd\VendorManagementController@secret_login')->name('admin.vendor_management.vendor.secret_login');
  });
  // vendor management route start

  // home-page route start
  Route::prefix('/home-page')->middleware('permission:Home Page')->group(function () {
    // hero section
    Route::prefix('/hero-section')->group(function () {
      // slider version route
      Route::prefix('/slider-version')->group(function () {
        Route::get('', 'BackEnd\HomePage\Hero\SliderController@index')->name('admin.home_page.hero_section.slider_version');

        Route::post('/store', 'BackEnd\HomePage\Hero\SliderController@store')->name('admin.home_page.hero_section.slider_version.store');

        Route::post('/update', 'BackEnd\HomePage\Hero\SliderController@update')->name('admin.home_page.hero_section.slider_version.update');

        Route::post('/{id}/delete', 'BackEnd\HomePage\Hero\SliderController@destroy')->name('admin.home_page.hero_section.slider_version.delete');

        Route::post('update-video-url', 'BackEnd\HomePage\Hero\SliderController@update_video_url')->name('admin.home_page.hero_section.update.video-url');
      });

      // static version route
      Route::prefix('/static-version')->group(function () {
        Route::get('', 'BackEnd\HomePage\Hero\StaticController@index')->name('admin.home_page.hero_section.static_version');

        Route::post('/update-image', 'BackEnd\HomePage\Hero\StaticController@updateImage')->name('admin.home_page.hero_section.static_version.update_image');

        Route::post(
          '/update-information',
          'BackEnd\HomePage\Hero\StaticController@updateInformation'
        )->name('admin.home_page.hero_section.static_version.update_information');
      });
    });

    // category section
    Route::get('/category-section', 'BackEnd\HomePage\CategorySectionController@index')->name('admin.home_page.category_section');

    Route::post('/update-category-section-image', 'BackEnd\HomePage\CategorySectionController@updateImage')->name('admin.home_page.update_category_section_image');

    Route::post('/update-category-section', 'BackEnd\HomePage\CategorySectionController@update')->name('admin.home_page.update_category_section');


    // work process section
    Route::get('/work-process-section', 'BackEnd\HomePage\WorkProcessController@sectionInfo')->name('admin.home_page.work_process_section');

    Route::post('/update-work-process-section', 'BackEnd\HomePage\WorkProcessController@updateSectionInfo')->name('admin.home_page.update_work_process_section');

    Route::prefix('/work-process')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\WorkProcessController@storeWorkProcess')->name('admin.home_page.store_work_process');

      Route::post('/update', 'BackEnd\HomePage\WorkProcessController@updateWorkProcess')->name('admin.home_page.update_work_process');

      Route::post('{id}/delete', 'BackEnd\HomePage\WorkProcessController@destroyWorkProcess')->name('admin.home_page.delete_work_process');

      Route::post('/bulk-delete', 'BackEnd\HomePage\WorkProcessController@bulkDestroyWorkProcess')->name('admin.home_page.bulk_delete_work_process');
    });

    // features section
    Route::get('/feature-section', 'BackEnd\HomePage\FeatureController@sectionInfo')->name('admin.home_page.feature_section');

    Route::post('/update-feature-section', 'BackEnd\HomePage\FeatureController@updateSectionInfo')->name('admin.home_page.update_feature_section');

    Route::prefix('/feature')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\FeatureController@storeFeature')->name('admin.home_page.store_feature');

      Route::post('/update', 'BackEnd\HomePage\FeatureController@updateFeature')->name('admin.home_page.update_feature');

      Route::post('{id}/delete', 'BackEnd\HomePage\FeatureController@destroyFeature')->name('admin.home_page.delete_feature');

      Route::post('/bulk-delete', 'BackEnd\HomePage\FeatureController@bulkDestroyFeature')->name('admin.home_page.bulk_delete_feature');
    });

    // counter section
    Route::get('/counter-section', 'BackEnd\HomePage\CounterController@index')->name('admin.home_page.counter_section');

    Route::post('/update-counter-section-image', 'BackEnd\HomePage\CounterController@updateImage')->name('admin.home_page.update_counter_section_image');

    Route::post('/update-counter-section-info', 'BackEnd\HomePage\CounterController@updateInfo')->name('admin.home_page.update_counter_section_info');

    Route::prefix('/counter')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\CounterController@storeCounter')->name('admin.home_page.store_counter');

      Route::post('/update', 'BackEnd\HomePage\CounterController@updateCounter')->name('admin.home_page.update_counter');

      Route::post('{id}/delete', 'BackEnd\HomePage\CounterController@destroyCounter')->name('admin.home_page.delete_counter');

      Route::post('/bulk-delete', 'BackEnd\HomePage\CounterController@bulkDestroyCounter')->name('admin.home_page.bulk_delete_counter');
    });

    // testimonial section
    Route::get('/testimonial-section', 'BackEnd\HomePage\TestimonialController@index')->name('admin.home_page.testimonial_section');

    Route::post('/update-testimonial-section', 'BackEnd\HomePage\TestimonialController@updateSectionInfo')->name('admin.home_page.update_testimonial_section');

    Route::post('/update-testimonial-section-img', 'BackEnd\HomePage\TestimonialController@updateSectionBackground')->name('admin.home_page.update_testimonial_section_background');

    Route::prefix('/testimonial')->group(function () {
      Route::post('/store', 'BackEnd\HomePage\TestimonialController@storeTestimonial')->name('admin.home_page.store_testimonial');

      Route::post('/update', 'BackEnd\HomePage\TestimonialController@updateTestimonial')->name('admin.home_page.update_testimonial');

      Route::post('{id}/delete', 'BackEnd\HomePage\TestimonialController@destroyTestimonial')->name('admin.home_page.delete_testimonial');

      Route::post('/bulk-delete', 'BackEnd\HomePage\TestimonialController@bulkDestroyTestimonial')->name('admin.home_page.bulk_delete_testimonial');
    });

    // call to action section
    Route::get('/call-to-action-section', 'BackEnd\HomePage\CallToActionController@index')->name('admin.home_page.call_to_action_section');

    Route::post('/update-call-to-action-section-image', 'BackEnd\HomePage\CallToActionController@updateImage')->name('admin.home_page.update_call_to_action_section_image');

    Route::post('/update-call-to-action-section', 'BackEnd\HomePage\CallToActionController@update')->name('admin.home_page.update_call_to_action_section');

    // blog section
    Route::get('/blog-section', 'BackEnd\HomePage\BlogController@index')->name('admin.home_page.blog_section');

    Route::post('/update-blog-section', 'BackEnd\HomePage\BlogController@update')->name('admin.home_page.update_blog_section');

    // section customization
    Route::get('/section-customization', 'BackEnd\HomePage\SectionController@index')->name('admin.home_page.section_customization');

    Route::post(
      '/update-section-status',
      'BackEnd\HomePage\SectionController@update'
    )->name('admin.home_page.update_section_status');


    // banners route
    Route::get('/banners', 'BackEnd\HomePage\BannerController@index')->name('admin.home_page.banners');

    Route::post('/store-banners', 'BackEnd\HomePage\BannerController@store')->name('admin.home_page.store_banner');

    Route::post('/update-banners', 'BackEnd\HomePage\BannerController@update')->name('admin.home_page.update_banner');

    Route::post('/delete-banners/{id}', 'BackEnd\HomePage\BannerController@destroy')->name('admin.home_page.delete_banner');
  });

  // home-page route end


  #====support tickets ============

  Route::prefix('support-ticket')->group(function () {
    Route::get('/setting', 'BackEnd\SupportTicketController@setting')->name('admin.support_ticket.setting');
    Route::post('/setting/update', 'BackEnd\SupportTicketController@update_setting')->name('admin.support_ticket.update_setting');
    Route::get('/tickets', 'BackEnd\SupportTicketController@index')->name('admin.support_tickets');
    Route::get('/message/{id}', 'BackEnd\SupportTicketController@message')->name('admin.support_tickets.message');
    Route::post('/zip-upload', 'BackEnd\SupportTicketController@zip_file_upload')->name('admin.support_ticket.zip_file.upload');
    Route::post('/reply/{id}', 'BackEnd\SupportTicketController@ticketreply')->name('admin.support_ticket.reply');
    Route::post('/closed/{id}', 'BackEnd\SupportTicketController@ticket_closed')->name('admin.support_ticket.close');
    Route::post('/assign-stuff/{id}', 'BackEnd\SupportTicketController@assign_stuff')->name('assign_stuff.supoort.ticket');

    Route::get('/unassign-stuff/{id}', 'BackEnd\SupportTicketController@unassign_stuff')->name('admin.support_tickets.unassign');

    Route::post('/delete/{id}', 'BackEnd\SupportTicketController@delete')->name('admin.support_tickets.delete');
    Route::post('/bulk-delete', 'BackEnd\SupportTicketController@bulk_delete')->name('admin.support_tickets.bulk_delete');
  });


  // footer route start
  Route::prefix('/footer')->middleware('permission:Footer')->group(function () {
    // logo & image route
    Route::get('/logo-and-image', 'BackEnd\Footer\ImageController@index')->name('admin.footer.logo_and_image');

    Route::post('/update-logo', 'BackEnd\Footer\ImageController@updateLogo')->name('admin.footer.update_logo');

    Route::post(
      '/update-background-image',
      'BackEnd\Footer\ImageController@updateImage'
    )->name('admin.footer.update_background_image');

    // content route
    Route::get('/content', 'BackEnd\Footer\ContentController@index')->name('admin.footer.content');

    Route::post('/update-content', 'BackEnd\Footer\ContentController@update')->name('admin.footer.update_content');

    // quick link route
    Route::get('/quick-links', 'BackEnd\Footer\QuickLinkController@index')->name('admin.footer.quick_links');

    Route::post('/store-quick-link', 'BackEnd\Footer\QuickLinkController@store')->name('admin.footer.store_quick_link');

    Route::post('/update-quick-link', 'BackEnd\Footer\QuickLinkController@update')->name('admin.footer.update_quick_link');

    Route::post(
      '/delete-quick-link/{id}',
      'BackEnd\Footer\QuickLinkController@destroy'
    )->name('admin.footer.delete_quick_link');
  });
  // footer route end


  // custom-pages route start
  Route::prefix('/custom-pages')->middleware('permission:Custom Pages')->group(function () {
    Route::get('', 'BackEnd\CustomPageController@index')->name('admin.custom_pages');

    Route::get('/create-page', 'BackEnd\CustomPageController@create')->name('admin.custom_pages.create_page');

    Route::post('/store-page', 'BackEnd\CustomPageController@store')->name('admin.custom_pages.store_page');

    Route::get('/edit-page/{id}', 'BackEnd\CustomPageController@edit')->name('admin.custom_pages.edit_page');

    Route::post('/update-page/{id}', 'BackEnd\CustomPageController@update')->name('admin.custom_pages.update_page');

    Route::post('/delete-page/{id}', 'BackEnd\CustomPageController@destroy')->name('admin.custom_pages.delete_page');

    Route::post('/bulk-delete-page', 'BackEnd\CustomPageController@bulkDestroy')->name('admin.custom_pages.bulk_delete_page');
  });
  // custom-pages route end

  // blog route start
  Route::prefix('/blog-management')->middleware('permission:Blog Management')->group(function () {
    // blog category route
    Route::get('/categories', 'BackEnd\Journal\CategoryController@index')->name('admin.blog_management.categories');

    Route::post('/store-category', 'BackEnd\Journal\CategoryController@store')->name('admin.blog_management.store_category');

    Route::post('/update-category', 'BackEnd\Journal\CategoryController@update')->name('admin.blog_management.update_category');

    Route::post(
      '/delete-category/{id}',
      'BackEnd\Journal\CategoryController@destroy'
    )->name('admin.blog_management.delete_category');

    Route::post(
      '/bulk-delete-category',
      'BackEnd\Journal\CategoryController@bulkDestroy'
    )->name('admin.blog_management.bulk_delete_category');

    // blog route
    Route::get(
      '/blogs',
      'BackEnd\Journal\BlogController@index'
    )->name('admin.blog_management.blogs');

    Route::get('/create-blog', 'BackEnd\Journal\BlogController@create')->name('admin.blog_management.create_blog');

    Route::post('/store-blog', 'BackEnd\Journal\BlogController@store')->name('admin.blog_management.store_blog');

    Route::get('/edit-blog/{id}', 'BackEnd\Journal\BlogController@edit')->name('admin.blog_management.edit_blog');

    Route::post('/update-blog/{id}', 'BackEnd\Journal\BlogController@update')->name('admin.blog_management.update_blog');

    Route::post('/delete-blog/{id}', 'BackEnd\Journal\BlogController@destroy')->name('admin.blog_management.delete_blog');

    Route::post('/bulk-delete-blog', 'BackEnd\Journal\BlogController@bulkDestroy')->name('admin.blog_management.bulk_delete_blog');
  });
  // blog route end

  // faq route start
  Route::prefix('/faq-management')->middleware('permission:FAQ Management')->group(function () {
    Route::get('', 'BackEnd\FaqController@index')->name('admin.faq_management');

    Route::post('/store-faq', 'BackEnd\FaqController@store')->name('admin.faq_management.store_faq');

    Route::post('/update-faq', 'BackEnd\FaqController@update')->name('admin.faq_management.update_faq');

    Route::post('/delete-faq/{id}', 'BackEnd\FaqController@destroy')->name('admin.faq_management.delete_faq');

    Route::post('/bulk-delete-faq', 'BackEnd\FaqController@bulkDestroy')->name('admin.faq_management.bulk_delete_faq');
  });
  // faq route end

  // advertise route start
  Route::prefix('/advertise')->middleware('permission:Advertise')->group(function () {
    Route::get('/settings', 'BackEnd\AdvertisementController@advertiseSettings')->name('admin.advertise.settings');

    Route::post('/update-settings', 'BackEnd\AdvertisementController@updateAdvertiseSettings')->name('admin.advertise.update_settings');

    Route::get('/all-advertisement', 'BackEnd\AdvertisementController@index')->name('admin.advertise.all_advertisement');

    Route::post('/store-advertisement', 'BackEnd\AdvertisementController@store')->name('admin.advertise.store_advertisement');

    Route::post(
      '/update-advertisement',
      'BackEnd\AdvertisementController@update'
    )->name('admin.advertise.update_advertisement');

    Route::post('/delete-advertisement/{id}', 'BackEnd\AdvertisementController@destroy')->name('admin.advertise.delete_advertisement');

    Route::post('/bulk-delete-advertisement', 'BackEnd\AdvertisementController@bulkDestroy')->name('admin.advertise.bulk_delete_advertisement');
  });
  // advertise route end

  // announcement-popup route start
  Route::prefix('/announcement-popups')->middleware('permission:Announcement Popups')->group(function () {
    Route::get('', 'BackEnd\PopupController@index')->name('admin.announcement_popups');

    Route::get('/select-popup-type', 'BackEnd\PopupController@popupType')->name('admin.announcement_popups.select_popup_type');

    Route::get('/create-popup/{type}', 'BackEnd\PopupController@create')->name('admin.announcement_popups.create_popup');

    Route::post('/store-popup', 'BackEnd\PopupController@store')->name('admin.announcement_popups.store_popup');

    Route::post('/popup/{id}/update-status', 'BackEnd\PopupController@updateStatus')->name('admin.announcement_popups.update_popup_status');

    Route::get('/edit-popup/{id}', 'BackEnd\PopupController@edit')->name('admin.announcement_popups.edit_popup');

    Route::post('/update-popup/{id}', 'BackEnd\PopupController@update')->name('admin.announcement_popups.update_popup');

    Route::post('/delete-popup/{id}', 'BackEnd\PopupController@destroy')->name('admin.announcement_popups.delete_popup');

    Route::post('/bulk-delete-popup', 'BackEnd\PopupController@bulkDestroy')->name('admin.announcement_popups.bulk_delete_popup');
  });
  // announcement-popup route end

  // payment-gateway route start
  Route::prefix('/payment-gateways')->middleware('permission:Payment Gateways')->group(function () {
    Route::get('/online-gateways', 'BackEnd\PaymentGateway\OnlineGatewayController@index')->name('admin.payment_gateways.online_gateways');

    Route::post('/update-paypal-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePayPalInfo')->name('admin.payment_gateways.update_paypal_info');

    Route::post('/update-instamojo-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateInstamojoInfo')->name('admin.payment_gateways.update_instamojo_info');

    Route::post('/update-paystack-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaystackInfo')->name('admin.payment_gateways.update_paystack_info');

    Route::post('/update-flutterwave-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateFlutterwaveInfo')->name('admin.payment_gateways.update_flutterwave_info');

    Route::post('/update-razorpay-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateRazorpayInfo')->name('admin.payment_gateways.update_razorpay_info');

    Route::post('/update-mercadopago-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMercadoPagoInfo')->name('admin.payment_gateways.update_mercadopago_info');

    Route::post('/update-mollie-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateMollieInfo')->name('admin.payment_gateways.update_mollie_info');

    Route::post('/update-stripe-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateStripeInfo')->name('admin.payment_gateways.update_stripe_info');

    Route::post('/update-paytm-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updatePaytmInfo')->name('admin.payment_gateways.update_paytm_info');
    Route::post('/update-anet-info', 'BackEnd\PaymentGateway\OnlineGatewayController@updateAnetInfo')->name('admin.payment_gateways.update_anet_info');

    Route::get('/offline-gateways', 'BackEnd\PaymentGateway\OfflineGatewayController@index')->name('admin.payment_gateways.offline_gateways');

    Route::post('/store-offline-gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@store')->name('admin.payment_gateways.store_offline_gateway');

    Route::post('/update-status/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@updateStatus')->name('admin.payment_gateways.update_status');

    Route::post('/update-offline-gateway', 'BackEnd\PaymentGateway\OfflineGatewayController@update')->name('admin.payment_gateways.update_offline_gateway');

    Route::post('/delete-offline-gateway/{id}', 'BackEnd\PaymentGateway\OfflineGatewayController@destroy')->name('admin.payment_gateways.delete_offline_gateway');
  });
  // payment-gateway route end

  Route::prefix('/basic-settings')->middleware('permission:Basic Settings')->group(function () {
    // basic settings favicon route
    Route::get('pwa', 'BackEnd\BasicSettings\BasicController@pwa')->name('admin.pwa');
    Route::post('/pwa/post', 'BackEnd\BasicSettings\BasicController@updatepwa')->name('admin.pwa.update');

    Route::get('/favicon', 'BackEnd\BasicSettings\BasicController@favicon')->name('admin.basic_settings.favicon');

    Route::post('/update-favicon', 'BackEnd\BasicSettings\BasicController@updateFavicon')->name('admin.basic_settings.update_favicon');

    // basic settings logo route
    Route::get('/logo', 'BackEnd\BasicSettings\BasicController@logo')->name('admin.basic_settings.logo');

    Route::post('/update-logo', 'BackEnd\BasicSettings\BasicController@updateLogo')->name('admin.basic_settings.update_logo');

    // basic settings information route
    Route::get('/information', 'BackEnd\BasicSettings\BasicController@information')->name('admin.basic_settings.information');

    Route::post('/update-info', 'BackEnd\BasicSettings\BasicController@updateInfo')->name('admin.basic_settings.update_info');

    Route::get('/general-settings', 'BackEnd\BasicSettings\BasicController@general_settings')->name('admin.basic_settings.general_settings');

    Route::post('/update-general-settings', 'BackEnd\BasicSettings\BasicController@update_general_setting')->name('admin.basic_settings.general_settings.update');

    Route::get('/contact-page', 'BackEnd\BasicSettings\BasicController@contact_page')->name('admin.basic_settings.contact_page');

    Route::post('/update-contact-page', 'BackEnd\BasicSettings\BasicController@update_contact_page')->name('admin.basic_settings.contact_page.update');

    // basic settings (theme & home) route
    Route::get('/theme-and-home', 'BackEnd\BasicSettings\BasicController@themeAndHome')->name('admin.basic_settings.theme_and_home');

    Route::post(
      '/update-theme-and-home',
      'BackEnd\BasicSettings\BasicController@updateThemeAndHome'
    )->name('admin.basic_settings.update_theme_and_home');

    // basic settings currency route
    Route::get('/currency', 'BackEnd\BasicSettings\BasicController@currency')->name('admin.basic_settings.currency');

    Route::post('/update-currency', 'BackEnd\BasicSettings\BasicController@updateCurrency')->name('admin.basic_settings.update_currency');

    // basic settings appearance route
    Route::get('/appearance', 'BackEnd\BasicSettings\BasicController@appearance')->name('admin.basic_settings.appearance');

    Route::post('/update-appearance', 'BackEnd\BasicSettings\BasicController@updateAppearance')->name('admin.basic_settings.update_appearance');

    // basic settings mail route start
    Route::get('/mail-from-admin', 'BackEnd\BasicSettings\BasicController@mailFromAdmin')->name('admin.basic_settings.mail_from_admin');

    Route::post(
      '/update-mail-from-admin',
      'BackEnd\BasicSettings\BasicController@updateMailFromAdmin'
    )->name('admin.basic_settings.update_mail_from_admin');

    Route::get('/mail-to-admin', 'BackEnd\BasicSettings\BasicController@mailToAdmin')->name('admin.basic_settings.mail_to_admin');

    Route::post(
      '/update-mail-to-admin',
      'BackEnd\BasicSettings\BasicController@updateMailToAdmin'
    )->name('admin.basic_settings.update_mail_to_admin');

    Route::get('/mail-templates', 'BackEnd\BasicSettings\MailTemplateController@index')->name('admin.basic_settings.mail_templates');

    Route::get('/edit-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@edit')->name('admin.basic_settings.edit_mail_template');

    Route::post('/update-mail-template/{id}', 'BackEnd\BasicSettings\MailTemplateController@update')->name('admin.basic_settings.update_mail_template');
    // basic settings mail route end

    // basic settings breadcrumb route
    Route::get('/breadcrumb', 'BackEnd\BasicSettings\BasicController@breadcrumb')->name('admin.basic_settings.breadcrumb');

    Route::post('/update-breadcrumb', 'BackEnd\BasicSettings\BasicController@updateBreadcrumb')->name('admin.basic_settings.update_breadcrumb');

    // basic settings page-headings route
    Route::get('/page-headings', 'BackEnd\BasicSettings\PageHeadingController@pageHeadings')->name('admin.basic_settings.page_headings');

    Route::post(
      '/update-page-headings',
      'BackEnd\BasicSettings\PageHeadingController@updatePageHeadings'
    )->name('admin.basic_settings.update_page_headings');

    // basic settings plugins route start
    Route::get('/plugins', 'BackEnd\BasicSettings\BasicController@plugins')->name('admin.basic_settings.plugins');

    Route::post('/update-disqus', 'BackEnd\BasicSettings\BasicController@updateDisqus')->name('admin.basic_settings.update_disqus');

    Route::post('/update-tawkto', 'BackEnd\BasicSettings\BasicController@updateTawkTo')->name('admin.basic_settings.update_tawkto');

    Route::post('/update-recaptcha', 'BackEnd\BasicSettings\BasicController@updateRecaptcha')->name('admin.basic_settings.update_recaptcha');

    Route::post('/update-facebook', 'BackEnd\BasicSettings\BasicController@updateFacebook')->name('admin.basic_settings.update_facebook');

    Route::post('/update-google', 'BackEnd\BasicSettings\BasicController@updateGoogle')->name('admin.basic_settings.update_google');

    Route::post('/update-whatsapp', 'BackEnd\BasicSettings\BasicController@updateWhatsApp')->name('admin.basic_settings.update_whatsapp');
    // basic settings plugins route end

    // basic settings seo route
    Route::get('/seo', 'BackEnd\BasicSettings\SEOController@index')->name('admin.basic_settings.seo');

    Route::post('/update-seo', 'BackEnd\BasicSettings\SEOController@update')->name('admin.basic_settings.update_seo');

    // basic settings maintenance-mode route
    Route::get('/maintenance-mode', 'BackEnd\BasicSettings\BasicController@maintenance')->name('admin.basic_settings.maintenance_mode');

    Route::post('/update-maintenance-mode', 'BackEnd\BasicSettings\BasicController@updateMaintenance')->name('admin.basic_settings.update_maintenance_mode');

    // basic settings cookie-alert route
    Route::get('/cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@cookieAlert')->name('admin.basic_settings.cookie_alert');

    Route::post('/update-cookie-alert', 'BackEnd\BasicSettings\CookieAlertController@updateCookieAlert')->name('admin.basic_settings.update_cookie_alert');

    // basic-settings social-media route
    Route::get('/social-medias', 'BackEnd\BasicSettings\SocialMediaController@index')->name('admin.basic_settings.social_medias');

    Route::post('/store-social-media', 'BackEnd\BasicSettings\SocialMediaController@store')->name('admin.basic_settings.store_social_media');

    Route::post('/update-social-media', 'BackEnd\BasicSettings\SocialMediaController@update')->name('admin.basic_settings.update_social_media');

    Route::post('/delete-social-media/{id}', 'BackEnd\BasicSettings\SocialMediaController@destroy')->name('admin.basic_settings.delete_social_media');
  });



  // admin management route start
  Route::prefix('/admin-management')->middleware('permission:Admin Management')->group(function () {
    // role-permission route
    Route::get('/role-permissions', 'BackEnd\Administrator\RolePermissionController@index')->name('admin.admin_management.role_permissions');

    Route::post('/store-role', 'BackEnd\Administrator\RolePermissionController@store')->name('admin.admin_management.store_role');

    Route::get('/role/{id}/permissions', 'BackEnd\Administrator\RolePermissionController@permissions')->name('admin.admin_management.role.permissions');

    Route::post('/role/{id}/update-permissions', 'BackEnd\Administrator\RolePermissionController@updatePermissions')->name('admin.admin_management.role.update_permissions');

    Route::post('/update-role', 'BackEnd\Administrator\RolePermissionController@update')->name('admin.admin_management.update_role');

    Route::post('/delete-role/{id}', 'BackEnd\Administrator\RolePermissionController@destroy')->name('admin.admin_management.delete_role');

    // registered admin route
    Route::get('/registered-admins', 'BackEnd\Administrator\SiteAdminController@index')->name('admin.admin_management.registered_admins');

    Route::post('/store-admin', 'BackEnd\Administrator\SiteAdminController@store')->name('admin.admin_management.store_admin');

    Route::post('/update-status/{id}', 'BackEnd\Administrator\SiteAdminController@updateStatus')->name('admin.admin_management.update_status');

    Route::post('/update-admin', 'BackEnd\Administrator\SiteAdminController@update')->name('admin.admin_management.update_admin');

    Route::post('/delete-admin/{id}', 'BackEnd\Administrator\SiteAdminController@destroy')->name('admin.admin_management.delete_admin');
  });
  // admin management route end


  // language management route start
  Route::prefix('/language-management')->middleware('permission:Language Management')->group(function () {
    Route::get('', 'BackEnd\LanguageController@index')->name('admin.language_management');

    Route::post('/store', 'BackEnd\LanguageController@store')->name('admin.language_management.store');

    Route::post('/{id}/make-default-language', 'BackEnd\LanguageController@makeDefault')->name('admin.language_management.make_default_language');

    Route::post('/update', 'BackEnd\LanguageController@update')->name('admin.language_management.update');

    Route::get('/{id}/edit-keyword', 'BackEnd\LanguageController@editKeyword')->name('admin.language_management.edit_keyword');

    Route::post('add-keyword', 'BackEnd\LanguageController@addKeyword')->name('admin.language_management.add_keyword');

    Route::post('/{id}/update-keyword', 'BackEnd\LanguageController@updateKeyword')->name('admin.language_management.update_keyword');

    Route::post('/{id}/delete', 'BackEnd\LanguageController@destroy')->name('admin.language_management.delete');

    Route::get('/{id}/check-rtl', 'BackEnd\LanguageController@checkRTL');
    Route::get('/{id}/check-rtl2', 'BackEnd\LanguageController@checkRTL2');
  });
  // language management route end
});
