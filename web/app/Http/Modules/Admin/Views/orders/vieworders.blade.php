@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


@endsection


@section('pagecontent')


    <h1>viewOrders</h1>
@foreach($order_details as $order)
    <p>Order ID: {{$order->order_id}}</p>
    <p>Name: {{$order->name}} {{$order->lastname}}</p>
    <p>User Email: {{$order->email}}</p>
    <p>Plan Name: {{$order->plan_name}}</p>
    {{--<p>User ID: {{$order->for_user_id}}</p>--}}
    <p>Instagram Link: {{$order->ins_url}}</p>
    <p>Total Quantity: {{$order->quantity_total}}</p>
    <p>Quantity done: {{$order->quantity_done}}</p>
    <p>Start time: {{$order->start_time}}</p>
    <p>End Time: {{$order->end_time}}</p>

    @if(($order->status)==0)
        <p>Status: payment pending </p>
    @elseif(($order->status)==1)
        <p>Status: completed</p>

    @elseif(($order->status)==2)
        <p>Status: payment done, order not yet started</p>

    @elseif(($order->status)==3)
        <p>Status: running</p>

    @elseif(($order->status)==4)
        <p>Status: failed</p>

    @else
        <p>Status: started</p>
    @endif

    <p>Added Time: {{$order->added_time}}</p>
    <p>Started Time: {{$order->updated_time}}</p>

@endforeach
@endsection


@section('pagescripts')

@endsection