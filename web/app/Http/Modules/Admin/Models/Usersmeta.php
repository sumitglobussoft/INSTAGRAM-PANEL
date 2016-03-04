<?php namespace InstagramAutobot\Http\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Usersmeta extends Model
{
    protected $table = 'usersmeta';
    protected $fillable = ['user_id', 'addressline1', 'addressline2', 'city', 'state', 'country', 'zipcode', 'phone', 'account_bal'];
    private static $_instance = null;

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Usersmeta();
        return self::$_instance;
    }
//    public function getAvaiableUserMetaDetails($where){
//
//        try{
//            $result = Usersmeta::whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
////                ->whereRaw($status['rawQuery'], isset($status['bindParams']) ? $status['bindParams'] : array())
//                ->select()
//                ->get();
//
//            return $result;
//
//        }catch (QueryException $e){
//            echo $e;
//        }
//
//    }
    public function getAvaiableUserMetaDetails()
    {
        try {
            $result = Usersmeta::join('users', function ($join) {
                $join->on('usersmeta.user_id', '=', 'users.id');
            })
//                ->leftJoin('location', function ($join) {
//                    $join->on('location.location_id', '=', 'usersmeta.city');
//                })
//                ->join('location', function ($join) {
//                    $join->on('location.location_id', '=', 'usersmeta.state');
//                })
                ->join('currencies', function ($join) {
                    $join->on('currencies.currency_id', '=', 'usersmeta.currency_id');
                })
                ->select()
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }

    public function getUserMetaInfoByUserId($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('users', function ($join) {
                    $join->on('usersmeta.user_id', '=', 'users.id');
                })
                ->join('currencies', 'currencies.currency_id', '=', 'usersmeta.currency_id')
                ->select($selectedColumns)
                ->get();
            if ($result)
                return $result;
            else{
                $result = DB::table('users')
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                    ->select($selectedColumns)
                    ->first();
                $result=array($result);
                return $result;
            }
        } catch (QueryException $e) {
            echo $e;
        }
    }

    public function getUserMetaWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }

    public function addNewUserMeta()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data);
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function updateUserMetaWhere()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }
}