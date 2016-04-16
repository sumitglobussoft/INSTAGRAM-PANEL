<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    public function getCommentList($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table('comments')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->first();
            return ($result) ? $result : 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();
        }

    }

    public function insertComments($data)
    {
        try {
            $id = DB::table('comments')->insertGetId($data);
            return ($id) ? $id : 0;
        } catch (QueryException $exc) {
            return $exc->getMessage();
        }
    }

}//END OF CLASS ORDER
