<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_notices_shortcode($atts, $content = null, $tag = '') {
	date_default_timezone_set('America/Los_Angeles');

	$sk_admin_options = get_option('sk_admin_options');
	
	if($sk_admin_options['enable_notices'] === 'true') {
		$sk_notice_options = get_option('sk_notice_options');

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
			'date_format'		=> $sk_notice_options['default_date_format'], // e.g. Tuesday, October 29, 2019
			'type'					=> $sk_notice_options['default_message_type'],
			'schedule'			=> 'every day',
			'end_schedule'	=> 'never',
			'on'						=> null,
			'off'						=> null,
			'debug'					=> 'false',
			'margin'				=> 'true',
			'overlay'				=> 'false',
			'transparent'		=> 'false'
		), $atts );

		$output = "";

		$allowed_html = array(
	    'a' => array(
	        'href' => array(),
	        'title' => array(),
	        'target' => array()
	    ),
	    'br' => array(),
	    'em' => array(),
	    'strong' => array(),
	    'h1' => array(),
	    'h2' => array(),
	    'h3' => array(),
	    'h4' => array(),
	    'h5' => array(),
	    'h6' => array(),
	    'p' => array(),
	    'span' => array(),
		);

		if($a['date_location'] == 'first') { $a['date_location'] = 'before'; }
		if($a['date_location'] == 'last') { $a['date_location'] = 'after'; }
		if($a['center_text'] != 'false') { $a['center'] = $a['center_text']; }

		$a['schedule'] = strtolower($a['schedule']);

		$schedule = explode(",", $a['schedule']);

		$today = date('Y-m-d');
		$today_day_name = strtolower(date('l'));
		$today_short_day_name = strtolower(date('D'));

		$days = array('mon','tue','wed','thu','fri','sat','sun');
		$weekdays = $sk_notice_options['default_weekdays'];
		$weekends = array_diff($days, $weekdays);

		$show_notice = false;

		if( in_array($today_day_name, $schedule) || in_array($today_short_day_name, $schedule) || in_array('every day', $schedule) || (in_array('weekdays', $schedule) && in_array($today_short_day_name, $weekdays)) || (in_array('weekends', $schedule) && in_array($today_short_day_name, $weekends)) ) { 
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
			$margin = $a['margin'] == 'true' ? '' : ' no-margin';
			$transparent = $a['transparent'] == 'true' ? ' transparent' : '';

			if($a['overlay'] == 'true')
				$output .= "<div class='sk-notice--overlay'>";

			$output .= "<div class='sk-notice sk-notice--'";
			$output .= $a['type'];

			if($a['center'] == 'true') { 
				$output .= " sk-notice--centered"; 
			}
			$output .= " sk-notice--font-{$a['font_size']}";
			$output .= $margin; 
			$output .= $transparent;

			$output .= ">"; // end opening div.sk-notice tag

			$bold = ""; $date_large = "";
			if($a['date_bold'] == 'true') { $bold = "sk-notice--date-bold"; }
			if($a['date_large'] == 'true') { $date_large = "sk-notice--date-large"; }

			if($a['show_date'] == "true" && $a['date_location'] === "before") {
				$output .= "<span class='sk-notice--date $bold $date_large'>". date($a['date_format']) ."</span>";

				if($a['new_line'] == 'true') { $output .= "<br/>"; } else { $output .= "&nbsp;"; }
			}

			$output .= "<span class='sk-notice--message'>". wp_kses( $content, $allowed_html ) ."</span>";

			if($a['show_date'] == "true" && $a['date_location'] === "after") {
				if($a['new_line'] == 'true') { $echo .= "<br/>"; } else { $echo .= "&nbsp;"; } 

				$output .= "<span class='sk-notice-date $bold $date_large'>". date($a['date_format']) ."</span>"; 
			}
	
		$output .= "</div>";

		if($a['overlay'] == 'true')
				$output .= "</div>";

		} elseif($a['debug'] === 'true') {
			$output .= "<p class='note'>This message is scheduled to show only ";
			if( substr($schedule[0], -3) == 'day' ) {
				$output .= "on ". implode(", ", $schedule);

			} elseif( count($schedule) > 1 ) {
				$last_index = count($schedule) - 1;
				$penultimate_index = count($schedule) - 2;
				$output .= "on ";

				foreach($schedule as $i=>$dt) {
					echo date('l, F j, Y', strtotime($dt));
					if($i === $penultimate_index) {
						$output .= " & ";
					} elseif($i !== $penultimate_index && $i !== $last_index) {
						$output .= ", ";
					}
				}

				$output .= "</p>";
			} elseif( count($schedule) === 1 ) {
				$output .= "through ". date('l, F j, Y', strtotime($schedule[0]));

			}
		}

	} else {
		$output .= "<p>sk_notices shortcode not enabled</p>";
	} // end if sk_enable_notices check

	return $output;
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