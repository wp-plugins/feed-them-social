<?php
namespace feedthemsocial;
class FTS_pinterest_options_page {
	function __construct() {
	}
	//**************************************************
	// Pinterest Options Page
	//**************************************************
function feed_them_pinterest_options_page() {
	$fts_functions = new feed_them_social_functions();
		$fts_pinterest_access_token = get_option('fts_pinterest_custom_api_token');
		$fts_pinterest_show_follow_btn = get_option('pinterest_show_follow_btn');
		$fts_pinterest_show_follow_btn_where = get_option('pinterest_show_follow_btn_where');
		
?>
	<div class="feed-them-social-admin-wrap">
	  <h1>
	    <?php _e('Pinterest Feed Options', 'feed-them-social'); ?>
	  </h1>
	  <div class="use-of-plugin">
	    <?php _e('Add a follow button and position it using the options below.', 'feed-them-social'); ?>
	  </div>
	  
	  
	    <br/>
	      
	      
	  <!-- custom option for padding -->
	  <form method="post" class="fts-pinterest-feed-options-form" action="options.php">
	  
	    	 	<?php settings_fields('fts-pinterest-feed-style-options'); ?>
	
	   <div class="feed-them-social-admin-input-wrap">
	           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Show Follow Button', 'feed-them-social'); ?></div>
	    
	    <select name="pinterest_show_follow_btn" id="pinterest-show-follow-btn" class="feed-them-social-admin-input">
			  <option '<?php echo selected($fts_pinterest_show_follow_btn, 'no', false ) ?>' value="no"><?php _e('No', 'feed-them-social'); ?></option>
	  		  <option '<?php echo selected($fts_pinterest_show_follow_btn, 'yes', false ) ?>' value="yes"><?php _e('Yes', 'feed-them-social'); ?></option>
	    </select>
	
	      <div class="clear"></div>
	 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
	      
	      
	      <div class="feed-them-social-admin-input-wrap">
	           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Placement of the Buttons', 'feed-them-social'); ?></div>
	    	
	    <select name="pinterest_show_follow_btn_where" id="pinterest-show-follow-btn-where" class="feed-them-social-admin-input">
			  <option ><?php _e('Please Select Option', 'feed-them-social'); ?></option>
			  <option '<?php echo selected($fts_pinterest_show_follow_btn_where, 'pinterest-follow-above', false ) ?>' value="pinterest-follow-above"><?php _e('Show Above Feed', 'feed-them-social'); ?></option>
	  		  <option '<?php echo selected($fts_pinterest_show_follow_btn_where, 'pinterest-follow-below', false ) ?>' value="pinterest-follow-below"><?php _e('Show Below Feed', 'feed-them-social'); ?></option>
	    </select>
	
	      <div class="clear"></div>
	 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
	      
	     
	    <div class="clear"></div>
	    <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
	  </form>
	  <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a> </div>
	<!--/feed-them-social-admin-wrap-->

<?php } 
}//END Class