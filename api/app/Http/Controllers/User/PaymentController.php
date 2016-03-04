<?php
namespace App\Http\Controllers\User;

//use App\Http\Controllers\API;

use App\Http\Models\Notification;
use App\Http\Models\Transaction;
use App\Http\Models\User;
use App\Http\Models\Usersmeta;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use stdClass;
use vendor\Payment\Paypal\Paypal;

include public_path() . "/../vendor/Payment/Paypal/Paypal.php";

class PaymentController extends Controller
{

    protected $API_TOKEN;

    public function  __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
    }

    public function payment(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
                if ($apiToken == $this->API_TOKEN) {
                    $authFlag = true;
                }
            }
            if ($authFlag) {
                $result = paypal::getInstance();
//                print_r($result);
//                $result = 1;
                if ($result) {
                    $response->code = 200;
                    $response->message = "Payment model created successfully.";
                    $response->data = 1;
                    echo json_encode($response, true);
                } else {
                    $response->code = 400;
                    $response->message = "Error in creating model of payment.";
                    $response->data = null;
                    echo json_encode($response, true);
                }

            } else {
                $response->code = 401;
                $response->message = "Access Denied.. Auth flag not set";
                $response->data = null;
                echo json_encode($response, true);
            }
        } else {
            $response->code = 400;
            $response->message = "Request not allowed.. couldnt enter into the method post if statement";
            $response->data = null;
            echo json_encode($response, true);
        }


//        $objPaypal = paypal::getInstance();
//        print_r($objPaypal);
//        die;
    }

    public function addBalance(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $paymentAmount = $postData['money'];
            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
                if ($apiToken == $this->API_TOKEN) {
                    $authFlag = true;
                } else {

                }
            }
            if ($authFlag) {
                $rules = array(
                    'money' => 'required|regex:/^[0-9]+([.][0-9]{0,2}+)?$/',
                );
                $message = array(
                    'money.required' => 'Please Enter Amount that you want to add to your wallet',
                    'money.regex' => 'Please Enter a valid Amount i.e. number or decimal value '
                );
                $validator = Validator::make($request->all(), $rules, $message);
                if (!$validator->fails()) {
                    $returnURL = "http://instagramautolike.localhost.com/expressCallback/" . $paymentAmount;
                    $cancelURL = "http://instagramautolike.localhost.com/paymentError/196";
//                    $ipn_url = 'http://www.myurl.com/dev/inc/paypal/ipn.php';
                    $payment_request_quantity = 1;
                    $description = "Adding Credit";
                    $payment_request_number = 1;
                    $payment_type = "Any";
                    $custom = "";
                    $subscription_type = "";

                    $objpaypal = paypal::getInstance();

                    $result = $objpaypal->CallShortcutExpressCheckout($paymentAmount, $returnURL, $cancelURL, $payment_request_quantity, $description, $payment_request_number, $payment_type, $custom, $subscription_type);
//                    return json_encode($result['TOKEN']);
//                    echo'hrjkhfas';
//                   echo "<pre>"; print_r($result);die;
                    $token = json_encode($result['TOKEN']);

//                    print_r($token);die;
//                    echo 'helrjfklj';
//                    return $token;

//            echo "<pre>";print_r($token);
//            die;
                    if ($result) {
//                        $function_result = $this->myCurlFunction();
//                        return $function_result;

//                        return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token);


//                        return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_notify-validate&token=' . $token);
//                        $function_result=$this->myCurlFunction();
//                        return $function_result;

                        $response->code = 200;
                        $response->message = "Redirecting to the paypal website";
                        $response->data = $token;
                        echo json_encode($response, true);
                    } else {

                        $response->code = 400;
                        $response->message = "Error in payment.";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 100;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response);
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied.";
                $response->data = null;
                echo json_encode($response, true);
            }
        } else {
            $response->code = 401;
            $response->message = "Request not Allowed.";
            $response->data = null;
            echo json_encode($response, true);
        }
    }


