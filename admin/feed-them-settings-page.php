<?php

//Main setting page function
function feed_them_settings_page() {
	
add_action( 'wp_enqueue_style', 'feed_them_admin_css' );

?>
<link href='http://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>				
<div class="feed-them-social-admin-wrap">
  <h1>Feed Them Social</h1>
  <div class="use-of-plugin">Select what type of feed you would like to generate a shortcode for using the select option below. Then you'll copy that shortcode to a page or post.</div>
  
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
		 <div id="discount-for-review">20% off Premium Version</div>
    <div class="discount-review-text">Leave us a <a href="http://wordpress.org/support/view/plugin-reviews/feed-them-social" target="_blank">review here</a>, then send us an <a href="mailto:info@slickremix.com">email</a> letting us know and we'll send you a coupon code for 20% ASAP!</div>
<?php } ?>
   
  </div>

	<form class="feed-them-social-admin-form"> 
    	<select id="shortcode-form-selector">
        	<option value="">Please Select Feed Type </option>
            <option value="fb-page-shortcode-form">Facebook Page Feed</option>
        	<option value="fb-group-shortcode-form">Facebook Group Feed</option>
        	<option value="fb-event-shortcode-form">Facebook Event Feed</option>
            <option value="twitter-shortcode-form">Twitter Feed</option>
            <option value="instagram-shortcode-form">Instagram Feed</option>
            <option value="youtube-shortcode-form">YouTube Feed</option>
            <option value="pinterest-shortcode-form">Pinterest Feed</option>
        </select>
    </form><!--/feed-them-social-admin-form-->

<div class="fts-facebook_group-shortcode-form">
<form class="feed-them-social-admin-form shortcode-generator-form fb-group-shortcode-form" id="fts-fb-group-form">
    <h2>Facebook Group Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">Facebook ID</a> and paste it in the first input below.</div>
      <div class="feed-them-social-admin-input-wrap fb_group_id">
        <div class="feed-them-social-admin-input-label">Facebook Group ID (required)</div>
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
  <div class="feed-them-social-admin-input-label"># of Posts</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show the Group Title?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. </div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show the Group Description?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Amount of words per post?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
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
      
      <input type="button" class="feed-them-social-admin-submit-btn" value="Generate Facebook Group Shortcode" onclick="updateTextArea_fb_group();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>
      
        <div class="feed-them-social-admin-input-label">Facebook Group Feed Shortcode</div>
        
        <input class="copyme facebook-group-final-shortcode feed-them-social-admin-input" value="" />
        
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-facebook_group-shortcode-form-->


<div class="fts-facebook_page-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form fb-page-shortcode-form" id="fts-fb-page-form">
    <h2>Facebook Page Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/09/09/how-to-get-your-facebook-page-vanity-url/" target="_blank">Facebook Page ID</a> and paste it in the first input below.</div>
      <div class="feed-them-social-admin-input-wrap fb_page_id">
        <div class="feed-them-social-admin-input-label">Facebook Page ID (required)</div>
        <input type="text" id="fb_page_id" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
     
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Post Type Visible</div>
  <select id="fb_page_posts_displayed" class="feed-them-social-admin-input">
    <option selected="selected" value="page_only">Display Posts made by Page only</option>
    <option value="everyone">Display Posts made by Everyone</option>
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
  <div class="feed-them-social-admin-input-label"># of Posts</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show the Page Title?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. </div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show the Page Description?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Amount of words per post?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<?php
}
 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?>
      <input type="button" class="feed-them-social-admin-submit-btn" value="Generate Facebook Page Shortcode" onclick="updateTextArea_fb_page();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>
      
        <div class="feed-them-social-admin-input-label">Facebook Page Feed Shortcode</div>
        
        <input class="copyme facebook-page-final-shortcode feed-them-social-admin-input" value="" />
        
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-facebook_page-shortcode-form-->


