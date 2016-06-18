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
                            <div class="panel-table-inner-offset">
                                <form method="post">

                                    <input name="usergroup_name" value="{{$ugDetails['data']['usergroup_name']}}"/>
                                    <br>
                                    <select id="selectplan">
                                        @foreach($allPlans['data'] as $valP)
                                            <option data-planid="{{$valP['plan_id']}}"
                                                    data-planname="{{$valP['plan_name']}}"
                                                    data-actualrate="{{$valP['charge_per_unit']}}">{{$valP['plan_name']}}</option>
                                        @endforeach
                                    </select>

                                    <button type="button" id="addplan">Add plan to group</button>

                                    <table id="tablePlans" class="table">
                                        <thead>
                                        <tr>
                                            <th class="text-left">No.</th>
                                            <th class="text-left">Plan name</th>
                                            <th class="text-left">Actual rate</th>
                                            <th class="text-left">Rate for usergroup</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody class="test" id="tableBodyPlans">


                                        </tbody>
                                    </table>
                                    <button type="submit">Add usergroup</button>
                                </form>
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

            /*  $(document.body).on("click", "#del", function () {
             var obj = $(this);
             var planId = $(this).attr('data-id');
             console.log(planId);
             var x = confirm("Are you sure, you want to remove this plans from the usergroup");
             if (x) {
             $.ajax({
             url: '/admin/usergroupPlansDelete',
             type: 'post',
             datatype: 'json',
             data: {
             planId: planId
             },
             success: function (response) {
             obj.parent().parent().parent().remove();
             console.log(response);
             }
             });
             }
             console.log('fgdsjkjf');
             }); */

            $(document.body).on("click", "#addplan", function () {
                var selectedplan = $('#selectplan').val();
                var selectedOption = $('#selectplan').find('option:selected');
                var planid = $(selectedOption).data('planid');
                var planname = $(selectedOption).data('planname');
                var actualrate = $(selectedOption).data('actualrate');
                if (false) {
                    //TODO if plan already exists in below table then show error message else add to table
                } else {
                    var toAppend = '<tr class="text-left">';
                    toAppend += '<td></td>';
                    toAppend += '<td>' + planname + '</td>';
                    toAppend += '<td>' + actualrate + '</td>';
                    toAppend += '<td><input name="plans[' + planid + '][charge_per_unit]"/></td>';
                    toAppend += ' <td><button class="removeplan" data-planid="{{$ugPlan['plan_id']}}">Remove from group</button> </td>';
                    toAppend += '</tr>';
                    $('#tableBodyPlans').append(toAppend);
                }
            });

            $(document.body).on("click", ".removeplan", function () {
                //TODO remove the table row here
            });

        });

    </script>

@endsection