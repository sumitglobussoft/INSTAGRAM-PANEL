@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">

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
                            <h1>  </h1>

                            <form class="form" role="form" method="post" action="/admin/comments-add-ajax-handler">
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
                                <span> </span>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-theme btn-raised btn-block" id="addcomment1">
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
                            <th>#ID</th>
                            <th>Comments Group</th>
                            <th>Comments</th>

                            {{--<th>status</th>--}}
                            <th>Action</th>
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
    <script>
        $(document).ready(function () {
//            var oTable =  $('#table_id').DataTable({
            $('#table_id').DataTable({
//                dom: "<'row'<'col-xs-12'<'col-xs-6'l><'col-xs-6'p>>r>"+
//                "<'row'<'col-xs-12't>>"+
//                "<'row'<'col-xs-12'<'col-xs-6'i><'col-xs-6'p>>>",
                processing: true,
                serverSide: true,
                ajax: '/admin/show-comments-datatables',

//                ajax: {
//                    url: '/admin/show-comments-datatables',
//                    data: function (d) {
//                        d.name = $('input[name=name]').val();
//                        d.email = $('input[name=email]').val();
//                    }

//                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'comment_group_name', name: 'comment_group_name'},
                    {data: 'comments', name: 'comments'},
                    {data: 'edit', name: 'edit'}
//                    {data: 'delete', name: 'delete'}
                ]
            });
//            $('#search-form').on('submit', function(e) {
//                oTable.draw();
//                e.preventDefault();
//            });
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

    {{--<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js">--}}
    {{--</script>--}}
    <script>

        $(document).ready(function () {

            $(document.body).on("change", ".change-status", function () {
                var obj = $(this);
                var status = $(this).val();
//                console.log(status);
                if (status != 0) {
                    $.ajax({
                        url: '/admin/show-selected-comments',
//                            url: '/admin/custom-search',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'showSelectedComments',
                            status: status
                        },
//                            success:function(data) {
//                                if(data) {   // DO SOMETHING
//                                    alert(data);
////                                    console.log(data[0].value);
////                                    $('#test').html(data[0].value);
//                                } else { // DO SOMETHING }
//                                }
//                            success: function (response) {
//                               alert(response);
//                            },
//                            error: function (response) {
//                                response = $.parseJSON(response);
////                                alert(toastr[response['status']](response['message']));
//                                alert(response['status']);
//                                var trHTML = '';
//                                $.each(response, function (i, o) {
//                                    trHTML += '<tr><td>' + o.Name +
//                                            '</td><td>' + o.Group +
//                                            '</td><td>' + o.Work +
//                                            '</td><td>' + o.Grade1 +
//                                            '</td><td>' + o.Grade2 +
//                                            '</td><td>' + o.Grade3 +
//                                            '</td><td>' + o.TeacherName +
//                                            '</td><td>' + o.RollNo +
//                                            '</td></tr>';
//                                });
//                                $('#records_table').append(trHTML);
////                               $("#test").html(response.responseText);
//                            }
////                                response = $.parseJSON(response);
//////                        toastr[response['status']](response['msg']);
////                                if (response['status'] == '200') {
////                                    $('#test').text('response was: ' + response.d)},failure: function (msg) {
////                                alert('an error occured');
////                            }
//                        });
//                                    alert('response');
//                                        $('#test').html(response[0].value);
                        error: function (response) {
//                            alert(response);
                            $("#test").html(response.responseText);
//                            console.log(response)
                            alert('err')
                        },

                        success: function (response) {
                            response = $.parseJSON(response);
                            console.log('test')
                            console.log(response)
//                            toastr.success('this is simply to waste your time, nothing else!!!');
//                            $("#test").html(response.responseText);
                            var trHTML = '';
                            $.each(response.data, function (i, o) {
                                trHTML += '<tr><td>' + o.id +
                                        '</td><td>' + o.comments +
//
                                        '</td></tr>';
                            });
                            $('#records_table').append(trHTML);
//                            response = $.parseJSON(response);
//                            $.each(response.data, function (i, o) {
//                                var oTable = $('#records_table').DataTable({
//
//                                    processing: true,
//                                    serverSide: true,
////                                    ajax: '/admin/show-selected-comments',
//
//                                    columns: [
//
//                                        {data: 'id', name: 'id'},
////                                                {data: 'comment_group_name', name: 'comment_group_name'},
//                                        {data: 'comments', name: 'comments'},
////                                    {data: 'edit', name: 'edit'},
////                    {data: 'delete', name: 'delete'}
//                                    ]
//                                });
//                                    $('#search-form').on('submit', function(e) {
//                            oTable.draw();
//                                        e.preventDefault();
//                                    });

//
//
////                                    obj.parent().parent().remove();
////                            var oTable = $('#optionTable').dataTable();
////                            oTable.fnDeleteRow(document.getElementById('option-' + optionId));
//                                } else {
//
//                                }//TODO SHOW MESSAGE
//                            },
//                        });
//                            });

                        }
                    });
                }
            });
        });

    </script>

@endsection
