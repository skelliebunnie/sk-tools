<?php 
/**
 * Plugin Name: SKB Tools
 * Description: A collection of small tools
 * Version: 1.0
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

require_once('dist/skb.enqueue.php');

// SKB-FILTER
foreach(glob(SKB_ROOTDIR ."skb-filter/*.php") as $filename) {
	require_once($filename);
}