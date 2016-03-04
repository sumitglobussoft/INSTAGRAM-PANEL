<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta content="AllThatIsRam" name="author">
    <title>InstaPanel || ADMIN</title>

    <!-- Bootstrap Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include with every page -->
    <link href="/css/admin.min.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link href="/css/custom.css" rel="stylesheet"/>
</head>

<body id="page-authentication" class="container-fluid">

<div id="authentication-box" class="authentication-style1">
    <div class="authentication-box-wrapper">
        <div class="panel panel-default">
            <div class="panel-body no-padding">
                <div class="authentication-header">
                    <div class="logo-box logo-box-primary-light padding-top-4">
                        <div class="logo">
                            <b>IP</b>
                        </div>
                    </div>
                    <span>Sign in to your account</span>
                </div>
                @if(Session::has('message')) <div class="alert alert-info" style="color:red;"><b>{{session('status')}}</b>{{Session::get('message')}} </div> @endif
                <div class="authentication-body">
                    <form class="form" role="form" method="post">
                        <div class="form-group floating-label">
                            <input type="text" class="form-control" id="email" name="email" value="{{old('email')}}">
                            <label for="email">Email</label>
                            <div class="error" style="color:red">{{ $errors->first('email') }}</div>
                        </div>
                        <div class="form-group floating-label">
                            <input type="password" class="form-control" id="password" name="password">
                            <label for="password">Password</label>
                            <div class="error" style="color:red">{{ $errors->first('password') }}</div>
                        </div>

                        <button type="submit" class="btn btn-theme btn-raised btn-block">Sign in</button>
                        <div class="authentication-body-footer margin-top-5">
                            {{--<div>--}}
                            {{--<label>--}}
                            {{--<input class="checkbox checkbox-info" type="checkbox" />--}}
                            {{--<span>Stay signed in</span>--}}
                            {{--</label>--}}
                            {{--</div>--}}
                            <div class="text-right">
                                <a href="forgot">Forgot password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{--<div class="text-center foot-login">--}}
            {{--Don't have an account? <a href="signup.html" class="text-theme">Create an account</a>--}}
        {{--</div>--}}
    </div>
</div>

<!-- Core Scripts - Include with every page -->
<script src="/js/jquery-1.10.2.js"></script>
<script src="/js/jquery-ui.custom.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>

<!-- Plugin Scripts -->
<script src="/js/perfectscrollbar/perfect-scrollbar.jquery.min.js"></script>
<script src="/js/iCheck/icheck.min.js"></script>
<script src="/js/materialRipple/jquery.materialRipple.js"></script>

<!-- Included with every page -->
<script src="/js/admin-common.min.js"></script>
</body>

</html>


{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}

{{--</head>--}}

{{--<body>--}}
{{--@if(Session::has('message')) <div class="alert alert-info" style="color:red;"><b>{{session('status')}}</b> {{Session::get('message')}} </div> @endif--}}
{{--<form class="m-t-md" method="post">--}}

{{--<div class="form-group">--}}
{{--<input class="form-control" placeholder="Your Email" name="email" type="email">--}}

{{--</div>--}}
{{--<div class="form-group">--}}
{{--<input type="password" class="form-control" placeholder="*********" name="password">--}}

{{--</div>--}}
{{--<button type="submit" class="btn btn-success btn-block">Login</button>--}}
{{--<a href="forgot">forgotten password click here!!!</a>--}}

{{--</form>--}}

{{--</body>--}}
{{--</html>--}}


