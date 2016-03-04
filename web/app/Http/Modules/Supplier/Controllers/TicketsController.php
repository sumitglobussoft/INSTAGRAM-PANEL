<?php

namespace InstagramAutobot\Http\Modules\Supplier\Controllers;

use Illuminate\curl\CurlRequestHandler;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use InstagramAutobot\Http\Modules\Supplier\Models\Ticket;
use Yajra\Datatables\Datatables;

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
            $sessionUserDetails = Session::get('ig_supplier');
            $id = $sessionUserDetails['id'];
            $objTicket = new Ticket();
//            dd($objComment);
            $input = array(
                'ticket_id' => '',
                'user_id' => $id,
                'subject' => $sub,
                'descriptions' =>trim(preg_replace('/\s+/', ' ', $txt)),
                'ticket_status' => '0',
                'created_at' => '',
            );
//            dd($input);
//            $data = json_encode($input, true);
//            dd($data);
//            $result = DB::table('comments')->insert($data);
            $result = $objTicket->addNewTicket($input);
            if ($result) {
                return Redirect::back()->with(['status' => 'Success', 'msg' => 'Your comment has successfully added, Add some more here!!!']);
            } else {
                return Redirect::back()->with(['status' => 'Error', 'msg' => 'Some Problem occurred, Please reload the page and try again.']);
            }
        }

        return view('Supplier::tickets.createtickets');
    }

    public function showTickets(){
//        $userId=Session::get('ig_supplier')['id'];
//        $objUserTicket = Ticket::getInstance();
//        $where=array(
//            'rawQuery'=>'user_id=?',
//            'bindParams'=>[$userId]
//        );
//        $selectedColumns=array('users.*','tickets.*');
//        $ticketDetailsOfUser = $objUserTicket->getUserInfoByUserId($where,$selectedColumns);
//        dd($ticketDetailsOfUser);

        return view('Supplier::tickets.showtickets');
    }

    public function showTicketsAjaxHandler(){
        $userId=Session::get('ig_supplier')['id'];
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
}