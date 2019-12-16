<?php

function sk_filter_shortcode($atts) {
	global $sk_options;

	ob_start();

	if($sk_options['sk_enable_filter'] === 'true') {
		wp_enqueue_style('sk-filters-styles');

		wp_enqueue_script('sk-functions-script');
		wp_enqueue_script('sk-filter-script');

		// types are: 
		//	* default ( 1 filter at a time )
		//	* additive / add ( select 1+ filter, across lists; results are 1+ match )
		// 	* subtractive / sub ( select 1+ filter; results must match ALL selected )
		$a = shortcode_atts( array(
			'type'				=> 'default',
			'colorblocks'	=> 'false'
		), $atts );
		
		if($a['colorblocks'] === 'true') { wp_enqueue_script('sk-filter-color-script'); }

	?>
		<div id="sk-filter-container" data-type="<?php echo $a['type']; ?>" data-colorfilter="<?php echo $a['colorblocks']; ?>"></div>
	<?php
		 

	} else {
		echo "<p>sk_filter shortcode not enabled</p>";
	}

	return ob_get_clean();
}
add_shortcode('sk_filter', 'sk_filter_shortcode');