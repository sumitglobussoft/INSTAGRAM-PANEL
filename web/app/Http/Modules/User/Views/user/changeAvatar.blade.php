@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
        <!-- BEGIN PAGE LEVEL STYLES -->

<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
<link href="/assets/css/plugins-md.css" rel="stylesheet"/>
<link href="/assets/css/layout.css" rel="stylesheet"/>
<link href="/assets/css/default.css" rel="stylesheet" id="style_color"/>
<link href="/assets/css/profile.css" rel="stylesheet"/>
<link href="/assets/css/custom.css" rel="stylesheet"/>
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico"/>

@endsection
@section('classMyAccount','active')
@section('classMyAccount6','active')
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
                <h1>Change Avatar</h1>
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
                <a href="/user/changeAvatar">Change Avatar</a>
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
                            <span class="caption-subject font-green-sharp bold uppercase">Change Avatar</span>
                            <span class="caption-helper">manage your account avatar ...</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabs-left row">
                            <div class="row" style="margin-top:3%;">
                                <div class="col-md-12">
                                    <form class="" role="form" id="changeAvatar">

                                        <div class="clearfix"></div>
                                        <div class="form-group col-md-6 ">
                                            <?php if (isset($_COOKIE['profile_pic_url'])) {
                                                Session::put('ig_user.profile_pic', $_COOKIE['profile_pic_url']);
                                            }?>
                                            <img class="img-thumbnail col-md-6 " alt="user Avatar" width="304"
                                                 height="236"
                                                 src="@if(isset(Session::get('ig_user')['profile_pic'])) {{Session::get('ig_user')['profile_pic']}} @else /assets/uploads/useravatar/default-profile-pic.png @endif ">
                                        </div>
                                        {{--<div id="spinner" style="margin-left: 45%" hidden>--}}
                                        {{--<img src="/assets/images/input-spinner.gif"--}}
                                        {{--alt="Loading"/>--}}
                                        {{--</div>--}}
                                        <div id="spinner" style="display:none;width:69px;height:89px;border:1px solid black;position:absolute;top:50%;left:50%;padding:2px;">
                                            <img src='/assets/images/input-spinner.gif' width="64" height="64" /><br>Loading..</div>

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
<!--BEGIN CUSTOM PAGE LEVEL SCRIPT-->
<script type="text/javascript">
    $('#avatar-submit').click(function (e) {
        e.preventDefault();
        var formData = new FormData(); //$('#profilepicform').serialize();
        formData.append('file', $('input[type=file]')[0].files[0]);
        formData.append('api_token', '{{env('API_TOKEN')}}');
        formData.append('user_id', '{{Session::get('ig_user')['id']}}');
        var profile_pic_url = "";
        $.ajax({
            beforeSend: function () {
                $('#spinner').show();
            },
            complete: function () {
                $('#spinner').hide();
            },
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
</script>
<!--END CUSTOM PAGE LEVEL SCRIPT-->
@endsection



