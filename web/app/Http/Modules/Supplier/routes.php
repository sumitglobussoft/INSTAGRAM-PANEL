<?php


Route::group(array('module' => 'Supplier', 'namespace' => 'Supplier\Controllers'), function () {
//Your routes belong to this module.

    Route::resource('supplier/login', 'SupplierController@login');
    Route::resource('supplier/register', 'SupplierController@register');
    Route::resource('supplier/logout', 'SupplierController@logout');
    Route::resource('supplier/forgotPassword', 'SupplierController@forgotPassword');



    Route::group(['middleware' => 'auth:supplier'], function () {
        Route::resource('supplier/dashboard', 'SupplierController@dashboard');
        Route::resource('supplier/profileView', 'SupplierController@profileView');
        Route::resource('supplier/updateProfileInfo', 'SupplierController@updateProfileInfo');
        Route::resource('supplier/updatePassword', 'SupplierController@updatePassword');
        Route::resource('supplier/changeAvatar', 'SupplierController@changeAvatar');

        Route::resource('supplier/addOrder', 'OrderController@addOrder');
        Route::resource('supplier/viewOrder', 'OrderController@viewOrder');

    });

    Route::resource('supplier/resetPassword/{resetCode?}/{id?}', 'SupplierController@resetPassword');
});