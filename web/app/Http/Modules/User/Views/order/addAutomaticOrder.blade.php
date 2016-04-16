@extends('User/Layouts/userlayout')

@section('title','Dashboard')


@section('headcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}
    <link rel="stylesheet" href="/assets/plugins/toastr/css/toastr.css"/>
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
            padding: 9px 3px !important;
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

        .show-error-message {
            color: red;
            font-style: italic;
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
                <li>Automatic Orders</li>
            </ol>
        </section>

        <section class="page-content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="panel panel-default panel-divider">
                        <div class="panel-heading">
                            <header>
                                <div style="float: left"><i class="fa fa-bolt font-green-sharp"></i>&nbsp;AUTOMATIC
                                    &nbsp;&nbsp;&nbsp;</div>
                                <div style="float: left; font-size: small">Check your current usernames with auto-likes
                                    subscription
                                </div>

                                <div style="float: right">
                                    <button class="btn btn-primary pull-right" data-toggle="modal"
                                            data-target="#addOrders"><i class="fa fa-plus-circle"></i> Add username
                                    </button>
                                </div>
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
                                    <div class="col-md-4 col-sm-12 paging"></div>


                                    <table class="table table-striped table-borderless  table-hover"
                                           id="datatable_ajax">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="1%"><input type="checkbox" class="group-checkable"></th>
                                            <th width="">#ID</th>
                                            <th width="">Username</th>
                                            <th width="">Pics Done</th>
                                            <th width="">Pics Limit</th>
                                            <th width="">Likes per Pic</th>
                                            <th width="">Last Check</th>
                                            <th width="">Last Delivery</th>
                                            <th width="">Status</th>
                                            <th width="">Details</th>
                                        </tr>
                                        <tr role="row" class="filter">
                                            <td></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_id"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_username"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_pics_done"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_pics_limit"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_pics_likes"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_last_check" disabled="disabled"></td>
                                            <td><input type="text" class="form-control form-filter input-sm"
                                                       name="search_last_delivery" disabled="disabled"></td>
                                            <td><select name="search_status" class="form-control form-filter input-sm">
                                                    <option value="">Select...</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Processing">Processing</option>
                                                    <option value="Completed">Completed</option>
                                                    <option value="Refunded">Refunded</option>
                                                    <option value="Partial Refunded">Partial Refunded</option>
                                                </select></td>
                                            <td>
                                                <button class="btn btn-xs default filter-submit margin-bottom"><i
                                                            class="fa fa-search"></i> Search
                                                </button>
                                                <button class="btn btn-xs red filter-cancel"><i class="fa fa-times"></i>
                                                    Reset
                                                </button>
                                            </td>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><input type="checkbox" class="group-checkable"></td>
                                            <td>23</td>
                                            <td>Username</td>
                                            <td>32</td>
                                            <td>12</td>
                                            <td>10</td>
                                            <td>Last Check</td>
                                            <td>Last Delivery</td>
                                            <td>1</td>
                                            <td>Details</td>
                                        </tr>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    @if(!isset($orders))
                                        <div>
                                            <h3>There are no orders to show</h3>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </section>
    </section>

    <!-- Modal HTML -->
    <div id="addOrders" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New Username</h4>
                </div>
                <div class="modal-body">
                    <form class="form" role="form" id="addAutoOrderForm">
                        <div class="form-group floating-label">
                            <input type="text" class="form-control " name="instagramUsername" id="instagramUsername"
                                   placeholder=""/>
                            <label for="instagramUsername">Instagram Username without @</label>
                        </div>

                        <div class="form-group floating-label">
                            <input type="number" class="form-control" name="likesPerPic" id="likesPerPic"
                                   placeholder=""/>
                            <label for="likesPerPic">The Amount of likes to send every new post</label>
                        </div>

                        <div class="form-group floating-label">
                            <input type="number" class="form-control" name="picLimit" id="picLimit" placeholder=""/>
                            <label for="picLimit">Stop After X Post(s) got Likes</label>
                        </div>


                        <div class="form-group floating-label">

                            <select id="planId" name="planId" class="form-control">
                                <option value="">&nbsp;</option>
                                @if(isset($planList))
                                    @foreach($planList as $plan)
                                        @if($plan['plan_type']==0 )
                                            <option value="{{$plan['plan_id']}}"
                                                    data-planType="{{$plan['plan_type']}}"
                                                    data-supplierServerId="{{$plan['supplier_server_id']}}"
                                                    data-minQuantity="{{$plan['min_quantity']}}"
                                                    data-maxQuantity="{{$plan['max_quantity']}}"
                                                    data-chargePer1K="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="">There are currently no any active services</option>
                                @endif
                            </select>
                            <label for="planId">Please choose type of likes </label>
                        </div>


                        <div class="form-group floating-label">
                            <span style="padding-left: 43%">Advance Option</span>
                        </div>
                        <div class="form-group floating-label">
                            <select id="autoComments" name="autoComments" class="form-control">
                                <option value=""></option>
                                <option value="YES">Yes</option>
                                <option value="NO">No</option>
                            </select>
                            <label for="autoComments">Add Auto Comments also?</label>
                        </div>

                        <div class="form-group floating-label" id="autoCommentsArea" hidden>
                            <select id="autoCommentPlanId" name="autoCommentPlanId" class="form-control">
                                <option value=""></option>
                                @if(isset($planList))
                                    @foreach($planList as $plan)
                                        @if($plan['plan_type']==2 || $plan['plan_type']==3 )
                                            <option value="{{$plan['plan_id']}}"
                                                    data-planType="{{$plan['plan_type']}}"
                                                    data-supplierServerId="{{$plan['supplier_server_id']}}"
                                                    data-minQuantity="{{$plan['min_quantity']}}"
                                                    data-maxQuantity="{{$plan['max_quantity']}}"
                                                    data-chargePer1K="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>
                                        @endif
                                    @endforeach
                                @else
                                    <option value="">There are currently no any active services</option>
                                @endif
                            </select>
                            <label for="autoCommentPlanId">Please select the service</label>
                        </div>

                        <div class="form-group floating-label" id="autoCustomCommentsArea" hidden>
                            <select id="customCommentGroupId" name="customCommentGroupId" class="form-control">
                                <option value=""></option>
                                @if(isset($commentListData))
                                    @foreach($commentListData as $list)
                                        <option value="{{$list['comment_group_id']}}">{{$list['comment_group_name']}}</option>
                                    @endforeach
                                @else
                                    <option value="">There are currently no any active services</option>
                                @endif
                            </select>
                            <label for="customCommentGroupId">Please select the comment group type</label>
                        </div>

                        <div class="form-group floating-label" id="autoCommentAmountArea" hidden>
                            <input type="number" class="form-control" name="autoCommentAmount" id="autoCommentAmount"
                                   placeholder=""/>
                            <label for="autoCommentAmount">The amount of comments to send every new post</label>
                        </div>
                        <div class="form-group floating-label" id="commentsMessage" hidden>
                            <span>( Standard rate of $ <span id="comment_rate">o.ooo</span>&nbsp; per comment is applied, random comments are sent ) </span>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-flat btn-ripple" data-dismiss="modal"
                                    id="cancelButton">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success btn-flat btn-ripple" id="submitButton">Add
                                Username
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>





@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
    {{--datatable-start--}}
    <script type="text/javascript" src="/assets/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript"
            src="/assets/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
    <script type="text/javascript"
            src="/assets/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
    <script type="text/javascript" src="/assets/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
    <script type="text/javascript" src="/assets/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="/assets/datatables/scripts/table-advanced.js"></script>
    <script type="text/javascript" src="/assets/datatables/scripts/datatable.js"></script>

    {{--datatable end--}}



    <script src="/assets/plugins/toastr/js/toastr.js"></script>
    <script type="text/javascript">
        $('#autoComments').change(function () {

            if ($('#autoComments option:selected').val() == 'YES') {
                $('#autoCommentsArea').show();
                $('#autoCommentAmountArea').show();
                $('#commentsMessage').show();
            } else {
                $('#autoCommentsArea').hide();
                $('#autoCommentAmountArea').hide();
                $('#autoCustomCommentsArea').hide();
                $('#commentsMessage').hide();
            }
        });

        $('#autoCommentPlanId').change(function () {
            var autoCommentPlanId = $('#autoCommentPlanId option:selected');
            if (autoCommentPlanId.attr('data-planType') == 3) {
                $('#autoCustomCommentsArea').show();
            } else {
                $('#autoCustomCommentsArea').hide();
            }

            var chargePer1K = autoCommentPlanId.attr('data-chargePer1K');
            var chargePerUnit = (chargePer1K / 1000).toFixed(4);
            $('#comment_rate').text(chargePerUnit);


        });

        toastr.options.positionClass = "toast-top-center";
        toastr.options.preventDuplicates = true;
        toastr.options.closeButton = true;

        $("#likesPerPic").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                toastr.error('Please enter integer value only.', {timeOut: 4000});
                $(this).focus();
                return false;
            }
        });
        $("#picLimit").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                toastr.error('Please enter integer value only.', {timeOut: 4000});
                $(this).focus();
                return false;
            }
        });
        $("#autoCommentAmount").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                toastr.error('Please enter integer value only.', {timeOut: 4000});
                $(this).focus();
                return false;
            }
        });

        $(document).on('click', '#submitButton', function (e) {
            e.preventDefault();

            var username = $('#instagramUsername').val();
            var likesPerPic = $('#likesPerPic').val();
            var picLimit = $('#picLimit').val();
            var planId = $('#planId option:selected').val();
            var minAmount = $('#planId option:selected').attr('data-minQuantity');
            var autoComments = $('#autoComments option:selected').val();
            var autoCommentPlanId = $('#autoCommentPlanId option:selected').val();
            var customCommentGroupId = $('#customCommentGroupId option:selected').val();
            var autoCommentAmount = $('#autoCommentAmount').val();

            toastr.options.positionClass = "toast-top-center";
            toastr.options.preventDuplicates = true;
            toastr.options.closeButton = true;
            if (username == '' || username == null || likesPerPic == '' || picLimit == '' || planId == '') {
                toastr.error('Please fill all the inputs.', {timeOut: 4000});
                if (username == '' || username == null) {
                    $('#instagramUsername').focus();
                    return false;
                }
                if (likesPerPic == '') {
                    $('#likesPerPic').focus();
                    return false;
                }
                if (picLimit == '') {
                    $('#picLimit').focus();
                    return false;
                }
                if (planId == '') {
                    $('#planId').focus();
                    return false;
                }
            }
            if (likesPerPic < minAmount) {
                toastr.error('Minimum Amount of likes is ' + minAmount + '.', {timeOut: 4000});
                $('#likesPerPic').focus();
                return false;
            }

            if (autoComments == 'YES') {
                if (autoCommentPlanId == '') {
                    toastr.error('Please fill all the inputs.', {timeOut: 4000});
                    $('#autoCommentPlanId').focus();
                    return false;
                } else {
                    if ($('#autoCommentPlanId option:selected').attr('data-planType') == 3) {
                        if (customCommentGroupId == '') {
                            toastr.error('Please fill all the inputs.', {timeOut: 4000});
                            $('#customCommentGroupId').focus();
                            return false;
                        }
                    }
                }

                if (autoCommentAmount == '') {
                    toastr.error('Please fill all the inputs.', {timeOut: 4000});
                    $('#autoCommentAmount').focus();
                    return false;
                }

                if (autoCommentAmount < 5) {
                    toastr.error('Minimum amount of comments to send every new post is 5.', {timeOut: 4000});
                    $('#autoCommentAmount').focus();
                    return false;
                }
            }

            var data = $('#addAutoOrderForm').serializeArray();
            console.log(data);
            $.ajax({
                url: "/user/addAutolikesOrder",
                data: data,
                dataType: "json",
                method: 'post',
                success: function (response) {
                    console.log(response);
                    if (response['status'] == 1) {
                        $("#cancelButton").trigger("click");
                        toastr.success('Username added successfull for autolikes');
                        console.log(response['message']);
                    }
                    else if (response['status'] == 0) {
                        var messages = response['message'];
                        console.log(messages);
                        var messageStr = "";
                        $.each(messages, function (index, value) {
                            messageStr += value + "\n";
                        });
                        toastr.error(messageStr);
                    }
                },
                error: function (xhr, status, error) {
                    toastr.error(error);
                    console.log(error);
                }
            });
        });




    </script>


@endsection



