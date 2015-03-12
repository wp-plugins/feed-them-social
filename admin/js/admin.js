jQuery(document).ready(function() {
    jQuery("#wp-admin-bar-feed_them_social_admin_bar_clear_cache a").click(function() {
		
        console.log('Submit Function');
     
        jQuery.ajax({
            data: {action: "fts_clear_cache_ajax" },
            type: 'POST',
            url: ftsAjax.ajaxurl,
            success: function( response ) { 
			//	jQuery('body').hide();
				console.log('Well Done and got this from sever: ' + response);
				alert('Cache for all FTS Feeds cleared! Refresh page with feed to view update.');
				return false;
			}
        }); // end of ajax()
        return false;
    }); // end of document.ready
}); // end of form.submit