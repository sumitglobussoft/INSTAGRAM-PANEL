<?php

namespace InstagramAutobot\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    protected $auth;

    public function __construct(Guard $auth){
        $this->auth=$auth;
    }

    public function handle($request, Closure $next, $module)
    {

//        if (Auth::guard($guard)->guest()) {

       if($this->auth->guest()){
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        $userRoleFlag=false;

        if(Auth::check()){

        }


        return $next($request);
    }
}
