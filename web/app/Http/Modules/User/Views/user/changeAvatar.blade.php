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
            <li>Over view</li>
        </ol>
    </section>

    <section class="page-content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-default panel-divider">
                    <div class="panel-body" style="padding-top: 0;">
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
    </script>

@endsection



