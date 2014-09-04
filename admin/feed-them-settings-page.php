<?php

//Main setting page function
function feed_them_settings_page() {

$fts_functions = new feed_them_social_functions();

?>
<link href='http://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>				
<div class="feed-them-social-admin-wrap">
  <h1><?php _e('Feed Them Social', 'feed-them-social'); ?></h1>
  <div class="use-of-plugin"><?php _e('Select what type of feed you would like to generate a shortcode for using the select option below. Then you\'ll copy that shortcode to a page or post.', 'feed-them-social'); ?></div>
  
  <div class="feed-them-icon-wrap">
    <a href="#" class="youtube-icon"></a>
    <a href="#" class="twitter-icon"></a>
    <a href="#" class="facebook-icon"></a>
  	<a href="#" class="instagram-icon"></a>
  	<a href="#" class="pinterest-icon"></a>
    
    <?php
	//show the js for the discount option under social icons on the settings page
	if(!is_plugin_active('feed-them-premium/feed-them-premium.php')) { ?>
		 <div id="discount-for-review"><?php _e('20% off Premium Version', 'feed-them-social'); ?></div>
    <div class="discount-review-text"><a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank"><?php _e('Share here', 'feed-them-social'); ?></a> <?php _e('and receive 20% OFF your total order.', 'feed-them-social'); ?></div>
<?php } ?>
   
  </div>

	<form class="feed-them-social-admin-form"> 
    	<select id="shortcode-form-selector">
        	<option value=""><?php _e('Please Select Feed Type', 'feed-them-social'); ?> </option>
            <option value="fb-page-shortcode-form"><?php _e('Facebook Page Feed', 'feed-them-social'); ?></option>
        	<option value="fb-group-shortcode-form"><?php _e('Facebook Group Feed', 'feed-them-social'); ?></option>
        	<option value="fb-event-shortcode-form"><?php _e('Facebook Event Feed', 'feed-them-social'); ?></option>
            <option value="twitter-shortcode-form"><?php _e('Twitter Feed', 'feed-them-social'); ?></option>
            <option value="instagram-shortcode-form"><?php _e('Instagram Feed', 'feed-them-social'); ?></option>
            <option value="youtube-shortcode-form"><?php _e('YouTube Feed'); ?></option>
            <option value="pinterest-shortcode-form"><?php _e('Pinterest Feed', 'feed-them-social'); ?></option>
        </select>
    </form><!--/feed-them-social-admin-form-->



	 <?php
     //Add Facebook Event Form
     echo $fts_functions->fts_facebook_group_form(false); 
	 
	 //Add Facebook Page Form
     echo $fts_functions->fts_facebook_page_form(false); 
    
	 //Add Facebook Event Form
	 echo $fts_functions->fts_facebook_event_form(false); 
	
	 //Add Twitter Form
	 echo $fts_functions->fts_twitter_form(false); 
	 
	 //Add Instagram Form
	 echo $fts_functions->fts_instagram_form(false);
	
	 //Add Youtube Form
	 echo $fts_functions->fts_youtube_form(false);
	 
	 //Add Pinterest Form
	 echo $fts_functions->fts_pinterest_form(false);
	 ?>
	
<div class="clear"></div>
 <div class="feed-them-clear-cache">
 <h2><?php _e('Clear All Cache Option', 'feed-them-social'); ?></h2>
    <div class="use-of-plugin"><?php _e('Please Clear Cache if you have changed a FTS Shortcode. This will Allow you to see the NEW feed\'s options you just set!', 'feed-them-social'); ?></div>
    
<?php if($_GET['cache']=='clearcache'){ 
 	echo '<div class="feed-them-clear-cache-text">'.$fts_functions->feed_them_clear_cache().'</div>';
}
?>

    <form method="post" action="?page=feed-them-settings-page&cache=clearcache">
      <input class="feed-them-social-admin-submit-btn" type="submit" value="<?php _e('Clear All FTS Feeds Cache', 'feed-them-social'); ?>" />	
    </form>
  </div><!--/feed-them-clear-cache-->
  
  
  <!-- custom option for padding -->
  <form method="post" class="fts-color-settings-admin-form" action="options.php">
  
   <div class="feed-them-custom-css">
   
   
  
  <?php // get our registered settings from the gq theme functions 
	 	   settings_fields('feed-them-social-settings'); ?> 
           
           
  <?php 
  		$ftsDateTimeFormat = get_option('fts-date-and-time-format');
   ?>
  <h2><?php _e('FaceBook & Twitter Date Format', 'feed-them-social'); ?></h2>
   <fieldset>
        <select id="fts-date-and-time-format" name="fts-date-and-time-format">
            <option value="l, F jS, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'l, F jS, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('l, F jS, Y \a\t g:ia'); ?></option>
            <option value="F j, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('F j, Y \a\t g:ia'); ?></option>
            <option value="F j, Y g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('F j, Y g:ia'); ?></option>
            <option value="F, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('F, Y \a\t g:ia'); ?></option>
            <option value="M j, Y @ g:ia" <?php if ($ftsDateTimeFormat == 'M j, Y @ g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('M j, Y @ g:ia'); ?></option>
            <option value="M j, Y @ G:i" <?php if ($ftsDateTimeFormat == 'M j, Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('M j, Y @ G:i'); ?></option>
            <option value="m/d/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'm/d/Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('m/d/Y \a\t g:ia'); ?></option>
            <option value="m/d/Y @ G:i" <?php if ($ftsDateTimeFormat == 'm/d/Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('m/d/Y @ G:i'); ?></option>
            <option value="d/m/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'd/m/Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('d/m/Y \a\t g:ia'); ?></option>
            <option value="d/m/Y @ G:i" <?php if ($ftsDateTimeFormat == 'd/m/Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('d/m/Y @ G:i'); ?></option>
            <option value="Y/m/d \a\t g:ia" <?php if ($ftsDateTimeFormat == 'Y/m/d \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('Y/m/d \a\t g:ia'); ?></option>
            <option value="Y/m/d @ G:i" <?php if ($ftsDateTimeFormat == 'Y/m/d @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('Y/m/d @ G:i'); ?></option>
        </select>
	</fieldset>
<br/>

   <h2><?php _e('Custom CSS Option', 'feed-them-social'); ?></h2>
     
  
     <p>
        <input name="fts-color-options-settings-custom-css" class="fts-color-settings-admin-input" type="checkbox"  id="fts-color-options-settings-custom-css" value="1" <?php echo checked( '1', get_option( 'fts-color-options-settings-custom-css' ) ); ?>/>
        <?php  
                        if (get_option( 'fts-color-options-settings-custom-css' ) == '1') { ?>
                           <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('Custom CSS option is being used now.', 'feed-them-social'); ?> <?php
                        }
                        else	{ ?>
                          <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong> <?php _e('You are using the default CSS.', 'feed-them-social'); ?> <?php
                        }
                           ?>
       </p>
     
         <label class="toggle-custom-textarea-show"><span><?php _e('Show', 'feed-them-social'); ?></span><span class="toggle-custom-textarea-hide"><?php _e('Hide', 'feed-them-social'); ?></span> <?php _e('custom CSS', 'feed-them-social'); ?></label>
        
          <div class="clear"></div>
       <div class="fts-custom-css-text"><?php _e('Thanks for using our plugin :) Add your custom CSS additions or overrides below.', 'feed-them-social'); ?></div>
      <textarea name="fts-color-options-main-wrapper-css-input" class="fts-color-settings-admin-input" id="fts-color-options-main-wrapper-css-input"><?php echo get_option('fts-color-options-main-wrapper-css-input'); ?></textarea>
    
      
      </div><!--/feed-them-custom-css--> 
      
 
 
 
 
    <div class="feed-them-custom-logo-css">
   <h2><?php _e('Powered by Text', 'feed-them-social'); ?></h2>
     
    
  
     <p>
        <input name="fts-powered-text-options-settings" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts-powered-text-options-settings" value="1" <?php echo checked( '1', get_option( 'fts-powered-text-options-settings' ) ); ?>/>
        <?php  
                        if (get_option( 'fts-powered-text-options-settings' ) == '1') { ?>
                           <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('You are not showing the Powered by Logo.', 'feed-them-social'); ?> <?php
                        }
                        else	{ ?>
                          <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong><?php _e('The Powered by text will appear in the site. Awesome! Thanks so much for sharing.', 'feed-them-social'); ?> <?php
                        }
                           ?>
      </p>
     <br/>
          <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save Changes') ?>" />
      <div class="clear"></div>
    
     
      </div><!--/feed-them-custom-logo-css--> 
 
       </form>
  	<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
  
</div><!--/feed-them-social-admin-wrap-->

<script>
jQuery(function() {    

    jQuery('#shortcode-form-selector').change(function(){
        jQuery('.shortcode-generator-form').hide();
        jQuery('.' + jQuery(this).val()).fadeIn('fast');
    });
});


//START Facebook Group//
function updateTextArea_fb_group() {

	var fb_group_id = ' id=' + jQuery("input#fb_group_id").val(); 
	// var fb_group_custom_name = ' custom_name=' + jQuery("select#fb_group_custom_name").val();
	
	if (fb_group_id == " id=") {
	  	 jQuery(".fb_group_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_group_id").focus();
		 return false;
	}
	if (fb_group_id != " id=") {
	  	 jQuery(".fb_group_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/facebook-group-settings-js.js');
	}
	else 	{
	?>
		
		//	if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//					var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + ' type=group' + final_fts_rotate_shortcode + ']';
//			}
//			else	{
					// var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + fb_group_custom_name + ' type=group]';
					var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + ' type=group]';
			//}	
	
<?php } ?>

jQuery('.facebook-group-final-shortcode').val(final_fb_group_shorcode);
	
	jQuery('.fb-group-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Group//

//START Page Group//
function updateTextArea_fb_page() {

	var fb_page_id = ' id=' + jQuery("input#fb_page_id").val(); 
	var fb_page_posts_displayed = ' posts_displayed=' + jQuery("select#fb_page_posts_displayed").val();

	
	if (fb_page_id == " id=") {
	  	 jQuery(".fb_page_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_page_id").focus();
		 return false;
	}
	if (fb_page_id != " id=") {
	  	 jQuery(".fb_page_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/facebook-page-settings-js.js');
	}
	else 	{ ?>
	
		//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//				var final_fb_page_shorcode = '[fts facebook page' + fb_page_id + ' type=page' + final_fts_rotate_shortcode + ']';
//			}
//		else	{
				var final_fb_page_shorcode = '[fts facebook page' + fb_page_id + fb_page_posts_displayed + ' type=page]';
	//	}
		
<?php } ?>

jQuery('.facebook-page-final-shortcode').val(final_fb_page_shorcode);
	
	jQuery('.fb-page-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Page//

//START Facebook Event//
function updateTextArea_fb_event() {

	var fb_event_id = ' id=' + jQuery("input#fb_event_id").val(); 
	
	if (fb_event_id == " id=") {
	  	 jQuery(".fb_event_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_event_id").focus();
		 return false;
	}
	if (fb_event_id != " id=") {
	  	 jQuery(".fb_event_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/facebook-event-settings-js.js');
	}
	else 	{
	?>
		//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//				var final_fb_event_shorcode = '[fts facebook event' + fb_page_id + ' type=event' + final_fts_rotate_shortcode + ']';
//			}
//		else	{
				var final_fb_event_shorcode = '[fts facebook event' + fb_event_id + ' type=event]';
	//	}		
		
<?php } ?>

jQuery('.facebook-event-final-shortcode').val(final_fb_event_shorcode);
	
	jQuery('.fb-event-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Event//

//START Twitter//
function updateTextArea_twitter() {

	var twitter_name = ' twitter_name=' + jQuery("input#twitter_name").val();
	
	
	
	if (twitter_name == " twitter_name=") {
	  	 jQuery(".twitter_name").addClass('fts-empty-error');  
      	 jQuery("input#twitter_name").focus();
		 return false;
		 
	}
	if (twitter_name != " twitter_name=") {
	  	 jQuery(".twitter_name").removeClass('fts-empty-error');  
	}
	
	<?php
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/twitter-settings-js.js');
	}
	
	else 	{ ?>
	
	//	if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//			var final_twitter_shorcode = '[fts twitter' + twitter_name + final_fts_rotate_shortcode + ']';
//		}
//		else	{
			var final_twitter_shorcode = '[fts twitter' + twitter_name + ']';
		//}		
	
<?php } ?>

jQuery('.twitter-final-shortcode').val(final_twitter_shorcode);
	
	jQuery('.twitter-shortcode-form .final-shortcode-textarea').slideDown();
}
//END Twitter//

//START Instagram//
function updateTextArea_instagram() {

	var instagram_id = ' instagram_id=' + jQuery("input#instagram_id").val(); 
	
	if (instagram_id == " instagram_id=") {
	  	 jQuery(".instagram_id").addClass('fts-empty-error');  
      	 jQuery("input#instagram_id").focus();
		 return false;
	}
	if (instagram_id != " instagram_id=") {
	  	 jQuery(".instagram_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/instagram-settings-js.js');
	}//end if Premium version
	else 	{ ?>
		//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//				var final_instagram_shorcode = '[fts instagram' + instagram_id + final_fts_rotate_shortcode +']'
//		}
//		else	{
				var final_instagram_shorcode = '[fts instagram' + instagram_id + ']';
		//}		
		
<?php } ?>

jQuery('.instagram-final-shortcode').val(final_instagram_shorcode);
	
	jQuery('.instagram-shortcode-form .final-shortcode-textarea').slideDown();
}
//END Instagram//

//START convert Instagram name to id//
function converter_instagram_username() {
	
	var convert_instagram_username = jQuery("input#convert_instagram_username").val(); 
	
	if (convert_instagram_username == "") {
	  	 jQuery(".convert_instagram_username").addClass('fts-empty-error');  
      	 jQuery("input#convert_instagram_username").focus();
		 return false;
	}
	if (convert_instagram_username  != "") {
	  	 jQuery(".convert_instagram_username").removeClass('fts-empty-error');
		 
			var username = jQuery("input#convert_instagram_username").val();
			
			console.log(username);
			jQuery.getJSON("https://api.instagram.com/v1/users/search?q="+username+"&access_token=267791236.f78cc02.bea846f3144a40acbf0e56b002c112f8&callback=?",
			  {
				format: "json"
			  },
			  function(data) {
					console.log(data);
					var final_instagram_us_id = data.data[0].id;
					
					jQuery('#instagram_id').val(final_instagram_us_id);
					
					jQuery('.final-instagram-user-id-textarea').slideDown();
   			 });
	}
}

//select all 
jQuery(".copyme").focus(function() {
    var jQuerythis = jQuery(this);
    jQuerythis.select();

    // Work around Chrome's little problem
    jQuerythis.mouseup(function() {
        // Prevent further mouseup intervention
        jQuerythis.unbind("mouseup");
        return false;
    });
});


jQuery( document ).ready(function() {
  jQuery( ".toggle-custom-textarea-show" ).click(function() {  
		 jQuery('textarea#fts-color-options-main-wrapper-css-input').slideToggle();
		  jQuery('.toggle-custom-textarea-show span').toggle();
		  jQuery('.fts-custom-css-text').toggle();
		  
}); 


<?php
	//show the js for the discount option under social icons on the settings page
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				// do not show the js below
		}
	else { ?>
		jQuery( "#discount-for-review" ).click(function() {  
			 jQuery('.discount-review-text').slideToggle();
		});
<?php } ?>
  }); //end document ready
  

</script>
<?php
	//Premium JS
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/premium-js.php'); 
		}
	}
?>