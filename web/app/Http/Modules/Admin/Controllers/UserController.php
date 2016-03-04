<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Usersmeta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Modules\Admin\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use stdClass;
use DB;
use Illuminate\Support\Collection;
use Mandrill;

include public_path() . "/../vendor/mandrill/src/Mandrill.php";

class UserController extends Controller
{
    /**
     * Display all pending users.
     *
     * @return Response
     * @since 19-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function pendingUsers()
    {
        $objModelUser = User::getInstance();
        $whereForUsers = array(
            'rawQuery' => 'role = 1 and status = 0'
        );
        $allUsers = $objModelUser->getAllUsersWhere($whereForUsers);

        return view('Admin::users.pendingusers', ['users' => $allUsers]);

    }

    public function userAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $method = $request->input('method');
            switch ($method) {
                case "changeStatus":
                    $userId = $request->input('id');
                    $status = $request->input('status');
                    $objModelUser = User::getInstance();
                    $whereForUpdateUser = array(
                        'rawQuery' => 'id = ?',
                        'bindParams' => [$userId]
                    );
                    $dataForUpdateUser = array('status' => $status);
                    $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);
                    if ($updated) {
                        $message = '';
                        if ($status == 1) {
                            $message = "User approved.";
                        } else {
                            $message = "User rejected.";
                        }
                        echo json_encode(array('status' => '200', 'message' => $message));

                    } else {
                        echo json_encode(array('status' => '400', 'message' => 'Failed. Plesae try again.'));
                    }
                    break;

                default:
                    break;
            }
        }
    }

    /**
     * Display all available users.
     *
     * @return Response
     * @since 21-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function availableUsers()
    {
        $objModelUser = User::getInstance();
        $whereForUsers = array(
            'rawQuery' => 'role = 1 and (status = 1 or status = 2)'
        );
        $allActiveUsers = $objModelUser->getAllUsersWhere($whereForUsers);
//        foreach($allActiveUsers as $active){
//            $id=$active->id;
//            print_r($id);
//        }
//        die;
//        dd($allActiveUsers);
        return view('Admin::users.availableusers', ['approved_users' => $allActiveUsers]);//, ['approved_users' => $allActiveUsers]

    }

//    public function availableUsersDatatables()
//    {
//        $objModelUser = User::getInstance();
//        $whereForUsers = array(
//            'rawQuery' => 'role = 1 and (status = 1 or status = 2)'
//        );
//        $allActiveUsers = $objModelUser->getAllUsersWhere($whereForUsers);
//
////        dd($allActiveUsers);
//
//
//        $users = new Collection;
//
//        $allActiveUsers = json_decode(json_encode($allActiveUsers), true);
////        dd($allRejectedUsers);
//        $i = 0;
//        foreach ($allActiveUsers as $active) {
//            if ($active['status'] == 1) {
//                $style = 'btn-success';
//                $statement = 'Active';
//            } else {
//                $style = 'btn-danger';
//                $statement = 'Inactive';
//            }
//            $id=$active['id'];
//            $users->push([
//                'id' => ++$i,
//                'fullname' => $active['name'] . $active['lastname'],
//                'username' => $active['username'],
//                'email' => $active['email'],
////                'action_change_status' => '<i data=' . $active['id'] . ' class="status_checks btn"' . $style . '"> hello</i>',
////                'action_edit'=>'<a href ="/admin/edituser/' .$active['id']  . '" class="btn btn-warning">edit</a>'
//                'edit' => '<a href ="edituser/' . $id . '" class="btn btn-warning">edit</a>'
//            ]);
//        }
//
//        return Datatables::of($users)->make(true);
//
//
//    }

    /**
     * Changing available Users from active to inactive and viceversa.
     *
     * @return Response
     * @since 21-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function availableUserAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $method = $request->input('method');
            switch ($method) {
                case "changeStatus":
                    $userId = $request->input('id');
                    $status = $request->input('status');
                    $objModelUser = User::getInstance();
                    $whereForUpdateUser = array(
                        'rawQuery' => 'id = ?',
                        'bindParams' => [$userId]
                    );
                    $dataForUpdateUser = array('status' => $status);
                    $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);
                    if ($updated) {
                        $message = '';
                        if ($status == 1) {
                            $message = "User Inactivated.";
                        } else {
                            $message = "Already active.";
                        }

                        echo json_encode(array('status' => '200', 'message' => $message));

                    } else {
                        echo json_encode(array('status' => '400', 'message' => 'Failed. Plesae try again.'));

                    }
                    break;

                default:

                    break;
            }
        }
    }

    public function addUser(Request $request)
    {
        $objModelUser = User::getInstance();
        if ($request->isMethod('post')) {

            $firstname = $request->input('firstname');
            $lastname = $request->input('lastname');
            $username = $request->input('username');
            $email = $request->input('email');
            $password = $request->input('password');
            $password_confirmation = $request->input('password_confirmation');

            $this->validate($request, [
                'firstname' => 'required',
                'lastname' => 'required',
                'username' => 'required|alpha_num|min:3|max:30',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:3|confirmed',
//                'password_confirmation' => 'required|min:3',

            ], ['firstname.required' => 'Please enter a name',
                    'lastname.required' => 'Please enter lastname',
                    'username.required' => 'Please enter a username',
                    'username.alpha_num' => 'Please enter only alphabets and numbers',
                    'username.min' => 'The username should be more than 3 characters.',
                    'username.max' => 'The username should not be greater than 30 characters.',
                    'email.required' => 'Please enter email address',
                    'email.email' => 'Please enter a valid email',
                    'email.unique' => 'This email already exists, Please choose another',
                    'password.required' => 'Please Enter Password.',
//                    'password_confirmation.required' => 'Please Re-type the password.',
                ]
            );
            $dataForInsertUser = array('name' => $firstname, 'lastname' => $lastname, 'username' => $username, 'email' => $email, 'password' => Hash::make($password), 'role' => 1, 'status' => 1);
            $added = $objModelUser->addNewUser($dataForInsertUser);
            if ($added) {

                $key = env('MANDRILL_KEY');
                $mandrill = new Mandrill($key);
                $async = false;
                $ip_pool = 'Main Pool';
                $message = array(
                    'html' => "<html><body>Hello *|username|* <br><p>your username is:*|username|* and password is:*|password|* </p> </body></html>",
                    'subject' => "Welcome Mail",
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
                                array(
                                    "name" => "name",
                                    "content" => $firstname
                                ),
                                array(
                                    "name" => "username",
                                    "content" => $username
                                ),
                                array(
                                    "name" => "password",
                                    "content" => $password
                                )
                            )
                        )
                    ),
                );

                $mailrespons = $mandrill->messages->send($message, $async, $ip_pool);
                //  dd($mailrespons);
                if ($mailrespons[0]['status'] == "sent") {
                    return redirect('admin/users-list-active')->with(['status' => 'Success', 'msg' => 'User has been added successfully. Username and Password has sent to registered email address.']);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'msg' => 'Missing Something. Or may be the email is not a valid one']);
                }
            } else {
                return Redirect::back()->with(['status' => 'Error', 'msg' => 'Some Problem Occurred in adding user. Plz reload the page and try again.']);
            }
        }
        return view('Admin::users.adduser');
    }

    public function editUser($id, Request $request)
    {
        $objModelUser = User::getInstance();

        if ($request->isMethod('post')) {

            $firstname = $request->input('firstname');
            $lastname = $request->input('lastname');
            $username = $request->input('username');
            $email = $request->input('email');
            $account_bal = $request->input('account_bal');
            $currentpassword = $request->input('currentpassword');
            $password = $request->input('password');
            $password_confirmation = $request->input('password_confirmation');
            $changeGeneralInfo = $request->input('change-generalinfo');
            $editPassword = $request->input('edit-password');
            if (isset($changeGeneralInfo)) {
                $this->validate($request, [
                    'firstname' => 'required',
                    'lastname' => 'required',
                    'username' => 'required',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'account_bal' => 'regex:/^[1-9]\d*(\.\d+)?$/',//  /^[0-9]{0,4}+([.][0-9]+)?$/
                ], ['firstname.required' => 'Please enter a name',
                        'lastname.required' => 'Please enter your lastname',
                        'username.required' => 'Please enter a username',
                        'email.required' => 'Please enter email address',
                        'email.email' => 'Please enter a valid email',
                        'email.unique' => 'This email already exists, Please choose another',
//                        'account_bal.required' => 'Please Enter Amount that you want to add to your wallet',
                        'account_bal.regex' => 'Please Enter a valid Amount i.e. number or decimal value '

                    ]
                );

                $whereForUpdateUser = array(
                    'rawQuery' => 'id = ?',
                    'bindParams' => [$id]
                );
                $dataForUpdateUser = array('name' => $firstname, 'lastname' => $lastname, 'username' => $username, 'email' => $email);
                $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);

                //for adding  or updating account_bal in usersmeta table

                $objModelUsermeta = Usersmeta::getInstance();

                $whereUsermeta = array(
                    'rawQuery' => 'user_id = ?',
                    'bindParams' => [$id]
                );

                $isUserAvailable = $objModelUsermeta->getUsermetaWhere($whereUsermeta);
                if ($isUserAvailable) {
                    $dataForUpdateUserMeta = array('account_bal' => $account_bal);
//                        return $dataForUpdateUser;
                    $updateUserMeta = $objModelUsermeta->updateUserMetaWhere($dataForUpdateUserMeta, $whereUsermeta);

                } else {

                    $addData = array(
                        'user_id' => $id,
                        'account_bal' => $account_bal,
                        'currency_id' => 1,
                    );
                    $addUsermeta = $objModelUsermeta->addNewUserMeta($addData);
                }

                if ($updated || $updateUserMeta) {
//                    return redirect('admin/dashboard')->with('msg', 'your data has updated');
                    return Redirect::back()->with(['status' => 'Success', 'message' => 'Your Profile has Updated.']);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'message' => 'Something went wrong, please reload the page and try again...']);
                }
            } elseif (isset($editPassword)) {

                $this->validate($request, [
//                    'oldpassword' => 'required',
                    'currentpassword' => 'required',
                    'password' => 'required|min:3|confirmed',
                    'password_confirmation' => 'required|min:3',
                ], [

                    'currentpassword.required' => 'Please Enter Current Password.',
                    'password.required' => 'New Password Required',
                    //'newpassword_confirmation.required'=>'Please Re-type Password',
                ]);

                $whereForUpdateUser = array(
                    'rawQuery' => 'id = ?',
                    'bindParams' => [$id]
                );
                $userDetails = $objModelUser->getUserWhere($whereForUpdateUser);
                if (Hash::check($currentpassword, $userDetails->password)) {
                    $dataForUpdateUser = array('password' => Hash::make($password));
                    $updated = $objModelUser->updateUserWhere($dataForUpdateUser, $whereForUpdateUser);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'pswdErr' => 'Your Current Password didnt match']);
                }
                if ($updated) {
                    return Redirect::back()->with(['status' => 'Success', 'message' => 'Your Password has Updated.']);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'message' => 'Something went wrong, please reload the page and try again...']);
                }

            }
        }
//        $whereForUser = array(
//            'rawQuery' => 'id = ?',
//            'bindParams' => [$id]
//        );
//        $UserDetails = $objModelUser->getAllUsersWhere($whereForUser);

//        $first = DB::table('users')
//            ->where('users.id', '=', $id)
//            ->get();
//        echo '<pre>';
//        print_r($first);
//        die;
//        $accountBalance = DB::table('usersmeta')
//            ->join('users', 'users.id', '=', 'usersmeta.user_id')
//            ->join('currencies', 'usersmeta.currency_id', '=', 'currencies.currency_id')
//            ->select('users.id', 'currencies.conversion_symbol', 'usersmeta.account_bal')
//            ->where('users.id', '=', $id)
//            ->get();
//        print_r($accountBalance);
//        die;
        $objModelUsermeta = Usersmeta::getInstance();
        $where = array('rawQuery' => 'users.id = ?', 'bindParams' => [$id]);
        $selectedColumns = ['users.*', 'usersmeta.account_bal', 'currencies.conversion_symbol'];
        $userDetails = $objModelUsermeta->getUserMetaInfoByUserId($where, $selectedColumns);
//        print_r($userDetails);die;

        return view('Admin::users.edituser', ['suppDetails' => $userDetails]);
    }

    public function rejectedUsers()
    {

//        return view('Admin::users.rejectedusers', ['rejected_users' => $allRejectedUsers]);

        return view('Admin::users.rejectedusers');
    }

    public function rejectedUsersAjaxHandler(Request $request)
    {
        $objModelUser = User::getInstance();
        $whereForUsers = array(
            'rawQuery' => 'role = 1 and status = 3'
        );
        $allRejectedUsers = $objModelUser->getAllUsersWhere($whereForUsers);
//        return view('Admin::users.rejectedusers', ['rejected_users' => $allRejectedusers]);
//       $var= print_r(json_decode(json_encode($allRejectedUsers), true));

//        DB::statement(DB::raw('set @rownum=0'));
//        $users = DB::table('users')
//            ->select([ DB::raw('@rownum  := @rownum  + 1 AS rownum'), 'name', 'lastname', 'username', 'email'])
//            ->where('role', '=', '1')
//            ->where('status', '=', '3')
//        ;
//        dd($allRejectedUsers);
        $users = new Collection;

        $allRejectedUsers = json_decode(json_encode($allRejectedUsers), true);
//        dd($allRejectedUsers);
        $i = 0;
        foreach ($allRejectedUsers as $valueRS) {
            $users->push([
                'id' => ++$i,
                'fullname' => $valueRS['name'] . $valueRS['lastname'],
                'username' => $valueRS['username'],
                'email' => $valueRS['email'],
            ]);
        }

        return Datatables::of($users)->make(true);
    }

}