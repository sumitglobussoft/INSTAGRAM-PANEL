<?php

namespace InstagramAutobot\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth as AuthUser;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $module)
    {
//        dd(AuthUser::user());
//        dd(Session::all());

        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/');
            }
        }
//        die("Asd");
        $userRoleFlag = false;

//        if (Authuser::check()) {
//            die("checked");
//        } else {
//            die("checked else");
//        }
//        die;


    if(AuthUser::check()){
        if($module == 'admin'){
            if(Session::has('instagram_admin')){
                $userRoleFlag=true;
            }
            if(!$userRoleFlag){
                return redirect('/admin/login');
            }
        }
        else if($module =='supplier'){
            if(Session::has('ig_supplier')){
                $userRoleFlag=true;
            }
            if(!$userRoleFlag){
                return redirect('/supplier/login');
            }
        }
        else if($module == 'User'){
            if(Session::has('ig_user')){
                $userRoleFlag=true;
            }
            if(!$userRoleFlag){
                return redirect('/user/login');
            }
        }
    }

    return $next($request);
    }
}
