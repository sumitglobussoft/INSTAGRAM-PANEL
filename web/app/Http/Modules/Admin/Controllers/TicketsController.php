<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Ticket;
use Illuminate\Http\Request;
//use DB;
use Illuminate\Support\Facades\Validator;
use Input;
use InstagramAutobot\Http\Modules\Admin\Models\Ticket_reply;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Yajra\Datatables\Datatables;

use Mandrill;

//use InstagramAutobot\Http\Models\MailTemplate;

include public_path() . "/../vendor/mandrill/src/Mandrill.php";

class TicketsController extends Controller
{
    public function ticketDetails()
    {
        return view('Admin::tickets.ticketdetails');
    }

    public function ticketDetailsAjaxHandler()
    {
        $objTicket = Ticket::getInstance();
        $ticketDetails = $objTicket->getAvaiableUsersDetails();

        $tickets = new Collection;

        $ticketDetails = json_decode(json_encode($ticketDetails), true);
        foreach ($ticketDetails as $td) {
            $id = $td['ticket_id'];
            if ($td['ticket_status'] == 0)
                $status = '<button class="btn btn-success" data-id="' . $id . '" data-status="1">&nbsp;Opened</button>';
            else
                $status = '<button class="btn btn-danger" data-id="' . $id . '" data-status="0">&nbsp;Closed</button>';
            $tickets->push([
                'ticket_id' => $td['ticket_id'],
                'name' => $td['name'] . $td['lastname'],
                'email' => $td['email'],
                'subject' => $td['subject'],
                'descriptions' => $td['descriptions'],
                'status' => $status,
                'created_at' => $td['created_at'],
                'reply' => '<a href ="view-queries/' . $id . '">reply</a>'
            ]);
        }

        return Datatables::of($tickets)->make(true);

    }

    public function changeTicketStatusAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $ticketId = $request->input('ticketId');
            $status = $request->input('status');
            if ($status == 0)
                $statusMsg = 'Opened';
            else
                $statusMsg = 'Closed';
            $objModelTicket = Ticket::getInstance();
            $whereForUpdate = array('rawQuery' => 'ticket_id=?', 'bindParams' => [$ticketId]);
            $dataForUpdate = array('ticket_status' => $status);
            $updated = $objModelTicket->updateTicketWhere($dataForUpdate, $whereForUpdate);
            if ($updated) {
                echo json_encode(array('status' => '200', 'message' => 'Status has been changed'));
            } else {
                echo json_encode(array('status' => '400', 'message' => 'Already' . $statusMsg . '.Please reload the page and try again.'));
            }
        }
    }

    public function closedTickets()
    {
        return view('Admin::tickets.closedtickets');
    }

    public function closedTicketsAjaxHandler()
    {
        $objTicket = Ticket::getInstance();
        $ticketDetails = $objTicket->getAvaiableUsersDetails();

        $tickets = new Collection;

        $ticketDetails = json_decode(json_encode($ticketDetails), true);
        foreach ($ticketDetails as $td) {
            if ($td['ticket_status'] == 1) {
                $tickets->push([
                    'ticket_id' => $td['ticket_id'],
                    'name' => $td['name'] . $td['lastname'],
                    'email' => $td['email'],
                    'subject' => $td['subject'],
                    'descriptions' => $td['descriptions'],
//                    'status' => $status,
                    'created_at' => $td['created_at']
                ]);
            }
        }

        return Datatables::of($tickets)->make(true);

    }

    public function reply($id)
    {
        $objTicket = Ticket::getInstance();
        $whereForData = array('rawQuery' => 'ticket_id=?', 'bindParams' => [$id]);
        $selectedColumns = ['users.*', 'tickets.*', 'ticket_reply.reply_text', 'ticket_reply.created_at', 'ticket_reply.replied_by'];

        $ticketDetails = $objTicket->getUserInfoByTicketId($whereForData, $selectedColumns);

        return view('Admin::tickets.reply', ['ticketdetails' => $ticketDetails]);
    }

    public function postreply($id)
    {

        $reply = $_POST['val'];
        $reply = trim(preg_replace('/\s+/', ' ', $reply));
        if (!($reply) == "") {
            $objMOdelTicket_reply = new Ticket_reply();
//            dd($objComment);
            $input = array(
                'reply_id' => '',
                'ticketId' => $id,
                'replied_by' => Session::get('instagram_admin')['id'],
                'reply_text' => $reply,
            );
            $result = $objMOdelTicket_reply->addNewReply($input);
        }
        $objTicket = Ticket::getInstance();
        $whereForData = array('rawQuery' => 'ticket_id=?', 'bindParams' => [$id]);
        $selectedColumns = ['users.*', 'tickets.*', 'ticket_reply.reply_text', 'ticket_reply.created_at'];

        $ticketDetails = $objTicket->getUserInfoByTicketId($whereForData, $selectedColumns);
        $countTotalReply = count($ticketDetails);
        $i = 0;
        foreach ($ticketDetails as $t) {
//            if (++$i === $countTotalReply) {
            $username = $t->username;
            $email = $t->email;
            $ticket_id = $t->ticket_id;
            $subject = $t->subject;
            $descriptions = $t->descriptions;
            $text = $t->reply_text;
//            }
        }
        if ($result) {
//                    $objMailTemplate = new MailTemplate();
//                    $temp_name = "forgot_password_mail";
//                    $mailTempContent = $objMailTemplate->getTemplateByName($temp_name);
            $key = env('MANDRILL_KEY');
            $mandrill = new Mandrill($key);
            $async = false;
            $ip_pool = 'Main Pool';
            $message = array(
                'html' => "<html><body>Hello!!!*|username|* Reply to your query<br>
                           Ticket ID: *|ticket_id|*<br>
                           Subject:*|subject|*<br>
                           Descriptions:*|descriptions|*<br>
                           Reply:*|reply_text|*<br>
                           <br> If You are Satisfied with this reply, Please Close the ticket.</body></html>",
                'subject' => "Reply To your Query",
                'from_email' => "support@instagramautolike.com",
                'to' => array(
                    array(
                        'email' => $email,
                        'type' => 'to'
                    )
                ),
                'merge_vars' => array(
                    array(
                        "rcpt" => $email,
                        'vars' => array(
                            array(
                                "name" => "username",
                                "content" => $username
                            ),
                            array(
                                "name" => "ticket_id",
                                "content" => $ticket_id
                            ),
                            array(
                                "name" => "subject",
                                "content" => $subject
                            ),
                            array(
                                "name" => "descriptions",
                                "content" => $descriptions
                            ),
                            array(
                                "name" => "reply_text",
                                "content" => $text
                            ),
                        )
                    )
                ),
            );

            $mailrespons = $mandrill->messages->send($message, $async, $ip_pool);
//                  dd($mailrespons);
            if ($mailrespons[0]['status'] == "sent") {
                return Redirect::back()->with(['status' => 'Success', 'msg' => 'An email has sent to ' . $email . ' regarding the reply.']);
            } else {
                return Redirect::back()->with(['status' => 'Error', 'msg' => 'Missing Something.']);
            }
        } else {
            return Redirect::back()->with(['status' => 'Error', 'msg' => 'This Email is not Registered.']);
        }


    }

}