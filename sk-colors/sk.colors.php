<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_color_shortcode($atts) {
	global $sk_options;

	// wp_enqueue_style('sk-colors-styles');

	// if using name:color format, name *must* be first
	// # not required for hex; % not required for hsl[a]
	$a = shortcode_atts( array(
		'color'		=> '', // #ff0000, rgb[a](0,255,0[, 1]), hsl(240,100%,50%[, 1]), cmyk(0,0,100,0)
		'output'	=> 'hex' // hex, rgb(a), hsl, cmyk, all
	), $atts );

	ob_start();

	$colorsArray = array();
	
	$colors = preg_split("/[\;\|\/]+/", $a['color']);

	foreach($colors as $color) {
		$clr = array('hex' => '', 'rgb' => '', 'rgba' => '', 'hsla' => '', 'hsl' => '', 'name' => '');

		$color = explode(":", $color);
		var_dump($color);
		echo "<br>";

		if( count($color) === 2 ) {
			$clr['name'] = $color[0];
			$clr_index = 1;

		} else {
			$clr_index = 0;

		}
		$clr_alpha = "1";

		if( strpos($color[$clr_index], "rgba") !== false ) { 
			$clr['rgba'] = $color[$clr_index];
			$clr['rgb'] = preg_replace("/,(\s*\d*)\.*(\d*)\)/i", ")", $clr['rgba']);

			preg_match("/(\s*\d*)\.*(\d*)\)/i", $clr['hsla'], $alpha);
			$clr_alpha = substr($alpha[0], 0, strlen($alpha[0]) - 1);

		} elseif( strpos($color[$clr_index], "rgba") === false && strpos($color[$clr_index], "rgb") !== false ) { 
			$clr['rgb'] = $color[$clr_index];
			$clr['rgba'] = str_replace(")", ", 1)", $color[$clr_index]);

		} elseif( strpos($color[$clr_index], "hsla") !== false ) { 
			$clr['hsla'] = $color[$clr_index];
			$clr['hsl'] = preg_replace("/,(\s*\d*)\.*(\d*)\)/i", ")", $clr['hsla']);
			$clr['hsl'] = str_replace("hsla", "hsl", $clr['hsl']);

			preg_match("/(\s*\d*)\.*(\d*)\)/i", $clr['hsla'], $alpha);
			$clr_alpha = substr($alpha[0], 0, strlen($alpha[0]) - 1);

		} elseif( strpos($color[$clr_index], "hsla") === false && strpos($color[$clr_index], "hsl") !== false ) { 
			$clr['hsl'] = $color[$clr_index];
			$clr['hsla'] = str_replace(")", ", 1)", $color[$clr_index]);

		} else {
			$clr['hex'] = $color[$clr_index];

			if( strpos($clr['hex'], "#") === 0 ) { $clr['hex'] = substr($clr['hex'], 1); }
			// if( strlen($clr['hex']) === 8 ) { $clr_alpha =  }

			echo "<hr>";
			var_dump(hexToDec($clr['hex']));
			echo "<hr>";
		}

	}

	return ob_get_clean();
}
add_shortcode('sk_color', 'sk_color_shortcode');