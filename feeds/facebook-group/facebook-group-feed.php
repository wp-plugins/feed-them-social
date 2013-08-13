<?php

add_action('wp_enqueue_scripts', 'fts_fb_group_head');
function  fts_fb_group_head() {
    wp_enqueue_style( 'fts_fb_group_css', plugins_url( 'facebook-group/css/styles.css',  dirname(__FILE__ ) ) ); 
}

add_shortcode( 'fts facebook group', 'fts_fb_group_func' );

//Main Funtion
function fts_fb_group_func($atts){

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('wp-content/plugins/feed-them-premium/feeds/facebook-group/facebook-group-feed.php');
}
else 	{
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	
	$fts_limiter = '5';
	$group_id = $id;
	$access_token = '226916994002335|ks3AFvyAOckiTA1u_aDoI4HYuuw';
}
ob_start(); 

$view_group_link ='https://www.facebook.com/groups/'.$group_id.'/';


//URL to get page info
$url1 = 'https://graph.facebook.com/'.$group_id.'?access_token='.$access_token.'';
$des = json_decode(file_get_contents($url1));


//URL to get Feeds
$url2 = 'https://graph.facebook.com/'.$group_id.'/feed?access_token='.$access_token.'';
$data = json_decode(file_get_contents($url2));

print '<div class="fts-jal-fb-group-display">';
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('wp-content/plugins/feed-them-premium/feeds/facebook-group/facebook-group-feed-title-description-options.php');
}
  else {
	  print '<div class="fts-jal-fb-header"><h1><a href="http://www.facebook.com/home.php?sk=group_'.$group_id.'&ap=1">'.$des->name.'</a></h1>';
	  print '<div class="fts-jal-fb-group-header-desc" style="">'.$des->description.'</div>';
      print '</div>';
  }
print '</div>'; 
 	
$set_zero = 0;
foreach($data->data as $d) {
if($set_zero==$fts_limiter)
break;

  print '<div class="fts-jal-single-fb-post">';
  
      print '<div class="fts-jal-fb-user-thumb">';
      print '<a href="http://facebook.com/profile.php?id='.$d->from->id.'"><img border="0" alt="'.$d->from->name.'" src="https://graph.facebook.com/'.$d->from->id.'/picture"/></a>'; 
      print '</div>';
      
      print '<div class="fts-jal-fb-right-wrap">';
      print '<div class="fts-jal-fb-top-wrap">';
      print '<span class="fts-jal-fb-user-name" style=""><a href="http://facebook.com/profile.php?id='.$d->from->id.'">'.$d->from->name.'</a></span>';
      print '<span class="fts-jal-fb-post-time">on '.date('F j, Y H:i',strtotime($d->created_time)).'</span><div class="clear"></div>';
      print '</div>';

//Create Facebook Variables 
$FBtype = $d->type;
$FBmessage = $d->message;	
$FBpicture = $d->picture;
$FBlink = $d->link;
$FBname = $d->name;
$FBcaption = $d->caption;
$FBdescription = $d->description;
$FBicon = $d->icon;
$FBby = $d->properties->text;
$FBbylink = $d->properties->href;
$FBpost_id = $d->id;
$FBpost_like_count = $d->likes->count;
$FBpost_comments_count_array = $d->comments->data;

if (!empty($FBpost_comments_count_array))	{	
			$FBpost_comments_count = 0;	
			foreach	($d->comments->data as $comments_count){
				$FBpost_comments_count++;
			}		
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


	
	//Output Message  
	if ( $FBmessage == '' ) {
	}
	else {
		print '<div class="fts-jal-fb-message">'.$FBmessage.'</div><div class="clear"></div> ';
	}
    
	//Output Link    
	if ( $FBtype == '' ) {
		
	}
	
	elseif ( $FBtype == 'status' ) {
		
		print '<div class="fts-jal-fb-link-wrap">';
		  //Output Link Pricture
		  if ( $FBpicture == '' ) {
		  }
		  else {
			  print '<a href="'.$view_group_link.'" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="fts-jal-fb-description-wrap">';
		  //Output Link Name
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$view_group_link.'" target="_blank" class="fts-jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Link Caption
		  if ( $FBcaption  == 'Attachment Unavailable. This attachment may have been removed or the person who shared it may not have permission to share it with you.' ) {
			  	print '<div class="fts-jal-fb-caption" style="width:100% !important">This user\'s permissions are keeping you from seeing this post. Please Click "See More" to view this post on this group\'s facebook wall.</div>';
		  }
		  else {
			  print '<div class="fts-jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Link Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="fts-jal-fb-description">'.$FBdescription.'</div>';
		  };
		print '<div class="clear"></div></div>';
		
		print '<div class="clear"></div></div>';
	} 
	
	elseif ( $FBtype == 'link' ) {
		
		print '<div class="fts-jal-fb-link-wrap">';
		  //Output Link Pricture
		  if ( $FBpicture == '' ) {
		  }
		  else {
			  print '<a href="'.$view_group_link.'" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="fts-jal-fb-description-wrap">';
		  //Output Link Name
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$view_group_link.'" target="_blank" class="fts-jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Link Caption
		  if ( $FBcaption  == '' ) {
		  }
		  else {
			  print '<div class="fts-jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Link Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-description">'.$FBdescription.'</div>';
		  };
		print '<div class="clear"></div></div>';
		
		print '<div class="clear"></div></div>';
	}
	 
	
	//Output Video
	elseif ( $FBtype == 'video' ) {
		
		print '<div class="fts-jal-fb-vid-wrap">';
		
		  if ( $FBpicture == '' ) {
		  }
		  else{	
		
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
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$view_group_link.'" target="_blank" class="fts-jal-fb-name fb-id'.$FBpost_id.'">'.$FBname.'</a>';
		  };
		  //Output Video Caption
		  if ( $FBcaption  == '' ) {
		  }
		  else {
			  print '<div class="fts-jal-fb-caption fb-id'.$FBpost_id.'">'.$FBcaption.'</div>';
		  };
		  //Output Video Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="fts-jal-fb-description fb-id'.$FBpost_id.'">'.$FBdescription.'</div>';
		  };
	  	print '<div class="clear"></div></div>';
		
	 	print '<div class="clear"></div></div>';	
	}
	
	//Output Photo
	elseif ( $FBtype == 'photo' ) {
		
		print '<div class="fts-jal-fb-link-wrap">';
		  
		  //Output Photo Picture
		  if ( $FBpicture == '' ) {
		  }
		  else {
			  print '<a href="'.$view_group_link.'" target="_blank" class="fts-jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="fts-jal-fb-description-wrap">';
		  //Output Photo Name
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$view_group_link.'" target="_blank" class="fts-jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Photo Caption
		  if ( $FBcaption  == '' ) {
		  }
		  else {
			  print '<div class="fts-jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Photo Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="fts-jal-fb-description">'.$FBdescription.'</div>';
			  print '<div>By: <a href="'.$view_group_link.'">'.$FBby.'<a/></div>';
		  };
		print '</div>';
		
		print '<div class="clear"></div></div>';
	} 

  print '<div class="clear"></div>';
	print '</div>';
	
	print '<a href="'.$view_group_link.'#mall_post_'.$FBpost_id_final.'" target="_blank" class="fts-jal-fb-see-more"> '.$final_FBpost_like_count.' '.$final_FBpost_comments_count.' See More</a>';
print '<div class="clear"></div>';
print '</div>';

	 $set_zero++;
	 }	

  print '</div>';
  print '<div class="clear"></div>'; 
  
  return ob_get_clean(); 
}
?>