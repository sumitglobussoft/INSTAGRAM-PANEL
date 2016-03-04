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
                <h1>Add Users </h1>
            </div>
        </section>

        <section class="page-content">
            <form class="form" role="form" method="post" action="/admin/adduser">
                <h2 style="color:yellowgreen">Add Users Form</h2>

                <div class="form-group floating-label">
                    <input class="form-control" id="regular2" type="text" name="firstname" value="{{old('firstname')}}">
                    <label for="regular2">First Name</label>

                    <div class="error" style="color:red">{{ $errors->first('firstname') }}</div>
                </div>
                <div class="form-group floating-label">
                    <input class="form-control" id="regular2" type="text" name="lastname" value="{{old('lastname')}}">
                    <label for="regular2">Last Name</label>

                    <div class="error" style="color:red">{{ $errors->first('lastname') }}</div>
                </div>
                <div class="form-group floating-label">
                    <input class="form-control" id="regular2" type="text" name="username" value="{{old('username')}}">
                    <label for="regular2">UserName</label>

                    <div class="error" style="color:red">{{ $errors->first('username') }}</div>
                </div>
                <div class="form-group floating-label">
                    <input class="form-control" id="regular2" type="email" name="email" value="{{old('email')}}">
                    <label for="regular2">Email</label>

                    <div class="error" style="color:red">{{ $errors->first('email') }}</div>
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
                <button type="submit" class="btn btn-theme btn-raised btn-block">Sign Up</button>
            </form>
        </section>
    </section>
@endsection


@section('pagescripts')

@endsection