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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Exception;

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
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.status=?',
                            'bindParams' => [1, intval($userDetails->usergroup_id), 1]
                        ];

                        $plansGroupDetails = $objPlanModel->getFilterPlansDetails($whereUserGroupID);

                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.status=?',
                            'bindParams' => [1, 0, 1]
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
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=?  and plans.status=?',
                            'bindParams' => [1, 0, 1]
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
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=? and plans.status=?',
                            'bindParams' => [1, intval($userDetails->usergroup_id), intval($postData['plan_type_id']), $postData['service_type_id'], 1]
                        ];

                        if (intval($postData['plan_type_id']) == 2) {
                            $whereUserGroupID = [
                                'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and ( plans.plan_type=? or plans.plan_type=? ) and plans.service_type=?  and plans.status=?',
                                'bindParams' => [1, 0, intval($postData['plan_type_id']), 3, $postData['service_type_id'], 1]
                            ];
                        }

                        $plansGroupDetails = $objPlanModel->getFilterPlansDetails($whereUserGroupID);


                        $wherePlans = [
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?  and plans.status=?',
                            'bindParams' => [1, 0, intval($postData['plan_type_id']), $postData['service_type_id'], 1]
                        ];
                        if (intval($postData['plan_type_id']) == 2) {
                            $wherePlans = [
                                'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and ( plans.plan_type=? or plans.plan_type=? ) and plans.service_type=?  and plans.status=?',
                                'bindParams' => [1, 0, intval($postData['plan_type_id']), 3, $postData['service_type_id'], 1]
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
                            'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and plans.plan_type=? and plans.service_type=?  and plans.status=?',
                            'bindParams' => [1, 0, intval($postData['plan_type_id']), $postData['service_type_id'], 1]
                        ];

                        if (intval($postData['plan_type_id']) == 2) {
                            $wherePlans = [
                                'rawQuery' => 'plans.plangroup_id=? and plans.for_usergroup_id=? and ( plans.plan_type=? or plans.plan_type=? ) and plans.service_type=?  and plans.status=?',
                                'bindParams' => [1, 0, intval($postData['plan_type_id']), 3, $postData['service_type_id'], 1]
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
                    'rawQuery' => '(added_by=? or added_by=?) and status=?',
                    'bindParams' => [0, $postData['user_id'], 1]
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

    public function URLinfo(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();

            $instagramScrape = new API\InstagramAPI\Instagram_scrape();
            $objIinstagramAPI = new API\InstagramAPI\Instagram();
            $objUserModel = new User();

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
                $validatePlanId = Validator::make($postData, ['order_url' => "required|url"]);
                if (!$validatePlanId->fails()) {

                    $orderUrl = $postData['order_url'];

                    $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/+(p)\/(([a-zA-Z0-9\.\-\_])*)+\/(([a-zA-Z0-9\?\-\=\.\@])*)/';
                    $urlType = (preg_match($regex, $orderUrl)) ? "postLink" : "profileLink";

                    if ($urlType == "postLink") {
                        $result = $instagramScrape->instagramScrapeOfDirectLink($orderUrl);
                        $data['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                        $data['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                        $data['initial_views_count'] = (isset($result['views_count'])) ? $result['views_count'] : 0;
                        $data['image_url'] = (isset($result['image_url'])) ? $result['image_url'] : '';
                        $data['url_type'] = $urlType;

                        $response->code = 200;
                        $response->message = 'success';
                        $response->data = $data;
                        echo json_encode($response);

                    } else {
                        $temp = explode('/', $orderUrl);
                        $instagramUsername = $temp[3];
                        $result = $instagramScrape->getProfilePicUrl($instagramUsername);

                        $data['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                        $data['image_url'] = (isset($result['image_url'])) ? $result['image_url'] : '';
                        $data['url_type'] = $urlType;

                        $response->code = 200;
                        $response->message = 'success';
                        $response->data = $data;
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

    public function addOrderOLD(Request $request)
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

                    $data['orders_per_run'] = 0;
                    $data['time_interval'] = 0;

                    $startingTime = time();
                    $errorMessage = '';
                    $errorMessageFlag = false;
                    $customOrderMessage = 'Order has inserted! Please wait for 5 minutes to get it started!';

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
                    $planType = intval($planDetails[0]->plan_type);

                    //perform the order_url validation
                    $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/+(p)\/(([a-zA-Z0-9\.\-\_])*)+\/(([a-zA-Z0-9\?\-\=\.\@])*)/';
                    $urlType = (preg_match($regex, $orderUrl)) ? "postLink" : "profileLink";

                    // This script validating the instagram url (whether entered profile user 1=exist or not, 2=post is there  or not, and if the user profile is private or public)
                    // This script runs only when spread order is set.
                    if (isset($postData['spreadOrders']) && $postData['spreadOrders'] == 'on' && $planType == 0) {
                        if ($urlType != "profileLink") {
                            $errorMessage = 'Your link looks invalid! Example of a correct link for this service : http://instagram.com/username/';
                            $errorMessageFlag = true;
                        } else {

                            $temp = explode('/', $orderUrl);
                            $instagramUsername = $temp[3];
                            $endIndex = intval($postData['endSpreadIndex']);

//                            $result = $objIinstagramAPI->getUserDetailsByUsername($instagramUsername, $endIndex);
//
//                            if ($result == 'Username does not exist') {
//                                $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' does not exist.';
//                                $errorMessageFlag = true;
//                            } else if ($result == 'user is private') {
//                                $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' is private !. You cannot place order for private user.';
//                                $errorMessageFlag = true;
//                            } else if ($result == 'There are no any post') {
//                                $errorMessage = 'Error! There are no any post in this profile ( ' . $instagramUsername . ' ).';
//                                $errorMessageFlag = true;
//                            } else if ($result == 'Too many request') {
//
//                                //instgarm api is block the do scrap here to check username is exist or not
//                                if ($instagramScrape->isUserFound($instagramUsername)) {
//                                    $userProfilePostCount = $instagramScrape->getProfilePostCountByUsername($instagramUsername);
//                                    if ($userProfilePostCount < $endIndex) {
//                                        $errorMessage = 'Error! This order cannot be place due to less number of post in this profile ( ' . $instagramUsername . ' ).';
//                                        $errorMessageFlag = true;
//                                    }
//                                } else {
//                                    $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' does not exist.';
//                                    $errorMessageFlag = true;
//                                }
//                            } else {
//                                $userProfilePostCount = $instagramScrape->getProfilePostCountByUsername($instagramUsername);
//                                if ($userProfilePostCount < $endIndex) {
//                                    $errorMessage = 'Error! This order cannot be place due to less number of post in this profile ( ' . $instagramUsername . ' ).';
//                                    $errorMessageFlag = true;
//                                }
//                            }

                            $result = $instagramScrape->getInsUserProfileDetails($instagramUsername);
                            if ($result != null) {
                                if ($result == 'This user does not exist.') {
                                    $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' does not exist.';
                                    $errorMessageFlag = true;
                                } else if ($result == 'This user is private.') {
                                    $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' is private ! You cannot place order for private user.';
                                    $errorMessageFlag = true;
                                } else if (intval(str_replace(",", "", $result['posts'])) == 0) {
                                    $errorMessage = 'Error! There are no any post in this profile ( ' . $instagramUsername . ' ).';
                                    $errorMessageFlag = true;
                                } else if (intval(str_replace(",", "", $result['posts'])) < $endIndex) {
                                    $errorMessage = 'Error! This order cannot be place due to less number of post in this profile ( ' . $instagramUsername . ' ).';
                                    $errorMessageFlag = true;
                                }

                            } else {
                                $errorMessage = 'Error! There is an error in Network. Please try again after sometime.';
                                $errorMessageFlag = true;
                            }
                        }
                    } else if ($planType == 0 || $planType == 2 || $planType == 3) {
                        if ($urlType != "postLink") {
                            $errorMessage = 'Your link looks invalid! Example of a correct link for this service : http://instagram.com/p/vrTV-bAp9E/';
                            $errorMessageFlag = true;
                        }
                    }

                    // This script set the split amount and order per run value only when user set the split amount to delivery check box
                    if (isset($postData['splitTotalAmounts']) && $postData['splitTotalAmounts'] == 'on') {

                        if (isset($postData['ordersPerRun']) && ($postData['ordersPerRun'] == 0 || $postData['ordersPerRun'] == 0)) {
                            $errorMessage = 'Order per run value is required';
                            $errorMessageFlag = true;
                        } else if (isset($postData['time_interval']) && ($postData['time_interval'] == null || $postData['time_interval'] == 0)) {
                            $errorMessage = 'Time interval value is required';
                            $errorMessageFlag = true;
                        } else {
                            $data['orders_per_run'] = intval($postData['ordersPerRun']);
                            $data['time_interval'] = intval($postData['timeInterval']);
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
                                $data['start_index'] = $postData['startSpreadIndex'];
                                $data['end_index'] = $postData['endSpreadIndex'];
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
                    } else if ($planType == 4) {  //Done by Saurabh for videos views
                        $tempOrderUrl = explode('/', $orderUrl);
                        $shortcode = $tempOrderUrl[4];

                        $mediaDetails = $objIinstagramAPI->getMediaDetailsByShortcode($shortcode);
                        if (($mediaDetails['type'] != 'video')) {
                            $errorMessage = 'This is not a video post. Please provide a video post OR change the service';
                            $errorMessageFlag = true;
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
                                $response->message = 'Order Placed Successfully';
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

    public function addOrder(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objUsermetaModel = new Usersmeta();
            $instagramScrape = new API\InstagramAPI\Instagram_scrape();
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

                    $data['orders_per_run'] = 0;
                    $data['time_interval'] = 0;

                    $startingTime = time();
                    $errorMessage = '';
                    $errorMessageFlag = false;
                    $customOrderMessage = 'Order has inserted! Please wait for 5 minutes to get it started!';

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
                    $planType = intval($planDetails[0]->plan_type);

                    //perform the order_url validation
                    $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/+(p)\/(([a-zA-Z0-9\.\-\_])*)+\/(([a-zA-Z0-9\?\-\=\.\@])*)/';
                    $urlType = (preg_match($regex, $orderUrl)) ? "postLink" : "profileLink";

                    // This script validating the instagram url (whether entered profile user 1=exist or not, 2=post is there  or not, and if the user profile is private or public)
                    // This script runs only when spread order is set.
                    if (isset($postData['spreadOrders']) && $postData['spreadOrders'] == 'on' && $planType == 0) {
                        if ($urlType != "profileLink") {
                            $errorMessage = 'Your link looks invalid! Example of a correct link for this service : http://instagram.com/username/';
                            $errorMessageFlag = true;
                        } else {

                            $temp = explode('/', $orderUrl);
                            $instagramUsername = $temp[3];
                            $endIndex = intval($postData['endSpreadIndex']);

                            $result = $instagramScrape->isUsernameExists($instagramUsername);
                            if ($result != null) {
                                if ($result == 'This user does not exist.') {
                                    $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' does not exist.';
                                    $errorMessageFlag = true;
                                } else if ($result == 'Account is private.') {
                                    $errorMessage = 'Error! This Instagram user # ' . $instagramUsername . ' is private ! You cannot place order for private user.';
                                    $errorMessageFlag = true;
                                } else if ($result == 'There is no any post for this profile') {
                                    $errorMessage = 'Error! There are no any post in this profile ( ' . $instagramUsername . ' ).';
                                    $errorMessageFlag = true;
                                } else if ($result < $endIndex) {
                                    $errorMessage = 'Error! This order cannot be place due to less number of post in this profile ( ' . $instagramUsername . ' ).';
                                    $errorMessageFlag = true;
                                }

                            } else {
                                $errorMessage = 'Error! There is an error in Network. Please try again after sometime.';
                                $errorMessageFlag = true;
                            }
                        }
                    } else if ($planType == 0 || $planType == 2 || $planType == 3) {
                        if ($urlType != "postLink") {
                            $errorMessage = 'Your link looks invalid! Example of a correct link for this service : http://instagram.com/p/vrTV-bAp9E/';
                            $errorMessageFlag = true;
                        }
                    }

                    // This script set the split amount and order per run value only when user set the split amount to delivery check box
                    if (isset($postData['splitTotalAmounts']) && $postData['splitTotalAmounts'] == 'on') {

                        if (isset($postData['ordersPerRun']) && ($postData['ordersPerRun'] == 0 || $postData['ordersPerRun'] == 0)) {
                            $errorMessage = 'Order per run value is required';
                            $errorMessageFlag = true;
                        } else if (isset($postData['time_interval']) && ($postData['time_interval'] == null || $postData['time_interval'] == 0)) {
                            $errorMessage = 'Time interval value is required';
                            $errorMessageFlag = true;
                        } else {
                            $data['orders_per_run'] = intval($postData['ordersPerRun']);
                            $data['time_interval'] = intval($postData['timeInterval']);
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
                                $data['start_index'] = $postData['startSpreadIndex'];
                                $data['end_index'] = $postData['endSpreadIndex'];
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
                    } else if ($planType == 4) {  //Done by Saurabh for videos views
                        $result = $instagramScrape->isVideoPost($orderUrl);
                        if ($result == false) {
                            $errorMessage = 'This is not a video post. Please provide a video post OR change the service';
                            $errorMessageFlag = true;
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
                                $response->message = 'Order Placed Successfully';
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
                    $data = [
                        'plans.plan_name',
                        'plans.plan_type',
                        'orders.initial_likes_count',
                        'orders.initial_followers_count',
                        'orders.initial_comments_count',
                        'orders.quantity_done',
                        'orders.quantity_total',
                        'orders.auto_order_status',
                        'orders.status',
                        'orders.order_message',
                        'orders.start_time',

                    ];
                    $userOrderDetails = $objOrderModel->getOrderHistory($whereOderUserID, $data);

                    if ($userOrderDetails) {

                        //for display more details
                        $resultData['planName'] = $userOrderDetails[0]->plan_name;

                        $startCount = 0;
                        if ($userOrderDetails[0]->plan_type == 0) {
                            $resultData['startCount'] = $userOrderDetails[0]->initial_likes_count;
                        } else if ($userOrderDetails[0]->plan_type == 1) {
                            $resultData['startCount'] = $userOrderDetails[0]->initial_followers_count;
                        } else if ($userOrderDetails[0]->plan_type == 4) {
                            $resultData['startCount'] = $userOrderDetails[0]->initial_views_count;
                        } else {
                            $resultData['startCount'] = $userOrderDetails[0]->initial_comments_count;
                        }
                        $resultData['currentCount'] = $resultData['startCount'] + $userOrderDetails[0]->quantity_done;
                        $resultData['remainCount'] = $userOrderDetails[0]->quantity_total - $userOrderDetails[0]->quantity_done;
                        $resultData['finishCount'] = $userOrderDetails[0]->quantity_done;

                        if (($userOrderDetails[0]->auto_order_status == 1) && ($userOrderDetails[0]->status == 0)) {
                            $resultData['message'] = 'Order has inserted! This order has a schedule time and it will start after ' . $this->getDateDifference($userOrderDetails[0]->start_time) . '. Please wait to get it started!';
                        } else {
                            $resultData['message'] = $userOrderDetails[0]->order_message;
                        }

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

    public function reAddOrder(Request $request)
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
                            $data['order_message'] = 'Order has inserted! Please wait for 5 minutes to get it started!';
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
            $objCommentModel = new Comment();
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

                $rules = array();
                if ($postData['orderType'] == "autolikes") {
                    $rules = [
                        'instagramUsername' => 'required',
                        'likesPerPic' => 'required|integer',
                        'picLimit' => 'required|integer',
                        'planId' => 'required|exists:plans,plan_id',
                        'user_id' => 'required|exists:users,id',
                    ];
                } else if ($postData['orderType'] == "autoviews") {
                    $rules = [
                        'instagramUsername' => 'required',
                        'viewsPerVideo' => 'required|integer',
                        'videoLimit' => 'required|integer',
                        'viewplanId' => 'required|exists:plans,plan_id',
                        'user_id' => 'required|exists:users,id',
                    ];
                }


                $validator = Validator::make($postData, $rules);

                if (!$validator->fails()) {

                    $data['by_user_id'] = $postData['user_id'];
                    $data['ins_username'] = $postData['instagramUsername'];

                    if ($postData['orderType'] == "autoviews") {

                        $data['plan_id'] = $postData['viewplanId'];
                        $data['pics_limit'] = $postData['videoLimit'];
                        $data['likes_per_pic'] = $postData['viewsPerVideo'];

                    } else if ($postData['orderType'] == "autolikes") {

                        $data['plan_id'] = $postData['planId'];
                        $data['pics_limit'] = $postData['picLimit'];
                        $data['likes_per_pic'] = $postData['likesPerPic'];
                    }

                    $data['daily_post_limit'] = $postData['dailyPostLimit'];
                    $data['ig_user_status'] = 2;
                    $data['last_check'] = 0;
                    $data['last_delivery'] = 0;
                    $data['message'] = 'The script is waiting for new post. Searching new post in every 5 minutes!';

                    //modified by saurabh for giving option for 10 mins delay
                    if (isset($postData['orderDelay']) && ($postData['orderDelay'] == "on")) {
                        $data['order_delay_flag'] = 1;
                    } else {
                        $data['order_delay_flag'] = 0;
                    }

                    if (isset($postData['splitTotalAmounts']) && ($postData['splitTotalAmounts'] == 'on')) {
                        $data['orders_per_run'] = $postData['ordersPerRun'];
                        $data['time_interval'] = $postData['timeInterval'];
                    } else {
                        $data['orders_per_run'] = 0;
                        $data['time_interval'] = 0;
                    }

                    $errorFlag = false;
                    if (isset($postData['autolikesSubscription']) && ($postData['autolikesSubscription'] == 'on')) {

                        $rules = [
                            'startDate' => 'required|date',
                            'endDate' => 'required|date|after:' . $postData['startDate']
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
                            $data['last_post_created_time'] = $startDateTime;
                            $data['end_date_time'] = $endDateTime;
                            $data['message'] = 'The script will start searching for new post after 5 minutes!.';

                            if (time() < $startDateTime) {
                                $data['ig_user_status'] = 5;
                                $data['message'] = 'The script will start searching for new post after ' . $this->getDateDifference($startDateTime) . '.';
                            }

                        } else {
                            $errorFlag = true;
                            $response->code = 401;
                            $response->message = $validator->messages();
                            $response->data = null;
                            echo json_encode($response, true);
                        }
                    } else {
                        $data['start_date_time'] = time();
                        $data['last_post_created_time'] = time();
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
                                    $data['autoComments'] = 'YES';
                                    $planId = $postData['autoCommentPlanId'];
                                    $data['plan_id_for_autoComments'] = $planId;
                                    $where = ['rawQuery' => 'plan_id=?', 'bindParams' => [$planId]];
                                    $selectColumn = array('plan_type', 'charge_per_unit');
                                    $planIdDetails = $objPlanModel->getPlansDetails($where, $selectColumn);

                                    $data['price_for_autoComments'] = $planIdDetails[0]->charge_per_unit;

                                    if ($planIdDetails[0]->plan_type == 3) {
                                        if (isset($postData['customCommentGroupId'])) {

                                            $whereCommentGroupId = ['rawQuery' => 'comment_group_id=?', 'bindParams' => [$postData['customCommentGroupId']]];
                                            $commentsData = $objCommentModel->getCommentList($whereCommentGroupId, ['comment_id', 'comments']);

                                            if ((isset($commentsData->comments))) {
                                                if (count(json_decode($commentsData->comments)) != 0) {
                                                    $data['custom_comment_id'] = $commentsData->comment_id;
                                                } else {
                                                    $errorFlag = true;
                                                    $response->code = 401;
                                                    $response->message = 'There are no comments in this group please add comments in this group.';
                                                    $response->data = null;
                                                    echo json_encode($response, true);
                                                }
                                            } else {
                                                $errorFlag = true;
                                                $response->code = 401;
                                                $response->message = 'Invalid Comment Group Id.';
                                                $response->data = null;
                                                echo json_encode($response, true);
                                            }
                                        } else {
                                            $data['custom_comment_id'] = 1;
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
                    $data = ['last_delivery_link', 'last_delivery', 'likes_per_pic', 'pics_done', 'comments_amount', 'start_date_time', 'last_check', 'ig_user_status', 'message'];
                    $instagramUserDetails = $objInstagramUserModel->getUserDetails($whereInsUserID, $data);

                    if (isset($instagramUserDetails) && $instagramUserDetails != 0) {

                        //for display more details
                        $resultData['last_delivery'] = ($instagramUserDetails[0]->last_delivery != 0) ? $this->getDateDifference($instagramUserDetails[0]->last_delivery) . ' ago' : 'null';
                        $resultData['last_delivered_link'] = $instagramUserDetails[0]->last_delivery_link;
                        $resultData['likes_sent'] = intval($instagramUserDetails[0]->likes_per_pic * $instagramUserDetails[0]->pics_done);
                        $resultData['comment_sent'] = intval($instagramUserDetails[0]->comments_amount * $instagramUserDetails[0]->pics_done);
                        $resultData['pics_done'] = $instagramUserDetails[0]->pics_done;
                        $resultData['message'] = $instagramUserDetails[0]->message;


                        if (($instagramUserDetails[0]->ig_user_status == 5)) {
                            $resultData['message'] = 'The autolikes script is not yet started! It will start after ' . $this->getDateDifference($instagramUserDetails[0]->start_date_time) . '. Please wait to get it started!';
                        } else if (($instagramUserDetails[0]->ig_user_status == 2)) {
                            $resultData['message'] = 'The script will start searching for new post after ' . $this->getDateDifference($instagramUserDetails[0]->last_check + 360) . '.';
                        } else {
                            $resultData['message'] = $instagramUserDetails[0]->message;
                        }

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
            $objCommentModel = new Comment();
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
                $postData['dailyPostLimit'] = (isset($request['edit_dailyPostLimit'])) ? $request['edit_dailyPostLimit'] : '';
                $postData['orderDelay'] = (isset($request['edit_orderDelay'])) ? $request['edit_orderDelay'] : '';
                $postData['editAutolikesSubscription'] = (isset($request['edit_autolikesSubscription'])) ? $request['edit_autolikesSubscription'] : '';
                $postData['endDate'] = (isset($request['endDate'])) ? $request['endDate'] : '';

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
                    $data['daily_post_limit'] = $postData['dailyPostLimit'];
                    if ($postData['orderDelay'] == "on")
                        $data['order_delay_flag'] = 1;
                    else
                        $data['order_delay_flag'] = 0;

                    $errorFlag = false;
                    if ($postData['editAutolikesSubscription'] == "on") {
//                        dd("inside");
                        $rules = [
//                                'startDate' => 'required|date',
                            'endDate' => 'required|date'
                        ];
                        $validator = Validator::make($postData, $rules);
                        if (!$validator->fails()) {

                            $dt = new API\TimeZoneConvertion();
                            $endDT = $postData['endDate'];
                            //get the tinezone from server
                            $result = $objUserModel->getUsercredsWhere(['rawQuery' => 'id=?', 'bindParams' => [$userId]], ['user_timezone']);
                            $fromTz = $result->user_timezone;
                            $toTz = 'UTC';
                            $endDateTime = $dt->convertLocalTimeToUnixTime($endDT, $fromTz, $toTz);
                            $data['end_date_time'] = $endDateTime;
                        } else {
                            $errorFlag = true;
                            $response->code = 401;
                            $response->message = $validator->messages();
                            $response->data = null;
                            echo json_encode($response, true);
                        }
                    }

                    $advanceOptionflag = true;
                    $errorFlag = false;
                    if (isset($request['edit_autoComments'])) {
                        if ($request['edit_autoComments'] == 'YES') {
                            $rules = [
                                'edit_autoCommentPlanId' => 'required|exists:plans,plan_id',
                                'edit_autoCommentAmount' => 'required|integer'
                            ];
                            $validator = Validator::make($request->all(), $rules);
                            if (!$validator->fails()) {
                                $planId = $request['edit_autoCommentPlanId'];
                                $data['plan_id_for_autoComments'] = $planId;
                                $where = [
                                    'rawQuery' => 'plan_id=?',
                                    'bindParams' => [$planId]
                                ];
                                $selectColumn = array('plan_type');
                                $planIdDetails = $objPlanModel->getPlansDetails($where, $selectColumn);

                                if ($planIdDetails[0]->plan_type == 3) {
                                    if (isset($postData['edit_customCommentGroupId'])) {

                                        $whereCommentGroupId = ['rawQuery' => 'comment_group_id=?', 'bindParams' => [$postData['edit_customCommentGroupId']]];
                                        $commentsData = $objCommentModel->getCommentList($whereCommentGroupId, ['comment_id', 'comments']);

                                        if ((isset($commentsData->comments))) {
                                            if (count(json_decode($commentsData->comments)) != 0) {
                                                $data['custom_comment_id'] = $commentsData->comment_id;
                                            } else {
                                                $errorFlag = true;
                                                $response->code = 401;
                                                $response->message = 'There are no comments in this group please add comments inthis group.';
                                                $response->data = null;
                                                echo json_encode($response, true);
                                            }
                                        } else {
                                            $errorFlag = true;
                                            $response->code = 401;
                                            $response->message = 'Invalid Comment Group Id.';
                                            $response->data = null;
                                            echo json_encode($response, true);
                                        }
                                    }
                                }

                                if (isset($request['edit_autoCommentAmount'])) {
                                    $data['comments_amount'] = $request['edit_autoCommentAmount'];
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

                    if (!$errorFlag) {
                        $queryResult = $objInstagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$postData['ins_user_id']]], $data);

                        if ($queryResult) {
                            $response->code = 200;
                            $response->message = 'Instagram user order details has been updated successfully';
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
//        dd($orderDetails);

        if (!empty($orderDetails) || intval($orderDetails) != 0) {
            $this->scheduleAutolikesOrders($orderDetails);
        }

    }

    //Process order: Pick up the latest orders and schedule placing orders in to server based on time-interval
    public function scheduleOrdersOLD($orderDetails)
    {
        $orderModel = new Order();
        $objUsersmetaModel = new Usersmeta();
        $objCommentModel = new Comment();
        $objIinstagramAPI = new API\InstagramAPI\Instagram();
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();

        $whereIn = implode(',', array_unique(array_map(function ($order) {
            return $order->order_id;
        }, $orderDetails)));
        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 1]); //replace with 1
        try {
            foreach ($orderDetails as $order) {
                $whereOrderStatus = [
                    'rawQuery' => 'order_id=?',
                    'bindParams' => [intval($order->order_id)]
                ];

                $userProfileData = '';
                $orderProcessingMessage = 'This order is in process. Please wait for sometime to finish it.';
                //0= postlink; 1= profilelink
                if ($order->url_type == 0) {
                    $orderLink = 'postLink';
                    $temp = explode('/', $order->ins_url);

//                $instagramMediaShortcode = 'BB19YjwEDHw';
                    $instagramMediaShortcode = $temp[4];
                    $result = $objIinstagramAPI->getMediaDetailsByShortcode($instagramMediaShortcode);

                    //store initial details of given link (current likes, total followers and username) in order table
                    $data['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                    $data['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                    $data['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                    $data['initial_views_count'] = (isset($result['views_count'])) ? $result['views_count'] : 0;
                    $data['order_message'] = $orderProcessingMessage;
                    $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $data);
                } else {
                    $orderLink = 'profileLink';
                    $numberOfLatestPostCount = intval($order->end_index);

                    $temp = explode('/', $order->ins_url);
                    $instagramUsername = $temp[3];

//                $result = $instagramScrape->isUserFound($instagramUsername);
//                dd($result);

                    $result = $objIinstagramAPI->getUserDetailsByUsername($instagramUsername, $numberOfLatestPostCount);
                    $userProfileData = (isset($result['instagramUsersData'])) ? $result['instagramUsersData'] : '';
//                dd($userProfileData);
//                $userProfileData = $instagramScrape->getInsUserDetailsByUsername($instagramUsername,$numberOfLatestPostCount);

                    $data['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                    $data['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                    $data['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                    $data['order_message'] = $orderProcessingMessage;
                    $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $data);
                }
//            dd($data);
                $quantityTotal = intval($order->quantity_total);
                $minQuantity = intval($order->min_quantity);
                $startTime = $order->start_time;
                // $amountPerRun = (intval($order->orders_per_run) > 0) ? $order->orders_per_run : 100;
                $amountPerRun = (intval($order->orders_per_run) > 0) ? $order->orders_per_run : $quantityTotal;
                $timeInterval = (intval($order->time_interval) > 0) ? $order->time_interval : 600;
                $userData = [];
//            dd($order);

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

                                //Spread amount between given pics range.
                                $userProfileData = array_slice($userProfileData, $startPicIndex - 1, $numberOfPost);
                                //divide the total amount into number of sub amounts
                                $amountOfLikesPerRun = array();
                                $tempQuantityTotal = $quantityTotal;
                                while (($tempQuantityTotal - $amountPerRun * $numberOfPost) >= $amountPerRun * $numberOfPost) {
                                    $amountOfLikesPerRun[] = $amountPerRun * $numberOfPost;
                                    $tempQuantityTotal -= $amountPerRun * $numberOfPost;
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
                            }//modified

                            $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0]);

                        } else { //cancel order and refund amount back
                            $OrderData['order_message'] = 'This order has cancelled !. And Money ( $ ' . $order->price . ') is refunded back due to less number of post in instagram user profile.';
                            $OrderData['status'] = 6; //modified from 5 to 6
                            $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $OrderData);
                            $oldAccountBal = $objUsersmetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$order->by_user_id]], ['account_bal']);
                            $newAccountBal = $oldAccountBal->account_bal + $order->price;
                            $queryResult = $objUsersmetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$order->by_user_id]], ['account_bal' => $newAccountBal]);
//                        dd("less number of post so the order is cancel.");
                        }
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
                        for ($j = 0; $j < $i; $j++) {
                            while ($commentFilterListData[$j] == $commentFilterListData[$i]) {
                                $commentFilterListData[$i] = $customCommentlist[rand(0, (count($customCommentlist) - 1))];
                                $j = 0;
                            }
                        }
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

                } else if ($planType == 4) { // for views
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
                }

            }//End of Second inner foreach loop
        } catch (\Exception $e) {
            $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]);
        }

    }// End of function scheduleOrders

    public function scheduleOrders($orderDetails)
    {
        $orderModel = new Order();
        $objUsersmetaModel = new Usersmeta();
        $objCommentModel = new Comment();
        $objIinstagramAPI = new API\InstagramAPI\Instagram();
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();

        $whereIn = implode(',', array_unique(array_map(function ($order) {
            return $order->order_id;
        }, $orderDetails)));
        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); //replace with 1
        try {
            foreach ($orderDetails as $order) {
                $whereOrderStatus = [
                    'rawQuery' => 'order_id=?',
                    'bindParams' => [intval($order->order_id)]
                ];

                $userProfileData = '';
                $orderProcessingMessage = 'This order is in process. Please wait for sometime to finish it.';
                //0= postlink; 1= profilelink
                if ($order->url_type == 0) {
                    $orderLink = 'postLink';
//                    $link = "https://www.instagram.com/p/8aG__xK1Um/?taken-by=saurabh_bond";
                    $result = $instagramScrape->instagramScrapeOfDirectLink($order->ins_url);
                    if ($result != null && $result != "Account is private OR doesn't exist.") {
                        //store initial details of given link (current likes, total followers and username) in order table
                        $data['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                        $data['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                        $data['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                        $data['initial_views_count'] = (isset($result['views_count'])) ? $result['views_count'] : 0;
                        $data['order_message'] = $orderProcessingMessage;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $data);
                    }
                } else {
                    $orderLink = 'profileLink';
                    $numberOfLatestPostCount = intval($order->end_index);

                    $temp = explode('/', $order->ins_url);
                    $instagramUsername = $temp[3];
//                    dd($instagramUsername);
                    $userProfileData = $instagramScrape->instagramScrapeByEndIndex($instagramUsername, $numberOfLatestPostCount);
//                    dd($userProfileData);

                }
//            dd($data);
                $quantityTotal = intval($order->quantity_total);
                $minQuantity = intval($order->min_quantity);
                $startTime = $order->start_time;
                // $amountPerRun = (intval($order->orders_per_run) > 0) ? $order->orders_per_run : 100;
                $amountPerRun = (intval($order->orders_per_run) > 0) ? $order->orders_per_run : $quantityTotal;
                $timeInterval = (intval($order->time_interval) > 0) ? $order->time_interval : 600;
                $userData = [];
//            dd($order);

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
                            $totalLikesCount = $totalViewsCount = $totalCommentsCount = 0;
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
                                    $totalLikesCount += $userProfileData[$i]['likes_count'];
                                    $totalCommentsCount += $userProfileData[$i]['comments_count'];
                                    $totalViewsCount += $userProfileData[$i]['views_count'];
                                    $this->addScheduleOrderToProcessOrder(
                                        $order->order_id,
                                        $order->supplier_server_id,
                                        $order->plan_name_code,
                                        $order->plan_type,
                                        'https://www.instagram.com/p/' . $userProfileData[$i]['link'] . '/',
                                        $perPostLike[$j],
                                        $startTime
                                    );
                                }
                            } else {

                                //Spread amount between given pics range.
                                $userProfileData = array_slice($userProfileData, $startPicIndex - 1, $numberOfPost);

                                for ($i = 0; $i < count($userProfileData); $i++) {
                                    $totalLikesCount += $userProfileData[$i]['likes_count'];
                                    $totalCommentsCount += $userProfileData[$i]['comments_count'];
                                    $totalViewsCount += $userProfileData[$i]['views_count'];
                                }

                                //divide the total amount into number of sub amounts
                                $amountOfLikesPerRun = array();
                                $tempQuantityTotal = $quantityTotal;
                                while (($tempQuantityTotal - $amountPerRun * $numberOfPost) >= $amountPerRun * $numberOfPost) {
                                    $amountOfLikesPerRun[] = $amountPerRun * $numberOfPost;
                                    $tempQuantityTotal -= $amountPerRun * $numberOfPost;
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
                                            'https://www.instagram.com/p/' . $userProfileData[$i]['link'] . '/',
                                            $perPostLike[$i],
                                            $startTimeForSubOrder
                                        );
                                    }
                                    $startTimeForSubOrder += $timeInterval;
                                }
                            }//modified

                            $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0, 'initial_likes_count' => $totalLikesCount, 'initial_comments_count' => $totalCommentsCount, 'initial_views_count' => $totalViewsCount, 'initial_followers_count' => $userProfileData[0]['followers_count'], 'order_message' => $orderProcessingMessage]);

                        } else { //cancel order and refund amount back
                            $OrderData['order_message'] = 'This order has cancelled !. And Money ( $ ' . $order->price . ') is refunded back due to less number of post in instagram user profile.';
                            $OrderData['status'] = 6; //modified from 5 to 6
                            $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$order->order_id]], $OrderData);
                            $oldAccountBal = $objUsersmetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$order->by_user_id]], ['account_bal']);
                            $newAccountBal = $oldAccountBal->account_bal + $order->price;
                            $queryResult = $objUsersmetaModel->updateUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$order->by_user_id]], ['account_bal' => $newAccountBal]);
