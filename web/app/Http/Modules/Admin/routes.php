<?php


Route::group(array('module' => 'Admin', 'namespace' => 'Admin\Controllers'), function () {
    //Your routes belong to this module.

//    Route::any('admin/dashboard', function () {
//        return view('Admin::dashboard');
//    });

//    Route::group(['middleware'=>'auth:admin'],function(){
//        Route::resource('admin/dashboard', 'AdminController@dashboard');
//    });
//    Route::get('admin/login','AdminController@getadminlogin');
//    Route::post('admin/login','AdminController@postadminlogin');
    Route::get('/',function(){
       return redirect('/admin/login');
    });
    Route::get('/admin/adminsample', 'PlansController@adminsample');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::resource('admin/dashboard', 'AdminController@dashboard');
        //  Route::resource('admin/forgotpassword','AdminController@forgotPassword');
        Route::resource('/admin/logout', 'AdminController@adminLogout');
        Route::resource('/admin/edit-profile', 'AdminController@editProfile');
        Route::resource('/admin/update-password', 'AdminController@updatePassword');
        Route::resource('/admin/users-list', 'UserController@pendingUsers');
        Route::resource('/admin/users-list-active', 'UserController@availableUsers');
//        Route::resource('/admin/addsupplierform', 'SupplierController@addSupplierForm');
        Route::resource('/admin/adduser', 'UserController@addUser');
        Route::get('/admin/edituser/{id}', 'UserController@editUser');
        Route::post('/admin/edituser/{id}', 'UserController@editUser');


        Route::resource('/admin/users-list-rejected', 'UserController@rejectedUsers');
        Route::resource('/admin/datatables-ajax', 'UserController@rejectedUsersAjaxHandler');

        Route::resource('/admin/users-ajax-handler', 'UserController@userAjaxHandler');
        Route::resource('/admin/users-ajax-handler/available', 'UserController@availableUserAjaxHandler');
//        Route::resource('/admin/datatables-ajax/available', 'UserController@availableUsersDatatables');

        Route::resource('/admin/plans-list', 'PlansController@availablePlans');
        Route::resource('/admin/plans-ajax-handler', 'PlansController@availablePlansAjaxHandler');

        Route::resource('/admin/orders-list', 'OrdersController@orderList');
        Route::resource('/admin/orders-list-ajax', 'OrdersController@showOrderListAjaxHandler');
//        Route::get('/admin/view-orders/{id}', 'OrdersController@viewOrderList');


       // ----------------

        Route::post('/admin/view-orders', 'OrdersController@viewOrderList');

        //--------------------
//        Route::post('/admin/view-orders/{id}', 'OrdersController@viewOrderList');

        Route::resource('/admin/add-comments', 'CommentController@addComment');
        Route::resource('/admin/show-comments', 'CommentController@showRandomComments');
        Route::resource('/admin/show-comments-datatables', 'CommentController@showCommentsAjaxHandler');
        Route::get('/admin/edit-comments/{id}', 'CommentController@editComments');
        Route::post('/admin/edit-comments/{id}', 'CommentController@editComments');
//        Route::get('/admin/delete-comments/{id}', 'CommentController@deleteComments');
//        Route::post('/admin/delete-comments/{id}', 'CommentController@deleteComments');
        Route::resource('/admin/comments-add-ajax-handler', 'CommentController@addCommentsAjaxHandler');
        Route::resource('/admin/show-selected-comments', 'CommentController@showSelectedComments');
        Route::resource('/admin/custom-search', 'CommentController@customSearch');
        Route::get('/admin/sample/{status}', 'CommentController@sample');
        Route::post('/admin/sample/{status}', 'CommentController@sample');



        Route::get('/admin/plans-list-edit/{id}', 'PlansController@editPlan');
        Route::post('/admin/plans-list-edit/{id}', 'PlansController@editPlan');

//Routes For Ticket Controllers
        Route::resource('/admin/ticketdetails','TicketsController@ticketDetails');
        Route::resource('/admin/ticketdetails-datatables','TicketsController@ticketDetailsAjaxHandler');
        Route::resource('/admin/closedtickets','TicketsController@closedTickets');
        Route::resource('/admin/closedtickets-datatables','TicketsController@closedTicketsAjaxHandler');
        Route::get('/admin/view-queries/{id}','TicketsController@reply');
        Route::post('/admin/view-queries/{id}','TicketsController@postreply');
//        Route::resource('/admin/postreply','TicketsController@postreply');




        Route::resource('/admin/curlusingpost','AdminController@curlUsingPost');


/*-------------------Routes for Paypal Integration-------------------------*/

//        Route::resource('/admin/api-for-paypal','AdminController@paypalIntegration');
        Route::resource('/payment/success','AdminController@paypalIntegration');
        Route::resource('/payment/cancelled','AdminController@paypalIntegration');


// Add this route for checkout or submit form to pass the item into paypal
        Route::get('/admin/payment', array(
            'as' => '/admin/payment',
            'uses' => 'PaypalController@postPayment',
        ));

// this is after make the payment, PayPal redirect back to your site
        Route::get('/admin/payment/status', array(
            'as' => 'payment.status',
            'uses' => 'PaypalController@getPaymentStatus',
        ));

        Route::resource('/admin/shit','AdminController@shit');
        Route::resource('/admin/paypal','AdminController@paypal');
        Route::resource('/admin/cancelurl','AdminController@cancelurl');
        Route::resource('/admin/returnurl','AdminController@returnurl');








//        Route::controller('/admin/suppliers-list-rejected', 'SupplierController', [
//            'anyData'  => 'datatables.data',
//            'getIndex' => '/admin/suppliers-list-rejected',
//        ]);


//    Route::resource('/admin/suppliers-lists', 'SupplierController');
    });

    Route::resource('/admin/login', 'AdminController@adminlogin');
    Route::resource('/admin/forgot', 'AdminController@resetPassword');


    Route::get('/resetpassword/{token}', 'AdminController@checkToken');
    Route::post('/resetpassword/{token}', 'AdminController@checkToken');




    //--------------------instagram_users-------------------

    Route::resource('/admin/ig_users-details','InstagramAccountsController@viewDetails');

    //---------------------For Scrapping Data--------------

    Route::resource('/admin/scrap','PaypalController@scrapping');


});


