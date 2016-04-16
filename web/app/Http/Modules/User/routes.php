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

        Route::post('/user/getBalance','UserController@getBalance');
        Route::resource('/user/accountOverview', 'UserController@accountOverview');

//        Route::resource('user/profileView', 'UserController@profileView');
        Route::resource('/user/updateProfileInfo', 'UserController@updateProfileInfo');
        Route::resource('/user/emailNotifications', 'UserController@emailNotifications');

        Route::resource('/user/changePassword', 'UserController@changePassword');
        Route::get('user/changeAvatar', 'UserController@changeAvatar');

        Route::resource('/user/addOrder', 'OrderController@addOrder');
        Route::resource('/user/getFilterPlanList', 'OrderController@getFilterPlanList');

        Route::resource('/user/orderHistory', 'OrderController@orderHistory');
        Route::post('/user/cancelOrder', 'OrderController@cancelOrder');
        Route::post('/user/reAddOrder', 'OrderController@reAddOrder');
        Route::post('/user/editOrder', 'OrderController@editOrder');
        Route::post('/user/getMoreOrderDetails', 'OrderController@getMoreOrderDetails');


        Route::resource('user/orderHistoryAjax', 'OrderController@orderHistoryAjax');
        Route::post('user/getPreviousOrderDetails', 'OrderController@getPreviousOrderDetails');

        Route::get('user/pricingInformation', 'OrderController@pricingInformation');




        //Routes for ticket Controller-- Done by Saurabh
        Route::resource('user/create-ticket', 'TicketsController@createTicket');
        Route::resource('/user/show-tickets', 'TicketsController@showTickets');
        Route::resource('/user/show-tickets-status', 'TicketsController@changeTicketStatusAjaxHandler');
//        Route::resource('/user/show-tickets-datatables', 'TicketsController@showTicketsAjaxHandler');
        Route::get('/user/conversations/{id}', 'TicketsController@replyOnTicketsGet');
        Route::post('/user/conversations/{id}', 'TicketsController@replyOnTicketsPost');


        //done by saurabh-----------------
//        Route::resource('/user/add-balance', 'UserController@addBalance');
        //--------------------------------
        //routes for paypal-- Done by Saurabh
        Route::resource('/user/payment', 'UserController@payment');
        Route::resource('/user/depositHistory', 'UserController@transactionHistory');
        Route::resource('/user/depositHistory-ajaxDatatables','UserController@showTransactionHistory');

        Route::resource('/user/cheapbulk', 'UserController@cheapbulk');


        Route::get('/expressCallback/{amount}', 'UserController@expressCallback');
        Route::post('/expressCallback/{amount}', 'UserController@expressCallback');
        Route::resource('/paymentError', 'UserController@paymentError');
//        Route::post('/paymentError/196/{token}', 'UserController@paymentError');

        //Routes for Notifications-- Done by Saurabh
        Route::resource('/user/notification','NotificationsController@notificationLog');

        //Routes for FAQ-- Done by Saurabh
        Route::resource('/user/faq','UserController@faq');

        //Route for automatic orders
        Route::resource('user/addAutoOrder', 'OrderController@addAutoOrder');

        //Route for autolikes order
        Route::resource('user/addAutolikesOrder', 'OrderController@addAutolikesOrder');
        Route::resource('user/getAutolikesOrderHistory', 'OrderController@getAutolikesOrderHistory');
        Route::post('user/autolikeOrderHistoryAjax', 'OrderController@autolikeOrderHistoryAjax');
        Route::post('user/getUserPreviousDetails', 'OrderController@getUserPreviousDetails');
        Route::post('/user/getMoreAutolikesOrderDetails', 'OrderController@getMoreAutolikesOrderDetails');
        Route::post('user/updateUserOrderDetails', 'OrderController@updateUserOrderDetails');

        Route::get('user/temp', function () {
//            return view("User::order.temp");
            return view("User::order.newDesign");
        });
        Route::post('user/tempajax', 'OrderController@temp');


    });

});