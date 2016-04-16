<?php

namespace App\Http\Controllers\User;

use App\Http\Models\Comment;
use App\Http\Models\Comments_Group;
use App\Http\Models\Instagram_User;
use App\Http\Models\Order;
use App\Http\Models\Plan;
use App\Http\Models\Process_Order;
use App\Http\Models\User;
use App\Http\Models\Usersmeta;
use App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class OrderController extends Controller
{

    protected $API_TOKEN;

    public function  __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
    }

    public function getAddOrderFormDetails(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objPlanModel = new Plan();
            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                    'user_id' => "required|exists:users,id",
                ];


                $validateUserId = Validator::make($postData, $rules);

                if (!$validateUserId->fails()) {
                    $whereGroupID = [
                        'rawQuery' => 'plans.plangroup_id=? and plans.plan_type=? and plans.service_type=?',
                        'bindParams' => [1, 0, 'R']
                    ];

//                $whereGroupID = [
//                    'rawQuery' => 'plans.plangroup_id=?',
//                    'bindParams' => ['1']
//                ];

                    $where = [
                        'rawQuery' => 'id=?',
                        'bindParams' => [$postData['user_id']]
                    ];
                    $selectColumn = array('usergroup_id');
                    $userDetails = $objUserModel->getUsercredsWhere($where, $selectColumn);

                    if (intval($userDetails->usergroup_id) != 0) {
                        $whereUserGroupID = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?',
                            'bindParams' => [1, intval($userDetails->usergroup_id), 0, 'R']
                        ];

                        $plansGroupDetails = $objPlanModel->getFilterPlansDetails($whereUserGroupID);


                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?',
                            'bindParams' => [1, 0, 0, 'R']
                        ];

                        $plansList = $objPlanModel->getFilterPlansDetails($wherePlans);

                        if ($plansGroupDetails != 0) {
                            foreach ($plansGroupDetails as $planGroup) {
                                $parentPlanId = $planGroup->parent_plan_id;
                                foreach ($plansList as $plan) {
                                    if ($plan->plan_id == $parentPlanId) {
                                        $plan->charge_per_unit = $planGroup->charge_per_unit;
                                    }
                                }
                            }
                        }
                    } else {
                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?',
                            'bindParams' => [1, 0, 0, 'R']
                        ];

                        $plansList = $objPlanModel->getFilterPlansDetails($wherePlans);
                    }


                    if ($plansList != 0 and $plansList != 2) {
                        foreach ($plansList as $plan) {
                            $plan_name = $plan->plan_name;
                            $price_per_k = $plan->charge_per_unit;
//                        $price_per_k = rtrim((string)$price_per_k,"0");
                            $plan->plan_name = $plan_name . ' - $' . $price_per_k . '/k';
                        }
                    }


                    if ($plansList) {
                        $response->code = 200;
                        $response->message = "Plans and Price Details";
                        $response->data = $plansList;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 400;
                        $response->message = "Something went wrong, please try again.";
                        $response->data = null;
                        echo json_encode($response);
                    }

                } else {
                    $response->code = 401;
                    $response->message = $validateUserId->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function getPlanList(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objPlanModel = new Plan();
            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $plansList = '';
                if (isset($postData['filter_plan_list']) && $postData['filter_plan_list'] == 'yes') {
                    $where = ['rawQuery' => 'id=?', 'bindParams' => [$postData['user_id']]];
                    $selectColumn = array('usergroup_id');
                    $userDetails = $objUserModel->getUsercredsWhere($where, $selectColumn);

                    if (intval($userDetails->usergroup_id) != 0) {
                        $whereUserGroupID = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=?',
                            'bindParams' => [1, intval($userDetails->usergroup_id)]
                        ];

                        $plansGroupDetails = $objPlanModel->getFilterPlansDetails($whereUserGroupID);

                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=?',
                            'bindParams' => [1, 0]
                        ];
                        $plansList = $objPlanModel->getFilterPlansDetails($wherePlans);

                        if ($plansGroupDetails != 0) {
                            foreach ($plansGroupDetails as $planGroup) {
                                $parentPlanId = $planGroup->parent_plan_id;
                                foreach ($plansList as $plan) {
                                    if ($plan->plan_id == $parentPlanId) {
                                        $plan->charge_per_unit = $planGroup->charge_per_unit;
                                    }
                                }
                            }
                        }

                    } else {
                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? ',
                            'bindParams' => [1, 0]
                        ];
                        $plansList = $objPlanModel->getFilterPlansDetails($wherePlans);
                    }

                    if ($plansList != 0 and $plansList != 2) {
                        foreach ($plansList as $plan) {
                            $plan_name = $plan->plan_name;
                            $price_per_k = $plan->charge_per_unit;
//                        $price_per_k = rtrim((string)$price_per_k,"0");
                            $plan->plan_name = $plan_name . ' - $' . $price_per_k . '/k';
                        }
                    }

                } else {
                    $whereGroupID = [
                        'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? ',
                        'bindParams' => [1, 0]
                    ];
                    $plansList = $objPlanModel->getPlansDetails($whereGroupID);
                }


                if ($plansList != 0 and $plansList != 2) {
                    $response->code = 200;
                    $response->message = "List of Available Plans details.";
                    $response->data = $plansList;
                    echo json_encode($response, true);
                } else if ($plansList == 0) {
                    $response->code = 201;
                    $response->message = "No plans are available.";
                    $response->data = null;
                    echo json_encode($response, true);
                } else {
                    $response->code = 400;
                    $response->message = "Something went wrong, please try again.";
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


    public function getFilterPlanList(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objPlanModel = new Plan();
            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : '';

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                    'user_id' => "required|exists:users,id",
                    'plan_type_id' => 'required',
                    'service_type_id' => 'required',
                ];

                $validator = Validator::make($postData, $rules);

                if (!$validator->fails()) {

                    // check if user exist in any plan group created by admin or not.

                    $where = [
                        'rawQuery' => 'id=?',
                        'bindParams' => [$postData['user_id']]
                    ];
                    $selectColumn = array('usergroup_id');
                    $userDetails = $objUserModel->getUsercredsWhere($where, $selectColumn);

                    if (intval($userDetails->usergroup_id) != 0) {
                        $whereUserGroupID = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?',
                            'bindParams' => [1, intval($userDetails->usergroup_id), intval($postData['plan_type_id']), $postData['service_type_id']]
                        ];

                        if (intval($postData['plan_type_id']) == 2) {
                            $whereUserGroupID = [
                                'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and ( plans.plan_type=? or plans.plan_type=? ) and plans.service_type=?',
                                'bindParams' => [1, 0, intval($postData['plan_type_id']), 3, $postData['service_type_id']]
                            ];
                        }

                        $plansGroupDetails = $objPlanModel->getFilterPlansDetails($whereUserGroupID);


                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?',
                            'bindParams' => [1, 0, intval($postData['plan_type_id']), $postData['service_type_id']]
                        ];
                        if (intval($postData['plan_type_id']) == 2) {
                            $wherePlans = [
                                'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and ( plans.plan_type=? or plans.plan_type=? ) and plans.service_type=?',
                                'bindParams' => [1, 0, intval($postData['plan_type_id']), 3, $postData['service_type_id']]
                            ];
                        }
                        $plansList = $objPlanModel->getFilterPlansDetails($wherePlans);

                        if ($plansGroupDetails != 0) {
                            foreach ($plansGroupDetails as $planGroup) {
                                $parentPlanId = $planGroup->parent_plan_id;
                                foreach ($plansList as $plan) {
                                    if ($plan->plan_id == $parentPlanId) {
                                        $plan->charge_per_unit = $planGroup->charge_per_unit;
                                    }
                                }
                            }
                        }
                    } else {
                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?',
                            'bindParams' => [1, 0, intval($postData['plan_type_id']), $postData['service_type_id']]
                        ];

                        if (intval($postData['plan_type_id']) == 2) {
                            $wherePlans = [
                                'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and ( plans.plan_type=? or plans.plan_type=? ) and plans.service_type=?',
                                'bindParams' => [1, 0, intval($postData['plan_type_id']), 3, $postData['service_type_id']]
                            ];
                        }
                        $plansList = $objPlanModel->getFilterPlansDetails($wherePlans);
                    }


                    if ($plansList != 0 and $plansList != 2) {
                        foreach ($plansList as $plan) {
                            $plan_name = $plan->plan_name;
                            $price_per_k = $plan->charge_per_unit;
//                        $price_per_k = rtrim((string)$price_per_k,"0");
                            $plan->plan_name = $plan_name . ' - $' . $price_per_k . '/k';
                        }
//                        dd($plansList);
                        $response->code = 200;
                        $response->message = "List of Available Plans details.";
                        $response->data = $plansList;
                        echo json_encode($response, true);
                    } else if ($plansList == 0) {
                        $response->code = 201;
                        $response->message = "No plans are available in this category.";
                        $response->data = null;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 400;
                        $response->message = "Something went wrong, please try again.";
                        $response->data = null;
                        echo json_encode($response);
                    }
                } else {
                    $response->code = 201;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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


    public function getCommentsGroupList(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objCommentGroupModel = new Comments_Group();
            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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

                $whereCommentGroupId = [
                    'rawQuery' => 'added_by=? or added_by=?',
                    'bindParams' => [0, $postData['user_id']]
                ];
                $commentGroupList = $objCommentGroupModel->getCommentGroupListAddedBy($whereCommentGroupId);

                if ($commentGroupList) {
                    $response->code = 200;
                    $response->message = "List of Comments";
                    $response->data = $commentGroupList;
                    echo json_encode($response, true);
                } else {
                    $response->code = 400;
                    $response->message = "Something went wrong, please try again.";
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

    public function addOrder(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objUsermetaModel = new Usersmeta();
            $instagramScrape = new API\InstagramAPI\Instagram_scrape();

            $objIinstagramAPI = new API\InstagramAPI\Instagram();
            $objOrderModel = new Order();
            $objPlanModel = new Plan();
            $objCommentModel = new Comment();

            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : "";

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                    'plan_id' => 'required|exists:plans,plan_id',
                    'user_id' => "required|exists:users,id",
                    'order_url' => "required|url",//|regex: $regex",//TODO:
                    'quantity' => "required|integer",
                ];
                $messages = [
                    'plan_id.exists' => 'Please choose a service',
                    'user_id.required' => 'User Id is required',
                    'order_url.required' => 'Please enter URL',
                    'quantity.required' => 'Please enter amount to delivery',
                ];

                $validatePlanId = Validator::make($postData, $rules, $messages);

                if (!$validatePlanId->fails()) {

                    $planId = $postData['plan_id'];
                    $userId = $postData['user_id'];
                    $orderUrl = $postData['order_url'];
                    $quantity = $postData['quantity'];
                    $data = array();
                    $startingTime = time();
                    $errorMessage = '';
                    $errorMessageFlag = false;
                    $customOrderMessage = 'Order has inserted! Please wait 5 minutes to get it started!';

                    if (isset($postData['starting_time_option']) && $postData['starting_time_option'] == 'on') {
                        if (isset($postData['starting_time']) && $postData['starting_time'] != '') {
                            $dt = new API\TimeZoneConvertion();
                            $time = $postData['starting_time'];
                            //get the tinezone from server
                            $result = $objUserModel->getUsercredsWhere(['rawQuery' => 'id=?', 'bindParams' => [$userId]], ['user_timezone']);

                            $fromTz = $result->user_timezone;
                            $toTz = 'UTC';
                            $startingTime = $dt->convertLocalTimeToUnixTime($time, $fromTz, $toTz);
                            $customOrderMessage = 'Order has inserted! This order has a schedule time (' . $postData['starting_time'] . ').Please wait to get it started!';
                        } else {
                            $errorMessage = 'Please select schedule starting time.';
                            $errorMessageFlag = true;
                        }
                    }

                    $planDetails = $objPlanModel->getPlansDetails(['rawQuery' => 'plans.plangroup_id=? and plan_id=?', 'bindParams' => ['1', $planId]]);
                    $maxQuantity = $planDetails[0]->max_quantity;
                    $chargePer1K = $planDetails[0]->charge_per_unit;
                    $planType = $planDetails[0]->plan_type;


                    //perform the order_url validation
                    $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/+(p)\/(([a-zA-Z0-9\.\-\_])*)+\/(([a-zA-Z0-9\?\-\=\.\@])*)/';
                    $urlType = (preg_match($regex, $orderUrl)) ? "postLink" : "profileLink";

                    if (isset($postData['spreadOrders']) && $postData['spreadOrders'] == 'on' && $planType == 0) {

                        if ($urlType != "profileLink") {
                            $errorMessage = 'Your link looks invalid! Example of a correct link for this service : http://instagram.com/username/';
                            $errorMessageFlag = true;
                        } else {

                            $temp = explode('/', $orderUrl);
                            $instagramUsername = $temp[3];
                            $endIndex = $postData['endSpreadIndex'];

                            $result = $objIinstagramAPI->getUserDetailsByUsername($instagramUsername, $endIndex);

                            if ($result == 'Username does not exist') {
                                $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' does not exist.';
                                $errorMessageFlag = true;
                            } else if ($result == 'user is private') {
                                $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' is private !. You cannot place order for private user.';
                                $errorMessageFlag = true;
                            } else if ($result == 'There are no any post') {
                                $errorMessage = 'Error! There are no any post in this profile ( ' . $instagramUsername . ' ).';
                                $errorMessageFlag = true;
                            } else if ($result == 'Too many request') {

                                //instgarm api is block the do scrap here to check username is exist or not
                                if ($instagramScrape->isUserFound($instagramUsername)) {
                                    $userProfilePostCount = $instagramScrape->getProfilePostCountByUsername($instagramUsername);
                                    if ($userProfilePostCount < $endIndex) {
                                        $errorMessage = 'Error! This order cannot be place due to less number of post in this profile ( ' . $instagramUsername . ' ).';
                                        $errorMessageFlag = true;
                                    }
                                } else {
                                    $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' does not exist.';
                                    $errorMessageFlag = true;
                                }
                            } else {
                                $userProfilePostCount = $instagramScrape->getProfilePostCountByUsername($instagramUsername);
                                if ($userProfilePostCount < $endIndex) {
                                    $errorMessage = 'Error! This order cannot be place due to less number of post in this profile ( ' . $instagramUsername . ' ).';
                                    $errorMessageFlag = true;
                                }
                            }
                        }
                    } else if ($planType == 0 || $planType == 2 || $planType == 3) {
                        if ($urlType != "postLink") {
                            $errorMessage = 'Your link looks invalid! Example of a correct link for this service : http://instagram.com/p/vrTV-bAp9E/';
                            $errorMessageFlag = true;
                        }
                    }

                    $commentFlag = false;
                    $commentsData = array();
                    if ($planType == 0) { // for likes
                        if (isset($postData['spreadOrders']) && $postData['spreadOrders'] == 'on') {

                            if (isset($postData['startSpreadIndex']) && ($postData['startSpreadIndex'] == null || $postData['startSpreadIndex'] == '')) {
                                $errorMessage = 'Start pics index is required';
                                $errorMessageFlag = true;
                            } else if (isset($postData['endSpreadIndex']) && ($postData['endSpreadIndex'] == null || $postData['endSpreadIndex'] == '')) {
                                $errorMessage = 'End pics index is required';
                                $errorMessageFlag = true;
                            } else {
                                $data['start_index'] = intval($postData['startSpreadIndex']);
                                $data['end_index'] = intval($postData['endSpreadIndex']);
                            }
                        }
                    } elseif ($planType == 3) { // for custom comment
                        if ($postData['customCommentType'] == 0) { //custom written comments by user

                            $commentsArray = explode("\r\n", $postData['commentsTextArea']);
                            $commentsFilterArray = array();
                            foreach ($commentsArray as $comment) {
                                if (!empty($comment)) {
                                    $commentsFilterArray[] = $comment;
                                }
                            }
                            $commentsJson = json_encode($commentsFilterArray, true);
                            $commentsData['comments'] = $commentsJson;
                            $commentsData['comment_group_id'] = 0;
                            $commentsData['added_by'] = $userId;
                            $commentFlag = true;
                        } else { // Selected Comments  comment_group
                            $whereCommentGroupId = ['rawQuery' => 'comment_group_id=?', 'bindParams' => [$postData['comment_group_id']]];
                            $commentsData = $objCommentModel->getCommentList($whereCommentGroupId, ['comment_id', 'comments']);

                            if ((isset($commentsData->comments))) {
                                if (count(json_decode($commentsData->comments)) != 0) {
                                    $data['comment_id'] = $commentsData->comment_id;
                                } else {
                                    $errorMessage = 'There are no comments in this group please add comments inthis group.';
                                    $errorMessageFlag = true;
                                }
                            } else {
                                $errorMessage = 'Invalid Comment Group Id.';
                                $errorMessageFlag = true;
                            }
                        }
                    }

                    if (!$errorMessageFlag) {

                        $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$userId]], ['account_bal']);
                        $accountBalance = $accountBalanceDetails->account_bal;
                        $postData['total_order_price'] = ($chargePer1K / 1000) * $quantity;

                        $rules = [
                            'quantity' => "required|integer|max:$maxQuantity",
                            'total_order_price' => "required|numeric|max:$accountBalance",
                        ];
                        $messages = [
                            'quantity.required' => 'Please enter amount to delivery',
                            'total_order_price.max' => 'Insufficient Balance while placing an order',
                        ];

                        $validator = Validator::make($postData, $rules, $messages);

                        if (!$validator->fails()) {
                            $data['order_message'] = $customOrderMessage;
                            $data['plan_id'] = $planId;
                            $data['by_user_id'] = $userId;
                            $data['ins_url'] = $orderUrl;
                            $data['quantity_total'] = $quantity;
                            $data['start_time'] = $startingTime;
                            $data['added_time'] = time();
                            $data['updated_time'] = time();
                            $data['status'] = 0;
                            $data['price'] = $postData['total_order_price'];

                            $data['orders_per_run'] = intval((isset($postData['ordersPerRun'])) ? $postData['ordersPerRun'] : 0);
                            $data['time_interval'] = intval((isset($postData['timeInterval'])) ? $postData['timeInterval'] : 0);

                            $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/+(p)\/(([a-zA-Z0-9\.\-])*)/';
                            $data['url_type'] = (preg_match($regex, $orderUrl)) ? 0 : 1;

                            $rollback = false;
                            DB::beginTransaction();
                            DB::table('usersmeta')->where('user_id', '=', $userId)->lockForUpdate()->get();
                            if ($commentFlag) {
                                $commentsInsertedID = $objCommentModel->insertComments($commentsData);
                                $data['comment_id'] = $commentsInsertedID;
                            }
                            $orderInsertStatus = $objOrderModel->insertOrder($data);
                            if ($orderInsertStatus) {
                                $current_bal['account_bal'] = $accountBalance - $postData['total_order_price'];
                                $orderUpdateBalanceStatus = $objUsermetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$userId]], $current_bal);
                                if ($orderUpdateBalanceStatus) {
                                    DB::commit();
                                } else {
                                    $rollback = true;
                                    DB::rollBack();
                                }
                            } else {
                                $rollback = true;
                                DB::rollBack();
                            }
                            if (!$rollback) {
                                $response->code = 200;
                                $response->message = 'Order Placed Successfull';
                                $response->data = $current_bal;
                                echo json_encode($response);
                            } else {
                                $response->code = 400;
                                $response->message = "Something went wrong, please try again.";
                                $response->data = null;
                                echo json_encode($response);
                            }
                        } else {
                            $response->code = 204;
                            $response->message = $validator->messages();
                            $response->data = null;
                            echo json_encode($response);
                        }
                    } else {
                        $response->code = 204;
                        $response->message = $errorMessage;
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

    public function getOrderHistory(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objOrderModel = new Order();

            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : "";

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['user_id' => 'required']);
                if (!$validator->fails()) {
                    $whereOderUserID = [
                        'rawQuery' => 'by_user_id=? and orders.status!=6',
                        'bindParams' => [$userId]
                    ];
                    $data = [
                        'orders.order_id', 'orders.server_order_id', 'orders.ins_url', 'orders.quantity_total', 'orders.price',
                        'orders.quantity_done', 'orders.status', 'orders.added_time', 'orders.updated_time',
                        'plans.plan_name', 'plans.supplier_server_id'
                    ];
                    $userOrderHistory = $objOrderModel->getOrderHistory($whereOderUserID, $data);

                    foreach ($userOrderHistory as $order) {
                        $order->added_time = $this->getDateDifference($order->added_time);
                        $order->updated_time = $this->getDateDifference($order->updated_time);
                    }
                    if ($userOrderHistory) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $userOrderHistory;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 401;
                        $response->message = "Error in connection please try again.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function getMoreOrderDetails(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objOrderModel = new Order();

            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : "";

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['order_id' => 'required|exists:orders,order_id']);
                if (!$validator->fails()) {
                    $whereOderUserID = [
                        'rawQuery' => 'order_id=?',
                        'bindParams' => [$postData['order_id']]
                    ];

                    $userOrderDetails = $objOrderModel->getOrderHistory($whereOderUserID);

                    if ($userOrderDetails) {

                        //for display more details
                        $resultData['planName'] = $userOrderDetails[0]->plan_name;


                        $startCount = 0;
                        if ($userOrderDetails[0]->plan_type == 0) {
                            $resultData['startCount'] = $userOrderDetails[0]->initial_likes_count;
                        } else if ($userOrderDetails[0]->plan_type == 1) {
                            $resultData['startCount'] = $userOrderDetails[0]->initial_followers_count;
                        } else {
                            $resultData['startCount'] = $userOrderDetails[0]->initial_comments_count;
                        }

                        $resultData['currentCount'] = $resultData['startCount'] + $userOrderDetails[0]->quantity_done;
                        $resultData['remainCount'] = $userOrderDetails[0]->quantity_total - $userOrderDetails[0]->quantity_done;
                        $resultData['finishCount'] = $userOrderDetails[0]->quantity_done;
                        $resultData['message'] = $userOrderDetails[0]->order_message;

                        $response->code = 200;
                        $response->message = "success";
                        $response->data = $resultData;
                        echo json_encode($response, true);


                    } else {
                        $response->code = 401;
                        $response->message = "Error in connection please try again.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }

                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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
        return $count . (($count == 1) ? (" $text") : (" ${text}s"));
    }

    public function cancelOrder(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objOrderModel = new Order();
            $objUsersmetaModel = new Usersmeta();
            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $orderData = json_decode($request['order_id'], true);
                if (empty($orderData)) {
                    $postData['order_id'] = "";
//                    dd($postData);
                } elseif (count($orderData) == 1 && ($orderData[0] == null || $orderData[0] == '')) {
                    $postData['order_id'] = $orderData[0];
//                    dd($postData);
                } else if (count($orderData) > 1 && ($orderData[0] == null || $orderData[0] == '')) {
                    $orderData = array_slice($orderData, 1);
                }


                $validator = Validator::make($postData, ['order_id' => 'required'], ['order_id.required' => 'Order Id is required']);
                if (!$validator->fails()) {
                    //TODO WRITE API CODE HERE
//                    dd($orderData);

                    foreach ($orderData as $key => $order_id) {
                        $orderStatus = $objOrderModel->getOrderStatus(['rawQuery' => 'order_id=?', 'bindParams' => [$order_id]], ['orders.status', 'orders.by_user_id', 'orders.price']);

                        if ($orderStatus) {
                            if ($orderStatus[0]->status == 0 || $orderStatus[0]->status == 1) {
                                $rollback = false;
                                DB::beginTransaction();
                                DB::table('usersmeta')->where('user_id', '=', $orderStatus[0]->by_user_id)->lockForUpdate()->get();
                                $oldAccountBal = $objUsersmetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$orderStatus[0]->by_user_id]], ['account_bal']);
                                $newAccountBal = $oldAccountBal->account_bal + $orderStatus[0]->price;

                                $queryResult = $objUsersmetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$orderStatus[0]->by_user_id]], ['account_bal' => $newAccountBal]);
                                if ($queryResult) {
//                                    $result = $objOrderModel->updateOrder($where, ['status' => 6]);
                                    $result = $objOrderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order_id]], ['status' => 6]);
                                    DB::commit();
                                } else {
                                    $rollback = true;
                                    DB::rollBack();
                                }
                                if (!$rollback) {
                                    $messages[$key] = "This order is now canceled and the money is deposited back in your account, order_id :" . $order_id . "\n";
                                } else {
                                    $messages[$key] = "There is an problem in order #ID " . $order_id . " cancellation process.\n";
                                }
                            } else if ($orderStatus[0]->status == 3 || $orderStatus[0]->status == 4 || $orderStatus[0]->status == 5) {
                                $messages[$key] = "Your order with the ID #" . $order_id . " cannot be refunded because its already added on the system.\n";
                            } else if ($orderStatus[0]->status == 6) {
                                $messages[$key] = "Your order with the ID #" . $order_id . " is already cancelled.\n";
                            }
                        } else {
                            $messages[$key] = "This order ID #" . $order_id . " is invalid \n";
                        }
                    }

                    $response->code = 200;
                    $response->message = $messages;
                    $response->data = null;
                    echo json_encode($response, true);
                } else {
                    $response->code = 204;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function  reAddOrder(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objUsermetaModel = new Usersmeta();
            $objOrderModel = new Order();
            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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

                $orderData = json_decode($request['order_id'], true);
                if (empty($orderData)) {
                    $postData['order_id'] = "";
                } elseif (count($orderData) == 1 && ($orderData[0] == null || $orderData[0] == '')) {
                    $postData['order_id'] = $orderData[0];
                } else if (count($orderData) > 1 && ($orderData[0] == null || $orderData[0] == '')) {
                    $orderData = array_slice($orderData, 1);
                }

                $rules = [
                    'order_id' => 'required',
                    'user_id' => 'required|exists:users,id'
                ];
                $messages = [
                    'order_id.required' => 'Order Id is required',
                    'user_id.required' => 'User Id is required',
                    'user_id.exists' => 'Invalid User Id'
                ];
                $validator = Validator::make($postData, $rules, $messages);
                if (!$validator->fails()) {
                    //TODO WRITE API CODE HERE

                    $messages = array();
                    foreach ($orderData as $key => $order_id) {

                        $orderDetails = $objOrderModel->getOrderDetails(['rawQuery' => 'order_id=?', 'bindParams' => [$order_id]]);
                        if ($orderDetails) {
                            unset($orderDetails[0]->order_id);

                            $data = "";
                            foreach ($orderDetails[0] as $key => $value) {
                                $data[$key] = $value;
                            }
                            $data['status'] = 0;
                            $data['start_time'] = time() + 300;
                            $data['added_time'] = time();
                            $data['updated_time'] = time();
                            $data['cronjob_status'] = 0;
                            $data['order_message'] = 'Order has inserted! Please wait 5 minutes to get it started!';
                            $price = $data['price'];

//                            dd($data);
                            //TODO PRODUCT LOCKING, DB NOCOMMIT IN LARAVEL
                            $rollback = false;
                            $successFlag = false;
                            DB::beginTransaction();
                            DB::table('usersmeta')->where('user_id', '=', [$postData['user_id']])->lockForUpdate()->get();

                            $orderInsertedID = $objOrderModel->insertOrder($data);
//                            dd($orderInsertedID);
                            if ($orderInsertedID) {
                                $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$postData['user_id']]], ['account_bal']);
                                $accountBalance = $accountBalanceDetails->account_bal;
//                                dd($accountBalance);
                                if ($accountBalance >= $price) {
                                    $current_bal['account_bal'] = $accountBalance - $price;
                                    $orderUpdateBalanceStatus = $objUsermetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$postData['user_id']]], $current_bal);
//                                    dd($orderUpdateBalanceStatus);
                                    if ($orderUpdateBalanceStatus) {
                                        $messages[] = "This order ID #" . $order_id . " is re-added successful with order ID #" . $orderInsertedID;
                                        DB::commit();
                                    } else {
                                        $rollback = true;
                                        $messages[] = "Error in re-add order with order ID #" . $order_id . " Please try again after few minutes.";
                                        DB::rollBack();
                                    }
                                } else {
                                    $messages[] = "Insufficient Balance while adding an order.";
                                    $rollback = true;
                                    DB::rollBack();
                                }
                            } else {
                                $rollback = true;
                                $messages[] = "Error in re-add order with order ID #" . $order_id . " Please try again after few minutes.";
                                DB::rollBack();
                            }
                            if ($rollback) {
                                break;
                            }
                        } else {
                            $messages[] = "This order ID #" . $order_id . " is invalid.";
                        }
                    }
                    $response->code = 200;
                    $response->message = $messages;
                    $response->data = null;
                    echo json_encode($response, true);
                } else {
                    $response->code = 204;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function editOrder(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objOrderModel = new Order();

            $userId = (isset($postData['user_id'])) ? $userId = $postData['user_id'] : "";
            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                    'order_id' => 'required|exists:orders,order_id',
                    'order_url' => 'required',
                    'user_id' => 'required|exists:users,id'
                ];
                $messages = [
                    'order_id.required' => 'Order Id is required',
                    'order_id.exists' => 'Invalid Order Id',
                    'user_id.required' => 'User Id is required',
                    'user_id.exists' => 'Invalid User Id'
                ];
                $validator = Validator::make($postData, $rules, $messages);


                if (!$validator->fails()) {

                    $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/(([a-zA-Z0-9\.\-])*)/';
                    if (preg_match($regex, $postData['order_url'])) {

                        $orderDetails = $objOrderModel->getOrderDetails(['rawQuery' => 'order_id=?', 'bindParams' => [$postData['order_id']]], ['url_type', 'status', 'cronjob_status']);

                        $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/+(p)\/(([a-zA-Z0-9\.\-])*)/';
                        $editOrderUrl = (preg_match($regex, $postData['order_url'])) ? 0 : 1;

                        $errorMessage = '';
                        $successFlag = false;
                        if ($orderDetails[0]->url_type == 0) {
                            if ($orderDetails[0]->url_type == $editOrderUrl) {
                                $successFlag = true;
                            } else {
                                $errorMessage = 'Please enter only post URL. Ex https://www.instagram.com/p/BB19YjwEDHw/';
                            }
                        } else {
                            if ($orderDetails[0]->url_type == $editOrderUrl) {
                                $successFlag = true;
                            } else {
                                $errorMessage = 'Please enter only profile URL.  Ex http://www.instagram.com/Username/';
                            }
                        }
                        if ($successFlag) {
                            if ($orderDetails[0]->status == 0) {
                                $queryResult = $objOrderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$postData['order_id']]], ['ins_url' => $postData['order_url']]);
                                if ($queryResult) {
                                    $response->code = 200;
                                    $response->message = "This order ID #" . $postData['order_id'] . " has updated successful.";
                                    $response->data = null;
                                    echo json_encode($response, true);
                                } else {
                                    $response->code = 204;
                                    $response->message = "Something went wrong please try again after sometime.";
                                    $response->data = null;
                                    echo json_encode($response, true);
                                }
                            } else {
                                $response->code = 204;
                                $response->message = "This order ID #" . $postData['order_id'] . " is not editable as this order is already added in server";
                                $response->data = null;
                                echo json_encode($response, true);
                            }
                        } else {
                            $response->code = 204;
                            $response->message = $errorMessage;
                            $response->data = null;
                            echo json_encode($response, true);
                        }
                    } else {
                        $response->code = 204;
                        $response->message = "Invalid instagram Url.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 204;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function addAutolikesOrder(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objPlanModel = new Plan();
            $objInstagramUserModel = new Instagram_User();
            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : '';

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                    'instagramUsername' => 'required',
                    'likesPerPic' => 'required|integer',
                    'picLimit' => 'required|integer',
                    'planId' => 'required|exists:plans,plan_id',
                    'user_id' => 'required|exists:users,id',

                ];
                $validator = Validator::make($postData, $rules);

                if (!$validator->fails()) {
                    $data['by_user_id'] = $postData['user_id'];
                    $data['ins_username'] = $postData['instagramUsername'];
                    $data['plan_id'] = $postData['planId'];
                    $data['pics_limit'] = $postData['picLimit'];
                    $data['likes_per_pic'] = $postData['likesPerPic'];
                    $data['ig_user_status'] = 2;
                    $data['last_check'] = time();
                    $data['last_delivery'] = 0;


                    $errorFlag = false;
                    if (isset($postData['autolikesSubscription'])) {
                        if ($postData['autolikesSubscription'] == 'on') {
                            $rules = [
                                'startDate' => 'required|date',
                                'endDate' => 'required|date'
                            ];
                            $validator = Validator::make($postData, $rules);
                            if (!$validator->fails()) {

                                $dt = new API\TimeZoneConvertion();
                                $startDT = $postData['startDate'];
                                $endDT = $postData['endDate'];
                                //get the tinezone from server
                                $result = $objUserModel->getUsercredsWhere(['rawQuery' => 'id=?', 'bindParams' => [$userId]], ['user_timezone']);

                                $fromTz = $result->user_timezone;
                                $toTz = 'UTC';
                                $startDateTime = $dt->convertLocalTimeToUnixTime($startDT, $fromTz, $toTz);
                                $endDateTime = $dt->convertLocalTimeToUnixTime($endDT, $fromTz, $toTz);
                                $data['start_date_time'] = $startDateTime;
                                $data['end_date_time'] = $endDateTime;

                            } else {
                                $errorFlag = true;
                                $response->code = 401;
                                $response->message = $validator->messages();
                                $response->data = null;
                                echo json_encode($response, true);
                            }
                        }
                    } else {
                        $data['start_date_time'] = time();
                        $data['end_date_time'] = 0;
                    }

                    if (!$errorFlag) {
                        if (isset($postData['autoComments'])) {
                            if ($postData['autoComments'] == 'YES') {
                                $rules = [
                                    'autoCommentPlanId' => 'required|exists:plans,plan_id',
                                    'autoCommentAmount' => 'required|integer'
                                ];
                                $validator = Validator::make($postData, $rules);
                                if (!$validator->fails()) {
                                    $planId = $postData['autoCommentPlanId'];
                                    $data['plan_id_for_auto_comment'] = $planId;
                                    $where = ['rawQuery' => 'plan_id=?', 'bindParams' => [$planId]];
                                    $selectColumn = array('plan_type');
                                    $planIdDetails = $objPlanModel->getPlansDetails($where, $selectColumn);

                                    if ($planIdDetails[0]->plan_type == 3) {
                                        if (isset($postData['customCommentGroupId'])) {
                                            $data['custom_comment_group_id'] = $postData['customCommentGroupId'];
                                        } else {
                                            $data['custom_comment_group_id'] = 1;
                                        }
                                    }
                                    if (isset($postData['autoCommentAmount'])) {
                                        $data['comments_amount'] = $postData['autoCommentAmount'];
                                    }
                                } else {
                                    $errorFlag = true;
                                    $response->code = 401;
                                    $response->message = $validator->messages();
                                    $response->data = null;
                                    echo json_encode($response, true);
                                }
                            }
                        }
                    }

                    if (!$errorFlag) {
                        $result = $objInstagramUserModel->insertInsUserAutolikesOrder($data);
                        if ($result) {
                            $response->code = 200;
                            $response->message = "Username added successfully for autolikes. ";
                            $response->data = null;
                            echo json_encode($response, true);
                        } else {
                            $response->code = 401;
                            $response->message = "Something went wrong please try after sometime";
                            $response->data = null;
                            echo json_encode($response, true);
                        }
                    }

                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function getAutolikesOrderHistory(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objInstagramUserModel = new Instagram_User();
            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['user_id' => 'required']);
                if (!$validator->fails()) {
                    $whereOderUserID = [
                        'rawQuery' => 'by_user_id=?',
                        'bindParams' => [$userId]
                    ];
                    $data = [
                        'instagram_users.ins_user_id',
                        'instagram_users.ins_username',
                        'plans.plan_name',
                        'instagram_users.pics_done',
                        'instagram_users.pics_limit',
                        'instagram_users.likes_per_pic',
                        'instagram_users.last_check',
                        'instagram_users.last_delivery',
                        'instagram_users.ig_user_status',
                        'instagram_users.message',
                        'instagram_users.last_delivery_link',
                    ];
                    $userAutolikesOrderHistory = $objInstagramUserModel->getInsUserAutolikesOrderHistory($whereOderUserID, $data);
//                    dd($userAutolikesOrderHistory);
                    foreach ($userAutolikesOrderHistory as $order) {
                        if ($order->last_delivery == 0) {
                            $order->last_delivery = '-';
                        } else {
                            $order->last_delivery = $this->getDateDifference($order->last_delivery);
                        }
                    }

//                    dd($userAutolikesOrderHistory);
                    if ($userAutolikesOrderHistory) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $userAutolikesOrderHistory;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 401;
                        $response->message = "Error in connection please try again.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function getMoreAutolikesOrderDetails(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objInstagramUserModel = new Instagram_User();
            $objOrderModel = new Order();

            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : "";

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['ins_user_id' => 'required|exists:instagram_users,ins_user_id']);
                if (!$validator->fails()) {
                    $whereInsUserID = [
                        'rawQuery' => 'instagram_users.ins_user_id=?',
                        'bindParams' => [$postData['ins_user_id']]
                    ];
                    $data = ['last_delivery_link', 'likes_per_pic', 'pics_done', 'comments_amount', 'message'];
                    $instagramUserDetails = $objInstagramUserModel->getUserDetails($whereInsUserID, $data);

                    if (isset($instagramUserDetails) && $instagramUserDetails != 0) {

                        //for display more details
                        $resultData['last_delivered_link'] = $instagramUserDetails[0]->last_delivery_link;
                        $resultData['likes_sent'] = intval($instagramUserDetails[0]->likes_per_pic * $instagramUserDetails[0]->pics_done);
                        $resultData['comment_sent'] = intval($instagramUserDetails[0]->comments_amount * $instagramUserDetails[0]->pics_done);
                        $resultData['pics_done'] = $instagramUserDetails[0]->pics_done;
                        $resultData['message'] = $instagramUserDetails[0]->message;

                        $response->code = 200;
                        $response->message = "success";
                        $response->data = $resultData;
                        echo json_encode($response, true);

                    } else {
                        $response->code = 401;
                        $response->message = "Error in connection please try again.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function updateUserOrderDetails(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {

            $objUserModel = new User();
            $objInstagramUserModel = new Instagram_User();
            $objPlanModel = new Plan();
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
                $postData = array();
                $postData['ins_user_id'] = (isset($request['ins_user_id'])) ? $request['ins_user_id'] : '';
                $postData['likesPerPic'] = (isset($request['edit_likesPerPic'])) ? $request['edit_likesPerPic'] : '';
                $postData['picLimit'] = (isset($request['edit_picLimit'])) ? $request['edit_picLimit'] : '';
                $postData['planId'] = (isset($request['edit_planId'])) ? $request['edit_planId'] : '';

                $rules = [
                    'ins_user_id' => 'required|exists:instagram_users,ins_user_id',
                    'likesPerPic' => 'required|integer',
                    'picLimit' => 'required|integer',
                    'planId' => 'required|exists:plans,plan_id'
                ];

                $validator = Validator::make($postData, $rules);
                if (!$validator->fails()) {

                    $data['plan_id'] = $postData['planId'];
                    $data['pics_limit'] = $postData['picLimit'];
                    $data['likes_per_pic'] = $postData['likesPerPic'];

                    $advanceOptionflag = true;
                    if (isset($request['edit_autoComments'])) {
                        if ($request['edit_autoComments'] == 'YES') {
                            $rules = [
                                'edit_autoCommentPlanId' => 'required|exists:plans,plan_id',
                                'edit_autoCommentAmount' => 'required|integer'
                            ];
                            $validator = Validator::make($request->all(), $rules);
                            if (!$validator->fails()) {
                                $planId = $request['edit_autoCommentPlanId'];
                                $data['plan_id_for_auto_comment'] = $planId;
                                $where = [
                                    'rawQuery' => 'plan_id=?',
                                    'bindParams' => [$planId]
                                ];
                                $selectColumn = array('plan_type');
                                $planIdDetails = $objPlanModel->getPlansDetails($where, $selectColumn);

                                if ($planIdDetails[0]->plan_type == 3) {
                                    if (isset($request['edit_customCommentGroupId'])) {
                                        $data['custom_comment_group_id'] = $request['edit_customCommentGroupId'];
                                    } else {
                                        $data['custom_comment_group_id'] = 1;
                                    }
                                }
                                if (isset($request['edit_autoCommentAmount'])) {
                                    $data['comments_amount'] = $request['edit_autoCommentAmount'];
                                }
                            } else {
                                $advanceOptionflag = false;
                            }
                        }
                    }
                    if ($advanceOptionflag) {
                        $queryResult = $objInstagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$postData['ins_user_id']]], $data);

                        if ($queryResult) {
                            $response->code = 200;
                            $response->message = 'Instagram user order details update successfull';
                            $response->data = null;
                            echo json_encode($response, true);
                        } else {
                            $response->code = 401;
                            $response->message = "Something went wrong please try after sometime";
                            $response->data = null;
                            echo json_encode($response, true);
                        }

                    } else {
                        $response->code = 401;
                        $response->message = $validator->messages();
                        $response->data = null;
                        echo json_encode($response, true);
                    }

                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function scheduleOrdersCronJob()
    {
        $orderModel = new Order();

        //This below code is used for processing the normal orders.
        $whereOrderStatus = [
            'rawQuery' => 'orders.start_time < ? and orders.status=? and orders.cronjob_status=? and orders.auto_order_status=?',
            'bindParams' => [time(), 0, 0, 0]
        ];
        $orderDetails = $orderModel->getOrderHistory($whereOrderStatus);
        if (!empty($orderDetails) || intval($orderDetails) != 0) {
            $this->scheduleOrders($orderDetails);
        }

        //This below code is used for processing the autolikes orders.
        $whereOrderStatus = [
            'rawQuery' => 'start_time < ? and orders.status=? and cronjob_status=? and auto_order_status=?',
            'bindParams' => [time(), 0, 0, 1]
        ];
        $orderDetails = $orderModel->getOrderHistory($whereOrderStatus);
        if (!empty($orderDetails) || intval($orderDetails) != 0) {
            $this->scheduleAutolikesOrders($orderDetails);
        }


    }

//Process order: Pick up the latest orders and schedule placing orders in to server based on time-interval
    public function scheduleOrders($orderDetails)
    {
        $orderModel = new Order();
        $objUsersmetaModel = new Usersmeta();
        $objCommentModel = new Comment();
        $objIinstagramAPI = new API\InstagramAPI\Instagram();
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();
        foreach ($orderDetails as $order) {
            $whereOrderStatus = [
                'rawQuery' => 'order_id=?',
                'bindParams' => [intval($order->order_id)]
            ];
            $queryResult = $orderModel->updateOrder($whereOrderStatus, ['cronjob_status' => 1]); // replace with 1
        }

        foreach ($orderDetails as $order) {
            $whereOrderStatus = [
                'rawQuery' => 'order_id=?',
                'bindParams' => [intval($order->order_id)]
            ];

            $userProfileData = '';
            $orderProcessingMessage = 'This order is in process. Please wait for 5 minute';
            //0= postlink; 1= profilelink
            if ($order->url_type == 0) {
                $orderLink = 'postLink'; // TODO scrapping here cal instagram API using post link id and fetch complete details of post link
                $temp = explode('/', $order->ins_url);

//                $instagramMediaShortcode = 'BB19YjwEDHw';
                $instagramMediaShortcode = $temp[4];
                $result = $objIinstagramAPI->getMediaDetailsByShortcode($instagramMediaShortcode);

                //store initial details of given link (current likes, total followers and username) in order table
                $data['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                $data['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                $data['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                $data['order_message'] = $orderProcessingMessage;
                $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $data);
            } else {
                $orderLink = 'profileLink'; //TODO scrapping here fetch latest given post link and details
                $numberOfLatestPostCount = intval($order->end_index);

                $temp = explode('/', $order->ins_url);
//                dd($temp[3]);
//                $instagramUsername = 'guiltytrips';
//                $instagramUsername = 'liveajayyadav';
                $instagramUsername = $temp[3];

//                $result = $instagramScrape->isUserFound($instagramUsername);
//                dd($result);


                $result = $objIinstagramAPI->getUserDetailsByUsername($instagramUsername, $numberOfLatestPostCount);
                $userProfileData = (isset($result['instagramUsersData'])) ? $result['instagramUsersData'] : '';
//                dd($result);
//                $userProfileData = $instagramScrape->getInsUserDetailsByUsername($instagramUsername,$numberOfLatestPostCount);

                $data['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                $data['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                $data['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                $data['order_message'] = $orderProcessingMessage;
                $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $data);
            }
//            dd($userProfileData);
            $quantityTotal = intval($order->quantity_total);
            $minQuantity = intval($order->min_quantity);
            $startTime = intval($order->start_time);
            $amountPerRun = (intval($order->orders_per_run) > 0) ? $order->orders_per_run : 100;
            $timeInterval = (intval($order->time_interval) > 0) ? $order->time_interval : 600;
            $userData = [];

            $planType = intval($order->plan_type);
            if ($planType == 0) { // for likes

                if (intval($order->url_type) == 0) { //0= postlink;
                    $tempQuantityTotal = $quantityTotal;
                    while (($tempQuantityTotal - $amountPerRun) >= $minQuantity) {
                        // insert order in to process order table
                        $this->addScheduleOrderToProcessOrder(
                            $order->order_id,
                            $order->supplier_server_id,
                            $order->plan_name_code,
                            $order->plan_type,
                            $order->ins_url,
                            $amountPerRun,
                            $startTime
                        );
                        $tempQuantityTotal = $tempQuantityTotal - $amountPerRun;
                        $startTime = $startTime + $timeInterval;
                    }
                    if ($tempQuantityTotal >= $minQuantity) {
                        $this->addScheduleOrderToProcessOrder(
                            $order->order_id,
                            $order->supplier_server_id,
                            $order->plan_name_code,
                            $order->plan_type,
                            $order->ins_url,
                            $tempQuantityTotal,
                            $startTime
                        );
                    }
                    $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0]);
                } else if (intval($order->url_type) == 1) { // 1= profilelink
                    $latestPostCount = count($userProfileData);
                    $startPicIndex = intval($order->start_index);
                    $endPicIndex = intval($order->end_index);
                    $numberOfPost = $endPicIndex - $startPicIndex + 1;

                    if ($latestPostCount == $endPicIndex) {
                        //if order_per_run and timeInterval is not set

                        if ((intval($order->orders_per_run) == 0) && ((intval($order->time_interval) == 0))) {
                            $perPostLike = array();
                            $tempQuantityTotal = $quantityTotal;
                            for ($i = $numberOfPost; $i > 0; $i--) {
                                $temp = intval(ceil($tempQuantityTotal / $i));
                                $perPostLike[] = $temp;
                                $tempQuantityTotal -= $temp;
                            }

                            for ($i = $startPicIndex - 1, $j = 0; $i < $endPicIndex; $i++, $j++) {
                                $this->addScheduleOrderToProcessOrder(
                                    $order->order_id,
                                    $order->supplier_server_id,
                                    $order->plan_name_code,
                                    $order->plan_type,
                                    $userProfileData[$i]['link'],
                                    $perPostLike[$j],
                                    $startTime
                                );
                            }
                        } else {

                            //TODO Spread amount between given pics range.
                            $userProfileData = array_slice($userProfileData, $startPicIndex - 1, $numberOfPost);
                            //divide the total amount into number of sub amounts
                            $amountOfLikesPerRun = array();
                            $tempQuantityTotal = $quantityTotal;
                            while (($tempQuantityTotal - $amountPerRun) >= $amountPerRun) {
                                $amountOfLikesPerRun[] = $amountPerRun;
                                $tempQuantityTotal -= $amountPerRun;
                            }
                            if ($tempQuantityTotal >= $amountPerRun) {
                                $amountOfLikesPerRun[] = $tempQuantityTotal;
                            }


                            $startTimeForSubOrder = $startTime;
                            foreach ($amountOfLikesPerRun as $amount) {
                                $perPostLike = array();
                                $tempQuantityTotal = $amount;
                                for ($i = $numberOfPost; $i > 0; $i--) {
                                    $temp = intval(ceil($tempQuantityTotal / $i));
                                    $perPostLike[] = $temp;
                                    $tempQuantityTotal -= $temp;
                                }
//                                dd($perPostLike);
                                //place order in process table
                                for ($i = 0; $i < $numberOfPost; $i++) {
                                    $this->addScheduleOrderToProcessOrder(
                                        $order->order_id,
                                        $order->supplier_server_id,
                                        $order->plan_name_code,
                                        $order->plan_type,
                                        $userProfileData[$i]['link'],
                                        $perPostLike[$i],
                                        $startTimeForSubOrder
                                    );
                                }
                                $startTimeForSubOrder += $timeInterval;
                            }
                        }
                    } else { //cancel order and refund amount back
                        $OrderData['order_message'] = 'This order has cancelled !. And Money ( $ ' . $order->price . ') is refunded back due to less number of post in instagram user profile.';
                        $OrderData['status'] = 5;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $OrderData);
                        $oldAccountBal = $objUsersmetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$order->by_user_id]], ['account_bal']);
                        $newAccountBal = $oldAccountBal->account_bal + $order->price;
                        $queryResult = $objUsersmetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$order->by_user_id]], ['account_bal' => $newAccountBal]);
