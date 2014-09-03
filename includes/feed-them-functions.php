<?php
/************************************************
 	Function file for Feed Them Social plugin
************************************************/

class feed_them_social_functions {
	
	function __construct() {
	  $root_file = plugin_dir_path(dirname( __FILE__));
	  $this->premium = str_replace('feed-them-social/','feed-them-premium/', $root_file);
	}
	
	/*
     * For Loading in the Admin.
     */ 
     function init(){
		 
	  if ( is_admin() ){
		//Adds setting page to FTS menu
		add_action('admin_init', array( $this,'fts_settings_page_register_settings' ));
		add_action('admin_menu', array( $this,'Feed_Them_Main_Menu'));
		add_action('admin_menu', array( $this,'Feed_Them_Submenu_Pages'));
		
		// THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
		add_action('admin_enqueue_scripts', array( $this,'feed_them_admin_css'));
		
		//Main Settings Page
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
	 	 add_action('admin_enqueue_scripts',  array( $this,'feed_them_settings'));
		}
		
		//System Info Page
		if (isset($_GET['page']) && $_GET['page'] == 'fts-system-info-submenu-page') {
		  add_action('admin_enqueue_scripts', array( $this,'feed_them_system_info_css'));
		}
		
		//Check Premium Version.
		$this->fts_get_check_plugin_version('feed-them-premium.php', '1.3.0');
	 }//end if admin
		
		//Settings option. Add Custom CSS to the header of FTS pages only
		$fts_include_custom_css_checked_css =  get_option( 'fts-color-options-settings-custom-css' );
		if ($fts_include_custom_css_checked_css == '1') { 
			add_action('wp_enqueue_scripts', array( $this,'fts_color_options_head_css'));	
		}
		 
