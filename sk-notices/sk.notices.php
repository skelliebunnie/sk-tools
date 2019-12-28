<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_notices_shortcode($atts) {
	global $sk_options;

	ob_start();
	
	if($sk_options['sk_enable_notices'] === 'true') {
		wp_enqueue_style('sk-notices-styles');

		$a = shortcode_atts( array(
			'show_date'			=> 'true',
			'date_location'	=> 'before',
			'date_bold'			=> 'false',
			'date_large'		=> 'false',
			'center_text'		=> 'false',
			'center'				=> 'false',
			'font_size'			=> 'normal',
			'new_line'			=> 'true',
			'date_format'		=> $sk_options['sk-n-default_date_format'], // e.g. Tuesday, October 29, 2019
			'message'				=> $sk_options['sk-n-default_message'],
			'type'					=> $sk_options['sk-n-default_message_type'],
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

		$today = date('Y-m-d');
		$today_day_name = strtolower(date('l'));
		$today_short_day_name = strtolower(date('D'));

		$days = array('mon','tue','wed','thu','fri','sat','sun');
		$weekdays = $sk_options['sk-n-default_weekdays'];
		$weekends = array_diff($days, $weekdays);

		$show_notice = false;

		if( in_array($today_day_name, $schedule) || in_array($today_short_day_name, $schedule) || in_array('every day', $schedule) || (in_array('weekdays', $schedule) && in_array($today_short_day_name, $weekdays)) || in_array('weekends', $schedule) && in_array($today_short_day_name, $weekends) ) { 
			$show_notice = true; 

		} elseif( strpos($schedule[0], "/") > 0 && strpos($schedule[0], "-") > 0 ) {

			$schedule_dates = array();
			foreach($schedule as $s) {
				$dates = explode("-", $s);

				$start_date = sk_parse_date($dates[0]);
				$end_date = sk_parse_date($dates[1]);

				array_push($schedule_dates, $start_date);
				array_push($schedule_dates, $end_date);	

				$start = date_create($start_date);
				$end = date_create($end_date);
				$end = $end->modify("+1 day");
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($start, $interval, $end);

				foreach($period as $dt) {
					array_push($schedule_dates, $dt->format('Y-m-d'));
				}
			}

			if( in_array($today, $schedule_dates) ) {
				$show_notice = true;
			}
			
		} elseif( (strpos($schedule[0], "/") > 0 || strpos($schedule[0], "-") > 0) && is_numeric($schedule[0][0]) ) {

			foreach($schedule as $dt) {
				$date = sk_parse_date($dt);

				if( (count($schedule) === 1 && $today <= $date) || count($schedule) > 1 && $today === $date ) {
					$show_notice = true;
				}
			}

		} elseif( strpos($schedule[0], "-") > 0 && in_array(substr($schedule[0], 0, 3), $days) ) {

			$days_schedule = explode("-", $schedule[0]);
			
			$first_day = $days_schedule[0];
			$last_day = $days_schedule[1];

			$days_list = array(); $first_index = 0; $last_index = 6;
			foreach($days as $index=>$day) {

				if( $day === $first_day ) {
					$first_index = $index;
					array_push($days_list, $day);

				} elseif( $day === $last_day ) {
					$last_index = $index;
					array_push($days_list, $day);

				} elseif( $index > $first_index && $index < $last_index && $day !== $last_day && $day !== $first_day ) {
					array_push($days_list, $day);
				}
			}

			if( in_array($today_short_day_name, $days_list) || in_array($today_day_name, $days_list) ) {
				$show_notice = true;
			}
		}

		// this way, you can schedule every Monday / Tuesday but only until <date>
		if($a['end_schedule'] !== 'never') {
			$dt = sk_parse_date($a['end_schedule']);
			
			if($dt < $today) { $show_notice = false; }
		}

		if($a['on'] !== null && $a['on'] !== "") {
			$on = explode(",", $a['on']);
			foreach($on as $date) {
				$date = sk_parse_date($date);

				$d = date('Y-m-d', strtotime($date));

				if($d === $today) {
					$show_notice = true;
				}
			}
		}

		if($a['off'] !== null && $a['off'] !== "") {
			$off = explode(",", $a['off']);
			foreach($off as $date) {
				$date = sk_parse_date($date);

				$d = date('Y-m-d', strtotime($date));

				if($d === $today) {
					$show_notice = false;
				}
			}
		}

		if($show_notice) {
		?>
<div class="sk-notice sk-notice--<?php echo $a['type']; if($a['center'] == 'true') { echo " sk-notice--centered"; }; echo " sk-notice--font-{$a['font_size']}"; ?>">
<?php
	$bold = ""; $date_large = "";
	if($a['date_bold'] == 'true') { $bold = "sk-notice--date-bold"; }
	if($a['date_large'] == 'true') { $date_large = "sk-notice--date-large"; }

	if($a['show_date'] == "true" && $a['date_location'] === "before") {
		echo "<span class='sk-notice--date $bold $date_large'>". date($a['date_format']) ."</span>";

		if($a['new_line'] == 'true') { echo "<br/>"; } else { echo "&nbsp;"; }
	}

	echo "<span class='sk-notice--message'>". $a['message'] ."</span>";

	if($a['show_date'] == "true" && $a['date_location'] === "after") {
		if($a['new_line'] == 'true') { echo "<br/>"; } else { echo "&nbsp;"; } 

		echo "<span class='sk-notice-date $bold $date_large'>". date($a['date_format']) ."</span>"; 
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
		echo "<p>sk_notices shortcode not enabled</p>";
	} // end if sk_enable_notices check

	return ob_get_clean();
}
add_shortcode('sk_notice', 'sk_notices_shortcode');

function sk_parse_date($date) {
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