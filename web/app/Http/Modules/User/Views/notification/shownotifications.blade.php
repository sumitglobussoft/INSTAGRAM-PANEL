@extends('User/Layouts/userlayout')

@section('title','Notification')


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
            <li>Notifications Log</li>
        </ol>
    </section>


    <section class="page-content">

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body no-padding">
                        <ul class="list">
                            @foreach($notifications as $ntf)
                                <li class="list-item list-2-line">
                                    <div class="list-icon list-avatar-icon">
                                        <i class="material-icons">folder</i>
                                    </div>
                                    <div>
                                        {{$ntf->created_at}}
                                    </div>
                                    <div class="list-item-text layout-column">
                                        <h3 style="color: darkblue" >{{$ntf->notifications_txt}}</h3>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}

@endsection