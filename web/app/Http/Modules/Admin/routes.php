<?php


Route::group(array('module' => 'Admin', 'namespace' => 'InstagramAutobot\Http\Modules\Admin\Controllers'), function () {
    //Your routes belong to this module.

//    Route::any('admin/dashboard', function () {
//        return view('Admin::dashboard');
//    });


    Route::group(['middleware' => 'auth:admin'], function () {
        Route::resource('admin/dashboard', 'AdminController@dashboard');
    });


});