//                        dd("less number of post so the order is cancel.");
                    }

                    $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0]);
                }

            } else if ($planType == 1) { // for followers
                $tempQuantityTotal = $quantityTotal;
                while (($tempQuantityTotal - $amountPerRun) >= $minQuantity) {
                    // insert order in to process order table
                    $this->addScheduleOrderToProcessOrder(
                        $order->order_id,
                        $order->supplier_server_id,
                        $order->plan_name_code,
                        $order->plan_type,
                        $order->ins_url,
                        $amountPerRun,
                        $startTime
                    );
                    $tempQuantityTotal = $tempQuantityTotal - $amountPerRun;
                    $startTime = $startTime + $timeInterval;
                }
                if ($tempQuantityTotal >= $minQuantity) {
                    $this->addScheduleOrderToProcessOrder(
                        $order->order_id,
                        $order->supplier_server_id,
                        $order->plan_name_code,
                        $order->plan_type,
                        $order->ins_url,
                        $tempQuantityTotal,
                        $startTime
                    );
                }
                $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0]);

            } else if ($planType == 2) { //random comments

                $tempQuantityTotal = $quantityTotal;

                while (($tempQuantityTotal - $amountPerRun) >= $minQuantity) {
                    $this->addScheduleOrderToProcessOrder(
                        $order->order_id,
                        $order->supplier_server_id,
                        $order->plan_name_code,
                        $order->plan_type,
                        $order->ins_url,
                        $amountPerRun,
                        $startTime
                    );
                    $tempQuantityTotal = $tempQuantityTotal - $amountPerRun;
                    $startTime = $startTime + $timeInterval;
                }
                if ($tempQuantityTotal >= $minQuantity) {
                    $this->addScheduleOrderToProcessOrder(
                        $order->order_id,
                        $order->supplier_server_id,
                        $order->plan_name_code,
                        $order->plan_type,
                        $order->ins_url,
                        $tempQuantityTotal,
                        $startTime
                    );
                }
                $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0]);

            } else if ($planType == 3) { // custom comments

                $whereCommentId = ['rawQuery' => 'comment_id=?', 'bindParams' => [$order->comment_id]];
                $commentListData = $objCommentModel->getCommentList($whereCommentId, ['comments']);
                $customCommentlist = json_decode($commentListData->comments, true);
                $commentFilterListData = array();

                for ($i = 0; $i < $quantityTotal; $i++) {
                    $commentFilterListData[] = $customCommentlist[rand(0, (count($customCommentlist) - 1))];
                }

                $tempQuantityTotal = $quantityTotal;
                while (($tempQuantityTotal - $amountPerRun) >= $minQuantity) {
                    // insert order in to process order table
                    $commentList = json_encode(array_slice($commentFilterListData, 0, $amountPerRun), true);
                    $commentFilterListData = array_slice($commentFilterListData, $amountPerRun);
                    $this->addScheduleOrderToProcessOrder(
                        $order->order_id,
                        $order->supplier_server_id,
                        $order->plan_name_code,
                        $order->plan_type,
                        $order->ins_url,
                        $amountPerRun,
                        $startTime,
                        $commentList
                    );
                    $tempQuantityTotal = $tempQuantityTotal - $amountPerRun;
                    $startTime = $startTime + $timeInterval;
                }
                if ($tempQuantityTotal >= $minQuantity) {
                    $commentList = json_encode($commentFilterListData, true);
                    $this->addScheduleOrderToProcessOrder(
                        $order->order_id,
                        $order->supplier_server_id,
                        $order->plan_name_code,
                        $order->plan_type,
                        $order->ins_url,
                        $tempQuantityTotal,
                        $startTime,
                        $commentList
                    );
                }
                $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0]);

            }

        }//End of Second inner foreach loop
    }// End of function scheduleOrders

    public function scheduleAutolikesOrders($orderDetails) //TODO incomplete
    {
        $orderModel = new Order();
        $objCommentModel = new Comment();
        $objIinstagramAPI = new API\InstagramAPI\Instagram();

        foreach ($orderDetails as $autolikesOrder) {
            $whereOrderStatus = [
                'rawQuery' => 'order_id=?',
                'bindParams' => [$autolikesOrder->order_id]
            ];
            $queryResult = $orderModel->updateOrder($whereOrderStatus, ['cronjob_status' => 0]); // replace with 1
        }


        foreach ($orderDetails as $autolikesOrder) {

            $instagramMediaShortcode = explode('/', $autolikesOrder->ins_url);
            $result = $objIinstagramAPI->getMediaDetailsByShortcode($instagramMediaShortcode[4]);

            //store initial details of given link (current likes, total followers and username) in order table
            $data1['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
            $data1['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
            $data1['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
            $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);

            if ($autolikesOrder->plan_type == 0) {
                $this->addScheduleOrderToProcessOrder(
                    $autolikesOrder->order_id,
                    $autolikesOrder->supplier_server_id,
                    $autolikesOrder->plan_name_code,
                    $autolikesOrder->plan_type,
                    $autolikesOrder->ins_url,
                    $autolikesOrder->quantity_total,
                    $autolikesOrder->start_time
                );
            } elseif ($autolikesOrder->plan_type == 2) {

            } elseif ($autolikesOrder->plan_type == 3) {

            }


//            dd($autolikesOrder);
        }
//        dd($orderDetails);

    }

    public function addScheduleOrderToProcessOrder($orderID, $supplierServerId, $planNameCode, $planType, $insUrl, $quantityTotal, $startTime, $commentList = '')
    {
        $processOrderModel = new Process_Order();
        $userData['parent_order_id'] = $orderID;
        $userData['supplier_server_id'] = $supplierServerId;
        $userData['plan_name_code'] = $planNameCode;
        $userData['plan_type'] = $planType;
        $userData['ins_url'] = $insUrl;
        $userData['quantity_total'] = $quantityTotal;
        $userData['start_time'] = intval($startTime);
        $userData['updated_time'] = time();
        $userData['process_order_status'] = 0;
        if (isset($commentList)) {
            $userData['comments'] = $commentList;
        }
        $queryResult = $processOrderModel->insertProcessOrder($userData);

    }

    public function addProcessOrdersToServerCronJob()
    {
        $processOrderModel = new Process_Order();
        $whereProcessOrder = [
            'rawQuery' => 'start_time<? and process_order_status=? and cronjob_status=?',
            'bindParams' => [time(), 0, 0]
        ];
        $orderDetails = $processOrderModel->getProcessOrders($whereProcessOrder);
        if (!empty($orderDetails) || intval($orderDetails) != 0) {
            $this->processOrders($orderDetails);
        }
    }

    public function processOrders($orderDetails)
    {
        $objOrderModel = new Order();
        $objProcessOrderModel = new Process_Order();

        foreach ($orderDetails as $order) {
            $whereProcessOrderId = [
                'rawQuery' => 'process_order_id=?',
                'bindParams' => [intval($order->process_order_id)]
            ];
            $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderId, ['cronjob_status' => 0]); // replace with cronjob_status=1
        }

        foreach ($orderDetails as $order) {

            if ($order->supplier_server_id == 1) {  //process order for igerslike API
                $url = $order->ins_url;
                $type = $order->plan_name_code;
                $amount = intval($order->quantity_total);
                $comments_data = array();
                $commentFlag = false;
                if (isset($order->comments) && ($order->comments != null || $order->comments != '')) {
                    $comments_data = json_decode($order->comments, true);
                    $comments_data = implode("\\r\\n", $comments_data);
                    $commentFlag = true;
                }

                $objIgersLike = new API\IgersLike();
                if ($commentFlag) {
                    $result = $objIgersLike->order_add($url, $type, $amount, $comments_data);
                } else {
                    $result = $objIgersLike->order_add($url, $type, $amount);
                }

                $result = json_decode($result, true);
                if ($result['status'] == 'ok') {
                    //update process_orders table
                    $whereProcessOrderId = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [intval($order->process_order_id)]
                    ];
                    $data = array(
                        'server_order_id' => $result['order'],
                        'updated_time' => time(),
                        'process_order_status' => 1,
                        'cronjob_status' => 0
                    );
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderId, $data);

                    //update orders table
                    $whereOrderStatus = [
                        'rawQuery' => 'order_id=?',
                        'bindParams' => [intval($order->parent_order_id)]
                    ];
                    $data = array(
                        'status' => 1,
                        'updated_time' => time(),
                        'cronjob_status' => 0
                    );
                    $queryResult = $objOrderModel->updateOrder($whereOrderStatus, $data);
//                    echo "ok", "\n";
                } else if ($result['status'] == 'fail') {
                    $whereProcessOrderStatus = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$order->process_order_id]
                    ];
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderStatus, ['cronjob_status' => 0]);
//                    echo "fail", "\n";
                }
            }

            if ($order->supplier_server_id == 2) {
                //process order for cheapbulk API
                $url = $order->ins_url;
                $type = $order->plan_name_code;
                $amount = $order->quantity_total;

                $objCheapBulkSocial = new API\CheapBulkSocial();
                $result = $objCheapBulkSocial->order_add($url, $type, $amount);
                $result = json_decode($result, true);

                if ($result['status_code'] == 1) {
                    $tempStr = explode(':', $result['status_message']);
                    $order_id = trim(substr($tempStr[1], 0, -1), ' ');
                    $order_id = intval($order_id);

                    //update process_orders table
                    $whereProcessOrderId = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$order->process_order_id]
                    ];
                    $data = array(
                        'server_order_id' => $order_id,
                        'updated_time' => time(),
                        'process_order_status' => 1,
                        'cronjob_status' => 0
                    );
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderId, $data);

                    //update orders table
                    $whereOrderStatus = [
                        'rawQuery' => 'order_id=?',
                        'bindParams' => [$order->parent_order_id]
                    ];
                    $data = array(
                        'status' => 1,
                        'updated_time' => time(),
                        'cronjob_status' => 0

                    );
                    $queryResult = $objOrderModel->updateOrder($whereOrderStatus, $data);
