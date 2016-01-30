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
        <input type="text" name="firstname" placeholder="Enter Firstname" required="required" />
        <input type="text" name="lastname" placeholder="Enter Lastname" required="required" />
        <input type="text" name="username" placeholder="Choose Username" required="required" />
        <input type="text" name="email" placeholder="Email" required="required" />
        <input type="password" name="password" placeholder="Choose a Password" required="required" />
        <input type="password" name="conform_password" placeholder="Enter your Conform Password" required="required" />
        <button type="submit" class="btn btn-primary btn-block btn-large">SignUp</button>
    </form>
    <span class="text-white" style="margin-top:3%;">Already a member ?? <a href="/supplier/login" class="text-info">LOGIN</a></span>
</div>



@include('Supplier/Layouts/suppliercommonfooterscripts')

</body>

</html>

