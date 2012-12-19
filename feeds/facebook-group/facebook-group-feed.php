<?php

add_action('wp_head', 'fts_fb_group_head');
function  fts_fb_group_head() {
    wp_enqueue_style( 'fts_fb_group_css', plugins_url( 'facebook-group/css/styles.css',  dirname(__FILE__ ) ) ); 
}

add_shortcode( 'fts facebook group', 'fts_fb_group_func' );

//Main Funtion
function fts_fb_group_func($atts){

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
//URL to get page info
$url1 = 'https://graph.facebook.com/'.$group_id.'?access_token='.$access_token.'';
$des = json_decode(file_get_contents($url1));

//URL to get Feeds
$url2 = 'https://graph.facebook.com/'.$group_id.'/feed?access_token='.$access_token.'';
$data = json_decode(file_get_contents($url2));

print '<div class="jal-fb-group-display">';
  print '<div class="jal-fb-header">';
    print '<h1><a href="http://www.facebook.com/home.php?sk=group_'.$group_id.'&ap=1">'.$des->name.'</a></h1>';
    	print '<div class="jal-fb-group-header-desc" style="">'.$des->description.'</div>';
  print '</div><!--/jal-fb-header-->';
 	
$set_zero = 0;
foreach($data->data as $d) {
if($set_zero==$fts_limiter)
break;

  print '<div class="jal-single-fb-post">';
  
      print '<div class="jal-fb-user-thumb">';
      	print '<a href="http://facebook.com/profile.php?id='.$d->from->id.'"><img border="0" alt="'.$d->from->name.'" src="https://graph.facebook.com/'.$d->from->id.'/picture"/></a>'; 
      print '</div><!--/fb-user-thumb-->';
      
      print '<div class="jal-fb-right-wrap">';
      	print '<div class="jal-fb-top-wrap">';
          print '<span class="jal-fb-user-name" style=""><a href="http://facebook.com/profile.php?id='.$d->from->id.'">'.$d->from->name.'</a></span>';
          print '<span class="jal-fb-post-time">on '.date('F j, Y H:i',strtotime($d->created_time)).'</span><div class="clear"></div>';
        print '</div><!--/jal-fb-top-wrap-->';

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
    
	
	//Output Message  
	if ( $FBmessage == '' ) {
	}
	else {
		print '<div class="jal-fb-message">'.$FBmessage.'</div><div class="clear"></div> ';
	}
    
	//Output Link    
	if ( $FBtype == '' ) {
		
	}
	
	elseif ( $FBtype == 'status' ) {
		
		print '<div class="jal-fb-link-wrap">';
		  //Output Link Pricture
		  if ( $FBpicture == '' ) {
		  }
		  else {
			  print '<a href="'.$FBlink.'" target="_blank" class="jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="jal-fb-description-wrap">';
		  //Output Link Name
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$FBlink.'" target="_blank" class="jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Link Caption
		  if ( $FBcaption  == 'Attachment UnavailableThis attachment may have been removed or the person who shared it may not have permission to share it with you.' ) {
			  	print '<div class="jal-fb-caption" style="width:100% !important">This user\'s persmissions are keeping you from seeing this post. Please Click "See More" to view this post on this group\'s facebook wall.</div>';
		  }
		  else {
			  print '<div class="jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Link Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-description">'.$FBdescription.'</div>';
		  };
		print '<div class="clear"></div></div><!--/jal-fb-description-wrap-->';
		
		print '<div class="clear"></div></div><!--/jal-fb-link-wrap-->';
	} 
	
	elseif ( $FBtype == 'link' ) {
		
		print '<div class="jal-fb-link-wrap">';
		  //Output Link Pricture
		  if ( $FBpicture == '' ) {
		  }
		  else {
			  print '<a href="'.$FBlink.'" target="_blank" class="jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="jal-fb-description-wrap">';
		  //Output Link Name
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$FBlink.'" target="_blank" class="jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Link Caption
		  if ( $FBcaption  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Link Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-description">'.$FBdescription.'</div>';
		  };
		print '<div class="clear"></div></div><!--/jal-fb-description-wrap-->';
		
		print '<div class="clear"></div></div><!--/jal-fb-link-wrap-->';
	}
	 
	
	//Output Video
	elseif ( $FBtype == 'video' ) {
		
		print '<div class="jal-fb-vid-wrap">';
		
		  if ( $FBpicture == '' ) {
		  }
		  else{	
		
		print '<a href="javascript:;" target="_blank" class="jal-fb-vid-picture fb-id'.$FBpost_id.' vid-btn'.$FBpost_id.'"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/> <div class="jal-fb-vid-play-btn"></div> </a>';
		 
		print '<div id="video'.$FBpost_id.'" class="vid-div"></div>';
		 
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
					print 'jQuery("#video'.$FBpost_id.'").prepend(\'<iframe width="488" height="281" class="video'.$FBpost_id.'" style="display:none;" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe>\');';
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
					print 'jQuery("#video'.$FBpost_id.'").prepend(\'<iframe width="488" height="281" class="video'.$FBpost_id.'" style="display:none;" src="http://www.youtube.com/embed/'.$youtubeURLfinal.'?autoplay=1" frameborder="0" allowfullscreen></iframe>\');';
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
					print 'jQuery("#video'.$FBpost_id.'").prepend(\'<iframe src="http://player.vimeo.com/video/'.$vimeoURLfinal.'?autoplay=1" class="video'.$FBpost_id.'" style="display:none;" width="488" height="390" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>\');';
					print 'jQuery(".video'.$FBpost_id.'").show();';
				print '});';		
				print '});';	
				print '</script>';
			} 
		}
						 
		print '<div class="jal-fb-description-wrap fb-id'.$FBpost_id.'">';
		
		  //Output Video Name
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$FBlink.'" target="_blank" class="jal-fb-name fb-id'.$FBpost_id.'">'.$FBname.'</a>';
		  };
		  //Output Video Caption
		  if ( $FBcaption  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-caption fb-id'.$FBpost_id.'">'.$FBcaption.'</div>';
		  };
		  //Output Video Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-description fb-id'.$FBpost_id.'">'.$FBdescription.'</div>';
		  };
	  	print '<div class="clear"></div></div><!--/jal-fb-description-wrap-->';
		
	 	print '<div class="clear"></div></div><!--/jal-fb-vid-wrap-->';	
	}
	
	//Output Photo
	elseif ( $FBtype == 'photo' ) {
		
		print '<div class="jal-fb-link-wrap">';
		  
		  //Output Photo Picture
		  if ( $FBpicture == '' ) {
		  }
		  else {
			  print '<a href="'.$FBlink.'" target="_blank" class="jal-fb-picture"><img border="0" alt="' .$d->from->name.'" src="'.$d->picture.'"/></a>';
		  };
		  
		print '<div class="jal-fb-description-wrap">';
		  //Output Photo Name
		  if ( $FBname  == '' ) {
		  }
		  else {
			  print '<a href="'.$FBlink.'" target="_blank" class="jal-fb-name">'.$FBname.'</a>';
		  };
		  //Output Photo Caption
		  if ( $FBcaption  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-caption">'.$FBcaption.'</div>';
		  };
		  //Output Photo Description
		  if ( $FBdescription  == '' ) {
		  }
		  else {
			  print '<div class="jal-fb-description">'.$FBdescription.'</div>';
			  print '<div>By: <a href="'.$FBbylink.'">'.$FBby.'<a/></div>';
		  };
		print '</div><!--/jal-fb-description-wrap-->';
		
		print '<div class="clear"></div></div><!--/jal-fb-link-wrap-->';
	} 

  print '<div class="clear"></div>';
	print '</div><!--/jal-fb-right-wrap-->';
	
	print '<a href="http://www.facebook.com/home.php?sk=group_'.$group_id.'&ap=1" target="_blank" class="jal-fb-see-more">See More</a>';
print '<div class="clear"></div>';
print '</div><!--/jal-single-fb-post-->';

	 $set_zero++;
	 }	

  print '</div><!--/jal-fb-group-display-->';
  print '<div class="clear"></div>'; 
}
?>