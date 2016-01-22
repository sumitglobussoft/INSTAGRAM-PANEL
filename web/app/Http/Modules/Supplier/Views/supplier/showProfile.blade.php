<html>
<head>
    {{--<META HTTP-EQUIV="Access-Control-Allow-Origin" CONTENT="*">--}}

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

</head>
<body>
<div>
    <form enctype="multipart/form-data" id="profilepicform">
        <div>
            <img src="{{Session::get('ig_supplier')['profile_pic']}}" alt="user Avatar" width="70px" height="70px">

            {{--<div><input type="button" id="chngeAvatar" value="Edit Avatar"></div>--}}

            {{--<div>--}}
            {{--<span class="default btn-file">--}}
            {{--<span class="fileinput-new"> Select image </span>--}}
            {{--<span class="fileinput-exists">Change </span>--}}
            {{--<input type="file" name="..." accept="image/*">--}}
            {{--</span>--}}
            {{--<a href="#" class=" default fileinput-exists"   data-dismiss="fileinput">  Remove </a>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="margin-top-10">--}}
            {{--<div><input type="button" id="avatar-submit" value="Submit"></div>--}}
            {{--<div><input type="button"  value="Cancel"></div>--}}
            <input type="file" name="profilepic" accept="image/*">

            <div><input type="button" id="avatar-submit" value="Submit"></div>
        </div>
    </form>
</div>

{{--<div>--}}
{{--<form action="" method="post" id="profile-info">--}}
{{--<div class="userDetailsContainer">--}}

{{--<div>--}}
{{--First Name <input type="text" name="firstname" value="{{$userData['name']}}"><br>--}}
{{--Last Name <input type="text" name="lastname" value="{{$userData['lastname']}}"><br>--}}
{{--User Name <input type="text" name="username" value="{{$userData['username']}}"><br>--}}
{{--Email Address <input type="email" name="email" value="{{$userData['email']}}"><br>--}}
{{--</div>--}}
{{--<div>--}}
{{--<h3>Address Information</h3><br>--}}
{{--Address Line 1 <input type="text" name="addressline1" value="{{$userData['addressline1']}}"><br>--}}
{{--Address Line 2 <input type="text" name="addressline2" value="{{$userData['addressline2']}}"><br>--}}
{{--City<input type="text" name="city" value="{{$userData['city']}} "><br>--}}
{{--State<input type="text" name="state" value="{{$userData['state']}}"><br>--}}
{{--Country<input type="text" name="country_id" value="{{$userData['country_id']}}"><br>--}}
{{--Contact No. <input type="text" name="contact_no" value="{{$userData['contact_no']}}"><br>--}}
{{--</div>--}}
{{--<div>--}}
{{--<button id="edit-info">Edit Details</button>--}}
{{--</div>--}}

{{--<div>--}}
{{--<button class="hidden" id="save-info-changes">Save Changes</button>--}}
{{--<button class="hidden" id="cancel">Cancel</button>--}}
{{--</div>--}}


{{--<br>--}}
{{--</div>--}}
{{--</form>--}}
{{--</div>--}}

{{--<div>--}}
{{--<form action="" method="post" id="password-change">--}}
{{--<div class="changePasswordContainer">--}}
{{--<div>--}}
{{--<input type="password" name="oldPassword" placeholder="Enter Old Password"><br>--}}
{{--<span id="oldPassword"></span>--}}
{{--<input type="password" name="newPassword" id="newPassword" placeholder="Enter New Password"><br>--}}

{{--<input type="password" name="conformNewPassword" placeholder="Enter New Conform Password"><br>--}}
{{--</div>--}}
{{--<div>--}}
{{--<button id="submit-change-password" type="submit">Change Password</button>--}}
{{--</div>--}}
{{--</div>--}}
{{--</form>--}}
{{--</div>--}}


