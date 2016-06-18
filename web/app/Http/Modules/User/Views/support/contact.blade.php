@extends('User/Layouts/userlayout')

@section('title','Contact Us')


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
@section('classSupport','active')
@section('classSupport2','active')
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
                <h1>Contact</h1>
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
                <a href="javascript:;">Support</a>
                <i class="fa fa-circle"></i>
            </li>
            {{--<li>--}}
                {{--<a href="javascript:;">Support</a>--}}
            {{--</li>--}}
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-inbox font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Contact Us</span>
                            {{--<span class="caption-helper">manage your orders...</span>--}}
                        </div>
                    </div>
                    <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <form class="" action="" role="form">
                                        <div class="form-group">
                                            <label class="control-label"> Subject : </label>
                                            <input type="text" class="form-control"
                                                   name="subject"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"> Message : </label>
                                                                        <textarea class="form-control"
                                                                                  name="message"></textarea>
                                        </div>
                                        <div class="form-group text-center">
                                            <button class="btn btn-primary">SUBMIT</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <ul class="list-unstyled">
                                        <li>
                                            <p>
                                                <b>Skype :</b> messazon.support
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                <b>Email :</b> support@messazon.com
                                            </p>
                                        </li>
                                        <li>
                                            <p>
                                                <b>FAQ :</b> <a href="/user/faq">Visit FAQ</a>
                                            </p>
                                        </li>
                                    </ul>
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
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->
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
<!-- END JAVASCRIPTS -->
@endsection



