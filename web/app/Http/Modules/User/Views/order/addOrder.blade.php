@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')

        <!-- BEGIN PAGE LEVEL STYLES -->
<link href="/assets/css/dataTables.bootstrap.css" rel="stylesheet"/>
<link href="/assets/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="/assets/css/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<link href="/assets/css/toastr/toastr.css" rel="stylesheet"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
<link href="/assets/css/plugins-md.css" rel="stylesheet"/>
<link href="/assets/css/layout.css" rel="stylesheet"/>
<link href="/assets/css/default.css" rel="stylesheet" id="style_color"/>
<link href="/assets/css/profile.css" rel="stylesheet"/>
<link href="/assets/css/custom.css" rel="stylesheet"/>
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

    .profile-pic img {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        border: 10px solid #f1f2f7;
        margin-top: 20px;
    }

    .error {
        color: red;
    }

    .valid {
        color: green;
    }
</style>

<link rel="shortcut icon" href="favicon.ico"/>
@endsection
@section('classMarket','active')
@section('classMarket1','active')
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
                <h1>Add Order</h1>
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
                <a href="/user/addOrder">Add Order</a>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Add Order</span>
                            <span class="caption-helper">manage your orders...</span>
                        </div>
                    </div>

                    @if (count($errors) > 0)
                        <div class="alert alert-danger ">
                            <button class="close" data-close="alert"></button>
                            <p>Please Correct the following Error(s) : </p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('errorMessage'))
                        <div class="alert alert-danger ">
                            <button class="close" data-close="alert"></button>
                            <h5 style="text-align: center;"> <?php echo session('errorMessage'); ?></h5>
                        </div>
                    @endif

                    @if(session('successMessage'))
                        <div class="alert alert-success ">
                            <button class="close" data-close="alert"></button>
                            <h5 style="text-align: center;"> <?php echo session('successMessage'); ?></h5>
                        </div>
                    @endif
                    @if(isset($successMessage))
                        <div class="alert alert-success ">
                            <button class="close" data-close="alert"></button>
                            <h5 style="text-align: center;">   {{$successMessage}}</h5>
                        </div>
                    @endif

                    <div class="portlet-body">
                        <div class="tabs-left row">
                            <div class="col-md-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-12">
                                        <div class="panel panel-default panel-divider">
                                            <div class="panel-heading">
                                                <header> Add new Order(s) &nbsp;&nbsp;
                                                    {{--<small><a href="javascript:;"> Single Order </a> | <a href="javascript:;"> Bulk Orders </a></small> --}}
                                                </header>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form class="" action="/user/addOrder" method="post"
                                                              id="addOrderForm"
                                                              role="form">

                                                            <div class="form-group ">
                                                                <label class="control-label">Choose Category</label>
                                                                <select class="js-example-responsive form-control"
                                                                        name="plan_type_id" id="plan_type_id" required>
                                                                    <option value="0" selected="selected"> Instagram
                                                                        likes
                                                                    </option>
                                                                    <option value="1"> Instagram followers</option>
                                                                    <option value="2"> Instagram comments</option>
                                                                    <option value="4"> Instagram views</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group ">
                                                                <label class="control-label">Choose Type </label>
                                                                <select class="js-example-responsive form-control"
                                                                        name="service_type_id" id="service_type_id"
                                                                        required>
                                                                    <option value="R" selected="selected"> Real</option>
                                                                    <option value="F"> Fake</option>
                                                                    <option value="T"> Targetted</option>
                                                                </select>
                                                            </div>

                                                            <div id="spinner" style="margin-left: 45%" hidden>
                                                                <img src="/assets/images/input-spinner.gif"
                                                                     alt="Loading"/>
                                                            </div>

                                                            <div class="form-group ">
                                                                <label class="control-label">Choose Service</label>
                                                                <select class="js-example-responsive form-control"
                                                                        name="plan_id"
                                                                        id="plan_id"
                                                                        required>
                                                                    <option value="" selected="selected">Please select a
                                                                        Service
                                                                    </option>
                                                                    @if(isset($data))
                                                                        @foreach($data as $plan)
                                                                            <option value="{{$plan['plan_id']}}"
                                                                                    data-planType="{{$plan['plan_type']}}"
                                                                                    data-supplierServerId="{{$plan['supplier_server_id']}}"
                                                                                    data-minQuantity="{{$plan['min_quantity']}}"
                                                                                    data-maxQuantity="{{$plan['max_quantity']}}"
                                                                                    data-chargePer1K="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>
                                                                        @endforeach

                                                                    @endif
                                                                </select>
                                                            </div>

                                                            {{--<div class="form-group ">--}}
                                                            {{--<label class="control-label">Choose Service</label>--}}
                                                            {{--<select class="js-example-responsive form-control"--}}
                                                            {{--name="plan_id"--}}
                                                            {{--id="plan_id"--}}
                                                            {{--required>--}}

                                                            {{--<option value="" selected>Please select a--}}
                                                            {{--Service--}}
                                                            {{--</option>--}}
                                                            {{--@if(isset($data))--}}
                                                            {{--@foreach($data as $plan)--}}
                                                            {{--<option value="{{$plan['plan_id']}}"--}}
                                                            {{--data-planType="{{$plan['plan_type']}}"--}}
                                                            {{--data-supplierServerId="{{$plan['supplier_server_id']}}"--}}
                                                            {{--data-minQuantity="{{$plan['min_quantity']}}"--}}
                                                            {{--data-maxQuantity="{{$plan['max_quantity']}}"--}}
                                                            {{--data-chargePer1K="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>--}}
                                                            {{--@endforeach--}}

                                                            {{--@endif--}}
                                                            {{--</select>--}}
                                                            {{--</div>--}}

                                                            <div class="form-group " id="spreadOrdersOption"
                                                                 hidden>

                                                                <input type="checkbox" name="spreadOrders"
                                                                       id="spreadOrders"
                                                                        > &nbsp;&nbsp;
                                                                <label class="control-label">I want it spreaded
                                                                    between my pictures</label>

                                                                <div class="clearfix"></div>
                                                                <div class="form-group  col-md-6 spreadOrderBetween"
                                                                     hidden>
                                                                    <input type="number" class="form-control"
                                                                           name="startSpreadIndex"
                                                                           id="startSpreadIndex" required
                                                                           placeholder="Enter Start Pics">
                                                                </div>
                                                                <div class="form-group  col-md-6 spreadOrderBetween"
                                                                     hidden>
                                                                    <input type="number"
                                                                           class="col-md-6 form-control"
                                                                           name="endSpreadIndex"
                                                                           id="endSpreadIndex" required
                                                                           placeholder="Enter End Pics"/>
                                                                </div>
                                                            </div>
                                                            <div class="form-group m-t-10">
                                                                <label class="control-label">Order Link</label>
                                                                <input type="url" class="form-control"
                                                                       name="order_url" id="order_url"
                                                                       required value="{{old('order_url')}}"
                                                                       placeholder="Enter Post URL ( https://www.instagram.com/p/vrTV-bAp9E/"/>
                                                                <span id="order_url_error"></span>
                                                            </div>

                                                            <div class="form-group" id="commentOptionArea"
                                                                 hidden>

                                                                <div id="customCommentRadioButton" hidden>
                                                                    <input type="radio" name="customCommentType"
                                                                           id="writeComments" class="form-control"
                                                                           value="0" checked>
                                                                    <label class="control-label" for="writeComments"
                                                                           style="margin-left: 1%;"> Write comments into
                                                                        a text area</label>
                                                                    <input type="radio" name="customCommentType"
                                                                           id="selectPreMadeComments"
                                                                           class="form-control"
                                                                           value="1" style="margin-left: 1.5%;">
                                                                    <label class="control-label"
                                                                           for="selectPreMadeComments"
                                                                           style="margin-left: 1%;"> Select
                                                                        Comments</label>
                                                                </div>

                                                                <div class="form-group "
                                                                     id="customCommentTextArea" hidden>
                                                                    <label class="control-label">Comments( 1
                                                                        comment per line , click out of
                                                                        the
                                                                        box to check the amount and estimate
                                                                        price )</label>
                                            <textarea class="form-control" name="commentsTextArea" id="commentsTextArea"
                                                      rows="6"></textarea>
                                                                    <span id="commentsTextArea_error"></span>
                                                                </div>

                                                                <div class="" id="selectPreMadeCommentsArea"
                                                                     hidden>

                                                                    <label class="control-label"
                                                                           for="comment_group_id"
                                                                           style="margin-top: 1%; text-align: right;">Please
                                                                        Select a
                                                                        Comment Group</label>

                                                                    <div class=" form-group ">
                                                                        <select class="form-control"
                                                                                name="comment_group_id"
                                                                                id="comment_group_id">
                                                                            @if(isset($commentsGroupListData))
                                                                                @foreach($commentsGroupListData as $list)
                                                                                    <option value="{{$list['comment_group_id']}}">{{$list['comment_group_name']}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>&nbsp;&nbsp;<span
                                                                                id="comment_group_id_error"></span><br>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group ">
                                                                <label class="control-label">Amount to
                                                                    Delivery</label>
                                                                <input type="number" class="form-control"
                                                                       name="quantity" id="quantity"
                                                                       required
                                                                       placeholder="Amount of Likes,Followers you want in that link"/>
                                                                <span id="quantity_error"></span>
                                                            </div>


                                                            <div class="form-group" id="schedule_time_option">
                                                                <input type="checkbox" name="starting_time_option"
                                                                       id="starting_time_option"
                                                                       class="form-control"> &nbsp;&nbsp;
                                                                <label class="control-label">I want to use the schedule
                                                                    option <span style="font-size: 13px">(Order will be process after schedule time)</span>.</label>
                                                            </div>

                                                            <div class="form-group schedule_time_option_area" hidden>
                                                                {{--<label class="control-label">Schedule Starting--}}
                                                                {{--Time</label>--}}

                                                                <div class="">
                                                                    <div class="input-group date form_datetime">
                                                                        <input type="text" size="16" readonly
                                                                               class="form-control"
                                                                               name="starting_time" id="starting_time"
                                                                               placeholder="Please select scheduling Time">
                                                                            <span class="input-group-btn"> <button
                                                                                        class="btn default date-set"
                                                                                        type="button"><i
                                                                                            class="fa fa-calendar"></i>
                                                                                </button> </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group" id="splitTotalAmount">
                                                                <input type="checkbox" name="splitTotalAmounts"
                                                                       id="splitTotalAmounts"
                                                                       class="form-control"> &nbsp;&nbsp;
                                                                <label class="control-label">I want to split the
                                                                    Likes/Followers/Comments
                                                                    amount to delivery</label>
                                                            </div>
                                                            <div class="splitAmountArea" hidden>
                                                                <div class="form-group">
                                                                    <label class="control-label">Amount to
                                                                        delivery per run</label>
                                                                    <input type="number" class="form-control"
                                                                           name="ordersPerRun"
                                                                           id="ordersPerRun" required
                                                                           value="{{old('ordersPerRun')}}"
                                                                           placeholder="Every run"/>
                                                                    <span id="ordersPerRun"></span>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">Deliver
                                                                        every</label>
                                                                    <select class="js-example-responsive form-control"
                                                                            name="timeInterval"
                                                                            required
                                                                            id="timeInterval">
                                                                        <option value="" selected disabled>-
                                                                            Choose timer -
                                                                        </option>
                                                                        <option value="600">10 Minutes</option>
                                                                        <option value="1200">20 Minutes</option>
                                                                        <option value="1800">30 Minutes</option>
                                                                        <option value="3600">1 Hour</option>
                                                                        <option value="7200">2 Hours</option>
                                                                        <option value="10800">3 Hours</option>
                                                                        <option value="14400">4 Hours</option>
                                                                        <option value="28800">8 Hours</option>
                                                                        <option value="43200">12 Hours</option>
                                                                        <option value="86400">24 Hours (1 Day
                                                                            )
                                                                        </option>
                                                                        <option value="172800">48 Hours (2 Days
                                                                            )
                                                                        </option>
                                                                        <option value="259200">72 Hours (3 Days
                                                                            )
                                                                        </option>
                                                                    </select>
                                                                    <span id="error_timeInterval"></span>
                                                                </div>
                                                            </div>

                                                            <p class="text-muted">
                                                                <small>By placing this order you agree to our
                                                                    Terms of Service and Refund
                                                                    Policy and you understand and agree that
                                                                    this action cannot be canceled
                                                                    once started or initiated. Thanks for your
                                                                    order :)
                                                                </small>
                                                            </p>
                                                            <div class="form-group ">
                                                                <button class="btn btn-success btn-raised"
                                                                        type="submit"
                                                                        id="submit_order">
                                                                    <i
                                                                            class="fa fa-arrow-circle-right"></i>
                                                                    Place Order
                                                                </button>
                                                                <button class="btn default btn-raised"
                                                                        style="margin-left:1%;" type="button"
                                                                        name="reset_order"
                                                                        id="reset">Reset
                                                                </button>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="panel panel-default panel-divider">
                                            <div class="panel-heading">
                                                <header> Order Resume</header>
                                            </div>
                                            <div class="panel-body" style="padding-top: 0; padding-bottom:0;">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table table-hover table-light">
                                                            <tbody>
                                                            <tr>
                                                                <td>
                                                                    <strong>Order Cost ($)</strong>
                                                                </td>
                                                                <td align="right" id="order_total"
                                                                    style="vertical-align: middle;">-
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Price per 1K</td>
                                                                <td align="right" id="price">-</td>
                                                            </tr>

                                                            {{--<tr>--}}
                                                            {{--<td>Delivery time</td>--}}
                                                            {{--<td align="right" id="order_overall_amount">-</td>--}}
                                                            {{--</tr>--}}

                                                            {{--<tr>--}}
                                                            {{--<td>Delivery for 1K</td>--}}
                                                            {{--<td align="right" id="order_overall_time">-</td>--}}
                                                            {{--</tr>--}}
                                                            <tr>
                                                                <td>Status</td>
                                                                <td align="right" id="status">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Min. Order</td>
                                                                <td align="right" id="min_order">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Max. Order</td>
                                                                <td align="right" id="max_order">-</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Current Balance</td>
                                                                <td align="right" id="current_balance">
                                                                    $ <span class="account_bal">
                                                    @if(isset(Session::get('ig_user')['account_bal']))
                                                                            {{Session::get('ig_user')['account_bal']}}
                                                                        @else
                                                                            0.0000
                                                                        @endif
                                                  </span>
                                                                </td>


                                                            </tr>
                                                            {{--<tr>--}}
                                                            {{--<td>Pricing &amp; Info</td>--}}
                                                            {{--<td align="right"><a href="#">Click here</a>--}}
                                                            {{--</td>--}}
                                                            {{--</tr>--}}
                                                            </tbody>
                                                        </table>
                                                        <p class="text-muted">
                                                            <small>Delivery time can go up and
                                                                down depending on the orders volume in real
                                                                time. All orders are subject to
                                                                terms of service delivery times.
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="panel panel-default panel-divider">
                                            <div class="panel-heading">
                                                <header> URL info</header>
                                            </div>
                                            <div class="panel-body" style="padding-top: 0; padding-bottom:0;">
                                                <div class="row" style="padding-top: 10px; padding-bottom:10px;">
                                                    <div class="form-group input-group-sm col-md-12 col-sm-12 col-xs-12 m-bot15">
                                                        <div class="profile-pic text-center">
                                                            <img id="AvatarPic" src="/images/url_avatar.png">
                                                        </div>
                                                        <span class="alert-success" id="URLLabel"
                                                              style="width:100% !important; font-size:10pt; cursor:default; text-align:left;"></span><br>
                                                        <span class="alert-default" id="URLInfo"
                                                              style="width:100% !important; font-size:10pt; cursor:default; text-align:left;"></span><br>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->

@endsection

@section('pagejavascripts')
        <!-- BEGIN PAGE LEVEL PLUGINS -->
{{--<script src="/assets/js/dataTables.bootstrap.js"></script>--}}
<script src="/assets/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/datatable.js"></script>
<script src="/assets/js/datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script src="/assets/js/toastr/toastr.js"></script>
<script src="/assets/js/validate/jquery.validate.js"></script>
<script type="text/javascript" src="/assets/js/jstz.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<script src="/assets/js/datetimepicker/components-pickers.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
        ComponentsPickers.init();
    });

