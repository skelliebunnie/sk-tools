<?php
if( !defined( 'ABSPATH' ) ) { exit; }

// menu items
function skb_tools_admin_menu() {
	// // MAIN MENU ITEM: Shortcode samples & list of categories
	// add_menu_page(
	// 	'SiriusTimes Documentation', // $page_title
	// 	'SiriusTimes', // $menu_title
	// 	'st_admin', // $capability; manage_options restricts to Administrator Role
	// 	'st_admin_documentation', // $menu_slug
	// 	'st_admin_documentation', // $function
	// 	'dashicons-carrot' // $icon_url, $position
	// );

	// CREATE EVENTS
	add_submenu_page(
		'options-general.php', // parent slug
		'SKB Tools Settings', // $page_title
		'SKB Tools Settings', // $menu_title
		'manage_options', // $capability
		'skb_admin_settings', // $menu_slug
		'skb_admin_settings'//,  $function
	);
}
add_action('admin_menu', 'skb_tools_admin_menu');