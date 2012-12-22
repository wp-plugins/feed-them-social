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

<div class="instagram feed-<?php $instagram_id ?>"></div>

<script>
    jQuery.ajax({
    	type: "GET",
        dataType: "jsonp",
        cache: false,
        url: "https://api.instagram.com/v1/users/<?php print $instagram_id?>/media/recent/?access_token=267791236.f78cc02.bea846f3144a40acbf0e56b002c112f8",
        success: function(data) {
		
            for (var i = 0; i < <?php print $pics_count?>; i++) {
				
				var timestamp = data.data[i].created_time;
				var now = new Date(timestamp* 1000);
				final_date = dateFormat(now, "d mmmm yyyy");
				
        		jQuery(".instagram").append("<div class='instagram-placeholder'><a class='backg' target='_blank' href='" + data.data[i].link +"'></a><div class='date'>"+ final_date +"</div><a class='instaG-backg-link' target='_blank' href='" + data.data[i].link +"'><div class='instagram-image' style='background:rgba(204, 204, 204, 0.8) url("+ data.data[i].images.thumbnail.url +")'></div><div class='instaG-photoshadow'></div></a><ul class='heart-comments-wrap'><li class='instagram-image-likes'>"+ data.data[i].likes.count +"</li><li class='instagram-image-comments'>"+ data.data[i].comments.count +"</li></ul></div>");   
      		}                 
        }
    });	
</script>


<?php   	
}
?>