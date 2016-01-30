<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    public function getPlansDetails($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table('plans')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('plangroups','plans.plangroup_id','=','plangroups.plangroup_id')
                ->select('plans.*','plangroups.plangroup_name', 'plangroups.status')
                ->get();

            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();
        }
    }

}
