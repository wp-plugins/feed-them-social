<?php

//Main setting page function
function feed_them_settings_page() {
	
add_action( 'wp_enqueue_style', 'feed_them_admin_css' );

?>
<link href='http://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>				
<div class="feed-them-social-admin-wrap">
  <h1><?php _e('Feed Them Social', 'feed-them-social'); ?></h1>
  <div class="use-of-plugin"><?php _e('Select what type of feed you would like to generate a shortcode for using the select option below. Then you\'ll copy that shortcode to a page or post.', 'feed-them-social'); ?></div>
  
  <div class="feed-them-icon-wrap">
    <a href="#" class="youtube-icon"></a>
    <a href="#" class="twitter-icon"></a>
    <a href="#" class="facebook-icon"></a>
  	<a href="#" class="instagram-icon"></a>
  	<a href="#" class="pinterest-icon"></a>
    
    <?php
	//show the js for the discount option under social icons on the settings page
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				// do not show discount option
		}
	else { ?>
		 <div id="discount-for-review"><?php _e('20% off Premium Version', 'feed-them-social'); ?></div>
    <div class="discount-review-text"><a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank"><?php _e('Share here', 'feed-them-social'); ?></a> <?php _e('and receive 20% OFF your total order.', 'feed-them-social'); ?></div>
<?php } ?>
   
  </div>

	<form class="feed-them-social-admin-form"> 
    	<select id="shortcode-form-selector">
        	<option value=""><?php _e('Please Select Feed Type', 'feed-them-social'); ?> </option>
            <option value="fb-page-shortcode-form"><?php _e('Facebook Page Feed', 'feed-them-social'); ?></option>
        	<option value="fb-group-shortcode-form"><?php _e('Facebook Group Feed', 'feed-them-social'); ?></option>
        	<option value="fb-event-shortcode-form"><?php _e('Facebook Event Feed', 'feed-them-social'); ?></option>
            <option value="twitter-shortcode-form"><?php _e('Twitter Feed', 'feed-them-social'); ?></option>
            <option value="instagram-shortcode-form"><?php _e('Instagram Feed', 'feed-them-social'); ?></option>
            <option value="youtube-shortcode-form"><?php _e('YouTube Feed'); ?></option>
            <option value="pinterest-shortcode-form"><?php _e('Pinterest Feed', 'feed-them-social'); ?></option>
        </select>
    </form><!--/feed-them-social-admin-form-->

<div class="fts-facebook_group-shortcode-form">
<form class="feed-them-social-admin-form shortcode-generator-form fb-group-shortcode-form" id="fts-fb-group-form">
    <h2><?php _e('Facebook Group Shortcode Generator', 'feed-them-social'); ?></h2>
    <div class="instructional-text"><?php _e('You must copy your <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">Facebook ID</a> and paste it in the first input below.', 'feed-them-social'); ?></div>
      <div class="feed-them-social-admin-input-wrap fb_group_id">
        <div class="feed-them-social-admin-input-label"><?php _e('Facebook Group ID (required)', 'feed-them-social'); ?></div>
        <input type="text" id="fb_group_id" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    
<!-- Using this for a future update <div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Customized Group Name</div>
  <select id="fb_group_custom_name" class="feed-them-social-admin-input">
    <option selected="selected" value="yes">My group name is custom</option>
    <option value="no">My group name is number based</option>
  </select>
  <div class="clear"></div>
</div>
/feed-them-social-admin-input-wrap-->

 
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/facebook-group-settings-fields.php');
}
else 	{
?>

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of Posts', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Show the Group Title?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Show the Group Description?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Amount of words per post?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<?php
}
?>
    
<?php 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?> 
      
      <input type="button" class="feed-them-social-admin-submit-btn" value="<?php _e('Generate Shortcode', 'feed-them-social'); ?>" onclick="updateTextArea_fb_group();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4><?php _e('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social'); ?></h4>
      
        <div class="feed-them-social-admin-input-label"><?php _e('Facebook Group Feed Shortcode', 'feed-them-social'); ?></div>
        
        <input class="copyme facebook-group-final-shortcode feed-them-social-admin-input" value="" />
        
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-facebook_group-shortcode-form-->


