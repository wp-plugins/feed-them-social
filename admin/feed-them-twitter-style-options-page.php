<?php
namespace feedthemsocial;
class FTS_twitter_options_page {
	function __construct() {
	}
	//**************************************************
	// Twitter Options Page
	//**************************************************
	function feed_them_twitter_options_page() {
		$fts_functions = new feed_them_social_functions();
	?>
		<div class="feed-them-social-admin-wrap">
		<h1>
		<?php _e('Twitter Feed Options', 'feed-them-social'); ?>
		</h1>
		<div class="use-of-plugin">
		<?php _e('Change the color of your twitter feed and more using the options below.', 'feed-them-social'); ?>
		</div>
		<!-- custom option for padding -->
		<form method="post" class="fts-twitter-feed-options-form" action="options.php">
		<br/>
		<?php // get our registered settings from the fts functions 
			 	   settings_fields('fts-twitter-feed-style-options'); 
				   
			 $twitter_full_width = get_option('twitter_full_width');
			 $twitter_allow_videos = get_option('twitter_allow_videos');
			 $twitter_allow_shortlink_conversion = get_option('twitter_allow_shortlink_conversion');
			 $twitter_show_follow_btn = get_option('twitter_show_follow_btn');
			 $twitter_show_follow_count = get_option('twitter_show_follow_count');
			 $twitter_show_follow_btn_where = get_option('twitter_show_follow_btn_where');
			 
			 ?>
		<div class="feed-them-social-admin-input-wrap">
		<div class="fts-title-description-settings-page" style="border:none; padding-top:0px">
		<h3>
		<?php _e('Follow Button Options', 'feed-them-social'); ?>
		</h3>
		</div>
		<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
		<?php _e('Show Follow Count', 'feed-them-social'); ?>
		</div>
		<select name="twitter_show_follow_count" id="twitter-show-follow-count" class="feed-them-social-admin-input">
		<option '<?php echo selected($twitter_show_follow_count, 'no', false ) ?>' value="no">
		<?php _e('No', 'feed-them-social'); ?>
		</option>
		<option '<?php echo selected($twitter_show_follow_count, 'yes', false ) ?>' value="yes">
		<?php _e('Yes', 'feed-them-social'); ?>
		</option>
		</select>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
		<?php _e('Show Follow Button', 'feed-them-social'); ?>
		</div>
		<select name="twitter_show_follow_btn" id="twitter-show-follow-btn" class="feed-them-social-admin-input">
		<option '<?php echo selected($twitter_show_follow_btn, 'no', false ) ?>' value="no">
		<?php _e('No', 'feed-them-social'); ?>
		</option>
		<option '<?php echo selected($twitter_show_follow_btn, 'yes', false ) ?>' value="yes">
		<?php _e('Yes', 'feed-them-social'); ?>
		</option>
		</select>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
		<?php _e('Placement of Follow Button', 'feed-them-social'); ?>
		</div>
		<select name="twitter_show_follow_btn_where" id="twitter-show-follow-btn-where" class="feed-them-social-admin-input">
		<option >
		<?php _e('Please Select Option', 'feed-them-social'); ?>
		</option>
		<option '<?php echo selected($twitter_show_follow_btn_where, 'twitter-follow-above', false ) ?>' value="twitter-follow-above">
		<?php _e('Show Above Feed', 'feed-them-social'); ?>
		</option>
		<option '<?php echo selected($twitter_show_follow_btn_where, 'twitter-follow-below', false ) ?>' value="twitter-follow-below">
		<?php _e('Show Below Feed', 'feed-them-social'); ?>
		</option>
		</select>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="fts-title-description-settings-page">
		<h3>
		<?php _e('Video Player Options', 'feed-them-social'); ?>
		</h3>
		</div>
		<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
		<?php _e('Show Media button for videos<br/><small>Video shows upon clicking</small>', 'feed-them-social'); ?>
		</div>
		<select name="twitter_allow_videos" id="twitter-allow-videos" class="feed-them-social-admin-input">
		<option '<?php echo selected($twitter_allow_videos, 'no', false ) ?>' value="no">
		<?php _e('No', 'feed-them-social'); ?>
		</option>
		<option '<?php echo selected($twitter_allow_videos, 'yes', false ) ?>' value="yes">
		<?php _e('Yes', 'feed-them-social'); ?>
		</option>
		</select>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
		<?php _e('Convert shortlinks for video<br/><small>Like bitly etc. May slow load time slightly</small>', 'feed-them-social'); ?>
		</div>
		<select name="twitter_allow_shortlink_conversion" id="twitter-allow-shortlink-conversion" class="feed-them-social-admin-input">
		<option '<?php echo selected($twitter_allow_shortlink_conversion, 'no', false ) ?>' value="no">
		<?php _e('No', 'feed-them-social'); ?>
		</option>
		<option '<?php echo selected($twitter_allow_shortlink_conversion, 'yes', false ) ?>' value="yes">
		<?php _e('Yes', 'feed-them-social'); ?>
		</option>
		</select>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="fts-title-description-settings-page">
		<h3>
		<?php _e('Profile Photo Option', 'feed-them-social'); ?>
		</h3>
		</div>
		<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
		<?php _e('Hide Profile Photo', 'feed-them-social'); ?>
		</div>
		<select name="twitter_full_width" id="twitter-full-width" class="feed-them-social-admin-input">
		<option '<?php echo selected($twitter_full_width, 'no', false ) ?>' value="no">
		<?php _e('No', 'feed-them-social'); ?>
		</option>
		<option '<?php echo selected($twitter_full_width, 'yes', false ) ?>' value="yes">
		<?php _e('Yes', 'feed-them-social'); ?>
		</option>
		</select>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="fts-title-description-settings-page">
		<h3>
		<?php _e('Style Options', 'feed-them-social'); ?>
		</h3>
		</div>
		<div class="feed-them-social-admin-input-label fts-twitter-text-color-label">
		<?php _e('Feed Text Color', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_text_color" class="feed-them-social-admin-input twitter-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-text-color-input" placeholder="#222" value="<?php echo get_option('twitter_text_color');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-link-color-label">
		<?php _e('Feed Link Color', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_link_color" class="feed-them-social-admin-input twitter-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-link-color-input" placeholder="#222" value="<?php echo get_option('twitter_link_color');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-link-color-hover-label">
		<?php _e('Feed Link Color Hover', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_link_color_hover" class="feed-them-social-admin-input twitter-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-link-color-hover-input" placeholder="#ddd" value="<?php echo get_option('twitter_link_color_hover');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-feed-width-label">
		<?php _e('Feed Width', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_feed_width" class="feed-them-social-admin-input twitter-feed-width-input"  id="twitter-feed-width-input" placeholder="500px" value="<?php echo get_option('twitter_feed_width');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-feed-margin-label">
		<?php _e('Feed Margin <br/><small>To center feed type auto</small>', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_feed_margin" class="feed-them-social-admin-input twitter-feed-margin-input"  id="twitter-feed-margin-input" placeholder="10px" value="<?php echo get_option('twitter_feed_margin');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-feed-padding-label">
		<?php _e('Feed Padding', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_feed_padding" class="feed-them-social-admin-input twitter-feed-padding-input"  id="twitter-feed-padding-input" placeholder="10px" value="<?php echo get_option('twitter_feed_padding');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-feed-background-color-label">
		<?php _e('Feed Background Color', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_feed_background_color" class="feed-them-social-admin-input twitter-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-feed-background-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_feed_background_color');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
		<?php _e('Feed Border Bottom Color', 'feed-them-social'); ?>
		</div>
		<input type="text" name="twitter_border_bottom_color" class="feed-them-social-admin-input twitter-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_border_bottom_color');?>"/>
		<div class="clear"></div>
		</div>
		<!--/fts-twitter-feed-styles-input-wrap-->
		
		<?php
		   $test_fts_twitter_custom_consumer_key = get_option('fts_twitter_custom_consumer_key');
				 $test_fts_twitter_custom_consumer_secret = get_option('fts_twitter_custom_consumer_secret');
				 $test_fts_twitter_custom_access_token = get_option('fts_twitter_custom_access_token');
				 $test_fts_twitter_custom_access_token_secret = get_option('fts_twitter_custom_access_token_secret');
				 
				 if (isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page'){
				  
				  include(WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/twitter/twitteroauth/twitteroauth.php');
				   
				   $test_connection = new TwitterOAuthFTS(
					//Consumer Key
					$test_fts_twitter_custom_consumer_key,
					//Consumer Secret
					$test_fts_twitter_custom_consumer_secret,
					//Access Token
					$test_fts_twitter_custom_access_token,  
					//Access Token Secret
					$test_fts_twitter_custom_access_token_secret
					);
					
					$fetchedTweets = $test_connection->get(
					'statuses/user_timeline',
					  array(
						'screen_name'     => 'twitter',
						'count' => '1',
					  )
					);
				 }			 
			  ?>
		<div class="feed-them-social-admin-input-wrap">
		<div class="fts-title-description-settings-page">
		<h3>
		<?php _e('Twitter API Token', 'feed-them-social'); ?>
		</h3>
		<?php _e('If you keep seeing the message \'sorry twitter is down and will be right back\', it may be a good idea to add your own tokens below. See how to <a href="http://www.slickremix.com/docs/how-to-get-api-keys-and-tokens-for-twitter/" target="_blank">get API Keys and Tokens for Twitter</a>. Leave the fields below empty to use our Default API access tokens. If you do add your own tokens, after Saving all Changes make sure and <a href="admin.php?page=feed-them-settings-page&cache=clearcache">click here to delete cache</a>.', 'feed-them-social'); ?>
		</div>
		</div>
		<div class="twitter-api-wrap">
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
		<?php _e('Consumer Key (API Key)', 'feed-them-social'); ?>
		</div>
		<input type="text" name="fts_twitter_custom_consumer_key" class="feed-them-social-admin-input"  id="fts_facebook_custom_api_token" value="<?php echo get_option('fts_twitter_custom_consumer_key');?>"/>
		<div class="clear"></div>
		</div>
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
		<?php _e('Consumer Secret (API Secret)', 'feed-them-social'); ?>
		</div>
		<input type="text" name="fts_twitter_custom_consumer_secret" class="feed-them-social-admin-input"  id="fts_facebook_custom_api_token" value="<?php echo get_option('fts_twitter_custom_consumer_secret');?>"/>
		<div class="clear"></div>
		</div>
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
		<?php _e('Access Token', 'feed-them-social'); ?>
		</div>
		<input type="text" name="fts_twitter_custom_access_token" class="feed-them-social-admin-input"  id="fts_facebook_custom_api_token" value="<?php echo get_option('fts_twitter_custom_access_token');?>"/>
		<div class="clear"></div>
		</div>
		<div class="feed-them-social-admin-input-wrap">
		<div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
		<?php _e('Access Token Secret', 'feed-them-social'); ?>
		</div>
		<input type="text" name="fts_twitter_custom_access_token_secret" class="feed-them-social-admin-input"  id="fts_facebook_custom_api_token" value="<?php echo get_option('fts_twitter_custom_access_token_secret');?>"/>
		<div class="clear"></div>
		</div>
		</div>
		<!--twitter-api-wrap-->
		<div class="feed-them-social-admin-input-wrap">
		<?php if (!empty($test_fts_twitter_custom_consumer_key) && !empty($test_fts_twitter_custom_consumer_secret) && !empty($test_fts_twitter_custom_access_token) && !empty($test_fts_twitter_custom_access_token_secret)){	 
						if($test_connection->http_code != 200 || isset($fetchedTweets->errors)){
							echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').'';
							 foreach($fetchedTweets->errors as $error){
							 	echo ' <strong>'.$error->message.'. </strong> '. __('You may have entered in the Access information incorrectly please re-enter and try again.', 'feed-them-social').'';
							 }
						    echo'</div>';
						}
						else{
							echo'<div class="fts-successful-api-token">'. __('Your access token is working!', 'feed-them-social').'</div>';
						}
					  }
					  else{
					  		echo'<div class="fts-successful-api-token">'. __('You are using our Default Access info.', 'feed-them-social').'</div>';
					  }
				
				
				?>
		<div class="clear"></div>
		<input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
		</div>
		</form>
		<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a> </div>
		<!--/feed-them-social-admin-wrap-->
	
	<?php } 
}//END Class