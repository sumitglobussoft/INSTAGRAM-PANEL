<?php

namespace App\Http\Controllers\User;

use App\Http\Models\User;
use App\Http\Models\Usersmeta;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

use stdClass;
use Image;

class ProfileController extends Controller
{
    protected $API_TOKEN;
    private $imageWidth = 1024;//TO BE USED FOR IMAGE RESIZING
    private $imageHeight = 1024;//TO BE USED FOR IMAGE RESIZING

    public function  __construct()
    {
//        $this->API_TOKEN = env('API_TOKEN');
        $this->API_TOKEN = '9876543210'; //TODO REMOVE THIS LINE AND REMOVE ABOVE COMMENT
    }

    public function  showProfileDetails(Request $request)
    {
        $response = new stdClass();
        $objUserModel = new User();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $userId = '';
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $apiToken = 0;
            $authFlag = false;
            if (isset($postData['api_token'])) {
                if ($userId != '') {
                    $where = [
                        'rawQuery' => 'id=?',
                        'bindParams' => [$userId]
                    ];
                    $selectColumn = array('login_token');
                    $userCredentials = $objUserModel->getUsercredsWhere($where, $selectColumn);
                    if ($userCredentials) {
                        $apiToken = $postData['api_token'];
                        if ($apiToken == $this->API_TOKEN) {
                            $authFlag = true;
                        } else if ($apiToken == $userCredentials->login_token) {
                            $authFlag = true;
                        }
                    }
                }
            }

            if ($authFlag) {
                if ($userId != '') {
                    $where = [
                        'rawQuery' => 'users.id =?',
                        'bindParams' => [$userId],
                    ];
                    $userDetails = $objUserModel->getUserDetails($where);
                    if ($userDetails) {
                        $response->code = 200;
                        $response->message = "Success";
                        $response->data = $userDetails;
                    } else {
                        $response->code = 400;
                        $response->message = "No user Details found.";
                        $response->data = null;
                    }
                } else {
                    $response->code = 400;
                    $response->message = "You need to login to view profile setting.";
                    $response->data = null;
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
            }
        } else {
            $response->code = 401;
            $response->message = "Invalid request";
            $response->data = null;
        }
        echo json_encode($response, true);
        die;
    }

    public function updateProfileInfo(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();

            $objUserModel = new User();
            $objUsermetaModel = new Usersmeta();

            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }
            $firstname = "";
            if (isset($postData['firstname'])) {
                $firstname = $postData['firstname'];
            }
            $lastname = "";
            if (isset($postData['lastname'])) {
                $lastname = $postData['lastname'];
            }

