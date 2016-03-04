@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Manage Users</li>
            </ol>
            <div class="page-header_title">
                <h1>Rejected Users </h1>
            </div>
        </section>
        <section class="page-content">

            <h1>Rejected User lists</h1>
            <hr>
            <table id="table_id" class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="bg-info">
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>email</th>
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
                ajax: '/admin/datatables-ajax',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'fullname', name: 'name'},
//                {data: 'lastname', name: 'lastname'},
                    {data: 'username', name: 'username'},
                    {data: 'email', name: 'email'}
                ]
            });
        });
    </script>

@endsection