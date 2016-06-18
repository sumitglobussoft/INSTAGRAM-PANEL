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
                                    <table id="tableDataTables1" class="table">
                                        <thead>
                                        <tr>
                                            <th class="text-left">No.</th>
                                            <th class="text-left">Usergroup name</th>
                                            <th class="text-left">Edit</th>
                                            <th class="text-left">Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody class="test" id="test">
                                        @foreach($ugDetails['data'] as $ug)

                                            <tr class="text-left">
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <a href="/admin/edit-usergroup/{{$ug['usergroup_id']}}">
                                                        <i class="material-icons">phonelink_setup</i>
                                                    </a>
                                                </td>
                                                <td>
                                                    {{--<a href="{{url('/admin/usergroupPlansDelete')}}">--}}
                                                    {{--<i class="material-icons">delete</i>--}}
                                                    {{--</a>--}}
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                @else
                                    No usergroups.
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

    {{--<script>--}}
    {{--$("#tableDataTables1").dataTable();--}}
    {{--</script>--}}
    <script>
        $(document).ready(function () {


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
        });
    </script>

@endsection