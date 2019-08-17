<?php 
/**
 * Plugin Name: SKB Tools
 * Description: A collection of small tools
 * Version: 0.1
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

function skb_styles() {
	wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css');

	wp_register_style('skb-filters-style', SKB_ROOTURL ."dist/css/skb-filter.styles.css");
}
add_action( 'wp_enqueue_scripts', 'skb_styles' );
add_action( 'admin_enqueue_scripts', 'skb_styles' );

function skb_scripts() {
	wp_register_script('skb-functions-script', SKB_ROOTURL ."skb.functions.js", array('jquery'), null, true);
	wp_register_script('skb-filter-script', SKB_ROOTURL ."skb.filter.js", array('skb-functions-script'), null, true);
}
add_action( 'wp_enqueue_scripts', 'skb_scripts' );
add_action( 'admin_enqueue_scripts', 'skb_scripts' );

function skb_filter_shortcode($atts) {
	wp_enqueue_style('skb-filters-style');

	$a = shortcode_atts( array(
		'singular' 	=> 'butterfly',
		'plural'		=> 'butterflies'
	), $atts );

	wp_enqueue_script('skb-filter-script');
	ob_start();
?>
	<div id="skb-filter-container" data-singular="<?php echo $a['singular']; ?>" data-plural="<?php echo $a['singular']; ?>"></div>
<?php
	 return ob_get_clean();
}
add_shortcode('skb_filter', 'skb_filter_shortcode');