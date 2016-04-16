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
<link href="/assets/css/light.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/custom.css" rel="stylesheet" />

<link rel="shortcut icon" href="favicon.ico" />
<!-- END THEME STYLES -->

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
                <h1>Dashboard</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD -->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="index.html">Home</a>
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
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-green-sharp">7800<small class="font-green-sharp"></small></h3>
                            <small>TOTAL Order</small>
                        </div>
                        <div class="icon">
                            <i class="icon-pie-chart"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
									<span class="progress-bar progress-bar-success green-sharp" style="width: 76%;">
								<span class="sr-only">76% progress</span>
									</span>
                        </div>
                        <div class="status">
                            <div class="status-title">
                                progress
                            </div>
                            <div class="status-number">
                                76%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-red-haze">1349</h3>
                            <small>NEW FEEDBACKS</small>
                        </div>
                        <div class="icon">
                            <i class="icon-like"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
									<span class="progress-bar progress-bar-success red-haze" style="width: 85%;">
								<span class="sr-only">85% change</span>
									</span>
                        </div>
                        <div class="status">
                            <div class="status-title">
                                change
                            </div>
                            <div class="status-number">
                                85%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-blue-sharp">567</h3>
                            <small>NEW ORDERS</small>
                        </div>
                        <div class="icon">
                            <i class="icon-basket"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
									<span class="progress-bar progress-bar-success blue-sharp" style="width: 45%;">
								<span class="sr-only">45% grow</span>
									</span>
                        </div>
                        <div class="status">
                            <div class="status-title">
                                grow
                            </div>
                            <div class="status-number">
                                45%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-purple-soft">276</h3>
                            <small>NEW USERS</small>
                        </div>
                        <div class="icon">
                            <i class="icon-user"></i>
                        </div>
                    </div>
                    <div class="progress-info">
                        <div class="progress">
									<span class="progress-bar progress-bar-success purple-soft" style="width: 57%;">
								<span class="sr-only">56% change</span>
									</span>
                        </div>
                        <div class="status">
                            <div class="status-title">
                                change
                            </div>
                            <div class="status-number">
                                57%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- BEGIN SLIDER -->
            <div class="col-md-12">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-green-sharp"><i class="fa fa-newspaper-o font-green-sharp"></i><span class="caption-subject bold uppercase"> Server News</span>
                            <span class="caption-helper"> Check the news everyday for promotions and updates</span></div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable tabbable-custom">
                            <div class="scroller" style="height: 300px;" data-always-visible="1" data-rail-visible1="0" data-handle-color="#D7DCE2">
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
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-danger"><i class="fa fa-instagram"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">There is a small downtime with Instagram Normal Likes &amp; Medium , if you wish any IDS canceled in the meanwhile please write on skype or send ticket. </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">2 weeks</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-success"><i class="fa fa-wifi"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">System Proxys updated! Enjoy :) All accounts using the old proxys was updated with the new proxys instead.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
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
                                                    <div class="desc">Instagram Bot updates done , public proxys still not working, please update to private proxys if you dont want to wait. We estimate maximum 24-48 hours to replace the system proxys with new ones.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-danger"><i class="fa fa-instagram"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Instagram Bot : Performance Cleanup , please estimate 1-2hours downtime.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-warning"><i class="fa fa-wifi"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">We will update instagram public proxies, anyone using public proxies provided by IGERSLIKE should update the accounts with the new system proxys that will come tomorow. We strongly recommend to use your own private proxies, we dont gurantee that public proxies are working 100% of the time.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
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
                                                    <div class="desc">Mentions enable for test, who wish to use please write on skype : zeus.gram</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
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
                                                    <div class="desc">Instagram Bot : Picture uploader is finally fixed , and should show the hashtags correctly and wont flag anymore! Thanks for everyone that was patiente waiting more then 1 year for this fix :) </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-danger"><i class="fa fa-at"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Mentions will be disable for a longer period tonight. Please dont submit tickets about it. Once its possible we will enable them again. </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-danger"><i class="fa fa-phone"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">Instagram SMS numbers are not working , we estimate the same service to be back in 20 days maximum. In the meanwhile you can google and search for other SMS verification services.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
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
                                                    <div class="desc">Instagram Followers Fast - Maximum raised to 30.000! Thanks for the trust using our services , soon new web-site with new features :)</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
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
                                                    <div class="desc">Mentions enable again , please order lower amounts and dont abuse the service , or we might disabled it again if its abused. Use with responsability and dont spam a lot.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
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
                                                    <div class="desc">Instagram Followers Fast base 25k, Instagram Mentions &amp; Mentions Custom new price 2,1? per 1000. Enjoy :) </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
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
                                                    <div class="desc">Followers Fast is enable. Maximum per photo raised to 20.000 per photo.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">1 month</div>
                                        </div>
                                    </li>
                                    <!--ITEM TABS-->
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm label-danger"><i class="fa fa-instagram"></i></div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc">We had a small delay with instagram mentions and HQ service , now its all back online and working.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col2">
                                            <div class="date">2 months</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  END SLIDER -->

            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-green-sharp"><i class="fa fa-child  font-green-sharp"></i><span class="caption-subject bold uppercase">Invite your friends!</span>
                            <span class="caption-helper"> Invite your friends to InstaPanel</span></div>
                    </div>
                    <div class="portlet-body">

                        <a href="#" class="list-group-item">
                            <h4 class="list-group-item-heading">Time to Invite!</h4>
                            <p class="list-group-item-text">
                                InstaPanel is now a private community. We will still open the public registry sometimes but our main concept will be invited based. Each user will have 10-20 Invites to send. Use the form bellow to invite your friend directly with a unique link!
                            </p>
                        </a>
                        <br>

                        <form aaction="" method="post" role="form">
                            <div class="form-body">
                                <div class="form-group">
                                    <div class="input-group"><span class="input-group-addon input-circle-left"><i class="fa fa-envelope"></i></span>
                                        <input type="text" required="required" id="email" name="email" placeholder="yourfriend@domain.com" class="form-control input-circle-right">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group"><span class="input-group-addon input-circle-left">Remaining Invites</span>
                                        <input type="text" disabled="disabled" value="10" id="x" name="x" class="form-control input-circle-right">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group"><span class="input-group-addon input-circle-left">Invites Accepted</span>
                                        <input type="text" disabled="disabled" value="0" id="x" name="x" class="form-control input-circle-right">
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions">
                                <button class="btn bg-green-haze" name="submit" type="submit">Invite</button>
                            </div>
                        </form>

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
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>
<!-- END JAVASCRIPTS -->
@endsection


