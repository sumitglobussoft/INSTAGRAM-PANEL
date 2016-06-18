<?php

namespace InstagramAutobot\Http\Modules\Admin\Controllers;

use InstagramAutobot\Http\Modules\Admin\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redirect;
use InstagramAutobot\Http\Modules\User\Models\Usersmeta;
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

        foreach ($allOrders as $order) {
            $id = $order['order_id'];
            $reAdd = '';
            $cancel = '';
            if ($order['status'] == 0) {
                $status = '<span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span>';
//                $reAdd = '<button class="label label-warning" id="reAdd"> Re-add</button>';
//                $cancel = '<button class="bond label label-warning" id="cancel" data-id=' . $id . '> Cancel</a>';
//                $cancel = '<span class="btn popovers btn-default btn-xs"><i class="fa fa-info-circle"></i> Cancel</span>';
//                $reAdd = '<span class="btn popovers btn-default btn-xs"><i class="fa fa-info-circle"></i> Re-Add </span>';
            } elseif ($order['status'] == 1) {
                $status = '<span class="label label-info"><i class="fa fa-refresh fa-spin"></i>Processing</span>';
            } elseif ($order['status'] == 2) {
                $status = '<span class="label label-info"><i class="fa fa-refresh fa-spin"></i>Processing</span>';
            } elseif ($order['status'] == 3) {
                $status = '<span class="label label-primary"><i class="fa fa-check-circle"></i> Completed</span>';
            } elseif ($order['status'] == 4) {
                $status = 'Failed';
            } elseif ($order['status'] == 5) {
                $status = '<span class="label label-warning"><i class="fa fa-dollar"></i>Refunded</span>';
            } else {
                $status = '<span class="label label-danger"><i class="fa fa-times-circle"></i> Cancelled</span>';
            }

            $orders->push([
                'chck'=> '<input type="checkbox" class="orderCheckBox" name="checkbox" value="' .$id. '">',
                'id' => ++$i,
                'order_id' => $order['order_id'],
                'by_user_id' => $order['name'],
                'plan_id' => $order['plan_name'],
                'ins_url' => '<a href="' . $order['ins_url'] . '" target="_blank">' . $order['ins_url'] . '</a>',
                'quantity_total' => $order['quantity_total'],
                'status' => $status,
//                'view' => '<a href= "url('view-orders', $parameters = [$id], $secure = null)">View</a>'
//                'view' => '<a href ="view-orders/' . $id . '">view</a>'
                'view' => '<a class="btn  btn-sm btn-default btn-raised" data-toggle="modal" data-target="#modal1" data-id=' . $id . ' style="margin-left:1%;">details</a>',
//                'cancel' => '<button class="bond label label-warning" id="cancel" data-id=' . $id . '> Cancel</a>',
//                'reAdd' => '<button aria-expanded="true" data-toggle="dropdown"
//                                                    class="btn btn-default dropdown-toggle" type="button">
//                                                <i class="fa fa-cog"></i>&nbsp;
//                                                <span class="caret"></span>
//                                            </button>
//                                            <ul role="menu" class="dropdown-menu">
//                                                <li><a href="javascript:void(0);" class="bond" data-id=' . $id . '><i class="fa fa-pencil"></i>&nbsp;Cancel</a>
//                                                </li>
//                                                <li><a href="javascript:void(0);" class="delete-option"
//                                                       data-id='.$id.'><i class="fa fa-trash"></i>&nbsp;Restart</a>
//                                                </li>
//                                            </ul>'
//                'reAdd' => $reAdd
//                'reAdd' => '<select class="action" data-id=' . $id . '><option value="0">&nbsp;Select Action</option><option value="1">&nbsp;Cancel</option><option value="2">&nbsp;Restart</option>
//            </select>'
            ]);
        }

        return Datatables::of($orders)->make(true);
    }

    public function cancelOrderAjaxHandler(Request $request)
    {
        if ($request->isMethod('post')) {
            $orderID = $request->input('orderId');
            $method = $request->input('method');
            $objModelOrder = Order::getInstance();

            $msg = [];
            foreach ($orderID as $orderId) {
                $where = array('rawQuery' => 'order_id=?', 'bindParams' => [$orderId]);
                $selectCols = ['orders.*', 'usersmeta.account_bal'];
                $orderInfo = $objModelOrder->getOrderWithUsersmetaInfo($where, $selectCols);

                switch ($method) {
                    case "Cancel":
                        foreach ($orderInfo as $oi) {
                            $orderStatus = $oi->status;
                            $price = $oi->price;
                            $userId = $oi->by_user_id;
                            $accountBalance = $oi->account_bal;
                        }

                        if ($orderStatus == 0) {
                            $newAccountBalance = $accountBalance + $price;

                            // change the status from pending(1) to cancelled (6)
                            $whereForUpdateOrder = array('rawQuery' => 'order_id=?', 'bindParams' => [$orderId]);
                            $dataForUpdateOrder = array('status' => '6');
                            $updated = $objModelOrder->updateOrderWhere($dataForUpdateOrder, $whereForUpdateOrder);

                            // add the price to the users account_bal
                            $objModelUsersmeta = Usersmeta::getInstance();
                            $whereForUpdateUsersmeta = array('rawQuery' => 'user_id=?', 'bindParams' => [$userId]);
                            $dataForUpdateUsersmeta = array('account_bal' => $newAccountBalance);
                            $updatedUsersmeta = $objModelUsersmeta->updateUsersmetaWhere($dataForUpdateUsersmeta, $whereForUpdateUsersmeta);
                            if ($updatedUsersmeta) {
                                $msg[] = 'Order Id #' . $orderId . 'has successfully cancelled and amount has been refunded to the account.';
//                                echo json_encode(array('status' => '200', 'message' => 'cancelled'));
                            } else {
                                echo json_encode(array('status' => '400', 'message' => 'Some Problem Occured. Please reload and try again.'));
                            }
                        } elseif ($orderStatus != 0) {
                            if ($orderStatus == 1 || $orderStatus == 2)
                                $msg[] = 'Sorry, Order Id #' . $orderId . ' is already in process. You can\'t cancel it now';
                            elseif ($orderStatus == 3)
                                $msg[] = 'Sorry, Order Id #' . $orderId . ' has been completed';
                            elseif ($orderStatus == 4)
                                $msg[] = 'Sorry Order Id #' . $orderId . ' has been failed';
                            elseif ($orderStatus == 5 || $orderStatus == 6)
                                $msg[] = 'The Order Id #' . $orderId . ' has been already cancelled';
                        }
                        break;
                    case "Restart":
//                    dd($orderInfo);
                        foreach ($orderInfo as $oi) {
                            $price = $oi->price;
                            $account_bal = $oi->account_bal;
                            $uId = $oi->by_user_id;
                            $data = array('by_user_id' => $oi->by_user_id, 'plan_id' => $oi->plan_id, 'for_user_id' => $oi->for_user_id, 'ins_url' => $oi->ins_url,
                                'url_type' => $oi->url_type, 'start_index' => $oi->start_index, 'end_index' => $oi->end_index, 'comment_id' => $oi->comment_id, 'quantity_total' => $oi->quantity_total,
                                'orders_per_run' => $oi->orders_per_run, 'price' => $oi->price, 'quantity_done' => '0', 'start_time' => time() + 600,
                                'end_time' => $oi->end_time, 'time_interval' => $oi->time_interval, 'status' => '0', 'auto_order_status' => $oi->auto_order_status,
                                'cronjob_status' => $oi->cronjob_status, 'parent_order_id' => $oi->parent_order_id, 'added_time' => time(), 'updated_time' => time());


//                    dd($data[0]);
                        }
                        if ($account_bal >= $price) {
                            $newAccountBal = $account_bal - $price;
                            $newOrderId = $objModelOrder->addNewOrder($data);
                            DB::beginTransaction();
                            DB::table('usersmeta')->where('user_id', '=', $uId)->lockForUpdate()->get();
                            // deduct the price from the users account_bal
                            $objModelUsersmeta = Usersmeta::getInstance();
                            $whereForUpdateUsersmeta = array('rawQuery' => 'user_id=?', 'bindParams' => [$uId]);
                            $dataForUpdateUsersmeta = array('account_bal' => $newAccountBal);
                            $updatedUsersmeta = $objModelUsersmeta->updateUsersmetaWhere($dataForUpdateUsersmeta, $whereForUpdateUsersmeta);
                            DB::commit();
                            if ($updatedUsersmeta && $newOrderId) {
                                $msg[] = 'Order with order id #' . $orderId . ' has been restarted with new order id #' . $newOrderId . '';
//                           echo json_encode(array('status' => '600', 'message' => 'The Order with order id #' . $orderId . ' has been restarted with new order id #' . $newOrderId . ''), true);
                            } else {
                                echo json_encode(array('status' => '400', 'message' => 'Some Problem Occured. Please reload and try again.'));
                            }
                        } else {
                            $msg[] = 'Insufficient balance for user # ' . $uId . ' to place the order #' . $orderId . '';
                        }
                        break;

                    default:
                        break;

                }
            }
            echo json_encode(array('status' => '200', 'message' => $msg));

        }

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