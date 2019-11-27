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
			'on'						=> null,
			'off'						=> null,
			'debug'					=> 'false'
		), $atts );

		if($a['date_location'] == 'first') { $a['date_location'] = 'before'; }
		if($a['date_location'] == 'last') { $a['date_location'] = 'after'; }
		if($a['center_text'] != 'false') { $a['center'] = $a['center_text']; }

		$a['schedule'] = strtolower($a['schedule']);

		$schedule = explode(",", $a['schedule']);

		$weekday = strtolower(date('l'));
		$short_weekday = strtolower(date('D'));
		$today = date('Y-m-d');

		$days = array('mon','tue','wed','thu','fri','sat','sun');
		$weekdays = $skb_options['skb-n-default_weekdays'];
		$weekends = array_diff($days, $weekdays);

		$show_notice = false;

		if( in_array($weekday, $schedule) || in_array($short_weekday, $schedule) || in_array('every day', $schedule) || (in_array('weekdays', $schedule) && in_array($short_weekday, $weekdays)) || in_array('weekends', $schedule) && in_array($short_weekday, $weekends) ) { 
			$show_notice = true; 

		} elseif( (strpos($schedule[0], "/") > 0 || strpos($schedule[0], "-") > 0) && is_numeric($schedule[0][0]) ) {

			foreach($schedule as $dt) {
				$date = skb_parse_date($dt);

				if( (count($schedule) === 1 && $today <= $date) || count($schedule) > 1 && $today === $date ) {
					$show_notice = true;
				}
			}
			
		} elseif( strpos($schedule[0], "-") > 0 && in_array(substr($schedule[0], 0, 3), $days) ) {
			$days_schedule = explode("-", $schedule[0]);
			
			$first_day = $days_schedule[0];
			$last_day = $days_schedule[1];

			$days_list = array();

			echo "First Day: $first_day<br>";
			echo "Last Day: $last_day<br>";
			// echo "All Days: ". implode(", ", $between_days) ."<br>";
		}

		// this way, you can schedule every Monday / Tuesday but only until <date>
		if($a['end_schedule'] !== 'never') {
			$dt = skb_parse_date($a['end_schedule']);
			
			if($dt < $today) { $show_notice = false; }
		}

		if($a['on'] !== null && $a['on'] !== "") {
			$on = explode(",", $a['on']);
			foreach($on as $date) {
				$date = skb_parse_date($date);

				$d = date('Y-m-d', strtotime($date));

				if($d === $today) {
					$show_notice = true;
				}
			}
		}

		if($a['off'] !== null && $a['off'] !== "") {
			$off = explode(",", $a['off']);
			foreach($off as $date) {
				$date = skb_parse_date($date);

				$d = date('Y-m-d', strtotime($date));

				if($d === $today) {
					$show_notice = false;
				}
			}
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

function skb_parse_date($date) {
	if( strpos($date, "/") > 0 ) {
		$x = explode("/", $date);
		$m = $x[0];
		$d = $x[1];
		$y = $x[2];

		if(strpos($m, "0") !== 0 && (int)$m < 10) { $d = "0{$m}"; }
		if(strpos($d, "0") !== 0 && (int)$d < 10) { $d = "0{$d}"; }

		$date = "{$y}-{$m}-{$d}";
	}

	return $date;
}