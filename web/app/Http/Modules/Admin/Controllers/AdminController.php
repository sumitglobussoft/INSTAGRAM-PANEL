<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use Illuminate\Http\Request;

use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;

class AdminController extends Controller
{
    //
    public  function dashboard(){
        return view('Admin::dashboard');
    }
}
