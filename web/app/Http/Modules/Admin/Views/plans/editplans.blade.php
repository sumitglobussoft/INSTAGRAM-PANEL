@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')


@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Plans</li>
                <li>Edit Plans</li>
            </ol>
        </section>

        <section class="page-content">
            <form class="m-t-md" method="post">

                <div class="form-group">
                    Service: <input class="form-control" name="plan_name" type="text" size="50"
                                    value="<?php echo $planDetails->plan_name; ?>">

                    <div class="error" style="color:red">{{ $errors->first('plan_name') }}</div>
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
                    Charge Per Unit: <input class="form-control" name="charge_per_unit" type="text"
                                            value="<?php echo($planDetails->charge_per_unit); ?>">

                    <div class="error" style="color:red">{{ $errors->first('charge_per_unit') }}</div>
                </div>
                <button type="submit" class="btn btn-success btn-block">Save Changes</button>


            </form>
            @if(Session::has('message'))
                @if(session('status')=='success')
                    <div class="alert alert-info" style="color:green;">
                        <b>{{session('status')}}</b> {{Session::get('message')}}
                    </div>
                @endif
                @if(session('status')=='error')
                    <div class="alert alert-info" style="color:red;">
                        <b>{{session('status')}}</b> {{Session::get('message')}})
                    </div>
                @endif
            @endif
        </section>
    </section>

@endsection


@section('pagescripts')

@endsection