@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Tickets</li>
            </ol>
            <div class="page-header_title">
                <h1>Closed Tickets </h1>
            </div>
        </section>

        <section class="page-content">

            <h1>Closed Tickets</h1>
            <hr>
            <table id="table_id" class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="bg-info">
                    <th>#TicketID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Descriptions</th>
                    {{--<th>Status</th>--}}
                    <th>Queried_at</th>
                </tr>
                </thead>


            </table>
        </section>
    </section>
@endsection


@section('pagescripts')

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>


    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/closedtickets-datatables',
                columns: [
                    {data: 'ticket_id', name: 'ticket_id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'subject', name: 'subject'},
                    {data: 'descriptions', name: 'descriptions'},
//                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'}
                ]
            });
        });
    </script>

@endsection