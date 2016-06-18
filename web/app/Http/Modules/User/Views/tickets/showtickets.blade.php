@extends('User/Layouts/userlayout')

@section('title','Tickets')


@section('headcontent')
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/default.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/profile.css" rel="stylesheet" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->
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

<link rel="shortcut icon" href="favicon.ico" />
@endsection
@section('classTickets','active')
@section('classTickets2','active')
@section('content')
{{--PAGE CONTENT GOES HERE--}}
        <!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE HEAD -->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Tickets</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD -->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="/user/dashboard">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="javascript:;">Tickets</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/user/show-tickets">Show Tickets</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                {{--<div class="note note-danger note-shadow">--}}
                {{--<p>--}}
                {{--NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only.--}}
                {{--</p>--}}
                {{--</div>--}}
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Tickets</span>
                        </div>
                    </div>
                    <div class="portlet-body">
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
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->

@endsection

@section('pagejavascripts')

<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>
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



