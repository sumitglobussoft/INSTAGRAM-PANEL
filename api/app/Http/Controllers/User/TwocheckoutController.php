<?php
namespace App\Http\Controllers\User;

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

//require_once("/lib/Twocheckout.php");
require_once public_path() . "/../vendor/2checkout/lib/Twocheckout.php";

class TwocheckoutController extends Controller
{
    protected $API_TOKEN;

    public function  __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
    }

    public function checkout(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();

            $userId=$postData['id'];
            $token = $postData['token'];
            $amount = $postData['money'];
            $name = $postData['name'];
            $addrLine1 = $postData['addrLine1'];
            $city = $postData['city'];
            $state = $postData['state'];
            $country = $postData['country'];
            $email = $postData['email'];
            $zipCode = $postData['zipCode'];
            $phoneNumber = $postData['phoneNumber'];
            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
                if ($apiToken == $this->API_TOKEN) {
                    $authFlag = true;
                } else {
                    $authFlag = false;
                }
            }
            if ($authFlag) {
                $rules = array(
                    'money' => 'required|regex:/^[0-9]+([.][0-9]{0,2}+)?$/',
                    'name' => 'required',
                    'addrLine1' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'zipCode' => 'required',
                    'country' => 'required',
                    'email' => 'required',
                    'phoneNumber' => 'required'
                );
                $message = array(
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
                );
                $validator = Validator::make($request->all(), $rules, $message);
                if (!$validator->fails()) {
                    \Twocheckout::privateKey('1768AF13-92B6-4B9D-8493-66E884E98FEF');
                    \Twocheckout::sellerId('901311477');
                    \Twocheckout::sandbox(true);  #Uncomment to use Sandbox
                    \Twocheckout::verifySSL(false);


                    try {
                        $charge = \Twocheckout_Charge::auth(array(
                            "merchantOrderId" => "123",
                            "token" => $token,
                            "currency" => 'USD',
                            "total" => $amount,
                            "billingAddr" => array(
                                "name" => $name,
                                "addrLine1" => $addrLine1,
                                "city" => $city,
                                "state" => $state,
                                "zipCode" => $zipCode,
                                "country" => $country,
                                "email" => $email,
                                "phoneNumber" => $phoneNumber

                            )
                        ));
//                        echo json_encode($charge,true);die;
//                        echo '<pre>';
//            print_r($charge);die;
                        if ($charge['response']['responseCode'] == 'APPROVED') {
//                            echo "Thanks for your Order!";
//                            echo "<h3>Return Parameters:</h3>";
//                            echo "<pre>";
//                            print_r($charge);
//                            echo "</pre>";
//                            echo die;
                            $transactionId = $charge['response']['transactionId'];
                            $objModelTransaction = new Transaction();
                            $input = array(
                                'tx_id' => '',
                                'tx_type' => '1',   // 1 for Add Money ; 0 for order
                                'tx_mode' => '1',   // 0 for paypal // 1 for 2CO payment method
                                'tx_code' => ' ',
                                'transaction_id' => $transactionId,
                                'user_id' => $userId,
                                'amount' => $amount,
                                'payment_time' => time()+19800,

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
                                'notifications_txt' => '$ ' . $amount . ' is successfully credited to your account through 2CO credit card payment',
                            );
                            $addNotification = $objModelNotification->addNewNotification($input);


                            $response->code = 200;
                            $response->message = "Payment Approved";
                            $response->data = $totalBalance;
                            echo json_encode($response, true);

                        }
                    } catch (\Twocheckout_Error $e) {
                        echo json_encode($e->getMessage(), true);
//                        print_r($e->getMessage());
                    }
                }
            }
        }
    }
}