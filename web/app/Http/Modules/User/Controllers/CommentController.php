<?php

namespace InstagramAutobot\Http\Modules\User\Controllers;

use Illuminate\Http\Request;
//use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Modules\User\Models\Comment;
use InstagramAutobot\Http\Modules\User\Models\Comment_group;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class CommentController extends Controller
{

    public function showComments()
    {
        $objModelCommentGroup = Comment_group::getInstance();
        $where = array('rawQuery' => 'status=1');
        $grpDetails = $objModelCommentGroup->getAllCommentsGroupDetailsWhere($where);
        return view('User::comments.showcomments', ['groupname' => $grpDetails]);
    }

    public function showCommentsAjaxHandler(Request $request)
    {
        $userId = Session::get('ig_user')['id'];
        $objCommentGrp = Comment_group::getInstance();
        $where = array('rawQuery' => 'added_by=?', 'bindParams' => [$userId]);
        $cmntGrpDetails = $objCommentGrp->getAllCommentsGroupDetailsWhere($where);

        $comments = new Collection;

        $cmntGrpDetails = json_decode(json_encode($cmntGrpDetails), true);
        $count = 0;
        foreach ($cmntGrpDetails as $cmnt) {
            ++$count;
            $id = $cmnt['comment_group_id'];
//            $comm = $cmnt['comments'];
//            $comm = json_decode($cmnt['comments'], true);
            $comments->push([
                'id' => $count,
                'comment_group_name' => $cmnt['comment_group_name'],
//                'comments' => $comm[0],// $cmnt['comments'],
                'edit' => '<a href ="edit-comments/' . $id . '"><i class="material-icons">Edit</i></a>',
                'delete' => ' <a href="javascript:;"><i class="material-icons" id="del" data-id=' . $id . '>delete</i></a>'
            ]);

        }
//        print_r($comments);die;
        return Datatables::of($comments)->make(true);

    }

    public function editComments($id, Request $request)
    {
        $objModelComment = Comment::getInstance();
        $whereForComment = array(
            'rawQuery' => 'comment_group_id = ?',
            'bindParams' => [$id]
        );
        $commentDetails = $objModelComment->getCommentWhere($whereForComment);

        $objModelCommentGrp = Comment_group::getInstance();
        $commentGrpDetails = $objModelCommentGrp->getCommentGroupWhere($whereForComment);

        if ($request->isMethod('post')) {
            $cmntGrpName = $request->input('comment_group_name');
            $comment = $request->input('comment');
            $this->validate($request, [
                'comment_group_name' => 'required|unique:comments_groups,comment_group_name,' . $id . ',comment_group_id',
                'comment' => 'required'
            ], [
                'comment_group_name' => 'Please Enter Group Name',
                'comment.required' => 'Whats in your mind, Please enter something'
            ]);

            $whereForUpdateComment = array(
                'rawQuery' => 'comment_group_id = ?',
                'bindParams' => [$id]
            );
            $dataForUpdateCmntGrp = ['comment_group_name' => $cmntGrpName];
            $updatedGrpName = $objModelCommentGrp->updateCommentGroupWhere($dataForUpdateCmntGrp, $whereForComment);
            if ($commentDetails) {
                $comment = explode(PHP_EOL, $comment);
//            dd($comment);
                $cmnt1 = '';
                foreach ($comment as $cmnt) {
                    if (trim(preg_replace('/\s+/', ' ', $cmnt)) != '') {
                        $cmnt1 .= json_encode(trim(preg_replace('/\s+/', ' ', $cmnt))) . ',';
                    }
                }

                $cmnt2 = (rtrim($cmnt1, ','));
                $cmnt2 = '[' . $cmnt2 . ']';

                $dataForUpdateComment = array('comments' => $cmnt2);
                $updated = $objModelComment->updateCommentWhere($dataForUpdateComment, $whereForUpdateComment);
                if ($updated) {
//                return redirect('/admin/plans-list')->with('message', 'Updated!');
                    return Redirect::back()->with(['status' => 'Success', 'msg' => 'Your comment has successfully Updated.']);
                } elseif ($updatedGrpName) {
                    return Redirect::back()->with(['status' => 'Success', 'msg' => 'Comment Group Name has successfully Updated.']);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'msg' => 'Same as previous comment, No changes.']);
                }
            } else {
                $objComment = new Comment();
                $comment = explode(PHP_EOL, $comment);

                $cmnt1 = '';
                foreach ($comment as $cmnt) {
                    if (trim(preg_replace('/\s+/', ' ', $cmnt)) != '') {
                        $cmnt1 .= json_encode(trim(preg_replace('/\s+/', ' ', $cmnt))) . ',';
                    }
                }

                $cmnt2 = (rtrim($cmnt1, ','));
                $cmnt2 = '[' . $cmnt2 . ']';

                $input = array(
                    'comment_group_id' => $id,
                    'comments' => $cmnt2,
                    'added_by' => '1',
                    'comment_status' => '1',
                );

                $result = $objComment->addNewComment($input);

                if ($result) {
                    return Redirect::back()->with(['status' => 'Success', 'msg' => 'Your comment has been successfully added.']);
                } else {
                    return Redirect::back()->with(['status' => 'Error', 'msg' => 'Some Error Occurred, For checking errors, please again click on the add comments for group']);
                }
            }

        }

        $commentList = 0;
        if ($commentDetails) {
            $commentList = $commentDetails->comments;
            $commentList = json_decode($commentList, true);
        }
        return view('User::comments.editcomments', ['cd' => $commentList, 'cgd' => $commentGrpDetails]);
    }

    public function deleteCommentGroup(Request $request)
    {
        if ($request->isMethod('post')) {
            $cmntGrpId = $request->input('cmntGrpId');
            $objModelComment = Comment::getInstance();
            $objModelGroup = Comment_group::getInstance();
            $whereForDelete = array(
                'rawQuery' => 'comment_group_id = ?',
                'bindParams' => [$cmntGrpId]
            );
            $deletedGroup = $objModelGroup->deleteCommentGroupWhere($whereForDelete);
            $deleted = $objModelComment->deleteCommentWhere($whereForDelete);
            if ($deleted || $deletedGroup) {
                echo json_encode(array('status' => '200', 'message' => 'Comment grp has been successfully deleted'));
            } else {
                echo json_encode(array('status' => '400', 'message' => 'error'));
            }
        }
    }

    public function addCommentsAjaxHandler(Request $request)
    {
        $addGroup = $request->input('comment_group_name');
        $userId = Session::get('ig_user')['id'];
        $this->validate($request, [
            'comment_group_name' => 'required|unique:comments_groups'
        ], [
            'comment_group_name.unique' => 'This comment group has already created. Please choose some other name',
            'comment_group_name.required' => 'Please Provide Some Group Name.'
        ]);
        $objComment = new Comment_group();
        $input = array(
            'comment_group_id' => '',
            'comment_group_name' => $addGroup,
            'added_by' => $userId,
        );

        $result = $objComment->addNewCommentGroup($input);
        if ($result) {
            $comment_group_details = Comment_group::orderBy('comment_group_id', 'desc')->first();
            $comment_group_id = $comment_group_details['comment_group_id'];
        }

        $comment = $request->input('comment1');
        $this->validate($request, [
            'comment1' => 'required'
        ], [
            'comment1.required' => 'Whats in your mind, Please enter something'
        ]);


        $objComment = new Comment();
        $comment = explode(PHP_EOL, $comment);

        $cmnt1 = '';
        foreach ($comment as $cmnt) {
            if (trim(preg_replace('/\s+/', ' ', $cmnt)) != '') {
                $cmnt1 .= json_encode(trim(preg_replace('/\s+/', ' ', $cmnt))) . ',';
            }
        }

        $cmnt2 = (rtrim($cmnt1, ','));
        $cmnt2 = '[' . $cmnt2 . ']';

        $input = array(
            'comment_group_id' => $comment_group_id,
            'comments' => $cmnt2,
            'added_by' => $userId,
            'comment_status' => '1',
        );

        $result = $objComment->addNewComment($input);

        if ($result) {
            return Redirect::back()->with(['status' => 'Success', 'msg' => 'Your comment has been successfully added.']);
        } else {
            return Redirect::back()->with(['status' => 'Error', 'msg' => 'Some Error Occurred, For checking errors, please again click on the add comments for group']);
        }
    }
}
