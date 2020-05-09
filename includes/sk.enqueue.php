<?php

// function sk_admin_enqueue() {

// }
// add_action( 'admin_enqueue_scripts', 'sk_admin_enqueue' );

function sk_styles() {
	global $sk_options;
	
	// FONT AWESOME
	wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css');

	// ADMIN STYLES
	wp_register_style('sk-admin-styles', SK_ROOTURL ."/includes/css/sk.admin.styles.css");

	// FILTER STYLES
	wp_register_style('sk-filters-styles', SK_ROOTURL ."/includes/css/sk.filter.styles.css");
	// FILTER ADVANCED STYLES
	wp_register_style('sk-filters-adv-styles', SK_ROOTURL ."/includes/css/sk.filter-adv.styles.css");
	
	// BREADCRUMB STYLES
	wp_register_style('sk-breadcrumbs-styles', SK_ROOTURL ."/includes/css/sk.breadcrumbs.styles.css");

	// NOTICES STYLES
	wp_register_style('sk-notices-styles', SK_ROOTURL ."/includes/css/sk.notices.styles.css");

	// COLOR-PALETTES STYLES
	wp_register_style('sk-colorpalettes-styles', SK_ROOTURL ."/includes/css/sk.colorpalettes.styles.css");

	// DEFAULT-PALETTE STYLES
	wp_register_style('sk-defaultpalettes-styles', SK_ROOTURL ."/includes/css/sk.defaultpalettes.styles.css");

	// COLORS STYLES
	wp_register_style('sk-color-styles', SK_ROOTURL ."/includes/css/sk.colors.styles.css");

	// CHECKLIST STYLES
	// wp_register_style('sk-checklists-styles', SK_ROOTURL ."/includes/css/sk.checklists.styles.css");
	// if($sk_options['sk_enable_checklists'] === 'true') {
	// 	wp_enqueue_style('sk-checklists-styles');
	// }
}
add_action( 'wp_enqueue_scripts', 'sk_styles' );

function sk_scripts() {
	global $sk_options;

	// General Functions
	wp_register_script('sk-functions-script', SK_ROOTURL ."/includes/js/sk.functions.js", array('jquery'), null, true);

	// Checklists
	wp_register_script('sk-checklists-script', SK_ROOTURL ."/includes/js/sk.checklists.js", array('jquery'), null, true);

	// ../sk-filter/skb.filter.js
	wp_register_script('sk-filter-script', SK_ROOTURL ."/sk-filter/sk.filter.js", array('sk-functions-script'), null, true);

	// ../sk-filter/skb.filter-color.js
	wp_register_script('sk-filter-color-script', SK_ROOTURL ."/sk-filter/sk.filter-color.js", array('sk-functions-script'), null, true);

	// if($sk_options['sk_enable_checklists'] === 'true') {
	// 	wp_enqueue_script( 'sk-checklists-scripts', SK_ROOTURL .'/includes/js/sk.checklists.js', array('jquery'), false, true );
 //  	wp_localize_script( 'sk-checklists-scripts', 'ajaxChecklistsObject', array( 'checklists_ajax_url' => admin_url('admin-ajax.php') ));
	// }

}
add_action( 'wp_enqueue_scripts', 'sk_scripts', 50 );