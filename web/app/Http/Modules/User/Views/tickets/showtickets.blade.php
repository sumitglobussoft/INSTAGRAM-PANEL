@extends('User/Layouts/userlayout')

@section('headcontent')
    <style>
        .btn-success {
            background-color: #65B688;
            border-color: #65B688;
        }

        .btn-danger {
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
        }

        .of {
            /*overflow: auto;*/
            /*height: 670px !important;*/
            margin-bottom: 20px;
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


@section('content')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Tickets</li>
                <li>Reply</li>
            </ol>
        </section>

        <div class="col-md-12">
            <div class="row">
                <div class="of col-md-12">


                    <h1>Your Tickets</h1>
                    @if(Session::has('msg'))
                        <div style="color:green;"><b>{{session('status')}}:</b> {{Session::get('msg')}}</div>
                    @endif
                    {{--<a href="{{url('/admin/addsupplier')}}" class="btn btn-success">ADD User</a>--}}
                    <hr>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr class="bg-info">
                            <th>TicketID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Descriptions</th>
                            <th>Status</th>
                            <th>Queried_at</th>
                            <th>View Query</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($tickets as $ticket)

                            <tr>
                                <td>{{ $ticket->ticket_id }}</td>
                                <td>{{ $ticket->name }} {{ $ticket->lastname }}</td>
                                <td>{{ $ticket->email }}</td>
                                <td>{{ $ticket->subject }}</td>
                                <td>{{ $ticket->descriptions }}</td>
                                <td>
                                    <i data="<?php echo $ticket->ticket_id;?>"
                                       class="status_checks btn <?php echo ($ticket->ticket_status == 0) ? 'btn-success' : 'btn-danger'?>">
                                        <?php echo ($ticket->ticket_status == 0) ? 'Opened' : 'Closed'?>
                                    </i>
                                </td>
                                <td>{{ $ticket->created_at }}</td>
                                <td>
                                    <a href="{{url('/user/conversations',$ticket->ticket_id)}}" class="btn btn-warning">View</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        {{$tickets->links()}}
                    </table>

                </div>
            </div>
        </div>
    </section>
@endsection


@section('pagejavascripts')
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}
    <script>

        $(document).ready(function () {

            $(document).on('click', '.status_checks', function () {
                var status = ($(this).hasClass("btn-success")) ? '1' : '0';
//            document.write(status);die;
                var msg = (status == '1') ? 'Close' : 'Open';
                if (confirm("Are you sure to " + msg + " this ticket")) {
                    var current_element = $(this);
                    $.ajax({

                        url: '/user/show-tickets-status',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'changeStatus',
                            id: $(current_element).attr('data'),
                            status: status
                        },
                        success: function (response) {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>

@endsection


