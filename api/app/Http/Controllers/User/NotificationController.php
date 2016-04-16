<?php

namespace App\Http\Controllers\User;


use App\Http\Models\Instagram_User;
use App\Http\Models\User;
use App\Http\Models\Usersmeta;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use stdClass;
use Mandrill;


include public_path() . "/../vendor/mandrill/src/Mandrill.php";

class NotificationController extends Controller
{
    protected $API_TOKEN;
    protected $MANDRILL_KEY;

    public function  __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
        $this->MANDRILL_KEY = env('MANDRILL_KEY');
    }


    public function emailNotifications(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objUsersmetaModel = new Usersmeta();

            $userId = (isset($request['user_id'])) ? $request['user_id'] : '';

            $authFlag = false;
            if (isset($request['api_token'])) {
                $apiToken = $request['api_token'];

                if ($apiToken == $this->API_TOKEN) {
                    $authFlag = true;
                } else {
                    if ($userId != '') {
                        $where = [
                            'rawQuery' => 'id=?',
                            'bindParams' => [$userId]
                        ];
                        $selectColumn = array('login_token');
                        $userCredentials = $objUserModel->getUsercredsWhere($where, $selectColumn);
                        if ($apiToken == $userCredentials->login_token) {
                            $authFlag = true;
                        }
                    }
                }
            }
            if ($authFlag) {
                $rules = [
                    'notifyBalance' => 'required',
                    'notifyProfileLikes' => 'required',
                    'notifyDailySubscription' => "required",
                    'user_id' => 'required|exists:users,id'
                ];

                $validatePlanId = Validator::make($postData, $rules);
                if (!$validatePlanId->fails()) {
                    $data['notify_bal'] = intval($postData['notifyBalance']);
                    $data['notify_profile_likes'] = intval($postData['notifyProfileLikes']);
                    $data['notify_daily_subscription'] = intval($postData['notifyDailySubscription']);

                    $isUserExistInUsersmeta = $objUsersmetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [intval($postData['user_id'])]]);

                    $queryResult = '';
                    if ($isUserExistInUsersmeta) {
                        $queryResult = $objUsersmetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [intval($postData['user_id'])]], $data);
                    } else {
                        $data['user_id'] = intval($postData['user_id']);
                        $data['account_bal'] = 0.0000;
                        $queryResult = $objUsersmetaModel->addUsermeta($data);
                    }
                    if ($queryResult != 2) {
                        $response->code = 200;
                        $response->message = "Email Notification successfully updated";
                        $response->data = $data;
                        echo json_encode($response);
                    } else {
                        $response->code = 204;
                        $response->message = "Something went wrong! please try again after sometime.";
                        $response->data = null;
                        echo json_encode($response);
                    }
                } else {
                    $response->code = 204;
                    $response->message = $validatePlanId->messages();
                    $response->data = null;
                    echo json_encode($response);
                }

            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
                echo json_encode($response, true);
            }
        } else {
            $response->code = 400;
            $response->message = "Request not allowed";
            $response->data = null;
            echo json_encode($response, true);
        }
    }

    public function emailNotificationsCronJob()
    {
        $objUsersmetaModel = new Usersmeta();
        $where = [
            'rawQuery' => 'cronjob_status=?',
            'bindParams' => [0]
        ];
        $data = ['usersmeta.user_id', 'usersmeta.account_bal', 'usersmeta.notify_bal', 'usersmeta.notify_profile_likes', 'usersmeta.notify_daily_subscription', 'usersmeta.cronjob_status', 'users.email'];
        $userDetails = $objUsersmetaModel->getUsermetaDetails($where, $data);

        if (!empty($userDetails)) {
            $this->sendScheduleEmails($userDetails);
        }
    }

    public function  sendScheduleEmails($userDetails)
    {

        $objUsersmetaModel = new Usersmeta();
        $objInstagramUserModel = new Instagram_User();
        foreach ($userDetails as $user) {
            $where = [
                'rawQuery' => 'user_id=?',
                'bindParams' => [$user->user_id]
            ];
            $queryResult = $objUsersmetaModel->updateUsermetaWhere($where, ['cronjob_status' => 0]); // replace with 1
        }

        foreach ($userDetails as $user) {
            // code for notify balance
            if ($user->notify_bal != 0) {
                if ($user->account_bal <= $user->notify_bal) {
                    $html = '<div>
                                <h3>Alert Message</h3><br>
                                <span>Your account balance is less than $' . $user->notify_bal . ' Please add balance in your account to continue autolikes service. </span><br>
                             </div>';
                    $subject = 'Less Balance Notification';
                    $toEmail = $user->email;
                    $result = $this->sendEmailNotification($html, $subject, $toEmail);
                }
            }

            $where = [
                'rawQuery' => 'instagram_users.by_user_id=?',
                'bindParams' => [$user->user_id]
            ];
            $data = ['instagram_users.ins_user_id', 'instagram_users.by_user_id', 'instagram_users.ins_username', 'instagram_users.pics_done', 'instagram_users.pics_limit', 'instagram_users.start_date_time', 'instagram_users.end_date_time'];
            $instagramUserDetails = $objInstagramUserModel->getUserDetails($where, $data);

            if ($instagramUserDetails) {

                // code for notify autolikes profile
                if ($user->notify_profile_likes != 0) {

                    $notifyUserCount = 0;
                    $tableBody = '<tbody>';
                    foreach ($instagramUserDetails as $insUser) {
                        $postLeft = $insUser->pics_limit - $insUser->pics_done;

                        if (($postLeft != 0) && ($postLeft <= $user->notify_profile_likes)) {
                            $tableBody = $tableBody . '<tr><td>' . $insUser->ins_username . '</td><td>' . $postLeft . '</td></tr>';
                            $notifyUserCount += 1;
                        }
                    }//End of autolikes profile - Inner for loop
                    $tableBody = $tableBody . '<tbody>';

                    if ($notifyUserCount != 0) {
                        $html = '<div><h3>Alert Message</h3><br><span>The following autolikes users profile has less than ' . $user->notify_profile_likes . ' Post left. </span><br>
                                  <table>
                                     <thead>
                                        <tr> <th>Instagram Username</th> <th>Autolikes Profile Post left</th>  </tr>
                                     </thead>
                                    ' . $tableBody . '
                                  </table>
                              </div>';
                        $subject = 'Instagram Users Profile Notification';
                        $toEmail = $user->email;
                        $result = $this->sendEmailNotification($html, $subject, $toEmail);
                    }
                }

                // code for notify autolikes profile subscription
                if ($user->notify_daily_subscription != 0) {

                    $notifyUserCount = 0;
                    $dateTimeDiff = 0;
                    $tableBody = '<tbody>';
                    foreach ($instagramUserDetails as $insUser) {

                        if (intval($insUser->end_date_time) != 0) {
                            $endDateTime = intval($insUser->end_date_time);

                            $currentDateTime = time();
                            if ($currentDateTime <= $endDateTime) {
                                $dateTimeDiff = $endDateTime - $currentDateTime;
                                $notifyTime = intval($user->notify_daily_subscription);

                                if ($notifyTime == 3) {
                                    if ($dateTimeDiff <= (3600 * 24)) {// 24 hr=3600*24
                                        $tableBody = $tableBody . '<tr><td>' . $insUser->ins_username . '</td><td>' . $this->getDateDifference($endDateTime) . ' </td></tr>';
                                        $where = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUser->ins_user_id]];
                                        $customMessageData['message'] = 'This instagram users Auto Likes profile subscription will expire in th next 24 hrs) or (last day of Auto Likes profile subscription. It will expire today';
                                        $queryResult = $objInstagramUserModel->updateUserDetails($where, $customMessageData);
                                        $notifyUserCount += 1;
                                    }
                                } else if ($notifyTime == 1) {
                                    if (($dateTimeDiff >= (3600 * 24 * 1)) && ($dateTimeDiff <= (3600 * 24 * 2))) {// 48 hr=3600*24*2
                                        $tableBody = $tableBody . '<tr><td>' . $insUser->ins_username . '</td><td>' . $this->getDateDifference($endDateTime) . ' </td></tr>';
                                        $where = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUser->ins_user_id]];
                                        $customMessageData['message'] = 'This instagram users Auto Likes profile subscription will expire after 1 day) or ( only 2 days remaining for Auto Likes profile subscription.';
                                        $queryResult = $objInstagramUserModel->updateUserDetails($where, $customMessageData);
                                        $notifyUserCount += 1;
                                    }
                                } else if ($notifyTime == 2) {
                                    if (($dateTimeDiff >= (3600 * 24 * 2)) && ($dateTimeDiff <= (3600 * 24 * 3))) {// 72 hr=3600*24*3
                                        $tableBody = $tableBody . '<tr><td>' . $insUser->ins_username . '</td><td>' . $this->getDateDifference($endDateTime) . ' </td></tr>';
                                        $where = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUser->ins_user_id]];
                                        $customMessageData['message'] = 'This instagram users Auto Likes profile subscription will expire after 2 days) or ( only 3 days remaining for Auto Likes profile subscription.';
                                        $queryResult = $objInstagramUserModel->updateUserDetails($where, $customMessageData);
                                        $notifyUserCount += 1;
                                    }
                                }

                            } else {
                                //Code for for Autolikes subscription expired.
                                //After end date reached system will pause the auto likes and set the profile status to finished
                                $where = [
                                    'rawQuery' => 'ins_user_id=?',
                                    'bindParams' => [$insUser->ins_user_id]
                                ];
                                $queryResult = $objInstagramUserModel->updateUserDetails($where, ['ig_user_status' => 1]);
                            }
                        }

                    }//End of profile subscription - Inner for loop
                    $tableBody = $tableBody . '<tbody>';
                    if ($notifyUserCount != 0) {
                        $html = '<div><h3>Alert Message</h3><br> <span>The following Instagram Auto-Likes profile are about to expire. </span><br>
                                <table>
                                    <thead>
                                        <tr> <th>Instagram Username</th> <th>Autolikes Profile subscription Expire after </th>  </tr>
                                    </thead>
                                ' . $tableBody . '
                                </table>
                            </div>';
                        $subject = 'Instagram Users Profile daily subscription Notification';
                        $toEmail = $user->email;
                        $result = $this->sendEmailNotification($html, $subject, $toEmail);
                    }

                }
            }

            $where = ['rawQuery' => 'user_id=?', 'bindParams' => [$user->user_id]];
            $queryResult = $objUsersmetaModel->updateUsermetaWhere($where, ['cronjob_status' => 0]);
        }//End of Outer for loop


    }

    public function sendEmailNotification($html, $subject, $toEmail)
    {
        $mandrill = new Mandrill($this->MANDRILL_KEY);
        $async = false;
        $ip_pool = 'Main Pool';
        $message = array(
            'html' => $html,
            'subject' => $subject,
            'from_email' => "support@instagramautolikes.com",
            'to' => array(
                array(
                    'email' => $toEmail,
                    'type' => 'to'
                )
            ),
//            'merge_vars' => array(
//                array(
//                    "rcpt" => $toEmail,
//                    'vars' => array(
//                        array(
//                            "name" => "firstname",
//                            "content" => $postData['firstname']
//                        ),
//                        array(
//                            "name" => "password",
//                            "content" => $password
//                        )
//                    )
//                )
//            ),
        );
//        $mailRespons = $mandrill->messages->send($message, $async, $ip_pool);
//        if ($mailRespons[0]['status'] == "sent") {
//            return "sent";
//        } else {
//            echo "error";
//        }
    }

    public function getDateDifference($datetime)
    {
        $datetime1 = new \DateTime(date('Y-m-d H:i:s', time()));
        $datetime2 = new \DateTime(date('Y-m-d H:i:s', $datetime));
        $interval = $datetime1->diff($datetime2);
//        $suffix = ($interval->invert ? ' ago' : '');
        if ($v = $interval->y >= 1) return $this->pluralize($interval->y, 'year');// . $suffix;
        if ($v = $interval->m >= 1) return $this->pluralize($interval->m, 'month');// . $suffix;
        if ($v = $interval->d >= 1) return $this->pluralize($interval->d, 'day');// . $suffix;
        if ($v = $interval->h >= 1) return $this->pluralize($interval->h, 'hour');// . $suffix;
        if ($v = $interval->i >= 1) return $this->pluralize($interval->i, 'min');// . $suffix;
        return $this->pluralize($interval->s, 'sec');// . $suffix;
    }

    private function pluralize($count, $text)
    {
//        $data = array();
//        $data[] = $count;
//        $data[] = $text;
//        return $data;
        return $count . (($count == 1) ? (" $text") : (" ${text}s"));
    }

}// END OF CLASS
