@extends('Supplier/Layouts/supplierlayout')

@section('title','Dashboard')


@section('headcontent')
{{--OPTIONAL--}}
{{--PAGE STYLES OR SCRIPTS LINKS--}}

@endsection

@section('content')
{{--PAGE CONTENT GOES HERE--}}

        <!-- Sub Nav End -->
<div class="sub-nav hidden-sm hidden-xs">
    <ul>
        <li><a href="javascript:;" class="heading">Dashboard</a></li>
    </ul>
    <div class="custom-search hidden-sm hidden-xs">
        <input type="text" class="search-query" placeholder="Search here ...">
        <i class="fa fa-search"></i>
    </div>
</div>
<!-- Sub Nav End -->


<!-- Dashboard Wrapper Start -->
<div class="dashboard-wrapper-lg">
<h3>Welcome to Supplier Dashboard</h3>
    {{--<!-- Row starts -->--}}
    {{--<div class="row">--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-6">--}}
            {{--<div class="mini-widget">--}}
                {{--<div class="mini-widget-heading clearfix">--}}
                    {{--<div class="pull-left">Pending Orders</div>--}}
                    {{--<div class="pull-right"><i class="fa fa-angle-up"></i> 12.2<sup>%</sup></div>--}}
                {{--</div>--}}
                {{--<div class="mini-widget-body clearfix">--}}
                    {{--<div class="pull-left">--}}
                        {{--<i class="fa fa-globe"></i>--}}
                    {{--</div>--}}
                    {{--<div class="pull-right number">0</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-6">--}}
            {{--<div class="mini-widget mini-widget-red">--}}
                {{--<div class="mini-widget-heading clearfix">--}}
                    {{--<div class="pull-left">Processing Orders</div>--}}
                    {{--<div class="pull-right"><i class="fa fa-angle-up"></i> 18.3<sup>%</sup></div>--}}
                {{--</div>--}}
                {{--<div class="mini-widget-body clearfix">--}}
                    {{--<div class="pull-left">--}}
                        {{--<i class="fa fa-twitter"></i>--}}
                    {{--</div>--}}
                    {{--<div class="pull-right number">0</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-6">--}}
            {{--<div class="mini-widget mini-widget-green">--}}
                {{--<div class="mini-widget-heading clearfix">--}}
                    {{--<div class="pull-left">Cancelled Orders</div>--}}
                    {{--<div class="pull-right"><i class="fa fa-angle-down"></i> 21.9<sup>%</sup></div>--}}
                {{--</div>--}}
                {{--<div class="mini-widget-body clearfix">--}}
                    {{--<div class="pull-left">--}}
                        {{--<i class="fa fa-upload"></i>--}}
                    {{--</div>--}}
                    {{--<div class="pull-right number">0</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-lg-3 col-md-3 col-sm-6">--}}
            {{--<div class="mini-widget mini-widget-grey">--}}
                {{--<div class="mini-widget-heading clearfix">--}}
                    {{--<div class="pull-left">Completed Orders</div>--}}
                    {{--<div class="pull-right"><i class="fa fa-angle-up"></i> 67.1<sup>%</sup></div>--}}
                {{--</div>--}}
                {{--<div class="mini-widget-body clearfix">--}}
                    {{--<div class="pull-left">--}}
                        {{--<i class="fa fa-coffee"></i>--}}
                    {{--</div>--}}
                    {{--<div class="pull-right number">0</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<!-- Row ends -->--}}

    {{--<!-- Row Start -->--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="widget">--}}
                {{--<div class="widget-header">--}}
                    {{--<div class="title">SERVER NEWS &nbsp;&nbsp;--}}
                        {{--<small>Check the news everyday for promotions and updates--}}
                        {{--</small>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="widget-body">--}}
                    {{--<div class="wrapper">--}}
                        {{--<div id="scrollbar">--}}
                            {{--<div class="scrollbar">--}}
                                {{--<div class="track">--}}
                                    {{--<div class="thumb">--}}
                                        {{--<div class="end">--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="viewport">--}}
                                {{--<div class="overview">--}}
                                    {{--<div class="featured-articles-container">--}}
                                        {{--<h5 class="heading"> Recent Articles </h5>--}}

                                        {{--<div class="articles">--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Hosting Made For--}}
                                                {{--WordPress--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Reinvent cutting-edge--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> partnerships models--}}
                                                {{--24/7--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Eyeballs frictionless--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Empower deliver--}}
                                                {{--innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Portals technologies--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Collaborative innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Mashups experiences--}}
                                                {{--plug--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Portals technologies--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Collaborative innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Mashups experiences--}}
                                                {{--plug--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> B2B plug and play--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Need some interesting--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Hosting Made For--}}
                                                {{--WordPress--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Reinvent cutting-edge--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> partnerships models--}}
                                                {{--24/7--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Eyeballs frictionless--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Empower deliver--}}
                                                {{--innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Portals technologies--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Collaborative innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Mashups experiences--}}
                                                {{--plug--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Portals technologies--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Collaborative innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Mashups experiences--}}
                                                {{--plug--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> B2B plug and play--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Need some interesting--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Hosting Made For--}}
                                                {{--WordPress--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Reinvent cutting-edge--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> partnerships models--}}
                                                {{--24/7--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Eyeballs frictionless--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Empower deliver--}}
                                                {{--innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Portals technologies--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Collaborative innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Mashups experiences--}}
                                                {{--plug--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Portals technologies--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Collaborative innovate--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Mashups experiences--}}
                                                {{--plug--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> B2B plug and play--}}
                                            {{--</a>--}}
                                            {{--<a href="#">--}}
                                                {{--<span class="label-bullet">&nbsp;</span> Need some interesting--}}
                                            {{--</a>--}}
                                        {{--</div>--}}

                                    {{--</div>--}}

                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<!-- Row End -->--}}

    {{--<!-- Row Start -->--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="widget">--}}
                {{--<div class="widget-header">--}}
                    {{--<div class="title">INVITE YOUR FRIENDS! &nbsp;&nbsp;--}}
                        {{--<small>Invite your friends to INSTAPANEL</small>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="widget-body">--}}
                    {{--<div class="wrapper">--}}
                        {{--<a href="#" class="list-group-item">--}}
                            {{--<h4 class="list-group-item-heading">Time to Invite!</h4>--}}

                            {{--<p class="list-group-item-text">--}}
                                {{--INSTAPANEL is now a private community. We will still open the public registry--}}
                                {{--sometimes but our main concept will be invited based. Each user will have 10-20--}}
                                {{--Invites to send. Use the form bellow to invite your friend directly with a--}}
                                {{--unique link!--}}
                            {{--</p>--}}
                        {{--</a>--}}
                        {{--<br/>--}}

                        {{--<form class="" role="form" style="padding: 1% 2%;">--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group">--}}
                                    {{--<div class="input-group-addon"><span class="fa fa-envelope"></span></div>--}}
                                    {{--<input type="email" class="form-control" id=""--}}
                                           {{--placeholder="yourfriend@domain.com"/>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group">--}}
                                    {{--<div class="input-group-addon">Remaining Invites</div>--}}
                                    {{--<input type="text" class="form-control" id="" value="53" disabled/>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<div class="input-group">--}}
                                    {{--<div class="input-group-addon">Invites Accepted</div>--}}
                                    {{--<input type="text" class="form-control" id="" value="00" disabled/>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<button class="btn btn-primary"> INVITE</button>--}}
                            {{--</div>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<!-- Row End -->--}}

</div>
<!-- Dashboard Wrapper End -->

{{--<a href="/supplier/profileView">Profile</a> <br><br>--}}



@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
    {{--<script src="/assets/plugins/js/jquery.scrollUp.js"></script>--}}

    {{--<!-- jQuery UI JS -->--}}
    {{--<script src="/assets/plugins/js/jquery-ui-v1.10.3.js"></script>--}}

    {{--<!-- Scroller -->--}}
    {{--<script src="/assets/plugins/js/tiny-scrollbar.js"></script>--}}

    {{--<!-- Custom JS -->--}}
    {{--<script src="/assets/plugins/js/menu.js"></script>--}}
    {{--<script src="/assets/plugins/js/custom.js"></script>--}}

    {{--<script>--}}
        {{--//ScrollUp--}}
        {{--$(function () {--}}
            {{--$.scrollUp({--}}
                {{--scrollName: 'scrollUp', // Element ID--}}
                {{--topDistance: '300', // Distance from top before showing element (px)--}}
                {{--topSpeed: 300, // Speed back to top (ms)--}}
                {{--animation: 'fade', // Fade, slide, none--}}
                {{--animationInSpeed: 400, // Animation in speed (ms)--}}
                {{--animationOutSpeed: 400, // Animation out speed (ms)--}}
                {{--scrollText: 'Top', // Text for element--}}
                {{--activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF'--}}
            {{--});--}}
        {{--});--}}

        {{--//Tiny Scrollbar--}}
        {{--$('#scrollbar').tinyscrollbar();--}}
    {{--</script>--}}
@endsection

