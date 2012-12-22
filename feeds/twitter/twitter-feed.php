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

?>

<div id="twitter-feed" class="fts-twitter-div"></div>

<script>
jQuery(document).ready(function() {
    loadLatestTweet();
}); 
 
//Twitter Parsers
String.prototype.parseURL = function() {
    return this.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/g, function(url) {
        return url.link(url);
    });
};
String.prototype.parseUsername = function() {
    return this.replace(/[@]+[A-Za-z0-9-_]+/g, function(u) {
        var username = u.replace("@","")
        return u.link("http://twitter.com/"+username);
    });
};
String.prototype.parseHashtag = function() {
    return this.replace(/[#]+[A-Za-z0-9-_]+/g, function(t) {
        var tag = t.replace("#","%23")
        return t.link("http://search.twitter.com/search?q="+tag);
    });
};
function parseDate(str) {
    var v=str.split(' ');
    return new Date(Date.parse(v[1]+" "+v[2]+", "+v[5]+" "+v[3]+" UTC"));
} 
 
function loadLatestTweet(){
    var numTweets = <?php print $tweets_count?>;
    var _url = 'https://api.twitter.com/1/statuses/user_timeline/<?php print $twitter_name?>.json?callback=?&count='+numTweets+'&include_rts=1';
   jQuery.getJSON(_url,function(data){
    for(var i = 0; i< data.length; i++){
            var tweet = data[i].text;
            var created = new Date(data[i].created_at);
            var createdDate = created.getDate()+'-'+(created.getMonth()+1)+'-'+created.getFullYear()+' at '+created.getHours()+':'+created.getMinutes();
            tweet = tweet.parseURL().parseUsername().parseHashtag();
            tweet += '<div class="tweeter-info"><div class="fts-twitter-image"><img class="twitter-image" src="'+ data[i].user.profile_image_url +'" /></div><div class="uppercase bold"><a href="https://twitter.com/#!/<?php print $twitter_name?>" target="_blank" class="black">@<?php print $twitter_name?></a></div><div class="right"><a href="https://twitter.com/#!/<?php print $twitter_name?>/status/'+data[i].id_str+'">'+createdDate+'</a></div></div>'
            jQuery("#twitter-feed").append('<p>'+tweet+'</p>');
        }
    });
}
</script>
<?php
}
?>