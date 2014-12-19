<?php
/************************************************
 	Function file for Feed Them Social plugin
************************************************/
 
add_filter('widget_text', 'do_shortcode');
  
if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	
// Added Scripts to allow loadmore but only if premium is active. Additional code in premium too.
function my_fts_fb_script_enqueuer() {
	
	$ftsFBfileJS = dirname(dirname(__FILE__)) . '/feed-them-social.php';
	$FTS_plugin_url = plugin_dir_url($ftsFBfileJS);


   wp_enqueue_script( 'my-fts-ajax-handle', $FTS_plugin_url . '/feeds/facebook/js/ajax.js', array( 'jquery' ) );
   wp_localize_script( 'my-fts-ajax-handle', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );     
	
}
add_action( 'init', 'my_fts_fb_script_enqueuer' );


// enqueue and localise scripts
// THE AJAX ADD ACTIONS
// this function is being called from the fb feed... it calls the ajax in this case. 
function my_fts_fb_load_more() {
  
   $object = do_shortcode($_REQUEST['rebuilt_shortcode']);
  
    echo $object;
    die();
}
add_action( 'wp_ajax_my_fts_fb_load_more', 'my_fts_fb_load_more' );
add_action( 'wp_ajax_nopriv_my_fts_fb_load_more', 'my_fts_fb_load_more' );

} // end is_plugin_active premium

class feed_them_social_functions {
	
	public $output = "";
	
	function __construct() {
	  $root_file = plugin_dir_path(dirname( __FILE__));
	  $this->premium = str_replace('feed-them-social/','feed-them-premium/', $root_file);

	  //$load_fts->fts_get_check_plugin_version('feed-them-premium.php', '1.3.0');
	  	register_deactivation_hook( __FILE__, array( $this, 'fts_get_check_plugin_version' ));
	}
	
	/*
     * For Loading in the Admin.
     */ 
     function init(){
		 
	  if ( is_admin() ){
		  //Register Settings
		  add_action('admin_init', array( $this,'fts_settings_page_register_settings' ));
		  add_action('admin_init', array( $this,'fts_facebook_style_options_page' ));
		  add_action('admin_init', array( $this,'fts_twitter_style_options_page' ));
		  //Adds setting page to FTS menu
		  add_action('admin_menu', array( $this,'Feed_Them_Main_Menu'));
		  add_action('admin_menu', array( $this,'Feed_Them_Submenu_Pages'));
		
		  // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
		  add_action('admin_enqueue_scripts', array( $this,'feed_them_admin_css'));
		
		//Main Settings Page
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page' or isset($_GET['page']) && $_GET['page'] == 'fts-facebook-feed-styles-submenu-page'  or isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page' ) {
			add_action('admin_enqueue_scripts',  array( $this,'feed_them_settings'));
			
		}
		
		//System Info Page
		if (isset($_GET['page']) && $_GET['page'] == 'fts-system-info-submenu-page') {
		  add_action('admin_enqueue_scripts', array( $this,'feed_them_system_info_css'));
		}
		
		
	 }//end if admin
		
		//Settings option. Add Custom CSS to the header of FTS pages only
		$fts_include_custom_css_checked_css =  get_option( 'fts-color-options-settings-custom-css' );
		if ($fts_include_custom_css_checked_css == '1') { 
			add_action('wp_enqueue_scripts', array( $this,'fts_color_options_head_css'));	
		}
		
		//Facebook Settings option. Add Custom CSS to the header of FTS pages only
		$fts_include_fb_custom_css_checked_css =  '1'; //get_option( 'fts-color-options-settings-custom-css' );
		if ($fts_include_fb_custom_css_checked_css == '1') { 
			add_action('wp_enqueue_scripts', array( $this,'fts_fb_color_options_head_css'));	
		}
		 
