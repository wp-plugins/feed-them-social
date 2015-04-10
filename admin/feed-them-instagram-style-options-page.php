<?php
//Functions for the 
function feed_them_instagram_options_page() {
	$fts_functions = new feed_them_social_functions();
	  $fts_instagram_access_token = get_option('fts_instagram_custom_api_token');
	 	 $fts_instagram_show_follow_btn = get_option('instagram_show_follow_btn');
	 	 $fts_instagram_show_follow_btn_where = get_option('instagram_show_follow_btn_where');
		
?>
<link href='http://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>
<div class="feed-them-social-admin-wrap">
  <h1>
    <?php _e('Instagram Feed Options', 'feed-them-social'); ?>
  </h1>
  <div class="use-of-plugin">
    <?php _e('Add a follow button and position it using the options below.', 'feed-them-social'); ?>
  </div>
  
  
    <br/>
      
      
  <!-- custom option for padding -->
  <form method="post" class="fts-facebook-feed-options-form" action="options.php">
  
   <div class="feed-them-social-admin-input-wrap">
           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Show Follow Button', 'feed-them-social'); ?></div>
    
    <select name="instagram_show_follow_btn" id="instagram-show-follow-btn" class="feed-them-social-admin-input">
		  <option '<?php echo selected($fts_instagram_show_follow_btn, 'no', false ) ?>' value="no"><?php _e('No', 'feed-them-social'); ?></option>
  		  <option '<?php echo selected($fts_instagram_show_follow_btn, 'yes', false ) ?>' value="yes"><?php _e('Yes', 'feed-them-social'); ?></option>
    </select>

      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap">
           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Placement of the Buttons', 'feed-them-social'); ?></div>
    	
    <select name="instagram_show_follow_btn_where" id="instagram-show-follow-btn-where" class="feed-them-social-admin-input">
		  <option ><?php _e('Please Select Option', 'feed-them-social'); ?></option>
		  <option '<?php echo selected($fts_instagram_show_follow_btn_where, 'instagram-follow-above', false ) ?>' value="instagram-follow-above"><?php _e('Show Above Feed', 'feed-them-social'); ?></option>
  		  <option '<?php echo selected($fts_instagram_show_follow_btn_where, 'instagram-follow-below', false ) ?>' value="instagram-follow-below"><?php _e('Show Below Feed', 'feed-them-social'); ?></option>
    </select>

      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
      
      
    <?php // get our registered settings from the fts functions 
	 	   settings_fields('fts-instagram-feed-style-options'); ?>
    <h2>
      <?php _e('Instagram API Token', 'feed-them-social'); ?>
    </h2>
    <?php
       
		 $insta_url = 'https://api.instagram.com/v1/tags/slickremix/media/recent/?access_token='.$fts_instagram_access_token;
		 //Get Data for Instagram
		 $response = wp_remote_fopen($insta_url);
		 //Error Check
		 $test_app_token_response = json_decode($response);
				
// echo '<pre>';
// print_r(json_decode($response));
// echo '</pre>';
	  ?>
     
      
      
      
    <div class="fts-facebook-custom-api-token-label">
      <?php _e('Instagram Access Token (for all Instagram feeds). Not required to make the feed work. An Instagram Access Token however will keep your feed from going down if you are using our default Access Tokens. Read here and see how to <a href="http://www.slickremix.com/docs/how-to-create-instagram-access-token" target="_blank">GET AN ACCESS TOKEN</a>.', 'feed-them-social'); ?>
    </div>
    <div class="feed-them-social-admin-input-wrap" style="margin-bottom:0px;">
      <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
        <?php _e('Access Token optional', 'feed-them-social'); ?>
      </div>
      <input type="text" name="fts_instagram_custom_api_token" class="feed-them-social-admin-input"  id="fts_instagram_custom_api_token" value="<?php echo get_option('fts_instagram_custom_api_token');?>"/>
      <div class="clear"></div>
    </div>
    <?php 
	  // Error Check
	  if(!isset($test_app_token_response->meta->error_message) && !empty($fts_instagram_access_token)){
		  echo'<div class="fts-successful-api-token">'. __('Your access token is working!', 'feed-them-social').'</div>';
	  }
	  elseif(isset($test_app_token_response->meta->error_message) && !empty($fts_instagram_access_token)){
			  echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').' '.$test_app_token_response->meta->error_message.'</div>';
	  }
	 if(empty($fts_instagram_access_token)){
				echo'<div class="fts-successful-api-token">'. __('You are using our Default Access Token.', 'feed-them-social').'</div>';
	 }  
	?>
    <div class="clear"></div>
    <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
  </form>
  <a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a> </div>
<!--/feed-them-social-admin-wrap-->

<?php } ?>