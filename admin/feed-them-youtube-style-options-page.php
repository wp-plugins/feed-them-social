<?php
namespace feedthemsocial;
class FTS_youtube_options_page {
	function __construct() {
	}
	//**************************************************
	// Youtube Options Page
	//**************************************************
	function feed_them_youtube_options_page() {
		$fts_functions = new feed_them_social_functions();
		$fts_youtube_show_follow_btn = get_option('youtube_show_follow_btn');
		$fts_youtube_show_follow_btn_where = get_option('youtube_show_follow_btn_where');

?>
		<div class="feed-them-social-admin-wrap">
		  <h1>
		    <?php _e(' Feed Options', 'feed-them-social'); ?>
		  </h1>
		  <div class="use-of-plugin">
		    <?php _e('Add a follow button and position it using the options below.', 'feed-them-social'); ?>
		  </div>

		    <br/>

		  <!-- custom option for padding -->
		  <form method="post" class="fts-youtube-feed-options-form" action="options.php">
		  	<?php settings_fields('fts-youtube-feed-style-options'); ?>
		   <div class="feed-them-social-admin-input-wrap">
		   <div class="fts-title-description-settings-page" style="padding-top:0; border:none;">
		       <h3><?php _e('Follow Button Options', 'feed-them-social'); ?></h3>
		      </div>
		           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Show Follow Button', 'feed-them-social'); ?></div>

		    <select name="youtube_show_follow_btn" id="youtube-show-follow-btn" class="feed-them-social-admin-input">
				  <option '<?php echo selected($fts_youtube_show_follow_btn, 'no', false ) ?>' value="no"><?php _e('No', 'feed-them-social'); ?></option>
		  		  <option '<?php echo selected($fts_youtube_show_follow_btn, 'yes', false ) ?>' value="yes"><?php _e('Yes', 'feed-them-social'); ?></option>
		    </select>

		      <div class="clear"></div>
		 	  </div><!--/fts-twitter-feed-styles-input-wrap-->


		      <div class="feed-them-social-admin-input-wrap">
		           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Placement of the Buttons', 'feed-them-social'); ?></div>

		    <select name="youtube_show_follow_btn_where" id="youtube-show-follow-btn-where" class="feed-them-social-admin-input">
				  <option ><?php _e('Please Select Option', 'feed-them-social'); ?></option>
				  <option '<?php echo selected($fts_youtube_show_follow_btn_where, 'youtube-follow-above', false ) ?>' value="youtube-follow-above"><?php _e('Show Above Feed', 'feed-them-social'); ?></option>
		  		  <option '<?php echo selected($fts_youtube_show_follow_btn_where, 'youtube-follow-below', false ) ?>' value="youtube-follow-below"><?php _e('Show Below Feed', 'feed-them-social'); ?></option>
		    </select>

		      <div class="clear"></div>
		 	  </div><!--/fts-twitter-feed-styles-input-wrap-->

		  		<?php
		$youtubeAPIkey = get_option('youtube_custom_api_token');

		$youtube_userID_data = 'https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=slickremix&key='.$youtubeAPIkey;

		//Get Data for Youtube
		$response = wp_remote_fopen($youtube_userID_data);
		//Error Check
		$test_app_token_response = json_decode($response);

		// echo'<pre>';
		// print_r($test_app_token_response);
		// echo'</pre>';
		?><div class="feed-them-social-admin-input-wrap">
		 <div class="fts-title-description-settings-page">
		       <h3><?php _e('YouTube API Key', 'feed-them-social'); ?></h3>
		        <?php _e('Read here and learn how to <a href="http://www.slickremix.com/docs/get-api-key-for-youtube" target="_blank">GET AN API KEY</a>.', 'feed-them-social'); ?>
		      </div>
		    <div class="feed-them-social-admin-input-wrap" style="margin-bottom:0px;">
		      <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label">
		        <?php _e('API Key Required', 'feed-them-social'); ?>
		      </div>
		      <input type="text" name="youtube_custom_api_token" class="feed-them-social-admin-input"  id="youtube_custom_api_token" value="<?php echo get_option('youtube_custom_api_token');?>"/>
		      <div class="clear"></div>
		    </div>
		    <?php
		foreach($test_app_token_response as $userID) {
			// Error Check
			if(!isset($userID->errors[0]->reason) && !empty($youtubeAPIkey)){
				echo'<div class="fts-successful-api-token">'. __('Your API key is working!', 'feed-them-social').'</div>';
			}
			elseif(isset($userID->errors[0]->reason) && !empty($youtubeAPIkey)){
				echo'<div class="fts-failed-api-token">'. __('This API key does not appear to be valid. YouTube responded with: ', 'feed-them-social').' '.$userID->errors[0]->reason.'</div>';
			}
			if ($youtubeAPIkey == ''){
				echo'<div class="fts-failed-api-token">'. __('You must register for an API token to use the youtube feed.', 'feed-them-social').'</div>';
			}
			break;
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