<?php
class FTS_Instagram_Feed extends feed_them_social_functions {
	function __construct() {
		add_shortcode( 'fts instagram',  array( $this, 'fts_instagram_func'));
		add_action('wp_enqueue_scripts', array( $this, 'fts_instagram_head'));
	}
	//**************************************************
	// Add Styles and Scripts functions
	//**************************************************
	function fts_instagram_head() {
			wp_enqueue_style( 'fts-feeds', plugins_url( 'feed-them-social/feeds/css/styles.css'));
	}
	//**************************************************
	// Display Instagram Feed
	//**************************************************
	function fts_instagram_func($atts) {
		
			wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js'), array( 'jquery' ) );
			// masonry and date js snippet in fts-global
	 	wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/feeds/js/imagesloaded.pkgd.min.js' ));
			wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js'), array( 'jquery' ) );
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include(WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/instagram/instagram-feed.php');
					if ($popup == 'yes') {
							// it's ok if these styles & scripts load at the bottom of the page
							wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/feeds/css/magnific-popup.css'));
							wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/feeds/js/magnific-popup.js'));
					}
		}
		else {
			extract( shortcode_atts( array(
						'instagram_id' => '',
						'type' => '',
						'super_gallery' => '',
						'image_size' => '',
						'icon_size' => '',
						'space_between_photos' => '',
						'hide_date_likes_comments' => '',
						'center_container' => '',
						'image_stack_animation' => '',
					), $atts ) );
			$pics_count = '6';
		}
		
		$instagram_data_array = array();
		$fts_instagram_access_token = get_option('fts_instagram_custom_api_token');
		$fts_instagram_show_follow_btn = get_option('instagram_show_follow_btn');
		$fts_instagram_show_follow_btn_where = get_option('instagram_show_follow_btn_where');
		
		ob_start();
		if (empty($fts_instagram_access_token)) {
			$fts_instagram_tokens_array = array('267791236.df31d88.30e266dda9f84e9f97d9e603f41aaf9e', '267791236.14c1243.a5268d6ed4cf4d2187b0e98b365443af', '267791236.f78cc02.bea846f3144a40acbf0e56b002c112f8', '258559306.502d2c4.c5ff817f173547d89477a2bd2e6047f9');
			$fts_instagram_access_token = $fts_instagram_tokens_array[array_rand($fts_instagram_tokens_array, 1)];
		}
		else {
			$fts_instagram_access_token = $fts_instagram_access_token;
		}
		//URL to get Feeds
		if ($type == 'hashtag') {
			$instagram_data_array['data'] = 'https://api.instagram.com/v1/tags/'.$instagram_id.'/media/recent/?access_token='.$fts_instagram_access_token;
		}
		else {
			$instagram_data_array['data'] = 'https://api.instagram.com/v1/users/'.$instagram_id.'/media/recent/?access_token='.$fts_instagram_access_token;
		}
		
		$instagram_data_array['user_info'] = 'https://api.instagram.com/v1/users/'.$instagram_id.'?access_token='.$fts_instagram_access_token;
		
		$cache = WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/instagram/cache/instagram-cache-'.$instagram_id.'.cache';
		
		$response = $this->fts_get_feed_json($instagram_data_array);
		
		//Error Check
		$error_check = json_decode($response['data']);
		if (isset($error_check->meta->error_message)) {
			return $error_check->meta->error_message;
		}
		if (file_exists($cache) && !filesize($cache) == 0 && filemtime($cache) > time() - 900) {
			  $insta_data = $this->fts_get_feed_cache($cache);
		}
		else {
			$insta_data = json_decode($response['data']);
			//if Error DON'T Cache
			if (!isset($error_check->meta->error_message)) {
				$this->fts_create_feed_cache($cache, $insta_data);
			}
		}
		
		$instagram_user_info = !empty($response['user_info']) ? json_decode($response['user_info']) : '';
		
		//******************
		// SOCIAL BUTTON
		//******************
		if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn !== 'no' && $fts_instagram_show_follow_btn_where == 'instagram-follow-above' && isset($instagram_user_info->data->username)) {
			echo '<div class="instagram-social-btn-top">';
			$this->social_follow_button('instagram', $instagram_user_info->data->username);
			echo '</div>';
		}
		if (isset($super_gallery) && $super_gallery == 'yes') { ?>
		<div class="fts-slicker-instagram masonry js-masonry <?php if ($popup == 'yes') { print 'popup-gallery'; }?>" style="margin:auto" data-masonry-options='{ "isFitWidth": <?php if ($center_container == 'no') { ?>false<?php } else {?>true<?php } if ($image_stack_animation == 'no') { ?>, "transitionDuration": 0<?php } ?> }'>
			<?php }
		else { ?>
		    	<div class="fts-instagram <?php if ($popup == 'yes') { print 'popup-gallery'; }?>">
		   <?php }
		$set_zero = 0;
		
