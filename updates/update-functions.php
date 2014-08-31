<?php
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
if (!defined('SLICKREMIX_STORE_URL')) {
	define( 'SLICKREMIX_STORE_URL', 'http://www.slickremix.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file.
}
if( !class_exists('EDD_SL_Plugin_Updater')) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

if( !class_exists('EDD_SL_Plugin_Licence_Manager')) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Licence_Manager.php' );
}