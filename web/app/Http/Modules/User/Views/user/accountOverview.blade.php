@extends('User/Layouts/userlayout')

@section('title','Order History')

@section('headcontent')
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/default.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/profile.css" rel="stylesheet" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico" />

@endsection
@section('classMyAccount','active')
@section('classMyAccount1','active')

@section('content')
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE HEAD -->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Account Overview</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD -->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="/user/dashboard">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="javascript:;">Market</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/user/accountOverview">Account Overview</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                {{--<div class="note note-danger note-shadow">--}}
                {{--<p>--}}
                {{--NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only.--}}
                {{--</p>--}}
                {{--</div>--}}
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Account Overview</span>
                            <span class="caption-helper">manage your account...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabs-left row">
                            <div class="col-md-12 col-xs-12">
                                <div id="a">
                                    <div class="row" style="margin-top:3%;">
                                        <div class="col-md-12">
                                            <table class="table table-hover table-condensed">
                                                <tbody>
                                                <tr>
                                                    <td><i class="fa fa-user"></i></td>
                                                    <td> Username</td>
                                                    <td> {{Session::get('ig_user')['username']}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa-skype"></i></td>
                                                    <td> Skype Username</td>
                                                    <td> {{Session::get('ig_user')['skype_username']}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa fa-euro"></i></td>
                                                    <td> Account Balance</td>
                                                    <td>
                                                        $ @if(isset(Session::get('ig_user')['account_bal'])) {{Session::get('ig_user')['account_bal']}} @else
                                                            0.0000 @endif
                                                        <a href="/user/payment">( Add More )</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa fa-at"></i></td>
                                                    <td> E-Mail</td>
                                                    <td> {{Session::get('ig_user')['email']}}</td>
                                                </tr>
                                                {{--<tr>--}}
                                                    {{--<td><i class="fa fa fa-credit-card"></i></td>--}}
                                                    {{--<td> Paypal / 2Checkout Payments</td>--}}
                                                    {{--<td>--}}
                                                        {{--<span class="label label-success">Enable</span> /--}}
                                                        {{--<span class="label label-danger">Disable</span>--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td><i class="fa fa fa-server"></i></td>--}}
                                                    {{--<td> Shop API Key</td>--}}
                                                    {{--<td> 0</td>--}}
                                                {{--</tr>--}}
                                                {{--<tr>--}}
                                                    {{--<td><i class="fa fa fa-external-link"></i></td>--}}
                                                    {{--<td> Affiliated Link</td>--}}
                                                    {{--<td><a target="_blank"--}}
                                                           {{--href="javascript:;">https://www.instapanel.com/?a=1181</a>--}}
                                                    {{--</td>--}}
                                                {{--</tr>--}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->

@endsection

@section('pagejavascripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>
<!-- END JAVASCRIPTS -->

@endsection



