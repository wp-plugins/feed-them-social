<?php

add_action('wp_enqueue_scripts', 'fts_fb_head');
function  fts_fb_head() {
    wp_enqueue_style( 'fts_fb_css', plugins_url( 'facebook/css/styles.css',  dirname(__FILE__ ) ) ); 
}

add_shortcode( 'fts facebook group', 'fts_fb_func' );

add_shortcode( 'fts facebook page', 'fts_fb_func' );

//Main Funtion
function fts_fb_func($atts){

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//Eventually add premium page file
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('wp-content/plugins/feed-them-premium/feeds/facebook/facebook-premium-feed.php'); 
}
else 	{
	extract( shortcode_atts( array(
		'id' => '',
		'type' => ''
	), $atts ) );
	
	$fts_limiter = '5';
	$fts_fb_id = $id;
	$access_token = '226916994002335|ks3AFvyAOckiTA1u_aDoI4HYuuw';
}
ob_start(); 

if ($type !== 'page' or $type == 'group')	{
		$fts_view_fb_link ='https://www.facebook.com/groups/'.$fts_fb_id.'/';	
}

if ($type == 'page')	{
	$fts_view_fb_link ='https://www.facebook.com/'.$fts_fb_id.'/';
}

	//URL to get page info
	$url1 = 'https://graph.facebook.com/'.$fts_fb_id.'?access_token='.$access_token.'';
	$des_cache = 'wp-content/plugins/feed-them-social/feeds/facebook/cache/FB_des_cache-'.$fts_fb_id.'-num'.$fts_limiter.'.json';
	  //Check Cache
	  if(file_exists($des_cache) && !filesize($des_cache) == 0 && filemtime($des_cache) > time() - 900 && false !== strpos($des_cache,'-num'.$fts_limiter.'')){
		$des = json_decode(file_get_contents($des_cache));
	  } 
	  else {
		$des = json_decode((file_get_contents($url1)));
		if (!file_exists($des_cache)) {
			touch($des_cache);
		}
		file_put_contents($des_cache,json_encode($des));
	  }
	
	//URL to get Feeds
	$url2 = 'https://graph.facebook.com/'.$fts_fb_id.'/feed?access_token='.$access_token.'';
	$data_cache = 'wp-content/plugins/feed-them-social/feeds/facebook/cache/FB_data_cache-'.$fts_fb_id.'-num'.$fts_limiter.'.json';
	  //Check Cache
	  if(file_exists($data_cache) && !filesize($data_cache) == 0 && filemtime($data_cache) > time() - 900 && false !== strpos($data_cache,'-num'.$fts_limiter.'')){
		$data = json_decode(file_get_contents($data_cache));
	  } 
	  else {
		$data = json_decode((file_get_contents($url2)));
		if (!file_exists($data_cache)) {
			touch($data_cache);
		}
		file_put_contents($data_cache,json_encode($data));
	  }
	

	//URL to get comments count
	$url3 = 'https://graph.facebook.com/'.$fts_fb_id.'/feed?access_token='.$access_token.'&fields=comments.summary(true)';
	$comment_count_data_cache = 'wp-content/plugins/feed-them-social/feeds/facebook/cache/FB_cc_cache-'.$fts_fb_id.'-num'.$fts_limiter.'.json';
	  //Check Cache
	  if(file_exists($comment_count_data_cache) && !filesize($comment_count_data_cache) == 0 && filemtime($comment_count_data_cache) > time() - 900 && false !== strpos($comment_count_data_cache,'-num'.$fts_limiter.'')){
		$FBpost_comment_counted  = json_decode(file_get_contents($comment_count_data_cache));
	  } 
	  else {
		$comment_count_data  = json_decode((file_get_contents($url3)));
		
		//Create comments count array
		$FBpost_comment_counted = array();
		foreach($comment_count_data ->data as $com_dat){
				$FBpost_comment_counted[] = $com_dat->comments->summary->total_count;
		}
		
		if (!file_exists($comment_count_data_cache)) {
			touch($comment_count_data_cache);
		}
		file_put_contents($comment_count_data_cache,json_encode($FBpost_comment_counted));
	  }	

		