<div class="fts-facebook_page-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form fb-page-shortcode-form" id="fts-fb-page-form">
    <h2><?php _e('Facebook Page Shortcode Generator', 'feed-them-social'); ?></h2>
    <div class="instructional-text"><?php _e('You must copy your <a href="http://www.slickremix.com/2013/09/09/how-to-get-your-facebook-page-vanity-url/" target="_blank">Facebook Page ID</a> and paste it in the first input below.', 'feed-them-social'); ?></div>
      <div class="feed-them-social-admin-input-wrap fb_page_id">
        <div class="feed-them-social-admin-input-label"><?php _e('Facebook Page ID (required)', 'feed-them-social'); ?></div>
        <input type="text" id="fb_page_id" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
     
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Post Type Visible', 'feed-them-social'); ?></div>
  <select id="fb_page_posts_displayed" class="feed-them-social-admin-input">
    <option selected="selected" value="page_only"><?php _e('Display Posts made by Page only', 'feed-them-social'); ?></option>
    <option value="everyone"><?php _e('Display Posts made by Everyone', 'feed-them-social'); ?></option>
  </select>
  <div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/facebook-page-settings-fields.php');
}
else 	{
?>

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of Posts', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Show the Page Title?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Show the Page Description?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Amount of words per post?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<?php
}
 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?>
      <input type="button" class="feed-them-social-admin-submit-btn" value="<?php _e('Generate Shortcode', 'feed-them-social'); ?>" onclick="updateTextArea_fb_page();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4><?php _e('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social'); ?></h4>
      
        <div class="feed-them-social-admin-input-label"><?php _e('Facebook Page Feed Shortcode', 'feed-them-social'); ?></div>
        
        <input class="copyme facebook-page-final-shortcode feed-them-social-admin-input" value="" />
        
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-facebook_page-shortcode-form-->


<div class="fts-facebook_event-shortcode-form">
<form class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form">
    <h2><?php _e('Facebook Event Shortcode Generator.', 'feed-them-social'); ?></h2>
    <div class="instructional-text"><?php _e('Copy your <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-event-id/" target="_blank">Facebook Page Event ID or Group Event ID</a> and paste it in the first input below.', 'feed-them-social'); ?></div>
      <div class="feed-them-social-admin-input-wrap fb_event_id">
        <div class="feed-them-social-admin-input-label"><?php _e('Facebook Event ID (required)', 'feed-them-social'); ?></div>
        <input type="text" id="fb_event_id" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
     
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/facebook-event-settings-fields.php');
}
else 	{
?>

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of Posts', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Show the Event Title?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Show the Event Description?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div> <!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Amount of words per post?', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<?php
}

if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?> 
      
      <input type="button" class="feed-them-social-admin-submit-btn" value="<?php _e('Generate Shortcode', 'feed-them-social'); ?>" onclick="updateTextArea_fb_event();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4><?php _e('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social'); ?></h4>
      
        <div class="feed-them-social-admin-input-label"><?php _e('Facebook Event Feed Shortcode', 'feed-them-social'); ?></div>
        
        <input class="copyme facebook-event-final-shortcode feed-them-social-admin-input" value="" />
        
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-facebook_group-shortcode-form-->