//                        dd("less number of post so the order is cancel.");
                        }
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
                    $queryResult = $orderModel->updateOrder($whereOrderStatus, ['status' => 1, 'cronjob_status' => 0, 'initial_followers_count' => $userProfileData[0]['followers_count'], 'order_message' => $orderProcessingMessage]);

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
                        for ($j = 0; $j < $i; $j++) {
                            while ($commentFilterListData[$j] == $commentFilterListData[$i]) {
                                $commentFilterListData[$i] = $customCommentlist[rand(0, (count($customCommentlist) - 1))];
                                $j = 0;
                            }
                        }
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

                } else if ($planType == 4) { // for views
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
                }

            }//End of Second inner foreach loop
        } catch (\Exception $e) {
            $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]);
        }

    }

    public function scheduleAutolikesOrdersOLD($orderDetails)
    {
        $orderModel = new Order();
        $objCommentModel = new Comment();
        $objIinstagramAPI = new API\InstagramAPI\Instagram();

        $whereIn = implode(',', array_unique(array_map(function ($order) {
            return $order->order_id;
        }, $orderDetails)));

        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 1]); // replace with cronjob_status=1
//        dd($orderDetails);

        try {
            foreach ($orderDetails as $autolikesOrder) {

                $instagramMediaShortcode = explode('/', $autolikesOrder->ins_url);
                $result = $objIinstagramAPI->getMediaDetailsByShortcode($instagramMediaShortcode[4]);

                //store initial details of given link (current likes, total followers and username) in order table
                $data1['initial_likes_count'] = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                $data1['initial_followers_count'] = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                $data1['initial_comments_count'] = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                $data1['initial_views_count'] = (isset($result['views_count'])) ? $result['views_count'] : 0;
                $data1['order_message'] = 'This order is in process. Please wait for sometime to finish it.';

                if ($autolikesOrder->plan_type == 0) { // for likes
                    $queryResult1 = $this->addScheduleOrderToProcessOrder(
                        $autolikesOrder->order_id,
                        $autolikesOrder->supplier_server_id,
                        $autolikesOrder->plan_name_code,
                        $autolikesOrder->plan_type,
                        $autolikesOrder->ins_url,
                        $autolikesOrder->quantity_total,
                        $autolikesOrder->start_time
                    );
                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }

                } elseif ($autolikesOrder->plan_type == 2) {// for random comment

                    $queryResult1 = $this->addScheduleOrderToProcessOrder(
                        $autolikesOrder->order_id,
                        $autolikesOrder->supplier_server_id,
                        $autolikesOrder->plan_name_code,
                        $autolikesOrder->plan_type,
                        $autolikesOrder->ins_url,
                        $autolikesOrder->quantity_total,
                        $autolikesOrder->start_time
                    );
                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }
                } elseif ($autolikesOrder->plan_type == 3) {// for custom comment

                    $whereCommentId = ['rawQuery' => 'comment_id=?', 'bindParams' => [$autolikesOrder->comment_id]];
                    $commentListData = $objCommentModel->getCommentList($whereCommentId, ['comments']);
                    $customCommentlist = json_decode($commentListData->comments, true);
                    $commentList = array();
                    $quantityTotal = $autolikesOrder->quantity_total;
//                dd($customCommentlist);
                    $commentsCount = count($customCommentlist);
                    if ($commentsCount > $quantityTotal) {
                        for ($i = 0; $i < $quantityTotal; $i++) {
                            $commentList[$i] = $customCommentlist[rand(0, $commentsCount - 1)];
                            for ($j = 0; $j < $i; $j++) {
                                if ($commentList[$j] == $commentList[$i]) {
                                    $i--;
                                    break;
                                }
                            }
                        }
                    } else {
                        for ($i = 0, $j = 0; $i < $quantityTotal; $i++, $j++) {
                            if ($j >= $commentsCount) {
                                $j = 0;
                            }
                            $commentList[$i] = $customCommentlist[$j];
                        }
                    }
//                $commentList = json_encode($commentList, true);

//                dd($commentList);

//                for ($i = 0; $i < $quantityTotal; $i++) {
//                    $commentList[] = $customCommentlist[rand(0, (count($customCommentlist) - 1))];
//                    for ($j = 0; $j < $i; $j++) {
//                        while ($commentList[$j] == $commentList[$i]) {
//                            $commentList[$i] = $customCommentlist[rand(0, (count($customCommentlist) - 1))];
//                            $j = 0;
//                        }
//                    }
//                }

                    $commentList = json_encode($commentList, true);
                    $queryResult1 = $this->addScheduleOrderToProcessOrder(
                        $autolikesOrder->order_id,
                        $autolikesOrder->supplier_server_id,
                        $autolikesOrder->plan_name_code,
                        $autolikesOrder->plan_type,
                        $autolikesOrder->ins_url,
                        $autolikesOrder->quantity_total,
                        $autolikesOrder->start_time,
                        $commentList
                    );

                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }

                } elseif ($autolikesOrder->plan_type == 4) {// for views

                    $queryResult1 = $this->addScheduleOrderToProcessOrder(
                        $autolikesOrder->order_id,
                        $autolikesOrder->supplier_server_id,
                        $autolikesOrder->plan_name_code,
                        $autolikesOrder->plan_type,
                        $autolikesOrder->ins_url,
                        $autolikesOrder->quantity_total,
                        $autolikesOrder->start_time
                    );
                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }
                }