<script type="text/javascript">
    $(document).ready(function () {

//        $("#edit-info").click(function (e) {
//
//            $("#edit-info").addClass("hidden");
//            $("#save-info-changes").removeClass("hidden");
//            $("#cancel").removeClass("hidden");
//        });


//        $('#profile-info').validate({
//            rules: {
//                firstname: {required: true},
//                lastname: {required: true},
//                addressline1: {required: true},
//                city: {required: true},
//                state: {required: true},
//                country_id: {required: true},
//                contact_no: {
//                    required: true
////                    remote: {
////                        url: "/supplier/ajaxHandler",
////                        type: 'POST',
////                        datatype: 'json',
////                        data: {
////                            method: 'checkContactNumber'
////                        }
////                    }
//                }
//
//            },
//            messages: {
//                firstname: {
//                    required: "Please enter first name"
//                },
//                lastname: {
//                    required: "Please enter last name"
//                },
//                addressline1: {
//                    required: "Please enter an address"
//                },
//                city: {
//                    required: "Please enter city"
//                },
//                state: {
//                    required: "Please enter state"
//                },
//                country_id: {
//                    required: "Please enter zip code"
//                },
//                contact_no: {
//                    required: "Please enter your contact number"
////                    remote: "This Contact Number is already in use."
//                }
//            }
//        });
//
//        $("#save-info-changes").click(function (e) {
//            e.preventDefault();
//            if ($('#profile-info').valid()) {
//                console.log("Form validate successful");
//                var passwordData = $('#profile-info').serializeArray()
//                console.log(passwordData);
//
//                $.ajax({
//                    url: '/supplier/updateProfileInfo',
//                    type: 'POST',
//                    dataType: 'json',
//                    data: passwordData,
//                    success: function (response) {
//                        console.log(response);
//                        console.log("After ajax call");
//                    },
//                    error: function (xhr, status, err) {
//                        console.log(err);
//                    }
//                });
//            }
//        });


        {{--$('#password-change').validate({--}}
        {{--rules: {--}}
        {{--oldPassword: {--}}
        {{--required: true--}}
        {{--},--}}
        {{--newPassword: {--}}
        {{--required: true,--}}
        {{--},--}}
        {{--conformNewPassword: {--}}
        {{--required: true,--}}
        {{--equalTo: "#newPassword"--}}
        {{--}--}}
        {{--},--}}
        {{--messages: {--}}
        {{--conformNewPassword: " Enter Confirm Password Same as Password"--}}
        {{--},--}}
        {{--//            submitHandler: function(form) {--}}
        {{--//                alert("Asd");--}}
        {{--//--}}
        {{--//                form.submit();--}}
        {{--//--}}
        {{--//            },--}}


        {{--});--}}


        {{--$("#submit-change-password").click(function (e) {--}}
        {{--e.preventDefault();--}}
        {{--if ($('#password-change').valid()) {--}}
        {{--console.log("Form validate successful");--}}

        {{--var passwordData = $('#password-change').serializeArray();--}}

        {{--passwordData.push({name: 'userId', value:'{{Auth::User()->id}}'});--}}

        {{--console.log(passwordData);--}}
        {{--$.ajax({--}}
        {{--type: "POST",--}}
        {{--url: "/supplier/updatePassword",--}}
        {{--dataType: "json",--}}
        {{--data: passwordData,--}}
        {{--success: function (response) {--}}
        {{--console.log(response);--}}
        {{--console.log("After ajax call");--}}
        {{--},--}}
        {{--error: function (xhr, status, err) {--}}
        {{--console.log(err);--}}
        {{--}--}}
        {{--});--}}
        {{--}--}}

        {{--});--}}

        $('#avatar-submit').click(function (e) {
            e.preventDefault();
            var formData = new FormData(); //$('#profilepicform').serialize();
            formData.append('file', $('input[type=file]')[0].files[0]);
            formData.append('api_token', '{{env('API_TOKEN')}}');
            formData.append('user_id', '{{Session::get('ig_supplier')['id']}}');
            $.ajax({
                type: "POST",
//                url: "/supplier/changeAvatar",
                url: "http://api.instagramautolike.localhost.com/supplier/changeAvatar",
                contentType: false,
                dataType: "json",
                processData: false,
                data: formData,
                success: function (response) {
                    console.log(response);
                    if (response['code'] == 200) {
                        var profile_pic_url = response['data'];
                        var d = new Date();
                        d.setTime(d.getTime() + (60 * 1000));
                        var expires = "expires=" + d.toUTCString();
                        document.cookie = "profile_pic_url=" + profile_pic_url + ';' + expires;
                        <?php
                        if (isset($_COOKIE['profile_pic_url'])) {
                            Session::put('ig_supplier.profile_pic' , $_COOKIE['profile_pic_url']);
                        }?>
                        window.location.reload(true );
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });

    });
</script>
</body>
</html>