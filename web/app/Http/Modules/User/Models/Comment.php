<?php
namespace InstagramAutobot\Http\Modules\User\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    public static $_instance = null;
    protected $table = 'comments';
    protected $fillable = ['comment_id', 'comment_group_id', 'comments', 'added_by', 'comment_status'];

    public static function getInstance()
    {
        if (!is_object(self::$_instance))  //or if( is_null(self::$_instance) ) or if( self::$_instance == null )
            self::$_instance = new Comment();
        return self::$_instance;
    }


    public function getCommentById($commentId)
    {
        $result = Comment::whereId($commentId)->first();
        //first() function in sql returns the first value of the selected column
        return $result;
    }

    /**
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function addNewComment()
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
     * Get all Comments details
     * @param $where
     * @param array $selectedColumns Column names to be fetched
     * @return mixed
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function getAllCommentsWhere($where, $selectedColumns = ['*'])
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
    public function getCommentWhere($where, $selectedColumns = ['*'])
    {
        $result = DB::table($this->table)
            ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
            ->select($selectedColumns)
            ->first();
        return $result;
    }

    /**
     * Update Comment details
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function updateCommentWhere()
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
     * Delete Comment details
     * @return string
     * @throws Exception
     * @since 15-1-2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */
    public function deleteCommentWhere()
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
     * This is for JOINING comments_group table and comments table
     * to fetch the comment_group_name through comment_group_id
     * @return string
     * @throws Exception
     * @since 8th feb 2016
     * @author Saurabh Kumar <saurabh.kumar@globussoft.com>
     */

    public function getCommentsGrpNameFromCommentsGrpId()
    {
        try {
            $result = Comment::join('comments_groups', function ($join) {
                $join->on('comments.comment_group_id', '=', 'comments_groups.comment_group_id');
            })
                ->select()
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }
    public function getCommentsDetails($where,$selectedColumns = ['*'])
    {
        try {
            $result = DB::table($this->table)
                ->whereRaw($where['rawQuery'], isset($where['bindParams']) ? $where['bindParams'] : array())
                ->join('comments_groups', function ($join) {
                    $join->on('comments.comment_group_id', '=', 'comments_groups.comment_group_id');
                })
                ->select($selectedColumns)
                ->get();
            return $result;
        } catch (QueryException $e) {
            echo $e;
        }
    }

}