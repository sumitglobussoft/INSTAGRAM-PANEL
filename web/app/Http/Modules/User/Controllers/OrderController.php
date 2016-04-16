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
use DateTime;
use DateTimeZone;

class OrderController extends Controller
{
    protected $API_URL;
    protected $API_TOKEN;

    public function __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
        $this->API_URL = env('API_URL');
    }


    public function addOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/addOrder';
            $postData = $request->all();
//dd($postData);
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

//                echo "<pre>"; print_r($data); dd($data);

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

    function converToTz($time = "", $fromTz = '', $toTz = '')
    {
        // timezone by php friendly values
        $date = new DateTime($time, new DateTimeZone($fromTz));
        $date->setTimezone(new DateTimeZone($toTz));
        $o = new \ReflectionObject($date);
        $p = $o->getProperty('date');
        $date = $p->getValue($date);
        return strtotime($date);
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
            $rules = [
                'instagramUsername' => 'required',
                'likesPerPic' => 'required|integer',
                'picLimit' => 'required|integer',
                'planId' => 'required|exists:plans,plan_id',
            ];

            $validator = Validator::make($request->all(), $rules);
            if (!$validator->fails()) {
                $url = $this->API_URL . '/user/addAutolikesOrder';
                $data = $request->all();
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

        $records = array();
        if ($curlResponse->code == 200) {
            $records = $curlResponse->data;

            $recordsData = $records['data'];
            $records['data'] = array();

            foreach ($recordsData as $ORkey => $ORvalue) {

                $details = ' <a href="javascript:;" class="show-details" data-toggle="modal" data-target="#showDetails" data-id="' . $ORvalue['ins_user_id'] . '">
                                              <span class="label label-default"> Details </span>
                                           </a>';

                if ($ORvalue['ig_user_status'] == 2) { // replace with 0 if you want edit order option
                    $details = $details . '&nbsp; <a href="javascript:;" class="edit-user" data-toggle="modal" data-target="#editOrder" data-id="' . $ORvalue['ins_user_id'] . '">
                                <span class="label label-default"> Edit </span>
                            </a>';
                }


                $status = '';
                if ($ORvalue['ig_user_status'] == 0) {
                    $status = '<span class="label label-success"><i class="icon-warning-sign"></i>&nbsp; Failed</span>';
                } else if ($ORvalue['ig_user_status'] == 1) {
                    $status = '<span class="label label-success"><i class="fa fa-check-circle-o"></i>&nbsp; Finished</span>';
                } else if ($ORvalue['ig_user_status'] == 2) {
                    $status = '<span class="label label-success"><i class="fa fa-refresh fa-spin"></i>&nbsp; Waiting</span>';
                }

                $records['data'][] = array(
                    '<input type="checkbox" class="orderCheckBox" name="orderId[]" value="' . $ORvalue['ins_user_id'] . '">',

                    $ORvalue['ins_user_id'],
                    '<p ><a class="btn btn-xs default text-case link-width" href="https://instagram.com/' . $ORvalue['ins_username'] . '/" target="_blank"><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $ORvalue['ins_username'] . '</p>',
                    $ORvalue['pics_done'],
                    $ORvalue['pics_limit'],
                    '<small><a class="btn btn-xs default text-case" href="#" target="_blank"><i class="fa fa-heart-o"></i> ' . $ORvalue['likes_per_pic'] . ' Likes p/ picture</a></small>',

                    $ORvalue['last_check'],
                    $ORvalue['last_delivery'],
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
                6 => 'cancel'
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
                    '<span class="label label-info"> <i class="fa fa-clock-o"></i>&nbsp;' . $status_list[$ORvalue['status']] . '</span>',
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
                0 => 'pending',
                1 => 'processing',//'Queue',
                2 => 'processing',
                3 => 'completed',
                4 => 'refunded',
                5 => 'Error',
                6 => 'cancel'
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
                    '<p class="link-width"><i style="font-size:10px" class="fa fa-instagram"></i>&nbsp;' . $ORvalue['plan_name'] . '</p>',
                    '<p class="link-width"><a target="_blank" href="' . $ORvalue['ins_url'] . '">' . $ORvalue['ins_url'] . '</a> </p>',
                    $ORvalue['quantity_total'],
                    $price,
                    $ORvalue['added_time'],
                    $ORvalue['updated_time'],
                    '<span class="label label-info"> <i class="fa fa-clock-o"></i>&nbsp;' . $status_list[$ORvalue['status']] . '</span>',
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
