<?php

namespace InstagramAutobot\Http\Modules\Supplier\Controllers;


use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Modules\Supplier\Models\User;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class SupplierController extends Controller
{

    protected $apiurl;
    protected $API_TOKEN;

    public function __construct()
    {
        $this->apiurl = env('API_URL');
        $this->API_TOKEN=env('API_TOKEN');
//        $this->apiurl = 'api.instagramautolike.localhost.com';
//        $this->API_TOKEN = 9876543210;
    }

    function login(Request $request)
    {
        if (Session::has('ig_supplier')) {//|| $request->session()->has('ig_supplier')) {
            return redirect('/supplier/dashboard');
        }
        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/login';
            $data['username'] = $request['emailOrUsername'];
            $data['password'] = $request['password'];
            $data['api_token']=$this->API_TOKEN;
            //$data['rememberMe']=$request['rememberMe'] == 'on' ? true : false;
            $objCurlHandler = CurlRequestHandler::getInstance();

            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

            $field = 'username';
            if (strpos($data['username'], '@') !== false) {
                $field = 'email';
            }

            if ($curlResponse->code == 200) {
                if (Auth::attempt([$field => $data['username'], 'password' => $data['password']])) {
                    if ($curlResponse->data['role'] == 1) {
                        Session::put('ig_supplier', $curlResponse->data);
                       // dd($curlResponse->data);

                        return redirect('/supplier/dashboard');
                    } else
                        return Redirect::back()->with('errMsg', 'Invalid credentials.')->withInput();
                    // return view('Supplier::supplier.login')->withErrors(['errMsg' => 'Invalid credentials.']);
                } else
                    return Redirect::back()->with('errMsg', 'Invalid credentials.')->withInput();
                //return view('Supplier::supplier.login')->withErrors(['errMsg' => 'Invalid credentials.']);
            } else
                return Redirect::back()->withErrors($curlResponse->message)->withInput();
        }
        return view('Supplier::supplier.login');
//        return view('Supplier/Views/supplier/login');
    }

    function forgotPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), ['email' => 'required|email'], ['email.unique' => 'The: attribute is required']);
            if (!$validator->fails()) {
                $url = $this->apiurl . '/forgotPassword';
                $data['fpwemail'] = $request['email'];
                $data['method'] = 'enterEmailId';
                $data['api_token']=$this->API_TOKEN;
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
//        return view('Supplier/Views/forgotPassword');
        return view('Supplier::supplier.forgotPassword');
    }


    function resetPassword(Request $request, $resetCode = "", $id = "")
    {
        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/forgotPassword';
            $data['fpwemail'] = $request['fpwemail'];
            $data['password'] = $request['password'];
            $data['conformPassword'] = $request['conformPassword'];
            $data['method'] = 'resetPassword';
            $data['api_token']=$this->API_TOKEN;
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            if ($curlResponse->code == 200) {
               //TODO change this statement when ajax call used
                return view('Supplier::supplier.login');
            } else {
                return view('Supplier::supplier.resetPassword')->withErrors(['errMsg' => $curlResponse->message]);
            }

        } else {
            $url = $this->apiurl . '/forgotPassword';
            $data['id'] = $id;
            $data['resetCode'] = $resetCode;
            $data['method'] = 'verifyResetCode';

            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

            if ($curlResponse->code == 200) {
                return view('Supplier::supplier.resetPassword');
            } else {
                return view('Supplier::supplier.forgotPassword')->withErrors(['errMsg' => $curlResponse->message]);
            }
        }
    }

    function updatePassword(Request $request)
    {
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
            $url = $this->apiurl . '/supplier/updatePassword';
            $data['oldPassword'] = $request['oldPassword'];
            $data['newPassword'] = $request['newPassword'];
            $data['conformNewPassword'] = $request['conformNewPassword'];
            $data['userId'] = Session::get('ig_supplier')['id'];
            $data['api_token']=$this->API_TOKEN;
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

            if ($curlResponse->code == 200) {
                echo $curlResponse->message;
            } else
                echo $curlResponse->message;
        } else
            echo $validator->messages();
    }

    function register(Request $request)
    {
        if (Session::has('ig_supplier')) {//|| $request->session()->has('ig_supplier')) {
            return redirect('/supplier/dashboard');
        }

        if ($request->isMethod('post')) {
            $url = $this->apiurl . '/signUp';

            $data['firstname'] = $request['firstname'];
            $data['lastname'] = $request['lastname'];
            $data['username'] = $request['username'];
            $data['email'] = $request['email'];
            $data['password'] = $request['password'];
            $data['role'] = 1;
            $data['api_token']=$this->API_TOKEN;
            $objCurlHandler = CurlRequestHandler::getInstance();
            $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
            //echo '<pre>'; print_r($curlResponse);die;

            if ($curlResponse->code == 200) {
                $responseData = $curlResponse->data;
                if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                    if ($responseData['role'] == 1) {
                        Session::put('ig_supplier', $responseData);
//                    dd(Session::all());
                        return redirect('supplier/dashboard');
                    } else
                        return redirect('supplier/login');
                }
            } else
                if ($curlResponse->code == 400) {
                    return Redirect::back()->withErrors(['registerErrMsg' => $curlResponse['message']])->withInput();
                } else {
                    return Redirect::back()->withErrors(['registerErrMsg' => 'Something went wrong, please try again.'])->withInput();
                }
        }
        return view('Supplier::supplier.register');
    }


    function dashboard()
    {
        return view('Supplier::supplier.dashboard');
    }

    public function logout()
    {
        Session::forget('ig_supplier');
        return redirect('/supplier/login');
    }

    function profileView(Request $request)
    {
        $url = $this->apiurl . '/supplier/showProfileDetails';
        $data['user_id'] = Session::get('ig_supplier')['id'];
        $data['api_token'] = $this->API_TOKEN;
        $objCurlHandler = CurlRequestHandler::getInstance();
        $curlResponse = $objCurlHandler->curlUsingPost($url, $data);
        $userDetails = $curlResponse->data;
        return view('Supplier::supplier.showProfile', ['userData' => $userDetails]);
    }

    public function updateProfileInfo(Request $request)
    {
        $url = $this->apiurl . '/supplier/updateProfileInfo';

        $data['user_id'] = Session::get('ig_supplier')['id'];
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
        $userDetails = $curlResponse->data;
        echo "asd";
    }

    public function changeAvatar(Request $request)
    {
        if (Input::hasFile('file')) {
            $validator = Validator::make($request->all(), ['file' => 'image']);
            if (!$validator->fails()) {

                $url = $this->apiurl . '/supplier/changeAvatar';
                $data['user_id'] = Session::get('ig_supplier')['id'];
                $data['api_token'] = $this->API_TOKEN;
                $data['file']=Input::file('file');

                $objCurlHandler = CurlRequestHandler::getInstance();
                $curlResponse = $objCurlHandler->curlUsingPost($url, $data);

               dd($curlResponse);

            }else{
                echo "eror";
                echo json_encode(array('status' => 2, 'message' => $validator->messages()->all()));
            }

        } else {
            echo "false";
            die;
        }
    }
}
