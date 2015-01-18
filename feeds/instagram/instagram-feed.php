<?php
add_action('wp_enqueue_scripts', 'fts_instagram_head');
function  fts_instagram_head() {
    wp_enqueue_style( 'fts_instagram_css', plugins_url( 'instagram/css/styles.css',  dirname(__FILE__) ) );
	wp_enqueue_script( 'fts_instagram_masonry_pkgd_js', plugins_url( 'instagram/js/masonry.pkgd.min.js',  dirname(__FILE__) ), array( 'jquery' ) );
	// masonry js snippet in date-format.js too
	wp_enqueue_script( 'fts_instagram_date_js', plugins_url( 'instagram/js/date-format.js',  dirname(__FILE__) ), array( 'jquery' ) );
}

add_shortcode( 'fts instagram', 'fts_instagram_func' );
//Main Funtion
function fts_instagram_func($atts){

$fts_functions = new feed_them_social_functions;

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/instagram/instagram-feed.php');
}
else 	{
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


$popup = isset($popup) ? $popup : "";

if ($popup == 'yes') {
		// it's ok if these styles & scripts load at the bottom of the page because they are just for the popup
	 	wp_enqueue_style( 'fts_instagram_css_popup', plugins_url( 'instagram/css/magnific-popup.css',  dirname(__FILE__) ) );
		wp_enqueue_script( 'fts_instagram_popup_js', plugins_url( 'instagram/js/magnific-popup.js',  dirname(__FILE__) ) );
 }

ob_start(); 

	$fts_instagram_tokens_array = array('267791236.df31d88.30e266dda9f84e9f97d9e603f41aaf9e','267791236.14c1243.a5268d6ed4cf4d2187b0e98b365443af','267791236.f78cc02.bea846f3144a40acbf0e56b002c112f8','258559306.502d2c4.c5ff817f173547d89477a2bd2e6047f9');
	$fts_instagram_access_token = $fts_instagram_tokens_array[array_rand($fts_instagram_tokens_array,1)];

//URL to get Feeds
 if ($type == 'hashtag') {  
	$insta_url = 'https://api.instagram.com/v1/tags/'.$instagram_id.'/media/recent/?access_token='.$fts_instagram_access_token;
  } 
	else {
 	$insta_url = 'https://api.instagram.com/v1/users/'.$instagram_id.'/media/recent/?access_token='.$fts_instagram_access_token;
	}
$cache = WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/instagram/cache/instagram-cache-'.$instagram_id.'.cache';
// https://api.instagram.com/v1/tags/snow/media/recent?access_token=ACCESS-TOKEN
// https://instagram.com/oauth/authorize/?client_id=[CLIENT_ID_HERE]&redirect_uri=http://localhost&response_type=token

	//Get Data for Instagram
	$response = wp_remote_fopen($insta_url);
	
	//Error Check
	$error_check = json_decode($response);
	if(isset($error_check->meta->error_message)){
		return $error_check->meta->error_message;
	}

if(file_exists($cache) && !filesize($cache) == 0 && filemtime($cache) > time() - 900){
	$insta_data = $fts_functions->fts_get_feed_cache($cache);
} 
else {
	$insta_data = json_decode($response);
	//if Error DON'T Cache
	if(!isset($error_check->meta->error_message)){
		$fts_functions->fts_create_feed_cache($cache, $insta_data);
	}
}

if ($super_gallery == 'yes') { ?>
<div class="fts-slicker-instagram masonry js-masonry <?php if ($popup == 'yes') { print 'popup-gallery'; }?>" style="margin:auto" data-masonry-options='{ "isFitWidth": <?php if ($center_container == 'no') { ?>false<?php } else {?>true<?php } if ($image_stack_animation == 'no') { ?>, "transitionDuration": 0<?php } ?> }'>
	<?php } 
    else { ?>
    	<div class="fts-instagram <?php if ($popup == 'yes') { print 'popup-gallery'; }?>">
   <?php } 
   
$set_zero = 0;
if (!isset($insta_data->data)) {
	return '<div style="padding-right:35px;">Looks like instagram\'s API down. Please try clearing cache and reloading this page in the near future.</div></div>';
}
foreach($insta_data->data as $insta_d) {
if($set_zero==$pics_count)
break;
//Create Instagram Variables 
$instagram_date = isset($insta_d->created_time) ? date('F j, Y',$insta_d->created_time) : "";
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
if ($super_gallery == 'yes') { ?>
<div class='slicker-instagram-placeholder fts-instagram-wrapper' style="width:<?php print $image_size ?>; margin:<?php print $space_between_photos ?>;">

<?php if ($popup == 'yes') {  ?>
<div class="fts-instagram-caption"><?php if (!$instagram_caption == '') { print ''.$instagram_caption.'<br/>';} ?><a href='<?php print $instagram_link ?>' class="fts-view-on-instagram-link" target="_blank">View on Instagram</a></div>
 <?php } ?>

<a href='<?php if ($popup == 'yes') { print $instagram_lowRez_url; } else { print $instagram_link; } ?>' title='<?php print $instagram_caption_a_title ?>' target="_blank" class='fts-slicker-backg fts-instagram-img-link' style="height:<?php print $icon_size ?> !important; width:<?php print $icon_size ?>; line-height:<?php print $icon_size ?>; font-size:<?php print $icon_size ?>;"><span class="fts-instagram-icon" style="height:<?php print $icon_size ?>; width:<?php print $icon_size ?>; line-height:<?php print $icon_size ?>; font-size:<?php print $icon_size ?>;"></span></a>

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
<div class='instagram-placeholder fts-instagram-wrapper'><?php if ($popup == 'yes') { print '<div class="fts-backg"></div>'; } else { ?>  <a class='fts-backg' target='_blank' href='<?php print $instagram_link ?>'></a>  <?php  };?>
  <div class='date'><?php print $instagram_date ?></div>
 <?php if ($popup == 'yes') {  ?>
<div class="fts-instagram-caption"><?php if (!$instagram_caption == '') { print ''.$instagram_caption.'<br/>';} ?><a href='<?php print $instagram_link ?>' class="fts-view-on-instagram-link" target="_blank">View on Instagram</a></div>
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
return ob_get_clean(); 	
}
?>