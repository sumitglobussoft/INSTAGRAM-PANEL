@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/light.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/profile.css" rel="stylesheet" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico" />

@endsection

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
                <h1>Add Balance</h1>
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
                <a href="/user/payment">Add Balance</a>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Purchase Coinz with Paypal</span>
                            <span class="caption-helper">manage your current balance ...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabs-left row">
                            <div class="col-md-12 col-xs-12">
                                <div id="b">


                                    <div class="row" style="margin-top:3%;">
                                        <div class="col-md-12 text-center">
                                            <img max-width="100%" src="http://s24.postimg.org/5gt3rmjd1/paypal.png"/>
                                        </div>
                                    </div>

                                    @if(Session::has('message'))
                                        <div class="row" style="margin-top:3%;">
                                            <div class="col-md-12">
                                                <div class="alert alert-success"><a class="close" data-dismiss="alert" href="#"
                                                                                    aria-hidden="true">×</a>
                                                    {{Session::get('message')}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {{--<div class="row" style="margin-top:3%;">--}}
                                        {{--<div class="col-md-12">--}}
                                            {{--<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#"--}}
                                                                             {{--aria-hidden="true">×</a>We are having--}}
                                                {{--issues with Payments, if you wish to deposit and Paypal is not enable--}}
                                                {{--for your account please talk with Live chat, or send directly to--}}
                                                {{--zeusgram@gmail.com as Friends and Family and let us know the Transaction--}}
                                                {{--ID--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="row">
                                        <div class="col-md-12">

                                            <form class="" role="form" method="post">
                                                <h5>Amount</h5>

                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><span class="fa fa-money"></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="money" name="money"
                                                               placeholder="Amount of Coinz to Purchase"/>

                                                        <div class="error"
                                                             style="color:red">{{ $errors->first('money') }}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <h5>Purchase Agreement</h5>

                                                    <div class="checkbox-list">
															<span>
															<input type="checkbox" required="required"/> <small>By
                                                                    Checking this box i understand that my purchase is
                                                                    irrevocable and ill not ask fraudulent dispute. This
                                                                    purchase is final, we reserve the right to use the
                                                                    confirmation as proof of your agreement.
                                                                </small> </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-success btn-raised" type="submit"><i
                                                                class="fa fa-arrow-circle-right"></i> Continue to Paypal
                                                    </button>
                                                    {{--<button class="btn btn-default btn-raised" style="margin-left:1%;"--}}
                                                    {{--type="button">Cancel--}}
                                                    {{--</button>--}}
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                    <h3> Read before Purchase </h3>

                                    <div class="row" style="margin-top:1%;">
                                        <div class="col-md-12">
                                            <ul class="purchase">
                                                <li><b>Use valid and real information about you</b></li>
                                                <li>Don't use Proxies or VPN</li>
                                                <li>Payments can be refused , please check your payment status before
                                                    you open a
                                                    ticket.
                                                </li>
                                                <li>Minimum Purchase <b>10 credits -$10</b></li>
                                                <li>Purchases are final and we cannot refund credits back to your paypal
                                                    or credit
                                                    card
                                                </li>
                                                <li>Credits Amount.<b>1 Credit=1 US Dollar </b></li>
                                            </ul>
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
@endsection



