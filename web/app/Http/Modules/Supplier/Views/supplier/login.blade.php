<!DOCTYPE html>
<html>

<head>
    @include('Supplier/Layouts/supplierheadscripts')
</head>

<body>
<div class="login-content">
    <div class="branding text-center">
        <a href="" class="logo"> I N S T A P A N E L </a>
    </div>
</div>

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="login">
    <form method="post"  style="margin-bottom: 3%;">
        <input type="text" name="emailOrUsername" placeholder="Email or Username"   value="{{old('emailOrUsername')}}" />
        {{--<span class="error" style="color: red;">{!! $errors->first('emailOrUsername') !!}</span>--}}

        <input type="password" name="password" placeholder="Password"  />
        {{--<span class="error" style="color: red;">{!! $errors->first('password') !!}</span><br>--}}

        <input type="checkbox" name="remember" style="margin-bottom: 5%;" /> <span class="text-white">Remember Me</span>
        <a href=/supplier/forgotPassword" class="pull-right text-white">Forgot Password?</a>
        <button type="submit" class="btn btn-primary btn-block btn-large">Login</button>
    </form>
    <span class="text-white" style="margin-top:3%;">Not a member ?? <a href="/supplier/register" class="text-info">SIGNUP</a></span>
</div>



@include('Supplier/Layouts/suppliercommonfooterscripts')

</body>

</html>



