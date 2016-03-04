@extends('Supplier/Layouts/supplierlayout')

@section('title','Dashboard')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

@section('headcontent')

@endsection
@section('content')
<h1>Ticket Details</h1>
<hr>
<table id="table_id" class="table table-striped table-bordered table-hover">
    <thead>
    <tr class="bg-info">
        <th>#TicketID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Subject</th>
        <th>Descriptions</th>
        <th>Status</th>
        <th>Queried_at</th>
        <th>View Query</th>

    </tr>
    </thead>


</table>
@endsection

@section('pagejavascripts')

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/user/show-tickets-datatables',
            columns: [
                {data: 'ticket_id', name: 'ticket_id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'subject', name: 'subject'},
                {data: 'descriptions', name: 'descriptions'},
                {data: 'status', name: 'status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'reply', name: 'reply'}
            ]
        });
    });
</script>

@endsection
