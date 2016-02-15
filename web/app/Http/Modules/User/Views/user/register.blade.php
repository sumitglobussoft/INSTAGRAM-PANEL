<!DOCTYPE html>
{{--{if lt IE 7]--}}
{{--<html class="no-js lt-ie9 lt-ie8 lt-ie7"> [endif]--}}
{{--[if IE 7]--}}
{{--<html class="no-js lt-ie9 lt-ie8"> [endif]--}}
{{--[if IE 8]--}}
{{--<html class="no-js lt-ie9"> [endif]--}}
{{--[if gt IE 8]--}}
<html class="no-js">
<!--<![endif]-->


<head>
    @include('User/Layouts/userheadscripts')
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
                    <span style="padding-left: 100px ">Create an account</span>
                </div>

                <div class="authentication-body">
                    <form class="form" role="form" method="post" action="/user/register">
                        <h4 style="color: red; text-align: center;">@if(session('registerErrorMessage')) <?php echo session('registerErrorMessage'); ?> @endif</h4>

                        <div class="form-group floating-label">
                            <input type="text" class="form-control" id="firstname" name="firstname" value="{{old('firstname')}}">
                            <label for="firstname">First Name</label>
                            <span class="error" style="color: red;">{!! $errors->first('firstname') !!}</span>
                        </div>
                        <div class="form-group floating-label">
                            <input type="text" class="form-control" id="lastname" name="lastname" value="{{old('lastname')}}">
                            <label for="lastname">Last Name</label>
                            <span class="error" style="color: red;">{!! $errors->first('lastname') !!}</span>
                        </div>
                        <div class="form-group floating-label">
                            <input type="text" class="form-control" name="username" value="{{old('username')}}"/>
                            <label for="username">Choose Username</label>
                            <span class="error" style="color: red;">{!! $errors->first('username') !!}</span>
                        </div>
                        <div class="form-group floating-label">
                            <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" required="required"/>
                            <label for="email">E-mail address</label>
                            <span class="error" style="color: red;">{!! $errors->first('email') !!}</span>
                        </div>
                        <div class="form-group floating-label">
                            <input type="password" class="form-control" id="password" name="password">
                            <label for="password">Password</label>
                            <span class="error" style="color: red;">{!! $errors->first('password') !!}</span>
                        </div>
                        <div class="form-group floating-label">
                            <input type="password" class="form-control" id="conform_password" name="conform_password">
                            <label for="conform_password">Conform Password</label>
                            <span class="error" style="color: red;">{!! $errors->first('conform_password') !!}</span>
                        </div>
                        <div>
                            <label>
                                <input class="checkbox checkbox-info" type="checkbox"/>
                                <span>Agree the <a href="javascript:;">terms and conditions</a></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-theme btn-raised btn-block">Sign up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="text-center foot-login" style="margin-bottom: 5%;">
            Already have an account? <a href="/user/login" class="text-theme">Sign in</a>
        </div>
    </div>
</div>


@include('User/Layouts/usercommonfooterscripts')

</body>

</html>

