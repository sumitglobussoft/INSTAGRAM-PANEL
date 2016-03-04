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
    @include('Admin/Layouts/adminheadcontent')
    @yield('pageheadcontent')
</head>

<body class="dark-sidebar dark-header-brand container-fluid">
<div id="page-wrapper">
    <aside id="left-content" data-toggle="open" data-default="open" data-size="">
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
                        <a href="/admin/dashboard">
                            <span class="menu-item-ico"><i class="material-icons">dashboard</i></span>
                            <span class="menu-item-name">Dashboard</span>
                        </a>
                    </li>
                    {{--<li>--}}
                        {{--<a href="/admin/edit-profile">--}}
                            {{--<span class="menu-item-ico"><i class="material-icons">perm_identity</i></span>--}}
                            {{--<span class="menu-item-name">Profile Settings</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico"><i class="material-icons">shopping_cart</i></span>--}}
                            {{--<span class="menu-item-name">Social Market</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li>
                        <a href="javascript:;">
                            <span class="menu-item-ico"><i class="fa fa-instagram"></i></span>
                            <span class="menu-item-name">Manage Users</span>
                        </a>
                        <ul>
                            <li>
                                <a href="/admin/users-list">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-eye"></i></span>
                                    <span class="menu-item-name menu-right">Pending Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/users-list-active">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-users"></i></span>
                                    <span class="menu-item-name menu-right">Available Users</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/users-list-rejected">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-clock-o"></i></span>
                                    <span class="menu-item-name menu-right">Rejected Users</span>
                                </a>
                                {{--<ul>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;"> Auto Like <span class="badge badge-danger">5</span> </a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;"> Auto Comment <span class="badge badge-danger">3</span>--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;"> Auto Follow <span class="badge badge-danger">8</span>--}}
                                        {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;"> Auto Unfollow </a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;"> Picture Uploader </a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;"> Profile Image </a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<a href="javascript:;"> Profile Uploader </a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            </li>
                            {{--<li>--}}
                                {{--<a href="javascript:;">--}}
                                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-database"></i></span>--}}
                                    {{--<span class="menu-item-name menu-right">Mass Promoter</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;">--}}
                                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-user-plus"></i></span>--}}
                                    {{--<span class="menu-item-name menu-right">Account Creator</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="javascript:;">--}}
                                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-paw"></i></span>--}}
                                    {{--<span class="menu-item-name menu-right">Scrapper</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                        </ul>
                    </li>
                    <li>
                        <a href="/admin/ig_users-details">
                            <span class="menu-item-ico"><i class="fa fa-instagram"></i></span>
                            <span class="menu-item-name">Instagram AutoLikes Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <span class="menu-item-ico"><i class="material-icons">shopping_cart</i></span>
                            <span class="menu-item-name">Manage Orders</span>
                        </a>
                        <ul>
                            <li>
                                <a href="/admin/plans-list">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-eye"></i></span>
                                    <span class="menu-item-name menu-right">Available plans Lists</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/orders-list">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-users"></i></span>
                                    <span class="menu-item-name menu-right">All Order Lists</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;">
                            <span class="menu-item-ico"><i class="fa fa-life-ring"></i></span>
                            <span class="menu-item-name">Manage Comments</span>
                        </a>
                        <ul>
                            {{--<li>--}}
                                {{--<a href="/admin/add-comments">--}}
                                    {{--<span class="menu-item-ico ico-right"><i class="fa fa-paper-plane"></i></span>--}}
                                    {{--<span class="menu-item-name menu-right">Add Comments</span>--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a href="/admin/show-comments">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-eye"></i></span>
                                    <span class="menu-item-name menu-right">Comment Details </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <span class="menu-item-ico"><i class="fa fa-life-ring"></i></span>
                            <span class="menu-item-name">Manage Tickets</span>
                        </a>
                        <ul>
                            <li>
                                <a href="/admin/ticketdetails">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-paper-plane"></i></span>
                                    <span class="badge badge-danger">2</span>
                                    <span class="menu-item-name menu-right">Tickets</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/closedtickets">
                                    <span class="menu-item-ico ico-right"><i class="fa fa-eye"></i></span>
                                    <span class="menu-item-name menu-right">Closed Tickets </span>
                                </a>
                            </li>
                        </ul>
                    </li>


                    {{--<li>--}}
                        {{--<a href="javascript:;">--}}
                            {{--<span class="menu-item-ico"><i class="fa fa-life-ring"></i></span>--}}
                            {{--<span class="menu-item-name">Support</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="samples.html">--}}
                            {{--<span class="menu-item-ico"><i class="fa fa-paper-plane"></i></span>--}}
                            {{--<span class="menu-item-name">Samples</span>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                </ul>
            </nav>
            <!-- END: nav#sidebar -->
        </div>
    </aside>

    <section id="right-content">
        <header class="header-container">
            <div class="header-wrapper">
                <div id="header-toolbar">
                    <ul class="toolbar toolbar-left">
                        <li>
                            <a id="sidebar-toggle" data-state="open" href="#"><i class="material-icons">menu</i></a>
                        </li>
                    </ul>

                    <ul class="toolbar toolbar-right">
                        <li id="user-profile" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                {{--<div class="avatar">--}}
                                    {{--<img src="/images/avatar.png" class="img-circle img-responsive"/>--}}
                                {{--</div>--}}
                                <div class="user">
                                    <span class="username">{{Session::get('instagram_admin')['username']}}</span>
                                </div>
                                <span class="expand-ico"><i class="material-icons">expand_more</i></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                {{--<li><a href="#"><i class="material-icons">person</i>Your Profile</a></li>--}}
                                <li><a href="/admin/edit-profile"><i class="material-icons">settings</i>Profile Settings</a></li>
                                {{--<li class="divider"></li>--}}
                                {{--<li><a href="#"><i class="material-icons">lock</i> Lock</a></li>--}}
                                {{--<li class="divider"></li>--}}
                                <li><a href="/admin/logout"><i class="material-icons">exit_to_app</i> Log Out</a></li>
                            </ul>
                        </li>
                        <!-- /#user-profile -->
                    </ul>
                    <!-- /.navbar-right -->


                </div>
            </div>
            <!-- /#header-toolbar -->
        </header>
        {{--<section id="right-content-wrapper">--}}
            {{--<section class="page-header alternative-header">--}}
                {{--<ol class="breadcrumb">--}}
                    {{--<li>IP Admin</li>--}}
                    {{--<li>Samples</li>--}}
                {{--</ol>--}}
                {{--<div class="page-header_title">--}}
                    {{--<h1> Samples </h1>--}}
                {{--</div>--}}
            {{--</section>--}}

            {{--<section class="page-content">--}}
                @yield('pagecontent')
            {{--</section>--}}
            <!-- /#page-content -->
        </section>
        <!-- /#right-content -->
    </section>
    <!-- /#right-content-wrapper -->
</div>

<!-- Modal HTML -->
<div id="demoModal1" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modal Heading</h4>
            </div>
            <div class="modal-body">
                <p>Loreum Ipsum Loreum Ipsum Loreum Ipsum Loreum Ipsum Loreum Ipsum Loreum Ipsum Loreum Ipsum Loreum
                    Ipsum Loreum Ipsum Loreum Ipsum .</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-flat btn-ripple" data-dismiss="modal">Disagree</button>
                <button type="button" class="btn btn-primary btn-flat btn-ripple">Agree</button>
            </div>
        </div>
    </div>
</div>

@include('Admin/Layouts/admincommonscripts')
@yield('pagescripts')



</body>

</html>