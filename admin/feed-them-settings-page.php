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
  </div>

	<form class="feed-them-social-admin-form"> 
    	<select id="shortcode-form-selector">
        	<option value="">Please Select Feed Type </option>
        	<option value="fb-group-shortcode-form">Facebook Group Feed</option>
            <option value="twitter-shortcode-form">Twitter Feed</option>
            <option value="instagram-shortcode-form">Instagram Feed</option>
            <option value="youtube-shortcode-form">YouTube Feed</option>
            <option value="pinterest-shortcode-form">Pinterest Feed</option>
        </select>
    </form><!--/feed-them-social-admin-form-->

<div class="fts-facebook_group-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form fb-group-shortcode-form">
    <h2>Facebook Group Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">Facebook ID</a> and paste it in the first input below.</div>
      <div class="feed-them-social-admin-input-wrap fb_group_id">
        <div class="feed-them-social-admin-input-label">Facebook Group ID (required)</div>
        <input type="text" id="fb_group_id" class="feed-them-social-admin-input" value="" />
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
     
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/facebook-group-settings-fields.php');
}
else 	{
?>

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of Posts (optional)</div>
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

<?php
}
?>
      
      <input type="button" class="feed-them-social-admin-submit-btn" value="Generate Facebook Group Shortcode" onclick="updateTextArea_fb_group();" tabindex="4" style="margin-right:1em;" />
      <div class="feed-them-social-admin-input-wrap final-shortcode-textarea">
      
      	<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>
      
        <div class="feed-them-social-admin-input-label">Facebook Feed Shortcode</div>
        
        <input class="copyme facebook-group-final-shortcode feed-them-social-admin-input" value="" />
        
    <div class="clear"></div>
      </div><!--/feed-them-social-admin-input-wrap-->
    
    </form>
</div><!--/fts-facebook_group-shortcode-form-->

<div class="fts-twitter-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form">
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
  <div class="feed-them-social-admin-input-label"># of Tweets (optional)</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->
<?php
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

	<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">
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
  <div class="feed-them-social-admin-input-label"># of Pics (optional)</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. Default is 6.</div>
  <div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->
<?php } ?>
 
     
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
    <form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form">
    <h2>YouTube Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-youtube-name/" target="_blank">YouTube Name</a> and paste it in the first input below.</div>
      
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/youtube-settings-fields.php');
}
else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">YouTube Name (required)</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of videos (required)</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of videos in each row?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit. </div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Display First video full size?</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

 <a href="http://www.slickremix.com/product/feed-them-social-premium-extension/" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; max-width:192px; text-decoration:none !important;">Click here for Premium Plugin</a>

<?php
}
?>
    </form>
</div><!--/fts-youtube-shortcode-form-->





<div class="fts-pinterest-shortcode-form">
    <form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form">
    <h2>Pinterest Shortcode Generator</h2>
    <div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-pinterest-name/" target="_blank">Pinterest Name</a> and paste it in the first input below.</div>
      
<?php
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
   include('../wp-content/plugins/feed-them-premium/admin/pinterest-settings-fields.php');
}
else 	{
?>
<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label">Pinterest Name (required)</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

<div class="feed-them-social-admin-input-wrap">
  <div class="feed-them-social-admin-input-label"># of Boards (required)</div>
  <div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/product/feed-them-social-premium-extension/">premium version</a> to edit.</div>
<div class="clear"></div>
</div><!--/feed-them-social-admin-input-wrap-->

 <a href="http://www.slickremix.com/product/feed-them-social-premium-extension/" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; max-width:192px; text-decoration:none !important;">Click here for Premium Plugin</a>

<?php
}
?>
    </form>
</div><!--/fts-pinterest-shortcode-form-->



      
  	<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
  
</div><!--/feed-them-social-admin-wrap-->


<script>

jQuery(function() {    
    jQuery('#shortcode-form-selector').change(function(){
        jQuery('.shortcode-generator-form').hide();
        jQuery('.' + jQuery(this).val()).fadeIn('fast');
    });
});


//START Facebook Group//
function updateTextArea_fb_group() {

	var fb_group_id = ' id=' + jQuery("input#fb_group_id").val(); 
	
	if (fb_group_id == " id=") {
	  	 jQuery(".fb_group_id").addClass('fts-empty-error');  
      	 jQuery("input#fb_group_id").focus();
		 return false;
	}
	if (fb_group_id != " id=") {
	  	 jQuery(".fb_group_id").removeClass('fts-empty-error');  
	}
	
	<?php
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/facebook-group-settings-js.js');
	}
	else 	{
	?>
		var final_fb_group_shorcode = '[fts facebook group' + fb_group_id + ']'
	<?php
	}
	?>

jQuery('.facebook-group-final-shortcode').val(final_fb_group_shorcode);
	
	jQuery('.fb-group-shortcode-form .final-shortcode-textarea').slideDown();
	
}
//END Facebook Group//

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
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/twitter-settings-js.js');
	}
	else 	{
	?>
		var final_twitter_shorcode = '[fts twitter' + twitter_name + ']'
	<?php
	}
	?>

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
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/instagram-settings-js.js');
	}
	else 	{
	?>
		var final_instagram_shorcode = '[fts instagram' + instagram_id + ']'
	<?php
	}
	?>

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

<?php
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include('../wp-content/plugins/feed-them-premium/admin/js/youtube-settings-js.js'); 
	}
?>


<?php
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) { 
	    include('../wp-content/plugins/feed-them-premium/admin/js/pinterest-settings-js.js');
	}
?>
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
</script>

<?php
}
?>