		if (!isset($insta_data->data)) {
			return '<div style="padding-right:35px;">Looks like instagram\'s API down. Please try clearing cache and reloading this page in the near future. If it continues try adding your own API Token to the Instragram Options page of our plugin.</div></div>';
		}
		foreach ($insta_data->data as $insta_d) {
			if ($set_zero==$pics_count)
				break;
			//Create Instagram Variables
			$instagram_date = isset($insta_d->created_time) ? date('F j, Y', $insta_d->created_time) : "";
			$instagram_link = isset($insta_d->link) ? $insta_d->link : "";
			$instagram_thumb_url = isset($insta_d->images->thumbnail->url) ? $insta_d->images->thumbnail->url : "";
			$instagram_lowRez_url = isset($insta_d->images->standard_resolution->url) ? $insta_d->images->standard_resolution->url : "";
			if (isset($_SERVER["HTTPS"])) {
				$instagram_thumb_url = str_replace('http://', 'https://', $instagram_thumb_url );
				$instagram_lowRez_url = str_replace('http://', 'https://', $instagram_lowRez_url );
			}
			$instagram_likes = isset($insta_d->likes->count) ? $insta_d->likes->count : "";
			$instagram_comments = isset($insta_d->comments->count) ? $insta_d->comments->count : "";
			$instagram_caption_a_title = isset($insta_d->caption->text) ? $insta_d->caption->text : "";
			//Create links from @mentions and regular links.
			$pattern = array('/http:(\S)+/', '/https:(\S)+/', '/([^a-zA-Z0-9-_&])@([0-9a-zA-Z_]+)/');
			$replace = array(' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="http://instagram.com/$2" target="_blank" rel="nofollow">@$2</a>');
			$instagram_caption = preg_replace($pattern, $replace, $instagram_caption_a_title);
			// Super Gallery If statement
			if (isset($super_gallery) && $super_gallery == 'yes') { ?>
		<div class='slicker-instagram-placeholder fts-instagram-wrapper' style="width:<?php print $image_size ?>; margin:<?php print $space_between_photos ?>;">
		<?php if (isset($popup) && $popup == 'yes') {  ?>
		<div class="fts-instagram-caption"><?php if (!$instagram_caption == '') { print ''.$instagram_caption.'<br/>';} ?><a href='<?php print $instagram_link ?>' class="fts-view-on-instagram-link" target="_blank"><?php _e('View on Instagram', 'feed-them-social');?></a></div>
		 <?php } ?>
		<a href='<?php if (isset($popup) && $popup == 'yes') { print $instagram_lowRez_url; } else { print $instagram_link; } ?>' title='<?php print $instagram_caption_a_title ?>' target="_blank" class='fts-slicker-backg fts-instagram-img-link' style="height:<?php print $icon_size ?> !important; width:<?php print $icon_size ?>; line-height:<?php print $icon_size ?>; font-size:<?php print $icon_size ?>;"><span class="fts-instagram-icon" style="height:<?php print $icon_size ?>; width:<?php print $icon_size ?>; line-height:<?php print $icon_size ?>; font-size:<?php print $icon_size ?>;"></span></a>
		  <?php if ($hide_date_likes_comments == 'no') { ?>
		  	<div class='slicker-date'><?php print $instagram_date?></div>
		  <?php } ?>
		  <div class='slicker-instaG-backg-link'>
		    <div class='slicker-instagram-image'><img src="<?php print $instagram_lowRez_url ?>" /></div>
		    <div class='slicker-instaG-photoshadow'></div>
		  </div>
			 <?php if ($hide_date_likes_comments == 'no') { ?>
		          <ul class='slicker-heart-comments-wrap'>
		            <li class='slicker-instagram-image-likes'><?php print $instagram_likes ?></li>
		            <li class='slicker-instagram-image-comments'><span class="fts-comment-instagram"></span><?php print $instagram_comments ?></li>
		          </ul>
			  <?php  }  ?>
		</div>
		<?php }
			// Classic Gallery If statement
			else {  ?>
		<div class='instagram-placeholder fts-instagram-wrapper'><?php if (isset($popup) && $popup == 'yes') { print '<div class="fts-backg"></div>'; } else { ?>  <a class='fts-backg' target='_blank' href='<?php print $instagram_link ?>'></a>  <?php  };?>
		  <div class='date'><?php print $instagram_date ?></div>
		 <?php if (isset($popup) && $popup == 'yes') {  ?>
		<div class="fts-instagram-caption"><?php if (!$instagram_caption == '') { print ''.$instagram_caption.'<br/>';} ?><a href='<?php print $instagram_link ?>' class="fts-view-on-instagram-link" target="_blank"><?php _e('View on Instagram', 'feed-them-social');?></a></div>
		 <?php } ?>
		  <a href="<?php if ($popup == 'yes') { print $instagram_lowRez_url; } else { print $instagram_link; } ?>" class='instaG-backg-link fts-instagram-img-link' target='_blank' title="<?php print $instagram_caption_a_title ?>">
		    <img src="<?php print $instagram_thumb_url ?>" class="instagram-image" />
		    <div class='instaG-photoshadow'></div>
		  </a>
		  <ul class='heart-comments-wrap'>
		    <li class='instagram-image-likes'><?php print $instagram_likes ?></li>
		    <li class='instagram-image-comments'><?php print $instagram_comments ?></li>
		  </ul>
		</div>
		<?php }
			$set_zero++;
		}
?>
		<div class="clear"></div>
		</div>
		<?php
		//******************
		// SOCIAL BUTTON
		//******************
		if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn !== 'no' && $fts_instagram_show_follow_btn_where == 'instagram-follow-below' && isset($instagram_user_info->data->username)) {
			echo '<div class="instagram-social-btn-bottom">';
			$this->social_follow_button('instagram', $instagram_user_info->data->username);
			echo '</div>';
		}
		return ob_get_clean();
	}
}//fts_instagram_func END CLASS
new FTS_Instagram_Feed();
?>