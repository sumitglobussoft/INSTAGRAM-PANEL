@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <style>
        .btn-success {
            background-color: #65B688;
            border-color: #65B688;
        }

        .btn-danger {
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        .btn {
            color: white;
            display: inline-block;
            margin-bottom: 0;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

@endsection


@section('pagecontent')

    <form class="m-t-md form" method="post">

        <div class="form-group">
            First Name: <input class="form-control" placeholder="Your Name" name="newname" type="text"
                               value="<?php if (Auth::check()) {
                                   $name = Auth::user()->name;
                                   echo $name;
                               }
                               ?>">

            <div class="error" style="color:red">{{ $errors->first('newname') }}</div>
        </div>

        <div class="form-group">
            Last Name:<input class="form-control" placeholder="Last Name" name="newlastname" type="text"
                             value="<?php if (Auth::check()) {
                                 $lastname = Auth::user()->lastname;
                                 echo $lastname;
                             }
                             ?>">

            <div class="error" style="color:red">{{ $errors->first('newlastname') }}</div>
        </div>

        <div class="form-group">
            UserName:<input class="form-control" placeholder="Username" name="newusername" type="text"
                            value="<?php if (Auth::check()) {
                                $name = Auth::user()->username;
                                echo $name;
                            }
                            ?>">

            <div class="error" style=" color:red">{{ $errors->first('newusername') }}</div>
        </div>

        <div class="form-group floating-label">
            <input type="text" class="form-control" id="regular2" name="newemail"
                   value="<?php if (Auth::check()) {
                       $name = Auth::user()->email;
                       echo $name;
                   }
                   ?>">
            <label for="regular2">Email Id:</label>

            <div class="error" style="color:red">{{ $errors->first('newemail') }}</div>
        </div>


        <input type="submit" class="btn btn-success btn-block" name="generalinfo" value="Save Changes">
    </form>
    <br><br>

    <form class="m-t-md" method="post">
        <div class="form-group">
            New Password:<input type="password" class="form-control" placeholder="New Password" name="newpassword">

            <div class="error" style="color:red">{{ $errors->first('newpassword') }}</div>
        </div>

        <div class="form-group">
            Re-type Password:<input type="password" class="form-control" placeholder="Re-enter Password"
                                    name="newpassword_confirmation">

        </div>
        <input type="submit" class="btn btn-success btn-block" name="editpassword" value="Save Changes">

    </form>

@endsection


@section('pagescripts')


@endsection