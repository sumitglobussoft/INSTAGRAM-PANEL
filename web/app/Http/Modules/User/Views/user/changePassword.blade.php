@extends('User/Layouts/userlayout')

@section('title','Dashboard')


@section('headcontent')
{{--OPTIONAL--}}
{{--PAGE STYLES OR SCRIPTS LINKS--}}

@endsection

@section('content')
{{--PAGE CONTENT GOES HERE--}}

        <!-- Right-Page-content Start-->
<section id="right-content-wrapper">
    <section class="page-header alternative-header">
        <ol class="breadcrumb">
            <li>IP User</li>
            <li>My Account</li>
            <li>Over view</li>
        </ol>
    </section>

    <section class="page-content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-default panel-divider">
                    <div class="panel-body" style="padding-top: 0;">
                        <div class="tab-pane">
                            <h3> Password Update </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <form class="" role="form" id="changePassword">
                                        <div class="form-group">
                                            <label class="">Old Password</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-unlock"></span></div>
                                                <input type="password" class="form-control" id="oldPassword"
                                                       name="oldPassword"
                                                       placeholder="Your Current Password" value=""/>
                                            </div>
                                            <span style="color: red" id="oldPasswordError"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="">New Password</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-lock"></span></div>
                                                <input type="password" class="form-control" id="newPassword"
                                                       name="newPassword" placeholder="New Password"
                                                       value=""/>
                                            </div>
                                            <span style="color: red" id="newPasswordError"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Repeat New Password</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-unlock-alt"></span>
                                                </div>
                                                <input type="password" class="form-control" id="conformNewPassword"
                                                       name="conformNewPassword"
                                                       placeholder="Confirm New Password" value=""/>
                                            </div>
                                            <span style="color: red" id="conformNewPasswordError"></span>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-success" type="submit" id="submitUpdatePassword"><i
                                                        class="fa fa-arrow-circle-right"></i> Save Settings
                                            </button>
                                            <button class="btn default" style="margin-left:1%;" type="button">Cancel
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
    </section>
</section>

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
    <script type="text/javascript">
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
@endsection



