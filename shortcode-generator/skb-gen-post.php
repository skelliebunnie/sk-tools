<?php
/*
	FULL CUSTOM POST TYPE CREATION
 */
function skb_custom_posttype() {
	// UI labels
	$labels = array(
		'name'								=> _x('Sliders', 'Post Type General Name', 'skb_domain'),
		'singular_name'				=> _x('Slider', 'Post Type Singular Name', 'skb_domain'),
		'menu_name'						=> __('Sliders', 'skb_domain'),
		'parent_item_colon'		=> __('Parent Slider', 'skb_domain'),
		'all_items'						=> __('All Sliders', 'skb_domain'),
		'view_item'						=> __('View Slider', 'skb_domain'),
		'add_new_item'				=> __('Add New Slider', 'skb_domain'),
		'add_new'							=> __('Add New', 'skb_domain'),
		'edit_item'						=> __('Edit Slider', 'skb_domain'),
		'update_item'					=> __('Update Slider', 'skb_domain'),
		'search_items'				=> __('Search Sliders', 'skb_domain'),
		'not_found'						=> __('Not Found', 'skb_domain'),
		'not_found_in_trash'	=> __('Not Found in Trash', 'skb_domain')
	);

	// Other options for Custom Post Type
	$args = array(
		'label'					=> __('sliders', 'skb_domain'),
		'description'		=> __('Auto-rotating sliders', 'skb_domain'),
		'labels'				=> $labels,
		// Features this CPT supports in Post Editor
		'supports'			=> array('title', 'author', 'custom-fields'),
		// You can associate this CPT with a (custom) taxonomy
		'taxonomies'		=> array('sliders'),
		/*
			Hierarchical CPT is like Pages & can have parent/child items.
			Non-hierarchical is like Posts, with no parent/child items.
		 */
		'hierarchical'				=> false,
		'public'							=> true,
		'show_ui'							=> true,
		'show_in_menu'				=> true,
		'show_in_admin_bar'		=> true,
		'menu_position'				=> 5,
		'can_export'					=> false,
		'has_archive'					=> true,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'capability_type'			=> 'page'
	);

	// Register custom post type
	register_post_type('sliders', $args);
}
add_action('init', 'skb_custom_posttype', 0);

/*
	SIMPLIFIED CUSTOM POST TYPE CREATION
 */
// function skb_create_posttype() {
// 	register_post_type(
// 		'skb_gen',
// 		// CPT Options
// 		array(
// 			'labels' => array(
// 				'name' => __('Sliders'), 
// 				'singular_name' => __('Slider')
// 			),
// 			'public' => true,
// 			'has_archive' => true,
// 			'rewrite' => array('slug' => 'sliders'),
// 		)
// 	);
// }
// add_action('init', 'skb_create_posttype');