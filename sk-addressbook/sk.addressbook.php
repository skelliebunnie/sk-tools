<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_addressbook_shortcode($atts) {
	global $sk_options;

	ob_start();

	if($sk_options['sk_enable_addressbook'] === 'true') {
		wp_enqueue_style('sk-addressbook-styles');

		$contacts = is_array( get_option('sk_addressbook') ) ? get_option('sk_addressbook') : explode(",", get_option('sk_addressbook'));

		$admins = get_users( [ 'role__in' => ['administrator'], 'fields' => ['id', 'display_name', 'user_email'] ] );

		$main_admin = array('name' => $admins[0]->display_name, 'email' => $admins[0]->user_email, 'title' => 'Administrator');

    $admin_id = $admins[0]->id;
    foreach($admins as $admin) {
    	if( $admin->id < $admin_id ) {
    		$main_admin['name'] = $admin->display_name;
    		$main_admin['email'] = $admin->user_email;

    		$admin_id = $admin->id;
    	}
    }

		$a = shortcode_atts( array(
			'target'			=> 'default',
			'mailto'			=> 'true',
			'show_title'	=> 'false'
		), $atts );

		$info = "";
		if( $a['target'] == 'default' ) {
			$info = "{$main_admin['name']}";
		} else {
			// foreach( $contacts as $contact ) {

			// }
		}

	} else {
		echo "<p>sk_addressbook shortcode not enabled</p>";
	} // end if sk_enable_breadcrumbs check

	return ob_get_clean();
}
add_shortcode('sk_addressbook', 'sk_addressbook_shortcode');