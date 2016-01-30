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

Route::resource('/login','Supplier\AuthenticationController@login');
Route::resource('/signUp','Supplier\AuthenticationController@signUp');
Route::resource('/forgotPassword','Supplier\AuthenticationController@forgotPassword');
Route::resource('/supplier/showProfileDetails','Supplier\ProfileController@showProfileDetails');
Route::resource('/supplier/updateProfileInfo','Supplier\ProfileController@updateProfileInfo');
Route::resource('/supplier/updatePassword','Supplier\ProfileController@updatePassword');
Route::resource('/supplier/changeAvatar','Supplier\ProfileController@changeAvatar');

Route::resource('/supplier/getAddOrderFormDetails','Supplier\OrderController@getAddOrderFormDetails');
Route::resource('/supplier/addOrder','Supplier\OrderController@addOrder');
Route::resource('/supplier/getCommentsList','Supplier\OrderController@getCommentsList');