//            dd($autolikesOrder);
            }
        } catch (Exception $e) {
            $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); // replace with cronjob_status=1
        }

//        dd($orderDetails);

    }

    public function scheduleAutolikesOrders($orderDetails)
    {
        $orderModel = new Order();
        $objCommentModel = new Comment();
        $objIinstagramAPI = new API\InstagramAPI\Instagram();

        $whereIn = implode(',', array_unique(array_map(function ($order) {
            return $order->order_id;
        }, $orderDetails)));

        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); // replace with cronjob_status=1
//        dd($orderDetails);

        try {
            foreach ($orderDetails as $autolikesOrder) {
//                dd($autolikesOrder->start_time);
                $data1['order_message'] = 'This order is in process. Please wait for sometime to finish it.';
                $quantityTotal = $autolikesOrder->quantity_total;
                $minQuantity = $autolikesOrder->min_quantity;
                $startTime = $autolikesOrder->start_time;
                $amountPerRun = (intval($autolikesOrder->orders_per_run) > 0) ? $autolikesOrder->orders_per_run : $quantityTotal;
                $timeInterval = (intval($autolikesOrder->time_interval) > 0) ? $autolikesOrder->time_interval : 0;
                $userData = [];
//            dd($order);


                if ($autolikesOrder->plan_type == 0) { // for likes

                    $tempQuantityTotal = $quantityTotal;
//                    dd($minQuantity);
                    while (($tempQuantityTotal - $amountPerRun) >= $minQuantity) {

                        $queryResult1 = $this->addScheduleOrderToProcessOrder(
                            $autolikesOrder->order_id,
                            $autolikesOrder->supplier_server_id,
                            $autolikesOrder->plan_name_code,
                            $autolikesOrder->plan_type,
                            $autolikesOrder->ins_url,
//                            $autolikesOrder->quantity_total,
                            $amountPerRun,
                            $startTime
                        );
                        $tempQuantityTotal = $tempQuantityTotal - $amountPerRun;
                        $startTime = $startTime + $timeInterval;
                    }
                    if ($tempQuantityTotal >= $minQuantity) {
                        $queryResult1 = $this->addScheduleOrderToProcessOrder(
                            $autolikesOrder->order_id,
                            $autolikesOrder->supplier_server_id,
                            $autolikesOrder->plan_name_code,
                            $autolikesOrder->plan_type,
                            $autolikesOrder->ins_url,
//                            $autolikesOrder->quantity_total,
                            $tempQuantityTotal,
                            $startTime
                        );
                    }

                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }

                } elseif ($autolikesOrder->plan_type == 2) {// for random comment

                    $queryResult1 = $this->addScheduleOrderToProcessOrder(
                        $autolikesOrder->order_id,
                        $autolikesOrder->supplier_server_id,
                        $autolikesOrder->plan_name_code,
                        $autolikesOrder->plan_type,
                        $autolikesOrder->ins_url,
                        $autolikesOrder->quantity_total,
                        $autolikesOrder->start_time
                    );
                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }
                } elseif ($autolikesOrder->plan_type == 3) {// for custom comment

                    $whereCommentId = ['rawQuery' => 'comment_id=?', 'bindParams' => [$autolikesOrder->comment_id]];
                    $commentListData = $objCommentModel->getCommentList($whereCommentId, ['comments']);
                    $customCommentlist = json_decode($commentListData->comments, true);
                    $commentList = array();
                    $quantityTotal = $autolikesOrder->quantity_total;
//                dd($customCommentlist);
                    $commentsCount = count($customCommentlist);
                    if ($commentsCount > $quantityTotal) {
                        for ($i = 0; $i < $quantityTotal; $i++) {
                            $commentList[$i] = $customCommentlist[rand(0, $commentsCount - 1)];
                            for ($j = 0; $j < $i; $j++) {
                                if ($commentList[$j] == $commentList[$i]) {
                                    $i--;
                                    break;
                                }
                            }
                        }
                    } else {
                        for ($i = 0, $j = 0; $i < $quantityTotal; $i++, $j++) {
                            if ($j >= $commentsCount) {
                                $j = 0;
                            }
                            $commentList[$i] = $customCommentlist[$j];
                        }
                    }
//                $commentList = json_encode($commentList, true);

//                dd($commentList);

//                for ($i = 0; $i < $quantityTotal; $i++) {
//                    $commentList[] = $customCommentlist[rand(0, (count($customCommentlist) - 1))];
//                    for ($j = 0; $j < $i; $j++) {
//                        while ($commentList[$j] == $commentList[$i]) {
//                            $commentList[$i] = $customCommentlist[rand(0, (count($customCommentlist) - 1))];
//                            $j = 0;
//                        }
//                    }
//                }

                    $commentList = json_encode($commentList, true);
                    $queryResult1 = $this->addScheduleOrderToProcessOrder(
                        $autolikesOrder->order_id,
                        $autolikesOrder->supplier_server_id,
                        $autolikesOrder->plan_name_code,
                        $autolikesOrder->plan_type,
                        $autolikesOrder->ins_url,
                        $autolikesOrder->quantity_total,
                        $autolikesOrder->start_time,
                        $commentList
                    );

                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }

                } elseif ($autolikesOrder->plan_type == 4) {// for views

                    $queryResult1 = $this->addScheduleOrderToProcessOrder(
                        $autolikesOrder->order_id,
                        $autolikesOrder->supplier_server_id,
                        $autolikesOrder->plan_name_code,
                        $autolikesOrder->plan_type,
                        $autolikesOrder->ins_url,
                        $autolikesOrder->quantity_total,
                        $autolikesOrder->start_time
                    );
                    if ($queryResult1) {
                        $data1['status'] = 1;
                        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id=?', 'bindParams' => [$autolikesOrder->order_id]], $data1);
                    }
                }


