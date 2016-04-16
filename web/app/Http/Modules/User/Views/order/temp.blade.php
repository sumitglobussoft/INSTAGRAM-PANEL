@extends('User/Layouts/userlayout')

@section('title','Dashboard')


@section('headcontent')
    {{--<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>--}}
    {{--<meta http-equiv="Pragma" content="no-cache"/>--}}
    {{--<meta http-equiv="Expires" content="0"/>--}}
    <link rel="stylesheet" type="text/css" href="/assets/plugins/temp/jquery.dataTables.min.css"/>
    <link href="/assets/metronic/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);

        .tooltip.top.fade.in {
            color: #fff !important;
            background-color: #000;
        }

        .filter > td {
            vertical-align: middle !Important;
        }

        .seperator-n {
            padding: 0 2px;
        }

        b {
            font-weight: 700;
        }

        .input-inline {
            display: inline-block;
            vertical-align: middle;
            width: auto;
        }

        select.input-sm {
            height: 28px;
            line-height: 28px;
            padding: 2px 10px;
            display: inline-block;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
            vertical-align: middle;
            width: 45px !important;
            color: #333333;
            border: 1px solid #e5e5e5;
            vertical-align: middle;
            width: 75px !important;
        }

        .dataTables_length {
            display: inline-block;
            float: none !important;
            margin: 0 !important;
            padding: 0 !important;
            position: static !important;
            font-family: "Open Sans", sans-serif;
            font-size: 13px;
        }

        .input-sm {
            font-size: 13px;
            height: 28px;
            padding: 5px 10px;
            display: inline-block;
            vertical-align: middle;
            width: 45px !important;
            background-color: white;
            border: 1px solid #e5e5e5;
            box-shadow: none;
            color: #333333;
            font-size: 14px;
            font-weight: normal;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
        }

        .btn-sm {
            font-size: 13px;
            line-height: 1.5;
            padding: 4px 10px 5px;
            background-color: #e5e5e5;
            color: #333333;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive .table .btn {
            margin-left: 0;
            margin-top: 0;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive input[type="text"] {
            border: .5px solid #CCC;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive input[type="text"]:hover, .table.table-striped.table-borderless.table-hover.table-responsive input[type="text"]:focus {
            border-color: #999999;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive .red.btn {
            background-color: #d84a38;
            color: white;
        }

        .btn-xs, .btn.btn-sm.default.table-group-action-submit {
            padding: 0px 1px !important;
            font-size: 9px;
            line-height: 1.5;
            background-color: #e5e5e5;
            color: #333333;
            border-width: 0;
            box-shadow: none;
            filter: none;
            outline: medium none !important;
            text-shadow: none;
            border-radius: 3px;
            margin: 3px;
            outline: medium none !important;
            padding: 0 3px !important;
        }

        .table * {
            line-height: 24px;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive td {
            line-height: 3px;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive small {
            color: rgba(0, 0, 0, 0.54);
            font-size: 11px;
            letter-spacing: 0;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive td.insta {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive td.insta-link {
            max-width: 100px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table.table-striped.table-borderless.table-hover.table-responsive button, a {
            min-width: 5px;
        }

    </style>
@endsection

@section('content')
    {{--PAGE CONTENT GOES HERE--}}
    <section id="right-content-wrapper">
        <section class="page-header alternative-header">
            <ol class="breadcrumb">
                <li>IP Admin</li>
                <li>Social Market</li>
                <li>Instagram Auto-Likes</li>
            </ol>
        </section>

        <section class="page-content">

            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="panel panel-default panel-divider">
                    <div class="panel-heading">
                        <header> Latest Orders</header>
                    </div>
                    <div class="panel-body" style="padding-top: 0;">

                        <div class="row">
                            <div class="col-md-12">
                                {{--<div class="table-actions-wrapper">--}}
                                {{--<span>--}}
                                {{--</span>--}}
                                {{--<select class="table-group-action-input form-control input-inline input-small input-sm">--}}
                                {{--<option value="">Select...</option>--}}
                                {{--<option value="Cancel">Cancel</option>--}}
                                {{--<option value="Cancel">Hold</option>--}}
                                {{--<option value="Cancel">On Hold</option>--}}
                                {{--<option value="Close">Close</option>--}}
                                {{--</select>--}}
                                {{--<button class="btn btn-sm yellow table-group-action-submit"><i--}}
                                {{--class="fa fa-check"></i> Submit--}}
                                {{--</button>--}}
                                {{--</div>--}}
                                <div class="">
                                    <div class="table-group-actions pull-right">
                                        <span></span>
                                        <select class="table-group-action-input form-control input-inline input-small input-sm">
                                            <option value="">Select Action</option>
                                            <option value="export_id">Export selected Order ID(s)</option>
                                            <option value="export_link">Export with Details</option>
                                            <option value="order_cancel">Cancel selected Order(s)</option>
                                            <option value="order_refill">Refill Order(s)</option>
                                            <option value="order_mentions_data">Get Mentions Data</option>
                                            <option value="order_resubmit">Re-Add selected Order(s)</option>
                                        </select>
                                        <button name="search_query" class="btn btn-sm default table-group-action-submit"
                                                data-original-title="" title=""><i class="fa fa-check"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-striped table-borderless table-hover table-responsive"
                                   id="datatable_ajax">
                                <thead>
                                <tr role="row" class="heading">
                                    <th width="1%"><input type="checkbox" class="group-checkable"></th>
                                    <th width="3%">#ID</th>
                                    <th width="25%">Service</th>
                                    <th width="25%">Link</th>
                                    <th width="5%">Amount</th>
                                    <th width="5%">Price</th>
                                    <th width="3%">Added</th>
                                    <th width="3%">Updated</th>
                                    <th width="10%">Status</th>
                                    <th width="20%">Details</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_order_id"></td>
                                    <td class='insta-link'>
                                        <select name="search_service_type"
                                                class="form-control form-filter input-sm">
                                            <option value="">Select...</option>
                                            <option value="like">Facebook - Post Likes</option>
                                            <option value="like">Facebook - Fan Page Likes ( Real )</option>
                                            <option value="like">Facebook - Post Likes ( Real )</option>
                                            <option value="like">Facebook - Page Likes ( English )
                                            </option>
                                            <option value="likes">Instagram - Likes ( Normal )</option>
                                            <option value="comments">Instagram - Comments ( Random )</option>
                                            <option value="follower">Instagram - Followers ( Fast )
                                            </option>
                                            <option value="likes">Instagram - Likes ( Fast )</option>
                                            <option value="likes">Instagram - Likes ( Medium)</option>
                                            <option value="likes">Instagram - Likes ( HQ )</option>
                                            <option value="followers">Instagram - Followers ( Medium )
                                            </option>


                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_link"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_amount"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_price" disabled="disabled"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_time_added" disabled="disabled"></td>
                                    <td><input type="text" class="form-control form-filter input-sm"
                                               name="search_time_updated" disabled="disabled"></td>
                                    <td><select name="search_status" class="form-control form-filter input-sm">
                                            <option value="">Select...</option>
                                            <option value="0">Pending</option>
                                            <option value="3">Processing</option>
                                            <option value="5">Completed</option>
                                            <option value="4">Refunded</option>
                                            <option value="7">Partial Refunded</option>
                                        </select></td>
                                    <td class=''>
                                        <button class="btn btn-xs default filter-submit margin-bottom"><i
                                                    class="fa fa-search"></i> Search
                                        </button>
                                        <button class="btn btn-xs red filter-cancel"><i class="fa fa-times"></i>
                                            Reset
                                        </button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            </div>

        </section>
    </section>

    @endsection

    @section('pagejavascripts')
            <!--BEGIN CORE PLUGINS -->
    {{--<script src="metronic-plugins/assets/global/plugins/jquery.min.js" type="text/javascript"></script>--}}

    <!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
    {{--<script src="metronic-plugins/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>--}}
    {{--<script src="metronic-plugins/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>--}}

    <script src="/assets/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>

    <!-- END CORE PLUGINS -->



    <script src="/assets/plugins/temp/jquery.dataTables.min.js"></script>

    {{--<script src="/assets/metronic/admin/layout/scripts/layout.js" type="text/javascript"></script>--}}


    <script src="/assets/metronic/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>


    {{--<script src="/assets/metronic/admin/layout/scripts/demo.js" type="text/javascript"></script>--}}



    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {{--<script type="text/javascript"--}}
    {{--src="/assets/metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>--}}
    <script type="text/javascript"
            src="/assets/metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {{--<script src="/assets/metronic/global/scripts/metronic.js" type="text/javascript"></script>--}}
    <script src="/assets/metronic/global/scripts/datatable.js"></script>
    <!-- END PAGE LEVEL SCRIPTS -->


    <script>


        var Metronic = function () {
            var assetsPath = '/assets/metronic/';

            var globalImgPath = 'global/img/';
            return {
                scrollTo: function (el, offeset) {
                    var pos = (el && el.size() > 0) ? el.offset().top : 0;

                    if (el) {
                        if ($('body').hasClass('page-header-fixed')) {
                            pos = pos - $('.page-header').height();
                        } else if ($('body').hasClass('page-header-top-fixed')) {
                            pos = pos - $('.page-header-top').height();
                        } else if ($('body').hasClass('page-header-menu-fixed')) {
                            pos = pos - $('.page-header-menu').height();
                        }
                        pos = pos + (offeset ? offeset : -1 * el.height());
                    }

                    $('html,body').animate({
                        scrollTop: pos
                    }, 'slow');
                },
                getUniqueID: function (prefix) {
                    return 'prefix_' + Math.floor(Math.random() * (new Date()).getTime());
                },
                getGlobalImgPath: function () {
                    return assetsPath + globalImgPath;
                },
                blockUI: function (options) {
                    options = $.extend(true, {}, options);
                    var html = '';
                    if (options.animate) {
                        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
                    } else if (options.iconOnly) {
                        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""></div>';
                    } else if (options.textOnly) {
                        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
                    } else {
                        html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + this.getGlobalImgPath() + 'loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
                    }

                    if (options.target) { // element blocking
                        var el = $(options.target);
                        if (el.height() <= ($(window).height())) {
                            options.cenrerY = true;
                        }
                        el.block({
                            message: html,
                            baseZ: options.zIndex ? options.zIndex : 1000,
                            centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                            css: {
                                top: '10%',
                                border: '0',
                                padding: '0',
                                backgroundColor: 'none'
                            },
                            overlayCSS: {
                                backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                                opacity: options.boxed ? 0.05 : 0.1,
                                cursor: 'wait'
                            }
                        });
                    } else { // page blocking
                        $.blockUI({
                            message: html,
                            baseZ: options.zIndex ? options.zIndex : 1000,
                            css: {
                                border: '0',
                                padding: '0',
                                backgroundColor: 'none'
                            },
                            overlayCSS: {
                                backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                                opacity: options.boxed ? 0.05 : 0.1,
                                cursor: 'wait'
                            }
                        });
                    }
                },

                // wrMetronicer function to  un-block element(finish loading)
                unblockUI: function (target) {
                    if (target) {
                        $(target).unblock({
                            onUnblock: function () {
                                $(target).css('position', '');
                                $(target).css('zoom', '');
                            }
                        });
                    } else {
                        $.unblockUI();
                    }
                },
                alert: function (options) {

                    options = $.extend(true, {
                        container: "", // alerts parent container(by default placed after the page breadcrumbs)
                        place: "append", // "append" or "prepend" in container
                        type: 'success', // alert's type
                        message: "", // alert's message
                        close: true, // make alert closable
                        reset: true, // close all previouse alerts first
                        focus: true, // auto scroll to the alert after shown
                        closeInSeconds: 0, // auto close after defined seconds
                        icon: "" // put icon before the message
                    }, options);

                    var id = Metronic.getUniqueID("Metronic_alert");

                    var html = '<div id="' + id + '" class="Metronic-alerts alert alert-' + options.type + ' fade in">' + (options.close ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' : '') + (options.icon !== "" ? '<i class="fa-lg fa fa-' + options.icon + '"></i>  ' : '') + options.message + '</div>';

                    if (options.reset) {
                        $('.Metronic-alerts').remove();
                    }

                    if (!options.container) {
                        if ($('body').hasClass("page-container-bg-solid")) {
                            $('.page-title').after(html);
                        } else {
                            if ($('.page-bar').size() > 0) {
                                $('.page-bar').after(html);
                            } else {
                                $('.page-breadcrumb').after(html);
                            }
                        }
                    } else {
                        if (options.place == "append") {
                            $(options.container).append(html);
                        } else {
                            $(options.container).prepend(html);
                        }
                    }

                    if (options.focus) {
                        Metronic.scrollTo($('#' + id));
                    }

                    if (options.closeInSeconds > 0) {
                        setTimeout(function () {
                            $('#' + id).remove();
                        }, options.closeInSeconds * 1000);
                    }

                    return id;
                },

                // initializes uniform elements
                initUniform: function (els) {
                    if (els) {
                        $(els).each(function () {
                            if ($(this).parents(".checker").size() === 0) {
                                $(this).show();
                                $(this).uniform();
                            }
                        });
                    } else {
                        handleUniform();
                    }
                }
            };

        }();
        var EcommerceOrders = function () {

            var handleOrders = function () {
                var grid = new Datatable();
                grid.init({
                    src: $("#datatable_ajax"),
                    onSuccess: function (grid, response) {
                        console.log("success");
                        console.log(grid);
                        console.log(response);
                        // grid:        grid object
                        // response:    json object of server side ajax response
                        // execute some code after table records loaded
                    },
                    onError: function (grid) {
                        console.log("Error");
                        console.log(grid);
//                        grid.fnDraw();
//                        grid.ajax.reload();
//                        window.location.reload();
                        // execute some code on network or other general error
                    },
                    onDataLoad: function (grid) {
                        console.log("Data loaded");
                        console.log(grid);
                        // execute some code on ajax data load
                    },
                    loadingMessage: 'Loading...',
                    dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

                        // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                        // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                        // So when dropdowns used the scrollable div should be removed.
                        "dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                        "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                        "lengthMenu": [
                            [10, 20, 50, 100, 150],
                            [10, 20, 50, 100, 150] // change per page values here
                        ],
                        "pageLength": 10, // default record count per page
                        "ajax": {
                            "url": "/user/tempajax" // ajax source
                        },
                        "order": [
                            [1, "asc"]
                        ]// set first column as a default sort by asc
                    }
                });

                // handle group actionsubmit button click
                grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
                    e.preventDefault();
                    var action = $(".table-group-action-input", grid.getTableWrapper());
                    if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                        grid.setAjaxParam("customActionType", "group_action");
                        grid.setAjaxParam("customActionName", action.val());
                        grid.setAjaxParam("id", grid.getSelectedRows());
                        grid.getDataTable().ajax.reload();
                        grid.clearAjaxParams();
                    } else if (action.val() == "") {
                        //                        Metronic.alert({
                        //                            type: 'danger',
                        //                            icon: 'warning',
                        //                            message: 'Please select an action',
                        //                            container: grid.getTableWrapper(),
                        //                            place: 'prepend'
                        //                        });
                    } else if (grid.getSelectedRowsCount() === 0) {
                        //                        Metronic.alert({
                        //                            type: 'danger',
                        //                            icon: 'warning',
                        //                            message: 'No record selected',
                        //                            container: grid.getTableWrapper(),
                        //                            place: 'prepend'
                        //                        });
                    }
                });
            };
            return {
                //main function to initiate the module
                init: function () {
                    handleOrders();
                }
            };
        }();

        $(document).ready(function () {
            setTimeout(function () {
                EcommerceOrders.init();
            }, 1000);

//            Metronic.init();
        });
    </script>

@endsection



