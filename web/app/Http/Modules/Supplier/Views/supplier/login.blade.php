<form method="post">
    <div>
        <p>Login Page</p>
        <input type="text" name="emailOrUsername" placeholder="Enter your Email"><br><br>
        <input type="password" name="password" placeholder="Enter your Password"><br><br>
        <input type="submit" value="Log in">
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

    {{Session::get('errMsg') }}
</form>

<div><a href="/supplier/forgotPassword">Forgot password</a></div><br>
<a href="/supplier/register">Sign Up</a>