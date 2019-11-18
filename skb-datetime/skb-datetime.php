<?php

function skb_datetime_shortcode($atts) {
	global $skb_options;

	ob_start();

	if($skb_options['skb_enable_datetime'] === 'true') {
		$a = shortcode_atts( array(
			'date'						=> 'today',
			'time'						=> 'now',
			'date_format'			=> 'default',
			'time_format'			=> 'default',
			'order'						=> 'date,time',
			'separator'				=> '&nbsp;',
			'bold_date'				=> 'false',
			'bold_time'				=> 'false',
			'bold_all'				=> 'false'
		), $atts );

		$order = explode(",", $a['order']);

		$date_format = $skb_options['skb-dt-default_date_format'];
		$time_format = $skb_options['skb-dt-default_time_format'];

		if($a['date_format'] !== 'default' && $a['date_format'] != $date_format) {
			$date_format = $a['date_format'];
		}

		if($a['time_format'] !== 'default' && $a['time_format'] != $time_format) {
			$time_format = $a['time_format'];
		}

		$date = date($date_format);
		$time = date($time_format);

		if($a['date'] != 'today') {
			$date = date($date_format, strtotime($a['date']));
		}

		if($a['time'] != 'today') {
			$date = date($date_format, strtotime($a['time']));
		}

		$separator = $a['separator'];
		if($separator != '' && $separator != '&nbsp;') {
			$separator = str_pad($separator, strlen($separator) + 2, " ", STR_PAD_BOTH);
		}

		if($a['bold_date'] == 'true') { $date = "<strong>{$date}</strong>"; }
		if($a['bold_time'] == 'true') { $time = "<strong>{$time}</strong>"; }

		$content = "";
		if($date != 'false' && $time != 'false') {
			if($order[0] === 'date') {
				$content .= "{$date}{$separator}{$time}";

			} else {
				$content .= "{$time}{$separator}{$date}";

			}
		} elseif($date != 'false') {
			$content .= "{$date}";

		} elseif($time != 'false') {
			$content .= "{$time}";
		}

		if($a['bold_all'] == 'true') { $content = "<strong>{$content}</strong>"; }

		echo $content;

	} else {
		echo "<p>skb_datetime shortcode not enabled</p>";
	}

	return ob_get_clean();
}
add_shortcode('skb_datetime', 'skb_datetime_shortcode');