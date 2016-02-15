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

    public function addOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->API_URL . '/user/addOrder';
            $postData = $request->all();

            $rules = [
                'plan_id' => 'required',
                'order_url' => 'required|url',
                'quantity' => 'required|integer',
                'starting_time' => 'required',
                'commentType' => 'required',
                'customCommentType' => 'required'
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

                //TODO URL CVALIDATION HIT CURLGET, BALANCE, QUANTITY

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
                dd($validator->messages());
                return Redirect::back()->with(['errorMessage' => "Please correct the following errors"])->withErrors($validator)->withInput();
            }
        }

        $url = $this->API_URL . '/user/getAddOrderFormDetails';

        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $planDetailsData = $curlResponse->data;

        $url = $this->API_URL . '/user/getCommentsList';

        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $commentsListData = $curlResponse->data;
        //dd($curlResponse);
        return view('User::order.addOrder', ['data' => $planDetailsData, 'commentListData' => $commentsListData]);

    }

    public function orderHistory(Request $request)
    {
        $url = $this->API_URL . '/user/getOrderHistory';
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_user')['id'];
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        if ($curlResponse->code == 200)
            return view('User::order.orderHistory')->with(['orders' => $curlResponse->data]);
        else
            return view('User::order.orderHistory');

    }


    public function cancelOrder(Request $request)
    {

        $orderId = $request['orderId'];

        $messages = '';
//        foreach($orderId as $key=>$value){
        $url = $this->API_URL . '/user/cancelOrder';
        $data['api_token'] = $this->API_TOKEN;
        $data['order_id'] = json_encode($request['orderId'], true);

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

//        }

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

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        dd($curlResponse);
    }


}
