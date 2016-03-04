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
            <li>Add Balance</li>
        </ol>
    </section>

    <section class="page-content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-default panel-divider">
                    <div class="panel-body" style="padding-top: 0;">
                        @if(Session::has('message'))
                                <div style="color:green;">{{Session::get('message')}}</div>
                            @endif

                        <h3> Purchase Coinz with Paypal </h3>

                        <div class="row" style="margin-top:3%;">
                            <div class="col-md-12 text-center">
                                <img max-width="100%" src="http://s24.postimg.org/5gt3rmjd1/paypal.png"/>
                            </div>
                        </div>
                        <div class="row" style="margin-top:3%;">
                            <div class="col-md-12">
                                <div class="alert alert-info"><a class="close" data-dismiss="alert" href="#"
                                                                 aria-hidden="true">×</a> Hi I'm Saurabh (saurabh.kumar@globussoft.com)
                                    We have implemented Paypal Payment method. But need to get the IPN response also from Paypal as Vivek sir told. Working
                                    on that please stay cool and calm. But if u wish you can add balance now also. Its Secure, Dont worry!!!
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <form class="" role="form" method="post">
                                    <h5>Amount</h5>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-money"></span></div>
                                            <input type="text" class="form-control" id="money" name="money"
                                                   placeholder="Amount of Coinz to Purchase"/>
                                            <div class="error" style="color:red">{{ $errors->first('money') }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h5>Purchase Agreement</h5>

                                        <div class="checkbox-list">
															<span>
															<input type="checkbox" required="required"/> <small>By
                                                                    Checking this box i understand that my purchase is
                                                                    irrevocable and ill not ask fraudulent dispute. This
                                                                    purchase is final, we reserve the right to use the
                                                                    confirmation as proof of your agreement.
                                                                </small> </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success btn-raised" type="submit"><i
                                                    class="fa fa-arrow-circle-right"></i> Continue to Paypal
                                        </button>
                                        <button class="btn btn-default btn-raised" style="margin-left:1%;"
                                                type="button">Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <h3> Read before Purchase </h3>

                        <div class="row" style="margin-top:1%;">
                            <div class="col-md-12">
                                <ul class="purchase">
                                    <li><b>Always use EURO Currency, using dollar will need manual approve, taking more
                                            time</b></li>
                                    <li>Input Valid Information About you</li>
                                    <li>Don't use Proxies or VPN</li>
                                    <li>Payments can be refused , please check your payment status before you open a
                                        ticket.
                                    </li>
                                    <li>Minimum Purchase <b>10 Euros</b></li>
                                    <li>Purchases are final and we cannot refund credits back to your paypal or credit
                                        card
                                    </li>
                                    <li>Payments processed by 2Checkout&reg; and Paypal&reg; , we do not store any
                                        credit card or payment information, only your email and IP for security reasons.
                                    </li>
                                    <li><b>1 Coin = 1€</b></li>
                                </ul>
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



