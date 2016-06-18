@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
        <!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="/assets/css/select2.css"/>
<link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap.css"/>
<link rel="stylesheet" href="/assets/css/toastr/toastr.css"/>

<link href="/assets/css/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
<link href="/assets/css/plugins-md.css" rel="stylesheet"/>
<link href="/assets/css/layout.css" rel="stylesheet"/>
<link href="/assets/css/default.css" rel="stylesheet" id="style_color"/>
{{--<link href="/assets/css/profile.css" rel="stylesheet" />--}}
<link href="/assets/css/custom.css" rel="stylesheet"/>
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico"/>

@endsection
@section('classMarket','active')
@section('classMarket3','active')
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
                <h1>Instagram Auto-Likes </h1>
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
                <a href="/user/addAutolikesOrder">Instagram Auto-Likes</a>
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
                            <span class="caption-helper">Check your current usernames with auto-likes
                                    subscription...</span>
                        </div>
                        <div style="float: right">
                            <button class="btn btn-primary pull-right" data-toggle="modal"
                                    data-target="#addOrders" onclick="resetForm()"><i class="fa fa-plus-circle"></i> Add
                                username
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-success " id="messageArea" hidden>
                        <button class="close" data-close="alert"></button>
                        <span class="alert-message-content" style="text-align: center;"></span>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper">
										<span>
									</span>
                                <select class="table-group-action-input form-control input-inline input-small input-sm">
                                    <option value="">Select Action</option>
                                    <option value="restart_daily_counter">Restart Daily Counter</option>
                                    <option value="restart_total_counter">Restart Total Counter</option>
                                    {{--<option value="change_server">Change Server</option>--}}
                                    <option value="force_check">Force to check the profile for new posts</option>
                                    <option value="remove_user">Remove from system</option>
                                </select>
                                <button class="btn btn-sm yellow table-group-action-submit"><i
                                            class="fa fa-check"></i> Submit
                                </button>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                <tr role="row" class="heading">
                                    <th width="1%"><input type="checkbox" class="group-checkable"></th>
                                    <th width="10%">#ID</th>
                                    <th width="15%">Insta Profile</th>
                                    <th width="15%">Server Type</th>
                                    <th width="10%">Quantity/post</th>
                                    <th width="10%">Post Done</th>
                                    <th width="10%">Total Posts</th>
                                    <th width="6%">Start Date</th>
                                    <th width="6%">End Date</th>
                                    <th width="6%">Last Check</th>
                                    {{--<th width="6%">Last Delivery</th>--}}
                                    <th width="10%">Status</th>
                                    <th width="20%">Details</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_id" style="width:70px"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_username"></td>
                                    <td>
                                        <select name="search_service_type"
                                                class="form-control form-filter input-sm">
                                            <option value=""> Select...</option>
                                            @if(isset($planList))
                                                @foreach($planList as $plan)
                                                    <option value="{{$plan['plan_id']}}">{{$plan['plan_name']}}</option>
                                                @endforeach
                                            @else
                                                <option value="">There are currently no any active services</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_pics_likes"></td>
                                    <td><select name="search_daily_post_limit"
                                                class="form-control form-filter input-sm">
                                            <option value="">Daily Post Limit</option>
                                            <option value="0">Yes</option>
                                            <option value="1">No</option>
                                            <option value="2">Reached</option>
                                        </select></td>

                                    <td><select name="search_total_post_reached"
                                                class="form-control form-filter input-sm">
                                            <option value="">Total Post Reached</option>
                                            <option value="0">Yes</option>
                                            <option value="1">No</option>
                                            <option value="2">Left less than 10%</option>
                                        </select></td>

                                    {{--<td><input type="text" class="form-control form-filter input-sm"--}}
                                    {{--name="search_pics_done"></td>--}}
                                    {{--<td><input type="text" class="form-control form-filter input-sm"--}}
                                    {{--name="search_pics_limit"></td>--}}
                                    {{--<td><input type="text" class="form-control form-filter input-sm"--}}
                                    {{--name="search_pics_likes"></td>--}}
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_start_date" disabled="disabled"></td>
                                    <td><select name="search_end_date" class="form-control form-filter input-sm">
                                            <option value="">Expiration Date</option>
                                            <option value="0">Expired</option>
                                            <option value="1">Expiring the next 24-48 hours</option>
                                            <option value="2">Expiring the next 5 days</option>
                                        </select></td>
                                    {{--<td><input type="text" class="form-control form-filter input-sm"--}}
                                    {{--name="search_end_date" disabled="disabled"></td>--}}

                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_last_check" disabled="disabled"></td>
                                    {{--<td><input type="text" class="form-control form-filter input-sm"--}}
                                    {{--name="search_last_delivery" disabled="disabled"></td>--}}
                                    <td><select name="search_status" class="form-control form-filter input-sm">
                                            <option value="">Select...</option>
                                            <option value="0">Failed</option>
                                            <option value="1">Finished</option>
                                            <option value="2">Waiting</option>
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


