<?php

namespace InstagramAutobot\Http\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

//use Illuminate\Auth\Authenticatable;
//use Illuminate\Auth\Passwords\CanResetPassword;
//use Illuminate\Foundation\Auth\Access\Authorizable;
//use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model //implements AuthorizableContract

{
    public static $_instance = null;
    protected $table = 'users';
    protected $fillable = ['firstname', 'lastname', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))
            self::$_instance = new User();

        return self::$_instance;
    }


    public function getUserById($userId)
    {
        $result = User::whereId($userId)->first();
        return $result;
    }

    public function getUserWhere($where, $selectedCols = ['*'])
    {
        $res = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedCols)
            ->first();
        return $res;
    }

}
