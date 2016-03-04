<?php
namespace InstagramAutobot\Http\Modules\User\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Usersmeta extends Model
{
    protected $table = 'usersmeta';
    protected $fillable = ['id', 'user_id', 'invite_id', 'invitedby_id', 'addressline1', 'addressline2', 'city', 'state', 'country_id', 'contact_no', 'currency_id', 'account_bal', 'api_token'];
    private static $_instance = null;

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Usersmeta();
        return self::$_instance;
    }


    public function updateUsersmetaWhere()
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