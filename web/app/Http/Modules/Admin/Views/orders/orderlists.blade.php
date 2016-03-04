@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Manage Orders</li>
            </ol>
            <div class="page-header_title">
                <h1>Order Lists </h1>
            </div>
        </section>

        <section class="page-content">

            <div class="row">
                <div class="col-md-12">
                    <h1>Order Details</h1>
                    <hr>
                    <table id="table_id" class="details-control">
                        <thead>
                        <tr class="bg-info">
                            <th>OrderId</th>
                            <th>Name</th>
                            <th>Plan Type</th>
                            <th>Instagram Link</th>
                            <th>Quantity Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>


            <div id="modal1" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2" id="avatar">
                                    <img src="/images/avatar.png" class="img-responsive img-circle"/>
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-responsive table-hover" id="viewTable">
                                        <tbody>
                                        <tr>
                                            <td colspan="2">
                                                Name:
                                            </td>
                                            <td colspan="2">
                                                <strong id="name"> </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Email:
                                            </td>
                                            <td colspan="2">
                                                <strong id="email"></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Plan Name:
                                            </td>
                                            <td colspan="2">
                                                <strong id="planName"></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Instagram Link:
                                            </td>
                                            <td colspan="2">
                                                <strong id="ins_url"><a href="javascript:;"></a></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Total Quantity:
                                            </td>
                                            <td>
                                                <strong id="totalQuantity"></strong>
                                            </td>
                                            <td>
                                                Quantity done:
                                            </td>
                                            <td>
                                                <strong id="quantityDone"></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Start time:
                                            </td>
                                            <td>
                                                <strong id="startTime"></strong>
                                            </td>
                                            <td>
                                                End Time:
                                            </td>
                                            <td>
                                                <strong id="endTime"></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Status:
                                            </td>
                                            <td>
                                                <strong id="status"></strong>
                                            </td>
                                            <td>
                                                Added Time:
                                            </td>
                                            <td>
                                                <strong id="addedTime"></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Updated time:
                                            </td>
                                            <td>
                                                <strong id="updatedTime"></strong>
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                            <td>
                                                <strong>&nbsp;</strong>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>


@endsection


@section('pagescripts')
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}
    <script type="text/javascript" charset="utf8"
            src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/orders-list-ajax',
                columns: [
                    {data: 'order_id', name: 'order_id'},
                    {data: 'by_user_id', name: 'by_user_id'},
                    {data: 'plan_id', name: 'plan_id'},
                    {data: 'ins_url', name: 'ins_url'},
                    {data: 'quantity_total', name: 'quantity_total'},
                    {data: 'status', name: 'status'},
                    {data: 'view', name: 'view'}

                ]
            });
        });
    </script>
    <script>

        $(document).ready(function () {

            $(document.body).on('click', '.btn-raised', function () {
                var id = $(this).attr('data-id');
                console.log(id);
                $.ajax({

                    url: '/admin/view-orders',
                    type: 'POST',
                    datatype: 'json',
                    data: {
                        id: id,
                    },
                    success: function (response) {

                        response = $.parseJSON(response);
                        console.log('test')
                        console.log(response.data);
                        console.log(response.startTime);
                        startTime = '<td><strong>' + response.startTime + '</strong></td>';
                        endTime = '<td><strong>' + response.endTime + '</strong></td>';
                        addedTime = '<td><strong>' + response.addedTime + '</strong></td>';
                        updatedTime = '<td><strong>' + response.updatedTime + '</strong></td>';

//                            toastr.success('this is simply to waste your time, nothing else!!!');
//                            $("#test").html(response.responseText);
                        var trHTML = '';
                        $.each(response.data, function (i, o) {
//                                console.log(response.data);
//                                console.log(o.name)
                            if (o.status == 0)
                                status = "pending";
                            else if (o.status == 1)
                                status = "Queue";
                            else if (o.status == 2)
                                status = "Processing";
                            else if (o.status == 3)
                                status = "Completed";
                            else if (o.status == 4)
                                status = "Failed";
                            else if (o.status == 5)
                                status = "Refunded";
                            else if (o.status == 6)
                                status = "Cancelled";

                            name = '<td><strong>' + o.name + '</strong></td>';
                            email = '<td><strong>' + o.email + '</strong></td>';
                            planName = '<td><strong>' + o.plan_name + '</strong></td>';
                            link = '<td><strong>' + o.ins_url + '</strong></td>';
                            totalQuantity = '<td><strong>' + o.quantity_total + '</strong></td>';
                            quantityDone = '<td><strong>' + o.quantity_done + '</strong></td>';
                            status = '<td><strong>' + status + '</strong></td>';
                            if (o.profile_pic != null) {
                                profile_pic = ' <img src="' + o.profile_pic + '" class="img-responsive img-circle" />';
                                $('#avatar').html(profile_pic);
                            } else if (o.profile_pic == null) {
                                profile_pic = ' <img src="/images/avatar.png" class="img-responsive img-circle" />';
                                $('#avatar').html(profile_pic);
                            }
//                            $('#viewTable').append(trHTML);

                            $('#name').html(name);
                            $('#email').html(email);
                            $('#planName').html(planName);
                            $('#ins_url').html(link);
                            $('#totalQuantity').html(totalQuantity);
                            $('#quantityDone').html(quantityDone);
                            $('#startTime').html(startTime);
                            $('#endTime').html(endTime);
                            $('#status').html(status);
                            $('#addedTime').html(addedTime);
                            $('#updatedTime').html(updatedTime);
                        });

                    }
                });


            });
        });
    </script>

