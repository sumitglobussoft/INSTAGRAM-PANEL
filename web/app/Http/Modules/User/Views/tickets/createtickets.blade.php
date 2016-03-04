@extends('User/Layouts/userlayout')

@section('title','Tickets')

@section('headcontent')
    <title>Tickets</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">--}}
    <style>
        .container-raise-t {
            padding: 0px;
        }

        .container-raise-t .col-panel {
            margin-left: 30%;
            margin-top: 3%;
        }
    </style>


@endsection

@section('content')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP User</li>
                <li>Create Tickets</li>
            </ol>
        </section>

            <div class="container container-raise-t">
                <div class="row">
                    <div class="col-md-4 col-panel">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <p class="panel-title" style="color: green">
                                    <b>&nbsp; &nbsp; &nbsp;{{Session::get('ig_user')['username']}}</b>
                                </p>
                            </div>
                            @if(Session::has('message'))
                                @if(session('status')=='Success')
                                    <div style="color:green;"><b>{{session('status')}}</b> {{Session::get('message')}}
                                    </div>
                                @endif
                                @if(session('status')=='Error')
                                    <div style="color:green;"><b>{{session('status')}}</b> {{Session::get('message')}}
                                    </div>
                                @endif
                            @endif
                            <div class="panel-body">
                                <form method="post" action="" id="ticketsend">
                                    <div class="alert alert-success alert-dismissible hide" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <div>Show Success</div>
                                    </div>
                                    <div class="alert alert-danger alert-dismissible hide" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <div>Show Failure</div>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject" class="control-label">Subject</label>
                                        <input type="text" class="form-control" name="subject" id="subjet">
                                    </div>

                                    <div class="form-group">
                                        <div class="error" style="color:red">{{ $errors->first('text') }}</div>
                                        <label for="message" class="control-label">Message</label>
                                        <textarea id="text" name="text" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div style="font-size: 10px;">Please provide any additional information that you feel necessary! Such as : Instagram Accounts , Task, Types, Order Ids etc. </div>

                                    <button type="submit" class="btn btn-default" id="send">Submit Tickets</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

@endsection

@section('pagejavascripts')
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"--}}
            {{--type="text/javascript"></script>--}}
@endsection
