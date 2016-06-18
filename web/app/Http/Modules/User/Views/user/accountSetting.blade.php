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
@section('classMyAccount4','active')
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
                <h1>Account Settings</h1>
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
                <a href="/user/updateProfileInfo">Account Settings</a>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Account Settings</span>
                            <span class="caption-helper">Update your profile...</span>
                        </div>
                    </div>
                    @if(Session::has('message'))
                        @if(session('status')=='success')
                            <div style="color: green">{{Session::get('message')}}</div>
                        @elseif(session('status')=='error')
                            <div class="error" style="color: red">{{Session::get('message')}}</div>
                        @endif
                    @endif
                    <div class="portlet-body">

                        <div class="row" style="margin-top:3%;">
                            <div class="col-md-12">
                                <form class="" role="form" id="accountSetting" method="post">
                                    {{--<h4> General Informatiom </h4>--}}

                                    <div class="form-group">
                                        <label class="">First name</label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-user"></span>
                                            </div>
                                            <input type="text" class="form-control" id="firstname" name="firstname"
                                                   placeholder="" required
                                                   value="{{$userData['name']}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="">Last name</label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-user"></span>
                                            </div>
                                            <input type="text" class="form-control" id="lastname" name="lastname"
                                                   placeholder=""
                                                   value="{{$userData['lastname']}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="">Username</label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-user"></span>
                                            </div>
                                            <input type="text" class="form-control" id="username" name="username"
                                                   placeholder="" required
                                                   value="{{$userData['username']}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="">Skype</label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-skype"></span></div>
                                            <input type="text" class="form-control" placeholder="Skype Username"
                                                   id="skypeUsername" name="skypeUsername"
                                                   value="{{$userData['skype_username']}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="">Email Address</label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-envelope"></span>
                                            </div>
                                            <input type="text" class="form-control" id="email" name="email"
                                                   placeholder="" required
                                                   value="{{$userData['email']}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="submit" id="save-info-changes"><i
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
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
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
<!--BEGIN CUSTOME PAGE LEVEL SCRIPT-->

<script>
    $('#accountSetting').validate({
        rules: {
            firstname: {required: true},
            lastname: {required: true},
            username: {required: true},
            email: {required: true, email: true}
        },
        messages: {
            firstname: {
                required: "Please enter first name"
            },
            lastname: {
                required: "Please enter last name"
            },
            username: {required: "Please Enter Username"},
            email: {required: "Please Enter your email-Id", email: "Please enter a valid email-Id"},
        },
        submitHandler: function (form) {
            form.submit();
        }
    });
</script>
<!--END CUSTOME PAGE LEVEL SCRIPT-->
@endsection



