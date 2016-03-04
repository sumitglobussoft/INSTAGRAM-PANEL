        <!-- Core Scripts - Include with every page -->
<script src="/assets/plugins/js/jquery-1.10.2.js"></script>
<script src="/assets/plugins/js/jqueryui/jquery-ui.custom.min.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/plugins/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>

   <!-- Page-Level Plugin Scripts - Dashboard -->
<script src="/assets/plugins/js/perfectscrollbar/perfect-scrollbar.jquery.min.js"></script>
<script src="/assets/plugins/js/iCheck/icheck.min.js"></script>
<script src="/assets/plugins/js/bootstrap-select/bootstrap-select.min.js"></script>

	<!-- Cerocreativo Plugins -->
<script src="/assets/plugins/js/materialRipple/jquery.materialRipple.js"></script>

<!-- Bemat Admin Scripts - Included with every page -->
<script src="/assets/js/user-common.min.js"></script>



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
        $.post('/user/conversations'.id, 'val=' + $(this).parent().siblings('textarea').val(), function (response) {
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
                    element_prepend = '<li> <div class="comment-avatar"><img alt="" src="http://dummyimage.com/60"></div><div class="comment-box"> <div class="comment-head"> <h6 class="comment-name"><a href="#">User</a></h6> <span class="posted-time">Posted on DD-MM-YYYY HH:MM</span> <i class="fa fa-reply"></i> <i class="fa fa-heart"></i> </div> <div class="comment-content">' + reply_text + '  </div></div></li>';
                    $(targetObject).siblings('.comments-list.reply-list').prepend(element_prepend);
                }
            }
        }
    });
</script>

