<?php


Route::group(array('module'=>'Supplier','namespace' => 'InstagramAutobot\Http\Modules\Supplier\Controllers'), function() {
//Your routes belong to this module.

Route::get('supplier/dashboard',function(){
return view('Supplier::dashboard');
});
});