print '<div class="fts-jal-fb-group-display">';
if(is_plugin_active('feed-them-premium/feed-them-premium.php'))  {
	  print '<div class="fts-jal-fb-header">';
	 // Print our Facebook Page Title or About Text. Commented out the group description becuase in the future we will be adding the about description.
	  if ($title == 'yes' or $title == '') {
		print '<h1><a href="'.$fts_view_fb_link.'">'.$des->name.'</a></h1>';
	  }
	 if ($description == 'yes' || $description == '') {
		print '<div class="fts-jal-fb-group-header-desc">'.$des->description.'</div>';	
	  }
	  
	   print '</div>';

}
  else {
	  print '<div class="fts-jal-fb-header"><h1><a href="'.$fts_view_fb_link.'">'.$des->name.'</a></h1>';
	  print '<div class="fts-jal-fb-group-header-desc">'.$des->description.'</div>';
      print '</div>';
  }


$set_zero = 0;
foreach($data->data as $d) {
if($set_zero==$fts_limiter)
break;
$FBfinalstory ='';
$first_dir ='';
//Create Facebook Variables 
$FBtype = $d->type;
$FBmessage = $d->message;	
$FBpicture = $d->picture;
$FBlink = $d->link;
$FBname = $d->name;
$FBcaption = $d->caption;
$FBdescription = $d->description;
$FBstory = $d->story;
$FBicon = $d->icon;
$FBby = $d->properties->text;
$FBbylink = $d->properties->href;
$FBpost_id = $d->id;
$FBpost_like_count = $d->likes->count;
$FBpost_comments_count_array = $d->comments->data;


$FBfromName = $d->from->name;
$FBstory = $d->story;


if (!empty($FBstory)) {
	$FBfinalstory  = preg_replace('/'.$FBfromName.'/', '', $FBstory, 1);
}

  print '<div class="fts-jal-single-fb-post">';
  
      print '<div class="fts-jal-fb-user-thumb">';
      print '<a href="http://facebook.com/profile.php?id='.$d->from->id.'"><img border="0" alt="'.$d->from->name.'" src="https://graph.facebook.com/'.$d->from->id.'/picture"/></a>'; 
      print '</div>';
      
      print '<div class="fts-jal-fb-right-wrap">';
      print '<div class="fts-jal-fb-top-wrap">';
      print '<span class="fts-jal-fb-user-name" style=""><a href="http://facebook.com/profile.php?id='.$d->from->id.'">'.$d->from->name.'</a>'.$FBfinalstory.'</span>';
      print '<span class="fts-jal-fb-post-time">on '.date('F j, Y g:i a',strtotime($d->created_time)).'</span><div class="clear"></div>';
      print '</div>';


if (!empty($FBpost_comments_count_array))	{	
			$FBpost_comments_count = $FBpost_comment_counted[$set_zero];	
}
else	{
	$FBpost_comments_count = 0;
}

if ($FBpost_comments_count == '0')	{
	$final_FBpost_comments_count = "";
}
if ($FBpost_comments_count == '1')	{
	$final_FBpost_comments_count = "1 Comment -";
}

if ($FBpost_comments_count > '1')	{
	$final_FBpost_comments_count = $FBpost_comments_count." Comments -";
}

if ($FBpost_like_count == '0')	{
	$final_FBpost_like_count = "";
}
if ($FBpost_like_count == '1')	{
	$final_FBpost_like_count = "1 Like -";
}

if ($FBpost_like_count > '1')	{
	$final_FBpost_like_count = $FBpost_like_count." Likes -";
}

	$FBpost_id_final = substr($FBpost_id, strpos($FBpost_id, "_") + 1);

	//filter messages to have urls
    $FB_final_message = preg_replace('@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@', '<a target="_blank" href="http$2://$4">$1$2$3$4</a>', $FBmessage);


	
	//Output Message  
	if (!empty($FBmessage)) {
		print '<div class="fts-jal-fb-message">'.nl2br($FB_final_message).'</div><div class="clear"></div> ';
	}
    
	//Output Link    
	if ( $FBtype == '' ) {
		
	}
	
	elseif ( $FBtype == 'status' ) {
		if (empty($FBpicture) && empty($FBname) && empty($FBdescription) ) {
		}
		else	{
		  print '<div class="fts-jal-fb-link-wrap">';
		  
		 
		
				//Output Link Pricture
				if (!empty($FBpicture)) {
					print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
				};
				
			  print '<div class="fts-jal-fb-description-wrap">';
				//Output Link Name
				if (!empty($FBname)) {
					print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-name">'.$FBname.'</a>';
				};
				//Output Link Caption
				if ($FBcaption  == 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' ) {
					  print '<div class="fts-jal-fb-caption" style="width:100% !important">This user\'s permissions are keeping you from seeing this post. Please Click "See More" to view this post on this group\'s facebook wall.</div>';
				}
				else {
					print '<div class="fts-jal-fb-caption">'.$FBcaption.'</div>';
				};
				//Output Link Description
				if(!empty($FBdescription)) {
					print '<div class="fts-jal-fb-description">'.$FBdescription.'</div>';
				};
			  print '<div class="clear"></div></div>';
		  
		  print '<div class="clear"></div></div>';
		} 
	}
	
	elseif ( $FBtype == 'link' ) {
		
		print '<div class="fts-jal-fb-link-wrap">';
		
		 //start url check
		  $url = $FBlink;
		  $url_parts = parse_url($url);
		  $host = $url_parts['host'];
		  
		  if ($host == 'www.facebook.com'){
			  $spliturl= $url_parts['path'];
			  $path_components = explode('/', $spliturl);
			  $first_dir = $path_components[1];
			  $event_id_number = $path_components[2];
		  }
		  //end url check
		  
		  	if($host == 'www.facebook.com' and $first_dir == 'events')	{
				$event_url = 'https://graph.facebook.com/'.$event_id_number.'/?access_token='.$access_token.'';
				$event_data = json_decode(file_get_contents($event_url));
				
				$FB_event_name = $event_data->name;
				$FB_event_location = $event_data->location;
				$FB_event_city = $event_data->venue->city;
				$FB_event_state = $event_data->venue->state;
				$FB_event_start_time = date('l, F j, Y g:i a',strtotime($event_data->start_time));
				
				echo '<img class="fts-fb-event-photo" src="http://graph.facebook.com/'.$event_id_number.'/picture"></img>';
				
				print '<div class="fts-jal-fb-description-wrap">';
				  //Output Link Name
				  if (!empty($FB_event_name)) {
					  print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-name">'.$FB_event_name.'</a>';
				  };
				  //Output Link Caption
				  if (!empty($FB_event_start_time)) {
					  print '<div class="fts-fb-event-time">'.$FB_event_start_time.'</div>';
				  };
				  //Output Link Description
				  if (!empty($FB_event_location)) {
					  print '<div class="fts-fb-location">'.$FB_event_location;
					  if (!empty($FB_event_city) or !empty($FB_event_state)) {
					  	print ' in '.$FB_event_city.', '.$FB_event_state.'';
					  }
					  print '</div>';
				  };
				print '<div class="clear"></div></div>';
				
			}//end if event
			
		  //Output Link Pricture
		  if (!empty($FBpicture)) {
			  print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="fts-jal-fb-description-wrap">';
		  //Output Link Name
		  if (!empty($FBname)) {
			  print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Link Caption
		  if (!empty($FBcaption)) {
			  print '<div class="fts-jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Link Description
		  if (!empty($FBdescription)) {
			  print '<div class="jal-fb-description">'.$FBdescription.'</div>';
		  };
		print '<div class="clear"></div></div>';
		
		print '<div class="clear"></div></div>';
	}
	 
	
	//Output Video
	elseif ( $FBtype == 'video' ) {
		
		print '<div class="fts-jal-fb-vid-wrap">';
		
		  if (!empty($FBpicture)) {
		
		print '<a href="javascript:;" target="_blank" class="fts-jal-fb-vid-picture fb-id'.$FBpost_id.' vid-btn'.$FBpost_id.'"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/> <div class="fts-jal-fb-vid-play-btn"></div></a>';
		 
		print '<div id="video'.$FBpost_id.'" class="fts-vid-div"></div>';
		 
		 	//strip Youtube URL then ouput Iframe and script
		 	if (strpos($FBlink, 'youtube') > 0) {
				 $pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
        		 preg_match($pattern, $FBlink, $matches);
       		 	 $youtubeURLfinal = $matches[1];
		 
		 		print '<script>';
				print 'jQuery(document).ready(function() {';
				print 'jQuery(".vid-btn'.$FBpost_id.'").click(function() {';
					print 'jQuery(".fb-id'.$FBpost_id.'").hide();';
					print 'jQuery("#video'.$FBpost_id.'").show();';
					print 'jQuery("#video'.$FBpost_id.'").prepend(\'<iframe height="281" class="video'.$FBpost_id.'" style="display:none;" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe>\');';
					print 'jQuery(".video'.$FBpost_id.'").show();';
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
				print 'jQuery(".vid-btn'.$FBpost_id.'").click(function() {';
					print 'jQuery(".fb-id'.$FBpost_id.'").hide();';
					print 'jQuery("#video'.$FBpost_id.'").show();';
					print 'jQuery("#video'.$FBpost_id.'").prepend(\'<iframe height="281" class="video'.$FBpost_id.'" style="display:none;" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe>\');';
					print 'jQuery(".video'.$FBpost_id.'").show();';
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
				print 'jQuery(".vid-btn'.$FBpost_id.'").click(function() {';
					print 'jQuery(".fb-id'.$FBpost_id.'").hide();';
					print 'jQuery("#video'.$FBpost_id.'").show();';
					print 'jQuery("#video'.$FBpost_id.'").prepend(\'<iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=1" class="video'.$FBpost_id.'" style="display:none;" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>\');';
					print 'jQuery(".video'.$FBpost_id.'").show();';
				print '});';		
				print '});';	
				print '</script>';
			} 
		}
						 
		print '<div class="fts-jal-fb-description-wrap fb-id'.$FBpost_id.'">';
		
		  //Output Video Name
		  if (!empty($FBname)) {
			  print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-name fb-id'.$FBpost_id.'">'.$FBname.'</a>';
		  };
		  //Output Video Caption
		  if (!empty($FBcaption)) {
			  print '<div class="fts-jal-fb-caption fb-id'.$FBpost_id.'">'.$FBcaption.'</div>';
		  };
		  //Output Video Description
		  if (!empty($FBdescription)) {
			  print '<div class="fts-jal-fb-description fb-id'.$FBpost_id.'">'.$FBdescription.'</div>';
		  };
	  	print '<div class="clear"></div></div>';
		
	 	print '<div class="clear"></div></div>';	
	}
	
	//Output Photo
	elseif ( $FBtype == 'photo' ) {
		
		print '<div class="fts-jal-fb-link-wrap">';
		  
		  //Output Photo Picture
		  if (!empty($FBpicture)) {
			  print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="fts-jal-fb-description-wrap">';
		  //Output Photo Name
		  if (!empty($FBname)) {
			  print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Photo Caption
		  if (!empty($FBcaption)) {
			  print '<div class="fts-jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Photo Description
		  if (!empty($FBdescription)) {
			  print '<div class="fts-jal-fb-description">'.$FBdescription.'</div>';
			  print '<div>By: <a href="'.$fts_view_fb_link.'">'.$FBby.'<a/></div>';
		  };
		print '</div>';
		
		print '<div class="clear"></div></div>';
	} 

  print '<div class="clear"></div>';
	print '</div>';
	
if ($type !== 'page' or $type == 'group')	{
	print '<a href="'.$fts_view_fb_link.'#mall_post_'.$FBpost_id_final.'" target="_blank" class="fts-jal-fb-see-more"> '.$final_FBpost_like_count.' '.$final_FBpost_comments_count.' See More</a>';
}
if ($type == 'page')	{
	print '<a href="'.$fts_view_fb_link.'" target="_blank" class="fts-jal-fb-see-more"> '.$final_FBpost_like_count.' '.$final_FBpost_comments_count.' See More</a>';
}

	
print '<div class="clear"></div>';
print '</div>';

	 $set_zero++;
	 }	

  print '</div>';
  print '<div class="clear"></div>'; 
  
  return ob_get_clean(); 
}
?>