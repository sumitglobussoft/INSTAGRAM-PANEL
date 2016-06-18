@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/css/toastr.css"/>

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
                    <h1>Order History</h1>
                    <hr>
                    <div class="col-sm-12">
                        <div class="table-group-actions pull-right col-sm-6">
                            <span class="col-sm-4" id="displaySelectedRecord"></span>

                            <div class="col-sm-4">
                                <select class="table-group-action-input form-control input-inline input-small input-sm"
                                        id="selectAction">
                                    <option value="0">Select Action</option>
                                    <option value="1">Cancel selected Order(s)</option>
                                    <option value="2">Re-Add selected Order(s)</option>
                                </select>
                            </div>
                            <div class="col-sm-4 pull-right">
                                <button name="actionSubmit" id="actionSubmit"
                                        class="btn btn-sm btn-default table-group-action-submit pull-right"
                                        data-original-title="" title=""><i class="fa fa-check"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <table id="table_id" class="details-control">
                        <thead>
                        <tr class="bg-info">
                            <th><input type="checkbox" id="groupCheckBox"></th>
                            <th>OrderId</th>
                            <th>Name</th>
                            <th>Plan Type</th>
                            <th>Instagram Link</th>
                            <th>Quantity Total</th>
                            <th>Status</th>
                            <th>Details</th>
                            {{--<th>Action</th>--}}
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
                                        {{--<tr>--}}
                                        {{--<td>--}}
                                        {{--Start time:--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<strong id="startTime"></strong>--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--End Time:--}}
                                        {{--</td>--}}
                                        {{--<td>--}}
                                        {{--<strong id="endTime"></strong>--}}
                                        {{--</td>--}}
                                        {{--</tr>--}}
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
    <script src="/js/toastr.js"></script>
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({
                processing: true,
                serverSide: true,
                order: [1, 'desc'],
                ajax: '/admin/orders-list-ajax',
                columns: [
                    {data: 'chck', name: 'chck', orderable: false, searchable: false},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'by_user_id', name: 'by_user_id'},
                    {data: 'plan_id', name: 'plan_id'},
                    {data: 'ins_url', name: 'ins_url'},
                    {data: 'quantity_total', name: 'quantity_total'},
                    {data: 'status', name: 'status'},
                    {data: 'view', name: 'view'},
//                    {data: 'reAdd', name: 'reAdd'}

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
                                status = "Processing";
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

            $(document.body).on('click', '#actionSubmit', function () {
                toastr.options.positionClass = "toast-top-center";
                toastr.options.preventDuplicates = true;
                toastr.options.closeButton = true;
                var id = [];
                var count = 0;
                $.each($("input[name='checkbox']:checked"), function () {
                    count++;
                    id.push($(this).val());
//                    console.log(value);
                });
                console.log(count);
                $('#displaySelectedRecord').html(count + " records selected");
                if (count == 0) {
                    toastr.options.positionClass = "toast-top-center";
                    toastr.error("No order has selected");
                } else {
                    console.log(id);

//                var id = $(this).attr('data-id');
                    var value = $("#selectAction option:selected").val();
                    if (value == 0) {
//                    alert('Please select an action');
                        toastr.options.positionClass = "toast-top-right";
                        toastr.error("Please select an action");
                    } else {
                        var msg = (value == 1) ? 'Cancel' : 'Restart';
                        console.log(value);
                        if (value == 1 || value == 2) {
                            console.log(id);
                            var msg1 = (count == 1) ? 'this order' : 'these ' + count + ' orders'
                            var x = confirm("Are you sure you want to " + msg + " " + msg1 + " ");
                            if (x) {
                                $.ajax({
                                    url: '/admin/cancelOrderAjaxHandler',
                                    type: 'post',
                                    datatype: 'json',
                                    data: {
                                        orderId: id,
                                        method: msg
                                    },
                                    success: function (response) {
//                                response = $.parseJSON(response);
//                                if (response['status'] == '200') {
//                                    toastr.success('Order has successfully cancelled and amount has been refunded to the account.', {timeOut: 5000});
//                                    location.reload();
//                                }
                                        response = $.parseJSON(response);
                                        if (response['status'] == '200') {
                                            for (var i = 0; i < count; i++) {
                                                if (response.message[i].charAt(0) == 'O') {
                                                    toastr.success(response.message[i]);
                                                } else
                                                    toastr.error(response.message[i], {timeOut: 9000});
                                            }
//                                                alert(response.message);
                                            setTimeout(function () {
                                                location.reload();
                                            }, 7000);
                                        }
//                                else if (response['status'] == '600') {
//                                    toastr.success(response.message);
//                                    location.reload();
//
//                                }
                                        else if (response['status'] == '400') {
                                            toastr.error(response.message);
                                        }
                                    }
                                });
                            }
                        }
                    }
                }
            });

        });
    </script>

    <script>

        // add multiple select / deselect functionality
        $('#groupCheckBox').click(function (event) {

            //$(".orderCheckBox").prop('checked', $(this).prop("checked"));

            if (this.checked) {
                console.log('group box checked');
                $('.orderCheckBox').each(function () {
                    this.checked = true;
                });
                var recordCount = $(".orderCheckBox").length;
                $('#displaySelectedRecord').html(recordCount + " records selected ");
            } else {
                console.log('group box un checked');
                $('.orderCheckBox').each(function () {
                    this.checked = false;
                });
                $('#displaySelectedRecord').html("");
            }
        });
    </script>

@endsection