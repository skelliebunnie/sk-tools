<?php

function skb_airtable_shortcode($atts) {
	global $skb_options;

	ob_start();

	if($skb_options['skb_enable_airtable'] === 'true') {
		wp_enqueue_script( 'skb-airtable-api-script' );
		//wp_enqueue_script( 'skb-airtable-init-script' );

		$a = shortcode_atts( array(
			'table' => 'butterflies',

		), $atts );

		$conn = new SKB_AirtableConnection();

		$results = $conn->getResults();

		echo "<pre>";
		var_dump($results);
		echo "</pre>";

	} else {
		echo "<p>skb_airtable shortcode not enabled</p>";
	} // end if skb_enable_airtable check

	return ob_get_clean();
}
add_shortcode('skb_airtable', 'skb_airtable_shortcode');