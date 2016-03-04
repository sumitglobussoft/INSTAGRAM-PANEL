<?php

namespace InstagramAutobot\Http\Modules\User\Controllers;


use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Modules\User\Models\Transaction;
use InstagramAutobot\Http\Modules\User\Models\User;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

use InstagramAutobot\Http\Modules\User\Models\Usersmeta;
use vendor\Payment\Paypal\Paypal;

include public_path() . "/../vendor/Payment/Paypal/Paypal.php";

//include public_path() . "/../vendor/curl/CurlRequestHandler.php";

class UserController extends Controller
{

    protected $apiurl;
    protected $API_TOKEN;

    public function __construct()
    {
        $this->apiurl = env('API_URL');
        $this->API_TOKEN = env('API_TOKEN');
    }

    public function login(Request $request)
    {
        if (Session::has('ig_user')) {//|| $request->session()->has('ig_user')) {
            return redirect('/user/dashboard');
        }
        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/login';
            $data['emailOrUsername'] = $request['emailOrUsername'];
            $data['password'] = $request['password'];
            $data['api_token'] = $this->API_TOKEN;
            //$data['rememberMe']=$request['rememberMe'] == 'on' ? true : false;
            $objCurlHandler = CurlRequestHandler::getInstance();

            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//            dd($curlResponse);
            $field = 'username';
            if (strpos($data['emailOrUsername'], '@') !== false) {
                $field = 'email';
            }

            if ($curlResponse->code == 200) {
                if (Auth::attempt([$field => $data['emailOrUsername'], 'password' => $data['password']])) {
                    if ($curlResponse->data['role'] == 1) {
                        Session::put('ig_user', $curlResponse->data);
                        // dd($curlResponse->data);

                        return redirect('/user/dashboard');
                    } else
                        return Redirect::back()->with('errMsg', 'Invalid credentials.')->withInput();
                    // return view('User::user.login')->withErrors(['errMsg' => 'Invalid credentials.']);
                } else
                    return Redirect::back()->with('errMsg', 'Invalid credentials.')->withInput();

                //return view('User::user.login')->withErrors(['errMsg' => 'Invalid credentials.']);
            } else
                //dd($curlResponse);
                return Redirect::back()->withErrors($curlResponse->message)->withInput();
        }
        return view('User::user.login');
//        return view('User/Views/user/login');
    }

    public function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), ['email' => 'required|email'], ['email.unique' => 'The: attribute is required']);
            if (!$validator->fails()) {
                $url = $this->apiurl . '/forgotPassword';
                $data['fpwemail'] = $request['email'];
                $data['method'] = 'enterEmailId';
                $data['api_token'] = $this->API_TOKEN;
                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

                if ($curlResponse->code == 200) {
                    return Redirect::back()->with('successMsg', $curlResponse->message)->withInput();
                } else {
                    return Redirect::back()->with('errMsg', $curlResponse->message)->withInput();
                }

            } else
//                return Redirect::back()->withErrors(['errMsg' => $validator->messages()])->withInput();
                return Redirect::back()->withErrors($validator)->withInput();
        }
