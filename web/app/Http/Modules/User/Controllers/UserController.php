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

//addded by saurabh
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use stdClass;

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
            $data['user_timezone'] = $request['user_timezone'];
            $data['emailOrUsername'] = $request['emailOrUsername'];
            $data['password'] = $request['password'];
            $data['api_token'] = $this->API_TOKEN;
//            $data['rememberMe']=$request['rememberMe'] == 'on' ? true : false;

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//dd($curlResponse);
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
                'password' => 'required',//|regex:/^[A-Za-z0-9@#$_\s]+$/',
                'conform_password' => 'required|same:password',
            );
            $messages = [
                'firstname.regex' => 'The :attribute cannot contain special characters.',
                'lastname.regex' => 'The :attribute cannot contain special characters.',
                'username.regex' => 'The :attribute cannot contain special characters.',
                'email.unique' => 'The: attribute already exist',
//                'password.regex' => 'The :attribute cannot contain special characters except @#$_.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
//            if (!$validator->fails()) {
            $url = $this->apiurl . '/signUp';
            $data['user_timezone'] = $request['user_timezone'];
            $data['firstname'] = $request['firstname'];
            $data['lastname'] = $request['lastname'];
            $data['username'] = $request['username'];
            $data['skypeUsername'] = (isset($request['skypeUsername'])) ? $request['skypeUsername'] : '';
            $data['email'] = $request['email'];
            $data['password'] = $request['password'];
            $data['conform_password'] = $request['conform_password'];
            $data['role'] = 1;
            $data['api_token'] = $this->API_TOKEN;
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

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

    public function checkUsername(Request $request)
    {
        if ($request->isMethod('post')) {
            $userName = $request->input('userName');
            if (preg_match("/\\s/", $userName)) {
                echo json_encode(array('status' => '100', 'message' => 'Username should not contain a white space'));
            } else {
                $objModelUsers = User::getInstance();
                $where = array('rawQuery' => 'username=?', 'bindParams' => [$userName]);
                $usersDetails = $objModelUsers->getUserWhere($where);
                if ($usersDetails) {
                    echo json_encode(array('status' => '200', 'message' => 'Username has already been taken.'));
                } else {
                    echo json_encode(array('status' => '400', 'message' => 'Username can be assigned.'));
                }
            }
        }

    }


    public function dashboard()
    {
        $objModelTransaction = Transaction::getInstance();
        $userId = Session::get('ig_user')['id'];
        $where = array('rawQuery' => 'by_user_id=?', 'bindParams' => [$userId]);
//        $selColms=['orders.status','transactions.amount'];
        $orderDetails = $objModelTransaction->getAllOrdersDetails($where);
        $totalOrders = 0;
        $completedCount = 0;
        $processingCount = 0;
        foreach ($orderDetails as $orderStatus) {
            ++$totalOrders;
            if ($orderStatus->status == 3)
                ++$completedCount;
            else if($orderStatus->status==1 || $orderStatus->status==2)
                ++$processingCount;
        }
        $whereForTransaction=array('rawQuery'=>'user_id=?','bindParams'=>[$userId]);
        $transactionDetails=$objModelTransaction->getAllTransactionsWhere($whereForTransaction);
        $payment=0;
        foreach($transactionDetails as $td){
            $payment+=$td->amount;
        }
//        dd($transactionDetails);
        return view('User::user.dashboard', ['totalOrders' => $totalOrders, 'completed' => $completedCount, 'processing' => $processingCount,'payment'=>$payment]);
    }

    public function checkUserStatus(){
        $id=Session::get('ig_user')['id'];
        $objModelUser=User::getInstance();
        $userStatus=$objModelUser->getUserWhere(array('rawQuery'=>'id=?','bindParams'=>[$id]),['status']);
        if($userStatus->status==2){
            Session::forget('ig_user');
            echo json_encode(array('status'=>'400','message'=>'user has deactivated by admin'));
        }else{
            echo json_encode(array('status'=>'200','message'=>'user is active'));
        }
    }

    public function getBalance(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/user/getBalance';
            $data['user_id'] = Session::get('ig_user')['id'];
            $data['api_token'] = $this->API_TOKEN;
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
                Session::put("ig_user.account_bal", $curlResponse->data['account_bal']);
                echo json_encode(['status' => 'success', 'data' => $curlResponse->data], true);
            } else {
                echo json_encode(['status' => 'fails', 'data' => $curlResponse->message], true);
            }
        }
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
            $data['skypeUsername'] = (isset($request['skypeUsername']))?$request['skypeUsername']:'';
            $data['email'] = $request['email'];
