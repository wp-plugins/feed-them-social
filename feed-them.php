<?php
/*
Plugin Name: Feed Them Social
Plugin URI: http://slickremix.com/
Description: Display feeds for Facebook Groups, Twitter, Pinterest, Instagram and YouTube. Fastest, easiest social plugin in the world to use, hands down!
Version: 1.2.6
Author: SlickRemix
Author URI: http://slickremix.com/
Requires at least: wordpress 3.4.0
Tested up to: wordpress 3.6
Stable tag: 1.2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 * @package    			Feed Them
 * @category   			Core
 * @author     		    SlickRemix
 * @copyright  			Copyright (c) 2012-2013 SlickRemix

If you need support or want to tell us thanks please contact us at info@slickremix.com or use our support forum on slickremix.com.

This is the main file for building the plugin into wordpress
*/
define( 'FEED_THEM_PLUGIN_PATH', plugins_url());

// Include admin
include( 'admin/feed-them-system-info.php' );
include( 'admin/feed-them-settings-page.php' );

// Include core files and classes
include( 'includes/feed-them-functions.php' );

// Include feeds
include( 'feeds/facebook-group/facebook-group-feed.php' );
include( 'feeds/twitter/twitter-feed.php' );
include( 'feeds/instagram/instagram-feed.php' );

/**
 * Returns current plugin version. SRL added
 * 
 * @return string Plugin version
 */
function ftsystem_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}
?>