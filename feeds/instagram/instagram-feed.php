<?php
namespace feedthemsocial;
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

		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/instagram/instagram-feed.php';
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
						'height' => '',
					), $atts ) );
			$pics_count = '6';
		}

		$instagram_data_array = array();
		$fts_instagram_access_token = get_option('fts_instagram_custom_api_token');
		$fts_instagram_show_follow_btn = get_option('instagram_show_follow_btn');
		$fts_instagram_show_follow_btn_where = get_option('instagram_show_follow_btn_where');


		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			$_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10).'_'.$type);
			//Create Dynamic Class Name
			$fts_dynamic_class_name =  '';
			if (isset($_REQUEST['fts_dynamic_name'])) {
				$fts_dynamic_class_name =  'feed_dynamic_class'.$_REQUEST['fts_dynamic_name'];
			}
		}

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
			$instagram_data_array['data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://api.instagram.com/v1/tags/'.$instagram_id.'/media/recent/?access_token='.$fts_instagram_access_token;
		}
		else {
			// $instagram_data_array['data'] = 'https://api.instagram.com/v1/users/'.$instagram_id.'/media/recent/?access_token='.$fts_instagram_access_token;
			$instagram_data_array['data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://api.instagram.com/v1/users/'.$instagram_id.'/media/recent/?count='.$pics_count.'&access_token='.$fts_instagram_access_token;
		}

		$instagram_data_array['user_info'] = 'https://api.instagram.com/v1/users/'.$instagram_id.'?access_token='.$fts_instagram_access_token;

		$cache = 'instagram_cache_'.$instagram_id.'';

		$response = $this->fts_get_feed_json($instagram_data_array);

		//Error Check
		$error_check = json_decode($response['data']);
		if (isset($error_check->meta->error_message)) {
			return $error_check->meta->error_message;
		}
		if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($cache)) && !isset($_GET['load_more_ajaxing'])) {
			$response = $this->fts_get_feed_cache($cache);
			$insta_data = json_decode($response['data']);
		}
		else {
			$insta_data = json_decode($response['data']);
			//if Error DON'T Cache
			if (!isset($error_check->meta->error_message) && !isset($_GET['load_more_ajaxing'])) {
				$this->fts_create_feed_cache($cache, $response);
			}
		}

		$instagram_user_info = !empty($response['user_info']) ? json_decode($response['user_info']) : '';

		//  echo '<pre>';
		//  print_r($insta_data->pagination->next_url);
		//  echo '</pre>';

		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			//******************
			// SOCIAL BUTTON
			//******************
			if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn !== 'no' && $fts_instagram_show_follow_btn_where == 'instagram-follow-above' && isset($instagram_user_info->data->username)) {
				echo '<div class="instagram-social-btn-top">';
				$this->social_follow_button('instagram', $instagram_user_info->data->username);
				echo '</div>';
			}
			if(isset($scrollMore) && $scrollMore == 'autoscroll' || isset($height) && $height !== '') { ?>
				 <div class="fts-instagram-scrollable <?php echo $fts_dynamic_class_name ?>instagram" style="overflow:auto;<?php if ($height !== '') {?> height:<?php echo $height; } ?>">
     <?php }
			if (isset($super_gallery) && $super_gallery == 'yes') { ?>
				<div class="fts-slicker-instagram masonry js-masonry <?php if ($popup == 'yes') { print 'popup-gallery '; } echo $fts_dynamic_class_name ?>" style="margin:auto" data-masonry-options='{ "isFitWidth": <?php if ($center_container == 'no') { ?>false<?php } else {?>true<?php } if ($image_stack_animation == 'no') { ?>, "transitionDuration": 0<?php } ?> }'>
					<?php }
		elseif(isset($scrollMore) && $scrollMore == 'autoscroll' || isset($height) && $height !== '') { ?>
									<div class="fts-instagram masonry js-masonry <?php if ($popup == 'yes') { print 'popup-gallery '; } echo $fts_dynamic_class_name ?>" style="margin:auto;" data-masonry-options='{ "isFitWidth": true , "transitionDuration": 0 }'>
							<?php }
			else { ?>
    				<div class="fts-instagram <?php if ($popup == 'yes') { echo 'popup-gallery '; } echo $fts_dynamic_class_name ?>">

    <?php }

			$set_zero = 0;
		} // END Make sure it's not ajaxing


		if (!isset($insta_data->data)) {
			return '<div style="padding-right:35px;">Looks like instagram\'s API down. Please try clearing cache and reloading this page in the near future. If it continues try adding your own API Token to the Instragram Options page of our plugin.</div></div>';
		}
		//echo '<pre>';
		//print_r($insta_data);
		//echo '</pre>';
		
		foreach ($insta_data->data as $insta_d) {
			if (isset($set_zero) && $set_zero == $pics_count)
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
			
			// These need to be in this order to keep the different counts straight since I used either $instagram_likes or $instagram_comments throughout.
			$instagram_likes = isset($insta_d->likes->count) ? $insta_d->likes->count : "";
				// here we add a , for all numbers below 9,999
			if (isset($instagram_likes) && $instagram_likes <=  9999) {
				$instagram_likes = number_format($instagram_likes);
			}
			// here we convert the number for the like count like 1,200,000 to 1.2m if the number goes into the millions
			if (isset($instagram_likes) && $instagram_likes >= 1000000) {
				$instagram_likes = round(($instagram_likes/1000000),1).'m';
			}
			// here we convert the number for the like count like 10,500 to 10.5k if the number goes in the 10 thousands
			if (isset($instagram_likes) && $instagram_likes >= 10000) {
				$instagram_likes = round(($instagram_likes/1000),1).'k';
			}
			$instagram_comments = isset($insta_d->comments->count) ? $insta_d->comments->count : "";
			// here we add a , for all numbers below 9,999
			if (isset($instagram_comments) && $instagram_comments <=  9999) {
				$instagram_comments = number_format($instagram_comments);
			}
			// here we convert the number for the comment count like 1,200,000 to 1.2m if the number goes into the millions
			if (isset($instagram_comments) && $instagram_comments >= 1000000) {
				$instagram_comments = round(($instagram_comments/1000000),1).'m';
			}
			// here we convert the number  for the comment count like 10,500 to 10.5k if the number goes in the 10 thousands
			if (isset($instagram_comments) && $instagram_comments >= 10000) {
				$instagram_comments = round(($instagram_comments/1000),1).'k';
			}
			
			$instagram_caption_a_title = isset($insta_d->caption->text) ? $insta_d->caption->text : "";
			//Create links from @mentions and regular links.
			$pattern = array('/http:(\S)+/', '/https:(\S)+/', '/([^a-zA-Z0-9-_&])@([0-9a-zA-Z_]+)/');
			$replace = array(' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="${0}" target="_blank" rel="nofollow">${0}</a>', ' <a href="http://instagram.com/$2" target="_blank" rel="nofollow">@$2</a>');
			$instagram_caption = preg_replace($pattern, $replace, $instagram_caption_a_title);
			// Super Gallery If statement
			if (isset($super_gallery) && $super_gallery == 'yes') { ?>
		<div class='slicker-instagram-placeholder fts-instagram-wrapper' style='width:<?php print $image_size ?>; height:<?php print $image_size ?>; margin:<?php print $space_between_photos ?>;'>
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
		<div class='instagram-placeholder fts-instagram-wrapper' style='width:150px;'><?php if (isset($popup) && $popup == 'yes') { print '<div class="fts-backg"></div>'; } else { ?>  <a class='fts-backg' target='_blank' href='<?php print $instagram_link ?>'></a>  <?php  };?>
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
			if (isset($set_zero)) {
				$set_zero++;
			}
		}

		//******************
		//Load More BUTTON Start
		//******************
		$build_shortcode = '[fts instagram';
		foreach ($atts as $attribute => $value) {
			$build_shortcode .= ' '.$attribute.'='.$value;
		}
		$build_shortcode .= ']';
		$_REQUEST['next_url'] = isset($insta_data->pagination->next_url) ? $insta_data->pagination->next_url : "";
?>
<script>
var nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?>= "<?php echo $_REQUEST['next_url']; ?>";
</script>
<?php
		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing']) && !isset($_REQUEST['fts_no_more_posts']) && !empty($loadmore)) {
			$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
			$time = time();
			$nonce = wp_create_nonce($time."load-more-nonce");
?>
<script>
	 jQuery(document).ready(function() {
		  <?php // $scrollMore = load_more_posts_style shortcode att
			if ($scrollMore == 'autoscroll') { // this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode ?>
			jQuery(".<?php echo $fts_dynamic_class_name ?>instagram").bind("scroll",function() {
   				 if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
		 <?php }
			else { // this is where we do CLICK function to LOADMORE if = button in shortcode ?>
				jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").click(function() {
			<?php } ?>
					jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").addClass('fts-fb-spinner');
						var button = jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div>');
						console.log(button);
						var build_shortcode = "<?php if (get_option('fts_fix_loadmore')) { ?>[<?php print $build_shortcode;?>]<?php } else { print $build_shortcode; } ?>";
						var yes_ajax = "yes";
						var fts_d_name = "<?php echo $fts_dynamic_name;?>";
						var fts_security = "<?php echo $nonce;?>";
						var fts_time = "<?php echo $time;?>";
					jQuery.ajax({
						data: {action: "my_fts_fb_load_more", next_url: nextURL_<?php echo $fts_dynamic_name ?>, fts_dynamic_name: fts_d_name, rebuilt_shortcode: build_shortcode, load_more_ajaxing: yes_ajax, fts_security: fts_security, fts_time: fts_time},
						type: 'GET',
						url: myAjaxFTS,
						success: function( data ) {
							console.log('Well Done and got this from sever: ' + data);
					 	jQuery('.<?php echo $fts_dynamic_class_name ?>').append(data).filter('.<?php echo $fts_dynamic_class_name ?>').html();
				 <?php if ($super_gallery == 'yes') {  ?>
							jQuery('.<?php echo $fts_dynamic_class_name ?>').masonry( 'reloadItems' );
						setTimeout(function() {
									// Do something after 3 seconds
								jQuery('.<?php echo $fts_dynamic_class_name ?>').masonry( 'layout' );
								}, 500);
						<?php } ?>
						if(!nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> || nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> == 'no more'){
						  jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php _e('No More Photos', 'feed-them-social') ?></div>');
						  jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').removeAttr('id');
						  jQuery(".<?php echo $fts_dynamic_class_name ?>instagram").unbind('scroll');
						}
					 jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').html('<?php _e('Load More', 'feed-them-social') ?>');
					  //	jQuery('#loadMore_< ?php echo $fts_dynamic_name ?>').removeClass('flip360-fts-load-more');
					 jQuery("#loadMore_<?php echo $fts_dynamic_name ?>").removeClass('fts-fb-spinner');
						}
					}); // end of ajax()
					return false;
					<?php // string $scrollMore is at top of this js script. acception for scroll option closing tag
			if ($scrollMore == 'autoscroll' ) { ?>
								} // end of scroll ajax load.
					 <?php } ?>
		  }); // end of document.ready
	  }); // end of form.submit
