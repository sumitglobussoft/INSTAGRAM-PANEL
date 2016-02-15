<!DOCTYPE html>
<html>

<head>
    @include('User/Layouts/userheadscripts')
</head>

<body>

<div class="login-content">
    <div class="branding text-center">
        <a href="index.html" class="logo"> I N S T A P A N E L </a>
    </div>
</div>


<div class="login">


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

        <h5 style="color: red; text-align: center">@if (Session::get('errMsg') ) {{Session::get('errMsg') }} @endif</h5>
        <h5 style="color: red; text-align: center">@if (Session::get('successMsg') ) {{Session::get('successMsg') }} @endif</h5>


    </div>

    <form action="/user/forgotPassword" method="post" id="forgotPasswordForm" style="margin-bottom: 3%;">

        <input type="email" name="email" id="fpwEmail" placeholder="Email Address" required="required"/>
        <button type="submit" id="submitEmail" class="btn btn-primary btn-block btn-large">Reset Password</button>
    </form>
        <span class="text-white" style="margin-top:3%;">Back to LogIn ?? <a href="/user/login"
                                                                            class="text-info">LOGIN</a></span>
</div>


@include('User/Layouts/usercommonfooterscripts')

</body>

</html>


