@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
<!-- BEGIN PAGE LEVEL STYLES -->
<link href="/assets/css/select2.css" rel="stylesheet" />
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/default.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/profile.css" rel="stylesheet" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->

<style>
    form legend::after {
        background: #dfdfdf none repeat scroll 0 0;
        content: "";
        height: 1px;
        margin-left: 0.5rem;
        position: absolute;
        top: 1rem;
        width: 100%;
    }

    form legend {
        display: block;
        margin-bottom: 0.5rem;
        overflow: hidden;
        position: relative;
        width: 100%;
        color: #bbbbbb;
        font-size: 1.5rem;
        border: none;
        font-weight: 600;
        letter-spacing: 0.06rem;
        text-transform: uppercase;
    }
</style>


<link rel="shortcut icon" href="favicon.ico" />

@endsection


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
                <h1>Add Order</h1>
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
            <li>
                <a href="javascript:;">Market</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/user/orderHistory">Add Order</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                {{--<div class="note note-danger note-shadow">--}}
                {{--<p>--}}
                {{--NOTE: The below datatable is not connected to a real database so the filter and sorting is just simulated for demo purposes only.--}}
                {{--</p>--}}
                {{--</div>--}}
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-money font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Payment through 2CO</span>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <div class="tabs-left row">
                            <div class="col-md-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="tabbable-line">
                                            <ul class="nav nav-tabs">
                                                <li style="padding: 1%; padding-bottom:.3%;" class="active text-center">
                                                    <a aria-expanded="true" href="payment.html/#tab_15_1"
                                                       data-toggle="tab">
                                                        <i class="fa fa-credit-card fa-2x"></i>
                                                        <br> Credit Card
                                                    </a>
                                                </li>
                                            </ul>

                                            <h3 class="label-control">Add Credit Card</h3>

                                            <div class="row">
                                                @if(Session::has('message'))
                                                    <div style="color:green;">{{Session::get('message')}}</div>
                                                @endif
                                                <div class="col-md-10 col-md-offset-1">
                                                    <form id="myCCForm" method="post">
                                                        <input id="token" name="token" type="hidden" value="">
                                                        <fieldset style="margin-top: 3%;">
                                                            <legend>Add Amount</legend>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <div class="input-group-addon"><span
                                                                                class="fa fa-money"></span></div>
                                                                    <input type="text" class="form-control" id="money"
                                                                           name="money"
                                                                           placeholder="Amount of Coinz to Purchase"
                                                                           value="{{old('money')}}"/>

                                                                    <div class="error"
                                                                         style="color:red">{{ $errors->first('money') }}</div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <fieldset style="margin-top: 3%;">
                                                            <legend>CARD DETAILS</legend>
                                                            <div>
                                                                <label>
                                                                    <span>Card Number</span>
                                                                </label>
                                                                <input id="ccNo" type="text" size="20" value=""
                                                                       autocomplete="off" required/>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                    <span>Expiration Date (MM/YYYY)</span>
                                                                </label>
                                                                <input type="text" size="2" maxlength="2" id="expMonth" required/>
                                                                <span> / </span>
                                                                <input type="text" size="2" maxlength="4" id="expYear" required/>
                                                            </div>
                                                            <div>
                                                                <label>
                                                                    <span>CVC</span>
                                                                </label>
                                                                <input id="cvv" size="4" maxlength="3" type="text" value=""
                                                                       autocomplete="off" required/>
                                                            </div>

                                                        </fieldset>

                                                        <fieldset style="margin-top: 3%;">
                                                            <legend>YOUR DETAILS</legend>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required=""
                                                                           placeholder="Full Name" name="name"
                                                                           class="form-control" value="{{old('name')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('name') }}</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required=""
                                                                           placeholder="Address Line1" name="addrLine1"
                                                                           class="form-control"
                                                                           value="{{old('addrLine1')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('addrLine1') }}</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required="" placeholder="City"
                                                                           name="city" class="form-control"
                                                                           value="{{old('city')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('city') }}</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required="" placeholder="State"
                                                                           name="state" class="form-control"
                                                                           value="{{old('state')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('state') }}</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required=""
                                                                           placeholder="Postal Code" name="zipCode"
                                                                           class="form-control"
                                                                           value="{{old('zipCode')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('zipCode') }}</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required="" placeholder="Country"
                                                                           name="country" class="form-control"
                                                                           value="{{old('country')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('country') }}</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required="" placeholder="Email"
                                                                           name="email" class="form-control"
                                                                           value="{{old('email')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('email') }}</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <input type="text" required=""
                                                                           placeholder="Phone Number" name="phoneNumber"
                                                                           class="form-control"
                                                                           value="{{old('phoneNumber')}}">
                                                                </div>
                                                                <div class="error"
                                                                     style="color:red">{{ $errors->first('phoneNumber') }}</div>
                                                            </div>


                                                            <div style="margin-top: 2%;" class="row">
                                                                <div class="col-md-12">
                                                                    <input type="submit" class="btn btn-success"
                                                                           value="Save &amp; Pay via 2CO">
                                                                </div>
                                                            </div>
                                                        </fieldset>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<!-- END CONTENT -->

@endsection
@section('pagejavascripts')

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/js/select2.min.js"></script>
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

<!-- BEGIN CUSTOM PAGE LEVEL SCRIPTS -->
<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script>
    // Called when token created successfully.
    var successCallback = function (data) {
        var myForm = document.getElementById('myCCForm');

        // Set the token as the value for the token input
        myForm.token.value = data.response.token.token;

        // IMPORTANT: Here we call `submit()` on the form element directly instead of using jQuery to prevent and infinite token request loop.
        myForm.submit();
    };

    // Called when token creation fails.
    var errorCallback = function (data) {
        if (data.errorCode === 200) {
            tokenRequest();
        } else {
            alert(data.errorMsg);
        }
    };

    var tokenRequest = function () {
        // Setup token request arguments
        var args = {
            sellerId: "901311477",
            publishableKey: "BAE9DA61-C226-4F53-AAEF-678E07B9259B",
            ccNo: $("#ccNo").val(),
            cvv: $("#cvv").val(),
            expMonth: $("#expMonth").val(),
            expYear: $("#expYear").val()
        };

        // Make the token request
        TCO.requestToken(successCallback, errorCallback, args);
    };

    $(function () {
        // Pull in the public encryption key for our environment
        TCO.loadPubKey('sandbox');

        $("#myCCForm").submit(function (e) {
            // Call our token request function
            tokenRequest();

            // Prevent form from submitting
            return false;
        });
    });
</script>
<!-- END CUSTOM PAGE LEVEL SCRIPTS -->

<!-- END PAGE LEVEL SCRIPTS -->
@endsection



