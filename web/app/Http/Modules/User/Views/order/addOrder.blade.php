@extends('User/Layouts/userlayout')

@section('title','Dashboard')


@section('headcontent')

    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <link rel="stylesheet" href="/assets/plugins/datetimepicker/css/datetimepicker.css">
    <style>
        .m-t-10 {
            margin-top: 54px;
        }
        /*.error {*/
            /*color: red;*/
        /*}*/
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
                <li>Add Order</li>
            </ol>
        </section>

        <section class="page-content">
            <div class="row">
                {{--<div class="col-md-12">--}}
                    {{--<a class="btn btn-success btn-raised waves-effect waves-light btn modal-trigger" data-toggle="modal"--}}
                       {{--data-target="#modal-comments">Modal</a>--}}
                {{--</div>--}}
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="panel panel-default panel-divider">
                        <div class="panel-heading">
                            <header> Add new Order(s) &nbsp;&nbsp;
                                {{--<small><a href="javascript:;"> Single Order </a> | <a href="javascript:;"> Bulk Orders </a></small> --}}
                            </header>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-success-alert alert alert-warning alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>@if(session('errorMessage')) <?php echo session('errorMessage'); ?> @endif</strong>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <form class="" action="/user/addOrder" method="post" id="addOrderForm"
                                          role="form">
                                        <div class="form-group ">
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
                                                                data-chargePer1K="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>
                                                    @endforeach

                                                @endif
                                            </select>
                                            {{--<span id="plan_id_error"></span>--}}
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
                                        <div class="form-group " id="spreadOrdersOption" hidden>

                                            <input type="checkbox" name="spreadOrders" id="spreadOrders"> &nbsp;&nbsp;
                                            <label class="control-label">I want it spreaded between my pictures</label>

                                            <div class="clearfix"></div>
                                            <div class="form-group  col-md-6 spreadOrderBetween" hidden>
                                                <input type="number" class="form-control" name="startSpreadIndex"
                                                       id="startSpreadIndex"
                                                       placeholder="Enter Start Pics">
                                            </div>
                                            <div class="form-group  col-md-6 spreadOrderBetween" hidden>
                                                <input type="number" class="col-md-6 form-control" name="endSpreadIndex"
                                                       id="endSpreadIndex"
                                                       placeholder="Enter End Pics">
                                            </div>

                                        </div>
                                        <div class="form-group m-t-10" >
                                            <label class="control-label">Order Link</label>
                                            <input type="url" class="form-control" name="order_url" id="order_url"
                                                   placeholder="Your Video,Image,Page,Profile URL"/>
                                            <span id="order_url_error"></span>
                                        </div>

                                        <div class="form-group " id="commentOptionArea" hidden>
                                            <label class="control-label"> Choose a Comment Type</label>

                                            <div class="form-group ">
                                                <select class="form-control" name="commentType"
                                                        id="commentType" style="width: 100%">
                                                    <option selected value="0">Random Comments</option>
                                                    <option value="1">Custom Comments</option>
                                                </select>
                                            </div>


                                            <div class="form-group " id="customCommentRadioButton" hidden>
                                                <input type="radio" name="customCommentType" id="writeComments" value="0"
                                                       checked>
                                                <label class="control-label" style="margin-left: 1%;"> Write comments into a
                                                    text area</label>
                                                <input type="radio" name="customCommentType" id="selectPreMadeComments"
                                                       value="1" style="margin-left: 1.5%;">
                                                <label class="control-label" style="margin-left: 1%;"> Select
                                                    Comments</label>
                                            </div>

                                            <div class="form-group " id="customCommentTextArea" hidden>
                                                <label class="control-label">Comments( 1 comment per line , click out of the
                                                    box to check the amount and estimate price )</label>
                                            <textarea class="form-control" name="commentsTextArea" id="commentsTextArea"
                                                      rows="6" placeholder="Write your Comments "></textarea><span
                                                        id="commentsTextArea_error"></span>
                                            </div>

                                            <div class="" id="selectPreMadeCommentsArea" hidden>

                                                    <label class="col-sm-4 control-label" for="comment_id"
                                                           style="margin-top: 1%; text-align: right;">Please Select a Comment Group</label>
                                                    <div class=" form-group ">
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


                                        <div class="form-group ">
                                            <label class="control-label">Amount to Delivery</label>
                                            <input type="number" class="form-control" name="quantity" id="quantity"
                                                   placeholder="Amount of Likes,Followers you want in that link"/>
                                            <span id="quantity_error"></span>
                                        </div>

                                        <div class="form-group ">
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
                                            <small>By placing this order you agree to our Terms of Service and Refund
                                                Policy and you understand and agree that this action cannot be canceled
                                                once started or initiated. Thanks for your order :)
                                            </small>
                                        </p>
                                        <div class="form-group ">
                                            <button class="btn btn-success btn-raised" type="submit" name="submit_order"
                                                    id="submit_order">
                                                <i
                                                        class="fa fa-arrow-circle-right"></i> Place Order
                                            </button>
                                            <button class="btn default btn-raised" style="margin-left:1%;" type="button"
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
                                                <h4><strong>Order Total</strong></h4>
                                            </td>
                                            <td align="right" id="order_total" style="vertical-align: middle;">-</td>
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
                                            <td>Max. p/ Link</td>
                                            <td align="right" id="max_order">-</td>
                                        </tr>
                                        <tr>
                                            <td>Current Balance</td>
                                            <td align="right" id="current_balance">
                                                $ @if(isset(Session::get('ig_user')['account_bal'])) {{Session::get('ig_user')['account_bal']}} @else
                                                    0.0000 @endif </td>
                                        </tr>
                                        {{--<tr>--}}
                                        {{--<td>Pricing &amp; Info</td>--}}
                                        {{--<td align="right"><a href="#">Click here</a>--}}
                                        {{--</td>--}}
                                        {{--</tr>--}}
                                        </tbody>
                                    </table>
                                    <p class="text-muted">
                                        <small>Delivery time is avarage on the system latest orders, it can go up and
                                            down depending on the orders volume in real time. All orders are subject to
                                            terms of service delivery times.
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /#page-content -->
    </section>
    <!-- Right-Page-content End-->


  <!-- Modal For Custom comment group Trigger -->
    <!-- Modal Structure -->
    <div id="modal-comments" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Comments</h4>
                </div>
                <div class="modal-body">
                    <form class="form" role="form">
                        <div class="form-group floating-label">
                            <select id="select-comment" name="select-comment" class="form-control">
                               @if(isset($commentListData))
                                    @foreach($commentListData as $list)
                                        <option value="{{$list['comment_id']}}">{{$list['comment_group_id']}}</option>
                                    @endforeach
                                @endif
                                <option value="NEW">Create New Comment Group</option>
                            </select>

                            <label for="select-comment">Comment Group</label>
                        </div>

                        <div class="form-group floating-label" id='common-group-name'>
                            <input disabled="disabled" type="text" class="form-control" id="group-name">
                            <label for="group-name">Group Name</label>
                        </div>
                        <div class="form-group floating-label">
                            <textarea name="textarea2" id="textarea2" class="form-control" rows="3" placeholder=""></textarea>
                            <label for="textarea2">Comments( 1 comment per line )</label>
                        </div>
                        <style>
                            .fa.fa-minus-circle.pull-right {
                                cursor: pointer;
                            }
                            .comment-list-data {
                                max-height: 140px;
                            }
                        </style>
                        <div class="form-group floating-label scroll-fancy comment-list-data" id="listGroupComments" >
                            <ul class='list-group'>
                                <li class='list-group-item'>1gggggggggggggggggggggggggggggggg <i class="fa fa-minus-circle pull-right"></i></li>
                                <li class='list-group-item'>2hhhhhhrrrrrrhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh <i class="fa fa-minus-circle pull-right"></i></li>
                                <li class='list-group-item'>3 <i class="fa fa-minus-circle pull-right"></i></li>
                                <li class='list-group-item'>4 <i class="fa fa-minus-circle pull-right"></i></li>
                                <li class='list-group-item'>5 <i class="fa fa-minus-circle pull-right"></i></li>
                                <li class='list-group-item'>6 <i class="fa fa-minus-circle pull-right"></i></li>
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-theme btn-raised">Add Comment</button>
                    <button type="button" class="btn btn-default btn-raised">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!--/Modal-->
