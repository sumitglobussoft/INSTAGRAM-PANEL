@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Dashboard</li>
            </ol>
        </section>

        <section class="page-content">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-primary">
                                <div class="mini-card-left">
                                    <span>Total Users</span>

                                    <h2>{{$count_all}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-success">
                                <div class="mini-card-left">
                                    <a href="/admin/users-list-active">
                                        <span>Active Users</span>

                                        <h2>{{$count_active}}</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-info">
                                <div class="mini-card-left">
                                    <a href="/admin/orders-list">
                                        <span>Total Orders</span>

                                        <h2>{{$total_orders}}</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-saurabh">
                                <div class="mini-card-left">
                                    <a href="/admin/orders-list">
                                        <span>Todays Orders</span>

                                        <h2>{{$todays_orders}}</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-bond">
                                <div class="mini-card-left">
                                    <span>Pending Orders</span>

                                    <h2>{{$pending_orders}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-danger">
                                <div class="mini-card-left">
                                    <span>Failed Orders</span>

                                    <h2>{{$failed_orders}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-globussoft">
                                <div class="mini-card-left">
                                    <span>Refunded Orders</span>

                                    <h2>{{$refunded_orders}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body no-padding">
                            <div class="mini-card mini-card-danger">
                                <div class="mini-card-left">
                                    <span>Cancelled Orders</span>

                                    <h2>{{$cancelled_orders}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{--<div class="row">--}}
            {{--<div class="col-lg-6">--}}
            {{--<div class="panel panel-default panel-divider">--}}
            {{--<div class="panel-heading">--}}
            {{--</div>--}}
            {{--<div class="panel-body no-padding">--}}

            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-lg-6">--}}
            {{--<div class="panel panel-default">--}}
            {{--<div class="panel-heading">--}}

            {{--</div>--}}
            {{--<div class="panel-body height-5 no-padding">--}}

            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
        </section>
    </section>




    {{--<h1>Welcome to admin</h1>--}}
    {{--<a href="logout">Log out</a><br>--}}
    {{--<a href="edit-profile">Edit profile</a>--}}
    {{--<br>--}}
    {{--<a href="suppliers-list">Pending Suppliers Lists</a><br>--}}
    {{--<a href="suppliers-list-active">Available Supplier Lists</a><br>--}}
    {{--<a href="suppliers-list-rejected">Rejected Supplier Lists</a><br>--}}

    {{--<a href="plans-list">Available plans Lists</a><br>--}}
    {{--<a href="orders-list">All Order Lists</a><br>--}}

@endsection


@section('pagescripts')


@endsection






