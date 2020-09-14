<?php 
/**
 * Plugin Name: SK Tools
 * Description: A collection of small tools
 * Version: 4.5.1
 * Author: Angel Knight
 * Author URI: https://curiousexplorations.com
 * Prefix: sk
 */

if( !defined( 'ABSPATH' ) ) { exit; }
date_default_timezone_set('America/Los_Angeles');

register_activation_hook( __FILE__, 'flush_rewrite_rules' );

define( 'SK_ROOTDIR', plugin_dir_path(__FILE__) );
define( 'SK_ROOTURL', plugins_url() .'/sk-tools' );
define( 'SK_SITE_URL', get_site_url() .'/' );
define( 'SK_SITE_ADMIN_URL', get_site_url() .'/wp-admin' );

// global options variable; note SINGULAR get_option!
$defaults = array(
	'sk_clear_options_on_deactivation' => 'true',
	'sk_enable_addressbook'			=> 'true',
	'sk_enable_breadcrumbs'			=> 'true',
	'sk_enable_notices'					=> 'true',
	'sk_enable_filter'					=> 'true',
	'sk_enable_filter_advanced'	=> 'true',
	'sk_enable_datetime'				=> 'true',
	'sk_enable_colorpalettes'		=> 'true',
	'sk-bc-show_home'						=> 'true',
	'sk-bc-show_home_icon'			=> 'true',
	'sk-bc-home_icon_only'			=> 'false',
	'sk-bc-show_current'				=> 'true',
	'sk-bc-current_url'					=> 'false',
	'sk-n-default_message'			=> 'Notice',
	'sk-n-default_date_format'	=> 'l, F j, Y',
	'sk-n-default_message_type' => 'simple',
	'sk-n-default_weekdays'			=> array('mon','tue','wed','thu','fri'),
	'sk-dt-default_date_format' => 'l, F j, Y', // e.g. Monday, November 18, 2019
	'sk-dt-default_time_format' => 'h:i A' // e.g. 07:12 AM; g/G no leading 0, h/H leading 0 (12/24)
);
// wp_parse_args is REQUIRED when assigning an ARRAY of default values
// Also, $defaults is in wp_parse_args(), NOT get_option() ...
// global options variable; note SINGULAR get_option!
$sk_options = wp_parse_args(get_option('sk_settings'), $defaults);

// $vp_defaults = array(
// 	'sk-vp-url_slug'				=> 'virtual',
// 	'sk-vp-post_title'			=> 'Virtual Post Title',
// 	'sk-vp-post_content'		=> 'Virtual Post Content'
// );
// // wp_parse_args is REQUIRED when assigning an ARRAY of default values
// // Also, $defaults is in wp_parse_args(), NOT get_option() ...
// $sk_virtualpost_options = wp_parse_args(get_option('sk_virtualpost_settings'), $vp_defaults);

require_once('includes/sk.functions.php');
require_once('includes/sk.enqueue.php');

// SK-ADMIN
foreach(glob(SK_ROOTDIR ."/admin/*.php") as $filename) {
	require_once($filename);
}

// SK-FILTER
foreach(glob(SK_ROOTDIR ."/sk-filter/*.php") as $filename) {
	require_once($filename);
}

// SK-BREADCRUMBS
foreach(glob(SK_ROOTDIR ."/sk-breadcrumbs/*.php") as $filename) {
	require_once($filename);
}

// SK-NOTICES
foreach(glob(SK_ROOTDIR ."/sk-notices/*.php") as $filename) {
	require_once($filename);
}

// SK-DATETIME
foreach(glob(SK_ROOTDIR ."/sk-datetime/*.php") as $filename) {
	require_once($filename);
}

// SK-COLORS
foreach(glob(SK_ROOTDIR ."/sk-colors/*.php") as $filename) {
	require_once($filename);
}

// SK-ADDRESSBOOK
foreach(glob(SK_ROOTDIR ."/sk-addressbook/*.php") as $filename) {
	require_once($filename);
}

function sk_tools_activation() {
	//flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sk_tools_activation' );

function sk_tools_deactivation() {
	global $sk_options;

	if( $sk_options['sk_clear_options_on_deactivation'] == 'true' ) {
		delete_option('sk_addressbook');
		delete_option('sk_settings');
	}
}
register_deactivation_hook( __FILE__, 'sk_tools_deactivation' );