</script>
<?php
		}//End Check
		// main closing div not included in ajax check so we can close the wrap at all times

		print '</div>'; // closing main div for photos and scroll wrap

		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
			// this div returns outputs our ajax request via jquery append html from above

			print '<div class="clear"></div>';
			print '<div id="output_'.$fts_dynamic_name.'"></div>';
			if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $scrollMore == 'autoscroll') {
				print '<div id="loadMore_'.$fts_dynamic_name.'" class="fts-fb-load-more fts-fb-autoscroll-loader">Instagram</div>';
			}
		}
?>
		 <?php //only show this script if the height option is set to a number
		if ($height !== '' && empty($height) == NULL) { ?>
										 <script>
											// this makes it so the page does not scroll if you reach the end of scroll bar or go back to top
											jQuery.fn.isolatedScrollFacebookFTS = function() {
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
											jQuery('.fts-instagram-scrollable').isolatedScrollFacebookFTS();
										 </script>
									   <?php } //end $height !== 'auto' && empty($height) == NULL ?>
			<?php
		if(isset($scrollMore) && $scrollMore == 'autoscroll' || isset($height) && $height !== '') {
			print '</div>'; // closing height div for scrollable feeds
		}

		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			print '<div class="clear"></div>';
			if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $scrollMore == 'button') {
				//  print '<div class="fts-fb-load-more-wrapper">';
				print '<div id="loadMore_'.$fts_dynamic_name.'" class="fts-fb-load-more">'.__('Load More', 'feed-them-social').'</div>';
				//  print '</div>';
			}
		}//End Check
		unset($_REQUEST['next_url']);

		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			//******************
			// SOCIAL BUTTON
			//******************
			if (isset($fts_instagram_show_follow_btn) && $fts_instagram_show_follow_btn !== 'no' && $fts_instagram_show_follow_btn_where == 'instagram-follow-below' && isset($instagram_user_info->data->username)) {
				echo '<div class="instagram-social-btn-bottom">';
				$this->social_follow_button('instagram', $instagram_user_info->data->username);
				echo '</div>';
			}
		}
		return ob_get_clean();
	}
	//**************************************************
	// Create a random string
	//**************************************************
	function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$size = strlen( $chars );
		for ( $i = 0; $i < $length; $i++ ) {
			$str = $chars[ rand( 0, $size - 1 ) ];
		}
		return $str;
	}

}//fts_instagram_func END CLASS
new FTS_Instagram_Feed();
?>