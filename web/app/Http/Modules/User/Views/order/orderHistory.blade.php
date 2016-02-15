@extends('User/Layouts/userlayout')

@section('title','Dashboard')


@section('headcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);

        .tooltip.top.fade.in {
            color: #fff !important;
            background-color: #000;
        }

        .filter > td {
            vertical-align: middle !Important;
        }

        .seperator-n {
            padding: 0 2px;
        }

        b {
            font-weight: 700;
        }

        .input-inline {
            display: inline-block;
            vertical-align: middle;
            width: auto;
        }

        select.input-sm {
            height: 28px;
            line-height: 28px;
            padding: 2px 10px;
            display: inline-block;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
            vertical-align: middle;
            width: 45px !important;
            color: #333333;
            border: 1px solid #e5e5e5;
            vertical-align: middle;
            width: 75px !important;
        }

        .dataTables_length {
            display: inline-block;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            position: static !important;
            font-family: "Open Sans", sans-serif;
            font-size: 13px;
        }

        .input-sm {
            font-size: 13px;
            height: 28px;
            padding: 5px 10px;
            display: inline-block;
            vertical-align: middle;
            width: 45px !important;
            background-color: white;
            border: 1px solid #e5e5e5;
            box-shadow: none;
            color: #333333;
            font-size: 14px;
            font-weight: normal;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
        }

        .btn-sm {
            font-size: 13px;
            line-height: 1.5;
            padding: 4px 10px 5px;
            background-color: #e5e5e5;
            color: #333333;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive .table .btn {
            margin-left: 0;
            margin-top: 0;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive input[type="text"] {
            border: .5px solid #CCC;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive input[type="text"]:hover, .table.table-striped.table-borderless.table-hover.table-responsive input[type="text"]:focus {
            border-color: #999999;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive .red.btn {
            background-color: #d84a38;
            color: white;
        }

        .btn-xs, .btn.btn-sm.default.table-group-action-submit {
            padding: 0px 1px !important;
            font-size: 9px;
            line-height: 1.5;
            background-color: #e5e5e5;
            color: #333333;
            border-width: 0;
            box-shadow: none;
            filter: none;
            outline: medium none !important;
            text-shadow: none;
            border-radius: 3px;
            margin: 3px;
            outline: medium none !important;
            padding: 0 3px !important;
        }

        .table * {
            line-height: 24px;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive td {
            line-height: 3px;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive small {
            color: rgba(0, 0, 0, 0.54);
            font-size: 11px;
            letter-spacing: 0;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive td.insta {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive td.insta-link {
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive button, a {
            min-width: 5px;
        }

    </style>
    @endsection

    @section('content')
    {{--PAGE CONTENT GOES HERE--}}

            <!-- Right-Page-content Start-->
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Social Market</li>
                <li>Order History</li>
            </ol>
        </section>

        <section class="page-content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="panel panel-default panel-divider">
                        <div class="panel-heading">
                            <header>
                                <div style="float: left">Orders History</div>
                                {{--<div style="float: right">Add Order</div>--}}
                            </header>
                        </div>
                        <div class="panel-body" style="padding-top: 0;">

                            <div class="row">
                                <div class="col-md-8 col-sm-12">
                                    <span style="color:red;" id="errorMessage"></span>
                                    <span style="color:green;" id="successMessage"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-sm-12">
                                    <div class='dataTables_length'>
                                        Page
                                        <a title="Prev" class="btn-sm default prev" href="#">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                        <input type="text" class="form-control input-sm">
                                        <a title="Next" class="btn-sm default next" href="#"><i
                                                    class="fa fa-angle-right"></i></a> of <span
                                                class="pagination-panel-total">14318</span>
                                    </div>
                                    <div class="dataTables_length">
                                        <label>
                                            <span class="seperator-n">|</span>View
                                            <select class="form-control input-sm ">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="150">150</option>
                                                <option value="200">200</option>
                                                <option value="300">300</option>
                                                <option value="400">400</option>
                                                <option value="500">500</option>
                                            </select>
                                            records
                                        </label>
                                    </div>
                                    <div class="dataTables_length">
                                        <span class="seperator-n">|</span>Found total <b> 71,590 </b> records
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="table-group-actions pull-right">
                                        <span id="displaySelectedRecord"></span>
                                        <select class="table-group-action-input form-control input-inline input-small input-sm"
                                                id="selectAction">
                                            <option value="">Select Action</option>
                                            <option value="order_cancel">Cancel selected Order(s)</option>
                                            <option value="order_reAdd">Re-Add selected Order(s)</option>
                                            <option value="order_changeService">Change Service</option>
                                        </select>
                                        <button name="actionSubmit" id="actionSubmit"
                                                class="btn btn-sm default table-group-action-submit"
                                                data-original-title="" title=""><i class="fa fa-check"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-borderless table-hover table-responsive"
                                           id="dataTableHeadData">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="1%"><input type="checkbox" id="groupCheckBox"></th>
                                            <th width="3%">#ID</th>
                                            <th width="25%">Service</th>
                                            <th width="25%">Link</th>
                                            <th width="5%">Amount</th>
                                            <th width="5%">Price</th>
                                            <th width="3%">Added</th>
                                            <th width="3%">Updated</th>
                                            <th width="10%">Status</th>
                                            <th width="20%">Details</th>
                                        </tr>
                                        </thead>
                                    </table>
                                    <table class="table table-striped table-borderless table-hover table-responsive"
                                           id="dataTableBodyData">
                                        <tbody>
                                        <tr role="row" class="filter">
                                            <td></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_id" id="search_id"></td>
                                            <td class='insta-link'>
                                                <select name="search_service_type" id="search_service_type"
                                                        class="form-control form-filter input-sm">
                                                    <option value="" disabled>Select...</option>
                                                    @if(isset($orders))
                                                        @foreach($orders as $orderData)
                                                            <option value="{{$orderData['plan_name']}}">{{$orderData['plan_name']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_link" id="search_link"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_amount" id="search_amount"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_price" id="search_price" disabled="disabled"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_time_added" disabled="disabled"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_time_updated" disabled="disabled"></td>
                                            <td><select name="search_status" id="search_status"
                                                        class="form-control form-filter input-sm">
                                                    <option value="">Select...</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="">Pending but added to server</option>
                                                    <option value="">Processing</option>
                                                    <option value="Completed">Completed</option>
                                                    <option value="Partial Refunded">Error</option>
                                                    <option value="Refunded">Refunded</option>
                                                </select></td>
                                            <td class=''>
                                                <button class="btn btn-xs default filter-submit margin-bottom"
                                                        id="searchButton"><i
                                                            class="fa fa-search"></i> Search
                                                </button>
                                                <button class="btn btn-xs red filter-cancel" id="resetButton"><i
                                                            class="fa fa-times"></i>
                                                    Reset
                                                </button>
                                            </td>
                                        </tr>
                                        @if(isset($orders))
                                            @foreach($orders as $orderData)
                                                <tr role="row">
                                                    <td>
                                                        <div class="checker"><span><input type="checkbox"
                                                                                          class="orderCheckBox"
                                                                                          value="{{$orderData['order_id']}}"
                                                                                          name="orderId[]"></span></div>
                                                    </td>

                                                    <td class="sorting_1">
                                                        <small>{{$orderData['order_id']}}</small>
                                                    </td>
                                                    <td class='insta'>
                                                        <small><i style="font-size:10px"
                                                                  class="fa fa-instagram"></i> {{$orderData['plan_name']}}
                                                        </small>
                                                    </td>

                                                    <td class='insta-link'>
                                                        <small><a target="_blank"
                                                                  href="{{$orderData['ins_url']}}">{{$orderData['ins_url']}}</a>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>{{$orderData['quantity_total']}}</small>
                                                    </td>
                                                    <td>
                                                        <small>$ {{$orderData['price']}}</small>
                                                    </td>

                                                    <td>
                                                        <small>
                                                            <?php
                                                            $dateTime = new \DateTime();
                                                            $dateTime->setTimestamp(intval($orderData['added_time']));
                                                            echo $dateTime->format('Y-m-d H:i:s ');
                                                            ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <small>9 mins</small>
                                                    </td>

                                                    <td>
                                                        <small>
                                                            <small>
                                                                @if ($orderData['status']==0)
                                                                    <span class="label label-info"> <i
                                                                                class="fa fa-clock-o"></i> Pending</span>
                                                                @elseif($orderData['status']==1)
                                                                    <span class="label label-info"> <i
                                                                                class="fa fa-clock-o"></i> Queue</span>
                                                                @elseif($orderData['status']==2)
                                                                    <span class="label label-info"> <i
                                                                                class="fa fa-clock-o"></i> Processing</span>
                                                                @elseif($orderData['status']==3)
                                                                    <span class="label label-info"> <i
                                                                                class="fa fa-clock-o"></i> Completed</span>
                                                                @elseif($orderData['status']==4)
                                                                    <span class="label label-warning"><i
                                                                                class="fa fa-dollar"></i> Refunded</span>
                                                                @endif
                                                            </small>
                                                        </small>
                                                    </td>

                                                    <td>
                                                        <button data-toggle="tooltip" title='popover'
                                                                class="btn popovers btn-default btn-xs"><i
                                                                    class="fa fa-info-circle"></i> Details
                                                        </button>
                                                    </td>


                                                </tr>
                                            @endforeach
                                        @endif

                                        {{--<tr role="row" class="even">--}}
                                        {{--<td>--}}
                                        {{--<div class="checker"><span><input type="checkbox" value="2569509"--}}
                                        {{--name="id[]"></span></div>--}}
                                        {{--</td>--}}
                                        {{--<td class="sorting_1">--}}
                                        {{--<small>2569509</small>--}}
                                        {{--</td>--}}
                                        {{--<td class='insta'>--}}
                                        {{--<small><i style="font-size:10px" class="fa fa-instagram"></i> Instagram--}}
                                        {{--- Likes .--}}
                                        {{--</small>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<small>103</small>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<small>0.08 €</small>--}}
                                        {{--</td>--}}
                                        {{--<td class='insta-link'>--}}
                                        {{--<small><a target="_blank" href="#">https://www.instagram.com</a></small>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<small>17 mins</small>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<small>17 mins</small>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<small>--}}
                                        {{--<small><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span>--}}
                                        {{--</small>--}}
                                        {{--</small>--}}
                                        {{--</td>--}}
                                        {{--<td class='btn-group'>--}}
                                        {{--<button data-toggle="tooltip" title='popover'--}}
                                        {{--class="btn popovers btn-default btn-xs"><i--}}
                                        {{--class="fa fa-info-circle"></i> Details--}}
                                        {{--</button>--}}
                                        {{--<button class="btn btn-default btn-xs" href="#"><i--}}
                                        {{--class="fa fa-pencil"></i> Edit--}}
                                        {{--</button>--}}
                                        {{--</td>--}}
                                        {{--</tr>--}}

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 col-sm-12">
                                    <div class='dataTables_length'>
                                        Page
                                        <a title="Prev" class="btn-sm default prev" href="#">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                        <input type="text" class="form-control input-sm">
                                        <a title="Next" class="btn-sm default next" href="#"><i
                                                    class="fa fa-angle-right"></i></a> of <span
                                                class="pagination-panel-total">14318</span>
                                    </div>
                                    <div class="dataTables_length">
                                        <label>
                                            <span class="seperator-n">|</span>View
                                            <select class="form-control input-sm ">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="150">150</option>
                                                <option value="200">200</option>
                                                <option value="300">300</option>
                                                <option value="400">400</option>
                                                <option value="500">500</option>
                                            </select>
                                            records
                                        </label>
                                    </div>
                                    <div class="dataTables_length">
                                        <span class="seperator-n">|</span>Found total <b> 71,590 </b> records
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </section>

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
    {{--<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>--}}

    <script src="/assets/js/tooltip.js"></script>
    <script src="/assets/js/popover.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {
//            alert("thrtu");
            console.log('yes i got it');

            var tableSel = $('#dataTableBodyData');
            tableSel.dataTable({
                'sDom': '' // Hiding the datatables ui
            });


        });


        // add multiple select / deselect functionality
        $('#groupCheckBox').click(function (event) {

            //$(".orderCheckBox").prop('checked', $(this).prop("checked"));

            if (this.checked) {
                console.log('group box checked');
                $('.orderCheckBox').each(function () {
                    this.checked = true;
                });
                var recordCount = $(".orderCheckBox").length;
                $('#displaySelectedRecord').text(recordCount + " records selected ");
            } else {
                console.log('group box un checked');
                $('.orderCheckBox').each(function () {
                    this.checked = false;
                });
                $('#displaySelectedRecord').text("");
            }
        });

        // if all checkbox are selected, check the selectall checkbox
        // and viceversa
        $(".orderCheckBox").click(function () {
            if ($(".orderCheckBox").length == $(".orderCheckBox:checked").length) {
                $("#groupCheckBox").attr("checked", "checked");
            } else {
                var recordCount = $(".orderCheckBox:checked").length;
                if (recordCount != 0)
                    $('#displaySelectedRecord').text(recordCount + " records selected ");
                else
                    $('#displaySelectedRecord').text("");

                $("#groupCheckBox").removeAttr("checked");
            }
        });


        $('#actionSubmit').click(function () {
            if ($('#selectAction option:selected').attr('value') == '') {
                $('#errorMessage').text("please select an action");
            } else {
                var orderId = new Array();
                $("#dataTableBodyData input:checked").each(function (index) {
                    orderId[index] = $(this).attr('value');
                });

                console.log(orderId);
                if (orderId.length != 0) {
                    if ($('#selectAction option:selected').attr('value') == 'order_cancel') {
                        var x = confirm("Are You sure you want to cancel selected order(s)");
                        if (x) {
                            $.ajax({
                                url: "/user/cancelOrder",
                                type: "POST",
                                dataType: "json",
                                data: {orderId: orderId},
                                success: function (response) {
                                    console.log(response);
                                    if (response['status'] == 'success') {

                                        $('#errorMessage').text("");
                                        $('#successMessage').text(response['message']);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.log(error);
                                }
                            });
                        }
                    }
                    else if ($('#selectAction option:selected').attr('value') == 'order_reAdd') {
                        var x = confirm("Are You sure you want to Re - ADD selected order(s)");
                        if (x) {
                            $.ajax({
                                url: "/user/reAddOrder",
                                type: "POST",
                                dataType: "json",
                                data: {orderId: orderId},
                                success: function (response) {
                                    console.log(response);
                                    if (response['status'] == 'success') {

                                        $('#errorMessage').text("");
                                        $('#successMessage').text(response['message']);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.log(error);
                                }
                            });
                        }
                    }
                } else {
                    $('#errorMessage').text("please select an Order ID");
                }


            }


        });


    </script>
@endsection



