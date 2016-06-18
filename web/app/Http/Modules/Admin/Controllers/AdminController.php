<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Order;
use InstagramAutobot\Http\Modules\Admin\Models\User;
use InstagramAutobot\Http\Modules\Admin\Models\Paypal;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Hash;
use Input;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use ResetsPasswords;
use Mandrill;
use stdClass;

use Omnipay\Omnipay;

//use InstagramAutobot\Http\Models\MailTemplate;

include public_path() . "/../vendor/mandrill/src/Mandrill.php";

class AdminController extends Controller
{

    public function dashboard()
    {

        $objModelUser = User::getInstance();
        $whereAllUsers = array(
            'rawQuery' => 'role=1'
        );

        $allUsers = $objModelUser->getAllUsersWhere($whereAllUsers);
        $countAllUsers = 0;
        $countAllActiveUsers = 0;
        foreach ($allUsers as $all) {
            ++$countAllUsers;
            if ($all->status == 1)
                ++$countAllActiveUsers;

        }

        $objModelOrder = Order::getInstance();
        $whereAllOrders = array(
            'rawQuery' => 'status=0 or 1 or 2 or 3 or 4 or 5 or 6'
        );
        $allOrders = $objModelOrder->getAllOrdersWhere($whereAllOrders);
        $countAllOrder = 0;
        $countTodaysOrders=0;
        $countPendingOrders = 0;
        $countFailedOrders = 0;
        $countCancelledOrders = 0;
        $countRefundedOrders = 0;

        foreach ($allOrders as $all) {
            ++$countAllOrder;
            if($all->added_time>=(time()+19800-(24*3600)))
                ++$countTodaysOrders;
            if ($all->status == 0)
                ++$countPendingOrders;
            elseif ($all->status == 4)
                ++$countFailedOrders;
            elseif ($all->status == 5)
                ++$countRefundedOrders;
            elseif ($all->status == 6)
                ++$countCancelledOrders;

        }

        return view('Admin::admin.dashboard', ['count_all' => $countAllUsers, 'count_active' => $countAllActiveUsers,
            'total_orders' => $countAllOrder, 'pending_orders' => $countPendingOrders, 'failed_orders' => $countFailedOrders,
            'refunded_orders' => $countRefundedOrders, 'cancelled_orders' => $countCancelledOrders,'todays_orders'=>$countTodaysOrders]);
    }

    public function adminlogin(Request $request)
    {

////        $objUser = new User();
//        //dd($objUser);
//        $data = array(
//            'name' => 'Saurabh',
//            'lastname' => 'Kumar',
//            'username' => 'admin',
//            'email' => 'saurabh.kumar@globussoft.com',
//            'password' => Hash::make('bond'),
//            'role' => "0",
//            'status' => '0',
//          //  'registration_date' => time()
//        );
//        $result = DB::table('users')->insert($data);
////            $result = $objUser->addNewUser($data);

        if (Session::has('instagram_admin')) {

            return redirect('admin/dashboard');
        }

        if ($request->isMethod('post')) {
            $email = $request->input('email');
            $password = $request->input('password');

            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required',
            ], ['email.required' => 'Please enter email address or username',
                    'email.email' => 'Please enter a valid email',
                    'password.required' => 'Please enter a password']
            );