//                    echo "ok", "\n";
                } else if ($result['status_code'] == 0) {
                    $whereProcessOrderStatus = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$order->process_order_id]
                    ];
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderStatus, ['cronjob_status' => 0]);
//                    echo "fail", "\n";
                } else {
                    $whereProcessOrderStatus = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$order->process_order_id]
                    ];
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderStatus, ['cronjob_status' => 0]);
                }
            }

            if ($order->supplier_server_id == 3) {
                //process order for sociL panel 24 API

                $url = $order->ins_url;
                $type = $order->plan_name_code;
                $amount = $order->quantity_total;
//                $url = 'https://www.instagram.com/p/BB19YjwEDHw/';
//                $type = 74000000;
//                $amount = 50;
                $objSocialPanel24 = new API\SocialPanel24();
                $result = $objSocialPanel24->order_add($url, $type, $amount);
                $result = json_decode($result, true);

                if (current(array_keys($result, true)) == 'id') {
                    //update process_orders table
                    $whereProcessOrderId = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$order->process_order_id]
                    ];
                    $data = array(
                        'server_order_id' => $result['id'],
                        'updated_time' => time(),
                        'process_order_status' => 1,
                        'cronjob_status' => 0
                    );
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderId, $data);

                    //update orders table
                    $whereOrderStatus = [
                        'rawQuery' => 'order_id=?',
                        'bindParams' => [$order->parent_order_id],
                    ];
                    $data = array(
                        'status' => 1,
                        'updated_time' => time(),
                        'cronjob_status' => 0
                    );
                    $queryResult = $objOrderModel->updateOrder($whereOrderStatus, $data);
