@extends('User/Layouts/userlayout')

@section('title','Dashboard')


@section('headcontent')
{{--OPTIONAL--}}
{{--PAGE STYLES OR SCRIPTS LINKS--}}

@endsection

@section('content')
{{--PAGE CONTENT GOES HERE--}}

        <!-- Right-Page-content Start-->
<section id="right-content-wrapper">
    <section class="page-header alternative-header">
        <ol class="breadcrumb">
            <li>IP User</li>
            <li>My Account</li>
            <li>Over view</li>
        </ol>
    </section>

    <section class="page-content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-default panel-divider">
                    <div class="panel-body" style="padding-top: 0;">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <h3> Account Overview </h3>
                                <div class="row" style="margin-top:3%;">
                                    <div class="col-md-12">
                                        <table class="table table-hover table-condensed">
                                            <tbody>
                                            <tr>
                                                <td><i class="fa fa-user"></i></td>
                                                <td> Username</td>
                                                <td> {{Session::get('ig_user')['username']}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa fa-euro"></i></td>
                                                <td> Account Balance</td>
                                                <td>
                                                    $ @if(isset(Session::get('ig_user')['account_bal'])) {{Session::get('ig_user')['account_bal']}} @else
                                                        0.0000 @endif
                                                    {{--<a href="javascript:;">( Add More )</a>--}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa fa-at"></i></td>
                                                <td> E-Mail</td>
                                                <td> {{Session::get('ig_user')['email']}}</td>
                                            </tr>
                                            {{--<tr>--}}
                                            {{--<td><i class="fa fa fa-credit-card"></i></td>--}}
                                            {{--<td> Paypal / 2Checkout Payments</td>--}}
                                            {{--<td>--}}
                                            {{--<span class="label label-success">Enable</span> /--}}
                                            {{--<span class="label label-danger">Disable</span>--}}
                                            {{--</td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                            {{--<td><i class="fa fa fa-server"></i></td>--}}
                                            {{--<td> Shop API Key</td>--}}
                                            {{--<td> 0</td>--}}
                                            {{--</tr>--}}
                                            {{--<tr>--}}
                                            {{--<td><i class="fa fa fa-external-link"></i></td>--}}
                                            {{--<td> Affiliated Link</td>--}}
                                            {{--<td><a target="_blank" href="javascript:;">https://www.instapanel.com/?a=1181</a>--}}
                                            {{--</td>--}}
                                            {{--</tr>--}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}

@endsection



