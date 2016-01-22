<?php

namespace InstagramAutobot\Http\Modules\Supplier\Controllers;

use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
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
            ];
            $messages = [
                'plan_id.required' => 'Please select a service',
                'order_url.required' => 'Please enter URL',
                'order_url.regex' => 'Please enter correct URL',
                'quantity.required' => 'Please enter amount to delivery',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if (!$validator->fails()) {

                $data['user_id'] = Session::get('ig_supplier')['id'];
                $data['plan_id'] = $postData['plan_id'];
                $data['order_url'] = $postData['order_url'];
                $data['quantity'] = $postData['quantity'];
                $data['api_token'] = $this->API_TOKEN;


                //TODO URL CVALIDATION HIT CURLGET, BALANCE, QUANTITY

                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                $resultData = $curlResponse->data;

                dd($resultData);

            } else {
                dd($validator->messages());

                return Redirect::back()->with('errMsg', $validator->messages())->withInput();
            }
        }

        $url = $this->apiurl . '/supplier/getAddOrderFormDetails';

        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $resultData = $curlResponse->data;
        return view('Supplier::order.addOrder', ['data' => $resultData]);

    }

    public function viewOrder(Request $request)
    {
        return view('Supplier::order.viewOrder');
    }
}
