@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')

        <!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="/assets/css/select2.css"/>
<link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap.css"/>
<link rel="stylesheet" href="/assets/css/toastr/toastr.css"/>
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/default.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/profile.css" rel="stylesheet" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->
<style>
    #myPopoverContent {
        display: none;
        float: right;
    }

    .popover-content > span {
        font-family: segoe UI;
        font-size: 13px;
    }

    .popover {
        width: 700px;
    }

    .modal-dialog {
        z-index: 9999 !important;
    }

    #datatable_length {
        margin-top: 2%;
    }

    #datatable_length label {
        display: inline-flex;
    }
</style>

<link rel="shortcut icon" href="favicon.ico"/>

@endsection
@section('classMarket','active')
@section('classMarket2','active')
@section('content')
{{--PAGE CONTENT GOES HERE--}}
        <!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE HEAD -->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Order History</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD -->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="/user/dashboard">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="javascript:;">Market</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/user/orderHistory">Order History</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Record Listing</span>
                            <span class="caption-helper">manage records...</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success" class="alert-message-window" hidden>
                                <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                <span class="alert-message">order</span></div>
                        </div>


                        <div class="alert alert-success " id="messageArea" hidden>
                            <button class="close" data-close="alert"></button>
                            <span class="alert-message-content" style="text-align: center;"></span>
                        </div>
                    </div>


                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper">
										<span>
									</span>
                                <select class="table-group-action-input form-control input-inline input-small input-sm">
                                    <option value="">Select Action</option>
                                    <option value="cancel_order">Cancel selected Order(s)</option>
                                    <option value="reAdd_order">Re-Add selected Order(s)</option>
                                </select>
                                <button class="btn btn-sm yellow table-group-action-submit"><i
                                            class="fa fa-check"></i> Submit
                                </button>
                            </div>

                            <div id="myPopoverContent">
                                <label>Product :</label> <span>Instagram - Followers (Normal)</span>
                                <br/>
                                <label>Amount Limit :</label> <span>1000</span>
                                <br/>
                                <label>Amount Done :</label> <span>1000</span>
                                <br/>
                                <label>Amount per Run :</label> <span>100</span>
                                <br/>
                                <label>Message :</label> <span>This area is for some messages which has to be shown for the orders.</span>
                            </div>

                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                <tr role="row" class="heading">
                                    <th width="1%"><input type="checkbox" class="group-checkable"></th>
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
                                <tr role="row" class="filter">
                                    <td></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_order_id"></td>
                                    <td class='insta-link'>

                                        <select name="search_service_type"
                                                class="form-control form-filter input-sm">
                                            <option value="">Select...</option>
                                            @if(isset($plansList))
                                                @foreach($plansList as $plan)
                                                    <option value="{{$plan['plan_name']}}">{{$plan['plan_name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_link"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_amount"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_price" disabled="disabled"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_time_added" disabled="disabled"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_time_updated" disabled="disabled"></td>
                                    <td><select name="search_status" class="form-control form-filter input-sm">
                                            <option value="">Select...</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Queue</option>
                                            <option value="2">Processing</option>
                                            <option value="3">Completed</option>
                                            <option value="5">Refunded</option>
                                            <option value="6">Canceled</option>
                                        </select></td>
                                    <td class=''>
                                        <button class="btn btn-xs default filter-submit margin-bottom"><i
                                                    class="fa fa-search"></i> Search
                                        </button>
                                        <button class="btn btn-xs red filter-cancel"><i class="fa fa-times"></i>
                                            Reset
                                        </button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>

<!--Modal for edit order-->
<div id="editOrder" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="caption">
                    <h4 class="modal-title"><i class="fa fa-pencil font-green-sharp"></i>
                        <span class="caption-subject font-green-sharp bold uppercase">EDIT YOUR ORDER</span>
                        <span class="caption-helper">&nbsp;Editing order with order ID #<span
                                    id="edit_orderID"></span> </span>
                    </h4>
                </div>


            </div>
            <div class="modal-body">
                <form class="form" role="form" id="edit_addAutoOrderForm">
                    <div class="form-group floating-label">
                        <input type="text" class="form-control " name="edit_orderLink" id="edit_orderLink"
                               placeholder=""/>
                    </div>

                    <p>If you wish you cancel this order you should select it on the checkbox , click "Select
                        Actions" , choose the option "Cancel" & Click "Submit" :)</p>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-flat btn-ripple" data-dismiss="modal"
                                id="edit_cancelButton"> Close
                        </button>
                        <button type="submit" class="btn btn-success btn-flat btn-ripple" id="edit_submitButton">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--Modal for details-->
<div id="showDetails" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Information</h4>
            </div>
            <div class="modal-body">
                <label><b>Service :</b></label>&nbsp; <span id="details-planName"></span>
                <br/>
                <label><b>Start / Current Count :</b></label>&nbsp; <span id="details-start-count">1000</span>/<span
                        id="details-current-count">1000</span>
                <br/>
                <label><b>Remain / Finish Count :</b></label>&nbsp; <span id="details-remain-count"> 1000</span>/<span
                        id="details-finish-count"> 1000</span>
                <br/>
                <label><b>Message :</b></label>&nbsp; <span id="details-message">This area is for some messages which has to be shown for the orders.</span>
            </div>
            <div class="modal-footer">
                <button type="button" id="details-close-btn" class="btn btn-danger btn-flat btn-ripple"
                        data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- END CONTENT -->

@endsection

@section('pagejavascripts')


        <!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/js/select2.min.js"></script>
<script src="/assets/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/dataTables.bootstrap.js"></script>
<script src="/assets/js/bootstrap-datepicker.js"></script>
<script src="/assets/js/toastr/toastr.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<script src="/assets/js/datatable.js"></script>
{{--<script src="/assets/js/table-ajax.js"></script>--}}
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
        TableAjax.init();
    });
</script>

<script>
    $(function () {
        console.log("data2");
        //DataTable
        $('#datatable').dataTable();
        $('#datatable_filter input, #datatable_length select').addClass('form-control');
        $('#datatable_length').addClass('form-group');
    });
</script>
<script>
    $(function () {
        console.log("data3");
        $('.text').popover({
            content: $('#myPopoverContent').html(),
            html: true
        }).mouseover(function () {
//            alert("mouse over");
            $(this).popover('show');
        }).mouseleave(function () {
//            alert("mouse leaves");
            $(this).popover('hide');
        });
    });
</script>

<!--BEGIN CUSTOM PAGE LEVEL SCRIPT-->
<script type="text/javascript">
    toastr.options.positionClass = "toast-top-center";
    toastr.options.preventDuplicates = true;
    toastr.options.closeButton = true;


    var TableAjax = function () {
        var handleRecords = function () {

            var grid = new Datatable();
            grid.init({
                src: $("#datatable_ajax"),
                onSuccess: function (grid) {
                    // execute some code after table records loaded
                },
                onError: function (grid) {
                    // execute some code on network or other general error
                },
                onDataLoad: function (grid) {
                    // execute some code on ajax data load
                },
                loadingMessage: 'Loading...',
                dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                    // So when dropdowns used the scrollable div should be removed.
                    //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                    "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                    "lengthMenu": [
                        [10, 20, 50, 100, 150, -1],
                        [10, 20, 50, 100, 150, "All"] // change per page values here
                    ],
                    "pageLength": 10, // default record count per page
                    "ajax": {
                        "url": "/user/orderHistoryAjax" // ajax source
                    },

                    "columnDefs": [
                        {orderable: false, targets: 0},
                        {orderable: false, targets: -1}
                    ],

                    "order": [[1, "desc"]]
                    // set first column as a default sort by asc
                }
            });

            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
                e.preventDefault();
                var action = $(".table-group-action-input", grid.getTableWrapper());
                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    grid.setAjaxParam("customActionType", "group_action");
                    grid.setAjaxParam("customActionName", action.val());
                    grid.setAjaxParam("orderId", grid.getSelectedRows());
                    grid.getDataTable().ajax.reload();
                    grid.clearAjaxParams();
                } else if (action.val() == "") {
                    InstaPanel.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Please select an action',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                } else if (grid.getSelectedRowsCount() === 0) {
                    InstaPanel.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'No record selected',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                }
            });
        };
        return {
            //main function to initiate the module
            init: function () {
                handleRecords();
            }
        };
    }();


    var orderId = '';
    $(document.body).on('click', '.edit-order', function (e) {
        e.preventDefault();
        var orderLink = $(this).closest("tr").find('td:eq(3)').text();
        $('#edit_orderLink').val(orderLink);
        $('#edit_orderID').text($(this).closest("tr").find('td:eq(1)').text());
        orderId = $(this).closest("tr").find('td:eq(1)').text();
    });

    $(document.body).on('click', '#edit_submitButton', function (e) {
        e.preventDefault();
        var orderLink = $('#edit_orderLink').val();
        var order_id = orderId;

        if (orderLink == null || orderLink == '') {
            toastr.error('Please enter valid Instagram Url', {timeOut: 4000});
            $('#edit_orderLink').focus();
            return false;
        }
        $.ajax({
            url: "/user/editOrder",
            type: "POST",
            dataType: "json",
            data: {
                orderId: order_id,
                orderLink: orderLink
            },
            success: function (response) {
                console.log(response);
                if (response['status'] == 'success') {
                    toastr.success(response['message'], {timeOut: 4000});
                    $("#edit_cancelButton").trigger("click");
//                        $('.alert-message').text(response['message']);
//                        $('.alert-message-window').show();

//                        location.reload();
                } else if (response['status'] == 'fail') {
                    toastr.error(response['message'], {timeOut: 4000});
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });

    });

    $(document.body).on('click', '.show-details', function (e) {
        e.preventDefault();
        orderId = $(this).closest("tr").find('td:eq(1)').text();
        console.log(orderId);
        $.ajax({
            url: "/user/getMoreOrderDetails",
            type: "POST",
            dataType: "json",
            data: {
                orderId: orderId
            },
            success: function (response) {
                console.log(response);

                if (response['status'] == 'success') {
                    $('#details-planName').text('');
                    $('#details-start-count').text('');
                    $('#details-current-count').text('');
                    $('#details-remain-count').text('');
                    $('#details-finishCount-count').text('');
                    $('#details-message').text('');


                    $('#details-planName').text(response['data']['planName']);
                    $('#details-start-count').text(response['data']['startCount']);
                    $('#details-current-count').text(response['data']['currentCount']);
                    $('#details-remain-count').text(response['data']['remainCount']);
                    $('#details-finish-count').text(response['data']['finishCount']);
                    $('#details-message').text(response['data']['message']);
                }
                else {
                    $('#details-planName').text('');
                    $('#details-start-count').text('');
                    $('#details-current-count').text('');
                    $('#details-remain-count').text('');
                    $('#details-finishCount-count').text('');
                    $('#details-message').text('');
                    $('#messageArea').show();
                    $('.alert-message-content').text(response['message']);
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    });

</script>
<!--END CUSTOM PAGE LEVEL SCRIPT-->


@endsection



