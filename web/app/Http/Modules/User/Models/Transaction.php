<?php
namespace InstagramAutobot\Http\Modules\User\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public static $_instance = null;
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['tx_id', 'tx_type', 'tx_mode', 'tx_code', 'transaction_id', 'user_id', 'amount', 'payment_time'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Transaction();
        return self::$_instance;
    }

    public function getTransactionDetailsById($ticketId)
    {
        $result = Transaction::whereId($ticketId)->first();
        //first() function in sql returns the first value of the selected column
        return $result;
    }

    /**
     * @return string
     * @throws Exception
     * @since 10-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function addNewTransaction()
    {
        if (func_num_args() > 0) {
            $data = func_get_arg(0);
            try {
                $result = DB::table($this->table)->insertGetId($data);
//                print_r($result);die;
                return $result;

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else {
            return 1;
//            echo'reached here';
//            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * Get all Tickets details
     * @param $where
     * @param array $selectedColumns Column names to be fetched
     * @return mixed
     * @throws Exception
     * @since 10-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAllTransactionsWhere($where, $selectedColumns = ['*'])
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
     * @since 10-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getTransactionWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }

    /**
     * Update Tickets details such as change status
     * @return string
     * @throws Exception
     * @since 10-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function updateTransactionWhere()
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
     * Delete tickets details
     * @return string
     * @throws Exception
     * @since 10-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function deleteTransactionWhere()
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
     * This is for joining users table and tickets table so that i can get the users info in ticket details page
     * @since 11-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAvaiableUsersDetails()
    {
        try {
            $result = Transaction::join('users', function ($join) {
                $join->on('transactions.user_id', '=', 'users.id');
            })
                ->select()
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }

    public function getUserInfoByUserId($where, $selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('users', function ($join) {
                    $join->on('transactions.user_id', '=', 'users.id');
                })
//                ->join('ticket_reply', 'ticket_reply.ticketId', '=', 'tickets.ticket_id')
                ->select($selectedColumns)
//                ->paginate(10);
                ->get();
//            if(!$result){
//                $result = Ticket::join('users', function ($join) {
//                    $join->on('tickets.user_id', '=', 'users.id');
//                })
//                    ->select()
//                    ->get();
//                return $result;
//            }
////                return 5;
//
//            else{
            return $result;
//        }
        } catch (QueryException $e) {
            echo $e;
        }
    }
}
