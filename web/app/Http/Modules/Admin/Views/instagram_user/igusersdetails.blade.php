@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')

    <link rel="stylesheet" type="text/css" href="/css/datatables.bs.css"/>
    <link rel="stylesheet" type="text/css" href="/css/datatable-bootstrap-t1.css"/>
    <link rel="stylesheet" type="text/css" href="/css/datatable-t1.css"/>

    <style>
        #page-wrapper #left-content #sidebar-wrapper {
            overflow-y: hidden;
        }
    </style>
@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Instagram AutoLikes Users</li>
            </ol>
        </section>

        <section class="page-content">

            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-default panel-divider">
                    <div class="panel-heading">
                        <header>
                    <span class="pull-left" style="margin-top: 16px;">Instagram Auto-Likes <span
                                style="font-size: 15px;">&nbsp;&nbsp;usernames with auto-likes subscription
                    </span></span>
                            {{--<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addOrders"><i--}}
                            {{--class="fa fa-plus-circle"></i> Add Orders--}}
                            {{--</button>--}}
                        </header>
                    </div>
                    <div class="panel-body" style="padding-top: 0;">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="pull-right">
                                    <a href="/admin/ig_users-details" class="btn btn-xs red filter-cancel" style="background-color: lightcoral"><i class="fa fa-times"></i>Reset</a>
                                </div>
                                <div class="paging"></div>
                                <table class="table" id="example-table">
                                    <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Username</th>
                                        <th>Pics Done</th>
                                        <th>Pics Limit</th>
                                        <th>Likes per Pic</th>
                                        <th>Last Check</th>
                                        <th>Last Delivery</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($igUsersDetails as $igUsers)
                                        <tr>
                                            <td>{{$igUsers->ins_user_id}}</td>
                                            <td>
                                                <a class="fa fa-instagram" style="font-size:12px"
                                                   href="https://www.instagram.com/{{$igUsers->ins_username}}/"
                                                   target="_blank">
                                                    {{$igUsers->ins_username}}</a></td>
                                            <td>{{$igUsers->pics_done}}</td>
                                            <td>{{$igUsers->pics_limit}}</td>

                                            <td>{{$igUsers->likes_per_pic}}</td>
                                            <?php $j = 0; ?>
                                            @foreach($last_check as $lc)
                                                @if($j == $i)
                                                    <td>{{$lc}}</td>
                                                    @break;
                                                @endif
                                                <?php $j++; ?>
                                                @continue;

                                            @endforeach
                                            <?php $j = 0; ?>
                                            @foreach($last_delivery as $ld)
                                                @if($j == $i)
                                                    <td>{{$ld}}</td>
                                                    @break;
                                                @endif
                                                <?php $j++; ?>
                                                @continue;
                                            @endforeach


                                            {{--<td>{{$igUsers->last_check}}</td>--}}
                                            {{--<td>{{$igUsers->last_delivery}}</td>--}}
                                            @if($igUsers->ig_user_status==0)
                                                <td>Failed</td>
                                            @elseif($igUsers->ig_user_status==1)
                                                <td>Completed</td>
                                            @else
                                                <td>Waiting</td>
                                            @endif
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach

                                    </tbody>
                                </table>

                                <div class="paging"></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    @endsection


    @section('pagescripts')


    {{--<script src="/js/jquery-1.10.2.js"></script>--}}

            <!-- Scripts - Included with every page -->
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/bootstrap_datatable.js"></script>

    <script src="/js/datatable.jquery-t1.js"></script>
    <script src="/js/datatable-t1.js"></script>

    <script>
        $('#example-table').datatable({
            pageSize: 5,
            sort: [true, true, true, true, true, true],
            filters: [true, true, true, true, true, true, true, 'select', false],
            filterText: ' '
        });
        $('#Reset-dt').click(function (e) {
            e.preventDefault();
            //$('#example-table').datatable({'reset-filters': ''});
        });
    </script>
    <script>
        //DataTable
        $('#datatable').dataTable();
        $(window).load(function () {
            $('#datatable_filter input, #datatable_length select').addClass('form-control');
            $('#datatable_length').addClass('form-group');
            $("#datatable_info").parent().css("margin-right", "15px").removeClass("col-sm-5");
        });
    </script>
@endsection