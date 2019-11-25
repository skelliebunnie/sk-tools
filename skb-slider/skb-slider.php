<?php

function skb_slider_shortcode($atts) {
	$a = shortcode_atts(array(

	), $atts);

	ob_start();

	// content

	return ob_get_clean();
}
add_shortcode('skb_slider', 'skb_slider_shortcode');