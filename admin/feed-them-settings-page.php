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
		 <div id="discount-for-review"><?php _e('15% off Premium Version', 'feed-them-social'); ?></div>
    <div class="discount-review-text"><a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank"><?php _e('Share here', 'feed-them-social'); ?></a> <?php _e('and receive 15% OFF your total order.', 'feed-them-social'); ?></div>
<?php } ?>
   
  </div>

	<form class="feed-them-social-admin-form"> 
    	<select id="shortcode-form-selector">
        	<option value=""><?php _e('Please Select Feed Type', 'feed-them-social'); ?> </option>
            <option value="fb-page-shortcode-form"><?php _e('Facebook Feed', 'feed-them-social'); ?></option>
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
    
<?php if(isset($_GET['cache']) && $_GET['cache']=='clearcache'){ 
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
   
  <?php // get our registered settings from the fts functions 
	 	   settings_fields('feed-them-social-settings'); ?> 
           
           
  <?php 
  		isset($ftsDateTimeFormat) ? $ftsDateTimeFormat : "";
  		isset($ftsTimezone) ? $ftsTimezone : "";
		
  		$ftsDateTimeFormat = get_option('fts-date-and-time-format');
		$ftsTimezone = get_option('fts-timezone');
		date_default_timezone_set(get_option('fts-timezone'));
   ?>
  <div style="float:left; max-width:400px; margin-right:30px;"><h2><?php _e('FaceBook & Twitter Date Format', 'feed-them-social'); ?></h2>
   <fieldset>
        <select id="fts-date-and-time-format" name="fts-date-and-time-format">
            <option value="l, F jS, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'l, F jS, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date('l, F jS, Y \a\t g:ia'); ?></option>
            <option value="F j, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date('F j, Y \a\t g:ia'); ?></option>
            <option value="F j, Y g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y g:ia' ) echo 'selected="selected"'; ?>><?php echo date('F j, Y g:ia'); ?></option>
            <option value="F, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date('F, Y \a\t g:ia'); ?></option>
            <option value="M j, Y @ g:ia" <?php if ($ftsDateTimeFormat == 'M j, Y @ g:ia' ) echo 'selected="selected"'; ?>><?php echo date('M j, Y @ g:ia'); ?></option>
            <option value="M j, Y @ G:i" <?php if ($ftsDateTimeFormat == 'M j, Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date('M j, Y @ G:i'); ?></option>
            <option value="m/d/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'm/d/Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date('m/d/Y \a\t g:ia'); ?></option>
            <option value="m/d/Y @ G:i" <?php if ($ftsDateTimeFormat == 'm/d/Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date('m/d/Y @ G:i'); ?></option>
            <option value="d/m/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'd/m/Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date('d/m/Y \a\t g:ia'); ?></option>
            <option value="d/m/Y @ G:i" <?php if ($ftsDateTimeFormat == 'd/m/Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date('d/m/Y @ G:i'); ?></option>
            <option value="Y/m/d \a\t g:ia" <?php if ($ftsDateTimeFormat == 'Y/m/d \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date('Y/m/d \a\t g:ia'); ?></option>
            <option value="Y/m/d @ G:i" <?php if ($ftsDateTimeFormat == 'Y/m/d @ G:i' ) echo 'selected="selected"'; ?>><?php echo date('Y/m/d @ G:i'); ?></option>
        </select>
	</fieldset>
</div>
<div style="float:left; max-width:330px"> <h2><?php _e('TimeZone', 'feed-them-social'); ?></h2>
    <fieldset>
        <select id="fts-timezone" name="fts-timezone">
          <option value="Pacific/Midway" <?php if($ftsTimezone == "Pacific/Midway") echo 'selected="selected"' ?> >
          <?php _e('UTC-11:00'); ?>
          </option>
          <option value="Etc/UTC+10" <?php if($ftsTimezone == "Etc/UTC+10") echo 'selected="selected"' ?> >
          <?php _e('UTC-10:00'); ?>
          </option>
          <option value="Pacific/Marquesas" <?php if($ftsTimezone == "Pacific/Marquesas") echo 'selected="selected"' ?> >
          <?php _e('UTC-09:30'); ?>
          </option>
          <option value="Pacific/Gambier" <?php if($ftsTimezone == "Pacific/Gambier") echo 'selected="selected"' ?> >
          <?php _e('UTC-09:00'); ?>
          </option>
          <option value="Etc/UTC+8" <?php if($ftsTimezone == "Etc/UTC+8") echo 'selected="selected"' ?> >
          <?php _e('UTC-08:00'); ?>
          </option>
          <option value="America/Denver" <?php if($ftsTimezone == "America/Denver") echo 'selected="selected"' ?> >
          <?php _e('UTC-07:00'); ?>
          </option>
          <option value="America/Chicago" <?php if($ftsTimezone == "America/Chicago") echo 'selected="selected"' ?> >
          <?php _e('UTC-06:00'); ?>
          </option>
          <option value="America/Havana" <?php if($ftsTimezone == "America/Havana") echo 'selected="selected"' ?> >
          <?php _e('UTC-05:00'); ?>
          </option>
          <option value="America/Caracas" <?php if($ftsTimezone == "America/Caracas") echo 'selected="selected"' ?> >
          <?php _e('UTC-04:30'); ?>
          </option>
          <option value="America/Glace_Bay" <?php if($ftsTimezone == "America/Glace_Bay") echo 'selected="selected"' ?> >
          <?php _e('UTC-04:00'); ?>
          </option>
          <option value="America/St_Johns" <?php if($ftsTimezone == "America/St_Johns") echo 'selected="selected"' ?> >
          <?php _e('UTC-03:30'); ?>
          </option>
          <option value="America/Sao_Paulo" <?php if($ftsTimezone == "America/Sao_Paulo") echo 'selected="selected"' ?> >
          <?php _e('UTC-03:00'); ?>
          </option>
          <option value="America/Noronha" <?php if($ftsTimezone == "America/Noronha") echo 'selected="selected"' ?> >
          <?php _e('UTC-02:00'); ?>
          </option>
          <option value="Atlantic/Cape_Verde" <?php if($ftsTimezone == "Atlantic/Cape_Verde") echo 'selected="selected"' ?> >
          <?php _e('UTC-01:00'); ?>
          </option>
          <option value="Europe/Belfast" <?php if($ftsTimezone == "Europe/Belfast") echo 'selected="selected"' ?> >
          <?php _e('UTC'); ?>
          <option value="Europe/Amsterdam" <?php if($ftsTimezone == "Europe/Amsterdam") echo 'selected="selected"' ?> >
          <?php _e('UTC+01:00'); ?>
          </option>
          <option value="Asia/Beirut" <?php if($ftsTimezone == "Asia/Beirut") echo 'selected="selected"' ?> >
          <?php _e('UTC+02:00'); ?>
          </option>
          <option value="Europe/Moscow" <?php if($ftsTimezone == "Europe/Moscow") echo 'selected="selected"' ?> >
          <?php _e('UTC+03:00'); ?>
          </option>
          <option value="Asia/Tehran" <?php if($ftsTimezone == "Asia/Tehran") echo 'selected="selected"' ?> >
          <?php _e('UTC+03:30'); ?>
          </option>
          <option value="Asia/Yerevan" <?php if($ftsTimezone == "Asia/Yerevan") echo 'selected="selected"' ?> >
          <?php _e('UTC+04:00'); ?>
          </option>
          <option value="Asia/Kabul" <?php if($ftsTimezone == "Asia/Kabul") echo 'selected="selected"' ?> >
          <?php _e('UTC+04:30'); ?>
          </option>
          <option value="Asia/Tashkent" <?php if($ftsTimezone == "Asia/Tashkent") echo 'selected="selected"' ?> >
          <?php _e('UTC+05:00'); ?>
          </option>
          <option value="Asia/Kolkata" <?php if($ftsTimezone == "Asia/Kolkata") echo 'selected="selected"' ?> >
          <?php _e('UTC+05:30'); ?>
          </option>
          <option value="Asia/Katmandu" <?php if($ftsTimezone == "Asia/Katmandu") echo 'selected="selected"' ?> >
          <?php _e('UTC+05:45'); ?>
          </option>
          <option value="Asia/Dhaka" <?php if($ftsTimezone == "Asia/Dhaka") echo 'selected="selected"' ?> >
          <?php _e('UTC+06:00'); ?>
          </option>
          <option value="Asia/Novosibirsk" <?php if($ftsTimezone == "Asia/Novosibirsk") echo 'selected="selected"' ?> >
          <?php _e('UTC+06:00'); ?>
          </option>
          <option value="Asia/Rangoon" <?php if($ftsTimezone == "Asia/Rangoon") echo 'selected="selected"' ?> >
          <?php _e('UTC+06:30'); ?>
          </option>
          <option value="Asia/Bangkok" <?php if($ftsTimezone == "Asia/Bangkok") echo 'selected="selected"' ?> >
          <?php _e('UTC+07:00'); ?>
          </option>
          <option value="Australia/Perth" <?php if($ftsTimezone == "Australia/Perth") echo 'selected="selected"' ?> >
          <?php _e('UTC+08:00'); ?>
          </option>
          <option value="Australia/Eucla" <?php if($ftsTimezone == "Australia/Eucla") echo 'selected="selected"' ?> >
          <?php _e('UTC+08:45'); ?>
          </option>
          <option value="Asia/Tokyo" <?php if($ftsTimezone == "Asia/Tokyo") echo 'selected="selected"' ?> >
          <?php _e('UTC+09:00'); ?>
          </option>
          <option value="Australia/Adelaide" <?php if($ftsTimezone == "Australia/Adelaide") echo 'selected="selected"' ?> >
          <?php _e('UTC+09:30'); ?>
          </option>
          <option value="Australia/Hobart" <?php if($ftsTimezone == "Australia/Hobart") echo 'selected="selected"' ?> >
          <?php _e('UTC+10:00'); ?>
          </option>
          <option value="Australia/Lord_Howe" <?php if($ftsTimezone == "Australia/Lord_Howe") echo 'selected="selected"' ?> >
          <?php _e('UTC+10:30'); ?>
          </option>
          <option value="Etc/UTC-11" <?php if($ftsTimezone == "Etc/UTC-11") echo 'selected="selected"' ?> >
          <?php _e('UTC+11:00'); ?>
          </option>
          <option value="Pacific/Norfolk" <?php if($ftsTimezone == "Pacific/Norfolk") echo 'selected="selected"' ?> >
          <?php _e('UTC+11:30'); ?>
          </option>
          <option value="Asia/Anadyr" <?php if($ftsTimezone == "Asia/Anadyr") echo 'selected="selected"' ?> >
          <?php _e('UTC+12:00'); ?>
          </option>
          <option value="Pacific/Chatham" <?php if($ftsTimezone == "Pacific/Chatham") echo 'selected="selected"' ?> >
          <?php _e('UTC+12:45'); ?>
          </option>
          <option value="Pacific/Tongatapu" <?php if($ftsTimezone == "Pacific/Tongatapu") echo 'selected="selected"' ?> >
          <?php _e('UTC+13:00'); ?>
          </option>
          <option value="Pacific/Kiritimati" <?php if($ftsTimezone == "Pacific/Kiritimati") echo 'selected="selected"' ?> >
          <?php _e('UTC+14:00'); ?>
          </option>
        </select>
    </fieldset>
</div>
<div class="clear"></div>
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
          <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes', 'feed-them-social') ?>" />
      <div class="clear"></div>
    
     
      </div><!--/feed-them-custom-logo-css--> 
 
       </form>
       
     
  	<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
  
</div><!--/feed-them-social-admin-wrap-->
<script>
jQuery(function() {    
	// Master feed selector
    jQuery('#shortcode-form-selector').change(function(){
        jQuery('.shortcode-generator-form').hide();
        jQuery('.' + jQuery(this).val()).fadeIn('fast');
    });
	
	 // change the feed type 'how to' message when a feed type is selected
	jQuery('#facebook-messages-selector').change(function(){
        jQuery('.facebook-message-generator').hide();
        jQuery('.' + jQuery(this).val()).fadeIn('fast');
		
		// if the facebook type select is changed we hide the shortcode code so not to confuse people
		jQuery('.final-shortcode-textarea').hide();
		
		
	// only show the Super Gallery Options if the facebook ablum or album covers feed type is selected	
	 var facebooktype = jQuery("select#facebook-messages-selector").val();
		 if (facebooktype == 'albums' || facebooktype == 'album_photos') {
       		 jQuery('.fts-super-facebook-options-wrap').show();
				jQuery('.fixed_height_option').hide();
				jQuery('.fb-posts-in-grid-option-wrap').hide();
				jQuery('.fixed_height_option').hide();
				jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Display Posts in Grid', 'feed-them-social'); ?>')").parent('div').hide();
 		 }
		 else {
       		 jQuery('.fts-super-facebook-options-wrap').hide();
			 jQuery('.fixed_height_option').show();
			 jQuery('.fb-posts-in-grid-option-wrap').show();
			 jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Display Posts in Grid', 'feed-them-social'); ?>')").parent('div').show();
		 }
		 
		 

		 
		 
		 
		 // only show the post type visible if the facebook page feed type is selected
		 jQuery('.facebook-post-type-visible').hide();
		  if (facebooktype == 'page' ) {
 		 	jQuery('.facebook-post-type-visible').show();
		 }
		 
	var fb_feed_type_option = jQuery("select#facebook-messages-selector").val();  
		if (fb_feed_type_option == 'album_photos') {
				jQuery('.fb_album_photos_id').show();
			}
			else {
				jQuery('.fb_album_photos_id').hide();
			}
    });
	
	// Instagram Super Gallery option
   jQuery('#instagram-custom-gallery').bind('change', function (e) { 
    if( jQuery('#instagram-custom-gallery').val() == 'yes') {
      jQuery('.fts-super-instagram-options-wrap').show();
    }
    else{
      jQuery('.fts-super-instagram-options-wrap').hide();
    }         
  });
  
   jQuery('#instagram-messages-selector').bind('change', function (e) { 
    if( jQuery('#instagram-messages-selector').val() == 'hashtag') {
      jQuery(".instagram-id-option-wrap").hide(); 
      jQuery(".instagram-hashtag-option-text").show(); 
      jQuery(".instagram-user-option-text").hide(); 
	  
    }
	 else{
      jQuery(".instagram-id-option-wrap").show(); 
      jQuery(".instagram-hashtag-option-text").hide(); 
      jQuery(".instagram-user-option-text").show(); 
    } 
   
  });
   // facebook show grid options
  jQuery('#fb-grid-option').bind('change', function (e) { 
    if( jQuery('#fb-grid-option').val() == 'yes') {
      jQuery('.fts-facebook-grid-options-wrap').show();
	  jQuery(".feed-them-social-admin-input-label:contains('<?php _e('Center Facebook Container?', 'feed-them-social'); ?>')").parent('div').show();
    }
    else{
      jQuery('.fts-facebook-grid-options-wrap').hide();
    }         
  });
  // facebook Super Gallery option
  jQuery('#facebook-custom-gallery').bind('change', function (e) { 
    if( jQuery('#facebook-custom-gallery').val() == 'yes') {
      jQuery('.fts-super-facebook-options-wrap').show();
    }
    else{
      jQuery('.fts-super-facebook-options-wrap').hide();
    }         
  });
   // facebook show load more options
  jQuery('#fb_load_more_option').bind('change', function (e) { 
    if( jQuery('#fb_load_more_option').val() == 'yes') {
      jQuery('.fts-facebook-load-more-options-wrap').show();
    }
    else{
      jQuery('.fts-facebook-load-more-options-wrap').hide();
    }         
  });
});
//START Page JS/
function updateTextArea_fb_page() {
	var fb_feed_type = ' type=' + jQuery("select#facebook-messages-selector").val();
	var fb_page_id = ' id=' + jQuery("input#fb_page_id").val(); 
	var fb_album_id = ' album_id=' + jQuery("input#fb_album_id").val(); 
	var fb_page_posts_displayed = ' posts_displayed=' + jQuery("select#fb_page_posts_displayed").val();
	var facebook_height = jQuery("input#facebook_page_height").val();
	
	// var super_gallery = ' super_gallery=' + jQuery("select#facebook-custom-gallery").val();
	var image_width = ' image_width=' + jQuery("input#fts-slicker-facebook-container-image-width").val();  
	var image_height = ' image_height=' + jQuery("input#fts-slicker-facebook-container-image-height").val();  
	var space_between_photos = ' space_between_photos=' + jQuery("input#fts-slicker-facebook-container-margin").val();  
	var hide_date_likes_comments = ' hide_date_likes_comments=' + jQuery("select#fts-slicker-facebook-container-hide-date-likes-comments").val();  
	var center_container = ' center_container=' + jQuery("select#fts-slicker-facebook-container-position").val();  
	var image_stack_animation = ' image_stack_animation=' + jQuery("select#fts-slicker-facebook-container-animation").val();  
	var position_lr = ' image_position_lr=' + jQuery("input#fts-slicker-facebook-image-position-lr").val();  
	var position_top = ' image_position_top=' + jQuery("input#fts-slicker-facebook-image-position-top").val();  
	
	if (fb_page_id == " id=") {
	  	 jQuery(".fb_page_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_page_id").focus();
		 return false;
	}
	if (fb_page_id != " id=") {
	  	 jQuery(".fb_page_id").removeClass('fts-empty-error');  
	}
	
	if (fb_album_id == " album_id=" && fb_feed_type == " type=album_photos") {
	  	 jQuery(".fb_album_photos_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_album_id").focus();
		 return false;
	}
	if (fb_album_id != " album_id=") {
	  	 jQuery(".fb_album_photos_id").removeClass('fts-empty-error');  
	}
	
	
	
	
	if (facebook_height)	{
		var facebook_height_final = ' height=' + jQuery("input#facebook_page_height").val();
	}
	else {
		var facebook_height_final = '';
	}
	
	
	var super_gallery_option = jQuery("select#facebook-custom-gallery").val();
		var albums_photos_option = jQuery("select#facebook-messages-selector").val();
	<?php 
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/facebook-page-settings-js.js');
	}
	else 	{ ?>
				
				if (albums_photos_option == "album_photos") {
	  				var final_fb_page_shortcode = '[fts facebook' + fb_page_id + fb_album_id + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top +']';
				}
				else if (albums_photos_option == "albums") {
	  				var final_fb_page_shortcode = '[fts facebook' + fb_page_id + fb_feed_type + image_width + image_height + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + position_lr + position_top +']';
				}
				else if (albums_photos_option == "page") {
					var final_fb_page_shortcode = '[fts facebook' + fb_page_id + fb_page_posts_displayed + facebook_height_final + fb_feed_type + ']';
				}
				else {
					var final_fb_page_shortcode = '[fts facebook' + fb_page_id + facebook_height_final + fb_feed_type + ']';
				} 
<?php } ?>

	jQuery('.facebook-page-final-shortcode').val(final_fb_page_shortcode);
	
	jQuery('.fb-page-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Page//

//START Facebook Group//
function updateTextArea_fb_group() {

	var fb_group_id = ' id=' + jQuery("input#fb_group_id").val(); 
	// var fb_group_custom_name = ' custom_name=' + jQuery("select#fb_group_custom_name").val();
	var facebook_height = jQuery("input#facebook_group_height").val();
	
	
	if (fb_group_id == " id=") {
	  	 jQuery(".fb_group_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_group_id").focus();
		 return false;
	}
	if (fb_group_id != " id=") {
	  	 jQuery(".fb_group_id").removeClass('fts-empty-error');  
	}
	
	if (facebook_height)	{
		var facebook_height_final = ' height=' + jQuery("input#facebook_group_height").val();
	}
	else {
		var facebook_height_final = '';
	}
	<?php 
	
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/facebook-group-settings-js.js');
	}
	else 	{
	?>
	var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + facebook_height_final + ' type=group]';
<?php } ?>

jQuery('.facebook-group-final-shortcode').val(final_fb_group_shorcode);
	
	jQuery('.fb-group-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Group//

//START Facebook Event//
function updateTextArea_fb_event() {

	var fb_event_id = ' id=' + jQuery("input#fb_event_id").val(); 
	var facebook_height = jQuery("input#facebook_event_height").val();
	
	if (fb_event_id == " id=") {
	  	 jQuery(".fb_event_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_event_id").focus();
		 return false;
	}
	if (fb_event_id != " id=") {
	  	 jQuery(".fb_event_id").removeClass('fts-empty-error');  
	}
	
	if (facebook_height)	{
		var facebook_height_final = ' height=' + jQuery("input#facebook_event_height").val();
	}
	else {
		var facebook_height_final = '';
	}
	<?php 
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/facebook-event-settings-js.js');
	}
	else 	{
	?>
		var final_fb_event_shorcode = '[fts facebook event' + fb_event_id + facebook_height_final + ' type=event]';
		
<?php } ?>

jQuery('.facebook-event-final-shortcode').val(final_fb_event_shorcode);
	
	jQuery('.fb-event-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Event//

//START Twitter//
function updateTextArea_twitter() {

	var twitter_name = ' twitter_name=' + jQuery("input#twitter_name").val();
	var twitter_height = jQuery("input#twitter_height").val();
	
	if (twitter_name == " twitter_name=") {
	  	 jQuery(".twitter_name").addClass('fts-empty-error');  
      	 jQuery("input#twitter_name").focus();
		 return false;
	}
	if (twitter_name != " twitter_name=") {
	  	 jQuery(".twitter_name").removeClass('fts-empty-error');  
	}
	
	if (twitter_height)	{
		var twitter_height_final = ' twitter_height=' + jQuery("input#twitter_height").val();
	}
	else {
		var twitter_height_final = ''; 
	}
	<?php
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/twitter-settings-js.js');
	}
	else 	{ ?>
			var final_twitter_shorcode = '[fts twitter' + twitter_name + twitter_height_final + ']';
<?php } ?>

jQuery('.twitter-final-shortcode').val(final_twitter_shorcode);
	
	jQuery('.twitter-shortcode-form .final-shortcode-textarea').slideDown();
}
//END Twitter//
	
//START Instagram//
function updateTextArea_instagram() {
	
	var instagram_id = ' instagram_id=' + jQuery("input#instagram_id").val();
	var super_gallery = ' super_gallery=' + jQuery("select#instagram-custom-gallery").val();
	var image_size = ' image_size=' + jQuery("input#fts-slicker-instagram-container-image-size").val();  
	var icon_size = ' icon_size=' + jQuery("input#fts-slicker-instagram-icon-center").val();  
	var space_between_photos = ' space_between_photos=' + jQuery("input#fts-slicker-instagram-container-margin").val();  
	var hide_date_likes_comments = ' hide_date_likes_comments=' + jQuery("select#fts-slicker-instagram-container-hide-date-likes-comments").val();  
	var center_container = ' center_container=' + jQuery("select#fts-slicker-instagram-container-position").val();  
	var image_stack_animation = ' image_stack_animation=' + jQuery("select#fts-slicker-instagram-container-animation").val();  
	var instagram_feed_type = ' type=' + jQuery("select#instagram-messages-selector").val();
	var instagram_popup_option = ' popup=' + jQuery("select#instagram-popup-option").val();
	 
	if (instagram_id == " instagram_id=") {
	  	 jQuery(".instagram_name").addClass('fts-empty-error');  
      	 jQuery("input#instagram_id").focus();
		 return false;
	}
	if (instagram_id != " instagram_id=") {
	  	 jQuery(".instagram_name").removeClass('fts-empty-error');  
	}
	
<?php 
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/admin/js/instagram-settings-js.js');
	   
	}//end if Premium version
	else 	{ ?>
	
			if (jQuery("select#instagram-custom-gallery").val() == "no") {
	  			var final_instagram_shorcode = '[fts instagram' + instagram_id + instagram_feed_type +']';
			}
			else {
				var final_instagram_shorcode = '[fts instagram' + instagram_id + super_gallery + image_size + icon_size + space_between_photos + hide_date_likes_comments + center_container + image_stack_animation + instagram_feed_type +']';
			}
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