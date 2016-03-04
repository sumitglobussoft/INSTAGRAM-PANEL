<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta content="AllThatIsRam" name="author">
    <title>InstaPanel || ADMIN</title>

    <!-- Bootstrap Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include with every page -->
    <link href="/css/admin.min.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link href="/css/custom.css" rel="stylesheet"/>
</head>

<body id="page-authentication" class="container-fluid">

<div id="authentication-box" class="authentication-style1">
    <div class="authentication-box-wrapper">
        <div class="panel panel-default">
            <div class="panel-body no-padding">
                <div class="authentication-header">
                    <div class="logo-box logo-box-primary-light padding-top-4">
                        <div class="logo">
                            <b>IP</b>
                        </div>
                    </div>
                    <span>Reset Your Password</span>
                </div>
                @if(Session::has('message')) <div class="alert alert-info" style="color:red;"><b>{{session('status')}}</b> {{Session::get('message')}} </div> @endif
                <div class="authentication-body">
                    <form class="form" role="form" method="post">
                        <div class="form-group floating-label">
                            <input class="form-control" id="regular2" name="newpassword" type="password">
                            <label for="regular2">New Password</label>
                        </div>
                        <div class="error" style="color:red;">{{ $errors->first('newpassword') }}</div>
                        <div class="form-group floating-label">
                            <input type="password" class="form-control" id="password" name="newpassword_confirmation">
                            <label for="password">New Password Confirmation</label>
                        </div>

                        <button type="submit" class="btn btn-theme btn-raised btn-block">Reset Passsword</button>
                        <div class="authentication-body-footer margin-top-5">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Core Scripts - Include with every page -->
<script src="/js/jquery-1.10.2.js"></script>
<script src="/js/jquery-ui.custom.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>

<!-- Plugin Scripts -->
<script src="/js/perfectscrollbar/perfect-scrollbar.jquery.min.js"></script>
<script src="/js/iCheck/icheck.min.js"></script>
<script src="/js/materialRipple/jquery.materialRipple.js"></script>

<!-- Included with every page -->
<script src="/js/admin-common.min.js"></script>
</body>

</html>