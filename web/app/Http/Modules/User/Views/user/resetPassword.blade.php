<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    @include('User/Layouts/userheadscripts')
            <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="/assets/css/login.css" rel="stylesheet"/>
    <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
    <link href="/assets/css/plugins-md.css" rel="stylesheet"/>
    <link href="/assets/css/layout.css" rel="stylesheet"/>
    <link href="/assets/css/light.css" rel="stylesheet" id="style_color"/>
    <link href="/assets/css/custom.css" rel="stylesheet"/>
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->


{{--<div class="login">--}}
{{--<form method="post" action="/user/login" style="margin-bottom: 3%;">--}}
{{--<div class="">--}}
{{--@if (count($errors) > 0)--}}
{{--<div>--}}
{{--<ul>--}}
{{--@foreach ($errors->all() as $error)--}}
{{--<li style="color: red; font-size: 14px; text-align: center">{{ $error }}</li>--}}
{{--@endforeach--}}
{{--</ul>--}}
{{--</div>--}}
{{--@endif--}}

{{--</div>--}}
{{--<h5 style="color: green; text-align: center;">@if(isset($passwordChangeSuccessMessage)) {{$passwordChangeSuccessMessage}} @endif</h5>--}}
{{--<h5 style="color: green; text-align: center;">@if(isset($registerSuccesMessage)) {{$registerSuccesMessage}} @endif</h5>--}}

{{--<input type="text" name="emailOrUsername" placeholder="Email or Username" value="{{old('emailOrUsername')}}"--}}
{{--required/>--}}
{{--<span class="error" style="color: red;">{!! $errors->first('emailOrUsername') !!}</span>--}}

{{--<input type="password" name="password" placeholder="Password" required/>--}}
{{--<span class="error" style="color: red;">{!! $errors->first('password') !!}</span><br>--}}

{{--<input type="checkbox" name="remember" style="margin-bottom: 5%;"/> <span class="text-white">Remember Me</span>--}}
{{--<a href="/user/forgotPassword" class="pull-right text-white">Forgot Password?</a>--}}
{{--<button type="submit" class="btn btn-primary btn-block btn-large">Login</button>--}}
{{--</form>--}}
{{--<span class="text-white" style="margin-top:3%;">Not a member ?? <a href="/user/register" class="text-info">SIGNUP</a></span>--}}
{{--</div>--}}

<body class="page-md login">
<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->

<!-- BEGIN LOGO -->
<div class="logo">
    <a href="/user/login">
        <strong> I N S T A P A N E L </strong>
    </a>
</div>
<!-- END LOGO -->

<div class="content">
    <!-- BEGIN FORGOT PASSWORD FORM -->


    <form class="" role="form" method="post" id="resetPasswordForm" style="margin-bottom: 3%">


        <h3 class="form-title">Reset Password</h3>
        @if (count($errors) > 0)
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color: red; font-size: 14px; text-align: center">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
                   name="newPassword" id="newPassword" placeholder="Enter New Password" required/>
        </div>


        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Re-Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
                   name="conformNewPassword" id="conformNewPassword" placeholder="Enter your conform Password"
                   required/>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase">Reset Password</button>
        </div>

    </form>
    <!-- END FORGOT PASSWORD FORM -->
</div>
<div class="copyright">
    2016 &copy; InstaPanel
</div>


@include('User/Layouts/usercommonfooterscripts')
        <!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/js/validate/jquery.validate.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
{{--<script src="/assets/js/login.js"></script>--}}
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
//        Login.init();
        Demo.init();
    });
</script>
<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>

