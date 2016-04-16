@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
        <!-- BEGIN PAGE LEVEL STYLES -->

<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components" />
<link href="/assets/css/plugins-md.css" rel="stylesheet" />
<link href="/assets/css/layout.css" rel="stylesheet" />
<link href="/assets/css/light.css" rel="stylesheet" id="style_color" />
<link href="/assets/css/profile.css" rel="stylesheet" />
<link href="/assets/css/custom.css" rel="stylesheet" />
<!-- END THEME STYLES -->

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
                <h1>FAQ</h1>
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
                <a href=/user/faq">FAQ</a>
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
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">FAQ</span>
                            {{--<span class="caption-helper">manage your current balance ...</span>--}}
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabs-left row">
                            <div class="col-md-12 col-xs-12">
                                <div id="h">
                                    <div class="row" style="margin-top:3%;">
                                        <div class="col-md-12">
                                            <div class="panel panel-default" id="faq">
                                                <div class="panel-heading">
                                                    <ul class="nav nav-tabs nav-justified" role="tablist">
                                                        <li class="active ">
                                                            <a href="#tab1" data-toggle="tab">
                                                                <i class="fa fa-legal"></i>
                                                                <span> Terms of Service </span>
                                                            </a>
                                                        </li>
                                                        <li >
                                                            <a href="#tab2" data-toggle="tab">
                                                                <i class="fa fa-user"></i>
                                                                <span> Contact </span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab3" data-toggle="tab">
                                                                <i class="fa fa-instagram"></i>
                                                                <span> FAQ </span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab4" data-toggle="tab">
                                                                <i class="fa fa-shopping-cart"></i>
                                                                <span> Payment </span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab5" data-toggle="tab">
                                                                <i class="fa fa-eye-slash"></i>
                                                                <span> Refund Policy </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- /.panel-heading -->
                                                <div class="panel-body tab-content" style="margin-top: 3%;">
                                                    <div role="tabpanel" class="tab-pane active fade in" id="tab1">
                                                        <div class="panel-group" id="accordion" role="tablist"
                                                             aria-multiselectable="true">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingOne">
                                                                    <h4 class="panel-title">
                                                                        <a role="button" data-toggle="collapse"
                                                                           data-parent="#accordion" href="#collapseOne"
                                                                           aria-expanded="true" aria-controls="collapseOne">
                                                                            1.General
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseOne" class="panel-collapse collapse in"
                                                                     role="tabpanel" aria-labelledby="headingOne">
                                                                    <div class="panel-body">
                                                                        <p><i style="font-size: 80px;">.</i> Messazon, in its
                                                                            sole discretion, may modify or review these Terms of
                                                                            Service
                                                                            at any time without prior notice. All recent
                                                                            modifications
                                                                            or revisions done by Messazon replaces all former
                                                                            agreements and takes effect upon date of
                                                                            posting.</p>

                                                                        <p><span style="font-size: 80px;">.</span>
                                                                            Continued use of the service following recent
                                                                            amendments
                                                                            signifies your
                                                                            assent to the revised Terms of Service. You further
                                                                            agree to
                                                                            apprise yourself of
                                                                            recent changes on the Terms of Service through
                                                                            periodic
                                                                            reviews of Messazon
                                                                            website.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            <i style="color: maroon;text-underline: green;">
                                                                                IF YOU DO NOT WISH TO BE BOUND BY THESE TERM S
                                                                                OF SE
                                                                                RVICE OR DO NOT
                                                                                AGREE TO ANY OR ALL TERMS, PLEASE DO NOT
                                                                                REGISTER!
                                                                            </i>
                                                                        </p>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingTwo">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" role="button"
                                                                           data-toggle="collapse" data-parent="#accordion"
                                                                           href="#collapseTwo" aria-expanded="false"
                                                                           aria-controls="collapseTwo">
                                                                            2. Services
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseTwo" class="panel-collapse collapse"
                                                                     role="tabpanel" aria-labelledby="headingTwo">
                                                                    <div class="panel-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p><i style="font-size: 80px;">.</i> Messazon
                                                                                    sole purpose is for the social media
                                                                                    promotion.</p>

                                                                                <p><i style="font-size: 80px;">.</i> We
                                                                                    are not responsible for your actions and
                                                                                    their consequences.</p>

                                                                                <p><i style="font-size: 80px;">.</i>If for any
                                                                                    reason your accounts get banned,we are not
                                                                                    responsible.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i> We do not
                                                                                    guarantee followers interaction at
                                                                                    all.Although so
                                                                                    me servers
                                                                                    have active and real followers,we cannot be
                                                                                    100%
                                                                                    sure.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i>We cannot
                                                                                    guarantee 100% that the followers
                                                                                    accounts
                                                                                    have
                                                                                    a profile
                                                                                    picture, Bio, posts ... etc. It all depends
                                                                                    on
                                                                                    the ser
                                                                                    vice/server you will
                                                                                    choose.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i>You shall
                                                                                    not knowingly exploit the system
                                                                                    including but
                                                                                    not lim
                                                                                    ited to,
                                                                                    gaining advantage, exploit speed or any
                                                                                    other
                                                                                    bug in a
                                                                                    manner oth
                                                                                    er than
                                                                                    purchasing them from the website or use the
                                                                                    regular
                                                                                    features
                                                                                    .
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i> It is your
                                                                                    sole responsibility to comply with
                                                                                    any social
                                                                                    n
                                                                                    etwork you use
                                                                                    and any legislation that you are subject to.
                                                                                    You
                                                                                    use our
                                                                                    ser
                                                                                    vices at your
                                                                                    own risk.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i>When the
                                                                                    order is in "Processing" status, you
                                                                                    may not
                                                                                    reque
                                                                                    st to cancel the
                                                                                    service under any circumstances.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i>There are a
                                                                                    certain time limits for the delivery
                                                                                    of all
                                                                                    request or service
                                                                                    "Don't give promises to your clients for
                                                                                    quick
                                                                                    delivery
                                                                                    ”
                                                                                    this delay may be
                                                                                    changed at any time without warning and this
                                                                                    is
                                                                                    due to
                                                                                    any sudden
                                                                                    renovation or upgrades in the social media
                                                                                    sites.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i>Do not do
                                                                                    the same order for the same account
                                                                                    link until
                                                                                    th
                                                                                    e first order is
                                                                                    finished, In this case will only do one
                                                                                    request,
                                                                                    and
                                                                                    consider th
                                                                                    e second
                                                                                    request is also complete.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i>There are a
                                                                                    certain time limits for the delivery
                                                                                    of all
                                                                                    request or service
                                                                                    "Don't give promises to your clients for
                                                                                    quick
                                                                                    delivery
                                                                                    ”
                                                                                    this delay may be
                                                                                    changed at any time without warning and this
                                                                                    is
                                                                                    due to
                                                                                    any sudden
                                                                                    renovation or upgrades in the social media
                                                                                    sites.
                                                                                </p>

                                                                                <p><i style="font-size: 80px;">.</i>In the case
                                                                                    if you change your account to
                                                                                    private, the
                                                                                    order
                                                                                    will be a fully
                                                                                    completed service, even if you change it on
                                                                                    public again
                                                                                    and
                                                                                    your order
                                                                                    will not be refund
                                                                                    ed
                                                                                    .
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingThree">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" role="button"
                                                                           data-toggle="collapse" data-parent="#accordion"
                                                                           href="#collapseThree" aria-expanded="false"
                                                                           aria-controls="collapseThree">
                                                                            3. Purchase
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseThree" class="panel-collapse collapse"
                                                                     role="tabpanel" aria-labelledby="headingThree">
                                                                    <div class="panel-body">

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            You agree that upon purchasing you clearly
                                                                            understand
                                                                            and agree
                                                                            what
                                                                            you are purchasing and will not file a fraudulent
                                                                            dispute.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            Upon a fraudulent attempt to file a dispute, we
                                                                            grant
                                                                            the right
                                                                            , if
                                                                            necessary, to reset your account,remove all
                                                                            followers/li
                                                                            kes,spam with
                                                                            comments your profile,terminate your account and/or
                                                                            permanen
                                                                            tly ban
                                                                            your IP address.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            The credits added in Messazon
                                                                            ar
                                                                            e not reversible if the system and web
                                                                            site it self are fully working. Subscription based
                                                                            service
                                                                            is counted
                                                                            based on the time you have registered your Instagram
                                                                            profil
                                                                            e,in this
                                                                            case if you do not login or use the account we will
                                                                            not
                                                                            replac
                                                                            e the time
                                                                            and Messazon web site is not obligated in any case
                                                                            to
                                                                            refund
                                                                            you.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            We are not able to transfer time or services between
                                                                            dif
                                                                            ferent services
                                                                            or servers
                                                                            .</p>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingFour">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" role="button"
                                                                           data-toggle="collapse" data-parent="#accordion"
                                                                           href="#collapseFour" aria-expanded="false"
                                                                           aria-controls="collapseFour">
                                                                            4. Refunds
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseFour" class="panel-collapse collapse"
                                                                     role="tabpanel" aria-labelledby="headingFour">
                                                                    <div class="panel-body">

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            You fully understand and agree that no refunds to
                                                                            PayPal
                                                                            will
                                                                            ever be
                                                                            made at any circumstances once you made your
                                                                            payment(s)
                                                                            at
                                                                            PayPal.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            You understand and agree that the amount of your
                                                                            order
                                                                            may be
                                                                            dropped
                                                                            at any time by the social media site without warning
                                                                            and
                                                                            y
                                                                            ou'll not be
                                                                            refunded.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            Since Messazon offers non-tangible irrevocable
                                                                            goods,
                                                                            you agree that after
                                                                            the purchase is made you cannot cancel/stop or
                                                                            remove
                                                                            any a
                                                                            ctions that
                                                                            this initiated. You understand that by Purchasing
                                                                            any
                                                                            goods on
                                                                            Messazon.com this decision is final and you won’
                                                                            t be able to reserve it.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            We will not refund to your PayPal account when the
                                                                            payment
                                                                            at PayPal
                                                                            has been completed. We can only refund to your
                                                                            Messazon
                                                                            a
                                                                            ccount.Any
                                                                            PayPal refund request will be denied.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            You agree that once the payment is made to PayPal
                                                                            and
                                                                            your
                                                                            account at
                                                                            Messazon is charged, you will not lodge a complaint
                                                                            or a
                                                                            dis
                                                                            pute against
                                                                            us.If the dispute was filed against us or conflict
                                                                            for
                                                                            any reas
                                                                            on without
                                                                            contacting us, we reserve the right to stop all
                                                                            previous
                                                                            orders and the
                                                                            current, and prohibit your account from the site
                                                                            entirely,
                                                                            and we reserve
                                                                            the right to take back all followers of your account
                                                                            or the accounts of your
                                                                            clients you order for them previously.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            If for any reason,your credit card or your Paypal
                                                                            account
                                                                            is stolen and the
                                                                            payment is declared as un-authorized,we will not
                                                                            give a
                                                                            refu
                                                                            nd.It's your
                                                                            responsibility to keep safe your money/credit
                                                                            cards/Paypal acc
                                                                            ount and not
                                                                            ours.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            We do not cancel and return the amount of any
                                                                            request (
                                                                            upo
                                                                            n a request
                                                                            from you ) for any reason, except in case of system
                                                                            fail
                                                                            ure to complete the
                                                                            work.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            Messazon has the right to cancel any order without
                                                                            notice an
                                                                            d without
                                                                            explaining the reason for the cancellation.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            Messazon services prices are subjected to change at
                                                                            any
                                                                            ti
                                                                            me without any
                                                                            prior notice.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingFive">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" role="button"
                                                                           data-toggle="collapse" data-parent="#accordion"
                                                                           href="#collapseFive" aria-expanded="false"
                                                                           aria-controls="collapseFive">
                                                                            5. Registration
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseFive" class="panel-collapse collapse"
                                                                     role="tabpanel" aria-labelledby="headingFive">
                                                                    <div class="panel-body">

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            By registering you affirm that you are 13 years of
                                                                            age
                                                                            or
                                                                            an emancipated
                                                                            minor and are completely able and competent to enter
                                                                            into t
                                                                            he terms and
                                                                            conditions set forth in these Terms of Service and
                                                                            co
                                                                            mply and abide by
                                                                            them.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingSix">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" role="button"
                                                                           data-toggle="collapse" data-parent="#accordion"
                                                                           href="#collapseSix" aria-expanded="false"
                                                                           aria-controls="collapseSix">
                                                                            6. Copyright
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseSix" class="panel-collapse collapse"
                                                                     role="tabpanel" aria-labelledby="headingSix">
                                                                    <div class="panel-body">

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            Without prior written consent of a Messazon
                                                                            representative
                                                                            , you may not
                                                                            copy or reproduce any texts, images or programming
                                                                            used
                                                                            on the
                                                                            Messazon website.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            All brand icons are trademarks of their respective
                                                                            owners.
                                                                            The use of these
                                                                            trademarks does not indicate endorsement of the
                                                                            trademark
                                                                            holder by Font
                                                                            Awesome, nor vice versa.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingSeven">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" role="button"
                                                                           data-toggle="collapse" data-parent="#accordion"
                                                                           href="#collapseSeven" aria-expanded="false"
                                                                           aria-controls="collapseSeven">
                                                                            7. Disclaimer
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapseSeven" class="panel-collapse collapse"
                                                                     role="tabpanel" aria-labelledby="headingSeven">
                                                                    <div class="panel-body">

                                                                        <p><li>
                                                                            You agree that your use of Messazon is at your own
                                                                            risk.
                                                                            Messazon may
                                                                            not be held liable or accountable for any or
                                                                            whatever
                                                                            damage
                                                                            s you or your
                                                                            business may incur.</li>

                                                                        <p><li>
                                                                            Messazon does not guarantee website uptime or
                                                                            availability as
                                                                            it uses the
                                                                            internet to deliver its services.</li></p>

                                                                        <p><li>
                                                                            Messazon will not be responsible for any damage
                                                                            happens to the
                                                                            account(s).</li></p>

                                                                        <p><li>
                                                                            Messazon services do not guarantee delivery within
                                                                            24 hours. We don’t guarantee the delivery time limitation at all</li></p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            We are not responsible for the loss of money or
                                                                            negati
                                                                            ve comments or ban
                                                                            because of delays in delivery.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            If you sell on mini-sites or services for personal
                                                                            custo
                                                                            mers, use our
                                                                            services at your own risk.</p>

                                                                        <p><i style="font-size: 80px;">.</i>
                                                                            The registration in Messazon, charging your account
                                                                            an
                                                                            d making new
                                                                            orders means that full acceptance of these
                                                                            conditions,
                                                                            whether you read it
                                                                            or not.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="heading8">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" role="button"
                                                                           data-toggle="collapse" data-parent="#accordion"
                                                                           href="#collapse8" aria-expanded="false"
                                                                           aria-controls="collapse8">
                                                                            8. Change of Terms
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <div id="collapse8" class="panel-collapse collapse"
                                                                     role="tabpanel" aria-labelledby="heading8">
                                                                    <div class="panel-body">

                                                                        <p>

                                                                        <li>This Terms of Service are subject to change at any
                                                                            time. Notices of change will be considered given and effective on the date
                                                                            posted on our website.
                                                                            The changes made will become effective the date they are posted on our
                                                                            website. No further notice by Messazon is required upon your continued
                                                                            use of our website.
                                                                        </li>

                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div role="tabpanel" class="tab-pane fade" id="tab2">
                                                        <div class="tabs-left row">
                                                            <div class="col-md-12 col-xs-12">
                                                                <div class="row">
                                                                    <div class="col-md-8">
                                                                        <form class="" action="" role="form">
                                                                            <div class="form-group">
                                                                                <label class="control-label"> Subject : </label>
                                                                                <input type="text" class="form-control"
                                                                                       name="subject"/>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label"> Message : </label>
                                                                        <textarea class="form-control"
                                                                                  name="message"></textarea>
                                                                            </div>
                                                                            <div class="form-group text-center">
                                                                                <button class="btn btn-primary">SUBMIT</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <ul class="list-unstyled">
                                                                            <li>
                                                                                <p>
                                                                                    <b>Skype :</b> messazon.support
                                                                                </p>
                                                                            </li>
                                                                            <li>
                                                                                <p>
                                                                                    <b>Email :</b> support@messazon.com
                                                                                </p>
                                                                            </li>
                                                                            <li>
                                                                                <p>
                                                                                    <b>FAQ :</b> <a href="/user/faq">See Next Tab</a>
                                                                                </p>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane fade" id="tab3">
                                                        <p>LOrem Ipsum
                                                            This is FAQ page</p>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane fade" id="tab4">
                                                        <p> <div class="row" style="margin-top:1%;">
                                                            <div class="col-md-12">
                                                                <ul class="purchase">
                                                                    <li><b>Use valid and real information about you</b></li>
                                                                    <li>Don't use Proxies or VPN</li>
                                                                    <li>Payments can be refused , please check your payment status before you open a
                                                                        ticket.
                                                                    </li>
                                                                    <li>Minimum Purchase <b>10 credits -$10</b></li>
                                                                    <li>Purchases are final and we cannot refund credits back to your paypal or credit
                                                                        card
                                                                    </li>
                                                                    <li>Credits Amount.<b>1 Credit=1 US Dollar </b></li>
                                                                </ul>
                                                            </div>
                                                        </div></p>
                                                    </div>
                                                    <div role="tabpanel" class="tab-pane fade" id="tab5">
                                                        <p>
                                                        <p>1. You fully understand and agree that no refunds to PayPal will
                                                            ever be made at any circumstances once you made your payment(s) at
                                                            PayPal.</p>

                                                        <p> 2. You understand and agree that the amount of your order may be
                                                            dropped at any time by the social media site without warning and
                                                            you'll not
                                                            be refunded.</p>

                                                        <p>3. Since Messazon offers non-tangible irrevocable goods,
                                                            you agree that after the purchase is made you cannot cancel/stop or
                                                            remove any actions that
                                                            this initiated. You understand that by Purchasing any goods on
                                                            Messazon.com this decision is final and you won’t be able to reserve
                                                            it.</p>

                                                        <p>4. We will not refund to your PayPal account when the payment
                                                            at PayPal has been completed. We can only refund to your Messazon a
                                                            ccount.Any PayPal refund request will be denied.</p>

                                                        <p>5. You agree that once the payment is made to PayPal and your account
                                                            at
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
                                                            clients you order for them previously.</p>

                                                        <p> 6. If for any reason,your credit card or your Paypal account is
                                                            stolen and the
                                                            payment is declared as un-authorized,we will not give a refund.It’s
                                                            your
                                                            responsibility to keep safe your money/credit cards/Paypal account
                                                            and not ours.</p>

                                                        <p> 7. We do not cancel and return the amount of any request ( upon a
                                                            request
                                                            from you ) for any reason, except in case of system failure to
                                                            complete thework.</p>

                                                        <p>8. Messazon has the right to cancel any order without notice and
                                                            without
                                                            explaining the reason for the cancellation.</p>

                                                        <p> 9. Messazon services prices are subjected to change at any time
                                                            without any prior notice.</p>
                                                        </p>
                                                    </div>

                                                </div>



                                                <!-- /.panel-body -->
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

@endsection



