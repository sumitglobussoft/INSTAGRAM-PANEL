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
            <li>Account Settings</li>
        </ol>
    </section>

    <section class="page-content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-default panel-divider">
                    <div class="panel-body" style="padding-top: 0;">
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

                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}
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
                        window.alert(alertMsg);
                    },
                    error: function (xhr, status, err) {
                        console.log(err);
                    }
                });
            }
        });
    </script>
@endsection



