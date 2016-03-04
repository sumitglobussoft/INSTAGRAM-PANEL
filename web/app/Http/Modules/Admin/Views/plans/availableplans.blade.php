@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Plans Lists</li>
            </ol>
            <div class="page-header_title">
                <h1>Plans Lists </h1>
            </div>
        </section>

        <section class="page-content">

            <h1> Available Plans lists</h1>
            <hr>
            @if(Session::has('message'))
                <div class="alert alert-info" style="color:red;"> {{Session::get('message')}} </div> @endif
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr class="bg-info">
                    <th>Service</th>
                    <th>Min Quantity</th>
                    <th>Max Quantity</th>
                    <th>Rate Per 1000</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($plans as $plan)
                    <tr>
                        <td>{{ $plan->plan_name }}</td>
                        <td>{{ $plan->min_quantity }}</td>
                        <td>{{ $plan->max_quantity}}</td>
                        <td>$ {{ ($plan->charge_per_unit)*1000 }} </td>
                        <td>
                            <i data="{{$plan->plan_id}}"
                               class="bond @if($plan->status==1) btn label-success fa fa-check-circle-o @else btn label-info fa fa-clock-o @endif ">
                                @if($plan->status==1) Working @else Pending  @endif
                            </i>
                        </td>
                        <td>
                            <a href="{{url('/admin/plans-list-edit',$plan->plan_id)}}" class="btn btn-warning">Edit</a>
                        </td>
                    </tr>

                @endforeach


                </tbody>

            </table>
            <span class="centre">{{$plans->links()}}</span>
        </section>
    </section>
@endsection


@section('pagescripts')
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}
    <script>

        $(document).ready(function () {

            $(document).on('click', '.bond', function () {
                var status = ($(this).hasClass("label label-info fa fa-clock-o")) ? '1' : '0';
                var id = $(this).attr('data');
                console.log(id);
//            document.write(status);die;
                var msg = (status == '1') ? 'Activate' : 'Deactivate';
                if (confirm("Are you sure to " + msg)) {
                    var current_element = $(this);
                    $.ajax({

                        url: '/admin/plans-ajax-handler',
                        type: 'POST',
                        datatype: 'json',
                        data: {
                            method: 'changeStatus',
                            id: $(current_element).attr('data'),
                            status: status
                        },
                        success: function (response) {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection