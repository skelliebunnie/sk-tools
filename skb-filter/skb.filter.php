<?php

function skb_filter_shortcode($atts) {
	wp_enqueue_style('skb-filters-style');

	// types are: 
	//	* default ( 1 filter at a time )
	//	* additive / add ( select 1+ filter, across lists; results are 1+ match )
	// 	* subtractive / sub ( select 1+ filter; results must match ALL selected )
	$a = shortcode_atts( array(
		'type'				=> 'default',
		'colorblocks'	=> 'true'
	), $atts );

	wp_enqueue_script('skb-filter-script');
	
	if($a['colorblocks'] === 'true') { wp_enqueue_script('skb-filter-color-script'); }

	ob_start();
?>
	<div id="skb-filter-container" data-type="<?php echo $a['type']; ?>" data-colorfilter="<?php echo $a['colorblocks']; ?>"></div>
<?php
	 return ob_get_clean();
}
add_shortcode('skb_filter', 'skb_filter_shortcode');