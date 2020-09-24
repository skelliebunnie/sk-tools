<?php

if( !defined( 'ABSPATH' ) ) { exit; }

require_once SK_PATHS['root_dir'] .'includes/sk.functions.php';

function sk_addressbook_shortcode($atts) {
	$sk_admin_options = get_option("sk_admin_options");

	ob_start();

	if($sk_admin_options['enable_addressbook'] === 'true') {
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
			'show_title'					=> 'true',
			'show_name'						=> 'true',
			'show_phone'					=> 'false',
			'show_email'					=> 'false',
			'title_first'					=> 'true',
			'format'							=> 'block',
			'contact_info_inline'	=> 'false',
			'order'								=> 'default'
		), $atts );

		$contact_list = array();
		$info = null;

		if( $a['target'] !== 'default' ) {
			foreach($contacts as $index=>$item) {
				$vals = array_values($item);

				$target = strtolower($a['target']);
				$target_id = null;

				if( preg_match('~[0-9]~', $target) === 1 ) {
					// remove all digits & PARENTHESES
					// CANNOT use square brackets in shortcode option
					if( preg_match('~:[0-9]~', $target) === 1 ) {
						// echo "found :<br>";
						$target = preg_replace('/\:\d/', '', $target);

					} elseif( preg_match('~\([0-9]\)~', $target) === 1 ) {
						// echo "found ()<br>";
						$target = preg_replace('/\(\d*\)/', '', $target);

					}

					// remove everything *except* digits
					$target_id = preg_replace('/[^\d]/', '', $a['target']);
				}
				
				$target = strtolower(trim($target));

				if( in_array($target, array_map('strtolower', $vals)) ) {

					if( $target_id === null )
						array_push($contact_list, $item);

					if( $target_id !== null && (int)$target_id === $index )
						array_push($contact_list, $item);

					if( $target_id !== null && $target_id === $index )
						array_push($contact_list, $item);
				}

			}
		} else {
			array_push($contact_list, $main_admin);
		}

		if( empty($contact_list) || (is_string($contact_list[0]) && $contact_list[0] == "") ) {
			$site_url = get_site_url();
			$url = substr($site_url, strpos($site_url, "//") + 2);
			$url = str_replace("/", "", $url);
			echo "<a href='mailto:info@{$url}'>info@{$url}</a>";

		} else {
			// ASC == Z-A, DESC == A-Z; order == none / false / default / '', no sort
			if(strtoupper($a['order']) === 'ASC') {
				usort($contact_list, function($a, $b) { return strcmp( strtolower($a['name']), strtolower($b['name']) ); });

				$contact_list = array_reverse($contact_list);

			} elseif(strtoupper($a['order']) === 'DESC') {
				 usort($contact_list, function($a, $b) { return strcmp( strtolower($a['name']), strtolower($b['name']) ); });

			}

			foreach($contact_list as $contact) {
				$info .= sk_format_contact_info( $a, $contact );
			}

		}

		echo $info;

	} else {
		echo "<p>sk_addressbook shortcode not enabled</p>";
	} // end if sk_enable_breadcrumbs check

	return ob_get_clean();
}
add_shortcode('sk_addressbook', 'sk_addressbook_shortcode');

function sk_format_contact_info($options, $contact) {
	$SK_Functions = new SK_Functions();

	$name 	= "<span class='sk-contact--name'>{$contact['name']}</span>";
	$title 	= $options['show_title'] === 'true' ? "<span class='sk-contact--title'>{$contact['title']}</span>" : '';
	$at 		= $options['format'] == 'inline' ? ' at ' : '';
	$email 	= $options['show_email'] === 'true' && $contact['email'] !== '' ? "<span class='sk-contact--email'>{$contact['email']}</span>" : null;
	$tel 		= array_key_exists('phone', $contact) && $contact['phone'] !== '' ? $SK_Functions->sk_format_tel($contact['phone']) : null;
	$call 	= $options['format'] == 'inline' && $tel !== null ? 'call ' : null;
	$phone 	= $options['show_phone'] === 'true' && $tel !== null ? "<span class='sk-contact--phone'>$call<a href='tel:{$tel}'>{$contact['phone']}</a></span>" : null;

	$contact_info = array(); // email & telephone only
	$inline = $options['contact_info_inline'] == 'false' ? '' : 'contact-info-inline';

	$info = "<div class='sk-addressbook--block'>"; // all info; by default, block

	if($options['mailto'] == 'true') {
		$name = $options['show_email'] == 'true' ? "<span class='sk-contact--name'>{$contact['name']}</span>" : "<span class='sk-contact--name'><a href='mailto:{$contact['email']}'>{$contact['name']}</a></span>";

		$email = $options['show_email'] == 'true' ? "<span class='sk-contact--email'>$at<a href='mailto:{$contact['email']}'>{$contact['email']}</a></span>" : null;
	}

	if( $options['contact_info_inline'] == 'true' || str_replace(' ', '', $options['contact_info_inline']) == 'email,phone' ) {
		$contact_info = array();
		if($email !== '' && $email !== null)
			array_push($contact_info, $email);

		if($phone !== '' && $phone !== null)
			array_push($contact_info, $phone);

	} elseif( str_replace(' ', '', $options['contact_info_inline']) == 'phone,email' ) {
		$contact_info = array();
		if($email !== '' && $email !== null)
			array_push($contact_info, $email);
		if($phone !== '' && $phone !== null)
			array_push($contact_info, $phone);

	} elseif( $options['contact_info_inline'] == 'false' ) {
		if($options['show_email'] == 'true' && $email !== '' && $email !== null)
			array_push($contact_info, $email);

		if($options['show_phone'] == 'true' && $phone !== '' && $phone !== null)
			array_push($contact_info, $phone);
	}

	$contact_info_block = "<span class='sk-contact--contact-info $inline'>";
	if( $options['format'] == 'block' && $options['contact_info_inline'] == 'false' || count($contact_info) === 1 ) {
		$contact_info_block .= implode("", $contact_info);

	} elseif( $options['format'] == 'block' && $options['contact_info_inline'] !== 'false' && count($contact_info) > 1 ) {
			$contact_info_block .= implode(", ", $contact_info);

	} else {
		if( count($contact_info) > 1 ) {
			$contact_info_block .= implode(", ", $contact_info);
		} else {
			$contact_info_block .= implode("", $contact_info);
		}

	} 

	$contact_info_block .= "</span>";

	// updating opening div class if format is 'inline'
	if($options['format'] == 'inline')
		$info = "<div class='sk-addressbook--inline'>";

	$info .= $options['title_first'] == 'true' ? $title . $name . $contact_info_block : $name . $title . $contact_info_block;

	$info .= "</div>"; // closing div

	return $info;
}