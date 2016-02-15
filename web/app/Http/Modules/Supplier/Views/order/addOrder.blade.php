@extends('Supplier/Layouts/supplierlayout')

@section('title','Dashboard')


@section('headcontent')

    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <link rel="stylesheet" href="/assets/plugins/datetimepicker/css/datetimepicker.css">
    @endsection

    @section('content')
    {{--PAGE CONTENT GOES HERE--}}

            <!-- Sub Nav End -->
    <div class="sub-nav hidden-sm hidden-xs">
        <ul>
            <li><a href="javascript:;" class="heading">Market-->Add Order</a></li>
        </ul>
        <div class="custom-search hidden-sm hidden-xs">
            <input type="text" class="search-query" placeholder="Search here ...">
            <i class="fa fa-search"></i>
        </div>
    </div>
    <!-- Sub Nav End -->

    <!-- Dashboard Wrapper Start -->
    <div class="dashboard-wrapper-lg">

        <!-- Row starts -->
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="widget">
                    <div class="widget-header">
                        <div class="title"> Add new Order(s) &nbsp;&nbsp;
                            {{--<small><a href="javascript:;"> Single Order </a> | <a href="javascript:;"> Bulk Orders </a>  </small>--}}
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="font-segoe" action="/supplier/addOrder" method="post" id="addOrderForm"
                                      role="form">

                                    <div class="form-group">
                                        <label class="control-label">Choose Service</label>

                                        <select class="js-example-responsive form-control" name="plan_id" id="plan_id"
                                                style="width:100%;">
                                            <option value="" selected>Please select a Service</option>
                                            @if(isset($data))
                                                <optgroup label='Intagram'></optgroup>
                                                @foreach($data as $plan)
                                                    <option value="{{$plan['plan_id']}}"
                                                            data-planType="{{$plan['plan_type']}}"
                                                            data-minQuantity="{{$plan['min_quantity']}}"
                                                            data-maxQuantity="{{$plan['max_quantity']}}"
                                                            data-chargePerUnit="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>
                                                @endforeach

                                            @endif
                                        </select>
                                        <span id="plan_id_error"></span>

                                        {{--<select class="js-example-responsive form-control" name="plan_id" id="plan_id" style="width:100%;">--}}
                                        {{--<option disabled selected>Please select a Service</option>--}}
                                        {{--<optgroup label="Instagram - Likes & Comments"></optgroup>--}}
                                        {{--<option value=""> Instagram - Likes ( Normal )</option>--}}
                                        {{--<option value=""> Instagram - Likes ( Fast )</option>--}}
                                        {{--<option value=""> Instagram - Likes ( Medium)</option>--}}
                                        {{--<option value=""> Instagram - Likes ( HQ )</option>--}}
                                        {{--<option value=""> Instagram - Comments ( Random )</option>--}}
                                        {{--<option value=""> Instagram - Comments HQ ( Custom )</option>--}}
                                        {{--<option value=""> Instagram - Comments ME ( Custom )</option>--}}
                                        {{--<optgroup label="Instagram - Followers"></optgroup>--}}
                                        {{--<option value=""> Instagram - Followers ( Fast )</option>--}}
                                        {{--<option value=""> Instagram - Followers ( Medium )</option>--}}
                                        {{--<option value=""> Instagram - Followers ( Normal )</option>--}}
                                        {{--<option value=""> Instagram - Followers ( Middle East )</option>--}}
                                        {{--</select>--}}
                                    </div>
                                    <div class="form-group" id="spreadOrdersOption" hidden>

                                        <input type="checkbox" name="spreadOrders" id="spreadOrders"> &nbsp;&nbsp;
                                        <label class="control-label">I want it spreaded between my pictures</label>

                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-6 spreadOrderBetween" hidden>
                                            <input type="number" class="col-md-6  form-control" name="startSpreadIndex"
                                                   id="startSpreadIndex"
                                                   placeholder="Enter Start Pics">
                                        </div>
                                        <div class="form-group col-md-6 spreadOrderBetween" hidden>
                                            <input type="number" class="col-md-6 form-control" name="endSpreadIndex"
                                                   id="endSpreadIndex"
                                                   placeholder="Enter End Pics">
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Order Link</label>
                                        <input type="url" class="form-control" name="order_url" id="order_url"
                                               placeholder="Your Video,Image,Page,Profile URL"/>
                                        <span id="order_url_error"></span>
                                    </div>

                                    <div class="form-group" id="commentOptionArea" hidden>
                                        <label class="control-label"> Choose a Comment Type</label>

                                        <div class="form-group">
                                            <select class="form-control" name="commentType"
                                                    id="commentType" style="width: 100%">
                                                <option selected value="0">Random Comments</option>
                                                <option value="1">Custom Comments</option>
                                            </select>
                                        </div>


                                        <div class="form-group" id="customCommentRadioButton" hidden>
                                            <input type="radio" name="customCommentType" id="writeComments" value="0"
                                                   checked>
                                            <label class="control-label" style="margin-left: 1%;"> Write comments into a
                                                text area</label>
                                            <input type="radio" name="customCommentType" id="selectPreMadeComments"
                                                   value="1" style="margin-left: 1.5%;">
                                            <label class="control-label" style="margin-left: 1%;"> Select
                                                Comments</label>
                                        </div>

                                        <div class="form-group" id="customCommentTextArea" hidden>
                                            <label class="control-label">Comments( 1 comment per line , click out of the
                                                box to check the amount and estimate price )</label>
                                            <textarea class="form-control" name="commentsTextArea" id="commentsTextArea"
                                                      rows="6" placeholder="Write your Comments "></textarea><span
                                                    id="commentsTextArea_error"></span>
                                        </div>

                                        <div id="selectPreMadeCommentsArea" hidden>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="comment_id"
                                                       style="margin-top: 1%; text-align: right;">Please Select a
                                                    Comment
                                                    Group</label>

                                                <div class="col-sm-7">

                                                    <select class="form-control" name="comment_id" id="comment_id">
                                                        @if(isset($commentListData))
                                                            @foreach($commentListData as $list)
                                                                <option value="{{$list['comment_id']}}">{{$list['comment_group_name']}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>&nbsp;&nbsp;<span id="comment_id_error"></span><br>

                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="control-label">Amount to Delivery</label>
                                        <input type="number" class="form-control" name="quantity" id="quantity"
                                               placeholder="Amount of Likes,Followers you want in that link"/>
                                        <span id="quantity_error"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"> Schedule Starting Time</label>

                                        <div class='input-group date' id='datetimepicker1'>
                                            <input type='text' class="form-control" name="starting_time"
                                                   id="starting_time" placeholder="Please select scheduling Time"/>
                                            <span class="input-group-addon">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>


                                    <p class="text-muted">
                                        <small>By placing this order you agree to our Terms of Service and Refund Policy
                                            and
                                            you understand and agree that this action cannot be canceled once started or
                                            initiated. Thanks for your order :)
                                        </small>
                                    </p>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="submit" name="submit_order"
                                                id="submit_order">
                                            <i
                                                    class="fa fa-arrow-circle-right"></i> Place Order
                                        </button>
                                        <button class="btn default" style="margin-left:1%;" type="button"
                                                name="reset_order"
                                                id="reset">Reset
                                        </button>

                                        <h4 style="color: green; text-align:center">@if(session('successMessage')) <?php echo session('successMessage'); ?> @endif</h4>
                                        <h4 style="color: red; text-align:center">@if(session('errorMessage')) <?php session('errorMessage'); ?> @endif</h4>

                                        @if (count($errors) > 0)
                                            <div>
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li style="color: red; font-size: 14px; text-align: center">{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif


                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="widget">
                    <div class="widget-header">
                        <div class="title"> Order Resume</div>
                    </div>


                    <div class="widget-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-hover table-light font-segoe">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <h4><strong>Order Total</strong></h4>
                                        </td>
                                        <td align="right" id="order_total" style="vertical-align: middle;">-</td>
                                    </tr>
                                    <tr>
                                        <td>Price per Unit</td>
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
                                        <td>Max. p/ Link</td>
                                        <td align="right" id="max_order">-</td>
                                    </tr>
                                    <tr>
                                        <td>Current Balance</td>
                                        <td align="right" id="current_balance">
                                            $ @if(isset(Session::get('ig_supplier')['account_bal'])) {{Session::get('ig_supplier')['account_bal']}} @else
                                                0.0000 @endif </td>
                                    </tr>
                                    {{--<tr>--}}
                                    {{--<td>Pricing &amp; Info</td>--}}
                                    {{--<td align="right"><a href="#">Click here</a>--}}
                                    {{--</td>--}}
                                    {{--</tr>--}}
                                    </tbody>
                                </table>
                                <p class="text-muted font-segoe">
                                    <small>Delivery time is avarage on the system latest orders, it can go up and down
                                        depending on the orders volume in real time. All orders are subject to terms of
                                        service delivery times.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--<div class="col-lg-12 col-md-12 col-sm-12">--}}
            {{--<div class="widget">--}}
            {{--<div class="widget-header">--}}
            {{--<div class="title"> Latest Orders &nbsp;&nbsp;--}}
            {{--<small>Latest orders placed</small>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="widget-body">--}}
            {{--<div class="row">--}}
            {{--<div class="col-md-12">--}}
            {{--<table class="table table-hover table-responsive font-segoe" id="datatable">--}}
            {{--<thead>--}}
            {{--<tr>--}}
            {{--<th> #</th>--}}
            {{--<th> Type</th>--}}
            {{--<th> Link</th>--}}
            {{--<th> Amount</th>--}}
            {{--<th> Added</th>--}}
            {{--<th> Status</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-success"><i--}}
            {{--class="fa fa-check-circle-o"></i> Completed</span>--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-success"><i--}}
            {{--class="fa fa-check-circle-o"></i> Completed</span>--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-success"><i--}}
            {{--class="fa fa-check-circle-o"></i> Completed</span>--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-info"><i class="fa fa-clock-o"></i> Pending</span></td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-success"><i--}}
            {{--class="fa fa-check-circle-o"></i> Completed</span>--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
            {{--<td> 5381709</td>--}}
            {{--<td><i style="font-size:10px" class="fa fa-instagram"></i> Instagram - Likes (--}}
            {{--Normal )--}}
            {{--</td>--}}
            {{--<td><a target="_blank"--}}
            {{--href="http://nullrefer.com/?https://www.instagram.com/p/BBJnrOHKAm0/">https://www.instagram.com/p/BB...</a>--}}
            {{--</td>--}}
            {{--<td> 1002</td>--}}
            {{--<td> 8 mins</td>--}}
            {{--<td><span class="label label-success"><i--}}
            {{--class="fa fa-check-circle-o"></i> Completed</span>--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--</tbody>--}}
            {{--</table>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
        </div>
        <!-- Row ends -->
    </div>
    <!-- Dashboard Wrapper End -->

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
    <script src="/assets/plugins/datetimepicker/js/moment-locales.js"></script>

    <script src='/assets/plugins/datetimepicker/js/datetimepicker.js'></script>

    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript">
        var dateToday = new Date();
        $('#datetimepicker1').datetimepicker({
            minDate: dateToday
        });

        $('#plan_id').change(function (e) {
            e.preventDefault();

            if ($('#plan_id option:selected').attr('data-planType') == 0) {
                $('#spreadOrders').checked = false;
                $('#spreadOrdersOption').show();
            } else {
                $('#spreadOrdersOption').hide();
            }

            if ($('#plan_id option:selected').attr('data-planType') == 2) {
                $('#commentOptionArea').show();
            } else {
                $('#commentOptionArea').hide();
            }


            $('#quantity').attr({'min': $('#plan_id option:selected').attr('data-minQuantity')});
            $('#quantity').attr({'max': $('#plan_id option:selected').attr('data-maxQuantity')});

            $('#order_total').text('0');
            $('#price').text($('#plan_id option:selected').attr('data-chargePerUnit'));
            $('#delivery_for1K').text('0');
            $('#status').text('0');
            $('#min_order').text($('#plan_id option:selected').attr('data-minQuantity'));
            $('#max_order').text($('#plan_id option:selected').attr('data-maxQuantity'));
        });

        $('#quantity').change(function (e) {
            e.preventDefault();
            var quantity = $('#quantity').val();
            var totalOrder = $('#plan_id option:selected').attr('data-chargePerUnit') * quantity;
            $('#order_total').text(totalOrder.toFixed(4));
        });

        $('#spreadOrders').change(function (e) {
            e.preventDefault();

            if ($(this).is(":checked")) {
                $('.spreadOrderBetween').show();
            } else {
                $('.spreadOrderBetween').hide();
            }
        });

        $('#commentType').change(function (e) {
            e.preventDefault();
            if ($('#commentType option:selected').attr('value') == 0) {

                $('#customCommentRadioButton').hide();
                $('#customCommentTextArea').hide();
                $('#selectPreMadeCommentsArea').hide();
                $('#quantity').attr({'min': $('#plan_id option:selected').attr('data-minQuantity')});
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
            $('#quantity').attr({'min': $('#plan_id option:selected').attr('data-minQuantity')});
        });

        $.validator.addMethod('greaterThan', function (value, element, param) {
            var startSpreadIndex = parseInt($(param).val());
            var endSpreadIndex = parseInt(value);
            console.log(startSpreadIndex);
            console.log(endSpreadIndex);
            if (endSpreadIndex < startSpreadIndex)
                return false;
            else
                return true;
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
            console.log(lineCount);
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

        $('#addOrderForm').validate({
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
                    url: true
                },
                quantity: {
                    required: true
                },
                commentType: {
                    required: true
                },
                commentsTextArea: {
                    required: true,
                    fivechars: false,
                    fivelines: true
                },
                starting_time: {required: true}
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
            console.log(lineCount);
            $('#quantity').val(lineCount);

        });

        $('#reset').click(function (e) {
            e.preventDefault();
            $('#plan_id option:selected').removeAttr('selected');
            $('#spreadOrdersOption').hide();
            $('#order_url').val('');
            $('#commentOptionArea').hide();
            $('#commentsTextArea').val('');
            $('#quantity').val('');
            $('#order_total').text('-');
            $('#price').text('-');
            $('#delivery_for1K').text('-');
            $('#status').text('-');
            $('#min_order').text('-');
            $('#max_order').text('-');

        });


    </script>

@endsection