            if (Auth::attempt(['email' => $email, 'password' => $password])) {
                $objModelUsers = User::getInstance();
                $userDetails = $objModelUsers->getUserById(Auth::id());
//                dd($userDetails);
                if ($userDetails->role == 0) {
                    $sessionName = 'instagram_admin';
                    $session = Session::put($sessionName, $userDetails['original']);
//                       dd(Session::all());
                    return redirect('admin/dashboard');
                } else {
                    return redirect('admin/login')->with([
                        'message' => 'You are not an ADMIN. Please do user Sign In.'
                    ])->withInput();
                }
                // return redirect('admin/dashboard');
            } else {
                return Redirect::back()->with(['status' => 'error', 'message' => 'Invalid Credentials.'])->withInput();
            }
        }
        return view('Admin::admin.adminlogin');
    }

    public function resetPassword(Request $data)
    {
        if ($data->isMethod('post')) {
            $objModelUser = User::getInstance();
            $email = $data->input('email');
            // if (User::where('email', '=', $email)->exists()) {
            if ($id = DB::table('users')->where('email', $email)->pluck('id')) {
                $pdResetToken = str_random(10);
//                $pdResetToken = "";
//                $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
//                $max = count($characters) - 1;
//                for ($i = 0; $i < 8; $i++) {
//                    $rand = mt_rand(0, $max);
//                    $pdResetToken .= $characters[$rand];
//                }

                $whereForUpdateUser = array(
                    'rawQuery' => 'id = ?',
                    'bindParams' => [$id]
                );
                $dataForUpdateUser = array('pd_reset_token' => $pdResetToken);
                $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);
                if ($updated) {
//                    $objMailTemplate = new MailTemplate();
//                    $temp_name = "forgot_password_mail";
//                    $mailTempContent = $objMailTemplate->getTemplateByName($temp_name);
                    $key = env('MANDRILL_KEY');
                    $mandrill = new Mandrill($key);
                    $async = false;
                    $ip_pool = 'Main Pool';
                    $message = array(
                        'html' => "<html><body>Hello!!! To reset your password click <a href='*|url|*'>here </a><br> If this wasnt you, please ignore this email.</body></html>",
                        'subject' => "Password Reset",
                        'from_email' => "support@instagramautolike.com",
                        'to' => array(
                            array(
                                'email' => $email,
                                'type' => 'to'
                            )
                        ),
                        'merge_vars' => array(
                            array(
                                "rcpt" => $email,
                                'vars' => array(
//                                    array(
//                                        "name" => "username",
//                                        "content" => $postData['first_name']
//                                    ),
                                    array(
                                        "name" => "url",
                                        "content" => url('resetpassword', $parameters = [$pdResetToken], $secure = null)
//                                        env('HOST_URl') . '/resetpassword/' . $pdResetToken;
                                    )
                                )
                            )
                        ),
                    );

                    $mailrespons = $mandrill->messages->send($message, $async, $ip_pool);
                    //  dd($mailrespons);
                    if ($mailrespons[0]['status'] == "sent") {
                        return Redirect::back()->with(['status' => 'Success', 'msg' => 'Thanks! Password recovery mail has sent. Please check (' . $email . ') for a link to reset your password.']);
                    } else {
                        return Redirect::back()->with(['status' => 'Error', 'msg' => 'Missing Something.']);
                    }
                }

            } else {
                return Redirect::back()->with(['status' => 'Error', 'msg' => 'This Email is not Registered.']);
                //return redirect('admin/forgot');
//                return redirect('admin/forgotpasswordpage')->withErrors([
//                    'errMsg' => 'this email is not registered.'
//                ]);
            }
        }
        return view('Admin::admin.forgotpassword');
    }

    public function adminLogout()
    {
        Session::forget('instagram_admin');
        //  dd(Session::all());
        return redirect('admin/login');
    }

    public function editProfile(Request $request)
    {
        $objModelUser = User::getInstance();
        $sessionUserDetails = Session::get('instagram_admin');
        $id = $sessionUserDetails['id'];

        if ($request->isMethod('post')) {

            $newName = $request->input('newname');
            $newLastname = $request->input('newlastname');
            $newUsername = $request->input('newusername');
            $newEmail = $request->input('newemail');
            $currentpassword = $request->input('currentpassword');
            $newPassword = $request->input('newpassword');
            $newpassword_confirmation = $request->input('newpassword_confirmation');
            $generalInfo = $request->input('generalinfo');
            $editpassword = $request->input('editpassword');
            if (isset($generalInfo)) {
                $this->validate($request, [
                    'newname' => 'required',
                    'newlastname' => 'required',
                    'newusername' => 'required',
                    'newemail' => 'required|email|unique:users,email,'.$id,  //unique:users,email// unique email validation I will do later
                ], ['newname.required' => 'Please enter a name',
                        'newlastname.required' => 'Please enter your lastname',
                        'newusername.required' => 'Please enter a username',
                        'newemail.required' => 'Please enter email address',
                        'newemail.email' => 'Please enter a valid email',
//                        'newemail.unique' => 'This email already exists, Please choose another',
                    ]
                );

                $whereForUpdateUser = array(
                    'rawQuery' => 'id = ?',
                    'bindParams' => [$id]
                );
                $dataForUpdateUser = array('name' => $newName, 'lastname' => $newLastname, 'username' => $newUsername, 'email' => $newEmail);
                $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);
                if ($updated) {
//                    return redirect('admin/dashboard')->with('msg', 'your data has updated');
                    return Redirect::back()->with(['status' => 'Success', 'message' => 'Your Profile has Updated.']);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'message' => 'Nothing to update, Same contents...']);
                }
            } elseif (isset($editpassword)) {

                $this->validate($request, [
                    'currentpassword' => 'required',
                    'newpassword' => 'required|min:3|confirmed',
                    'newpassword_confirmation' => 'required|min:3',
                ], [
                    'currentpassword.required' => 'Please Enter Your Current Password.',
                    'newpassword.required' => 'New Password Required',
                    //'newpassword_confirmation.required'=>'Please Re-type Password',
                ]);

                $whereForUpdateUser = array(
                    'rawQuery' => 'id = ?',
                    'bindParams' => [$id]
                );
                $userDetails = $objModelUser->getUserWhere($whereForUpdateUser);
                if (Hash::check($currentpassword, $userDetails->password)) {
                    $dataForUpdateUser = array('password' => Hash::make($newPassword));
                    $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'message' => 'Your Current Password is not matching. How do we know this is you!']);
                }
                if ($updated) {
//                    return redirect('admin/dashboard')->with('msg', 'your data has updated');
                    return Redirect::back()->with(['status' => 'Success', 'message' => 'Your Password has Updated.']);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'message' => 'Something went wrong, please reload the page and try again...']);
                }

            }
        }
        return view('Admin::admin.editprofile');
    }

    public function checkToken(Request $data, $token)
    {
        $objModelUser = User::getInstance();
        $whereForSearchToken = array(
            'rawQuery' => 'pd_reset_token = ?',
            'bindParams' => [$token]
        );
        $userDetails = $objModelUser->getUserWhere($whereForSearchToken);

        if ($userDetails) {
            if ($data->isMethod('post')) {
                $newPassword = $data->input('newpassword');
                $newPassword_confirmation = $data->input('newpassword_confirmation');
                $this->validate($data, [
                    'newpassword' => 'required|min:3|confirmed',
                    'newpassword_confirmation' => 'required|min:3',
                ], [
                        'newpassword.required' => 'New Password Required',
                        //'confirmpassword.required' => 'Please Re-type Password',
                    ]
                );
                $whereForUpdateUser = array(
                    'rawQuery' => 'pd_reset_token = ?',
                    'bindParams' => [$token]
                );
                $dataForUpdateUser = array('password' => Hash::make($newPassword), 'pd_reset_token' => '');
                $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);
                return redirect('admin/login')->with(['message' => 'your password has successfully updated. please login now with the new password']);
            }
            return view('Admin::admin.resetpassword');

        } else {
            echo 'This link has expired';
        }
    }

    /* --------------curl------------------------------------------*/

    //url-ify the data for the POST
