<?php namespace InstagramAutobot\Http\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    public static $_instance = null;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'transaction_id', 'by_user_id', 'plan_id', 'for_user_id', 'ins_url',
        'quantity_total', 'quantity_done','start_time','end_time','time_interval','status','parent_order_id',
           'added_time','updated_time'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = ['password', 'remember_token'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Order();
        return self::$_instance;
    }

    /**
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function addNewOrder()
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
     * Get all order details
     * @param $where
     * @param array $selectedColumns Column names to be fetched
     * @return mixed
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAllOrdersWhere($where, $selectedColumns = ['*'])
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

    /**
     * @param $where
     * @param array $selectedColumns
     * @return mixed
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getOrderWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }

    /**
     * Update order details
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function updateOrderWhere()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            $where = func_get_arg(1);
            try {
                $result = DB::table($this->table)
                    ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                    ->update($data);
                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * Delete order details
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function deleteOrderWhere()
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

    public function getId()
    {
        return $this->id;
    }

    public function getAvaiableUserDetailsFromOrders($selectedColumns)
    {
        try {
            $result = Order::join('users', function ($join) {
                $join->on('orders.by_user_id', '=', 'users.id');
            })
                ->join('plans','orders.plan_id', '=', 'plans.plan_id')
//                ->leftJoin('location', function ($join) {
//                    $join->on('location.location_id', '=', 'usersmeta.city');
//                })
//                ->join('location', function ($join) {
//                    $join->on('location.location_id', '=', 'usersmeta.state');
//                })
//                ->join('location', function ($join) {
//                    $join->on('location.location_id', '=', 'usersmeta.country');
//                })
                ->select($selectedColumns)
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }
    public function getUsersInfoFromOrdersByUserId($where,$selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('users', function ($join) {
                    $join->on('orders.by_user_id', '=', 'users.id');
                })
                ->join('plans','orders.plan_id', '=', 'plans.plan_id')
                ->select($selectedColumns)
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }

}