@endsection


{{--<script>--}}

{{--var template = Handlebars.compile($("#details-template").html());--}}

{{--var table = $('#table_id').DataTable({--}}
{{--processing: true,--}}
{{--serverSide: true,--}}
{{--ajax: '/admin/orders-list-ajax',--}}
{{--columns: [--}}
{{--{--}}
{{--"className": 'details-control',--}}
{{--"orderable": false,--}}
{{--"data": null,--}}
{{--"defaultContent": ''--}}
{{--},--}}
{{--{data: 'order_id', name: 'order_id'},--}}
{{--{data: 'by_user_id', name: 'by_user_id'},--}}
{{--{data: 'plan_id', name: 'plan_id'},--}}
{{--{data: 'for_user_id', name: 'for_user_id'},--}}
{{--{data: 'ins_url', name: 'ins_url'},--}}
{{--{data: 'quantity_total', name: 'quantity_total'},--}}
{{--{data: 'status', name: 'status'}--}}
{{--],--}}
{{--order: [[1, 'asc']]--}}
{{--});--}}

{{--// Add event listener for opening and closing details--}}
{{--$('#table_id.tbody').on('click', 'td.details-control', function () {--}}
{{--var tr = $(this).closest('tr');--}}
{{--var row = table.row(tr);--}}

{{--if (row.child.isShown()) {--}}
{{--// This row is already open - close it--}}
{{--row.child.hide();--}}
{{--tr.removeClass('shown');--}}
{{--}--}}
{{--else {--}}
{{--// Open this row--}}
{{--row.child(template(row.data())).show();--}}
{{--tr.addClass('shown');--}}
{{--}--}}
{{--});--}}
{{--</script>--}}
{{--<script id="details-template" type="text/x-handlebars-template">--}}
{{--<table class="table">--}}
{{--<tr>--}}
{{--<td>order_id:</td>--}}
{{--<td>helllo</td>--}}
{{--</tr>--}}
{{--<tr>--}}
{{--<td>ins_url:</td>--}}
{{--<td>hiii</td>--}}
{{--</tr>--}}
{{--<tr>--}}
{{--<td>Extra info:</td>--}}
{{--<td>And any further details here (images etc)...</td>--}}
{{--</tr>--}}
{{--</table>--}}
{{--</script>--}}