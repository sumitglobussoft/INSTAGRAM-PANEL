<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Models\Order;
use App\Http\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use stdClass;

class OrderController extends Controller
{

    protected $API_TOKEN;

    public function  __construct()
    {
//        $this->API_TOKEN = env('API_TOKEN');
        $this->API_TOKEN = '9876543210'; //TODO REMOVE THIS LINE AND REMOVE ABOVE COMMENT

//        dd(env('API_TOKEN'));
    }

    public function getAddOrderFormDetails(Request $request)
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
                $whereGroupID = [
                    'rawQuery' => 'plans.plangroup_id=?',
                    'bindParams' => ['1']
                ];
                $plansDetails = $objOrderModel->getPlansDetails($whereGroupID);
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

    public function  addOrder(Request $request)
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
                $rules = [
                    'user_id' => 'required',
                    'plan_id' => 'required',
                    'order_url' => 'required|url|regex:',//TODO:
                    'quantity' => 'required|integer',
                ];
                $messages = [
                    'user_id.required' => 'User Id is required',
                    'plan_id.required' => 'Please select a service',
                    'order_url.required' => 'Please enter URL',
                    'quantity.required' => 'Please enter amount to delivery',
                ];

                $validator =Validator::make($request->all(), $rules, $messages);
                if (!$validator->fails()) {

                    //TODO URL CVALIDATION HIT CURLGET, BALANCE, QUANTITY 

                  // $result=$objOrderModel->updatedOrder();
                    $response->code = 100;
                    $response->message = "Error";
                    $response->data = null;
                    echo json_encode($response);
                }else{
                    $response->code = 100;
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

}
