<form action="/supplier/resetPassword" method="post">
    <div>
        <input type="email" name="fpwemail" placeholder="Enter Email Address"><br>
        <input type="password" name="password" placeholder="Enter New Password"><br>
        <input type="password" name="conformPassword" placeholder="Enter Conform Password"><br>
        <input type="submit" name="Reset Password" value="Reset Password"> &nbsp;&nbsp;
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
    </div>
</form>

