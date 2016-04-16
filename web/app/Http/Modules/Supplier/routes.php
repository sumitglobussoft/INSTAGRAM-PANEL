<?php


Route::group(array('module' => 'Supplier', 'namespace' => 'Supplier\Controllers'), function () {
//Your routes belong to this module.

    Route::get('/', function () {
        return view('Supplier::supplier.login');
    });

    Route::resource('supplier/login', 'SupplierController@login');
    Route::resource('supplier/register', 'SupplierController@register');
    Route::resource('supplier/logout', 'SupplierController@logout');
    Route::resource('supplier/forgotPassword', 'SupplierController@forgotPassword');
    Route::get('supplier/verifyResetCode/{resetCode}', 'SupplierController@verifyResetCode');
    Route::post('supplier/verifyResetCode/{resetCode}', 'SupplierController@verifyResetCode');


    Route::group(['middleware' => 'auth:supplier'], function () {
        Route::resource('supplier/dashboard', 'SupplierController@dashboard');
        Route::resource('supplier/myAccount', 'SupplierController@myAccount');

        Route::resource('supplier/profileView', 'SupplierController@profileView');
        Route::resource('supplier/updateProfileInfo', 'SupplierController@updateProfileInfo');
        Route::resource('supplier/updatePassword', 'SupplierController@updatePassword');
        Route::resource('supplier/changeAvatar', 'SupplierController@changeAvatar');

        Route::resource('supplier/addOrder', 'OrdersController@addOrder');
        Route::resource('supplier/orderHistory', 'OrdersController@orderHistory');



//        //Routes for ticket Controller-- Done by Saurabh
//        Route::resource('user/create-ticket', 'TicketsController@createTicket');
//        Route::resource('/user/show-tickets', 'TicketsController@showTickets');
//        Route::resource('/user/show-tickets-datatables', 'TicketsController@showTicketsAjaxHandler');



    });

});