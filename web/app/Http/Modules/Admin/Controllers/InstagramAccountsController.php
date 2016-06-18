<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;
use Input;
use InstagramAutobot\Http\Modules\Admin\Models\Instagram_user;
use InstagramAutobot\Http\Modules\Admin\Models\Plan;
use InstagramAutobot\Http\Modules\User\Controllers\OrderController;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

//include public_path() . "/../../api/app/Http/Controllers/User/OrderController.php";

class InstagramAccountsController extends Controller
{
    public function autolikesProfile()
    {

        $planLists = Plan::getInstance()->getAllPlansWhere(['rawQuery' => 'for_usergroup_id=0 and status=1 and plan_type IN(0,4)'], ['plans.plan_id', 'plans.plan_name']);

//        dd($planLists);
        return view('Admin::instagram_user.igusersdetails', ['planLists' => $planLists]);//, ['igUsersDetails' => $IgUsersDetails]
//        if ($IgUsersDetails) {
//            foreach ($IgUsersDetails as $ig) {
//                $last_check[] = $this->convertUT($ig->last_check);
//                if ($ig->last_delivery != 0)
//                    $last_delivery[] = $this->convertUT($ig->last_delivery);
//                else
//                    $last_delivery[] = 0;
//            }
//            return view('Admin::instagram_user.igusersdetails', ['igUsersDetails' => $IgUsersDetails, 'last_check' => $last_check, 'last_delivery' => $last_delivery]);
//        } else {
//            return view('Admin::instagram_user.igusersdetails', ['igUsersDetails' => $IgUsersDetails]);
//        }
    }

