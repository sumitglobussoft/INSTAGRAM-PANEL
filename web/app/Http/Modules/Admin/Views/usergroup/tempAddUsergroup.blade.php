@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>User Groups</li>
            </ol>
            <div class="page-header_title">
                <h1>UserGroup Lists </h1>
            </div>
        </section>

        <section class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <button class="btn btn-success" id="addGrp">Add Group</button>
                                    </div>
                                </div>
                                {{--<input type="submit" value="Add Second Driver" id="driver" />--}}
                                <div id="text">
                                    <form method="post" action="/admin/add-userGroup">
                                        Group Name:<input type="text" name="groupName" id="groupName"/>
                                        <div class="error" style="color:red">{{ $errors->first('groupName') }}</div>
                                        <button class="btn btn-success" id="save">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="panel-table-inner-offset">
                                <table id="tableDataTables1" class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-left">No.</th>
                                        <th class="text-left">UserGroup</th>
                                        <th class="text-left">Edit</th>
                                        <th class="text-left">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody class="test" id="test">
                                    <?php $i=0; ?>
                                    @foreach($ugDetails as $ug)
                                        <?php ++$i; ?>
                                        <tr class="text-left">
                                            <td>{{$i}}</td>
                                            <td>{{$ug->usergroup_name}}</td>
                                            <td>
                                                <a href="{{url('/admin/edit-usergroup',$ug->usergroup_id)}}">
                                                    <i class="material-icons">phonelink_setup</i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:;">
                                                    <i class="material-icons" id="del" data-id="{{$ug->usergroup_id}}">delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
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
    <script src="/js/jquery.dataTables.min.js"></script>
    <script src="/js/dataTables.bootstrap.min.js"></script>

    {{--<script>--}}
    {{--$("#tableDataTables1").dataTable();--}}
    {{--</script>--}}
    <script>
        $(document).ready(function () {
            $("#text").hide();
            $("#addGrp").click(function () {
                $("#text").show();
            });
        });
    </script>
    <script>
        $(document.body).on("click", "#save", function () {
            var obj = $(this);
            var groupName = $("#groupName").val();
            console.log(groupName);
            $.ajax({
                url: '/admin/addUserGroupAjaxHandler',
                type: 'post',
                datatype: 'json',
                data: {
                    groupName: groupName
                },
                success: function (response) {
                    obj.parent().parent().parent().remove();
                    console.log(response);
                }
            });
            console.log('fgdsjkjf');
        });
    </script>
    <script>
        $(document.body).on("click","#del",function(){
            var obj=$(this);
            var ugId=$(this).attr('data-id');
            console.log(ugId);
            var x=confirm("Are you sure, you want to Delete this Group. By deleting this group all the plans of this group will be deleted");
            if(x){
                $.ajax({
                    url:'/admin/usergroupPlansDelete',
                    type:'post',
                    datatype:'json',
                    data:{
                        ugId:ugId
                    },
                    success: function (response) {
                        obj.parent().parent().parent().remove();
                        console.log(response);
                    }
                });
            }
            console.log('fgdsjkjf');
        });
    </script>

@endsection