<?php


Route::group(array('module'=>'User', 'namespace'=>'Modules\Views\Controllers'),function(){
    Route::get('user/dashboard',function(){
        return view('User::dashboard');
    });
});