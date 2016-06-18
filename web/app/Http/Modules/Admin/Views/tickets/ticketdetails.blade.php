@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/css/toastr.css">

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Tickets</li>
                <li>Ticket Details</li>

            </ol>
        </section>

        <section class="page-content">
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
        </section>
    </section>
@endsection


@section('pagescripts')

    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
    <script src="/js/toastr.js"></script>



    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/ticketdetails-datatables',
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
            $(document.body).on('click', '.btn', function () {
                toastr.options.positionClass = "toast-top-center";
                toastr.options.preventDuplicates = true;
                toastr.options.closeButton = true;
                var obj = $(this);
                var status = $(this).attr('data-status');
                console.log(status);
                var ticketId = $(this).attr('data-id');
                var msg = (status == 0) ? 'Open' : 'Close';
                var x = confirm('Are you sure to ' + msg + ' this ticket');
                if (x) {
                    $.ajax({
                        url: '/admin/changeTicketStatus-AjaxHandler',
                        type: 'post',
                        datatype: 'json',
                        data: {
                            ticketId: ticketId,
                            status: status
                        },
                        success: function (response) {
                            response = $.parseJSON(response);
                            if (response['status'] == '200') {
                                if (obj.hasClass('btn-success')) {
                                    obj.removeClass('btn-success');
                                    obj.addClass('btn-danger');
                                    obj.text('Closed');
                                } else if (obj.hasClass('btn-danger')) {
                                    obj.removeClass('btn-danger');
                                    obj.addClass('btn-success');
                                    obj.text('Opened');
                                }
                                toastr.success(response.message, {timeOut: 4000});
                            } else if (response['status'] == '400') {
                                toastr.error(response.message);
                            }
                        }
                    });
                }

            });
        });
    </script>

@endsection