    public function autolikesProfileAjaxDatatables(Request $request)
    {
        $objModelIgUsers = Instagram_user::getInstance();

        if ($request->input('method') == "withoutFilter") {
            $IgUsersDetails = $objModelIgUsers->getInstagramUsersDetailsWithUsersInfo();
//        dd($IgUsersDetails);
            $IgUsersDetails = json_decode(json_encode($IgUsersDetails), true);
            $igUsers = new Collection();
            foreach ($IgUsersDetails as $igu) {
                $currentTime = time();
                $startDate = $this->convertUT($igu['start_date_time']);
                $endDate = ($igu['end_date_time'] != 0) ? (($igu['end_date_time'] > $currentTime) ? $this->convertUT($igu['end_date_time']) : 'Expired ' . $this->convertUT($igu['end_date_time']) . ' before') : '-';
                $lastCheck = $this->convertUT($igu['last_check']);
                $lastDelivery = ($igu['last_delivery'] != 0) ? $this->convertUT($igu['last_delivery']) : '-';
                if ($igu['ig_user_status'] == 0)
                    $status = '<span class="label label-danger"><i class="fa fa-times-circle"></i>&nbsp; Failed</span>';
                else if ($igu['ig_user_status'] == 1)
                    $status = '<span class="label label-primary"><i class="fa fa-check-circle-o"></i>&nbsp; Finished</span>';
                else if ($igu['ig_user_status'] == 2)
                    $status = '<span class="label label-success"><i class="fa fa-refresh fa-spin"></i>&nbsp; Waiting</span>';
                else if ($igu['ig_user_status'] == 3)
                    $status = '<span class="label label-warning"><i class="fa fa-ban"></i>&nbsp; Stopped</span>';
                else
                    $status = '<span class="label label-info"><i class="fa fa-times-circle"></i>&nbsp; Expired</span>';
                $igUsers->push([
                    'check' => '<input type="checkbox" class="autolikesCheckBox" name="checkbox" value="' . $igu['ins_user_id'] . '">',
                    'id' => $igu['ins_user_id'],
                    'addedBy' => $igu['username'],
                    'instagramProfile' => '<p><a class="btn btn-xs default text-case link-width" href="https://instagram.com/' . $igu['ins_username'] . '/" target="_blank"><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $igu['ins_username'] . '</a></p>',
                    'serverType' => $igu['plan_name'],
                    'PostDoneAndTotalPost' => $igu['pics_done'] . ' / ' . $igu['pics_limit'],
                    'startDate' => $startDate,
                    'endDate' => $endDate,
//                'lastCheck' => $lastCheck,
//                'lastDelivery' => $lastDelivery,
                    'status' => $status,
//                    'details' => '<a class="list-group-item fa fa-book" style="font-size:21px;" data-toggle="modal" data-target="#details" data-id=' . $igu['ins_user_id'] . ' style="margin-left:1%;"></a>',
                    'details' => '<button class="btn btn-raised btn-default details" data-toggle="modal" data-target="#details" data-id=' . $igu['ins_user_id'] . '><i class="fa fa-eye"></i></button>',
//                    'details' => '<a class="list-group-item" href="#"><i class="fa fa-book fa-fw" aria-hidden="true"></i></a>'

                ]);
            }
            return Datatables::of($igUsers)->make(true);
        } else if ($request->input('method') == "withFilter") {
            $endDate = $request->input('endDate');
            $dailyPost = $request->input('dailyPost');
            $totalPost = $request->input('totalPost');
            $services = $request->input('services');
            if ($endDate != 0) {
                $currentTime = time();
                $currentTimeAfter24hrs = time() + 86400;   // after 24 hrs
                $currentTimeAfter48hrs = time() + 172800;  // after 48 hrs
                $currentTimeAfter5days = time() + 432000;  // after 5 days
                if ($endDate == 1)   //Expired
                    $filteringRules[] = "(instagram_users.end_date_time !=0 && instagram_users.end_date_time <= " . $currentTime . ")";
                elseif ($endDate == 2) //Expirirng today
                    $filteringRules[] = "( instagram_users.end_date_time >" . $currentTime . " && instagram_users.end_date_time <=  " . $currentTimeAfter24hrs . ")"; //172800=48hrs
                elseif ($endDate == 3) // Expiring in next 24-48 hrs
                    $filteringRules[] = "(instagram_users.end_date_time >" . $currentTimeAfter24hrs . " && instagram_users.end_date_time <= " . $currentTimeAfter48hrs . ")";//432000= 5days
                elseif ($endDate == 4) // Expiring the next 5 days
                    $filteringRules[] = "(instagram_users.end_date_time >" . $currentTimeAfter48hrs . " && instagram_users.end_date_time <= " . $currentTimeAfter5days . ")";//432000= 5days
            }
            if ($dailyPost != 0) {
                if ($dailyPost == 1) //Daily Post Limit is set i.e. some value is there in db
                    $filteringRules[] = "(instagram_users.daily_post_limit > 0)";
                elseif ($dailyPost == 2) // Daily post limit is not set i.e. daily_post_limit=0 (unlimiited daily post)
                    $filteringRules[] = "(instagram_users.daily_post_limit = 0)";
                elseif ($dailyPost == 3) //Reached i.e. dailyPostLimit has been reached for that users.
                    $filteringRules[] = "(instagram_users.daily_post_limit > 0 && instagram_users.daily_post_limit = instagram_users.daily_post_done)";
            }
            if ($totalPost != 0) {
                if ($totalPost == 1)
                    $filteringRules[] = "(instagram_users.pics_done = instagram_users.pics_limit)";
                elseif ($totalPost == 2)
                    $filteringRules[] = "(instagram_users.pics_done != instagram_users.pics_limit)";
                elseif ($totalPost == 3)
                    $filteringRules[] = "(instagram_users.pics_done >= instagram_users.pics_limit-(instagram_users.pics_limit*0.1))";
            }
            if ($services != 0) {
                $filteringRules[] = "(instagram_users.plan_id = " . $services . ")";
            }
            if (($endDate && $dailyPost && $totalPost && $services) == 0) {
                $filteringRules[] = "(instagram_users.cronjob_status IN(0,1,2))";
            }
            $filteringRules = implode("and", $filteringRules);
//            dd($filteringRules);
            $whereForFilters = ['rawQuery' => $filteringRules];
            $filterDetails = $objModelIgUsers->getInstagramUsersDetailsWithPlans($whereForFilters);
            $filterDetails = json_decode(json_encode($filterDetails), true);
            $igUsers = new Collection();
            foreach ($filterDetails as $igu) {
                $currentTime = time();
                $startDate = $this->convertUT($igu['start_date_time']);
                $endDate = ($igu['end_date_time'] != 0) ? (($igu['end_date_time'] > $currentTime) ? $this->convertUT($igu['end_date_time']) : 'Expired ' . $this->convertUT($igu['end_date_time']) . ' before') : '-';
                $lastCheck = $this->convertUT($igu['last_check']);
                $lastDelivery = ($igu['last_delivery'] != 0) ? $this->convertUT($igu['last_delivery']) : '-';
                if ($igu['ig_user_status'] == 0)
                    $status = '<span class="label label-danger"><i class="fa fa-times-circle"></i>&nbsp; Failed</span>';
                else if ($igu['ig_user_status'] == 1)
                    $status = '<span class="label label-success"><i class="fa fa-check-circle-o"></i>&nbsp; Finished</span>';
                else if ($igu['ig_user_status'] == 2)
                    $status = '<span class="label label-success"><i class="fa fa-refresh fa-spin"></i>&nbsp; Waiting</span>';
                else if ($igu['ig_user_status'] == 3)
                    $status = '<span class="label label-warning"><i class="fa fa-ban"></i>&nbsp; Stopped</span>';
                else
                    $status = '<span class="label label-info"><i class="fa fa-times-circle"></i>&nbsp; Expired</span>';
                $igUsers->push([
                    'check' => '<input type="checkbox" class="autolikesCheckBox" name="checkbox" value="' . $igu['ins_user_id'] . '">',
                    'id' => $igu['ins_user_id'],
                    'addedBy' => $igu['username'],
                    'instagramProfile' => '<p><a class="btn btn-xs default text-case link-width" href="https://instagram.com/rafiahmadanjum/"' . $igu['ins_username'] . ' target="_blank"><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $igu['ins_username'] . '</a></p>',
                    'serverType' => $igu['plan_name'],
                    'PostDoneAndTotalPost' => $igu['pics_done'] . ' / ' . $igu['pics_limit'],
                    'startDate' => $startDate,
                    'endDate' => $endDate,
//                'lastCheck' => $lastCheck,
//                'lastDelivery' => $lastDelivery,
                    'status' => $status,
//                    'details' => '<a class="fa fa-eye" style="font-size:21px;" data-toggle="modal" data-target="#details" data-id=' . $igu['ins_user_id'] . ' style="margin-left:1%;"></a>',
                    'details' => '<a class="list-group-item fa fa-book" style="font-size:21px;" data-toggle="modal" data-target="#details" data-id=' . $igu['ins_user_id'] . ' style="margin-left:1%;"></a>',
                ]);
            }
            return Datatables::of($igUsers)->make(true);

        }


    }

