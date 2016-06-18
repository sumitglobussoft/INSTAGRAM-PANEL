@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
        <!-- BEGIN PAGE LEVEL STYLES -->

<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
<link href="/assets/css/plugins-md.css" rel="stylesheet"/>
<link href="/assets/css/layout.css" rel="stylesheet"/>
<link href="/assets/css/default.css" rel="stylesheet" id="style_color"/>
<link href="/assets/css/profile.css" rel="stylesheet"/>
<link href="/assets/css/custom.css" rel="stylesheet"/>
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico"/>


@endsection
@section('classMyAccount','active')
@section('classMyAccount5','active')
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
                <h1>Change Password</h1>
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
                <a href="/user/changePassword">Change Password</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                {{--<div class="note note-danger note-shadow">--}}
                {{--<p>--}}
                {{--NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only.--}}
                {{--</p>--}}
                {{--</div>--}}
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Change Password</span>
                            <span class="caption-helper">manage your credential...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabs-left row">
                            <div class="col-md-12 col-xs-12">
                                <div id="e">
                                    <div class="row" style="margin-top:3%;">
                                        <div class="col-md-12">
                                            <form class="" role="form" id="changePassword">
                                                <div class="form-group">
                                                    <label class="">Old Password</label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span
                                                                    class="fa fa-unlock"></span></div>
                                                        <input type="password" class="form-control" id="oldPassword"
                                                               name="oldPassword"
                                                               placeholder="Your Current Password" value=""/>
                                                    </div>
                                                    <span style="color: red" id="oldPasswordError"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label class="">New Password</label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="fa fa-lock"></span>
                                                        </div>
                                                        <input type="password" class="form-control" id="newPassword"
                                                               name="newPassword" placeholder="New Password"
                                                               value=""/>
                                                    </div>
                                                    <span style="color: red" id="newPasswordError"></span>
                                                </div>
                                                <div class="form-group">
                                                    <label class="">Repeat New Password</label>

                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span
                                                                    class="fa fa-unlock-alt"></span>
                                                        </div>
                                                        <input type="password" class="form-control"
                                                               id="conformNewPassword"
                                                               name="conformNewPassword"
                                                               placeholder="Confirm New Password" value=""/>
                                                    </div>
                                                    <span style="color: red" id="conformNewPasswordError"></span>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-success" type="submit"
                                                            id="submitUpdatePassword"><i
                                                                class="fa fa-arrow-circle-right"></i> Save Settings
                                                    </button>
                                                    <button class="btn default" style="margin-left:1%;" type="button">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
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
<script src="/assets/js/validate/jquery.validate.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>

<!--BEGIN CUSTOM PAGE LEVEL SCRIPT-->
<script>
    $('#changePassword').validate({
        rules: {
            oldPassword: {
                required: true
            },
            newPassword: {
                required: true
            },
            conformNewPassword: {
                required: true,
                equalTo: "#newPassword"
            }
        },
        messages: {
            conformNewPassword: " Enter Confirm Password Same as Password"
        },
        //               errorPlacement: function (error, element) {
//                    if (element.attr("name") == "oldPassword") {
//                        if ($(error).html() != '')
//                            $('#oldPasswordError').html($(error).html());
//                        else
//                            $('#oldPasswordError').html("");
//                    }
//                    if (element.attr("name") == "newPassword") {
//                        if ($(error).html() != '')
//                            $('#newPasswordError').html($(error).html());
//                        else
//                            $('#newPasswordError').html("");
//                    }
//                    if (element.attr("name") == "conformNewPassword") {
//                        if ($(error).html() != '')
//                            $('#conformNewPasswordError').html($(error).html());
//                        else
//                            $('#conformNewPasswordError').html("");
//                    }
        //               },

        submitHandler: function (form) {
            console.log("Form validate successful");
            $('#oldPasswordError').html('');
            $('#newPasswordError').html('');
            $('#conformNewPasswordError').html('');
            var passwordData = $('#changePassword').serializeArray();
            passwordData.push({name: 'userId', value: '{{Auth::User()->id}}'});
            $.ajax({
                type: "POST",
                url: "/user/changePassword",
                dataType: "json",
                data: passwordData,
                success: function (response) {
                    console.log(response);
                    var alertMsg = '';
                    if (response['status'] == 1) {
                        alertMsg += response['successMessage']
                    } else if (response['status'] == 0) {
                        if ($.isArray(response['errorMessage'])) {
                            $.each(response['errorMessage'], function (index, value) {
                                alertMsg += value + '\n';
                            })
                        } else {
                            alertMsg = response['errorMessage'];
                        }
                    }
                    alert(alertMsg);
                    $('#changePassword').trigger("reset");
                },
                error: function (xhr, status, err) {
                    console.log(err);
                    $('#changePassword').trigger("reset");
                }
            });
        }
    });
</script>
<!--END CUSTOM PAGE LEVEL SCRIPT-->
@endsection