//        return view('User/Views/forgotPassword');
        return view('User::user.forgotPassword');
    }

    public function verifyResetCode(Request $request, $resetCode)
    {
        $url = $this->apiurl . '/forgotPassword';
        $data['resetCode'] = $resetCode;
        $data['method'] = 'verifyResetCode';
        $data['api_token'] = $this->API_TOKEN;

        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

        if ($curlResponse->code == 200) {
            if ($request->isMethod('post')) {
                $url = $this->apiurl . '/forgotPassword';
                $data['newPassword'] = $request['newPassword'];
                $data['conformNewPassword'] = $request['conformNewPassword'];
                $data['method'] = 'resetPassword';
                $data['api_token'] = $this->API_TOKEN;
                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
                if ($curlResponse->code == 200) {
                    //TODO change this statement when ajax call used

                    return view('User::user.login')->with(['passwordChangeSuccessMessage' => 'Please Login with your New Credential']);
                } else {
                    return view('User::user.resetPassword')->withErrors($curlResponse->message);
                }
            }
            return view('User::user.resetPassword');
        } else {
            return view('User::user.forgotPassword')->withErrors(['errMsg' => $curlResponse->message]);
        }
    }

    public function changePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = array(
                'oldPassword' => 'required',
                'newPassword' => 'required',
                'conformNewPassword' => 'required|same:newPassword',
            );
            $message = array(
                'oldPassword.required' => 'Old Password is require',
                'newPassword.required' => 'New Password is require',
                'conformNewPassword.same' => 'Conform New Password is same as New Password ',
            );
            $validator = Validator::make($request->all(), $rules, $message);
            if (!$validator->fails()) {
                $url = $this->apiurl . '/user/updatePassword';
                $data['oldPassword'] = $request['oldPassword'];
                $data['newPassword'] = $request['newPassword'];
                $data['conformNewPassword'] = $request['conformNewPassword'];
                $data['user_id'] = Session::get('ig_user')['id'];
                $data['api_token'] = $this->API_TOKEN;
                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

                if ($curlResponse->code == 200) {
                    return json_encode(array('status' => 1, 'successMessage' => $curlResponse->message));
                } else
                    return json_encode(array('status' => 0, 'errorMessage' => $curlResponse->message));
            } else
                echo json_encode(array('status' => 0, 'errorMessage' => $validator->messages()));
        }

        return view('User::user.changePassword');
    }

    public function register(Request $request)
    {
        if (Session::has('ig_user')) {//|| $request->session()->has('ig_user')) {
            return redirect('/user/dashboard');
        }

        if ($request->isMethod('post')) {
            $rules = array(
                'firstname' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
                'lastname' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
                'username' => 'required|regex:/^[A-Za-z0-9._\s]+$/|max:255|unique:users',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|regex:/^[A-Za-z0-9@#$_\s]+$/',
                'conform_password' => 'required|same:password',
            );
            $messages = [
                'firstname.regex' => 'The :attribute cannot contain special characters.',
                'lastname.regex' => 'The :attribute cannot contain special characters.',
                'username.regex' => 'The :attribute cannot contain special characters.',
                'email.unique' => 'The: attribute already exist',
                'password.regex' => 'The :attribute cannot contain special characters except @#$_.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
//            if (!$validator->fails()) {
            $url = $this->apiurl . '/signUp';
            $data['firstname'] = $request['firstname'];
            $data['lastname'] = $request['lastname'];
            $data['username'] = $request['username'];
            $data['email'] = $request['email'];
            $data['password'] = $request['password'];
            $data['conform_password'] = $request['conform_password'];
            $data['role'] = 1;
            $data['api_token'] = $this->API_TOKEN;
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            //echo '<pre>'; print_r($curlResponse);die;

            if ($curlResponse->code == 200)
                return view('User::user.login')->with(['registerSuccesMessage' => 'You have succesfull sign up, please wait for Admin Approval']);
            else if ($curlResponse->code == 100)
                return Redirect::back()->withErrors($curlResponse->message)->withInput();
            else
                return Redirect::back()->with(['registerErrorMessage' => $curlResponse->message])->withInput();
//            } else {
//                return Redirect::back()->withErrors($validator->messages())->withInput();
//            }

        }

        return view('User::user.register');
    }


    public function dashboard()
    {
        return view('User::user.dashboard');
    }

    public function myAccount()
    {

        $url = $this->apiurl . '/user/showProfileDetails';
        $data['user_id'] = Session::get('ig_user')['id'];
        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $userDetails = $curlResponse->data;
        return view('User::user.account', ['userData' => $userDetails]);
    }

    public function accountOverview(Request $request)
    {
        if ($request->isMethod('post')) {

        }

        $url = $this->apiurl . '/user/showProfileDetails';
        $data['user_id'] = Session::get('ig_user')['id'];
        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $userDetails = $curlResponse->data;
        return view('User::user.accountOverview', ['userData' => $userDetails]);
    }

    public function logout()
    {
        Session::forget('ig_user');
        return redirect('/user/login');
    }

    public function profileView(Request $request)
    {
        $url = $this->apiurl . '/user/showProfileDetails';
        $data['user_id'] = Session::get('ig_user')['id'];
        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $userDetails = $curlResponse->data;
        return view('User::user.showProfile', ['userData' => $userDetails]);
    }

    public function updateProfileInfo(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/user/updateProfileInfo';

            $data['user_id'] = Session::get('ig_user')['id'];
            $data['api_token'] = $this->API_TOKEN;

            $data['firstname'] = $request['firstname'];
            $data['lastname'] = $request['lastname'];
            $data['username'] = $request['username'];
            $data['email'] = $request['email'];
            $data['addressline1'] = $request['addressline1'];
            $data['addressline2'] = $request['addressline2'];
            $data['city'] = $request['city'];
            $data['state'] = $request['state'];
            $data['country_id'] = $request['country_id'];
            $data['contact_no'] = $request['contact_no'];

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
                echo json_encode(array('status' => 1, 'successMessage' => $curlResponse->message));
            } else {
                echo json_encode(array('status' => 0, 'errorMessage' => $curlResponse->message));
            }
        }

        $url = $this->apiurl . '/user/showProfileDetails';
        $data['user_id'] = Session::get('ig_user')['id'];
        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $userDetails = $curlResponse->data;
        return view('User::user.accountSetting', ['userData' => $userDetails]);
    }

    public function changeAvatar(Request $request)
    {
        return view('User::user.changeAvatar');
    }
    /*-------------------function created by saurabh------------------------------*/
    //PAYPAL Integration
    public function payment(Request $request)
    {

        if ($request->isMethod('post')) {

            $url = $this->apiurl . '/user/add-balance';
//            print_r($url);
            $data['api_token'] = $this->API_TOKEN;
            $this->validate($request, [
                'money' => 'required|regex:/^[0-9]+([.][0-9]+)?$/',
            ], [
                'money.required' => 'Please Enter Amount that you want to add to your wallet',
                'money.regex' => 'Please Enter a valid Amount i.e. number or decimal value '
            ]);
            $data['money'] = $request['money'];
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//                print_r($curlResponse);
//dd("asd");
            if ($curlResponse->code == 200) {
                $token = $curlResponse->data;
                $token = json_decode($token);
//                return $curlResponse->data;
//                return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate');
                return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token);

//                return json_encode(array('status' => 1, 'successMessage' => $curlResponse->message));
            } else
                return json_encode(array('status' => 0, 'errorMessage' => $curlResponse->message));
        }
        return view('User::user.addbalance');

