<?php
add_action('wp_enqueue_scripts', 'fts_instagram_head');
function  fts_instagram_head() {
    wp_enqueue_style( 'fts_instagram_css', plugins_url( 'instagram/css/styles.css',  dirname(__FILE__) ) );
	wp_enqueue_script( 'fts_instagram_date_js', plugins_url( 'instagram/js/date-format.js',  dirname(__FILE__) ) ); 
}
add_shortcode( 'fts instagram', 'fts_instagram_func' );

//Main Funtion
function fts_instagram_func($atts){

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('wp-content/plugins/feed-them-premium/feeds/instagram/instagram-feed.php');
}
else 	{
	extract( shortcode_atts( array(
		'instagram_id' => ''
	), $atts ) );
	$pics_count = '6';
}
?>
<?php
ob_start(); 
//URL to get Feeds
$insta_url = 'https://api.instagram.com/v1/users/'.$instagram_id.'/media/recent/?access_token=267791236.14c1243.a5268d6ed4cf4d2187b0e98b365443af';
$insta_data = json_decode(file_get_contents($insta_url));
?>
<div class="fts-instagram">
<?php 	
$set_zero = 0;
foreach($insta_data->data as $insta_d) {
if($set_zero==$pics_count)
break;

//Create Instagram Variables 
$instagram_date =  date('F j, Y',$insta_d->created_time);
$instagram_link = $insta_d->link;
$instagram_thumb_url = $insta_d->images->thumbnail->url;
$instagram_likes = $insta_d->likes->count;
$instagram_comments = $insta_d->comments->count;
?>
<div class='instagram-placeholder'><a class='fts-backg' target='_blank' href='<?php print $instagram_link ?>'></a>
  <div class='date'><?php print $instagram_date ?></div>
  <a class='instaG-backg-link' target='_blank' href='<?php print $instagram_link ?>'>
    <div class='instagram-image' style='background:rgba(204, 204, 204, 0.8) url(<?php print $instagram_thumb_url ?>)'></div>
    <div class='instaG-photoshadow'></div>
  </a>
  <ul class='heart-comments-wrap'>
    <li class='instagram-image-likes'><?php print $instagram_likes ?></li>
    <li class='instagram-image-comments'><?php print $instagram_comments ?></li>
  </ul>
</div>
<?php 
	 $set_zero++;
	 }	
?>
<div class="clear"></div>
</div>
	
<?php 
return ob_get_clean(); 	
}
?>