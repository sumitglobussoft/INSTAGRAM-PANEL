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

use DB;
use Exception;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    protected $fillable = ['name', 'lastname', 'email', 'password', 'username', 'role', 'status'];
    protected $hidden = ['password', 'remember_token'];

    /**
     * @author Chandrakar Ramkishan
     * @uses Authentication::login[1]//Used in each service for getting user login token details
     */
    function getUsercredsWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table('users')
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        if ($result) {
            return $result;
        } else {
            return 0;
        }
    }


    /**
     * @param array : $where
     * @return array
     * @throws "Argument Not Passed"
     * @author Chandrakar Ramkishan
     * @uses profile::showProfileDetails
     */
    function getUserDetails()
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table('users')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('usersmeta', 'users.id', '=', 'usersmeta.user_id')
                ->select('users.id', 'users.name', 'users.lastname', 'users.username', 'users.email', 'users.profile_pic', 'usersmeta.addressline1', 'usersmeta.addressline2', 'usersmeta.city', 'usersmeta.state', 'usersmeta.country_id', 'usersmeta.contact_no')
                ->first();
            if ($result)
                return $result;
            else
                return 0;
        }
    }

    function deleteUserDetails($where)
    {
        $result = DB::table('users')
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->delete();

        if ($result)
            return $result;
        else
            return 0;
    }


    function isMailExist()
    {
        if (func_num_args() > 0) {
            $fpdemail = func_get_arg(0);
            $resetcode = func_get_arg(1);
            $data = array(
                'pd_reset_token' => $resetcode
            );

            $row = DB::table('users')
                ->where('email', $fpdemail)
                ->first();
            if ($row) {
                try {
                    $updated = DB::table('users')
                        ->where('email', $fpdemail)
                        ->update($data);
                } catch (Exception $e) {
                    throw new Exception('Unable to update, exception occured' . $e);
                }
                if ($updated) {
                    return $updated;
                } else {
                    return false;
                }

            }
        } else {
            throw new Exception('Argument not passed');
        }
    }

    function verifyResetCode($where)
    {
        $row = DB::table('users')
            ->select()
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->first();
        if ($row) {
            return 1;
        } else {
            return 0;
        }
    }

    public function resetPassword()
    {
        if (func_num_args() > 0) {
            $fpwemail = func_get_arg(0);
            $password = func_get_arg(1);
            $row = DB::table("users")
                ->select()
                ->where('email', $fpwemail)
                ->first();
            if ($row) {
                try {
                    $data = array('password' => $password, 'pd_reset_token' => '');
                    $updated = DB::table('users')
                        ->where('email', $fpwemail)
                        ->update($data);
                    if ($updated)
                        return $updated;
                    else
                        return false;
                } catch (Exception $e) {
                    throw new Exception('Unable to update, exception occured' . $e);
                }
            } else
                return false;
        } else
            throw new Exception('Argument not passed');
    }

    public function UpdateUserDetailsbyId()
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $data = func_get_arg(1);
            $result = DB::table('users')
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->update($data);
            if ($result)
                return 1;
            else
                return 0;
        }
    }

}//End of Class User
