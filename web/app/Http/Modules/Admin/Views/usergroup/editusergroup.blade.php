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
                                @if($ugDetails['code'] == 200)
                                    <form method="post" id="addForm">

                                        <div class="form-group floating-label">
                                            <input class="form-control" id="success1" name="usergroup_name"
                                                   value="{{$ugDetails['data']['usergroup_name']}}"/>
                                            <label for="success1"></label>

                                            <div class="error"
                                                 style="color:red">{{ $errors->first('usergroup_name') }}</div>
                                        </div>

                                        <br>

                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label for="selectplan">Choose Plans</label>
                                                <select id="selectplan" class="form-control">
                                                    @foreach($allPlans['data'] as $valP)
                                                        <option data-planid="{{$valP['plan_id']}}"
                                                                data-planname="{{$valP['plan_name']}}"
                                                                data-actualrate="{{$valP['charge_per_unit']}}">{{$valP['plan_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <button class="btn btn-success" type="button" id="addplan">Add plan to
                                                    group
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <div class="panel-table-inner-offset">
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
                                                            @if(isset(old('plans')['data']) || isset(old('plans')['newdata']))
                                                                @if(isset(old('plans')['data']))
                                                                    <?php $i = 0;?>
                                                                    @foreach(old('plans')['data'] as $keyPlan => $ugPlan)
                                                                        <?php ++$i;?>
                                                                        <tr class="text-left"
                                                                            data-id="{{$ugPlan['parent_plan_id']}}">
                                                                            <td>{{$i}}</td>
                                                                            <td>{{$ugPlan['plan_name']}}</td>
                                                                            <td>{{$ugPlan['actual_rate']}}</td>
                                                                            <td>
                                                                                <input name="plans[data][{{$keyPlan}}][charge_per_unit]"
                                                                                       value="{{$ugPlan['charge_per_unit']}}"/>
                                                                                <input name="plans[data][{{$keyPlan}}][parent_plan_id]"
                                                                                       value="{{$ugPlan['parent_plan_id']}}"
                                                                                       type="hidden"/>
                                                                                <input name="plans[data][{{$keyPlan}}][plan_name]"
                                                                                       value="{{$ugPlan['plan_name']}}"
                                                                                       type="hidden"/>
                                                                                <input name="plans[data][{{$keyPlan}}][actual_rate]"
                                                                                       value="{{$ugPlan['actual_rate']}}"
                                                                                       type="hidden"/>

                                                                                <div class="error"
                                                                                     style="color:red">{{ $errors->first("plans.data.$keyPlan.charge_per_unit") }}</div>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="removeplan"
                                                                                        data-planid="{{$keyPlan}}">
                                                                                    {{--<i class="material-icons">delete</i>--}}
                                                                                    Remove from group
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                                @if(isset(old('plans')['newdata']))

                                                                @endif
                                                                @if(isset(old('plans')['newdata']))
                                                                    <?php $i = 0; ?>
                                                                    @foreach(old('plans')['newdata'] as $keyPlan => $ugPlan)
                                                                        <?php ++$i;?>
                                                                        <tr class="text-left"
                                                                            data-id="{{$ugPlan['parent_plan_id']}}">
                                                                            <td>{{$i}}</td>
                                                                            <td>{{$ugPlan['plan_name']}}</td>
                                                                            <td>{{$ugPlan['actual_rate']}}</td>
                                                                            <td>
                                                                                <input name="plans[newdata][{{$keyPlan}}][charge_per_unit]"
                                                                                       value="{{$ugPlan['charge_per_unit']}}"/>
                                                                                <input name="plans[newdata][{{$keyPlan}}][parent_plan_id]"
                                                                                       value="{{$ugPlan['parent_plan_id']}}"
                                                                                       type="hidden"/>
                                                                                <input name="plans[newdata][{{$keyPlan}}][plan_name]"
                                                                                       value="{{$ugPlan['plan_name']}}"
                                                                                       type="hidden"/>
                                                                                <input name="plans[newdata][{{$keyPlan}}][actual_rate]"
                                                                                       value="{{$ugPlan['actual_rate']}}"
                                                                                       type="hidden"/>

                                                                                <div class="error"
                                                                                     style="color:red">{{ $errors->first("plans.newdata.$keyPlan.charge_per_unit") }}</div>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="removeplan"
                                                                                        data-planid="{{$keyPlan}}">
                                                                                    {{--<i class="material-icons">delete</i>--}}
                                                                                    Remove from group
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            @elseif(isset($ugPlans['data']) && !empty($ugPlans['data']))
                                                                <?php $i = 0; ?>
                                                                @foreach($ugPlans['data'] as $ugPlan)
                                                                    <tr class="text-left"
                                                                        data-id="{{$ugPlan['parent_plan_id']}}">
                                                                        <?php ++$i ?>
                                                                        <td>{{$i}}</td>
                                                                        <td>{{$ugPlan['plan_name']}}</td>
                                                                        <td>{{$ugPlan['actual_rate']}}</td>
                                                                        <td>
                                                                            <input name="plans[data][{{$ugPlan['plan_id']}}][charge_per_unit]"
                                                                                   value="{{$ugPlan['charge_per_unit']}}"/>
                                                                            <input name="plans[data][{{$ugPlan['plan_id']}}][parent_plan_id]"
                                                                                   value="{{$ugPlan['parent_plan_id']}}"
                                                                                   type="hidden"/>
                                                                            <input name="plans[data][{{$ugPlan['plan_id']}}][plan_name]"
                                                                                   value="{{$ugPlan['plan_name']}}"
                                                                                   type="hidden"/>
                                                                            <input name="plans[data][{{$ugPlan['plan_id']}}][actual_rate]"
                                                                                   value="{{$ugPlan['actual_rate']}}"
                                                                                   type="hidden"/>

                                                                            <div class="error"
                                                                                 style="color:red">{{ $errors->first('charge_per_unit') }}</div>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="removeplan"
                                                                                    data-planid="{{$ugPlan['plan_id']}}">
                                                                                {{--<i class="material-icons">delete</i>--}}
                                                                                Remove from group
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary materialRipple-light materialRipple-btn pull-right" >Save changes</button>
                                    </form>
                                @else
                                    No such usergroup found.
                                @endif
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
    <script src="/assets/js/validate/jquery.validate.js"></script>

    {{--<script>--}}
    {{--$("#tableDataTables1").dataTable();--}}
    {{--</script>--}}
    <script>
        $(document).ready(function () {

            /*
             $(document.body).on("click", "#del", function () {
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
             });
             */
            var planCount = 1;
            var i = 1;
            $(document.body).on("click", "#addplan", function () {
                var selectedplan = $('#selectplan').val();
                var selectedOption = $('#selectplan').find('option:selected');
                var planid = $(selectedOption).data('planid');
                var planname = $(selectedOption).data('planname');
                var actualrate = $(selectedOption).data('actualrate');

                //TODO if plan already exists in below table then show error message else add to table
                var rowCheck = true;
                if ($('#tableBodyPlans tr').length > 0) {
                    $.each($('#tableBodyPlans tr'), function (i, a) {
                        if (planid == $(a).attr('data-id')) {
                            rowCheck = false;
                        }
                    });
                }
                if (!rowCheck) {
                    alert('plan already added');
                } else {
                    var toAppend = '<tr class="text-left" data-id="' + planid + '">';
                    toAppend += '<td>' + i + '</td>';
                    toAppend += '<td>' + planname + '</td>';
                    toAppend += '<td>' + actualrate + '</td>';
                    toAppend += '<td><input name="plans[newdata][' + planCount + '][charge_per_unit]"/>';
                    toAppend += '<input name="plans[newdata][' + planCount + '][parent_plan_id]" type="hidden" value="' + planid + '"/>';
                    toAppend += '<input name="plans[newdata][' + planCount + '][plan_name]" type="hidden" value="' + planname + '"/>';
                    toAppend += '<input name="plans[newdata][' + planCount + '][actual_rate]" type="hidden" value="' + actualrate + '"/>';
                    toAppend += '</td>';
                    toAppend += ' <td><button type="button" class="removeplan" data-planid="' + planid + '">Remove from group</button> </td>';
                    toAppend += '</tr>';
                    $('#tableBodyPlans').append(toAppend);
                }
                i++;
                planCount++;
            });

            $(document.body).on("click", ".removeplan", function () {
                //TODO remove the tr here
                var obj = $(this);
                var planId = $(this).attr('data-planid');
                console.log(planId);
                var x = confirm("Are you sure, you want to remove this plans from the usergroup");
                if (x) {
                    $.ajax({
                        url: '/admin/usergroupPlansDeleteInEdit',
                        type: 'post',
                        datatype: 'json',
                        data: {
                            planId: planId
                        },
                        success: function (response) {
                            obj.parent().parent().remove();
                            console.log(response);
                        }
                    });
                }
            });

        });
        /* $('#addForm').validate({
         errorElement: 'span',
         rules: {
         usergroup_name: {required: true},
         charge_per_unit: {required: true},
         },
         messages: {
         usergroup_name: {
         required: "Please Provide usergroup name"
         },
         charge_per_unit: {
         required: "Please provide rate for this plan",
         },

         }
         });*/

    </script>

@endsection