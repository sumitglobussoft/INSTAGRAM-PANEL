@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    {{--<link rel="stylesheet" type="text/css" href="/css/datatables.bs.css"/>--}}
    {{--<link rel="stylesheet" type="text/css" href="/css/datatable-bootstrap-t1.css"/>--}}
    {{--<link rel="stylesheet" type="text/css" href="/css/datatable-t1.css"/>--}}
    <link rel="stylesheet" href="http://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="/css/toastr.css"/>

    <style>
        #page-wrapper #left-content #sidebar-wrapper {
            overflow-y: hidden;
        }

        .onoffswitch {
            position: relative;
            width: 90px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }

        .onoffswitch-checkbox {
            display: none;
        }

        .onoffswitch-label {
            display: block;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid #999999;
            border-radius: 20px;
        }

        .onoffswitch-inner {
            display: block;
            width: 200%;
            margin-left: -100%;
            transition: margin 0.3s ease-in 0s;
        }

        .onoffswitch-inner:before, .onoffswitch-inner:after {
            display: block;
            float: left;
            width: 50%;
            height: 30px;
            padding: 0;
            line-height: 30px;
            font-size: 14px;
            color: white;
            font-family: Trebuchet, Arial, sans-serif;
            font-weight: bold;
            box-sizing: border-box;
        }

        .onoffswitch-inner:before {
            content: "Active";
            padding-left: 10px;
            background-color: #34C247;
            color: #FFFFFF;
        }

        .onoffswitch-inner:after {
            content: "INACTIVE";
            padding-right: 10px;
            font-size: 10px;
            background-color: red;
            color: white;
            text-align: right;
        }

        .onoffswitch-switch {
            display: block;
            width: 18px;
            margin: 6px;
            background: #FFFFFF;
            position: absolute;
            top: 0;
            bottom: 0;
            right: 56px;
            border: 2px solid #999999;
            border-radius: 20px;
            transition: all 0.3s ease-in 0s;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
            margin-left: 0;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
            right: 0px;
        }

        .cmn-toggle {
            position: absolute;
            margin-left: -9999px;
            visibility: hidden;
        }

        .cmn-toggle + label {
            display: block;
            position: relative;
            cursor: pointer;
            outline: none;
            user-select: none;
        }

        input.cmn-toggle-yes-no + label {
            padding: 2px;
            width: 60px;
            height: 30px;
        }

        input.cmn-toggle-yes-no + label:before,
        input.cmn-toggle-yes-no + label:after {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            color: #fff;
            font-family: "Roboto Slab", serif;
            font-size: 10px;
            text-align: center;
            line-height: 30px;
        }

        input.cmn-toggle-yes-no + label:before {
            /*background-color: red;*/
            content: attr(data-text);
            transition: transform 0.5s;
            backface-visibility: hidden;
        }

        input.cmn-toggle-yes-no + label:after {
            /*background-color: green;*/
            content: attr(data-text);
            transition: transform 0.5s;
            transform: rotateY(180deg);
            backface-visibility: hidden;
        }

        input.cmn-toggle-yes-no:checked + label:before {
            transform: rotateY(180deg);
        }

        input.cmn-toggle-yes-no:checked + label:after {
            transform: rotateY(0);
        }

        /*.dataTables_length.form-group {*/
        /*margin-top: 10%;*/
        /*width: 50%;*/
        /*}*/
        /*.dataTables_filter {*/
        /*float: right;*/
        /*margin-top: -6%;*/
        /*width: 50%;*/
        /*}*/
    </style>

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Plans Lists</li>
            </ol>
            <div class="page-header_title">
                <h1>Plans Lists </h1>
            </div>
        </section>

        <section class="page-content">

            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-default panel-divider">
                    <div class="panel-heading">
                        <header>
                            <span class="pull-left" style="margin-top: 16px;">Available Plans lists </span>
                            <a href="/admin/add-plans" class="btn btn-primary pull-right"><i
                                        class="fa fa-plus-circle"></i> Add New Plan
                            </a>
                        </header>
                    </div>

                    <div class="panel-body" style="padding-top: 0;">
                        <div class="row">
                            @if(Session::has('message'))
                                <div class="alert alert-info"
                                     style="color:red;"> {{Session::get('message')}} </div> @endif
                            <div class="col-md-12">

                                <div class="form-group pull-left ">
                                    <label class="control-label">Choose Category</label>
                                    <select class="js-example-responsive form-control"
                                            name="plan_type_id" id="plan_type_id" required>
                                        {{--<option selected> Select Category</option>--}}
                                        <option value="5"> Show All</option>
                                        <option value="0"> Instagram likes</option>
                                        <option value="1"> Instagram followers</option>
                                        <option value="2"> Instagram comments</option>
                                        <option value="4"> Instagram views</option>
                                    </select>
                                </div>

                                <div class="form-group pull-right">
                                    <label class="control-label">Choose Type </label>
                                    <select class="js-example-responsive form-control"
                                            name="service_type_id" id="service_type_id"
                                            required>
                                        {{--<option selected> Select Type </option>--}}
                                        <option value="5"> Show All</option>
                                        <option value="R"> Real</option>
                                        <option value="F"> Fake</option>
                                        <option value="T"> Targeted</option>
                                    </select>
                                </div>
                                <table class="table" id="example-table">

                                    <thead>
                                    <tr class="bg-info">
                                        <th>Service</th>
                                        <th>Min Quantity</th>
                                        <th>Max Quantity</th>
                                        <th>Rate Per 1000</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>
