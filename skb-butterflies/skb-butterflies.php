<?php

function skb_ap_butterflies_shortcode($atts) {
	global $skb_options;

	$a = shortcode_atts( array(
		'sort' 		=> 'ASC',
		'sort_on'	=> 'Common Name'
		), $atts );

	ob_start();

	if($skb_options['skb_enable_butterflies'] === "true") {
		$sort = strtoupper($a['sort']);
		$sort_key = ucwords( strtolower($a['sort_on']) );

		if($sort_key !== "Common Name" && $sort_key !== "Genus" && $sort_key !== "Species") {
			$sort_key = "Common Name";
		}

		wp_enqueue_style('skb-butterflies-styles');

		$root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

		$my_airpress_connection_id_or_name = "appm9rCl4jFRtRASj";

		$query = new AirpressQuery("Butterflies", $my_airpress_connection_id_or_name);

		// Optionally specify View
		$query->view("Main View");

		// This will ensure that you can use shortcodes on the homepage still
		// such as [apr field="Slug"]
		$list = new AirpressCollection($query);

		$butterflies = (array)$list;
		// foreach($list as $butterfly) {
		// 	array_push($butterflies, $butterfly);
		// }

		if($sort === "ASC") {
			if( $sort_key === "Common Name" ) {
				usort($butterflies, function($a, $b) {
					return strcasecmp($a["Common Name"],$b["Common Name"]);
				});
			} elseif( $sort_key === "Genus" ) {
				usort($butterflies, function($a, $b) {
					return strcasecmp($a["Genus"],$b["Genus"]);
				});
			} elseif( $sort_key === "Species" ) {
				usort($butterflies, function($a, $b) {
					return strcasecmp($a["Species"],$b["Species"]);
				});
			}

		} elseif($sort === "DESC") {
			if( $sort_key === "Common Name" ) {
				usort($butterflies, function($a, $b) {
					return strcasecmp($b["Common Name"],$a["Common Name"]);
				});
			} elseif( $sort_key === "Genus" ) {
				usort($butterflies, function($a, $b) {
					return strcasecmp($b["Genus"],$a["Genus"]);
				});
			} elseif( $sort_key === "Species" ) {
				usort($butterflies, function($a, $b) {
					return strcasecmp($b["Species"],$a["Species"]);
				});
			}
		}
		
		echo "<div id='skb-butterflies-gallery' class='masonry'>";
		foreach($butterflies as $butterfly) {
			if( isset($butterfly['Photo'][0]['url']) && $butterfly['Photo'][0]["url"] !== "" ) {
				$genus = isset($butterfly['Genus']) ? strtolower($butterfly["Genus"]) : "";
				$region = isset($butterfly['Region']) ? strtolower($butterfly["Region"]) : "";
				$markings = isset($butterfly['Markings']) ? strtolower(htmlentities($butterfly["Markings"])) : "";
				$shape = isset($butterfly['Shape']) ? strtolower(htmlentities($butterfly["Shape"])) : "";

				$colors = isset($butterfly['Colors']) ? $butterfly['Colors'] : "";
				if( substr($colors, 0, strlen($colors) - 1) === "," ) {
					$colors = substr($colors, 0, strlen($colors) - 2);
				}

				$data_colors = strtolower($colors);

				?>
					<div class="card skb-filter-item" data-colors="<?php echo $data_colors; ?>" data-region="<?php echo $region; ?>" data-markings="<?php echo $markings; ?>" data-shape="<?php echo $shape; ?>" data-genus="<?php echo $genus; ?>">
						<a href="<?php echo $root .'butterflies/'. $butterfly['id']; ?>">
							<figure class="ap-list-item">
								<?php if(isset($butterfly['Photo'][0]["url"])) : ?>
									<img src="<?php echo $butterfly['Photo'][0]["url"]; ?>" alt="<?php echo $butterfly['Common Name'] .' Photo'; ?>">
								<?php endif; ?>
								<figcaption class='skb-butterfly-details'>
									<?php if(isset($butterfly['Common Name'])) { echo "<p><strong>Common Name(s):</strong> ". ucwords($butterfly['Common Name']) ."</p>"; } ?>
									<?php if(isset($butterfly['Region'])) { echo "<p><strong>Region</strong>: ". $butterfly['Region'] ."</p>"; } ?>
									<?php if(isset($butterfly['Genus']) && isset($butterfly['Species'])) { echo "<p><strong>Genus:</strong> {$butterfly['Genus']}<br><strong>Species:</strong> {$butterfly['Species']}"; } ?>
								</figcaption>
							</figure>
						</a>
					</div>
				<?php
			}
		} // end foreach $list
		echo "</div>";
		
	} else {
		echo "<p>SKB-Butterflies shortcode is not enabled.</p>";
	}

	return ob_get_clean();
}
add_shortcode('skb_butterflies', 'skb_ap_butterflies_shortcode');