<div class="fts-facebook_event-shortcode-form">
<form class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form">
    <h2>Facebook Event Shortcode Generator.</h2>
    <div class="instructional-text">Copy your <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-event-id/" target="_blank">Facebook Page Event ID or Group Event ID</a> and paste it in the first input below.</div>
      <div class="feed-them-social-admin-input-wrap fb_event_id">
        <div class="feed-them-social-admin-input-label">Facebook Event ID (required)</div>
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
  <div class="feed-them-social-admin-input-label"># of Posts</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show the Event Title?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. </div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Show the Event Description?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div> <!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Amount of words per post?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<?php
}

if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?> 
      
      <input type="button" class="feed-them-social-admin-submit-btn" value="Generate Facebook Event Shortcode" onclick="updateTextArea_fb_event();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>
      
        <div class="feed-them-social-admin-input-label">Facebook Event Feed Shortcode</div>
        
        <input class="copyme facebook-event-final-shortcode feed-them-social-admin-input" value="" />
        
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-facebook_group-shortcode-form-->







<div class="fts-twitter-shortcode-form"> 
    <form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form" id="fts-twitter-form">
    <h2>Twitter Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-twitter-name/" target="_blank">Twitter Name</a> and paste it in the first input below.</div>
    
      <div class="feed-them-social-admin-input-wrap twitter_name">
        <div class="feed-them-social-admin-input-label">Twitter Name (required)</div>
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
  <div class="feed-them-social-admin-input-label"># of Tweets</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->
<?php
}
 
if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?>
      <input type="button" class="feed-them-social-admin-submit-btn" value="Generate Twitter Shortcode" onclick="updateTextArea_twitter();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>
      
        <div class="feed-them-social-admin-input-label">Twitter Feed Shortcode</div>
        <input class="copyme twitter-final-shortcode feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-twitter-shortcode-form-->


<div class="fts-instagram-shortcode-form">

	<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form" id="fts-instagram-form">
    <h2>Convert Instagram Name to ID</h2>
     <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below.</div>
      <div class="feed-them-social-admin-input-wrap convert_instagram_username">
        <div class="feed-them-social-admin-input-label">Instagram Name (required)</div>
        <input type="text" id="convert_instagram_username" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
     
      <input type="button" class="feed-them-social-admin-submit-btn" value="Convert Instagram Username" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />
      
    </form>

    <form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">
    <h2>Instagram Shortcode Generator</h2>
     <div class="instructional-text">If you added your ID above and clicked convert, a number should appear in the input below, now continue.</div>
     
      <div class="feed-them-social-admin-input-wrap instagram_name">
        <div class="feed-them-social-admin-input-label">Instagram ID # (required)</div>
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
  <div class="feed-them-social-admin-input-label"># of Pics</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. Default is 6.</div>
  <div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->
<?php } 

if(is_plugin_active('fts-rotate/fts-rotate.php')) {
	include('../wp-content/plugins/fts-rotate/admin/fts-rotate-settings-fields.php');
}
?> 
     
      <input type="button" class="feed-them-social-admin-submit-btn" value="Generate instagram Shortcode" onclick="updateTextArea_instagram();" tabindex="4" style="margin-right:1em;" />
      
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>
      
        <div class="feed-them-social-admin-input-label">Instagram Feed Shortcode</div>
        <input class="copyme instagram-final-shortcode feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
    
</div><!--/fts-instagram-shortcode-form-->


<div class="fts-youtube-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form" id="fts-youtube-form">
    <h2>YouTube Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-youtube-name/" target="_blank">YouTube Name</a> and paste it in the first input below.</div>
      
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/youtube-settings-fields.php');
}
else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">YouTube Name</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of videos</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of videos in each row</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. </div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Display First video full size</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

 <a href="http://www.slickremix.com/product/feed-them-social-premium-extension/" target="_blank" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;">Click to see Premium Version</a>

<?php
}
?>
    </form>
</div><!--/fts-youtube-shortcode-form-->





