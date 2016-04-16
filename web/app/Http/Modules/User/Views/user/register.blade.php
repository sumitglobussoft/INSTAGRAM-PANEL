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
<div class="content">
    <!-- BEGIN REGISTRATION FORM -->

    <form class="" role="form" method="post" action="/user/register">

        <div class="form-group">
            <input class="form-control placeholder-no-fix" type="hidden" id="user_timezone" name="user_timezone" value="">
        </div>

        <h3>Sign Up</h3>
        @if(session('registerErrorMessage'))
            <div class="alert alert-danger ">
                <button class="close" data-close="alert"></button>
                <span style="text-align: center;"><?php echo session('registerErrorMessage'); ?></span>
            </div>
        @endif

        <p class="hint">
            Enter your personal details below:
        </p>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">First Name</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="First Name" id="firstname"
                   name="firstname"
                   value="{{old('firstname')}}" required>
            <span class="error" style="color: red;">{!! $errors->first('firstname') !!}</span>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Last Name</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="Last Name" id="lastname"
                   name="lastname"
                   value="{{old('lastname')}}">
            <span class="error" style="color: red;">{!! $errors->first('lastname') !!}</span>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Email</label>
            <input class="form-control placeholder-no-fix" type="text" placeholder="E-mail address" id="email"
                   name="email" value="{{old('email')}}"
                   required="required"/>
            <span class="error" style="color: red;">{!! $errors->first('email') !!}</span>
        </div>

        <p class="hint">
            Enter your account details below:
        </p>


        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Skype Username</label>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Skype Username"
                   name="skypeUsername" value="{{old('skypeUsername')}}"/>
            <span class="error" style="color: red;">{!! $errors->first('skypeUsername') !!}</span>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Username</label>
            <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Choose Username"
                   name="username" value="{{old('username')}}" required="required"/>
            <span class="error" style="color: red;">{!! $errors->first('username') !!}</span>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Password</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off"
                   placeholder="Password" id="password" name="password" required="required"/>
            <span class="error" style="color: red;">{!! $errors->first('password') !!}</span>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Re-type Your Password</label>
            <input class="form-control placeholder-no-fix" type="password" autocomplete="off"
                   placeholder="Re-type Your Password" id="conform_password" name="conform_password"
                   required="required"/>
            <span class="error" style="color: red;">{!! $errors->first('conform_password') !!}</span>
        </div>
        <div class="form-group margin-top-20 margin-bottom-20">
            <label class="check">
                <input type="checkbox" name="tnc" required/> I agree to the <a href="javascript:;">
                    Terms of Service </a> &amp; <a href="javascript:;">
                    Privacy Policy </a>
            </label>

            <div id="register_tnc_error">
            </div>
        </div>
        <div class="form-actions">
            <a href="/user/login" class="btn btn-default">Back</a>
            <button type="submit" id="register-submit-btn" class="btn btn-success uppercase pull-right">Submit</button>
        </div>
    </form>
    <!-- END REGISTRATION FORM -->
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

