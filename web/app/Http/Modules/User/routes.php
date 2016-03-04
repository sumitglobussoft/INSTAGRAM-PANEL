<?php


Route::group(array('module' => 'User', 'namespace' => 'User\Controllers'), function () {
//Your routes belong to this module.

    Route::get('/', function () {
        return redirect('/user/login');
    });

    Route::resource('/user/login', 'UserController@login');
    Route::resource('/user/register', 'UserController@register');
    Route::resource('/user/logout', 'UserController@logout');
    Route::resource('/user/forgotPassword', 'UserController@forgotPassword');
    Route::get('/user/verifyResetCode/{resetCode}', 'UserController@verifyResetCode');
    Route::post('/user/verifyResetCode/{resetCode}', 'UserController@verifyResetCode');



    Route::group(['middleware' => 'auth:user'], function () {
        Route::resource('/user/dashboard', 'UserController@dashboard');
        Route::resource('/user/accountOverview', 'UserController@accountOverview');

//        Route::resource('user/profileView', 'UserController@profileView');
        Route::resource('/user/updateProfileInfo', 'UserController@updateProfileInfo');
        //done by saurabh-----------------
//        Route::resource('/user/add-balance', 'UserController@addBalance');
        //--------------------------------
        Route::resource('/user/changePassword', 'UserController@changePassword');
        Route::get('user/changeAvatar', 'UserController@changeAvatar');

        Route::resource('/user/addOrder', 'OrderController@addOrder');
        Route::resource('/user/orderHistory', 'OrderController@orderHistory');
        Route::post('/user/cancelOrder', 'OrderController@cancelOrder');
        Route::post('/user/reAddOrder', 'OrderController@reAddOrder');

        //Routes for ticket Controller-- Done by Saurabh
        Route::resource('user/create-ticket', 'TicketsController@createTicket');
        Route::resource('/user/show-tickets', 'TicketsController@showTickets');
        Route::resource('/user/show-tickets-status', 'TicketsController@changeTicketStatusAjaxHandler');
//        Route::resource('/user/show-tickets-datatables', 'TicketsController@showTicketsAjaxHandler');
        Route::get('/user/conversations/{id}', 'TicketsController@replyOnTicketsGet');
        Route::post('/user/conversations/{id}', 'TicketsController@replyOnTicketsPost');


        //routes for paypal-- Done by Saurabh
        Route::resource('/user/payment', 'UserController@payment');


        Route::resource('/user/cheapbulk', 'UserController@cheapbulk');


        Route::get('/expressCallback/{amount}', 'UserController@expressCallback');
        Route::post('/expressCallback/{amount}', 'UserController@expressCallback');
        Route::resource('/paymentError', 'UserController@paymentError');
//        Route::post('/paymentError/196/{token}', 'UserController@paymentError');

        //Routes for Notifications-- Done by Saurabh
        Route::resource('/user/notification','NotificationsController@notificationLog');

        //Routes for FAQ-- Done by Saurabh
        Route::resource('/user/faq','UserController@faq');
    });

});