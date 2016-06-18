@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    {{--<meta charset="utf-8">--}}
    {{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
    {{--<title>Conversation</title>--}}
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">--}}
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">--}}
    <style>
        .comments-container {
            margin: 60px auto 15px;
            width: 768px;
        }

        .comments-container h1 {
            font-size: 36px;
            color: #283035;
            font-weight: 400;
        }

        .comments-container h1 a {
            font-size: 18px;
            font-weight: 700;
        }

        .comments-list {
            margin-top: 30px;
            position: relative;
        }

        .comments-list:before {
            content: '';
            width: 2px;
            height: 100%;
            background: #c7cacb;
            position: absolute;
            left: 32px;
            top: 0;
        }

        .comments-list:after {
            content: '';
            position: absolute;
            background: #c7cacb;
            bottom: 0;
            left: 27px;
            width: 7px;
            height: 7px;
            border: 3px solid #dee1e3;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
        }

        .reply-list:before, .reply-list:after {
            display: none;
        }

        .reply-list li:before {
            content: '';
            width: 60px;
            height: 2px;
            background: #c7cacb;
            position: absolute;
            top: 25px;
            left: -55px;
        }

        .comments-list li {
            margin-bottom: 15px;
            display: block;
            position: relative;
        }

        .comments-list li:after {
            content: '';
            display: block;
            clear: both;
            height: 0;
            width: 0;
        }

        .reply-list {
            padding-left: 88px;
            clear: both;
            margin-top: 15px;
        }

        .comments-list .comment-avatar {
            width: 65px;
            height: 65px;
            position: relative;
            z-index: 99;
            float: left;
            border: 3px solid #FFF;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .comments-list .comment-avatar img {
            width: 100%;
            height: 100%;
        }

        .reply-list .comment-avatar {
            width: 50px;
            height: 50px;
        }

        .comment-main-level:after {
            content: '';
            width: 0;
            height: 0;
            display: block;
            clear: both;
        }

        .comments-list .comment-box {
            width: 680px;
            float: right;
            position: relative;
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
            -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
            background-color: #FFF;
        }

        .comments-list .comment-box:before, .comments-list .comment-box:after {
            content: '';
            height: 0;
            width: 0;
            position: absolute;
            display: block;
            border-width: 10px 12px 10px 0;
            border-style: solid;
            border-color: transparent #FCFCFC;
            top: 8px;
            left: -11px;
        }

        .comments-list .comment-box:before {
            border-width: 11px 13px 11px 0;
            border-color: transparent rgba(0, 0, 0, 0.05);
            left: -12px;
        }

        .reply-list .comment-box {
            width: 610px;
        }

        .comment-box .comment-head {
            background: #FCFCFC;
            padding: 10px 12px;
            border-bottom: 1px solid #E5E5E5;
            overflow: hidden;
            -webkit-border-radius: 4px 4px 0 0;
            -moz-border-radius: 4px 4px 0 0;
            border-radius: 4px 4px 0 0;
        }

        .comment-box .comment-head i {
            float: right;
            margin-left: 14px;
            position: relative;
            top: 2px;
            color: #A6A6A6;
            cursor: pointer;
            -webkit-transition: color 0.3s ease;
            -o-transition: color 0.3s ease;
            transition: color 0.3s ease;
        }

        .comment-box .comment-head i:hover {
            color: #03658c;
        }

        .comment-box .comment-name {
            color: #283035;
            font-size: 14px;
            font-weight: 700;
            float: left;
            margin-right: 10px;
        }

        .comment-box .comment-name a {
            color: #283035;
        }

        .comment-box .comment-head span {
            float: left;
            color: #999;
            font-size: 13px;
            position: relative;
            top: 1px;
        }

        .comment-box .comment-content {
            background: #FFF;
            padding: 12px;
            font-size: 15px;
            color: #595959;
            -webkit-border-radius: 0 0 0px 0px;
            -moz-border-radius: 0 0 0px 0px;
            border-radius: 0 0 0px 0px;
            border-bottom: .5px solid #e5e5e5;
        }

        .comment-box .comment-footer {
            border-radius: 0 0 4px 4px;
            padding: 12px;
            width: 100%;
            background: #fff none repeat scroll 0 0;
        }

        .comment-box .comment-footer textarea {
            resize: none;
            width: 100%;
            border-radius: 4px;
            padding: 1%;
        }

        .comment-box .send-button, .comment-box .comment-open {
            padding: 12px;
            background: #fff none repeat scroll 0 0;
        }

        .comment-box .send-button .btn-send, .comment-box .comment-open .btn-send {
            background-color: #03658c;
            border-color: #03658c;
            color: #fff;
            padding: 6px 12px;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
        }

        .comment-box .send-button .btn-send, .comment-box .comment-open .btn-send {
            text-decoration: none;
        }

        .comment-box .btn-reply {
            cursor: pointer;
        }

        .comment-box .comment-name.by-author, .comment-box .comment-name.by-author a {
            color: #03658c;
        }

        .comment-box .comment-name.by-author:after {
            /*content: '';*/
            background: #03658c;
            color: #FFF;
            font-size: 12px;
            padding: 3px 5px;
            font-weight: 700;
            margin-left: 10px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }

        .comment-box .posted-time {
            margin-top: 8px;
        }

        .comment-box .comment-footer {
            display: none;
        }

        @media only screen and (max-width: 766px) {
            .comments-container {
                width: 480px;
            }

            .comments-list .comment-box {
                width: 390px;
            }

            .reply-list .comment-box {
                width: 320px;
            }
        }
    </style>

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Tickets</li>
            </ol>
            <div class="page-header_title">
                <h1>Conversations </h1>
            </div>
        </section>

        <section class="page-content">
            <div class="comments-container">
                <h1>User Queries</h1>
                @foreach($ticketdetails as $td)
                    @if($td->ticket_status==1)
                        <h2 style="color: red">This ticket has closed.</h2>
                    @endif
                    <ul id="comments-list" class="comments-list">
                        <li>
                            <div class="comment-main-level">
                                <!-- Avatar -->
                                <div class="comment-avatar"><img src="{{$td->profile_pic}}" alt=""></div>
                                <!-- Contenedor del Comentario -->
                                <div class="comment-box">
                                    <div class="comment-head">
                                        <h6 class="comment-name by-author"><a href="#">{{$td->username}}</a></h6>
                                        <span class="posted-time">{{$td->created_at}}</span>
                                        {{--<i class="fa fa-heart"></i>--}}
                                    </div>
                                    <div class="comment-content">
                                        {{$td->descriptions}}
                                        {{--<form method="post" action="/admin/postreply">--}}
                                        @if($td->ticket_status==0)
                                            <div class="comment-open" name="reply">
                                                <a class="btn-reply">
                                                    <i class="fa fa-reply"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="comment-footer">
                                        <div class="comment-form">
                                    <textarea class="form-control" name="" id="getid"
                                              value="{{$td->ticket_id}}"></textarea>

                                            <div class="pull-right send-button">
                                                <a class="btn-send" name="send">send</a>
                                            </div>
                                        </div>
                                    </div>
                                    {{--</form>--}}
                                </div>
                            </div>

                            @if(isset($td->reply_text))
                                @foreach($ticketdetails as $reply)
                                    <ul class="comments-list reply-list">
                                        <li>

                                            <div class="comment-avatar"><img src="@if($reply->replied_by==1)
                                                        http://dummyimage.com/60 @else {{$reply->profile_pic}} @endif" alt="">
                                            </div>

                                            <div class="comment-box">
                                                <div class="comment-head">
                                                    <h6 class="comment-name"><a href="#">@if($reply->replied_by==1)
                                                                Admin @else {{$reply->username}} @endif</a></h6>
                                                    <span class="posted-time">Posted on {{$reply->created_at}}</span>
                                                    {{--<i class="fa fa-heart"></i>--}}
                                                </div>
                                                <div class="comment-content">
                                                    {{$reply->reply_text}}
                                                </div>
                                            </div>
                                        </li>

                                        {{--<li>--}}

                                        {{--<div class="comment-avatar"><img src="http://dummyimage.com/60" alt=""></div>--}}

                                        {{--<div class="comment-box">--}}
                                        {{--<div class="comment-head">--}}
                                        {{--<h6 class="comment-name by-author"><a href="#">User Name</a></h6>--}}
                                        {{--<span class="posted-time">Posted on DD-MM-YYYY HH:MM</span>--}}
                                        {{--<i class="fa fa-heart"></i>--}}
                                        {{--</div>--}}
                                        {{--<div class="comment-content">--}}
                                        {{--Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit omnis animi et iure laudantium vitae, praesentium optio, sapiente distinctio illo?--}}
                                        {{--</div>--}}
                                        {{--</div>--}}
                                        {{--</li>--}}
                                    </ul>
                                @endforeach
                            @endif
                        </li>
                    </ul>
                    @break
                @endforeach
            </div>
        </section>
    </section>
@endsection


@section('pagescripts')
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>--}}
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>--}}
    <script>
        $(document).on('click', '.btn-reply', function (eve) {
            eve.preventDefault();
            $(this).parent().parent().siblings('.comment-footer').slideToggle();
            eve.stopImmediatePropagation();
            console.log($(this));
        });

        $(document).on('click', '.btn-send', function (eve) {
            var targetObject = $(this).parent().parent().parent().parent().parent();
            //console.log(targetObject);
            var reply_text = $(this).parent().siblings('textarea').val();
            var id = $('#getid').val();
            console.log(id);
            console.log(reply_text)
            $.post('/admin/view-queries/'.id, 'val=' + $(this).parent().siblings('textarea').val(), function (response) {
//                alert(response);
                location.reload();

            });
            $(this).parent().siblings('textarea').val(" ");
            $(this).parent().parent().parent().slideUp("fast");

            if ($.trim(reply_text) == " " || $.trim(reply_text) == "") {
                alert("insert comment");
            } else {
                if ($(targetObject).hasClass("comment-main-level")) {
                    if ($(targetObject).siblings('.comments-list.reply-list')) {
                        element_prepend = '<li> <div class="comment-avatar"><img alt="" src="http://dummyimage.com/60"></div><div class="comment-box"> <div class="comment-head"> <h6 class="comment-name"><a href="#">Admin</a></h6> <span class="posted-time">Posted on DD-MM-YYYY HH:MM</span> <i class="fa fa-reply"></i> <i class="fa fa-heart"></i> </div> <div class="comment-content">' + reply_text + '  </div></div></li>';
                        $(targetObject).siblings('.comments-list.reply-list').prepend(element_prepend);
                    }
                }
            }
        });
    </script>
@endsection
