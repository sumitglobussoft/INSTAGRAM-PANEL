@extends('User/Layouts/userlayout')

@section('title','Add Balance')


@section('headcontent')
{{--OPTIONAL--}}
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
@section('classMarket','active')
@section('classMarket4','active')
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
                <h1>Edit Comment Group</h1>
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
                <a href="/user/show-comments">Manage Comments</a>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Edit Comment Group</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="panel panel-default panel-divider">
                                    <div class="panel-body" style="padding-top: 0;">
                                        @if(Session::has('msg'))
                                            @if(session('status')=='Success')
                                                <div style="color:green;">
                                                    <b>{{session('status')}}</b> {{Session::get('msg')}} <a
                                                            href="/user/show-comments">Go Back</a></div>
                                            @elseif(session('status')=='Error')
                                                <div style="color:red;">
                                                    <b>{{session('status')}}</b> {{Session::get('msg')}}</div>
                                            @endif
                                        @endif
                                        <form class="form" role="form" method="post">
                                            <div class="form-group floating-label has-success">
                                                <input class="form-control" id="comment_group_name" type="text"
                                                       name="comment_group_name"
                                                       value="{{$cgd->comment_group_name}}">
                                                <label for="regular2">Comment Group</label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('comment_group_name') }}</div>
                                            </div>

                                            <div class="form-group floating-label">
                                                <textarea class="form-control" id="comment" name="comment" rows="10"
                                                          cols="100">@if($cd!=0)@foreach($cd as $grp){{$grp."\n"}}@endforeach @endif</textarea>
                                                <label for="regular2">Comments</label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('comment') }}</div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-theme btn-raised btn-block"
                                                        id="addcomment"><i class="fa fa-check-circle-o"></i>Save
                                                </button>
                                            </div>
                                            <div class="col-md-2" class="pull-right">
                                                <a href="/user/show-comments" class="btn btn-default"
                                                   id="goback"><i class="fa fa-arrow-circle-left"></i> Back
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
    {{--PAGE SCRIPTS GO HERE--}}
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
    <script>

        $(document).ready(function () {
            $('#addcomment').attr('disabled', true);
            $('#comment').keyup(function () {
                var comment = $.trim($('#comment').val());
//        if($.trim($(this).val().length) !=0)
                if (comment.length == 0)
                    $('#addcomment').attr('disabled', true);
                else
                    $('#addcomment').attr('disabled', false);
            })
            $('#comment_group_name').keyup(function () {
                var comment = $.trim($('#comment').val());
//        if($.trim($(this).val().length) !=0)
                if (comment.length == 0)
                    $('#addcomment').attr('disabled', true);
                else
                    $('#addcomment').attr('disabled', false);
            })
        });
    </script>

@endsection