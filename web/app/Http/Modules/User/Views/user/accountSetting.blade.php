@extends('User/Layouts/userlayout')

@section('title','Order History')


@section('headcontent')
        <!-- BEGIN PAGE LEVEL STYLES -->

<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="/assets/css/components-md.css" rel="stylesheet" id="style_components"/>
<link href="/assets/css/plugins-md.css" rel="stylesheet"/>
<link href="/assets/css/layout.css" rel="stylesheet"/>
<link href="/assets/css/light.css" rel="stylesheet" id="style_color"/>
<link href="/assets/css/profile.css" rel="stylesheet"/>
<link href="/assets/css/custom.css" rel="stylesheet"/>
<!-- END THEME STYLES -->

<link rel="shortcut icon" href="favicon.ico"/>

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
                <h1>Account Settings</h1>
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
                <a href="/user/updateProfileInfo">Account Settings</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- END PAGE HEADER-->
        <!-- BEGIN PAGE CONTENT-->
        <div class="row">
            <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift font-green-sharp"></i>
                            <span class="caption-subject font-green-sharp bold uppercase">Account Settings</span>
                            <span class="caption-helper">manage your account...</span>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <div class="alert alert-danger" hidden>
                            <button class="close" data-close="alert"></button>
                            <span id="errorMessage" style="text-align: center;"></span>
                        </div>

                        <div class="alert alert-success" hidden>
                            <button class="close" data-close="alert"></button>
                            <span id="successMessage" style="text-align: center;"></span>
                        </div>

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
                                                   placeholder="" required
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
                                                   placeholder="" required
                                                   value="{{$userData['username']}}"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="">Skype</label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-skype"></span></div>
                                            <input type="text" class="form-control" placeholder="Skype Username"
                                                   id="skypeUsername" name="skypeUsername"
                                                   value=""/>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="">Email Address</label>

                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-envelope"></span>
                                            </div>
                                            <input type="text" class="form-control" id="emai" name="email"
                                                   placeholder="" required
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
<script src="/assets/js/validate/jquery.validate.js"></script>
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
<!--BEGIN CUSTOME PAGE LEVEL SCRIPT-->
<script type="text/javascript">
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
            var userInformation = $('#accountSetting').serializeArray();
//            console.log(userInformation);

            $.ajax({
                url: '/user/updateProfileInfo',
                type: 'POST',
                dataType: 'json',
                data: userInformation,
                success: function (response) {
                    console.log(response);
                    var alertMsg = '';
                    if (response['status'] == 'success') {

                        alertMsg += response['successMessage']
                    } else if (response['status'] == 'fail') {
                        if ($.isArray(response['errorMessage'])) {
                            $.each(response['errorMessage'], function (index, value) {
                                alertMsg += value + '\n';
                            });
                            $('#successMessage').text(alertMsg);
                        } else {
                            alertMsg = response['errorMessage'];
                            $('#errorMessage').text(alertMsg);
                        }
                    }
                    window.alert(alertMsg);
                },
                error: function (xhr, status, err) {
                    console.log(err);
                }
            });
        }
    });
</script>
<!--END CUSTOME PAGE LEVEL SCRIPT-->
@endsection



