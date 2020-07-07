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

    // var_dump($contacts);

		$a = shortcode_atts( array(
			'target'			=> 'default',
			'mailto'			=> 'true',
			'show_title'	=> 'false',
			'format'			=> 'block', // ALT: inline
			'show_email'	=> 'false',
			'title_first'	=> 'true'
		), $atts );

		$info = $main_admin;
		if( $a['target'] !== 'default' ) {
			foreach($contacts as $contact) {
				$vals = array_values($contact);
				if( in_array(strtolower($a['target']), array_map('strtolower', $vals)) ) {
					$info = $contact;
				}
			}
		}

		if( empty($info) || (is_string($info) && $info == "") ) {
			$site_url = get_site_url();
			$url = substr($site_url, strpos($site_url, "//") + 2);
			$url = str_replace("/", "", $url);
			echo "<a href='mailto:info@{$url}'>info@{$url}</a>";

		} else {

			if($a['format'] == 'block') {
				$text = "<div class='sk-addressbook--block'>";
				
				if($a['title_first'] == 'true') {
					$text .= "<span class='sk-contact--name'>{$info['name']}</span><br/>";
					$text .= "<span class='sk-contact--title'>{$info['title']}</span><br/>";
				} else {
					$text .= "<span class='sk-contact--title'>{$info['title']}</span><br/>";
					$text .= "<span class='sk-contact--name'>{$info['name']}</span><br/>";
				}

				$text .= "<span class='sk-contact--email'><a href='mailto:{$info['email']}'>{$info['email']}</a></span>";
				$text .= "</div>";

			} else {
				$text = "<span class='sk-addressbook--inline'>";
				if($a['title_first'] == 'true')
					$text .= "<span class='sk-contact--title'>{$info['title']}</span>, ";

				if( $a['show_email'] !== 'true' ) {
					$text .= "<span class='sk-contact--name'><a href='mailto:{$info['email']}'>{$info['name']}</a></span>";

					if($a['title_first'] != 'true')
						$text .= ", <span class='sk-contact--title'>{$info['title']}</span>";

				} else {
					$text .= "<span class='sk-contact--name'>{$info['name']}</span>";
					if($a['title_first'] != 'true')
						$text .= ", <span class='sk-contact--title'>{$info['title']}</span>, ";
					$text .= " at <span class='sk-contact--email'><a href='mailto:{$info['email']}'>{$info['email']}</a></span>";
				}

				$text .= "</span>";

			}

			echo $text;

		}

	} else {
		echo "<p>sk_addressbook shortcode not enabled</p>";
	} // end if sk_enable_breadcrumbs check

	return ob_get_clean();
}
add_shortcode('sk_addressbook', 'sk_addressbook_shortcode');