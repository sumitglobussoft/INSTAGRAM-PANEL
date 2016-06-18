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
                                <div class="col-md-7">

                                    <div class="form-group">
                                        <select id="select1" name="select1" class="form-control">
                                            <option value="">&nbsp;</option>
                                            @foreach($ugDetails as $ug)
                                                <option value="{{$ug->usergroup_id}}">{{$ug->usergroup_name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="select1">Choose the Plan<p></p></label>

                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <button class="btn btn-success">Add Plan</button>
                                    </div>
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
                                        <th class="text-left">Plan</th>
                                        <th class="text-left">Actual Rate</th>
                                        <th class="text-left">Rate for Usergroup</th>
                                        <th class="text-left">Edit</th>
                                        <th class="text-left">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody class="test" id="test">
                                    <tr class="text-left">
                                        <td></td>
                                        <td></td>
                                        <td>
                                            {{--<input type="text" class="form-control" value=" "/>--}}
                                        </td>
                                        <td>
                                            {{--<a href="javascript:;">--}}
                                                {{--<i class="material-icons">phonelink_setup</i>--}}
                                            {{--</a>--}}
                                        </td>
                                        <td>
                                            {{--<a href="{{url('/admin/usergroupPlansDelete')}}">--}}
                                                {{--<i class="material-icons">delete</i>--}}
                                            {{--</a>--}}
                                        </td>
                                    </tr>

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
        $(document).ready(function(){
            $(document.body).on("change",".form-control",function(){

                var obj=$(this);
                var ug_id=$(this).val();
                console.log(ug_id);
                $.ajax({
                   url:'/admin/ugerGroupPlans',
                    type:'post',
                    datatype:'json',
                    data:{
                        ugId:ug_id,
                    },
                    success:function(response){
                      console.log(response);
                        response= $.parseJSON(response);
                        var tableData='';
                        if(response['status']=='200'){

                            $.each(response.data,function(i,o){
                                tableData+='<tr><td>'+o.plan_name+
                                        '</td><td>'+ o.actualRate+
                                                '</td><td><input type="text" class="form-control" value="'+ o.charge_per_unit+'"/>' +
                                        '</td><td><a href="/admin/plans-list-edit/'+ o.plan_id+'"><i class="material-icons">phonelink_setup</i> </a></td><td><a href="javascript:;"><i class="material-icons" id="del" data-id="'+ o.plan_id+'">delete</i></a></td></tr>';
                            });
                            $("#test").html(tableData);
                        }
                        else{
                            tableData+='<tr><td style="text-align: center;">There are no plans in this usergroup.</td></tr>';
                            $("#test").html(tableData);
                        }
                    }
                });

            });

        });

    </script>
    <script>
        $(document.body).on("click","#del",function(){
            var obj=$(this);
            var planId=$(this).attr('data-id');
            console.log(planId);
            var x=confirm("Are you sure, you want to remove this plans from the usergroup");
            if(x){
                $.ajax({
                   url:'/admin/usergroupPlansDelete',
                    type:'post',
                    datatype:'json',
                    data:{
                      planId:planId
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