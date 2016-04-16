<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Comments_Group extends Model
{
    public function getCommentGroupList()
    {
        try {
            $result = DB::table('comments_groups')
                ->select()
                ->get();

            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();
        }
    }

    public function getCommentGroupListAddedBy($where)
    {
        try {
            $result = DB::table('comments_groups')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select()
                ->get();

            if ($result)
                return $result;
            else
                return 0;
        } catch (QueryException $exc) {
            return 2;
        }
    }

}//END OF CLASS ORDER
