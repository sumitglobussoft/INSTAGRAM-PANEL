@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
    {{--<link rel="stylesheet" type="text/css" href="http://w2ui.com/src/w2ui-1.4.2.min.css" />--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="/css/toastr.css">
    <style>
        #page-wrapper #left-content #sidebar-wrapper {
            overflow-y: hidden;
        }
        #sel {
            margin-bottom: 2%;
            margin-top: 2%;
        }

        /*.saurabh {*/
        /*/!*color: blue;*!/*/
        /*background-color:  #8e9bae;*/
        /*}*/

        /*.btn.dropdown-toggle.btn-default{*/
        /*diplay:none;*/
        /*}*/
        /*.bs-select-hidden {*/
        /*display:none;*/
        /*position: absolute !important;*/
        /*top: -9999px !important;*/
        /*left: -9999px !important;*/
        /*}*/
        /*.btn-group.bootstrap-select.bond{*/
        /*/!*border-bottom: transparent !important;*!/*/
        /*background: #fff;*/
        /*border: 1px solid #000;*/
        /*}*/
        /*.btn.dropdown-toggle{*/
        /*display: none;*/
        /*}*/
        /*.bond{*/
        /*background: #fff;*/
        /*border: 1px solid #000;*/
        /*}*/
        /*.no-sort::after { display: none!important; }*/

        /*.no-sort { pointer-events: none!important; cursor: default!important; }*/

    </style>
