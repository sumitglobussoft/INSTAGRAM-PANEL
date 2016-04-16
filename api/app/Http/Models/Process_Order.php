<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Process_Order extends Model
{
    public function insertProcessOrder()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table('process_orders')->insert($data);
                if ($result)
                    return 1;
                else
                    return 0;
            } catch (QueryException $exc) {
                return 0;
            }
        }
    }

    public function deleteProcessOrder($where)
    {
        try {
            $result = DB::table('process_orders')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->delete();
            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();
        }
    }

    public function updateProcessOrder()
    {
        try {
            $where = func_get_arg(0);
            $data = func_get_arg(1);
            $result = DB::table('process_orders')
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

    public function getProcessOrders($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table('process_orders')
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

    public function getProcessOrderStatus($where, $selectedColumns = [])
    {
        if (empty($selectedColumns)) {
            $selectedColumns = ['*'];
        }
        try {
            $result = DB::table('process_orders')
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