<div class="fts-pinterest-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form" id="fts-pinterest-form">
    <h2>Pinterest Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-pinterest-name/" target="_blank">Pinterest Name</a> and paste it in the first input below.</div>
      
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/pinterest-settings-fields.php');
}
else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Pinterest Name</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of Boards</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div>
<!--/feed-them-social-admin-input-wrap-->

 <a href="http://www.slickremix.com/product/feed-them-social-premium-extension/" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;" target="_blank" >Click to see Premium Version</a>

<?php
}
?>
    </form>
</div><!--/fts-pinterest-shortcode-form-->


<div class="clear"></div>
 <div class="feed-them-clear-cache">
 <h2>Clear All Cache Option</h2>
    <div class="use-of-plugin">Please Clear Cache if you have changed a FTS Shortcode. This will Allow you to see the NEW feed's options you just set!</div>
    
<?php if($_GET['cache']=='clearcache'){ 
 	echo '<div class="feed-them-clear-cache-text">'.feed_them_clear_cache().'</div>';
}
?>

    <form method="post" action="?page=feed-them-settings-page&cache=clearcache">
      <input class="feed-them-social-admin-submit-btn" type="submit" value="Clear All FTS Feeds Cache" />	
    </form>
  </div><!--/feed-them-clear-cache-->
  
  
  
   <div class="feed-them-custom-css">
   <h2>Custom CSS Option</h2>
     
    
  <!-- custom option for padding -->
  <form method="post" class="fts-color-settings-admin-form" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
  
     <p>
        <input name="fts-color-options-settings-custom-css" class="fts-color-settings-admin-input" type="checkbox"  id="fts-color-options-settings-custom-css" value="1" <?php echo checked( '1', get_option( 'fts-color-options-settings-custom-css' ) ); ?>/>
        <?php  
                        if (get_option( 'fts-color-options-settings-custom-css' ) == '1') {
                           echo "<strong>Checked:</strong> Custom CSS option is being used now.";
                        }
                        else	{
                          echo "<strong>Not Checked:</strong> You are using the default CSS.";
                        }
                           ?>
       </p>
     
         <label class="toggle-custom-textarea-show"><span>Show</span><span class="toggle-custom-textarea-hide">Hide</span> custom CSS</label>
          <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save Changes') ?>" />
          <div class="clear"></div>
       <div class="fts-custom-css-text">Thanks for using our plugin :) Add your custom CSS additions or overrides below.</div>
      <textarea name="fts-color-options-main-wrapper-css-input" class="fts-color-settings-admin-input" id="fts-color-options-main-wrapper-css-input"><?php echo get_option('fts-color-options-main-wrapper-css-input'); ?></textarea>
    
      
      <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="fts-color-options-settings-custom-css, fts-color-options-main-wrapper-css-input" />
   
    
    
      </form>
      </div><!--/feed-them-custom-css--> 
      
 
 
 
 
    <div class="feed-them-custom-logo-css">
   <h2>Powered by Text</h2>
     
    
  <!-- custom option for padding -->
  <form method="post" class="fts-powered-by-settings-admin-form" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
  
     <p>
        <input name="fts-powered-text-options-settings" class="fts-powered-by-settings-admin-input" type="checkbox" id="fts-powered-text-options-settings" value="1" <?php echo checked( '1', get_option( 'fts-powered-text-options-settings' ) ); ?>/>
        <?php  
                        if (get_option( 'fts-powered-text-options-settings' ) == '1') {
                           echo "<strong>Checked:</strong> You are not showing the Powered by Logo.";
                        }
                        else	{
                          echo "<strong>Not Checked:</strong>The Powered by text will appear in the site. Awesome! Thanks so much for sharing.";
                        }
                           ?>
      </p>
     
          <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save Changes') ?>" />
      <div class="clear"></div>
      <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="fts-powered-text-options-settings" />
   
    
    
      </form>
      </div><!--/feed-them-custom-logo-css--> 
 
      
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