		//Settings option. Custom Powered by Feed Them Social Option
		$fts_powered_text_options_settings =  get_option( 'fts-powered-text-options-settings' );
		if ($fts_powered_text_options_settings != '1') { 
		 	add_action('wp_enqueue_scripts', array( $this,'fts_powered_by_js'));
		}
	}//end if init
	
	function fts_get_check_plugin_version($plugin_file = 'feed-them-premium.php', $version_needed = '1.3.0', $return_version = false) {
		if (!empty($_GET['activate'])){
		  if (is_admin() && $_GET['activate'] == 'true') {
			  require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
			  $plugins = get_plugins();
			
			  foreach($plugins as $plugin_file => $plugin_info) {
				  
				  //Check if plugin is active if not don't bug em
				  if (is_plugin_active($plugin_file)) {
						$plugin_file_name = explode('/', $plugin_file);
						
							if ($plugin_file_name[1] == $_plugin_file && $plugin_info['Version'] < $version_needed){
								$download_location = "__( 'If you have not received an update notification for this plugin you may re-download the plugin/extension from your <a href='http://slickremix.com/my-account' target='_blank'>SlickRemix 'My Account' page.</a>, 'fts-bar' ) . ";
							   
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
		}
	}
	
	function Feed_Them_Main_Menu() {
  	 	add_menu_page('Feed Them Social', 'Feed Them', 'manage_options', 'feed-them-settings-page', 'feed_them_settings_page', '');
		add_submenu_page('feed-them-settings-page', __('Settings', 'feed-them-social'),  __('Settings', 'feed-them-social'), 'manage_options', 'feed-them-settings-page' );
		
	}
	// add the word setting in place of the default menu page name 'Feed Them'
	function Feed_Them_Submenu_Pages() {   
		
		//System Info
		add_submenu_page( 
			'feed-them-settings-page',
			__('Facebook Options', 'feed-them-social'),
			__('Facebook Options', 'feed-them-social'),
			'manage_options',
			'fts-facebook-feed-styles-submenu-page',
			'feed_them_facebook_options_page'
		);
		
		//System Info
		add_submenu_page( 
			'feed-them-settings-page',
			__('Twitter Options', 'feed-them-social'),
			__('Twitter Options', 'feed-them-social'),
			'manage_options',
			'fts-twitter-feed-styles-submenu-page',
			'feed_them_twitter_options_page'
		);
		
		//System Info
		add_submenu_page( 
			'feed-them-settings-page',
			__('System Info', 'feed-them-social'),
			__('System Info', 'feed-them-social'),
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
        
      	if (isset($_GET['page']) && $_GET['page'] == 'fts-facebook-feed-styles-submenu-page' or isset($_GET['page']) && $_GET['page'] == 'fts-twitter-feed-styles-submenu-page') {
			wp_enqueue_script( 'feed_them_style_options_color_js', plugins_url( 'admin/js/jscolor/jscolor.js',  dirname(__FILE__) ) );
			
        }
          
	}
	
	function need_fts_premium_fields($fields) {
		
		 $output = isset($output) ? $output : "";
		
		foreach($fields as $key => $label)	{
			  $output .= '<div class="feed-them-social-admin-input-wrap">';
				  $output .= '<div class="feed-them-social-admin-input-label">'.$label.'</div>';
				  $output .= '<div class="feed-them-social-admin-input-default">Must have <a target="_blank" href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/">premium version</a> to edit.</div>';
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
	 * Register Facebook Style Options.
	*/
	function fts_facebook_style_options_page() { 
		$fb_style_options = array(
					'fb_text_color',
					'fb_link_color',
					'fb_link_color_hover',
					'fb_feed_width',
					'fb_feed_margin',
					'fb_feed_padding',
					'fb_feed_background_color',
					'fb_grid_posts_background_color',
					'fb_border_bottom_color',
					'fts_facebook_custom_api_token',
					);
		$this->register_settings('fts-facebook-feed-style-options', $fb_style_options);
	}
	
	/*
	 * Register Twitter Style Options.
	*/
	function fts_twitter_style_options_page() { 
		$twitter_style_options = array(
					'twitter_full_width',
					'twitter_text_color',
					'twitter_link_color',
					'twitter_link_color_hover',
					'twitter_feed_width',
					'twitter_feed_margin',
					'twitter_feed_padding',
					'twitter_feed_background_color',
					'twitter_border_bottom_color',
					'fts_twitter_custom_consumer_key',
					'fts_twitter_custom_consumer_secret',
					'fts_twitter_custom_access_token',
					'fts_twitter_custom_access_token_secret',
					);
		$this->register_settings('fts-twitter-feed-style-options', $twitter_style_options);
	}
	
	/*
	 * Register Free Version Settings.
	*/
	function fts_settings_page_register_settings() { 
		$settings = array(
					'fts-date-and-time-format',
					'fts-timezone',
					'fts-color-options-settings-custom-css',
					'fts-color-options-main-wrapper-css-input',
					'fts-powered-text-options-settings',
					 
					'fts-slicker-instagram-icon-center',
					'fts-slicker-instagram-container-image-size',
					'fts-slicker-instagram-container-hide-date-likes-comments',
					'fts-slicker-instagram-container-position',
					'fts-slicker-instagram-container-animation',
					'fts-slicker-instagram-container-margin',
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
	
	// color options for facebook
	function  fts_color_options_head_css() { ?>
	<style type="text/css"><?php echo get_option('fts-color-options-main-wrapper-css-input');?></style>
	<?php 
	}
	
	
	

	// color options for facebook
	function  fts_fb_color_options_head_css() { 
	
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
		
		$fb_text_color = get_option('fb_text_color');
		$fb_link_color = get_option('fb_link_color');
		$fb_link_color_hover = get_option('fb_link_color_hover');
		$fb_feed_width = get_option('fb_feed_width');
		$fb_feed_margin = get_option('fb_feed_margin');
		$fb_feed_padding = get_option('fb_feed_padding');
		$fb_feed_background_color = get_option('fb_feed_background_color');
		$fb_grid_posts_background_color = get_option('fb_grid_posts_background_color');
		$fb_border_bottom_color = get_option('fb_border_bottom_color');
		
		$twitter_hide_profile_photo = get_option('twitter_hide_profile_photo');			
		$twitter_text_color = get_option('twitter_text_color');
		$twitter_link_color = get_option('twitter_link_color');
		$twitter_link_color_hover = get_option('twitter_link_color_hover');
		$twitter_feed_width = get_option('twitter_feed_width');
		$twitter_feed_margin = get_option('twitter_feed_margin');
		$twitter_feed_padding = get_option('twitter_feed_padding');
		$twitter_feed_background_color = get_option('twitter_feed_background_color');
		$twitter_border_bottom_color = get_option('twitter_border_bottom_color');
		?>
        
<style type="text/css">
<?php if (!empty($fb_text_color)) { ?>
.fts-simple-fb-wrapper .fts-jal-single-fb-post, 
.fts-simple-fb-wrapper .fts-jal-fb-description-wrap, 
.fts-simple-fb-wrapper .fts-jal-fb-post-time,
.fts-slicker-facebook-posts .fts-jal-single-fb-post, 
.fts-slicker-facebook-posts .fts-jal-fb-description-wrap, 
.fts-slicker-facebook-posts .fts-jal-fb-post-time { color:<?php echo $fb_text_color ?>!important; }
<?php } 
	 if (!empty($fb_link_color)) { ?>	
.fts-simple-fb-wrapper .fts-jal-single-fb-post a, 
.fts-fb-load-more-wrapper .fts-fb-load-more, 
.fts-slicker-facebook-posts .fts-jal-single-fb-post a, 
.fts-fb-load-more-wrapper .fts-fb-load-more { color:<?php echo $fb_link_color ?>!important; }
<?php } 
	 if (!empty($fb_link_color_hover)) { ?>
.fts-simple-fb-wrapper .fts-jal-single-fb-post a:hover, 
.fts-simple-fb-wrapper .fts-fb-load-more:hover, 
.fts-slicker-facebook-posts .fts-jal-single-fb-post a:hover, 
.fts-slicker-facebook-posts .fts-fb-load-more:hover { color:<?php echo $fb_link_color_hover ?>!important; }
<?php } 
	 if (!empty($fb_feed_width)) { ?>
.fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper { max-width:<?php echo $fb_feed_width ?> !important; }
<?php } 
	 if (!empty($fb_feed_margin)) { ?>	
.fts-simple-fb-wrapper, .fts-fb-header-wrapper, .fts-fb-load-more-wrapper { margin:<?php echo $fb_feed_margin ?> !important; }
<?php } 
	 if (!empty($fb_feed_padding)) { ?>
.fts-simple-fb-wrapper { padding:<?php echo $fb_feed_padding ?>!important; }
<?php } 
	 if (!empty($fb_feed_background_color)) { ?>
.fts-simple-fb-wrapper, .fts-fb-load-more-wrapper .fts-fb-load-more { background:<?php echo $fb_feed_background_color ?>!important; }
<?php } 
	 if (!empty($fb_grid_posts_background_color)) { ?>
.fts-slicker-facebook-posts .fts-jal-single-fb-post { background:<?php echo $fb_grid_posts_background_color ?>!important; }
<?php } 
	 if (!empty($fb_border_bottom_color)) { ?>
.fts-slicker-facebook-posts .fts-jal-single-fb-post { border-bottom:1px solid <?php echo $fb_border_bottom_color ?>!important; }
<?php } ?>
<?php if (!empty($twitter_text_color)) { ?>
.tweeter-info .fts-twitter-text, .fts-twitter-reply-wrap:before { color:<?php echo $twitter_text_color ?>!important; }
<?php } 
	 if (!empty($twitter_link_color)) { ?>	
.tweeter-info .fts-twitter-text a, .tweeter-info .fts-twitter-text .time a, .fts-twitter-reply-wrap a, .tweeter-info a   { color:<?php echo $twitter_link_color ?>!important; }
<?php } 
	 if (!empty($twitter_link_color_hover)) { ?>
.tweeter-info a:hover, .tweeter-info:hover .fts-twitter-reply { color:<?php echo $twitter_link_color_hover ?>!important; }
<?php } 
	 if (!empty($twitter_feed_width)) { ?>
.fts-twitter-div { max-width:<?php echo $twitter_feed_width ?> !important; }
<?php } 
	 if (!empty($twitter_feed_margin)) { ?>	
.fts-twitter-div { margin:<?php echo $twitter_feed_margin ?> !important; }
<?php } 
	 if (!empty($twitter_feed_padding)) { ?>
.fts-twitter-div { padding:<?php echo $twitter_feed_padding ?>!important; }
<?php } 
	 if (!empty($twitter_feed_background_color)) { ?>
.fts-twitter-div { background:<?php echo $twitter_feed_background_color ?>!important; }
<?php } 
	if (!empty($twitter_border_bottom_color)) { ?>
.tweeter-info { border-bottom:1px solid <?php echo $twitter_border_bottom_color ?>!important; }
<?php } ?>
</style>


	<?php } //close if premium active
	}		
	function  fts_powered_by_js() {
		
		  wp_register_style( 'fts_powered_by_css', plugins_url( 'css/powered-by.css',  dirname(__FILE__) ) );
		  wp_enqueue_style('fts_powered_by_css'); 
		  
		  wp_enqueue_script( 'fts_powered_by_js', plugins_url( 'js/powered-by.js',  dirname(__FILE__) ), array( 'jquery' )
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
			
			$fts_bar_fb_prefix = 'fb_event_';
			$fb_load_more_option = get_option('fb_event_fb_load_more_option');
			$fb_load_more_style = get_option('fb_event_fb_load_more_style');
			$facebook_popup = get_option('fb_event_facebook_popup');
		}
		
		$fb_event_id_option = isset($fb_event_id_option) ? $fb_event_id_option : "";
		
        $output = '<div class="fts-facebook_event-shortcode-form">';
		if($save_options == false){
      	  $output .= '<form method="post" class="feed-them-social-admin-form shortcode-generator-form fb-event-shortcode-form" id="fts-fb-event-form" action="options.php">';
		  $output .= '<h2>'.__('Facebook Event Shortcode Generator', 'feed-them-social').'</h2>';
		}
        
        $output .= '<div class="instructional-text inst-text-facebook-page">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-event-id/" target="_blank">'.__('Facebook Page Event ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap fb_event_id">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Event ID (required)', 'feed-them-social').'</div>';
        $output .= '<input type="text" name="fb_event_id" id="fb_event_id" class="feed-them-social-admin-input" value="'.$fb_event_id_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
         
        // Facebook Height Option
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
        $output .= '<input type="text" name="facebook_event_height" id="facebook_event_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'e" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
		
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			
        	include($this->premium.'admin/facebook-event-settings-fields.php');
			
			if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page'){
			  //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
			  include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
        }
        else 	{
		  $fields = array(
			__('# of Posts (default 5)', 'feed-them-social'),
			__('Show the Event Title', 'feed-them-social'),
			__('Show the Event Description', 'feed-them-social'),
			__('Amount of words per post', 'feed-them-social'),
			__('Load More Posts', 'feed-them-social'),
			__('Display Photos in Popup', 'feed-them-social'),
			__('Display Posts in Grid', 'feed-them-social'),
		  );
		 $output .=  $this->need_fts_premium_fields($fields);
        }
        
	   if($save_options == false){
			$output .=  $this->generate_shortcode('updateTextArea_fb_event();','Facebook Event Feed Shortcode','facebook-event-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
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
			
			$fts_bar_fb_prefix = 'fb_group_';
			$fb_load_more_option = get_option('fb_group_fb_load_more_option');
			$fb_load_more_style = get_option('fb_group_fb_load_more_style');
			$facebook_popup = get_option('fb_group_facebook_popup');
		}
		$fb_group_id_option = isset($fb_group_id_option) ? $fb_group_id_option : "";
		
        $output = '<div class="fts-facebook_group-shortcode-form">';
		if($save_options == false){
        	$output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-group-shortcode-form" id="fts-fb-group-form">';
            $output .= '<h2>'.__('Facebook Group Shortcode Generator', 'feed-them-social').'</h2>';
		}
            $output .= '<div class="instructional-text">'.__('You must copy your ', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">'.__('Facebook Group ID ', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
              $output .= '<div class="feed-them-social-admin-input-wrap fb_group_id">';
                $output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Group ID (required)', 'feed-them-social').'</div>';
                $output .= '<input type="text" name="fb_group_id" id="fb_group_id" class="feed-them-social-admin-input" value="'.$fb_group_id_option.'" />';
            $output .= '<div class="clear"></div>';
              $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
            
			// Facebook Height Option
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
        $output .= '<input type="text" name="facebook_group_height" id="facebook_group_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
            
      //  $output .= '<!-- Using this for a future update <div class="feed-them-social-admin-input-wrap">
       //   <div class="feed-them-social-admin-input-label">'.__('Customized Group Name', 'feed-them-social').'</div>
        //  <select id="fb_group_custom_name" class="feed-them-social-admin-input">
         //   <option selected="selected" value="yes">'.__('My group name is custom', 'feed-them-social').'</option>
          //  <option value="no">'.__('My group name is number based', 'feed-them-social').'</option>
          // </select>
          // <div class="clear"></div>
        // </div>
        // /feed-them-social-admin-input-wrap-->';
        
         
        
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
           include($this->premium.'admin/facebook-group-settings-fields.php');
		   
		   if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page'){
			  //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
			  include($this->premium.'admin/facebook-loadmore-settings-fields.php');
		   }
        }
        else 	{
            //Create Need Premium Fields
            $fields = array(
			__('# of Posts (default 5)', 'feed-them-social'),
			__('Show the Group Title', 'feed-them-social'),
			__('Show the Group Description', 'feed-them-social'),
			__('Amount of words per post', 'feed-them-social'),
			__('Load More Posts', 'feed-them-social'),
			__('Display Photos in Popup', 'feed-them-social'),
			__('Display Posts in Grid', 'feed-them-social'),
            );
           $output .= $this->need_fts_premium_fields($fields);
        }
		
	   if($save_options == false){
			$output .= $this->generate_shortcode('updateTextArea_fb_group();','Facebook Group Feed Shortcode','facebook-group-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
	   }
		
        $output .= '</div><!--/fts-facebook_group-shortcode-form-->';
		
		return $output;
	}
	
	
	
	
	
	
	
	
	
	
	
	

	
////////////////////////////////////////////////////////////////	
////////////////// MASTER FACEBOOK OPTIONS ////////////////////	
//////////////////////////////////////////////////////////////
function  fts_facebook_page_form($save_options = false) {
		
		if($save_options){
			$fb_page_id_option = get_option('fb_page_id');
			$fb_page_posts_displayed_option = get_option('fb_page_posts_displayed');
			$fb_page_post_count_option = get_option('fb_page_post_count');
			$fb_page_title_option = get_option('fb_page_title_option');
			$fb_page_description_option = get_option('fb_page_description_option');
			$fb_page_word_count_option = get_option('fb_page_word_count_option');
			
			$fts_bar_fb_prefix = 'fb_page_';
			$fb_load_more_option = get_option('fb_page_fb_load_more_option');
			$fb_load_more_style = get_option('fb_page_fb_load_more_style');
			$facebook_popup = get_option('fb_page_facebook_popup');
		}
		
        $output = '<div class="fts-facebook_page-shortcode-form">';
		
	if($save_options == false){
		$output .= '<form class="feed-them-social-admin-form shortcode-generator-form fb-page-shortcode-form" id="fts-fb-page-form">';
		$output .= '<h2>'.__('Facebook Page Shortcode Generator', 'feed-them-social').'</h2>';
	 }	
	 
	 $fb_page_id_option = isset($fb_page_id_option) ? $fb_page_id_option : "";
	 
	 		// ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
	if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page'){
		
	 	// FACEBOOK FEED TYPE
	 	$output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Feed Type', 'feed-them-social').'</div>';
		$output .= '<select name="facebook-messages-selector" id="facebook-messages-selector" class="feed-them-social-admin-input">';
        $output .= '<option value="page">'.__('Facebook Page', 'feed-them-social').'</option>';
        $output .= '<option value="group">'.__('Facebook Group', 'feed-them-social').'</option>';
        $output .= '<option value="event">'.__('Facebook Even', 'feed-them-social').'t</option>';
        $output .= '<option value="album_photos">'.__('Facebook Album Photos', 'feed-them-social').'</option>';
        $output .= '<option value="albums">'.__('Facebook Album Covers', 'feed-them-social').'</option>';
        $output .= '<option value="hashtag">'.__('Facebook Hashtag', 'feed-them-social').'</option>';
        $output .= '</select>';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
	};
	
		// INSTRUCTIONAL TEXT FOR FACEBOOK TYPE SELECTION. PAGE, GROUP, EVENT, ALBUMS, ALBUM COVERS AND HASH TAGS
		$output .= '<div class="instructional-text facebook-message-generator page inst-text-facebook-page" style="display:block;">'.__('Copy your', 'feed-them-social').'<a href="http://www.slickremix.com/2013/09/09/how-to-get-your-facebook-page-vanity-url/" target="_blank">'.__('Facebook Page ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
		
		<div class="instructional-text facebook-message-generator group inst-text-facebook-group">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-group-id/" target="_blank">'.__('Facebook Group ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
		
		<div class="instructional-text facebook-message-generator event inst-text-facebook-event">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/14/how-to-get-your-facebook-event-id/" target="_blank">'.__('Facebook Event ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
		
		<div class="instructional-text facebook-message-generator album_photos inst-text-facebook-album-photos">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">'.__('Facebook Album ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
		
		<div class="instructional-text facebook-message-generator albums inst-text-facebook-albums">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">'.__('Facebook Album Covers ID', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>
		
		<div class="instructional-text facebook-message-generator hashtag inst-text-facebook-hashtag">'.__('Copy your', 'feed-them-social').' <a href="http://www.slickremix.com/docs/how-to-get-your-facebook-photo-gallery-id/" target="_blank">'.__('Facebook Hashtag', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
		
		// FACEBOOK PAGE ID
        $output .= '<div class="feed-them-social-admin-input-wrap fb_page_id ">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook ID (required)', 'feed-them-social').'</div>';
        $output .= '<input type="text" name="fb_page_id" id="fb_page_id" class="feed-them-social-admin-input" value="'.$fb_page_id_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        
		
		// FACEBOOK ALBUM PHOTOS ID
        $output .= '<div class="feed-them-social-admin-input-wrap fb_album_photos_id" style="display:none;">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Album ID (required)', 'feed-them-social').'</div>';
        $output .= '<input type="text" name="fb_album_id" id="fb_album_id" class="feed-them-social-admin-input" value="" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
		$fb_page_posts_displayed_option = isset($fb_page_posts_displayed_option) ? $fb_page_posts_displayed_option : "";
		
		// FACEBOOK PAGE POST TYPE VISIBLE	
        $output .= '<div class="feed-them-social-admin-input-wrap facebook-post-type-visible">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Post Type Visible', 'feed-them-social').'</div>';
        $output .= '<select name="fb_page_posts_displayed" id="fb_page_posts_displayed" class="feed-them-social-admin-input">';
        $output .= '<option '.selected($fb_page_posts_displayed_option, 'page_only', false ) .' value="page_only">'.__('Display Posts made by Page only', 'feed-them-social').'</option>';
        $output .= '<option '.selected($fb_page_posts_displayed_option, 'others_only', false ) .' value="others_only">'.__('Display Posts made by Others', 'feed-them-social').'</option>';
        $output .= '</select>';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
		
	
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
        	include($this->premium.'admin/facebook-page-settings-fields.php');
				
			if (isset($_GET['page']) && $_GET['page'] == 'fts-bar-settings-page'){
			  //PREMIUM LOAD MORE SETTINGS & LOADS in FTS BAR
			  include($this->premium.'admin/facebook-loadmore-settings-fields.php');
			}
        }
        else 	{
        //Create Need Premium Fields
        $fields = array(
			__('# of Posts (default 5)', 'feed-them-social'),
			__('Show the Page Title', 'feed-them-social'),
			__('Show the Page Description', 'feed-them-social'),
			__('Amount of words per post', 'feed-them-social'),
			__('Load More Posts', 'feed-them-social'),
			__('Display Photos in Popup', 'feed-them-social'),
			__('Display Posts in Grid', 'feed-them-social'),
			__('Center Grid', 'feed-them-social'),
			__('Grid Stack Animation', 'feed-them-social'),
        );
        $output .= $this->need_fts_premium_fields($fields);
        }
		
		
		
		
		// ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
	if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page'){
		
		
		
		// FACEBOOK HEIGHT OPTION
		$output .= '<div class="feed-them-social-admin-input-wrap twitter_name fixed_height_option">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Facebook Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
        $output .= '<input type="text" name="facebook_page_height" id="facebook_page_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
		
	
		
		// FACEBOOK super gallery	
	//	$output .= '<div class="feed-them-social-admin-input-wrap facebook_name" style="display:none">';
	//	$output .= '<div class="feed-them-social-admin-input-label">Super Facebook Gallery</div>';
	//	$output .= '<select id="facebook-custom-gallery" name="facebook-custom-gallery" class="feed-them-social-admin-input"><option value="no" >No</option><option value="yes" >Yes. See Super Facebook Gallery Options below.</option></select>';
	//	$output .= '<div class="clear"></div>';
	//	$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
		
		// These options are only for FB album photos and covers
		// SUPER FACEBOOK GALLERY OPTIONS	
		$output .= '<div class="fts-super-facebook-options-wrap" style="display:none">';
		
		// FACEBOOK IMAGE HEIGHT
		$output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Facebook Image Width', 'feed-them-social').'<br/><small>'.__('Max is 640px. You can use % too.', 'feed-them-social').'</small></div>
           <input type="text" name="fts-slicker-instagram-container-image-width" id="fts-slicker-facebook-container-image-width" class="feed-them-social-admin-input" value="250px" placeholder="">
           <div class="clear"></div> </div>';
		 // FACEBOOK IMAGE WIDTH
		 $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Facebook Image Height', 'feed-them-social').'<br/><small>'.__('Max is 640px. You can use % too.', 'feed-them-social').'</small></div>
           <input type="text" name="fts-slicker-instagram-container-image-height" id="fts-slicker-facebook-container-image-height" class="feed-them-social-admin-input" value="250px" placeholder="">
           <div class="clear"></div> </div>';
		// FACEBOOK SPACE BETWEEN PHOTOS  
   	    $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('The space between photos', 'feed-them-social').'</div>
           <input type="text" name="fts-slicker-facebook-container-margin" id="fts-slicker-facebook-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
           <div class="clear"></div></div>';
		// HIDE DATES, LIKES AND COMMENTS ETC  
        $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Hide Date, Likes and Comments', 'feed-them-social').'<br/><small>'.__('Facebook Fixed Height', 'feed-them-social').'Good for image sizes under 120px</small></div>
       		 <select id="fts-slicker-facebook-container-hide-date-likes-comments" name="fts-slicker-facebook-container-hide-date-likes-comments" class="feed-them-social-admin-input">
        	  <option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
			  
		// CENTER THE FACEBOOK CONTAINER 
        $output .= '<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Center Facebook Container', 'feed-them-social').'</div>
        	<select id="fts-slicker-facebook-container-position" name="fts-slicker-facebook-container-position" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
		   
		// ANIMATE PHOTO POSITIONING   
     	$output .= ' <div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Image Stacking Animation On', 'feed-them-social').'<br/><small>'.__('This happens when resizing browsert', 'feed-them-social').'</small></div>
        	 <select id="fts-slicker-facebook-container-animation" name="fts-slicker-facebook-container-animation" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
			 
			 		 
		// POSITION IMAGE LEFT RIGHT 
        $output .= '<div class="instructional-text" style="display: block;">'.__('These options allow you to make the thumbnail larger if you do not want to see black bars above or below your photos.', 'feed-them-social').' <a href="http://www.slickremix.com/docs/fit-thumbnail-on-facebook-galleries/" target="_blank">'.__('View Examples', 'feed-them-social').'</a> '.__('and simple details or leave default options.', 'feed-them-social').'</div>
		
		<div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Make photo larger', 'feed-them-social').'<br/><small>'.__('Helps with blackspace', 'feed-them-social').'</small></div>
			<input type="text" id="fts-slicker-facebook-image-position-lr" name="fts-slicker-facebook-image-position-lr" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -50%. -0% '.__('is default', 'feed-them-social').'">
           <div class="clear"></div></div>';
		// POSITION IMAGE TOP
     	$output .= ' <div class="feed-them-social-admin-input-wrap facebook_name"><div class="feed-them-social-admin-input-label">'.__('Image Position Top', 'feed-them-social').'<br/><small>'.__('Helps center image', 'feed-them-social').'</small></div>
			<input type="text" id="fts-slicker-facebook-image-position-top" name="fts-slicker-facebook-image-position-top" class="feed-them-social-admin-input" value="-0%" placeholder="eg. -10%. -0% '.__('is default', 'feed-them-social').'">
			<div class="clear"></div></div>';
			 
      	$output .= '</div><!--fts-super-facebook-options-wrap-->';
        
	
	
	 	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
		 	//PREMIUM LOAD MORE SETTINGS
        	include($this->premium.'admin/facebook-loadmore-settings-fields.php');
        }	
	
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
			$twitter_popup_option = get_option('twitter_popup_option');
		}
		
		$twitter_name_option = isset($twitter_name_option) ? $twitter_name_option : "";
		
        $output = '<div class="fts-twitter-shortcode-form">'; 
		if($save_options == false){
		  $output .= '<form class="feed-them-social-admin-form shortcode-generator-form twitter-shortcode-form" id="fts-twitter-form">';
		  $output .= '<h2>'.__('Twitter Shortcode Generator', 'feed-them-social').'</h2>';
		}
        $output .= '<div class="instructional-text">'.__('You must copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-twitter-name/" target="_blank">'.__('Twitter Name', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
        
        $output .= '<div class="feed-them-social-admin-input-wrap twitter_name">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Twitter Name (required)', 'feed-them-social').'</div>';
        $output .= '<input type="text" name="twitter_name" id="twitter_name" class="feed-them-social-admin-input" value="'.$twitter_name_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
	if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page'){	
		$output .= '<div class="feed-them-social-admin-input-wrap">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Twitter Fixed Height', 'feed-them-social').'<br/><small>'.__('Leave blank for auto height', 'feed-them-social').'</small></div>';
        $output .= '<input type="text" name="twitter_height" id="twitter_height" class="feed-them-social-admin-input" value="" placeholder="450px '.__('for example', 'feed-them-social').'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
	}
        
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
        	include($this->premium.'admin/twitter-settings-fields.php');
        }
        else 	{
        //Create Need Premium Fields
        $fields = array(
			__('# of Tweets (default 5)', 'feed-them-social'),
			__('Display Photos in Popup', 'feed-them-social'),
        );
        $output .= $this->need_fts_premium_fields($fields);
        }
        
	   if($save_options == false){
			$output .= $this->generate_shortcode('updateTextArea_twitter();','Twitter Feed Shortcode','twitter-final-shortcode');
			$output .= '</form>';
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn" value="'.__('Save Changes', 'feed-them-social').'" />';
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
			$instagram_popup_option = get_option('instagram_popup_option');
		}
        $output = '<div class="fts-instagram-shortcode-form">';
        
		
	
	
		
		if($save_options == false){
		  $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form" id="fts-instagram-form">';
		  
				// ONLY SHOW SUPER GALLERY OPTIONS ON FTS SETTINGS PAGE FOR NOW, NOT FTS BAR
			if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page'){
				
				// INSTAGRAM FEED TYPE
				$output .= '<h2>'.__('Instagram Feed', 'feed-them-social').'</h2><div class="feed-them-social-admin-input-wrap instagram-gen-selection">';
				$output .= '<div class="feed-them-social-admin-input-label">'.__('Feed Type', 'feed-them-social').'</div>';
				$output .= '<select name="instagram-messages-selector" id="instagram-messages-selector" class="feed-them-social-admin-input">';
				$output .= '<option value="user">'.__('User Feed', 'feed-them-social').'</option>';
				$output .= '<option value="hashtag">'.__('Hashtag Feed', 'feed-them-social').'</option>';
				//$output .= '<option value="hashtag">Facebook Hashtag</option>';
				$output .= '</select>';
				$output .= '<div class="clear"></div>';
				$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			};
			
				$output .= '<div class="instagram-id-option-wrap">';
				$output .= '<h2>'.__('Instagram Feed', 'feed-them-social').'Convert Instagram Name to ID</h2>';
		}
		
		$instagram_name_option = isset($instagram_name_option) ? $instagram_name_option : "";
		$instagram_id_option = isset($instagram_id_option) ? $instagram_id_option : "";
		
        $output .= '<div class="instructional-text">'.__('You must copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2012/12/18/how-to-get-your-instagram-name-and-convert-to-id/" target="_blank">'.__('Instagram Name', 'feed-them-social').'</a> '.__('and paste it in the first input below', 'feed-them-social').'</div>';
        $output .= '<div class="feed-them-social-admin-input-wrap convert_instagram_username">';
        $output .= '<div class="feed-them-social-admin-input-label">'.__('Instagram Name (required)', 'feed-them-social').'</div>';
        $output .= '<input type="text" id="convert_instagram_username" name="convert_instagram_username" class="feed-them-social-admin-input" value="'.$instagram_name_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
        
        $output .= '<input type="button" class="feed-them-social-admin-submit-btn" value="'.__('Convert Instagram Username', 'feed-them-social').'" onclick="converter_instagram_username();" tabindex="4" style="margin-right:1em;" />';
       		// ONLY THIS DIV IF ON OUR SETTINGS PAGE
			if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page'){
	   			 $output .= '</div><!--instagram-id-option-wrap-->';
			};	 
		if($save_options == false){
       	  $output .= '</form>';
		}
        
		if($save_options == false){
		  $output .= '<form class="feed-them-social-admin-form shortcode-generator-form instagram-shortcode-form">';
		  $output .= '<h2>'.__('Instagram Shortcode Generator', 'feed-them-social').'</h2>';
		}
        
		$output .= '<div class="instructional-text instagram-user-option-text">'.__('If you added your ID above and clicked convert, a number should appear in the input below, now continue.', 'feed-them-social').'</div>';
        $output .= '<div class="instructional-text instagram-hashtag-option-text" style="display:none;">'.__('Add your Hashtag below. Do not add the #, just the name.', 'feed-them-social').'</div>';
		
		
        $output .= '<div class="feed-them-social-admin-input-wrap instagram_name">';
        $output .= '<div class="feed-them-social-admin-input-label instagram-user-option-text">'.__('Instagram ID # (required)', 'feed-them-social').'</div>';
        $output .= '<div class="feed-them-social-admin-input-label instagram-hashtag-option-text" style="display:none;">'.__('Hashtag (required)', 'feed-them-social').'</div>';
        $output .= '<input type="text" name="instagram_id" id="instagram_id" class="feed-them-social-admin-input" value="'.$instagram_id_option.'" />';
        $output .= '<div class="clear"></div>';
        $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
		
		// Super Instagram Options
		if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page'){
			
		$output .= '<div class="feed-them-social-admin-input-wrap">';
		$output .= '<div class="feed-them-social-admin-input-label">'.__('Super Instagram Gallery', 'feed-them-social').'</div>';
		$output .= '<select id="instagram-custom-gallery" name="instagram-custom-gallery" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!--/feed-them-social-admin-input-wrap-->';
			
		$output .= '<div class="fts-super-instagram-options-wrap"><h2>'.__('Super Instagram Gallery Options', 'feed-them-social').'</h2><div class="instructional-text">'.__('View demos and', 'feed-them-social').' <a href="#">read more</a> '.__('on setup instructions.', 'feed-them-social').'</div>';
			
		$output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Instagram Image Size', 'feed-them-social').'<br/><small>'.__('Max is 640px. You can use % too.', 'feed-them-social').'</small></div>
           <input type="text" name="fts-slicker-instagram-container-image-size" id="fts-slicker-instagram-container-image-size" class="feed-them-social-admin-input" value="250px" placeholder="">
           <div class="clear"></div> </div>';
        $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Size of the Instagram Icon', 'feed-them-social').'<br/><small>'.__('Visible when you hover over photo', 'feed-them-social').'</small></div>
           <input type="text" name="fts-slicker-instagram-icon-center" id="fts-slicker-instagram-icon-center" class="feed-them-social-admin-input" value="65px" placeholder="">
           <div class="clear"></div></div>';
   	    $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('The space between photos', 'feed-them-social').'</div>
           <input type="text" name="fts-slicker-instagram-container-margin" id="fts-slicker-instagram-container-margin" class="feed-them-social-admin-input" value="1px" placeholder="">
           <div class="clear"></div></div>';
        $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Hide Date, Likes and comments', 'feed-them-social').'<br/><small>'.__('Good for image sizes under 120px', 'feed-them-social').'</small></div>
       		 <select id="fts-slicker-instagram-container-hide-date-likes-comments" name="fts-slicker-instagram-container-hide-date-likes-comments" class="feed-them-social-admin-input">
        	  <option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
        $output .= '<div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Center Instagram Container', 'feed-them-social').'</div>
        	<select id="fts-slicker-instagram-container-position" name="fts-slicker-instagram-container-position" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select>
           <div class="clear"></div></div>';
     	$output .= ' <div class="feed-them-social-admin-input-wrap"><div class="feed-them-social-admin-input-label">'.__('Image Stacking Animation On', 'feed-them-social').'<br/><small>'.__('This happens when resizing browser', 'feed-them-social').'</small></div>
        	 <select id="fts-slicker-instagram-container-animation" name="fts-slicker-instagram-container-animation" class="feed-them-social-admin-input"><option value="no">'.__('No', 'feed-them-social').'</option><option value="yes">'.__('Yes', 'feed-them-social').'</option></select><div class="clear"></div></div>';
      	$output .= '</div><!--fts-super-instagram-options-wrap-->';
        
		}
		
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
       		include($this->premium.'admin/instagram-settings-fields.php');
        }
        else 	{
        
        //Create Need Premium Fields
        $fields = array(
			__('# of Pics (default 5)', 'feed-them-social'),
			__('Display Photos in Popup', 'feed-them-social'),
        );
        $output .= $this->need_fts_premium_fields($fields);
        } 
		
	   if($save_options == false){
			$output .= $this->generate_shortcode('updateTextArea_instagram();','Instagram Feed Shortcode','instagram-final-shortcode');
			$output .= '</form>';
			
	   }
	   else{
       		$output .= '<input type="submit" class="feed-them-social-admin-submit-btn instagram-submit" value="'.__('Save Changes', 'feed-them-social').'" />';
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
        $output = '<div class="fts-youtube-shortcode-form">';
		if($save_options == false){
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form youtube-shortcode-form" id="fts-youtube-form">';
			$output .= '<h2>'.__('YouTube Shortcode Generator', 'feed-them-social').'</h2>';
		}
        $output .= '<div class="instructional-text">'.__('You must copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-youtube-name/" target="_blank">'.__('YouTube Name', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
          
      
        if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
        	include($this->premium.'admin/youtube-settings-fields.php');
        }
        else 	{
        //Create Need Premium Fields
        $fields = array(
			__('YouTube Name', 'feed-them-social'),
			__('# of videos', 'feed-them-social'),
			__('# of videos in each row', 'feed-them-social'),
			__('Display First video full size', 'feed-them-social'),
        );
       $output .= $this->need_fts_premium_fields($fields);
      
       $output .= '<a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" target="_blank" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;">'.__('Click to see Premium Version', 'feed-them-social').'</a>';
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
		
		$output = '<div class="fts-pinterest-shortcode-form">';
		if($save_options == false){
			$output .= '<form class="feed-them-social-admin-form shortcode-generator-form pinterest-shortcode-form" id="fts-pinterest-form">';
			$output .= '<h2>'.__('Pinterest Shortcode Generator', 'feed-them-social').'</h2>';
		}
		$output .= '<div class="instructional-text">'.__('You must copy your', 'feed-them-social').' <a href="http://www.slickremix.com/2013/08/01/how-to-get-your-pinterest-name/" target="_blank">'.__('Pinterest Namen', 'feed-them-social').'</a> '.__('and paste it in the first input below.', 'feed-them-social').'</div>';
		
		if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
			include($this->premium.'admin/pinterest-settings-fields.php');
		}
		else 	{
		
		//Create Need Premium Fields
		$fields = array(
			__('Pinterest Name', 'feed-them-social'),
			__('# of boards', 'feed-them-social'),
		);
		$output .= $this->need_fts_premium_fields($fields);
		
		$output .= '<a href="http://www.slickremix.com/downloads/feed-them-social-premium-extension/" class="feed-them-social-admin-submit-btn" style="margin-right:1em; margin-top: 15px; display:block; float:left; text-decoration:none !important;" target="_blank" >'.__('Click to see Premium Version', 'feed-them-social').'</a>';
		$output .= '</form>';

		}
		
		$output .= '</div><!--/fts-pinterest-shortcode-form-->';
		
		return $output;
	}		
	
	
	//Generate Shorecode Button and I<?phpnput for FTS settings Page
	function  generate_shortcode($onclick, $label, $input_class) {
	
      $output = '<input type="button" class="feed-them-social-admin-submit-btn" value="'.__('Generate Shortcode', 'feed-them-social').'" onclick="'.$onclick.'" tabindex="4" style="margin-right:1em;" />';
      $output .= '<div class="feed-them-social-admin-input-wrap final-shortcode-textarea">';
      
     	 $output .= '<h4>'.__('Copy the ShortCode below and paste it on a page or post that you want to display your feed.', 'feed-them-social').'</h4>';
      
        $output .= '<div class="feed-them-social-admin-input-label">'.$label.'></div>';
        
        $output .= '<input class="copyme '.$input_class.' feed-them-social-admin-input" value="" />';
        
      $output .= '<div class="clear"></div>';
      $output .= '</div><!--/feed-them-social-admin-input-wrap-->';
	  
	  return $output;
	}



	/****************************************************
		General Feed Functions
	****************************************************/
	 function fts_get_feed_json($feeds_mulit_data) {
			// data to be returned
			$response = array();
			$curl_success = true;
			if(is_callable('curl_init')){
				// array of curl handles
				$curly = array();
				
				// multi handle
				$mh = curl_multi_init();
			
				// loop through $data and create curl handles
				// then add them to the multi-handle
				foreach ($feeds_mulit_data as $id => $d) {
				  $curly[$id] = curl_init();
			   
				  $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
				  curl_setopt($curly[$id], CURLOPT_URL,            $url);
				  curl_setopt($curly[$id], CURLOPT_HEADER,         0);
				  curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
				  curl_setopt($curly[$id], CURLOPT_SSL_VERIFYPEER, false);
				  curl_setopt($curly[$id], CURLOPT_SSL_VERIFYHOST, 0);
			   
				  // post?
				  if (is_array($d)) {
					if (!empty($d['post'])) {
					  curl_setopt($curly[$id], CURLOPT_POST,       1);
					  curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
					}
				  }
			   
				  // extra options?
				  if (!empty($options)) {
					curl_setopt_array($curly[$id], $options);
				  }
			   
				  curl_multi_add_handle($mh, $curly[$id]);
				}
			   
				// execute the handles
				$running = null;
				do {
				  $curl_status = curl_multi_exec($mh, $running);
				  // Check for errors
				  $info = curl_multi_info_read($mh);
					if (false !== $info) {
						// Add connection info to info array:
						if (!$info['result']) {
							//$multi_info[(integer) $info['handle']]['error'] = 'OK';
						} else {
							$multi_info[(integer) $info['handle']]['error'] = curl_error($info['handle']);
							$curl_success = false;
						}
					}
				  
				} while($running > 0);
			   
				// get content and remove handles
				foreach($curly as $id => $c) {
				  $response[$id] = curl_multi_getcontent($c);
				  curl_multi_remove_handle($mh, $c);
				}
				
				// Display result messages:
				//if($multi_info){
//				  foreach ($multi_info as $each) {
//					  echo $each['url'] . ' => ' . $each['error'] . "\n";
//				  }
//				}

				// All Done With Curl Call
				curl_multi_close($mh);
			} 
			//File_Get_Contents if Curl doesn't work
			if (!$curl_success && ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE) {
				foreach ($feeds_mulit_data as $id => $d) {
				  $response[$id] = @file_get_contents($d);
				}
			} else {
			  //If nothing else use wordpress http API
			  if(!$curl_success && !class_exists( 'WP_Http' )) {
				include_once( ABSPATH . WPINC. '/class-http.php' );
				$wp_http_class = new WP_Http;
				foreach ($feeds_mulit_data as $id => $d) {
					$wp_http_result = $wp_http_class->request($d);
					$response[$id] = $wp_http_result['body'];
				} 
			  }
			  //Do nothing if Curl was Successful 
			}
			
			return $response;
		}
		
		//Create feed cache
		function fts_create_feed_cache($url_to_cache, $response){
			if (!file_exists($url_to_cache)) {
				  touch($url_to_cache);
			 }
			 file_put_contents($url_to_cache, serialize($response));
		}
		
		function fts_get_feed_cache($url_to_cache){
			$returned_cache_data = unserialize(file_get_contents($url_to_cache));
			return $returned_cache_data;
		}
	
	

}//END Class
?>