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
            <div class="page-header_title">
                <h1>Edit Comment Group </h1>
            </div>
        </section>

        <section class="page-content">
            @if(Session::has('msg'))
                @if(session('status')=='Success')
                    <div style="color:green;"><b>{{session('status')}}</b> {{Session::get('msg')}} <a
                                href="/admin/show-comments">Go Back</a></div>
                @elseif(session('status')=='Error')
                    <div style="color:red;"><b>{{session('status')}}</b> {{Session::get('msg')}}</div>
                @endif
            @endif
            <form class="form" role="form" method="post">
                <div class="form-group floating-label">
                    <input class="form-control" id="comment_group_name" type="text" name="comment_group_name"
                           value="{{$cgd->comment_group_name}}">
                    <label for="regular2">Comment Group</label>

                    <div class="error" style="color:red">{{ $errors->first('comment_group_name') }}</div>
                </div>

                <div class="form-group floating-label">
                        <textarea class="form-control" id="comment" name="comment" rows="10"
                                  cols="100">@if($cd!=0)@foreach($cd as $grp){{$grp."\n"}}@endforeach @endif</textarea>
                    <label for="regular2">Comments</label>

                    <div class="error" style="color:red">{{ $errors->first('comment') }}</div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-theme btn-raised btn-block" id="addcomment">Save
                    </button>
                </div>
            </form>
        </section>
    </section>
@endsection


@section('pagescripts')
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