</script>
<script>
    //DataTable
    $('#datatable').dataTable();
    $(window).load(function () {
        $('#datatable_filter input, #datatable_length select').addClass('form-control');
        $('#datatable_length').addClass('form-group');
    });

</script>
<!--BEGIN CUSTOM PAGE LEVEL SCRIPT-->
<script type="text/javascript">
    toastr.options.positionClass = "toast-top-center";
    toastr.options.preventDuplicates = true;
    toastr.options.closeButton = true;

    $("#plan_type_id").change(function () {
        $('#spreadOrdersOption').hide();
        $('#commentOptionArea').hide();
        $('#customCommentRadioButton').hide();
        $('#customCommentTextArea').hide();

        var plan_type_id = $("#plan_type_id").children("option").filter(":selected").val();
        var service_type_id = $("#service_type_id").children("option").filter(":selected").val();

        if (plan_type_id == 0 || plan_type_id == 2 || plan_type_id == 4) {
            $('#order_url').attr('placeholder', 'Enter Post URL ( https://www.instagram.com/p/vrTV-bAp9E/ )');
        } else if (plan_type_id == 1) {
            $('#order_url').attr('placeholder', 'Enter Profile or Post URL');
        }

        $.ajax({
            beforeSend: function () {
                $('#spinner').show();
            },
            complete: function () {
                $('#spinner').hide();
            },
            url: "/user/getFilterPlanList",
            type: "POST",
            dataType: "json",
            data: {
                plan_type_id: plan_type_id,
                service_type_id: service_type_id
            },
            success: function (response) {
//                console.log(response);
                if (response['status'] == 'success') {
                    var data = response['data'];
                    var html = "<option value='' selected>Please select a Service </option>";

                    $.each(data, function (index, value) {
                        html += "<option value='" + value['plan_id'] + "' data-planType='" + value['plan_type'] + "'" +
                                "data-supplierServerId='" + value['supplier_server_id'] + "'  data-minQuantity='" + value['min_quantity'] + "'" +
                                "data-maxQuantity='" + value['max_quantity'] + "' data-chargePer1K='" + value['charge_per_unit'] + "' >" + value['plan_name'] + "</option>";

                    });
                    $("#plan_id").html(html);

                } else if (response['status'] == 'emptyList') {
                    console.log(response['message']);
                    var html = "<option value='' selected>" + response['message'] + " </option>";
                    $("#plan_id").html(html);
                } else if (response['status'] == 'fail') {
                    console.log(response['message']);
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });

    });

    $("#service_type_id").change(function () {

        $('#spreadOrdersOption').hide();
        $('#commentOptionArea').hide();
        $('#customCommentRadioButton').hide();
        $('#customCommentTextArea').hide();

        var plan_type_id = $("#plan_type_id").children("option").filter(":selected").val();
        var service_type_id = $("#service_type_id").children("option").filter(":selected").val();

        $.ajax({
            beforeSend: function () {
                $('#spinner').show();
            },
            complete: function () {
                $('#spinner').hide();
            },
            url: "/user/getFilterPlanList",
            type: "POST",
            dataType: "json",
            data: {
                plan_type_id: plan_type_id,
                service_type_id: service_type_id
            },
            success: function (response) {
//                console.log(response);
                if (response['status'] == 'success') {
                    var data = response['data'];
                    var html = "<option value='' selected>Please select a Service </option>";

                    $.each(data, function (index, value) {
                        html += "<option value='" + value['plan_id'] + "' data-planType='" + value['plan_type'] + "'" +
                                "data-supplierServerId='" + value['supplier_server_id'] + "'  data-minQuantity='" + value['min_quantity'] + "'" +
                                "data-maxQuantity='" + value['max_quantity'] + "' data-chargePer1K='" + value['charge_per_unit'] + "' >" + value['plan_name'] + "</option>";

                    });
                    $("#plan_id").html(html);

                } else if (response['status'] == 'emptyList') {
                    console.log(response['message']);
                    var html = "<option value='' selected>" + response['message'] + " </option>";
                    $("#plan_id").html(html);
                } else if (response['status'] == 'fail') {
                    console.log(response['message']);
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });

    });

    $('#plan_id').change(function (e) {
        e.preventDefault();

        var selectedPlan = $('#plan_id option:selected');
        if (selectedPlan.attr('data-planType') == 0) {
            $('#spreadOrders').checked = false;
            $('#spreadOrdersOption').show();
        } else {
            $('#spreadOrdersOption').hide();
        }


        if (selectedPlan.attr('data-supplierServerId') == 1 && selectedPlan.attr('data-planType') == 3) {
            $('#commentOptionArea').show();
            $('#customCommentRadioButton').show();
            $('#customCommentTextArea').show();
        } else {
            $('#commentOptionArea').hide();
            $('#customCommentRadioButton').hide();
            $('#customCommentTextArea').hide();
        }


//            $('#quantity').attr({'min': selectedPlan.attr('data-minQuantity')});
//            $('#quantity').attr({'max': selectedPlan.attr('data-maxQuantity')});
        $('#quantity').attr('data-min', selectedPlan.attr('data-minQuantity'));
        $('#quantity').attr('data-max', selectedPlan.attr('data-maxQuantity'));

        $('#order_total').text('0');
        $('#price').text(selectedPlan.attr('data-chargePer1K'));
        $('#delivery_for1K').text('0');
        $('#status').text('0');
        $('#min_order').text(selectedPlan.attr('data-minQuantity'));
        $('#max_order').text(selectedPlan.attr('data-maxQuantity'));
    });

    $(document.body).on('keypress', '#quantity', function (e) {
        return validateNumber(e);
    });

    $(document.body).on('change click', '#quantity', function (e) {
        e.preventDefault();
        var quantity = $('#quantity').val();
        var totalOrder = ($('#plan_id option:selected').attr('data-chargePer1K') / 1000) * quantity;
        $('#order_total').text(totalOrder.toFixed(4));
    });


    $('#spreadOrders').change(function (e) {
        e.preventDefault();
        if ($(this).is(":checked")) {
            $('#startSpreadIndex').val('');
            $('.spreadOrderBetween').show();
            $('#endSpreadIndex').val('');
            $('#order_url').attr('placeholder', 'Enter Profile URL ( https://www.instagram.com/username/ )');
        } else {
            $('#startSpreadIndex').val('');
            $('#endSpreadIndex').val('');
            $('.spreadOrderBetween').hide();
            $('#order_url').attr('placeholder', 'Enter Post URL ( https://www.instagram.com/p/vrTV-bAp9E/ )');
            $('#min_order').text($('#plan_id option:selected').attr('data-minQuantity'));
        }
    });

    $(document.body).on('change', '#endSpreadIndex', function (e) {
        e.preventDefault();
        if ($('#spreadOrders').is(":checked")) {
            if ((parseInt($("#startSpreadIndex").val()) > 0) && (parseInt($(this).val()) > 0)) {
                if (parseInt($("#startSpreadIndex").val()) <= parseInt($(this).val())) {
                    var minMultiply = parseInt($(this).val()) - parseInt($("#startSpreadIndex").val()) + 1;
                    var minQuantity = parseInt($("#plan_id").children("option").filter(":selected").attr('data-minQuantity'));
                    $('#min_order').text(minQuantity * minMultiply);
                }
            }
        }
    });
    $(document.body).on('keypress', '#endSpreadIndex', function (e) {
        return validateNumber(e);
    });

    $(document.body).on('change', '#startSpreadIndex', function (e) {
        e.preventDefault();
        if ($('#spreadOrders').is(":checked")) {
            if ((parseInt($(this).val()) > 0) && (parseInt($("#endSpreadIndex").val()) > 0)) {
                if (parseInt($(this).val()) <= parseInt($("#endSpreadIndex").val())) {
                    var minMultiply = parseInt($("#endSpreadIndex").val()) - parseInt($(this).val()) + 1;
                    var minQuantity = parseInt($("#plan_id").children("option").filter(":selected").attr('data-minQuantity'));
                    $('#min_order').text(minQuantity * minMultiply);
                }
            }
        }
    });
    $(document.body).on('keypress', '#startSpreadIndex', function (e) {
        return validateNumber(e);
    });

    $(document.body).on('keyup', '#endSpreadIndex', function (e) {
        if ($(this).val() > 12) {
            alert("Post Limited to latest 12 posts only. We are working on it , Soon we will come up with spread opt feature with more no. of posts");
            return false;
        }
    });


    $('#commentType').change(function (e) {
        e.preventDefault();
        if ($('#commentType option:selected').attr('value') == 0) {
            $('#customCommentRadioButton').hide();
            $('#customCommentTextArea').hide();
            $('#selectPreMadeCommentsArea').hide();
//                $('#quantity').attr({'min': $('#plan_id option:selected').attr('data-minQuantity')});
            $('#quantity').attr('data-min', $('#plan_id option:selected').attr('data-minQuantity'));
        }
        else {
            $('#customCommentRadioButton').show();
            if ($('#selectPreMadeComments').is(":checked")) {
                $('#selectPreMadeComments').removeAttr("checked");
                $('#writeComments').prop("checked", true);
            } else {
                $('#writeComments').prop("checked", true);
            }
            $('#customCommentTextArea').show();
        }
    });

    $('#writeComments').change(function (e) {
        e.preventDefault();
        if ($(this).is(":checked")) {
            $('#selectPreMadeComments').removeAttr("checked");
        }
        $('#customCommentTextArea').show();
        $('#selectPreMadeCommentsArea').hide();
    });

    $('#selectPreMadeComments').change(function (e) {
        e.preventDefault();
        if ($(this).is(":checked")) {
            $('#writeComments').removeAttr("checked");
        }
        $('#selectPreMadeCommentsArea').show();
        $('#customCommentTextArea').hide();
//            $('#quantity').attr({'min': $('#plan_id option:selected').attr('data-minQuantity')});
        $('#quantity').attr('data-min', $('#plan_id option:selected').attr('data-minQuantity'));
    });

    $(document.body).on('keypress', '#ordersPerRun', function (e) {
        return validateNumber(e);
    });

    $.validator.addMethod('greaterThan', function (value, element, param) {
        var startSpreadIndex = parseInt($(param).val());
        var endSpreadIndex = parseInt(value);
        var minQuantity = $("#plan_id").children("option").filter(":selected").attr('data-minQuantity');
//        console.log(startSpreadIndex);
//        console.log(endSpreadIndex);
        if (endSpreadIndex < startSpreadIndex) {
            $('#min_order').text(minQuantity);
            return false;
        }
        else {
            var minOrder = parseInt((endSpreadIndex - startSpreadIndex + 1) * parseInt(minQuantity));
//            console.log(minOrder);
            $('#min_order').text(minOrder);
//            $('#max_order').text('-');
            return true;
        }

    }, "Value of End Pic Index must be greater value of Start Pic Index");

    $.validator.addMethod("fivelines", function (value, element) {
        var rows = 1;
        var rowvalues = value.split("\n");
        //rows = value.split("\n").length;

        var lineCount = 0;
        $.each(rowvalues, function (i, a) {
            if (a.length != 0)
                lineCount++;
        });
//        console.log(lineCount);
        $('#quantity').val(lineCount);
        $('#quantity').removeAttr('min');

        if (lineCount < 5) {
            return false;
        } else {
            return true;
        }
    }, "Please Write atleast 5 Comments (1 comment per line)");

    $.validator.addMethod("fivechars", function (value, element) {
        var rowvalues = value.split("\n");
        var flag = true;
        $.each(rowvalues, function (i, a) {
            if (a.length < 5)
                flag = false;
        });
        if (!flag) {
            return false;
        } else {
            return true;
        }
    }, "Comments should be atleast 5 characters.");

    $.validator.addMethod("quantmin", function (value, element) {
        var min = parseInt($('#quantity').attr('data-min'));
        var max = parseInt($('#quantity').attr('data-max'));
        var minMulitplier = 1;
        if ($('#spreadOrders').is(':checked')) {
//            console.log("spread checked");
            minMulitplier = parseInt($('#endSpreadIndex').val() - $('#startSpreadIndex').val() + 1);
            $('#min_order').text(min * minMulitplier);
        }
//        console.log(minMulitplier);
        if ((parseInt(value) >= (min * minMulitplier)) && (parseInt(value) <= max)) {
//            console.log("true");
            return true;
        } else {
//            console.log("false");
            return false;
        }
    }, "Qunatity should be greater than min order qunatity and less than max order quantity.");

    $.validator.addMethod("validateURL", function (value, element) {

        // var pattern = /^(http|https)?:\/\/(instagram.com)\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/;
        var pattern = /^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/(([a-zA-Z0-9\.\_])*)+(([a-zA-Z0-9\_\.\-\=])*)/;///^(http(s)?:\/\/)?(www\.)?(instagram)\.+(com)+\/(([a-zA-Z0-9\.\_])*)+\/(([a-zA-Z0-9\_\.\-\=])*)/;
        var regex = new RegExp(pattern);
        var url = value;
        if (url.match(regex)) {
            return true;
        } else {
            return false;
        }
    }, "Please enter valid instagram URL");


    var minOrder = '';
    var getMinOrderMessage = function getMinOrderMessage() {
        return "This attribute value should be greater than min order quantity ( " + minOrder + " ) !";
    };
    $.validator.addMethod("minAmount", function (value, element) {
        var amountPerRun = parseInt($('#ordersPerRun').val());
        minOrder = parseInt($('#min_order').text());

        if (amountPerRun >= minOrder) {
            return true;
        } else {
            return false;
        }
    }, getMinOrderMessage);

    var maxOrder = '';
    var getMaxOrderMessage = function getMaxOrderMessage() {
        return "This attribute value should be less than Amount to delivery quantity ( " + maxOrder + " ) !";
    };
    $.validator.addMethod("maxAmount", function (value, element) {
        var amountPerRun = parseInt($('#ordersPerRun').val());
        maxOrder = parseInt($('#quantity').val());
        if (amountPerRun <= maxOrder) {
            return true;
        } else {
            return false;
        }
    }, getMaxOrderMessage);


    var minOrdersPerRun = '';
    var getOrdersPerRunMessage = function getOrdersPerRunMessage() {
//        console.log("test"+minOrdersPerRun);
        return "This attribute value should be greater than " + minOrdersPerRun + " (Max sub orders is 50)! ";
    };
    $.validator.addMethod("validateOrdersPerRun", function (value, element) {
        var amountPerRun = parseInt($('#ordersPerRun').val());
        var quantity = parseInt($('#quantity').val());

        minOrdersPerRun = Math.ceil(quantity / 50);

        if (amountPerRun < minOrdersPerRun) {
            return false;
        } else {
            return true;
        }
    }, getOrdersPerRunMessage);
    //    }, "This attribute value should be one-fourth of Amount to delivery quantity");

    $('#addOrderForm').validate({
        errorElement: 'span',
        rules: {
            plan_id: {required: true},
            startSpreadIndex: {
                required: true,
                digits: true
            },
            endSpreadIndex: {
                required: true,
                digits: true,
                greaterThan: '#startSpreadIndex'
            },
            order_url: {
                required: true,
                url: true,
                validateURL: true
            },
            quantity: {
                required: true,
                quantmin: true
            },
            commentType: {
                required: true
            },
            commentsTextArea: {
                required: true,
                fivechars: false,
                fivelines: true
            },
            starting_time: {required: true},
            ordersPerRun: {required: true, minAmount: true, maxAmount: true, validateOrdersPerRun: true},
            timeInterval: {required: true}

        },
        messages: {
            plan_id: {
                required: "Please Select a Service"
            },
            startSpreadIndex: {
                required: "Please enter a valid number",
                digits: "Please enter a valid number."
            },
            endSpreadIndex: {
                required: "Please enter a valid number",
                digits: "Please enter a valid number."
            },
            order: {
                required: "Please type Correct URL"
            },
            commentType: {
                required: "Please Choose a Service"
            },
            quantity: {
                required: "Please enter amount to delivery"
            },
            commentsTextArea: {
                required: "Please Write atleast 5 Comments (1 comment per line)"
            },
            starting_time: {
                required: "Please select schedule starting time"
            },
            ordersPerRun: {
                required: "Please enter a valid number",
                digits: "Please enter a valid number.",
            }
        }
    });

    $('#commentsTextArea').on('change click input keyup', function (e) {
        e.preventDefault();
        var value = $(this).val();
        var rowvalues = value.split("\n");
        var lineCount = 0;
        $.each(rowvalues, function (i, a) {
            if (a.length != 0)
                lineCount++;
        });
//            console.log(lineCount);
        $('#quantity').val(lineCount);

    });


    $('#splitTotalAmounts').change(function (e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
            $('#ordersPerRun').val('');
//            console.log();
            $('#timeInterval option:selected').removeAttr('selected');
            $('.splitAmountArea').show();
        } else {
            $('#ordersPerRun').val('');
            $('#timeInterval option:selected').removeAttr('selected');
            $('.splitAmountArea').hide();
        }
    });

    $('#starting_time_option').change(function (e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
//            console.log('checkbox is selected');
            $('.schedule_time_option_area').show();
        } else {
//            console.log('checkbox is de selected');
            $('.schedule_time_option_area').hide();
        }
    });

    $('#reset').click(function (e) {
        e.preventDefault();
        $('#plan_id option:selected').removeAttr('selected');

        $('#spreadOrdersOption span').removeClass('checked');


        $('#startSpreadIndex').val('');
        $('#endSpreadIndex').val('');

        $('#spreadOrdersOption').hide();
        $('#order_url').val('');
        $('#commentOptionArea').hide();
        $('#commentsTextArea').val('');
        $('#quantity').val('');

        $('#splitTotalAmount span').removeClass('checked');

        $('#ordersPerRun').val('');
        $('#timeInterval option:selected').removeAttr('selected');
        $('.splitAmountArea').hide();

        $('#order_total').text('-');
        $('#price').text('-');
        $('#delivery_for1K').text('-');
        $('#status').text('-');
        $('#min_order').text('-');
        $('#max_order').text('-');

    });

    var validateNumber = function validateNumber(e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            toastr.error('Please enter integer value only.', {timeOut: 4000});
            return false;
        }
    }

</script>
<!--END CUSTOM PAGE LEVEL SCRIPT-->

<script>

    $("#order_url").on("change", document, function () {
//        $("#URLLabel").html($.trim($(this).val()));
        $("#URLInfo").html("Loading...");
        var data = $.trim($(this).val());
//        console.log(data);
        $.ajax({
            type: "POST",
            url: "/user/URLinfo",
            data: {order_url: data},
            dataType: "json",
            beforeSend: function () {
                $("#AvatarPic").attr("src", "/images/url_loader.gif");
            },
            success: function (response) {

//                console.log(response.status);

                if (response.status != 'success') {
                    $("#AvatarPic").attr("src", "/images/url_avatar.png");
                    $("#URLInfo").html("");
                } else {

                    var url_data = response.url_data;

//                    console.log(url_data['url_type']);
                    if (url_data['url_type'] == "postLink") {

                        $("#URLInfo").html("Likes: " + url_data['initial_likes_count'] + "<BR />Comments: " + url_data['initial_comments_count']);
                        $("#AvatarPic").attr("src", url_data['image_url']);
                    }
                    if (url_data['url_type'] == "profileLink") {
                        $("#URLInfo").html("Followed by: " + url_data['initial_followers_count']);
                        $("#AvatarPic").attr("src", url_data['image_url']);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#AvatarPic").attr("src", "/images/url_avatar.png");
                $("#URLInfo").html("");
            }
        });
    });

</script>
@endsection