    public function autolikesProfileDetailsAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $where = array('rawQuery' => 'ins_user_id=?', 'bindParams' => [$request->input('id')]);
            $instaUsersDetails = Instagram_user::getInstance()->getInstagramUsersDetailsWithOrdersAndUsersInfo($where);
//            dd($instaUsersDetails);
            if ($instaUsersDetails) {
                $lastCheck = ($instaUsersDetails[0]->last_check != 0) ? $this->convertUT($instaUsersDetails[0]->last_check) : '-';
                $lastDelivery = ($instaUsersDetails[0]->last_delivery != 0) ? $this->convertUT($instaUsersDetails[0]->last_delivery) : '-';
//
                echo json_encode(['status' => '200', 'message' => 'got the details', 'data' => $instaUsersDetails, 'lastCheck' => $lastCheck, 'lastDelivery' => $lastDelivery]);
            } else {
                echo json_encode(['status' => '400', 'message' => 'errorrorror']);
            }
        }
    }

    public function viewAllOrders(Request $forUserId)
    {
        $where = array('rawQuery' => 'for_user_id=?', 'bindParams' => [$forUserId->input('forUserId')]);
        $orderDetails = Instagram_user::getInstance()->getAllOrdersDetails($where);
//        dd($instaUsersDetails);
        if ($orderDetails) {
            echo json_encode(['status' => '200', 'message' => 'got all orders details', 'data' => $orderDetails]);
        } else {
            echo json_encode(['status' => '400', 'message' => 'errorrorror']);
        }
    }

    public function autolikesSelectAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $insUserId = $request->input('id');
//            dd($insUserId);
            $action = $request->input('action');
            $objModelInstagramUsers = Instagram_user::getInstance();
            $msg = [];
            $whereIn = implode(',', array_unique($insUserId));

            switch ($action) {
                case 1: //Restart Daily Counter
                    $updated = $objModelInstagramUsers->updateInstagramUserWhere(['daily_post_done' => 0, 'firstpost_delivery_daytime' => 0], ['rawQuery' => 'ins_user_id IN(' . $whereIn . ')']);
                    if ($updated) {
                        echo json_encode(['status' => 200, 'message' => 'Done! We have reset the daily post done count!!']);
                    } else {
                        echo json_encode(['status' => 400, 'message' => 'Some problem occurred. Daily post done may have already reset OR there will not be any post done for today']);
                    }
                    break;
                case 2: // Restart Total Counter
                    $updated = $objModelInstagramUsers->updateInstagramUserWhere(['pics_fetch_count' => 0, 'pics_done' => 0, 'daily_post_done' => 0, 'reset_counter_time' => time()], ['rawQuery' => 'ins_user_id IN(' . $whereIn . ')']);
                    if ($updated) {
                        echo json_encode(['status' => 200, 'message' => 'Done! We have reset the total counter. The profile has restarted again with 0 pics done!!']);
                    } else {
                        echo json_encode(['status' => 400, 'message' => 'Some problem occurred. Please reload the page and try again later.']);
                    }
                    break;
                case 3: //change Server
                    $planId = $request->input('planId');
                    $updated = $objModelInstagramUsers->updateInstagramUserWhere(['plan_id' => $planId], ['rawQuery' => 'ins_user_id IN(' . $whereIn . ')']);
                    if ($updated) {
                        echo json_encode(['status' => 200, 'message' => 'Done! Services has been changed successfully']);
                    } else {
                        echo json_encode(['status' => 400, 'message' => 'Some problem occurred. This may be due to the same se']);
                    }
                    break;
                case 5: //Remove from the system
                    $deleted = $objModelInstagramUsers->deleteInstagramUserWhere(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')']);
                    if ($deleted)
                        echo json_encode(['status' => 200, 'message' => 'Instagram Profile has been successfully deleted from our system!']);
                    else
                        echo json_encode(['status' => 400, 'message' => 'Some problem occurred. Please reload the page and try again.']);

                default:
                    break;


            }
        }
    }

    public function convertUT($ptime)
    {
        $difftime = time() - $ptime;
        $afterFlag = '';

        if ($difftime < 1) { // this condition will satisfy only for endDate whose expirationDate is far(i.e. greater than current time)
            $afterFlag = "after ";
            $difftime = abs($difftime);
//            return '0 seconds';
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
                return $afterFlag . $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
            }
        }
    }


}