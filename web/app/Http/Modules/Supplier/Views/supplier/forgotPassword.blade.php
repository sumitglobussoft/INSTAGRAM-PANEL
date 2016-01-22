<form action="/supplier/forgotPassword" method="post">
    <div>
        <input type="text" name="email" placeholder="Enter Email Address"><br>
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
        @if (Session::get('successMsg') )
            {{Session::get('successMsg') }}
        @endif

        @if (Session::get('errMsg') )
            {{Session::get('errMsg') }}
        @endif


    </div>
</form>

