<?php namespace InstagramAutobot\Http\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Ticket_reply extends Model
{
    public static $_instance = null;
    protected $table = 'ticket_reply';
    protected $fillable = ['reply_id', 'ticketId', 'replied_by', 'reply_text', 'created_at'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Ticket_reply();
        return self::$_instance;
    }

    public function getTicketReplyById($replyId)
    {
        $result = Ticket_reply::whereId($replyId)->first();
        //first() function in sql returns the first value of the selected column
        return $result;
    }

    /**
     * @return string
     * @throws Exception
     * @since 13-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function addNewReply()
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
     * Get all Reply details
     * @param $where
     * @param array $selectedColumns Column names to be fetched
     * @return mixed
     * @throws Exception
     * @since 13-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAllRepliesWhere($where, $selectedColumns = ['*'])
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
     * @since 13-2-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getReplyWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }
}