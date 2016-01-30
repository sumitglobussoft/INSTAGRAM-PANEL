@extends('Supplier/Layouts/supplierlayout')

@section('title','Dashboard')


@section('headcontent')
{{--OPTIONAL--}}
{{--PAGE STYLES OR SCRIPTS LINKS--}}

@endsection

@section('content')
{{--PAGE CONTENT GOES HERE--}}

        <!-- Sub Nav End -->
<div class="sub-nav hidden-sm hidden-xs">
    <ul>
        <li><a href="javascript:;" class="heading">Market-->Order History</a></li>
    </ul>
    <div class="custom-search hidden-sm hidden-xs">
        <input type="text" class="search-query" placeholder="Search here ...">
        <i class="fa fa-search"></i>
    </div>
</div>
<!-- Sub Nav End -->

<!-- Dashboard Wrapper Start -->
<div class="dashboard-wrapper-lg">

  order History Null

</div>

@endsection

@section('pagejavascripts')
    {{--PAGE SCRIPTS GO HERE--}}

@endsection