<!-- Modal HTML -->
<div id="addOrders" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-green-sharp bold uppercase"><i class="fa fa-plus-circle"></i>&nbsp;Add New
                    Username</h4>
            </div>
            <div class="modal-body">
                <form class="form" role="form" id="addUsernameForm">
                    <div class="form-group floating-label">
                        <label for="instagramUsername">Instagram Username without @</label>
                        <input type="text" class="form-control " name="instagramUsername" id="instagramUsername"
                               placeholder="Enter only Instagram username" required/>
                    </div>

                    <div id="radioButton">
                        <input type="radio" name="orderType"
                               id="autolikes" class="bond form-control"
                               value="autolikes" checked>
                        <label class="control-label" for="autolikes"
                               style="margin-left: 1%;"> AutoLikes Subscription</label>
                        <input type="radio" name="orderType"
                               id="autoviews"
                               class="bond form-control"
                               value="autoviews" style="margin-left: 1.5%;">
                        <label class="control-label"
                               for="autoviews"
                               style="margin-left: 1%;"> AutoViews Subscription</label>
                    </div>

                    <div class="autolikes">
                        <div class="form-group floating-label">
                            <label for="likesPerPic">The Amount of likes to send every new post</label>
                            <input type="number" class="form-control" name="likesPerPic" id="likesPerPic"
                                   placeholder="Number of likes to send every new picture." required/>
                        </div>

                        <div class="form-group floating-label">
                            <label for="picLimit">Stop After X Post(s) got Likes</label>
                            <input type="number" class="form-control" name="picLimit" id="picLimit"
                                   placeholder="Number of post(s) to get likes (Pics Limit)." required/>
                        </div>


                        <div class="form-group floating-label">

                            <label for="planId">Please choose type of likes </label>
                            <select id="planId" name="planId" class="form-control" required>
                                <option value="" disabled>Please select type of service.</option>
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
                    </div>

                    <div class="autoviews" hidden>
                        <div class="form-group floating-label">
                            <label for="viewsPerVideo">The Amount of views to send every new video</label>
                            <input type="number" class="form-control" name="viewsPerVideo" id="viewsPerVideo"
                                   placeholder="Number of views to send every new video." required/>
                        </div>

                        <div class="form-group floating-label">
                            <label for="videoLimit">Stop After X video(s) got views</label>
                            <input type="number" class="form-control" name="videoLimit" id="videoLimit"
                                   placeholder="Number of video(s) to get views (Videos Limit)." required/>
                        </div>

                        <div class="form-group floating-label">

                            <label for="viewplanId">Please choose type of Views </label>
                            <select id="viewplanId" name="viewplanId" class="form-control" required>
                                <option value="" disabled>Please select type of service.</option>
                                @if(isset($planList))
                                    @foreach($planList as $plan)
                                        @if($plan['plan_type']==4 )
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
                    </div>

                    <div class="form-group floating-label">
                        <label for="dailyPostLimit">Daily Post Limit <span style="font-size: 12px"> &nbsp;( Value 0 for unlimited daily posts.) </span></label>
                        <input type="number" class="form-control" name="dailyPostLimit" id="dailyPostLimit"
                               placeholder="Number of picture(s) to get likes in a day." required/>
                    </div>

                    <div class="form-group floating-label">

                        <input type="checkbox" name="orderDelay"
                               id="orderDelay"
                                > &nbsp;&nbsp;
                        <label class="control-label" for="orderDelay">Add 10 mins delay for each posts. </label>&nbsp;&nbsp;
                        <a data-toggle="popover" data-placement="bottom" title="Information"
                           data-content="Every new post will process after 10 minutes delay so that user can cancel the order before its being added to the server.">
                            <i class="fa fa-question-circle"></i>
                        </a>
                    </div>


                    {{--split option--}}
                    <div class="form-group floating-label">

                        <div class="form-group" id="splitTotalAmount">
                            <input type="checkbox" name="splitTotalAmounts"
                                   id="splitTotalAmounts"
                                   class="form-control"> &nbsp;&nbsp;
                            <label class="control-label" for="splitTotalAmounts" id="custom_split_msg">I want to split
                                the Amount of Likes.</label>
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
                    </div>


                    <div class="form-group floating-label">

                        <input type="checkbox" name="autolikesSubscription"
                               id="autolikesSubscription"
                                > &nbsp;&nbsp;
                        <label class="control-label" for="autolikesSubscription">I want to set start date and end date
                            for autolikes profile. </label>


                        <div id="autolikesSubscriptionOption" style="display:inline-flex; width: 100%;">
                            <div class="clearfix"></div>

                            <div class="form-group  col-md-6 input-group date form_datetime">
                                <input type="text" size="16" readonly class="form-control" name="startDate"
                                       id="startDate"
                                       placeholder="Start date">
                                <span class="input-group-btn">
                                    <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>

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
                        <label for="autoComments">Add Auto Comments also?</label>
                        <select id="autoComments" name="autoComments" class="form-control" required>
                            {{--<option value="" selected></option>--}}
                            <option value="NO" selected>No</option>
                            <option value="YES">Yes</option>
                        </select>
                    </div>

                    <div class="form-group floating-label" id="autoCommentsArea" hidden>
                        <label for="autoCommentPlanId">Please select the service</label>
                        <select id="autoCommentPlanId" name="autoCommentPlanId" class="form-control" required>
                            <option value="" disabled>Please select comments type.</option>
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

                    <div class="form-group floating-label" id="autoCustomCommentsArea" hidden>
                        <label for="customCommentGroupId">Please select the comment group type</label>
                        <select id="customCommentGroupId" name="customCommentGroupId" class="form-control" required>
                            <option value="" disabled>Select comments from CommentGroup</option>
                            @if(isset($commentListData))
                                @foreach($commentListData as $list)
                                    <option value="{{$list['comment_group_id']}}">{{$list['comment_group_name']}}</option>
                                @endforeach
                            @else
                                <option value="">There are currently no any active services</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group floating-label" id="autoCommentAmountArea" hidden>
                        <label for="autoCommentAmount">The amount of comments to send every new post</label>
                        <input type="number" class="form-control" name="autoCommentAmount" id="autoCommentAmount"
                               placeholder="Number of comments to send every new picture." required/>
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