//            dd($autolikesOrder);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); // replace with cronjob_status=1
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
        $userData['start_time'] = $startTime;
        $userData['updated_time'] = time();
        $userData['process_order_status'] = 0;
        if (isset($commentList)) {
            $userData['comments'] = $commentList;
        }
        $queryResult = $processOrderModel->insertProcessOrder($userData);
        return ($queryResult) ? true : false;
    }

    public function addProcessOrdersToServerCronJob()
    {
        $processOrderModel = new Process_Order();
        $whereProcessOrder = [
            'rawQuery' => 'start_time<? and process_order_status=? and cronjob_status=? and server_order_id IS NULL',
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

        $whereIn = implode(',', array_unique(array_map(function ($order) {
            return $order->process_order_id;
        }, $orderDetails)));

        $queryResult = $objProcessOrderModel->updateProcessOrder(['rawQuery' => 'process_order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); // replace with cronjob_status=1
        try {
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
//                $amount = 00;
                    $objSocialPanel24 = new API\SocialPanel24();
                    $result = $objSocialPanel24->order_add($url, $type, $amount);
                    $result = json_decode($result, true);

                    if (current(array_keys($result, true)) == 'order') { // for old api replace with 'id'
                        //update process_orders table
                        $whereProcessOrderId = [
                            'rawQuery' => 'process_order_id=?',
                            'bindParams' => [intval($order->process_order_id)]
                        ];
                        $data = array(
                            'server_order_id' => $result['order'],  // for old api replace with 'id'
                            'updated_time' => time(),
                            'process_order_status' => 1,
                            'cronjob_status' => 0
                        );
                        $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderId, $data);

                        //update orders table
                        $whereOrderStatus = [
                            'rawQuery' => 'order_id=?',
                            'bindParams' => [intval($order->parent_order_id)],
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

                if ($order->supplier_server_id == 4) {
                    //process order for socialnator.com

                    $url = $order->ins_url;
                    $type = $order->plan_name_code;
                    $amount = $order->quantity_total;

                    $objModelSocialNator = new API\SocialNator();
                    $order_details = [];
                    $order_details['instagramprofileurl'] = $url;
                    $order_details['amount'] = $amount;
                    $order_details['method'] = $type;

//$response = $api->add_order($order_details['instagramprofileurl'], $order_details['amount'], $order_details['method']);
                    $result = $objModelSocialNator->add_order($order_details['instagramprofileurl'], $order_details['amount'], $order_details['method']);
//                    dd($result);
                    $result = json_decode($result, true);
                    if ($result['code'] == 200) {
                        $whereProcessOrderId = [
                            'rawQuery' => 'process_order_id=?',
                            'bindParams' => [intval($order->process_order_id)]
                        ];
                        $data = array(
                            'server_order_id' => 4444,  // This is our hardcoded server_id . as the socialnator.com is not giving any orderId.
                            'updated_time' => time(),
                            'process_order_status' => 1,
                            'cronjob_status' => 0
                        );
                        $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderId, $data);

                        // code for updating orders table
                        $whereOrderStatus = [
                            'rawQuery' => 'order_id=?',
                            'bindParams' => [intval($order->parent_order_id)],
                        ];
                        $data = array(
                            'status' => 1,
                            'updated_time' => time(),
                            'cronjob_status' => 0,
                            'order_message' => 'This order has been added to the server for processing.'
                        );
                        $queryResult = $objOrderModel->updateOrder($whereOrderStatus, $data);
                    } else {
                        $whereProcessOrderStatus = [
                            'rawQuery' => 'process_order_id=?',
                            'bindParams' => [$order->process_order_id]
                        ];
                        $queryResult = $objProcessOrderModel->updateProcessOrder($whereProcessOrderStatus, ['cronjob_status' => 0]);
//                    echo "error", "\n";
                    }
                }

            }
        } catch (Exception $e) {
            $queryResult = $objProcessOrderModel->updateProcessOrder(['rawQuery' => 'process_order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]);
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

        $whereIn = implode(',', array_unique(array_map(function ($order) {
            return $order->order_id;
        }, $orderList)));

        $queryResult = $orderModel->updateOrder(['rawQuery' => 'order_id IN(' . $whereIn . ')'], ['cronjob_status' => 1]); // replace with cronjob_status=1

//        dd($queryResult);

        if (!Session::has('FE_in_checkOrderStatus')) { //FE=Fatal_Error
            $fataErrorData['whereIn'] = $whereIn;
            $fataErrorData['modalObject'] = $objProcessOrderModel;
            $fataErrorData['functionName'] = 'updateProcessOrder';
            $fataErrorData['params'] = 'process_order_id';
            Session::put('FE_in_checkOrderStatus', $fataErrorData);
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
                    1 => 'This order is in process. Please wait for sometime to finish it.',
                    2 => 'This order is in process. Please wait for sometime to finish it.',
                    3 => 'This order has completed!. Thank you.',
                    4 => 'This order has failed!, due to some error in service!.Money has refunded back',
                    5 => 'This order has cancelled!, due to some error in service!.Money has refunded back',
                    6 => 'This order has cancelled!, due to some error in service!.Money has refunded back',
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

//        dd($processOrderList);

        $whereIn = implode(',', array_unique(array_map(function ($order) {
            return $order->process_order_id;
        }, $processOrderList)));

        $queryResult = $objProcessOrderModel->updateProcessOrder(['rawQuery' => 'process_order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); // replace with cronjob_status=1


        if (!Session::has('FE_checkProcessOrderStatus')) { // FE=Fatal Error
            $fataErrorData['whereIn'] = $whereIn;
            $fataErrorData['modalObject'] = $objProcessOrderModel;
            $fataErrorData['functionName'] = 'updateProcessOrder';
            $fataErrorData['params'] = 'process_order_id';
            Session::put('FE_checkProcessOrderStatus', $fataErrorData);
        }

        try {
            foreach ($processOrderList as $processOrder) {
                $where = [
                    'rawQuery' => 'process_order_id=?',
                    'bindParams' => [$processOrder->process_order_id]
                ];
                if ($processOrder->supplier_server_id == 1) {
                    //process order status for igerslike API

                    try {
                        $result = $objIgersLike->order_status($processOrder->server_order_id);
                        $result = json_decode($result, true);
                        if (!empty($result) || $result != '' || $result != null) {
                            $orderStatus = "";

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
                                //update the initial views counts.//Done by Saurabh
                                if ($processOrder->plan_type == 4) {
                                    $objModelOrders = new Order();
                                    $whereForUpdateOrders = array('rawQuery' => 'order_id=?', 'bindParams' => [$processOrder->parent_order_id]);
                                    $updated = $objModelOrders->updateOrder($whereForUpdateOrders, ['initial_views_count' => $result['count_start']]);
                                }
                            } else if ($result['status'] == 'fail') {
                                $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                            }
                        }
                    } catch (\Exception $e) {
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                    }
                } else if ($processOrder->supplier_server_id == 2) {
                    //process order for cheapbulk API

                    try {
                        $result = $objCheapBulkSocial->order_status($processOrder->server_order_id);
                        $result = json_decode($result, true);
                        if (isset($result['status_code'])) {

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
                    } catch (\Exception $e) {
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                    }

                } else if ($processOrder->supplier_server_id == 3) {
                    //process order for sociL panel 24 API
                    try {
                        $result = $objSocialPanel24->order_status($processOrder->server_order_id);
                        $result = json_decode($result, true);
                        $orderStatus = "";

                        if (isset($result['status'])) {
                            if ($result['status'] != '' || $result['status'] != NULL) {
                                //0 = Pending, 1 = In progress, 2 = Completed, 3 = Partial, 4 = Canceled, 5 = Processing //This is the response of older API
                                switch ($result['status']) {
                                    case "Pending":
                                        $orderStatus = 1;
                                        break;
                                    case "In progress":
                                        $orderStatus = 2;
                                        break;
                                    case "Completed":
                                        $orderStatus = 3;
                                        break;
                                    case "Canceled":
                                        $orderStatus = 6;
                                        break;
//                            case "Completed":
//                                $orderStatus = 2;
//                                break;
//                            case "In progress":
//                                $orderStatus = 6;
//                                break;
//                            case "In progress":
//                                $orderStatus = 2;
//                                break;
                                    default:
                                        break;
                                }
                                $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['updated_time' => time(), 'process_order_status' => $orderStatus, 'cronjob_status' => 0]);
                            }
                        } else {
                            $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                        }
                    } catch (\Exception $e) {
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                    }
                } else if ($processOrder->supplier_server_id == 4) {
                    //coded by Saurabh
//                   dd($processOrder);
                    try {
                        $objInstagramAPI = new API\InstagramAPI\Instagram();
                        $objInstagramScrape = new API\InstagramAPI\Instagram_scrape();
                        $objOrder = new Order();
                        $whereForOrders = [
                            'rawQuery' => 'orders.order_id = ?',
                            'bindParams' => [$processOrder->parent_order_id]
                        ];
                        $orderDetails = $objOrder->getOrderHistory($whereForOrders);
//                        dd($orderDetails);


                        if ($orderDetails[0]->url_type == 0) { //PostLink
//                            $temp = explode('/', $orderDetails[0]->ins_url);
//
//                            $instagramMediaShortcode = $temp[4];
//
//                            $result = $objInstagramAPI->getMediaDetailsByShortcode($instagramMediaShortcode);
//                            dd($orderDetails[0]->ins_url);

                            $result = $objInstagramScrape->instagramScrapeOfDirectLink($orderDetails[0]->ins_url);
//                            dd($result);

                            $finalLikesCount = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                            $finalFollowersCount = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
                            $finalCommentsCount = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
                            $finalViewsCount = (isset($result['views_count'])) ? $result['views_count'] : 0;

                            $trueFlag = false;

                            if ($orderDetails[0]->plan_type == 0) {//likes
                                $initialLikesCount = $orderDetails[0]->initial_likes_count;
                                $trueFlag = ($finalLikesCount - $initialLikesCount >= $processOrder->quantity_total);

                            } else if ($orderDetails[0]->plan_type == 1) {//followers
                                $initialFollowersCount = $orderDetails[0]->initial_followers_count;
                                $trueFlag = ($finalFollowersCount - $initialFollowersCount >= $processOrder->quantity_total);

                            } else if ($orderDetails[0]->plan_type == 2 || $orderDetails[0]->plan_type == 3) {//comments
                                $initialCommentsCount = $orderDetails[0]->initial_comments_count;
                                $trueFlag = ($finalCommentsCount - $initialCommentsCount >= $processOrder->quantity_total);

                            } else if ($orderDetails[0]->plan_type == 4) {//views
                                $initialViewsCount = $orderDetails[0]->initial_views_count;
                                $trueFlag = ($finalViewsCount - $initialViewsCount >= $processOrder->quantity_total);

                            }
                            if ($trueFlag)
                                $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['updated_time' => time(), 'process_order_status' => 3, 'cronjob_status' => 0]);
                            else
                                $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);

                        } else { //ProfileLink

                            $temp = explode('/', $orderDetails[0]->ins_url);//$orderDetails[0]->ins_url
                            $instagramUsername = $temp[3];
                            $numberOfLatestPostCount = 0;
                            $startIndex = $orderDetails[0]->start_index;
                            $endIndex = $orderDetails[0]->end_index;
                            $result = $objInstagramScrape->getDetailsByStartAndLastSpreadIndex($instagramUsername, $startIndex, $endIndex);


                            $finalLikesCount = (isset($result['likes_count'])) ? $result['likes_count'] : 0;
                            $finalFollowersCount = (isset($result['followers_count'])) ? $result['followers_count'] : 0;
//                            $finalCommentsCount = (isset($result['comments_count'])) ? $result['comments_count'] : 0;
//                            $finalViewsCount = (isset($result['views_count'])) ? $result['views_count'] : 0;

                            $trueFlag = false;
                            if ($orderDetails[0]->plan_type == 0) {//likes
                                $initialLikesCount = $orderDetails[0]->initial_likes_count;
                                $trueFlag = ($finalLikesCount - $initialLikesCount >= $processOrder->quantity_total);

                            } else if ($orderDetails[0]->plan_type == 1) {//followers
                                $initialFollowersCount = $orderDetails[0]->initial_followers_count;
                                $trueFlag = ($finalFollowersCount - $initialFollowersCount >= $processOrder->quantity_total);
                            }
                            if ($trueFlag)
                                $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['updated_time' => time(), 'process_order_status' => 3, 'cronjob_status' => 0]);
                            else
                                $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                        }

                    } catch (\Exception $e) {
                        $queryResult = $objProcessOrderModel->updateProcessOrder($where, ['cronjob_status' => 0]);
                    }
                }
            }
        } catch (\Exception $e) {
            $queryResult = $objProcessOrderModel->updateProcessOrder(['rawQuery' => 'process_order_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); // replace with cronjob_status=1
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
                        'plans.plan_name',
                        'instagram_users.likes_per_pic',
                        'instagram_users.pics_done',
                        'instagram_users.pics_limit',
                        'instagram_users.start_date_time',
                        'instagram_users.end_date_time',
                        'instagram_users.last_check',
                        'instagram_users.ig_user_status'
//                        'instagram_users.last_delivery',
                    );
                    $sortingOrder = "";
                    if (isset($requestParam['order'])) {
                        $sortingOrder = [$columns[$requestParam['order'][0]['column'] - 1], $requestParam['order'][0]['dir']];
                    }

                    //code Modified by Saurabh
                    //group action perform here
                    if (isset($requestParam["customActionType"]) && $requestParam["customActionType"] == "group_action") {
                        if ($requestParam['customActionName'] != '' && !empty($requestParam['insUserId'])) {
                            $insUsersId = $requestParam['insUserId'];

                            if ($requestParam['customActionName'] == 'remove_user') {
                                //delet the profile(s) from DB permanently.

                                $messages = array();
                                foreach ($insUsersId as $key => $insUserId) {


//first check if ins user order details is present in order table or if it exist then details that details in both table.

                                    //TODO
                                    $queryResult = $objInstagramUserModel->deleteInsUser(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]]);

                                    if ($queryResult) {
                                        $messages[$key] = 'Instagram user ID #' . $insUserId . ' record deleted successfully';
                                    } else {
                                        $messages[$key] = 'There is an problem in deleting this user ID#' . $insUserId;
                                    }
                                }
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            } else if ($requestParam['customActionName'] == 'restart_daily_counter') {
                                //first check daily_post_limit
                                //If (daily_post_limit is not equal to 0) then reset daily_post_done = 0 from DB
                                //If 0 no need to reset daily_post_done

                                $messages = array();
                                foreach ($insUsersId as $key => $insUserId) {
                                    $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['ins_username', 'daily_post_limit']);
                                    if ($instagramUserDetails[0]->daily_post_limit != 0) {
                                        $queryResult = $objInstagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['daily_post_done' => 0, 'firstpost_delivery_daytime' => 0, 'ig_user_status' => 2]); //'pics_done' => 0,
                                        if ($queryResult) {
                                            $messages[$key] = 'Done! We have reset the daily post done count for the profile # ' . $instagramUserDetails[0]->ins_username . ' .AutoLikes script can process ' . $instagramUserDetails[0]->daily_post_limit . ' more new post for today.';
                                        } else {
                                            $messages[$key] = 'Sorry! some error occurred, please reload the page and try again later.';
                                        }
                                    } else {
                                        $messages[$key] = '#' . $instagramUserDetails[0]->ins_username . ' has already subscribed for unlimited daily post.';
                                    }

//                                    $response->code = 200;
//                                    $response->message = "Success";
//                                    $response->data = $instagramUserDetails[0]->ins_username;
//                                    echo json_encode($response, true);die;

                                }
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            } else if ($requestParam['customActionName'] == 'restart_total_counter') {
                                //reset all the setting for this user

                                $messages = array();
                                foreach ($insUsersId as $key => $insUserId) {
                                    $queryResult = $objInstagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['pics_fetch_count' => 0, 'pics_done' => 0, 'daily_post_done' => 0, 'reset_counter_time' => time()]);
                                    $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['ins_username', 'pics_limit']);

                                    if ($queryResult) {
                                        $messages[$key] = 'Done! We have reset the post done count for # ' . $instagramUserDetails[0]->ins_username . ' .Autolikes script can process ' . $instagramUserDetails[0]->pics_limit . ' more new posts.';
                                    } else {
                                        $messages[$key] = 'Sorry! Some Problem Occurred. Please reload the page and try again.';
                                    }

//                                    $response->code = 200;
//                                    $response->message = "Success";
//                                    $response->data = $instagramUserDetails[0]->ins_username;
//                                    echo json_encode($response, true);die;

                                }
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;


                            } else if ($requestParam['customActionName'] == 'force_check') {//code for force to check the profile for new posts
                                $messages = array();
                                $objInstagramScrape = new API\InstagramAPI\Instagram_scrape();
                                foreach ($insUsersId as $key => $insUserId) {
                                    //code modified by saurabh //just use the autolikesscript() function here.
                                    $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]]);
//                                    dd($instagramUserDetails[0]->ins_username);
                                    $userExists = $objInstagramScrape->isUsernameExists($instagramUserDetails[0]->ins_username);
                                    if (is_numeric($userExists)) {
                                        $oldPicsFetchCount = $instagramUserDetails[0]->pics_fetch_count;
                                        if (($instagramUserDetails[0]->ig_user_status == 2 || $instagramUserDetails[0]->ig_user_status == 3) && $instagramUserDetails[0]->cronjob_status == 0) {
//                                        if (!empty($instagramUserDetails[0]) || $instagramUserDetails[0] != 0) {
//                                            $this->checkUserProfile($instagramUserDetails[0]);
//                                            $messages[$key] = 'hahahsfashash';
//                                        }
                                            $this->checkUserProfile($instagramUserDetails);

                                            $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['pics_fetch_count', 'ins_username']);
                                            $picsFetchCount = $instagramUserDetails[0]->pics_fetch_count - $oldPicsFetchCount;
                                            if ($picsFetchCount != 0)
                                                $messages[$key] = 'Done! We got ' . $picsFetchCount . ' new posts and added for processing. Please check your order history.';
                                            else
                                                $messages[$key] = 'There is no any new post for this profile ' . $instagramUserDetails[0]->ins_username . ' .';
                                        } else {
                                            $messages[$key] = '#' . $instagramUserDetails[0]->ins_username . ' has been finished or failed';
                                        }
                                    } else {
                                        $messages[$key] = $instagramUserDetails[0]->ins_username . ' does not exists OR may be private';

                                    }
                                }
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            } else if ($requestParam['customActionName'] == 'change_server') {// code for change server
                                $messages[] = 'You can change server in edit order details form. Kindly wait, we are working on code for this option';
                                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                                $records["customActionMessage"] = $messages;
                            }
                        }
                    }


                    //code modified by Saurabh
                    //FIRLTERING START FROM HERE
                    $filteringRules = '';
                    if (isset($requestParam['action']) && $requestParam['action'] == 'filter' && $requestParam['action'][0] != 'filter_cancel') {
                        if ($requestParam['search_id'] != '') {
                            $filteringRules[] = "( instagram_users.ins_user_id LIKE '%" . $requestParam['search_id'] . "%' )";
                        }
                        if ($requestParam['search_username'] != '') {
                            $filteringRules[] = "( instagram_users.ins_username LIKE '%" . $requestParam['search_username'] . "%' )";
                        }
                        if ($requestParam['search_service_type'] != '') {
                            $filteringRules[] = "( instagram_users.plan_id LIKE '%" . $requestParam['search_service_type'] . "%' )";
                        }
                        if ($requestParam['search_pics_likes'] != '') {
                            $filteringRules[] = "( instagram_users.likes_per_pic LIKE '%" . $requestParam['search_pics_likes'] . "%' )";
                        }
                        if ($requestParam['search_daily_post_limit'] != '') {
                            if ($requestParam['search_daily_post_limit'] == 0)// dailyPostLimit is set.
                                $filteringRules[] = "( instagram_users.daily_post_limit != 0 )";
                            else if ($requestParam['search_daily_post_limit'] == 1)//subscribed for unlimited dailyPost(0)
                                $filteringRules[] = "(instagram_users.daily_post_limit = 0)";
                            else if ($requestParam['search_daily_post_limit'] == 2)
                                $filteringRules[] = "(instagram_users.daily_post_limit = instagram_users.daily_post_done)";
                        }
                        if ($requestParam['search_total_post_reached'] != '') {
                            if ($requestParam['search_total_post_reached'] == 0)
                                $filteringRules[] = "( instagram_users.pics_done = instagram_users.pics_limit )";
                            else if ($requestParam['search_total_post_reached'] == 1)
                                $filteringRules[] = "( instagram_users.pics_done != instagram_users.pics_limit )";
                            else if ($requestParam['search_total_post_reached'] == 2)
                                $filteringRules[] = "(instagram_users.pics_done >= instagram_users.pics_limit-(instagram_users.pics_limit*0.1) && instagram_users.pics_done != instagram_users.pics_limit )";
                        }
//                        if ($requestParam['search_pics_done'] != '') {
//                            $filteringRules[] = "( instagram_users.pics_done LIKE '%" . $requestParam['search_pics_done'] . "%' )";
//                        }
                        if ($requestParam['search_start_date'] != '') {
                            $filteringRules[] = "( instagram_users.start_date_time LIKE '%" . $requestParam['search_start_date'] . "%' )";
                        }
                        if ($requestParam['search_end_date'] != '') {
                            $currentTime = time();
                            $currentTimeAfter48hrs = time() + 172800;
                            $currentTimeAfter5days = time() + 432000;
                            if ($requestParam['search_end_date'] == 0)
                                $filteringRules[] = "( instagram_users.end_date_time <= " . $currentTime . ")";
                            else if ($requestParam['search_end_date'] == 1)
                                $filteringRules[] = "( instagram_users.end_date_time >" . $currentTime . "&& instagram_users.end_date_time <=  " . $currentTimeAfter48hrs . ")"; //172800=48hrs
                            else if ($requestParam['search_end_date'] == 2)
                                $filteringRules[] = "(instagram_users.end_date_time >" . $currentTime . "&& instagram_users.end_date_time <= " . $currentTimeAfter5days . ")";//432000= 5days
                        }
                        if ($requestParam['search_last_check'] != '') {
                            $filteringRules[] = "( instagram_users.last_check LIKE '%" . $requestParam['search_last_check'] . "%' )";
                        }
//                        if ($requestParam['search_last_delivery'] != '') {
//                            $filteringRules[] = "( instagram_users.last_delivery LIKE '%" . $requestParam['search_last_delivery'] . "%' )";
//                        }
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
                                //code modified by Saurabh
                                $currentTime = time();
                                if ($user->start_date_time != 0) {
                                    if ($currentTime < $user->start_date_time) {
                                        $user->start_date_time = 'After ' . $this->getDateDifference($user->start_date_time);
                                    } else {
                                        $user->start_date_time = $this->getDateDifference($user->start_date_time) . ' ago';
                                    }
                                } else {
                                    $user->start_date_time = '-';
                                }


                                if ($user->end_date_time != 0) {
                                    if ($currentTime <= $user->end_date_time)
                                        $user->end_date_time = 'After ' . $this->getDateDifference($user->end_date_time);
                                    else
                                        $user->end_date_time = 'Expired ' . $this->getDateDifference($user->end_date_time) . ' before';
                                } else if ($user->end_date_time == 0) {
                                    $user->end_date_time = '-';
                                }
                                $user->last_check = ($user->last_check != 0) ? $this->getDateDifference($user->last_check) : '-';
//                                $user->last_delivery = ($user->last_delivery != 0) ? $this->getDateDifference($user->last_delivery) : '-';
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
                        'instagram_users.daily_post_limit',
                        'instagram_users.order_delay_flag',
                        'plans.plan_name',
                        'instagram_users.plan_id_for_autoComments',
                        'instagram_users.custom_comment_id',
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
                                        } else if ($orderStatus[0]->status == 1 || $orderStatus[0]->status == 2 || $orderStatus[0]->status == 3 || $orderStatus[0]->status == 4 || $orderStatus[0]->status == 5) {
                                            $messages[$key] = "Your order with the ID #" . $order_id . " cannot be cancelled as its already been added on the system.\n";
                                        } else if ($orderStatus[0]->status == 6) {
                                            $messages[$key] = "Your order with the ID #" . $order_id . " has already cancelled.\n";
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

                                        $data['order_message'] = 'Order has inserted! Please wait for 10 minutes to get it started!';
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

    //Start Autolikes script functions

    public function autoLikesScriptServer1()
    {
        $instagramUserModel = new Instagram_User();
        $whereStatus = [
            'rawQuery' => 'start_date_time<=? and ig_user_status IN(?,?,?) and cronjob_status=? and plans.plan_type=? and plans.supplier_server_id=?',
            'bindParams' => [time(), 2, 3, 4, 0, 0, 1],
        ];
        $userDetails = $instagramUserModel->getUserDetails($whereStatus);
//        dd($userDetails);

        if (!empty($userDetails) || $userDetails != 0) {
            $this->checkUserProfile($userDetails);
        }
    }

    public function autoLikesScriptServer2()
    {
        $instagramUserModel = new Instagram_User();
        $whereStatus = [
            'rawQuery' => 'start_date_time<=? and ig_user_status IN(?,?,?) and cronjob_status=? and plans.plan_type=? and plans.supplier_server_id=?',
            'bindParams' => [time(), 2, 3, 4, 0, 0, 2],
        ];
        $userDetails = $instagramUserModel->getUserDetails($whereStatus);
//        dd($userDetails);

        if (!empty($userDetails) || $userDetails != 0) {
            $this->checkUserProfile($userDetails);
        }
    }

    public function autoLikesScriptServer3()
    {
        $instagramUserModel = new Instagram_User();
        $whereStatus = [
            'rawQuery' => 'start_date_time<=? and ig_user_status IN(?,?,?) and cronjob_status=? and plans.plan_type=? and plans.supplier_server_id=?',
            'bindParams' => [time(), 2, 3, 4, 0, 0, 3],
        ];
        $userDetails = $instagramUserModel->getUserDetails($whereStatus);
//        dd($userDetails);

        if (!empty($userDetails) || $userDetails != 0) {
            $this->checkUserProfile($userDetails);
        }
    }

    public function autoLikesScriptServer4()
    {
        $instagramUserModel = new Instagram_User();
        $whereStatus = [
            'rawQuery' => 'start_date_time<=? and ig_user_status IN(?,?,?) and cronjob_status=? and plans.plan_type=? and plans.supplier_server_id=?',
            'bindParams' => [time(), 2, 3, 4, 0, 0, 4],
        ];
        $userDetails = $instagramUserModel->getUserDetails($whereStatus);
//        dd($userDetails);

        if (!empty($userDetails) || $userDetails != 0) {
            $this->checkUserProfile($userDetails);
        }
    }

    public function checkUserProfileOLD($userDetails)
    {
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();
        $instagramUserModel = new Instagram_User();
        $objUsermetaModel = new Usersmeta();
        $objOrderModel = new Order();

        $whereIn = implode(',', array_unique(array_map(function ($v) {
            return $v->ins_user_id;
        }, $userDetails)));


        $insUserStatus = $instagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')'], ['cronjob_status' => 1]); //replace with 1

        if (!Session::has('FE_in_checkUserProfile')) { //FE=Fatal_Error
            $fataErrorData['whereIn'] = $whereIn;
            $fataErrorData['modalObject'] = $instagramUserModel;
            $fataErrorData['functionName'] = 'updateUserDetails';
            $fataErrorData['params'] = 'ins_user_id';
            Session::put('FE_in_checkUserProfile', $fataErrorData);
        }