//$fields_string = '';
//foreach ($data as $key => $value) {
//$fields_string .= $key . '=' . $value . '&';
//}
//$fields_string = rtrim($fields_string, '&');
////print_r($fields_string);die;
////open connection
//$ch = curl_init();
////set the url, number of POST vars, POST data
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POST, count($data));
//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
//curl_setopt($ch, CURLOPT_HEADER, false);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
//// curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
////execute post
//$result = curl_exec($ch);

    public function curlUsingPost()//$url, $data
    {
//        echo "<pre>";print_r($url);die();

        $url = 'https://www.igerslike.com/api/market/api.php';
        $data = array(
            'key' => '9d6f65be87883e23b078532d3ada6a91fc3ba3bcf93fedd552f5a3c1f04707f4',
            'action' => 'order_status',
            'order_id' => '2589672'
        );


        $response = new stdClass();
        if (empty($url) OR empty($data)) {
            $response->code = 198;
            $response->message = 'Parameter not Passed';
            return $response;
        }

        //url-ify the data for the POST
        $fields_string = '';
        foreach ($data as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = rtrim($fields_string, '&');
        //print_r($fields_string);die;
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
        // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //execute post
        $result = curl_exec($ch);

//       echo"<pre>"; print_r($result); echo"</pre>";die;
        $result = json_decode($result, true);
//       echo"<pre>"; print_r($result); echo"</pre>";die;
        echo "<pre>";
        print_r($result);
        die;
//       echo"</pre>";die;

        //close connection
        curl_close($ch);
        if ($result) {
            @$response->message = $result['status'];
            @$response->code = $result['type'];
            @$response->data = $result['time'];
//            print_r(@$response->code);
            dd(@$response);
            return @$response;
        } else {
            $response->code = 196;
            $response->message = 'Some error Occured, Request not complete';
            dd(@response);
            return $response;
        }
    }
    /*--------------------------------------------------------------------------------------------------------*/
    /*----------------------------------API for Paypal Integration-----------------------------------------------------*/
//    public function request($method, $params = array())
//    {
//        $_errors = array();
//
//        $_credentials = array(
//
//        'USER' => 'saurabh.kumar_api1.globussoft.com',
//        'PWD' => 'X6ZH5X2G2U3NNLD8',
//        'SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AOpS3AsKcc857KLz99TD-QdguXVE',
//    );
//        $_endPoint = 'https://api-3t.sandbox.paypal.com/nvp';
//        $_version = '74.0';
//
//
////        $method=array(
////
////            'USER' => 'saurabh.kumar_api1.globussoft.com',
////            'PWD' => 'X6ZH5X2G2U3NNLD8',
////            'SIGNATURE' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AOpS3AsKcc857KLz99TD-QdguXVE',
////        );
////        $params = array();
//
//
//
//        $this->_errors = array();
//        if (empty($method)) { //Check if API method is not empty
//            $this->_errors = array('API method is missing');
//            return false;
//        }
//
////Our request parameters
//        $requestParams = array(
//                'METHOD' => $method,
//                'VERSION' => $this->_version
//            ) + $this->_credentials;
//
////Building our NVP string
//        $request = http_build_query($requestParams + $params);
//
////cURL settings
//        $curlOptions = array(
//            CURLOPT_URL => $this->_endPoint,
//            CURLOPT_VERBOSE => 1,
//            CURLOPT_SSL_VERIFYPEER => true,
//            CURLOPT_SSL_VERIFYHOST => 2,
//            CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', //CA cert file
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_POST => 1,
//            CURLOPT_POSTFIELDS => $request
//        );
//
//        $ch = curl_init();
//        curl_setopt_array($ch, $curlOptions);
//
////Sending our request - $response will hold the API response
//        $response = curl_exec($ch);
//        print_r($response);die;
//
////Checking for cURL errors
//        if (curl_errno($ch)) {
//            $this->_errors = curl_error($ch);
//            curl_close($ch);
//            return false;
////Handle errors
//        } else {
//            curl_close($ch);
//            $responseArray = array();
//            parse_str($response, $responseArray); // Break the NVP string to an array
//            return $responseArray;
//        }
//    }

    public function paypalIntegration()
    {
        $requestParams = array(
            'RETURNURL' => 'instagramautolike.localhost.com/payment/success',
            'CANCELURL' => 'instagramautolike.localhost.com/payment/cancelled'
        );

        $orderParams = array(
            'PAYMENTREQUEST_0_AMT' => '500',
            'PAYMENTREQUEST_0_SHIPPINGAMT' => '4',
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'GBP',
            'PAYMENTREQUEST_0_ITEMAMT' => '496'
        );

        $item = array(
            'L_PAYMENTREQUEST_0_NAME0' => 'iPhone',
            'L_PAYMENTREQUEST_0_DESC0' => 'White iPhone, 16GB',
            'L_PAYMENTREQUEST_0_AMT0' => '496',
            'L_PAYMENTREQUEST_0_QTY0' => '1'
        );

        $paypal = new Paypal();
        $response = $paypal->request('SetExpressCheckout', $requestParams + $orderParams + $item);
        print_r($response);
        die;
    }





    /*---------------------------------API For Paypal Ends Here--------------------------------------------------------*/

    /*--------------this is bond shit---------------------*/

// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
    public function paypalApi()
    {
        require __DIR__ . '/PayPal-PHP-SDK/autoload.php';
// 2. Provide your Secret Key. Replace the given one with your app clientId, and Secret
// https://developer.paypal.com/webapps/developer/applications/myapps
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AZL8y_b5_YMk3t9FFpIK39QA9c7GhF16rG0L4KPbnxclQw36MoQBHB0pIOFndHdgz3Ims3he7pPgB1I-',     // ClientID
                'EF0wu84NNCfmnYX3J1aQlGbia15m67pDAmm2kuGXv9-wh69-ofn7Lv_kWcjKzAiE7fxD27oWEHhBzdyp'      // ClientSecret
            )
        );
