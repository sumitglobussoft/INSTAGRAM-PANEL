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
    protected $apiurl;
    protected $API_TOKEN;

    public function __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
        $this->apiurl = env('API_URL');
    }

    public function addOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/supplier/addOrder';
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


//                dd($curlResponse);
                return Redirect::back()->with(['successMessage' => $curlResponse->message]);
            } else {
//                dd($validator->messages());
                return Redirect::back()->with(['errorMessage' => "Please correct the following errors"])->withErrors($validator)->withInput();
            }
        }

        $url = $this->apiurl . '/supplier/getAddOrderFormDetails';

        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $planDetailsData = $curlResponse->data;

        $url = $this->apiurl . '/supplier/getCommentsList';

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
        return view('Supplier::order.orderHistory');
    }
}