//        dd($insUserStatus);
//        dd($userDetails);

        try {
            foreach ($userDetails as $user) {
                $username = $user->ins_username;
                $picsFetchCount = intval($user->pics_fetch_count);
//            $picsDone = intval($user->pics_done);
                $picLimit = intval($user->pics_limit);
                $dailyPostLimit = intval($user->daily_post_limit);
                $dailyPostDone = intval($user->daily_post_done);
                $lastPostCreatedTime = intval($user->last_post_created_time);
                $firstPost_deliveryTime_day = intval($user->firstpost_delivery_daytime);
                $orderDelay = intval($user->order_delay_flag);
                $endDateTime = intval($user->end_date_time);

                $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];

                // code for reset daily limit every 24 hr. If daily limit cross then stop  autolikes script for next 24 hrs.
                // Daily limit will be automatically reset every 24 hours from the time the user make the first post
                if ((intval($user->ig_user_status) == 2) || (intval($user->ig_user_status) == 3)) {
                    if ($firstPost_deliveryTime_day != 0) {
                        if ((time() - $firstPost_deliveryTime_day) >= 86400) { //24 hr = 86400 seconds
                            $updatedData23 = [
                                'last_post_created_time' => time(), // if you want to make likes which are made in between 24 hrs then remove this line.
                                'firstpost_delivery_daytime' => time(),
                                'daily_post_done' => 0,
                                'ig_user_status' => 2,
                                'cronjob_status' => 0,
                                'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                            ];
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData23);
                        }
                    } else if (intval($user->ig_user_status) == 3) {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                }

                // This script will run when autolikes script was stopped due to in-sufficient balance,
                // This code will automatically reset autolikes running script when user add balance in account.
                if (intval($user->ig_user_status) == 3) {
                    if ($user->last_order_total_price != 0) {
                        $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                        $accountBalance = $accountBalanceDetails->account_bal;
                        if ($accountBalance >= $user->last_order_total_price) {
                            $updatedData3 = [
                                'ig_user_status' => 2,
                                'cronjob_status' => 0,
                                'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                            ];
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData3);
                        }
                    } else {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                }


                $expiredFlag = false;
                if ($endDateTime != 0 && $endDateTime < time()) {
                    if ($user->ig_user_status != 4) {
                        $updatedData = [
                            'ig_user_status' => 4,
                            'cronjob_status' => 0,
                            'message' => 'This profile #' . $username . ' has expired. If you wish to continue, increase the end date in edit option. '
                        ];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                    } else {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                    $expiredFlag = true;
                } else {
                    if ($user->ig_user_status == 4) {
                        $updatedData = [
                            'ig_user_status' => 2,
                            'cronjob_status' => 0,
                            'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                        ];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                    }
                }

                if (!$expiredFlag) {
                    if (intval($user->ig_user_status) == 2) {

                        // If the last_post_created_time is 0 then update it with the first post created time.
                        // If profile post count is zero then update last_post_created_time with the current time.
//                            if ($lastPostCreatedTime == 0) {
//                                $data1 = array();
//                                $profilePostCount = $instagramScrape->getProfilePostCountByUsername($username);
//                                if ($profilePostCount != null && $profilePostCount > 0) {
//                                    $userDetails = $instagramScrape->getInsUserDetailsByUsername($username, 1);
//                                    if ($userDetails) {
//                                        $data1['last_post_created_time'] = $userDetails[0]['created_time'];
//                                        $lastPostCreatedTime = $userDetails[0]['created_time'];
//                                    }
//                                } else if ($profilePostCount == 0) {
//                                    $data1['last_post_created_time'] = time();
//                                    $lastPostCreatedTime = time();
//                                }
//                                $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
//                                $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $data1);
//                            }


                        if ($lastPostCreatedTime == 0) {
                            $lastPostCreatedTime = time();
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['last_post_created_time' => time()]);
                        }

                        if ($dailyPostLimit > 0) {

                            if ($picsFetchCount < $picLimit) {

                                if ($dailyPostDone < $dailyPostLimit) {

                                    //scrap all the latest post created after the last post created time
                                    $userProfileData = '';
                                    if (intval($user->plan_type) == 0) {// for likes
                                        $userProfileData = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, $dailyPostLimit, 'image');
                                    } else if (intval($user->plan_type) == 4) { // for video
                                        $userProfileData = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, $dailyPostLimit, 'video');
                                    }

//                                dd($userProfileData);

                                    if (!empty($userProfileData) && $userProfileData != null) {
//                                dd($userProfileData);
                                        $latestPostCreatedTime = 0;
                                        $latestPostCreatedTimeFlag = false;
                                        $latestDeliveryLink = '';
                                        $startTime = time();

                                        foreach ($userProfileData as $key => $value) {
                                            $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; //600= 10 minutes delay in next order
                                            // get the latest post link and place that link  for autolikes order
                                            if (($dailyPostDone < $dailyPostLimit) && ($picsFetchCount < $picLimit)) {

                                                //add order in order table and then in order-process table
                                                // This code is for placing  autolikes order only (likes orders)

                                                // check if auto comment is set or not

                                                $autoCommentsPrice = 0;
                                                $autoCommentsData = array();
                                                if ($user->autoComments == "YES") {

                                                    $autoCommentsPrice = (($user->price_for_autoComments) / 1000) * $user->comments_amount;

                                                    $autoCommentsData['plan_id'] = $user->plan_id_for_autoComments;
                                                    $autoCommentsData['by_user_id'] = $user->by_user_id;
                                                    $autoCommentsData['for_user_id'] = $user->ins_user_id;
                                                    $autoCommentsData['ins_url'] = $value['link'];
                                                    $autoCommentsData['quantity_total'] = $user->comments_amount;
                                                    $autoCommentsData['comment_id'] = $user->custom_comment_id;
                                                    $autoCommentsData['start_time'] = $startTime;
                                                    $autoCommentsData['added_time'] = time();
                                                    $autoCommentsData['updated_time'] = time();
                                                    $autoCommentsData['auto_order_status'] = 1;  // 1=autolikes order
                                                    $autoCommentsData['status'] = 0; // order is in pending state
                                                    $autoCommentsData['price'] = $autoCommentsPrice;
                                                    $autoCommentsData['orders_per_run'] = 0;
                                                    $autoCommentsData['time_interval'] = 0;
                                                    $autoCommentsData['url_type'] = 0;  // 0=postLink
                                                    $autoCommentsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                                }

                                                $autoLikesPrice = (($user->charge_per_unit) / 1000) * $user->likes_per_pic;
                                                $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                                $accountBalance = $accountBalanceDetails->account_bal;

                                                $totalPrice = $autoLikesPrice + $autoCommentsPrice;

                                                if ($accountBalance >= $totalPrice) {

                                                    $autoLikesData['plan_id'] = $user->plan_id;
                                                    $autoLikesData['by_user_id'] = $user->by_user_id;
                                                    $autoLikesData['for_user_id'] = $user->ins_user_id;
                                                    $autoLikesData['ins_url'] = $value['link'];
                                                    $autoLikesData['quantity_total'] = $user->likes_per_pic;
                                                    $autoLikesData['start_time'] = $startTime;
                                                    $autoLikesData['added_time'] = time();
                                                    $autoLikesData['updated_time'] = time();
                                                    $autoLikesData['auto_order_status'] = 1;  // 1=autolikes order
                                                    $autoLikesData['status'] = 0; // order is in pending state
                                                    $autoLikesData['price'] = $autoLikesPrice;
                                                    $autoLikesData['orders_per_run'] = 0;
                                                    $autoLikesData['time_interval'] = 0;
                                                    $autoLikesData['url_type'] = 0;  // 0=postLink
                                                    $autoLikesData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                                    $rollback = false;

                                                    DB::beginTransaction();
                                                    DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();

                                                    $autoLikesOrderInsertedStatus = $objOrderModel->insertOrder($autoLikesData);

                                                    $autoCommentsOrderInsertedStatus = 1;
                                                    if ($user->autoComments == "YES") {
                                                        $autoCommentsOrderInsertedStatus = $objOrderModel->insertOrder($autoCommentsData);
                                                    }
                                                    if ($autoLikesOrderInsertedStatus && $autoCommentsOrderInsertedStatus) {
                                                        $current_bal['account_bal'] = $accountBalance - $totalPrice;
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
                                                        //this code runs only when user make the first post (first order is placed).
                                                        if ($firstPost_deliveryTime_day == 0) {
                                                            $data4['firstpost_delivery_daytime'] = time();
                                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data4);
                                                        }
                                                        ++$picsFetchCount;
                                                        ++$dailyPostDone;
                                                        $latestPostCreatedTime = $value['created_time'];
                                                        $latestDeliveryLink = $value['link'];
                                                        $latestPostCreatedTimeFlag = true;
                                                        //modified by saurabh
                                                        $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; // for adding 10 MORE minutes delay in next order // if flag is not set than order will place at instant
//                                                $startTime += 600;//600= 10 minutes delay in next order placing
                                                    }
                                                } else {

                                                    $data5 = [
                                                        'cronjob_status' => 0,
                                                        'ig_user_status' => 3,
                                                        'last_order_total_price' => $totalPrice,
                                                        'message' => 'Autolikes script has been stopped for # ' . $user->ins_username . ' due to insufficient balance.'
                                                    ];
                                                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data5);
                                                    break;
                                                }
                                            }
                                        } //End of inner foreach loop

                                        // Update details in instagram_users table
                                        if ($latestPostCreatedTimeFlag) {
                                            $data6 ['pics_fetch_count'] = $picsFetchCount;
                                            $data6 ['daily_post_done'] = $dailyPostDone;
                                            $data6 ['last_post_created_time'] = $latestPostCreatedTime;
                                            $data6 ['last_delivery_link'] = $latestDeliveryLink;
                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data6);
                                        }
                                    }
                                }
                            }

                        } else if ($dailyPostLimit == 0) {

                            if ($picsFetchCount < $picLimit) {

                                //scrap all the latest post created after the first post created time
                                $userDetails = '';
                                if ($user->plan_type == 0) {// for likes
                                    $userDetails = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, 0, 'image');
                                } else if ($user->plan_type == 4) { // for video
                                    $userDetails = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, 0, 'video');
                                }

                                if ($userDetails != null) {
                                    $latestPostCreatedTime = 0;
                                    $latestDeliveryLink = '';
                                    $latestPostCreatedTimeFlag = false;
                                    $startTime = time();
                                    foreach ($userDetails as $key => $value) {

                                        $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; //600= 10 minutes delay in next order

                                        // get the latest post link and place that link  for autolikes order
                                        if ($picsFetchCount < $picLimit) {

                                            //add order in order table and then in order-process table
                                            // This code is for placing  autolikes order only (likes orders)

                                            $autoCommentsPrice = 0;
                                            $autoCommentsData = array();
                                            if ($user->autoComments == "YES") {

                                                $autoCommentsPrice = (($user->price_for_autoComments) / 1000) * $user->comments_amount;

                                                $autoCommentsData['plan_id'] = $user->plan_id_for_autoComments;
                                                $autoCommentsData['by_user_id'] = $user->by_user_id;
                                                $autoCommentsData['for_user_id'] = $user->ins_user_id;
                                                $autoCommentsData['ins_url'] = $value['link'];
                                                $autoCommentsData['quantity_total'] = $user->comments_amount;
                                                $autoCommentsData['comment_id'] = $user->custom_comment_id;
                                                $autoCommentsData['start_time'] = $startTime;
                                                $autoCommentsData['added_time'] = time();
                                                $autoCommentsData['updated_time'] = time();
                                                $autoCommentsData['auto_order_status'] = 1;  // 1=autolikes order
                                                $autoCommentsData['status'] = 0; // order is in pending state
                                                $autoCommentsData['price'] = $autoCommentsPrice;
                                                $autoCommentsData['orders_per_run'] = 0;
                                                $autoCommentsData['time_interval'] = 0;
                                                $autoCommentsData['url_type'] = 0;  // 0=postLink
                                                $autoCommentsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                            }


                                            $autoLikesOrViewsPrice = (($user->charge_per_unit) / 1000) * $user->likes_per_pic; //likes_per_pic is same as views per pic
                                            $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                            $accountBalance = $accountBalanceDetails->account_bal;

                                            $totalPrice = $autoLikesOrViewsPrice + $autoCommentsPrice;

                                            if ($accountBalance >= $totalPrice) {

                                                $autoLikesOrViewsData['plan_id'] = $user->plan_id;
                                                $autoLikesOrViewsData['by_user_id'] = $user->by_user_id;
                                                $autoLikesOrViewsData['for_user_id'] = $user->ins_user_id;
                                                $autoLikesOrViewsData['ins_url'] = $value['link'];
                                                $autoLikesOrViewsData['quantity_total'] = $user->likes_per_pic; //likes_per_pic is same as views per pic
                                                $autoLikesOrViewsData['start_time'] = $startTime;
                                                $autoLikesOrViewsData['added_time'] = time();
                                                $autoLikesOrViewsData['updated_time'] = time();
                                                $autoLikesOrViewsData['auto_order_status'] = 1;  // 1=autolikes order
                                                $autoLikesOrViewsData['status'] = 0; // order is in pending state
                                                $autoLikesOrViewsData['price'] = $autoLikesOrViewsPrice;
                                                $autoLikesOrViewsData['orders_per_run'] = 0;
                                                $autoLikesOrViewsData['time_interval'] = 0;
                                                $autoLikesOrViewsData['url_type'] = 0;  // 0=postLink
                                                $autoLikesOrViewsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';

                                                $rollback = false;

                                                DB::beginTransaction();
                                                DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();
                                                $orderInsertedStatus = $objOrderModel->insertOrder($autoLikesOrViewsData);

                                                $commentsOrderInsertedStatus = 1;
                                                if ($user->autoComments == "YES") {
                                                    $commentsOrderInsertedStatus = $objOrderModel->insertOrder($autoCommentsData);
                                                }

                                                if ($orderInsertedStatus && $commentsOrderInsertedStatus) {
                                                    $current_bal['account_bal'] = $accountBalance - $totalPrice;
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
//                                            // Update details in instagram_users table
//                                            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
//                                            $updateInsUserData = [
//                                                'pics_fetch_count' => ++$picsFetchCount,
//                                                'daily_post_done' => ++$dailyPostDone,
//                                                'cronjob_status' => 0,
//                                                'last_check' => time(),
////                                            'last_delivery' => time(),
////                                            'last_delivery_link' => $value['link'],
//                                                'last_post_created_time' => $value['created_time']
//                                            ];
//                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);

                                                    //this code is run only when user make the first post (first order is placed).
                                                    if ($firstPost_deliveryTime_day == 0) {
                                                        $data4['firstpost_delivery_daytime'] = time();
                                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data4);
                                                    }
                                                    ++$picsFetchCount;
                                                    ++$dailyPostDone;
                                                    $latestPostCreatedTime = $value['created_time'];
                                                    $latestDeliveryLink = $value['link'];
                                                    $latestPostCreatedTimeFlag = true;
                                                    //modified by saurabh
                                                    // for adding 10 MORE minutes delay in next order, if flag is not set than order will place at instant
                                                    $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime;
                                                    //$startTime += 600;//600= 10 minutes delay in next order placing
                                                }
                                            } else {
                                                // insert your custom message here in instagram_users table
                                                $updateInsUserMessageData = [
                                                    'cronjob_status' => 0,
                                                    'ig_user_status' => 3,
                                                    'last_order_total_price' => $totalPrice,
                                                    'message' => 'Autolikes script has been stopped for # ' . $user->ins_username . ' due to insufficient balance.'
                                                ];
                                                $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserMessageData);
                                                break;
                                            }
                                        }
                                    } //End of inner foreach loop

                                    // Update details in instagram_users table
                                    if ($latestPostCreatedTimeFlag) {
                                        $data6 ['pics_fetch_count'] = $picsFetchCount;
                                        $data6 ['daily_post_done'] = $dailyPostDone;
                                        $data6 ['last_post_created_time'] = $latestPostCreatedTime;
                                        $data6 ['last_delivery_link'] = $latestDeliveryLink;
                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data6);
                                    }
                                }
                            }
                        }

                        $updateInsUserData = ['cronjob_status' => 0, 'last_check' => time()];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);
                    }
                }

            } //End of Outer foreach loop
        } catch (\Exception $e) {
            $insUserStatus = $instagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]);
        }

    }

    public function checkUserProfile($userDetails)
    {
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();
//        $objInstagramAPI = new API\InstagramAPI\Instagram();
        $instagramUserModel = new Instagram_User();
        $objUsermetaModel = new Usersmeta();
        $objOrderModel = new Order();

        $whereIn = implode(',', array_unique(array_map(function ($v) {
            return $v->ins_user_id;
        }, $userDetails)));

        $insUserStatus = $instagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); //replace with 1

        if (!Session::has('FE_in_checkUserProfile')) { //FE=Fatal_Error
            $fataErrorData['whereIn'] = $whereIn;
            $fataErrorData['modalObject'] = $instagramUserModel;
            $fataErrorData['functionName'] = 'updateUserDetails';
            $fataErrorData['params'] = 'ins_user_id';
            Session::put('FE_in_checkUserProfile', $fataErrorData);
        }

        try {
            $id = 1;
            foreach ($userDetails as $user) {
                $username = $user->ins_username;
                print_r($id . ' . ');
                print_r($username);
                echo "<br>";
                ++$id;
                $username = $user->ins_username;
                $picsFetchCount = intval($user->pics_fetch_count);
//            $picsDone = intval($user->pics_done);
                $picLimit = intval($user->pics_limit);
                $dailyPostLimit = intval($user->daily_post_limit);
                $dailyPostDone = intval($user->daily_post_done);
                $lastPostCreatedTime = intval($user->last_post_created_time);
                $firstPost_deliveryTime_day = intval($user->firstpost_delivery_daytime);
                $orderDelay = intval($user->order_delay_flag);
                $endDateTime = intval($user->end_date_time);

                $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];

                // code for reset daily limit every 24 hr. If daily limit cross then stop  autolikes script for next 24 hrs.
                // Daily limit will be automatically reset every 24 hours from the time the user make the first post
                if ((intval($user->ig_user_status) == 2) || (intval($user->ig_user_status) == 3)) {
                    if ($firstPost_deliveryTime_day != 0) {
                        if ((time() - $firstPost_deliveryTime_day) >= 86400) { //24 hr = 86400 seconds
                            $updatedData23 = [
                                'last_post_created_time' => time(), // if you want to make likes which are made in between 24 hrs then remove this line.
                                'firstpost_delivery_daytime' => time(),
                                'daily_post_done' => 0,
                                'ig_user_status' => 2,
                                'cronjob_status' => 0,
                                'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                            ];
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData23);
                        }
                    } else if (intval($user->ig_user_status) == 3) {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                }

                // This script will run when autolikes script was stopped due to in-sufficient balance,
                // This code will automatically reset autolikes running script when user add balance in account.
                if (intval($user->ig_user_status) == 3) {
                    if ($user->last_order_total_price != 0) {
                        $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                        $accountBalance = $accountBalanceDetails->account_bal;
                        if ($accountBalance >= $user->last_order_total_price) {
                            $updatedData3 = [
                                'ig_user_status' => 2,
                                'cronjob_status' => 0,
                                'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                            ];
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData3);
                        }
                    } else {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                }


                $expiredFlag = false;
                if ($endDateTime != 0 && $endDateTime < time()) {
                    if ($user->ig_user_status != 4) {
                        $updatedData = [
                            'ig_user_status' => 4,
                            'cronjob_status' => 0,
                            'message' => 'This profile #' . $username . ' has expired. If you wish to continue, increase the end date in edit option. '
                        ];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                    } else {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                    $expiredFlag = true;
                } else {
                    if ($user->ig_user_status == 4) {
                        $updatedData = [
                            'ig_user_status' => 2,
                            'cronjob_status' => 0,
                            'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                        ];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                    }
                }
                if (!$expiredFlag) {
                    if (intval($user->ig_user_status) == 2) {
//                        print_r($username);

                        // If the last_post_created_time is 0 then update it with the first post created time.
                        // If profile post count is zero then update last_post_created_time with the current time.
//                            if ($lastPostCreatedTime == 0) {
//                                $data1 = array();
//                                $profilePostCount = $instagramScrape->getProfilePostCountByUsername($username);
//                                if ($profilePostCount != null && $profilePostCount > 0) {
//                                    $userDetails = $instagramScrape->getInsUserDetailsByUsername($username, 1);
//                                    if ($userDetails) {
//                                        $data1['last_post_created_time'] = $userDetails[0]['created_time'];
//                                        $lastPostCreatedTime = $userDetails[0]['created_time'];
//                                    }
//                                } else if ($profilePostCount == 0) {
//                                    $data1['last_post_created_time'] = time();
//                                    $lastPostCreatedTime = time();
//                                }
//                                $whereInstagramUserId = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
//                                $queryResult = $instagramUserModel->updateUserDetails($whereInstagramUserId, $data1);
//                            }


                        if ($lastPostCreatedTime == 0) {
                            $lastPostCreatedTime = time();
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['last_post_created_time' => time()]);
                        }

                        if ($dailyPostLimit > 0) {

                            if ($picsFetchCount < $picLimit) {

                                if ($dailyPostDone < $dailyPostLimit) {

                                    //scrap all the latest post created after the last post created time
                                    $userDetails = [];
                                    if (intval($user->plan_type) == 0) {// for likes
//                                        print_r($username); echo"<br>";
                                        $username = strtolower($username);
//                                        $userDetails = $objInstagramAPI->UserDetailsByUsernameWithLastPostCreatedTime($username, $user->last_post_created_time);
                                        $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, 'image');

//                                        $userProfileData = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, $dailyPostLimit, 'image');
                                    } else if (intval($user->plan_type) == 4) { // for video
                                        $username = strtolower($username);
                                        $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, 'video');
//                                        $userProfileData = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, $dailyPostLimit, 'video');
                                    }

//                                dd($userProfileData);

                                    if ($userDetails != null) { // && $userDetails != "Username does not exist" && $userDetails != "user is private"
                                        $latestPostCreatedTime = 0;
                                        $latestPostCreatedTimeFlag = false;
                                        $latestDeliveryLink = '';
                                        $startTime = time();
                                        $userDetails = array_reverse($userDetails, true);

                                        foreach ($userDetails as $key => $value) {
                                            $startTime = time();
                                            $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; //600= 10 minutes delay in next order
                                            // get the latest post link and place that link  for autolikes order
                                            if (($dailyPostDone < $dailyPostLimit) && ($picsFetchCount < $picLimit)) {

                                                //add order in order table and then in order-process table
                                                // This code is for placing  autolikes order only (likes orders)

                                                // check if auto comment is set or not

                                                $autoCommentsPrice = 0;
                                                $autoCommentsData = array();
                                                if ($user->autoComments == "YES") {

                                                    $autoCommentsPrice = (($user->price_for_autoComments) / 1000) * $user->comments_amount;

                                                    $autoCommentsData['plan_id'] = $user->plan_id_for_autoComments;
                                                    $autoCommentsData['by_user_id'] = $user->by_user_id;
                                                    $autoCommentsData['for_user_id'] = $user->ins_user_id;
                                                    $autoCommentsData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                    $autoCommentsData['quantity_total'] = $user->comments_amount;
                                                    $autoCommentsData['comment_id'] = $user->custom_comment_id;
                                                    $autoCommentsData['start_time'] = $startTime;
                                                    $autoCommentsData['added_time'] = time();
                                                    $autoCommentsData['updated_time'] = time();
                                                    $autoCommentsData['auto_order_status'] = 1;  // 1=autolikes order
                                                    $autoCommentsData['status'] = 0; // order is in pending state
                                                    $autoCommentsData['price'] = $autoCommentsPrice;
                                                    $autoCommentsData['orders_per_run'] = 0;
                                                    $autoCommentsData['time_interval'] = 0;
                                                    $autoCommentsData['url_type'] = 0;  // 0=postLink
                                                    $autoCommentsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                                }

                                                $autoLikesPrice = (($user->charge_per_unit) / 1000) * $user->likes_per_pic;
                                                $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                                $accountBalance = $accountBalanceDetails->account_bal;

                                                $totalPrice = $autoLikesPrice + $autoCommentsPrice;

                                                if ($accountBalance >= $totalPrice) {

                                                    $autoLikesData['plan_id'] = $user->plan_id;
                                                    $autoLikesData['by_user_id'] = $user->by_user_id;
                                                    $autoLikesData['for_user_id'] = $user->ins_user_id;

                                                    //code added and modified by Saurabh

                                                    $autoLikesData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                    $autoLikesData['initial_likes_count'] = $value['likes_count'];
                                                    $autoLikesData['initial_followers_count'] = $value['followers_count'];
                                                    $autoLikesData['initial_comments_count'] = $value['comments_count'];
                                                    $autoLikesData['initial_views_count'] = $value['views_count'];


                                                    $autoLikesData['quantity_total'] = $user->likes_per_pic;
                                                    $autoLikesData['start_time'] = $startTime;
                                                    $autoLikesData['added_time'] = time();
                                                    $autoLikesData['updated_time'] = time();
                                                    $autoLikesData['auto_order_status'] = 1;  // 1=autolikes order
                                                    $autoLikesData['status'] = 0; // order is in pending state
                                                    $autoLikesData['price'] = $autoLikesPrice;
                                                    $autoLikesData['orders_per_run'] = $user->orders_per_run;
                                                    $autoLikesData['time_interval'] = $user->time_interval;
                                                    $autoLikesData['url_type'] = 0;  // 0=postLink
                                                    $autoLikesData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                                    $rollback = false;

                                                    DB::beginTransaction();
                                                    DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();

                                                    $autoLikesOrderInsertedStatus = $objOrderModel->insertOrder($autoLikesData);

                                                    $autoCommentsOrderInsertedStatus = 1;
                                                    if ($user->autoComments == "YES") {
                                                        $autoCommentsOrderInsertedStatus = $objOrderModel->insertOrder($autoCommentsData);
                                                    }
                                                    if ($autoLikesOrderInsertedStatus && $autoCommentsOrderInsertedStatus) {
                                                        $current_bal['account_bal'] = $accountBalance - $totalPrice;
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
                                                        //this code runs only when user make the first post (first order is placed).
                                                        if ($firstPost_deliveryTime_day == 0) {
                                                            $data4['firstpost_delivery_daytime'] = time();
                                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data4);
                                                        }
                                                        ++$picsFetchCount;
                                                        ++$dailyPostDone;
                                                        $latestPostCreatedTime = $value['created_time'];
                                                        $latestDeliveryLink = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                        $latestPostCreatedTimeFlag = true;
                                                        //modified by saurabh
                                                        $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; // for adding 10 MORE minutes delay in next order // if flag is not set than order will place at instant
//                                                $startTime += 600;//600= 10 minutes delay in next order placing
                                                    }
                                                } else {

                                                    $data5 = [
                                                        'cronjob_status' => 0,
                                                        'ig_user_status' => 3,
                                                        'last_order_total_price' => $totalPrice,
                                                        'message' => 'Autolikes script has been stopped for # ' . $user->ins_username . ' due to insufficient balance.'
                                                    ];
                                                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data5);
                                                    break;
                                                }
                                            }
                                        } //End of inner foreach loop

                                        // Update details in instagram_users table
                                        if ($latestPostCreatedTimeFlag) {
                                            $data6 ['pics_fetch_count'] = $picsFetchCount;
                                            $data6 ['daily_post_done'] = $dailyPostDone;
                                            $data6 ['last_post_created_time'] = $latestPostCreatedTime;
                                            $data6 ['last_delivery_link'] = $latestDeliveryLink;
//                                            print_r($data6);
                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data6);
                                        }
                                    }
                                }
                            }

                        } else if ($dailyPostLimit == 0) {

                            if ($picsFetchCount < $picLimit) {

                                //scrap all the latest post created after the first post created time
                                $userDetails = [];
                                if ($user->plan_type == 0) {// for likes
//                                    $objInstagramAPI = new API\InstagramAPI\Instagram();
//                                    print_r($username); echo "<br>";
                                    $username = strtolower($username);
//                                    $userDetails = $objInstagramAPI->UserDetailsByUsernameWithLastPostCreatedTime($username, $user->last_post_created_time);
                                    $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, "image");
//                                    dd($userDetails);
//                                    $userProfileData = (isset($userDetails['instagramUsersData'])) ? $userDetails['instagramUsersData'] : '';


//                                    $userDetails = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, 0, 'image');
//                                    dd($userDetails['instagramUsersData']);
                                } else if ($user->plan_type == 4) { // for video

                                    $username = strtolower($username);
                                    $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, "video");
//                                    $userDetails = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, 0, 'video');
                                }

                                if ($userDetails != null) {// && $userDetails != "Username does not exist" && $userDetails != "user is private"
                                    $latestPostCreatedTime = 0;
                                    $latestDeliveryLink = '';
                                    $latestPostCreatedTimeFlag = false;
                                    $startTime = time();
                                    $userDetails = array_reverse($userDetails, true);
                                    foreach ($userDetails as $key => $value) {
                                        $startTime = time();
                                        $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; //600= 10 minutes delay in next order

                                        // get the latest post link and place that link  for autolikes order
                                        if ($picsFetchCount < $picLimit) {

                                            //add order in order table and then in order-process table
                                            // This code is for placing  autolikes order only (likes orders)

                                            $autoCommentsPrice = 0;
                                            $autoCommentsData = array();
                                            if ($user->autoComments == "YES") {

                                                $autoCommentsPrice = (($user->price_for_autoComments) / 1000) * $user->comments_amount;

                                                $autoCommentsData['plan_id'] = $user->plan_id_for_autoComments;
                                                $autoCommentsData['by_user_id'] = $user->by_user_id;
                                                $autoCommentsData['for_user_id'] = $user->ins_user_id;
                                                $autoCommentsData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                $autoCommentsData['quantity_total'] = $user->comments_amount;
                                                $autoCommentsData['comment_id'] = $user->custom_comment_id;
                                                $autoCommentsData['start_time'] = $startTime;
                                                $autoCommentsData['added_time'] = time();
                                                $autoCommentsData['updated_time'] = time();
                                                $autoCommentsData['auto_order_status'] = 1;  // 1=autolikes order
                                                $autoCommentsData['status'] = 0; // order is in pending state
                                                $autoCommentsData['price'] = $autoCommentsPrice;
                                                $autoCommentsData['orders_per_run'] = 0;
                                                $autoCommentsData['time_interval'] = 0;
                                                $autoCommentsData['url_type'] = 0;  // 0=postLink
                                                $autoCommentsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                            }


                                            $autoLikesOrViewsPrice = (($user->charge_per_unit) / 1000) * $user->likes_per_pic; //likes_per_pic is same as views per pic
                                            $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                            $accountBalance = $accountBalanceDetails->account_bal;

                                            $totalPrice = $autoLikesOrViewsPrice + $autoCommentsPrice;

                                            if ($accountBalance >= $totalPrice) {

                                                $autoLikesOrViewsData['plan_id'] = $user->plan_id;
                                                $autoLikesOrViewsData['by_user_id'] = $user->by_user_id;
                                                $autoLikesOrViewsData['for_user_id'] = $user->ins_user_id;

                                                $autoLikesOrViewsData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                $autoLikesOrViewsData['initial_likes_count'] = $value['likes_count'];
                                                $autoLikesOrViewsData['initial_followers_count'] = $value['followers_count'];
                                                $autoLikesOrViewsData['initial_comments_count'] = $value['comments_count'];
                                                $autoLikesOrViewsData['initial_views_count'] = $value['views_count'];

                                                $autoLikesOrViewsData['quantity_total'] = $user->likes_per_pic; //likes_per_pic is same as views per pic
                                                $autoLikesOrViewsData['start_time'] = $startTime;
                                                $autoLikesOrViewsData['added_time'] = time();
                                                $autoLikesOrViewsData['updated_time'] = time();
                                                $autoLikesOrViewsData['auto_order_status'] = 1;  // 1=autolikes order
                                                $autoLikesOrViewsData['status'] = 0; // order is in pending state
                                                $autoLikesOrViewsData['price'] = $autoLikesOrViewsPrice;
                                                $autoLikesOrViewsData['orders_per_run'] = $user->orders_per_run;
                                                $autoLikesOrViewsData['time_interval'] = $user->time_interval;
                                                $autoLikesOrViewsData['url_type'] = 0;  // 0=postLink
                                                $autoLikesOrViewsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';

                                                $rollback = false;

                                                DB::beginTransaction();
                                                DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();
                                                $orderInsertedStatus = $objOrderModel->insertOrder($autoLikesOrViewsData);

                                                $commentsOrderInsertedStatus = 1;
                                                if ($user->autoComments == "YES") {
                                                    $commentsOrderInsertedStatus = $objOrderModel->insertOrder($autoCommentsData);
                                                }

                                                if ($orderInsertedStatus && $commentsOrderInsertedStatus) {
                                                    $current_bal['account_bal'] = $accountBalance - $totalPrice;
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
//                                            // Update details in instagram_users table
//                                            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
//                                            $updateInsUserData = [
//                                                'pics_fetch_count' => ++$picsFetchCount,
//                                                'daily_post_done' => ++$dailyPostDone,
//                                                'cronjob_status' => 0,
//                                                'last_check' => time(),
////                                            'last_delivery' => time(),
////                                            'last_delivery_link' => $value['link'],
//                                                'last_post_created_time' => $value['created_time']
//                                            ];
//                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);

                                                    //this code is run only when user make the first post (first order is placed).
                                                    if ($firstPost_deliveryTime_day == 0) {
                                                        $data4['firstpost_delivery_daytime'] = time();
                                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data4);
                                                    }
                                                    ++$picsFetchCount;
                                                    ++$dailyPostDone;
                                                    $latestPostCreatedTime = $value['created_time'];
                                                    $latestDeliveryLink = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                    $latestPostCreatedTimeFlag = true;
                                                    //modified by saurabh
                                                    // for adding 10 MORE minutes delay in next order, if flag is not set than order will place at instant
                                                    $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime;
                                                    //$startTime += 600;//600= 10 minutes delay in next order placing
                                                }
                                            } else {
                                                // insert your custom message here in instagram_users table
                                                $updateInsUserMessageData = [
                                                    'cronjob_status' => 0,
                                                    'ig_user_status' => 3,
                                                    'last_order_total_price' => $totalPrice,
                                                    'message' => 'Autolikes script has been stopped for # ' . $user->ins_username . ' due to insufficient balance.'
                                                ];
                                                $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserMessageData);
                                                break;
                                            }
                                        }
                                    } //End of inner foreach loop

                                    // Update details in instagram_users table
                                    if ($latestPostCreatedTimeFlag) {
                                        $data6 ['pics_fetch_count'] = $picsFetchCount;
                                        $data6 ['daily_post_done'] = $dailyPostDone;
                                        $data6 ['last_post_created_time'] = $latestPostCreatedTime;
                                        $data6 ['last_delivery_link'] = $latestDeliveryLink;
//                                        print_r($data6);echo "<br>";
                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data6);
                                    }
                                }
                            }
                        }

                        $updateInsUserData = ['cronjob_status' => 0, 'last_check' => time()];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);
                    }
                }

            } //End of Outer foreach loop
        } catch (\Exception $e) {
            echo $e->getMessage();
            $insUserStatus = $instagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]);
        }

    }

    public function autoViewsScript()
    {
        $instagramUserModel = new Instagram_User();
        $whereStatus = [
            'rawQuery' => 'start_date_time<=? and ig_user_status IN(?,?,?) and cronjob_status=? and plans.plan_type=?',
            'bindParams' => [time(), 2, 3, 4, 0, 4],
        ];
        $userDetails = $instagramUserModel->getUserDetails($whereStatus);

        if (!empty($userDetails) || $userDetails != 0) {
            $this->checkUserProfile($userDetails);
        }
    }

    public function checkUserProfileViews($userDetails)
    {
        $instagramScrape = new API\InstagramAPI\Instagram_scrape();
//        $objInstagramAPI = new API\InstagramAPI\Instagram();
        $instagramUserModel = new Instagram_User();
        $objUsermetaModel = new Usersmeta();
        $objOrderModel = new Order();

        $whereIn = implode(',', array_unique(array_map(function ($v) {
            return $v->ins_user_id;
        }, $userDetails)));

        $insUserStatus = $instagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]); //replace with 1

        if (!Session::has('FE_in_checkUserProfile')) { //FE=Fatal_Error
            $fataErrorData['whereIn'] = $whereIn;
            $fataErrorData['modalObject'] = $instagramUserModel;
            $fataErrorData['functionName'] = 'updateUserDetails';
            $fataErrorData['params'] = 'ins_user_id';
            Session::put('FE_in_checkUserProfile', $fataErrorData);
        }

        try {
            $id = 1;
            foreach ($userDetails as $user) {
                $username = $user->ins_username;
                print_r($id . ' . ');
                print_r($username);
                echo "<br>";
                ++$id;
                $username = $user->ins_username;
                $picsFetchCount = intval($user->pics_fetch_count);
//            $picsDone = intval($user->pics_done);
                $picLimit = intval($user->pics_limit);
                $dailyPostLimit = intval($user->daily_post_limit);
                $dailyPostDone = intval($user->daily_post_done);
                $lastPostCreatedTime = intval($user->last_post_created_time);
                $firstPost_deliveryTime_day = intval($user->firstpost_delivery_daytime);
                $orderDelay = intval($user->order_delay_flag);
                $endDateTime = intval($user->end_date_time);

                $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];

                // code for reset daily limit every 24 hr. If daily limit cross then stop  autolikes script for next 24 hrs.
                // Daily limit will be automatically reset every 24 hours from the time the user make the first post
                if ((intval($user->ig_user_status) == 2) || (intval($user->ig_user_status) == 3)) {
                    if ($firstPost_deliveryTime_day != 0) {
                        if ((time() - $firstPost_deliveryTime_day) >= 86400) { //24 hr = 86400 seconds
                            $updatedData23 = [
                                'last_post_created_time' => time(), // if you want to make likes which are made in between 24 hrs then remove this line.
                                'firstpost_delivery_daytime' => time(),
                                'daily_post_done' => 0,
                                'ig_user_status' => 2,
                                'cronjob_status' => 0,
                                'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                            ];
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData23);
                        }
                    } else if (intval($user->ig_user_status) == 3) {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                }

                // This script will run when autolikes script was stopped due to in-sufficient balance,
                // This code will automatically reset autolikes running script when user add balance in account.
                if (intval($user->ig_user_status) == 3) {
                    if ($user->last_order_total_price != 0) {
                        $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                        $accountBalance = $accountBalanceDetails->account_bal;
                        if ($accountBalance >= $user->last_order_total_price) {
                            $updatedData3 = [
                                'ig_user_status' => 2,
                                'cronjob_status' => 0,
                                'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                            ];
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData3);
                        }
                    } else {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                }


                $expiredFlag = false;
                if ($endDateTime != 0 && $endDateTime < time()) {
                    if ($user->ig_user_status != 4) {
                        $updatedData = [
                            'ig_user_status' => 4,
                            'cronjob_status' => 0,
                            'message' => 'This profile #' . $username . ' has expired. If you wish to continue, increase the end date in edit option. '
                        ];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                    } else {
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
                    }
                    $expiredFlag = true;
                } else {
                    if ($user->ig_user_status == 4) {
                        $updatedData = [
                            'ig_user_status' => 2,
                            'cronjob_status' => 0,
                            'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                        ];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updatedData);
                    }
                }
                if (!$expiredFlag) {
                    if (intval($user->ig_user_status) == 2) {

                        if ($lastPostCreatedTime == 0) {
                            $lastPostCreatedTime = time();
                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, ['last_post_created_time' => time()]);
                        }
                        if ($dailyPostLimit > 0) {

                            if ($picsFetchCount < $picLimit) {

                                if ($dailyPostDone < $dailyPostLimit) {

                                    //scrap all the latest post created after the last post created time
                                    $userDetails = [];
//                                    if (intval($user->plan_type) == 0) {// for likes
////                                        print_r($username); echo"<br>";
//                                        $username = strtolower($username);
////                                        $userDetails = $objInstagramAPI->UserDetailsByUsernameWithLastPostCreatedTime($username, $user->last_post_created_time);
//                                        $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, 'image');
//
////                                        $userProfileData = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, $dailyPostLimit, 'image');
//                                    } else if (intval($user->plan_type) == 4) { // for video
                                    $username = strtolower($username);
                                    $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, 'video');
//                                        $userProfileData = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, $dailyPostLimit, 'video');
//                                    }

//                                dd($userProfileData);

                                    if ($userDetails != null) { // && $userDetails != "Username does not exist" && $userDetails != "user is private"
                                        $latestPostCreatedTime = 0;
                                        $latestPostCreatedTimeFlag = false;
                                        $latestDeliveryLink = '';
                                        $startTime = time();
                                        $userDetails = array_reverse($userDetails, true);

                                        foreach ($userDetails as $key => $value) {
                                            $startTime = time();
                                            $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; //600= 10 minutes delay in next order
                                            // get the latest post link and place that link  for autolikes order
                                            if (($dailyPostDone < $dailyPostLimit) && ($picsFetchCount < $picLimit)) {

                                                //add order in order table and then in order-process table
                                                // This code is for placing  autolikes order only (likes orders)

                                                // check if auto comment is set or not

                                                $autoCommentsPrice = 0;
                                                $autoCommentsData = array();
                                                if ($user->autoComments == "YES") {

                                                    $autoCommentsPrice = (($user->price_for_autoComments) / 1000) * $user->comments_amount;

                                                    $autoCommentsData['plan_id'] = $user->plan_id_for_autoComments;
                                                    $autoCommentsData['by_user_id'] = $user->by_user_id;
                                                    $autoCommentsData['for_user_id'] = $user->ins_user_id;
                                                    $autoCommentsData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                    $autoCommentsData['quantity_total'] = $user->comments_amount;
                                                    $autoCommentsData['comment_id'] = $user->custom_comment_id;
                                                    $autoCommentsData['start_time'] = $startTime;
                                                    $autoCommentsData['added_time'] = time();
                                                    $autoCommentsData['updated_time'] = time();
                                                    $autoCommentsData['auto_order_status'] = 1;  // 1=autolikes order
                                                    $autoCommentsData['status'] = 0; // order is in pending state
                                                    $autoCommentsData['price'] = $autoCommentsPrice;
                                                    $autoCommentsData['orders_per_run'] = 0;
                                                    $autoCommentsData['time_interval'] = 0;
                                                    $autoCommentsData['url_type'] = 0;  // 0=postLink
                                                    $autoCommentsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                                }

                                                $autoLikesPrice = (($user->charge_per_unit) / 1000) * $user->likes_per_pic;
                                                $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                                $accountBalance = $accountBalanceDetails->account_bal;

                                                $totalPrice = $autoLikesPrice + $autoCommentsPrice;

                                                if ($accountBalance >= $totalPrice) {

                                                    $autoLikesData['plan_id'] = $user->plan_id;
                                                    $autoLikesData['by_user_id'] = $user->by_user_id;
                                                    $autoLikesData['for_user_id'] = $user->ins_user_id;

                                                    //code added and modified by Saurabh

                                                    $autoLikesData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                    $autoLikesData['initial_likes_count'] = $value['likes_count'];
                                                    $autoLikesData['initial_followers_count'] = $value['followers_count'];
                                                    $autoLikesData['initial_comments_count'] = $value['comments_count'];
                                                    $autoLikesData['initial_views_count'] = $value['views_count'];


                                                    $autoLikesData['quantity_total'] = $user->likes_per_pic;
                                                    $autoLikesData['start_time'] = $startTime;
                                                    $autoLikesData['added_time'] = time();
                                                    $autoLikesData['updated_time'] = time();
                                                    $autoLikesData['auto_order_status'] = 1;  // 1=autolikes order
                                                    $autoLikesData['status'] = 0; // order is in pending state
                                                    $autoLikesData['price'] = $autoLikesPrice;
                                                    $autoLikesData['orders_per_run'] = 0;
                                                    $autoLikesData['time_interval'] = 0;
                                                    $autoLikesData['url_type'] = 0;  // 0=postLink
                                                    $autoLikesData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                                    $rollback = false;

                                                    DB::beginTransaction();
                                                    DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();

                                                    $autoLikesOrderInsertedStatus = $objOrderModel->insertOrder($autoLikesData);

                                                    $autoCommentsOrderInsertedStatus = 1;
                                                    if ($user->autoComments == "YES") {
                                                        $autoCommentsOrderInsertedStatus = $objOrderModel->insertOrder($autoCommentsData);
                                                    }
                                                    if ($autoLikesOrderInsertedStatus && $autoCommentsOrderInsertedStatus) {
                                                        $current_bal['account_bal'] = $accountBalance - $totalPrice;
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
                                                        //this code runs only when user make the first post (first order is placed).
                                                        if ($firstPost_deliveryTime_day == 0) {
                                                            $data4['firstpost_delivery_daytime'] = time();
                                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data4);
                                                        }
                                                        ++$picsFetchCount;
                                                        ++$dailyPostDone;
                                                        $latestPostCreatedTime = $value['created_time'];
                                                        $latestDeliveryLink = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                        $latestPostCreatedTimeFlag = true;
                                                        //modified by saurabh
                                                        $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; // for adding 10 MORE minutes delay in next order // if flag is not set than order will place at instant
//                                                $startTime += 600;//600= 10 minutes delay in next order placing
                                                    }
                                                } else {

                                                    $data5 = [
                                                        'cronjob_status' => 0,
                                                        'ig_user_status' => 3,
                                                        'last_order_total_price' => $totalPrice,
                                                        'message' => 'Autolikes script has been stopped for # ' . $user->ins_username . ' due to insufficient balance.'
                                                    ];
                                                    $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data5);
                                                    break;
                                                }
                                            }
                                        } //End of inner foreach loop

                                        // Update details in instagram_users table
                                        if ($latestPostCreatedTimeFlag) {
                                            $data6 ['pics_fetch_count'] = $picsFetchCount;
                                            $data6 ['daily_post_done'] = $dailyPostDone;
                                            $data6 ['last_post_created_time'] = $latestPostCreatedTime;
                                            $data6 ['last_delivery_link'] = $latestDeliveryLink;
//                                            print_r($data6);
                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data6);
                                        }
                                    }
                                }
                            }

                        } else if ($dailyPostLimit == 0) {

                            if ($picsFetchCount < $picLimit) {

                                //scrap all the latest post created after the first post created time
                                $userDetails = [];
//                                if ($user->plan_type == 0) {// for likes
////                                    $objInstagramAPI = new API\InstagramAPI\Instagram();
////                                    print_r($username); echo "<br>";
//                                    $username = strtolower($username);
////                                    $userDetails = $objInstagramAPI->UserDetailsByUsernameWithLastPostCreatedTime($username, $user->last_post_created_time);
//                                    $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, "image");
////                                    dd($userDetails);
////                                    $userProfileData = (isset($userDetails['instagramUsersData'])) ? $userDetails['instagramUsersData'] : '';
//
//
////                                    $userDetails = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, 0, 'image');
////                                    dd($userDetails['instagramUsersData']);
//                                } else if ($user->plan_type == 4) { // for video
                                $username = strtolower($username);
                                $userDetails = $instagramScrape->instagramScrape($username, $lastPostCreatedTime, "video");
//                                    $userDetails = $instagramScrape->getInsUserLatestPostDetails($username, $lastPostCreatedTime, 0, 'video');
//                                }

                                if ($userDetails != null) {// && $userDetails != "Username does not exist" && $userDetails != "user is private"
                                    $latestPostCreatedTime = 0;
                                    $latestDeliveryLink = '';
                                    $latestPostCreatedTimeFlag = false;
                                    $startTime = time();
                                    $userDetails = array_reverse($userDetails, true);
                                    foreach ($userDetails as $key => $value) {
                                        $startTime = time();
                                        $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime; //600= 10 minutes delay in next order

                                        // get the latest post link and place that link  for autolikes order
                                        if ($picsFetchCount < $picLimit) {

                                            //add order in order table and then in order-process table
                                            // This code is for placing  autolikes order only (likes orders)

                                            $autoCommentsPrice = 0;
                                            $autoCommentsData = array();
                                            if ($user->autoComments == "YES") {

                                                $autoCommentsPrice = (($user->price_for_autoComments) / 1000) * $user->comments_amount;

                                                $autoCommentsData['plan_id'] = $user->plan_id_for_autoComments;
                                                $autoCommentsData['by_user_id'] = $user->by_user_id;
                                                $autoCommentsData['for_user_id'] = $user->ins_user_id;
                                                $autoCommentsData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                $autoCommentsData['quantity_total'] = $user->comments_amount;
                                                $autoCommentsData['comment_id'] = $user->custom_comment_id;
                                                $autoCommentsData['start_time'] = $startTime;
                                                $autoCommentsData['added_time'] = time();
                                                $autoCommentsData['updated_time'] = time();
                                                $autoCommentsData['auto_order_status'] = 1;  // 1=autolikes order
                                                $autoCommentsData['status'] = 0; // order is in pending state
                                                $autoCommentsData['price'] = $autoCommentsPrice;
                                                $autoCommentsData['orders_per_run'] = 0;
                                                $autoCommentsData['time_interval'] = 0;
                                                $autoCommentsData['url_type'] = 0;  // 0=postLink
                                                $autoCommentsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';  // 0=postLink
                                            }


                                            $autoLikesOrViewsPrice = (($user->charge_per_unit) / 1000) * $user->likes_per_pic; //likes_per_pic is same as views per pic
                                            $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$user->by_user_id]], ['account_bal']);
                                            $accountBalance = $accountBalanceDetails->account_bal;

                                            $totalPrice = $autoLikesOrViewsPrice + $autoCommentsPrice;

                                            if ($accountBalance >= $totalPrice) {

                                                $autoLikesOrViewsData['plan_id'] = $user->plan_id;
                                                $autoLikesOrViewsData['by_user_id'] = $user->by_user_id;
                                                $autoLikesOrViewsData['for_user_id'] = $user->ins_user_id;

                                                $autoLikesOrViewsData['ins_url'] = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                $autoLikesOrViewsData['initial_likes_count'] = $value['likes_count'];
                                                $autoLikesOrViewsData['initial_followers_count'] = $value['followers_count'];
                                                $autoLikesOrViewsData['initial_comments_count'] = $value['comments_count'];
                                                $autoLikesOrViewsData['initial_views_count'] = $value['views_count'];

                                                $autoLikesOrViewsData['quantity_total'] = $user->likes_per_pic; //likes_per_pic is same as views per pic
                                                $autoLikesOrViewsData['start_time'] = $startTime;
                                                $autoLikesOrViewsData['added_time'] = time();
                                                $autoLikesOrViewsData['updated_time'] = time();
                                                $autoLikesOrViewsData['auto_order_status'] = 1;  // 1=autolikes order
                                                $autoLikesOrViewsData['status'] = 0; // order is in pending state
                                                $autoLikesOrViewsData['price'] = $autoLikesOrViewsPrice;
                                                $autoLikesOrViewsData['orders_per_run'] = 0;
                                                $autoLikesOrViewsData['time_interval'] = 0;
                                                $autoLikesOrViewsData['url_type'] = 0;  // 0=postLink
                                                $autoLikesOrViewsData['order_message'] = 'Order has inserted! This order has a schedule time and it will start after (' . $this->getDateDifference($startTime) . ').Please wait to get it started!';

                                                $rollback = false;

                                                DB::beginTransaction();
                                                DB::table('usersmeta')->where('user_id', '=', $user->by_user_id)->lockForUpdate()->get();
                                                $orderInsertedStatus = $objOrderModel->insertOrder($autoLikesOrViewsData);

                                                $commentsOrderInsertedStatus = 1;
                                                if ($user->autoComments == "YES") {
                                                    $commentsOrderInsertedStatus = $objOrderModel->insertOrder($autoCommentsData);
                                                }

                                                if ($orderInsertedStatus && $commentsOrderInsertedStatus) {
                                                    $current_bal['account_bal'] = $accountBalance - $totalPrice;
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
//                                            // Update details in instagram_users table
//                                            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$user->ins_user_id]];
//                                            $updateInsUserData = [
//                                                'pics_fetch_count' => ++$picsFetchCount,
//                                                'daily_post_done' => ++$dailyPostDone,
//                                                'cronjob_status' => 0,
//                                                'last_check' => time(),
////                                            'last_delivery' => time(),
////                                            'last_delivery_link' => $value['link'],
//                                                'last_post_created_time' => $value['created_time']
//                                            ];
//                                            $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);

                                                    //this code is run only when user make the first post (first order is placed).
                                                    if ($firstPost_deliveryTime_day == 0) {
                                                        $data4['firstpost_delivery_daytime'] = time();
                                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data4);
                                                    }
                                                    ++$picsFetchCount;
                                                    ++$dailyPostDone;
                                                    $latestPostCreatedTime = $value['created_time'];
                                                    $latestDeliveryLink = "https://www.instagram.com/p/" . $value['link'] . "/";
                                                    $latestPostCreatedTimeFlag = true;
                                                    //modified by saurabh
                                                    // for adding 10 MORE minutes delay in next order, if flag is not set than order will place at instant
                                                    $startTime = ($orderDelay == 1) ? $startTime + 600 : $startTime;
                                                    //$startTime += 600;//600= 10 minutes delay in next order placing
                                                }
                                            } else {
                                                // insert your custom message here in instagram_users table
                                                $updateInsUserMessageData = [
                                                    'cronjob_status' => 0,
                                                    'ig_user_status' => 3,
                                                    'last_order_total_price' => $totalPrice,
                                                    'message' => 'Autolikes script has been stopped for # ' . $user->ins_username . ' due to insufficient balance.'
                                                ];
                                                $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserMessageData);
                                                break;
                                            }
                                        }
                                    } //End of inner foreach loop

                                    // Update details in instagram_users table
                                    if ($latestPostCreatedTimeFlag) {
                                        $data6 ['pics_fetch_count'] = $picsFetchCount;
                                        $data6 ['daily_post_done'] = $dailyPostDone;
                                        $data6 ['last_post_created_time'] = $latestPostCreatedTime;
                                        $data6 ['last_delivery_link'] = $latestDeliveryLink;
//                                        print_r($data6);echo "<br>";
                                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $data6);
                                    }
                                }
                            }
                        }

                        $updateInsUserData = ['cronjob_status' => 0, 'last_check' => time()];
                        $queryResult = $instagramUserModel->updateUserDetails($whereInsUser, $updateInsUserData);
                    }
                }

            } //End of Outer foreach loop
        } catch (\Exception $e) {
            echo $e->getMessage();
            $insUserStatus = $instagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')'], ['cronjob_status' => 0]);
        }

    }


    public function updateInsUserOrdersCronJob()
    {
        $objInstagramUserModel = new Instagram_User();
        $where = [
            'rawQuery' => 'instagram_users.ig_user_status IN(?,?) and instagram_users.cronjob_status=? ',
            'bindParams' => [2, 5, 0]
        ];

        $insUserList = $objInstagramUserModel->getUserDetails($where);
//        dd($insUserList);
        if (!empty($insUserList) || intval($insUserList) != 0) {
            $this->updateInsUserOrders($insUserList);
        }
    }

    public function updateInsUserOrders($insUserList)
    {
        $objInstagramUserModel = new Instagram_User();
        $orderModel = new Order();

        $whereIn = implode(',', array_unique(array_map(function ($v) {
            return $v->ins_user_id;
        }, $insUserList)));

        $insUserStatus = $objInstagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id IN(' . $whereIn . ')'], ['cronjob_status' => 1]); //replace with 1

        foreach ($insUserList as $insUser) {
            $whereInsUser = ['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUser->ins_user_id]];

            // this script runs only when the instagram user script is not started. adn if the current time is greater than the start time
            if ((intval($insUser->ig_user_status) == 5) && ($insUser->start_date_time < time())) {
                $queryResult = $objInstagramUserModel->updateUserDetails($whereInsUser,
                    [
                        'ig_user_status' => 2,
                        'cronjob_status' => 0,
                        'message' => 'The script is waiting for new post. Searching new post in every 5 minutes!'
                    ]);
            }

            $where = [
                'rawQuery' => 'orders.by_user_id=? and orders.for_user_id=? and orders.auto_order_status=?', // and  orders.status=? //AND 1 group by plans.plan_id //code modified by Saurabh
                'bindParams' => [$insUser->by_user_id, $insUser->ins_user_id, 1]   // ,3 //modified by Saurabh
            ];
            $selectedColumns = [
                DB::raw('
                sum(case when orders.status IN(1,2,3) and ' . $insUser->reset_counter_time . ' < orders.added_time and plans.plan_type=0 then 1 else 0 end) as likesDoneCount,
                sum(case when orders.status IN(1,2,3) and ' . $insUser->reset_counter_time . ' < orders.added_time and ( plans.plan_type=2 or plans.plan_type=3) then 1 else 0 end) as commentsDoneCount,
                sum(case when orders.status IN(1,2,3) and ' . $insUser->reset_counter_time . ' < orders.added_time and plans.plan_type=4 then 1 else 0 end) as viewsDoneCount
                '),
                DB::raw('max(orders.updated_time) as updated_time'),
            ];

            $orderDetails = $orderModel->getAutolikesOrderStatus($where, $selectedColumns);

            if ($orderDetails[0]->likesDoneCount != null && $orderDetails[0]->commentsDoneCount != null &&
                $orderDetails[0]->viewsDoneCount != null && $orderDetails[0]->updated_time != null
            ) {

                if (intval($insUser->daily_post_limit) > 0) {
                    if (intval($insUser->daily_post_done) < intval($insUser->daily_post_limit)) {
                        if (intval($insUser->pics_done) < intval($insUser->pics_limit)) {

                            $updatedData1 = array();
                            if (intval($insUser->plan_type) == 0) { // for likes
                                $updatedData1['pics_done'] = intval($orderDetails[0]->likesDoneCount);

                            } else if ($insUser->plan_id_for_autoComments != null) { // for comments
                                $updatedData1['comments_per_pic_done_count'] = intval($orderDetails[0]->commentsDoneCount);

                            } elseif (intval($insUser->plan_type) == 4) { // for views
                                $updatedData1['pics_done'] = intval($orderDetails[0]->viewsDoneCount); //pics_done is same as views done
                            }

                            $updatedData1['last_delivery'] = intval($orderDetails[0]->updated_time);
                            $updatedData1['cronjob_status'] = 0;
                            $updatedData1['message'] = 'The script is waiting for new post. Searching new post in every 5 minutes!';

                            $queryResult = $objInstagramUserModel->updateUserDetails($whereInsUser, $updatedData1);
                        } else {
                            $updatedData2 = [
                                'cronjob_status' => 0,
                                'ig_user_status' => 1,
                                'message' => 'Thank You!! Your order has been finished. We have processed all your ' . $insUser->pics_limit . ' posts. If you want to restart it again, just restart the total counter . :) '
                            ];
                            $queryResult = $objInstagramUserModel->updateUserDetails($whereInsUser, $updatedData2);
                        }
                    } else {
                        $updatedData31 = [
                            'cronjob_status' => 0,
                            'ig_user_status' => 3,
                            'message' => 'Daily post limit for this user has been reached! The script will start searching new post after 24 hrs!'
                        ];
                        $queryResult = $objInstagramUserModel->updateUserDetails($whereInsUser, $updatedData31);
                    }

                } elseif (intval($insUser->daily_post_limit == 0)) {

                    if (intval($insUser->pics_done) < intval($insUser->pics_limit)) { //modified by Saurabh //daily_post_done

                        $updatedData4 = array();

                        if (intval($insUser->plan_type) == 0) { // for likes
                            $updatedData4['pics_done'] = intval($orderDetails[0]->likesDoneCount);

                        } else if ($insUser->plan_id_for_autoComments != null) { // for comments
                            $updatedData4['comments_per_pic_done_count'] = intval($orderDetails[0]->commentsDoneCount);

                        } elseif (intval($insUser->plan_type) == 4) { // for views
                            $updatedData4['pics_done'] = intval($orderDetails[0]->viewsDoneCount); //pics_done is same as views done
                        }

                        $updatedData4['last_delivery'] = $orderDetails[0]->updated_time;
                        $updatedData4['cronjob_status'] = 0;
                        $updatedData4['message'] = 'The script is waiting for new post. Searching new post in every 5 minutes!';
                        $queryResult = $objInstagramUserModel->updateUserDetails($whereInsUser, $updatedData4);
                    } else {
                        $updatedData5 = [
                            'ig_user_status' => 1,
                            'cronjob_status' => 0,
                            'message' => 'Thank You!! Your order has been finished. We have processed all your ' . $insUser->pics_limit . ' posts. If you want to restart it again, just restart the total counter . :) '
                        ];
                        $queryResult = $objInstagramUserModel->updateUserDetails($whereInsUser, $updatedData5);
                    }
                }
            } else {
                $queryResult = $objInstagramUserModel->updateUserDetails($whereInsUser, ['cronjob_status' => 0]);
            }
        }// end of foreach loop
    }


    //End Autolikes script functions

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
        $objInstagramScrape = new API\InstagramAPI\Instagram_scrape();
        $instagramUsername = "saurabh_bond";
        $startIndex = 1;
        $endIndex = 2;
        $res = $objInstagramScrape->getDetailsByStartAndLastSpreadIndex($instagramUsername, $startIndex, $endIndex);
        dd($res);


        $objInstagramUserModel = new Instagram_User();
        $insUsersId = [161, 40];


        $messages = array();
        foreach ($insUsersId as $key => $insUserId) {
            $queryResult = $objInstagramUserModel->updateUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['pics_fetch_count' => 0, 'pics_done' => 0, 'daily_post_done' => 0, 'reset_counter_time' => time()]);
            $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['ins_username', 'pics_limit']);

            if ($queryResult) {
                $messages[$key] = 'Done! We have reset the post done count for # ' . $instagramUserDetails[0]->ins_username . ' .Autolikes script can process ' . $instagramUserDetails[0]->pics_limit . ' more new posts.';
            } else {
                $messages[$key] = 'Sorry! Some Problem Occurred. Please reload the page and try again.';
            }

//                                    $response->code = 200;
//                                    $response->message = "Success";
//                                    $response->data = $instagramUserDetails[0]->ins_username;
//                                    echo json_encode($response, true);die;

        }
        $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
        $records["customActionMessage"] = $messages;
        dd($records["customActionMessage"]);


        foreach ($insUsersId as $key => $insUserId) {
            //code modified by saurabh //just use the autolikesscript() function here.
            $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]]);