<div class="fts-twitter-shortcode-form"> 
    <form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form" id="fts-twitter-form">
    <h2><?php _e('Twitter Shortcode Generator', 'feed-them-social'); ?></h2>
    <div class="instructional-text"><?php _e('You must copy your <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-twitter-name/" target="_blank">Twitter Name</a> and paste it in the first input below.', 'feed-them-social'); ?></div>
    
      <div class="feed-them-social-admin-input-wrap twitter_name">
        <div class="feed-them-social-admin-input-label"><?php _e('Twitter Name (required)', 'feed-them-social'); ?></div>
        <input type="text" id="twitter_name" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
      
      <?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/twitter-settings-fields.php');
}
else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of Tweets', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->
<?php
}
 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?>
      <input type="button" class="feed-them-social-admin-submit-btn" value="<?php _e('Generate Shortcode', 'feed-them-social'); ?>" onclick="updateTextArea_twitter();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4><?php _e('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social'); ?></h4>
      
        <div class="feed-them-social-admin-input-label"><?php _e('Twitter Feed Shortcode', 'feed-them-social'); ?></div>
        <input class="copyme twitter-final-shortcode feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-twitter-shortcode-form-->


<div class="fts-instagram-shortcode-form">

	<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form" id="fts-instagram-form">
    <h2><?php _e('Convert Instagram Name to ID', 'feed-them-social'); ?></h2>
     <div class="instructional-text"><?php _e('You must copy your <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below', 'feed-them-social'); ?>.</div>
      <div class="feed-them-social-admin-input-wrap convert_instagram_username">
        <div class="feed-them-social-admin-input-label"><?php _e('Instagram Name (required)', 'feed-them-social'); ?></div>
        <input type="text" id="convert_instagram_username" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
     
      <input type="button" class="feed-them-social-admin-submit-btn" value="<?php _e('Convert Instagram Username', 'feed-them-social'); ?>" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />
      
    </form>

    <form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">
    <h2><?php _e('Instagram Shortcode Generator', 'feed-them-social'); ?></h2>
     <div class="instructional-text"><?php _e('If you added your ID above and clicked convert, a number should appear in the input below, now continue.', 'feed-them-social'); ?></div>
     
      <div class="feed-them-social-admin-input-wrap instagram_name">
        <div class="feed-them-social-admin-input-label"><?php _e('Instagram ID # (required)', 'feed-them-social'); ?></div>
        <input type="text" id="instagram_id" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
<?php
  if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	 include('../wp-content/plugins/feed-them-premium/admin/instagram-settings-fields.php');
  }
  else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of Pics', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit. Default is 6.', 'feed-them-social'); ?></div>
  <div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->
<?php } 

if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?> 
     
      <input type="button" class="feed-them-social-admin-submit-btn" value="<?php _e('Generate Shortcode', 'feed-them-social'); ?>" onclick="updateTextArea_instagram();" tabindex="4" style="margin-right:1em;" />
      
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4><?php _e('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social'); ?></h4>
      
        <div class="feed-them-social-admin-input-label"><?php _e('Instagram Feed Shortcode', 'feed-them-social'); ?></div>
        <input class="copyme instagram-final-shortcode feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
    
</div><!--/fts-instagram-shortcode-form-->


<div class="fts-youtube-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form" id="fts-youtube-form">
    <h2><?php _e('YouTube Shortcode Generator', 'feed-them-social'); ?></h2>
    <div class="instructional-text"><?php _e('You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-youtube-name/" target="_blank">YouTube Name</a> and paste it in the first input below.', 'feed-them-social'); ?></div>
      
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/youtube-settings-fields.php');
}
else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('YouTube Name', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of videos', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of videos in each row', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Display First video full size', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

 <a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;"><?php _e('Click to see Premium Version', 'feed-them-social'); ?></a>

<?php
}
?>
    </form>
</div><!--/fts-youtube-shortcode-form-->





<div class="fts-pinterest-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form" id="fts-pinterest-form">
    <h2><?php _e('Pinterest Shortcode Generator', 'feed-them-social'); ?></h2>
    <div class="instructional-text"><?php _e('You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-pinterest-name/" target="_blank">Pinterest Name</a> and paste it in the first input below', 'feed-them-social'); ?>.</div>
      
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/pinterest-settings-fields.php');
}
else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('Pinterest Name', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"><?php _e('# of Boards', 'feed-them-social'); ?></div>
  <div class="feed-them-social-admin-input-default"><?php _e('Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.', 'feed-them-social'); ?></div>
<div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

 <a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;" target="_blank" ><?php _e('Click to see Premium Version', 'feed-them-social'); ?></a>

<?php
}
?>
    </form>
