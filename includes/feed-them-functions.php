<?php
/************************************************
 	Function file for Feed Them Social plugin
************************************************/
add_action('admin_menu', 'Feed_Them_Main_Menu');

function Feed_Them_Main_Menu() {
   add_menu_page('Feed Them Social', 'Feed Them', 'edit_plugins', 'feed-them-settings-page', 'feed_them_settings_page', 'div', 99);
}

add_action('admin_menu', 'Feed_Them_Submenu_Pages');

function Feed_Them_Submenu_Pages() {   
	add_submenu_page( 
          'feed-them-settings-page'
        , 'System Info' 
        , 'System Info'
        , 'manage_options'
        , 'fts-system-info-submenu-page'
        , 'feed_them_system_info_page'
    );
}

add_action('admin_enqueue_scripts', 'feed_them_admin_css');
// THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
function feed_them_admin_css() {
    wp_register_style( 'feed_them_admin', plugins_url( 'admin/css/admin.css', dirname(__FILE__) ) );  
	wp_enqueue_style('feed_them_admin');
}

if (isset($_GET['page']) && $_GET['page'] == 'fts-system-info-submenu-page') {
  add_action('admin_enqueue_scripts', 'feed_them_system_info_css');
  // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
  function feed_them_system_info_css() {
	  wp_register_style( 'fts-settings-admin-css', plugins_url( 'admin/css/admin-settings.css',  dirname(__FILE__) ) );
	  wp_enqueue_style('fts-settings-admin-css'); 
  }
}

if (isset($_GET['page']) && $_GET['page'] == 'feed-them-settings-page') {
  add_action('admin_enqueue_scripts', 'feed_them_settings');
  // THIS GIVES US SOME OPTIONS FOR STYLING THE ADMIN AREA
  function feed_them_settings() {
	  wp_register_style( 'feed_them_settings_css', plugins_url( 'admin/css/settings-page.css',  dirname(__FILE__) ) );
	  wp_enqueue_style('feed_them_settings_css'); 
	  wp_enqueue_script( 'feed_them_settings_js', plugins_url( 'admin/js/admin.js',  dirname(__FILE__) ) ); 
  }
}
?>