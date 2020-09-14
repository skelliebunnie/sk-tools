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

    /**
     * 'target' = (string) name, email, title, or phone number; what info to find
     * 'mailto' = (bool); whether or not to make name/email a mailto link
     * 'show_title' = (bool)
     * 'show_name' = (bool)
     * 'show_phone' = (bool)
     * 'show_email' = (bool)
     * 'title_first' = (bool); display title before name [true], default TRUE
     * 'format' = (string) block, inline; only 2 options, default BLOCK
     * 'contact_info_inline' = (string) false / true / email, phone / phone, email
     * 		ignored if format is inline
     * 		TRUE / phone, email: (123) 456-7890, name@email.com
     * 		email, phone: name@email.com, (123) 456-7890
     * 		FALSE: name@email.com \n (123) 456-7890
     */

		$a = shortcode_atts( array(
			'target'							=> 'default',
			'mailto'							=> 'true',
			'show_title'					=> 'false',
			'show_name'						=> 'true',
			'show_phone'					=> 'false',
			'show_email'					=> 'false',
			'title_first'					=> 'true',
			'format'							=> 'block',
			'contact_info_inline'	=> 'false',
		), $atts );

		$contact = $main_admin;
		$contact_list = array();

		if( $a['target'] !== 'default' ) {
			foreach($contacts as $index=>$item) {
				$vals = array_values($item);

				$target = strtolower($a['target']);
				$target_id = null;
				if( preg_match('~[0-9]~', $a['target']) === 1 ) {
					// remove all digits & square brackets
					$target = preg_replace('/\d*\[\]/', '', $a['target']);
					// remove everything *except* digits
					$target_id = preg_replace('/[^\d]/', '', $a['target']);
				}

				if( in_array($target, array_map('strtolower', $vals)) ) {
					if( $target_id === null )
						array_push($contact_list, $item);

					if( $target_id !== null && $target_id === $index )
						array_push($contact_list, $item);
				}

			}

			if( count($contact_list) > 1 ) {
				$contact = $contact_list[0];
			}
		}

		if( empty($contact) || (is_string($contact) && $contact == "") ) {
			$site_url = get_site_url();
			$url = substr($site_url, strpos($site_url, "//") + 2);
			$url = str_replace("/", "", $url);
			echo "<a href='mailto:info@{$url}'>info@{$url}</a>";

		} elseif( count($contact_list) === 1 ) {
			$info = sk_format_contact_info( $contact );

		} elseif( count($contact_list) > 1 ) {

			foreach($contact_list as $contact) {
				$contact_info = sk_format_contact_info( $contact );

			}

		}

		echo $info;

	} else {
		echo "<p>sk_addressbook shortcode not enabled</p>";
	} // end if sk_enable_breadcrumbs check

	return ob_get_clean();
}
add_shortcode('sk_addressbook', 'sk_addressbook_shortcode');

function sk_format_contact_info($contact) {
	$name = "<span class='sk-contact--name'>{$contact['name']}</span>";
	$title = "<span class='sk-contact--title'>{$contact['title']}</span>";
	$at = $a['format'] == 'inline' ? 'at ' : '';
	$email = "<span class='sk-contact--email'>{$contact['email']}</span>";
	$tel = sk_format_tel($contact['phone']);
	$call = $a['format'] == 'inline' ? 'call ' : '';
	$phone = $contact['phone'] !== null ? "<span class='sk-contact--phone'>$call<a href='tel:{$tel}'>{$contact['phone']}</a></span>" : null;

	$contact_info = array(); // email & telephone only
	$inline = $a['contact_info_inline'] == 'false' ? '' : 'contact-info-inline';

	$info = "<div class='sk-addressbook--block'>"; // all info; by default, block

	if($a['mailto'] == 'true') {
		$name = $a['show_email'] == 'true' ? "<span class='sk-contact--name'>{$contact['name']}</span>" : "<span class='sk-contact--name'><a href='mailto:{$contact['email']}'>{$contact['name']}</a></span>";

		$email = $a['show_email'] == 'true' ? "<span class='sk-contact--email'>$at<a href='mailto:{$contact['email']}'>{$contact['email']}</a></span>" : null;
	}

	if( $a['contact_info_inline'] == 'true' || str_replace(' ', '', $a['contact_info_inline']) == 'email,phone' ) {
		$contact_info = array($email, $phone);

	} elseif( str_replace(' ', '', $a['contact_info_inline']) == 'phone,email' ) {
		$contact_info = array($phone, $email);

	} elseif( $a['contact_info_inline'] == 'false' ) {
		if($a['show_email'] == 'true')
			array_push($contact_info, $email);

		if($a['show_phone'] == 'true')
			array_push($contact_info, $phone);
	}

	$contact_info_block = "<span class='sk-contact--contact-info $inline'>";
	if( $a['format'] == 'block' && $a['contact_info_inline'] == 'false' ) {
		$contact_info_block .= implode("", $contact_info);

	} elseif( $a['format'] == 'block' && $a['contact_info_inline'] !== 'false' ) {
		$contact_info_block .= implode(", ", $contact_info);

	} else {
		$contact_info_block .= implode(" or ", $contact_info);
	}

	$contact_info_block .= "</span>";

	// updating opening div class if format is 'inline'
	if($a['format'] == 'inline')
		$info = "<div class='sk-addressbook--inline'>";

	$info .= $a['title_first'] == 'true' ? $title . $name . $contact_info_block : $name . $title . $contact_info_block;

	$info .= "</div>"; // closing div

	return $info;
}