<?php

namespace App\Http\Controllers\User;

use App\Http\Models\User;
use App\Http\Models\Usersmeta;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Mandrill;


include public_path() . "/../vendor/mandrill/src/Mandrill.php";

class AuthenticationController extends Controller
{
    protected $API_TOKEN;
    protected $MANDRILL_KEY;
    protected $HOST_URL;

    public function  __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
        $this->MANDRILL_KEY = env('MANDRILL_KEY');
        $this->HOST_URL = env('HOST_URL');
//        $this->MANDRILL_KEY = 'lSqqGC9W5IZbmrOzyY60cA';
//        $this->API_TOKEN = '9876543210';
//        $this->HOST_URL = 'instagramautolike.localhost.com';//TODO REMOVE THIS LINE AND REMOVE ABOVE COMMENT
    }

    function signUp(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod("POST")) {
            $postData = $request->all();
            $objUsermeta = new Usersmeta();

            $apiToken = "";
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
            }

            if ($apiToken == $this->API_TOKEN) {
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
                    'email.unique' => 'E-Mail address already exist',
                    'password.regex' => 'The :attribute cannot contain special characters except @#$_.',
                ];
                $validator = Validator::make($request->all(), $rules, $messages);
                if (!$validator->fails()) {
                    $password = $postData['password'];

//                //generate random password
//                $password="";
//                $charecters=array_merge(range('A','Z'),range('a', 'z'), range('0', '9'));
//                $max=count($charecters)-1;
//                for($i=0;$i<8;$i++)
//                {
//                    $rand= mt_rand(0,$max);
//                    $password.=$charecters[$rand];
//                }
                    $user = User::create([
                        'name' => $postData['firstname'],
                        'lastname' => $postData['lastname'],
                        'username' => $postData['username'],
                        'email' => $postData['email'],
                        'password' => bcrypt($password),
                        'status' => '0',
                        'role' => '1',
                    ]);


                    if ($user) {
                        $userOriginalData = $user['original'];
                        //$userOriginalData['account_bal'] = 0.0000;

//                        $data['user_id'] = $userOriginalData['id'];
//                        $data['account_bal'] = 0;
//
//                        $result = $objUsermeta->insertUsermeta($data);
//                        if ($result) {
                        $response->code = 200;
                        $response->message = "Signup successful.";
                        $response->data = $userOriginalData;
                        echo json_encode($response);
//                        }

                    }


//                TODO this code is used for sending conformation ,mail with random generated password

//                if ($user) {
//                    $mandrill= new Mandrill($this->MANDRILL_KEY);
//                    $async = false;
//                    $ip_pool = 'Main Pool';
//                    $message = array(
//                        'html' =>'<div>
//                                    <h3>Registration Successful</h3><br>
//                                    <span>please login with this credentials</span><br>
//                                    <p>Username :'.$postData['username'].'<br>
//                                        Password: '.$password.'
//                                    </p>
//                                  </div>',
//                        'subject' => "Registration Successful",
//                        'from_email' => "saurabh.kumar@globussoft.com",//"support@instagramautolikes.com",
//                        'to' => array(
//                            array(
//                                'email' => 'chandrakarramkishan@globussoft.com,', //replace with $postData['email'],
//                                'type' => 'to'
//                            )
//                        ),
//                        'merge_vars' => array(
//                            array(
//                                "rcpt" => 'chandrakarramkishan@globussoft.com,', //replace with $postData['email'],
//                                'vars' => array(
//                                    array(
//                                        "name" => "firstname",
//                                        "content" => $postData['firstname']
//                                    ),
//                                    array(
//                                        "name" => "password",
//                                        "content" => $password
//                                    )
//                                )
//                            )
//                        ),
//                    );
//                    $mailRespons = $mandrill->messages->send($message, $async, $ip_pool);
//
//                    if ($mailRespons[0]['status'] == "sent") {
//                        $response->code = 200;
//                        $response->message = "Signup successful. Please check your email for Password";
//                        $response->data = null;
//                        echo json_encode($response);
//                    }
//                    else{
//                        $objuser = new User();
//                        $whereForUpdate = [
//                            'rawQuery' => 'id =?',
//                            'bindParams' => [$user->id]
//                        ];
//                        $deleteUser = $objuser->deleteUserDetails($whereForUpdate);//If mail sending fails then delete user details from db
//                        $response->code = 400;
//                        $response->message = "some Error occured try again";
//                        echo json_encode($response);
//                    }
//                }
                    else {
                        $response->code = 400;
                        $response->message = "some Error occured try again";
                        $response->data = null;
                        echo json_encode($response);
                    }
                } else {
                    $response->code = 100;
                    $response->message = $validator->messages();
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
            $response->message = "Request Not allowed";
            $response->data = null;
            echo json_encode($response);
        }
    }

    function login(Request $request)
    {

        $response = new stdClass();
        if ($request->isMethod("POST")) {
            $postData = $request->all();
            $apiToken = "";
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
            }

            if ($apiToken == $this->API_TOKEN) {
                $rules = array(
                    'emailOrUsername' => 'required',
                    'password' => 'required',
                );
                $messages = [
                    'emailOrUsername.required' => 'Please enter email address or username ',
                    'password.required' => 'Please enter a password',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);
                if (!$validator->fails()) {
                    $objUserModel = new User();
                    $objUsermetaModel = new Usersmeta();

                    $username = $postData['emailOrUsername'];
                    $password = $postData['password'];
                    $field = 'username';
                    if (strpos($username, '@') !== false) {
                        $field = 'email';
                    }
                    if (Auth::attempt([$field => $username, 'password' => $password])) {
                        $whereForUser = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [Auth::id()]
                        ];
                        $userDetails = $objUserModel->getUsercredsWhere($whereForUser);

                        $whereForUsermeta = [
                            'rawQuery' => 'user_id =?',
                            'bindParams' => [Auth::id()]
                        ];

                        $accountBalance = $objUsermetaModel->getUsermetaWhere($whereForUsermeta, ['account_bal']);
                        if ($accountBalance) {
                            $userDetails->account_bal = $accountBalance->account_bal;
                        }

                        if ($userDetails->status == 1) {
                            if (isset($postData['device_id']) && $postData['device_id'] != "") {
                                $data['device_id'] = $postData['device_id'];
                                $string = $userDetails->id . $postData['device_id'] . $this->API_TOKEN;
                                $token = hash('sha256', $string);
                                $data['login_token'] = $token;
                                $id = $userDetails->id;
                                $whereForUpdate = [
                                    'rawQuery' => 'id =?',
                                    'bindParams' => [$id]
                                ];
                                $objUserModel->UpdateUserDetailsbyId($whereForUpdate, $data);
                                $userDetails->login_token = $token;
                                $userDetails->device_id = $postData['device_id'];
                            }
                            $response->code = 200;
                            $response->message = "Login successful.";
                            $response->data = $userDetails;
                            echo json_encode($response, true);
                        } else if ($userDetails->status == 0) {
                            $response->code = 400;
                            $response->message = " Your account is currently pending approval by the site administrator";
                            $response->data = null;
                            echo json_encode($response, true);
                        } else if ($userDetails->status == 2) {
                            $response->message = 'This account has not been activated.';
                            $response->code = 400;
                            $response->data = null;
                            echo json_encode($response, true);
                        } else if ($userDetails->status == 3) {
                            $response->message = ' Your account is currently rejected by the site administrator.';
                            $response->code = 400;
                            $response->data = null;
                            echo json_encode($response, true);
                        } else if ($userDetails->status == 4) {
                            $response->message = 'This account has been deleted.';
                            $response->code = 400;
                            $response->data = null;
                            echo json_encode($response, true);
                        }
                    } else {
                        $response->message = 'Invalid login Credentials';
                        $response->code = 400;
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 100;
                    $response->message = $validator->messages();
                    echo json_encode($response, true);
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
                echo json_encode($response, true);
            }
        } else {
            $response->code = 401;
            $response->message = "Request Not allowed";
            $response->data = null;
            echo json_encode($response);
        }
    }

    function forgotPassword(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod("POST")) {
            $postData = $request->all();

            $apiToken = "";
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
            }
            $method = "";
            if (isset($postData['method'])) {
                $method = $postData['method'];
            }
            $objUserModel = new User();

            switch ($method) {
                case "enterEmailId" :
                    $fpwemail = '';
                    if (isset($postData['fpwemail'])) {
                        $fpwemail = $postData['fpwemail'];
                    }
                    if ($apiToken == $this->API_TOKEN) {
                        if ($fpwemail != '') {
                            $resetCode = mt_rand(100000, 999999);
                            $exist = $objUserModel->isMailExist($fpwemail, $resetCode);
                            if ($exist) {
                                $whereForUpdate = [
                                    'rawQuery' => 'email = ?',
                                    'bindParams' => [$fpwemail]
                                ];

                                $mandrill = new Mandrill($this->MANDRILL_KEY);
                                $async = false;
                                $ip_pool = 'Main Pool';
                                $message = array(
                                    'html' => "<div ><h3>You are requist for reset password<h3>
                                                <p>Click below link to reset your password</p>
                                                <a href='" . $this->HOST_URL . "/user/verifyResetCode/" . $resetCode . "'>" . env('HOST_URL') . "/user/verifyResetCode/" . $resetCode . "</a>
                                                </div>",

                                    'subject' => "Reset Code",
                                    'from_email' => "support@instagramautolikes.com",//"saurabh.kumar@globussoft.com",
                                    'to' => array(
                                        array(
                                            'email' => $postData['fpwemail'],//'chandrakarramkishan@globussoft.com',// replace with $postData['fpwemail'],
                                            'type' => 'to'
                                        )
                                    ),
                                    'merge_vars' => array(
                                        array(
                                            "rcpt" => $postData['fpwemail'],
                                            'vars' => array(
                                                array(
                                                    "name" => "usermail",
                                                    "content" => $postData['fpwemail'],//'chandrakarramkishan@globussoft.com',// replace with $postData['fpwemail'],
                                                ),
                                                array(
                                                    'name' => 'resetcode',
                                                    'content' => $resetCode
                                                )
                                            )
                                        )
                                    ),
                                );
                                $mailResponse = $mandrill->messages->send($message, $async, $ip_pool);

                                if ($mailResponse[0]['status'] == "sent") {
                                    $response->code = 200;
                                    $response->message = "Mail Sent with Reset code ";
                                    $response->data = 1;
                                } else if ($mailResponse[0]['status'] == "rejected") {
                                    $response->code = 200;
                                    $response->message = "Mail Sending failed";
                                    $response->data = 1;
                                }
                            } else {
                                $response->code = 400;
                                $response->message = "Email Doesn't Exist. Enter correct Email.";
                                $response->data = null;
                            }
                        } else {
                            $response->code = 400;
                            $response->message = "Email is required.";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Access Denied";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;

                case 'verifyResetCode' :
                    $resetCode = '';
                    if (isset($postData['resetCode'])) {
                        $resetCode = $postData['resetCode'];
                    }
                    if ($apiToken == $this->API_TOKEN) {
                        if ($resetCode != '') {
                            $whereForUpdate = [
                                'rawQuery' => 'pd_reset_token = ?',
                                'bindParams' => [$resetCode]
                            ];
                            $exists = $objUserModel->verifyResetCode($whereForUpdate);

                            if ($exists) {
                                $response->code = 200;
                                $response->message = "Reset Code Verified Successfully.";
                                $response->data = $exists;
                            } else {
                                $response->code = 400;
                                $response->message = "Reset Code Didn't Matched.";
                                $response->data = null;
                            }
                        } else {
                            $response->code = 400;
                            $response->message = "You missed something";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 401;
                        $response->message = "Access Denied";
                        $response->data = null;
                    }
                    echo json_encode($response, true);
                    break;

                case 'resetPassword' :
                    $resetCode = '';
                    if (isset($postData['resetCode'])) {
                        $resetCode = $postData['resetCode'];
                    }
                    $password = '';
                    if (isset($postData['newPassword'])) {
                        $password = $postData['newPassword'];
                    }
                    $conformPassword = '';
                    if (isset($postData['conformNewPassword'])) {
                        $conformPassword = $postData['conformNewPassword'];
                    }
                    $rules = array(
                        'resetCode' => 'required',
                        'newPassword' => 'required',
                        'conformNewPassword' => 'required|same:newPassword',
                    );

                    $validator = Validator::make($request->all(), $rules);
                    if (!$validator->fails()) {
                        if ($apiToken == $this->API_TOKEN) {
                            if ($password == $conformPassword) {
                                $exists = $objUserModel->resetPassword($resetCode, Hash::make($password));
                                if ($exists) {
                                    $response->code = 200;
                                    $response->message = "Password Changed Successfully.";
                                    $response->data = $exists;
                                } else {
                                    $response->code = 400;
                                    $response->message = "Something went Wrong. Provide Correct Input.";
                                    $response->data = null;
                                }
                            } else {
                                $response->code = 400;
                                $response->message = "Password Didn't match";
                                $response->data = null;
                            }
                        } else {
                            $response->code = 401;
                            $response->message = "Access Denied";
                            $response->data = null;
                        }
                    } else {
                        $response->code = 400;
                        $response->message = $validator->messages();
                        $response->data=$request->all();
                    }
                    echo json_encode($response, true);
                    break;

                default:
                    break;
            }
        } else {
            $response->code = 400;
            $response->message = "Request Not allowed";
            $response->data = null;
            echo json_encode($response);
        }
    }


}//End of Class
