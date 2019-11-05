<?php

function skb_filter_shortcode($atts) {
	global $skb_options;

	ob_start();

	if($skb_options['skb_enable_filter'] === 'true') {
		wp_enqueue_style('skb-filters-styles');

		wp_enqueue_script('skb-functions-script');
		wp_enqueue_script('skb-filter-script');

		// types are: 
		//	* default ( 1 filter at a time )
		//	* additive / add ( select 1+ filter, across lists; results are 1+ match )
		// 	* subtractive / sub ( select 1+ filter; results must match ALL selected )
		$a = shortcode_atts( array(
			'type'				=> 'default',
			'colorblocks'	=> 'false'
		), $atts );
		
		if($a['colorblocks'] === 'true') { wp_enqueue_script('skb-filter-color-script'); }

	?>
		<div id="skb-filter-container" data-type="<?php echo $a['type']; ?>" data-colorfilter="<?php echo $a['colorblocks']; ?>"></div>
	<?php
		 

	} else {
		echo "<p>skb_filter shortcode not enabled</p>";
	}

	return ob_get_clean();
}
add_shortcode('skb_filter', 'skb_filter_shortcode');