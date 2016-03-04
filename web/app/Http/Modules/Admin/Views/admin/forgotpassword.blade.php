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
@if(Session::has('msg'))
    @if(session('status')=='Success')
        <div class="alert alert-info" style="color:green;">
            <b>{{session('status')}}</b> {{Session::get('msg')}} </div>
    @endif
    @if(session('status')=='Error')
        <div class="alert alert-info" style="color:red;">
            <b>{{session('status')}}</b> {{Session::get('msg')}} </div>
    @endif
@endif

<body id="page-authentication" class="container-fluid" style="padding-top: 6%;">
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
                    <span>Recover your password</span>
                </div>

                <div class="authentication-body">
                    <form class="form" role="form" method="post">
                        <div class="form-group floating-label">
                            <input type="email" class="form-control" id="email" name="email">
                            <label for="email">E-mail address</label>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-theme btn-raised btn-block">Reset your password
                            </button>
                        </div>
                        <div class="authentication-body-footer margin-top-5">
                            <div class="text-right">
                                <a href="/admin/login">Back to LogIn</a>
                            </div>
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

