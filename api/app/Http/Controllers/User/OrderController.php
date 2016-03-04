<?php

namespace App\Http\Controllers\User;

use App\Http\Models\Comment;
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
                $whereUserID = [
                    'rawQuery' => "added_by=? and comment_group_name!=''",
                    'bindParams' => [1] //[$userId]
                ];
                $selectColumn = array('comment_id', 'comment_group_name', 'comments');
                $commentListDetails = $objCommentModel->getCommentList($whereUserID, $selectColumn);

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
//        $objIgersLike = new API\IgersLike();
//        $orderId = 2588982;
//        $result = $objIgersLike->order_status($orderId);
//
//        dd($result);
//


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
                    $startingTime = strtotime($postData['starting_time']); //TODO if time is not entered then pick up current time and start order after one hour

                    $planDetails = $objPlanModel->getPlansDetails(['rawQuery' => 'plans.plangroup_id=? and plan_id=?', 'bindParams' => ['1', $planId]]);
                    $maxQuantity = $planDetails[0]->max_quantity;
                    $chargePer1K = $planDetails[0]->charge_per_unit;
                    $planType = $planDetails[0]->plan_type;


                    $accountBalanceDetails = $objUsermetaModel->getUsermetaWhere(['rawQuery' => 'user_id=?', 'bindParams' => [$userId]], ['account_bal']);
                    $accountBalance = $accountBalanceDetails->account_bal;

                    $postData['total_order_price'] = ($chargePer1K / 1000) * $quantity;


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
                        $data['start_time'] = $startingTime - 19800;
                        $data['added_time'] = time() + 19800;
                        $data['status'] = 0;
                        $data['price'] = $postData['total_order_price']; // TODO added extra in Local DB

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
                                        $commentsJson = json_encode($commentsArray, true);
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

    public function getOrderHistory(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
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
                $validator = Validator::make($postData, ['user_id' => 'required']);
                if (!$validator->fails()) {
                    $whereOderUserID = [
                        'rawQuery' => 'by_user_id=? and orders.status!=6',
                        'bindParams' => [$userId]
                    ];
                    $userOrderHistory = $objOrderModel->getOrderHistory($whereOderUserID);

//                  echo'<pre>';  print_r($userOrderHistory);


                    foreach ($userOrderHistory as $key => $value) {

                        $currentTime = time()+ 19800;

                        //$previousTime = strtotime($value->added_time);
                        $previousTime = $value->added_time;


                        $timeDifferent=$currentTime-($currentTime-49);
                        print_r(date('U = Y-m-d H:i:s a T',$currentTime ));echo "\n";
                         print_r(date('U = Y-m-d H:i:s a T',$previousTime));echo "\n";
                        print_r(date('U = i:s a T',$currentTime-($currentTime-59)));echo "\n";

                        $seconds=0;
                        $minutes=0;
                        $hours=0;
                        $years=0;

                        while($timeDifferent){

                        }

                        die;

                    }

//                    dd($userOrderHistory);
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

    public function cancelOrder(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
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
                if (empty($orderData))
                    $orderData = "";
                else
                    $orderData = $orderData[0];

                $postData['order_id'] = $orderData;

                $validator = Validator::make($postData, ['order_id' => 'required'], ['order_id.required' => 'Order Id is required']);
                if (!$validator->fails()) {
                    //TODO WRITE API CODE HERE
                    $messages = array();
                    $orderID = json_decode($request['order_id'], true);

                    foreach ($orderID as $key => $order_id) {
                        $where = [
                            'rawQuery' => 'order_id=?',
                            'bindParams' => [$order_id]
                        ];
                        $orderStatus = $objOrderModel->getOrderStatus($where, ['status']);
//                        dd($orderStatus[0]->status);
                        if ($orderStatus) {
                            if ($orderStatus[0]->status == 0 || $orderStatus[0]->status == 1 || $orderStatus[0]->status == 2) {

                                $result = $objOrderModel->updateOrder($where, ['status' => 6]);
                                if ($result) {
                                    $messages[$key] = "This order is now canceled and the money is deposited back in your account, order_id :" . $order_id . "\n";
                                } else {
                                    $messages[$key] = "There is an problem in order #ID " . $order_id . " cancellation process.\n";
                                }
                            } else if ($orderStatus[0]->status == 3 || $orderStatus[0]->status == 4 || $orderStatus[0]->status == 5 || $orderStatus[0]->status == 6) {
                                $messages[$key] = "Your order with the ID #" . $order_id . " cannot be refunded because its already added on the system.\n";
                            }
                        } else {
                            $messages[$key] = "This order #ID " . $order_id . " is invalid \n";
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
                if (empty($orderData))
                    $orderData = "";
                else
                    $orderData = $orderData[0];

                $postData['order_id'] = $orderData;

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
                    //$orderID = json_decode($postData['order_id'], true);
                    $orderID = json_decode($request['order_id'], true);

                    foreach ($orderID as $key => $order_id) {
                        $where = [
                            'rawQuery' => 'order_id=?',
                            'bindParams' => [$order_id]
                        ];
                        $eachOrderDetails = $objOrderModel->getOrderHistory($where);
                        unset($eachOrderDetails[0]->order_id);

                        $data = "";
                        foreach ($eachOrderDetails[0] as $key => $value) {
                            $data[$key] = $value;
                        }
                        $data['status'] = 0;
                        $data['added_time'] = time();
                        $data['start_time'] = time(); //TODO write current date and old time


                        dd($data);
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

    public function addOrderToServerCronJob()
    {
        $date = new \DateTime();
        // print_r($date);

//        $date->setTimezone(new \DateTimeZone('Asia/Calcutta'));

//        dd($date->getTimezone());
//        dd(time()+19800);
//        echo "<pre>";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:30 PM')-19800));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',time()));echo "\n";
//
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:30 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',time()+19800));echo "\n";
//
//
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:55 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:56 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:57 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:58 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:58 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:59 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:59 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 12:59 PM')));echo "\n";
//        print_r(date('U = Y-m-d H:i:s a T',strtotime('02/13/2016 01:00 PM')));echo "\n";
//
//        dd();


        $orderModel = new Order();
        $whereOrderStatus = [
            'rawQuery' => 'start_time < ? and orders.status=?',
            'bindParams' => [time() + 19800, 0]
        ];
        $orderDetails = $orderModel->getOrderHistory($whereOrderStatus);
        if (!empty($orderDetails)) {
            $this->processOrders($orderDetails);
        }
    }

    public function processOrders($orderDetails)
    {
        $orderModel = new Order();
        foreach ($orderDetails as $order) {
            $whereOrderStatus = [
                'rawQuery' => 'order_id=?',
                'bindParams' => [$order->order_id]
            ];
            $orderList = $orderModel->updateOrder($whereOrderStatus, ['status' => 1]);
        }

        foreach ($orderDetails as $order) {
            if ($order->supplier_server_id == 1) {
                //process order for igerslike API

                $url = $order->ins_url;
                $type = $order->plan_name_code;
                $amount = $order->quantity_total;

                $objIgersLike = new API\IgersLike();
                $result = $objIgersLike->order_add($url, $type, $amount);

                $result = json_decode($result, true);

                if ($result['status'] == 'ok') {
                    $whereOrderStatus = [
                        'rawQuery' => 'order_id=?',
                        'bindParams' => [$order->order_id]
                    ];
                    $data = array(
                        'server_order_id' => $result['order'],
                        'updated_time' => time() + 19800
                    );
                    $orderList = $orderModel->updateOrder($whereOrderStatus, $data);
//                  echo "ok", "\n";
                } else if ($result['status'] == 'fail') {
                    $whereOrderStatus = [
                        'rawQuery' => 'order_id=?',
                        'bindParams' => [$order->order_id]
                    ];
                    $orderList = $orderModel->updateOrder($whereOrderStatus, ['status' => 1]);
//                    echo "fail", "\n";
                }
            }

            if ($order->supplier_server_id == 2) {
                //process order for cheapbulk API

            }

            if ($order->supplier_server_id == 3) {
                //process order for sociL panel 24 API

            }
        }
    }


    public function updateOrderStatusCronJob()
    {
        $orderModel = new Order();
        $whereOrderStatus = [
            'rawQuery' => '(orders.status=? or orders.status=? or orders.status=?) and orders.cronjob_status=?',
            'bindParams' => [[0, 1, 2], 0]
        ];
        $orderList = $orderModel->getOrderStatus($whereOrderStatus, ['orders.order_id', 'orders.server_order_id', 'orders.status', 'supplier_servers.supplier_name', 'plans.supplier_server_id']);
        if (!empty($orderList)) {
            $this->checkOrderStatus($orderList);
        }
    }

    public function checkOrderStatus($orderList)
    {
        $orderModel = new Order();
        foreach ($orderList as $order) {
            $whereOrderStatus = [
                'rawQuery' => 'order_id=?',
                'bindParams' => [$order->order_id]
            ];
            $orderStatus = $orderModel->updateOrder($whereOrderStatus, ['cronjob_status' => 1]);
        }

        foreach ($orderList as $order) {
//            if ($order->supplier_name == 'igerslike.com') {

            if ($order->supplier_server_id == 1) {
                //process order status for igerslike API

                $objIgersLike = new API\IgersLike();
                $orderId = $order->server_order_id;
                $result = $objIgersLike->order_status($orderId);
                $result = json_decode($result, true);

                $orderStatus = "";
                $updateOrderStatusWhereOrderID = [
                    'rawQuery' => 'order_id=?',
                    'bindParams' => [$order->order_id]
                ];
                if ($result['status'] == 'ok') {
                    if ($result['order_status'] == 'Pending') $orderStatus = 0;
                    else if ($result['order_status'] == 'Processing') $orderStatus = 2;
                    else if ($result['order_status'] == 'Completed') $orderStatus = 3;
                    else if ($result['order_status'] == 'Refunded') $orderStatus = 4;
                    else if ($result['order_status'] == 'Refunded Partial') $orderStatus = 5;

                    $result = $orderModel->updateOrder($updateOrderStatusWhereOrderID, ['status' => $orderStatus, 'cronjob_status' => 0]);
//                    echo "<pre>"; echo $result, 'ok';
                } else if ($result['status'] == 'fail') {
                    $result = $orderModel->updateOrder($updateOrderStatusWhereOrderID, ['cronjob_status' => 0]);
//                    echo "<pre>"; echo $result, 'fail';
                }
            }
            if ($order->supplier_server_id == 2) {
                //process order for cheapbulk API

            }

            if ($order->supplier_server_id == 3) {
                //process order for sociL panel 24 API

            }
        }
    }

}// END OF CLASS
