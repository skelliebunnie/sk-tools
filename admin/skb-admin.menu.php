<?php
if( !defined( 'ABSPATH' ) ) { exit; }

// menu items
function skb_tools_admin_menu() {
	// MAIN MENU ITEM: Shortcode samples & list of categories
	add_menu_page(
		'SKB Tools', // $page_title
		'SKB  Tools', // $menu_title
		'manage_options', // $capability; manage_options restricts to Administrator Role
		'skb_admin', // $menu_slug
		'skb_admin', // $function
		'dashicons-carrot' // $icon_url, $position
	);

	// CREATE EVENTS
	// add_submenu_page(
	// 	//'options-general.php', // parent slug
	// 	'skb_admin',
	// 	'Virtual Posts', // $page_title
	// 	'Virtual Posts', // $menu_title
	// 	'manage_options', // $capability
	// 	'skb_admin_virtualposts', // $menu_slug
	// 	'skb_admin_virtualposts'//,  $function
	// );

	// SKB-TOOLS SETTINGS
	add_submenu_page(
		//'options-general.php', // parent slug
		'skb_admin',
		'Settings', // $page_title
		'Settings', // $menu_title
		'manage_options', // $capability
		'skb_admin_settings', // $menu_slug
		'skb_admin_settings'//,  $function
	);
}
add_action('admin_menu', 'skb_tools_admin_menu');

function skb_admin_enqueue() {
	$script_misha = SKB_ROOTURL . 'admin/js/misha-image-upload.js';

	wp_register_script('skb-misha-script', $script_misha);
}
add_action( 'admin_enqueue_scripts', 'skb_admin_enqueue', 50 );