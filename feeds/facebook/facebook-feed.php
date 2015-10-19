<?php namespace feedthemsocial;
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
		// masonry snippet in fts-global
		wp_enqueue_script( 'fts-global', plugins_url( 'feed-them-social/feeds/js/fts-global.js'), array( 'jquery' ) );
		$developer_mode = 'on';
		//Make sure everything is reset
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		//Eventually add premium page file
		if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
			$FTS_Facebook_Reviews = new FTS_Facebook_Reviews();
			$review_atts = $FTS_Facebook_Reviews->shortcode_attributes();
			$FB_Shortcode =  shortcode_atts($review_atts , $atts);
			//Load up some scripts for popup
			$this->load_popup_scripts($FB_Shortcode);
		}
		elseif (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include(WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/facebook/facebook-premium-feed.php');
			//Load up some scripts for popup
			$this->load_popup_scripts($FB_Shortcode);
		}
		else {
			$FB_Shortcode =  shortcode_atts( array(
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
							), $atts );
			$FB_Shortcode['posts'] = '5';
		}
		//Get Access Token
		$access_token = $this->get_access_token();
		//UserName?
		if (!$FB_Shortcode['id']) { return 'Please enter a username for this feed.';}
		if ($FB_Shortcode['type'] == 'reviews' && !is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {return '<div style="clear:both; padding:15px 0;">You must have FTS Facebook Reviews extension active to see this feed.</div>';}
		ob_start();
		//View Link
		$fts_view_fb_link = $this->get_view_link($FB_Shortcode);
		//Get Cache Name
		$fb_cache_name = $this->get_fb_cache_name($FB_Shortcode);
		//Get language
		$language = $this->get_language($FB_Shortcode);
		//Get Response (AKA Page & Feed Information) ERROR CHECK inside this function
		$response = $this->get_facebook_feed_response($FB_Shortcode, $fb_cache_name, $access_token, $language);
		//Json decode data and build it from cache or response
		$page_data = json_decode($response['page_data']);
		$feed_data = json_decode($response['feed_data']);
		
		//If No Response or Error then return
		if($response == false){return;}
					
			if (is_plugin_active('feed-them-premium/feed-them-premium.php') && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {		
			//Make sure it's not ajaxing and we will allow the omition of certain album covers from the list by using omit_album_covers=0,1,2,3 in the shortcode
				if (!isset($_GET['load_more_ajaxing'])) {		
					if ($FB_Shortcode['type'] == 'albums') {
						// omit_album_covers=0,1,2,3 for example
						$omit_album_covers = $FB_Shortcode['omit_album_covers'];
						$omit_album_covers_new = array();
						$omit_album_covers_new = explode(',', $omit_album_covers);
						foreach ($feed_data->data as $post_data) {
							foreach ($omit_album_covers_new as $omit) {
								unset($feed_data->data[$omit]);
							}
						}
					}
				}
				//Reviews Rating Filter
				if ($FB_Shortcode['type'] == 'reviews') {
					foreach ($feed_data->data as $key => $post_data) {
						if($post_data->rating < $FB_Shortcode['reviews_type_to_show']){
							unset($feed_data->data[$key]);
						}
					}
				}
			}
				//echo'<pre>';
				//	print_r($feed_data);
				//echo'</pre>';
			//If events array Flip it so it's in proper order		
			if ($FB_Shortcode['type'] == 'events') {		
				if($feed_data->data){
					usort($feed_data->data, function($a, $b) {
					    $a = strtotime($a->start_time);
					    $b = strtotime($b->start_time);
						    return (($a == $b) ? (0) : (($a > $b) ? (1) : (-1)));
					});
				//	 $feed_data->data = array_reverse($feed_data->data);	
				}
			}
			
			
	
			$FTS_FB_OUTPUT = '';
			//Make sure it's not ajaxing
			if (!isset($_GET['load_more_ajaxing'])) {
				//Get Response (AKA Page & Feed Information)
				$_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10).'_'.$FB_Shortcode['type']);
				//Create Dynamic Class Name
				$fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
				//******************
				// SOCIAL BUTTON
				//******************
				$FTS_FB_OUTPUT .= $this->fb_social_btn_placement($FB_Shortcode, $access_token, 'fb-like-top-above-title');
				$page_data->description = isset($page_data->description) ? $page_data->description : "";
				$page_data->name = isset($page_data->name) ? $page_data->name : "";
				// fts-fb-header-wrapper (for grid)
				$FTS_FB_OUTPUT .= isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] !== 'yes' ? '<div class="fts-fb-header-wrapper">' : '';
					//Header 
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-header">';
						// $FTS_FB_OUTPUT .= our Facebook Page Title or About Text. Commented out the group description because in the future we will be adding the about description.
						$FTS_FB_OUTPUT .= isset($FB_Shortcode['title']) && $FB_Shortcode['title'] == 'yes' || isset($FB_Shortcode['title']) && $FB_Shortcode['title'] == '' ? '<h1><a href="'.$fts_view_fb_link.'" target="_blank">'.$page_data->name.'</a></h1>' : '';
						//Description
						$FTS_FB_OUTPUT .= isset($FB_Shortcode['description']) && $FB_Shortcode['description'] == 'yes' || isset($FB_Shortcode['description']) && $FB_Shortcode['description'] == '' ? '<div class="fts-jal-fb-group-header-desc">'.$this->fts_facebook_tag_filter($page_data->description).'</div>' : '';
					//END Header
					$FTS_FB_OUTPUT .= '</div>';
				// Close fts-fb-header-wrapper
				$FTS_FB_OUTPUT .= isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] !== 'yes' && $FB_Shortcode['type'] !== 'album_photos' && $FB_Shortcode['type'] !== 'albums' ? '</div>' : ''; 
			} //End check
			//******************
			// SOCIAL BUTTON
			//******************
			$FTS_FB_OUTPUT .= $this->fb_social_btn_placement($FB_Shortcode, $access_token, 'fb-like-top-below-title');
			//*********************
			// Feed Header
			//*********************
			//Make sure it's not ajaxing
				if (!isset($_GET['load_more_ajaxing'])) {
						if (!isset($FBtype) && $FB_Shortcode['type'] == 'albums' || !isset($FBtype) && $FB_Shortcode['type'] == 'album_photos' || isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
								if(isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes' ){ } else {
								wp_enqueue_script( 'fts-masonry-pkgd', plugins_url( 'feed-them-social/feeds/js/masonry.pkgd.min.js'), array( 'jquery' ) ); 
				$FTS_FB_OUTPUT .='<script>';
						 $FTS_FB_OUTPUT .='jQuery(window).load(function(){';
							 $FTS_FB_OUTPUT .='jQuery(".'.$fts_dynamic_class_name.'").masonry({';
				             $FTS_FB_OUTPUT .='itemSelector: ".fts-jal-single-fb-post"';
				            $FTS_FB_OUTPUT .='});';
						 $FTS_FB_OUTPUT .='});';
				        $FTS_FB_OUTPUT .='</script>';
				       } 
			if (!isset($FBtype) && $FB_Shortcode['type'] == 'albums' || !isset($FBtype) && $FB_Shortcode['type'] == 'album_photos' ) {  
	$FTS_FB_OUTPUT .= '<div class="fts-slicker-facebook-photos fts-slicker-facebook-albums '.(isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] && $FB_Shortcode['video_album'] == 'yes' ? 'popup-video-gallery-fb' : ' popup-gallery-fb masonry js-masonry').' '.(isset($FB_Shortcode['images_align']) && $FB_Shortcode['images_align'] ? ' popup-video-gallery-align-'.$FB_Shortcode['images_align'] : '').' popup-gallery-fb '.$fts_dynamic_class_name.'" style="margin:auto;" data-masonry-options=\'{ "isFitWidth": '.($FB_Shortcode['center_container'] == 'no' ? 'false' : 'true') .' '.($FB_Shortcode['image_stack_animation'] == 'no' ? ', "transitionDuration": 0' : '').'}\'>';
			}
				if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') { 
	$FTS_FB_OUTPUT .='<div class="fts-slicker-facebook-posts masonry js-masonry '.($FB_Shortcode['popup'] == 'yes' ? 'popup-gallery-fb-posts ' : '').($FB_Shortcode['type'] == 'reviews' ? 'fts-reviews-feed ' : '').$fts_dynamic_class_name.' " style="margin:auto;" data-masonry-options=\'{ "isFitWidth": '.($FB_Shortcode['center_container'] == 'no' ? 'false' : 'true').' '.($FB_Shortcode['image_stack_animation'] == 'no' ? ', "transitionDuration": 0' : '').'}\'>';
	            }
				}
				else { 
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-group-display fts-simple-fb-wrapper '.(isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? 'popup-gallery-fb-posts ' :'').($FB_Shortcode['type'] == 'reviews' ? 'fts-reviews-feed ' : '').$fts_dynamic_class_name.' '.($FB_Shortcode['height'] !== 'auto' && empty($FB_Shortcode['height']) == NULL ? 'fts-fb-scrollable" style="height:'.$FB_Shortcode['height'].'"' : '"').'>';
				}
			} //End ajaxing Check
			//*********************
			// Post Information
			//*********************
			$response_post_array = $this->get_post_info($feed_data, $FB_Shortcode,$access_token, $language);
			//Single event info call
			if ($FB_Shortcode['type'] == 'events') {
				$single_event_array_response = $this->fts_get_feed_json($response_post_array);
			}
			$set_zero = 0;
			//THE MAIN FEED		
			foreach ($feed_data->data as $post_data) {
				//Define Type NOTE Also affects Load More Fucntion call
				$FBtype = isset($post_data->type) ? $post_data->type : "";
				if (!$FBtype && $FB_Shortcode['type'] == 'album_photos') {
					$FBtype = 'photo';
				}
				if (!$FBtype && $FB_Shortcode['type'] == 'events') {
					$FBtype = 'events';
				}
				$post_types = new FTS_Facebook_Feed_Post_Types();
				$single_event_array_response = isset($single_event_array_response) ? $single_event_array_response : '';
				$FTS_FB_OUTPUT .=  $post_types->feed_post_types($set_zero, $FBtype, $post_data, $FB_Shortcode, $response_post_array, $single_event_array_response);
				$set_zero++;
			}// END POST foreach
			if(is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews'){
				$FTS_FB_OUTPUT .= $this->fts_facebook_loadmore($atts,$feed_data,$FBtype,$FB_Shortcode,$_REQUEST['fts_dynamic_name']);
			}
			
			$FTS_FB_OUTPUT .= '</div>'; // closing main div for fb photos, groups etc
	//only show this script if the height option is set to a number
			if ($FB_Shortcode['height'] !== 'auto' && empty($FB_Shortcode['height']) == NULL) { 
				$FTS_FB_OUTPUT .= '<script>';
					// this makes it so the page does not scroll if you reach the end of scroll bar or go back to top'
					$FTS_FB_OUTPUT .= 'jQuery.fn.isolatedScrollFacebookFTS = function() {';
							$FTS_FB_OUTPUT .= 'this.bind("mousewheel DOMMouseScroll", function (e) {';
							$FTS_FB_OUTPUT .= 'var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.detail,';
								$FTS_FB_OUTPUT .= 'bottomOverflow = this.scrollTop + jQuery(this).outerHeight() - this.scrollHeight >= 0,';
								$FTS_FB_OUTPUT .= 'topOverflow = this.scrollTop <= 0;';
							$FTS_FB_OUTPUT .= 'if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {';
								$FTS_FB_OUTPUT .= 'e.preventDefault();';
							$FTS_FB_OUTPUT .= '}';
						$FTS_FB_OUTPUT .= '});';
						$FTS_FB_OUTPUT .= 'return this;';
					$FTS_FB_OUTPUT .= '};';
					$FTS_FB_OUTPUT .= 'jQuery(".fts-fb-scrollable").isolatedScrollFacebookFTS();';
				$FTS_FB_OUTPUT .= '</script>';
	       } //end $FB_Shortcode['height'] !== 'auto' && empty($FB_Shortcode['height']) == NULL 
			//Make sure it's not ajaxing
			if (!isset($_GET['load_more_ajaxing'])) {
				$FTS_FB_OUTPUT .= '<div class="clear"></div><div id="fb-root"></div>';
				if(is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews'){
					if ($FB_Shortcode['loadmore'] == 'button') {
						// fts-fb-header-wrapper
						if ($FB_Shortcode['grid'] !== 'yes') {  $FTS_FB_OUTPUT .= '<div class="fts-fb-load-more-wrapper">'; }
						
						
						$FTS_FB_OUTPUT .= '<div id="loadMore_'.$_REQUEST['fts_dynamic_name'].'" style="';
						if ($FB_Shortcode['loadmore_btn_maxwidth'] !== '') {  $FTS_FB_OUTPUT .= 'max-width:'.$FB_Shortcode['loadmore_btn_maxwidth'].';'; }
						$FTS_FB_OUTPUT .= 'margin:'.$FB_Shortcode['loadmore_btn_margin'].' auto '.$FB_Shortcode['loadmore_btn_margin'].'" class="fts-fb-load-more">'.__('Load More', 'feed-them-social').'</div>';
						if ($FB_Shortcode['grid'] !== 'yes') {  $FTS_FB_OUTPUT .= '</div>'; }
					}
				}
			}//End Check
			unset($_REQUEST['next_url']);
			//******************
			// SOCIAL BUTTON
			//******************
			$FTS_FB_OUTPUT .= $this->fb_social_btn_placement($FB_Shortcode, $access_token, 'fb-like-below');
			$FTS_FB_OUTPUT .= ob_get_clean();
			return $FTS_FB_OUTPUT;	
	}
	function get_fts_dynamic_class_name(){
		$fts_dynamic_class_name =  '';
		if (isset($_REQUEST['fts_dynamic_name'])) {
			$fts_dynamic_class_name =  'feed_dynamic_class'.$_REQUEST['fts_dynamic_name'];
		}
		return $fts_dynamic_class_name;
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
	function fts_facebook_post_photo($FBlink, $FB_Shortcode, $photo_from, $photo_source) {
		if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
			$output =  '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-picture album-photo-fts"';
			if ($FB_Shortcode['image_position_lr'] !== '-0%' || $FB_Shortcode['image_position_top'] !== '-0%') {
				$output .= 'style="right:'.$FB_Shortcode['image_position_lr'].';left:'.$FB_Shortcode['image_position_lr'].';top:'.$FB_Shortcode['image_position_top'].'"';
			} 
						if ($FB_Shortcode['type'] == 'albums') {
					$output .= '><img border="0" alt="' .$photo_from.'" src="https://graph.facebook.com/'.$photo_source.'/picture"/>';
						}
						else {
					$output .= '><img border="0" alt="' .$photo_from.'" src="'.$photo_source.'"/>';
						}
		$output .= '</a>';
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
	function fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, $FBpost_id = NULL, $FBby = NULL) {
		switch ($FBtype) {
		case 'video':
			$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
			$output = '<div class="fts-jal-fb-description fb-id'.$FBpost_id.'">'.$FBdescription.'</div>';
			return $output;
		case 'photo':
			if ($FB_Shortcode['type'] == 'album_photos') {
				if (array_key_exists('words',$FB_Shortcode)) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
					$output = '<div class="fts-jal-fb-description">'.$trimmed_content.'</div>';
					return $output;
				}
				elseif(isset($FB_Shortcode['words']) && $FB_Shortcode['words'] !== '0') {
					$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
					$output = '<div class="fts-jal-fb-description">'.nl2br($FBdescription).'</div>';
					return $output;
				}
			}
		case 'albums':
			if ($FB_Shortcode['type']  == 'albums') {
				if (array_key_exists('words',$FB_Shortcode)) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
					$output = '<div class="fts-jal-fb-description">'.$trimmed_content.'</div>';
					return $output;
				}
				else {
					$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
					$output = '<div class="fts-jal-fb-description">'.nl2br($FBdescription).'</div>';
					return $output;
				}
			}
			//Do for Default feeds or the video gallery feed
			else {
				$FBdescription = $this->fts_facebook_tag_filter($FBdescription);
				if (is_array($FB_Shortcode) && array_key_exists('words',$FB_Shortcode) && $FB_Shortcode['words'] !== '0') {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
					$output = '<div class="fts-jal-fb-description">'.$trimmed_content.'</div>';
				}
				else {
					$output = '<div class="fts-jal-fb-description">';
					$output .= nl2br($FBdescription); 
					$output .= '</div>';
				}
				if (!empty($FBlink)) {
				 $output .= '<div>By: <a href="'.$FBlink.'">'.$FBby.'<a/></div>';
				}
				if(isset($FB_Shortcode['words']) && $FB_Shortcode['words'] !== '0') {	
				 return $output;
				}
			}
		default:
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				// here we trim the words for the links description text... for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
				if (is_array($FB_Shortcode) && array_key_exists('words',$FB_Shortcode)) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBdescription, $FB_Shortcode['words'], $more);
					$output = '<div class="jal-fb-description">'.$trimmed_content.'</div>';
					return $output;
				}
				elseif(is_array($FB_Shortcode) && array_key_exists('words',$FB_Shortcode) && $FB_Shortcode['words'] !== '0') {
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
	function fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype, $FBpost_id = NULL) {
		
		switch ($FBtype) {
		case 'video':
			$FBcaption = $this->fts_facebook_tag_filter(str_replace('www.', '', $FBcaption));
			$output = '<div class="fts-jal-fb-caption fb-id'.$FBpost_id.'">'.$FBcaption.'</div>';
				return $output;
		default:
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				// here we trim the words for the links description text... for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
				if (array_key_exists('words',$FB_Shortcode)) {
					$more = isset($more) ? $more : "";
					$trimmed_content = $this->fts_custom_trim_words($FBcaption, $FB_Shortcode['words'], $more);
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
	// Get Like/Shares/Comments Total Count
	//**************************************************
	function get_likes_shares_comments($response_post_array, $post_data_key, $FBpost_share_count){
		$LSC_array = array();
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
					$LSC_array['likes'] = "";
				}
				if ($FBpost_like_count == '1') {
					$LSC_array['likes'] = "<i class='icon-thumbs-up'></i> 1";
				}
				if ($FBpost_like_count > '1') {
					$LSC_array['likes'] = "<i class='icon-thumbs-up'></i> " . $FBpost_like_count;
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
					$LSC_array['comments'] = "";
				}
				if ($FBpost_comments_count == '1') {
					$LSC_array['comments'] = "<i class='icon-comments'></i> 1";
				}
				if ($FBpost_comments_count > '1') {
					$LSC_array['comments'] = "<i class='icon-comments'></i> " . $FBpost_comments_count;
				}
			}
		}
		//Shares Count
		if ($FBpost_share_count == '0' or !$FBpost_share_count) {
			$LSC_array['shares'] = "";
		}
		if ($FBpost_share_count == '1') {
			$LSC_array['shares'] = "<i class='icon-file'></i> 1";
		}
		if ($FBpost_share_count > '1') {
			$LSC_array['shares'] = "<i class='icon-file'></i> " . $FBpost_share_count;
		}	
		return $LSC_array; 		
	}
	//**************************************************
	// Generate See More Button
	//**************************************************
	function fts_facebook_post_see_more($FBlink, $lcs_array, $FBtype, $FBpost_id = NULL, $FB_Shortcode, $FBpost_user_id = NULL, $FBpost_single_id = NULL, $single_event_id = null,$post_data) {		
			
		switch ($FBtype) {
		case 'events':	
			$output = '<a href="http://facebook.com/events/'.$single_event_id.'" target="_blank" class="fts-jal-fb-see-more">'.__('View on Facebook', 'feed-them-social').'</a>';
			return $output;
		case 'photo':
		if (!empty($FBlink)) {
			$output = '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-see-more">';
		}
		// exception for videos
		else {
			$output = '<a href="http://facebook.com/'.$FBpost_id.'/" target="_blank" class="fts-jal-fb-see-more">';
		}
			if ($FB_Shortcode['type'] == 'album_photos' && $FB_Shortcode['hide_date_likes_comments'] == 'yes') { }
			else {
				$output .= ''.$lcs_array['likes'].' '.$lcs_array['comments'].' '.$lcs_array['shares'].' &nbsp;&nbsp;';
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
			if ($FB_Shortcode['type'] = 'albums' && $FB_Shortcode['hide_date_likes_comments'] == 'yes') { }
			else {
				$output .= ''.$lcs_array['likes'].' '.$lcs_array['comments'].' &nbsp;&nbsp;';
			}
			$output .='&nbsp;'.__('View on Facebook', 'feed-them-social').'</a>';
			return $output;
		default:
			
		if ($FB_Shortcode['type'] == 'reviews' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')){
			$output = '';
			$output .= ' <a href="https://facebook.com/'.$FB_Shortcode['id'].'/reviews" target="_blank" class="fts-jal-fb-see-more">'.__('See More Reviews', 'feed-them-social').'</a>';
		}	
		else{
			$output = '<a href="https://facebook.com/'.$FBpost_user_id.'/posts/'.$FBpost_single_id.'" target="_blank" class="fts-jal-fb-see-more">';
			$output .= ''.$lcs_array['likes'].' '.$lcs_array['comments'].' &nbsp;&nbsp;&nbsp;'.__('View on Facebook', 'feed-them-social').'</a>';
		}
			
			return $output;
		}
	}
	//**************************************************
	// Get Access Token
	//**************************************************
	function get_access_token(){
		//API Access Token
		$custom_access_token = get_option('fts_facebook_custom_api_token');
		if (!empty($custom_access_token)) {
			$access_token = get_option('fts_facebook_custom_api_token');
			return $access_token;
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
			return $access_token;
		}
	}
	//**************************************************
	// Get View Link
	//**************************************************
	function get_view_link($FB_Shortcode) {
		switch ($FB_Shortcode['type']) {
			case 'group' :
				$fts_view_fb_link ='https://www.facebook.com/groups/'.$FB_Shortcode['id'].'/';
				break;
			case 'page':
				$fts_view_fb_link ='https://www.facebook.com/'.$FB_Shortcode['id'].'/';
				break;
			case 'event' :
				$fts_view_fb_link ='https://www.facebook.com/events/'.$FB_Shortcode['id'].'/';
				break;
			case 'events' :
				$fts_view_fb_link ='https://www.facebook.com/'.$FB_Shortcode['id'].'/events/';
				break;
			case 'albums':
				$fts_view_fb_link ='https://www.facebook.com/'.$FB_Shortcode['id'].'/photos_stream?tab=photos_albums';
				break;
				// album photos and videos album
			case 'album_photos':
				$fts_view_fb_link = isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes' ?  'https://www.facebook.com/'.$FB_Shortcode['id'].'/videos/' : 'https://www.facebook.com/'.$FB_Shortcode['id'].'/photos_stream/';
				break;
			case 'hashtag':
				$fts_view_fb_link ='https://www.facebook.com/hashtag/'.$FB_Shortcode['id'].'/';
				break;
		    case 'reviews':
			   $fts_view_fb_link ='https://www.facebook.com/'.$FB_Shortcode['id'].'/reviews/';
			   break;
		}
		return $fts_view_fb_link;
	}
	//**************************************************
	// Get Feed Cache Name
	//**************************************************
	function get_fb_cache_name($FB_Shortcode){
		//URL to get page info
		switch ($FB_Shortcode['type']) {
			case 'album_photos':
				$fb_data_cache_name = 'fb_'.$FB_Shortcode['type'].'_'.$FB_Shortcode['id'].'_'.$FB_Shortcode['album_id'].'_num'.$FB_Shortcode['posts'].'';
				break;
			default:
				$fb_data_cache_name = 'fb_'.$FB_Shortcode['type'].'_'.$FB_Shortcode['id'].'_num'.$FB_Shortcode['posts'].'';
				break;
		}
		return $fb_data_cache_name;
	}		
	//**************************************************
	// Get Language
	//**************************************************
	function get_language() {
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
		return $language;
	}
	//**************************************************
	// Get Facebook Feed Information
	//**************************************************
	function get_facebook_feed_response($FB_Shortcode, $fb_cache_name, $access_token, $language) {
		if (false !== ($transient_exists = $this->fts_check_feed_cache_exists($fb_cache_name)) and !isset($_GET['load_more_ajaxing'])) {
			$response = $this->fts_get_feed_cache($fb_cache_name);
		}
		else {
			//Page
			if ($FB_Shortcode['type'] == 'page' && $FB_Shortcode['posts_displayed'] == 'page_only') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$FB_Shortcode['id'].'?fields=id,name,description&access_token='.$access_token.$language.'');
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$FB_Shortcode['id'].'/posts?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit='.$FB_Shortcode['posts'].'&access_token='.$access_token.$language.'';
			}
			//Event
			elseif ($FB_Shortcode['type'] == 'events') {
				date_default_timezone_set(get_option('fts-timezone'));
				$date = date('Y-m-d');
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$FB_Shortcode['id'].'?fields=id,name&access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$FB_Shortcode['id'].'/events?since='.$date.'&access_token='.$access_token.$language.'';
			}
			//Albums
			elseif ($FB_Shortcode['type'] == 'albums') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$FB_Shortcode['id'].'?fields=id,name,description,link&access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$FB_Shortcode['id'].'/albums?fields=id,created_time,name,from,link,cover_photo,count,updated_time,type&limit='.$FB_Shortcode['posts'].'&access_token='.$access_token.$language.'';
			}
			//Album Photos
			elseif ($FB_Shortcode['type'] == 'album_photos') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$FB_Shortcode['id'].'?fields=id,name,description&access_token='.$access_token.$language.'');
					//Check If Ajax next URL needs to be used
					//The reason I did not create a whole new else if for the video album is because I did not want to duplicate all the code required to make the video because the videos gallery comes from the photo albums on facebook.
					if (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
					$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$FB_Shortcode['album_id'].'/videos?fields=id,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,to,type,format,embed_html&limit='.$FB_Shortcode['posts'].'&access_token='.$access_token.$language.'';
					}
					else {
					$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$FB_Shortcode['album_id'].'/photos?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit='.$FB_Shortcode['posts'].'&access_token='.$access_token.$language.'';
					}
			}
			//HashTag
			elseif ($FB_Shortcode['type'] == 'hashtag') {
				$mulit_data = array(
					'page_data' => 'https://graph.facebook.com/search?q=%23'.$FB_Shortcode['id'].'&access_token='.$access_token.$language.''
				);
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/search?q=%23'.$FB_Shortcode['id'].'&limit='.$FB_Shortcode['posts'].'&access_token='.$access_token.$language.'';
				//Check If Ajax next URL needs to be used
			}
			//Group
			elseif ($FB_Shortcode['type'] == 'group') {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$FB_Shortcode['id'].'?fields=id,name,description&access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$FB_Shortcode['id'].'/feed?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit='.$FB_Shortcode['posts'].'&access_token='.$access_token.$language.'';
			}
			//Reviews
			elseif ($FB_Shortcode['type'] == 'reviews') {
				if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
					$FTS_Facebook_Reviews = new FTS_Facebook_Reviews();
					$mulit_data = $FTS_Facebook_Reviews->review_connection($FB_Shortcode, $access_token, $language);
				}	
				else{
					return 'Please Purchase and Activate the Feed Them Social Reviews plugin.';
					exit;
				}
			}
			else {
				$mulit_data = array('page_data' => 'https://graph.facebook.com/'.$FB_Shortcode['id'].'?fields=id,name,description&access_token='.$access_token.$language.'');
				//Check If Ajax next URL needs to be used
				$mulit_data['feed_data'] = isset($_REQUEST['next_url']) ? $_REQUEST['next_url'] : 'https://graph.facebook.com/'.$FB_Shortcode['id'].'/feed?fields=id,caption,created_time,description,from,icon,link,message,name,object_id,picture,place,shares,source,status_type,story,to,type&limit='.$FB_Shortcode['posts'].'&access_token='.$access_token.$language.'';
			}
			$response = $this->fts_get_feed_json($mulit_data);
			
			//Error Check
			$feed_data = json_decode($response['feed_data']);
			$fts_error_check = new fts_error_handler();
			$fts_error_check_complete = $fts_error_check->facebook_error_check($FB_Shortcode, $feed_data);
			if($fts_error_check_complete == true){return false;}
			
			//Make sure it's not ajaxing
			if (!isset($_GET['load_more_ajaxing']) && !empty($response['feed_data'])) {
				//Create Cache
				$this->fts_create_feed_cache($fb_cache_name, $response);
			}
		} // end main else
		//RETURN THE RESPONSE!!!
		return $response;
	}
	//**************************************************
	// FB Dynamic Feed Name
	//**************************************************
	function get_facebook_feed_dynamic_name($FB_Shortcode){
		return $_REQUEST['fts_dynamic_name'] = trim($this->rand_string(10).'_'.$FB_Shortcode['type']);
	}
	//**************************************************
	// FB Dynamic Feed CLASS Name
	//**************************************************
	function get_facebook_feed_dynamic_class_name($fts_dynamic_name = null){
			$fts_dynamic_class_name =  '';
			if (isset($fts_dynamic_name)) {
				$fts_dynamic_class_name =  'feed_dynamic_class'.$_REQUEST['fts_dynamic_name'];
			}
			return $fts_dynamic_class_name;
	}
	//**************************************************
	// FB Get Post Info
	//**************************************************
	function get_post_info($feed_data, $FB_Shortcode,$access_token, $language) {
		$fb_post_data_cache = 'fb_'.$FB_Shortcode['type'].'_post_'.$FB_Shortcode['id'].'_num'.$FB_Shortcode['posts'].'';
		if (file_exists($fb_post_data_cache) && !filesize($fb_post_data_cache) == 0 && filemtime($fb_post_data_cache) > time() - 900 && false !== strpos($fb_post_data_cache, '-num'.$FB_Shortcode['posts'].'' ) && !isset($_GET['load_more_ajaxing']) && $developer_mode !== 'on') {
			$response_post_array = $this->fts_get_feed_cache($fb_post_data_cache);
		}
		else {
			//Build the big post counter.
			$fb_post_array = array();
			//Single Events Array
			$fb_single_events_array = array();
			$set_zero = 0;
			foreach ($feed_data->data as $counter) {
				
				$counter->id = isset($counter->id) ? $counter->id : "";
				
				if ($set_zero==$FB_Shortcode['posts'])
					break;
				if ($FB_Shortcode['type'] == 'events') {
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
				else {
					$FBtype = isset($counter->type) ? $counter->type : "";
					$post_data_key = isset($counter->object_id) ? $counter->object_id : $counter->id;
					//Likes & Comments
					$fb_post_array[$post_data_key.'_likes'] = 'https://graph.facebook.com/'.$post_data_key.'/likes?summary=1&access_token='.$access_token;
					$fb_post_array[$post_data_key.'_comments'] = 'https://graph.facebook.com/'.$post_data_key.'/comments?summary=1&access_token='.$access_token;
					//Video
					if ($FBtype == 'video') {
						$fb_post_array[$post_data_key.'_video'] = 'https://graph.facebook.com/'.$post_data_key;
					}
					//Photo
					$FBalbum_cover = isset($counter->cover_photo->id) ? $counter->cover_photo->id : "";
					if ($FB_Shortcode['type'] == 'albums' && !$FBalbum_cover) {
						unset($counter);
						continue;
					}
					if ($FB_Shortcode['type'] == 'albums') {
						$fb_post_array[$FBalbum_cover.'_photo'] = 'https://graph.facebook.com/'.$FBalbum_cover;
					}
					if ($FB_Shortcode['type'] == 'hashtag') {
						$fb_post_array[$post_data_key.'_photo'] = 'https://graph.facebook.com/'.$counter->source;
					}
				}
			}
			if ($FB_Shortcode['type'] == 'events') {
				return $fb_single_events_array;
			}
			else{
				//Response
				$response_post_array = $this->fts_get_feed_json($fb_post_array);
				//Make sure it's not ajaxing
				if (!isset($_GET['load_more_ajaxing'])) {
					//Create Cache
					$this->fts_create_feed_cache($fb_post_data_cache, $response_post_array);
				}
				return $response_post_array;
			}
		} //End else
	}
	//**************************************************
	// FB Social Button Placement
	//**************************************************
	function fb_social_btn_placement($FB_Shortcode, $access_token, $share_loc) {
		    //Don't do it for these!
			if($FB_Shortcode['type'] == 'group' || $FB_Shortcode['type'] == 'event' || isset($FB_Shortcode['hide_like_option']) && $FB_Shortcode['hide_like_option'] == 'yes') { return;}
			//Facebook Follow Button Options
			$fb_show_follow_btn = get_option('fb_show_follow_btn');
			$fb_show_follow_btn_where = get_option('fb_show_follow_btn_where');
			if (!isset($_GET['load_more_ajaxing'])) {
				$like_option_align_final = isset($FB_Shortcode['like_option_align']) ? 'fts-fb-social-btn-'.$FB_Shortcode['like_option_align'].'' : '';
				$output ='';
				if($share_loc === $fb_show_follow_btn_where)	{
					switch($fb_show_follow_btn_where){
						case 'fb-like-top-above-title':
							// Top Above Title
							if (isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display') {
								$output .= '<div class="fb-social-btn-top '.$like_option_align_final.'">';
									$output .= $this->social_follow_button('facebook', $FB_Shortcode['id'], $access_token);
								$output .= '</div>';
							}
							break;
						//Top Below Title
						case 'fb-like-top-below-title':
							if (isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display') {
								$output .= '<div class="fb-social-btn-below-description">';
									$output .= $this->social_follow_button('facebook', $FB_Shortcode['id'], $access_token);
								$output .= '</div>';
							}
							break;
						//Bottom
						case 'fb-like-below':
							if (isset($fb_show_follow_btn) && $fb_show_follow_btn !== 'dont-display') {
								$output .= '<div class="fb-social-btn-bottom '.$like_option_align_final.'">';
									$output .= $this->social_follow_button('facebook', $FB_Shortcode['id'], $access_token);
								$output .= '</div>';
							}
							break;
					}
				}
			return $output ;	
			}
	}
	//**************************************************
	// Trim Word
	//**************************************************
	function fts_custom_trim_words( $text, $num_words = 45, $more) {
		!empty($num_words) && $num_words !== 0 ? $more = __( '...' ) : '';
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
	function rand_string( $length = 10) {
			$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	//**************************************************
	// Load PopUp Scripts
	//**************************************************
	function load_popup_scripts($FB_Shortcode) {
		if ($FB_Shortcode['popup'] == 'yes') {
				// it's ok if these styles & scripts load at the bottom of the page
				$fts_fix_magnific = get_option('fts_fix_magnific') ? get_option('fts_fix_magnific') : '';
				if(isset($fts_fix_magnific) && $fts_fix_magnific !== '1'){
					wp_enqueue_style( 'fts-popup', plugins_url( 'feed-them-social/feeds/css/magnific-popup.css'));
				}
				wp_enqueue_script( 'fts-popup-js', plugins_url( 'feed-them-social/feeds/js/magnific-popup.js'));
				wp_enqueue_script( 'fts-images-loaded', plugins_url( 'feed-them-social/feeds/js/imagesloaded.pkgd.min.js' ));
				if(!isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes' ){ 	
					wp_enqueue_script( 'fts-global', plugins_url('feed-them-social/feeds/js/fts-global.js'), array( 'jquery' ));
				}
		}
	}
	//**************************************************
	// Facebook LoadMore
	//**************************************************
	function fts_facebook_loadmore($atts,$feed_data, $FBtype, $FB_Shortcode) {
		$LOADMORE_OUPUT = '';
			if ((isset($FB_Shortcode['loadmore']) && $FB_Shortcode['loadmore'] == 'button' || isset($FB_Shortcode['loadmore']) && $FB_Shortcode['loadmore'] == 'autoscroll') && (is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews')) {		
					//******************
					//Load More BUTTON Start
					//****************** 
					$build_shortcode = '[fts facebook';
					foreach ($atts as $attribute => $value) {
						$build_shortcode .= ' '.$attribute.'='.$value;
					}
					$build_shortcode .= ']';
					$_REQUEST['next_url'] = isset($feed_data->paging->next) ? $feed_data->paging->next : "";
					//If events array Flip it so it's in proper order
					if ($FB_Shortcode['type'] == 'events') {
						$key_needed = isset($set_zero);
						$single_event_id = isset($data->data[$key_needed]->id);
						$single_event_info = json_decode($single_event_array_response['event_single_'.$single_event_id.'_info']);
						$FB_event_start_time = date('Y-m-d', strtotime($single_event_info->start_time));
						if(isset($FB_event_start_time) && $FB_event_start_time  !== '1969-12-31'){
							$_REQUEST['next_url'] = isset($data->paging->next) ? 'https://graph.facebook.com/'.$FB_Shortcode['id'].'/events?since='.$FB_event_start_time.'&access_token='.$access_token.$language.'' : "";
						}
						else{
							$_REQUEST['next_url'] = 'no more';
						}
					}
			$LOADMORE_OUPUT .= '<script>';
			$LOADMORE_OUPUT .= 'var nextURL_'.$_REQUEST['fts_dynamic_name'].'= "'.$_REQUEST['next_url'].'";';
			$LOADMORE_OUPUT .= '</script>';
					//Make sure it's not ajaxing
					if (!isset($_GET['load_more_ajaxing']) && !isset($_REQUEST['fts_no_more_posts']) && !empty($FB_Shortcode['loadmore'])) {
						$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
						$time = time();
						$nonce = wp_create_nonce($time."load-more-nonce");
						$fts_fix_loadmore = get_option('fts_fix_loadmore');
						$fts_dynamic_class_name = $this->get_fts_dynamic_class_name();
			$LOADMORE_OUPUT .= '<script>';
				 $LOADMORE_OUPUT .= 'jQuery(document).ready(function() {';
						if ($FB_Shortcode['loadmore'] == 'autoscroll') { 
							// this is where we do SCROLL function to LOADMORE if = autoscroll in shortcode
							$LOADMORE_OUPUT .= 'jQuery(".'.$fts_dynamic_class_name.'").bind("scroll",function() {';
				   				 $LOADMORE_OUPUT .= 'if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {';
					    }
						else { 
							// this is where we do CLICK function to LOADMORE if  = button in shortcode
							$LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").click(function() {';
						}
								$LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").addClass("fts-fb-spinner");';
								$bounce = "<div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div>";
									$LOADMORE_OUPUT .= 'var button = jQuery("#loadMore_'.$fts_dynamic_name.'").html("'.$bounce.'");';
									$LOADMORE_OUPUT .= 'console.log(button);';
									$LOADMORE_OUPUT .= 'var build_shortcode = "'. (!empty($fts_fix_loadmore) ? '['.$build_shortcode.']' : $build_shortcode).'";';
									$LOADMORE_OUPUT .= 'var yes_ajax = "yes";';
									$LOADMORE_OUPUT .= 'var fts_d_name = "'.$fts_dynamic_name.'";';
									$LOADMORE_OUPUT .= 'var fts_security = "'.$nonce.'";';
									$LOADMORE_OUPUT .= 'var fts_time = "'.$time.'";';
								$LOADMORE_OUPUT .= 'jQuery.ajax({';
									$LOADMORE_OUPUT .= 'data: {action: "my_fts_fb_load_more", next_url: nextURL_'.$fts_dynamic_name.', fts_dynamic_name: fts_d_name, rebuilt_shortcode: build_shortcode, load_more_ajaxing: yes_ajax, fts_security: fts_security, fts_time: fts_time},';
									$LOADMORE_OUPUT .= 'type: "GET",';
									$LOADMORE_OUPUT .= 'url: myAjaxFTS,';
									$LOADMORE_OUPUT .= 'success: function( data ) {';
										$LOADMORE_OUPUT .= 'console.log("Well Done and got this from sever: " + data);';
							    if ($FBtype && $FB_Shortcode['type'] == 'albums'|| $FBtype && $FB_Shortcode['type'] == 'album_photos' && $FB_Shortcode['video_album'] !== 'yes' || $FB_Shortcode['grid'] == 'yes') {
								 	$LOADMORE_OUPUT .= 'jQuery(".'.$fts_dynamic_class_name.'").append(data).filter(".'.$fts_dynamic_class_name.'").html();';
																$LOADMORE_OUPUT .= 'jQuery(".'.$fts_dynamic_class_name.'").masonry( "reloadItems");';
															$LOADMORE_OUPUT .= 'setTimeout(function() {';
															// Do something after 3 seconds
															// This can be direct code, or call to some other function												
													$LOADMORE_OUPUT .= 'jQuery(".'.$fts_dynamic_class_name.'").masonry("layout");';
														$LOADMORE_OUPUT .= '}, 500);';
									$LOADMORE_OUPUT .= 'if(!nextURL_'.$_REQUEST['fts_dynamic_name'].' || nextURL_'.$_REQUEST['fts_dynamic_name'].' == "no more"){';
									if ($FB_Shortcode['type'] == 'reviews') {
										$LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">'.__('No More Reviews', 'feed-them-social').'</div>\');';
									} else {
										$LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">'.__('No More Photos', 'feed-them-social').'</div>\');';

									}
									
									 $LOADMORE_OUPUT .= ' jQuery("#loadMore_'.$fts_dynamic_name.'").removeAttr("id");';
									  $LOADMORE_OUPUT .= 'jQuery(".'.$fts_dynamic_class_name.'").unbind("scroll");';
									$LOADMORE_OUPUT .= '}';
							   }
						       else { 
									if(isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') { 
										$LOADMORE_OUPUT .= 'var result = jQuery(data).insertBefore( jQuery("#output_'.$fts_dynamic_name.'") );';						
										$LOADMORE_OUPUT .= 'var result = jQuery(".feed_dynamic_'.$fts_dynamic_name.'_album_photos").append(data).filter("#output_'.$fts_dynamic_name.'").html();';
									}else{
										$LOADMORE_OUPUT .= 'var result = jQuery("#output_'.$fts_dynamic_name.'").append(data).filter("#output_'.$fts_dynamic_name.'").html();';
									} 
									$LOADMORE_OUPUT .= 'jQuery("#output_'.$fts_dynamic_name.'").html(result);';
									$LOADMORE_OUPUT .= 'if(!nextURL_'.$_REQUEST['fts_dynamic_name'].' || nextURL_'.$_REQUEST['fts_dynamic_name'].' == "no more"){';
									//Reviews
									if ($FB_Shortcode['type'] == 'reviews') {
										$LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">'.__('No More Reviews', 'feed-them-social').'</div>\');';
									}else {
										$LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").replaceWith(\'<div class="fts-fb-load-more no-more-posts-fts-fb">'.__('No More Posts', 'feed-them-social').'</div>\');';
									}
									  $LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").removeAttr("id");';
									  $LOADMORE_OUPUT .= 'jQuery(".'.$fts_dynamic_class_name.'").unbind("scroll");';
									$LOADMORE_OUPUT .= '}';
								}
									$LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").html("'.__('Load More', 'feed-them-social').'");';
								 //jQuery("#loadMore_'.$fts_dynamic_name.'").removeClass("flip360-fts-load-more");
								 $LOADMORE_OUPUT .= 'jQuery("#loadMore_'.$fts_dynamic_name.'").removeClass("fts-fb-spinner");';
									$LOADMORE_OUPUT .= '}';
								$LOADMORE_OUPUT .= '});';// end of ajax()
								$LOADMORE_OUPUT .= 'return false;';
								 // string $scrollMore is at top of this js script. acception for scroll option closing tag
						if ($FB_Shortcode['loadmore'] == 'autoscroll' ) {
											$LOADMORE_OUPUT .= '}';// end of scroll ajax load.
						 }
					  $LOADMORE_OUPUT .= '});';// end of document.ready
				  $LOADMORE_OUPUT .= '});';// end of form.submit
			$LOADMORE_OUPUT .= '</script>';
					}//End Check
					// main closing div not included in ajax check so we can close the wrap at all times
					//Make sure it's not ajaxing
					if (!isset($_GET['load_more_ajaxing'])) {
						$fts_dynamic_name = $_REQUEST['fts_dynamic_name'];
						// this div returns outputs our ajax request via jquery appenc html from above  style="display:nonee;"
						$LOADMORE_OUPUT .=  '<div id="output_'.$fts_dynamic_name.'"></div>';
						if ((is_plugin_active('feed-them-premium/feed-them-premium.php') && $FB_Shortcode['type'] !== 'reviews' || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && $FB_Shortcode['type'] == 'reviews') && $FB_Shortcode['loadmore'] == 'autoscroll') {
							$LOADMORE_OUPUT .=  '<div id="loadMore_'.$fts_dynamic_name.'" class="fts-fb-load-more fts-fb-autoscroll-loader">Facebook</div>';
						}
					}
			}// end of if loadmore is button or autoscroll
			return $LOADMORE_OUPUT;
		}//End Loadmore/Scroll
}// FTS_Facebook_Feed END CLASS
?>