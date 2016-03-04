<?php

namespace App\Http\Controllers\User;

use App\Http\Models\Ticket;
use Illuminate\curl\CurlRequestHandler;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


use Illuminate\Support\Facades\Validator;
use InstagramAutobot\Http\Requests;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables;
use stdClass;

class TicketsController extends Controller
{

    protected $API_TOKEN;

    public function  __construct()
    {
        $this->API_TOKEN = env('API_TOKEN');
    }


    public function createTicket(Request $request)
    {
        $response = new stdClass();

        if ($request->isMethod('post')) {
            $postData = $request->all();
            $objUserModel = new User();
            $objTicket = new Ticket();
            $userId = "";
            if (isset($postData['user_id'])) {
                $userId = $postData['user_id'];
            }

            $authFlag = false;
            if (isset($postData['api_token'])) {
                $apiToken = $postData['api_token'];

                if ($apiToken == $this->API_TOKEN) {
                    $authFlag = true;
                } else {
                    if ($userId != '') {
                        $where = [
                            'rawQuery' => 'id=?',
                            'bindParams' => [$userId]
                        ];
                        $selectColumn = array('login_token');
                        $userCredentials = $objUserModel->getUsercredsWhere($where, $selectColumn);
                        if ($apiToken == $userCredentials->login_token) {
                            $authFlag = true;
                        }
                    }
                }
            }

            if ($authFlag) {

                $rules=[
                    'user_id'=>'required|exist:users,id',
                    'text' => 'required'
                ];

                $messages=[
                    'text.required' => 'Please write something '
                ];
                $validator = Validator::make($postData, $rules,$messages);
                if (!$validator->fails()) {
                    $input = array(
                        'user_id' => $postData['user_id'],
                        'subject' => $postData['subject'],
                        'descriptions' =>trim(preg_replace('/\s+/', ' ', $postData['text'])),
                        'ticket_status' => '0',
                        'created_at' => '',
                    );

                    $result = $objTicket->addNewTicket($input);
                    if ($result) {
                        $response->code = 200;
                        $response->message = "Your Ticket has  generated succesfully. ";
                        $response->data = $result;
                        echo json_encode($response, true);

                    }else{
                        $response->code = 401;
                        $response->message = "Something went wrong, please try after sometime. ";
                        $response->data = null;
                        echo json_encode($response, true);
                    }
                }else{
                    $response->code = 401;
                    $response->message = $validator->messages();
                    $response->data = null;
                    echo json_encode($response, true);
                }

            } else {
                $response->code = 401;
                $response->message = "Access Denied";
                $response->data = null;
                echo json_encode($response, true);
            }
        } else {
            $response->code = 400;
            $response->message = "Request not allowed";
            $response->data = null;
            echo json_encode($response, true);
        }

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