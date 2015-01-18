<?php
add_action('wp_enqueue_scripts', 'fts_twitter_head');
function  fts_twitter_head() {
    wp_enqueue_style( 'fts_twitter_css', plugins_url( 'twitter/css/styles.css',  dirname(__FILE__) ) ); 
		if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			wp_enqueue_style( 'fts_instagram_css_popup', plugins_url( 'instagram/css/magnific-popup.css',  dirname(__FILE__) ) );
			wp_enqueue_script( 'fts_instagram_popup_js', plugins_url( 'instagram/js/magnific-popup.js',  dirname(__FILE__) ) );
		}
}
add_shortcode( 'fts twitter', 'fts_twitter_func' );
//Main Funtion
function fts_twitter_func($atts){
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$fts_functions = new feed_them_social_functions;

if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/twitter/twitter-feed.php');
   
}
else 	{
	extract( shortcode_atts( array(
		'twitter_name' => '',
		'twitter_height' => '',
		'description_image' => '',
		
	), $atts ) );
	$tweets_count ='5';
}
ob_start();  
 
$numTweets      = $tweets_count;
$name           = $twitter_name;  
$excludeReplies = true;            

 	  $data_cache = WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/twitter/cache/twitter_data_cache-'.$name.'-num'.$numTweets.'.cache';
	 
	  //Check Cache
	  if(file_exists($data_cache) && !filesize($data_cache) == 0 && filemtime($data_cache) > time() - 1800 && false !== strpos($data_cache,'-num'.$numTweets.'')){
		$fetchedTweets = $fts_functions->fts_get_feed_cache($data_cache);
		$cache_used = true;
	  } 
	  else {
		include(WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/twitter/twitteroauth/twitteroauth.php'); 
		  
		$fts_twitter_custom_consumer_key = get_option('fts_twitter_custom_consumer_key');
		$fts_twitter_custom_consumer_secret = get_option('fts_twitter_custom_consumer_secret');
		$fts_twitter_custom_access_token = get_option('fts_twitter_custom_access_token');
		$fts_twitter_custom_access_token_secret = get_option('fts_twitter_custom_access_token_secret');
		
		//Use custom api info  
		if (!empty($fts_twitter_custom_consumer_key) && !empty($fts_twitter_custom_consumer_secret) && !empty($fts_twitter_custom_access_token) && !empty($fts_twitter_custom_access_token_secret)){
		 		$connection = new TwitterOAuthFTS(
				//Consumer Key
				$fts_twitter_custom_consumer_key,
				//Consumer Secret
				$fts_twitter_custom_consumer_secret,
				//Access Token
				$fts_twitter_custom_access_token,  
				//Access Token Secret
				$fts_twitter_custom_access_token_secret
				);
		}
		//else use default info
		else {
			$connection = new TwitterOAuthFTS(
			//Consumer Key
			'dOIIcGrhWgooKquMWWXg',
			//Consumer Secret
			'qzAE4t4xXbsDyGIcJxabUz3n6fgqWlg8N02B6zM',
			//Access Token
			'1184502104-Cjef1xpCPwPobP5X8bvgOTbwblsmeGGsmkBzwdB',  
			//Access Token Secret
			'd789TWA8uwwfBDjkU0iJNPDz1UenRPTeJXbmZZ4xjY'
			);
		}
			// If excluding replies, we need to fetch more than requested as the
			// total is fetched first, and then replies removed.
			$totalToFetch = ($excludeReplies) ? max(50, $numTweets * 3) : $numTweets;
			
			$fetchedTweets = $connection->get(
			'statuses/user_timeline',
			  array(
				'screen_name'     => $name,
				'count'           => $totalToFetch,
				'exclude_replies' => $excludeReplies,
				'images'		  => $description_image
			  )
			);
			
		}//END ELSE		
  
  
  //Error Check
  if(isset($fetchedTweets->errors)){
  	$error_check = '<div>Oops, Somethings wrong. '.$fetchedTweets->errors[0]->message.'.</div>';
		if($fetchedTweets->errors[0]->code == 32){
			$error_check .= ' Please check that you have entered your Twitter API token information correctly.';
		}
		if($fetchedTweets->errors[0]->code == 34){
			$error_check .= ' Please check the Twitter Username you have entered is correct.';
		}
  }
  elseif(empty($fetchedTweets) && !isset($fetchedTweets->errors)){
  	$error_check = '<div>This account has no tweets. Please Tweet to see this feed.</div>';
  }
  
  
		//Does Cache folder exists? If not make it!
		
		//IS RATE LIMIT REACHED?
		if(isset($fetchedTweets->errors)){
			echo '<pre>';
			print_r($fetchedTweets->errors);
			echo '</pre>';
		}

  // Did the fetch fail?
  if(isset($error_check)) {
	  echo $error_check;
  }//END IF
  
  else {
	if (!empty($fetchedTweets)){
	  //Cache It
	  if (!isset($cache_used)){
		  	$fts_functions->fts_create_feed_cache($data_cache, $fetchedTweets);
	  }
	 
    // Fetch succeeded.
    // Now update the array to store just what we need.
    // (Done here instead of PHP doing this for every page load)

    $limitToDisplay = min($numTweets, count($fetchedTweets));
    
    for($i = 0; $i < $limitToDisplay; $i++) {
      $tweet = $fetchedTweets[$i];
    
	
      // Core info.
	  $name = isset( $tweet->user->name) ? $tweet->user->name : "";
	  $screen_name = isset($tweet->user->screen_name) ? $tweet->user->screen_name : "";
	
	  
	  $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
	  $not_protocol = !isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
	  
      $permalink = $protocol.'twitter.com/'. $screen_name .'/status/'. $tweet->id_str;
	  
	  $user_permalink = $protocol.'twitter.com/#!/'. $screen_name;
	  //Is Media Set
	  if(isset($tweet->entities->media[0]->media_url)){
		$media_url = $tweet->entities->media[0]->media_url;
		$media_url = str_replace($not_protocol, $protocol, $media_url);
	  }
	  else{
	  	$media_url = '';
	  }
	  
	  // leaving this for another update, trying to get videos, and I know this ain't right! $url = $tweet->entities->media[0]->expanded_url;
	  
      /* Alternative image sizes method: http://dev.twitter.com/doc/get/users/profile_image/:screen_name */
	  $image = isset($tweet->user->profile_image_url) ? $tweet->user->profile_image_url : "";
	  $image = str_replace($not_protocol, $protocol, $image);
 
      // Message. Convert links to real links.
      $pattern = array('/http:(\S)+/', '/https:(\S)+/', '/([^a-zA-Z0-9-_&])@([0-9a-zA-Z_]+)/', '/([^a-zA-Z0-9-_&])#([0-9a-zA-Z_]+)/');
      $replace = array(' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="'.$protocol.'twitter.com/$2" target="_blank" rel="nofollow">@$2</a>', ' <a href="'.$protocol.'twitter.com/search?q=%23$2&src=hash" target="_blank" rel="nofollow">#$2</a>');
      $text = preg_replace($pattern, $replace, $tweet->text);
 
      // Need to get time in Unix format.
	  $times = isset($tweet->created_at) ? $tweet->created_at : "";
	  
	  $CustomDateCheck = get_option('fts-date-and-time-format');
	  if($CustomDateCheck) {
  	   $CustomDateFormatTwitter = get_option('fts-date-and-time-format');
	  }
	  else {
		$CustomDateFormatTwitter = 'F jS, Y \a\t g:ia'; 
	  }
	  
	  date_default_timezone_set(get_option('fts-timezone'));
      $uTime = date($CustomDateFormatTwitter ,strtotime($times) - 3 * 3600 );
	  $twitter_id = isset($tweet->id_str) ? $tweet->id_str : "";
 		
		$fts_twitter_full_width = get_option('twitter_full_width');
	  
      // Now make the new array.
      $tweets[] = array(
              'text' => $text,
              'name' => $name,
              'screen_name' => $screen_name,
			  'user_permalink' => $user_permalink,
              'permalink' => $permalink,
              'image' => $image,
              'time' => $uTime,
			  'media_url' => $media_url,
			  'id' => $twitter_id,
			   // 'url' => $url,
              );
  }//End FOR fts-twitter-full-width ?>   
<div id="twitter-feed-<?php print $twitter_name?>" class="fts-twitter-div<?php if ($twitter_height !== 'auto' && empty($twitter_height) == NULL) {?> fts-twitter-scrollable<?php } if ($popup == 'yes') { ?> popup-gallery-twitter<?php } ?>" <?php if ($twitter_height !== 'auto' && empty($twitter_height) == NULL) {?>style="height:<?php echo $twitter_height; ?>"<?php }?>>
  <?php foreach($tweets as $t) : ?>
  <div class="fts-tweeter-wrap">
    <div class="tweeter-info">
      <?php if ($fts_twitter_full_width !== 'yes') {?>
      <div class="fts-twitter-image"><a href="<?php print $t['user_permalink'];?>" target="_blank" class="black"><img class="twitter-image" src="<?php print $t['image'];?>" /></a></div>
      <?php } ?>
      <div class="<?php if ($fts_twitter_full_width == 'yes') {?>fts-twitter-full-width<?php } else { ?>right<?php } ?>">
        <div class="uppercase bold"><a href="<?php print $t['user_permalink'];?>" target="_blank" class="black">@<?php print $t['screen_name'];?></a></div>
        <span class="time"><a href="<?php print $t['permalink']?>"><?php print $t['time'];?></a></span><br/>
        <span class="fts-twitter-text"><?php print $t['text'];?>
        <div class="fts-fb-caption"><a href="<?php print $t['permalink']?>" class="fts-view-on-twitter-link" target="_blank">View on Twitter</a></div>
        </span>
        <?php if ($t['media_url']) { ?>
        <a href="<?php if ($popup == 'yes') { print $t['media_url']; } else { print $t['permalink']; }?>" class="fts-twitter-link-image" target="_blank"><img class="fts-twitter-description-image" src="<?php print $t['media_url'];?>" /></a>
		<?php } ?>
        </div>
      <div class="fts-twitter-reply-wrap">
      <a href="<?php print $t['permalink']?>">
      <div class="fts-twitter-reply"></div>
      </a></div> 
      <div class="clear"></div>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="clear"></div>
</div>
<?php if ($twitter_height !== 'auto' && empty($twitter_height) == NULL) {?>
<script>
  // this makes it so the page does not scroll if you reach the end of scroll bar or go back to top
  jQuery.fn.isolatedScrollTwitter = function() {
		this.bind('mousewheel DOMMouseScroll', function (e) {
		var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.detail,
			bottomOverflow = this.scrollTop + jQuery(this).outerHeight() - this.scrollHeight >= 0,
			topOverflow = this.scrollTop <= 0;
	
		if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {
			e.preventDefault();
		}
	});
	return this;
  };
  jQuery('.fts-twitter-scrollable').isolatedScrollTwitter();
</script>
<?php }?>  
<?php  
	}// END IF $fetchedTweets
}//END ELSE

return ob_get_clean(); 
}
?>