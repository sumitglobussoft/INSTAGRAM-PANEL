<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Modules\Admin\Models\Plan;
use InstagramAutobot\Http\Modules\Admin\Models\Usergroup;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class UsergroupController extends Controller
{

    /* public function usergroupDetails()
    {
        $objUserGroup = Usergroup::getInstance();
        $where = array(
            'rawQuery' => 'usergroup_status=1'
        );
        $ugDetails = $objUserGroup->getAllUsergroupsWhere($where);
        return view('Admin::usergroup.usergroupDetails', ['ugDetails' => $ugDetails]);

    }

     public function usergroupAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $usergroup_id = $request->input('ugId');
            $objModelPlans = Plan::getInstance();
            $where = array(
                'rawQuery' => 'for_usergroup_id=?',
                'bindParams' => [$usergroup_id]
            );
            $ugPlansDetails = $objModelPlans->getAllPlansWhere($where);

            $where = array(
                'rawQuery' => 'status=?',
                'bindParams' => [1]
            );
            $OriginalPlansListDetails = $objModelPlans->getAllPlansWhere($where);

            foreach ($ugPlansDetails as $ugPlan) {
                $parentPlanId = $ugPlan->parent_plan_id;
                foreach ($OriginalPlansListDetails as $originalPlan) {
                    $planId = $originalPlan->plan_id;
                    if ($parentPlanId == $planId) {
                        $ugPlan->actualRate = $originalPlan->charge_per_unit;
                    }
                }

            }
            if ($ugPlansDetails) {
                echo json_encode(array('status' => '200', 'message' => 'success', 'data' => $ugPlansDetails));
            } else {
                echo json_encode(array('status' => '400', 'message' => 'No Plans are available in this UserGroups'));
            }


        }

    }*/

    public function usergroupAjaxHandlerDelete(Request $request)
    {
        if ($request->isMethod('post')) {
            $ugId = $request->input('ugId');
            $objModelUserGroup = Usergroup::getInstance();
            $whereForDelUsergroup = array('rawQuery' => 'usergroup_id=?', 'bindParams' => [$ugId]);
            $ugDeleted = $objModelUserGroup->deleteUserGroupWhere($whereForDelUsergroup);

            $objPlan = Plan::getInstance();
            $where = array(
                'rawQuery' => 'for_usergroup_id=?',
                'bindParams' => [$ugId]
            );
            $deleted = $objPlan->deletePlanWhere($where);
            if ($deleted) {
                echo json_encode(array('status' => '200', 'message' => 'deleted'));
            } else {
                echo json_encode(array('status' => '400', 'message' => 'error in deletion'));
            }

        }

    }

    public function usergroupAjaxHandlerDeleteInEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $planId = $request->input('planId');

            $objPlan = Plan::getInstance();
            $where = array(
                'rawQuery' => 'plan_id=? and for_usergroup_id!=?',
                'bindParams' => [$planId, 0]
            );
            $deleted = $objPlan->deletePlanWhere($where);
            if ($deleted) {
                echo json_encode(array('status' => '200', 'message' => 'deleted'));
            } else {
                echo json_encode(array('status' => '400', 'message' => 'error in deletion'));
            }

        }

    }

    public function usergroups(Request $request)
    {
        //TODO write datatables code here to show simple list of usergroups
        $objUserGroup = Usergroup::getInstance();
        $where = array(
            'rawQuery' => 'usergroup_status=1'
        );
        $ugDetails = $objUserGroup->getAllUsergroupsWhere($where);

        return view('Admin::usergroup.usergroups', ['ugDetails' => json_decode($ugDetails, true)]);

    }

    public function editUsergroup(Request $request, $ugid)
    {
        $objModelUserGroup = Usergroup::getInstance();
        $where = array(
            'rawQuery' => 'usergroup_id=?',
            'bindParams' => [$ugid]
        );
        $usergroupDetails = json_decode($objModelUserGroup->getUsergroupWhere($where), true);
        $objModelPlan = Plan::getInstance();
        $whereForAllPlans = array(
            'rawQuery' => 'plans.for_usergroup_id=0',
        );
        $allPlans = json_decode($objModelPlan->getAllPlansWhere1($whereForAllPlans), true);

        if ($request->isMethod('post')) {
            $postData = $request->all();
//            dd($postData);
            $rules = [
                'usergroup_name' => 'required'
            ];
            $messages = [
                'usergroup_name.required' => "Please enter a name for usergroup."
            ];
            if (isset($postData['plans']['data'])) {
                foreach ($postData['plans']['data'] as $keyP => $valP) {
                    $rules["plans.data.$keyP.charge_per_unit"] = 'required|regex:/^\d*(\.\d{2})?$/';
                    $messages["plans.data.$keyP.charge_per_unit.required"] = "Please enter a price.";
                    $messages["plans.data.$keyP.charge_per_unit.regex"] = "Please enter a valid price.";
                }
            }
            if (isset($postData['plans']['newdata'])) {
                foreach ($postData['plans']['newdata'] as $keyP => $valP) {
                    $rules["plans.newdata.$keyP.charge_per_unit"] = 'required|regex:/^\d*(\.\d{2})?$/';
                    $messages["plans.newdata.$keyP.charge_per_unit.required"] = "Please enter a price.";
                    $messages["plans.newdata.$keyP.charge_per_unit.regex"] = "Please enter a valid price.";
                }
            }

            $validator = Validator::make($postData, $rules, $messages);
            if ($validator->fails()) {
                return Redirect::back()
                    ->with(["code" => '400', 'message' => 'Please correct the following errors.'])
                    ->withErrors($validator)
                    ->withInput();
            } else {
                //TODO update usergroups table
                $whereForUg = ['rawQuery' => 'usergroup_id = ?', 'bindParams' => [$ugid]];
                $dataForUG = ['usergroup_name' => $postData['usergroup_name']];
                $updatedUG = json_decode($objModelUserGroup->updateUsergroupWhere($dataForUG, $whereForUg), true);
                if ($updatedUG['code'] == 200 || $updatedUG['code'] == 100) {
                    $planIds = array();
                    if (isset($postData['plans']['data'])) {
                        foreach ($postData['plans']['data'] as $keyPlan => $valPlan) {
                            $whereForPlan = ['rawQuery' => "plans.for_usergroup_id = ? and plans.parent_plan_id = ?", 'bindParams' => [$ugid, $valPlan['parent_plan_id']]];
                            $planDetails = json_decode($objModelPlan->getPlanWhere1($whereForPlan), true);
//                        print_r($planDetails);
                            if ($planDetails['code'] == 200) {
                                array_push($planIds, $planDetails['data']['plan_id']);
                                $dataForUpdatePlan = ['charge_per_unit' => $valPlan['charge_per_unit']];
                                $objModelPlan->updatePlanWhere($dataForUpdatePlan, $whereForPlan);
                            }
                        }
                    }
                    if (isset($postData['plans']['newdata'])) {
                        foreach ($postData['plans']['newdata'] as $keyPlan => $valPlan) {
                            $whereForPlan = ['rawQuery' => "plans.for_usergroup_id = ? and plans.plan_id = ?", 'bindParams' => [0, $valPlan['parent_plan_id']]];
                            $planDetails = json_decode($objModelPlan->getPlanWhere1($whereForPlan), true);
                            $planDetails['data']['parent_plan_id'] = $planDetails['data']['plan_id'];
                            unset($planDetails['data']['plan_id']);
                            unset($planDetails['data']['actual_rate']);
                            $planDetails['data']['charge_per_unit'] = $valPlan['charge_per_unit'];
                            $planDetails['data']['for_usergroup_id'] = $ugid;
                            $planInsertedId = json_decode($objModelPlan->addPlan($planDetails['data']), true);
                            array_push($planIds, $planInsertedId['data']);
                        }
                    }
//                    die;
                    $planIds = implode(",", $planIds);
//                    dd($planIds);
                    $whereForDeletePlan = ['rawQuery' => "(plan_id NOT IN ($planIds) and for_usergroup_id = ?)", 'bindParams' => [$ugid]];//TODO MAKE it prepared delete
                    $objModelPlan->deletePlanWhere($whereForDeletePlan);
                    //TODO delete not in $planIds
                }
            }
        }
        $whereForPlan = array(
            'rawQuery' => 'plans.for_usergroup_id=?',
            'bindParams' => [$ugid]
        );
        $ugPlans = json_decode($objModelPlan->getAllPlansWhere1($whereForPlan), true);
//        dd($ugPlans);

        return view('Admin::usergroup/editusergroup', ['code' => '', 'ugDetails' => $usergroupDetails, 'allPlans' => $allPlans, 'ugPlans' => $ugPlans]);
    }

    public function addUsergroup(Request $request)
    {
        $objModelPan = Plan::getInstance();
        $whereForAllPlans = array(
            'rawQuery' => 'plans . for_usergroup_id = 0',
        );
        $allPlans = json_decode($objModelPan->getAllPlansWhere1($whereForAllPlans), true);
        if ($request->isMethod('post')) {
            $postData = $request->all();
            //TODO do validation here [group_name required, duplicate   |   foreach plan selected, plan_rate inputs required,number ]

//            $postData['group_name']
            //TODO insert usergroups table

            foreach ($postData['plans'] as $keyPlan => $valPlan) {
                $newChargePerUnit = $valPlan['charge_per_unit'];
                $parentPlanId = $keyPlan;
                //TODO use addPlan here
            }
        }
        return view('Admin::usergroup/editusergroup', ['code' => '', 'allPlans' => $allPlans]);

    }

    public function tempAddUserGroup(Request $request)
    {
        $objUserGroup = Usergroup::getInstance();
        $where = array(
            'rawQuery' => 'usergroup_status=1'
        );
        $ugDetails = $objUserGroup->getAllUsergroupsWhereTemp($where);
//        dd($ugDetails);

        if ($request->isMethod('post')) {
            $groupName = $request->input('groupName');
            $this->validate($request, [
                'groupName' => 'required'
            ], [
                'groupName.required' => 'Please enter name of the group'
            ]);
            $objModelUserGroup = Usergroup::getInstance();
            $dataForAdd = array('usergroup_name' => $groupName, 'usergroup_status' => '1');
            $added = $objModelUserGroup->addNewUserGroup($dataForAdd);
            if ($added) {
                return Redirect::back()->with(['status' => 'Success', 'message' => 'usergroup added.']);
            } else {
                return Redirect::back()->with(['status' => 'Error', 'message' => 'Errrororrrr..']);
            }

        }

        return view('Admin::usergroup.tempAddUsergroup', ['ugDetails' => $ugDetails]);
    }

   /* public function addUserGroupAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $groupName = $request->input('groupName');
            $objModelUserGroup = Usergroup::getInstance();
            $dataForAdd = array('usergroup_name' => $groupName, 'usergroup_status' => '1');
            $added = $objModelUserGroup->addNewUserGroup($dataForAdd);
            if ($added) {
                echo json_encode(array('status' => '200', 'message' => 'usergroup added'));
            } else {
                echo json_encode(array('status' => '400', 'message' => 'not added'));
            }

        }

    }*/


}