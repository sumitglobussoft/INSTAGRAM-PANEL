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


<div class="login">
    <form method="post" action="/supplier/register" style="margin-bottom: 3%;">

        <h4 style="color: red; text-align: center;">@if(session('registerErrorMessage')) <?php echo session('registerErrorMessage'); ?> @endif</h4>

        <input type="text" name="firstname" placeholder="Enter Firstname" value="{{old('firstname')}}"/>
        <span class="error" style="color: red;">{!! $errors->first('firstname') !!}</span>

        <input type="text" name="lastname" placeholder="Enter Lastname" value="{{old('lastname')}}"/>
        <span class="error" style="color: red;">{!! $errors->first('lastname') !!}</span>

        <input type="text" name="username" placeholder="Choose Username" value="{{old('username')}}"/>
        <span class="error" style="color: red;">{!! $errors->first('username') !!}</span>

        <input type="text" name="email" placeholder="Email" required="required" value="{{old('email')}}"/>
        <span class="error" style="color: red;">{!! $errors->first('email') !!}</span>

        <input type="password" name="password" placeholder="Choose a Password"/>
        <span class="error" style="color: red;">{!! $errors->first('password') !!}</span>

        <input type="password" name="conform_password" placeholder="Enter your Conform Password"/>
        <span class="error" style="color: red;">{!! $errors->first('conform_password') !!}</span>

        <button type="submit" class="btn btn-primary btn-block btn-large">SignUp</button>
    </form>
    <span class="text-white" style="margin-top:3%;">Already a member ?? <a href="/supplier/login" class="text-info">LOGIN</a></span>
</div>


@include('Supplier/Layouts/suppliercommonfooterscripts')

</body>

</html>

