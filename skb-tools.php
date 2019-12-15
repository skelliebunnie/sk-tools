<?php 
/**
 * Plugin Name: SKB Tools
 * Description: A collection of small tools
 * Version: 4.0.0
 * Author: Angel Knight
 * Author URI: https://curiousexplorations.com
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
	'skb_enable_notices'				=> 'true',
	'skb_enable_filter'					=> 'true',
	'skb_enable_datetime'				=> 'true',
	'skb-bc-show_home'					=> 'true',
	'skb-bc-show_home_icon'			=> 'true',
	'skb-bc-home_icon_only'			=> 'false',
	'skb-bc-show_current'				=> 'true',
	'skb-bc-current_url'				=> 'false',
	'skb-btf-author'						=> 3,
	'skb-n-default_message'			=> 'Notice',
	'skb-n-default_date_format'	=> 'l, F j, Y',
	'skb-n-default_message_type' => 'simple',
	'skb-n-default_weekdays'		=> array('mon','tue','wed','thu','fri'),
	'skb-dt-default_date_format' => 'l, F j, Y', // e.g. Monday, November 18, 2019
	'skb-dt-default_time_format' => 'h:i A', // e.g. 07:12 AM; g/G no leading 0, h/H leading 0 (12/24)
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

require_once('includes/skb.functions.php');
require_once('includes/skb.enqueue.php');

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

// SKB-BUTTERFLIES
foreach(glob(SKB_ROOTDIR ."skb-butterflies/*.php") as $filename) {
	require_once($filename);
}

// SKB-NOTICES
foreach(glob(SKB_ROOTDIR ."skb-notices/*.php") as $filename) {
	require_once($filename);
}

// SKB-DATETIME
foreach(glob(SKB_ROOTDIR ."skb-datetime/*.php") as $filename) {
	require_once($filename);
}

// SKB-SHORTCODE-GENERATOR
foreach(glob(SKB_ROOTDIR ."skb-slider/*.php") as $filename) {
	require_once($filename);
}

function skb_tools_activation() {
	//flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'skb_tools_activation' );

function skb_tools_deactivation() {
	//flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'skb_tools_deactivation' );

// function skb_append($content) {
// 	global $post;

// 	//$content .= $post->post_parent;

// 	if( $post->post_name === "african-moon-moth" ) {
// 		$content .= "Permalink: ". get_permalink($post) ."<br>";
// 		$content .= "Ancestors: ". implode("<br>", $post->ancestors);
// 	}

// 	return $content;
// }
// add_filter('the_content', 'skb_append');