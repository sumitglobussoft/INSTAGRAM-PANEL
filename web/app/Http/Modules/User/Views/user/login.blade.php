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
    <a href="index.html">
        <strong> I N S T A P A N E L </strong>
    </a>
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="" role="form" method="post" action="/user/login">

        <h3 class="form-title">Sign In</h3>

        @if (count($errors) > 0)
            <div class="alert alert-danger ">
                <button class="close" data-close="alert"></button>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li style=" font-size: 14px; text-align: center">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(isset($passwordChangeSuccessMessage))
            <div class="alert alert-success ">
                <button class="close" data-close="alert"></button>
                <h5 style="text-align: center;">{{$passwordChangeSuccessMessage}}</h5>
            </div>
        @endif
        @if(isset($registerSuccesMessage))
            <div class="alert alert-success ">
                <button class="close" data-close="alert"></button>
                <h5 style="text-align: center;">{{$registerSuccesMessage}}</h5>
            </div>
        @endif

        <div class="form-group">
            <input class="form-control placeholder-no-fix" type="hidden" id="user_timezone" name="user_timezone" value="">
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"
                   placeholder="Email or Username" id="emailOrUsername" name="emailOrUsername"
                   value="{{old('emailOrUsername')}}" required/>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"
                   placeholder="Password" id="password" name="password" required/>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-success uppercase">Login</button>
            <a href="/user/forgotPassword" id="forget-password" class="forget-password">Forgot Password?</a>
        </div>
        <div class="create-account">
            <p>
                <a href="/user/register" id="register-btn" class="uppercase">Create an account</a>
            </p>
        </div>
    </form>
    <!-- END LOGIN FORM -->
</div>
<div class="copyright">
    2016 &copy; InstaPanel
</div>
<!-- END LOGIN -->


@include('User/Layouts/usercommonfooterscripts')
        <!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/js/validate/jquery.validate.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
{{--<script src="/assets/js/login.js"></script>--}}
<script type="text/javascript" src="/assets/js/jstz.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
//        Login.init();
        Demo.init();
    });

    var timezone = jstz.determine();
    console.log('Your timezone is: ' + timezone.name());
    $('#user_timezone').val(timezone.name());

</script>
<!-- END JAVASCRIPTS -->

</body>

<!-- END BODY -->

</html>