@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}

    <script type="text/javascript">


        $(document).ready(function(){
            $(".form-success-alert").hide();

            if('{{session('errorMessage')}}'){
                $(".form-success-alert").alert();
                $(".form-success-alert").fadeTo(5000, 800).slideUp(5000, function(){});
            }

        });
        //DataTable


        $('.fa.fa-minus-circle.pull-right').click(function(){
            $(this).parent().slideUp();
        });
        $('#datatable').dataTable();
        $(window).load(function () {
            $('#datatable_filter input, #datatable_length select').addClass('form-control');
            $('#datatable_length').addClass('form-group');
            $('#common-group-name').slideUp();
        });

        //Model for Custom Comment
        $('#select-comment').on('change', function(){

            var selected_option = $('#select-comment').find("option:selected").attr("value");

            if(selected_option === 'NEW') {
                if ($('#group-name').attr('disabled')) $('#group-name').removeAttr('disabled');
                $('#common-group-name').slideDown();
                $('#listGroupComments').addClass('hidden');

            } else {
                $('#group-name').attr('disabled', 'disabled');
                $('#common-group-name').slideUp();
                $('#listGroupComments').removeClass('hidden');

            }
        });


        // For Date Time Picker
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
            $('#price').text($('#plan_id option:selected').attr('data-chargePer1K'));
            $('#delivery_for1K').text('0');
            $('#status').text('0');
            $('#min_order').text($('#plan_id option:selected').attr('data-minQuantity'));
            $('#max_order').text($('#plan_id option:selected').attr('data-maxQuantity'));
        });

        $('#quantity').change(function (e) {
            e.preventDefault();
            var quantity = $('#quantity').val();
            var totalOrder = ($('#plan_id option:selected').attr('data-chargePer1K')/1000) * quantity;
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






