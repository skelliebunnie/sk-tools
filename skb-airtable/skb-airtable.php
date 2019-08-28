<?php

function skb_airtable_shortcode($atts) {

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

	ob_start();
	


	return ob_get_clean();
}
add_shortcode('skb_airtable', 'skb_airtable_shortcode');