<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Models\Comment;
use App\Http\Models\Order;
use App\Http\Models\Plan;
use App\Http\Models\User;
use App\Http\Models\Usersmeta;
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
                $whereGroupID = [
                    'rawQuery' => 'plans.plangroup_id=?',
                    'bindParams' => ['1']
                ];
                $plansDetails = $objPlanModel->getPlansDetails($whereGroupID);
                if ($plansDetails) {
                    $response->code = 200;
                    $response->message = "Plans and Price Details";
                    $response->data = $plansDetails;
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

    public function getCommentsList(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objCommentModel = new Comment();
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
                $rules = ['user_id' => "required|exists:users,id"];
                $validator = Validator::make($postData, $rules);
                if (!$validator->fails()) {
                    $whereUserID = [
                        'rawQuery' => "added_by=? and comment_group_name!=''",
                        'bindParams' => [$userId]
                    ];
                    $commentListDetails = $objCommentModel->getCommentList($whereUserID);

                    if ($commentListDetails) {
                        $response->code = 200;
                        $response->message = "List of Comments";
                        $response->data = $commentListDetails;
                        echo json_encode($response, true);
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

    public function  addOrder(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objUsermetaModel = new Usersmeta();
            $objOrderModel = new Order();
            $objPlanModel = new Plan();
            $objCommentModel = new Comment();
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
                    'plan_id' => 'required|exists:plans,plan_id',
                    'user_id' => "required|exists:users,id",
                    'order_url' => "required|url",//|regex: $regex",//TODO:
                    'quantity' => "required|integer",
                    'starting_time' => "required",
                    'commentType' => 'required',
                    'customCommentType' => 'required',
                    'comment_id' => 'required'
                ];
                $messages = [
                    'plan_id.exists' => 'Please choose a service',
                    'user_id.required' => 'User Id is required',
                    'order_url.required' => 'Please enter URL',
                    'quantity.required' => 'Please enter amount to delivery',
                    'starting_time' => 'Please Select Schedule Starting Time',
                ];

                $validatePlanId = Validator::make($postData, $rules, $messages);

                if (!$validatePlanId->fails()) {

                    $planId = $postData['plan_id'];
                    $userId = $postData['user_id'];
                    $orderUrl = $postData['order_url'];
                    $quantity = $postData['quantity'];
                    $startingTime = $postData['starting_time'];

                    $planDetails = $objPlanModel->getPlansDetails(['rawQuery' => 'plans.plangroup_id=? and plan_id=?', 'bindParams' => ['1', $planId]]);
                    $maxQuantity = $planDetails[0]->max_quantity;
                    $chargePerUnit = $planDetails[0]->charge_per_unit;
                    $planType = $planDetails[0]->plan_type;


                    $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$userId]], ['account_bal']);
                    $accountBalance = $accountBalanceDetails->account_bal;

                    $postData['total_order_price'] = $chargePerUnit * $quantity;


                    // $regex = '/[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/';
                    // $regex = '/(^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9]-*)*[a-z0-9]+)(?:\.(?:[a-z0-9]-*)*[a-z0-9]+)*(?:\.(?:[a-z]{2,})).?)(?::\d{2,5})?(?:[\/?#]\S*)?$)+/';

                    $rules = [
                        'quantity' => "required|integer|max:$maxQuantity",
                        'total_order_price' => "required|numeric|max:$accountBalance",
                    ];
                    $messages = [
                        'quantity.required' => 'Please enter amount to delivery',
                        'total_order_price.max' => 'Insufficient Balance while placing an order',
                    ];

                    //TODO URL VALIDATION HIT CURLGET,

                    $validator = Validator::make($postData, $rules, $messages);


                    if (!$validator->fails()) {



                        $data['plan_id'] = $planId;
                        $data['by_user_id'] = $userId;
                        $data['ins_url'] = $orderUrl;
                        $data['quantity_total'] = $quantity;
                        $data['start_time'] = $startingTime;
                        $data['status'] = 2;

                        $comment_id = "";
                        $commentsData = "";
                        $commentsTextAreaFlag = false;

                        switch ($planType) {
                            case 0;
                                if (isset($postData['spreadOrders'])) {
                                    $data['start_index'] = $postData['startSpreadIndex'];
                                    $data['end_index'] = $postData['endSpreadIndex'];
                                }
                                break;
                            case 1;//reserve for follow code
                                break;
                            case 2;
                                if ($postData['commentType'] == 0) { // random comments
                                    $comment_id = 0;
                                } else { // custom comments
                                    if ($postData['customCommentType'] == 0) { //Write comments
                                        $commentsTextAreaFlag = true;
                                        $comments = $postData['commentsTextArea'];
                                        $commentsArray = explode("\r\n", $comments);
                                        $commentsJson = json_encode($commentsArray);
                                        $commentsData['comments'] = $commentsJson;
                                        $commentsData['added_by'] = $userId;
                                    } else { // Select Comment group
                                        $comment_id = $postData['comment_id'];
                                    }
                                }
                                break;
                            default:
                                break;
                        }



                        //TODO PRODUCT LOCKING, DB NOCOMMIT IN LARAVEL
                        $rollback = false;
                        DB::beginTransaction();

                        DB::table('usersmeta')->where('user_id', '=', $userId)->lockForUpdate()->get();

                        if ($commentsTextAreaFlag) {
                            $commentsInsertedID = $objCommentModel->insertComments($commentsData);
                            $data['comment_id'] = $commentsInsertedID;
                        } else {
                            $data['comment_id'] = $comment_id;
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

}// END OF CLASS
