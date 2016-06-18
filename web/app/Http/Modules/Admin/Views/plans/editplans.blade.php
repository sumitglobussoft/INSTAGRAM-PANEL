@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li style="color: white;"><a href="/admin/plans-list">Plans</a></li>
                <li>Edit Plans</li>
            </ol>
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
                        <b>{{session('status')}}</b> {{Session::get('message')}})
                    </div>
                @endif
            @endif
            <form class="m-t-md" method="post">

                <div class="form-group">
                    Service: <input class="form-control" name="plan_name" type="text" size="50"
                                    value="<?php echo $planDetails->plan_name; ?>">

                    <div class="error" style="color:red">{{ $errors->first('plan_name') }}</div>
                </div>

                <div class="form-group ">
                    <label class="control-label">Choose Type </label>
                    <select class="js-example-responsive form-control"
                            name="service_type" id="service_type"
                            required>
                        <option value="R" @if ($planDetails->service_type=="R") selected="selected" @endif> Real</option>
                        <option value="F" @if ($planDetails->service_type=="F") selected="selected" @endif> Fake</option>
                        <option value="T" @if ($planDetails->service_type=="T") selected="selected" @endif> Targetted</option>
                    </select>
                </div>
                <div class="form-group">
                    Plan Name Code(server api type): <input class="form-control" name="plan_name_code" type="text" size="50"
                                                            value="{{$planDetails->plan_name_code}}">

                    <div class="error" style="color:red">{{ $errors->first('plan_name_code') }}</div>
                </div>

                <div class="form-group">
                    Minimum: <input class="form-control" name="min_quantity" type="text"
                                    value="<?php echo $planDetails->min_quantity; ?>">

                    <div class="error" style="color:red">{{ $errors->first('min_quantity') }}</div>
                </div>

                <div class="form-group">
                    Maximum: <input class="form-control" name="max_quantity" type="text"
                                    value="<?php echo $planDetails->max_quantity; ?>">

                    <div class="error" style="color:red">{{ $errors->first('max_quantity') }}</div>
                </div>
                <div class="form-group">
                    Buying Price/k: <input class="form-control" name="buying_price_per_k" type="text"
                                           value="{{$planDetails->buying_price_per_k}}">

                    <div class="error" style="color:red">{{ $errors->first('buying_price_per_k') }}</div>
                </div>

                <div class="form-group">
                    Selling Price/k: <input class="form-control" name="charge_per_unit" type="text"
                                            value="<?php echo($planDetails->charge_per_unit); ?>">

                    <div class="error" style="color:red">{{ $errors->first('charge_per_unit') }}</div>
                </div>
                <button type="submit" class="btn btn-success btn-block">Save Changes</button>


            </form>
        </section>
    </section>

@endsection


@section('pagescripts')

@endsection