@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Instagram AutoLikes Users</li>
            </ol>
        </section>
        <section class="page-content">
            {{--<h1>Messazon Autolikes Subscriber</h1>--}}
            <div class="col-md-12">

                <div class="col-md-3">
                    {{--<label class="control-label">Expiration Date</label>--}}
                    <select class="form-control bond" name="endDate" id="endDate" required>
                        <option value="0"> Expiration date</option>
                        <option value="1"> Expired</option>
                        <option value="2"> Expiring Today</option>
                        <option value="3"> Expiring in next 24-48 hrs</option>
                        <option value="4"> Expiring the next 5 days</option>
                    </select>
                </div>


                <div class="col-md-3">
                    {{--<label class="control-label">Daily Posts Limit </label>--}}
                    <select class="form-control bond" name="dailyPost" id="dailyPost"
                            required>
                        <option value="0"> Daily Post Limit</option>
                        <option value="1"> Yes</option>
                        <option value="2"> NO</option>
                        <option value="3"> Reached</option>
                    </select>
                </div>

                <div class="col-md-3">
                    {{--<label class="control-label">Total posts Reached </label>--}}
                    <select class="form-control bond" name="totalPost" id="totalPost"
                            required>
                        <option value="0"> Total Post Reached</option>
                        <option value="1"> Yes</option>
                        <option value="2"> NO</option>
                        <option value="3"> Left less than 10%</option>
                    </select>
                </div>
                <div class="col-md-3">
                    {{--<label class="control-label">Services </label>--}}
                    <select class="form-control bond" name="services" id="services"
                            required>
                        <option value="0"> Select...</option>

                        @foreach($planLists as $plans)
                            <option value={{$plans->plan_id}}> {{$plans->plan_name}}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <hr>
            <div class="col-sm-12" id="sel">
                <div class="table-group-actions pull-right">
                    <span id="displaySelectedRecord" class="col-sm-2"></span>

                    <div class="col-sm-6">
                        <select class="table-group-action-input form-control input-inline input-small input-sm"
                                id="selectAction">
                            <option value="0">Select Action</option>
                            <option value="1">Restart Daily Counter</option>
                            <option value="2">Restart Total Counter</option>
                            <option value="3">Change Server</option>
                            {{--<option data-toggle="modal" data-target="#modalChangeServer">Change Server</option>--}}
                            <option value="4">Force to check profile for new posts</option>
                            <option value="5">Remove from system</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button name="actionSubmit" id="actionSubmit"
                                class="btn btn-sm btn-info table-group-action-submit pull-right"
                                data-original-title="" title=""><i class="fa fa-check"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
            <table id="example-table" class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="bg-info">
                    <th class="no-sort"><input type="checkbox" id="groupCheckBox"></th>
                    <th> #ID</th>
                    <th> Added By</th>
                    <th> InstaProfile</th>
                    <th> Service</th>
                    <th> Post Done / Post Limit</th>
                    <th> Start Date</th>
                    <th> End Date</th>
                    {{--<th> Last Check</th>--}}
                    {{--<th> Last Delivery</th>--}}
                    <th> Status</th>
                    <th> Details</th>
                </tr>
                </thead>
            </table>
            <!-- modals for Details -->
            <div id="details" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2" id="avatar">
                                    <img src="/images/avatar.png" class="img-responsive img-circle"/>
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-responsive table-hover" id="viewTable">
                                        <tbody>
                                        <tr>
                                            <td colspan="2">
                                                Username:
                                            </td>
                                            <td colspan="2">
                                                <strong id="username"> </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Email:
                                            </td>
                                            <td colspan="2">
                                                <strong id="email"></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Instagram Profile:
                                            </td>
                                            <td colspan="2">
                                                <strong id="instagramProfile"><a
                                                            class="btn btn-xs default text-case link-width"
                                                            target="_blank" href="">
                                                        <i class="fa fa-instagram" style="font-size:10px"></i>
                                                    </a></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Last Delivered Link:
                                            </td>
                                            <td colspan="2">
                                                <strong id="lastDeliveredLink"><a href="" taregt="_blank"></a></strong>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <button class="btn btn-sm btn-default orderDetails" data-toggle="modal" data-target="#viewAll"> view
                                                    all</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Quantity/Post:
                                            </td>
                                            <td colspan="2">
                                                <strong id="quantityPerPost"></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Last Check:
                                            </td>
                                            <td>
                                                <strong id="lastCheck"></strong>
                                            </td>
                                            <td>
                                                Last Delivery:
                                            </td>
                                            <td>
                                                <strong id="lastDelivery"></strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- modals for displaying all the orders lists -->
            <div id="viewAll" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body" style="overflow-y: auto; max-height: 250px;">
                            <div class="row">
                                <div class="col-md-10">
                                    <table class="table table-responsive table-hover" id="viewTable">
                                        <tbody id="tableRow">
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a href="" id="link"> </a>
                                            </td>
                                            <td id="status">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- modals for editing the profile-->
            <div id="editOrder" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title font-green-sharp bold uppercase"><i class="fa fa-pencil font-green-sharp"></i>&nbsp;Edit
                                User Order</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form" role="form" id="edit_usernameForm">
                                <div class="form-group floating-label">
                                    <label for="">Instagram Username without @</label>
                                    <input type="text" class="form-control " name="ins_username" id="ins_username" disabled
                                           placeholder=""/>
                                </div>

                                <div class="form-group floating-label">
                                    <label for="">The Amount of likes to send every new post</label>
                                    <input type="number" class="form-control" name="edit_likesPerPic" id="edit_likesPerPic"
                                           placeholder="" required/>
                                </div>

                                <div class="form-group floating-label">
                                    <label for="">Stop After X Post(s) got Likes</label>
                                    <input type="number" class="form-control" name="edit_picLimit" id="edit_picLimit"
                                           placeholder="" required/>
                                </div>


                                <div class="form-group floating-label">
                                    <label for="edit_planId">Change Server </label>
                                    <select id="edit_planId" name="edit_planId" class="form-control " required>
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
                                </div>

                                <div class="form-group floating-label">
                                    <label for="dailyPostLimit">Change Daily Post Limit <span style="font-size: 12px"> &nbsp;( Value 0 for unlimited daily posts.) </span></label>
                                    <input type="number" class="form-control" name="edit_dailyPostLimit" id="edit_dailyPostLimit"
                                           placeholder="Number of picture(s) to get likes in a day." required/>
                                </div>

                                <div class="form-group floating-label">

                                    <input type="checkbox" name="edit_orderDelay"
                                           id="edit_orderDelay"
                                            > &nbsp;&nbsp;
                                    <label class="control-label" for="edit_orderDelay">Add 10 mins delay for each posts. </label>&nbsp;&nbsp;
                                    <a data-toggle="popover" data-placement="bottom" title="Information"
                                       data-content="Every new post will process after 10 minutes delay so that user can cancel the order before its being added to the server.">
                                        <i class="fa fa-question-circle"></i>
                                    </a>
                                </div>

                                <div id="expiration" class="form-group floating-label">

                                    <input type="checkbox" name="edit_autolikesSubscription"
                                           id="edit_autolikesSubscription"
                                            > &nbsp;&nbsp;
                                    <label class="control-label" for="edit_autolikesSubscription">I want to set start date and end date
                                        for autolikes profile. </label>


                                    <div id="edit_autolikesSubscriptionOption" style="display:inline-flex; width: 100%;">
                                        <div class="clearfix"></div>

                                        <div class="form-group  col-md-6 input-group date form_datetime" style="margin-left: 1%;">
                                            <input type="text" size="16" readonly class="form-control" name="endDate" id="endDate"
                                                   placeholder="End date">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group floating-label">
                                    <h4 class="modal-title font-green-sharp bold uppercase" style="padding-left: 43%">Advance
                                        Option</h4>
                                </div>
                                <div class="form-group floating-label">
                                    <label for="edit_autoComments">Add Auto Comments also?</label>
                                    <select id="edit_autoComments" name="edit_autoComments" class="form-control" required>
                                        <option value=""></option>
                                        <option value="YES">Yes</option>
                                        <option value="NO">No</option>
                                    </select>
                                </div>

                                <div class="form-group floating-label" id="edit_autoCommentsArea" hidden>
                                    <label for="edit_autoCommentPlanId">Please select the service</label>
                                    <select id="edit_autoCommentPlanId" name="edit_autoCommentPlanId" class="form-control" required>
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
                                </div>

                                <div class="form-group floating-label" id="edit_autoCustomCommentsArea" hidden>
                                    <label for="edit_customCommentGroupId">Please select the comment group type</label>
                                    <select id="edit_customCommentGroupId" name="edit_customCommentGroupId"
                                            class="form-control" required>
                                        <option value=""></option>
                                        @if(isset($commentListData))
                                            @foreach($commentListData as $list)
                                                <option value="{{$list['comment_group_id']}}">{{$list['comment_group_name']}}</option>
                                            @endforeach
                                        @else
                                            <option value="">There are currently no any active services</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group floating-label" id="edit_autoCommentAmountArea" hidden>
                                    <label for="edit_autoCommentAmount">The amount of comments to send every new post</label>
                                    <input type="number" class="form-control" name="edit_autoCommentAmount"
                                           id="edit_autoCommentAmount"
                                           placeholder="" required/>
                                </div>

                                <div class="form-group floating-label" id="edit_commentsMessage" hidden>
                                    <span>( Standard rate of $ <span id="edit_comment_rate">o.ooo</span>&nbsp; per comment is applied, random comments are sent ) </span>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger btn-flat btn-ripple" data-dismiss="modal"
                                            id="edit_cancelButton"> Cancel
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

            <!-- modals for change server -->
            <div id="modalChangeServer" title="Change Server">
                <select id="changeServer">
                    <option value="0">Select...</option>
                    @foreach($planLists as $plans)
                        <option value="{{$plans->plan_id}}">{{$plans->plan_name}}</option>
                    @endforeach
                </select>
                <button id="changeServerSubmit">Choose Server</button>
            </div>


            <div id="all" title="All Delivered Link">
                <div id="tableRow">
                    <a>
                        <strong id="link"> </strong>
                    </a>
                    <span id="status"></span>
                </div>
            </div>
        </section>


    </section>

