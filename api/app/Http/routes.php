<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::resource('/login','User\AuthenticationController@login');
Route::resource('/signUp','User\AuthenticationController@signUp');
Route::resource('/forgotPassword','User\AuthenticationController@forgotPassword');
Route::resource('/user/showProfileDetails','User\ProfileController@showProfileDetails');
Route::resource('/user/updateProfileInfo','User\ProfileController@updateProfileInfo');
Route::resource('/user/updatePassword','User\ProfileController@updatePassword');
Route::resource('/user/changeAvatar','User\ProfileController@changeAvatar');
Route::resource('/user/getBalance','User\ProfileController@getBalance');

Route::resource('/user/emailNotifications', 'User\NotificationController@emailNotifications');

Route::resource('/user/getAddOrderFormDetails','User\OrderController@getAddOrderFormDetails');
Route::resource('/user/getCommentsGroupList','User\OrderController@getCommentsGroupList');
Route::resource('/user/getPlanList','User\OrderController@getPlanList');
Route::resource('/user/getFilterPlanList','User\OrderController@getFilterPlanList');

Route::resource('/user/addOrder','User\OrderController@addOrder');
Route::resource('/user/getOrderHistory','User\OrderController@getOrderHistory');
Route::resource('/user/orderHistoryAjax','User\OrderController@orderHistoryAjax');
Route::resource('/user/getMoreOrderDetails','User\OrderController@getMoreOrderDetails');
Route::resource('/user/getMoreAutolikesOrderDetails','User\OrderController@getMoreAutolikesOrderDetails');

Route::resource('/user/pricingInformation','User\OrderController@pricingInformation');

Route::resource('/user/cancelOrder','User\OrderController@cancelOrder');
Route::resource('/user/reAddOrder','User\OrderController@reAddOrder');
Route::resource('/user/editOrder','User\OrderController@editOrder');

Route::resource('/user/addAutolikesOrder','User\OrderController@addAutolikesOrder');
Route::resource('/user/getAutolikesOrderHistory','User\OrderController@getAutolikesOrderHistory');

Route::resource('/user/autolikeOrderHistoryAjax', 'User\OrderController@autolikeOrderHistoryAjax');
Route::resource('/user/getUserPreviousDetails', 'User\OrderController@getUserPreviousDetails');
Route::resource('/user/updateUserOrderDetails', 'User\OrderController@updateUserOrderDetails');


//  Routes for ticket generation done by saurabh
Route::resource('/user/create-tickets','User\TicketsController@createTicket');

//Routes for Payment Controller done by saurabh
Route::resource('/user/payment','User\PaymentController@payment');
Route::resource('/user/add-balance','User\PaymentController@addBalance');
Route::resource('/user/expressCallback', 'User\PaymentController@expressCallback');

//Routes for CHEAPBULK API done by SAURABH
Route::resource('/user/order-status-cheapbulk','API\CheapBulk@order_status');
Route::resource('/user/add-order-cheapbulk','API\CheapBulk@order_add');


//To test for cron function
Route::resource('/user/scheduleOrdersCronJob','User\OrderController@scheduleOrdersCronJob');
Route::resource('/user/addProcessOrdersToServerCronJob','User\OrderController@addProcessOrdersToServerCronJob');
Route::resource('/user/updateProcesOrderStatusCronJob','User\OrderController@updateProcesOrderStatusCronJob');
Route::resource('/user/updateOrderStatusCronJob','User\OrderController@updateOrderStatusCronJob');
Route::resource('/user/updateAutolikesUserStatusCronJob','User\OrderController@updateAutolikesUserStatusCronJob');
Route::resource('/user/emailNotificationsCronJob', 'User\NotificationController@emailNotificationsCronJob');

//Check every instagram user after every 5 minute (autolikes script)
Route::resource('/user/autoLikesScript', 'User\OrderController@autoLikesScript');

//temp path
Route::resource('/user/tempajax','User\OrderController@tempajax');
Route::resource('/user/testCronFunction','User\OrderController@testCronFunction');