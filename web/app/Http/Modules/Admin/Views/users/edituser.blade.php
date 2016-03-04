@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Manage Users</li>
            </ol>
            <div class="page-header_title">
                <h1>Edit User </h1>
            </div>
        </section>

        <section class="page-content">

            @if(Session::has('message'))
                @if(session('status')=='Success')
                    <div style="color:green;"><b>{{session('status')}}</b> {{Session::get('message')}}</div>
                @endif
                @if(session('status')=='Error')
                    <div style="color:green;"><b>{{session('status')}}</b> {{Session::get('message')}}</div>
                @endif
            @endif

            <h3 style="color: firebrick">Edit User Profile</h3>
            @foreach($suppDetails as $sd)
                <form method="post" class="form ">
                    <div class="form-group floating-label">
                        <input class="form-control" id="regular2" type="text" name="firstname" value="{{$sd->name}}">
                        <label for="regular2">First Name</label>

                        <div class="error" style="color:red">{{ $errors->first('firstname') }}</div>
                    </div>
                    <div class="form-group floating-label">
                        <input class="form-control" id="regular2" type="text" name="lastname" value="{{$sd->lastname}}">
                        <label for="regular2">Last Name</label>

                        <div class="error" style="color:red">{{ $errors->first('lastname') }}</div>
                    </div>
                    <div class="form-group floating-label">
                        <input class="form-control" id="regular2" type="text" name="username" value="{{$sd->username}}">
                        <label for="regular2">UserName</label>

                        <div class="error" style="color:red">{{ $errors->first('username') }}</div>
                    </div>
                    <div class="form-group floating-label">
                        <input class="form-control" id="regular2" type="email" name="email" value="{{$sd->email}}">
                        <label for="regular2">Email</label>

                        <div class="error" style="color:red">{{ $errors->first('email') }}</div>
                    </div>
                    <div class="form-group floating-label">
                        <span style="color:blue;">@if(isset($sd->conversion_symbol)) {{$sd->conversion_symbol}} @endif</span>
                        <input class="form-control" id="regular2" type="text" name="account_bal"
                               value="@if(isset($sd->account_bal)) {{$sd->account_bal}} @endif">
                        <label for="regular2">Account Balance</label>

                        <div class="error" style="color:red">{{ $errors->first('account_bal') }}</div>
                    </div>
                    <button type="submit" class="btn btn-theme btn-raised" name="change-generalinfo">Save Changes
                    </button>
                </form>
            @endforeach
            <h3 style="color: firebrick">Edit User Password</h3>

            <form method="post" class="form ">
                <div class="form-group floating-label">
                    <input class="form-control" id="regular2" type="password" name="currentpassword">
                    <label for="regular2">Current Password</label>
                    @if(Session::has('pswdErr'))
                        <div style="color: red">{{Session::get('pswdErr')}}</div>
                    @endif
                    <div class="error" style="color:red">{{ $errors->first('currentpassword') }}</div>
                </div>
                <div class="form-group floating-label">
                    <input class="form-control" id="regular2" type="password" name="password">
                    <label for="regular2">Password</label>

                    <div class="error" style="color:red">{{ $errors->first('password') }}</div>
                </div>
                <div class="form-group floating-label">
                    <input class="form-control" id="regular2" type="password" name="password_confirmation">
                    <label for="regular2">Password Confirmation</label>

                    <div class="error" style="color:red">{{ $errors->first('password_confirmation') }}</div>
                </div>
                <button type="submit" class="btn btn-theme btn-raised " name="edit-password">Save Changes</button>
            </form>
        </section>
    </section>


@endsection

@section('pagescripts')

@endsection