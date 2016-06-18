@extends('Admin/Layouts/adminlayout')

@section('pageheadcontent')
    {{--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
    <style>
        .onoffswitch {
            position: relative; width: 90px;
            -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
        }
        .onoffswitch-checkbox {
            display: none;
        }
        .onoffswitch-label {
            display: block; overflow: hidden; cursor: pointer;
            border: 2px solid #999999; border-radius: 20px;
        }
        .onoffswitch-inner {
            display: block; width: 200%; margin-left: -100%;
            transition: margin 0.3s ease-in 0s;
        }
        .onoffswitch-inner:before, .onoffswitch-inner:after {
            display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
            font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
            box-sizing: border-box;
        }
        .onoffswitch-inner:before {
            content: "ON";
            padding-left: 10px;
            background-color: #34C247; color: #FFFFFF;
        }
        .onoffswitch-inner:after {
            content: "OFF";
            padding-right: 10px;
            background-color: #EEEEEE; color: #999999;
            text-align: right;
        }
        .onoffswitch-switch {
            display: block; width: 18px; margin: 6px;
            background: #FFFFFF;
            position: absolute; top: 0; bottom: 0;
            right: 56px;
            border: 2px solid #999999; border-radius: 20px;
            transition: all 0.3s ease-in 0s;
        }
        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
            margin-left: 0;
        }
        .onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
            right: 0px;
        }




        .cmn-toggle {
            position: absolute;
            margin-left: -9999px;
            visibility: hidden;
        }
        .cmn-toggle + label {
            display: block;
            position: relative;
            cursor: pointer;
            outline: none;
            user-select: none;
        }


        input.cmn-toggle-yes-no + label {
            padding: 2px;
            width: 60px;
            height: 30px;
        }
        input.cmn-toggle-yes-no + label:before,
        input.cmn-toggle-yes-no + label:after {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            color: #fff;
            font-family: "Roboto Slab", serif;
            font-size: 10px;
            text-align: center;
            line-height: 30px;
        }
        input.cmn-toggle-yes-no + label:before {
            background-color: red;
            content: attr(data-off);
            transition: transform 0.5s;
            backface-visibility: hidden;
        }
        input.cmn-toggle-yes-no + label:after {
            background-color: green;
            content: attr(data-on);
            transition: transform 0.5s;
            transform: rotateY(180deg);
            backface-visibility: hidden;
        }
        input.cmn-toggle-yes-no:checked + label:before {
            transform: rotateY(180deg);
        }
        input.cmn-toggle-yes-no:checked + label:after {
            transform: rotateY(0);
        }
    </style>

@endsection


@section('pagecontent')
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Manage Users</li>
            </ol>
            <div class="page-header_title">
                <h1>Rejected Users </h1>
            </div>
        </section>
        <section class="page-content">

            <h1>Rejected User lists</h1>
            <hr>

            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                <label class="onoffswitch-label" for="myonoffswitch">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>

            <div class="onoffswitch">
                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                <label class="onoffswitch-label" for="myonoffswitch">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </div>

            <div class="switch">
                <input id="status" class="cmn-toggle cmn-toggle-yes-no" type="checkbox" style="height: 2px; width: 2px;">
                <label for="status" data-on="Active" data-off="Inactive"></label>
            </div>

            <div class="switch">
                <input id="cmn-toggle-2" class="cmn-toggle cmn-toggle-yes-no" type="checkbox">
                <label for="cmn-toggle-2" data-on="Yes" data-off="No"></label>
            </div>

            <input type="checkbox" data-render="switchery" class="js-switch" data-theme="lime" checked /> </td>

            <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Office</th>
                    <th>Age</th>
                    <th>Start date</th>
                    <th>Salary</th>
                </tr>
                </tfoot>
                <tbody>
                <tr>
                    <td>Saurabh Bond</td>
                    <td>Software Developer</td>
                    <td>Bangalore</td>
                    <td>22</td>
                    <td>26/11/2015</td>
                    <td>Rs.20000</td>
                </tr>
                <tr>
                    <td>Chandrakar Ramkishan</td>
                    <td>Software Developer</td>
                    <td>Bhilai</td>
                    <td>24</td>
                    <td>17/11/2016</td>
                    <td>Rs.20000</td>
                </tr>

                </tbody>
            </table>
        </section>
    </section>
@endsection


@section('pagescripts')
    {{--<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>--}}
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                                .appendTo( $(column.footer()).empty() )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                    );

                                    column
                                            .search( val ? '^'+val+'$' : '', true, false )
                                            .draw();
                                } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            } );
        } );
    </script>


@endsection