//if ($request->isMethod('post')) {
//    $paymentAmount = $request->input('money');
//    $this->validate($request, [
//        'money' => 'required'
//    ], [
//        'money.required' => 'Please Enter Amount that you want to add to your wallet'
//    ]);
//
////            $objPaypal = paypal::getInstance();
////            $description="Adding Credit";
//    $returnURL = "http://instagramautolike.localhost.com/expressCallback/" . $paymentAmount;
//    $cancelURL = "http://instagramautolike.localhost.com/paymentError/196";
//    $payment_request_quantity = 1;
//    $description = "Adding Credit";
//    $payment_request_number = 1;
//    $payment_type = "Any";
//    $custom = "";
//    $subscription_type = "";
//
//    $objpaypal = paypal::getInstance();
//    $result = $objpaypal->CallShortcutExpressCheckout($paymentAmount, $returnURL, $cancelURL, $payment_request_quantity, $description, $payment_request_number, $payment_type, $custom, $subscription_type);
//    $token = $result['TOKEN'];
//    return redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $token);
////            echo "<pre>";print_r($token);
////            die;
//}
//return view('User::user.addbalance');
//}

    public function expressCallback(Request $request)
    {
//        return 23;
        $response = new stdClass();
        $postData = $request->all();
        $userId = $postData['id'];
        $amount = $postData['amount'];
        $payerid = $postData['PayerID'];
        $token = $postData['token'];
//        print_r($token);print_r($payerid);die;
        $authFlag = false;
        if (isset($postData['api_token'])) {
            $apiToken = $postData['api_token'];
            if ($apiToken == $this->API_TOKEN) {
                $authFlag = true;
            }
        }
        if ($authFlag) {
            $objpaypal = paypal::getInstance();
            $result = $objpaypal->ConfirmPayment($amount, $token, $payerid);
//                echo"<pre>";print_r($result);die;
//            echo json_encode($result, true);
            if ($result) {
//                $function_result=$this->myCurlFunction();
//                return $function_result;

                if ($result['ACK'] == "Success") {
//                    $response->code = 200;
//                    $response->message = "Amount added to your Wallet Successfully.";
                    $data['transactionId'] = $result['PAYMENTINFO_0_TRANSACTIONID'];
                    $data['acknowledgement'] = $result['ACK'];
                    $data['amount'] = $amount;
                    $data['paymentTime'] = date('Y-m-d H:i:s');

                    $transactionId = $data['transactionId'];
                    $amount = $data['amount'];
                    $paymentTime = $data['paymentTime'];
                    $objModelTransaction = new Transaction();
                    $input = array(
                        'tx_id' => '',
                        'tx_type' => '1',   // 1 for Add Money ; 0 for order
                        'tx_mode' => '0',   // 0 for paypal
                        'tx_code' => ' ',
                        'transaction_id' => $transactionId,
                        'user_id' => $userId,
                        'amount' => $amount,
                        'payment_time' => strtotime($paymentTime),

                    );
                    $result = $objModelTransaction->addNewTransaction($input);


                    //code for increasing the amount (updating the account bal)
                    // first checking that user has details in usersmeta table or not, if not then acc_bal will be 0 & add users with amount
                    // or if yes then update accountbalance


                    $objModelUsermeta = new Usersmeta();

                    $whereForUpdateUser = array(
                        'rawQuery' => 'user_id = ?',
                        'bindParams' => [$userId]
                    );

                    $isUserAvailable = $objModelUsermeta->getUsermetaWhere($whereForUpdateUser);
                    if ($isUserAvailable) {
                        $accountBal = $isUserAvailable->account_bal;
                        $totalBalance = $accountBal + $amount;
                        $dataForUpdateUser = array('account_bal' => $totalBalance);
//                        return $dataForUpdateUser;
                        $updated = $objModelUsermeta->updateUsermetaWhere($dataForUpdateUser, $whereForUpdateUser);

                    } else {
                        $accountBal = 0;
                        $totalBalance = $accountBal + $amount;
                        $addData = array(
                            'user_id' => $userId,
                            'account_bal' => $totalBalance,
                        );
                        $addUsermeta = $objModelUsermeta->addUsermeta($addData);
                    }
                    // code for generating NOTIFICATION
                    $objModelNotification = Notification::getInstance();
                    $input = array(
                        'notification_id' => '',
                        'user_id' => $userId,
                        'notifications_txt' => '$ ' . $amount . ' is successfully credited to your account',
                    );
                    $addNotification = $objModelNotification->addNewNotification($input);


                    $response->code = 200;
                    $response->message = "Amount added to your Wallet Successfully.";
//                    $response->notification="Your Account is Successfully Credited.";
                    $response->data = $totalBalance;


//                    echo '<pre>';print_r($response);die;
//                    return $response;
                    echo json_encode($response, true);

                } else if ($result['ACK'] == "SuccessWithWarning") {
//                        return 6;
                    $response->code = 007;
                    $response->message = "Amount added to your Wallet Successfully.";
                    $data['transactionId'] = $result['PAYMENTINFO_0_TRANSACTIONID'];
                    $data['acknowledgement'] = $result['ACK'];
                    $data['amount'] = $amount;
                    $data['paymentTime'] = date('Y-m-d H:i:s');
                    $response->data = $data;
                    echo json_encode($response, true);
//                        echo'<pre>';print_r($data);die;
//                        return $response->data = $data;
//                        echo "<pre>";
//                        print_r($result);
//                        die;

//                        return 7;
                } else {
                    $response->code = 400;
                    $response->message = "Some ERROR OCCURRED.";

                    echo "<pre>";
                    print_r($result);
                    die;
                }
            }
//                if ($result['ACK'] == "Success") {
//                    $response->code = 200;
//                    $response->message = "Payment model created successfully.";
//                    $data['transactionId'] = $result['PAYMENTINFO_0_TRANSACTIONID'];
//                    $data['acknowledgement'] = $result['ACK'];
//                    $data['amount'] = $amount;
//                    $data['paymentTime'] = date('Y-m-d H:i:s');
//                    $response->data = $data;
//                }
//                if ($result) {
//                    $response->code = 200;
//                    $response->message = "Payment model created successfully.";
//                    $response->data = 1;
//                    echo json_encode($response, true);
        } else {
            $response->code = 400;
            $response->message = "Error in getting callback results. auth flag is not set";
            $response->data = null;
            echo json_encode($response, true);
        }


//
//        $payerid = $request->input('PayerID');
//        $token = $request->input('token');
//
//        $objpaypal = paypal::getInstance();
//        $result = $objpaypal->ConfirmPayment($amount, $token, $payerid);
//
////        echo "<pre>";print_r($result);
////        die;
//        if ($result['ACK'] == "Success") {
//            $data['transactionId'] = $result['PAYMENTINFO_0_TRANSACTIONID'];
//            $data['acknowledgement'] = $result['ACK'];
//            $data['amount'] = $amount;
//            $data['paymentTime'] = date('Y-m-d H:i:s');
//        } else {
//            echo "<pre>";
//            print_r($result);
//            die;
//        }
//        echo "<pre>";
//        print_r($data);
//        die;
    }

    public function myCurlFunction()
    {
// STEP 1: read POST data
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
//        return $req;

        $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?' . $req;
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  # Set curl to return the data instead of printing it to the browser.
        // curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); # Some server may refuse your request if you dont pass user agent
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //execute post
        $result = curl_exec($ch);
        return $result;





//        print_r($url);die;
//        $response = $this->curlUsingGet($url);
//
//
//// Step 2: POST IPN data back to PayPal to validate
//        $ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr?');
//        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
//
//// In wamp-like environments that do not come bundled with root authority certificates,
//// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
//// the directory path of the certificate as shown below:
//// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
//
//        $result = curl_exec($ch);
////        print_r($result);die;
//
//        if (!($res = curl_exec($ch))) {
////            echo "<pre>"; print_r($res);die;
////            return $res;
//            // error_log("Got " . curl_error($ch) . " when processing IPN data");
//            curl_close($ch);
//            exit;
//
//        }
////        echo "<pre>"; print_r($res);die;
////        return $res;
        curl_close($ch);

//        if (strcmp($res, "VERIFIED") == 0) {
//            return 1;
//        } else if (strcmp($res, "INVALID") == 0) {
//            return 0;
//            // IPN invalid, log for manual investigation
//        }

    }


}