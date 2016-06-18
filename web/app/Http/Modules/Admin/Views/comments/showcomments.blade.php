@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
    <link rel="stylesheet" href="/css/toastr.css"/>

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Comments</li>
            </ol>
        </section>

        <section class="page-content">
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
                                class="fa fa-plus-circle"></i>Add Comments</a>
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
                                  action="/admin/comments-add-ajax-handler">
                                <select class="change-status" id="select-comment" name="select-comment">
                                    @foreach($groupname as $grp)
                                        <option value="{{$grp->comment_group_id}}">{{$grp->comment_group_name}}</option>
                                        {{--<option value="{{$grp->comment_group_id}}">{{$grp->comment_group_name}}</option>--}}
                                    @endforeach
                                    <option value="0">Add New Group</option>
                                </select>


                                <div class="form-group floating-label" id='common-group-name'>
                                    <input type="text" class="form-control" id="group-name"
                                           name="comment_group_name">
                                    <label for="group-name1">Group Name</label>

                                    <div class="error"
                                         style="color:red">{{ $errors->first('comment_group_name') }}</div>
                                </div>
                                <div class="form-group floating-label">
                                    <textarea class="form-control" id="comment1" name="comment1" rows="10" cols="100"
                                              value="{{old('comment')}}"></textarea>
                                    <label for="regular2">Comments<span style="font-size: 12px;">&nbsp; (write 1 comment per line)</span></label>

                                    <div class="error" style="color:red">{{ $errors->first('comment1') }}</div>
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
                            <th>Status</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </section>
    </section>


@endsection


@section('pagescripts')
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
    <script src="/js/toastr.js"></script>
    <script>
        $(document).ready(function () {
            $('#table_id').DataTable({

                processing: true,
                serverSide: true,
                ajax: '/admin/show-comments-datatables',

                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'comment_group_name', name: 'comment_group_name'},
//                    {data: 'comments', name: 'comments'},
                    {data: 'edit', name: 'edit'},
                    {data: 'delete', name: 'delete'},
                    {data: 'status', name: 'status'}
                ]
            });
        });
    </script>

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
        });
        $(document).ready(function () {
            toastr.options.positionClass = "toast-top-center";
            toastr.options.preventDuplicates = true;
            toastr.options.closeButton = true;
            $('#group-name').attr('disabled', true);
            $(document.body).on("change", ".change-status", function () {
                var selected_option = $('#select-comment').find("option:selected").attr("value");
                var obj = $(this);
                //                console.log(selected_option);
                if (selected_option == 0) {
                    $('#group-name').attr('disabled', false);
                } else {
                    $('#group-name').attr('disabled', true);
                }
            });
            $(document.body).on('click', '#del', function () {
                var obj = $(this);
                var cmntGrpId = $(this).attr('data-id');
                console.log(cmntGrpId);
                var x = confirm('Are you sure to delete this comment group. By deleting this group all the comments of this grp will be deleted');
                if (x) {
                    $.ajax({
                        url: '/admin/delete-commentGroup',
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
            $(document).on('click', '#status', function () {
                var obj = $(this);
                var cmntGrpId = $(this).attr('data-id');
//                var status = $(this).attr('data-status');
                var status = (obj.hasClass('fa-check-circle')) ? 0 : 1;
                console.log(status);
                console.log(cmntGrpId);
                $.ajax({
                    url: '/admin/changeStatus',
                    type: 'post',
                    datatype: 'json',
                    data: {
                        cmntGrpId: cmntGrpId,
                        status: status
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        if (response['status'] == '200') {
                            toastr.success(response.message);
                            if (obj.hasClass('fa-check-circle')) {
                                obj.removeClass('fa-check-circle');
                                obj.addClass('fa-times-circle');
                                obj.css("color", "red");

                            }
                            else {
                                obj.removeClass('fa-times-circle');
                                obj.addClass('fa-check-circle');
                                obj.css("color", "#00897b");

                            }

                        } else if (response['status'] == '400') {
                            toastr.error(response.message);
                        }
                    }
                });
            })
        });
    </script>

@endsection
