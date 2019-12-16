<?php

function sk_styles() {
	wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css');

	wp_register_style('sk-admin-styles', SK_ROOTURL ."includes/css/sk.admin.styles.css");

	wp_register_style('sk-filters-styles', SK_ROOTURL ."includes/css/sk.filter.styles.css");

	wp_register_style('sk-breadcrumbs-styles', SK_ROOTURL ."includes/css/sk.breadcrumbs.styles.css");

	wp_register_style('sk-notices-styles', SK_ROOTURL ."includes/css/sk.notices.styles.css");
}
add_action( 'wp_enqueue_scripts', 'sk_styles' );
add_action( 'admin_enqueue_scripts', 'sk_styles' );

function sk_scripts() {
	// General Functions
	wp_register_script('sk-functions-script', SK_ROOTURL ."includes/sk.functions.js", array('jquery'), null, true);

	// ../sk-filter/skb.filter.js
	wp_register_script('sk-filter-script', SK_ROOTURL ."sk-filter/sk.filter.js", array('sk-functions-script'), null, true);

	// ../sk-filter/skb.filter-color.js
	wp_register_script('sk-filter-color-script', SK_ROOTURL ."sk-filter/sk.filter-color.js", array('sk-functions-script'), null, true);

}
add_action( 'wp_enqueue_scripts', 'sk_scripts' );
add_action( 'admin_enqueue_scripts', 'sk_scripts' );