<?php
//Functions for the 
function feed_them_twitter_options_page() {

$fts_functions = new feed_them_social_functions();
?>

<link href='http://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>				
<div class="feed-them-social-admin-wrap">


 <h1><?php _e('Twitter Feed Options', 'feed-them-social'); ?></h1>
  <div class="use-of-plugin"><?php _e('Change the color of your twitter feed and more using the options below.', 'feed-them-social'); ?></div>
  <!-- custom option for padding -->
  <form method="post" class="fts-twitter-feed-options-form" action="options.php"><br/>
  
  <?php // get our registered settings from the fts functions 
	 	   settings_fields('fts-twitter-feed-style-options'); ?>        
   <?php 
   
	if (!is_plugin_active('feed-them-premium/feed-them-premium.php')){
		  	 
			 $twitter_style_options = array(
				'Feed Text Color',
				'Feed Link Color',
				'Feed Link Color Hover',
				'Feed Width',
				'Feed Margin ',
				'Feed Padding',
				'Feed Background Color',
				'Feed Border Bottom Color',
			 );
			 
			 echo $fts_functions->need_fts_premium_fields($twitter_style_options);
			
	 }else { ?>        
 	  <div class="feed-them-social-admin-input-wrap">
           <div class="feed-them-social-admin-input-label fts-twitter-text-color-label"><?php _e('Feed Text Color', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_text_color" class="feed-them-social-admin-input twitter-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-text-color-input" placeholder="#222" value="<?php echo get_option('twitter_text_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->	
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-twitter-link-color-label"><?php _e('Feed Link Color', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_link_color" class="feed-them-social-admin-input twitter-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-link-color-input" placeholder="#222" value="<?php echo get_option('twitter_link_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-twitter-link-color-hover-label"><?php _e('Feed Link Color Hover', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_link_color_hover" class="feed-them-social-admin-input twitter-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-link-color-hover-input" placeholder="#ddd" value="<?php echo get_option('twitter_link_color_hover');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->	
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-twitter-feed-width-label"><?php _e('Feed Width', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_feed_width" class="feed-them-social-admin-input twitter-feed-width-input"  id="twitter-feed-width-input" placeholder="500px" value="<?php echo get_option('twitter_feed_width');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->	
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-twitter-feed-margin-label"><?php _e('Feed Margin <br/><small>To center feed type auto</small>', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_feed_margin" class="feed-them-social-admin-input twitter-feed-margin-input"  id="twitter-feed-margin-input" placeholder="10px" value="<?php echo get_option('twitter_feed_margin');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-twitter-feed-padding-label"><?php _e('Feed Padding', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_feed_padding" class="feed-them-social-admin-input twitter-feed-padding-input"  id="twitter-feed-padding-input" placeholder="10px" value="<?php echo get_option('twitter_feed_padding');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-twitter-feed-background-color-label"><?php _e('Feed Background Color', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_feed_background_color" class="feed-them-social-admin-input twitter-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-feed-background-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_feed_background_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
      
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label"><?php _e('Feed Border Bottom Color', 'feed-them-social'); ?></div>
           <input type="text" name="twitter_border_bottom_color" class="feed-them-social-admin-input twitter-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="twitter-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('twitter_border_bottom_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-twitter-feed-styles-input-wrap-->
      
    <?php }//END IF PREMIUM ?>   
      
      
   
 <?php if (is_plugin_active('feed-them-premium/feed-them-premium.php')){ ?>  
   <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
  <?php } ?> 
  
   </form>
   


            
  	<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
  
</div><!--/feed-them-social-admin-wrap-->

<?php } ?>