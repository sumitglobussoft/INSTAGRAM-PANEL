@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
        <!-- BEGIN PAGE LEVEL STYLES -->
<link href="/assets/css/dataTables.bootstrap.css" rel="stylesheet" />
<link href="/assets/css/jquery.dataTables.min.css" rel="stylesheet" />
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/default.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->

<style>

</style>

<link rel="shortcut icon" href="favicon.ico" />


@endsection
@section('classDashboard','active')

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
                <h1>Dashboard</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD -->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="javascript:;">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="javascript:;">Dashboard</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row margin-top-10">
            <!-- BEGIN SLIDER -->
            <div class="col-md-6">
                <div>
                    <div id="clock" class="light">
                        <div class="display">
                            <div class="weekdays"></div>
                            <div class="ampm"></div>
                            <div class="alarm"></div>
                            <div class="digits"></div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-green-sharp"><i class="fa fa-newspaper-o font-green-sharp"></i><span class="caption-subject bold uppercase"> Server News</span>
                            <span class="caption-helper"> Check the news everyday for promotions and updates</span></div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable tabbable-custom">
                            <div class="scroller" style="height: 200px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
                                <ul class="feeds">
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success"><i class="fa fa-instagram"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Instagram Views Price reduced to 2.5€ per 1000</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">6 days</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-warning"><i class="fa fa-instagram"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Instagram HQ Service is currently down , we estimate to have this service as soon as possible back online. In the meanwhile any orders added from this service might be refunded automaticly.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 week</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success"><i class="fa fa-instagram"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Instagram views will be available in the next Minutes , base price 3€ per 1000. Test Mode.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 week</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success"><i class="fa fa-instagram"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Instagram Followers ( Fast ) Maximum 150.000 &amp; Instagram Likes ( Fast ) Maximum 40.000 ( Per Instagram Account )</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 week</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success"><i class="fa fa-at"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Mentions - Tags, Followers, Media Likers, Popular and many more comming soon :) Who wish you test drop me us a line on skype.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 week</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success"><i class="fa fa-at"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Mentions - Tags, Followers, Media Likers, Popular and many more comming soon :) Who wish you test drop me us a line on skype.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 week</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  END SLIDER -->
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                {{--<div class="widget widget-stat" style="background-image:url('/assets/images/messazon_rio_bridge.png');background-size: cover;min-height:515px;padding:0;">--}}
                    {{--<div class="img-txt">--}}
                        {{--<p>Messazon.com - Bridge to your Success.</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <img src="/assets/images/messazon_rio_bridge.png" class="img-responsive dash-pic" />
                <div class="img-txt">
                    <p>Messazon.com - Bridge to your Success</p>
                </div>
            </div>
        </div>

        <div class="row margin-top-10">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="widget widget-stat">
                    <div class="widget-stat-header bg-theme1">
                        <h3 class="text-center text-white m-t-0 m-b-0">Orders : <b>{{$totalOrders}}</b></h3>
                    </div>
                    <div class="widget-stat-content">
                        <div class="row text-center">
                            <div class="col-xs-4">
                                <h2 class="text-muted text-ellipsis m-t-0"><b>{{$completed}}</b></h2>
                                <small class="m-b-0">Completed</small>
                            </div>
                            <div class="col-xs-4">
                                <h2 class="text-muted text-ellipsis m-t-0"><b>{{$processing}}</b></h2>
                                <small class="m-b-0">Processing</small>
                            </div>
                            <div class="col-xs-4">
                                <h2 class="text-muted text-ellipsis m-t-0"><a href="/user/addOrder" class="label label-sm label-warning">
                                        <i class="icon-basket"></i></a></h2>
                                {{--<h2 class="fa  "><a href="/user/addOrder"></a> </h2>--}}
                                <small class="m-b-0"><b>Add Order</b></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="widget widget-stat">
                    <div class="widget-stat-header bg-theme2">
                        <h3 class="text-center text-white m-t-0 m-b-0">Balance : <b><small class="text-white">$</small>{{Session::get('ig_user')['account_bal']}}</b></h3>
                    </div>
                    <div class="widget-stat-content">
                        <div class="row text-center">
                            <div class="col-xs-4">
                                <h2 class="text-muted text-ellipsis m-t-0"><b><small>$</small>{{$payment}}</b></h2>
                                <small class="m-b-0">Payments</small>
                            </div>
                            <div class="col-xs-4">
                                <h2 class="text-muted text-ellipsis m-t-0"><a href="/user/payment" class="label label-sm label-warning">
                                        <i class="fa fa-paypal"></i></a></h2>
                                <small class="m-b-0"><b>Charge Wallet</b></small>
                            </div>
                            <div class="col-xs-4">
                                <h2 class="text-muted text-ellipsis m-t-0"><a href="/user/depositHistory" class="label label-sm label-warning">
                                        <i class="fa fa-dollar"></i></a></h2>
                                <small class="m-b-0">Transaction Details</small>
                            </div>
                        </div>
                    </div>
                </div>
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
    jQuery(document).ready(function() {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>
<script>
    $(function() {
        var clock = $('#clock'),
                alarm = clock.find('.alarm'),
                ampm = clock.find('.ampm');

        var digit_to_name = 'zero one two three four five six seven eight nine'.split(' ');

        var digits = {};

        var positions = [
            'h1', 'h2', ':', 'm1', 'm2', ':', 's1', 's2'
        ];

        var digit_holder = clock.find('.digits');

        $.each(positions, function() {

            if (this == ':') {
                digit_holder.append('<div class="dots">');
            } else {

                var pos = $('<div>');

                for (var i = 1; i < 8; i++) {
                    pos.append('<span class="d' + i + '">');
                }
                digits[this] = pos;
                digit_holder.append(pos);
            }

        });

        var weekday_names = 'MON TUE WED THU FRI SAT SUN'.split(' '),
                weekday_holder = clock.find('.weekdays');

        $.each(weekday_names, function() {
            weekday_holder.append('<span>' + this + '</span>');
        });

        var weekdays = clock.find('.weekdays span');

        (function update_time() {

            var now = moment().format("hhmmssdA");

            digits.h1.attr('class', digit_to_name[now[0]]);
            digits.h2.attr('class', digit_to_name[now[1]]);
            digits.m1.attr('class', digit_to_name[now[2]]);
            digits.m2.attr('class', digit_to_name[now[3]]);
            digits.s1.attr('class', digit_to_name[now[4]]);
            digits.s2.attr('class', digit_to_name[now[5]]);

            var dow = now[6];
            dow--;

            if (dow < 0) {
                // Make it last
                dow = 6;
            }

            weekdays.removeClass('active').eq(dow).addClass('active');

            ampm.text(now[7] + now[8]);

            setTimeout(update_time, 1000);
        })();
    });
</script>

@endsection