<?php

namespace App\Http\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;


class Usersmeta extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $fillable = ['user_id', 'addressline1', 'addressline2', 'city', 'state', 'country_id', 'contact_no'];

    public function getUsermetaWhere($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table("usersmeta")
                ->select($selectedColumns)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->first();
            return ($result) ? $result : 0;
        } catch (QueryException $e) {
            return 0;
        }
    }

    public function getUsermetaDetails($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table("usersmeta")
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('users', 'usersmeta.user_id', '=', 'users.id')
                ->select($selectedColumns)
                ->get();
            return ($result) ? $result : 0;
        } catch (QueryException $e) {
            return 0;
        }
    }

    public function updateUsermetaWhere()
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $data = func_get_arg(1);
            try {
                $result = DB::table('usersmeta')
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                if ($result) {
                    return $result;
                } else {
                    return 0;
                }
            } catch (\Exception $e) {
                return 2;
//                return $e->getMessage();
            }
        } else {
            throw new \Exception('Argument Not Passed');
        }
    }

    public function addUsermeta()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $result = DB::table('usersmeta')->insert($data);
            if ($result) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function insertUsermeta()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $id = DB::table('usersmeta')
                    ->insertGetId($data);

                if ($id)
                    return $id;
                else
                    return 0;
            } catch (QueryException $exc) {
                return 0;
            }
        }
    }
}
