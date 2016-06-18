@extends('User/Layouts/userlayout')

@section('title','Order History')


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
@section('classMyAccount','active')
@section('classMyAccount7','active')
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
                <h1>Notifications</h1>
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
                <a href="/user/accountNotification">Notifications</a>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Email Notifications</span>
                            <span class="caption-helper">manage your email subscription...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabs-left row">
                            <div class="col-md-12 col-xs-12">
                                <div id="g">
                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger ">
                                            <button class="close" data-close="alert"></button>
                                            <p>Please Correct the following Error(s) : </p>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(session('errorMessage'))
                                        <div class="alert alert-danger ">
                                            <button class="close" data-close="alert"></button>
                                            <h5 style="text-align: center;"> <?php echo session('errorMessage'); ?></h5>
                                        </div>
                                    @endif

                                    @if(session('successMessage'))
                                        <div class="alert alert-success ">
                                            <button class="close" data-close="alert"></button>
                                            <span style="text-align: center;"> <?php echo session('successMessage'); ?></span>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-12">
                                            <form class="" role="form" method="post" action="/user/emailNotifications">
                                                <div class="form-group">
                                                    <label class="">Notify me when balance is less then ...</label>
                                                    <select class="form-control" name="notifyBalance"  id="notifyBalance">
                                                        <option value="0" @if(Session::get('ig_user')['notify_bal']==0) selected @endif >Disable</option>
                                                        <option value="5"  @if(Session::get('ig_user')['notify_bal']==5) selected @endif >$5</option>
                                                        <option value="10"  @if(Session::get('ig_user')['notify_bal']==10) selected @endif>$10</option>
                                                        <option value="15"  @if(Session::get('ig_user')['notify_bal']==15) selected @endif >$15</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="">Notify me when Auto Likes profile has less then ...</label>
                                                    <select class="form-control" name="notifyProfileLikes" id="notifyProfileLikes">
                                                        <option value="0"  @if(Session::get('ig_user')['notify_profile_likes']==0) selected @endif >Disable</option>
                                                        <option value="2"  @if(Session::get('ig_user')['notify_profile_likes']==2) selected @endif >2 posts left</option>
                                                        <option value="3"  @if(Session::get('ig_user')['notify_profile_likes']==3) selected @endif>3 posts left</option>
                                                        <option value="5"  @if(Session::get('ig_user')['notify_profile_likes']==5) selected @endif>5 posts left</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    {{--<label class="">Notify me when Auto Likes profile has less then 1,2 days left on daily subscription.</label>--}}
                                                    <label class="">Notify me when my Instagram Auto-Likes profile are about to expire.</label>

                                                    <select class="form-control"  name="notifyDailySubscription"  id="notifyDailySubscription">
                                                        <option value="0"  @if(Session::get('ig_user')['notify_daily_subscription']==0) selected @endif>Disable</option>
                                                        {{--<option value="1"  @if(Session::get('ig_user')['notify_daily_subscription']==1) selected @endif>Enable</option>--}}
                                                        <option value="3"  @if(Session::get('ig_user')['notify_daily_subscription']==3) selected @endif> On expiration date </option>
                                                        <option value="1"  @if(Session::get('ig_user')['notify_daily_subscription']==1) selected @endif> 1 Day before expiration </option>
                                                        <option value="2"  @if(Session::get('ig_user')['notify_daily_subscription']==2) selected @endif> 2 Day before expiration </option>

                                                    </select>
                                                </div>

                                                <small class="help-block">Notifications are sent every 24 hours</small>
                                                <div class="form-group">
                                                    <button class="btn btn-success btn-raised" type="submit"><i class="fa fa-arrow-circle-right"></i> Save Settings</button>
                                                    <button class="btn btn-default btn-raised" style="margin-left:1%;" type="button">Cancel</button>
                                                </div>
                                            </form>
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



