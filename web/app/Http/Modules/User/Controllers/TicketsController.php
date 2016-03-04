<?php
namespace InstagramAutobot\Http\Modules\User\Controllers;

use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Modules\User\Models\Ticket_reply;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use InstagramAutobot\Http\Modules\User\Models\Ticket;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;

class TicketsController extends Controller
{
    public function createTicket(Request $request)
    {
        if($request->isMethod('post')){
            $sub=$request->input('subject');
            $txt=$request->input('text');
            $this->validate($request, [
                'text' => 'required'
            ], [
                'text.required' => 'Please write something '
            ]);
            $sessionUserDetails = Session::get('ig_user');
            $id = $sessionUserDetails['id'];
            $objTicket = new Ticket();
//            dd($objComment);
            $input = array(
                'ticket_id' => '',
                'user_id' => $id,
                'subject' => $sub,
                'descriptions' =>trim(preg_replace('/\s+/', ' ', $txt)),
                'ticket_status' => '0'
            );
//            dd($input);
//            $data = json_encode($input, true);
//            dd($data);
//            $result = DB::table('comments')->insert($data);
            $result = $objTicket->addNewTicket($input);

            if ($result) {
                return Redirect::back()->with(['status' => 'Success', 'message' => 'Your Ticket has Successfully generated, Any more Query ask here!!!']);
            } else {
                return Redirect::back()->with(['status' => 'Error', 'message' => 'Some Problem occurred, Please reload the page and try again.']);
            }
        }

        return view('User::tickets.createtickets');
    }

    public function showTickets(){
        $userId=Session::get('ig_user')['id'];
        $objUserTicket = Ticket::getInstance();
        $where=array(
            'rawQuery'=>'user_id=?',
            'bindParams'=>[$userId]
        );
        $selectedColumns=array('users.*','tickets.*');
        $ticketDetailsOfUser = $objUserTicket->getUserInfoByUserId($where,$selectedColumns);
//        dd($ticketDetailsOfUser);

        return view('User::tickets.showtickets',['tickets'=>$ticketDetailsOfUser]);
    }
    public function changeTicketStatusAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $method = $request->input('method');
            switch ($method) {
                case "changeStatus":
                    $ticketId = $request->input('id');
                    $status = $request->input('status');
                    $objModelUser = Ticket::getInstance();
                    $whereForUpdateUser = array(
                        'rawQuery' => 'ticket_id = ?',
                        'bindParams' => [$ticketId]
                    );
//                    print_r($whereForUpdateUser);
                    $dataForUpdateUser = array('ticket_status' => $status);
                    print_r($dataForUpdateUser);
                    $updated = $objModelUser->updateTicketWhere($dataForUpdateUser, $whereForUpdateUser);
                    if ($updated) {
                        $message = '';
                        if ($status == 1) {
                            $message = "Ticket Closed.";
                        } else {
                            $message = "Ticket Opened.";
                        }

                        echo json_encode(array('status' => '200', 'message' => $message));

                    } else {
                        echo json_encode(array('status' => '400', 'message' => 'Failed. Plesae try again.'));

                    }
                    break;

                default:

                    break;
            }
        }
    }

    public function showTicketsAjaxHandler(){
        $userId=Session::get('ig_user')['id'];
        $objUserTicket = Ticket::getInstance();
        $where=array(
            'rawQuery'=>'user_id=?',
            'bindParams'=>[$userId]
        );
        $selectedColumns=array('users.*','tickets.*');
        $ticketDetailsOfUser = $objUserTicket->getUserInfoByUserId($where,$selectedColumns);
//        dd($ticketDetailsOfUser);

        $tickets = new Collection;

        $ticketDetailsOfUser = json_decode(json_encode($ticketDetailsOfUser), true);
        foreach ($ticketDetailsOfUser as $tdu) {
            $id = $tdu['ticket_id'];
            if ($tdu['ticket_status'] == 0)
                $status = 'opened';
            else
                $status = 'closed';
            $tickets->push([
                'ticket_id' => $tdu['ticket_id'],
                'name' => $tdu['name'] . $tdu['lastname'],
                'email' => $tdu['email'],
                'subject' => $tdu['subject'],
                'descriptions' => $tdu['descriptions'],
                'status' => $status,
                'created_at' => $tdu['created_at'],
//                'view' => '<a href ="view-queries/' . $id . '">view</a>'
            ]);
        }

        return Datatables::of($tickets)->make(true);
    }