<!--Modal for edit username-->
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


                    <div class="form-group floating-label">
                        <label for="edit_planId">Please choose type of likes </label>
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

<!--Modal for details-->
<div id="showDetails" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Information</h4>
            </div>
            <div class="modal-body">
                <label> <b>Last Delivery Time : </b></label>&nbsp; <span id="details_lastDelivery_time">null</span>
                <br/>
                <label> <b>Last Delivery Link : </b></label>&nbsp; <span id="details_lastDelivery_link">null</span>
                <br/>
                <label><b>Likes and Comments sent : </b></label>&nbsp; <span id="details_likes_count"></span>/
                <span id="details_comments_count">0</span>
                <br/>
                <label><b>Pictures Done :</b></label>&nbsp; <span id="details_picsDone_count">0</span>
                <br/>
                <label><b>Message :</b></label>&nbsp; <span id="details_message">This area is for some messages which has to be shown for the orders.</span>
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
{{--<script src="/assets/js/bootstrap-datepicker.js"></script>--}}

<script src="/assets/js/datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script src="/assets/js/toastr/toastr.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<script src="/assets/js/datatable.js"></script>
{{--<script src="/assets/js/table-ajax.js"></script>--}}

<script src="/assets/js/datetimepicker/components-pickers.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
        TableAjax.init();
        ComponentsPickers.init();

        toastr.options.positionClass = "toast-top-center";
        toastr.options.preventDuplicates = true;
        toastr.options.closeButton = true;
    });
</script>
<script>
    //DataTable
    $('#datatable').dataTable();
    $(window).load(function () {
        $('#datatable_filter input, #datatable_length select').addClass('form-control');
        $('#datatable_length').addClass('form-group');

        $('#autolikesSubscriptionOption').hide();
    });
    $('[data-toggle=popover]').popover({
        content: $('#myPopoverContent').html(),
        html: true
    }).mouseover(function () {
        $(this).popover('show');
    }).mouseleave(function () {
        $(this).popover('hide');
    });
