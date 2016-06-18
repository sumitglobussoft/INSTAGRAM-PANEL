<?php
namespace App\Http\Controllers\User;

use App\Http\Models\Comment_group;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use stdClass;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;

class CommentsController extends Controller
{
    protected $API_TOKEN;

    public function __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
    }

    public function showComments(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $userId = $postData['userId'];
            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
                if ($apiToken == $this->API_TOKEN) {
                    $authFlag = true;
                }
            }
            if ($authFlag) {
                $objModelCommentGroup = Comment_group::getInstance();
                $where = array('rawQuery' => 'added_by=? or added_by=?', 'bindParams' => ['0', [$userId]]);
                $grpDetails = $objModelCommentGroup->getAllCommentsGroupDetailsWhere($where);
                if ($grpDetails) {
                    $response->code = 200;
                    $response->message = "success.";
                    $response->data = $grpDetails;
                    echo json_encode($response, true);
                } else {
                    $response->code = 400;
                    $response->message = "Error.";
                    $response->data = null;
                    echo json_encode($response, true);
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied.. Auth flag not set";
                $response->data = null;
                echo json_encode($response, true);
            }
        } else {
            $response->code = 400;
            $response->message = "Request not allowed.. couldnt enter into the method post if statement";
            $response->data = null;
            echo json_encode($response, true);
        }
    }

    public function showCommentsDatatablesAjaxHandler(Request $request)
    {
        $response = new stdClass();
        if ($request->isMethod('post')) {
            $postData = $request->all();
            $userId = $postData['userId'];
            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];
                if ($apiToken == $this->API_TOKEN) {
                    $authFlag = true;
                }
            }
            if ($authFlag) {
                $objCommentGrp = Comment_group::getInstance();
                $where = array('rawQuery' => 'added_by=? or added_by=?', 'bindParams' => ['0', [$userId]]);
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
                        'edit' => '<a href ="edit-comments/' . $id . '"><i class="material-icons">phonelink_setup</i></a>',
                        'delete' => ' <a href="javascript:;"><i class="material-icons" id="del" data-id=' . $id . '>delete</i></a>'
                    ]);

                }
                if ($comments) {
                    $response->code = 200;
                    $response->message = "success.";
                    $response->data = $comments;
                    return $response;
                    echo json_encode($response, true);
                } else {
                    $response->code = 400;
                    $response->message = "Error.";
                    $response->data = null;
                    echo json_encode($response, true);
                }
            } else {
                $response->code = 401;
                $response->message = "Access Denied.. Auth flag not set";
                $response->data = null;
                echo json_encode($response, true);
            }
        } else {
            $response->code = 400;
            $response->message = "Request not allowed.. couldnt enter into the method post if statement";
            $response->data = null;
            echo json_encode($response, true);
        }
    }

}