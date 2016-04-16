<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    public function getPlansDetails($where, $selectedColumns = ['*'])
    {

        if ($selectedColumns[0] == '*') {
            $selectedColumns = ['plans.*', 'plangroups.plangroup_name', 'plangroups.status'];
        }
        try {
            $result = DB::table('plans')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('plangroups', 'plans.plangroup_id', '=', 'plangroups.plangroup_id')
                ->select($selectedColumns)
                ->get();
            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();
        }
    }

    public function getPlanPricingInfo($selectedColumns = [])
    {
        if (empty($selectedColumns)) {
            $selectedColumns = ['*'];
        }
        try {
            $result = DB::table('plans')
                ->select($selectedColumns)
                ->get();

            return $result;

        } catch (QueryException $exc) {
            return "error";
//            return $exc->getMessage();
        }
    }

    public function getFilterPlansDetails($where, $selectedColumns = ['*'])
    {

        if ($selectedColumns[0] == '*') {
            $selectedColumns = ['plans.*', 'plangroups.plangroup_name', 'plangroups.status'];
        }
        try {
            $result = DB::table('plans')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('plangroups', 'plans.plangroup_id', '=', 'plangroups.plangroup_id')
                ->select($selectedColumns)
                ->get();

            return ($result) ? $result : 0;

        } catch (QueryException $exc) {
            return 2;
        }
    }


}
