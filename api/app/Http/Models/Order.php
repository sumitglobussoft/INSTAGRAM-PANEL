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

            $result = DB::table('orders')->insert($data);
            if ($result)
                return 1;
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

            if ($result)
                return 1;
            else
                return 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();

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
}//END OF CLASS ORDER
