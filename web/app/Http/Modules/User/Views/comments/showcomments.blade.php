@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
    {{--OPTIONAL--}}
    <!-- BEGIN THEME STYLES -->
    <link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
    <link href="/assets/css/plugins-md.css" rel="stylesheet"/>
    <link href="/assets/css/layout.css" rel="stylesheet"/>
    <link href="/assets/css/default.css" rel="stylesheet" id="style_color"/>
    <link href="/assets/css/profile.css" rel="stylesheet"/>
    <link href="/assets/css/custom.css" rel="stylesheet"/>
    <!-- END THEME STYLES -->

    <link rel="shortcut icon" href="favicon.ico"/>

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
                    <h1>Comment Group</h1>
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
                    <a href="javascript:;">Manage Comments</a>
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
                                <span class="caption-subject font-green-sharp bold uppercase">Comment Group</span>
                            </div>
                        </div>
                        @if(Session::has('msg'))
                            @if(session('status')=='Success')
                                <div style="color:green;"><b>{{session('status')}}</b> {{Session::get('msg')}}</div>
                            @elseif(session('status')=='Error')
                                <div style="color:green;"><b>{{session('status')}}</b> {{Session::get('msg')}}</div>
                            @endif
                        @elseif($errors)
                            @foreach($errors->all() as $error)
                                <li style="color: red">{{ $error }}</li>
                            @endforeach
                        @endif


                        <div class="panel-heading">
                            <header>
                                <span class="pull-left" style="margin-top: 16px; font-size: 20px;">COMMENTS </span>
                                <a class="btn btn-success btn-raised waves-effect waves-light btn modal-trigger pull-right"
                                   data-toggle="modal"
                                   data-target="#modal-comments"><i
                                            class="fa fa-plus-circle"></i>Create Comment Group</a>
                            </header>
                        </div>


                        <div id="modal-comments" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Comments</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h1></h1>

                                        <form class="form" role="form" method="post"
                                              action="/user/comments-add-ajax-handler">

                                            <div class="form-group floating-label" id='common-group-name'>
                                                <input type="text" class="form-control" id="group-name"
                                                       name="comment_group_name" value="{{old('comment_group_name')}}">
                                                <label for="group-name1">Group Name</label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('comment_group_name') }}</div>
                                            </div>
                                            <div class="form-group floating-label">
                                    <textarea class="form-control" id="comment1" name="comment1" rows="10" cols="100"
                                              value="{{old('comment1')}}"></textarea>
                                                <label for="regular2">Comments<span style="font-size: 12px;">&nbsp; (press enter to store new comment)</span></label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('comment1') }}</div>
                                            </div>
                                            <span> </span>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-theme btn-raised btn-block"
                                                        id="addcomment1">
                                                    Add Comments
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">

                                <table id="table_id" class="details-control">
                                    <thead>
                                    <tr class="bg-info">
                                        <th>#</th>
                                        <th>Comments Group</th>
                                        <th>Action</th>
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                </table>
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
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
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
            $('#table_id').DataTable({

                processing: true,
                serverSide: true,
                ajax: '/user/show-comments-datatables',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'comment_group_name', name: 'comment_group_name'},
//                    {data: 'comments', name: 'comments'},
                    {data: 'edit', name: 'edit'},
                    {data: 'delete', name: 'delete'}
                ]
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $(document.body).on('click', '#del', function () {
                var obj = $(this);
                var cmntGrpId = $(this).attr('data-id');
                console.log(cmntGrpId);
                var x = confirm('Are you sure to delete this comment group. By deleting this group all the comments of this group will be deleted');
                if (x) {
                    $.ajax({
                        url: '/user/delete-commentGroup',
                        type: 'post',
                        datatype: 'json',
                        data: {
                            cmntGrpId: cmntGrpId
                        },
                        success: function (response) {
                            response = $.parseJSON(response);
                            if (response['status'] == '200') {
                                obj.parent().parent().parent().remove();
                            }
                            else {
                                console.log(response.message);
                            }
                        }
                    });
                }
            });
        });
    </script>

@endsection