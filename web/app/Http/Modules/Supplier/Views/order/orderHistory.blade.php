@extends('Supplier/Layouts/supplierlayout')

@section('title','Dashboard')


@section('headcontent')
{{--OPTIONAL--}}
{{--PAGE STYLES OR SCRIPTS LINKS--}}

@endsection

@section('content')
{{--PAGE CONTENT GOES HERE--}}

        <!-- Sub Nav End -->
<div class="sub-nav hidden-sm hidden-xs">
    <ul>
        <li><a href="javascript:;" class="heading">Market-->Order History</a></li>
    </ul>
    <div class="custom-search hidden-sm hidden-xs">
        <input type="text" class="search-query" placeholder="Search here ...">
        <i class="fa fa-search"></i>
    </div>
</div>
<!-- Sub Nav End -->

<!-- Dashboard Wrapper Start -->
<div class="dashboard-wrapper-lg">

    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="widget">
            <div class="widget-header">
                <div class="title"> Latest Orders &nbsp;&nbsp;
                    <small>Latest orders placed</small>
                </div>
            </div>

            <div class="widget-body">
                <div class="row">
                    <div class="col-md-12" style="border-width:thick">
                        <table class="table table-hover table-responsive font-segoe" id="datatable">
                            <thead style="background-color: #e5e5e5 ">
                            <tr>
                                <th> Id</th>
                                <th> Service</th>
                                <th> Link</th>
                                <th> Amount</th>
                                <th> Price</th>
                                <th> Added</th>
                                <th> Status</th>
                                <th> Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="text" class="form-control"></td>
                                <td>
                                    <select class="js-example-responsive form-control" name="plan_id" id="plan_id"
                                            style="width:100%;">
                                        <option value="" selected>Please select a Service</option>
                                        @if(isset($data))
                                            <optgroup label='Intagram'></optgroup>
                                            @foreach($data as $plan)
                                                <option value="{{$plan['plan_id']}}"
                                                        data-planType="{{$plan['plan_type']}}"
                                                        data-minQuantity="{{$plan['min_quantity']}}"
                                                        data-maxQuantity="{{$plan['max_quantity']}}"
                                                        data-chargePerUnit="{{$plan['charge_per_unit']}}">{{$plan['plan_name']}}</option>
                                            @endforeach

                                        @endif
                                    </select>
                                    <span id="plan_id_error"></span>
                                </td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control" disabled></td>
                                <td><input type="text" class="form-control" disabled></td>
                                <td>
                                    <select name="" id="" class="form-control">
                                        <option value="">Select...</option>
                                        <option value="">Pending</option>
                                        <option value="">Processing</option>
                                        <option value="">Completed</option>
                                        <option value="">Refunded</option>
                                        <option value="">Partial Refunded</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn popovers btn-default btn-xs " data-placement="left"
                                            data-toggle="tooltip"
                                            title="dummy data" data-trigger="hover" data-container="body"
                                            ><i class="fa fa-search" style="margin-right: 7px"></i>Search
                                    </button>
                                    <button class="btn popovers btn-default btn-xs " data-placement="left"
                                            data-toggle="tooltip"
                                            title="dummy data" data-trigger="hover" data-container="body"
                                            ><i class="fa fa-times" style="margin-right: 7px"></i> Reset
                                    </button>
                                </td>
                            </tr>
                            @if(isset($orders))
                                @foreach($orders as $orderData)
                                    <tr>
                                        <td>{{$orderData['order_id']}}</td>
                                        <td><i style="font-size:10px"
                                               class="fa fa-instagram"> </i> {{$orderData['plan_name']}}</td>
                                        <td><a target="_blank"
                                               href="{{$orderData['ins_url']}}">{{$orderData['ins_url']}}</a></td>
                                        <td>{{$orderData['quantity_total']}}</td>
                                        <td>$ {{$orderData['price']}}</td>
                                        <td><?php
                                            $dateTime = new \DateTime();
                                            $dateTime->setTimestamp(intval($orderData['added_time']));
                                            echo $dateTime->format('Y-m-d H:i:s ');
                                            ?></td>
                                        <td>
                                            @if ($orderData['status']==2)
                                                <span class="label label-info"> <i class="fa fa-clock-o"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn popovers btn-default btn-xs" data-placement="left"
                                                    data-toggle="tooltip"
                                                    title="dummy data" data-trigger="hover" data-container="body"
                                                    ><i class="fa fa-info-circle"></i> Details
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
    </div>

</div>

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
    <script>
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection



