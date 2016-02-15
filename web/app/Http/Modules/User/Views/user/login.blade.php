<!DOCTYPE html>
{{--<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->--}}
{{--<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->--}}
{{--<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->--}}
{{--<!--[if gt IE 8]><!-->--}}
<html class="no-js">
<!--<![endif]-->

<head>
    @include('User/Layouts/userheadscripts')
</head>




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

                <div class="authentication-body">
                    <form class="form" role="form" method="post" action="/user/login" >

                        <div class="">
                            @if (count($errors) > 0)
                                <div>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li style="color: red; font-size: 14px; text-align: center">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <h5 style="color: green; text-align: center;">@if(isset($passwordChangeSuccessMessage)) {{$passwordChangeSuccessMessage}} @endif</h5>
                        <h5 style="color: green; text-align: center;">@if(isset($registerSuccesMessage)) {{$registerSuccesMessage}} @endif</h5>

                        <div class="form-group floating-label">
                            <input type="text" class="form-control" id="emailOrUsername" name="emailOrUsername"  value="{{old('emailOrUsername')}}" required>
                            <label for="emailOrUsername" >Email or Username</label>
                        </div>
                        <div class="form-group floating-label">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <label for="password">Password</label>
                        </div>

                        <button type="submit" class="btn btn-theme btn-raised btn-block">Sign in</button>
                        <div class="authentication-body-footer margin-top-5">
                            <div>
                                <label>
                                    <input class="checkbox checkbox-info" type="checkbox" name="remember"/>
                                    <span>Stay signed in</span>
                                </label>
                            </div>
                            <div class="text-right">
                                <a href="/user/forgotPassword">Forgot password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="text-center foot-login">
            Don't have an account? <a href="/user/register" class="text-theme">Create an account</a>
        </div>
    </div>
</div>

@include('User/Layouts/usercommonfooterscripts')


</body>

</html>