// 3. Lets try to save a credit card to Vault using Vault API mentioned here
// https://developer.paypal.com/webapps/developer/docs/api/#store-a-credit-card
        $creditCard = new \PayPal\Api\CreditCard();
        $creditCard->setType("visa")
            ->setNumber("4417119669820331")
            ->setExpireMonth("11")
            ->setExpireYear("2019")
            ->setCvv2("012")
            ->setFirstName("Joe")
            ->setLastName("Shopper");
// 4. Make a Create Call and Print the Card
        try {
            $creditCard->create($apiContext);
            echo $creditCard;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }
    }

    /*-------------------------*/


    public function paypal()
    {
        $gateway = Omnipay::create('PayPal_Express');
        $gateway->setUsername('saurabh.kumar_api1.globussoft.com');
        $gateway->setPassword('X6ZH5X2G2U3NNLD9');
        $gateway->setSignature('AFcWxV21C7fd0v3bYYYRCpSSRl31AOpS3AsKcc857KLz99TD-QdguXVE');
//    print_r($gateway);die;
//    $settings = $gateway->getDefaultParameters();
//// default settings array format:
//    array(
//        'username' => 'saurabh.kumar_api1.globussoft.com', // string variable
//        'password'=>'X6ZH5X2G2U3NNLD8',
//        'signature'=>'AFcWxV21C7fd0v3bYYYRCpSSRl31AOpS3AsKcc857KLz99TD-QdguXVE',
//        'testMode' => false, // boolean variable
//        'landingPage' => array('billing', 'login'), // enum variable, first item should be treated as default
//    );
//print_r($settings);die;

        $response = $gateway->purchase(
            array(
                'cancelUrl' => 'http://instagramautolike.localhost.com/admin/paypal',
                'returnUrl' => 'http://instagramautolike.localhost.com/admin/paypal',
                'amount' => 25.00,
                'currency' => 'USD'
            )
        )->send();

        $response->redirect();

    }

    public function cancelurl()
    {
        echo 'cancel Url';
    }

    public function returnurl()
    {
        echo 'return Url';
    }

}