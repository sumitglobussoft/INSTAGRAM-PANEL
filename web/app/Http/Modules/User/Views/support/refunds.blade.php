@extends('User/Layouts/userlayout')

@section('title','Payment Policy')


@section('headcontent')
        <!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/default.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/profile.css" rel="stylesheet" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico" />
@endsection
@section('classSupport','active')
@section('classSupport5','active')
@section('content')
{{--PAGE CONTENT GOES HERE--}}
        <!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE HEAD -->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Refund Policy</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD -->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="/user/dashboard">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            {{--<li>--}}
                {{--<a href="javascript:;">Market</a>--}}
                {{--<i class="fa fa-circle"></i>--}}
            {{--</li>--}}
            <li>
                <a href="javascript:;">Support</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-eye-slash font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Refund Policy</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row" style="margin-top:1%;">
                            <div class="col-md-12">
                                <ul class="purchase">
                                    <div>1. You fully understand and agree that no refunds to PayPal will
                                        ever be made at any circumstances once you made your payment(s) at
                                        PayPal.</div>
                                    <br>

                                    <div> 2. You understand and agree that the amount of your order may be
                                        dropped at any time by the social media site without warning and
                                        you'll not
                                        be refunded.</div>
                                    <br>
                                    <div>3. Since Messazon offers non-tangible irrevocable goods,
                                        you agree that after the purchase is made you cannot cancel/stop or
                                        remove any actions that
                                        this initiated. You understand that by Purchasing any goods on
                                        Messazon.com this decision is final and you won't be able to reserve
                                        it.</div>
                                    <br>

                                    <p>4. We will not refund to your PayPal account when the payment
                                        at PayPal has been completed. We can only refund to your Messazon a
                                        ccount.Any PayPal refund request will be denied.</p>
                                    <br>
                                    <p>5. You agree that once the payment is made to PayPal and your account at
                                        Messazon is charged, you will not lodge a complaint or a dispute
                                        against
                                        us.If the dispute was filed against us or conflict for any reason
                                        without
                                        contacting us, we reserve the right to stop all previous orders and
                                        the
                                        current, and prohibit your account from the site entirely, and we
                                        reserve
                                        the right to take back all followers of your account or the accounts
                                        of your
                                        clients you order for them previously.</p><br>

                                    <p> 6. If for any reason,your credit card or your Paypal account is
                                        stolen and the
                                        payment is declared as un-authorized,we will not give a refund.It's
                                        your
                                        responsibility to keep safe your money/credit cards/Paypal account
                                        and not ours.</p><br>

                                    <p> 7. We do not cancel and return the amount of any request ( upon a
                                        request
                                        from you ) for any reason, except in case of system failure to
                                        complete thework.</p><br>

                                    <p>8. Messazon has the right to cancel any order without notice and
                                        without
                                        explaining the reason for the cancellation.</p><br>

                                    <p> 9. Messazon services prices are subjected to change at any time
                                        without any prior notice.</p><br>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>
        <!-- END PAGE CONTENT-->
    </div>
</div>
<!-- END CONTENT -->

@endsection

@section('pagejavascripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/assets/js/instapanel.js"></script>
<script src="/assets/js/layout.js"></script>
<script src="/assets/js/demo.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    jQuery(document).ready(function () {
        InstaPanel.init(); // init InstaPanel core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>
<!-- END JAVASCRIPTS -->
@endsection



