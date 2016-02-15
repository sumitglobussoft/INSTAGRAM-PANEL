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
