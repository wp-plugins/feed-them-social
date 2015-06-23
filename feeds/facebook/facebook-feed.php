<?php
namespace feedthemsocial;
class FTS_Facebook_Feed extends feed_them_social_functions {
	function __construct() {
		add_shortcode( 'fts facebook group', array($this, 'fts_fb_func'));
		add_shortcode( 'fts facebook page', array($this, 'fts_fb_func'));
		add_shortcode( 'fts facebook event', array( $this, 'fts_fb_func'));
		add_shortcode( 'fts facebook', array( $this, 'fts_fb_func'));
		add_action('wp_enqueue_scripts', array( $this, 'fts_fb_head'));
	}
	//**************************************************
	// Add Styles and Scripts functions
	//**************************************************
	function fts_fb_head() {
			 wp_enqueue_style( 'fts-feeds', plugins_url( 'feed-them-social/feeds/css/styles.css'));
	}
	//**************************************************
	// Display Facebook Feed
	//**************************************************
	function fts_fb_func($atts) {
			
		$developer_mode = 'on';
		//Facebook Follow Button Options
		$fb_show_follow_btn = get_option('fb_show_follow_btn');
		$fb_show_follow_btn_where = get_option('fb_show_follow_btn_where');
		
		wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js'), array( 'jquery' ) );
		//Make sure everything is reset
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		//Eventually add premium page file
		if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include(WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/facebook/facebook-premium-feed.php');
			if ($fts_fb_popup == 'yes') {
				// it's ok if these styles & scripts load at the bottom of the page
				wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/feeds/css/magnific-popup.css'));
				wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/feeds/js/magnific-popup.js'));
				wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/feeds/js/imagesloaded.pkgd.min.js' ));
				wp_enqueue_script( 'fts-global', plugins_url('feed-them-social/feeds/js/fts-global.js'), array( 'jquery' ));
			}
		}
		else {
			extract( shortcode_atts( array(
						'id' => '',
						'type' => '',
						'posts_displayed' => '',
						'height' => '',
						'album_id' => '',
						'image_width' => '',
						'image_height' => '',
						'space_between_photos' => '',
						'hide_date_likes_comments' => '',
						'center_container' => '',
						'image_stack_animation' => '',
						'image_position_lr' => '',
						'image_position_top' => '',
					), $atts ) );
			$custom_name = $posts_displayed;
			$fts_limiter = '5';
			$fts_fb_id = $id;
		}
		//API Access Token
		$custom_access_token = get_option('fts_facebook_custom_api_token');
		if (!empty($custom_access_token)) {
			$access_token = get_option('fts_facebook_custom_api_token');
		}
		else {
			//Randomizer
			$values = array(
			'817537814961507|HSQjMRcTKHfsqO4CSItHTrnyVBk', 
			'1102592936422517|FG1AX3hBb0hzEKrYIFi6z71lYs8', 
			'1581354922148318|Ta9P0qtrRTcI4s9_bmnMGbGAfv4', 
			'1470900269866726|EYsX18Tk8iw84zr-Err483yUR4c', 
			'346055802264380|wSn-ygXzJJkTuPsNuQMksUMRWuc', 
			'646491715485385|vhBnr8q-42P49EiXnZnmr4F1AX4', 
			'433290036843633|spBHAl5Mw9s2VouZRnlp3GTO2Gw', 
			'706407866152249|nUx5ejy-JLxdSDmb0xB9p1ybolA', 
			'1425804354387502|wzo-X1Q7V87SqhjhXV7GSB0qb8A', 
			'1586514261631082|Uxz9iF9llMEiCWwBMyUO1GK8H_A', 
			);
			$access_token = $values[array_rand($values, 1)];
		}
		//Error Check
		if (!$fts_fb_id) {
			return 'Please enter a username for this feed.';
		}
		ob_start();
		switch ($type) {
		case 'group' :
			$fts_view_fb_link ='https://www.facebook.com/groups/'.$fts_fb_id.'/';
			break;
		case 'page':
			$fts_view_fb_link ='https://www.facebook.com/'.$fts_fb_id.'/';
			break;
		case 'event' :
			$fts_view_fb_link ='https://www.facebook.com/events/'.$fts_fb_id.'/';
			break;
		case 'events' :
			$fts_view_fb_link ='https://www.facebook.com/'.$fts_fb_id.'/events/';
			break;
		case 'albums':
			$fts_view_fb_link ='https://www.facebook.com/'.$fts_fb_id.'/photos_stream?tab=photos_albums';
			break;
		case 'album_photos':
			$fts_view_fb_link ='https://www.facebook.com/'.$fts_fb_id.'/photos_stream';
			break;
		case 'hashtag':
			$fts_view_fb_link ='https://www.facebook.com/hashtag/'.$fts_fb_id.'/';
			break;
			// case 'videos':
			// $fts_view_fb_link ='https://www.facebook.com/'.$fts_fb_id.'/videos/';
			// break;
		}
		//URL to get page info
		switch ($type) {
		case 'album_photos':
			$fb_data_cache = 'fb_'.$type.'_'.$fts_fb_id.'_'.$album_id.'_num'.$fts_limiter.'';
			break;
		default:
			$fb_data_cache = 'fb_'.$type.'_'.$fts_fb_id.'_num'.$fts_limiter.'';
			break;
		}
		if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($fb_data_cache)) and !isset($_GET['load_more_ajaxing'])) {
			$response = $this->fts_get_feed_cache($fb_data_cache);
		}
		else {
			//this check is in place because we used this option and it failed for many people because we use wp get contents instead of curl
			// this can be removed in a future update and just keep the $language_option = get_option('fb_language', 'en_US');
			$language_option_check = get_option('fb_language');
			if (isset($language_option_check) && $language_option_check !== 'Please Select Option') {
				$language_option = get_option('fb_language', 'en_US');
			}
			else {
						$language_option = 'en_US';
			}
			$language = !empty($language_option) ? '&locale='.$language_option : '';
			//URL to get Feeds
			if ($type == 'page' && $posts_displayed == 'page_only') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$fts_fb_id.'?access_token='.$access_token.$language.'');
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$fts_fb_id.'/posts?limit='.$fts_limiter.'&access_token='.$access_token.$language.'';
			}
			elseif ($type == 'events') {
				date_default_timezone_set(get_option('fts-timezone'));
				$date = date('Y-m-d');
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$fts_fb_id.'?access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$fts_fb_id.'/events?since='.$date.'&access_token='.$access_token.$language.'';

			}
			elseif ($type == 'albums') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$fts_fb_id.'?access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$fts_fb_id.'/albums?limit='.$fts_limiter.'&access_token='.$access_token.$language.'';
			}
			elseif ($type == 'album_photos') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$fts_fb_id.'?access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$album_id.'/photos?limit='.$fts_limiter.'&access_token='.$access_token.$language.'';
			}
			elseif ($type == 'hashtag') {
				$mulit_data = array(
					'page_data' => 'https://graph.facebook.com/search?q=%23'.$fts_fb_id.'&access_token='.$access_token.$language.''
				);
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/search?q=%23'.$fts_fb_id.'&limit='.$fts_limiter.'&access_token='.$access_token.$language.'';
				//Check If Ajax next URL needs to be used
			}
			// elseif ($type == 'videos') {
			//  $mulit_data['feed_data'] = 'https://graph.facebook.com/'.$fts_fb_id.'/videos/uploaded?access_token='.$access_token.'';
			//  }
			elseif ($type == 'group') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$fts_fb_id.'?access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$fts_fb_id.'/feed?limit='.$fts_limiter.'&access_token='.$access_token.$language.'';
			}
			else {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$fts_fb_id.'?access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$fts_fb_id.'/feed?limit='.$fts_limiter.'&access_token='.$access_token.$language.'';
			}
			$response = $this->fts_get_feed_json($mulit_data);
			//Make sure it's not ajaxing
			if (!isset($_GET['load_more_ajaxing']) && !empty($response['feed_data'])) {
				//Create Cache
				$this->fts_create_feed_cache($fb_data_cache, $response);
			}
		} // end main else
		//Json decode data and build it from cache or response
		$des = json_decode($response['page_data']);
		$data = json_decode($response['feed_data']);
		//If events array Flip it so it's in proper order
		if ($type == 'events') {
			//echo '<pre>';
			//print_r($data->data);
			//echo '</pre>';
						
				if($data->data){
					
				usort($data->data, function($a, $b) {
					    $a = strtotime($a->start_time);
				    $b = strtotime($b->start_time);
					    return (($a == $b) ? (0) : (($a > $b) ? (1) : (-1)));
					});
				//	 $data->data = array_reverse($data->data);
					
							}
		}
		// return error if no data retreived
		if (!isset($data->data) || empty($data->data)) {
			//If Error msg.
			if (isset($data->error->message)) $output = 'Error: '.$data->error->message;
			if (isset($data->error->type)) $output .= '<br />Type: '.$data->error->type;
			if (isset($data->error->code)) $output .= '<br />Code: '.$data->error->code;
			if (isset($data->error->error_subcode)) $output .= '<br />Subcode:'.$data->error->error_subcode;
			//If just code.
			if (isset($data->error_msg)) $output = 'Error: '.$data->error_msg;
			if (isset($data->error_code) ) $output .= '<br />Code: '.$data->error_code;
			if (!$output && $type !== 'events') $output = 'No Posts Found. Are you sure this is a Facebook Page ID and not a Facebook Group or Event ID?';
			return $type == 'events' ? '' : '<div style="clear:both; padding:15px 0;">'.$output.'</div>';
		}
		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			$_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10).'_'.$type);
			//Create Dynamic Class Name
			$fts_dynamic_class_name =  '';
			if (isset($_REQUEST['fts_dynamic_name'])) {
				$fts_dynamic_class_name =  'feed_dynamic_class'.$_REQUEST['fts_dynamic_name'];
			}
				//******************
				// SOCIAL BUTTON
				//****************** 
				if(isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display' && $fb_show_follow_btn_where == 'fb-like-top-above-title' && $type !== 'event' && $type !== 'group'){
					 	echo '<div class="fb-social-btn-top">';
						$this->social_follow_button('facebook', $id, $access_token);
						echo '</div>';
				}
			// so we can remove the fts-jal-fb-header for our special album view
			if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				$des->description = isset($des->description) ? $des->description : "";
				// fts-fb-header-wrapper
				if ($fts_grid !== 'yes' && $type !== 'album_photos' && $type !== 'albums') {  print '<div class="fts-fb-header-wrapper">'; }
				print '<div class="fts-jal-fb-header">';
				// Print our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.
				if ($title == 'yes' or $title == '') {
					print '<h1><a href="'.$fts_view_fb_link.'" target="_blank">'.$des->name.'</a></h1>';
				}
				if ($description == 'yes' || $description == '') {
					print '<div class="fts-jal-fb-group-header-desc">'.$this->fts_facebook_tag_filter($des->description).'</div>';
				}
				print '</div>';
				// Close fts-fb-header-wrapper
				if ($fts_grid !== 'yes' && $type !== 'album_photos' && $type !== 'albums') {  print '</div>'; }
			}
			else {
				$des->description = isset($des->description) ? $des->description : "";
				print '<div class="fts-jal-fb-header"><h1><a href="'.$fts_view_fb_link.'" target="_blank">'.$des->name.'</a></h1>';
				print '<div class="fts-jal-fb-group-header-desc">'.$this->fts_facebook_tag_filter($des->description).'</div>';
				print '</div><div class="clear"></div>';
			}
		} //End check
				//******************
				// SOCIAL BUTTON
				//****************** 
				if(isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display' && $fb_show_follow_btn_where == 'fb-like-top-below-title' && $type !== 'event' && $type !== 'group'){
						echo '<div class="fb-social-btn-below-description">';
						$this->social_follow_button('facebook', $id, $access_token);
						echo '</div>';
				}
		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			$fts_grid = isset($fts_grid) ? $fts_grid : "";
			if (!isset($FBtype) && $type == 'albums' || !isset($FBtype) && $type == 'album_photos' || $fts_grid == 'yes'  ) {
		 	wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js'), array( 'jquery' ) );
			?>
		<script>
		 jQuery(window).load(function(){
			 jQuery('.<?php echo $fts_dynamic_class_name ?>').masonry({
              // select the items we want to mason
             itemSelector: '.fts-jal-single-fb-post'
            });
		 });
        </script>
	<?php if (!isset($FBtype) && $type == 'albums' || !isset($FBtype) && $type == 'album_photos' ) {  ?>
        <div class="fts-slicker-facebook-photos fts-slicker-facebook-albums masonry js-masonry popup-gallery-fb <?php echo $fts_dynamic_class_name ?>" style="margin:auto" data-masonry-options='{ "isFitWidth": <?php if ($center_container == 'no') { ?>false<?php } else {?>true<?php } if ($image_stack_animation == 'no') { ?>, "transitionDuration": 0<?php } ?> }'>
	<?php } ?>
        <?php if ($fts_grid == 'yes') { ?>
          <div class="fts-slicker-facebook-posts masonry js-masonry <?php if ($fts_fb_popup == 'yes') { ?>popup-gallery-fb-posts <?php } echo $fts_dynamic_class_name ?>" style="margin:auto" data-masonry-options='{ "isFitWidth": <?php if ($center_container == 'no') { ?>false<?php } else {?>true<?php } if ($image_stack_animation == 'no') { ?>, "transitionDuration": 0<?php } ?> }'>
          <?php
				}
			}
			else {
?>
        <div class="fts-jal-fb-group-display fts-simple-fb-wrapper <?php if ($fts_fb_popup == 'yes') { ?>popup-gallery-fb-posts <?php } echo $fts_dynamic_class_name ?><?php if ($height !== 'auto' && empty($height) == NULL) {?> fts-fb-scrollable<?php } ?>" <?php if ($height !== 'auto' && empty($height) == NULL) {?>style="height:<?php echo $height; ?>"<?php } ?>> <?php }
		} //End ajaxing Check
		$fb_post_data_cache = 'fb_'.$type.'_post_'.$fts_fb_id.'_num'.$fts_limiter.'';
		if (file_exists($fb_post_data_cache) && !filesize($fb_post_data_cache) == 0 && filemtime($fb_post_data_cache) > time() - 900 && false !== strpos($fb_post_data_cache, '-num'.$fts_limiter.'' ) && !isset($_GET['load_more_ajaxing']) && $developer_mode !== 'on') {
			$response_post_array = $this->fts_get_feed_cache($fb_post_data_cache);
		}
		else {
			//Build the big post counter.
			$fb_post_array = array();
			//Single Events Array 
			$fb_single_events_array = array();
			$set_zero = 0;
			foreach ($data->data as $counter) {
				if ($set_zero==$fts_limiter)
					break;
					if ($type == 'events') {
						$single_event_id = $counter->id;
						$language = isset($language) ? $language : '';
						//Event Info
						$fb_single_events_array['event_single_'.$single_event_id.'_info'] = 'https://graph.facebook.com/'.$single_event_id.'/?access_token='.$access_token.$language;
						//Event Info
						$fb_single_events_array['event_single_'.$single_event_id.'_location'] = 'https://graph.facebook.com/'.$single_event_id.'/?fields=place&access_token='.$access_token.$language;
						//Event Cover Photo
						$fb_single_events_array['event_single_'.$single_event_id.'_cover_photo'] = 'https://graph.facebook.com/'.$single_event_id.'/?fields=cover&access_token='.$access_token.$language;
						//Event Ticket Info
						$fb_single_events_array['event_single_'.$single_event_id.'_ticket_info'] = 'https://graph.facebook.com/'.$single_event_id.'/?fields=ticket_uri&access_token='.$access_token.$language;
					}
					else{
						$FBtype = isset($counter->type) ? $counter->type : "";
						if (isset($counter->object_id)) {
							$post_data_key = $counter->object_id;
						}
						else {
							$post_data_key = $counter->id;
						}
						//Likes & Comments
						$fb_post_array[$post_data_key.'_likes'] = 'https://graph.facebook.com/'.$post_data_key.'/likes?summary=1&access_token='.$access_token;
						$fb_post_array[$post_data_key.'_comments'] = 'https://graph.facebook.com/'.$post_data_key.'/comments?summary=1&access_token='.$access_token;
						//Video
						if ($FBtype == 'video') {
							$fb_post_array[$post_data_key.'_video'] = 'https://graph.facebook.com/'.$post_data_key;
						}
						//Photo
						$FBalbum_cover = isset($counter->cover_photo) ? $counter->cover_photo : "";
						if ($type == 'albums' && !$FBalbum_cover) {
							unset($counter);
							continue;
						}
						if ($type == 'albums') {
							$fb_post_array[$FBalbum_cover.'_photo'] = 'https://graph.facebook.com/'.$FBalbum_cover;
						}
						if ($type == 'hashtag') {
							$fb_post_array[$post_data_key.'_photo'] = 'https://graph.facebook.com/'.$counter->source;
						}
					}
			}
			//Response
			$response_post_array = $this->fts_get_feed_json($fb_post_array);
			//Make sure it's not ajaxing
			if (!isset($_GET['load_more_ajaxing'])) {
				//Create Cache
				$this->fts_create_feed_cache($fb_post_data_cache, $response_post_array);
			}
		} //End else
		//Single event info call 
		$single_event_array_response = $this->fts_get_feed_json($fb_single_events_array);
		$set_zero = 0;
		//THE MAIN FEED
		foreach ($data->data as $d) {
			if ($set_zero==$fts_limiter)
				break;
			//Create Facebook Variables
			$FBfinalstory ='';
			$first_dir ='';
			$FBtype = isset($d->type) ? $d->type : "";
			if (!$FBtype && $type == 'album_photos') {
				$FBtype = 'photo';
			}
			if (!$FBtype && $type == 'events') {
				$FBtype = 'events';
			}
			$FBpicture = isset($d->picture) ? $d->picture : "";
			$FBlink = isset($d->link) ? $d->link : "";
			$FBname = isset($d->name) ? $d->name : "";
			$FBcaption = isset($d->caption) ? $d->caption : "";
			$FBmessage = isset($d->message) ? $d->message : "";
			$FBdescription = isset($d->description) ? $d->description : "";
			$FBstory = isset($d->story) ? $d->story : "";
			$FBicon = isset($d->icon) ? $d->icon : "";
			$FBby = isset($d->properties->text) ? $d->properties->text : "";
			$FBbylink = isset($d->properties->href) ? $d->properties->href : "";
			$FBpost_share_count = isset($d->shares->count) ? $d->shares->count : "";
			$FBpost_like_count_array = isset($d->likes->data) ? $d->likes->data : "";
			$FBpost_comments_count_array = isset($d->comments->data) ? $d->comments->data : "";
			$FBpost_object_id = isset($d->object_id) ? $d->object_id : "";
			$FBalbum_photo_count = isset($d->count) ? $d->count : "";
			$FBalbum_cover = isset($d->cover_photo) ? $d->cover_photo : "";
			if ($FBalbum_cover) {
				$photo_data = json_decode($response_post_array[$FBalbum_cover.'_photo']);
			}
			if (isset($d->id)) {
				$FBpost_id = $d->id;
				$FBpost_full_ID = explode('_', $FBpost_id);
				if (isset($FBpost_full_ID[0])) {
					$FBpost_user_id = $FBpost_full_ID[0];
				}
				if (isset($FBpost_full_ID[1])) {
					$FBpost_single_id = $FBpost_full_ID[1];
				}
			}
			if ($type == 'albums' && !$FBalbum_cover) {
				unset($d);
				continue;
			}
			//Create Post Data Key
			if (isset($d->object_id)) {
				$post_data_key = $d->object_id;
			}
			else {
				$post_data_key = $d->id;
			}
			//Get Likes & Comments
			if ($response_post_array) {
				if (isset($response_post_array[$post_data_key.'_likes'])) {
					$like_count_data  = json_decode($response_post_array[$post_data_key.'_likes']);
					//Like Count
					if (!empty($like_count_data->summary->total_count)) {
						$FBpost_like_count = $like_count_data->summary->total_count;
					}
					else {
						$FBpost_like_count = 0;
					}
					if ($FBpost_like_count == '0') {
						$final_FBpost_like_count = "";
					}
					if ($FBpost_like_count == '1') {
						$final_FBpost_like_count = "<i class='icon-thumbs-up'></i> 1";
					}
					if ($FBpost_like_count > '1') {
						$final_FBpost_like_count = "<i class='icon-thumbs-up'></i> " . $FBpost_like_count;
					}
				}
				if (isset($response_post_array[$post_data_key.'_comments'])) {
					$comment_count_data  = json_decode($response_post_array[$post_data_key.'_comments']);
					if (!empty($comment_count_data->summary->total_count)) {
						$FBpost_comments_count = $comment_count_data->summary->total_count;
					}
					else {
						$FBpost_comments_count = 0;
					}
					if ($FBpost_comments_count == '0') {
						$final_FBpost_comments_count = "";
					}
					if ($FBpost_comments_count == '1') {
						$final_FBpost_comments_count = "<i class='icon-comments'></i> 1";
					}
					if ($FBpost_comments_count > '1') {
						$final_FBpost_comments_count = "<i class='icon-comments'></i> " . $FBpost_comments_count;
					}
				}
			}
			//Shares Count
			if ($FBpost_share_count == '0' or !$FBpost_share_count) {
				$final_FBpost_share_count = "";
			}
			if ($FBpost_share_count == '1') {
				$final_FBpost_share_count = "<i class='icon-file'></i> 1";
			}
			if ($FBpost_share_count > '1') {
				$final_FBpost_share_count = "<i class='icon-file'></i> " . $FBpost_share_count;
			}
			$FBlocation = isset($d->location) ? $d->location : "";
			$FBembed_vid = isset($d->embed_html) ? $d->embed_html : "";
			$FBfromName = isset($d->from->name) ? $d->from->name : "";
			$FBfromName = preg_quote($FBfromName, "/");;
			$FBstory = isset($d->story) ? $d->story : "";
			$CustomDateCheck = get_option('fts-date-and-time-format');
			if ($CustomDateCheck) {
				$CustomDateFormat = get_option('fts-date-and-time-format');
			}
			else {
				$CustomDateFormat = 'F jS, Y \a\t g:ia';
			}
			$d->created_time = isset($d->created_time) ? $d->created_time : '';
			$CustomTimeFormat = strtotime($d->created_time);
			if (!empty($FBstory)) {
				$FBfinalstory  = preg_replace('/\b'.$FBfromName.'s*?\b(?=([^"]*"[^"]*")*[^"]*$)/i', '', $FBstory, 1);
			}
			switch ($FBtype) {
			case 'video'  :
				print '<div class="fts-jal-single-fb-post fts-fb-video-post-wrap" ';
				$fts_grid = isset($fts_grid) ? $fts_grid : "";
				if ($fts_grid == 'yes') {
					print 'style="width:'.$fts_colmn_width.'; margin:'.$space_between_posts.'"';
				}
				print '>';
				break;
			case 'app':
			case 'cover':
			case 'profile':
			case 'mobile':
			case 'wall':
			case 'normal':
			case 'photo':
				print ' <div class="fts-jal-single-fb-post  fts-fb-photo-post-wrap" ';
				if ($type == 'album_photos' || $type == 'albums') {
					print 'style="width:'.$image_width.'; height:'.$image_height.'; margin:'.$space_between_photos.'"';
				}
				if ($fts_grid == 'yes') {
					print 'style="width:'.$fts_colmn_width.'; margin:'.$space_between_posts.'"';
				}
				print '>';
				break;
			case 'album':
			default:
				print '<div class="fts-jal-single-fb-post" ';
				if ($fts_grid == 'yes') {
					print 'style="width:'.$fts_colmn_width.'; margin:'.$space_between_posts.'"';
				}
				print '>';
				break;
			}
				//Don't print if Events Feed
				if($type !== 'events'){
					print '<div class="fts-jal-fb-right-wrap">';
					if ($type == 'album_photos' && $hide_date_likes_comments == 'yes' || $type == 'albums' && $hide_date_likes_comments == 'yes') { }
					else {
						print '<div class="fts-jal-fb-top-wrap">';
					}
					print '<div class="fts-jal-fb-user-thumb">';
					print '<a href="http://facebook.com/profile.php?id='.$d->from->id.'" target="_blank"><img border="0" alt="'.$d->from->name.'" src="https://graph.facebook.com/'.$d->from->id.'/picture"/></a>';
					print '</div>';
					if ($type == 'album_photos' && $hide_date_likes_comments == 'yes' || $type == 'albums' && $hide_date_likes_comments == 'yes') { }
					else {
						date_default_timezone_set(get_option('fts-timezone'));
						
						$fb_hide_shared_by_etc_text = get_option('fb_hide_shared_by_etc_text');
		   	$fb_hide_shared_by_etc_text = isset($fb_hide_shared_by_etc_text) && $fb_hide_shared_by_etc_text == 'no' ? '' : $FBfinalstory;
						
						print '<span class="fts-jal-fb-user-name"><a href="http://facebook.com/profile.php?id='.$d->from->id.'" target="_blank">'.$d->from->name.'</a>'. $fb_hide_shared_by_etc_text .'</span>';
						print '<span class="fts-jal-fb-post-time">'.date($CustomDateFormat, $CustomTimeFormat).'</span><div class="clear"></div>';
						//Comments Count
						$FBpost_id_final = substr($FBpost_id, strpos($FBpost_id, "_") + 1);
						//filter messages to have urls
						//Output Message
						if ($FBmessage) {
							if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
								// here we trim the words for the premium version. The $words string actually comes from the javascript
								if ($words) {
									$more = isset($more) ? $more : "";
									$trimmed_content = $this->fts_custom_trim_words($FBmessage, $words, $more);
									print '<div class="fts-jal-fb-message">'.$trimmed_content.'';
									if ($fts_fb_popup == 'yes') {
										print '<div class="fts-fb-caption"><a href="'.$FBlink.'" class="fts-view-on-facebook-link" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div> ';
									}
									print '</div><div class="clear"></div> ';
								}
								else {
									$FB_final_message = $this->fts_facebook_tag_filter($FBmessage);
									print '<div class="fts-jal-fb-message">'.nl2br($FB_final_message).'';
									if ($fts_fb_popup == 'yes') {
										print '<div class="fts-fb-caption"><a href="'.$FBlink.'" class="fts-view-on-facebook-link" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div> ';
									}
									print '</div><div class="clear"></div> ';
								}
							} //END is_plugin_active
							// if the premium plugin is not active we will just show the regular full description
							else {
								$FB_final_message = $this->fts_facebook_tag_filter($FBmessage);
								print '<div class="fts-jal-fb-message">'.nl2br($FB_final_message).'</div><div class="clear"></div> ';
							}
						}//END Output Message
						elseif (!$FBmessage && $type == 'album_photos' || !$FBmessage && $type == 'albums') {
					
							print '<div class="fts-jal-fb-description-wrap">';
							if ($FBname) {
										$words = isset($words) ? $words : "";
								print $this->fts_facebook_post_desc($FBname, $words, $FBtype, NULL, $FBby, $type);
							};
							//Output Photo Caption
							if ($FBcaption) {
								print $this->fts_facebook_post_cap($FBcaption, $words, $FBtype);
							};
							if ($FBalbum_photo_count) {
								print $FBalbum_photo_count.' Photos';
							};
							if ($FBlocation) {
								print $this->fts_facebook_location($FBtype, $FBlocation);
							}
							//Output Photo Description
							if ($FBdescription) {
								print $this->fts_facebook_post_desc($FBdescription, $words, $FBtype, NULL, $FBby);
							};
							//Output Photo Description
							if (isset($fts_fb_popup) && $fts_fb_popup == 'yes') {
								print '<div class="fts-fb-caption fts-fb-album-view-link" style="display:block;">';
								if ($FBalbum_cover) {
									print '<a href="'.$photo_data->images[0]->source.'" class="fts-view-album-photos-large" target="_blank">'.__('View Photo', 'feed-them-social').'</a></div>';
								}
								else {
									print '<a href="https://graph.facebook.com/'.$FBpost_id.'/picture" class="fts-view-album-photos-large" target="_blank">'.__('View Photo', 'feed-them-social').'</a></div>';
								}
								print '<div class="fts-fb-caption"><a class="view-on-facebook-albums-link" href="'.$FBlink.'" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div>';
							};
							print '<div class="clear"></div></div>';
						}
					print '</div>'; // end .fts-jal-fb-top-wrap
				}
			}; //end if for show name date and comments
			//Post Type Build
			switch ($FBtype) {	
			//**************************************************
			// START STATUS POST
			//**************************************************
			case 'status':
				//  && !$FBpicture == '' makes it so the attachment unavailable message does not show up
				if (!$FBpicture && !$FBname && !$FBdescription && !$FBpicture == '' ) {
					print '<div class="fts-jal-fb-link-wrap">';
					//Output Link Picture
					if ($FBpicture) {
						print $this->fts_facebook_post_photo($FBlink, $FBtype, $d->from->name, $d->picture);
					};
					if ($FBname || $FBcaption || $FBdescription) {
						print '<div class="fts-jal-fb-description-wrap">';
						//Output Link Name
						if ($FBname) {
							print $this->fts_facebook_post_name($FBlink, $FBname, $FBtype);
						};
						//Output Link Caption
						if ($FBcaption  == 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' ) {
							print '<div class="fts-jal-fb-caption" style="width:100% !important">';
							_e('This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social');
							print '</div>';
						}
						else {
							print $this->fts_facebook_post_cap($FBcaption, $words, $FBtype);
						};
						//Output Link Description
						if ($FBdescription) {
							print $this->fts_facebook_post_desc($FBdescription, $words, $FBtype);
						};
						print '<div class="clear"></div></div>';
					}
					print '<div class="clear"></div></div>';
				}
				break;
			//**************************************************
			// Start Multiple Events
			//**************************************************
			case 'events': 
				$single_event_id = $d->id;
				$single_event_info = json_decode($single_event_array_response['event_single_'.$single_event_id.'_info']);
				$single_event_location = json_decode($single_event_array_response['event_single_'.$single_event_id.'_location']);
				$single_event_cover_photo = json_decode($single_event_array_response['event_single_'.$single_event_id.'_cover_photo']);
				//echo'<pre>';
				//print_r($single_event_info);
				//echo'</pre>';
				//Event Cover Photo
				$event_cover_photo = $single_event_cover_photo->cover->source;
				$event_description = $single_event_info->description;
					print '<div class="fts-jal-fb-right-wrap fts-events-list-wrap">';
					//Link Picture
					$FB_event_name = isset($single_event_info->name) ? $single_event_info->name : "";
					$FB_event_location = isset($single_event_location->place->name) ? $single_event_location->place->name : "";
					$FB_event_city = isset($single_event_location->place->location->city) ? $single_event_location->place->location->city.', ' : "";
					$FB_event_state = isset($single_event_location->place->location->state) ? $single_event_location->place->location->state : "";
					$FB_event_street = isset($single_event_location->place->location->street) ? $single_event_location->place->location->street : "";
					$FB_event_zip = isset($single_event_location->place->location->zip) ? ' '.$single_event_location->place->location->zip : "";
					$FB_event_latitude = isset($single_event_location->place->location->latitude) ? $single_event_location->place->location->latitude : "";
					$FB_event_longitude = isset($single_event_location->place->location->longitude) ? $single_event_location->place->location->longitude : "";
					date_default_timezone_set(get_option('fts-timezone'));
					$FB_event_start_time = date($CustomDateFormat, strtotime($single_event_info->start_time));
					//Output Photo Description
					if (isset($fts_fb_popup) && $fts_fb_popup == 'yes' && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
							print '<a href="'.$event_cover_photo.'" class="fts-jal-fb-picture fts-fb-large-photo" target="_blank"><img class="fts-fb-event-photo" src="'.$event_cover_photo.'"></a>';
							}
					else {
							print '<a href="http://facebook.com/events/'.$single_event_id.'" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img class="fts-fb-event-photo" src="'.$event_cover_photo.'" /></a>';	
						}
					print '<div class="fts-jal-fb-message">';
					//Link Name
					if ($FB_event_name) {
						print $this->fts_facebook_post_name('http://facebook.com/events/'.$single_event_id.'', $FB_event_name, $FBtype);
					};
					//Link Caption
					if ($FB_event_start_time) {
						print '<div class="fts-fb-event-time">'.$FB_event_start_time.'</div>';
					};
					//Link Description
					if (!empty($FB_event_location)) {
						print '<div class="fts-fb-location"><span class="fts-fb-location-title">'.$FB_event_location.'</span>';
							print $FB_event_street;
							if ($FB_event_city or $FB_event_state) {
								print '<br/>'.$FB_event_city.$FB_event_state.$FB_event_zip;
							}
						print '</div>';
					}
					//Get Directions
					if (!empty($FB_event_latitude) && !empty($FB_event_longitude)) {					
						print '<a target="_blank" class="fts-fb-get-directions" href="https://www.google.com/maps/dir/Current+Location/'.$FB_event_latitude.','.$FB_event_longitude.'
">Get Directions</a>';
					}
					if (!empty($single_event_ticket_info) && !empty($single_event_ticket_info)) {					
						print '<a target="_blank" class="fts-fb-ticket-info" href="'.$single_event_ticket_info->ticket_uri.'">Ticket Info</a>';
					}
					//Output Message
							if (!empty($words) && $single_event_info->description && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
								// here we trim the words for the premium version. The $words string actually comes from the javascript
									print $this->fts_facebook_post_desc($event_description, $words, $FBtype, NULL, $FBby, $type);
							} //END is_plugin_active
							// if the premium plugin is not active we will just show the regular full description
							else {
								print $this->fts_facebook_post_desc($event_description, $FBtype, NULL, $FBby, $type);
							}
					print '<div class="clear"></div></div>';
			break;
			//**************************************************
			// START LINK POST
			//**************************************************
			case 'link':
				print '<div class="fts-jal-fb-link-wrap">';
				//start url check
				$url = $FBlink;
				$url_parts = parse_url($url);
				$host = $url_parts['host'];
				if ($host == 'www.facebook.com') {
					$spliturl= $url_parts['path'];
					$path_components = explode('/', $spliturl);
					$first_dir = $path_components[1];
					$event_id_number = $path_components[2];
				}
				//end url check
				if ($host == 'www.facebook.com' and $first_dir == 'events') {
					$event_url = 'https://graph.facebook.com/'.$event_id_number.'/?access_token='.$access_token.'';
					$event_data = json_decode(file_get_contents($event_url));
					$FB_event_name = isset($event_data->name) ? $event_data->name : "";
					$FB_event_location = isset($event_data->location) ? $event_data->location : "";
					$FB_event_city = isset($event_data->venue->city) ? $event_data->venue->city : "";
					$FB_event_state = isset($event_data->venue->state) ? $event_data->venue->state : "";
					date_default_timezone_set(get_option('fts-timezone'));
					$date = date('Y-m-d');
					$FB_event_start_time = date($CustomDateFormat, strtotime($event_data->start_time));
					echo '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-picture"><img class="fts-fb-event-photo" src="http://graph.facebook.com/'.$event_id_number.'/picture"></img></a>';
					print '<div class="fts-jal-fb-description-wrap">';
					//Output Link Name
					if ($FB_event_name) {
						print $this->fts_facebook_post_name($FBlink, $FB_event_name, $FBtype);
					};
					//Output Link Caption
					if ($FB_event_start_time) {
						print '<div class="fts-fb-event-time">'.$FB_event_start_time.'</div>';
					};
					//Output Link Description
					if (!empty($FB_event_location)) {
						print '<div class="fts-fb-location">'.$FB_event_location;
						if ($FB_event_city or $FB_event_state) {
							print ' in '.$FB_event_city.', '.$FB_event_state.'';
						}
						print '</div>';
					};
					print '<div class="clear"></div></div>';
				}//end if event
				//Output Link Picture
				if ($FBpicture) {
					print $this->fts_facebook_post_photo($FBlink, $FBtype, $d->from->name, $d->picture);
				};
				$words = isset($words) ? $words : "";
				print '<div class="fts-jal-fb-description-wrap">';
				//Output Link Name
				if ($FBname) {
					print $this->fts_facebook_post_name($FBlink, $FBname, $FBtype);
				};
				//Output Link Caption
				if ($FBcaption) {
					print $this->fts_facebook_post_cap($FBcaption, $words, $FBtype);
				};
				//Output Link Description
				if ($FBdescription) {
					print $this->fts_facebook_post_desc($FBdescription, $words, $FBtype);
				};
				print '<div class="clear"></div></div>';
				print '<div class="clear"></div></div>';
				break;
			//**************************************************
			// START VIDEO POST
			//**************************************************
			case 'video'  :
				$video_data = json_decode($response_post_array[$post_data_key.'_video']);
				print '<div class="fts-jal-fb-vid-wrap">';
				if (!empty($FBpicture)) {
					if ((strpos($FBlink, 'facebook') > 0)) {
						if (!empty($video_data->format)) {
							foreach ($video_data->format as $video_data_format) {
								if ($video_data_format->filter == 'native') {
									print '<div class="fts-fluid-videoWrapper-html5">';
									print '<video controls poster="'.$video_data_format->picture.'" width="100%;" style="max-width:100%;" >';
									print '<source src="'.$video_data->source.'" type="video/mp4">';
									print '</video>';
									print '</div>';
								}
							}
							print '<div class="slicker-facebook-album-photoshadow"></div>';
						}
					}
					else {
						//Create Dynamic Class Name
						$fts_dynamic_vid_name_string = trim($this->rand_string(10).'_'.$type);
						$fts_dynamic_vid_name =  'feed_dynamic_video_class'.$fts_dynamic_vid_name_string;
						print '<div class="fts-jal-fb-vid-picture '.$fts_dynamic_vid_name.'"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/> <div class="fts-jal-fb-vid-play-btn"></div></div>';
						//strip Youtube URL then ouput Iframe and script
						if (strpos($FBlink, 'youtube') > 0) {
							$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
							preg_match($pattern, $FBlink, $matches);
							$youtubeURLfinal = $matches[1];
							print '<script>';
							print 'jQuery(document).ready(function() {';
							print 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
							print 'jQuery(this).addClass("fts-vid-div");';
							print 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							print 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$FBpost_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';
							if ($fts_grid == 'yes') {
								print 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
								print 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
							}
							print '});';
							print '});';
							print '</script>';
						}
						//strip Youtube URL then ouput Iframe and script
						else if (strpos($FBlink, 'youtu.be') > 0) {
								$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
								preg_match($pattern, $FBlink, $matches);
								$youtubeURLfinal = $matches[1];
								print '<script>';
								print 'jQuery(document).ready(function() {';
								print 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
								print 'jQuery(this).addClass("fts-vid-div");';
								print 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
								print 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$FBpost_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';
								if ($fts_grid == 'yes') {
									print 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
									print 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
								}
								print '});';
								print '});';
								print '</script>';
							}
						//strip Vimeo URL then ouput Iframe and script
						else if (strpos($FBlink, 'vimeo') > 0) {
								$pattern = '/(\d+)/';
								preg_match($pattern, $FBlink, $matches);
								$vimeoURLfinal = $matches[0];
								print '<script>';
								print 'jQuery(document).ready(function() {';
								print 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
								print 'jQuery(this).addClass("fts-vid-div");';
								print 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
								print 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=1" class="video'.$FBpost_id.'" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';
								if ($fts_grid == 'yes') {
									print 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
									print 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
								}
								print '});';
								print '});';
								print '</script>';
							}
						else if (strpos($FBlink, 'soundcloud') > 0) {
								//Get the SoundCloud URL
								$url = $FBlink;
								//Get the JSON data of song details with embed code from SoundCloud oEmbed
								$getValues=file_get_contents('http://soundcloud.com/oembed?format=js&url='.$url.'&auto_play=true&iframe=true');
								//Clean the Json to decode
								$decodeiFrame=substr($getValues, 1, -2);
								//json decode to convert it as an array
								$jsonObj = json_decode($decodeiFrame);
								//Change the height of the embed player if you want else uncomment below line
								// echo str_replace('height="400"', 'height="140"', $jsonObj->html);
								print '<script>';
								print 'jQuery(document).ready(function() {';
								print 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
								print 'jQuery(this).addClass("fts-vid-div");';
								print 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
								print '	jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper">'.$jsonObj->html.'</div>\');';
								if ($fts_grid == 'yes') {
									print 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
									print 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
								}
								print '});';
								print '});';
								print '</script>';
							}
					}
				}
				if ($FBname || $FBcaption || $FBdescription) {
					print '<div class="fts-jal-fb-description-wrap fb-id'.$FBpost_id.'">';
					//Output Video Name
					if ($FBname) {
						print $this->fts_facebook_post_name($FBlink, $FBname, $FBtype, $FBpost_id);
					};
					//Output Video Caption
					if ($FBcaption) {
						$words = isset($words) ? $words : '';
						print $this->fts_facebook_post_cap($FBcaption, $words, $FBtype, $FBpost_id);
					};
					//Output Video Description
					if ($FBdescription) {
						$words = isset($words) ? $words : '';
						print $this->fts_facebook_post_desc($FBdescription, $words, $FBtype, $FBpost_id);
					};
					print '<div class="clear"></div></div>';
				}
				print '<div class="clear"></div></div>';
				break;
			//**************************************************
			//START PHOTO POST
			//**************************************************
			case 'photo'  :
				print '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
				if ($type == 'album_photos' || $type == 'albums') {
					print 'style="line-height:'.$image_height.' !important;"';
				}
				print '>';
				$fts_fb_popup = isset($fts_fb_popup) ? $fts_fb_popup : "";
				if ($fts_fb_popup == 'yes') {
					print '<div class="fts-fb-caption"><a href="'.$FBlink.'" class="fts-view-on-facebook-link" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div> ';
				}
				//Output Photo Picture
				if ($FBpost_object_id) {
					if ($FBpost_object_id) {
						print '<a href="';
						if ($fts_fb_popup == 'yes') {
							print 'https://graph.facebook.com/'.$FBpost_object_id.'/picture';
						}
						else {
							print $FBlink;
						}
						print '" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="'.$d->from->name.'" src="https://graph.facebook.com/'.$FBpost_object_id.'/picture"></a>';
					}
					else {
						print '<a href="';
						if ($fts_fb_popup == 'yes') {
							print 'https://graph.facebook.com/'.$FBpost_object_id.'/picture';
						}
						else {
							print $FBlink;
						}
						print '" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="'.$d->from->name.'" src="https://graph.facebook.com/'.$FBpost_id.'/picture"></a>';
					}
				}
				elseif ($FBpicture) {
					if ($FBpost_object_id) {
						print $this->fts_facebook_post_photo($FBlink, $type, $d->from->name, 'https://graph.facebook.com/'.$FBpost_object_id.'/picture', $image_position_lr, $image_position_top);
					}
					else {
						print $this->fts_facebook_post_photo($FBlink, $type, $d->from->name, 'https://graph.facebook.com/'.$FBpost_id.'/picture', $image_position_lr, $image_position_top);
					}
				};
				print '<div class="slicker-facebook-album-photoshadow"></div>';
				if (!$type == 'album_photos') {
					print '<div class="fts-jal-fb-description-wrap" style="display:none">';
					//Output Photo Name
					if ($FBname) {
						print $this->fts_facebook_post_name($FBlink, $FBname, $FBtype);
					};
					//Output Photo Caption
					if ($FBcaption) {
						print $this->fts_facebook_post_cap($FBcaption, $words, $FBtype);
					};
					//Output Photo Description
					if ($FBdescription) {
						print $this->fts_facebook_post_desc($FBdescription, $words, $FBtype, NULL, $FBby);
					};
					print '<div class="clear"></div></div>';
				}
				print '<div class="clear"></div></div>';
				break;
			//**************************************************
			// START ALBUM POST
			//**************************************************
			case 'app':
			case 'cover':
			case 'profile':
			case 'mobile':
			case 'wall':
			case 'normal':
			case 'album':
				print '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
				if ($type == 'album_photos' || $type == 'albums') {
					print 'style="line-height:'.$image_height.' !important;"';
				}
				print '>';
				//Output Photo Picture
				if ($FBalbum_cover) {
					print $this->fts_facebook_post_photo($FBlink, $type, $d->from->name, $photo_data->images[0]->source, $image_position_lr, $image_position_top);
				};
				print '<div class="slicker-facebook-album-photoshadow"></div>';
				if (!$type == 'albums') {
					print '<div class="fts-jal-fb-description-wrap">';
					//Output Photo Name
					if ($FBname) {
						print $this->fts_facebook_post_name($FBlink, $FBname, $FBtype);
					};
					//Output Photo Caption
					if ($FBcaption) {
						print $this->fts_facebook_post_cap($FBcaption, $words, $FBtype);
					};
					//Output Photo Description
					if ($FBdescription) {
						print $this->fts_facebook_post_desc($FBdescription, $words, $FBtype, NULL, $FBby);
					};
					print '<div class="clear"></div></div>';
				}
				print '<div class="clear"></div></div>';
				break;
			}
			print '<div class="clear"></div>';
			print '</div>';
			$FBpost_single_id = isset($FBpost_single_id) ? $FBpost_single_id : "";
			$final_FBpost_like_count = isset($final_FBpost_like_count) ? $final_FBpost_like_count : "";
			$final_FBpost_comments_count = isset($final_FBpost_comments_count) ? $final_FBpost_comments_count : "";
			$single_event_id = isset($single_event_id) ? $single_event_id : "";
			print $this->fts_facebook_post_see_more($FBlink, $final_FBpost_like_count, $final_FBpost_comments_count, $final_FBpost_share_count, $FBtype, $FBpost_id, $type, $hide_date_likes_comments, $FBpost_user_id, $FBpost_single_id,$single_event_id);
			print '<div class="clear"></div>';
			print '</div>';
			$set_zero++;
		}
		//******************
		//Load More BUTTON Start
		//****************** 
		$build_shortcode = '[fts facebook';
		foreach ($atts as $attribute => $value) {
			$build_shortcode .= ' '.$attribute.'='.$value;
		}
		$build_shortcode .= ']';
		$_REQUEST['next_url'] = isset($data->paging->next) ? $data->paging->next : "";
		//If events array Flip it so it's in proper order
		if ($type == 'events') {
			
			$key_needed = isset($set_zero);
			$single_event_id = isset($data->data[$key_needed]->id);
			$single_event_info = json_decode($single_event_array_response['event_single_'.$single_event_id.'_info']);
			$FB_event_start_time = date('Y-m-d', strtotime($single_event_info->start_time));
			if(isset($FB_event_start_time) && $FB_event_start_time  !== '1969-12-31'){
				$_REQUEST['next_url'] = isset($data->paging->next) ? 'https://graph.facebook.com/'.$fts_fb_id.'/events?since='.$FB_event_start_time.'&access_token='.$access_token.$language.'' : "";
			}
			else{
				$_REQUEST['next_url'] = 'no more';
			}
		}
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
		  <?php
			
			if ($scrollMore == 'autoscroll') { ?>
			// this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode
			jQuery(".<?php echo $fts_dynamic_class_name ?>").bind("scroll",function() {
   				 if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
		 <?php }
			else { ?>
				// this is where we do CLICK function to LOADMORE if  = button in shortcode
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
				//		var fts_offset_posts = "< ?php echo $_REQUEST['next_offset'];?>";
					jQuery.ajax({
						data: {action: "my_fts_fb_load_more", next_url: nextURL_<?php echo $fts_dynamic_name ?>, fts_dynamic_name: fts_d_name, rebuilt_shortcode: build_shortcode, load_more_ajaxing: yes_ajax, fts_security: fts_security, fts_time: fts_time},
						type: 'GET',
						url: myAjaxFTS,
						success: function( data ) {
							console.log('Well Done and got this from sever: ' + data);
				 <?php if ($FBtype && $type == 'albums' || $FBtype && $type == 'album_photos' || $fts_grid == 'yes') {  ?>
					 	jQuery('.<?php echo $fts_dynamic_class_name ?>').append(data).filter('.<?php echo $fts_dynamic_class_name ?>').html();
							jQuery('.<?php echo $fts_dynamic_class_name ?>').masonry( 'reloadItems' );
						setTimeout(function() {
      // Do something after 3 seconds
      // This can be direct code, or call to some other function
	  jQuery('.<?php echo $fts_dynamic_class_name ?>').masonry( 'layout' );
     }, 500);
						if(!nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> || nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> == 'no more'){
						  jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php _e('No More Photos', 'feed-them-social') ?></div>');
						  jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').removeAttr('id');
						  jQuery(".<?php echo $fts_dynamic_class_name ?>").unbind('scroll');
						}
					<?php }
			else { ?>
						var result = jQuery('#output_<?php echo $fts_dynamic_name ?>').append(data).filter('#output_<?php echo $fts_dynamic_name ?>').html();
						jQuery('#output_<?php echo $fts_dynamic_name ?>').html(result);
						if(!nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> || nextURL_<?php echo $_REQUEST['fts_dynamic_name']; ?> == 'no more'){
						  jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').replaceWith('<div class="fts-fb-load-more no-more-posts-fts-fb"><?php _e('No More Posts', 'feed-them-social') ?></div>');
						  jQuery('#loadMore_<?php echo $fts_dynamic_name ?>').removeAttr('id');
						  jQuery(".<?php echo $fts_dynamic_class_name ?>").unbind('scroll');
						}
					<?php } ?>
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
		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
			// this div returns outputs our ajax request via jquery appenc html from above
			print '<div id="output_'.$fts_dynamic_name.'"></div>';
			if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $scrollMore == 'autoscroll') {
				print '<div id="loadMore_'.$fts_dynamic_name.'" class="fts-fb-load-more fts-fb-autoscroll-loader">Facebook</div>';
			}
		}
		print '</div>'; // closing main div for fb photos, groups etc
?>
		 <?php //only show this script if the height option is set to a number
		if ($height !== 'auto' && empty($height) == NULL) { ?>
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
											jQuery('.fts-fb-scrollable').isolatedScrollFacebookFTS();
										 </script>
									   <?php } //end $height !== 'auto' && empty($height) == NULL ?>
			<?php
		//Make sure it's not ajaxing
		if (!isset($_GET['load_more_ajaxing'])) {
			print '<div class="clear"></div><div id="fb-root"></div>';
			if (is_plugin_active('feed-them-premium/feed-them-premium.php') && $scrollMore == 'button') {
				// fts-fb-header-wrapper
				if ($fts_grid !== 'yes' && $type !== 'album_photos' && $type !== 'albums') {  print '<div class="fts-fb-load-more-wrapper">'; }
				print '<div id="loadMore_'.$fts_dynamic_name.'" class="fts-fb-load-more">'.__('Load More', 'feed-them-social').'</div>';
				if ($fts_grid !== 'yes' && $type !== 'album_photos' && $type !== 'albums') {  print '</div>'; }
			}
		}//End Check
		unset($_REQUEST['next_url']);
		//******************
		// SOCIAL BUTTON
		//****************** 
		if(isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display' && $fb_show_follow_btn_where == 'fb-like-below'){
					echo '<div class="fb-social-btn-bottom">';
					$this->social_follow_button('facebook', $id, $access_token);
					echo '</div>';
		}
		return ob_get_clean();
	}
	//**************************************************
	// Facebook Post Location
	//**************************************************
	function fts_facebook_location($FBtype = NULL, $location) {
		switch ($FBtype) {
		case 'app':
		case 'cover':
		case 'profile':
		case 'mobile':
		case 'wall':
		case 'normal':
		case 'album':
			$output = '<div class="fts-fb-location">'.$location.'</div>';
			return $output;
		}
	}
	//**************************************************
	// Facebook Post Photo
	//**************************************************
	function fts_facebook_post_photo($FBlink, $type, $photo_from, $photo_source, $image_position_lr = NULL, $image_position_top = NULL) {
		if ($type == 'album_photos' || $type == 'albums') {
			$output =  '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-picture album-photo-fts"';
			if ($image_position_lr !== '-0%' || $image_position_top !== '-0%') {
				$output .= 'style="right:'.$image_position_lr.';left:'.$image_position_lr.';top:'.$image_position_top.'"';
			}
			$output .= '><img border="0" alt="' .$photo_from.'" src="'.$photo_source.'"/></a>';
		}
		else {
			$output =  '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' .$photo_from.'" src="'.$photo_source.'"/></a>';
		}
		return $output;
	}
	//**************************************************
	// Facebook Post Name
	//**************************************************
	function fts_facebook_post_name($FBlink, $FBname, $FBtype, $FBpost_id = NULL) {
		switch ($FBtype) {
		case 'video':
			$FBname = $this->fts_facebook_tag_filter($FBname);
			$output = '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-name fb-id'.$FBpost_id.'">'.$FBname.'</a>';
			return $output;
		default:
			$FBname = $this->fts_facebook_tag_filter($FBname);
			$output = '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-name">'.$FBname.'</a>';
			return $output;
		}
	}
	//**************************************************
	// Facebook Post Description
	//**************************************************
	function fts_facebook_post_desc($FBdescription, $words, $FBtype, $FBpost_id = NULL, $FBby = NULL, $type = NULL) {
		switch ($FBtype) {
		case 'video':
			$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
			$output = '<div class="fts-jal-fb-description fb-id'.$FBpost_id.'">'.$FBdescription.'</div>';
			return $output;
		case 'photo':
			if ($type == 'album_photos') {
				if ($words) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBdescription, $words, $more);
					$output = '<div class="fts-jal-fb-description">'.$trimmed_content.'</div>';
					return $output;
				}
				else {
					$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
					$output = '<div class="fts-jal-fb-description">'.nl2br($FBdescription).'</div>';
					return $output;
				}
			}
		case 'albums':
			if ($type == 'albums') {
				if ($words) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBdescription, $words, $more);
					$output = '<div class="fts-jal-fb-description">'.$trimmed_content.'</div>';
					return $output;
				}
				else {
					$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
					$output = '<div class="fts-jal-fb-description">'.nl2br($FBdescription).'</div>';
					return $output;
				}
			}
			//Do for Default feeds
			else {
				$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
				$output = '<div class="fts-jal-fb-description">'.nl2br($FBdescription).'</div>';
				$output .= '<div>By: <a href="'.$FBlink.'">'.$FBby.'<a/></div>';
				return $output;
			}
		default:
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				// here we trim the words for the links description text... for the premium version. The $words string actually comes from the javascript
				if ($words) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBdescription, $words, $more);
					$output = '<div class="jal-fb-description">'.$trimmed_content.'</div>';
					return $output;
				}
				else {
					$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
					$output = '<div class="jal-fb-description">'.nl2br($FBdescription).'</div>';
					return $output;
				}
			} //END is_plugin_active
			// if the premium plugin is not active we will just show the regular full description
			else {
				$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
				$output = '<div class="jal-fb-description">'.nl2br($FBdescription).'</div>';
				return $output;
			}
		}
	}
	//**************************************************
	// Generate Post Caption
	//**************************************************
	function fts_facebook_post_cap($FBcaption, $words, $FBtype, $FBpost_id = NULL) {
		switch ($FBtype) {
		case 'video':
		
			
			$FBcaption = $this->fts_facebook_tag_filter(str_replace('www.', '', $FBcaption));
			
			$output = '<div class="fts-jal-fb-caption fb-id'.$FBpost_id.'">'.$FBcaption.'</div>';
			return $output;
		default:
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				
			
				// here we trim the words for the links description text... for the premium version. The $words string actually comes from the javascript
				if ($words) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBcaption, $words, $more);
					$output = '<div class="jal-fb-caption">'.$trimmed_content.'</div>';
				}
				else {
					$FBcaption = $this->fts_facebook_tag_filter($FBcaption);
					$output = '<div class="jal-fb-caption">'.nl2br($FBcaption).'</div>';
				}
			} //END is_plugin_active
			// if the premium plugin is not active we will just show the regular full description
			else {
				$FBcaption = $this->fts_facebook_tag_filter($FBcaption);
				$output = '<div class="jal-fb-caption">'.nl2br($FBcaption).'</div>';
			}
			return $output;
		}
	}
	//**************************************************
	// Generate See More Button
	//**************************************************
	function fts_facebook_post_see_more($FBlink, $final_FBpost_like_count, $final_FBpost_comments_count, $final_FBpost_share_count, $FBtype, $FBpost_id = NULL, $type, $hide_date_likes_comments, $FBpost_user_id = NULL, $FBpost_single_id = NULL, $single_event_id = null) {
		switch ($FBtype) {
		case 'events':	
			$output = '<a href="http://facebook.com/events/'.$single_event_id.'" target="_blank" class="fts-jal-fb-see-more">'.__('View on Facebook', 'feed-them-social').'</a>';
			return $output;
		case 'photo':
			$output = '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-see-more">';
			if ($type == 'album_photos' && $hide_date_likes_comments == 'yes') { }
			else {
				$output .= ''.$final_FBpost_like_count.' '.$final_FBpost_comments_count.' '.$final_FBpost_share_count.' &nbsp;&nbsp;';
			}
			$output .='&nbsp;'.__('View on Facebook', 'feed-them-social').'</a>';
			return $output;
		case 'app':
		case 'cover':
		case 'profile':
		case 'mobile':
		case 'wall':
		case 'normal':
		case 'album':
		case 'events':
			$output = '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-see-more">';
			if ($type = 'albums' && $hide_date_likes_comments == 'yes') { }
			else {
				$output .= ''.$final_FBpost_like_count.' '.$final_FBpost_comments_count.' &nbsp;&nbsp;';
			}
			$output .='&nbsp;'.__('View on Facebook', 'feed-them-social').'</a>';
			return $output;
		default:
			$output = '<a href="http://facebook.com/'.$FBpost_user_id.'/posts/'.$FBpost_single_id.'" target="_blank" class="fts-jal-fb-see-more">';
			$output .= ''.$final_FBpost_like_count.' '.$final_FBpost_comments_count.' &nbsp;&nbsp;&nbsp;'.__('View on Facebook', 'feed-them-social').'</a>';
			return $output;
		}
	}
	//**************************************************
	// Trim Word
	//**************************************************
	function fts_custom_trim_words( $text, $num_words = 45, $more) {
		$more = __( '...' );
		$text = nl2br($text);
		//Filter for Hashtags and Mentions Before returning.
		$text= $this->fts_facebook_tag_filter($text);
		$text = strip_shortcodes($text);
		// Add tags that you don't want stripped
		$text = strip_tags( $text, '<strong><br><em><i><a>' );
		$words_array = preg_split( "/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY );
		$sep = ' ';
		if ( count( $words_array ) > $num_words ) {
			array_pop( $words_array );
			$text = implode( $sep, $words_array );
			$text = $text . $more;
		} else {
			$text = implode( $sep, $words_array );
		}
		return wpautop( $text );
	}
	//**************************************************
	// Tags Filter (return clean tags)
	//**************************************************
	function fts_facebook_tag_filter($FBdescription) {
		//Converts URLs to Links
		$FBdescription = preg_replace('@(?!(?!.*?<a)[^<]*<\/a>)(?:(?:https?|ftp|file)://|www\.|ftp\.)[-A-‌​Z0-9+&#/%=~_|$?!:,.]*[A-Z0-9+&#/%=~_|$]@i', '<a href="\0" target="_blank">\0</a>', $FBdescription);
		// Mentions
		$FBdescription = preg_replace('/(?<!\S)@([0-9a-zA-Z]+)/', '<a target="_blank" href="http://facebook.com/$1">@$1</a>', $FBdescription);
		//Hash tags
		$FBdescription = preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a target="_blank" href="http://facebook.com/hashtag/$1">#$1</a>', $FBdescription);
		return $FBdescription;
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
}// FTS_Facebook_Feed END CLASS
?>