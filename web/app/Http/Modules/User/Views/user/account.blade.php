@extends('User/Layouts/userlayout')

@section('title','MyAccount')


@section('headcontent')
    {{--OPTIONAL--}}
    {{--PAGE STYLES OR SCRIPTS LINKS--}}

    <style>
        .badge {
            background-color: #777;
        }

        .tabs-left {
            margin-top: 3rem;
        }

        .nav-tabs {
            float: left;
            border-bottom: 0;
            border-bottom: 1px solid #ddd !important;
        }

        .nav-tabs li {
            float: none;
            margin: 0 0 8%;
        }

        .nav-tabs li a {
            margin-right: 0;
            border: 0;
            background-image: linear-gradient(#2d85be, #3693cf 85%, #1f5a80);
            background-color: #3187bf;
            border-radius: 0;
        }

        .nav-tabs li a:hover {
            background-color: #444;
        }

        .nav-tabs .glyphicon {
            color: #fff;
        }

        .nav-tabs .active .glyphicon {
            color: #FFF;
        }

        .nav-tabs > li.active > a,
        .nav-tabs > li.active > a:hover,
        .nav-tabs > li.active > a:focus {
            border: 0;
            background-color: #1A1A1A;
            color: #FFF;
            background-image: none;
            border-left: 5px solid #3187bf;
        }

        .nav-tabs > li > a:hover,
        .nav-tabs > li > a:focus {
            border: 0;
            background-color: #1A1A1A;
            color: #FFF;
            background-image: none;
            border-left: 5px solid #3187bf;
        }

        .tab-content {
            /*			margin-left: 190px;*/
        }

        .tab-content .tab-pane {
            display: none;
            background-color: #fff;
            padding: 1.6rem;
            /*			overflow-y: auto;*/
        }

        .tab-content .active {
            display: block;
        }

        .list-group {
            width: 100%;
        }

        .list-group .list-group-item {
            height: 50px;
        }

        .list-group .list-group-item h4,
        .list-group .list-group-item span {
            line-height: 11px;
        }

        td {
            font-family: segoe UI;
            font-size: 14px;
            padding: 1% !important;
        }

        .checkbox-list span {
            font-family: segoe UI;
            font-size: 14px;
        }

        .purchase {
            font-family: segoe UI;
            font-size: 14px;
            list-style: circle;
            margin-left: 3%;
        }
    </style>

    @endsection

    @section('content')
    {{--PAGE CONTENT GOES HERE--}}

            <!-- Sub Nav End -->
    <div class="sub-nav hidden-sm hidden-xs">
        <ul>
            <li><a href="javascript:;" class="heading">Account Settings</a></li>
        </ul>
        <div class="custom-search hidden-sm hidden-xs">
            <input type="text" class="search-query" placeholder="Search here ...">
            <i class="fa fa-search"></i>
        </div>
    </div>
    <!-- Sub Nav End -->

    <!-- Dashboard Wrapper Start -->
    <div class="dashboard-wrapper-lg">

        <!-- Row starts -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="tabs-left row">
                    <div class="col-md-2 col-xs-12">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#a" data-toggle="tab">
                                    <span class="fa fa-home"></span> &nbsp; OVERVIEW
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a href="#b" data-toggle="tab">--}}
                            {{--<span class="fa fa-money"></span> &nbsp; ADD BALANCE--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="#c" data-toggle="tab">--}}
                            {{--<span class="fa fa-files-o"></span> &nbsp; DEPOSIT HISTORY--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a href="#d" data-toggle="tab">
                                    <span class="fa fa-cog"></span> &nbsp; ACCOUNT SETTINGS
                                </a>
                            </li>
                            <li>
                                <a href="#e" data-toggle="tab">
                                    <span class="fa fa-unlock"></span> &nbsp; CHANGE PASSWORD
                                </a>
                            </li>
                            <li>
                                <a href="#f" data-toggle="tab">
                                    <span class="fa fa-unlock"></span> &nbsp; CHANGE AVATAR
                                </a>
                            </li>
                            {{--<li>--}}
                            {{--<a href="#g" data-toggle="tab">--}}
                            {{--<span class="fa fa-instagram"></span> &nbsp; INSTAGRAM SETTINGS--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="#h" data-toggle="tab">--}}
                            {{--<span class="fa fa-bell"></span> &nbsp; NOTIFICATION--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<a href="#i" data-toggle="tab">--}}
                            {{--<span class="fa fa-archive"></span> &nbsp; FAQ--}}
                            {{--</a>--}}
                            {{--</li>--}}
                            {{--<li style="margin-bottom:5px;">--}}
                            {{--<a href="#j" data-toggle="tab">--}}
                            {{--<span class="fa fa-dot-circle-o"></span> &nbsp; SUPPORT--}}
                            {{--</a>--}}
                            {{--</li>--}}
                        </ul>
                    </div>
                    <div class="tab-content col-md-10 col-xs-12">
                        <div class="tab-pane active" id="a">
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
                        <div class="tab-pane" id="b">
                            <h3> Purchase Coinz with Paypal </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12 text-center">
                                    <img max-width="100%" src="http://s24.postimg.org/5gt3rmjd1/paypal.png"/>
                                </div>
                            </div>
                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <div class="alert alert-info"><a class="close" data-dismiss="alert" href="#"
                                                                     aria-hidden="true">×</a>We are having issues with
                                        Payments, if you wish to deposit and Paypal is not enable for your account
                                        please talk with Live chat, or send directly to zeusgram@gmail.com as Friends
                                        and Family and let us know the Transaction ID
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="" role="form">
                                        <h5>Amount</h5>

                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-money"></span></div>
                                                <input type="text" class="form-control" id=""
                                                       placeholder="Amount of Coinz to Purchase"/>
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
                                            <button class="btn btn-success" type="submit"><i
                                                        class="fa fa-arrow-circle-right"></i> Continue to Paypal
                                            </button>
                                            <button class="btn default" style="margin-left:1%;" type="button">Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <h3> Read before Purchase </h3>

                            <div class="row" style="margin-top:1%;">
                                <div class="col-md-12">
                                    <ul class="purchase">
                                        <li><b>Always use EURO Currency, using dollar will need manual approve, taking
                                                more time</b></li>
                                        <li>Input Valid Information About you</li>
                                        <li>Don't use Proxies or VPN</li>
                                        <li>Payments can be refused , please check your payment status before you open a
                                            ticket.
                                        </li>
                                        <li>Minimum Purchase <b>10 Euros</b></li>
                                        <li>Purchases are final and we cannot refund credits back to your paypal or
                                            credit card
                                        </li>
                                        <li>Payments processed by 2Checkout&reg; and Paypal&reg; , we do not store any
                                            credit card or payment information, only your email and IP for security
                                            reasons.
                                        </li>
                                        <li><b>1 Coin = 1€</b></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="c">
                            <h3> Deposit Invoices </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <table class="table table-hover table-condensed" id="datatable">
                                        <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox"/></th>
                                            <th> #</th>
                                            <th> Date</th>
                                            <th> Amount</th>
                                            <th> Email</th>
                                            <th> Transcation ID</th>
                                            <th> Status</th>
                                            <th> Information</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="checkbox"/></td>
                                            <td> 5385</td>
                                            <td> 5 days</td>
                                            <td> 200.00 €</td>
                                            <td> test@globussoft.com</td>
                                            <td> 6L744875VU744833N</td>
                                            <td> Completed</td>
                                            <td>
                                                <button data-original-title="Information" data-html="true"
                                                        data-content="10% Bonus Deposit Promotion" data-placement="top"
                                                        data-trigger="hover" data-container="body"
                                                        class="btn popovers btn-circle btn-default btn-xs">
                                                    <i class="fa fa-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="d">
                            <h3> Account Settings </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <form class="" role="form" id="accountSetting">
                                        <h4> General Informatiom </h4>

                                        <div class="form-group">
                                            <label class="">First name</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="firstname" name="firstname"
                                                       placeholder=""
                                                       value="{{$userData['name']}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Last name</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="lastname" name="lastname"
                                                       placeholder=""
                                                       value="{{$userData['lastname']}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Username</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="username" name="username"
                                                       placeholder=""
                                                       value="{{$userData['username']}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Email Address</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-envelope"></span>
                                                </div>
                                                <input type="text" class="form-control" id="emai" name="email"
                                                       placeholder=""
                                                       value="{{$userData['email']}}"/>
                                            </div>
                                        </div>

                                        <br>
                                        <h4> Address Information </h4>

                                        <div class="form-group">
                                            <label class="">Address Line 1</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="addressline1"
                                                       name="addressline1" placeholder="" maxlength="60"
                                                       value="{{$userData['addressline1']}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Address Line 2</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="addressline2"
                                                       name="addressline2" placeholder="" maxlength="60"
                                                       value="{{$userData['addressline2']}}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="">City</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="city"
                                                       name="city" placeholder=""
                                                       value="{{$userData['city']}}"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="">State</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="state"
                                                       name="state" placeholder=""
                                                       value="{{$userData['state']}}"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="">Country</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-user"></span>
                                                </div>
                                                <input type="text" class="form-control" id="country_id"
                                                       name="country_id" placeholder=""
                                                       value="{{$userData['country_id']}}"/>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="">Contact No.</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-phone"></span>
                                                </div>
                                                <input type="text" class="form-control" id="contact_no"
                                                       name="contact_no" placeholder=""
                                                       value="{{$userData['contact_no']}}"/>
                                            </div>
                                        </div>
                                        {{--<div class="form-group">--}}
                                        {{--<label class="">Skype</label>--}}

                                        {{--<div class="input-group">--}}
                                        {{--<div class="input-group-addon"><span class="fa fa-skype"></span></div>--}}
                                        {{--<input type="text" class="form-control" id="" placeholder="Your Skype"--}}
                                        {{--value=""/>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label class="">Web-Site</label>--}}

                                        {{--<div class="input-group">--}}
                                        {{--<div class="input-group-addon"><span class="fa fa-globe"></span></div>--}}
                                        {{--<input type="text" class="form-control" id=""--}}
                                        {{--placeholder="Your Web-Site if any"/>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label class="">Managers API KEY</label>--}}

                                        {{--<div class="input-group">--}}
                                        {{--<div class="input-group-addon"><span class="fa fa-cubes"></span></div>--}}
                                        {{--<input type="text" class="form-control" id="" placeholder=""--}}
                                        {{--value="da37d1edd2a775b75b715c041711c269333ac3a46934cbc1d620bca1c2ac2bde"--}}
                                        {{--readonly/>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label class="">Market API KEY</label>--}}

                                        {{--<div class="input-group">--}}
                                        {{--<div class="input-group-addon"><span class="fa fa-cubes"></span></div>--}}
                                        {{--<input type="text" class="form-control" id="" placeholder=""--}}
                                        {{--value="9d6f65be87883e23b078532d3ada6a91fc3ba3bcf93fedd552f5a3c1f04707f4"--}}
                                        {{--readonly/>--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label class="">Managers API Status</label>--}}
                                        {{--<select class="form-control">--}}
                                        {{--<option>Enable</option>--}}
                                        {{--<option selected>Disable</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="form-group">--}}
                                        {{--<label class="">Market API Status</label>--}}
                                        {{--<select class="form-control">--}}
                                        {{--<option>Enable</option>--}}
                                        {{--<option selected>Disable</option>--}}
                                        {{--</select>--}}
                                        {{--</div>--}}
                                        <div class="form-group">
                                            <button class="btn btn-success" type="submit" id="#save-info-changes"><i
                                                        class="fa fa-arrow-circle-right"></i> Save Settings
                                            </button>
                                            <button class="btn default" style="margin-left:1%;" type="button">Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="e">
                            <h3> Password Update </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <form class="" role="form" id="changePassword">
                                        <div class="form-group">
                                            <label class="">Old Password</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-unlock"></span></div>
                                                <input type="password" class="form-control" id="oldPassword"
                                                       name="oldPassword"
                                                       placeholder="Your Current Password" value=""/>
                                            </div>
                                            <span style="color: red" id="oldPasswordError"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="">New Password</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-lock"></span></div>
                                                <input type="password" class="form-control" id="newPassword"
                                                       name="newPassword" placeholder="New Password"
                                                       value=""/>
                                            </div>
                                            <span style="color: red" id="newPasswordError"></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Repeat New Password</label>

                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="fa fa-unlock-alt"></span>
                                                </div>
                                                <input type="password" class="form-control" id="conformNewPassword"
                                                       name="conformNewPassword"
                                                       placeholder="Confirm New Password" value=""/>
                                            </div>
                                            <span style="color: red" id="conformNewPasswordError"></span>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-success" type="submit" id="submitUpdatePassword"><i
                                                        class="fa fa-arrow-circle-right"></i> Save Settings
                                            </button>
                                            <button class="btn default" style="margin-left:1%;" type="button">Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="f">
                            <h3> Change Avatar </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <form class="" role="form" id="changeAvatar">

                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-6 ">
                                            <?php if (isset($_COOKIE['profile_pic_url'])) {
                                               Session::put('ig_user.profile_pic' , $_COOKIE['profile_pic_url']);
                                            }?>
                                            <img class="img-thumbnail col-md-6 " alt="user Avatar" width="304" height="236"
                                                    src="@if(isset(Session::get('ig_user')['profile_pic'])) {{Session::get('ig_user')['profile_pic']}} @else /assets/uploads/useravatar/default-profile-pic.png @endif " >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="file" name="profilepic" accept="image/*"><br>

                                            <div><input type="button" class="col-md-6" id="avatar-submit"
                                                        value="Submit"></div>
                                        </div>

                                        {{--<div class="form-group">--}}
                                        {{--<img class="img-thumbnail form-group col-md-6"--}}
                                        {{--src="@if(isset(Session::get('ig_user')['profile_pic'])) {{Session::get('ig_user')['profile_pic']}} @else /assets/uploads/useravatar/default-profile-pic.png @endif "--}}
                                        {{--alt="user Avatar" width="304" height="236">--}}

                                        {{--<input type="file" name="profilepic" accept="image/*">--}}

                                        {{--<div><input type="button" id="avatar-submit" value="Submit"></div>--}}
                                        {{--</div>--}}
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="g">
                            <h3> Instagram Tool Settings </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <form class="" role="form">
                                        <div class="form-group">
                                            <label class="">Tasks - Maximum Failed runs before stop a
                                                task</label>
                                            <input type="number" class="form-control" id="" value="50"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Tasks - Maximum Failed Actions before stop a
                                                task</label>
                                            <input type="number" class="form-control" id="" value="200"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Proxies - Amount of Maximum Failed Attempts</label>
                                            <input type="number" class="form-control" id="" value="100"/>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Proxies - Disable Proxys when they reach their limit
                                                timeouts?</label>
                                            <select class="form-control">
                                                <option selected>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Proxies - Mark all accounts as "Proxy Offline" if
                                                the proxy
                                                become offline?</label>
                                            <select class="form-control">
                                                <option selected>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-success" type="submit"><i
                                                        class="fa fa-arrow-circle-right"></i> Save Settings
                                            </button>
                                            <button class="btn default" style="margin-left:1%;" type="button">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="h">
                            <h3> Email Notifications </h3>

                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <form class="" role="form">
                                        <div class="form-group">
                                            <label class="">Notify when your balance is less then ...</label>
                                            <select class="form-control">
                                                <option selected>Disable</option>
                                                <option>5€</option>
                                                <option>10€</option>
                                                <option>15€</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Notify me when my Instagram Auto-Likes are about to
                                                expire</label>
                                            <select class="form-control">
                                                <option selected>Disable</option>
                                                <option>5€</option>
                                                <option>10€</option>
                                                <option>15€</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="">Notify me when my Instagram License is
                                                Expiring</label>
                                            <select class="form-control">
                                                <option>Enable</option>
                                                <option selected>Disable</option>
                                            </select>
                                        </div>
                                        <small class="help-block">Notifications are sent every 24 hours</small>
                                        <div class="form-group">
                                            <button class="btn btn-success" type="submit"><i
                                                        class="fa fa-arrow-circle-right"></i> Save Settings
                                            </button>
                                            <button class="btn default" style="margin-left:1%;" type="button">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="i">
                            COMING SOON
                        </div>
                        <div class="tab-pane" id="j">
                            COMING SOON
                        </div>
                    </div>
                    <!-- /tab-content -->
                </div>
                <!-- /tabbable -->
            </div>
        </div>
        <!-- Row ends -->

        <!-- Row Start -->

        <!-- Row End -->
    </div>
    <!-- Dashboard Wrapper End -->

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

    <script type="text/javascript">

        //        jQuery('.navbar-links').on('click', function () {
        //            console.log('asdlasdk');
        //            jQuery('.navbar-links').removeClass('active');
        //            jQuery(this).addClass('active');
        //        });


        $(document).ready(function () {

            $('#changePassword').validate({
                rules: {
                    oldPassword: {
                        required: true
                    },
                    newPassword: {
                        required: true
                    },
                    conformNewPassword: {
                        required: true,
                        equalTo: "#newPassword"
                    }
                },
                messages: {
                    conformNewPassword: " Enter Confirm Password Same as Password"
                },
                //               errorPlacement: function (error, element) {
//                    if (element.attr("name") == "oldPassword") {
//                        if ($(error).html() != '')
//                            $('#oldPasswordError').html($(error).html());
//                        else
//                            $('#oldPasswordError').html("");
//                    }
//                    if (element.attr("name") == "newPassword") {
//                        if ($(error).html() != '')
//                            $('#newPasswordError').html($(error).html());
//                        else
//                            $('#newPasswordError').html("");
//                    }
//                    if (element.attr("name") == "conformNewPassword") {
//                        if ($(error).html() != '')
//                            $('#conformNewPasswordError').html($(error).html());
//                        else
//                            $('#conformNewPasswordError').html("");
//                    }
                //               },

                submitHandler: function (form) {
                    console.log("Form validate successful");
                    $('#oldPasswordError').html('');
                    $('#newPasswordError').html('');
                    $('#conformNewPasswordError').html('');
                    var passwordData = $('#changePassword').serializeArray();
                    passwordData.push({name: 'userId', value: '{{Auth::User()->id}}'});
                    $.ajax({
                        type: "POST",
                        url: "/user/updatePassword",
                        dataType: "json",
                        data: passwordData,
                        success: function (response) {
                            console.log(response);
                            var alertMsg = '';
                            if (response['status'] == 1) {
                                alertMsg += response['successMessage']
                            } else if (response['status'] == 0) {
                                if ($.isArray(response['errorMessage'])) {
                                    $.each(response['errorMessage'], function (index, value) {
                                        alertMsg += value + '\n';
                                    })
                                } else {
                                    alertMsg = response['errorMessage'];
                                }
                            }
                            alert(alertMsg);
                            $('#changePassword').trigger("reset");
                        },
                        error: function (xhr, status, err) {
                            console.log(err);
                            $('#changePassword').trigger("reset");
                        }
                    });
                }
            });


            $('#avatar-submit').click(function (e) {
                e.preventDefault();
                var formData = new FormData(); //$('#profilepicform').serialize();
                formData.append('file', $('input[type=file]')[0].files[0]);
                formData.append('api_token', '{{env('API_TOKEN')}}');
                formData.append('user_id', '{{Session::get('ig_user')['id']}}');
                var profile_pic_url = "";
                $.ajax({
                    type: "POST",
//                url: "/user/changeAvatar",
                    url: '{{env('API_URL')}}/user/changeAvatar',
                    contentType: false,
                    dataType: "json",
                    processData: false,
                    data: formData,
                    success: function (response) {
                        console.log(response);
                        console.log({{env('API_TOKEN')}});
                        if (response['code'] == 200) {
                            profile_pic_url = response['data'];
                            var d = new Date();
                            d.setTime(d.getTime() + (60 * 2000));
                            var expires = "expires=" + d.toUTCString();
                            document.cookie = "profile_pic_url=" + profile_pic_url + ';' + expires;
                            <?php


                            if (isset($_COOKIE['profile_pic_url'])) {
                                Session::put('ig_user.profile_pic' , $_COOKIE['profile_pic_url']);
                            }?>
                            window.location.reload(true);
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        console.log("error");
                    }
                });


            });

            $('#accountSetting').validate({
                rules: {
                    firstname: {required: true},
                    lastname: {required: true},
                    username: {required: true},
                    email: {required: true}
//                    addressline1: {required: true},
//                    city: {required: true},
//                    state: {required: true},
//                    country_id: {required: true},
//                    contact_no: {
//                        required: true
//                    remote: {
//                        url: "/user/ajaxHandler",
//                        type: 'POST',
//                        datatype: 'json',
//                        data: {
//                            method: 'checkContactNumber'
//                        }
//                    }
//                    }

                },
                messages: {
                    firstname: {
                        required: "Please enter first name"
                    },
                    lastname: {
                        required: "Please enter last name"
                    }
//                    addressline1: {
//                        required: "Please enter an address"
//                    },
//                    city: {
//                        required: "Please enter city"
//                    },
//                    state: {
//                        required: "Please enter state"
//                    },
//                    country_id: {
//                        required: "Please enter country name"
//                    },
//                    contact_no: {
//                        required: "Please enter your contact number"
////                    remote: "This Contact Number is already in use."
//                    }
                },
                submitHandler: function (form) {
                    console.log("Form validate successful");
                    var passwordData = $('#accountSetting').serializeArray();
                    console.log(passwordData);

                    $.ajax({
                        url: '/user/updateProfileInfo',
                        type: 'POST',
                        dataType: 'json',
                        data: passwordData,
                        success: function (response) {
                            console.log(response);
                            var alertMsg = '';
                            if (response['status'] == 1) {
                                alertMsg += response['successMessage']
                            } else if (response['status'] == 0) {
                                if ($.isArray(response['errorMessage'])) {
                                    $.each(response['errorMessage'], function (index, value) {
                                        alertMsg += value + '\n';
                                    })
                                } else {
                                    alertMsg = response['errorMessage'];
                                }
                            }
                            alert(alertMsg);
                        },
                        error: function (xhr, status, err) {
                            console.log(err);
                        }
                    });
                }
            });

        });
    </script>

@endsection