//                    echo "ok", "\n";

                } else if (current(array_keys($result, true)) == 'error') {
                    $whereProcessOrderStatus = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$order->process_order_id]
                    ];
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderStatus, ['cronjob_status' => 0]);
//                    echo "error", "\n";
                } else {
                    $whereProcessOrderStatus = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$order->process_order_id]
                    ];
                    $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderStatus, ['cronjob_status' => 0]);
                }

            }
        }
    }

    public function updateOrderStatusCronJob()
    {
        $orderModel = new Order();
        $whereOrderStatus = [
            'rawQuery' => '(orders.status=? or orders.status=?) and orders.cronjob_status=?',
            'bindParams' => [1, 2, 0]
        ];

        $orderList = $orderModel->getOrderStatus($whereOrderStatus, ['orders.order_id', 'orders.server_order_id', 'orders.status', 'supplier_servers.supplier_name', 'plans.supplier_server_id']);
        if (!empty($orderList) || intval($orderList) != 0) {
            $this->checkOrderStatus($orderList);
        }
    }

    public function checkOrderStatus($orderList)
    {
        $orderModel = new Order();
        $objProcessOrderModel = new Process_Order();
        foreach ($orderList as $order) {
            $whereOrderStatus = [
                'rawQuery' => 'order_id=?',
                'bindParams' => [intval($order->order_id)]
            ];
            $orderStatus = $orderModel->updateOrder($whereOrderStatus, ['cronjob_status' => 1]);
        }

        foreach ($orderList as $order) {

            $whereOrder = ['rawQuery' => 'order_id=?', 'bindParams' => [intval($order->order_id)]];
            $whereProcessOrder = ['rawQuery' => 'parent_order_id=?', 'bindParams' => [intval($order->order_id)]];
            $processOrderList = $objProcessOrderModel->getProcessOrders($whereProcessOrder, ['quantity_total', 'process_order_status']);
            $processOrderList = ($processOrderList) ? $processOrderList : array();
            $orderStatus = '';
            if (isset($processOrderList) && $processOrderList != 0) {

                $customProcessOrderMessage = array(
                    0 => 'Order has inserted! Please wait 5 minutes to get it started!',
                    1 => 'This order is in process. Please wait 5 minutes to finishe it.',
                    2 => 'This order is in process. Please wait 5 minutes to finishe it.',
                    3 => 'This order is completed!. Thank you.',
                    4 => 'This order is failed!, due to some error in service!.Money is refunded back',
                    5 => 'This order has cancelled!, due to some error in service!.Money is refunded back',
                    6 => 'This order has cancelled!, due to some error in service!.Money is refunded back',
                );

                //check if all order status are same or not
                $quantityDoneCount = 0;
                $orderStatusList = array();
                foreach ($processOrderList as $processOrder) {
                    $orderStatusList[] = $processOrder->process_order_status;
                    if (intval($processOrder->process_order_status) === 3) {
                        $quantityDoneCount += intval($processOrder->quantity_total);
                    }
                }


                $updateOrderData['quantity_done'] = intval($quantityDoneCount);
                $updateOrderData['cronjob_status'] = 0;

                if ($this->is_unique_array($orderStatusList)) {
                    $orderStatus = $orderStatusList[0];
                    if ($orderStatus != 0) {
                        $updateOrderData['order_message'] = $customProcessOrderMessage[$orderStatus];
                        $updateOrderData['status'] = intval($orderStatus);
                        $updateOrderData['updated_time'] = time();
                    }
                } else {
                    //check if any orders status list contain 2
                    foreach ($orderStatusList as $orderStatus) {
                        if ($orderStatus == 1 || $orderStatus == 2 || $orderStatus == 3) {
                            $updateOrderData['order_message'] = $customProcessOrderMessage[2];
                            $updateOrderData['status'] = 2;
                            $updateOrderData['updated_time'] = time();
                            break;
                        }
                    }
//                    $queryResult = $orderModel->updateOrder($where, ['quantity_done' => intval($quantityDoneCount), 'status' => 2, 'updated_time' => time(), 'cronjob_status' => 0]);
                }
                $queryResult = $orderModel->updateOrder($whereOrder, $updateOrderData);

            } else {
                $queryResult = $orderModel->updateOrder($whereOrder, ['cronjob_status' => 0]);
            }
        }
    }

    public function is_unique_array($array)
    {
        $firstElement = $array[0];
        foreach ($array as $element) {
            if ($firstElement != $element) {
                return false;
            }
        }
        return true;
    }

    public function updateProcesOrderStatusCronJob()
    {
        $objProcessOrderModel = new Process_Order();
        $whereOrderStatus = [
            'rawQuery' => '(process_order_status=? or process_order_status=?) and cronjob_status=?',
            'bindParams' => [1, 2, 0]
        ];
        $processOrderList = $objProcessOrderModel->getProcessOrderStatus($whereOrderStatus);

        if (!empty($processOrderList) || $processOrderList != 0) {
            $this->checkProcessOrderStatus($processOrderList);
        }
    }

    public function checkProcessOrderStatus($processOrderList)
    {
        $objProcessOrderModel = new Process_Order();
        $objIgersLike = new API\IgersLike();
        $objCheapBulkSocial = new API\CheapBulkSocial();
        $objSocialPanel24 = new API\SocialPanel24();

        foreach ($processOrderList as $processOrder) {
            $whereOrderStatus = [
                'rawQuery' => 'process_order_status=?',
                'bindParams' => [$processOrder->process_order_status]
            ];
            $processOrderStatus = $objProcessOrderModel->updateProcessOrder($whereOrderStatus, ['cronjob_status' => 1]);// replace with 1
        }
//        dd($processOrderList);
        foreach ($processOrderList as $processOrder) {
            if ($processOrder->supplier_server_id == 1) {
                //process order status for igerslike API

                $orderId = $processOrder->server_order_id;
                $result = $objIgersLike->order_status($orderId);
                $result = json_decode($result, true);

                if (!empty($result) || $result != '' || $result != null) {
                    $orderStatus = "";
                    $where = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$processOrder->process_order_id]
                    ];
                    if ($result['status'] == 'ok') {
                        switch ($result['order_status']) {
                            case 'Pending':
                                $orderStatus = 1;
                                break;
                            case 'Processing':
                                $orderStatus = 2;
                                break;
                            case 'Completed':
                                $orderStatus = 3;
                                break;
                            case 'Refunded':
                                $orderStatus = 5;
                                break;
                            case 'Refunded Partial':
                                $orderStatus = 5;
                                break;
                            default:
                                break;
                        }
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['updated_time' => time(), 'process_order_status' => $orderStatus, 'cronjob_status' => 0]);
                    } else if ($result['status'] == 'fail') {
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                    }
                }
            } else if ($processOrder->supplier_server_id == 2) {
                //process order for cheapbulk API
                $orderId = $processOrder->server_order_id;
                $result = $objCheapBulkSocial->order_status($orderId);
                $result = json_decode($result, true);

                if (isset($result['status_code'])) {
                    $where = [
                        'rawQuery' => 'process_order_id=?',
                        'bindParams' => [$processOrder->process_order_id]
                    ];
                    if ($result['status_code'] == 1) {
                        $orderStatus = "";
                        switch ($result['status_message']) {
                            case 'Processing':
                                $orderStatus = 2;
                                break;
                            case 'Completed':
                                $orderStatus = 3;
                                break;
                            default :
                                break;
                        }
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['updated_time' => time(), 'process_order_status' => $orderStatus, 'cronjob_status' => 0]);
                    } else {
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                    }
                }
            } else if ($processOrder->supplier_server_id == 3) {
                //process order for sociL panel 24 API
                $result = $objSocialPanel24->order_status($processOrder->server_order_id);
                $result = json_decode($result, true);
                $orderStatus = "";
                $where = [
                    'rawQuery' => 'process_order_id=?',
                    'bindParams' => [$processOrder->process_order_id]
                ];
                if (isset($result['status'])) {
                    if ($result['status'] != '' || $result['status'] != NULL) {
                        //0 = Pending, 1 = In progress, 2 = Completed, 3 = Partial, 4 = Canceled, 5 = Processing
                        switch ($result['status']) {
                            case 0:
                                $orderStatus = 1;
                                break;
                            case 1:
                                $orderStatus = 1;
                                break;
                            case 2:
                                $orderStatus = 3;
                                break;
                            case 3:
                                $orderStatus = 2;
                                break;
                            case 4:
                                $orderStatus = 6;
                                break;
                            case 5:
                                $orderStatus = 2;
                                break;
                            default:
                                break;
                        }
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['updated_time' => time(), 'process_order_status' => $orderStatus, 'cronjob_status' => 0]);
                    }
                } else {
                    $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                }
            }
        }
    }

    public function http_get($url)
    {
        $response = new stdClass();
        // echo "<pre>";print_r($url);die();
        if (empty($url)) {
            $response->code = 198;
            $response->message = 'Parameter not Passed';
            return $response;
        }

        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
        // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }

    public function autolikeOrderHistoryAjax(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objInstagramUserModel = new Instagram_User();
            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : '';

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['user_id' => 'required']);
                if (!$validator->fails()) {

                    $requestParam = json_decode($postData['requestData'], true);
                    $iTotalRecords = $iDisplayLength = intval($requestParam['length']);
                    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                    $iDisplayStart = intval($requestParam['start']);
                    $sEcho = intval($requestParam['draw']);

                    $whereUserById = [
                        'rawQuery' => 'by_user_id=?',
                        'bindParams' => [$userId]
                    ];

                    //GET TOTAL NUMBER OF NEW ORDERS
                    $iTotalRecords = count($objInstagramUserModel->getInsUserAutolikesOrderHistory($whereUserById));
                    $iTotalFilteredRecords = $iTotalRecords;

                    $records = array();
                    $records["data"] = array();

                    $columns = array(
                        'instagram_users.ins_user_id',
                        'instagram_users.ins_username',
                        'instagram_users.pics_done',
                        'instagram_users.pics_limit',
                        'instagram_users.likes_per_pic',
                        'instagram_users.last_check',
                        'instagram_users.last_delivery',
                        'instagram_users.ig_user_status'
                    );
                    $sortingOrder = "";
                    if (isset($requestParam['order'])) {
                        $sortingOrder = [$columns[$requestParam['order'][0]['column'] - 1], $requestParam['order'][0]['dir']];
                    }

                    //group action perform here
                    if (isset($requestParam["customActionType"]) && $requestParam["customActionType"] == "group_action") {
                        if ($requestParam['customActionName'] != '' && !empty($requestParam['insUserId'])) {
                            $insUsersId = $requestParam['insUserId'];

                            if ($requestParam['customActionName'] == 'remove_user') {
                                //cancel the order(s)

                                $messages = array();
                                foreach ($insUsersId as $key => $insUserId) {


//first check if ins user order details is present in order table or if it exist then details that details in both table.

                                    //TODO
                                    $queryResult = $objInstagramUserModel->deleteInsUser(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]]);

                                    if ($queryResult) {
                                        $messages[$key] = 'Instagram user ID #' . $insUserId . ' record deleted successfull';
                                    } else {
                                        $messages[$key] = 'There is an problem in deleting this user ID#' . $insUserId;
                                    }
                                }
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            } else if ($requestParam['customActionName'] == 'restart_user') {
                                //reset all the setting for this user

                                $messages = array();
                                foreach ($insUsersId as $key => $insUserId) {
                                    $queryResult = $objInstagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['pics_done' => 0, 'daily_post_done' => 0]);
                                    $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['ins_username']);

                                    if ($queryResult) {
                                        $messages[$key] = 'Done! We have restarted this User # ' . $instagramUserDetails[0]->ins_username . ' from Auto Likes';
                                    }

//                                    $response->code = 200;
//                                    $response->message = "Success";
//                                    $response->data = $instagramUserDetails[0]->ins_username;
//                                    echo json_encode($response, true);die;

                                }
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            } else if ($requestParam['customActionName'] == 'check_user') {
                                $messages = array();
                                foreach ($insUsersId as $key => $insUserId) {

                                    //TODO force to check for new post for the this user. integrate instagram API/ do scrap here

                                }
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            }
                        }
                    }


                    //FIRLTERING START FROM HERE
                    $filteringRules = '';
                    if (isset($requestParam['action']) && $requestParam['action'] == 'filter' && $requestParam['action'][0] != 'filter_cancel') {
                        if ($requestParam['search_id'] != '') {
                            $filteringRules[] = "( instagram_users.ins_user_id LIKE '%" . $requestParam['search_id'] . "%' )";
                        }
                        if ($requestParam['search_username'] != '') {
                            $filteringRules[] = "( instagram_users.ins_username LIKE '%" . $requestParam['search_username'] . "%' )";
                        }
                        if ($requestParam['search_pics_done'] != '') {
                            $filteringRules[] = "( instagram_users.pics_done LIKE '%" . $requestParam['search_pics_done'] . "%' )";
                        }
                        if ($requestParam['search_pics_limit'] != '') {
                            $filteringRules[] = "( instagram_users.pics_limit LIKE '%" . $requestParam['search_pics_limit'] . "%' )";
                        }
                        if ($requestParam['search_pics_likes'] != '') {
                            $filteringRules[] = "( instagram_users.likes_per_pic LIKE '%" . $requestParam['search_pics_likes'] . "%' )";
                        }
                        if ($requestParam['search_last_check'] != '') {
                            $filteringRules[] = "( instagram_users.last_check LIKE '%" . $requestParam['search_last_check'] . "%' )";
                        }
                        if ($requestParam['search_last_delivery'] != '') {
                            $filteringRules[] = "( instagram_users.last_delivery LIKE '%" . $requestParam['search_last_delivery'] . "%' )";
                        }
                        if ($requestParam['search_status'] != '') {
                            $filteringRules[] = "( instagram_users.ig_user_status LIKE '%" . $requestParam['search_status'] . "%' )";
                        }
                        if (!empty($filteringRules)) {
                            $whereUserById['rawQuery'] .= " AND " . implode(" AND ", $filteringRules);
//                            $iTotalFilteredRecords = count($objInstagramUserModel->getInsUserAutolikesOrderHistory($whereUserById));
                        }
                    }

                    $result = $objInstagramUserModel->getAllFilterUsers($whereUserById, $sortingOrder, $iDisplayStart, $iDisplayLength);

                    if ($result != 2) {
                        if ($result == 0) {
                            $iTotalFilteredRecords = 0;
                            $records['data'] = array();
                        } else {
                            $iTotalFilteredRecords = count($result);
                            foreach ($result as $user) {
                                $user->last_check = ($user->last_check != 0) ? $this->getDateDifference($user->last_check) : '-';
                                $user->last_delivery = ($user->last_delivery != 0) ? $this->getDateDifference($user->last_delivery) : '-';
                            }
                            $records['data'] = $result;
                        }

//                        $response->code = 200;
//                        $response->message = "Success";
//                        $response->data = $iTotalFilteredRecords;
//                        echo json_encode($response, true);
//                        die;

                        $records["draw"] = $sEcho;
                        $records["recordsTotal"] = $iTotalRecords;
                        $records["recordsFiltered"] = $iTotalFilteredRecords;

                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $records;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 401;
                        $response->message = "Error in connection please try again.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function getUserPreviousDetails(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objInstagramUserModel = new Instagram_User();
            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : '';

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['ins_user_id' => 'required']);
                if (!$validator->fails()) {
                    $where = [
                        'rawQuery' => 'ins_user_id=?',
                        'bindParams' => [$postData['ins_user_id']]
                    ];
                    $selectColumn = [
                        'instagram_users.ins_username',
                        'instagram_users.likes_per_pic',
                        'instagram_users.pics_limit',
                        'instagram_users.plan_id',
                        'plans.plan_name',
                        'instagram_users.plan_id_for_auto_comment',
                        'instagram_users.custom_comment_group_id',
                        'instagram_users.comments_amount'
                    ];

                    $userCredentials = $objInstagramUserModel->getUserDetails($where, $selectColumn);

                    $response->code = 200;
                    $response->message = "Success";
                    $response->data = $userCredentials;
                    echo json_encode($response, true);
                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function orderHistoryAjax(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objOrderModel = new Order();
            $objUsersmetaModel = new Usersmeta();

            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : '';

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['user_id' => 'required']);
                if (!$validator->fails()) {
                    $requestParam = json_decode($postData['requestData'], true);
                    $iTotalRecords = $iDisplayLength = intval($requestParam['length']);
                    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                    $iDisplayStart = intval($requestParam['start']);
                    $sEcho = intval($requestParam['draw']);

                    $whereOderUserID = [
                        'rawQuery' => 'by_user_id=?',// and orders.status!=6',
                        'bindParams' => [$userId]
                    ];
                    $data = [
                        'orders.order_id', 'orders.server_order_id', 'orders.ins_url', 'orders.quantity_total', 'orders.price',
                        'orders.quantity_done', 'orders.status', 'orders.added_time', 'orders.updated_time',
                        'plans.plan_name', 'plans.supplier_server_id'
                    ];
                    //GET TOTAL NUMBER OF NEW ORDERS
                    $iTotalRecords = count($objOrderModel->getOrderHistory($whereOderUserID, $data));
                    $iTotalFilteredRecords = $iTotalRecords;
                    $records = array();
                    $records["data"] = array();

                    $columns = array('orders.order_id', 'plans.plan_name', 'orders.ins_url', 'orders.quantity_total', 'orders.price', 'orders.added_time', 'orders.updated_time', 'orders.status');
                    $sortingOrder = "";
                    if (isset($requestParam['order'])) {
                        $sortingOrder = [$columns[$requestParam['order'][0]['column'] - 1], $requestParam['order'][0]['dir']];
                    }


                    //group action perform here
                    if (isset($requestParam["customActionType"]) && $requestParam["customActionType"] == "group_action") {
                        if ($requestParam['customActionName'] != '' && !empty($requestParam['orderId'])) {
                            $orderId = $requestParam['orderId'];

                            if ($requestParam['customActionName'] == 'cancel_order') {
                                //cancel the order(s)

                                $messages = array();
                                foreach ($orderId as $key => $order_id) {

                                    $orderStatus = $objOrderModel->getOrderStatus(['rawQuery' => 'order_id=?', 'bindParams' => [$order_id]], ['orders.status', 'orders.by_user_id', 'orders.price']);

                                    if ($orderStatus) {
                                        if ($orderStatus[0]->status == 0) {
                                            $rollback = false;
                                            DB::beginTransaction();
                                            DB::table('usersmeta')->where('user_id', '=', $orderStatus[0]->by_user_id)->lockForUpdate()->get();
                                            $oldAccountBal = $objUsersmetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$orderStatus[0]->by_user_id]], ['account_bal']);
                                            $newAccountBal = $oldAccountBal->account_bal + $orderStatus[0]->price;

                                            $queryResult = $objUsersmetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$orderStatus[0]->by_user_id]], ['account_bal' => $newAccountBal]);

                                            if ($queryResult) {
                                                $result = $objOrderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order_id]], ['status' => 6]);
                                                DB::commit();
                                            } else {
                                                $rollback = true;
                                                DB::rollBack();
                                            }
                                            if (!$rollback) {
                                                $messages[$key] = "This order is now canceled and the money is deposited back in your account, order_id :" . $order_id . "\n";
                                            } else {
                                                $messages[$key] = "There is an problem in order #ID " . $order_id . " cancellation process.\n";
                                            }
                                        } else if ($orderStatus[0]->status == 3 || $orderStatus[0]->status == 4 || $orderStatus[0]->status == 5) {
                                            $messages[$key] = "Your order with the ID #" . $order_id . " cannot be cancel because its already added on the system.\n";
                                        } else if ($orderStatus[0]->status == 6) {
                                            $messages[$key] = "Your order with the ID #" . $order_id . " is already cancelled.\n";
                                        }
                                    } else {
                                        $messages[$key] = "This order ID #" . $order_id . " is invalid \n";
                                    }
                                }

                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            }


                            if ($requestParam['customActionName'] == 'reAdd_order') {
                                //reAdd the order(s)
                                $messages = array();
                                foreach ($orderId as $key => $order_id) {

                                    $orderDetails = $objOrderModel->getOrderDetails(['rawQuery' => 'order_id=?', 'bindParams' => [$order_id]]);

                                    if ($orderDetails) {
                                        unset($orderDetails[0]->order_id);

                                        $data = "";
                                        foreach ($orderDetails[0] as $key => $value) {
                                            $data[$key] = $value;
                                        }
                                        $data['status'] = 0;
                                        $data['start_time'] = time() + 600;
                                        $data['added_time'] = time();
                                        $data['updated_time'] = time();
                                        $data['initial_likes_count'] = 0;
                                        $data['initial_followers_count'] = 0;
                                        $data['initial_comments_count'] = 0;
                                        $data['quantity_done'] = 0;
                                        $data['cronjob_status'] = 0;

                                        $data['order_message'] = 'Order has inserted! Please wait 10 minutes to get it started!';
                                        $price = $data['price'];


                                        //TODO PRODUCT LOCKING, DB NOCOMMIT IN LARAVEL
                                        $rollback = false;
                                        $successFlag = false;
                                        DB::beginTransaction();
                                        DB::table('usersmeta')->where('user_id', '=', [$postData['user_id']])->lockForUpdate()->get();

                                        $orderInsertedID = $objOrderModel->insertOrder($data);

                                        if ($orderInsertedID) {
                                            $accountBalanceDetails = $objUsersmetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$postData['user_id']]], ['account_bal']);
                                            $accountBalance = $accountBalanceDetails->account_bal;

                                            if ($accountBalance >= $price) {
                                                $current_bal['account_bal'] = $accountBalance - $price;
                                                $orderUpdateBalanceStatus = $objUsersmetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$postData['user_id']]], $current_bal);

                                                if ($orderUpdateBalanceStatus) {
                                                    $messages[] = "This order ID #" . $order_id . " is re-added successful with order ID #" . $orderInsertedID;
                                                    DB::commit();
                                                } else {
                                                    $rollback = true;
                                                    $messages[] = "Error in re-add order with order ID #" . $order_id . " Please try again after few minutes.";
                                                    DB::rollBack();
                                                }
                                            } else {
                                                $messages[] = "Insufficient Balance while adding an order.";
                                                $rollback = true;
                                                DB::rollBack();
                                            }
                                        } else {
                                            $rollback = true;
                                            $messages[] = "Error in re-add order with order ID #" . $order_id . " Please try again after few minutes.";
                                            DB::rollBack();
                                        }
                                        if ($rollback) {
                                            break;
                                        }
                                    } else {
                                        $messages[] = "This order ID #" . $order_id . " is invalid.";
                                    }
                                }

                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            }
                        }
                    }


                    //FIRLTERING START FROM HERE
                    $filteringRules = '';
                    if (isset($requestParam['action']) && $requestParam['action'] == 'filter' && $requestParam['action'][0] != 'filter_cancel') {
//                        print_r($_REQUEST);die;
                        if ($requestParam['search_order_id'] != '') {
                            $filteringRules[] = "( orders.order_id LIKE '%" . $requestParam['search_order_id'] . "%' )";
                        }
                        if ($requestParam['search_service_type'] != '') {
                            $filteringRules[] = "( plans.plan_name LIKE '%" . $requestParam['search_service_type'] . "%' )";
                        }
                        if ($requestParam['search_link'] != '') {
                            $filteringRules[] = "( orders.ins_url LIKE '%" . $requestParam['search_link'] . "%' )";
                        }
                        if ($requestParam['search_amount'] != '') {
                            $filteringRules[] = "( orders.quantity_total LIKE '%" . $requestParam['search_amount'] . "%' )";
                        }
                        if ($requestParam['search_price'] != '') {
                            $filteringRules[] = "( orders.price LIKE '%" . $requestParam['search_price'] . "%' )";
                        }
                        if ($requestParam['search_status'] != '') {
                            $filteringRules[] = "( orders.status LIKE '%" . $requestParam['search_status'] . "%' )";
                        }
                        if (!empty($filteringRules)) {
                            $whereOderUserID['rawQuery'] .= " AND " . implode(" AND ", $filteringRules);
                            $iTotalFilteredRecords = count($objOrderModel->getOrderHistory($whereOderUserID, $data));
                        }
                    }

                    $ordersResult = $objOrderModel->getAllOrders($whereOderUserID, $sortingOrder, $iDisplayStart, $iDisplayLength);

                    if ($ordersResult != 2) {
                        if ($ordersResult == 0) {
                            $iTotalFilteredRecords = 0;
                            $records['data'] = array();
                        } else {
                            $iTotalFilteredRecords = count($ordersResult);
                            foreach ($ordersResult as $order) {
                                $order->added_time = $this->getDateDifference($order->added_time);
                                $order->updated_time = $this->getDateDifference($order->updated_time);
                            }
                            $records['data'] = $ordersResult;
                        }

//                        $response->code = 200;
//                        $response->message = "Success";
//                        $response->data = $iTotalFilteredRecords;
//                        echo json_encode($response, true);
//                        die;

//


                        $records["draw"] = $sEcho;
                        $records["recordsTotal"] = $iTotalRecords;
                        $records["recordsFiltered"] = $iTotalFilteredRecords;

                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $records;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 401;
                        $response->message = "Error in connection please try again.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }

                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function pricingInformation(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objPlanModel = new Plan();

            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : '';

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $selectColumn = ['plan_name', 'min_quantity', 'max_quantity', 'charge_per_unit', 'status'];
                $plansDetailsList = $objPlanModel->getPlanPricingInfo($selectColumn);
                if ($plansDetailsList != "error") {
                    if ($plansDetailsList) {
                        $response->code = 200;
                        $response->message = "success";
                        $response->data = $plansDetailsList;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 401;
                        $response->message = "Plan list is empty";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 401;
                    $response->message = "This service is currently unavailable!  please try again after sometime";
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function autoLikesScript()
    {
        $instagramUserModel = new Instagram_User();
        $whereStatus = [
            'rawQuery' => 'start_date_time<=? and ig_user_status=? and cronjob_status=?',
            'bindParams' => [time(), 2, 0],
//            'rawQuery' => 'ig_user_status=? and cronjob_status=?',
//            'bindParams' => [2, 0]
        ];
        $userDetails = $instagramUserModel->getUserDetails($whereStatus);

        if (!empty($userDetails) || $userDetails != 0) {
            $this->checkUserProfile($userDetails);
        }
    }

    public function checkUserProfileOLD($userDetails) //TODO in-complete
    {
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();
        $instagramUserModel = new Instagram_User();
        $objUsermetaModel = new Usersmeta();
        $objOrderModel = new Order();
        foreach ($userDetails as $user) {
            $whereCronJobStatus = [
                'rawQuery' => 'ins_user_id=?',
                'bindParams' => [$user->ins_user_id]
            ];
            $queryResult = $instagramUserModel->updateUserDetails($whereCronJobStatus, ['cronjob_status' => 0]); // replace with cronjob_status=1
        }


        foreach ($userDetails as $user) {
            $username = $user->ins_username;
            $picsDone = intval($user->pics_done);
            $picLimit = intval($user->pics_limit);
            $dailyPostLimit = intval($user->daily_post_limit);
//            $dailyPostLimit =2;
            $dailyPostDone = intval($user->daily_post_done);
            $lastPostCreatedTime = intval($user->last_post_created_time);
            $lastPostDeliveryTime = intval($user->last_delivery);


            $firstPost_deliveryTime_day = intval($user->firstpost_delivery_daytime);

            if (intval($firstPost_deliveryTime_day) != 0) {
                if ((time() - $firstPost_deliveryTime_day) >= 86400) { //24 hr = 86400 seconds
                    $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                    $updatedData = ['firstpost_delivery_daytime' => time(), 'daily_post_done' => 0];
                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                }
            }


//            if (($dailyPostDone >= $dailyPostLimit)) {
            $firstPost_deliveryTime_day = intval($user->firstpost_delivery_daytime);

            if (intval($firstPost_deliveryTime_day) != 0) {
                if ((time() - $firstPost_deliveryTime_day) >= 86400) { //24 hr = 86400 seconds
                    $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                    $updatedData = ['firstpost_delivery_daytime' => time(), 'daily_post_done' => 0];
                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                }
            }
//            }


            //TODO implement

//            dd(intval($user->firstpost_delivery_daytime));


//            $lastPostDeliveryTime=1458463380;
//            $dayCount=intval(ceil((time()-$lastPostDeliveryTime)/(3600*24)));
//            echo time();


// dd($dayCount);

            if ($firstPost_deliveryTime_day == 0) {
                $data = array();
                $profilePostCount = $instagramScrape->getProfilePostCountByUsername($username);
                if ($profilePostCount != null && $profilePostCount > 0) {
                    $userDetails = $instagramScrape->getInsUserDetailsByUsername($username, 1);
                    if ($userDetails) {
                        $data['firstpost_delivery_daytime'] = $userDetails[0]['created_time'];
                        $firstPost_deliveryTime_day = $userDetails[0]['created_time'];
                    }
                } else if ($profilePostCount == 0) {
                    $data['firstpost_delivery_daytime'] = time();
                    $firstPost_deliveryTime_day = time();
                }
                $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $data);
            }

            if ($dailyPostLimit != 0) {

            }

            dd($dailyPostDone < $dailyPostLimit);
            if (($dailyPostDone < $dailyPostLimit) && ($picsDone < $picLimit)) {
                dd($dailyPostLimit);
                $userProfileData = $instagramScrape->getInsUserDetailsByUsername($username, $dailyPostLimit);
                $userProfileData = array_reverse($userProfileData);

                /*
                 * This code is used when we are using Instagram API to get the media details of instagram user.
                            $instagramUsername = 'guiltytrip';
                            $result = $objIinstagramAPI->getUserDetailsByUsername($instagramUsername, $dailyPostLimit);
                            $userProfileData = (isset($result['instagramUsersData'])) ? $result['instagramUsersData'] : '';
                            dd($userProfileData);
                */

                if ($userProfileData != null) {

                    $dayCount = ($lastPostDeliveryTime != 0) ? intval(ceil((time() - $lastPostDeliveryTime) / (3600 * 24))) : 1;

                    foreach ($userProfileData as $userMediaData) {

                        if ($lastPostCreatedTime < $userMediaData['created_time']) {

                            if ($picsDone < $picLimit) {

                                if ($picsDone < ($dailyPostLimit * $dayCount)) {

                                    if (($dailyPostDone < $dailyPostLimit)) {
                                        //add order in order table and then in order-process table


                                        //TODO Remove this below two lines
//                                        echo "<pre>";
//                                        print_r($userMediaData);
//dd("true");

                                        $price = (($user->charge_per_unit) / 1000) * $user->likes_per_pic;
                                        $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                        $accountBalance = $accountBalanceDetails->account_bal;

                                        if ($accountBalance >= $price) {
                                            $data['plan_id'] = $user->plan_id;
                                            $data['by_user_id'] = $user->by_user_id;
                                            $data['for_user_id'] = $user->ins_user_id;
                                            $data['ins_url'] = $userMediaData['link'];
                                            $data['quantity_total'] = $user->likes_per_pic;
                                            $data['start_time'] = time() + 600; //600= 10 minutes delay
                                            $data['added_time'] = time();
                                            $data['updated_time'] = time();
                                            $data['auto_order_status'] = 1;  // 1=autolikes order
                                            $data['status'] = 0;
                                            $data['price'] = $price;
                                            $data['orders_per_run'] = 0;
                                            $data['time_interval'] = 0;
                                            $data['url_type'] = 0;  // 0=postLink
                                            $data['initial_likes_count'] = $userMediaData['likes_count'];

                                            $rollback = false;

                                            DB::beginTransaction();
                                            DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();
                                            $orderInsertStatus = $objOrderModel->insertOrder($data);
                                            if ($orderInsertStatus) {
                                                $current_bal['account_bal'] = $accountBalance - $price;
                                                $orderUpdateBalanceStatus = $objUsermetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], $current_bal);
                                                if ($orderUpdateBalanceStatus) {
                                                    DB::commit();
                                                } else {
                                                    $rollback = true;
                                                    DB::rollBack();
                                                }
                                            } else {
                                                $rollback = true;
                                                DB::rollBack();
                                            }

                                            if (!$rollback) {
                                                // Update details in instagram_users table
                                                $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                                $updatedData = [
                                                    'pics_done' => ++$picsDone,
                                                    'daily_post_done' => ++$dailyPostDone,
                                                    'cronjob_status' => 0,
                                                    'last_check' => time(),
                                                    'last_delivery' => time(),
                                                    'last_delivery_link' => $userMediaData['link'],
                                                    'last_post_created_time' => $userMediaData['created_time']
                                                ];
                                                echo "<pre>";
                                                print_r($updatedData);
                                                $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);

                                                //TODO save time of first post make likes in a day in instagram_user table

                                                if (intval($picsDone - 1) == 0) {
                                                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['firstpost_delivery_daytime' => time()]);
                                                }
                                            }
                                        } else {
                                            // insert your custom message here in instagram_users table
                                            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                            $updatedData = ['cronjob_status' => 0,
                                                'message' => 'This instagram username # ' . $user->ins_username . ' autolikes script has been stopped due to insufficient balance.'
                                            ];
                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                                            break;
                                        }
                                    } else {
                                        $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                        $updatedData = ['cronjob_status' => 0,
                                            'message' => 'Profile Daily limit for this instagram user # ' . $user->ins_username . ' has been reached.' //TODO add custom message here
                                        ];
                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                                        break;
                                    }
                                }
                            } else {
                                $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                $updatedData = ['cronjob_status' => 1, 'ig_user_status' => 1,
                                    'message' => 'The order is finished, we have delivered all ' . $picLimit . ' you configured, if you wish you can just restart this task to start it again!'
                                ];
                                $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                                break;
                            }
                        }
                    } //End of Inner foreach loop

                } else {
                    $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                    $updatedData = ['cronjob_status' => 0];
                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                }

            } else {
                dd("pics competed");
                $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                $updatedData = ['cronjob_status' => 0,
                    'message' => '' //TODO add custom message here
                ];
                $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
            }

        } //End of Outer foreach loop
    }

    public function checkUserProfile($userDetails) //TODO in-complete
    {
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();
        $instagramUserModel = new Instagram_User();
        $objUsermetaModel = new Usersmeta();
        $objOrderModel = new Order();
        foreach ($userDetails as $user) {
            $whereCronJobStatus = [
                'rawQuery' => 'ins_user_id=?',
                'bindParams' => [$user->ins_user_id]
            ];
            $queryResult = $instagramUserModel->updateUserDetails($whereCronJobStatus, ['cronjob_status' => 0]); // replace with cronjob_status=1
        }

        foreach ($userDetails as $user) {
            $username = $user->ins_username;
            $picsFetchCount = intval($user->pics_fetch_count);
//            $picsDone = intval($user->pics_done);
            $picLimit = intval($user->pics_limit);
            $dailyPostLimit = intval($user->daily_post_limit);
            $dailyPostDone = intval($user->daily_post_done);
            $lastPostCreatedTime = intval($user->last_post_created_time);
            $firstPost_deliveryTime_day = intval($user->firstpost_delivery_daytime);

            if ($dailyPostLimit > 0) {

                if ($picsFetchCount < $picLimit) {

                    // code for reset daily limit every 24 hr. If daily limit cross then stop  autolikes script for next 24 hrs.
                    // Daily limit will be automatically reset every 24 hours from the time the user make the first post
                    if ($firstPost_deliveryTime_day != 0) {
                        if ((time() - $firstPost_deliveryTime_day) >= 86400) { //24 hr = 86400 seconds
                            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                            $updatedData = ['firstpost_delivery_daytime' => time(), 'daily_post_done' => 0];
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                        }
                    }

                    // If the the fist post deliver time is 0 then update it with the first post created time.
                    // and if profile post count i s zero the update first post created time with the current time.
                    if ($lastPostCreatedTime == 0) {
                        $data1 = array();
                        $profilePostCount = $instagramScrape->getProfilePostCountByUsername($username);
                        if ($profilePostCount != null && $profilePostCount > 0) {
                            $userDetails = $instagramScrape->getInsUserDetailsByUsername($username, 1);
                            if ($userDetails) {
                                $data1['last_post_created_time'] = $userDetails[0]['created_time'];
                                $lastPostCreatedTime = $userDetails[0]['created_time'];
                            }
                        } else if ($profilePostCount == 0) {
                            $data1['last_post_created_time'] = time();
                            $lastPostCreatedTime = time();
                        }
                        $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $data1);
                    }

                    if ($dailyPostDone < $dailyPostLimit) {
                        //scrap all the latest post created after the first post created time
                        $userProfileData = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, $dailyPostLimit);
                        if ($userProfileData) {

                            $latestPostCreatedTime = 0;
                            $latestPostCreatedTimeFlag = false;
                            foreach ($userProfileData as $key => $value) {

                                // get the latest post link and place that link  for autolikes order
                                if (($dailyPostDone < $dailyPostLimit) && ($picsFetchCount < $picLimit)) {

                                    //add order in order table and then in order-process table
                                    // This code is for placing  autolikes order only (likes orders)

                                    $price = (($user->charge_per_unit) / 1000) * $user->likes_per_pic;
                                    $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                    $accountBalance = $accountBalanceDetails->account_bal;

                                    if ($accountBalance >= $price) {

                                        $data2['plan_id'] = $user->plan_id;
                                        $data2['by_user_id'] = $user->by_user_id;
                                        $data2['for_user_id'] = $user->ins_user_id;
                                        $data2['ins_url'] = $value['link'];
                                        $data2['quantity_total'] = $user->likes_per_pic;
                                        $data2['start_time'] = time() + 600; //600= 10 minutes delay
                                        $data2['added_time'] = time();
                                        $data2['updated_time'] = time();
                                        $data2['auto_order_status'] = 1;  // 1=autolikes order
                                        $data2['status'] = 0; // order is in pending state
                                        $data2['price'] = $price;
                                        $data2['orders_per_run'] = 0;
                                        $data2['time_interval'] = 0;
                                        $data2['url_type'] = 0;  // 0=postLink

                                        $rollback = false;

                                        DB::beginTransaction();
                                        DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();
                                        $orderInsertStatus = $objOrderModel->insertOrder($data2);
                                        if ($orderInsertStatus) {
                                            $current_bal['account_bal'] = $accountBalance - $price;
                                            $orderUpdateBalanceStatus = $objUsermetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], $current_bal);
                                            if ($orderUpdateBalanceStatus) {
                                                DB::commit();
                                            } else {
                                                $rollback = true;
                                                DB::rollBack();
                                            }
                                        } else {
                                            $rollback = true;
                                            DB::rollBack();
                                        }

                                        if (!$rollback) {
                                            // Update details in instagram_users table
                                            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                            $data3 = [
                                                'pics_fetch_count' => ++$picsFetchCount,
                                                'daily_post_done' => ++$dailyPostDone,
                                                'cronjob_status' => 0,
                                                'last_check' => time(),
//                                            'last_delivery' => time(),
//                                            'last_delivery_link' => $value['link'],
                                                'last_post_created_time' => $value['created_time']
                                            ];
                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data3);

                                            if (($picsFetchCount - 1) == 0) { //this code is run only when user make the first post (first order is placed).
                                                $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                                $data4['firstpost_delivery_daytime'] = time();;
                                                $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $data4);
                                            }

                                            $latestPostCreatedTime = $value['created_time'];
                                            $latestPostCreatedTimeFlag = true;
                                        }
                                    } else {
                                        // insert your custom message here in instagram_users table
                                        $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                        $data5 = [
                                            'cronjob_status' => 0,
                                            'message' => 'This instagram username # ' . $user->ins_username . ' autolikes script has been stopped due to insufficient balance.'
                                        ];
                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data5);
                                        break;
                                    }
                                }
                            } //End of inner foreach loop

                            // update the latest post created time in instagram user table
                            if ($latestPostCreatedTimeFlag) {
                                $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                $updateLastPostCreatedTimeData['last_post_created_time'] = $latestPostCreatedTime;
                                $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $updateLastPostCreatedTimeData);
                            }
                        }
                    }
                }

            } else if ($dailyPostLimit == 0) {

                // If the the fist post deliver time is 0 then update it with the first post created time.
                // and if profile post count i s zero the update first post created time with the current time.
                if ($lastPostCreatedTime == 0) {
                    $data1 = array();
                    $profilePostCount = $instagramScrape->getProfilePostCountByUsername($username);
                    if ($profilePostCount != null && $profilePostCount > 0) {
                        $userDetails = $instagramScrape->getInsUserDetailsByUsername($username, 1);
                        if ($userDetails) {
                            $data1['last_post_created_time'] = $userDetails[0]['created_time'];
                            $lastPostCreatedTime = $userDetails[0]['created_time'];
                        }
                    } else if ($profilePostCount == 0) {
                        $data1['last_post_created_time'] = time();
                        $lastPostCreatedTime = time();
                    }
                    $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                    $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $data1);
                }

                if ($picsFetchCount < $picLimit) {
                    //scrap all the latest post created after the $firstPost_deliveryTime_day  time
                    $userDetails = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime);

                    if ($userDetails) {
                        $userDetails = array_reverse($userDetails);
                        $latestPostCreatedTime = 0;
                        $latestPostCreatedTimeFlag = false;

                        foreach ($userDetails as $key => $value) {
                            // get the latest post link and place that link  for autolikes order
                            if ($picsFetchCount < $picLimit) {

                                //add order in order table and then in order-process table
                                // This code is for placing  autolikes order only (likes orders)

                                $price = (($user->charge_per_unit) / 1000) * $user->likes_per_pic;
                                $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                $accountBalance = $accountBalanceDetails->account_bal;

                                if ($accountBalance >= $price) {

                                    $data2['plan_id'] = $user->plan_id;
                                    $data2['by_user_id'] = $user->by_user_id;
                                    $data2['for_user_id'] = $user->ins_user_id;
                                    $data2['ins_url'] = $value['link'];
                                    $data2['quantity_total'] = $user->likes_per_pic;
                                    $data2['start_time'] = time() + 600; //600= 10 minutes delay
                                    $data2['added_time'] = time();
                                    $data2['updated_time'] = time();
                                    $data2['auto_order_status'] = 1;  // 1=autolikes order
                                    $data2['status'] = 0; // order is in pending state
                                    $data2['price'] = $price;
                                    $data2['orders_per_run'] = 0;
                                    $data2['time_interval'] = 0;
                                    $data2['url_type'] = 0;  // 0=postLink

                                    $rollback = false;

                                    DB::beginTransaction();
                                    DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();
                                    $orderInsertStatus = $objOrderModel->insertOrder($data2);
                                    if ($orderInsertStatus) {
                                        $current_bal['account_bal'] = $accountBalance - $price;
                                        $orderUpdateBalanceStatus = $objUsermetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], $current_bal);
                                        if ($orderUpdateBalanceStatus) {
                                            DB::commit();
                                        } else {
                                            $rollback = true;
                                            DB::rollBack();
                                        }
                                    } else {
                                        $rollback = true;
                                        DB::rollBack();
                                    }

                                    if (!$rollback) {
                                        // Update details in instagram_users table
                                        $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                        $updateInsUserData = [
                                            'pics_fetch_count' => ++$picsFetchCount,
                                            'daily_post_done' => ++$dailyPostDone,
                                            'cronjob_status' => 0,
                                            'last_check' => time(),
//                                            'last_delivery' => time(),
//                                            'last_delivery_link' => $value['link'],
                                            'last_post_created_time' => $value['created_time']
                                        ];
                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);
                                        $latestPostCreatedTime = $value['created_time'];
                                        $latestPostCreatedTimeFlag = true;
                                    }
                                } else {
                                    // insert your custom message here in instagram_users table
                                    $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                                    $updateInsUserMessageData = [
                                        'cronjob_status' => 0,
                                        'message' => 'This instagram username # ' . $user->ins_username . ' autolikes script has been stopped due to insufficient balance.'
                                    ];
                                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserMessageData);
                                    break;
                                }
                            }
                        } //End of inner foreach loop

                        // update the latest post created time in instagram user table
                        if ($latestPostCreatedTimeFlag) {
                            $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
                            $updateLastPostCreatedTimeData['last_post_created_time'] = $latestPostCreatedTime;
                            $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $updateLastPostCreatedTimeData);
                        }
                    }
                }

            }

            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
            $updateInsUserData = ['cronjob_status' => 0, 'last_check' => time(),];
            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);

        } //End of Outer foreach loop
    }

    public function updateAutolikesUserStatusCronJob()
    {

        $orderModel = new Order();
        $whereOrderStatus = [
            'rawQuery' => 'orders.status=? and orders.cronjob_status=? and orders.autolikes_count_done_status=?  and orders.auto_order_status=?',
            'bindParams' => [3, 0, 0, 1]
        ];
//        $orderList = $orderModel->getOrderStatus($whereOrderStatus, ['orders.order_id', 'orders.by_user_id', 'orders.for_user_id', 'orders.quantity_done', 'orders.status']);
        $orderList = $orderModel->getAutolikesOrderStatus($whereOrderStatus, [
            'orders.order_id', 'orders.by_user_id', 'orders.ins_url', 'orders.for_user_id', 'orders.quantity_done', 'orders.status', 'orders.updated_time',
            'instagram_users.pics_done', 'instagram_users.ig_user_status','instagram_users.pics_fetch_count',
            'plans.plan_type'
        ]);
        if (!empty($orderList) || intval($orderList) != 0) {
            $this->checkAutolikesOrderStatus($orderList);
        }
    }

    public function checkAutolikesOrderStatus($orderList)
    {
        $objOrderModel = new Order();
        $objProcessOrderModel = new Process_Order();
        $objInstagramUserModel = new Instagram_User();
        foreach ($orderList as $order) {
            $whereOrderStatus = [
                'rawQuery' => 'order_id=?',
                'bindParams' => [intval($order->order_id)]
            ];
            $orderStatus = $objOrderModel->updateOrder($whereOrderStatus, ['cronjob_status' => 0]); // replace with 1
        }

//dd($orderList);
        $instagramUserId = array();
        foreach ($orderList as $order) {
            if ($order->plan_type == 0) { // for likes only
                $instagramUserId[] = $order->for_user_id;
            }
        }

        $instagramUserId = array_unique($instagramUserId);
        $temp = array();
        foreach ($instagramUserId as $key => $userId) {
            $temp[$key] = array();
            $picsDoneCount = 0;
            $updatedTime = 0;
            foreach ($orderList as $order) {
                if ($order->plan_type == 0) { // for likes only
                    if ($userId == $order->for_user_id) {
                        if ($updatedTime <= intval($order->updated_time)) {
                            $temp[$key]['picsCount'] = ++$picsDoneCount;
                            $temp[$key]['pics_fetch_count'] =intval($order->pics_fetch_count);
                            $temp[$key]['ins_user_id'] = $userId;
                            $temp[$key]['by_user_id'] = intval($order->by_user_id);
                            $updatedTime = intval($order->updated_time);
                            $temp[$key]['ins_url'] = $order->ins_url;
                            $temp[$key]['last_delivery'] = $updatedTime;
                        }
                    }
                }
            }

            if (!empty($temp[$key])) {
                if($temp[$key]['picsCount']<=$temp[$key]['pics_fetch_count']){
                    // update pics done in instagram user table
                    $where = [
                        'rawQuery' => 'ins_user_id=? and by_user_id=?',
                        'bindParams' => [$temp[$key]['ins_user_id'],  $temp[$key]['by_user_id']]
                    ];
                    $data1 = [
                        'pics_done' => $temp[$key]['picsCount'],
                        'last_delivery' => $temp[$key]['last_delivery'],
                        'last_delivery_link' => $temp[$key]['ins_url'],
                        'message'=>'The autolikes script is waiting for new pictures! Searching again in 5 minutes!'
                    ];
                    $resultQuery = $objInstagramUserModel->updateUserDetails($where, $data1);
                }
            }
        }
    }


    public function tempajax(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objOrderModel = new Order();

            $userId = (isset($postData['user_id'])) ? $postData['user_id'] : '';

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

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
                $validator = Validator::make($postData, ['user_id' => 'required']);
                if (!$validator->fails()) {
                    $requestParam = json_decode($postData['requestData'], true);
                    $iTotalRecords = $iDisplayLength = intval($requestParam['length']);
                    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                    $iDisplayStart = intval($requestParam['start']);
                    $sEcho = intval($requestParam['draw']);

                    $whereOderUserID = [
                        'rawQuery' => 'by_user_id=? and orders.status!=6',
                        'bindParams' => [$userId]
                    ];
                    $data = [
                        'orders.order_id', 'orders.server_order_id', 'orders.ins_url', 'orders.quantity_total', 'orders.price',
                        'orders.quantity_done', 'orders.status', 'orders.added_time', 'orders.updated_time',
                        'plans.plan_name', 'plans.supplier_server_id'
                    ];
                    //GET TOTAL NUMBER OF NEW ORDERS
                    $iTotalRecords = count($objOrderModel->getOrderHistory($whereOderUserID, $data));
                    $iTotalFilteredRecords = $iTotalRecords;
                    $records = array();
                    $records["data"] = array();

                    $columns = array('orders.order_id', 'plans.plan_name', 'orders.ins_url', 'orders.quantity_total', 'orders.price', 'orders.added_time', 'orders.updated_time', 'orders.status');
                    $sortingOrder = "";
                    if (isset($requestParam['order'])) {
                        $sortingOrder = [$columns[$requestParam['order'][0]['column'] - 1], $requestParam['order'][0]['dir']];
                    }

                    //FIRLTERING START FROM HERE
                    $filteringRules = '';
                    if (isset($requestParam['action']) && $requestParam['action'] == 'filter' && $requestParam['action'][0] != 'filter_cancel') {
                        if ($requestParam['search_order_id'] != '') {
                            $filteringRules[] = "( orders.order_id LIKE '%" . $requestParam['search_order_id'] . "%' )";
                        }
                        if ($requestParam['search_service_type'] != '') {
                            $filteringRules[] = "( plans.plan_name LIKE '%" . $requestParam['search_service_type'] . "%' )";
                        }
                        if ($requestParam['search_link'] != '') {
                            $filteringRules[] = "( orders.ins_url LIKE '%" . $requestParam['search_link'] . "%' )";
                        }
                        if ($requestParam['search_amount'] != '') {
                            $filteringRules[] = "( orders.quantity_total LIKE '%" . $requestParam['search_amount'] . "%' )";
                        }
                        if ($requestParam['search_price'] != '') {
                            $filteringRules[] = "( orders.price LIKE '%" . $requestParam['search_price'] . "%' )";
                        }
                        if ($requestParam['search_status'] != '') {
                            $filteringRules[] = "( orders.status LIKE '%" . $requestParam['search_status'] . "%' )";
                        }
                        if (!empty($filteringRules)) {
                            $whereOderUserID['rawQuery'] .= " AND " . implode(" AND ", $filteringRules);
                            $iTotalFilteredRecords = count($objOrderModel->getOrderHistory($whereOderUserID, $data));
                        }
                    }

                    $ordersResult = $objOrderModel->getAllOrders($whereOderUserID, $sortingOrder, $iDisplayStart, $iDisplayLength);

                    $records['data'] = $ordersResult;
                    $records["draw"] = $sEcho;
                    $records["recordsTotal"] = $iTotalRecords;
                    $records["recordsFiltered"] = $iTotalFilteredRecords;

                    if ($ordersResult) {
                        foreach ($ordersResult as $order) {
                            $order->added_time = $this->getDateDifference($order->added_time);
                            $order->updated_time = $this->getDateDifference($order->updated_time);
                        }
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $records;
                        echo json_encode($response, true);
                    } else {
                        $response->code = 401;
                        $response->message = "Error in connection please try again.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function testCronFunction()
    {
        $objUsersmeta = new Usersmeta();
        $objUsersmeta->updateUsermetaWhere(['rawQuery' => 'id=?', 'bindParams' => [7]], ['invite_id' => time() - 1459233158
        ]);
    }
}// END OF CLASS
