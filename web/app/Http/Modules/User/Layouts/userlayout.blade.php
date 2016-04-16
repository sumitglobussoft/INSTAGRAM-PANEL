<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    @include('User/Layouts/userheadscripts')
    @yield('headcontent')
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-md page-header-fixed page-sidebar-closed-hide-logo page-sidebar-fixed">
<!-- BEGIN HEADER -->
<div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="index.html">
                <strong> I N S T A P A N E L </strong>
            </a>

            <div class="menu-toggler sidebar-toggler">
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="separator hide">
                    </li>
                    <!-- BEGIN NOTIFICATION DROPDOWN -->
                    {{--<li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar" >--}}
                    {{--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"--}}
                    {{--data-close-others="true">--}}
                    {{--<i class="icon-bell"></i>--}}
                    {{--<span class="badge badge-success">--}}
                    {{--7 </span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                    {{--<li class="external">--}}
                    {{--<h3><span class="bold">12 pending</span> notifications</h3>--}}
                    {{--<a href="extra_profile.html">view all</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<ul class="dropdown-menu-list scroller" style="height: 250px;"--}}
                    {{--data-handle-color="#637283">--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">just now</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-success">--}}
                    {{--<i class="fa fa-plus"></i>--}}
                    {{--</span> New user registered. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">3 mins</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-danger">--}}
                    {{--<i class="fa fa-bolt"></i>--}}
                    {{--</span> Server #12 overloaded. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">10 mins</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-warning">--}}
                    {{--<i class="fa fa-bell-o"></i>--}}
                    {{--</span> Server #2 not responding. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">14 hrs</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-info">--}}
                    {{--<i class="fa fa-bullhorn"></i>--}}
                    {{--</span> Application error. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">2 days</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-danger">--}}
                    {{--<i class="fa fa-bolt"></i>--}}
                    {{--</span> Database overloaded 68%. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">3 days</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-danger">--}}
                    {{--<i class="fa fa-bolt"></i>--}}
                    {{--</span> A user IP blocked. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">4 days</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-warning">--}}
                    {{--<i class="fa fa-bell-o"></i>--}}
                    {{--</span> Storage Server #4 not responding dfdfdfd. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">5 days</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-info">--}}
                    {{--<i class="fa fa-bullhorn"></i>--}}
                    {{--</span> System Error. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="time">9 days</span>--}}
                    {{--<span class="details">--}}
                    {{--<span class="label label-sm label-icon label-danger">--}}
                    {{--<i class="fa fa-bolt"></i>--}}
                    {{--</span> Storage server failed. </span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    <li class="separator hide">
                    <li class="list-box" style="margin-top: 16px;">
                        <a id="account_bal" href="/user/payment">
                            BALANCE : &nbsp;
                            <span class="label label-success" style="font-size: 16px; border-radius: 12px;">
                                <i class="fa fa-usd "></i>
                                 <span class="account_bal"
                                       style="font-size: 16px; border-radius: 12px;">
                                    @if(isset(Session::get('ig_user')['account_bal']))
                                         {{Session::get('ig_user')['account_bal']}}
                                     @else
                                         0.0000
                                     @endif
                                </span>
                            </span>
                        </a>
                    </li>
                    <!-- END NOTIFICATION DROPDOWN -->
                    <li class="separator hide">
                    </li>
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown dropdown-user dropdown-extended">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                           data-close-others="true">
                            <span class="username username-hide-on-mobile"> {{Session::get('ig_user')['username']}} </span>
                            {{--<img alt="" class="img-circle" src="http://i.imgur.com/lvh4rG2.jpg"/>--}}

                            <img class="img-circle img-responsive"
                                 src="@if(isset(Session::get('ig_user')['profile_pic'])) {{Session::get('ig_user')['profile_pic']}} @else /assets/uploads/useravatar/default-profile-pic.png @endif "
                                 alt="user"/>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="/user/accountOverview">
                                    <i class="icon-user"></i> My Profile </a>
                            </li>
                            {{--<li>--}}
                            {{--<a href="page_calendar.html">--}}
                            {{--<i class="icon-calendar"></i> My Calendar </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="inbox.html">--}}
                            {{--<i class="icon-envelope-open"></i> My Inbox <span class="badge badge-danger">--}}
                            {{--3 </span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="page_todo.html">--}}
                            {{--<i class="icon-rocket"></i> My Tasks <span class="badge badge-success">--}}
                            {{--7 </span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li class="divider">
                            </li>
                            {{--<li>--}}
                            {{--<a href="extra_lock.html">--}}
                            {{--<i class="icon-lock"></i> Lock Screen </a>--}}
                            {{--</li>--}}
                            <li>
                                <a href="/user/logout">
                                    <i class="icon-key"></i> Log Out </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix"></div>

<!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar md-shadow-z-2-i  navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                <li class="start ">
                    <a href="/user/dashboard">
                        <i class="icon-speedometer"></i>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:;">
                        <i class="icon-user"></i>
                        <span class="title">My Account</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="/user/accountOverview">
                                <i class="fa fa-home"></i> Over View
                            </a>
                        </li>
                        {{--<li>--}}
                        {{--<a href="/user/payment">--}}
                        {{--<i class="fa fa-money"></i>Add Balance--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        <li>
                            <a href="javascript:;">
                                <i class="fa fa-money"></i>
                                <span class="title">Add Balance</span>
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="/user/payment">
                                        <i class="fa fa-paypal"></i>Through Paypal
                                    </a>
                                </li>
                                <li>

                                    <a href="/user/2coPayment">
                                        <i class="fa fa-credit-card"></i>Through 2CO
                                    </a>
                            </ul>
                        </li>
                        <li>
                            <a href="/user/depositHistory">
                                <i class="fa fa-files-o"></i> Deposit History
                            </a>
                        </li>
                        <li>
                            <a href="/user/updateProfileInfo">
                                <i class="fa fa-cog"></i>Account Settings
                            </a>
                        </li>

                        <li>
                            <a href="/user/changePassword">
                                <i class="fa fa-unlock"></i>Change Password
                            </a>
                        </li>
                        <li>
                            <a href="/user/changeAvatar">
                                <i class="fa fa-unlock"></i> Change Avatar
                            </a>
                        </li>

                        {{--<li>--}}
                        {{--<a href="javascript:;">--}}
                        {{--<i class="fa fa-instagram"></i>Instagram Settings--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        <li>
                            <a href="/user/emailNotifications">
                                <i class="fa fa-bell"></i>Notification
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;">
                        <i class="icon-basket"></i>
                        <span class="title">Market</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="/user/addOrder">
                                <i class="icon-plus"></i> Add Order
                            </a>
                        </li>
                        <li>
                            <a href="/user/orderHistory">
                                <i class="icon-clock"></i> Order History
                            </a>
                        </li>
                        {{--<li>--}}
                        {{--<a href="javascript:;">--}}
                        {{--<i class="icon-doc"></i> Automatic Orders--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        <li>
                            <a href="/user/addAutolikesOrder">
                                <i class="fa fa-instagram"></i> Instagram Auto Likes
                            </a>
                        </li>
                        <li>
                            <a href="/user/pricingInformation">
                                <i class="icon-wallet"></i> Pricing &amp; Information
                            </a>
                        </li>
                        {{--<li>--}}
                        {{--<a href="javascript:;">--}}
                        {{--<i class="icon-grid"></i> API Docs--}}
                        {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                        {{--<a href="javascript:;">--}}
                        {{--<i class="icon-puzzle"></i> Scripts--}}
                        {{--</a>--}}
                        {{--</li>--}}

                    </ul>
                </li>

                <li>
                    <a href="javascript:;">
                        <i class="fa fa-money"></i>
                        <span class="title">Support</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="/user/faq">
                                <i class="fa fa-instagram"></i>FAQ
                            </a>
                        </li>
                        <li>
                            <a href="/user/contactPage">
                                <i class="fa fa-user"></i>Contact Us
                            </a>
                        </li>
                        <li>
                            <a href="/user/termsOfServicePage">
                                <i class="fa fa-legal"></i>Terms of Service
                            </a>
                        </li>
                        <li>
                            <a href="/user/paymentPage">
                                <i class="fa fa-shopping-cart"></i>Payment Policy
                            </a>
                        </li>
                        <li>
                            <a href="/user/refundsPage">
                                <i class="fa fa-eye-slash"></i>Refunds Policy
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:;">
                        <i class="fa fa-ticket"></i>
                        <span class="title">Tickets</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="/user/create-ticket">
                                <i class="fa fa-plus-circle"></i>  Create Tickets
                            </a>
                        </li>
                        <li>
                            <a href="/user/show-tickets">
                                <i class="fa fa-file-text-o"></i> Show Tickets
                            </a>
                        </li>
                    </ul>
                </li>

                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-camera"></i>--}}
                {{--<span class="title">Instagram Tool</span>--}}
                {{--<span class="arrow "></span>--}}
                {{--</a>--}}
                {{--<ul class="sub-menu">--}}
                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-plus"></i> Add Order--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-clock"></i> Order History--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-doc"></i> Automatic Orders--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-camera"></i> Instagram Auto Likes--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-wallet"></i> Pricing &amp; Information--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-grid"></i> API Docs--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--<li>--}}
                {{--<a href="javascript:;">--}}
                {{--<i class="icon-puzzle"></i> Scripts--}}
                {{--</a>--}}
                {{--</li>--}}
                {{--</ul>--}}
                {{--</li>--}}
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->

    <!-- BEGIN CONTENT -->
    @yield('content')
            <!-- END CONTENT -->

</div>
<!-- END CONTAINER -->

<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        2016 &copy; InstaPanel
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

@include('User/Layouts/usercommonfooterscripts')
@yield('pagejavascripts')
<script type="text/javascript">
    setInterval(function () {
        //your jQuery ajax code
        $.ajax({
            type: "post",
            url: "/user/getBalance",
            dataType: "json",
            success: function (response) {
                console.log(response['status']);
                if (response['status'] == 'success') {
                    $('.account_bal').text(response['data']['account_bal']);
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }, 30000);
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>