		//Settings option. Custom Powered by Feed Them Social Option
		$fts_powered_text_options_settings =  get_option( 'fts-powered-text-options-settings' );
		if ($fts_powered_text_options_settings != '1') { 
		 	add_action('wp_enqueue_scripts', array( $this,'fts_powered_by_js'));
		}
	}//end if init
	
	function fts_get_check_plugin_version($_plugin_file, $version_needed) {
   		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	  	$plugins = get_plugins();
	  
		foreach($plugins as $plugin_file => $plugin_info) {
			
			//Check if plugin is active if not don't bug em
			if (is_plugin_active($plugin_file)) {
				  $plugin_file_name = explode('/', $plugin_file);
				   
				  if ($plugin_file_name[1] == $_plugin_file && $plugin_info['Version'] < $version_needed){
					  $download_location = 'If you have not recieved an update notification for this plugin you may re-download the plugin/extension from your <a href="http://slickremix.com/my-account" target="_blank">SlickRemix "My Account" page.</a>';
					 
					  $error_msg = '<div class="error"><p>' . __( 'Warning: <strong>'.$plugin_info['Name'].'</strong> needs to be <strong>UPDATED</strong> to <strong>version '.$version_needed.'</strong> to function properly. '.$download_location, 'fts-bar' ) . '</p></div>';
					
					 add_action( 'admin_notices', function() use ($error_msg) {
						  echo $error_msg;
					 });
					 
					 
					 deactivate_plugins($plugin_file);
					 
					 return $error_msg;
				  }
			}
		}
	}
	
	function Feed_Them_Main_Menu() {
  	 	add_menu_page('Feed Them Social', 'Feed Them', 'edit_plugins', 'feed-them-settings-page', 'feed_them_settings_page', 'div', 99);
	}
	
	function Feed_Them_Submenu_Pages() {   
		add_submenu_page( 
			'feed-them-settings-page',
			'System Info' ,
			'System Info',
			'manage_options',
			'fts-system-info-submenu-page',
			'feed_them_system_info_page'
		);
	}
	
	function feed_them_admin_css() {
		wp_register_style( 'feed_them_admin', plugins_url( 'admin/css/admin.css', dirname(__FILE__) ) );  
		wp_enqueue_style('feed_them_admin');
	}
	
	function feed_them_system_info_css() {
		wp_register_style( 'fts-settings-admin-css', plugins_url( 'admin/css/admin-settings.css',  dirname(__FILE__) ) );
		wp_enqueue_style('fts-settings-admin-css'); 
	}
	
	function feed_them_settings() {
		wp_register_style( 'feed_them_settings_css', plugins_url( 'admin/css/settings-page.css',  dirname(__FILE__) ) );
		wp_enqueue_style('feed_them_settings_css'); 
		wp_enqueue_script( 'feed_them_settings_js', plugins_url( 'admin/js/admin.js',  dirname(__FILE__) ) );  
	}
	
	function need_fts_premium_fields($fields) {
		foreach($fields as $key => $label)	{
			  $output .= '<div class="feed-them-social-admin-input-wrap">';
				  $output .= '<div class="feed-them-social-admin-input-label">'.$label.'</div>';
				  $output .= '<div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit. Default is 5.</div>';
				$output .= '<div class="clear"></div>';
			  $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		}//END Foreach
		
		return $output;
  }
	
	/*
	 * Generic Register Settings function
	 * 
	*/
	function register_settings($settings_name ,$settings)	{
		foreach($settings as $key => $setting)	{
			register_setting( $settings_name, $setting);
		}
	}
	
	/*
	 * Register Free Version Settings.
	*/
	function fts_settings_page_register_settings() { 
		$settings = array(
					'fts-date-and-time-format',
					'fts-color-options-settings-custom-css',
					'fts-color-options-main-wrapper-css-input',
					'fts-powered-text-options-settings',
					);
		$this->register_settings('feed-them-social-settings', $settings);
	}
	
	/*
	 * Clear Cache Folder,
	*/
	function feed_them_clear_cache() {
	   $plugins = array (
		 1 => 'facebook',
		 2 => 'instagram',
		 3 => 'twitter',
	   );
	  
	   foreach($plugins as $key => $value){
		$files = glob(WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/'.$value.'/cache/*'); // get all file names
		  if($files){
			foreach($files as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}//end foreach $files
		  }// end if($files)
	   }//end foreach $plugins
	   
	  return 'Cache for all FTS Feeds cleared!';
	}
	
	function  fts_color_options_head_css() { ?>
	<style type="text/css"><?php echo get_option('fts-color-options-main-wrapper-css-input');?></style>
	<?php 
	}
	
	function  fts_powered_by_js() {
		
		  wp_register_style( 'fts_powered_by_css', plugins_url( 'css/powered-by.css',  dirname(__FILE__) ) );
		  wp_enqueue_style('fts_powered_by_css'); 
		  
		  wp_enqueue_script( 'fts_powered_by_js', plugins_url( 'js/powered-by.js',  dirname(__FILE__) ),
		  array( 'jquery' )
		 ); 	
	}
	
	/*
	 * Feed Them Social FaceBook Forms
	*/
	function  fts_facebook_event_form($save_options = false) {
		
		if($save_options){
			$fb_event_id_option = get_option('fb_event_id');
			$fb_event_post_count_option = get_option('fb_event_post_count');
			$fb_event_title_option = get_option('fb_event_title_option');
			$fb_event_description_option = get_option('fb_event_description_option');
			$fb_event_word_count_option = get_option('fb_event_word_count_option');
		}
		
        $output .= '<div class="fts-facebook_event-shortcode-form">';
		if($save_options == false){
      	  $output .= '<form method="post" class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form" action="options.php">';
		  $output .= '<h2>Facebook Event Shortcode Generator</h2>';
		}
        
        $output .= '<div class="instructional-text">Copy your <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-event-id/" target="_blank">Facebook Page Event ID and paste it in the first input below.</a></div>';
          $output .= '<div class="feed-them-social-admin-input-wrap fb_event_id">';
            $output .= '<div class="feed-them-social-admin-input-label">Facebook Event ID (required)</div>';
            $output .= '<input type="text" name="fb_event_id" id="fb_event_id" class="feed-them-social-admin-input" value="'.$fb_event_id_option.'" />';
        $output .= '<div class="clear"></div>';
          $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
         
        
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			
        	include($this->premium.'admin/facebook-event-settings-fields.php');
        }
        else 	{
		  $fields = array(
			'# of Posts',
			'Show the Event Title?',
			'Show the Event Description?',
			'Amount of words per post?',
		  );
		 $output .=  $this->need_fts_premium_fields($fields);
        }
        
	   if($save_options == false){
			$output .=  $this->generate_shortcode('updateTextArea_fb_event();','Facebook Event Feed Shortcode','facebook-event-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="Save Changes" />';
	   }
          
        $output .= '</div><!--/fts-facebook_group-shortcode-form-->';
		
		return $output;
	}
	
	function  fts_facebook_group_form($save_options = false) {
		
		if($save_options){
			$fb_group_id_option = get_option('fb_group_id');
			$fb_group_post_count_option = get_option('fb_group_post_count');
			$fb_group_title_option = get_option('fb_group_title_option');
			$fb_group_description_option = get_option('fb_group_description_option');
			$fb_group_word_count_option = get_option('fb_group_word_count_option');
		}
		
        $output .= '<div class="fts-facebook_group-shortcode-form">';
		if($save_options == false){
        	$output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-group-shortcode-form" id="fts-fb-group-form">';
            $output .= '<h2>Facebook Group Shortcode Generator</h2>';
		}
            $output .= '<div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">Facebook ID</a> and paste it in the first input below.</div>';
              $output .= '<div class="feed-them-social-admin-input-wrap fb_group_id">';
                $output .= '<div class="feed-them-social-admin-input-label">Facebook Group ID (required)</div>';
                $output .= '<input type="text" name="fb_group_id" id="fb_group_id" class="feed-them-social-admin-input" value="'.$fb_group_id_option .'" />';
            $output .= '<div class="clear"></div>';
              $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            
            
        $output .= '<!-- Using this for a future update <div class="feed-them-social-admin-input-wrap">
          <div class="feed-them-social-admin-input-label">Customized Group Name</div>
          <select id="fb_group_custom_name" class="feed-them-social-admin-input">
            <option selected="selected" value="yes">My group name is custom</option>
            <option value="no">My group name is number based</option>
          </select>
          <div class="clear"></div>
        </div>
        /feed-them-social-admin-input-wrap-->';
        
         
        
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
           include($this->premium.'admin/facebook-group-settings-fields.php');
        }
        else 	{
            //Create Need Premium Fields
            $fields = array(
              '# of Posts',
              'Show the Group Title?',
              'Show the Group Description?',
              'Amount of words per post?',
            );
           $output .= $this->need_fts_premium_fields($fields);
        }
		
	   if($save_options == false){
			$output .= $this->generate_shortcode('updateTextArea_fb_group();','Facebook Group Feed Shortcode','facebook-group-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="Save Changes" />';
	   }
		
        $output .= '</div><!--/fts-facebook_group-shortcode-form-->';
		
		return $output;
	}
	
	function  fts_facebook_page_form($save_options = false) {
		
		if($save_options){
			$fb_page_id_option = get_option('fb_page_id');
			$fb_page_posts_displayed_option = get_option('fb_page_posts_displayed');
			$fb_page_post_count_option = get_option('fb_page_post_count');
			$fb_page_title_option = get_option('fb_page_title_option');
			$fb_page_description_option = get_option('fb_page_description_option');
			$fb_page_word_count_option = get_option('fb_page_word_count_option');
		}
		
        $output .= '<div class="fts-facebook_page-shortcode-form">';
		
		if($save_options == false){
		  $output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-page-shortcode-form" id="fts-fb-page-form">';
		  $output .= '<h2>Facebook Page Shortcode Generator</h2>';
		}
        $output .= '<div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/09/09/how-to-get-your-facebook-page-vanity-url/" target="_blank">Facebook Page ID</a> and paste it in the first input below.</div>';
          $output .= '<div class="feed-them-social-admin-input-wrap fb_page_id">';
            $output .= '<div class="feed-them-social-admin-input-label">Facebook Page ID (required)</div>';
            $output .= '<input type="text" name="fb_page_id" id="fb_page_id" class="feed-them-social-admin-input" value="'.$fb_page_id_option.'" />';
        $output .= '<div class="clear"></div>';
          $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
         
        $output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">Post Type Visible</div>';
        $output .= '<select name="fb_page_posts_displayed" id="fb_page_posts_displayed" class="feed-them-social-admin-input">';
        $output .= '<option '.selected($fb_page_posts_displayed_option, 'page_only', false ) .' value="page_only">Display Posts made by Page only</option>';
        $output .= '<option '.selected($fb_page_posts_displayed_option, '', false ) .' value="">Display Posts made by Everyone</option>';
        $output .= '</select>';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
        	include($this->premium.'admin/facebook-page-settings-fields.php');
        }
        else 	{
        //Create Need Premium Fields
        $fields = array(
          '# of Posts',
          'Show the Page Title?',
          'Show the Page Description?',
          'Amount of words per post?',
        );
        $output .= $this->need_fts_premium_fields($fields);
        }
		
	   if($save_options == false){
			 $output .= $this->generate_shortcode('updateTextArea_fb_page();','Facebook Page Feed Shortcode','facebook-page-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="Save Changes" />';
	   } 
        
      
        $output .= '</div><!--/fts-facebook_page-shortcode-form-->'; 
		
		return $output;
	}
	
	
   /*
	* Feed Them Social Twitter Form
	*/
	function  fts_twitter_form($save_options = false) {
		
		if($save_options){
			$twitter_name_option = get_option('twitter_name');
			$tweets_count_option = get_option('tweets_count');
		}
		
        $output .= '<div class="fts-twitter-shortcode-form">'; 
		if($save_options == false){
		  $output .= '<form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form" id="fts-twitter-form">';
		  $output .= '<h2>Twitter Shortcode Generator</h2>';
		}
        $output .= '<div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-twitter-name/" target="_blank">Twitter Name</a> and paste it in the first input below.</div>';
        
        $output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
          $output .= '<div class="feed-them-social-admin-input-label">Twitter Name (required)</div>';
          $output .= '<input type="text" name="twitter_name" id="twitter_name" class="feed-them-social-admin-input" value="'.$twitter_name_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        
        
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
        	include($this->premium.'admin/twitter-settings-fields.php');
        }
        else 	{
        //Create Need Premium Fields
        $fields = array(
        '# of Tweets',
        );
        $output .= $this->need_fts_premium_fields($fields);
        }
        
	   if($save_options == false){
			$output .= $this->generate_shortcode('updateTextArea_twitter();','Twitter Feed Shortcode','twitter-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="Save Changes" />';
	   } 
        
        $output .= '</div><!--/fts-twitter-shortcode-form-->';
		
		return $output;
	}
	
   /*
	* Feed Them Social Instagram Form
	*/
	function  fts_instagram_form($save_options = false) {
		if($save_options){
			$instagram_name_option = get_option('convert_instagram_username');
			$instagram_id_option = get_option('instagram_id');
			$pics_count_option = get_option('pics_count');
		}
        $output .= '<div class="fts-instagram-shortcode-form">';
        if($save_options == false){
		  $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form" id="fts-instagram-form">';
		  $output .= '<h2>Convert Instagram Name to ID</h2>';
		}
        $output .= '<div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">Instagram Name</a> and paste it in the first input below</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap convert_instagram_username">';
        $output .= '<div class="feed-them-social-admin-input-label">Instagram Name (required)</div>';
        $output .= '<input type="text" id="convert_instagram_username" name="convert_instagram_username" class="feed-them-social-admin-input" value="'.$instagram_name_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        
        $output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="Convert Instagram Username" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />';
        
		if($save_options == false){
       	  $output .= '</form>';
		}
        
		if($save_options == false){
		  $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">';
		  $output .= '<h2>Instagram Shortcode Generator</h2>';
		}
        $output .= '<div class="instructional-text">If you added your ID above and clicked convert, a number should appear in the input below, now continue.</div>';
        
        $output .= '<div class="feed-them-social-admin-input-wrap instagram_name">';
        $output .= '<div class="feed-them-social-admin-input-label">Instagram ID # (required)</div>';
        $output .= '<input type="text" name="instagram_id" id="instagram_id" class="feed-them-social-admin-input" value="'.$instagram_id_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        
		
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
       		include($this->premium.'admin/instagram-settings-fields.php');
        }
        else 	{
        
        //Create Need Premium Fields
        $fields = array(
        '# of Pics',
        );
        $output .= $this->need_fts_premium_fields($fields);
        } 
		
	   if($save_options == false){
			$output .= $this->generate_shortcode('updateTextArea_instagram();','Instagram Feed Shortcode','instagram-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn instagram-submit" value="Save Changes" />';
	   } 
        $output .= '</div><!--/fts-instagram-shortcode-form-->'; 
		return $output;
	}		


   /*
	* Feed Them Social Youtube Form
	*/
	function  fts_youtube_form($save_options = false) {
		if($save_options){
			$youtube_name_option = get_option('youtube_name');
			$youtube_vid_count_option = get_option('youtube_vid_count');
			$youtube_columns_option = get_option('youtube_columns');
			$youtube_first_video_option = get_option('youtube_first_video');
		}
        $output .= '<div class="fts-youtube-shortcode-form">';
		if($save_options == false){
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form" id="fts-youtube-form">';
			$output .= '<h2>YouTube Shortcode Generator</h2>';
		}
        $output .= '<div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-youtube-name/" target="_blank">YouTube Name</a> and paste it in the first input below.</div>';
          
      
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
        	include($this->premium.'admin/youtube-settings-fields.php');
        }
        else 	{
        //Create Need Premium Fields
        $fields = array(
          'YouTube Name',
          '# of videos',
          '# of videos in each row',
          'Display First video full size',
        );
       $output .= $this->need_fts_premium_fields($fields);
      
       $output .= '<a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;">Click to see Premium Version</a>';
	   $output .= '</form>';
		
        }
        
       $output .= '</div><!--/fts-youtube-shortcode-form-->';
	   
	   return $output;
	}
	
	/*
	* Feed Them Social Pinterest Form
	*/
	function  fts_pinterest_form($save_options = false) {
		if($save_options){
			$pinterest_name_option = get_option('pinterest_name');
			$boards_count_option = get_option('boards_count');
		}
		
		$output .= '<div class="fts-pinterest-shortcode-form">';
		if($save_options == false){
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form" id="fts-pinterest-form">';
			$output .= '<h2>Pinterest Shortcode Generator</h2>';
		}
		$output .= '<div class="instructional-text">You must copy your <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-pinterest-name/" target="_blank">Pinterest Name</a> and paste it in the first input below.</div>';
		
		if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/pinterest-settings-fields.php');
		}
		else 	{
		
		//Create Need Premium Fields
		$fields = array(
		  'Pinterest Name',
		  '# of boards',
		);
		$output .= $this->need_fts_premium_fields($fields);
		
		$output .= '<a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;" target="_blank" >Click to see Premium Version</a>';
		$output .= '</form>';
		
		}
		
		$output .= '</div><!--/fts-pinterest-shortcode-form-->';
		
		return $output;
	}		
	
	
	//Generate Shorecode Button and I<?phpnput for FTS settings Page
	function  generate_shortcode($onclick, $label, $input_class) {
	
      $output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="Generate Shortcode" onclick="'.$onclick.'" tabindex="4" style="margin-right:1em;" />';
      $output .= '<div class="feed-them-social-admin-input-wrap final-shortcode-textarea">';
      
     	 $output .= '<h4>Copy the ShortCode below and paste it on a page or post that you want to display your feed.</h4>';
      
        $output .= '<div class="feed-them-social-admin-input-label">'.$label.'></div>';
        
        $output .= '<input class="copyme '.$input_class.' feed-them-social-admin-input" value="" />';
        
      $output .= '<div class="clear"></div>';
      $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
	  
	  return $output;
	}



}//END Class
?>