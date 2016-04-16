<script src="/assets/plugins/js/jquery.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/plugins/js/scrollup/jquery.scrollUp.js"></script>

<!-- jQuery UI JS -->
<script src="/assets/plugins/js/jqueryui/jquery-ui-v1.10.3.js"></script>

<!-- Scroller -->
<script src="/assets/plugins/js/scroller/tiny-scrollbar.js"></script>

<!-- Custom JS -->
<script src="/assets/plugins/js/custom/menu.js"></script>
<script src="/assets/plugins/js/custom/custom.js"></script>

<!-- DataTables -->
<script src="/assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

<!-- Select2 -->
<script src="/assets/js/select2.full.min.js"></script>

<script>

    // For Navigating and Selecting Main Tab.
    $(document).ready(function () {
        var currentUrl = $(location).attr('href');
        var currentUrlPage = currentUrl.split('/');
        var currentPage = currentUrlPage[currentUrlPage.length - 1];
        console.log(currentPage);
        jQuery('.navbar-links').removeClass('active');
        jQuery('#' + currentPage).addClass('active');


        if (currentPage == 'addOrder') {
            console.log('asdlasdk');
            jQuery('.navbar-links').removeClass('active');
            jQuery('#market').addClass('active');
        }

    });

    //ScrollUp
    $(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            topDistance: '300', // Distance from top before showing element (px)
            topSpeed: 300, // Speed back to top (ms)
            animation: 'fade', // Fade, slide, none
            animationInSpeed: 400, // Animation in speed (ms)
            animationOutSpeed: 400, // Animation out speed (ms)
            scrollText: 'Top', // Text for element
            activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        });
    });

    //Tiny Scrollbar
    $('#scrollbar').tinyscrollbar();

    //DataTable
    $('#datatable').dataTable();
    //    $(window).load(function () {
    //        $('#datatable_filter input').addClass('form-control');
    //    });

    //Select2
    $(".js-example-responsive").select2();

    var tabsFn = (function () {
        function init() {
            setHeight();
        }

        function setHeight() {
            var $tabPane = $('.tab-pane'),
                    tabsHeight = $('.nav-tabs').height();
            $tabPane.css({
                height: tabsHeight
            });
        }

        $(init);
    })();

</script>