            $email = "";
            if (isset($postData['email'])) {
                $email = $postData['email'];
            }
            $username = "";
            if (isset($postData['username'])) {
                $username = $postData['username'];
            }
            $addressline1 = "";
            if (isset($postData['addressline1'])) {
                $addressline1 = $postData['addressline1'];
            }
            $addressline2 = "";
            if (isset($postData['addressline2'])) {
                $addressline2 = $postData['addressline2'];
            }
            $city = "";
            if (isset($postData['city'])) {
                $city = $postData['city'];
            }
            $state = "";
            if (isset($postData['state'])) {
                $state = $postData['state'];
            }
            $country_id = "";
            if (isset($postData['country_id'])) {
                $country_id = $postData['country_id'];
            }
            $contact_no = "";
            if (isset($postData['contact_no'])) {
                $contact_no = $postData['contact_no'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                if ($userId != '') {
                    $where = [
                        'rawQuery' => 'id=?',
                        'bindParams' => [$userId]
                    ];
                    $selectColumn = array('login_token');
                    $userCredentials = $objUserModel->getUsercredsWhere($where, $selectColumn);
                    if ($userCredentials) {
                        $apiToken = $postData['api_token'];
                        if ($apiToken == $this->API_TOKEN) {
                            $authFlag = true;
                        } else if ($apiToken == $userCredentials->login_token) {
                            $authFlag = true;
                        }
                    }
                }
            }

            if ($authFlag) {
                $rules = array(
                    'firstname' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
                    'lastname' => 'required|regex:/^[A-Za-z\s]+$/|max:255',
                    'username' => 'required|regex:/^[A-Za-z0-9._\s]+$/|max:255',
                    'email' => 'required|email|max:255',
                    'user_id' => 'required'
                );
                $messages = [
                    'firstname.regex' => 'The :attribute cannot contain special characters.',
                    'lastname.regex' => 'The :attribute cannot contain special characters.',
                    'username.regex' => 'The :attribute cannot contain special characters.',
                ];
                $validator = Validator::make($request->all(), $rules, $messages);
                if (!$validator->fails()) {

                    $where = [
                        'rawQuery' => 'id =?',
                        'bindParams' => [$userId]
                    ];

                    $currentUserDetails = $objUserModel->getUsercredsWhere($where);
                    $uniqueFlag = false;
                    if ($currentUserDetails->username == $username && $currentUserDetails->username == $email) {
                        $uniqueFlag = true;
                    } else if ($currentUserDetails->username != $username && $currentUserDetails->username == $email) {
                        $uniqueFlag = true;
                    } else if ($currentUserDetails->username == $username && $currentUserDetails->username != $email) {
                        $uniqueFlag = true;
                    } else {
                        $rules = array(
//                            'username' => 'unique:users',
//                            'email' => 'unique:users'
                        );
                        $validator = Validator::make($request->all(), $rules);
                        if ($validator->fails()) {
                            $response->code = 100;
                            $response->message = $validator->messages();
                            $response->data = null;
                            echo json_encode($response, true);
                        } else {
                            $uniqueFlag = true;
                        }
                    }

                    if ($uniqueFlag) {
                        $updateUserWhereId = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$userId]
                        ];
                        $data = array('name' => $firstname, 'lastname' => $lastname, 'username' => $username, 'email' => $email);
                        $updategeneralinfo = $objUserModel->UpdateUserDetailsbyId($updateUserWhereId, $data);

                        $updateUsermetaWhereUserId = [
                            'rawQuery' => 'user_id =?',
                            'bindParams' => [$userId]
                        ];
                        $updateUsermeta = "";
                        $addUsermeta = "";
                        $isUserAvailable = $objUsermetaModel->getUsermetaWhere($updateUsermetaWhereUserId);
                        if ($isUserAvailable) {
                            $dataUpdate = array(
                                'addressline1' => $addressline1,
                                'addressline2' => $addressline2,
                                'city' => $city,
                                'state' => $state,
                                'country_id' => $country_id,
                                'contact_no' => $contact_no
                            );
                            $updateUsermeta = $objUsermetaModel->updateUsermetaWhere($updateUsermetaWhereUserId, $dataUpdate);

                        } else {
                            $addData = array(
                                'user_id' => $userId,
                                'addressline1' => $addressline1,
                                'addressline2' => $addressline2,
                                'city' => $city,
                                'state' => $state,
                                'country_id' => $country_id,
                                'contact_no' => $contact_no,
                                'account_bal' => 0.0000,
                            );
                            $addUsermeta = $objUsermetaModel->addUsermeta($addData);
                        }
                        if ($updategeneralinfo || $updateUsermeta || $addUsermeta) {
                            $response->code = 200;
                            $response->message = "Update Successful";
                            $response->data = $updategeneralinfo;
                            echo json_encode($response, true);
                        } else {
                            $response->code = 400;
                            $response->message = "Information Already updated";
                            $response->data = 1;
                            echo json_encode($response, true);
                        }
                    }

                } else {
                    $response->code = 400;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function updatePassword(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();

            $objUserModel = new User();

            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }
            $oldPassword = "";
            if (isset($postData['oldPassword'])) {
                $oldPassword = $postData['oldPassword'];
            }
            $newPassword = "";
            if (isset($postData['newPassword'])) {
                $newPassword = $postData['newPassword'];
            }
            $conformNewPassword = "";
            if (isset($postData['conformNewPassword'])) {
                $conformNewPassword = $postData['conformNewPassword'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                if ($userId != '') {
                    $apiToken = $postData['api_token'];
                    if ($apiToken == $this->API_TOKEN) {
                        $authFlag = true;
                    } else {
                        $where = [
                            'rawQuery' => 'id=?',
                            'bindParams' => [$userId]
                        ];

                        $selectColumn = array('login_token');
                        $userCredentials = $objUserModel->getUsercredsWhere($where, $selectColumn);
                        if ($userCredentials) {
                            if ($apiToken == $userCredentials->login_token) {
                                $authFlag = true;
                            }
                        }
                    }
                }
            }


            if ($authFlag) {
                $rules = array(
                    'oldPassword' => 'required',
                    'newPassword' => 'required',
                    'conformNewPassword' => 'required|same:newPassword',
                    'user_id' => 'required'
                );
                $message = array(
                    'oldPassword.required' => 'Old Password is require',
                    'newPassword.required' => 'New Password is require',
                    'conformNewPassword.same' => 'Conform New Password is same as New Password ',
                    'user_id.required' => 'User Id is required',
                );
                $validator = Validator::make($request->all(), $rules, $message);
                if (!$validator->fails()) {
                    if ($newPassword != $oldPassword) {
                        $where = [
                            'rawQuery' => 'id =?',
                            'bindParams' => [$userId],
                        ];

                        $currentUserDetails = $objUserModel->getUsercredsWhere($where);
                        if (Hash::check($oldPassword, $currentUserDetails->password)) {
                            $newPassword = Hash::make($newPassword);
                            $data = array('password' => $newPassword);
                            $result = $objUserModel->UpdateUserDetailsbyId($where, $data);
                            if ($result) {
                                $response->code = 200;
                                $response->message = "Password Changed Successfully";
                                $response->data = 1;
                                echo json_encode($response, true);
                            } else {
                                $response->code = 400;
                                $response->message = "Error in Password Update please try again.";
                                $response->data = null;
                                echo json_encode($response, true);
                            }
                        } else {
                            $response->code = 400;
                            $response->message = "Invalid Old Password";
                            $response->data = null;
                            echo json_encode($response, true);
                        }
                    } else {
                        $response->code = 400;
                        $response->message = "New and old password should not be same";
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


    //This method is directly called from Ajax call of profile-setting.blade.php page
    public function changeAvatar(Request $request)
    {

        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();

            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                if ($userId != '') {
                    $apiToken = $postData['api_token'];
                    if ($apiToken == $this->API_TOKEN) {
                        $authFlag = true;
                    } else {
                        $where = [
                            'rawQuery' => 'id=?',
                            'bindParams' => [$userId]
                        ];

                        $selectColumn = array('login_token');
                        $userCredentials = $objUserModel->getUsercredsWhere($where, $selectColumn);
                        if ($userCredentials) {
                            if ($apiToken == $userCredentials->login_token) {
                                $authFlag = true;
                            }
                        }
                    }
                }
            }

            if ($authFlag) {
                if ($userId != '') {
                    if (Input::hasFile('file')) {
                        $validator = Validator::make($request->all(), ['file' => 'image']);
                        if (!$validator->fails()) {
                            $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/../../web/public/assets/uploads/useravatar/';
                            $fileName = $userId . '_' . time() . ".jpg";
                            File::makeDirectory($destinationPath, 0777, true, true);
                            $filePath = $destinationPath . $fileName;

                            $quality = 70;//$this->imageQuality(Input::file('file'));
                            Image::make(Input::file('file'))->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
                                $constraint->aspectRatio();
                            })->save($destinationPath . $fileName, $quality);

                            $filePathUpdate = '/assets/uploads/useravatar/' . $fileName;
                            $updateData['profile_pic'] = $filePathUpdate;
                            $where = [
                                'rawQuery' => 'id =?',
                                'bindParams' => [$userId]
                            ];
                            $userData = $objUserModel->getUsercredsWhere($where);
                            $updatedResult = $objUserModel->UpdateUserDetailsbyId($where, $updateData);

                            if ($updatedResult) {
                                if ($userData->profile_pic != '') {
                                    File::delete(public_path() . '/../../web/public' . $userData->profile_pic);
                                }
                                $response->code = 200;
                                $response->message = "Successfully updated profile image.";
                                $response->data = $filePathUpdate;
                                echo json_encode($response);
                            } else {
                                $response->code = 400;
                                $response->message = "Something went wrong, please try again.";
                                $response->data = null;
                                echo json_encode($response);
                            }
                        } else {
                            $response->code = 100;
                            $response->message = $validator->messages();
                            $response->data = null;
                            echo json_encode($response);
                        }
                    } else {
                        $response->code = 400;
                        $response->message = "Give correct input and Input Image files should be(jpg,gif,png,jpeg)only";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                } else {
                    $response->code = 400;
                    $response->message = "You need to login to change Avtar.";
                    $response->data = null;
                    echo json_encode($response, true);
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

    public function imageQuality($image)
    {
        $imageSize = filesize($image) / (1024 * 1024);
        if ($imageSize < 0.5) {
            return 70;
        } elseif ($imageSize > 0.5 && $imageSize < 1) {
            return 60;
        } elseif ($imageSize > 1 && $imageSize < 2) {
            return 50;
        } elseif ($imageSize > 2 && $imageSize < 5) {
            return 40;
        } elseif ($imageSize > 5) {
            return 30;
        } else {
            return 50;
        }
    }
}// END OF CLASS
