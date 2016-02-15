@extends('User/Layouts/userlayout')

@section('title','Dashboard')


@section('headcontent')
{{--OPTIONAL--}}
{{--PAGE STYLES OR SCRIPTS LINKS--}}

@endsection

@section('content')
{{--PAGE CONTENT GOES HERE--}}

        <!-- Right-Page-content Start-->
<section id="right-content-wrapper">
    <section class="page-header alternative-header">
        <ol class="breadcrumb">
            <li>IP User</li>
            <li>Dashboard</li>
        </ol>

        <div class="page-header_title">
            {{--<h1> Dashboard --}}
                <span class="page-header_subtitle">Welcome Back, {{Session::get('ig_user')['name']}}</span>
            {{--</h1>--}}
        </div>
    </section>

    <section class="page-content">
        <h3>Welcome to User Dashboard</h3>
        {{--<div class="row">--}}
            {{--<div class="col-lg-12">--}}
                {{--<div class="panel panel-default panel-divider">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<header>Server News</header>--}}

                        {{--<div class="panel-heading-tools">--}}
                            {{--<div class="btn-group">--}}
                                {{--<a class="btn btn-icon-toggle panel-tools-menu dropdown-toggle"--}}
                                   {{--data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i--}}
                                            {{--class="material-icons">more_vert</i></a>--}}
                                {{--<ul class="dropdown-menu dropdown-menu-right">--}}
                                    {{--<li><a href="#">Refresh</a></li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body no-padding">--}}
                        {{--<div class="tasks-list scroll-fancy height-5" data-class="height-5">--}}
                            {{--<section>--}}
                                {{--<ul class="list">--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                    {{--<li class="list-item list-1-line">--}}
                                        {{--<div class="list-icon">--}}
                                            {{--<input class="checkbox checkbox-primary" type="checkbox"/>--}}
                                        {{--</div>--}}
                                        {{--<div class="list-item-text layout-column">--}}
                                            {{--<h3>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean--}}
                                                {{--commodo ligula eget dolor.</h3>--}}
                                        {{--</div>--}}
                                        {{--<button type="button"--}}
                                                {{--class="secondary-container btn btn-default btn-icon-toggle"--}}
                                                {{--data-toggle="tooltip" data-placement="left" title="Delete Task">--}}
                                            {{--<i class="material-icons">delete</i></button>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</section>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="row">--}}
            {{--<div class="col-lg-12">--}}
                {{--<div class="panel panel-default panel-divider">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<header>Invite your friends!</header>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body no-padding">--}}
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
    </section>
    <!-- /#page-content -->
</section>
<!-- Right-Page-content End-->

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
@endsection

