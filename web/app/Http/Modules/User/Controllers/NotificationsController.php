<?php
namespace InstagramAutobot\Http\Modules\User\Controllers;

use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Modules\User\Models\Notification;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class NotificationsController extends Controller
{

    public function notificationLog()
    {
        $objNotification = Notification::getInstance();
        $userId = Session::get('ig_user')['id'];
        $where = array(
            'rawQuery' => 'user_id=?',
            'bindParams' => [$userId]
        );
        $notifications = $objNotification->getAllNotificationsWhere($where);
        Session::forget('ig_user.notification');
        Session::forget('ig_user.count');
//        print_r(Session::all());die;
        return view('User::notification.shownotifications', ['notifications' => $notifications]);

        $count = 0;
        foreach ($notifications as $ntf) {
            $data = $ntf->notifications_txt;
            Session::put('ig_user.notification', $data);
            $count++;
            print_r(Session::get('ig_user')['notification']);
        }
        Session::put('ig_user.count', $count);
//        echo "<pre>";print_r(Session::all());die;

//        die;
//        print_r($notifications);
//        die;
    }

}