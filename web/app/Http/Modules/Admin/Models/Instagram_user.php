<?php namespace InstagramAutobot\Http\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Instagram_user extends Model
{

    public static $_instance = null;
    protected $table = 'instagram_users';
    protected $fillable = ['ins_user_id', 'by_user_id', 'ins_username', 'plan_id', 'pics_done', 'daily_post_limit', 'pics_limit',
        'likes_per_pic', 'plan_id_for_auto_comment', 'custom_comment_group_id', 'comments_amount', 'last_check', 'last_delivery', 'status',
        'message', 'last_delivery_link', 'cronjob_status'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Instagram_user();
        return self::$_instance;
    }

    public function getUserById($userId)
    {
        $result = Instagram_user::whereId($userId)->first();
        //first() function in sql returns the first value of the selected column
        return $result;
    }

    /**
     * @return string
     * @throws Exception
     * @since 03-02-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function addNewInstagramUser()
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

    /**
     * Get all Instagram_user details
     * @param $where
     * @param array $selectedColumns Column names to be fetched
     * @return mixed
     * @throws Exception
     * @since 03-02-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAllInstagramUsersWhere($where, $selectedColumns = ['*'])
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->get();
            return $result;
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    public function getAllInstagramUsers()
    {
        $result = DB::table($this->table)
//                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select()
            ->get();
        return $result;

    }

    /**
     * @param $where
     * @param array $selectedColumns
     * @return mixed
     * @since 03-02-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getInstagramUserWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }

    /**
     * Update ig_users details
     * @return string
     * @throws Exception
     * @since 03-02-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function updateInstagramUserWhere()
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

    /**
     * Delete IgUsers details
     * @return string
     * @throws Exception
     * @since 03-02-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function deleteInstagramUserWhere()
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->delete();
                return $result;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * Joining instagram_users , users, and Plans tables
     * @return string
     * @throws Exception
     * @since 13-05-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getInstagramUsersDetailsWithUsersInfo()
    {
        $selectedCols = ['instagram_users.*', 'users.username', 'plans.plan_name'];
        $result = DB::table($this->table)
            ->join('users', 'users.id', '=', 'instagram_users.by_user_id')
            ->join('plans', 'plans.plan_id', '=', 'instagram_users.plan_id')
            ->select($selectedCols)
            ->get();
        return $result;
    }

    /**
     * Joining instagram_users , users, and Orders tables
     * @return string
     * @throws Exception
     * @since 14-05-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getInstagramUsersDetailsWithOrdersAndUsersInfo($where)
    {
        $selCols = ['instagram_users.*', 'users.username', 'users.email', 'users.profile_pic'];
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->join('users', 'users.id', '=', 'instagram_users.by_user_id')
//            ->join('orders', 'orders.for_user_id', '=', 'instagram_users.ins_user_id')
            ->select($selCols)
            ->get();
        return $result;

    }

    /**
     * Joining instagram_users , and plans table joining
     * @return string
     * @throws Exception
     * @since 20-05-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getInstagramUsersDetailsWithPlans($where)
    {
        $selCols = ['instagram_users.*', 'plans.plan_name', 'users.username'];
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->join('users', 'users.id', '=', 'instagram_users.by_user_id')
            ->join('plans', 'plans.plan_id', '=', 'instagram_users.plan_id')
//            ->join('orders', 'orders.for_user_id', '=', 'instagram_users.ins_user_id')
            ->select($selCols)
            ->get();
        return $result;

    }

    public function getAllOrdersDetails($where)
    {
        $select = ['orders.ins_url', 'orders.status'];
        $res = DB::table('orders')
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($select)
            ->get();
        return $res;
    }

}
