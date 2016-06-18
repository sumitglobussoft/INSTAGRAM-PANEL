<?php

namespace InstagramAutobot\Http\Modules\User\Controllers;

use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected $API_URL;
    protected $API_TOKEN;

    public function __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
        $this->API_URL = env('API_URL');
    }

    public function URLinfo(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/URLinfo';
            $postData = $request->all();

            $data = $request->all();
            $data['user_id'] = Session::get('ig_user')['id'];
            $data['api_token'] = $this->API_TOKEN;


            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

            if ($curlResponse->code == 200) {
                echo json_encode(['status' => 'success', 'url_data' => $curlResponse->data], true);
            } else {
                echo json_encode(['status' => 'fail', 'url_data' => null], true);
            }
        } else {
            echo json_encode(['status' => 'fail', 'url_data' => null], true);
        }
    }

    public function addOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/addOrder';
            $postData = $request->all();
//            echo $postData['starting_time'];
//            print_r(date('U = Y-m-d h:i:s A T',strtotime($postData['starting_time'])));echo "\n";
//            dd(strtotime($postData['starting_time']));

            $rules = [
                'plan_id' => 'required',
                'order_url' => 'required|url',
                'quantity' => 'required|integer',
//                'commentType' => 'required',
//                'customCommentType' => 'required'
            ];
            $messages = [
                'plan_id.required' => 'Please select a service',
                'order_url.required' => 'Please enter URL',
                'order_url.regex' => 'Please enter correct URL',
                'quantity.required' => 'Please enter amount to delivery',
            ];

            $validator = Validator::make($postData, $rules, $messages);

            if (!$validator->fails()) {

                $data = $request->all();
                $data['user_id'] = Session::get('ig_user')['id'];
                $data['api_token'] = $this->API_TOKEN;

                //TODO URL VALIDATION HIT CURLGET, BALANCE, QUANTITY

//                $errorMessage = '';
//                $errorMessageFlag = false;
//                $regex = '/^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/+(p)\/(([a-zA-Z0-9\.\-])*)/';
//                if (isset($postData['spreadOrders']) && $postData['spreadOrders'] == 'on') {
//                    if (preg_match($regex, $postData['order_url'])) {
//                        $errorMessage = "Please enter only instagram username link. Ex. www.instagram.com/YourUsername/";
//                        $errorMessageFlag = true;
//                    }
//                }else if (!preg_match($regex, $postData['order_url'])) {
//                    $errorMessage = 'Your link looks invalid! Example of a correct link for this service : http://instagram.com/p/vrTV-bAp9E/';
//                    $errorMessageFlag = true;
//                }
//
//                if (!$errorMessageFlag) {
//
//                }
//
//
//                echo $errorMessageFlag, $errorMessage;
//                echo "<pre>";
//                print_r($data);
//                dd($data);


                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

//                echo "<pre>"; print_r($curlResponse); dd($curlResponse);

                if ($curlResponse->code == 200) {
                    Session::put("ig_user.account_bal", $curlResponse->data['account_bal']);
                    return Redirect::back()->with(['successMessage' => $curlResponse->message]);
                } else if ($curlResponse->code == 204) {
                    return Redirect::back()->withErrors($curlResponse->message)->withInput();
                } else {
                    return Redirect::back()->with(['errorMessage' => $curlResponse->message])->withInput();
                }
            } else {
                return Redirect::back()->withErrors($validator->messages())->withInput();
            }
        } else {
            $url = $this->API_URL . '/user/getAddOrderFormDetails';
            $data['api_token'] = $this->API_TOKEN;
            $data['user_id'] = Session::get('ig_user')['id'];
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            $planDetailsData = $curlResponse->data;

//            dd($planDetailsData);
            $url = $this->API_URL . '/user/getCommentsGroupList';
            $data['api_token'] = $this->API_TOKEN;
            $data['user_id'] = Session::get('ig_user')['id'];
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            $commentsGroupListData = $curlResponse->data;
            //dd($curlResponse);
            return view('User::order.addOrder', ['data' => $planDetailsData, 'commentsGroupListData' => $commentsGroupListData]);
        }
    }

    public function getFilterPlanList(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/getFilterPlanList';
            $data = $request->all();
            $data['api_token'] = $this->API_TOKEN;
            $data['user_id'] = Session::get('ig_user')['id'];

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
                echo json_encode(['status' => 'success', 'data' => $curlResponse->data]);
            } elseif ($curlResponse->code == 201) {
                echo json_encode(['status' => 'emptyList', 'message' => $curlResponse->message]);
            } else {
                echo json_encode(['status' => 'error', 'message' => $curlResponse->message]);
            }
        }

    }

    public function orderHistory(Request $request)
    {
        $url = $this->API_URL . '/user/getOrderHistory';
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $orderList = ($curlResponse->code == 200) ? $curlResponse->data : '';

        $url = $this->API_URL . '/user/getPlanList';
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $planList = ($curlResponse->code == 200) ? $curlResponse->data : '';

        return view('User::order.orderHistory')->with(['orders' => $orderList, 'plansList' => $planList]);


    }

    public function cancelOrder(Request $request)
    {

        $orderId = $request['orderId'];

        $url = $this->API_URL . '/user/cancelOrder';
        $data['api_token'] = $this->API_TOKEN;
        $data['order_id'] = json_encode($request['orderId'], true);

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);


        if ($curlResponse->code == 200) {
            echo json_encode(['status' => 'success', 'message' => $curlResponse->message]);
        } else {
            echo json_encode(['status' => 'fail', 'message' => $curlResponse->message]);
        }
    }

    public function reAddOrder(Request $request)
    {
        $url = $this->API_URL . '/user/reAddOrder';
        $data['api_token'] = $this->API_TOKEN;
        $data['order_id'] = json_encode($request['orderId'], true);
        $data['user_id'] = Session::get('ig_user')['id'];
//dd($data);
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

        if ($curlResponse->code == 200) {
            echo json_encode(['status' => 'success', 'message' => $curlResponse->message]);
        } else {
            echo json_encode(['status' => 'fail', 'message' => $curlResponse->message]);
        }
    }

    public function editOrder(Request $request)
    {
        $url = $this->API_URL . '/user/editOrder';
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];
        $data['order_id'] = $request['orderId'];
        $data['order_url'] = $request['orderLink'];
