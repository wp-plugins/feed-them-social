<?php namespace feedthemsocial;

class FTS_Facebook_Feed_Post_Types extends FTS_Facebook_Feed {
	
	//**************************************************
	// Display Facebook Feed
	//**************************************************
	function feed_post_types($set_zero, $FBtype, $post_data, $FB_Shortcode, $response_post_array, $single_event_array_response=null) {	
			//Reviews Plugin
			if (is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php')) {
				$FTS_Facebook_Reviews = new FTS_Facebook_Reviews();
			}	
		
			$fts_dynamic_vid_name_string = trim($this->rand_string(10).'_'.$FB_Shortcode['type']);		
		
			if ($set_zero==$FB_Shortcode['posts'])
				return ;
			//Create Facebook Variables
			$FBfinalstory ='';
			$first_dir ='';
			
			$FBpicture = isset($post_data->picture) ? $post_data->picture : "";
			$FBlink = isset($post_data->link) ? $post_data->link : "";
			$FBname = isset($post_data->name) ? $post_data->name : '';
			$FBcaption = isset($post_data->caption) ? $post_data->caption : "";
			$FBmessage = (isset($post_data->message) ? $post_data->message : (isset($post_data->review_text) ? $post_data->review_text : ''). '');
			$FBdescription = isset($post_data->description) ? $post_data->description : "";
			$FBstory = isset($post_data->story) ? $post_data->story : "";
			$FBicon = isset($post_data->icon) ? $post_data->icon : "";
			$FBby = isset($post_data->properties->text) ? $post_data->properties->text : "";
			$FBbylink = isset($post_data->properties->href) ? $post_data->properties->href : "";
			$FBpost_share_count = isset($post_data->shares->count) ? $post_data->shares->count : "";
			$FBpost_like_count_array = isset($post_data->likes->data) ? $post_data->likes->data : "";
			$FBpost_comments_count_array = isset($post_data->comments->data) ? $post_data->comments->data : "";
			$FBpost_object_id = isset($post_data->object_id) ? $post_data->object_id : "";
			$FBalbum_photo_count = isset($post_data->count) ? $post_data->count : "";
			$FBalbum_cover = isset($post_data->cover_photo->id) ? $post_data->cover_photo->id : "";
			
			$FBvideo = isset($post_data->embed_html) ? $post_data->embed_html : "";
			$FBvideoPicture = isset($post_data->format[2]->picture) ? $post_data->format[2]->picture : "";
			
			if ($FBalbum_cover) {
				$photo_data = json_decode($response_post_array[$FBalbum_cover.'_photo']);
			}
			if (isset($post_data->id)) {
				$FBpost_id = $post_data->id;
				$FBpost_full_ID = explode('_', $FBpost_id);
				if (isset($FBpost_full_ID[0])) {
					$FBpost_user_id = $FBpost_full_ID[0];
				}
				if (isset($FBpost_full_ID[1])) {
					$FBpost_single_id = $FBpost_full_ID[1];
				}
			}
			if ($FB_Shortcode['type'] == 'albums' && !$FBalbum_cover) {
				unset($post_data);
				continue;
			}			
			//Create Post Data Key
			if (isset($post_data->object_id)) {
				$post_data_key = $post_data->object_id;
			}
			else {
				$post_data_key = $post_data->id;
			}
			//Count Likes/Shares/ 
			$lcs_array = $this->get_likes_shares_comments($response_post_array,$post_data_key, $FBpost_share_count);
			
			$FBlocation = isset($post_data->location) ? $post_data->location : "";
			$FBembed_vid = isset($post_data->embed_html) ? $post_data->embed_html : "";
			$FBfromName = isset($post_data->from->name) ? $post_data->from->name : "";
			$FBfromName = preg_quote($FBfromName, "/");;
			$FBstory = isset($post_data->story) ? $post_data->story : "";
			$CustomDateCheck = get_option('fts-date-and-time-format');
			if ($CustomDateCheck) {
				$CustomDateFormat = get_option('fts-date-and-time-format');
			}
			else {
				$CustomDateFormat = 'F jS, Y \a\t g:ia';
			}
			$createdTime = isset($post_data->created_time) ? $post_data->created_time : '';
			$CustomTimeFormat = strtotime($createdTime);
			if (!empty($FBstory)) {
				$FBfinalstory  = preg_replace('/\b'.$FBfromName.'s*?\b(?=([^"]*"[^"]*")*[^"]*$)/i', '', $FBstory, 1);
			}
		
			$FTS_FB_OUTPUT = '';
			switch ($FBtype) {
				case 'video'  :
				$FTS_FB_OUTPUT .= '<div class="fts-jal-single-fb-post fts-fb-video-post-wrap" ';
				if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
					$FTS_FB_OUTPUT .= 'style="width:'.$FB_Shortcode['colmn_width'].'; margin:'.$FB_Shortcode['space_between_posts'].'"';
				}
				$FTS_FB_OUTPUT .= '>';

					break;
				case 'app':
				case 'cover':
				case 'profile':
				case 'mobile':
				case 'wall':
				case 'normal':
				case 'photo':
					$FTS_FB_OUTPUT .=  '<div class="fts-jal-single-fb-post  fts-fb-photo-post-wrap" ';
					if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
						$FTS_FB_OUTPUT .=  'style="width:'.$FB_Shortcode['image_width'].' !important; height:'.$FB_Shortcode['image_height'].'!important; margin:'.$FB_Shortcode['space_between_photos'].'!important"';
					}
					if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
						$FTS_FB_OUTPUT .=  'style="width:'.$FB_Shortcode['colmn_width'].'; margin:'.$FB_Shortcode['space_between_posts'].'"';
					}
				$FTS_FB_OUTPUT .=  '>';


					break;
				case 'album':
				default:
				$FTS_FB_OUTPUT .= '<div class="fts-jal-single-fb-post" ';
					if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
						$FTS_FB_OUTPUT .= 'style="width:'.$FB_Shortcode['colmn_width'].'; margin:'.$FB_Shortcode['space_between_posts'].'"';
					}
				$FTS_FB_OUTPUT .= '>';
					break;
			}
			//output Single Post Wrap
			
			
			//Don't $FTS_FB_OUTPUT .= if Events Feed
			if ($FB_Shortcode['type'] !== 'events') {
				//Right Wrap
				$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-right-wrap">';
				//Top Wrap (Exluding : albums, album_photos, and hiding)
				$FTS_FB_OUTPUT .= $FB_Shortcode['type'] == 'album_photos' && $FB_Shortcode['hide_date_likes_comments'] == 'yes' || $FB_Shortcode['type'] == 'albums' && $FB_Shortcode['hide_date_likes_comments'] == 'yes' ? '' : '<div class="fts-jal-fb-top-wrap">';
				//User Thumbnail
				$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-user-thumb">';
				$FTS_FB_OUTPUT .= '<a href="http://facebook.com/'.($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->id : $post_data->from->id).'" target="_blank"><img border="0" alt="'.($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->name : $post_data->from->name).'" src="https://graph.facebook.com/'.($FB_Shortcode['type'] == 'reviews' ? $post_data->reviewer->id : $post_data->from->id).'/picture"/></a>';
				$FTS_FB_OUTPUT .= '</div>';
				
				if ($FB_Shortcode['type'] == 'album_photos' && $FB_Shortcode['hide_date_likes_comments'] == 'yes' || $FB_Shortcode['type'] == 'albums' && $FB_Shortcode['hide_date_likes_comments'] == 'yes') { }
				else {
					date_default_timezone_set(get_option('fts-timezone'));

					$fb_hide_shared_by_etc_text = get_option('fb_hide_shared_by_etc_text');
					$fb_hide_shared_by_etc_text = isset($fb_hide_shared_by_etc_text) && $fb_hide_shared_by_etc_text == 'no' ? '' : $FBfinalstory;
					//UserName
					$FTS_FB_OUTPUT .= $FB_Shortcode['type'] == 'reviews' && is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') ? '<span class="fts-jal-fb-user-name"><a href="http://facebook.com/'.$post_data->reviewer->id.'/" target="_blank">'.$post_data->reviewer->name.'</a>'.$FTS_Facebook_Reviews->reviews_rating_format($FB_Shortcode, $post_data->rating).'</span>' : '<span class="fts-jal-fb-user-name"><a href="http://facebook.com/profile.php?id='.$post_data->from->id.'" target="_blank">'.$post_data->from->name.'</a>'. $fb_hide_shared_by_etc_text .'</span>';
					
					//PostTime
					$FTS_FB_OUTPUT .= '<span class="fts-jal-fb-post-time">'.date($CustomDateFormat, $CustomTimeFormat).'</span><div class="clear"></div>';
					//Comments Count
					$FBpost_id_final = substr($FBpost_id, strpos($FBpost_id, "_") + 1);
					//filter messages to have urls
					//Output Message
					if ($FBmessage) {
						
						// here we trim the words for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
						if (is_plugin_active('feed-them-premium/feed-them-premium.php') && array_key_exists('words',$FB_Shortcode) || is_plugin_active('feed-them-social-facebook-reviews/feed-them-social-facebook-reviews.php') && array_key_exists('words',$FB_Shortcode)) {
							$more = isset($more) ? $more : "";
							$trimmed_content = $this->fts_custom_trim_words($FBmessage, $FB_Shortcode['words'], $more);
							
							$FTS_FB_OUTPUT .= !empty($trimmed_content) ? '<div class="fts-jal-fb-message">'.$trimmed_content.'</div><div class="clear"></div>' : '';
								//If POPUP
							//$FTS_FB_OUTPUT .= $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="'.$FBlink.'" class="fts-view-on-facebook-link" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div> ' : '';
						}
						else {
							$FB_final_message = $this->fts_facebook_tag_filter($FBmessage);
							$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-message">';
							$FTS_FB_OUTPUT .= nl2br($FB_final_message); 
								//If POPUP
						//		$FTS_FB_OUTPUT .= $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="'.$FBlink.'" class="fts-view-on-facebook-link" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div> ' : '';
							
							$FTS_FB_OUTPUT .= '<div class="clear"></div></div> ';
						}

					}//END Output Message
					elseif (!$FBmessage && $FB_Shortcode['type'] == 'album_photos' || !$FBmessage && $FB_Shortcode['type'] == 'albums') {

						$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
							
							$FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_desc($FBname, $FB_Shortcode, $FBtype, NULL, $FBby) : '';
							
						//Output Photo Caption
						$FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
						//Photo Count
						$FTS_FB_OUTPUT .= $FBalbum_photo_count ? $FBalbum_photo_count.' Photos' : '';
						//Location
						$FTS_FB_OUTPUT .= $FBlocation ? $this->fts_facebook_location($FBtype, $FBlocation) : '';
						//Output Photo Description
						$FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, NULL, $FBby) : '';
						//Output Photo Description
						if (isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes') {
							$FTS_FB_OUTPUT .= '<div class="fts-fb-caption fts-fb-album-view-link" style="display:block;">';
							if ($FBalbum_cover) {
								$FTS_FB_OUTPUT .= '<a href="https://graph.facebook.com/'.$FBalbum_cover.'/picture" class="fts-view-album-photos-large" target="_blank">'.__('View Photo', 'feed-them-social').'</a></div>';
							}
							elseif (isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {
								if ($FB_Shortcode['play_btn'] !== 'yes') {
									$FTS_FB_OUTPUT .= '<a href="'.$post_data->source.'"  data-poster="'.$post_data->format[3]->picture.'" id="fts-view-vid1-'.$fts_dynamic_vid_name_string.'" class="fts-view-fb-videos-large fts-view-fb-videos-btn fb-video-popup-'.$fts_dynamic_vid_name_string.'">'.__('View Video', 'feed-them-social').'</a>';
								}
								$FTS_FB_OUTPUT .= '</div>';
							}
							else {
								$FTS_FB_OUTPUT .= '<a href="https://graph.facebook.com/'.$FBpost_id.'/picture" class="fts-view-album-photos-large" target="_blank">'.__('View Photo', 'feed-them-social').'</a></div>';
							}
							$FTS_FB_OUTPUT .= '<div class="fts-fb-caption"><a class="view-on-facebook-albums-link" href="'.$FBlink.'" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div>';
						}
						$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
					}
					$FTS_FB_OUTPUT .= '</div>'; // end .fts-jal-fb-top-wrap
				}
			} //end if for show name date and comments			
			//Post Type Build
			switch ($FBtype) {	
			//**************************************************
			// START STATUS POST
			//**************************************************
			case 'status':
				//  && !$FBpicture == '' makes it so the attachment unavailable message does not show up
				if (!$FBpicture && !$FBname && !$FBdescription && !$FBpicture == '' ) {
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap">';
					//Output Link Picture
					$FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->picture) : '';
					
					if ($FBname || $FBcaption || $FBdescription) {
						$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
						//Output Link Name
						$FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
						//Output Link Caption
						if ($FBcaption  == 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' ) {
							$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-caption" style="width:100% !important">';
							_e('This user\'s permissions are keeping you from seeing this post. Please Click "View on Facebook" to view this post on this group\'s facebook wall.', 'feed-them-social');
							$FTS_FB_OUTPUT .= '</div>';
						}
						else {
							$FTS_FB_OUTPUT .= $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype);
						}
						//Output Link Description
						$FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype) : '';
						$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
					}
					$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				}
				break;
			//**************************************************
			// Start Multiple Events
			//**************************************************
			case 'events': 
				$single_event_id = $post_data->id;
				$single_event_info = json_decode($single_event_array_response['event_single_'.$single_event_id.'_info']);
				$single_event_location = json_decode($single_event_array_response['event_single_'.$single_event_id.'_location']);
				$single_event_cover_photo = json_decode($single_event_array_response['event_single_'.$single_event_id.'_cover_photo']);
				//echo'<pre>';
				//print_r($single_event_info);
				//echo'</pre>';
				//Event Cover Photo
				$event_cover_photo = isset($single_event_cover_photo->cover->source) ? $single_event_cover_photo->cover->source : "";
				$event_description = isset($single_event_info->description) ? $single_event_info->description : "";
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-right-wrap fts-events-list-wrap">';
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
					if (!empty($event_cover_photo)) {
						$FTS_FB_OUTPUT .= isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' && is_plugin_active('feed-them-premium/feed-them-premium.php') ? '<a href="'.$event_cover_photo.'" class="fts-jal-fb-picture fts-fb-large-photo" target="_blank"><img class="fts-fb-event-photo" src="'.$event_cover_photo.'"></a>' : '<a href="http://facebook.com/events/'.$single_event_id.'" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img class="fts-fb-event-photo" src="'.$event_cover_photo.'" /></a>' ;
					}
							
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-message">';
					//Link Name
					$FTS_FB_OUTPUT .= $FB_event_name ? $this->fts_facebook_post_name('http://facebook.com/events/'.$single_event_id.'', $FB_event_name, $FBtype) : '';
					//Link Caption
					$FTS_FB_OUTPUT .= $FB_event_start_time ? '<div class="fts-fb-event-time">'.$FB_event_start_time.'</div>' : '';
					//Link Description
					if (!empty($FB_event_location)) {
						$FTS_FB_OUTPUT .= '<div class="fts-fb-location"><span class="fts-fb-location-title">'.$FB_event_location.'</span>';
							//Street Adress
							$FTS_FB_OUTPUT .= $FB_event_street;
							//City & State
							$FTS_FB_OUTPUT .= ($FB_event_city or $FB_event_state) ? '<br/>'.$FB_event_city.$FB_event_state.$FB_event_zip : '';
						$FTS_FB_OUTPUT .= '</div>';
					}
					//Get Directions
					if (!empty($FB_event_latitude) && !empty($FB_event_longitude)) {					
						$FTS_FB_OUTPUT .= '<a target="_blank" class="fts-fb-get-directions" href="https://www.google.com/maps/dir/Current+Location/'.$FB_event_latitude.','.$FB_event_longitude.'
">Get Directions</a>';
					}
					if (!empty($single_event_ticket_info) && !empty($single_event_ticket_info)) {					
						$FTS_FB_OUTPUT .= '<a target="_blank" class="fts-fb-ticket-info" href="'.$single_event_ticket_info->ticket_uri.'">Ticket Info</a>';
					}
					//Output Message
							if (!empty($FB_Shortcode['words']) && $event_description && is_plugin_active('feed-them-premium/feed-them-premium.php')) {
								// here we trim the words for the premium version. The $FB_Shortcode['words'] string actually comes from the javascript
									$FTS_FB_OUTPUT .= $this->fts_facebook_post_desc($event_description, $FB_Shortcode, $FBtype, NULL, $FBby, $FB_Shortcode['type']);
							} //END is_plugin_active
							// if the premium plugin is not active we will just show the regular full description
							else {
								$FTS_FB_OUTPUT .= $this->fts_facebook_post_desc($event_description, $FBtype, NULL, $FBby, $FB_Shortcode['type']);
							}
					$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
			break;
			//**************************************************
			// START LINK POST
			//**************************************************
			case 'link':
				$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap">';
				//start url check
				$url = $FBlink;
				$url_parts = parse_url($url);
				$host = $url_parts['host'];
				if ($host == 'www.facebook.com') {
					$spliturl= $url_parts['path'];
					$path_components = explode('/', $spliturl);
					$first_dir = $path_components[1];
					$event_id_number = isset($path_components[2]) && $path_components[2] ? $path_components[2] : '';
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
					//Link Wrap
					$FTS_FB_OUTPUT .= '<a href="'.$FBlink.'" target="_blank" class="fts-jal-fb-picture"><img class="fts-fb-event-photo" src="http://graph.facebook.com/'.$event_id_number.'/picture"></img></a>';
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
					//Output Link Name
					$FTS_FB_OUTPUT .= $FB_event_name ? $this->fts_facebook_post_name($FBlink, $FB_event_name, $FBtype) : '';
					//Output Link Caption
					$FTS_FB_OUTPUT .= $FB_event_start_time ? '<div class="fts-fb-event-time">'.$FB_event_start_time.'</div>' : '';
					//Output Link Description
					if (!empty($FB_event_location)) {
						//Location
						$FTS_FB_OUTPUT .= '<div class="fts-fb-location">'.$FB_event_location;
						//City & State
							$FTS_FB_OUTPUT .= $FB_event_city || $FB_event_state ? ' in '.$FB_event_city.', '.$FB_event_state.'' : '';
						$FTS_FB_OUTPUT .= '</div>';
					};
					$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				}//end if event
				//Output Link Picture
				$FTS_FB_OUTPUT .= $FBpicture ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->picture) : '';
					$FB_Shortcode['words'] = isset($FB_Shortcode['words']) ? $FB_Shortcode['words'] : "";
					//Description Wrap
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
					//Output Link Name
					$FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
					//Output Link Caption
					$FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
					//Output Link Description
					$FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype) : '';
					$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				break;
			//**************************************************
			// START VIDEO POST
			//**************************************************
			case 'video'  :
				$video_data = json_decode($response_post_array[$post_data_key.'_video']);
				//Video Wrap
				$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-wrap">';
				if (!empty($FBpicture)) {
					if ((strpos($FBlink, 'facebook') > 0)) {
						if (!empty($video_data->format)) {
							foreach ($video_data->format as $video_data_format) {
								if ($video_data_format->filter == 'native') {
									$FTS_FB_OUTPUT .= '<div class="fts-fluid-videoWrapper-html5">';
									$FTS_FB_OUTPUT .= '<video controls poster="'.$video_data_format->picture.'" width="100%;" style="max-width:100%;">';
									$FTS_FB_OUTPUT .= '<source src="'.$video_data->source.'" type="video/mp4">';
									$FTS_FB_OUTPUT .= '</video>';
									$FTS_FB_OUTPUT .= '</div>';
								}
							}
							$FTS_FB_OUTPUT .= '<div class="slicker-facebook-album-photoshadow"></div>';
						}
					}
					else {
						//Create Dynamic Class Name
						$fts_dynamic_vid_name_string = trim($this->rand_string(10).'_'.$FB_Shortcode['type']);
						$fts_dynamic_vid_name =  'feed_dynamic_video_class'.$fts_dynamic_vid_name_string;
						$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-vid-picture '.$fts_dynamic_vid_name.'"><img border="0" alt="'.$post_data->from->name.'" src="'.$post_data->picture.'"/> <div class="fts-jal-fb-vid-play-btn"></div></div>';
						//strip Youtube URL then ouput Iframe and script
						if (strpos($FBlink, 'youtube') > 0) {
							$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
							preg_match($pattern, $FBlink, $matches);
							$youtubeURLfinal = $matches[1];
							$FTS_FB_OUTPUT .= '<script>';
							$FTS_FB_OUTPUT .= 'jQuery(document).ready(function() {';
							$FTS_FB_OUTPUT .= 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
							$FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
							$FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
							$FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$FBpost_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';
							if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
								$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
								$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
							}
							$FTS_FB_OUTPUT .= '});';
							$FTS_FB_OUTPUT .= '});';
							$FTS_FB_OUTPUT .= '</script>';
						}
						//strip Youtube URL then ouput Iframe and script
						else if (strpos($FBlink, 'youtu.be') > 0) {
								$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
								preg_match($pattern, $FBlink, $matches);
								$youtubeURLfinal = $matches[1];
								$FTS_FB_OUTPUT .= '<script>';
								$FTS_FB_OUTPUT .= 'jQuery(document).ready(function() {';
								$FTS_FB_OUTPUT .= 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
								$FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
								$FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
								$FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe height="281" class="video'.$FBpost_id.'" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe></div>\');';
								if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
									$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
									$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
								}
								$FTS_FB_OUTPUT .= '});';
								$FTS_FB_OUTPUT .= '});';
								$FTS_FB_OUTPUT .= '</script>';
						}
						//strip Vimeo URL then ouput Iframe and script
						else if (strpos($FBlink, 'vimeo') > 0) {
								$pattern = '/(\d+)/';
								preg_match($pattern, $FBlink, $matches);
								$vimeoURLfinal = $matches[0];
								$FTS_FB_OUTPUT .= '<script>';
								$FTS_FB_OUTPUT .= 'jQuery(document).ready(function() {';
								$FTS_FB_OUTPUT .= 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
								$FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
								$FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
								$FTS_FB_OUTPUT .= 'jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper"><iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=1" class="video'.$FBpost_id.'" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>\');';
								if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
									$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
									$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
								}
								$FTS_FB_OUTPUT .= '});';
								$FTS_FB_OUTPUT .= '});';
								$FTS_FB_OUTPUT .= '</script>';
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
								$FTS_FB_OUTPUT .= '<script>';
								$FTS_FB_OUTPUT .= 'jQuery(document).ready(function() {';
								$FTS_FB_OUTPUT .= 'jQuery(".'.$fts_dynamic_vid_name.'").click(function() {';
								$FTS_FB_OUTPUT .= 'jQuery(this).addClass("fts-vid-div");';
								$FTS_FB_OUTPUT .= 'jQuery(this).removeClass("fts-jal-fb-vid-picture");';
								$FTS_FB_OUTPUT .= '	jQuery(this).prepend(\'<div class="fts-fluid-videoWrapper">'.$jsonObj->html.'</div>\');';
								if (isset($FB_Shortcode['grid']) && $FB_Shortcode['grid'] == 'yes') {
									$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "reloadItems");';
									$FTS_FB_OUTPUT .= 'jQuery(".fts-slicker-facebook-posts").masonry( "layout" );';
								}
								$FTS_FB_OUTPUT .= '});';
								$FTS_FB_OUTPUT .= '});';
								$FTS_FB_OUTPUT .= '</script>';
						}
					}
				}
				if ($FBname || $FBcaption || $FBdescription) {
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap fb-id'.$FBpost_id.'">';
					//Output Video Name
					$FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype, $FBpost_id) : '';
					//Output Video Caption
					$FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype, $FBpost_id) : '';
					//Output Video Description
					$FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, $FBpost_id) : '';
					$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				}
				$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				break;
			//**************************************************
			//START PHOTO POST
			//**************************************************
			case 'photo'  :
				$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
				if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
					$FTS_FB_OUTPUT .= 'style="line-height:'.$FB_Shortcode['image_height'].' !important;"';
				}
				$FTS_FB_OUTPUT .= '>';
					$FTS_FB_OUTPUT .= isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? '<div class="fts-fb-caption"><a href="'.$FBlink.'" class="fts-view-on-facebook-link" target="_blank">'.__('View on Facebook', 'feed-them-social').'</a></div> ' : '';
				//Output Photo Picture
				if ($FBpost_object_id) {
					if ($FBpost_object_id) {
						$FTS_FB_OUTPUT .= '<a href="'.(isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? 'https://graph.facebook.com/'.$FBpost_object_id.'/picture' : $FBlink). '" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="'.$post_data->from->name.'" src="https://graph.facebook.com/'.$FBpost_object_id.'/picture"></a>';
					}
					else {
						$FTS_FB_OUTPUT .= '<a href="'.(isset($FB_Shortcode['popup']) && $FB_Shortcode['popup'] == 'yes' ? 'https://graph.facebook.com/'.$FBpost_object_id.'/picture' : $FBlink).'" target="_blank" class="fts-jal-fb-picture fts-fb-large-photo"><img border="0" alt="'.$post_data->from->name.'" src="https://graph.facebook.com/'.$FBpost_id.'/picture"></a>';
					}
				}
				elseif ($FBpicture) {
					if ($FBpost_object_id) {
						$FTS_FB_OUTPUT .= $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, 'https://graph.facebook.com/'.$FBpost_object_id.'/picture');
					}
					else {
						$FTS_FB_OUTPUT .= isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes' ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->format[1]->picture) : $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, 'https://graph.facebook.com/'.$FBpost_id.'/picture/');
					}
				}
				$FTS_FB_OUTPUT .= '<div class="slicker-facebook-album-photoshadow"></div>';
				   // FB Video play button for facebook videos. This button takes data from our a tag and along with additional js in the magnific-popup.js we can now load html5 videos. SO lightweight this way because no pre-loading of videos are on the page. We only show the posterboard on mobile devices because tablets and desktops will auto load the videos. SRL
							if(isset($FB_Shortcode['video_album']) && $FB_Shortcode['video_album'] == 'yes') {	
								if($FB_Shortcode['play_btn'] == 'yes'){
								$fb_play_btn_visible = isset($FB_Shortcode['play_btn_visible']) && $FB_Shortcode['play_btn_visible']== 'yes' ? ' visible-video-button' : '';
								$FTS_FB_OUTPUT .= '<a href="'.$post_data->source.'" data-poster="'.$post_data->format[3]->picture.'" id="fts-view-vid1-'.$fts_dynamic_vid_name_string.'" title="'.$FBdescription.'" class="fts-view-fb-videos-btn fb-video-popup-'.$fts_dynamic_vid_name_string . $fb_play_btn_visible.' fts-slicker-backg" style="height:'.$FB_Shortcode['play_btn_size'].' !important; width:'.$FB_Shortcode['play_btn_size'].'; line-height: '.$FB_Shortcode['play_btn_size'].'; font-size:'.$FB_Shortcode['play_btn_size'].'"><span class="fts-fb-video-icon" style="height:'.$FB_Shortcode['play_btn_size'].' width:'.$FB_Shortcode['play_btn_size'].'; line-height:'.$FB_Shortcode['play_btn_size'].'; font-size:'.$FB_Shortcode['play_btn_size'].'"></span></a>';
								 } 
							 }
				if (!$FB_Shortcode['type'] == 'album_photos') {
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap" style="display:none">';
						//Output Photo Name
						$FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
						//Output Photo Caption
						$FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
						//Output Photo Description
						$FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, NULL, $FBby) : '';
					$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				}
				$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
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
				$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-link-wrap fts-album-photos-wrap"';
				if ($FB_Shortcode['type'] == 'album_photos' || $FB_Shortcode['type'] == 'albums') {
					$FTS_FB_OUTPUT .= 'style="line-height:'.$FB_Shortcode['image_height'].' !important;"';
				}
				$FTS_FB_OUTPUT .= '>';
				//Output Photo Picture
				$FTS_FB_OUTPUT .= $FBalbum_cover ? $this->fts_facebook_post_photo($FBlink, $FB_Shortcode, $post_data->from->name, $post_data->cover_photo->id) : '';
				$FTS_FB_OUTPUT .= '<div class="slicker-facebook-album-photoshadow"></div>';
				if (!$FB_Shortcode['type'] == 'albums') {
					$FTS_FB_OUTPUT .= '<div class="fts-jal-fb-description-wrap">';
					//Output Photo Name
					$FTS_FB_OUTPUT .= $FBname ? $this->fts_facebook_post_name($FBlink, $FBname, $FBtype) : '';
					//Output Photo Caption
					$FTS_FB_OUTPUT .= $FBcaption ? $this->fts_facebook_post_cap($FBcaption, $FB_Shortcode, $FBtype) : '';
					//Output Photo Description
					$FTS_FB_OUTPUT .= $FBdescription ? $this->fts_facebook_post_desc($FBdescription, $FB_Shortcode, $FBtype, NULL, $FBby) : '';
					$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				}
				$FTS_FB_OUTPUT .= '<div class="clear"></div></div>';
				break;
			}
			
			$FTS_FB_OUTPUT .= '<div class="clear"></div>';
			$FTS_FB_OUTPUT .= '</div>';
			$FBpost_single_id = isset($FBpost_single_id) ? $FBpost_single_id : "";
			$final_FBpost_like_count = isset($final_FBpost_like_count) ? $final_FBpost_like_count : "";
			$final_FBpost_comments_count = isset($final_FBpost_comments_count) ? $final_FBpost_comments_count : "";
			$single_event_id = isset($single_event_id) ? $single_event_id : "";
			$FTS_FB_OUTPUT .= $this->fts_facebook_post_see_more($FBlink, $lcs_array, $FBtype, $FBpost_id, $FB_Shortcode, $FBpost_user_id, $FBpost_single_id, $single_event_id,$post_data);
			$FTS_FB_OUTPUT .= '<div class="clear"></div>';
			$FTS_FB_OUTPUT .= '</div>';

			return $FTS_FB_OUTPUT;
			
	}//function free_post_types
}// FTS_Facebook_Feed END CLASS
?>