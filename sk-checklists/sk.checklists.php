<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_find_checklists() {
	$values = $_POST;
	var_dump($values);
	// $user = wp_get_current_user();

	// $postMeta = get_post_meta($_POST['post_id']);

	// foreach($postMeta as $key,$meta) {
	// 	if( strpos($key, "sk-checklist") ) {

	// 	}
	// }

	// var_dump($getMeta);
	// var_dump($getMeta);
	// $return = ["meta_id" => $getMeta[0]->meta_id, "user_id" => $user->ID];

	// echo implode(",", $return);

	wp_die();
}
add_action( "wp_ajax_sk_find_checklists", "sk_find_checklists" );
add_action( "wp_ajax_nopriv_sk_find_checklists", "sk_find_checklists" );

function sk_save_checklist() {
	$values = $_POST;
	unset($values['action']);
	unset($values['post_id']);

	$user = wp_get_current_user();

	$meta_key = "sk-checklist-{$values['checklist_id']}--{$user->ID}";
	$meta_value = "checklist_id: {$values['checklist_id']};";
	foreach($values as $key=>$value) {
		$meta_value .= "{$key}: {$value};";
	}
	$meta_value = substr($meta_value, 0, strlen($meta_value) - 1);

	$updated = update_post_meta( (int)$_POST['post_id'], $meta_key, $meta_value, true );

	$getMeta = get_complete_meta($_POST['post_id'], $meta_key);
	var_dump($getMeta);
	$return = ["meta_id" => $getMeta[0]->meta_id, "user_id" => $user->ID];

	echo implode(",", $return);

	wp_die();
}
add_action( "wp_ajax_sk_save_checklist", "sk_save_checklist" );
add_action( "wp_ajax_nopriv_sk_save_checklist", "sk_save_checklist" );

function get_complete_meta( $post_id, $meta_key ) {
  global $wpdb;
  $mid = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post_id, $meta_key) );
  if( $mid != '' )
    return $mid;

  return false;
}