@endsection


@section('pagescripts')
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
    {{--<script type="text/javascript" src="http://w2ui.com/src/w2ui-1.4.2.min.js"></script>--}}

    {{--<script src="//code.jquery.com/jquery-1.10.2.js"></script>--}}
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

    <script src="/js/toastr.js"></script>

    <script>
        $(document).ready(function () {
            $('#modalChangeServer').dialog({
                modal: true,
                autoOpen: false,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                }
            });
            $('#all').dialog({
                modal: true,
                autoOpen: false,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                }
            });

            $('#example-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                "order": [1, 'desc'],
                "columnDefs": [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
                ajax: {
                    url: '/admin/autolikesProfileAjaxDatatables',
                    data: function (d) {
                        d.method = "withoutFilter";
                    }
                },
                columns: [
                    {data: 'check', name: 'check', orderable: false, searchable: false},
                    {data: 'id', name: 'id'},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'instagramProfile', name: 'instagramProfile'},
                    {data: 'serverType', name: 'serverType'},
                    {data: 'PostDoneAndTotalPost', name: 'PostDoneAndTotalPost'},
                    {data: 'startDate', name: 'startDate'},
                    {data: 'endDate', name: 'endDate'},
//                    {data: 'lastCheck', name: 'lastCheck'},
//                    {data: 'lastDelivery', name: 'lastDelivery'},
                    {data: 'status', name: 'status'},
                    {data: 'details', name: 'details'},
                ]
            });
            $(document.body).on('change', '.bond', function (e) {
                var endDate = $("#endDate").children('option').filter(":selected").val();
                var dailyPost = $("#dailyPost").children('option').filter(':selected').val();
                var totalPost = $("#totalPost").children('option').filter(':selected').val();
                var services = $("#services").children('option').filter(':selected').val();
                var oTable = $('#example-table').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    "order": [1, 'desc'],
                    "columnDefs": [{
                        "targets": 'no-sort',
                        "orderable": false,
                    }],
                    ajax: {
                        url: '/admin/autolikesProfileAjaxDatatables',
                        data: function (d) {
                            d.endDate = endDate;
                            d.dailyPost = dailyPost;
                            d.totalPost = totalPost;
                            d.services = services;
                            d.method = "withFilter";
                        }
                    },
                    columns: [
                        {data: 'check', name: 'check', orderable: false, searchable: false},
                        {data: 'id', name: 'id'},
                        {data: 'addedBy', name: 'addedBy'},
                        {data: 'instagramProfile', name: 'instagramProfile'},
                        {data: 'serverType', name: 'serverType'},
                        {data: 'PostDoneAndTotalPost', name: 'PostDoneAndTotalPost'},
                        {data: 'startDate', name: 'startDate'},
                        {data: 'endDate', name: 'endDate'},
//                    {data: 'lastCheck', name: 'lastCheck'},
//                    {data: 'lastDelivery', name: 'lastDelivery'},
                        {data: 'status', name: 'status'},
                        {data: 'details', name: 'details'},
                    ]
                });
            });
            var bondId;
            $(document.body).on('click', '.details', function () {
                bondId = $(this).attr('data-id');
                console.log(bondId);
                $.ajax({

                    url: '/admin/autolikesProfileAjaxHandler',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        id: bondId,
                    },
                    success: function (response) {
//alert(response);
                        response = $.parseJSON(response);
//                        alert(response['data']);
//                        alert(response.data);
                        console.log(response.lastCheck);
                        lastCheck = '<td><strong>' + response.lastCheck + '</strong></td>';
                        lastDelivery = '<td><strong>' + response.lastDelivery + '</strong></td>';
//                        addedTime = '<td><strong>' + response.addedTime + '</strong></td>';
//                        updatedTime = '<td><strong>' + response.updatedTime + '</strong></td>';

//                            toastr.success('this is simply to waste your time, nothing else!!!');
//                            $("#test").html(response.responseText);
//                        alert(response.data);
                        var trHTML = '';
//                        alert(response.data['ins_username']);
//                        $.each(response.data, function (i, o) {
//                                    alert(o.ins_username);
                        username = '<td><strong>' + response.data[0]['username'] + '</strong></td>';
                        email = '<td><strong>' + response.data[0]['email'] + '</strong></td>';
                        instagramProfile = '<td><strong><a class="btn btn-xs default text-case link-width" target="_blank" href="https://instagram.com/' + response.data[0]['ins_username'] + '">' +
                                '<i class="fa fa-instagram" style="font-size:10px"></i>' + response.data[0]['ins_username'] + '</a></strong></td>';
                        if (response.data[0]['last_delivery_link'] != null)
                            lastDeliveredLink = '<td><strong><a href="' + response.data[0]['last_delivery_link'] + '" target="_blank">' + response.data[0]['last_delivery_link'] + '</a></strong></td>';
                        else
                            lastDeliveredLink = '-';
                        quantityPerPost = '<td><strong>' + response.data[0]['likes_per_pic'] + '</strong></td>';
//                            lastCheck = '<td><strong>' + o.quantity_done + '</strong></td>';
//                            lastDelivery = '<td><strong>' + status + '</strong></td>';
                        if (response.data[0]['profile_pic'] != null) {
                            profile_pic = ' <img src="' + response.data[0]['profile_pic'] + '" class="img-responsive img-circle" />';
                            $('#avatar').html(profile_pic);
                        } else if (response.data[0]['profile_pic'] == null) {
                            profile_pic = ' <img src="/images/avatar.png" class="img-responsive img-circle" />';
                            $('#avatar').html(profile_pic);
                        }
//                            $('#viewTable').append(trHTML);

                        $('#username').html(username);
                        $('#email').html(email);
                        $('#instagramProfile').html(instagramProfile);
                        $('#lastDeliveredLink').html(lastDeliveredLink);
                        $('#quantityPerPost').html(quantityPerPost);
//                            $('#quantityDone').html(quantityDone);
//                            $('#startTime').html(startTime);
//                            $('#endTime').html(endTime);
//                            $('#status').html(status);
                        $('#lastCheck').html(lastCheck);
                        $('#lastDelivery').html(lastDelivery);
//                        });
                    }
                });
            });

            $(document).on("click", ".orderDetails", function () {
                alert(bondId);
                $.ajax({
                    url: '/admin/viewAllOrders',
                    datatype: 'json',
                    data: {forUserId: bondId},
                    success: function (response) {
                        response = $.parseJSON(response);
                        var tableRow = '';
                        $('#tableRow').text("");
                        var status;
                        var id = 1;
                        $.each(response.data, function (index, value) {
                            console.log(value.ins_url);
                            if (value.status == 0)
                                status = '<span style="color:pink">Pending</span>';
                            else if (value.status == 1 || value.status == 2)
                                status = '<span style="color: blue">Processing</span>';
                            else if (value.status == 3)
                                status = '<span style="color:green">Completed</span>';
                            else if (value.status == 4 || value.status == 5 || value.status == 6)
                                status = '<span style="color:red">Cancelled</span>';
                            tableRow += '<tr><td>' + id + '.' + '</td><td><a href="' + value.ins_url + '" target="_blank">' + value.ins_url + '</a></td><td>' + status + '</td ></tr>';
                            ++id;
                        });
                        $('#tableRow').append(tableRow);
                    }
                });
            });

            $(document.body).on('click', '#actionSubmit', function () {
                toastr.options.positionClass = "toast-top-center";
                toastr.options.preventDuplicates = true;
                toastr.options.closeButton = true;
                toastr.options.progressBar = true;
                var id = [];
                var count = 0;
                $.each($("input[name='checkbox']:checked"), function () {
                    count++;
                    id.push($(this).val());
//                    console.log(value);
                });
                console.log(count);
                $('#displaySelectedRecord').html(count + " records selected");
                if (count == 0) {
                    toastr.options.positionClass = "toast-top-center";
                    toastr.error("No order has selected");
                } else {
                    console.log(id);

                    var value = $("#selectAction option:selected").val();
                    if (value == 0) {
//                    alert('Please select an action');
                        toastr.options.positionClass = "toast-top-right";
                        toastr.error("Please select an action");
                    } else {
                        if (value == 3) {
//                        alert('hey');
                            var planId = 0;
                            $('#modalChangeServer').dialog('open');
                            $("#changeServerSubmit").button().on("click", function () {
//                            event.preventDefault();
                                planId = $('#changeServer option:selected').val();
//                                $(this).dialog("close");
                                $('#modalChangeServer').dialog('close');
//                                alert(planId);
                                if (planId == 0) {
                                    var flag = 0;
                                    alert('please select one plan');
                                }
                                else {
                                    $.ajax({
                                        url: '/admin/autolikesSelectAction',
                                        type: 'post',
                                        datatype: 'json',
                                        data: {
                                            id: id,
                                            action: value,
                                            planId: planId

                                        },
                                        success: function (response) {
                                            response = $.parseJSON(response);
                                            if (response['status'] == '200')
                                                toastr.success(response.message);
                                            else if (response['status'] == '400')
                                                toastr.error(response.message);
                                        }
                                    });
                                }
                            });
                        }
                        if (value != 3) {
                            $.ajax({
                                url: '/admin/autolikesSelectAction',
                                type: 'post',
                                datatype: 'json',
                                data: {
                                    id: id,
                                    action: value
                                },
                                success: function (response) {
//                                response = $.parseJSON(response);
//                                if (response['status'] == '200') {
//                                    toastr.success('Order has successfully cancelled and amount has been refunded to the account.', {timeOut: 5000});
//                                    location.reload();
//                                }
                                    response = $.parseJSON(response);
                                    if (response['status'] == '200') {
                                        toastr.success(response.message);
//                                    for (var i = 0; i < count; i++) {
//                                        if (response.message[i].charAt(0) == 'O') {
//                                            toastr.success(response.message[i]);
//                                        } else
//                                            toastr.error(response.message[i], {timeOut: 9000});
//                                    }
////                                                alert(response.message);
//                                    setTimeout(function () {
//                                        location.reload();
//                                    }, 7000);
                                    }
//                                else if (response['status'] == '600') {
//                                    toastr.success(response.message);
//                                    location.reload();
//
//                                }
                                    else if (response['status'] == '400') {
                                        toastr.error(response.message);
                                    }
                                }
                            });
                        }
                    }
                }
            });
            $('#groupCheckBox').click(function (event) {

                //$(".orderCheckBox").prop('checked', $(this).prop("checked"));

                if (this.checked) {
                    console.log('group box checked');
                    $('.autolikesCheckBox').each(function () {
                        this.checked = true;
                    });
                    var recordCount = $(".autolikesCheckBox").length;
                    $('#displaySelectedRecord').html(recordCount + " records selected ");
                } else {
                    console.log('group box un checked');
                    $('.autolikesCheckBox').each(function () {
                        this.checked = false;
                    });
                    $('#displaySelectedRecord').html("");
                }
            });
        });
    </script>


@endsection