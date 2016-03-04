<?php

namespace InstagramAutobot\Http\Modules\Supplier\Controllers;

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

            $date = new \DateTime();
//        echo $date->format('U = Y-m-d H:i:s') . "\n";
//
//        $date->setTimestamp(1171502725);
//        echo $date->format('U = Y-m-d H:i:s') . "\n";
//        dd();
//        $nowtime = time();
//        $oldtime = 1454599590;
//        echo $nowtime;
//        $temp = $nowtime - $oldtime;
//        echo $temp;
//        $date->setTimestamp($nowtime - $oldtime);
//        dd($date->format('H:i'));
//        date('d.m.Y H:i:s', 1454585614);
//        dd($date->format('d.m.Y H:i:s'));

//            $time=$request['starting_time'];
//            echo $time, "\n";
//            $d=strtotime($time);
//            echo strtotime($time), "\n";
//            dd();


            $url = $this->API_URL . '/supplier/addOrder';
            $postData = $request->all();

            $rules = [
                'plan_id' => 'required',
                'order_url' => 'required|url',
                'quantity' => 'required|integer',
                'starting_time' => 'required',
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
                $data['user_id'] = Session::get('ig_supplier')['id'];
                $data['api_token'] = $this->API_TOKEN;


                //TODO URL CVALIDATION HIT CURLGET, BALANCE, QUANTITY

                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

                if ($curlResponse->code == 200) {
                    Session::put("ig_supplier.account_bal", $curlResponse->data['account_bal']);
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

        $url = $this->API_URL . '/supplier/getAddOrderFormDetails';

        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $planDetailsData = $curlResponse->data;

        $url = $this->API_URL . '/supplier/getCommentsList';

        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_supplier')['id'];
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $commentsListData = $curlResponse->data;
//        dd($curlResponse);
        return view('Supplier::order.addOrder', ['data' => $planDetailsData, 'commentListData' => $commentsListData]);

    }

    public function orderHistory(Request $request)
    {
        $url = $this->API_URL . '/supplier/getOrderHistory';
        $data['api_token'] = $this->API_TOKEN;
        $data['user_id'] = Session::get('ig_supplier')['id'];
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        if ($curlResponse->code == 200)
            return view('Supplier::order.orderHistory')->with(['orders' => $curlResponse->data]);
        else
            return view('Supplier::order.orderHistory');

    }
}