//            $data['addressline1'] = $request['addressline1'];
//            $data['addressline2'] = $request['addressline2'];
//            $data['city'] = $request['city'];
//            $data['state'] = $request['state'];
//            $data['country_id'] = $request['country_id'];
//            $data['contact_no'] = $request['contact_no'];

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
                Session::put('ig_user.username',$data['username']);
                Session::put('ig_user.skype_username',$data['skypeUsername']);
                return Redirect::back()->with(['status' => 'success', 'message' => 'Profile has been successfully updated.']);
            }
            if ($curlResponse->code == 500) {
                foreach ($curlResponse->message as $key => $value) {
                    $msg = ($curlResponse->message[$key]);
                }
                return Redirect::back()->with(['status' => 'error', 'message' => $msg[0]]);
            } else {
                return Redirect::back()->with(['status' => 'error', 'message' => 'Same Contents.']);
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

    public function emailNotifications(Request $request)
    {
        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/user/emailNotifications';

            $data = $request->all();
            $data['user_id'] = Session::get('ig_user')['id'];
            $data['api_token'] = $this->API_TOKEN;

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//            dd($curlResponse);

            if ($curlResponse->code == 200) {
                Session::put("ig_user.notify_bal", intval($curlResponse->data['notify_bal']));
                Session::put("ig_user.notify_profile_likes", intval($curlResponse->data['notify_profile_likes']));
                Session::put("ig_user.notify_daily_subscription", intval($curlResponse->data['notify_daily_subscription']));

                return Redirect::back()->with(['successMessage' => $curlResponse->message]);
            } else if ($curlResponse->code == 204) {
                return Redirect::back()->withErrors($curlResponse->message)->withInput();
            } else {
                return Redirect::back()->with(['errorMessage' => $curlResponse->message])->withInput();
            }

        } else {
            return view('User::user.emailNotification');
        }
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
                'money' => 'required|regex:/^[0-9]+([.][0-9]{0,2}+)?$/',
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
//                return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token);
                return redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token);

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

    public function faq()
    {
        return view('User::support.faq');
    }

    public function contactPage()
    {
        return view('User::support.contact');
    }

    public function paymentPage()
    {
        return view('User::support.payment');
    }

    public function refundsPage()
    {
        return view('User::support.refunds');
    }

    public function termsOfServicePage()
    {
        return view('User::support.termsofservice');
    }


    /*-----------------------Deposit History-----------------*/
    public function transactionHistory()
    {
        return view('User::user.depositHistory');
    }

    public function showTransactionHistory()
    {
        $id = Session::get('ig_user')['id'];
        $modelTransactions = Transaction::getInstance();
        $where = array(
            'rawQuery' => 'user_id=?',
            'bindParams' => [$id]
        );
        $selectedColumns = ['transactions.*', 'users.email'];
        $txDetails = $modelTransactions->getUserInfoByUserId($where, $selectedColumns);
//        echo'<pre>';
//        print_r($txDetails);die;

        $trans = new Collection();
        $txDetails = json_decode(json_encode($txDetails), true);
        foreach ($txDetails as $txd) {

            $trans->push([
                'tx_id' => $txd['tx_id'],
                'date' => $this->convertUT($txd['payment_time']),
                'amount' => $txd['amount'],
                'email' => $txd['email'],
                'transaction_id' => $txd['transaction_id'],
                'status' => 'completed',
//                'view' => '<button data-original-title="Information" data-html="true" data-content="Payment Completed using PayPal! Thanks for your deposit" data-placement="top" data-trigger="hover" data-container="body" class="btn popovers btn-circle btn-default btn-xs">
//        <i class="fa fa-eye"></i> Details
//    </button>'
            ]);
        }
        return Datatables::of($trans)->make(true);
    }

    public function convertUT($ptime)
    {
        $difftime = time() - $ptime;

        if ($difftime < 1) {
            return '0 seconds';
        }

        $a = array(365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        $a_plural = array('year' => 'years',
            'month' => 'months',
            'day' => 'days',
            'hour' => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );

        foreach ($a as $secs => $str) {
            $d = $difftime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
            }
        }
    }

    /*---------------------2CheckOut Pyament Integration--------------*/

    public function TwoCOpayment(Request $request)
    {
        if ($request->isMethod('post')) {

            $url = $this->apiurl . '/user/add-balance-2co';
//            print_r($url);
            $data['api_token'] = $this->API_TOKEN;

            $this->validate($request, [
                'money' => 'required|regex:/^[0-9]+([.][0-9]{0,2}+)?$/',
                'name' => 'required',
                'addrLine1' => 'required',
                'city' => 'required',
                'state' => 'required',
                'zipCode' => 'required',
                'country' => 'required',
                'email' => 'required',
                'phoneNumber' => 'required'
            ], [
                'money.required' => 'Please Enter Amount that you want to add to your wallet',
                'money.regex' => 'Please Enter a valid Amount i.e. number or decimal value ',
                'name.required' => 'please enter your name',
                'addrLine1.required' => 'please enter address',
                'city.required' => 'please enter city',
                'state.required' => 'please enter state',
                'zipCode.required' => 'please provide zip code',
                'country.required' => 'please specify country name',
                'email.required' => 'please enter your email',
                'phoneNumber.required' => 'please enter your phone number',
            ]);
            $data['id'] = Session::get('ig_user')['id'];
            $data['token'] = $_POST['token'];
            $data['money'] = $request['money'];
            $data['name'] = $request['name'];
            $data['addrLine1'] = $request['addrLine1'];
            $data['city'] = $request['city'];
            $data['state'] = $request['state'];
            $data['zipCode'] = $request['zipCode'];
            $data['country'] = $request['country'];
            $data['email'] = $request['email'];
            $data['phoneNumber'] = $request['phoneNumber'];
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
//                print_r($curlResponse);
//dd("asd");
            if ($curlResponse->code == 200) {
//            $token = $curlResponse->data;
//            $token = json_decode($token);
////                return $curlResponse->data;
////                return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate');
//            return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token);

                $totalBalance = $curlResponse->data;
                Session::put("ig_user.account_bal", $totalBalance);
                Session::put('ig_user.notification', $curlResponse->message);
                Session::put('ig_user.count', 1);

                return redirect('/user/twoCO_payment')->with(['message' => 'Your Account is successfully credited with 2co payment method']);


//                return json_encode(array('status' => 1, 'successMessage' => $curlResponse->message));
            } else
                return json_encode(array('status' => 0, 'errorMessage' => $curlResponse->message));
        }
        return view('User::2CO.2checkout');
    }

}
