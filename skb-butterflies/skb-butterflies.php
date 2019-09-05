<?php

function skb_ap_butterflies_shortcode($atts) {
	global $skb_options;

	$butterflies_conn = new SKB_AirtableConnection();
	$skb_butterflies = $butterflies_conn->getAllRecords(true);

	// echo "<pre>";
	// var_dump($skb_butterflies);
	// echo "</pre>";

	$a = shortcode_atts( array(
		'sort' 				=> 'ASC',
		'photo_only'	=> 'true'
	), $atts );

	$sort = $a['sort'];
	$sort_key = "Common Name";

	ob_start();

	if($skb_options['skb_enable_butterflies'] === "true") {

		wp_enqueue_style('skb-butterflies-styles');

		// SORTING
		if($sort === "ASC") {
			if( $sort_key === "Common Name" ) {
				usort($skb_butterflies, function($a, $b) {
					return strcasecmp($a["Common Name"],$b["Common Name"]);
				});
			} elseif( $sort_key === "Genus" ) {
				usort($skb_butterflies, function($a, $b) {
					return strcasecmp($a["Genus"],$b["Genus"]);
				});
			} elseif( $sort_key === "Species" ) {
				usort($skb_butterflies, function($a, $b) {
					return strcasecmp($a["Species"],$b["Species"]);
				});
			}

		} elseif($sort === "DESC") {
			if( $sort_key === "Common Name" ) {
				usort($skb_butterflies, function($a, $b) {
					return strcasecmp($b["Common Name"],$a["Common Name"]);
				});
			} elseif( $sort_key === "Genus" ) {
				usort($skb_butterflies, function($a, $b) {
					return strcasecmp($b["Genus"],$a["Genus"]);
				});
			} elseif( $sort_key === "Species" ) {
				usort($skb_butterflies, function($a, $b) {
					return strcasecmp($b["Species"],$a["Species"]);
				});
			}
		}
		
		echo "<div id='skb-butterflies-gallery' class='masonry'>";
		foreach($skb_butterflies as $butterfly) :
			if(($a['photo_only'] === "true" && $butterfly['Thumbnail'] !== "") || $a['photo_only'] !== "true") :
		?>
		<div class="card skb-filter-item" data-colors="<?php echo $butterfly['Colors']; ?>" data-region="<?php echo $butterfly['Region']; ?>" data-genus="<?php echo $butterfly['Genus']; ?>" data-markings="<?php echo $butterfly['Markings']; ?>" data-shape="<?php echo $butterfly['Shape']; ?>">
			<a href="<?php echo SKB_SITE_URL ."butterflies/". $butterfly['slug']; ?>">
				<figure>
					<img src="<?php echo $butterfly['Thumbnail']; ?>" alt="<?php echo $butterfly['Common Name']; ?>">
					<figcaption>
						<p class="common-name"><strong><?php echo $butterfly['Common Name']; ?></strong><br>
						<span class="genus-species"><em><?php echo $butterfly['Genus'] ." ". $butterfly['Species']; ?></em></span></p>
						<p class="region"><strong>Region:</strong> <?php echo $butterfly['Region']; ?></p>
					</figcaption>
				</figure>
			</a>
		</div>
		<?php
			endif;
		endforeach;
		echo "</div>";

	} else {
		echo "<p>SKB-Butterflies shortcode is not enabled.</p>";
	}

	return ob_get_clean();
}
add_shortcode('skb_butterflies', 'skb_ap_butterflies_shortcode');

/**
 * Add a string to post content
 *
 * @param  string $content
 * @param  string $string This is $param2 in our example.
 * @return string
 */
// function skb_butter( $content, $string ) {
//     return "$content <p><b>$string</b></p>";
// }

// $param1 = '<p>This works!</p>';

// add_filter( 'the_content', function( $content ) use ( $param1 ) {
//     return skb_butter( $content, $param1 );
// }, 12 );