//dd($data);

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//        dd($curlResponse);

        if ($curlResponse->code == 200) {
            echo json_encode(['status' => 'success', 'message' => $curlResponse->message]);
        } else {
            echo json_encode(['status' => 'fail', 'message' => $curlResponse->message]);
        }
    }

    public function addAutoOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/addOrder';
            $postData = $request->all();

            $rules = [
                'plan_id' => 'required',
                'order_url' => 'required|url',
                'quantity' => 'required|integer',
                'starting_time' => 'required',
//                'commentType' => 'required',
//                'customCommentType' => 'required'
            ];
            $messages = [
                'plan_id.required' => 'Please select a service',
                'order_url.required' => 'Please enter URL',
                'order_url.regex' => 'Please enter correct URL',
                'quantity.required' => 'Please enter amount to delivery',
                'starting_time.required' => 'Please Select Schedule Starting Time'
            ];

            $validator = Validator::make($postData, $rules, $messages);

            if (!$validator->fails()) {

                $data = $request->all();
                $data['user_id'] = Session::get('ig_user')['id'];
                $data['api_token'] = $this->API_TOKEN;

                //TODO URL VALIDATION HIT CURLGET, BALANCE, QUANTITY

                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

                if ($curlResponse->code == 200) {
                    Session::put("ig_user.account_bal", $curlResponse->data['account_bal']);
                    return Redirect::back()->with(['successMessage' => $curlResponse->message]);
                } else if ($curlResponse->code == 204) {
                    return Redirect::back()->withErrors($curlResponse->message)->withInput();
                } else {
                    return Redirect::back()->with(['errorMessage' => $curlResponse->message])->withInput();
                }
            } else {
                return Redirect::back()->with(['errorMessage' => "Please correct the following errors"])->withErrors($validator)->withInput();
            }
        } else {

//            $url = $this->API_URL . '/user/getAddOrderFormDetails';
//            $data['api_token'] = $this->API_TOKEN;
//            $objCurlHandler = CurlRequestHandler::getInstance();
//            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//            $planDetailsData = $curlResponse->data;
//
            return view('User::order.addAutomaticOrder');
        }
    }

    public function addAutolikesOrder(Request $request)
    {
        if ($request->isMethod('post')) {


            $rules = array();
            if ($request['orderType'] == "autolikes") {
                $rules = [
                    'instagramUsername' => 'required',
                    'likesPerPic' => 'required|integer',
                    'picLimit' => 'required|integer',
                    'planId' => 'required|exists:plans,plan_id',
                ];
            } else if ($request['orderType'] == "autoviews") {
                $rules = [
                    'instagramUsername' => 'required',
                    'viewsPerVideo' => 'required|integer',
                    'videoLimit' => 'required|integer',
                    'viewplanId' => 'required|exists:plans,plan_id',
                ];
            }

            if (isset($request['autolikesSubscription']) && ($request['autolikesSubscription'] == 'on')) {
                $pushRules = [
                    'startDate' => 'required|date',
                    'endDate' => 'required|date|after:' . $request['startDate']
                ];
                $rules = array_merge($rules, $pushRules);
            }

            if (isset($request['splitTotalAmounts']) && ($request['splitTotalAmounts'] == 'on')) {
                $splitRules = [
                    'ordersPerRun' => 'required|integer|min:0',
                    'timeInterval' => 'required'
                ];
                $rules = array_merge($rules, $splitRules);
            }

//dd($rules);
            $validator = Validator::make($request->all(), $rules);
            if (!$validator->fails()) {
                $url = $this->API_URL . '/user/addAutolikesOrder';
                $data = $request->all();
//                dd($data);
                $data['api_token'] = $this->API_TOKEN;
                $data['user_id'] = Session::get('ig_user')['id'];

//                dd($data);

                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//                dd($curlResponse);

                if ($curlResponse->code == 200) {
                    echo json_encode(array('status' => 1, 'message' => $curlResponse->message), true);
                } else {
                    echo json_encode(array('status' => 0, 'message' => $curlResponse->message), true);
                }
            } else {
                echo json_encode(array('status' => 0, 'message' => $validator->messages()), true);
            }
        } else {
//            $url = $this->API_URL . '/user/getAutolikesOrderHistory';
//            $data['api_token'] = $this->API_TOKEN;
//            $data['user_id'] = Session::get('ig_user')['id'];
//            $objCurlHandler = CurlRequestHandler::getInstance();
//            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//            $orderHistoryList = "";
//            if ($curlResponse->code == 200) {
//                $orderHistoryList = $curlResponse->data;
//            }


            $url = $this->API_URL . '/user/getPlanList';
            $data['api_token'] = $this->API_TOKEN;
            $data['user_id'] = Session::get('ig_user')['id'];
            $data['filter_plan_list'] = 'yes';
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

            $planList = $curlResponse->data;

            $url = $this->API_URL . '/user/getCommentsGroupList';
            $data['api_token'] = $this->API_TOKEN;
            $data['user_id'] = Session::get('ig_user')['id'];
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            $commentListData = $curlResponse->data;

            return view('User::order.addAutolikesOrder', ['planList' => $planList, 'commentListData' => $commentListData]);
//            return view('User::order.addAutolikesOrder', ['orderHistoryList' => $orderHistoryList, 'planList' => $planList, 'commentListData' => $commentListData]);
        }
    }

    public function getAutolikesOrderHistory(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/getAutolikesOrderHistory';
            $data['api_token'] = $this->API_TOKEN;
            $data['user_id'] = Session::get('ig_user')['id'];
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
                $orderHistoryList = $curlResponse->data;
//                dd($orderHistoryList);
                echo json_encode($orderHistoryList, true);
//                echo "<pre>"; print_r($orderHistoryList); die;
            }
        }
    }

    public function autolikeOrderHistoryAjax(Request $request)
    {
        $url = $this->API_URL . '/user/autolikeOrderHistoryAjax';
        $data['requestData'] = json_encode($request->all(), true);
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

//        dd($curlResponse);
        $records = array();
        if ($curlResponse->code == 200) {
            $records = $curlResponse->data;

            $recordsData = $records['data'];
            $records['data'] = array();

            foreach ($recordsData as $ORkey => $ORvalue) {

                $details = ' <a href="javascript:;" class="show-details" data-toggle="modal" data-target="#showDetails" data-id="' . $ORvalue['ins_user_id'] . '">
                                             <span class="label label-default"> <i class="fa fa-info-circle"></i>&nbsp; Details </span>
                                           </a>';

                if ($ORvalue['ig_user_status'] != 0 || $ORvalue['ig_user_status'] != 1) { // replace with 5 if you don't want edit order option or replace with 2 if you want
                    $details = $details . '&nbsp; <a href="javascript:;" class="edit-user" data-toggle="modal" data-target="#editOrder" data-id="' . $ORvalue['ins_user_id'] . '" data-status="' . $ORvalue['ig_user_status'] . '">
                                <span class="label label-default"><i class="fa fa-pencil"></i>&nbsp; Edit </span>
                            </a>';
                }


                $status = '';
                if ($ORvalue['ig_user_status'] == 0) {
                    $status = '<span class="label label-danger"><i class="fa fa-times-circle"></i>&nbsp; Failed</span>';
                } else if ($ORvalue['ig_user_status'] == 1) {
                    $status = '<span class="label label-success"><i class="fa fa-check-circle"></i>&nbsp; Finished</span>';
                } else if ($ORvalue['ig_user_status'] == 2) {
                    $status = '<span class="label label-info"><i class="fa fa-refresh fa-spin"></i>&nbsp; Waiting</span>';
                } else if ($ORvalue['ig_user_status'] == 3) {
                    $status = '<span class="label label-warning"><i class="fa fa-ban " ></i>&nbsp; Stopped</span>';
                } else if ($ORvalue['ig_user_status'] == 4) {
                    $status = '<span class="label label-warning" style="background-color: darkmagenta"><i class="fa fa-ban" ></i>&nbsp; Expired</span>';
                } else if ($ORvalue['ig_user_status'] == 5) {
                    $status = '<span class="label label-warning" style="background-color: indianred"><i class="fa fa-ban" ></i>&nbsp; Not Yet Started</span>';
                }

                $likesPerPics = '';
//                if ($ORvalue['plan_type'] == 0) {
                $likesPerPics = '<i class="fa fa-heart-o"></i> ' . $ORvalue['likes_per_pic'] . ' Likes/post';
//                } elseif ($ORvalue['plan_type'] == 4) {
//                    $likesPerPics = '<i class="fa fa-eye" aria-hidden="true"></i> ' . $ORvalue['likes_per_pic'] . ' Views/video';
//                }

                $records['data'][] = array(
                    '<input type="checkbox" class="orderCheckBox" name="orderId[]" value="' . $ORvalue['ins_user_id'] . '">',
                    $ORvalue['ins_user_id'],
                    '<p ><a class="btn btn-xs default text-case link-width" href="https://instagram.com/' . $ORvalue['ins_username'] . '/" target="_blank"><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $ORvalue['ins_username'] . '</p>',
                    '<p class="link-width" title="' . $ORvalue['plan_name'] . '"><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $ORvalue['plan_name'] . '</p>',
//                    $ORvalue['plan_name'],
                    '<small><a class="btn btn-xs default text-case" href="#" target="_blank">' . $likesPerPics . '</a></small>',
                    $ORvalue['pics_done'],
                    $ORvalue['pics_limit'],

                    $ORvalue['start_date_time'],
                    $ORvalue['end_date_time'],
//                    gmdate("Y-m-d H:i:s ", $ORvalue['end_date_time']),//$ORvalue['end_date_time'],
                    $ORvalue['last_check'],
//                    $ORvalue['last_delivery'],
                    $status,

                    $details
                );
            }
//            dd($records);
            echo json_encode($records, true);
        }
    }

    public function getUserPreviousDetails(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/getUserPreviousDetails';
            $data['api_token'] = $this->API_TOKEN;
            $data['ins_user_id'] = $request['ins_user_id'];

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
                $userDetails = $curlResponse->data;
                echo json_encode(array('data' => $userDetails), true);
            }
        }
    }

    public function updateUserOrderDetails(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'edit_likesPerPic' => 'required|integer',
                'edit_picLimit' => 'required|integer',
                'edit_planId' => 'required|exists:plans,plan_id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if (!$validator->fails()) {
                $url = $this->API_URL . '/user/updateUserOrderDetails';
                $data = $request->all();
                $data['api_token'] = $this->API_TOKEN;
                $data['user_id'] = Session::get('ig_user')['id'];
                $data['ins_user_id'] = $request['ins_user_id'];


                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

                if ($curlResponse->code == 200) {
                    echo json_encode(array('status' => 'success', 'message' => $curlResponse->message), true);
                } else {
                    echo json_encode(array('status' => 'fail', 'message' => $curlResponse->message), true);
                }
            } else {
                echo json_encode(array('status' => 'fail', 'message' => $validator->messages()), true);
            }

        }
    }

    public function temp(Request $request)
    {
        $url = $this->API_URL . '/user/tempajax';
        $data['requestData'] = json_encode($request->all(), true);
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//        echo $curlResponse->code; die;
//        dd($curlResponse);
//        var_dump($curlResponse);
        $records = array();
        if ($curlResponse->code == 200) {
            $records = $curlResponse->data;

            $recordsData = $records['data'];
            $records['data'] = array();
            $status_list = array(
                0 => 'pending',
                1 => 'Queue',
                2 => 'processing',
                3 => 'completed',
                4 => 'refunded',
                5 => 'Error',
                6 => 'cancelled'
            );

            foreach ($recordsData as $ORkey => $ORvalue) {

//                $details='';

                $price = '$' . $ORvalue['price'];
                $records['data'][] = array(
                    '<input type="checkbox" class="orderCheckBox" name="orderId[]" value="' . $ORvalue['order_id'] . '">',
//                '<div class="checker"><span><input type="checkbox"  class="orderCheckBox"  name="orderId[]"  value="' . $ORvalue['order_id'] . '"
//                                                                                          ></span></div>',
                    $ORvalue['order_id'],
                    '<small><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $ORvalue['plan_name'] . '</small>',
                    '<small><a target="_blank" href="' . $ORvalue['ins_url'] . '">' . $ORvalue['ins_url'] . '</a> </small>',
                    $ORvalue['quantity_total'],
                    $price,
                    $ORvalue['added_time'],
                    $ORvalue['updated_time'],
                    '<span class="label label-info"> <i class="fa fa-clock-o"></i>' . $status_list[$ORvalue['status']] . '</span>',
                    '<button data-toggle="tooltip" title="popover" class="btn popovers btn-default btn-xs materialRipple-light materialRipple-btn"><i class="fa fa-info-circle"></i> Details
                                                        <div class="materialRipple-md-ripple-container"></div></button>'
                );
            }

//            dd($records);
            echo json_encode($records, true);
        }
    }


    public function orderHistoryAjax(Request $request)
    {
        $url = $this->API_URL . '/user/orderHistoryAjax';
        $data['requestData'] = json_encode($request->all(), true);
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

//       dd($curlResponse);

        $records = array();
        if ($curlResponse->code == 200) {
            $records = $curlResponse->data;
//dd($records);
            $recordsData = $records['data'];
            $records['data'] = array();
            $status_list = array(
                0 => '<span class="label label-primary"> <i class="fa fa-clock-o"></i>&nbsp; Pending</span>',
                1 => '<span class="label label-info"> <i class="fa fa-spin fa-refresh"></i>&nbsp; Processing</span>',//'Queue',
                2 => '<span class="label label-info"> <i class="fa fa-spin fa-refresh"></i>&nbsp; Processing</span>',
                3 => '<span class="label label-success"> <i class="fa fa-check-circle"></i>&nbsp; Completed</span>',
                4 => '<span class="label label-primary" style="background-color: #d5ab07"> <i class="fa fa-dollar"></i>&nbsp; Refunded</span>',
                5 => '<span class="label label-danger"> <i class="fa fa-ban"></i>&nbsp; Error</span>',
                6 => '<span class="label label-warning" style="background-color: indianred "> <i class="fa fa-times-circle"></i>&nbsp; Cancelled</span>',
                7 => '<span class="label label-success" style="background-color: green; text-align: center"> <i class="fa fa-check-circle"></i>&nbsp; Added</span>',
            );
            foreach ($recordsData as $ORkey => $ORvalue) {

                //for display more details
                $service = $ORvalue['plan_name'];
                $startCount = 0;
                if ($ORvalue['plan_type'] == 0) {
                    $startCount = $ORvalue['initial_likes_count'];
                } else if ($ORvalue['plan_type'] == 1) {
                    $startCount = $ORvalue['initial_followers_count'];
                } else {
                    $startCount = $ORvalue['initial_comments_count'];
                }

                $currentCount = '';
                $remainCount = $ORvalue['quantity_total'] - $ORvalue['quantity_done'];
                $finishCount = $ORvalue['quantity_done'];

                $details = ' <a href="javascript:;" class="show-details" data-toggle="modal" data-target="#showDetails" data-id="' . $ORvalue['order_id'] . '">
                                              <span class="label label-default"> Details </span>
                                           </a>';

                if ($ORvalue['status'] == 0) { // replace with 0 if you want edit order option
                    $details = $details . '&nbsp; <a href="javascript:;" class="edit-order" data-toggle="modal" data-target="#editOrder" data-id="' . $ORvalue['order_id'] . '">
                                              <span class="label label-default"> Edit </span>
                                           </a>';
                }
                $price = '$' . $ORvalue['price'];


                $records['data'][] = array(
                    '<input type="checkbox" class="orderCheckBox" name="orderId[]" value="' . $ORvalue['order_id'] . '">',
                    $ORvalue['order_id'],
                    '<p class="link-width" title="' . $ORvalue['plan_name'] . '"><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $ORvalue['plan_name'] . '</p>',
                    '<p class="link-width" title="' . $ORvalue['ins_url'] . '"><a target="_blank" href="' . $ORvalue['ins_url'] . '">' . $ORvalue['ins_url'] . '</a> </p>',
                    $ORvalue['quantity_total'],
                    $price,
                    $ORvalue['added_time'],
                    $ORvalue['updated_time'],
                    $status_list[$ORvalue['status']],
                    $details
                );
            }

//            dd($records);
            echo json_encode($records, true);
        }
    }

    public function getMoreOrderDetails(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/getMoreOrderDetails';
            $data['order_id'] = $request['orderId'];
            $data['api_token'] = $this->API_TOKEN;

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//dd($curlResponse);
            if ($curlResponse->code == 200) {
                echo json_encode(array('status' => 'success', 'data' => $curlResponse->data), true);
            } else {
                echo json_encode(array('status' => 'fail', 'message' => $curlResponse->message), true);
            }


        }
    }

    public function getMoreAutolikesOrderDetails(Request $request)
    {

        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/getMoreAutolikesOrderDetails';
            $data['ins_user_id'] = $request['instagramUserId'];
            $data['api_token'] = $this->API_TOKEN;

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
                echo json_encode(array('status' => 'success', 'data' => $curlResponse->data), true);
            } else {
                echo json_encode(array('status' => 'fail', 'message' => $curlResponse->message), true);
            }


        }
    }

    public function pricingInformation()
    {
        $url = $this->API_URL . '/user/pricingInformation';
        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        if ($curlResponse->code == 200) {
            return view('User::order.pricingInformation')->with(['plansList' => $curlResponse->data]);
        } else {
            return view('User::order.pricingInformation')->with(['errorMessage' => $curlResponse->message]);
        }
    }

}
