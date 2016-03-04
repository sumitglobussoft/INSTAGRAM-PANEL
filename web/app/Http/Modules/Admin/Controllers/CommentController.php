<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Comment;
use InstagramAutobot\Http\Modules\Admin\Models\Comment_group;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class CommentController extends Controller
{
    public function addComment(Request $request)
    {
        $objModelCommentGroup = Comment_group::getInstance();
        $where = array('rawQuery' => 'comment_group_id=1 or 3');
        $grpDetails = $objModelCommentGroup->getAllCommentsGroupDetailsWhere($where);
//        dd($grpDetails);
        if ($request->isMethod('post')) {
            $comment = $request->input('comment');
            $this->validate($request, [
                'comment' => 'required'
            ], [
                'comment.required' => 'Whats in your mind, Please enter something'
            ]);

            $objComment = new Comment();
//            dd($objComment);
            $input = array(
                'comment_group_id' => '',
                'comments' => json_encode(array(trim(preg_replace('/\s+/', ' ', $comment))), true),
                'added_by' => '1',
                'comment_status' => '1',
            );
//            dd($input);
//            $data = json_encode($input, true);
//            dd($data);
//            $result = DB::table('comments')->insert($data);
            $result = $objComment->addNewComment($input);
            if ($result) {
                return Redirect::back()->with(['status' => 'Success', 'msg' => 'Your comment has successfully added, Add some more here!!!']);
            } else {
                return Redirect::back()->with(['status' => 'Error', 'msg' => 'Some Problem occurred, Please reload the page and try again.']);
            }
        }
        return view('Admin::comments.addcomments', ['groupname' => $grpDetails]);


    }

    public function showRandomComments()
    {
        $objModelCommentGroup = Comment_group::getInstance();
        $where = array('rawQuery' => 'comment_group_id=1 or 3');
        $grpDetails = $objModelCommentGroup->getAllCommentsGroupDetailsWhere($where);
//        dd($grpDetails);
        return view('Admin::comments.showcomments', ['groupname' => $grpDetails]);
    }

    public function showCommentsAjaxHandler(Request $request)
    {
        $objModelComment = Comment::getInstance();
        $where = array(
            'rawQuery' => 'added_by = 1'
        );
        $selectedColumns = ['comments.*', 'comments_groups.comment_group_name'];
        $comments = new Collection;
        $cmntDetails = $objModelComment->getCommentsDetails($where, $selectedColumns);
        $cmntDetails = json_decode(json_encode($cmntDetails), true);
        foreach ($cmntDetails as $cmnt) {
            $id = $cmnt['comment_id'];
//            $comm = $cmnt['comments'];
            $comm = json_decode($cmnt['comments'], true);
            $comments->push([
                'id' => $cmnt['comment_id'],
                'comment_group_name' => $cmnt['comment_group_name'],
                'comments' => $comm[0],// $cmnt['comments'],
                'edit' => '<a href ="edit-comments/' . $id . '" class="btn btn-warning">edit</a>',
//                'delete' => '<a href="delete-comments/' . $id . '" class="btn btn-danger">delete</a>'
            ]);

        }
        return Datatables::of($comments)->make(true);

    }

    public function editComments($id, Request $request)
    {
        $objModelComment = Comment::getInstance();
        $whereForComment = array(
            'rawQuery' => 'comment_id = ?',
            'bindParams' => [$id]
        );
        $commentDetails = $objModelComment->getAllCommentsWhere($whereForComment);
//        dd($commentDetails);
        foreach($commentDetails as $cd) {
            $comm = $cd->comments;
        }
        $comm=json_decode($comm, true);

        if ($request->isMethod('post')) {
            $comment = $request->input('comment');
            $this->validate($request, [
                'comment' => 'required'
            ], [
                'comment.required' => 'Whats in your mind, Please enter something'
            ]);

            $whereForUpdateComment = array(
                'rawQuery' => 'comment_id = ?',
                'bindParams' => [$id]
            );
            $dataForUpdateComment = array('comments' => json_encode(array(trim(preg_replace('/\s+/', ' ', $comment)))));
            $updated = $objModelComment->updateCommentWhere($dataForUpdateComment, $whereForUpdateComment);
            if ($updated) {
//                return redirect('/admin/plans-list')->with('message', 'Updated!');
                return Redirect::back()->with(['status' => 'Success', 'msg' => 'Your comment has successfully Updated.']);
            } else {

                return Redirect::back()->with(['status' => 'Error', 'msg' => 'Some Problem occurred, Please reload the page and try again.']);
            }

        }
//        dd($commentDetails);
        return view('Admin::comments.editcomments', ['cd' => $commentDetails,'comm'=>$comm]);

    }

    public function deleteComments($id, Request $request)
    {
        $objModelComment = Comment::getInstance();
        $whereForDelete = array(
            'rawQuery' => 'comment_id = ?',
            'bindParams' => [$id]
        );
        $deleted = $objModelComment->deleteCommentWhere($whereForDelete);
    }

    public function addCommentsAjaxHandler(Request $request)
    {

        $comment_group_id = $_POST['select-comment'];
        if ($comment_group_id == 0) {
            $addGroup = $request->input('group-name');
           $this->validate($request, [
                'group-name' => 'required'
            ], [
                'group-name.required' => 'Please Provide Some Group Name.'
            ]);
            $objComment = new Comment_group();
            $input = array(
                'comment_group_id' => '',
                'comment_group_name' => $addGroup,
            );

            $result = $objComment->addNewCommentGroup($input);
            if ($result) {
                $comment_group_details = Comment_group::orderBy('comment_group_id', 'desc')->first();
                $comment_group_id = $comment_group_details['comment_group_id'];
            }
        }


        $comment = $request->input('comment1');
        $this->validate($request, [
            'comment1' => 'required'
        ], [
            'comment1.required' => 'Whats in your mind, Please enter something'
        ]);

        $objComment = new Comment();
//            dd($objComment);
        $input = array(
            'comment_group_id' => $comment_group_id,
            'comments' => json_encode(array(trim(preg_replace('/\s+/', ' ', $comment))), true),
            'added_by' => '1',
            'comment_status' => '1',
        );
//            dd($input);
//            $data = json_encode($input, true);
//            dd($data);
//            $result = DB::table('comments')->insert($data);
        $result = $objComment->addNewComment($input);
        if ($result) {
            return Redirect::back()->with(['status' => 'Success', 'msg' => 'Your comment has successfully added, Add some more here!!!']);
        } else {
            return Redirect::back()->with(['status' => 'Error', 'msg' => 'Some Error Occurred, For checking errors, please again click on the add comments for group']);
        }
    }

    public function showSelectedComments(Request $request)
    {
        if ($request->isMethod('post')) {
            $method = $request->input('method');
            switch ($method) {
                case "showSelectedComments":
                    $status = $request->input('status');
                    $objModelComment = Comment::getInstance();
                    $whereForComment = array(
                        'rawQuery' => 'comment_group_id = ?',
                        'bindParams' => [$status]
                    );
                    $grpDetails = $objModelComment->getAllCommentsWhere($whereForComment);
//                    dd($grpDetails);
                    $grpDetails = json_decode(json_encode($grpDetails), true);
                    $comments = new Collection;
//                    echo json_encode(array('status' => '200', 'message' => 'success'));
                    foreach ($grpDetails as $gd) {
                        $id = $gd['comment_id'];
//            $comm = $cmnt['comments'];
                        $comm = json_decode($gd['comments'], true);
                        $comments->push([
                            'id' => $gd['comment_id'],
//                            'comment_group_name' => $gd['comment_group_name'],
                            'comments' => $comm[0],// $cmnt['comments'],
//                            'edit' => '<a href ="edit-comments/' . $id . '" class="btn btn-warning">edit</a>',
//                'delete' => '<a href="delete-comments/' . $id . '" class="btn btn-danger">delete</a>'
                        ]);

                    }
//                    dd($comments);
                    $datatable = Datatables::of($comments)->make(true);
                    echo json_encode(array('status' => '200', 'message' => 'success', 'data' => $comments));
//                    return Datatables::of($comments)->make(true);

                    break;

                default:
                    break;
            }
        }

    }

    public function sample(Request $request, $status)
    {
//        $status = $request->input('status');
        dd($status);
        $objModelComment = Comment::getInstance();
        $whereForComment = array(
            'rawQuery' => 'comment_group_id = ?',
            'bindParams' => [$status]
        );
        $grpDetails = $objModelComment->getAllCommentsWhere($whereForComment);
//                    dd($grpDetails);
        $grpDetails = json_decode(json_encode($grpDetails), true);
        $comments = new Collection;
//                    echo json_encode(array('status' => '200', 'message' => 'success'));
        foreach ($grpDetails as $gd) {
//                        $id = $gd['comment_id'];
            $id = $gd['comment_id'];
//            $comm = $cmnt['comments'];
            $comm = json_decode($gd['comments'], true);
            $comments->push([
                'id' => $gd['comment_id'],
//                            'comment_group_name' => $gd['comment_group_name'],
                'comments' => $comm[0],// $cmnt['comments'],
//                            'edit' => '<a href ="edit-comments/' . $id . '" class="btn btn-warning">edit</a>',
//                'delete' => '<a href="delete-comments/' . $id . '" class="btn btn-danger">delete</a>'
            ]);

        }
        return Datatables::of($comments)->make(true);

    }

    public function customSearch(Request $request)
    {
        if ($request->isMethod('post')) {
//            $searchterm = $request->input('name');
//            $searchterm = $_POST['select-comment'];
            $searchterm = $request->input('status');
//print_r($searchterm);die;
//
//        $searchterm = Input::get('searchinput');

//            if ($searchterm) {
//
//                $commentDetails = DB::table('comments_group');
//                $results = $commentDetails->where('comment_group_name', 'LIKE', '%' . $searchterm . '%')
////                ->orWhere('description', 'LIKE', '%'. $searchterm .'%')
////                ->orWhere('brand', 'LIKE', '%'. $searchterm .'%')
//                    ->get();
//
//
//            }
//            dd($results); die;
//            foreach($results as $r){
//            $grpid=$r->comment_group_id;
//            }
            $objModelComment = Comment::getInstance();
            $whereForComment = array(
                'rawQuery' => 'comment_group_id = ?',
                'bindParams' => [$searchterm]
            );
            $grpDetails = $objModelComment->getAllCommentsWhere($whereForComment);
            dd($grpDetails);
            return view('Admin::comments.customsearch', ['cmnt' => $grpDetails]);
        }
    }
}
