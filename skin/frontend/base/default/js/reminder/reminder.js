jQuery(function() {

    jQuery('.reminder-form-load-ajax').click(function(event){
        event.preventDefault();

        jQuery.fancybox.showLoading();

        var load_ajax_url = jQuery(this).prop('href');
        jQuery.ajax( {
            type : "GET",
            url:load_ajax_url,
            success : function(msg) {
                jQuery.fancybox({
                    'content' : msg
                });
            }
        });

    });

});