//                                    dd($instagramUserDetails[0]->pics_fetch_count);
            $userExists = $objInstagramScrape->isUsernameExists($instagramUserDetails[0]->ins_username);
            if (is_numeric($userExists)) {
                $oldPicsFetchCount = $instagramUserDetails[0]->pics_fetch_count;
//            dd($oldPicsFetchCount);
                if (($instagramUserDetails[0]->ig_user_status == 2 || $instagramUserDetails[0]->ig_user_status == 3) && $instagramUserDetails[0]->cronjob_status == 0) {
//                                        if (!empty($instagramUserDetails[0]) || $instagramUserDetails[0] != 0) {
//                                            $this->checkUserProfile($instagramUserDetails[0]);
//                                            $messages[$key] = 'hahahsfashash';
//                                        }
                    $this->checkUserProfile($instagramUserDetails);
//                dd($user);

                    $instagramUserDetails = $objInstagramUserModel->getUserDetails(['rawQuery' => 'ins_user_id=?', 'bindParams' => [$insUserId]], ['pics_fetch_count', 'ins_username']);
//                dd($instagramUserDetails[0]->pics_fetch_count);
                    $picsFetchCount = $instagramUserDetails[0]->pics_fetch_count - $oldPicsFetchCount;
//                dd($picsFetchCount);
                    if ($picsFetchCount != 0)
                        $messages[$key] = 'Done! We got ' . $picsFetchCount . ' new posts and added for processing. Please check your order history.';
                    else
                        $messages[$key] = 'There is no any new post for this profile ' . $instagramUserDetails[0]->ins_username . ' .';
                } else {
                    $messages[$key] = '#' . $instagramUserDetails[0]->ins_username . ' has been finished or failed';
                }
            } else {
                $messages[$key] = $instagramUserDetails[0]->ins_username . ' does not exists OR may be private';

            }
        }
        dd($messages);


        $endIndex = 11;
        $link = "https://www.instagram.com/p/BE8Z1Iwq1Ss/?taken-by=saurabh_bond";
