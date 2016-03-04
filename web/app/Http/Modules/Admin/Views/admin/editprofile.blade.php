@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Profile</li>
            </ol>
            <div class="page-header_title">
                <h1>Profile Settings </h1>
            </div>
        </section>

        <section class="page-content">

            <div class="row">
                <div class="col-md-12">
                    <!-- <div class="panel panel-default panel-divider">
                       <div class="panel-body"> -->
                    <div class="panel panel-default panel-divider no-border">
                        <div class="panel-heading">
                            <header><i class="fa fa-cog"></i> Profile</header>
                            <ul role="tablist" class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#tab2-1" aria-expanded="true">Profile</a>
                                </li>
                                <li class=""><a data-toggle="tab" href="#tab2-2" aria-expanded="false">Change
                                        Password</a></li>
                                {{--<li class=""><a data-toggle="tab" href="#tab2-3" aria-expanded="false">Dummy</a></li>--}}
                            </ul>
                        </div>
                        <!--/.panel-heading -->
                        <div class="panel-body tab-content">
                            <div id="tab2-1" class="tab-pane active bg-gradient padding-1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    {{--<div class="col-md-4 grey-100-background-color">--}}
                                        {{--<div class="profile-avatar"></div>--}}

                                        {{----}}
                                        {{--<h1 class="profile-name"--}}
                                            {{--style="color:green">{{Session::get('instagram_admin')['name']}}</h1>--}}
                                    {{--</div>--}}
                                    @if(Session::has('message'))
                                        @if(session('status')=='Success')
                                            <div style="color:green;">
                                                <b>{{session('status')}}</b> {{Session::get('message')}}</div>
                                        @endif
                                        @if(session('status')=='Error')
                                            <div style="color:red;">
                                                <li> {{Session::get('message')}}</li>
                                            </div>
                                        @endif
                                    @endif
                                    <div class="col-md-6">
                                        <div class="profile-info">
                                            <h1>{{Session::get('instagram_admin')['name']}}</h1>

                                            <h3>{{Session::get('instagram_admin')['username']}}</h3>

                                            <h3>{{Session::get('instagram_admin')['email']}}</h3>

                                            <h3>India</h3>
                                            <a class="btn btn-primary" id='profile-edit'>EDIT</a>
                                        </div>
                                        <form class="form profile-form" style="display: none;" method="post">
                                            <div class="form-group floating-label">
                                                <input class="form-control" id="success1" type="text" name="newname"
                                                       value="{{Session::get('instagram_admin')['name']}}">
                                                <label for="success1">First Name</label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('newname') }}</div>
                                            </div>

                                            <div class="form-group floating-label">
                                                <input class="form-control" id="success1" type="text" name="newlastname"
                                                       value="{{Session::get('instagram_admin')['lastname']}}">
                                                <label for="success1">Last Name</label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('newlastname') }}</div>
                                            </div>

                                            <div class="form-group floating-label">
                                                <input class="form-control" id="success1" type="text" name="newusername"
                                                       value="{{Session::get('instagram_admin')['username']}}">
                                                <label for="success1">UserName</label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('newusername') }}</div>
                                            </div>

                                            <div class="form-group floating-label">
                                                <input class="form-control" id="success1" type="text" name="newemail"
                                                       value="{{Session::get('instagram_admin')['email']}}">
                                                <label for="success1">Email Address</label>

                                                <div class="error"
                                                     style="color:red">{{ $errors->first('newemail') }}</div>
                                            </div>
                                            <input type="submit" class="btn btn-primary" id='profile-save'
                                                   name="generalinfo" value="Save Changes">
                                            {{--<a class="btn btn-primary" id='profile-save' name="generalinfo">SAVE</a>--}}
                                        </form>


                                    </div>
                                </div>
                            </div>
                            <div id="tab2-2" class="tab-pane" role="tabpanel">
                                <form class="form change-password" method="post">
                                    <div class="form-group floating-label">
                                        <input class="form-control" id="success1" type="password"
                                               name="currentpassword">
                                        <label for="success1">Current Password</label>

                                        <div class="error"
                                             style="color:red">{{ $errors->first('currentpassword') }}</div>
                                    </div>

                                    <div class="form-group floating-label">
                                        <input class="form-control" id="" type="password" name="newpassword">
                                        <label for="success2">New Password</label>

                                        <div class="error" style="color:red">{{ $errors->first('newpassword') }}</div>
                                    </div>

                                    <div class="form-group floating-label">
                                        <input class="form-control" id="" type="password"
                                               name="newpassword_confirmation">
                                        <label for="success2">Re-enter Password</label>

                                        <div class="error"
                                             style="color:red">{{ $errors->first('newpassword_confirmation') }}</div>
                                    </div>
                                    <input type="submit" class="btn btn-primary" id='profile-save' name="editpassword"
                                           value="Save Changes">
                                </form>
                            </div>
                            {{--<div id="tab2-3" class="tab-pane" role="tabpanel">--}}
                            {{--<a class="btn btn-primary" id='profile-save'>DUMMY</a>--}}
                            {{--</div>--}}
                        </div>
                        <!--/.panel-body -->
                    </div>
                    <!-- </div>
                </div>  -->


                </div>
            </div>
        </section>
    </section>

@endsection


@section('pagescripts')
    <script type="text/javascript" src="/js/imagepreview.js"></script>
    <script type="text/javascript">
        $(document).ready(function (e) {
            $('.profile-form').css('display', 'none !important');
            $('.profile-info').show();
        });
        $('#profile-edit').on('click', function () {
            $('.profile-form').show();
            $('.profile-info').hide();
        });

        $('#profile-save').on('click', function () {

            $('.profile-form').show();
            $('.profile-info').hide();
        });

        $(document).ready(function () {
            $('#preview1').imagepreview({
                input: '[name="testimage1"]',
                reset: '#reset1',
                preview: '#preview1'
            });
            console.log('helo');
        });
    </script>
@endsection
