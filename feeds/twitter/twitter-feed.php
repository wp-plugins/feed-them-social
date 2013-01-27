<?php
add_action('wp_enqueue_scripts', 'fts_twitter_head');
function  fts_twitter_head() {
    wp_enqueue_style( 'fts_twitter_css', plugins_url( 'twitter/css/styles.css',  dirname(__FILE__) ) ); 
}
add_shortcode( 'fts twitter', 'fts_twitter_func' );
//Main Funtion
function fts_twitter_func($atts){
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('wp-content/plugins/feed-them-premium/feeds/twitter/twitter-feed.php');
}
else 	{
	extract( shortcode_atts( array(
		'twitter_name' => ''
	), $atts ) );
	$tweets_count ='5';
}
ob_start(); 
//URL to get Feeds
$twit_url = 'https://api.twitter.com/1/statuses/user_timeline.json?screen_name=' . $twitter_name . '&count=' . $tweets_count . '&include_entities=1&include_rts=1';
$twit_data = json_decode(file_get_contents($twit_url));
?>
<div id="twitter-feed-<?php print $twitter_name?>" class="fts-twitter-div"><?php 	
$set_zero = 0;
foreach($twit_data as $twit_d) {
if($set_zero==$tweets_count)
break;
//Create Instagram Variables 
$twitter_date =  date('F j, Y, \a\t g:i a',strtotime($twit_d->created_at));
$twitter_id = $twit_d->id_str;
$twitter_profile_thumb_url = $twit_d->user->profile_image_url;
$twitter_tweet = $twit_d->text;
?><p><?php print $twitter_tweet?></p><div class="tweeter-info"><div class="fts-twitter-image"><img class="twitter-image" src="<?php print $twitter_profile_thumb_url?>" /></div><div class="uppercase bold"><a href="https://twitter.com/#!/<?php print $twitter_name?>" target="_blank" class="black">@<?php print $twitter_name?></a></div><div class="right"><a href="https://twitter.com/#!/<?php print $twitter_name?>/status/<?php print $twitter_id?>"><?php print $twitter_date?></a></div></div>
<?php 
	 $set_zero++;
	 }	
?><div class="clear"></div>
</div>
<?php 
return ob_get_clean(); 
}
?>