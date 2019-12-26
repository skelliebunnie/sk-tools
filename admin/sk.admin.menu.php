<?php
if( !defined( 'ABSPATH' ) ) { exit; }

// menu items
function sk_tools_admin_menu() {
	// MAIN MENU ITEM: Shortcode samples & list of categories
	add_menu_page(
		'SK Tools', // $page_title
		'SK Tools', // $menu_title
		'manage_options', // $capability; manage_options restricts to Administrator Role
		'sk_admin', // $menu_slug
		'sk_admin', // $function
		'dashicons-carrot' // $icon_url, $position
	);

	// SLIDERS SUBMENU PAGE
	// add_submenu_page(
	// 	'sk_admin', // $parent_slug
	// 	'Sliders', // $page_title
	// 	'Sliders', // $menu_title
	// 	'manage_options', // $capability
	// 	'edit.php?post_type=sk_slider' // $callback
	// );

	// SKB-TOOLS SETTINGS
	add_submenu_page(
		//'options-general.php', // parent slug
		'sk_admin',
		'Settings', // $page_title
		'Settings', // $menu_title
		'manage_options', // $capability
		'sk_admin_settings', // $menu_slug
		'sk_admin_settings'//,  $function
	);
}
add_action('admin_menu', 'sk_tools_admin_menu');

function sk_admin_enqueue() {
	$script_misha = "{SK_ROOTURL}/admin/js/misha-image-upload.js";

	wp_register_script('sk-misha-script', $script_misha, array('jquery'), null, true);
}
add_action( 'admin_enqueue_scripts', 'sk_admin_enqueue', 50 );

// Keeps parent page (st_admin_documentation) hilighted when Custom Post Type page is selected
function sk_menu_hilight( $parent_file ) {
	global $plugin_name, $post_type;

	if('sk_sliders' == $post_type) {
		// doesn't work for the ACTUAl edit page, which is post.php?post=#&action=edit ...
		$events_page = 'edit.php?post_type=sk_slider';

		return $parent_file;
	}
}
add_filter('sk_tools_admin_menu', 'sk_menu_hilight');