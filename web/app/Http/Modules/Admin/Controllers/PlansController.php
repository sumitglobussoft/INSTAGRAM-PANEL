<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Plan;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

//use Hash;
//use Input;
use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Modules\Admin\Models\Usergroup;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;
use stdClass;
use Illuminate\Support\Str;

class PlansController extends Controller
{
    public function availablePlans()
    {
//        $objModelPlan = Plan::getInstance();
//        $whereForPlans = array(
//            'rawQuery' => 'status = 1 or status = 0'
//        );
//        $allAvailablePlans = $objModelPlan->getAllPlansWhere($whereForPlans);
//        dd($allAvailablePlans);
        return view('Admin::plans.availableplans');//, ['plans' => $allAvailablePlans]
    }

    public function plansDatatablesAjaxHandler(Request $request)
    {
        if ($request->input('method') == "first") {
            $objModelPlan = Plan::getInstance();
            $whereForPlans = array(
                'rawQuery' => 'status = 1 or status = 0'
            );
            $planLists = $objModelPlan->getAllPlansWhere($whereForPlans);
            $planLists = json_decode(json_encode($planLists), true);
            $plans = new collection;
            foreach ($planLists as $aap) {
                $id = $aap['plan_id'];
                $statusClass = ($aap['status'] == 1) ? 'fa fa-check-circle' : 'fa fa-times-circle';
                $color = ($aap['status'] == 1) ? 'green' : 'red';
                $text = ($aap['status'] == 1) ? 'Active' : 'Inactive';
                $bgcolor = ($aap['status'] == 1) ? 'lightgreen' : 'lightpink';
                $plans->push([
                    'service' => $aap['plan_name'],
                    'min' => $aap['min_quantity'],
                    'max' => $aap['max_quantity'],
                    'ratepk' => $aap['charge_per_unit'],
//                    'status' => '<a href="javascript:;" id="status" class="btn btn-sm btn-raised ' . $statusClass . '" style="color:' . $color . '; background-color:' . $bgcolor . '" data-id=' . $id . ' ></a>',
                    'status' => '<div class="switch" id="status" data-id="' . $id . '" style="background-color:' . $color . '" >
                <input id=' . $id . ' class="cmn-toggle cmn-toggle-yes-no" type="checkbox" style="background-color:' . $color . '">
                <label for=' . $id . ' data-text="'.$text.'"></label>
            </div>',
//                    'status' => '<div class="onoffswitch" id="status" data-id="' . $id . '">
//                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id=' . $id . ' checked>
//                <label class="onoffswitch-label" for=' . $id . '>
//                    <span class="onoffswitch-inner"></span>
//                    <span class="onoffswitch-switch"></span>
//                </label>
//            </div>',
                    'edit' => '<a href="/admin/plans-list-edit/' . $aap['plan_id'] . '" class="btn btn-sm btn-warning">Edit</a>'

                ]);
            }
            return Datatables::of($plans)->make(true);
        } else if ($request->input('method') == "second") {
            $planType = $request->input('planType');
            $serviceType = $request->input('serviceType');
            $objModelPlans = Plan::getInstance();
            if ($planType == 5 && $serviceType == 5) {
                $where = array(
                    'rawQuery' => 'status = 1 or status = 0'
                );
            } elseif ($planType == 5 && $serviceType != 5) {
                $where = array(
                    'rawQuery' => 'plan_type IN (0,1,3,4)  and service_type=?',
                    'bindParams' => [$serviceType]
                );
            } elseif ($planType != 5 && $serviceType == 5) {
                $where = array(
                    'rawQuery' => 'service_type IN ("R","F","T") and plan_type =?',
                    'bindParams' => [$planType]
                );
            } else {
                $where = array(
                    'rawQuery' => 'plan_type=? and service_type=?',
                    'bindParams' => [$planType, $serviceType]
                );
            }
            $planLists = $objModelPlans->getAllPlansWhere($where);
            $planLists = json_decode(json_encode($planLists), true);
            $plans = new collection;
            foreach ($planLists as $aap) {
                $id = $aap['plan_id'];
                $statusClass = ($aap['status'] == 1) ? 'fa fa-check-circle' : 'fa fa-times-circle';
                $color = ($aap['status'] == 1) ? 'green' : 'red';
                $bgcolor = ($aap['status'] == 1) ? 'lightgreen' : 'lightpink';
                $plans->push([
                    'service' => $aap['plan_name'],
                    'min' => $aap['min_quantity'],
                    'max' => $aap['max_quantity'],
                    'ratepk' => $aap['charge_per_unit'],
                    'status' => '<a href="javascript:;" id="status" class="btn btn-sm btn-raised ' . $statusClass . '" style="color:' . $color . '; background-color:' . $bgcolor . '" data-id=' . $id . ' ></a>',
                    'edit' => '<a href="/admin/plans-list-edit/' . $aap['plan_id'] . '" class="btn btn-sm btn-warning">Edit</a>'

                ]);
            }
            return Datatables::of($plans)->make(true);
        }
    }


