@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

    {{--<link rel="stylesheet" type="text/css" href="/css/datatables.bs.css"/>--}}
    {{--<link rel="stylesheet" type="text/css" href="/css/datatable-bootstrap-t1.css"/>--}}
    {{--<link rel="stylesheet" type="text/css" href="/css/datatable-t1.css"/>--}}

    <style>
        #page-wrapper #left-content #sidebar-wrapper {
            overflow-y: hidden;
        }

        .btn-success {
            background-color: #65B688;
            border-color: #65B688;
        }

        .btn-danger {
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        .btn {
            color: white;
            display: inline-block;
            margin-bottom: 0;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            border-radius: 4px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>


@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Manage Users</li>
                <li>Available Users</li>
            </ol>
        </section>

        <section class="page-content">

            <h1>Available User lists</h1>
            @if(Session::has('msg'))
                <div style="color:green;"><b>{{session('status')}}:</b> {{Session::get('msg')}}</div>
            @endif
            <a href="{{url('/admin/adduser')}}" class="btn btn-success">ADD User</a>
            <hr>
            <div class="paging"></div>
            <table id="example-table" class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="bg-info">
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>
                </thead>


            </table>
            {{--<div class="paging"></div>--}}
        </section>
    </section>
@endsection


@section('pagescripts')
    {{--<script src="/js/jquery.dataTables.min.js"></script>--}}
    {{--<script src="/js/bootstrap_datatable.js"></script>--}}

    {{--<script src="/js/datatable.jquery-t1.js"></script>--}}
    {{--<script src="/js/datatable-t1.js"></script>--}}
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>


    <script>
        $(document).ready(function () {
            $('#example-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/datatables-ajax-available',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'fullname', name: 'fullname'},
                    //                {data: 'lastname', name: 'lastname'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'status'},
                    {data: 'edit', name: 'edit'}
                ]
            });
        });
    </script>


    <script>

        $(document).ready(function () {

            $(document).on('click', '.status_checks', function () {
                var obj = $(this);
                var status = ($(this).hasClass("btn-success")) ? '2' : '1';
                //            document.write(status);die;
                var msg = (status == '2') ? 'Deactivate' : 'Activate';
                if (confirm("Are you sure to " + msg)) {
                    var current_element = $(this);
                    $.ajax({

                        url: '/admin/users-ajax-handler-available',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'changeStatus',
                            id: $(current_element).attr('data-id'),
                            status: status
                        },
                        success: function (response) {
//                            location.reload();
                            response = $.parseJSON(response);
                            if (response['status'] == '200') {
                                if (obj.hasClass('btn-success')) {
                                    obj.removeClass('btn-success');
                                    obj.addClass('btn-danger');
                                    obj.text('Inactive');
                                }
                                else {
                                    obj.removeClass('btn-danger');
                                    obj.addClass('btn-success');
                                    obj.text('Active');
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>

    {{--<script>--}}
        {{--$('#example-table').datatable({--}}
            {{--pageSize: 5,--}}
            {{--sort: [true, true, true, true, true, true],--}}
            {{--filters: [true, true, true, true, false, false],--}}
            {{--filterText: ' '--}}
        {{--});--}}
        {{--$('#Reset-dt').click(function (e) {--}}
            {{--e.preventDefault();--}}
            {{--//$('#example-table').datatable({'reset-filters': ''});--}}
        {{--});--}}
    {{--</script>--}}
    {{--<script>--}}
        {{--//DataTable--}}
        {{--$('#datatable').dataTable();--}}
        {{--$(window).load(function () {--}}
            {{--$('#datatable_filter input, #datatable_length select').addClass('form-control');--}}
            {{--$('#datatable_length').addClass('form-group');--}}
            {{--$("#datatable_info").parent().css("margin-right", "15px").removeClass("col-sm-5");--}}
        {{--});--}}
    {{--</script>--}}

@endsection
