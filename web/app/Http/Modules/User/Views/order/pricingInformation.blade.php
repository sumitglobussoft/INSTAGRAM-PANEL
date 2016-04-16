@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')

        <!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" href="/assets/css/select2.css"/>
<link rel="stylesheet" href="/assets/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap.css"/>

<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
<link href="/assets/css/plugins-md.css" rel="stylesheet"/>
<link href="/assets/css/layout.css" rel="stylesheet"/>
<link href="/assets/css/light.css" rel="stylesheet" id="style_color"/>
<link href="/assets/css/custom.css" rel="stylesheet"/>
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico"/>

@endsection

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
                <h1>Price & Information</h1>
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
                <a href="javascript:;">Market</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/user/pricingInformation">Information & Price</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Price & Information  Listing</span>
                            <span class="caption-helper">Check services price...</span>
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-container">

                            <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                                <thead>
                                <tr role="row" class="heading">
                                    <th width="">Service</th>
                                    <th width="">Minimum / Maximum</th>
                                    <th width="">Rate Per 1000</th>
                                    <th width="">Rate per Unit</th>
                                    <th width="">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($plansList))
                                    @foreach($plansList as $plan)
                                        <tr>
                                            <td><i class="fa fa-instagram"></i>&nbsp;&nbsp;{{$plan['plan_name']}}</td>
                                            <td>{{$plan['min_quantity']}} / {{$plan['max_quantity']}}</td>
                                            <td>{{$plan['charge_per_unit']}}</td>
                                            <td>{{$plan['charge_per_unit']/1000}}</td>
                                            <td>@if($plan['status']==1)
                                                    <span class="badge badge-success text-case">Working</span>
                                                @else
                                                    <span class="badge badge-success text-case">Not Working</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
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


        <!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/assets/js/select2.min.js"></script>
<script src="/assets/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/dataTables.bootstrap.js"></script>


<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<script src="/assets/js/datatable.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>

@endsection