//        $res = $objScrapeWebsta->instagram_scrape($username, $lastPostCreatedTime = 123);
//        $res = $objScrapeWebsta->instagramScrape($username, $lastPostCreatedTime = 123, 'image');
//        $res = $objScrapeWebsta->instagramScrapeOfDirectLink($username, $lastPostCreatedTime = 123);
//        $res = $objScrapeWebsta->isUsernameExists($username);
//        $res = $objScrapeWebsta->isVideoPost($username);
//        $res = $objScrapeWebsta->instagramScrapeOfDirectLink($link);
        $res = $objScrapeWebsta->getProfilePicUrl($username);
//        $res = $objScrapeWebsta->instagramScrapeByEndIndex($username, $endIndex);
//       dd(intval(str_replace(",", "",$res)));
        dd($res);

        $res = "{\"code\":200,\"Message\":\"Your requested information has been processed.\"}";
        $res = (json_decode($res, true));
        dd($res['code']);

//        $objModelSocialNator=new API\SocialNator();
//        $order_details = [];
//        $order_details['instagramprofileurl'] = 'https://www.instagram.com/p/BE8Z1IwqSs/';
//        $order_details['amount'] = 1;
//        $order_details['method'] = "add_likes";
//
////$response = $api->add_order($order_details['instagramprofileurl'], $order_details['amount'], $order_details['method']);
//        $result=$objModelSocialNator->add_order($order_details['instagramprofileurl'], $order_details['amount'], $order_details['method']);
//        $result=json_encode($result,true);


        $objUsersmeta = new Usersmeta();
        $objUsersmeta->updateUsermetaWhere(['rawQuery' => 'id=?', 'bindParams' => [7]], ['invite_id' => time() - 1459233158
        ]);
    }

    public function handle_fatal_error()
    {
        if (Session::has('FE_checkProcessOrderStatus')) {
            $queryData = Session::get('FE_in_checkOrderStatus');
            $queryResult = $queryData['modalObject']->$queryData['functionName'](['rawQuery' => '' . $queryData['params'] . ' IN(' . $queryData['whereIn'] . ')'], ['cronjob_status' => 0]);
            Session::forget('FE_checkProcessOrderStatus');
        }

        if (Session::has('FE_in_checkOrderStatus')) {
            $queryData = Session::get('FE_in_checkOrderStatus');
            $queryResult = $queryData['modalObject']->$queryData['functionName'](['rawQuery' => '' . $queryData['params'] . ' IN(' . $queryData['whereIn'] . ')'], ['cronjob_status' => 0]);
            Session::forget('FE_in_checkOrderStatus');
        }


        if (Session::has('FE_in_checkUserProfile')) {
            $queryData = Session::get('FE_in_checkUserProfile');
            $queryResult = $queryData['modalObject']->$queryData['functionName'](['rawQuery' => '' . $queryData['params'] . ' IN(' . $queryData['whereIn'] . ')'], ['cronjob_status' => 0]);
            Session::forget('FE_in_checkUserProfile');
        }
    }

}// END OF CLASS