</script>

<!--BEGIN CUSTOM PAGE LEVEL SCRIPT-->
<script type="text/javascript">

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
                        "url": "/user/autolikeOrderHistoryAjax" // ajax source
                    },


                    "columnDefs": [
                        {orderable: false, targets: 0},
                        {orderable: false, targets: -1}
                    ],

                    "order": [
                        [1, "desc"]
                    ]// set first column as a default sort by asc
                }
            });

            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
                e.preventDefault();
                var action = $(".table-group-action-input", grid.getTableWrapper());
                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    grid.setAjaxParam("customActionType", "group_action");
                    grid.setAjaxParam("customActionName", action.val());
                    grid.setAjaxParam("insUserId", grid.getSelectedRows());
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
        }
        return {
            //main function to initiate the module
            init: function () {
                handleRecords();
            }
        };
    }();

    //        add new username
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
    $("#dailyPostLimit").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            toastr.error('Please enter integer value only.', {timeOut: 4000});
            $(this).focus();
            return false;
        }
    });

    /*----------------------Split Orders Validation STARTS----------------------------------------------*/

    $('#splitTotalAmounts').change(function (e) {
        e.preventDefault();
        if ($(this).is(':checked')) {
            $('#ordersPerRun').val('');
//            alert($('#ordersPerRun').val(''));
            console.log();
            $('#timeInterval option:selected').removeAttr('selected');
            $('.splitAmountArea').show();
        } else {
            $('#ordersPerRun').val('');
            $('#timeInterval option:selected').removeAttr('selected');
            $('.splitAmountArea').hide();
        }
    });
    $(document.body).on('keypress', '#ordersPerRun', function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            toastr.error('Please enter integer value only.', {timeOut: 4000});
            $(this).focus();
            return false;
        }
    });

    //    var minOrder = '';
    //    var getMinOrderMessage = function getMinOrderMessage() {
    //        return "This attribute value should be greater than min order quantity ( " + minOrder + " ) !";
    //    };
    //    $.validator.addMethod("minAmount", function (value, element) {
    //        var amountPerRun = parseInt($('#ordersPerRun').val());
    //        minOrder = parseInt($('#min_order').text());
    //
    //        if (amountPerRun >= minOrder) {
    //            return true;
    //        } else {
    //            return false;
    //        }
    //    }, getMinOrderMessage);
    //
    //    var maxOrder = '';
    //    var getMaxOrderMessage = function getMaxOrderMessage() {
    //        return "This attribute value should be less than Amount to delivery quantity ( " + maxOrder + " ) !";
    //    };
    //    $.validator.addMethod("maxAmount", function (value, element) {
    //        var amountPerRun = parseInt($('#ordersPerRun').val());
    //        maxOrder = parseInt($('#likesPerPic').val());
    //        if (amountPerRun <= maxOrder) {
    //            return true;
    //        } else {
    //            return false;
    //        }
    //    }, getMaxOrderMessage);
    //
    //
    //    var minOrdersPerRun = '';
    //    var getOrdersPerRunMessage = function getOrdersPerRunMessage() {
    ////        console.log("test"+minOrdersPerRun);
    //        return "This attribute value should be greater than " + minOrdersPerRun + " (Max sub orders is 50)! ";
    //    };
    //    $.validator.addMethod("validateOrdersPerRun", function (value, element) {
    //        var amountPerRun = parseInt($('#ordersPerRun').val());
    //        var quantity = parseInt($('#likesPerPic').val());
    //
    //        minOrdersPerRun = Math.ceil(quantity / 50);
    //
    //        if (amountPerRun < minOrdersPerRun) {
    //            return false;
    //        } else {
    //            return true;
    //        }
    //    }, getOrdersPerRunMessage);

    /*----------------------Split Orders Validation ENDs here----------------------------------------------*/

    $('#autolikesSubscription').change(function (e) {
        e.preventDefault();
        if ($(this).is(":checked")) {
            $('#autolikesSubscriptionOption').show();
        } else {
            $('#autolikesSubscriptionOption').hide();
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
        if ((autoCommentPlanId.attr('data-planType') == 3) && (autoCommentPlanId.attr('data-supplierServerId')) == 1) {
            $('#autoCustomCommentsArea').show();
        } else {
            $('#autoCustomCommentsArea').hide();
        }

        var chargePer1K = autoCommentPlanId.attr('data-chargePer1K');
        var chargePerUnit = (chargePer1K / 1000).toFixed(4);
        $('#comment_rate').text(chargePerUnit);


    });


    function resetForm() {
        $('#instagramUsername').val('');

        if ($('.bond').val() == "autolikes") {
            $('#likesPerPic').val('');
            $('#picLimit').val('');
        } else {
            $('#viewsPerVideo').val('');
            $('#picLimit').val('');
        }
        $('#dailyPostLimit').val('');
    }


    $(document.body).on('click', '#submitButton', function (e) {
        e.preventDefault();

        var username = $('#instagramUsername').val();
        var likesPerPic = $('#likesPerPic').val();
        var picLimit = $('#picLimit').val();
        var dailyPostLimit = $('#dailyPostLimit').val();
        var planId = $('#planId option:selected').val();
        var minAmount = $('#planId option:selected').attr('data-minQuantity');
        var maxAmount = $('#planId option:selected').attr('data-maxQuantity');
        var autoComments = $('#autoComments option:selected').val();
        var autoCommentPlanId = $('#autoCommentPlanId option:selected').val();
        var customCommentGroupId = $('#customCommentGroupId option:selected').val();
        var autoCommentAmount = $('#autoCommentAmount').val();
        var maxCommentAmount = $('#autoCommentPlanId option:selected').attr('data-maxQuantity');
        var minCommentAmount = $('#autoCommentPlanId option:selected').attr('data-minQuantity');
        var ordersPerRun = $('#ordersPerRun').val();
        alert(ordersPerRun);

        if (username == '' || username == null || planId == '' || dailyPostLimit == '') {//|| likesPerPic == '' || picLimit == ''
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
            if (dailyPostLimit == '') {
                $('#dailyPostLimit').focus();
                return false;
            }
        }
        if (parseInt(likesPerPic) < parseInt(minAmount)) {
            toastr.error('Minimum Amount of likes is ' + minAmount + '.', {timeOut: 4000});
            $('#likesPerPic').focus();
            return false;
        }
        if (parseInt(likesPerPic) > parseInt(maxAmount)) {
            toastr.error('Maximum Amount of likes is ' + maxAmount + '.', {timeOut: 4000});
            $('#likesPerPic').focus();
            return false;
        }

//        console.log(dailyPostLimit);
        if (parseInt(dailyPostLimit) > parseInt(picLimit)) {
            toastr.error('Daily post limit should be less than Pic Limit value (' + picLimit + ').', {timeOut: 4000});
            $('#dailyPostLimit').focus();
            return false;
        }

        if (parseInt(ordersPerRun) < parseInt(minAmount)) {
            toastr.error("Amount to delivery per run should be greater than or equal to min quantity (" + minAmount + ").", {timeOut: 4000});
            $('#ordersPerRun').focus();
            return false;
        }
        if (parseInt(ordersPerRun) > parseInt(likesPerPic)) {
            toastr.error("Amount to delivery per run should be less than or equal to likes per pic amount (" + likesPerPic + ").", {timeOut: 4000});
            $('#ordersPerRun').focus();
            return false;
        }
        if (parseInt(ordersPerRun) < (parseInt(likesPerPic) / 50)) {
            toastr.error("Amount to delivery per run should be greater than " + (parseInt(likesPerPic) / 50) + ". (Max sub orders allowed are 50). ")
            $('#ordersPerRun').focus();
            return false;
        }

//        console.log(picLimit);

        if (autoComments == 'YES') {
            if (autoCommentPlanId == '') {
                toastr.error('Please fill all the inputs.', {timeOut: 4000});
                $('#autoCommentPlanId').focus();
                return false;
            } else {
                if (parseInt($('#autoCommentPlanId option:selected').attr('data-planType')) == 3) {
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

            if (parseInt(autoCommentAmount) < 5) {
                toastr.error('Minimum amount of comments to send every new post is 5.', {timeOut: 4000});
                $('#autoCommentAmount').focus();
                return false;
            }

            if (parseInt(autoCommentAmount) > parseInt(maxCommentAmount)) {
                toastr.error('Maximum amount of comments to send every new post is ' + parseInt(maxCommentAmount) + ' .', {timeOut: 4000});
                $('#autoCommentAmount').focus();
                return false;
            }

        }

        var data = $('#addUsernameForm').serializeArray();

        $.ajax({
            url: "/user/addAutolikesOrder",
            data: data,
            dataType: "json",
            method: 'post',
            success: function (response) {
                console.log(response);
                if (response['status'] == 1) {
                    $("#cancelButton").trigger("click");
                    $('#messageArea').show();
                    $('.alert-message-content').text('Username added successfully for autolikes');
//                    setInterval('location.reload()', 4000);
//                    toastr.success('Username added successfully for autolikes');
//                    console.log(response['message']);;
                }
                else if (response['status'] == 0) {
                    var messages = response['message'];
//                    console.log(messages);
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


    //        Edit User order
    $("#edit_likesPerPic").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            toastr.error('Please enter integer value only.', {timeOut: 4000});
            $(this).focus();
            return false;
        }
    });
    $("#edit_picLimit").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            toastr.error('Please enter integer value only.', {timeOut: 4000});
            $(this).focus();
            return false;
        }
    });
    $("#edit_autoCommentAmount").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            toastr.error('Please enter integer value only.', {timeOut: 4000});
            $(this).focus();
            return false;
        }
    });
    $('#edit_autoComments').change(function () {

        if ($('#edit_autoComments option:selected').val() == 'YES') {
            $('#edit_autoCommentsArea').show();
            $('#edit_autoCommentAmountArea').show();
            $('#edit_commentsMessage').show();
        } else {
            $('#edit_autoCommentsArea').hide();
            $('#edit_autoCommentAmountArea').hide();
            $('#edit_autoCustomCommentsArea').hide();
            $('#edit_commentsMessage').hide();
        }
    });
    $('#edit_autoCommentPlanId').change(function () {
        var autoCommentPlanId = $('#autoCommentPlanId option:selected');
        if (autoCommentPlanId.attr('data-planType') == 3) {
            $('#edit_autoCustomCommentsArea').show();
        } else {
            $('#edit_autoCustomCommentsArea').hide();
        }

        var chargePer1K = autoCommentPlanId.attr('data-chargePer1K');
        var chargePerUnit = (chargePer1K / 1000).toFixed(4);
        $('#edit_comment_rate').text(chargePerUnit);

    });
    var ins_user_id; //Modified by Saurabh // Global Variable that will be used in updateUserOrderDetails Ajax also.
    $(document.body).on('click', '.edit-user', function (e) {
        e.preventDefault();

        var user_id = $(this).attr('data-id');
        ins_user_id = user_id;
        console.log(user_id);
        $.ajax({
            url: '/user/getUserPreviousDetails',
            type: 'POST',
            datatype: 'json',
            data: {
                ins_user_id: user_id
            },
            success: function (response) {
                response = $.parseJSON(response);
                $.each(response.data, function (index, value) {

                    $('#ins_username').val(value['ins_username']);
                    $('#edit_likesPerPic').val(value['likes_per_pic']);
                    $('#edit_picLimit').val(value['pics_limit']);
                    $('#edit_planId').val(value['plan_id']);
                    $('#edit_dailyPostLimit').val(value['daily_post_limit']);
                    if (value['order_delay_flag'] == 1)
                        $('input[name="edit_orderDelay"]').prop('checked', true);
                    else
                        $('input[name="edit_orderDelay"]').prop('checked', true);

                    $('#edit_autoCommentPlanId').val(value['plan_id_for_autoComments']);
                    $('#edit_customCommentGroupId').val(value['custom_comment_id']);
                    $('#edit_autoCommentAmount').val(value['comments_amount']);

                });


            },
            error: function (xhr, status, error) {
                console.log(error)
            }
        });

    });

    $(document.body).on('click', '#edit_submitButton', function (e) {
        e.preventDefault();

        var likesPerPic = $('#edit_likesPerPic').val();
        var picLimit = $('#edit_picLimit').val();
        var dailyPostLimit = $('#edit_dailyPostLimit').val();
        var planId = $('#edit_planId option:selected').val();
        var minAmount = $('#edit_planId option:selected').attr('data-minQuantity');
        var autoComments = $('#edit_autoComments option:selected').val();
        var autoCommentPlanId = $('#edit_autoCommentPlanId option:selected').val();
        var customCommentGroupId = $('#edit_customCommentGroupId option:selected').val();
        var autoCommentAmount = $('#edit_autoCommentAmount').val();

        toastr.options.positionClass = "toast-top-center";
        toastr.options.preventDuplicates = true;
        toastr.options.closeButton = true;
        if (likesPerPic == '' || picLimit == '' || planId == '') {
            toastr.error('Please fill all the inputs. (Likes/pic ,Post Limits, Server Type).', {timeOut: 4000});

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
        if (parseInt(likesPerPic) < parseInt(minAmount)) {
            toastr.error('Minimum Amount of likes is ' + minAmount + '.', {timeOut: 4000});
            $('#edit_likesPerPic').focus();
            return false;
        }

        if (autoComments == 'YES') {
            if (autoCommentPlanId == '') {
                toastr.error('Please select custom comment service type.', {timeOut: 4000});
                $('#edit_autoCommentPlanId').focus();
                return false;
            } else {
                if ($('#edit_autoCommentPlanId option:selected').attr('data-planType') == 3) {
                    if (customCommentGroupId == '') {
                        toastr.error('Please select auto comment service type.', {timeOut: 4000});
                        $('#edit_customCommentGroupId').focus();
                        return false;
                    }
                }
            }

            if (autoCommentAmount == '') {
                toastr.error('Please enter amount of comments.', {timeOut: 4000});
                $('#edit_autoCommentAmount').focus();
                return false;
            }

            if (parseInt(autoCommentAmount) < 5) {
                toastr.error('Minimum amount of comments to send every new post is 5.', {timeOut: 4000});
                $('#edit_autoCommentAmount').focus();
                return false;
            }
        }


        var formData = $('#edit_usernameForm').serialize() + '&ins_user_id=' + ins_user_id; //$('.edit-user').attr('data-id')

        console.log(formData);
        $.ajax({
            url: "/user/updateUserOrderDetails",
            data: formData,
            dataType: "json",
            method: 'post',
            success: function (response) {
                console.log(response);
                $('#messageArea').show();
                if (response['status'] == 'success') {
                    $("#edit_cancelButton").trigger("click");
                    $('.alert-message-content').text(response['message']);
                    setInterval('location.reload()', 4000);
                }
                else {
                    $("#edit_cancelButton").trigger("click");
                    $('.alert-message-content').text(response['message']);
                }
            },
            error: function (xhr, status, error) {
                toastr.error(error);
                console.log(error);
            }

        });
    });

    $(document.body).on('click', '.show-details', function (e) {
        e.preventDefault();
        instagramUserId = $(this).closest("tr").find('td:eq(1)').text();
        console.log(instagramUserId);
        $.ajax({
            url: "/user/getMoreAutolikesOrderDetails",
            type: "POST",
            dataType: "json",
            data: {
                instagramUserId: instagramUserId
            },
            success: function (response) {
                console.log(response);

                if (response['status'] == 'success') {
                    $('#details_lastDelivery_time').text('');
                    $('#details_lastDelivery_link').text('');
                    $('#details_likes_count').text('');
                    $('#details_comments_count').text('');
                    $('#details_picsDone_count').text('');
                    $('#details_message').text('');


                    $('#details_lastDelivery_time').text(response['data']['last_delivery']);
                    $('#details_lastDelivery_link').text(response['data']['last_delivered_link']);
                    $('#details_likes_count').text(response['data']['likes_sent']);
                    $('#details_comments_count').text(response['data']['comment_sent']);
                    $('#details_picsDone_count').text(response['data']['pics_done']);
                    $('#details_message').text(response['data']['message']);
                }
                else {
                    $('#details_lastDelivery_time').text('');
                    $('#details_lastDelivery_link').text('');
                    $('#details_likes_count').text('');
                    $('#details_comments_count').text('');
                    $('#details_picsDone_count').text('');
                    $('#details_message').text('');

                    $('#messageArea').show();
                    $('.alert-message-content').text(response['message']);
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
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
<script>
    //code by Saurabh for Autoviews Hide/Show.
    $(document).ready(function () {
        $('.autoviews').hide();
        $(document.body).on('click', '.bond', function () {
            if ($(this).attr("value") == "autoviews") {
                $('.autolikes').hide();
                $('.autoviews').show();
                $('#custom_split_msg').text("I want to split the Amount of Views.");
            }
            if ($(this).attr("value") == "autolikes") {
                $('.autoviews').hide();
                $('.autolikes').show();
                $('#custom_split_msg').text("I want to split the Amount of Likes.");
            }

        });
    });
</script>
<!--END CUSTOM PAGE LEVEL SCRIPT-->

@endsection
