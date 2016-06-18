@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Manage Users</li>
            </ol>
            <div class="page-header_title">
                <h1>Pending Users </h1>
            </div>
        </section>

        <section class="page-content">

            <h1> User Lists</h1>
            <hr>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="bg-info">
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 0;?>
                @if($users!=[ ])
                    @foreach ($users as $user)
                        <tr id="test">
                            <td>{{ ++$count }}</td>
                            <td>{{ $user->name }}  {{ $user->lastname}}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <select class="change-status" data-id="{{$user->id}}">
                                    <option value="0">Pending</option>
                                    <option value="1">Approve</option>
                                    <option value="3">Reject</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                @else

                    <tr>
                        <td colspan="5" style="font-size: medium; text-align: center"> No Pending Users Left.
                        </td>
                    </tr>
                @endif

                </tbody>

            </table>
        </section>
    </section>

@endsection


@section('pagescripts')

    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}
    <script>
        $(document).ready(function () {

            $(document.body).on("change", ".change-status", function () {
                var obj = $(this);
                var userId = $(this).attr('data-id');
                var status = $(this).val();
                var msg = (status == '1') ? ' Approve ' : ' Reject ';
                var x = confirm("Are you sure, you want to" + msg + "this ID ?");
                if (status != 0) {
                    if (x) {
                        $.ajax({
                            url: '/admin/users-ajax-handler',
                            type: 'POST',
                            datatype: 'json',
                            data: {
                                method: 'changeStatus',
                                id: userId,
                                status: status
                            },
                            success: function (response) {
                                response = $.parseJSON(response);

//                        toastr[response['status']](response['msg']);
                                if (response['status'] == '200') {
                                    obj.parent().parent().remove();
//                            var oTable = $('#optionTable').dataTable();
//                            oTable.fnDeleteRow(document.getElementById('option-' + optionId));
                                } else {

                                }//TODO SHOW MESSAGE
                            },
                        });
                    }
                }
            });
        });

    </script>

@endsection