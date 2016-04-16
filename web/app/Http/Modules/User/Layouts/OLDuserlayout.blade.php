<!DOCTYPE html>
{{--<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->--}}
{{--<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->--}}
{{--<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->--}}
{{--<!--[if gt IE 8]><!-->--}}
<html class="no-js">
<!--<![endif]-->
<head>
    @include('User/Layouts/userheadscripts')
    @yield('headcontent')
</head>
<body class="dark-sidebar dark-header-brand container-fluid sidebar-open">

<div id="page-wrapper">
    <aside id="left-content" data-toggle="open" data-default="open" data-size="" class="SidebarOpen">
        <header class="header-container">
            <div class="header-wrapper">
                <div id="header-brand">
                    <div class="logo padding-left-2">
                        <span class="logo-image">IP</span>
                        <span class="logo-text">InstaPanel</span>
                    </div>
                </div>
            </div>
        </header>
        <div id="sidebar-wrapper">
            <nav id="sidebar">
                <ul>
                    <li>
                        <a href="/user/dashboard">
                            <span class="menu-item-ico"><i class="material-icons">dashboard</i></span>
                            <span class="menu-item-name">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:;">
                            <span class="menu-item-ico"><i class="material-icons">perm_identity</i></span>
                            <span class="menu-item-name">My Account</span>
                        </a>
                        <ul>
                            <li>
                                <a href="/user/accountOverview">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-home"></i></span>
                                    <span class="menu-item-name menu-right">&nbsp; Over View</span>
                                </a>
                            </li>
                            <li>
                                <a href="/user/updateProfileInfo">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-cog"></i></span>
                                    <span class="menu-item-name menu-right">&nbsp; Account Setting</span>
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-files-o"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">&nbsp; Deposit History</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a href="/user/changePassword">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-unlock"></i></span>
                                    <span class="menu-item-name menu-right">&nbsp; Change Password</span>
                                </a>
                            </li>
                            <li>
                                <a href="/user/changeAvatar">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-unlock"></i></span>
                                    <span class="menu-item-name menu-right">&nbsp; Change Avatar</span>
                                </a>
                            </li>
                            <li>
                                <a href="/user/payment">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-money"></i></span>
                                    <span class="menu-item-name menu-right">&nbsp; Add Balance</span>
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-instagram"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">&nbsp; Instagram Settings</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-bell"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">&nbsp; Notification</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="/user/faq">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-archive"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">&nbsp; FAQ</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                        </ul>
                    </li>

                    {{--<li>--}}
                    {{--<a href="/user/myAccount">--}}
                    {{--<span class="menu-item-ico"><i class="material-icons">perm_identity</i></span>--}}
                    {{--<span class="menu-item-name">My Account</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    <li>
                        <a href="javascript:;">
                            <span class="menu-item-ico"><i class="material-icons">shopping_cart</i></span>
                            <span class="menu-item-name">Market</span>
                        </a>
                        <ul>
                            <li>
                                <a href="/user/addOrder">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-plus-circle"></i></span>
                                    <span class="menu-item-name menu-right">Add Order</span>
                                </a>
                            </li>
                            <li>
                                <a href="/user/orderHistory">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-history"></i></span>
                                    <span class="menu-item-name menu-right">Order History</span>
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a href="/user/addAutoOrder">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-history"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">Automatic Orders</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a href="/user/addAutolikesOrder">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-instagram"></i></span>
                                    <span class="menu-item-name menu-right">Instagram Auto Likes</span>
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-instagram"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">Instagram Auto Likes</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-history"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">Pricing &amp; Information</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-cubes"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">API Docs</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico ico-right"><i class="fa fa-puzzle-piece"></i></span>--}}
                            {{--<span class="menu-item-name menu-right">Scripts</span>--}}
                            {{--</a>--}}
                            {{--</li>--}}
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;">
                            <span class="menu-item-ico"><i class="material-icons">perm_identity</i></span>
                            <span class="menu-item-name">Tickets</span>
                        </a>
                        <ul>
                            <li>
                                <a href="/user/create-ticket">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-money"></i></span>
                                    <span class="menu-item-name menu-right">&nbsp; Create Tickets</span>
                                </a>
                            </li>
                            <li>
                                <a href="/user/show-tickets">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-archive"></i></span>
                                    <span class="menu-item-name menu-right">&nbsp; Show Tickets</span>
                                </a>
                            </li>
                        </ul>
                    </li>


                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico"><i class="fa fa-instagram"></i></span>--}}
                    {{--<span class="menu-item-name">Instagram Tool</span>--}}
                    {{--</a>--}}
                    {{--<ul>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-users"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">Accounts Manager</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-server"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">Running Tasks</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-cog"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">Tool Settings</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-wrench"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">Tools</span>--}}
                    {{--</a>--}}
                    {{--<ul>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;"> Analytics </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;"> Scrapper </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;"> Bulk Actions </a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-star"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">Account Creator</span>--}}
                    {{--</a>--}}
                    {{--<ul>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;"> Bulk Creator </a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;"> Single Creator </a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-clock-o"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">Purchase License</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-bug"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">Changelog</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="javascript:;">--}}
                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-share-alt-square"></i></span>--}}
                    {{--<span class="menu-item-name menu-right">API</span>--}}
                    {{--</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                </ul>
            </nav>
            <!-- END: nav#sidebar -->
        </div>
    </aside>


    <section id="right-content" class="SidebarOpen">

        <!-- Top Header Start -->
        <header class="header-container">
            <div class="header-wrapper">
                <div id="header-toolbar">
                    <ul class="toolbar toolbar-left">
                        <li>
                            <a id="sidebar-toggle" data-state="open" class="SidebarOpen" href="javascript:;"><i
                                        class="material-icons">menu</i></a>
                        </li>
                    </ul>
                    <!-- Start top navbar-right -->
                    <ul class="toolbar toolbar-right">
                        <li class="dropdown">

                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">

                                @if(isset(Session::get('ig_user')['count']))
                                    <span class="badge badge-danger">
                                        {{Session::get('ig_user')['count']}}
                                        </span>
                                @endif

                                <i class="material-icons">notifications</i></a>

                            <ul class="dropdown-menu">
                                @if(isset(Session::get('ig_user')['notification']))
                                    <li><a href="/user/notification"><i class="fa fa-envelope"></i>You have a new
                                            message.</a></li>
                                    <li><a href="/user/notification">{{Session::get('ig_user')['notification']}}</a>
                                    </li>
                                @else
                                    <li><a href="#"><i class="fa fa-envelope"></i>You Dont have any new
                                            Notification.</a></li>
                                @endif
                                <li><a href="/user/notification"><i class="fa fa-envelope"></i>Show all
                                        notifications.</a></li>
                            </ul>
                        </li>

                        <li class="list-box dropdown">
                            <a id="account_bal" href="/user/payment">
                                BALANCE : &nbsp;
                                <span class="label label-success account_bal"
                                      style="font-size: 16px; border-radius: 12px;"> <i
                                            class="fa fa-usd "></i>
                                    @if(isset(Session::get('ig_user')['account_bal']))
                                        {{Session::get('ig_user')['account_bal']}}
                                    @else
                                        0.0000
                                    @endif
                                </span>
                            </a>
                        </li>
                        <li id="user-profile" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <div class="avatar">
                                    <img class="img-circle img-responsive"
                                         src="@if(isset(Session::get('ig_user')['profile_pic'])) {{Session::get('ig_user')['profile_pic']}} @else /assets/uploads/useravatar/default-profile-pic.png @endif "
                                         alt="user"/>
                                </div>
                                <div class="user">
                                    <span class="username">{{Session::get('ig_user')['name']}}</span>
                                </div>
                                <span class="expand-ico"><i class="material-icons">expand_more</i></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#"><i class="material-icons">person</i>Your Profile</a></li>
                                <li><a href="#"><i class="material-icons">settings</i>Settings</a></li>
                                <li class="divider"></li>
                                <li><a href="#"><i class="material-icons">lock</i> Lock</a></li>
                                <li class="divider"></li>
                                <li><a href="/user/logout"><i class="material-icons">exit_to_app</i> Log Out</a></li>
                            </ul>
                        </li>
                        <!-- /#user-profile -->
                    </ul>
                    <!-- End top navbar-right -->
                </div>
            </div>
            <!-- /#header-toolbar -->
        </header>
        <!-- Top Header  End -->

        {{------------------------------------------CONTENT GOES HERE-----------------------------------------}}
        @yield('content')

    </section>
    <!-- /#right-content-wrapper -->

</div>


@include('User/Layouts/usercommonfooterscripts')
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
    }, 10000); // where X is your every X minutes
</script>
@yield('pagejavascripts')
</body>
</html>