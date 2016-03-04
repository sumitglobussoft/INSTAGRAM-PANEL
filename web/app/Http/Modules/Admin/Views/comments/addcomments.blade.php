@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


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
            @endif

            <form class="form" role="form" method="post">
                <h2 style="color:lightpink">Add Comments: 1 comment at a time</h2>

                <div class="form-group floating-label">
            <textarea class="form-control" id="comment" name="comment" rows="2" cols="100"
                      value="{{old('comment')}}"></textarea>
                    <label for="regular2">Comments</label>

                    <div class="error" style="color:red">{{ $errors->first('comment') }}</div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-theme btn-raised btn-block" id="addcomment">Add Random
                        Comment
                    </button>
                </div>
            </form>
            <br><br><br><br><br>

            {{--<div class="col-md-12">--}}
            {{--<a class="btn btn-success btn-raised waves-effect waves-light btn modal-trigger" data-toggle="modal"--}}
            {{--data-target="#modal-comments">Add Comments to Group</a>--}}
            {{--</div>--}}

            {{--<div id="modal-comments" class="modal fade">--}}
            {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
            {{--<h4 class="modal-title">Comments</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
            {{--<form class="form" role="form" method="post" action="/admin/comments-add-ajax-handler">--}}
            {{--<h2 style="color:lightpink">Add Comments for group</h2>--}}
            {{--<div class="form-group floating-label">--}}
            {{--<select id="select-comment" name="select-comment" class="form-control">--}}
            {{--<option value="0">&nbsp;</option>--}}
            {{--<option value="1">Comment 1</option>--}}
            {{--<option value="2">Comment 2</option>--}}
            {{--<option value="3">Comment 3</option>--}}
            {{--<option value="4">Comment 4</option>--}}
            {{--<option value="5">Comment 5</option>--}}
            {{--<option value="6">NEW</option>--}}
            {{--</select>--}}
            {{--<select class="change-status" id="select-comment" name="select-comment">--}}
            {{--<option value="1">Generic Comments</option>--}}
            {{--<option value="2">Fitness Comments</option>--}}
            {{--<option value="0">Add New Group</option>--}}
            {{--</select>--}}
            {{--<label for="select-comment">Comment Group</label>--}}
            {{--</div>--}}

            {{--<div class="form-group floating-label" id='common-group-name'>--}}
            {{--<input type="text" class="form-control" id="group-name1">--}}
            {{--<label for="group-name1">Group Name</label>--}}
            {{--</div>--}}
            {{--<div class="form-group floating-label">--}}
            {{--<textarea name="comment1" id="comment1" class="form-control" rows="3"--}}
            {{--placeholder=""></textarea>--}}
            {{--<label for="textarea2">Comment</label>--}}
            {{--</div>--}}

            {{--<style>--}}
            {{--.fa.fa-minus-circle.pull-right {--}}
            {{--cursor: pointer;--}}
            {{--}--}}

            {{--.comment-list-data {--}}
            {{--max-height: 140px;--}}
            {{--}--}}
            {{--</style>--}}
            {{--<div class="form-group floating-label scroll-fancy comment-list-data">--}}
            {{--<ul class='list-group'>--}}
            {{--<li class='list-group-item'>1 <i class="fa fa-minus-circle pull-right"></i></li>--}}
            {{--<li class='list-group-item'>2 <i class="fa fa-minus-circle pull-right"></i></li>--}}
            {{--<li class='list-group-item'>3 <i class="fa fa-minus-circle pull-right"></i></li>--}}
            {{--<li class='list-group-item'>4 <i class="fa fa-minus-circle pull-right"></i></li>--}}
            {{--<li class='list-group-item'>5 <i class="fa fa-minus-circle pull-right"></i></li>--}}
            {{--<li class='list-group-item'>6 <i class="fa fa-minus-circle pull-right"></i></li>--}}
            {{--</ul>--}}
            {{--</div>--}}

            {{--<div class="modal-footer">--}}
            {{--<button type="button" class="btn btn-theme btn-raised" id="addcomment1">Add Comment</button>--}}
            {{--<button type="button" class="btn btn-default btn-raised">Reset</button>--}}
            {{--</div>--}}
            {{--</form>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--</div>--}}

            <div class="col-md-12">
                <a class="btn btn-success btn-raised waves-effect waves-light btn modal-trigger" data-toggle="modal"
                   data-target="#modal-comments">Add Comments to Group</a>
            </div>

            <div id="modal-comments" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Comments</h4>
                        </div>
                        <div class="modal-body">

                            <form class="form" role="form" method="post" action="/admin/comments-add-ajax-handler">
                                <h2 style="color:lightpink">Add Comments for group</h2>
                                <select class="change-status" id="select-comment" name="select-comment">
                                    @foreach($groupname as $grp)
                                        <option value="{{$grp->comment_group_id}}">{{$grp->comment_group_name}}</option>
                                        {{--<option value="{{$grp->comment_group_id}}">{{$grp->comment_group_name}}</option>--}}
                                    @endforeach
                                    <option value="0">Add New Group</option>
                                </select>

                                <div class="form-group floating-label" id='common-group-name'>
                                    <input type="text" class="form-control" id="group-name" name="group-name">
                                    <label for="group-name1">Group Name</label>

                                    <div class="error" style="color:red">{{ $errors->first('group-name') }}</div>
                                </div>
                                <div class="form-group floating-label">
    <textarea class="form-control" id="comment1" name="comment1" rows="2" cols="100"
              value="{{old('comment')}}"></textarea>
                                    <label for="regular2">Comments</label>

                                    <div class="error" style="color:red">{{ $errors->first('comment1') }}</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-theme btn-raised btn-block" id="addcomment1">
                                        Add
                                        Comments for Group
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

@endsection


@section('pagescripts')
    {{--<script>--}}
    {{--$(document).ready(function () {--}}

    {{--$(document.body).on("click", "#addcomment1", function () {--}}
    {{--var selected_option = $('#select-comment').find("option:selected").attr("value");--}}
    {{--var comments = document.getElementById('comment1');--}}

    {{--var obj = $(this);--}}
    {{--//                console.log(selected_option);--}}
    {{--if (selected_option != 0) {--}}
    {{--$.ajax({--}}
    {{--url: '/admin/comments-add-ajax-handler',--}}
    {{--type: 'POST',--}}
    {{--datatype: 'json',--}}
    {{--data: {--}}
    {{--method: 'addComments',--}}
    {{--id: selected_option,--}}
    {{--comments:comments,--}}
    {{--},--}}
    {{--success: function (response) {--}}
    {{--response = $.parseJSON(response);--}}
    {{--//                        toastr[response['status']](response['msg']);--}}
    {{--if (response['status'] == '200') {--}}
    {{--console.log('success');--}}
    {{--//                              obj.parent().parent().remove();--}}
    {{--//                                var oTable = $('#optionTable').dataTable();--}}
    {{--//                                oTable.fnDeleteRow(document.getElementById('option-' + optionId));--}}
    {{--} else {--}}
    {{--console.log('failure');--}}
    {{--}//TODO SHOW MESSAGE--}}
    {{--},--}}
    {{--});--}}
    {{--}--}}

    {{--});--}}
    {{--});--}}

    {{--</script>--}}

    {{--<script>--}}
    {{--$(document).ready(function () {--}}

    {{--$(document.body).on("change", ".change-status", function () {--}}
    {{--//            $(document.body).on("click", "#addcomment1", function () {--}}
    {{--var obj = $(this);--}}
    {{--//                var userId = $(this).attr('data-id');--}}
    {{--var status = $(this).val();--}}
    {{--var textarea =  jQuery("#comment").val();--}}

    {{--var lines = $("#comment").val().split("\n");--}}
    {{--console.log(lines);--}}
    {{--console.log(status);--}}
    {{--//                var msg = (status == '1') ? ' Approve ' : ' Reject ';--}}
    {{--//                var x = confirm("Are you sure, you want to" + msg + "this ID ?");--}}
    {{--if (status != 0) {--}}
    {{--//                    if (x) {--}}
    {{--$.ajax({--}}
    {{--url: '/admin/comments-add-ajax-handler',--}}
    {{--type: 'POST',--}}
    {{--datatype: 'json',--}}
    {{--data: {--}}
    {{--method: 'addComments',--}}
    {{--//                            id: userId,--}}
    {{--status: status--}}
    {{--},--}}
    {{--success: function (response) {--}}
    {{--response = $.parseJSON(response);--}}
    {{--//                        toastr[response['status']](response['msg']);--}}
    {{--if (response['status'] == '200') {--}}
    {{--obj.parent().parent().remove();--}}
    {{--//                            var oTable = $('#optionTable').dataTable();--}}
    {{--//                            oTable.fnDeleteRow(document.getElementById('option-' + optionId));--}}
    {{--} else {--}}

    {{--}//TODO SHOW MESSAGE--}}
    {{--},--}}
    {{--});--}}
    {{--}--}}

    {{--});--}}
    {{--});--}}

    {{--</script>--}}




    {{--<script >--}}
    {{--DataTable--}}

    {{--$('.fa.fa-minus-circle.pull-right').click(function () {--}}
    {{--$(this).parent().slideUp();--}}
    {{--});--}}
    {{--$('#datatable').dataTable();--}}
    {{--$(window).load(function () {--}}
    {{--$('#datatable_filter input, #datatable_length select').addClass('form-control');--}}
    {{--$('#datatable_length').addClass('form-group');--}}
    {{--$('#common-group-name').slideUp();--}}
    {{--});--}}
    {{----}}
    {{--$('#select-comment').on('change', function () {--}}
    {{----}}
    {{--var selected_option = $('#select-comment').find("option:selected").attr("value");--}}
    {{--console.log(selected_option);--}}
    {{--if (selected_option === 'NEW') {--}}
    {{--if ($('#group-name').attr('disabled')) $('#group-name').attr('disabled', true); //$('#group-name').removeAttr('disabled');--}}
    {{--$('#common-group-name').slideDown();--}}
    {{----}}
    {{--} else {--}}
    {{--$('#group-name').attr('disabled', 'disabled');--}}
    {{--$('#common-group-name').slideUp();--}}
    {{----}}
    {{--}--}}
    {{--});--}}

    {{--</script>--}}
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
            })
        });
        //        $(document).ready(function () {
        //            $('#group-name').attr('disabled', true);
        //            var obj=$(this);
        //            var value=$(this).val();
        //            console.log(5 + 6);
        //
        ////            $('#comment').keyup(function () {
        ////                var comment = $.trim($('#comment').val());
        //////        if($.trim($(this).val().length) !=0)
        //                if (value == 6)
        //                    $('#group-name').attr('disabled', false);
        //                else
        //                    $('#group-name').attr('disabled',true );
        //        });

    </script>
@endsection