<?php


Route::group(array('module' => 'User', 'namespace' => 'User\Controllers'), function () {
//Your routes belong to this module.

    Route::get('/', function () {
        return view('User::user.login');
    });

    Route::resource('user/login', 'UserController@login');
    Route::resource('user/register', 'UserController@register');
    Route::resource('user/logout', 'UserController@logout');
    Route::resource('user/forgotPassword', 'UserController@forgotPassword');
    Route::get('user/verifyResetCode/{resetCode}', 'UserController@verifyResetCode');
    Route::post('user/verifyResetCode/{resetCode}', 'UserController@verifyResetCode');



    Route::group(['middleware' => 'auth:user'], function () {
        Route::resource('user/dashboard', 'UserController@dashboard');
        Route::resource('user/accountOverview', 'UserController@accountOverview');

//        Route::resource('user/profileView', 'UserController@profileView');
        Route::resource('user/updateProfileInfo', 'UserController@updateProfileInfo');
        Route::resource('user/changePassword', 'UserController@changePassword');
        Route::get('user/changeAvatar', 'UserController@changeAvatar');

        Route::resource('user/addOrder', 'OrderController@addOrder');
        Route::resource('user/orderHistory', 'OrderController@orderHistory');
        Route::post('user/cancelOrder', 'OrderController@cancelOrder');
        Route::post('user/reAddOrder', 'OrderController@reAddOrder');

    });

});