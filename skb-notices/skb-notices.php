<?php

function skb_notices_shortcode($atts) {
	global $skb_options;

	ob_start();
	
	if($skb_options['skb_enable_notices'] === 'true') {
		wp_enqueue_style('skb-notices-styles');

		$a = shortcode_atts( array(
			'show_date'			=> 'true',
			'date_location'	=> 'before',
			'date_bold'			=> 'false',
			'center'				=> 'false',
			'font_size'			=> 'normal',
			'new_line'			=> 'true',
			'date_format'		=> $skb_options['skb-n-default_date_format'], // e.g. Tuesday, October 29, 2019
			'message'				=> $skb_options['skb-n-default_message'],
			'type'					=> $skb_options['skb-n-default_message_type']
		), $atts );

		if($a['date_location'] == 'first') { $a['date_location'] = 'before'; }
		if($a['date_location'] == 'last') { $a['date_location'] = 'after'; }

		?>
<div class="skb-notice skb-notice--<?php echo $a['type']; if($a['center'] == 'true') { echo " skb-notice--centered"; }; echo " skb-notice--font-{$a['font_size']}"; ?>">
<?php
	$bold = "";
	if($a['date_bold'] == 'true') { $bold = "skb-notice--date-bold"; }

	if($a['show_date'] == "true" && $a['date_location'] === "before") {
		echo "<span class='skb-notice--date $bold'>". date($a['date_format']) ."</span>";

		if($a['new_line'] == 'true') { echo "<br/>"; } else { echo "&nbsp;"; }
	}

	echo "<span class='skb-notice--message'>". $a['message'] ."</span>";

	if($a['show_date'] == "true" && $a['date_location'] === "after") {
		if($a['new_line'] == 'true') { echo "<br/>"; } else { echo "&nbsp;"; } 

		echo "<span class='skb-notice-date $bold'>". date($a['date_format']) ."</span>"; 
	}
?>
</div>
		<?php
	} else {
		echo "<p>skb_notices shortcode not enabled</p>";
	} // end if skb_enable_directory check

	return ob_get_clean();
}
add_shortcode('skb_notice', 'skb_notices_shortcode');