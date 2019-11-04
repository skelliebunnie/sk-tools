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
			'type'					=> $skb_options['skb-n-default_message_type'],
			'schedule'			=> 'every day'
		), $atts );

		if($a['date_location'] == 'first') { $a['date_location'] = 'before'; }
		if($a['date_location'] == 'last') { $a['date_location'] = 'after'; }

		$schedule = (strpos($a['schedule'], "/") === -1 && strpos($a['schedule'], "-") === -1) ? array(ucfirst(strtolower($a['schedule']))) : $a['schedule'];

		$weekday = date('l');

		$show_notice = false;

		if( is_array($schedule) && (in_array($weekday, $schedule) || in_array('Every day', $schedule) || in_array('every day', $schedule)) || $schedule === "every day" ) { $show_notice = true; }

		if( !is_array($schedule) && strpos($schedule, "/") ) {
			$dt = date('Y-m-d', strtotime($schedule));

			if($dt < date('Y-m-d')) {
				$show_notice = true;
			}
		}

		if($show_notice) :
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
	endif; // end if($show_notice)
	} else {
		echo "<p>skb_notices shortcode not enabled</p>";
	} // end if skb_enable_directory check

	return ob_get_clean();
}
add_shortcode('skb_notice', 'skb_notices_shortcode');