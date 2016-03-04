<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    public static $_instance = null;
    protected $table = 'notifications';
    protected $fillable = ['notification_id', 'user_id', 'notifications_txt', 'created_at'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Notification();
        return self::$_instance;
    }

    public function getNotificationById($id)
    {
        $result = Notification::whereId($id)->first();
        //first() function in sql returns the first value of the selected column
        return $result;
    }

    /**
     * @return string
     * @throws Exception
     * @since 20-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function addNewNotification()
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
            throw new Exception('Argument Not Passed');
        }
    }

    /**
     * Get all Notification details
     * @param $where
     * @param array $selectedColumns Column names to be fetched
     * @return mixed
     * @throws Exception
     * @since 20-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAllNotificationsWhere($where, $selectedColumns = ['*'])
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
     * @since 20-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getNotificationWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }

    /**
     * Update Notification details
     * @return string
     * @throws Exception
     * @since 20-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    //TODO // still to think what to update// how not to show the seen notifications
    public function updateNotificationWhere()
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
     * Delete Notification details
     * @return string
     * @throws Exception
     * @since 20-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function deleteNotificationWhere()
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
     * This is for joining users table and notifications table so that i can get the users info in notification log
     * @since 11-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
//    public function getAvaiableUsersDetails()
//    {
//        try {
//            $result = Ticket::join('users', function ($join) {
//                $join->on('tickets.user_id', '=', 'users.id');
//            })
//                ->select()
//                ->get();
//            return $result;
//        } catch (QueryException $e) {
//            echo $e;
//        }
//    }
//    public function getUserInfoByUserId($where,$selectedColumns = ['*'])
//    {
//        try {
//            $result = DB::table($this->table)
//                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
//                ->join('users', function ($join) {
//                    $join->on('tickets.user_id', '=', 'users.id');
//                })
////                ->join('ticket_reply', 'ticket_reply.ticketId', '=', 'tickets.ticket_id')
//                ->select($selectedColumns)
//                ->get();
////            if(!$result){
////                $result = Ticket::join('users', function ($join) {
////                    $join->on('tickets.user_id', '=', 'users.id');
////                })
////                    ->select()
////                    ->get();
////                return $result;
////            }
//////                return 5;
////
////            else{
//            return $result;
////        }
//        } catch (QueryException $e) {
//            echo $e;
//        }
//    }
}