@endsection


@section('pagescripts')
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
    <script src="/js/toastr.js"></script>

    <script>
        $(document).ready(function () {
            var oTable = $('#example-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/admin/plans-datatables-ajax',

                    data: function (d) {
                        d.method = "first";
                    }
                },
                columns: [
                    {data: 'service', name: 'sevice'},
                    {data: 'min', name: 'min'},
                    {data: 'max', name: 'max'},
//                {data: 'lastname', name: 'lastname'},
                    {data: 'ratepk', name: 'ratepk'},
                    {data: 'status', name: 'status'},
                    {data: 'edit', name: 'edit'}
                ]
            });
        });
        $(document.body).on('change', '.form-control', function (e) {
            var planType = $("#plan_type_id").children('option').filter(":selected").val();
            var serviceType = $("#service_type_id").children('option').filter(':selected').val();
            var oTable = $('#example-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/admin/plans-datatables-ajax',
                    data: function (d) {
                        d.planType = planType;
                        d.serviceType = serviceType;
                        d.method = "second";
                    }
                },
                columns: [
                    {data: 'service', name: 'sevice'},
                    {data: 'min', name: 'min'},
                    {data: 'max', name: 'max'},
//                {data: 'lastname', name: 'lastname'},
                    {data: 'ratepk', name: 'ratepk'},
                    {data: 'status', name: 'status'},
                    {data: 'edit', name: 'edit'}
                ]
            });
        });
    </script>
    <script>
        $(document.body).on('click', '#status', function () {
            toastr.options.positionClass = "toast-top-center";
            toastr.options.preventDuplicates = true;
            toastr.options.closeButton = true;
            var obj = $(this);
            var id = $(this).attr('data-id');
            var status = (document.getElementById("status").style.backgroundColor == "green") ? '0' : '1';
            alert(status);
            console.log(status);
//            var status = ($(this).hasClass("onoffswitch-inner:after")) ? '0' : '1';
//            var status = ($(this).hasClass("onoffswitch-inner:after")) ? '0' : '1';
//            alert(status);
//            var status = ($(this).hasClass("fa-check-circle")) ? '0' : '1';
//            console.log(id);
////                var status = $(this).attr('data-id');
////                if (status == 1)
////                    status = 0;
////                else
////                    status = 1;
//            console.log(id);
//            console.log(status);
////            document.write(status);die;
//            var msg = (status == '1') ? 'Activate' : 'Deactivate';
//            if (confirm("Are you sure to " + msg)) {
            $.ajax({

                url: '/admin/plans-ajax-handler',
                type: 'POST',
                datatype: 'json',
                data: {
                    method: 'changeStatus',
                    id: id,
                    status: status
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response['status'] == '200') {
                        toastr.success(response.message);
                        if (status == 0) {
//                            obj.removeClass('fa-check-circle');
                            obj.text('Inactive');
//                            obj.css('color', 'red');
                            obj.css('background-color', 'red');
                        } else {
//                            obj.removeClass('fa-times-circle');
//                            obj.addClass('fa-check-circle');
//                            obj.css('color', 'green');
//                            obj.css('background-color', 'lightgreen');
                            obj.css('background-color', 'red');
                            obj.css('transition','transform 0.5s');
                            obj.css('transform','rotateY(180deg)');
                            obj.text('Inactive');
//                            obj.css('backface-visibility','hidden');

                        }

                    }
                    else if (rsponse['status'] == '400') {
                        toastr.error(response.message);
                        console.log('err');
                    }

//                            location.reload();

                }
            });
//            }
        });
    </script>
@endsection