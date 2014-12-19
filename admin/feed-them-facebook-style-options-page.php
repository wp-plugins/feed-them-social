<?php
//Functions for the 
function feed_them_facebook_options_page() {
	$fts_functions = new feed_them_social_functions();
?>

<link href='http://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>				
<div class="feed-them-social-admin-wrap">


 <h1><?php _e('Facebook Feed Options', 'feed-them-social'); ?></h1>
  <div class="use-of-plugin"><?php _e('Change the color of your facebook feed and more using the options below.', 'feed-them-social'); ?></div>
  <!-- custom option for padding -->
  <form method="post" class="fts-facebook-feed-options-form" action="options.php"><br/>
  
  <?php // get our registered settings from the fts functions 
	 	   settings_fields('fts-facebook-feed-style-options'); ?>        
   <?php 
   
	if (!is_plugin_active('feed-them-premium/feed-them-premium.php')){
		  	
			 $FB_style_options = array(
				__('Feed Text Color', 'feed-them-social'),
				__('Feed Link Color', 'feed-them-social'),
				__('Feed Link Color Hover', 'feed-them-social'),
				__('Feed Width', 'feed-them-social'),
				__('Feed Margin ', 'feed-them-social'),
				__('Feed Padding', 'feed-them-social'),
				__('Feed Background Color', 'feed-them-social'),
				__('Feed Grid Posts Background Color (Grid style feeds ONLY)', 'feed-them-social'),
				__('Feed Border Bottom Color', 'feed-them-social'),
			 );
			 
			 echo $fts_functions->need_fts_premium_fields($FB_style_options);
	 }else { ?>        
 	  <div class="feed-them-social-admin-input-wrap">
           <div class="feed-them-social-admin-input-label fts-fb-text-color-label"><?php _e('Feed Text Color', 'feed-them-social'); ?></div>
           <input type="text" name="fb_text_color" class="feed-them-social-admin-input fb-text-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-text-color-input" placeholder="#222" value="<?php echo get_option('fb_text_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->	
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-link-color-label"><?php _e('Feed Link Color', 'feed-them-social'); ?></div>
           <input type="text" name="fb_link_color" class="feed-them-social-admin-input fb-link-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-link-color-input" placeholder="#222" value="<?php echo get_option('fb_link_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-link-color-hover-label"><?php _e('Feed Link Color Hover', 'feed-them-social'); ?></div>
           <input type="text" name="fb_link_color_hover" class="feed-them-social-admin-input fb-link-color-hover-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-link-color-hover-input" placeholder="#ddd" value="<?php echo get_option('fb_link_color_hover');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->	
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-feed-width-label"><?php _e('Feed Width', 'feed-them-social'); ?></div>
           <input type="text" name="fb_feed_width" class="feed-them-social-admin-input fb-feed-width-input"  id="fb-feed-width-input" placeholder="500px" value="<?php echo get_option('fb_feed_width');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->	
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-feed-margin-label"><?php _e('Feed Margin <br/><small>To center feed type auto</small>', 'feed-them-social'); ?></div>
           <input type="text" name="fb_feed_margin" class="feed-them-social-admin-input fb-feed-margin-input"  id="fb-feed-margin-input" placeholder="10px" value="<?php echo get_option('fb_feed_margin');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-feed-padding-label"><?php _e('Feed Padding', 'feed-them-social'); ?></div>
           <input type="text" name="fb_feed_padding" class="feed-them-social-admin-input fb-feed-padding-input"  id="fb-feed-padding-input" placeholder="10px" value="<?php echo get_option('fb_feed_padding');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-feed-background-color-label"><?php _e('Feed Background Color', 'feed-them-social'); ?></div>
           <input type="text" name="fb_feed_background_color" class="feed-them-social-admin-input fb-feed-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-feed-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_feed_background_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-grid-posts-background-color-label"><?php _e('Feed Grid Posts Background Color (Grid style feeds ONLY)', 'feed-them-social'); ?></div>
           <input type="text" name="fb_grid_posts_background_color" class="feed-them-social-admin-input fb-grid-posts-background-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-grid-posts-background-color-input" placeholder="#ddd" value="<?php echo get_option('fb_grid_posts_background_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->
      
      
      <div class="feed-them-social-admin-input-wrap"> 
           <div class="feed-them-social-admin-input-label fts-fb-border-bottom-color-label"><?php _e('Feed Border Bottom Color', 'feed-them-social'); ?></div>
           <input type="text" name="fb_border_bottom_color" class="feed-them-social-admin-input fb-border-bottom-color-input color {hash:true,caps:false,required:false,adjust:false,pickerFaceColor:'#eee',pickerFace:3,pickerBorder:0,pickerInsetColor:'white'}"  id="fb-border-bottom-color-input" placeholder="#ddd" value="<?php echo get_option('fb_border_bottom_color');?>"/>
      <div class="clear"></div>
 	  </div><!--/fts-facebook-feed-styles-input-wrap-->
      
    <?php }//END IF PREMIUM ?>   
      
      
      <h2><?php _e('Facebook API Token', 'feed-them-social'); ?></h2>  
         <?php
		 
         $test_app_token_id = get_option('fts_facebook_custom_api_token');
		 if (!empty($test_app_token_id)){
		   $fts_fb_access_token = '226916994002335|ks3AFvyAOckiTA1u_aDoI4HYuuw';
		   $test_app_token_URL = array(
					'app_token_id' => 'https://graph.facebook.com/debug_token?input_token='.$test_app_token_id.'&access_token='.$test_app_token_id
				//	'app_token_id' => 'https://graph.facebook.com/oauth/access_token?client_id=705020102908771&client_secret=70166128c6a7b5424856282a5358f47b&grant_type=fb_exchange_token&fb_exchange_token=CAAKBNkjLG2MBAK5jVUp1ZBCYCiLB8ZAdALWTEI4CesM8h3DeI4Jotngv4TKUsQZBwnbw9jiZCgyg0eEmlpiVauTsReKJWBgHe31xWCsbug1Tv3JhXZBEZBOdOIaz8iSZC6JVs4uc9RVjmyUq5H52w7IJVnxzcMuZBx4PThN3CfgKC5E4acJ9RnblrbKB37TBa1yumiPXDt72yiISKci7sqds0WFR3XsnkwQZD'
			);
		  
		  //Test App ID
		  // Leave these for reference: 
		  // App token for FTS APP2: 358962200939086|lyXQ5-zqXjvYSIgEf8mEhE9gZ_M
		  // App token for FTS APP3: 705020102908771|rdaGxW9NK2caHCtFrulCZwJNPyY
	 	  $test_app_token_response = $fts_functions->fts_get_feed_json($test_app_token_URL);
		  $test_app_token_response = json_decode($test_app_token_response['app_token_id']);
		 }
	 	
	 
	  ?>
      
         <div class="fts-facebook-custom-api-token-label"><?php _e('Facebook App ID or User Token (for all facebook feeds). Not required to make the feed work. A User Token however will allow you to see certain post types that may be returning the message, Undefined Attachment. See how to <a href="http://www.slickremix.com/docs/create-facebook-app-id-or-user-token" target="_blank">GET APP ID or USER TOKEN</a>.', 'feed-them-social'); ?></div>
      
      <div class="feed-them-social-admin-input-wrap" style="margin-bottom:0px;"> 
           <div class="feed-them-social-admin-input-label fts-twitter-border-bottom-color-label"><?php _e('APP ID or Token optional', 'feed-them-social'); ?></div>
         <input type="text" name="fts_facebook_custom_api_token" class="feed-them-social-admin-input"  id="fts_facebook_custom_api_token" value="<?php echo get_option('fts_facebook_custom_api_token');?>"/>
      <div class="clear"></div>
 	  </div>
      
      
        <?php if (!empty($test_app_token_response)){	 
			 	if(isset($test_app_token_response->data->is_valid)){
					echo'<div class="fts-successful-api-token">'. __('Your access token is working!', 'feed-them-social').'</div>';
				}
				if(isset($test_app_token_response->data->error->message) || isset($test_app_token_response->error->message)){
					if(isset($test_app_token_response->data->error->message)){
						echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').' '.$test_app_token_response->data->error->message.'</div>';
					}
					if(isset($test_app_token_response->error->message)){
						echo'<div class="fts-failed-api-token">'. __('Oh No something\'s wrong.', 'feed-them-social').' '.$test_app_token_response->error->message.'</div>';
					}
					
				}
				
		} else{
			  		echo'<div class="fts-successful-api-token">'. __('You are using our Default Access Token.', 'feed-them-social').'</div>';
			  }
			  ?>
      
   <div class="clear"></div>
   
   <input type="submit" class="feed-them-social-admin-submit-btn" value="<?php _e('Save All Changes') ?>" />
   
  
   </form>
   


            
  	<a class="feed-them-social-admin-slick-logo" href="http://www.slickremix.com" target="_blank"></a>
  
</div><!--/feed-them-social-admin-wrap-->

<?php } ?>