<?php

/**
 * File name: api.php
 * Last modified: 2020.08.20 at 17:21:16
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$auth = "apiToken";

if (request()->segment(2) == 'v2') { // if request for starts with V2 , set "api:JWT guard" as default guard
    auth()->shouldUse('apiJwt');
	$auth = "apiJwt";
}

$apiRoutes = function()use($auth) {

Route::prefix('driver')->group(function () {
    Route::post('login', 'API\Driver\UserAPIController@login');
    Route::get('register', 'API\UserAPIController@sendRegisterCodePhone');
    Route::post('confirm_register', 'API\UserAPIController@confirmRegisterCodePhone');
    Route::post('delete', 'API\UserAPIController@delete');
    Route::post('register', 'API\UserAPIController@registerDriver');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('reset_password', 'API\UserAPIController@sendResetCodePhone');
    Route::post('confirm_reset_code', 'API\UserAPIController@confirmResetCodePhone');
    Route::post('reset_password', 'API\UserAPIController@ResetPassword');
    Route::get('user', 'API\Driver\UserAPIController@user');
    Route::get('settings', 'API\Driver\UserAPIController@settings');
    Route::apiResource('driverTypes', 'API\DriverTypeAPIController')->only([
        'index', 'show'
    ]);
});

Route::prefix('manager')->group(function () {
    Route::post('login', 'API\Manager\UserAPIController@login');
    Route::get('register', 'API\UserAPIController@sendRegisterCodePhone');
    Route::post('confirm_register', 'API\UserAPIController@confirmRegisterCodePhone');
    Route::post('register', 'API\Manager\UserAPIController@register');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('reset_password', 'API\UserAPIController@sendResetCodePhone');
    Route::post('confirm_reset_code', 'API\UserAPIController@confirmResetCodePhone');
    Route::post('reset_password', 'API\UserAPIController@ResetPassword');
    Route::get('user', 'API\Manager\UserAPIController@user');
    Route::get('settings', 'API\Manager\UserAPIController@settings');
});


Route::post('login', 'API\UserAPIController@login');
Route::get('register', 'API\UserAPIController@sendRegisterCodePhone');
Route::post('confirm_register', 'API\UserAPIController@confirmRegisterCodePhone');
Route::post('register', 'API\UserAPIController@register');
Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
Route::get('reset_password', 'API\UserAPIController@sendResetCodePhone');
Route::post('confirm_reset_code', 'API\UserAPIController@confirmResetCodePhone');
Route::post('reset_password', 'API\UserAPIController@ResetPassword');
Route::get('user', 'API\UserAPIController@user');
Route::get('settings', 'API\UserAPIController@settings');
Route::get('distance', 'API\DistanceAPIController@getDistanceBetweenTwoPoints');


Route::resource('cuisines', 'API\CuisineAPIController');
Route::resource('categories', 'API\CategoryAPIController');
Route::resource('restaurants', 'API\RestaurantAPIController');
Route::apiResource('supermarkets', 'API\SupermarketAPIController')->only('index');

Route::resource('faq_categories', 'API\FaqCategoryAPIController');
Route::get('foods/categories', 'API\FoodAPIController@categories');
Route::resource('foods', 'API\FoodAPIController');
Route::resource('galleries', 'API\GalleryAPIController');
Route::resource('food_reviews', 'API\FoodReviewAPIController');
Route::resource('nutrition', 'API\NutritionAPIController');
Route::resource('extras', 'API\ExtraAPIController');
Route::resource('extra_groups', 'API\ExtraGroupAPIController');
Route::resource('faqs', 'API\FaqAPIController');
Route::resource('restaurant_reviews', 'API\RestaurantReviewAPIController');
Route::resource('currencies', 'API\CurrencyAPIController');
Route::resource('slides', 'API\SlideAPIController')->except([
    'show'
]);

Route::middleware("auth:$auth")->group(function () {
    Route::post('uploads/store', 'UploadController@store')->name('medias.create');
    Route::group(['middleware' => ['role:driver']], function () {
        Route::prefix('driver')->group(function () {
            Route::post('orders/delivery/{id}', 'API\OrderAPIController@delivery');
            Route::post('orders/cancel/{id}', 'API\OrderAPIController@cancel');
            Route::get('orders/open', 'API\OrderAPIController@open');
            Route::resource('orders', 'API\OrderAPIController');
            Route::resource('notifications', 'API\NotificationAPIController');
            Route::get('profile', 'API\Driver\UserAPIController@profile');
            Route::post('update-status', 'API\Driver\UserAPIController@updateStatus');
            Route::post('update-profile-image', 'API\UserAPIController@updateProfileImage');
            Route::post('users/{id}', 'API\UserAPIController@update');
            Route::resource('faq_categories', 'API\FaqCategoryAPIController');
            Route::resource('faqs', 'API\FaqAPIController');
            Route::get('statistics', 'API\Driver\StatisticAPIController@index');
            Route::apiResource('settlements', 'API\Driver\SettlementDriverController')->only(['index', 'show']);
        });
    });
    Route::group(['middleware' => ['role:manager']], function () {
        Route::prefix('manager')->group(function () {
            Route::get('orders/booking', 'API\OrderAPIController@booking');
            Route::get('profile', 'API\Manager\UserAPIController@profile');
            Route::get('users/get', 'API\Manager\UserAPIController@getUsers');
            Route::post('users/add', 'API\Manager\UserAPIController@addUser');
            Route::post('users/update/{id}', 'API\Manager\UserAPIController@updateUser');
            Route::post('users/{id}', 'API\UserAPIController@update');
            Route::get('unregistered-customer/{phone}', 'API\UnregisteredCustomerAPIController@show');
            Route::get('users/drivers_of_restaurant/{id}', 'API\Manager\UserAPIController@driversOfRestaurant');
            Route::get('dashboard/{id}', 'API\DashboardAPIController@manager');
            // Route::resource('restaurants', 'API\Manager\RestaurantAPIController');
            Route::resource('faq_categories', 'API\FaqCategoryAPIController');
            Route::resource('faqs', 'API\FaqAPIController');
            Route::get('statistics', 'API\Manager\StatisticAPIController@index');
            Route::apiResource('foods', 'API\Manager\FoodAPIController')->except(['destroy']);
            Route::get('days', 'API\DayAPIController@index');
            
                            /* THIS ROUTE FOR ADD DAY TO RESTAURANT  */
            Route::get('profile/{id}/days', 'API\Manager\RestaurantAPIController@days');
            Route::post('profile/{id}/days/store', 'API\Manager\RestaurantAPIController@daysStore');
            Route::post('profile/{id}/days/{dayId}/update', 'API\Manager\RestaurantAPIController@daysUpdate');
            Route::delete('profile/{id}/days/{dayId}/delete', 'API\Manager\RestaurantAPIController@daysDestroy');

            

        });
    });
    Route::post('users/change_password/{id?}', 'API\UserAPIController@updatePassword');
    Route::post('users/{id}', 'API\UserAPIController@update');

    Route::resource('order_statuses', 'API\OrderStatusAPIController');

    Route::get('payments/byMonth', 'API\PaymentAPIController@byMonth')->name('payments.byMonth');
    Route::resource('payments', 'API\PaymentAPIController');

    Route::get('favorites/exist', 'API\FavoriteAPIController@exist');
    Route::resource('favorites', 'API\FavoriteAPIController');

    Route::resource('orders', 'API\OrderAPIController');

    Route::resource('food_orders', 'API\FoodOrderAPIController');

    Route::resource('notifications', 'API\NotificationAPIController');

    Route::get('carts/count', 'API\CartAPIController@count')->name('carts.count');


    Route::resource('carts', 'API\CartAPIController');

    Route::resource('delivery_addresses', 'API\DeliveryAddressAPIController');

    Route::resource('drivers', 'API\DriverAPIController');

    Route::resource('driver_reviews', 'API\DriverReviewAPIController');

    Route::resource('earnings', 'API\EarningAPIController');

    Route::resource('driversPayouts', 'API\DriversPayoutAPIController');

    Route::resource('restaurantsPayouts', 'API\RestaurantsPayoutAPIController');

    Route::resource('coupons', 'API\CouponAPIController')->except([
        'show'
    ]);

    Route::post('logout', 'API\UserAPIController@logout');
// this api for save note for user 
    Route::post('user/note', 'API\NoteController@store');

});

};

Route::group(['prefix' => 'v2'],$apiRoutes);
Route::group([],$apiRoutes);
Route::post('carts/getDeletedCartItems', 'API\CartAPIController@getDeletedCartItems');

Route::post('carts/deleteItemInCart', 'API\CartAPIController@deleteItemInCart');


Route::get('/docs', function () {
    return view('swagger.index');
});