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
			'center_text'		=> 'false',
			'center'				=> 'false',
			'font_size'			=> 'normal',
			'new_line'			=> 'true',
			'date_format'		=> $skb_options['skb-n-default_date_format'], // e.g. Tuesday, October 29, 2019
			'message'				=> $skb_options['skb-n-default_message'],
			'type'					=> $skb_options['skb-n-default_message_type'],
			'schedule'			=> 'every day',
			'end_schedule'	=> 'never',
			'debug'					=> 'false'
		), $atts );

		if($a['date_location'] == 'first') { $a['date_location'] = 'before'; }
		if($a['date_location'] == 'last') { $a['date_location'] = 'after'; }
		if($a['center_text'] != 'false') { $a['center'] = $a['center_text']; }

		$a['schedule'] = strtolower($a['schedule']);

		$schedule = explode(",", $a['schedule']);

		$weekday = strtolower(date('l'));
		$date = date('Y-m-d');

		$show_notice = false;

		if( in_array($weekday, $schedule) || in_array('every day', $schedule) ) { 
			$show_notice = true; 

		} elseif( strpos($schedule[0], "/") > 0 || strpos($schedule[0], "-") > 0 ) {

			foreach($schedule as $dt) {
				if( strpos($dt, "/") > 0 ) {
					$x = explode("/", $dt);
					$m = $x[0];
					$d = $x[1];
					$y = $x[2];

					if(strpos($m, "0") !== 0 && (int)$m < 10) { $d = "0{$m}"; }
					if(strpos($d, "0") !== 0 && (int)$d < 10) { $d = "0{$d}"; }

					$dt = "{$y}-{$m}-{$d}";
				}

				if( (count($schedule) === 1 && $date <= $dt) || count($schedule) > 1 && $date === $dt ) {
					$show_notice = true;
				}
			}
			
		}

		// this way, you can schedule every Monday / Tuesday but only until <date>
		if($a['end_schedule'] !== 'never') {
			$dt = $a['end_schedule'];
			if( strpos($dt, "/") > 0 ) {
				$x = explode("/", $dt);
				$m = $x[0];
				$d = $x[1];
				$y = $x[2];

				if(strpos($m, "0") !== 0 && (int)$m < 10) { $d = "0{$m}"; }
				if(strpos($d, "0") !== 0 && (int)$d < 10) { $d = "0{$d}"; }

				$dt = "{$y}-{$m}-{$d}";
			}

			if($dt < $date) { $show_notice = false; }
		}

		if($show_notice) {
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
		} elseif($a['debug'] === 'true') {
			echo "<p class='note'>This message is scheduled to show only ";
			if( substr($schedule[0], -3) == 'day' ) {
				echo "on ". implode(", ", $schedule);

			} elseif( count($schedule) > 1 ) {
				$last_index = count($schedule) - 1;
				$penultimate_index = count($schedule) - 2;
				echo "on ";

				foreach($schedule as $i=>$dt) {
					echo date('l, F j, Y', strtotime($dt));
					if($i === $penultimate_index) {
						echo " & ";
					} elseif($i !== $penultimate_index && $i !== $last_index) {
						echo ", ";
					}
				}

				echo "</p>";
			} elseif( count($schedule) === 1 ) {
				echo "through ". date('l, F j, Y', strtotime($schedule[0]));

			}
		}
	} else {
		echo "<p>skb_notices shortcode not enabled</p>";
	} // end if skb_enable_directory check

	return ob_get_clean();
}
add_shortcode('skb_notice', 'skb_notices_shortcode');