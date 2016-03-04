<!DOCTYPE html>
<html>
<head>
    @include('Supplier/Layouts/supplierheadscripts')
    @yield('headcontent')

</head>
<body>
<!-- Header Start -->
<header>
    <a href="" class="logo"> I N S T A P A N E L </a>
    <div class="pull-right">
        <ul id="mini-nav" class="clearfix">
            <li class="list-box hidden-xs">
                <a href="#" data-toggle="modal" data-target="#modalMd">
                    <!--						<span class="text-white">Code</span> <i class="fa fa-code"></i>-->
                </a>
                <!-- Modal -->
                <div class="modal fade" id="modalMd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel5" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title text-danger" id="myModalLabel5">Coding Style </h4>
                            </div>
                            <div class="modal-body">
                                {{--<img src="img/documentation.png" alt="Esquare Admin">--}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-box dropdown">
                <a id="drop5" href="javascript:;">
                    BALANCE :
                    <span class="label label-success" style="font-size: 16px;"> <i class="fa fa-usd "></i>@if(isset(Session::get('ig_supplier')['account_bal'])) {{Session::get('ig_supplier')['account_bal']}} @else 0.0000 @endif </span>
                </a>
            </li>
            <li class="list-box user-profile">
                <a id="drop7" href="#" role="button" class="dropdown-toggle user-avtar" data-toggle="dropdown">
                    <img width=80 src="@if(isset(Session::get('ig_supplier')['profile_pic'])) {{Session::get('ig_supplier')['profile_pic']}} @else /assets/uploads/useravatar/default-profile-pic.png @endif " alt="user">
                </a>
                <ul class="dropdown-menu server-activity">
                    {{--<li>--}}
                        {{--<p><i class="fa fa-cog text-info"></i> Account Settings</p>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<p><i class="fa fa-fire text-warning"></i> Payment Details</p>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<div class="demo-btn-group clearfix">--}}
                            {{--<a href="#" data-original-title="" title="">--}}
                                {{--<i class="fa fa-facebook fa-lg icon-rounded info-bg"></i>--}}
                            {{--</a>--}}
                            {{--<a href="#" data-original-title="" title="">--}}
                                {{--<i class="fa fa-twitter fa-lg icon-rounded twitter-bg"></i>--}}
                            {{--</a>--}}
                            {{--<a href="#" data-original-title="" title="">--}}
                                {{--<i class="fa fa-linkedin fa-lg icon-rounded linkedin-bg"></i>--}}
                            {{--</a>--}}
                            {{--<a href="#" data-original-title="" title="">--}}
                                {{--<i class="fa fa-pinterest fa-lg icon-rounded danger-bg"></i>--}}
                            {{--</a>--}}
                            {{--<a href="#" data-original-title="" title="">--}}
                                {{--<i class="fa fa-google-plus fa-lg icon-rounded success-bg"></i>--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    <li>
                        <div class="demo-btn-group clearfix">
                            <a href="/supplier/logout" class="btn btn-danger">
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>
<!-- Header End -->

<!-- Main Container start -->
<div class="dashboard-container">

    <div class="container">
        <!-- Top Nav Start -->
        <div id='cssmenu'>
            <ul class="wcontent">
                <li class="navbar-links active" id="dashboard">
                    <a href="/supplier/dashboard"><i class="fa fa-home"></i>Home</a>
                </li>
                <li class="navbar-links"  id="myAccount">
                    <a href="/supplier/myAccount" >
                        <i class="fa fa-user"></i> My Account
                    </a>
                </li>
                <li class="navbar-links" id="market">
                    <a href="javascript:;" ><i class="fa fa-shopping-cart"></i>Market</a>
                    <ul>
                        <li><a href="/supplier/addOrder">Add Order</a></li>
                        <li><a href="/supplier/orderHistory">Orders History</a></li>
                        {{--<li><a href="javascript:;">Automatic Orders</a></li>--}}
                        {{--<li><a href="javascript:;">Instagram Auto-Likes</a></li>--}}
                        {{--<li><a href="javascript:;">Pricing &amp; Information</a></li>--}}
                        {{--<li style="border-top:1px #FFF solid;"><a href="javascript:;">API Docs</a></li>--}}
                        {{--<li><a href="javascript:;">Scripts</a></li>--}}
                    </ul>
                </li>
                {{--<li class="navbar-links">--}}
                    {{--<a href="javascript:;"  ><i class="fa fa-instagram"></i>Instagram Tool</a>--}}
                    {{--<ul>--}}
                        {{--<li><a href="javascript:;">Accounts Manager</a></li>--}}
                        {{--<li><a href="javascript:;">Running Tasks</a></li>--}}
                        {{--<li><a href="javascript:;">Tool Settings</a></li>--}}
                        {{--<li>--}}
                            {{--<a href="javascript:;">Tools</a>--}}
                            {{--<ul>--}}
                                {{--<li><a href="javascript:;">Analytics</a></li>--}}
                                {{--<li><a href="javascript:;">Scrapper</a></li>--}}
                                {{--<li><a href="javascript:;">Bulk Actions</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="javascript:;">Account Creator</a>--}}
                            {{--<ul>--}}
                                {{--<li><a href="javascript:;">Bulk Creator</a></li>--}}
                                {{--<li><a href="javascript:;">Single Creator</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li><a href="javascript:;">Purchase License</a></li>--}}
                        {{--<li><a href="javascript:;">Changelog</a></li>--}}
                        {{--<li><a href="javascript:;">API</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}

                <li class="">
                    <a href="javascript:;"  class="navbar-links"><i class="fa fa-shopping-cart"></i>Tickets</a>
                    <ul>
                        <li><a href="/user/create-ticket">Create Tickets</a></li>
                        <li><a href="/user/show-tickets">Show Tickets</a></li>
                        {{--<li><a href="javascript:;">Automatic Orders</a></li>--}}
                        {{--<li><a href="javascript:;">Instagram Auto-Likes</a></li>--}}
                        {{--<li><a href="javascript:;">Pricing &amp; Information</a></li>--}}
                        {{--<li style="border-top:1px #FFF solid;"><a href="javascript:;">API Docs</a></li>--}}
                        {{--<li><a href="javascript:;">Scripts</a></li>--}}
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Top Nav End -->

        {{------------------------------------------CONTENT GOES HERE-----------------------------------------}}
        @yield('content')

        <footer>

            <p>&copy; InstaPanel 2015-16</p>
        </footer>

    </div>
</div>
<!-- Main Container end -->



@include('Supplier/Layouts/suppliercommonfooterscripts')

@yield('pagejavascripts')
</body>
</html>