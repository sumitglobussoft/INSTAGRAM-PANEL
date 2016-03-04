<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Plan;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;

//use Hash;
//use Input;
use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PlansController extends Controller
{
    public function availablePlans()
    {

        $objModelPlan = Plan::getInstance();
        $whereForPlans = array(
            'rawQuery' => 'status = 1 or status = 0'
        );
        $allAvailablePlans = $objModelPlan->getAllPlansWhere($whereForPlans);

        return view('Admin::plans.availableplans', ['plans' => $allAvailablePlans]);
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
                        $message = '';
                        if ($status == 1) {
                            $message = "Plan type Inactivated.";
                        } else {
                            $message = "Plan is working.";
                        }

                        echo json_encode(array('status' => '200', 'message' => 'reached till here'));

                    } else {
                        echo json_encode(array('status' => '400', 'message' => 'Failed. Plesae try again.'));

                    }
                    break;

                default:

                    break;
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
            $min_quantity = $request->input('min_quantity');
            $max_quantity = $request->input('max_quantity');
            $charge_per_unit = $request->input('charge_per_unit');
            $status = $request->input('status');

            $this->validate($request, [
                'plan_name' => 'required|regex:/^[A-Za-z0-9 .-\[\]]+$/',
                'min_quantity' => 'required|integer|min:1',
                'max_quantity' => 'required|integer|min:1',
                'charge_per_unit' => 'required|regex:/^[0-9]+([.][0-9]+)?$/',
            ], ['plan_name.required' => 'Please enter a Plan Type',
                    'min_quantity.required' => 'Please enter a Minimum Quantity',
                    'max_quantity.required' => 'Please enter a Maximum Quantity',
                    'charge_per_unit.required' => 'Please Specify Charge Per Unit',
                    'charge_per_unit.regex' => 'please enter a number or decimal value',
                ]
            );
            $whereForUpdatePlan = array(
                'rawQuery' => 'plan_id = ?',
                'bindParams' => [$id]
            );
            $dataForUpdatePlan = array('plan_name' => $plan_name, 'min_quantity' => $min_quantity, 'max_quantity' => $max_quantity, 'charge_per_unit' => $charge_per_unit);
            $updated = $objModelPlan->updatePlanWhere($dataForUpdatePlan, $whereForUpdatePlan);
            if ($updated) {
//                return redirect('/admin/plans-list')->with('message', 'Updated!');
                return Redirect::back()->with(['status' => 'success', 'message' => 'Saved!!!.']);

            } else {
                return Redirect::back()->with(['status' => 'error', 'message' => 'Something went wrong, please reload the page and try again.']);
            }

        }
        return view('Admin::plans.editplans', ['planDetails' => $planDetails]);

    }

    public function adminsample()
    {
        return view('Admin::admin.adminsample');
    }
}