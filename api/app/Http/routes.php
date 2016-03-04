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

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::resource('/login','User\AuthenticationController@login');
Route::resource('/signUp','User\AuthenticationController@signUp');
Route::resource('/forgotPassword','User\AuthenticationController@forgotPassword');
Route::resource('/user/showProfileDetails','User\ProfileController@showProfileDetails');
Route::resource('/user/updateProfileInfo','User\ProfileController@updateProfileInfo');
Route::resource('/user/updatePassword','User\ProfileController@updatePassword');
Route::resource('/user/changeAvatar','User\ProfileController@changeAvatar');

Route::resource('/user/getAddOrderFormDetails','User\OrderController@getAddOrderFormDetails');
Route::resource('/user/getCommentsList','User\OrderController@getCommentsList');
Route::resource('/user/addOrder','User\OrderController@addOrder');
Route::resource('/user/getOrderHistory','User\OrderController@getOrderHistory');
Route::resource('/user/cancelOrder','User\OrderController@cancelOrder');
Route::resource('/user/reAddOrder','User\OrderController@reAddOrder');

//To test for cron function
Route::resource('/user/addOrderToServerCronJob','User\OrderController@addOrderToServerCronJob');
Route::resource('/user/updateOrderStatusCronJob','User\OrderController@updateOrderStatusCronJob');


//  Routes for ticket generation done by saurabh
Route::resource('/user/create-tickets','User\TicketsController@createTicket');

//Routes for Payment Controller done by saurabh
Route::resource('/user/paymenta','User\PaymentController@payment');
Route::resource('/user/add-balance','User\PaymentController@addBalance');
Route::resource('/user/expressCallback', 'User\PaymentController@expressCallback');

//Routes for CHEAPBULK API done by SAURABH
Route::resource('/user/order-status-cheapbulk','API\CheapBulk@order_status');
Route::resource('/user/add-order-cheapbulk','API\CheapBulk@order_add');

//To test for cron function
Route::resource('/user/scheduleOrdersCronJob','User\OrderController@scheduleOrdersCronJob');
Route::resource('/user/addProcessOrdersToServerCronJob','User\OrderController@addProcessOrdersToServerCronJob');