//    public function replyOnTickets(){
//        return view('User::tickets.replyontickets');
//    }

    public function replyOnTicketsGet($id)
    {
//        print_r($id);die;
        $objTicket = Ticket::getInstance();
        $whereForData = array('rawQuery' => 'ticket_id=?', 'bindParams' => [$id]);
        $selectedColumns = ['users.*', 'tickets.*', 'ticket_reply.reply_text','ticket_reply.created_at','ticket_reply.replied_by'];

        $ticketDetails = $objTicket->getUserInfoByTicketId($whereForData, $selectedColumns);
//        dd($ticketDetails);
//        $numItems = count($ticketDetails);
//        $i = 0;
//        foreach($ticketDetails as $t) {
//            if (++$i === $numItems) {
//                $username = $t->username;
//                $email = $t->email;
//                $ticket_id = $t->ticket_id;
//                $subject = $t->subject;
//                $descriptions = $t->descriptions;
//                $text = $t->reply_text;
//            }
//        }
//        print_r($username);
//        print_r($email);
//        print_r($ticket_id);
//        print_r($subject);
//        print_r($descriptions);
//        print_r($text);
//        die;
        return view('User::tickets.replyontickets',['ticketdetails' => $ticketDetails]);

    }

    public function replyOnTicketsPost($id)
    {


        $reply = $_POST['val'];
        $reply=trim(preg_replace('/\s+/', ' ', $reply));
        if(!($reply)=="") {
//        $objTicket = Ticket::getInstance();
//        $whereForData = array('rawQuery' => 'ticket_id=?', 'bindParams' => [$id]);
//        $selectedColumns = ['users.*', 'tickets.*', 'ticket_reply.reply_text','ticket_reply.created_at'];
//
//        $ticketDetails = $objTicket->getUserInfoByTicketId($whereForData, $selectedColumns);


//        print_r($reply);
////        $id = $_POST['ticket_id'];
//        print_r($id);die;
            $objMOdelTicket_reply = new Ticket_reply();
//            dd($objComment);
            $input = array(
                'reply_id' => '',
                'ticketId' => $id,
                'replied_by' => Session::get('ig_user')['id'],
                'reply_text' => $reply,
            );
//            dd($input);
//            $data = json_encode($input, true);
//            dd($data);
//            $result = DB::table('comments')->insert($data);
            $result = $objMOdelTicket_reply->addNewReply($input);
        }
        /*-------------------------------------------------*/
//        $objTicket = Ticket::getInstance();
//        $whereForData = array('rawQuery' => 'ticket_id=?', 'bindParams' => [$id]);
//        $selectedColumns = ['users.*', 'tickets.*', 'ticket_reply.reply_text','ticket_reply.created_at'];
//
//        $ticketDetails = $objTicket->getUserInfoByTicketId($whereForData, $selectedColumns);
//        $countTotalReply = count($ticketDetails);
//        $i = 0;
//        foreach($ticketDetails as $t) {
////            if (++$i === $countTotalReply) {
//            $username = $t->username;
//            $email = $t->email;
//            $ticket_id = $t->ticket_id;
//            $subject = $t->subject;
//            $descriptions = $t->descriptions;
//            $text = $t->reply_text;
////            }
//        }
        /*-------------------------*/
//        if ($result) {
////                    $objMailTemplate = new MailTemplate();
////                    $temp_name = "forgot_password_mail";
////                    $mailTempContent = $objMailTemplate->getTemplateByName($temp_name);
//            $key = env('MANDRILL_KEY');
//            $mandrill = new Mandrill($key);
//            $async = false;
//            $ip_pool = 'Main Pool';
//            $message = array(
//                'html' => "<html><body>Hello!!!*|username|* Reply to your query<br>
//                           Ticket ID: *|ticket_id|*<br>
//                           Subject:*|subject|*<br>
//                           Descriptions:*|descriptions|*<br>
//                           Reply:*|reply_text|*<br>
//                           <br> If You are Satisfied with this reply, Please Close the ticket.</body></html>",
//                'subject' => "Reply To your Query",
//                'from_email' => "support@instagramautolike.com",
//                'to' => array(
//                    array(
//                        'email' => $email,
//                        'type' => 'to'
//                    )
//                ),
//                'merge_vars' => array(
//                    array(
//                        "rcpt" => $email,
//                        'vars' => array(
//                            array(
//                                "name" => "username",
//                                "content" => $username
//                            ),
//                            array(
//                                "name" => "ticket_id",
//                                "content" => $ticket_id
//                            ),
//                            array(
//                                "name" => "subject",
//                                "content" => $subject
//                            ),
//                            array(
//                                "name" => "descriptions",
//                                "content" => $descriptions
//                            ),
//                            array(
//                                "name" => "reply_text",
//                                "content" => $text
//                            ),
//                        )
//                    )
//                ),
//            );
//
//            $mailrespons = $mandrill->messages->send($message, $async, $ip_pool);
////                  dd($mailrespons);
//            if ($mailrespons[0]['status'] == "sent") {
//                return Redirect::back()->with(['status' => 'Success', 'msg' => 'An email has sent to ' . $email . ' regarding the reply.']);
//            } else {
//                return Redirect::back()->with(['status' => 'Error', 'msg' => 'Missing Something.']);
//            }
//        }
//
//        else {
//            return Redirect::back()->with(['status' => 'Error', 'msg' => 'This Email is not Registered.']);
//            //return redirect('admin/forgot');
////                return redirect('admin/forgotpasswordpage')->withErrors([
////                    'errMsg' => 'this email is not registered.'
////                ]);
//        }
//
//
//    }

}


}