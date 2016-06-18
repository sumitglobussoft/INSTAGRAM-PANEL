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

<link rel="shortcut icon" href="favicon.ico" />


@endsection
@section('classTickets','active')
@section('classTickets1','active')
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
                <a href="/user/create-ticket">Create Tickets</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Create Tickets</span>
                        </div>
                    </div>
                    @if(Session::has('message'))
                        @if(session('status')=='Success')
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <div>{{Session::get('message')}}</div>
                            </div>
                        @endif
                        @if(session('status')=='Error')
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span></button>
                                <div>{{Session::get('message')}}</div>
                            </div>
                        @endif
                    @endif
                    <div class="portlet-body">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <p class="panel-title" style="color: green">
                                    <b>&nbsp;{{Session::get('ig_user')['username']}}</b>
                                </p>
                            </div>
                            <div class="panel-body">
                                <form method="post" action="" id="ticketsend">

                                    <div class="form-group">
                                        <label for="subject" class="control-label">Subject</label>
                                        <input type="text" class="form-control" name="subject" id="subjet" value="{{old('subject')}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="message" class="control-label">Message</label><span style="color: red">&nbsp;*</span>
                                        <textarea id="text" name="text" rows="5" class="form-control"></textarea>
                                        <div class="error" style="color:red">{{ $errors->first('text') }}</div>
                                    </div>
                                    <div style="font-size: 10px;">Please provide any additional information that you feel necessary! Such as : Instagram Accounts , Task, Types, Order Ids etc. </div>
                                    <p></p>
                                    <button type="submit" class="btn btn-default" id="send">Submit Tickets</button>
                                </form>
                            </div>
                        </div>


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
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
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

@endsection