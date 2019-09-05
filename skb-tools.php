<?php 
/**
 * Plugin Name: SKB Tools
 * Description: A collection of small tools
 * Version: 3.2
 * Author: Margaret Ralston
 * Author URI: https://tech.dinonite.com
 * Prefix: skb
 */

if( !defined( 'ABSPATH' ) ) { exit; }
date_default_timezone_set('America/Los_Angeles');

register_activation_hook( __FILE__, 'flush_rewrite_rules' );

define( 'SKB_ROOTDIR', plugin_dir_path(__FILE__) );
define( 'SKB_ROOTURL', plugins_url() .'/skb-tools/' );
define( 'SKB_SITE_URL', get_site_url() .'/' );
define( 'SKB_SITE_ADMIN_URL', get_site_url() .'/wp-admin/' );

// global options variable; note SINGULAR get_option!
$defaults = array(
	'skb_enable_butterflies'		=> 'true',
	'skb_enable_breadcrumbs'		=> 'true',
	'skb_enable_directory'			=> 'true',
	'skb_enable_filter'					=> 'true',
	'skb_enable_virtualposts'		=> 'true',
	'skb-bc-show_home'					=> 'true',
	'skb-bc-show_home_icon'			=> 'true',
	'skb-bc-home_icon_only'			=> 'false',
	'skb-bc-show_current'				=> 'true',
	'skb-bc-current_url'				=> 'false',
	'skb-d-default_photo'				=> SKB_ROOTURL .'skb-directory/pacsci-icon.png',
	'skb-d-photo_size'					=> '200'
);
// wp_parse_args is REQUIRED when assigning an ARRAY of default values
// Also, $defaults is in wp_parse_args(), NOT get_option() ...
$skb_options = wp_parse_args(get_option('skb_settings'), $defaults);

// global options variable; note SINGULAR get_option!
$vp_defaults = array(
	'skb-vp-url_slug'				=> 'virtual',
	'skb-vp-post_title'			=> 'Virtual Post Title',
	'skb-vp-post_content'		=> 'Virtual Post Content'
);
// wp_parse_args is REQUIRED when assigning an ARRAY of default values
// Also, $defaults is in wp_parse_args(), NOT get_option() ...
$skb_virtualpost_options = wp_parse_args(get_option('skb_virtualpost_settings'), $vp_defaults);

require_once('dist/skb.functions.php');
require_once('dist/skb.enqueue.php');

// SKB-ADMIN
foreach(glob(SKB_ROOTDIR ."admin/*.php") as $filename) {
	require_once($filename);
}

// SKB-FILTER
foreach(glob(SKB_ROOTDIR ."skb-filter/*.php") as $filename) {
	require_once($filename);
}

// SKB-BREADCRUMBS
foreach(glob(SKB_ROOTDIR ."skb-breadcrumbs/*.php") as $filename) {
	require_once($filename);
}

// SKB-AIRTABLE
foreach(glob(SKB_ROOTDIR ."skb-butterflies/*.php") as $filename) {
	require_once($filename);
}

// SKB-VIRTUALPOSTS
// foreach(glob(SKB_ROOTDIR ."skb-virtualposts/*.php") as $filename) {
// 	require_once($filename);
// }

// SKB-DIRECTORY
foreach(glob(SKB_ROOTDIR ."skb-directory/*.php") as $filename) {
	require_once($filename);
}

