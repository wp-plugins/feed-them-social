<?php
/*
Plugin Name: Feed Them Social
Plugin URI: http://slickremix.com/
Description: Create and display custom feeds for Facebook Groups, Facebook Pages, Facebook Events, Twitter, Instagram, Pinterest and YouTube.
Version: 1.5.4
Author: SlickRemix
Author URI: http://slickremix.com/
Requires at least: wordpress 3.4.0
Tested up to: WordPress 3.9.2
Stable tag: 1.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

 * @package    			Feed Them
 * @category   			Core
 * @author     		    SlickRemix
 * @copyright  			Copyright (c) 2012-2014 SlickRemix

If you need support or want to tell us thanks please contact us at info@slickremix.com or use our support forum on slickremix.com.

This is the main file for building the plugin into wordpress
*/
define( 'FEED_THEM_PLUGIN_PATH', plugins_url());

function fts_action_init()
{
// Localization
load_plugin_textdomain('feed-them-social', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
// Add actions
add_action('init', 'fts_action_init');

$fts_plugin_rel_url = plugin_dir_path( __FILE__ );

// Include admin
include( $fts_plugin_rel_url.'updates/update-functions.php' );
include( $fts_plugin_rel_url.'admin/feed-them-system-info.php' );
include( $fts_plugin_rel_url.'admin/feed-them-settings-page.php' );

// Include core files and classes
include( $fts_plugin_rel_url.'includes/feed-them-functions.php' );

$load_fts = new feed_them_social_functions();

//Check Premium Version.
$load_fts->fts_get_check_plugin_version('feed-them-premium.php', '1.3.0');

$load_fts->init();



// Include feeds
include( $fts_plugin_rel_url.'feeds/facebook/facebook-feed.php' );
include( $fts_plugin_rel_url.'feeds/twitter/twitter-feed.php' );
include( $fts_plugin_rel_url.'feeds/instagram/instagram-feed.php' );

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