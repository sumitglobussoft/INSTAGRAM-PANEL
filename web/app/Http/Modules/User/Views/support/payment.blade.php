@extends('User/Layouts/userlayout')

@section('title','Payment Policy')


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
@section('classSupport','active')
@section('classSupport4','active')
@section('content')
{{--PAGE CONTENT GOES HERE--}}
        <!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE HEAD -->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Payment Policy</h1>
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
            {{--<li>--}}
                {{--<a href="javascript:;">Market</a>--}}
                {{--<i class="fa fa-circle"></i>--}}
            {{--</li>--}}
            <li>
                <a href="javascript:;">Support</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-shopping-cart font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Payment Policy</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row" style="margin-top:1%;">
                            <div class="col-md-12">
                                <ul class="purchase">
                                    <li><b>Use valid and real information about you</b></li>
                                    <li>Don't use Proxies or VPN</li>
                                    <li>Payments can be refused , please check your payment status before you open a
                                        ticket.
                                    </li>
                                    <li>Minimum Purchase <b>10 credits -$10</b></li>
                                    <li>Purchases are final and we cannot refund credits back to your paypal or credit
                                        card
                                    </li>
                                    <li>Credits Amount.<b>1 Credit=1 US Dollar </b></li>
                                </ul>
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



