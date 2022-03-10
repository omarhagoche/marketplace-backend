<?php

/**
 * File name: web.php
 * Last modified: 2020.06.11 at 15:08:31
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('test/whatsapp/{phone?}/{msg?}', function ($phone, $msg) {
    $respone = [
        "phone" => $phone,
        "message" => $msg,
        "response" => send_whatsapp_msg($phone, $msg)
    ];
    return $respone;
});


Route::get('register-restaurant', 'RegisterRestaurantController@show');
Route::post('register-restaurant', 'RegisterRestaurantController@register');

Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');
Auth::routes();

Route::view('privacy', 'privacy');

Route::get('payments/failed', 'PayPalController@index')->name('payments.failed');
Route::get('payments/razorpay/checkout', 'RazorPayController@checkout');
Route::post('payments/razorpay/pay-success/{userId}/{deliveryAddressId?}/{couponCode?}', 'RazorPayController@paySuccess');
Route::get('payments/razorpay', 'RazorPayController@index');

Route::get('payments/paypal/express-checkout', 'PayPalController@getExpressCheckout')->name('paypal.express-checkout');
Route::get('payments/paypal/express-checkout-success', 'PayPalController@getExpressCheckoutSuccess');
Route::get('payments/paypal', 'PayPalController@index')->name('paypal.index');

Route::get('firebase/sw-js', 'AppSettingController@initFirebase');


Route::get('storage/app/public/{id}/{conversion}/{filename?}', 'UploadController@storage');
Route::middleware('auth')->group(function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('/', 'DashboardController@index')->name('dashboard');

    Route::post('uploads/store', 'UploadController@store')->name('medias.create');
    Route::get('users/profile', 'UserController@profile')->name('users.profile');
    Route::post('users/remove-media', 'UserController@removeMedia');
    Route::resource('users', 'UserController');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['middleware' => ['permission:medias']], function () {
        Route::get('uploads/all/{collection?}', 'UploadController@all');
        Route::get('uploads/collectionsNames', 'UploadController@collectionsNames');
        Route::post('uploads/clear', 'UploadController@clear')->name('medias.delete');
        Route::get('medias', 'UploadController@index')->name('medias');
        Route::get('uploads/clear-all', 'UploadController@clearAll');
    });

    Route::group(['middleware' => ['permission:permissions.index']], function () {
        Route::get('permissions/role-has-permission', 'PermissionController@roleHasPermission');
        Route::get('permissions/refresh-permissions', 'PermissionController@refreshPermissions');
    });
    Route::group(['middleware' => ['permission:permissions.index']], function () {
        Route::post('permissions/give-permission-to-role', 'PermissionController@givePermissionToRole');
        Route::post('permissions/revoke-permission-to-role', 'PermissionController@revokePermissionToRole');
    });

    Route::group(['middleware' => ['permission:app-settings']], function () {
        Route::prefix('settings')->group(function () {
            Route::resource('permissions', 'PermissionController');
            Route::resource('roles', 'RoleController');
            Route::resource('customFields', 'CustomFieldController');
            Route::resource('currencies', 'CurrencyController')->except([
                'show'
            ]);
            Route::get('users/login-as-user/{id}', 'UserController@loginAsUser')->name('users.login-as-user');
            Route::patch('update', 'AppSettingController@update');
            Route::patch('translate', 'AppSettingController@translate');
            Route::get('sync-translation', 'AppSettingController@syncTranslation');
            Route::get('clear-cache', 'AppSettingController@clearCache');
            Route::get('check-update', 'AppSettingController@checkForUpdates');
            // disable special character and number in route params
            Route::get('/{type?}/{tab?}', 'AppSettingController@index')
                ->where('type', '[A-Za-z]*')->where('tab', '[A-Za-z]*')->name('app-settings');
        });
    });

    Route::post('cuisines/remove-media', 'CuisineController@removeMedia');
    Route::resource('cuisines', 'CuisineController')->except([
        'show'
    ]);

    Route::post('restaurants/remove-media', 'RestaurantController@removeMedia');
    Route::get('requestedRestaurants', 'RestaurantController@requestedRestaurants')->name('requestedRestaurants.index'); //adeed
    Route::resource('restaurants', 'RestaurantController')->except([
        'show'
    ]);
    Route::resource('restaurantDistancePrices', 'RestaurantDistancePriceController')->except([
        'show'
    ]);

    Route::post('categories/remove-media', 'CategoryController@removeMedia');
    Route::resource('categories', 'CategoryController')->except([
        'show'
    ]);

    Route::resource('faqCategories', 'FaqCategoryController')->except([
        'show'
    ]);

    Route::resource('orderStatuses', 'OrderStatusController')->except([
        'create', 'store', 'destroy'
    ]);;

    Route::post('foods/remove-media', 'FoodController@removeMedia');
    Route::resource('foods', 'FoodController')->except([
        'show'
    ]);

    Route::post('galleries/remove-media', 'GalleryController@removeMedia');
    Route::resource('galleries', 'GalleryController')->except([
        'show'
    ]);

    Route::resource('foodReviews', 'FoodReviewController')->except([
        'show'
    ]);


    Route::resource('nutrition', 'NutritionController')->except([
        'show'
    ]);

   

    Route::resource('payments', 'PaymentController')->except([
        'create', 'store', 'edit', 'destroy'
    ]);;

    Route::resource('faqs', 'FaqController')->except([
        'show'
    ]);
    Route::resource('restaurantReviews', 'RestaurantReviewController')->except([
        'show'
    ]);

    Route::resource('favorites', 'FavoriteController')->except([
        'show'
    ]);


    Route::get('orders/waitting-drivers', 'OrderController@ordersWaittingForDrivers')->name('orders.waitting_drivers');
    Route::get('orders/set-driver/{order_id}/{driver_id}', 'OrderController@setDriverForOrder');
    
    Route::get('orders/statistics', 'OrderController@statistics')->name('orders.statistics');

    Route::resource('orders', 'OrderController');
    
    Route::resource('notifications', 'NotificationController')->except([
        'create', 'store', 'update', 'edit',
    ]);;

    Route::resource('carts', 'CartController')->except([
        'show', 'store', 'create'
    ]);
    Route::resource('deliveryAddresses', 'DeliveryAddressController')->except([
        'show'
    ]);

    Route::get('drivers/map', 'DriverController@map')->name('drivers.map');
    Route::resource('drivers', 'DriverController');

    Route::get('drivers/update-data-in-firestore', 'DriverController@updateDataInFirestore')->name('drivers.update_data_in_firestore');

    Route::resource('driverTypes', 'DriverTypeController');

    Route::resource('driverReviews', 'DriverReviewController');

    Route::resource('earnings', 'EarningController')->except([
        'show', 'edit', 'update'
    ]);

    Route::resource('driversPayouts', 'DriversPayoutController')->except([
        'show', 'edit', 'update'
    ]);

    Route::resource('restaurantsPayouts', 'RestaurantsPayoutController')->except([
        'show', 'edit', 'update'
    ]);

    Route::resource('extraGroups', 'ExtraGroupController')->except([
        'show'
    ]);

    
    Route::resource('coupons', 'CouponController'); //->except(['show']);
    Route::post('slides/remove-media', 'SlideController@removeMedia');
    Route::resource('slides', 'SlideController')->except([
        'show'
    ]);

    Route::get('settlementDrivers/available', 'SettlementDriverController@indexAvailable')->name('settlementDrivers.indexAvailable');
    Route::get('settlementDrivers/available/{driver_id}', 'SettlementDriverController@showAvailable')->name('settlementDrivers.showAvailable');
    Route::resource('settlementDrivers', 'SettlementDriverController');
    Route::get('settlementDrivers/print/{id}', 'SettlementDriverController@print');


    Route::get('settlementManagers/available', 'SettlementManagerController@indexAvailable')->name('settlementManagers.indexAvailable');
    Route::get('settlementManagers/available/{driver_id}', 'SettlementManagerController@showAvailable')->name('settlementManagers.showAvailable');
    Route::resource('settlementManagers', 'SettlementManagerController');
    Route::get('settlementManagers/print/{id}', 'SettlementManagerController@print');
    
    Route::get('orders/edit/foods/{order_id}', 'OrderController@editOrderFoods')->name('orders.edit-order-foods');
    Route::get('orders/show/coupon/{order_id}', 'OrderController@showCouponOrderFoods')->name('orders.show-order-coupon');
    Route::post('orders/store/coupon/restaurant/{order_id}', 'OrderController@storeRestaurantCouponOrderFoods')->name('orders.store-order-restaurant-coupon');
    Route::post('orders/store/coupon/delivery/{order_id}', 'OrderController@storeDeliveryCouponOrderFoods')->name('orders.store-order-delivery-coupon');
    Route::resource('foodOrders', 'FoodOrderController');
    Route::get('foods/show/{id}', 'FoodController@showFood')->name('foods.get-one');
    
    Route::post('orders/edit/foods/extra/{orderFoods}', 'OrderController@addExtraInOrderFood')->name('orders.add-extra');
    Route::post('orders/remove/extra', 'OrderController@removeExtraInOrderFood')->name('orders.remove-extra');
    Route::post('orders/edit/foods/update', 'OrderController@updateOrderFoods')->name('orders.food-update-quantity');
    
    //// new Dashboard for operations
        Route::prefix('operations')->group(function () {
            // section users
            Route::view('/', 'operations.index')->name('operations.index');
            Route::get('users/profile/{userId}/info', 'Operations\ClientController@profile')->name('operations.users.profile.info');
            Route::get('users/profile/{userId}/statistics', 'Operations\ClientController@statistics')->name('operations.users.profile.statistics');
            Route::get('users/profile/{userId}/favorites', 'Operations\ClientController@favorites')->name('operations.users.profile.favorites');
            Route::get('users/profile/{userId}/orders', 'Operations\ClientController@orders')->name('operations.users.profile.orders');
            Route::get('users/profile/{userId}/coupons', 'Operations\ClientController@coupons')->name('operations.users.profile.coupons');
            Route::get('users/profile/{userId}/notes', 'Operations\ClientController@notes')->name('operations.users.profile.notes');
            Route::get('users/profile/{userId}/address', 'Operations\ClientController@address')->name('operations.users.profile.address');
            Route::get('users/profile/{userId}/address/{addressId}/default', 'Operations\ClientController@setAddressDefault')->name('operations.users.profile.address.setDefault');
            Route::delete('users/profile/{userId}/address/{addressId}/delete', 'Operations\ClientController@deleteAddress')->name('operations.users.profile.address.delete');
            Route::get('users/profile/{userId}/orders/{orderId}', 'Operations\ClientController@viewOrders')->name('operations.users.profile.orders.view');
            Route::resource('users', 'Operations\ClientController',['names' => 'operations.users']);

            //section driver
            Route::get('drivers/map', 'Operations\DriverController@map')->name('operations.drivers.map');
            Route::resource('drivers', 'Operations\DriverController',['names' => 'operations.drivers']);

            // section order
            Route::get('orders/show/coupon/{order_id}', 'Operations\OrderController@showCouponOrderFoods')->name('operations.orders.show-order-coupon');

            Route::get('orders/edit/foods/{order_id}', 'Operations\OrderController@editOrderFoods')->name('operations.orders.edit-order-foods');
            Route::get('orders/waitting-drivers', 'Operations\OrderController@ordersWaittingForDrivers')->name('operations.orders.waitting_drivers');
            Route::get('orders/set-driver/{order_id}/{driver_id}', 'Operations\OrderController@setDriverForOrder');
            Route::get('orders/statistics', 'Operations\OrderController@statistics')->name('operations.orders.statistics');
            Route::resource('orders', 'Operations\OrderController',['names' => 'operations.orders']);

            //Restaurant
            Route::get('restaurantProfile/{id}/users', 'Operations\RestaurantController@users')->name('operations.restaurant_profile.users');
            Route::get('restaurantProfile/{id}/users/create/{userId?}', 'Operations\RestaurantController@usersCreate')->name('operations.restaurant_profile.users.create');
            Route::post('restaurantProfile/{id}/users/store/{userId?}', 'Operations\RestaurantController@usersStore')->name('operations.restaurant_profile.users.store');
            Route::delete('restaurantProfile/{id}/users/{userId}/destroy', 'Operations\RestaurantController@usersDestroy')->name('operations.restaurant_profile.users.destroy');
            Route::get('restaurantProfile/review/{id}', 'Operations\RestaurantReviewController@indexByRestaurant')->name('operations.restaurant_review');
            Route::get('restaurantProfile/{id}', 'Operations\RestaurantProfileController@editProfileRestaurant')->name('operations.restaurant_profile_edit');
            Route::resource('restaurantProfile', 'Operations\RestaurantProfileController',['names' => 'operations.restaurant_profile']);
            
            Route::get('restaurantFoodsindex/{restaurant_id}', 'Operations\RestaurantProfileController@restaurantFoodsindex')->name('operations.restaurant.foods.index');
            Route::get('restaurantFoods/create/{restaurant_id}', 'Operations\RestaurantProfileController@restaurantFoodsCreate')->name('operations.restaurant.foods.create');
            Route::post('restaurantFoods/store/{restaurant_id}', 'Operations\RestaurantProfileController@restaurantFoodsStore')->name('operations.restaurant.foods.store');
            Route::get('restaurantFoods/edit/{restaurant_id}/{food_id}', 'Operations\RestaurantProfileController@restaurantFoodsEdit')->name('operations.restaurant.foods.edit');
            Route::put('restaurantFoods/update/{restaurant_id}/{food_id}', 'Operations\RestaurantProfileController@restaurantFoodsUpdate')->name('operations.restaurant.foods.update');
            Route::post('restaurantFoods/extra/store', 'Operations\RestaurantProfileController@restaurantFoodsExtraStore')->name('operations.restaurant.foods.extra.store');
            Route::put('restaurantFoods/extra/update/{extraFoodId}', 'Operations\RestaurantProfileController@restaurantFoodsExtraUpdate')->name('operations.restaurant.foods.extra.update');
            Route::delete('restaurantFoods/extra/delete/{extraFoodId}', 'Operations\RestaurantProfileController@restaurantFoodsExtraDelete')->name('operations.restaurant.foods.extra.delete');
            Route::delete('restaurantFoods/delete/{food_id}/{restaurant_id}', 'Operations\RestaurantProfileController@restaurantFoodsDelete')->name('operations.restaurant.foods.delete');
            
            Route::post('restaurantFoods/update', 'Operations\RestaurantProfileController@restaurantFoodUpdate')->name('operations.restaurant.food.update');

            Route::post('extras/remove-media', 'Operations\ExtraController@removeMedia');

            Route::resource('extras', 'Operations\ExtraController')->except([
                'show'
            ]);
            Route::post('extras/remove-media', 'Operations\ExtraController@removeMedia');
            Route::resource('extras', 'Operations\ExtraController',['names' => 'operations.extras']);
            Route::get('extrasindex/{restaurant_id}', 'Operations\ExtraController@indexByRestaurant')->name('operations.restaurant.extra.index');
            Route::get('extrascreate/{restaurant_id}', 'Operations\ExtraController@createByrestuarant')->name('operations.restaurant.extra.create');
            Route::get('extrasedit/{id}/{restaurant_id}', 'Operations\ExtraController@editByRestuarant')->name('operations.restaurant.extra.edit');
    });

});
