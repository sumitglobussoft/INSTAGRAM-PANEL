<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public function insertOrder()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $id = DB::table('orders')->insertGetId($data);
            if ($id)
                return $id;
            else
                return 0;
        }
    }

    public function deleteOrder($where)
    {
        try {
            $result = DB::table('orders')
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

    public function updateOrder()
    {
        try {
            $where = func_get_arg(0);
            $data = func_get_arg(1);
            $result = DB::table('orders')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->update($data);

            if ($result == 0) {
                return 2;
            } else if ($result) {
                return 1;
            } else {
                return 0;
            }
        } catch (QueryException $exc) {
            return 0;
//            return $exc->getMessage();

        }
    }

    public function getOrderHistory($where, $selectedColumns = ['*'])
    {
        if (!isset($selectedColumns)) {
            $selectedColumns = ['orders.*', 'plans.plan_name', 'plans.plan_name_code', 'plans.supplier_server_id'];
        }

        try {
            $result = DB::table('orders')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('plans', 'plans.plan_id', '=', 'orders.plan_id')
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

    public function getAllOrders($where, $sortingOrder, $iDisplayStart, $iDisplayLength)
    {
        $selectedColumns = [
            'orders.order_id', 'orders.server_order_id', 'orders.ins_url', 'orders.quantity_total', 'orders.price',
            'orders.quantity_done', 'orders.status', 'orders.added_time', 'orders.updated_time',
            'orders.initial_likes_count', 'orders.initial_followers_count', 'orders.initial_comments_count',
            'plans.plan_name', 'plans.supplier_server_id','plans.plan_type'
        ];

        try {
            $result = DB::table('orders')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('plans', 'plans.plan_id', '=', 'orders.plan_id')
                ->orderBy($sortingOrder[0], $sortingOrder[1])
                ->skip($iDisplayStart)->take($iDisplayLength)
                ->select($selectedColumns)
                ->get();
            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
            return 2;//$exc->getMessage();
        }
    }


    public function getOrderStatus($where, $selectedColumns = ['*'])
    {
        if (!isset($selectedColumns)) {
            $selectedColumns = ['orders.*', 'plans.plan_name', 'plans.supplier_server_id', 'supplier_servers.*'];
        }
        try {
            $result = DB::table('orders')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('plans', 'plans.plan_id', '=', 'orders.plan_id')
                ->join('supplier_servers', 'supplier_servers.supplier_server_id', '=', 'plans.supplier_server_id')
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

    public function getAutolikesOrderStatus($where, $selectedColumns = ['*'])
    {

        try {
            $result = DB::table('orders')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('instagram_users', 'instagram_users.ins_user_id', '=', 'orders.for_user_id')
                ->join('plans', 'plans.plan_id', '=','orders.plan_id')
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

    public function getOrderDetails($where, $selectedColumns = [])
    {
        if (empty($selectedColumns)) {
            $selectedColumns = ['*'];
        }
        try {
            $result = DB::table('orders')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
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

}//END OF CLASS ORDER
