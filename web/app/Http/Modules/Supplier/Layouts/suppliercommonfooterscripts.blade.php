<script src="/assets/plugins/js/jquery.js"></script>
<script src="/assets/plugins/js/bootstrap.min.js"></script>
<script src="/assets/plugins/js/jquery.scrollUp.js"></script>

<!-- jQuery UI JS -->
<script src="/assets/plugins/js/jquery-ui-v1.10.3.js"></script>

<!-- Scroller -->
<script src="/assets/plugins/js/tiny-scrollbar.js"></script>

<!-- Custom JS -->
<script src="/assets/plugins/js/menu.js"></script>
<script src="/assets/plugins/js/custom.js"></script>

<!-- DataTables -->
<script src="/assets/plugins/js/jquery.dataTables.min.js"></script>


<script>
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