</div><!--/fts-pinterest-shortcode-form-->

	
<div class="clear"></div>
 <div class="feed-them-clear-cache">
 <h2><?php _e('Clear All Cache Option', 'feed-them-social'); ?></h2>
    <div class="use-of-plugin"><?php _e('Please Clear Cache if you have changed a FTS Shortcode. This will Allow you to see the NEW feed\'s options you just set!', 'feed-them-social'); ?></div>
    
<?php if($_GET['cache']=='clearcache'){ 
 	echo '<div class="feed-them-clear-cache-text">'.feed_them_clear_cache().'</div>';
}
?>

    <form method="post" action="?page=feed-them-settings-page&cache=clearcache">
      <input class="feed-them-social-admin-submit-btn" type="submit" value="<?php _e('Clear All FTS Feeds Cache', 'feed-them-social'); ?>" />	
    </form>
  </div><!--/feed-them-clear-cache-->
  
  
  <!-- custom option for padding -->
  <form method="post" class="fts-color-settings-admin-form" action="options.php">
  
   <div class="feed-them-custom-css">
   
   
  
  <?php // get our registered settings from the gq theme functions 
	 	   settings_fields('feed-them-social-settings'); ?> 
           
           
  <?php 
  		$ftsDateTimeFormat = get_option('fts-date-and-time-format');
   ?>
  <h2><?php _e('FaceBook & Twitter Date Format', 'feed-them-social'); ?></h2>
   <fieldset>
        <select id="fts-date-and-time-format" name="fts-date-and-time-format">
            <option value="l, F jS, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'l, F jS, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('l, F jS, Y \a\t g:ia'); ?></option>
            <option value="F j, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('F j, Y \a\t g:ia'); ?></option>
            <option value="F j, Y g:ia" <?php if ($ftsDateTimeFormat == 'F j, Y g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('F j, Y g:ia'); ?></option>
            <option value="F, Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'F, Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('F, Y \a\t g:ia'); ?></option>
            <option value="M j, Y @ g:ia" <?php if ($ftsDateTimeFormat == 'M j, Y @ g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('M j, Y @ g:ia'); ?></option>
            <option value="M j, Y @ G:i" <?php if ($ftsDateTimeFormat == 'M j, Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('M j, Y @ G:i'); ?></option>
            <option value="m/d/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'm/d/Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('m/d/Y \a\t g:ia'); ?></option>
            <option value="m/d/Y @ G:i" <?php if ($ftsDateTimeFormat == 'm/d/Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('m/d/Y @ G:i'); ?></option>
            <option value="d/m/Y \a\t g:ia" <?php if ($ftsDateTimeFormat == 'd/m/Y \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('d/m/Y \a\t g:ia'); ?></option>
            <option value="d/m/Y @ G:i" <?php if ($ftsDateTimeFormat == 'd/m/Y @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('d/m/Y @ G:i'); ?></option>
            <option value="Y/m/d \a\t g:ia" <?php if ($ftsDateTimeFormat == 'Y/m/d \a\t g:ia' ) echo 'selected="selected"'; ?>><?php echo date_i18n('Y/m/d \a\t g:ia'); ?></option>
            <option value="Y/m/d @ G:i" <?php if ($ftsDateTimeFormat == 'Y/m/d @ G:i' ) echo 'selected="selected"'; ?>><?php echo date_i18n('Y/m/d @ G:i'); ?></option>
        </select>
	</fieldset>
<br/>

   <h2><?php _e('Custom CSS Option', 'feed-them-social'); ?></h2>
     
  
     <p>
        <input name="fts-color-options-settings-custom-css" class="fts-color-settings-admin-input" type="checkbox"  id="fts-color-options-settings-custom-css" value="1" <?php echo checked( '1', get_option( 'fts-color-options-settings-custom-css' ) ); ?>/>
        <?php  
                        if (get_option( 'fts-color-options-settings-custom-css' ) == '1') { ?>
                           <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('Custom CSS option is being used now.', 'feed-them-social'); ?> <?php
                        }
                        else	{ ?>
                          <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong> <?php _e('You are using the default CSS.', 'feed-them-social'); ?> <?php
                        }
                           ?>
       </p>
     
         <label class="toggle-custom-textarea-show"><span><?php _e('Show', 'feed-them-social'); ?></span><span class="toggle-custom-textarea-hide"><?php _e('Hide', 'feed-them-social'); ?></span> <?php _e('custom CSS', 'feed-them-social'); ?></label>
        
          <div class="clear"></div>
       <div class="fts-custom-css-text"><?php _e('Thanks for using our plugin :) Add your custom CSS additions or overrides below.', 'feed-them-social'); ?></div>
      <textarea name="fts-color-options-main-wrapper-css-input" class="fts-color-settings-admin-input" id="fts-color-options-main-wrapper-css-input"><?php echo get_option('fts-color-options-main-wrapper-css-input'); ?></textarea>
    
      
      </div><!--/feed-them-custom-css--> 
      
 
 
 
 
    <div class="feed-them-custom-logo-css">
   <h2><?php _e('Powered by Text', 'feed-them-social'); ?></h2>
     
    
  
     <p>
        <input name="fts-powered-text-options-settings" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts-powered-text-options-settings" value="1" <?php echo checked( '1', get_option( 'fts-powered-text-options-settings' ) ); ?>/>
        <?php  
                        if (get_option( 'fts-powered-text-options-settings' ) == '1') { ?>
                           <strong><?php _e('Checked:', 'feed-them-social'); ?></strong> <?php _e('You are not showing the Powered by Logo.', 'feed-them-social'); ?> <?php
                        }
                        else	{ ?>
                          <strong><?php _e('Not Checked:', 'feed-them-social'); ?></strong><?php _e('The Powered by text will appear in the site. Awesome! Thanks so much for sharing.', 'feed-them-social'); ?> <?php
                        }
                           ?>
      </p>
     <br/>
          <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save Changes') ?>" />
      <div class="clear"></div>
    
     
      </div><!--/feed-them-custom-logo-css--> 
 
       </form>
  	<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
  
</div><!--/feed-them-social-admin-wrap-->

<script>
jQuery(function() {    

    jQuery('#shortcode-form-selector').change(function(){
        jQuery('.shortcode-generator-form').hide();
        jQuery('.' + jQuery(this).val()).fadeIn('fast');
    });
	
	<?php 
	//Rotate Plugin
	if(is_plugin_active('fts-rotate/fts-rotate.php')) {?>
	jQuery(".fts-rotate-settings-wrap").hide();
	
	 jQuery('input.fts_rotate_feed').change(function(){
		 var this_id = jQuery(this).closest('form').attr('id');
       if(jQuery("#"+this_id + " input.fts_rotate_feed").is(':checked'))	{
		   jQuery("#"+this_id +" .fts-rotate-settings-wrap").fadeIn();
	   }
	   else	{
	   	jQuery("#"+this_id +" .fts-rotate-settings-wrap").fadeOut();
	   }
    });
	<?php } ?>
	
});


//START Facebook Group//
function updateTextArea_fb_group() {

	var fb_group_id = ' id=' + jQuery("input#fb_group_id").val(); 
	// var fb_group_custom_name = ' custom_name=' + jQuery("select#fb_group_custom_name").val();
	
	if (fb_group_id == " id=") {
	  	 jQuery(".fb_group_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_group_id").focus();
		 return false;
	}
	if (fb_group_id != " id=") {
	  	 jQuery(".fb_group_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	//Rotate Plugin
	if(is_plugin_active('fts-rotate/fts-rotate.php')) {?>
		
		var rotate_form_id = "fts-fb-group-form";
		
		<?php include('../wp-content/plugins/fts-rotate/admin/js/fts-rotate-settings-options.js');
	}
	
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/facebook-group-settings-js.js');
	}
	else 	{
	?>
		
		//	if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//					var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + ' type=group' + final_fts_rotate_shortcode + ']';
//			}
//			else	{
					// var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + fb_group_custom_name + ' type=group]';
					var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + ' type=group]';
			//}	
	
<?php } ?>

jQuery('.facebook-group-final-shortcode').val(final_fb_group_shorcode);
	
	jQuery('.fb-group-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Group//

//START Page Group//
function updateTextArea_fb_page() {

	var fb_page_id = ' id=' + jQuery("input#fb_page_id").val(); 
	var fb_page_posts_displayed = ' posts_displayed=' + jQuery("select#fb_page_posts_displayed").val();

	
	if (fb_page_id == " id=") {
	  	 jQuery(".fb_page_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_page_id").focus();
		 return false;
	}
	if (fb_page_id != " id=") {
	  	 jQuery(".fb_page_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	//Rotate Plugin
	if(is_plugin_active('fts-rotate/fts-rotate.php')) {?>
		
		var rotate_form_id = "fts-fb-page-form";
		
		<?php include('../wp-content/plugins/fts-rotate/admin/js/fts-rotate-settings-options.js');
	}
	
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/facebook-page-settings-js.js');
	}
	else 	{ ?>
	
		//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//				var final_fb_page_shorcode = '[fts facebook page' + fb_page_id + ' type=page' + final_fts_rotate_shortcode + ']';
//			}
//		else	{
				var final_fb_page_shorcode = '[fts facebook page' + fb_page_id + fb_page_posts_displayed + ' type=page]';
	//	}
		
<?php } ?>

jQuery('.facebook-page-final-shortcode').val(final_fb_page_shorcode);
	
	jQuery('.fb-page-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Page//

//START Facebook Event//
function updateTextArea_fb_event() {

	var fb_event_id = ' id=' + jQuery("input#fb_event_id").val(); 
	
	if (fb_event_id == " id=") {
	  	 jQuery(".fb_event_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_event_id").focus();
		 return false;
	}
	if (fb_event_id != " id=") {
	  	 jQuery(".fb_event_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	//Rotate Plugin
	if(is_plugin_active('fts-rotate/fts-rotate.php')) {?>
		
		var rotate_form_id = "fts-fb-event-form";
		
		<?php include('../wp-content/plugins/fts-rotate/admin/js/fts-rotate-settings-options.js');
	}
	
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/facebook-event-settings-js.js');
	}
	else 	{
	?>
		//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//				var final_fb_event_shorcode = '[fts facebook event' + fb_page_id + ' type=event' + final_fts_rotate_shortcode + ']';
//			}
//		else	{
				var final_fb_event_shorcode = '[fts facebook event' + fb_event_id + ' type=event]';
	//	}		
		
<?php } ?>

jQuery('.facebook-event-final-shortcode').val(final_fb_event_shorcode);
	
	jQuery('.fb-event-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Event//

//START Twitter//
function updateTextArea_twitter() {

	var twitter_name = ' twitter_name=' + jQuery("input#twitter_name").val();
	
	
	
	if (twitter_name == " twitter_name=") {
	  	 jQuery(".twitter_name").addClass('fts-empty-error');  
      	 jQuery("input#twitter_name").focus();
		 return false;
		 
	}
	if (twitter_name != " twitter_name=") {
	  	 jQuery(".twitter_name").removeClass('fts-empty-error');  
	}
	
	<?php
	
	if(is_plugin_active('fts-rotate/fts-rotate.php')) {?>
		
		var rotate_form_id = "fts-twitter-form";
		
		<?php include('../wp-content/plugins/fts-rotate/admin/js/fts-rotate-settings-options.js');
	}
	
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/twitter-settings-js.js');
	}
	
	else 	{ ?>
	
	//	if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//			var final_twitter_shorcode = '[fts twitter' + twitter_name + final_fts_rotate_shortcode + ']';
//		}
//		else	{
			var final_twitter_shorcode = '[fts twitter' + twitter_name + ']';
		//}		
	
<?php } ?>

jQuery('.twitter-final-shortcode').val(final_twitter_shorcode);
	
	jQuery('.twitter-shortcode-form .final-shortcode-textarea').slideDown();
}
//END Twitter//

//START Instagram//
function updateTextArea_instagram() {

	var instagram_id = ' instagram_id=' + jQuery("input#instagram_id").val(); 
	
	if (instagram_id == " instagram_id=") {
	  	 jQuery(".instagram_id").addClass('fts-empty-error');  
      	 jQuery("input#instagram_id").focus();
		 return false;
	}
	if (instagram_id != " instagram_id=") {
	  	 jQuery(".instagram_id").removeClass('fts-empty-error');  
	}
	
	<?php 
	//Rotate Plugin
	if(is_plugin_active('fts-rotate/fts-rotate.php')) {?>
		
		var rotate_form_id = "fts-instagram-form";
		
		<?php include('../wp-content/plugins/fts-rotate/admin/js/fts-rotate-settings-options.js');
	}
	
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/instagram-settings-js.js');
	}//end if Premium version
	else 	{ ?>
		//if (final_fts_rotate_shortcode && jQuery("#"+ rotate_form_id + " input.fts_rotate_feed").is(':checked')){
//				var final_instagram_shorcode = '[fts instagram' + instagram_id + final_fts_rotate_shortcode +']'
//		}
//		else	{
				var final_instagram_shorcode = '[fts instagram' + instagram_id + ']';
		//}		
		
<?php } ?>

jQuery('.instagram-final-shortcode').val(final_instagram_shorcode);
	
	jQuery('.instagram-shortcode-form .final-shortcode-textarea').slideDown();
}
//END Instagram//

//START convert Instagram name to id//
function converter_instagram_username() {
	
	var convert_instagram_username = jQuery("input#convert_instagram_username").val(); 
	
	if (convert_instagram_username == "") {
	  	 jQuery(".convert_instagram_username").addClass('fts-empty-error');  
      	 jQuery("input#convert_instagram_username").focus();
		 return false;
	}
	if (convert_instagram_username  != "") {
	  	 jQuery(".convert_instagram_username").removeClass('fts-empty-error');
		 
			var username = jQuery("input#convert_instagram_username").val();
			
			console.log(username);
			jQuery.getJSON("https://api.instagram.com/v1/users/search?q="+username+"&access_token=267791236.f78cc02.bea846f3144a40acbf0e56b002c112f8&callback=?",
			  {
				format: "json"
			  },
			  function(data) {
					console.log(data);
					var final_instagram_us_id = data.data[0].id;
					
					jQuery('#instagram_id').val(final_instagram_us_id);
					
					jQuery('.final-instagram-user-id-textarea').slideDown();
   			 });
	}
}

//select all 
jQuery(".copyme").focus(function() {
    var jQuerythis = jQuery(this);
    jQuerythis.select();

    // Work around Chrome's little problem
    jQuerythis.mouseup(function() {
        // Prevent further mouseup intervention
        jQuerythis.unbind("mouseup");
        return false;
    });
});


jQuery( document ).ready(function() {
  jQuery( ".toggle-custom-textarea-show" ).click(function() {  
		 jQuery('textarea#fts-color-options-main-wrapper-css-input').slideToggle();
		  jQuery('.toggle-custom-textarea-show span').toggle();
		  jQuery('.fts-custom-css-text').toggle();
		  
}); 


<?php
	//show the js for the discount option under social icons on the settings page
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
				// do not show the js below
		}
	else { ?>
		jQuery( "#discount-for-review" ).click(function() {  
			 jQuery('.discount-review-text').slideToggle();
		});
<?php } ?>
  }); //end document ready
  

</script>
<?php
	//Premium JS
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/premium-js.php'); 
		}
	}
?>