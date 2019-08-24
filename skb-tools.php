<?php 
/**
 * Plugin Name: SKB Tools
 * Description: A collection of small tools
 * Version: 2.5
 * Author: Margaret Ralston
 * Author URI: https://tech.dinonite.com
 * Prefix: skb
 */

if( !defined( 'ABSPATH' ) ) { exit; }
date_default_timezone_set('America/Los_Angeles');

define( 'SKB_ROOTDIR', plugin_dir_path(__FILE__) );
define( 'SKB_ROOTURL', plugins_url() .'/skb-tools/' );
define( 'SKB_SITE_URL', get_site_url() .'/' );
define( 'SKB_SITE_ADMIN_URL', get_site_url() .'/wp-admin/' );

// global options variable; note SINGULAR get_option!
$defaults = array(
	'skb-bc_show_home'					=> 'true',
	'skb-bc_show_home_icon'			=> 'true',
	'skb-bc_home_icon_only'			=> 'false',
	'skb-bc_show_current'				=> 'true',
	'skb-bc_current_url'				=> 'false'
);
// wp_parse_args is REQUIRED when assigning an ARRAY of default values
// Also, $defaults is in wp_parse_args(), NOT get_option() ...
$skb_options = wp_parse_args(get_option('skb_settings'), $defaults);

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