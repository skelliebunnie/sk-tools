<?php
if( !defined( 'ABSPATH' ) ) { exit; }

function sk_image_upload($target, $url='', $setting = false) {
	global $sk_options;

  $image_size = 'thumbnail'; // it would be better to use thumbnail size here (150x150 or so)
  $display = 'none'; // display state ot the "Remove image" button

  $hide_remove_btn = 'hidden'; $set_new_img = 'Set';
	if($url !== "") {
		$hide_remove_btn = '';
		$set_new_img = 'New';

	}

	$target_name = $target;
	if($setting === true) {
		$target_name = "sk_settings[$target]";
	}

	$image_link = "<img id='imagePreview-{$target}' src='{$url}' height='150' style='margin-top: 1rem;'><br>";

	$image_link .= "<section style='margin-left: 1.25rem;'><a href='#' id='{$target}-image' data-target='{$target}' class='misha_upload_image_button button' title='Set Image' aria-label='Set Image'><i class='far fa-image'></i> $set_new_img</a>";
	
	$image_link .= "<input type='hidden' name='{$target_name}' id='image-{$target}' value='{$url}' />"; 

	$image_link .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='#' class='misha_remove_image_button button {$hide_remove_btn}' title='Remove Image' aria-label='Remove Image'><i class='far fa-trash-alt'></i> Remove</a></section>";

  return "<div id='{$target}_image-upload'>". $image_link ."</div>";
}

// by wpsmith @ https://gist.github.com/wpsmith/4541a05954aef58a59c5
function sk_sanitize_phone_number( $phone ) {
	$format = "/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?$/";
	
	$alt_format = '/^(\+\s*)?((0{0,2}1{1,3}[^\d]+)?\(?\s*([2-9][0-9]{2})\s*[^\d]?\s*([2-9][0-9]{2})\s*[^\d]?\s*([\d]{4})){1}(\s*([[:alpha:]#][^\d]*\d.*))?$/';

	// Trim & Clean extension
  $phone = trim( $phone );
  $phone = preg_replace( '/\s+(#|x|ext(ension)?)\.?:?\s*(\d+)/', ' ext \3', $phone );

  if ( preg_match( $alt_format, $phone, $matches ) ) {
      return '(' . $matches[4] . ') ' . $matches[5] . '-' . $matches[6] . ( !empty( $matches[8] ) ? ' ' . $matches[8] : '' );
  } elseif( preg_match( $format, $phone, $matches ) ) {

  	// format
  	$phone = preg_replace( $format, "($2) $3-$4", $phone );

  	// Remove likely has a preceding dash
  	$phone = ltrim( $phone, '-' );

  	// Remove empty area codes
  	if ( false !== strpos( trim( $phone ), '()', 0 ) ) { 
  		$phone = ltrim( trim( $phone ), '()' );
  	}

  	// Trim and remove double spaces created
  	return preg_replace( '/\\s+/', ' ', trim( $phone ) );
  }

  return '';
}

function sk_format_tel($phone) {
  if($phone !== '') {
    // replace all spaces with a dash
    $phone = preg_replace( '/\s/', '-', $phone );
    // remove everything except digits and dashes
    return preg_replace( '/[^\d-]/', '', $phone );
  }

  return null;
}