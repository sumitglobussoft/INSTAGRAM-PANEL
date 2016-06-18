<?php namespace InstagramAutobot\Http\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
//use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Auth\Passwords\CanResetPassword;
//use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Plan extends Model
{

    public static $_instance = null;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['plan_id', 'plangroup_id', 'for_usergroup_id', 'parent_plan_id', 'supplier_server_id', 'plan_name_code', 'plan_name', 'plan_type', 'service_type', 'min_quantity', 'max_quantity', 'buying_price_per_k', 'charge_per_unit', 'auto_flag', 'status'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = ['password', 'remember_token'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Plan();
        return self::$_instance;
    }

    /**
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function addNewPlan()
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
     * Get all plan details
     * @param $where
     * @param array $selectedColumns Column names to be fetched
     * @return mixed
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAllPlansWhere($where, $selectedColumns = ['*'])
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->select($selectedColumns)
                ->get();
//                ->paginate(5);

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
    public function getPlanWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }

    /**
     * Update plan details
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function updatePlanWhere()
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
     * Delete plan details
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function deletePlanWhere()
    {
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
//            dd(DB::table($this->table)
//                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->toSql());
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

    public function getAllPlansWhere1($where, $selectedColumns = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('plans as p', function ($join) {
                    $join->on('plans.parent_plan_id', '=', 'p.plan_id');
                })
                ->select(DB::raw('plans.*, p.charge_per_unit AS actual_rate'))
                ->get();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'All plans.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }

    public function addOrUpdatePlanWhere()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = $this->firstOrCreate($data);
                return json_encode(array('code' => 200, 'message' => 'Plan added/updated successfully.', 'data' => $result));
//                return $result;
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
//                return $e->getMessage();
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
//            throw new Exception('Argument Not Passed');
        }
    }

    public function addPlan()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data, 'plan_id');
                return json_encode(array('code' => 200, 'message' => 'Plan added successfully.', 'data' => $result));
//                return $result;
            } catch (\Exception $e) {
                return json_encode(array('code' => 400, 'message' => 'Could not add data. Please try again later', 'data' => $e));
//                return $e->getMessage();
            }
        } else {
            return json_encode(array('code' => 400, 'message' => 'Argument Not Passed.'));
//            throw new Exception('Argument Not Passed');
        }
    }

    public function getPlanWhere1($where, $selectedCols = ['*'])
    {
        $returnData = array('code' => 400, 'message' => 'Argument Not Passed.', 'data' => null);
        if (func_num_args() > 0) {
            $where = func_get_arg(0);
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->leftjoin('plans as p', function ($join) {
                    $join->on('plans.parent_plan_id', '=', 'p.plan_id');
                })
                ->select(DB::raw('plans.*, p.charge_per_unit AS actual_rate'))
                ->first();
            if ($result) {
                $returnData['code'] = 200;
                $returnData['message'] = 'Plan details.';
            } else {
                $returnData['code'] = 400;
                $returnData['message'] = 'No data found.';
            }
            $returnData['data'] = $result;
        }
        return json_encode($returnData);
    }


}
