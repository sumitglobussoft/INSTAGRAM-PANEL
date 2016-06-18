@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li style="color: white;"><a href="/admin/plans-list">Plans</a></li>
                <li>Add Plans</li>
            </ol>
            <div class="pull-right">
                <a href="/admin/plans-list" class="btn btn-default btn-circle"><i
                            class="fa fa-angle-left"></i> Back To List</a>
            </div>
        </section>

        <section class="page-content">
            @if(Session::has('message'))
                @if(session('status')=='success')
                    <div class="alert alert-info" style="color:green;">
                       {{Session::get('message')}}
                    </div>
                @endif
                @if(session('status')=='error')
                    <div class="alert alert-info" style="color:red;">
                        {{Session::get('message')}})
                    </div>
                @endif
            @endif
            <form class="m-t-md" method="post">

                <div class="form-group ">
                    <label class="control-label">Choose Category</label>
                    <select class="js-example-responsive form-control"
                            name="plan_type" id="plan_type" required>
                        <option value="0" selected="selected"> Instagram likes</option>
                        <option value="1"> Instagram followers</option>
                        <option value="2"> Instagram Random comments</option>
                        <option value="3"> Instagram Custom comments</option>
                        <option value="4"> Instagram views</option>
                    </select>
                </div>
                <div class="form-group ">
                    <label class="control-label">Choose Type </label>
                    <select class="js-example-responsive form-control"
                            name="service_type" id="service_type"
                            required>
                        <option value="R" selected="selected"> Real</option>
                        <option value="F"> Fake</option>
                        <option value="T"> Targetted</option>
                    </select>
                </div>
                <div class="form-group ">
                    <label class="control-label">Choose Server</label>
                    <select class="js-example-responsive form-control"
                            name="supplier_server_id" id="supplier_server_id" required>
                        <option value="1" selected="selected"> igerslike.com</option>
                        <option value="2"> cheapbulksocial.com</option>
                        <option value="3"> socialpanel24.com</option>
                    </select>
                </div>


                <div class="form-group">
                    Service: <input class="form-control" name="plan_name" type="text" size="50"
                                    value="{{old('plan_name')}}">

                    <div class="error" style="color:red">{{ $errors->first('plan_name') }}</div>
                </div>
                <div class="form-group">
                    Plan Name Code(server api type): <input class="form-control" name="plan_name_code" type="text" size="50"
                                    value="{{old('plan_name')}}">

                    <div class="error" style="color:red">{{ $errors->first('plan_name_code') }}</div>
                </div>
                <div class="form-group">
                    Minimum Quantity: <input class="form-control" name="min_quantity" type="text"
                                    value="{{old('min_quantity')}}">

                    <div class="error" style="color:red">{{ $errors->first('min_quantity') }}</div>
                </div>

                <div class="form-group">
                    Maximum Quantity: <input class="form-control" name="max_quantity" type="text"
                                    value="{{old('max_quantity')}}">

                    <div class="error" style="color:red">{{ $errors->first('max_quantity') }}</div>
                </div>

                <div class="form-group">
                    Buying Price/k: <input class="form-control" name="buying_price_per_k" type="text"
                                            value="{{old('buying_price_per_k')}}">

                    <div class="error" style="color:red">{{ $errors->first('buying_price_per_k') }}</div>
                </div>
                <div class="form-group">
                    Selling Price/k: <input class="form-control" name="charge_per_unit" type="text"
                                            value="{{old('charge_per_unit')}}">

                    <div class="error" style="color:red">{{ $errors->first('charge_per_unit') }}</div>
                </div>
                <button type="submit" class="btn btn-success btn-block">Save Changes</button>


            </form>
        </section>
    </section>

@endsection


@section('pagescripts')

@endsection