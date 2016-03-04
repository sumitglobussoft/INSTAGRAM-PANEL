<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use Input;
use InstagramAutobot\Http\Modules\Admin\Models\Instagram_user;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class InstagramAccountsController extends Controller
{
    public function viewDetails(){
        $objModelIgUsers=Instagram_user::getInstance();
        $IgUsersDetails=$objModelIgUsers->getAllInstagramUsers();
//        dd($IgUsersDetails);
        foreach($IgUsersDetails as $ig){
            $last_check[]=$this->convertUT($ig->last_check);
            $last_delivery[]=$this->convertUT($ig->last_delivery);
        }
//        print_r($last_check);die;
//        print_r($last_delivery);die;
//         $IgUsersDetails['last_check1']=$last_check;
//         $IgUsersDetails['last_delivery1']=$last_delivery;
//        echo'<pre>';print_r($IgUsersDetails);die;
        return view('Admin::instagram_user.igusersdetails',['igUsersDetails'=>$IgUsersDetails,'last_check'=>$last_check,'last_delivery'=>$last_delivery]);
        //,'last_check'=>$last_check,'last_delivery'=>$last_delivery
    }

    public function convertUT($ptime)
    {
        $difftime = time() - $ptime;

        if ($difftime < 1) {
            return '0 seconds';
        }

        $a = array(365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        $a_plural = array('year' => 'years',
            'month' => 'months',
            'day' => 'days',
            'hour' => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );

        foreach ($a as $secs => $str) {
            $d = $difftime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
            }
        }
    }
}