    public function availablePlansAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $method = $request->input('method');
            switch ($method) {
                case "changeStatus":
                    $planId = $request->input('id');
                    $status = $request->input('status');
                    $objModelPlan = Plan::getInstance();
                    $whereForUpdatePlan = array(
                        'rawQuery' => 'plan_id = ?',
                        'bindParams' => [$planId]
                    );
                    $dataForUpdatePlan = array('status' => $status);
                    $updated = $objModelPlan->updatePlanWhere($dataForUpdatePlan, $whereForUpdatePlan);
                    if ($updated) {
                        echo json_encode(array('status' => '200', 'message' => 'Status has been changed.'));
                    } else {
                        echo json_encode(array('status' => '400', 'message' => 'Some error occurred.Please reload the page and try again.'));
                    }
                    break;

                default:

                    break;
            }
        }
    }

    public function addPlans(Request $request)
    {
        if ($request->isMethod('post')) {

            $supplier_server_id = $request->input('supplier_server_id');
            $plan_name_code = $request->input('plan_name_code');
            $plan_name = $request->input('plan_name');
            $plan_type = $request->input('plan_type');
            $service_type = $request->input('service_type');
            $min_quantity = $request->input('min_quantity');
            $max_quantity = $request->input('max_quantity');
            $buying_price_per_k = $request->input('buying_price_per_k');
            $charge_per_unit = $request->input('charge_per_unit');
            $this->validate($request, [
                'plan_name' => 'required|regex:/^[A-Za-z0-9 \-.-\[\]]+$/',
                'min_quantity' => 'required|integer|min:1',
                'max_quantity' => 'required|integer|min:1',
                'buying_price_per_k' => 'required|regex:/^[0-9]+([.][0-9]+)?$/',
                'charge_per_unit' => 'required|regex:/^[0-9]+([.][0-9]+)?$/',
            ], ['plan_name.required' => 'Please enter a Plan Type',
                    'min_quantity.required' => 'Please enter a Minimum Quantity',
                    'max_quantity.required' => 'Please enter a Maximum Quantity',
                    'buying_price_per_k.required' => 'Please Enter Buying Price Per K',
                    'buying_price_per_k.regex' => 'This is not a valid price(please enter a number or decimal value',
                    'charge_per_unit.required' => 'Please Specify Charge Per Unit',
                    'charge_per_unit.regex' => 'please enter a number or decimal value',
                ]
            );


            $objModelPlan = Plan::getInstance();
            $dataForAdd = array('plangroup_id' => '1', 'supplier_server_id' => $supplier_server_id, 'plan_name_code' => $plan_name_code
            , 'plan_name' => $plan_name, 'plan_type' => $plan_type, 'service_type' => $service_type, 'min_quantity' => $min_quantity,
                'max_quantity' => $max_quantity, 'buying_price_per_k' => $buying_price_per_k, 'charge_per_unit' => $charge_per_unit,
                'status' => '1');
            $added = $objModelPlan->addNewPlan($dataForAdd);
            if ($added) {
                return Redirect::back()->with(['status' => 'success', 'message' => 'Plan has been Added Successfully.']);

            } else {
                return Redirect::back()->with(['status' => 'error', 'message' => 'Something went wrong, please reload the page and try again.']);
            }


        }
        return view('Admin::plans.addplans');
    }

    public function plansFilter(Request $request)
    {
        if ($request->isMethod('post')) {
            $planType = $request->input('pt');
            $serviceType = $request->input('st');

            $objModelPlans = Plan::getInstance();
            $where = array(
                'rawQuery' => 'plan_type=? and service_type=?',
                'bindParams' => [$planType, $serviceType]
            );
            $planLists = $objModelPlans->getAllPlansWhere($where);
            if ($planLists) {
                echo json_encode(array('status' => '200', 'message' => 'success', 'data' => $planLists));
            } else {
                echo json_encode(array('status' => '400', 'message' => 'No Plans are available in this category'));
            }
        }
    }


    public function editPlan($id, Request $request)
    {
        $objModelPlan = Plan::getInstance();
        $whereForPlan = array(
            'rawQuery' => 'plan_id = ?',
            'bindParams' => [$id]
        );
        $planDetails = $objModelPlan->getPlanWhere($whereForPlan);

        $objModelPlan = Plan::getInstance();
        if ($request->isMethod('post')) {

            $plan_name = $request->input('plan_name');
            $serviceType = $request->input('service_type');
            $planNameCode = $request->input('plan_name_code');
            $min_quantity = $request->input('min_quantity');
            $max_quantity = $request->input('max_quantity');
            $buyingPricePerK = $request->input('buying_price_per_k');
            $charge_per_unit = $request->input('charge_per_unit');
            $status = $request->input('status');

            $this->validate($request, [
                'plan_name' => 'required|regex:/^[A-Za-z0-9 \-.-\[\]]+$/',
                'min_quantity' => 'required|integer|min:1',
                'max_quantity' => 'required|integer|min:1',
                'buying_price_per_k' => 'required|regex:/^[0-9]+([.][0-9]+)?$/',
                'charge_per_unit' => 'required|regex:/^[0-9]+([.][0-9]+)?$/',
            ], ['plan_name.required' => 'Please enter a Plan Type',
                    'min_quantity.required' => 'Please enter a Minimum Quantity',
                    'max_quantity.required' => 'Please enter a Maximum Quantity',
                    'buying_price_per_k.required' => 'Please Enter Buying Price',
                    'buying_price_per_k.regex' => 'please enter a number or decimal value',
                    'charge_per_unit.required' => 'Please Specify Charge Per Unit',
                    'charge_per_unit.regex' => 'please enter a number or decimal value',
                ]
            );
            $whereForUpdatePlan = array(
                'rawQuery' => 'plan_id = ?',
                'bindParams' => [$id]
            );
            $dataForUpdatePlan = array('plan_name' => $plan_name, 'service_type' => $serviceType, 'plan_name_code' => $planNameCode, 'min_quantity' => $min_quantity,
                'max_quantity' => $max_quantity, 'buying_price_per_k' => $buyingPricePerK, 'charge_per_unit' => $charge_per_unit);
            $updated = $objModelPlan->updatePlanWhere($dataForUpdatePlan, $whereForUpdatePlan);
            if ($updated) {
//                return redirect('/admin/plans-list')->with('message', 'Updated!');
                return Redirect::back()->with(['status' => 'success', 'message' => 'Plans has been updated.']);

            } else {
                return Redirect::back()->with(['status' => 'error', 'message' => 'Something went wrong, may be due to same contents. Please reload the page and try again.']);
            }
        }
        return view('Admin::plans.editplans', ['planDetails' => $planDetails]);

    }

    public function adminsample()
    {
        return view('Admin::admin.adminsample');
    }
}