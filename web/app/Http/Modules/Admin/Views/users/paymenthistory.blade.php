@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Payment History</li>
            </ol>
            <div class="page-header_title">
                <h1>Payment History </h1>
            </div>
        </section>
        <section class="page-content">

            <h1>User's Transaction Lists</h1>
            <hr>
            <table id="table_id" class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="bg-info" style="background-color: skyblue">
                    <th>#</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Transaction Id</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="no-sort"></th>
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
                order: [0, 'desc'],
                columnDefs: [{
                    "targets": 'no-sort',
                    "orderable": false,
                }],
                ajax: '/admin/paymentHistory',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'},
                    {data: 'transcationId', name: 'transcationId'},
                    {data: 'amount', name: 'amount'},
                    {data: 'date', name: 'date'},
                    {data: 'status', name: 'status'},
                    {data: 'details', name: 'details'}
                ]
            });
        });
    </script>

@endsection