//api.instagramautolike.localhost.com/user/payment


//        $objPaypal = paypal::getInstance();
//        print_r($objPaypal);
//        die;
    }

//    public function addBalance(Request $request)
//    {
//
//        if ($request->isMethod('post')) {
//            $paymentAmount = $request->input('money');
//            $this->validate($request,[
//                'money'=>'required'
//            ],[
//                'money.required'=>'Please Enter Amount that you want to add to your wallet'
//            ]);
//
////            $objPaypal = paypal::getInstance();
////            $description="Adding Credit";
//            $returnURL = "http://instagramautolike.localhost.com/expressCallback/".$paymentAmount;
//            $cancelURL = "http://instagramautolike.localhost.com/paymentError/196";
//            $payment_request_quantity = 1;
//            $description = "Adding Credit";
//            $payment_request_number = 1;
//            $payment_type = "Any";
//            $custom = "";
//            $subscription_type = "";
//
//            $objpaypal = paypal::getInstance();
//            $result = $objpaypal->CallShortcutExpressCheckout($paymentAmount, $returnURL, $cancelURL, $payment_request_quantity, $description, $payment_request_number, $payment_type, $custom, $subscription_type);
//            $token=$result['TOKEN'];
//            return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$token);
////            echo "<pre>";print_r($token);
////            die;
//        }
//        return view('User::user.addbalance');
//    }

    public function expressCallback($amount, Request $request)
    {
//        dd($request);
        $url = $this->apiurl . '/user/expressCallback';
        $data['api_token'] = $this->API_TOKEN;
        $data['id'] = Session::get('ig_user')['id'];
        $data['amount'] = $amount;
        $data['PayerID'] = $request['PayerID'];
        $data['token'] = $request['token'];
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//                        print_r($curlResponse);

        if ($curlResponse->code == 200) {

            $totalBalance = $curlResponse->data;
            Session::put("ig_user.account_bal", $totalBalance);
            Session::put('ig_user.notification', $curlResponse->message);
            Session::put('ig_user.count', 1);

            return redirect('/user/payment')->with(['message' => 'Your Account is successfully credited']);

        } else if ($curlResponse->code == 007) {
            return json_encode(array('status' => 0, 'successMessage' => $curlResponse->message));
        } else {
            return json_encode(array('status' => 0, 'errorMessage' => $curlResponse->message));
        }
    }

//    public function expressCallback($amount, Request $request)
//    {
//
//        $payerid = $request->input('PayerID');
//        $token = $request->input('token');
//
//        $objpaypal = paypal::getInstance();
//        $result = $objpaypal->ConfirmPayment($amount, $token, $payerid);
//
//        echo "<pre>";print_r($result);
//        die;
//        if ($result['ACK'] == "Success") {
//            $data['transactionId'] = $result['PAYMENTINFO_0_TRANSACTIONID'];
//            $data['acknowledgement'] = $result['ACK'];
//            $data['amount'] = $amount;
//            $data['paymentTime'] = date('Y-m-d H:i:s');
//        }
//        else{
//            echo "<pre>";print_r($result);
//            die;
//        }
//        echo "<pre>";print_r($data);
//        die;
//    }

    public function cheapbulk(Request $request)
    {

        $url = $this->apiurl . '/user/order-status-cheapbulk';
//            print_r($url);
        $data['api_token'] = $this->API_TOKEN;
        $data['oid'] = '216236171';
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        print_r($curlResponse);

//        return $curlResponse;

//            if ($curlResponse->code == 200) {
////                $token = $curlResponse->data;
////                $token = json_decode($token);
//////                return $curlResponse->data;
////                return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token);
//                return json_encode(array('status' => 1, 'successMessage' => $curlResponse->message));
//            } else
//                return json_encode(array('status' => 0, 'errorMessage' => $curlResponse->code));
    }

    public function paymentError()
    {
        return redirect('/user/payment')->with(['message' => 'SORRY!! At this time, we are unable to process your request. Please Try Again Later...']);
    }

        /*---------------for FAQ pages-------------------*/

    public function faq(){
        return view('User::faq.faq');
    }



}
