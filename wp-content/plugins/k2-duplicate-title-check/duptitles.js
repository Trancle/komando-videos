jQuery(document).ready(function($){
    // Post function
    function checkTitle(title, id) {
        var data = {
            action: 'title_check',
            post_title: title,
            post_id: id
        };

        //var ajaxurl = 'wp-admin/admin-ajax.php';
        $.post(ajaxurl, data, function(response) {
            $('#message').remove();
            if(response) {
                $('#titlewrap').append('<div id=\"message\" class=\"updated fade\" style=\"position:absolute\;z-index:9999\;width:100%\;padding:0\;\">' + response + '</div>');
            }
        }); 
    };

    // Add button to "Check Titles" below title field in post editor
    // $('#edit-slug-box').append('<span id="check-title-btn"><a class="button" href="#">Check Title</a></span>');

    // Click function to initiate post function
    $('#check-title-btn a').click(function() {
        var title = $('#title').val();
        var id = $('#post_ID').val();
        checkTitle(title, id);
    });

    // Keyup function to initiate post function
    $('#title').keyup(function() {
        var title = $('#title').val();
        var id = $('#post_ID').val();

        if(title.length > 3) {
            setTimeout(function() {
                checkTitle(title, id);
            }, 500);
        } else {
            $('#message').remove();
        }
    });

    $('#title').on('focus', function() {
        var title = $('#title').val();
        var id = $('#post_ID').val();

        if(title.length > 3) {
            setTimeout(function() {
                checkTitle(title, id);
            }, 500);
        } else {
            $('#message').remove();
        }
    });

    $('#title, #message').on('blur', function() {
        setTimeout(function() {
            $('#message').remove();
        }, 500);
    });

});