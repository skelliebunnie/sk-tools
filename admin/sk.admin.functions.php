<?php

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