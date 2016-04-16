<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Instagram_User extends Model
{
    public function insertInsUserAutolikesOrder()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $id = DB::table('instagram_users')->insertGetId($data);
                if ($id)
                    return $id;
                else
                    return 0;
            } catch (QueryException $e) {
//                return 0;
                return $e->getMessage();
            }
        }
    }


    public function updateUserDetails()
    {
        try {
            $where = func_get_arg(0);
            $data = func_get_arg(1);
            $result = DB::table('instagram_users')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->update($data);
            if ($result==0)
                return 1;
            else
                return $result;
        } catch (QueryException $exc) {
            return 0;
//            return $exc->getMessage();

        }
    }

    public function getInsUserAutolikesOrderHistory($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table('instagram_users')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('plans', 'plans.plan_id', '=', 'instagram_users.plan_id')
                ->select($selectedColumns)
                ->get();
            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
//            return 0;
            return $exc->getMessage();
        }
    }

    public function getUserDetails($where, $selectedColumns = [])
    {
        if(empty($selectedColumns)){
            $selectedColumns=['instagram_users.*', 'plans.plan_name_code', 'plans.plan_name','plans.charge_per_unit', 'plans.status as plan_status'];
        }

        try {
            $result = DB::table('instagram_users')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('plans', 'plans.plan_id', '=', 'instagram_users.plan_id')
                ->select($selectedColumns)
                ->get();
            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
//            return 0;
            return $exc->getMessage();
        }
    }

    public function getAllFilterUsers($where, $sortingOrder, $iDisplayStart, $iDisplayLength)
    {
        try {
            $result = DB::table('instagram_users')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('plans', 'plans.plan_id', '=', 'instagram_users.plan_id')
                ->orderBy($sortingOrder[0], $sortingOrder[1])
                ->skip($iDisplayStart)->take($iDisplayLength)
                ->select('instagram_users.*', 'plans.plan_name_code', 'plans.plan_name', 'plans.status as plan_status')
                ->get();
            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();
        }
    }

    public function deleteInsUser($where)
    {
        try {
            $result = DB::table('instagram_users')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->delete();
            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $e) {
            return 0;
        }
    }



}//END OF CLASS ORDER
