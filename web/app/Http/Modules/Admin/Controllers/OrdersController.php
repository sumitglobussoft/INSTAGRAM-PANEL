<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Requests;
use InstagramAutobot\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use stdClass;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function orderList()
    {
        return view('Admin::orders.orderlists');
    }

    public function showOrderListAjaxHandler()
    {
        $objModelOrder = Order::getInstance();

        $selectedColumns = ['users.name', 'orders.*', 'plans.plan_name'];
        $allOrders = $objModelOrder->getAvaiableUserDetailsFromOrders($selectedColumns);

        $orders = new Collection;

        $allOrders = json_decode(json_encode($allOrders), true);
        $i = 0;
//        $userName = DB::table('orders')
//            ->join('users', 'orders.by_user_id', '=', 'users.id')
//            ->select('users.name')
//            ->where('users.status', '=', 0)
//            ->get();
        //  2 payment done, order not yet started 1 completed 3 running 4 failed else started
        foreach ($allOrders as $order) {
            $id = $order['order_id'];

            if ($order['status'] == 0) {
                $status = '<span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span>';
            } elseif ($order['status'] == 1) {
                $status = 'Queue';
            } elseif ($order['status'] == 2) {
                $status = 'Processing';
            } elseif ($order['status'] == 3) {
                $status = 'Completed';
            } elseif ($order['status'] == 4) {
                $status = 'Failed';
            } elseif ($order['status'] == 5) {
                $status = '<span class="label label-warning"><i class="fa fa-dollar"></i> Refunded</span>';
            } else {
                $status = 'Cancelled';
            }

            $orders->push([
                'id' => ++$i,
                'order_id' => $order['order_id'],
                'by_user_id' => $order['name'],
                'plan_id' => $order['plan_name'],
                'ins_url' => '<a href="' . $order['ins_url'] . '" target="_blank">' . $order['ins_url'] . '</a>',
                'quantity_total' => $order['quantity_total'],
                'status' => $status,
//                'view' => '<a href= "url('view-orders', $parameters = [$id], $secure = null)">View</a>'
//                'view' => '<a href ="view-orders/' . $id . '">view</a>'
                'view' => '<a class="btn btn-default btn-raised" data-toggle="modal" data-target="#modal1" data-id=' . $id . ' style="margin-left:1%;">details</a>'
            ]);
        }

        return Datatables::of($orders)->make(true);
    }

    public function viewOrderList(Request $request)
    {
        if ($request->isMethod('post')) {
            $orderId = $request->input('id');
            $objModelOrder = Order::getInstance();
            $where = array('rawQuery' => 'order_id = ?', 'bindParams' => [$orderId]);
            $selectedColumns = ['users.*', 'orders.*', 'plans.plan_name'];

            $orderDetails = $objModelOrder->getUsersInfoFromOrdersByUserId($where, $selectedColumns);

            if ($orderDetails) {

                foreach ($orderDetails as $od) {

                    $convertedStartTime = $this->convertUT($od->start_time);
                    $convertedEndTime = $this->convertUT($od->end_time);
                    $convertedAddedTime = $this->convertUT($od->added_time);
                    $convertedUpdatedTime = $this->convertUT($od->updated_time);

                }

                echo json_encode(array('status' => '200', 'message' => 'reached till here', 'data' => $orderDetails,
                    'startTime' => $convertedStartTime, 'endTime' => $convertedEndTime, 'addedTime' => $convertedAddedTime,
                    'updatedTime' => $convertedUpdatedTime));

            } else {
                echo json_encode(array('status' => '400', 'message' => 'Failed. Plesae try again.'));

            }

        }


//        $objModelOrder = Order::getInstance();
//        $where = array('rawQuery' => 'order_id = ?', 'bindParams' => [$id]);
//        $selectedColumns = ['users.*', 'orders.*','plans.plan_name'];
//
//        $orderDetails = $objModelOrder->getUsersInfoFromOrdersByUserId($where, $selectedColumns);
//
//        return view('Admin::orders.vieworders', ['order_details' => $orderDetails]);
    }

    public function convertUT($ptime)
    {
        $difftime = time() - $ptime;

        if ($difftime < 1) {
            return '0 seconds';
        }

        $a = array(365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        $a_plural = array('year' => 'years',
            'month' => 'months',
            'day' => 'days',
            'hour' => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );

        foreach ($a as $secs => $str) {
            $d = $difftime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
            }
        }
    }


}