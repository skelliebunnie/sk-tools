<?php

if( !defined( 'ABSPATH' ) ) { exit; }

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_datetime_shortcode($atts) {
	global $sk_options;

	ob_start();

	if($sk_options['sk_enable_datetime'] === 'true') {
		$a = shortcode_atts( array(
			'date'						=> 'today',
			'time'						=> 'now',
			'date_format'			=> 'default',
			'time_format'			=> 'default',
			'order'						=> 'date,time',
			'separator'				=> '&nbsp;',
			'bold_date'				=> 'false',
			'bold_time'				=> 'false',
			'bold_all'				=> 'false',
			'modify'					=> '0'
		), $atts );

		$order = explode(",", $a['order']);

		$date_format = $sk_options['sk-dt-default_date_format'];
		$time_format = $sk_options['sk-dt-default_time_format'];

		$today = date($date_format);
		$now = date($time_format);

		$date = $a['date']; $time = $a['time'];
		$dateTime_date = new DateTime; $dateTime_time = new DateTime;

		if($a['date_format'] !== 'default' && $a['date_format'] != $date_format) {
			$date_format = $a['date_format'];
		}

		if($a['time_format'] !== 'default' && $a['time_format'] != $time_format) {
			$time_format = $a['time_format'];
		}

		if($a['date'] != 'today' && $a['date'] != 'false') {
			$date = date($date_format, strtotime($a['date']));

		} elseif($a['date'] != 'false' && $a['date'] == 'today') {
			$date = $today;

		} else {
			$date = NULL;

		}

		if($a['time'] != 'now' && $a['time'] != 'today' && $a['time'] != 'false') {
			$time = date($time_format, strtotime($a['time']));

		} elseif($a['time']!= 'false' && $a['time'] == 'now') {
			$time = $now;

		} else {
			$time = NULL;
		}

		$full_datetime = $a['time'] != "false" ? $date ." ". $time : null;

		if( $a['modify'] != "0" ) {
			$dt_date = null; $dt_time = null;
			if($a['date'] === 'today') {
				$dt_date = date('Y-m-d');
			} elseif($a['date'] != 'false') {
				$dt_date = date('Y-m-d', strtotime($a['date']));
			} else {
				$dt_date = "1970-01-01";
			}

			if($a['date'] === 'today') {
				$dt_time = date('H:i:s');
			} elseif($a['time'] != 'false') {
				$dt_time = date('H:i:s', strtotime($a['time']));
			} else {
				$dt_time = "00:00:00";
			}

			$full_datetime = "{$dt_date}T{$dt_date}Z";

			$dt = new DateTime( strtotime($full_datetime) );
			$dt_date = $dateTime_date->modify("{$a['modify']}");
			$dt_time = $dateTime_time->modify("{$a['modify']}");
			$date = $dt_date->format($date_format);
			$time = $dt_time->format($time_format);
		}

		$separator = $a['separator'];
		if($separator != '' && $separator != '&nbsp;') {
			$separator = str_pad($separator, strlen($separator) + 2, " ", STR_PAD_BOTH);
		}

		if($a['bold_date'] == 'true' && $date !== NULL) { $date = "<strong>{$date}</strong>"; }
		if($a['bold_time'] == 'true' && $time !== NULL) { $time = "<strong>{$time}</strong>"; }

		$content = "";
		if($date !== NULL && $time !== NULL) {
			if($order[0] === 'date') {
				$content .= "{$date}{$separator}{$time}";

			} else {
				$content .= "{$time}{$separator}{$date}";

			}
		} elseif($date !== NULL) {
			$content .= "{$date}";

		} elseif($time !== NULL) {
			$content .= "{$time}";
		}

		if($a['bold_all'] == 'true') { $content = "<strong>{$content}</strong>"; }

		echo $content;

	} else {
		echo "<p>sk_datetime shortcode not enabled</p>";
	}

	return ob_get_clean();
}
add_shortcode('sk_datetime', 'sk_datetime_shortcode');