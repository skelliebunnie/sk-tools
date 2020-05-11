<?php

if( !defined( 'ABSPATH' ) ) { exit; }

class SK_Colors {
	public $functions;

	public function __construct() {
		$this->functions = new SK_Functions;

		add_shortcode('sk_color', array( $this, 'sk_color_shortcode' ));
	}

	public function sk_color_shortcode($atts) {
		global $sk_options;

		wp_enqueue_style('sk-colors-styles');

		// if using name:color format, name *must* be first
		// # not required for hex; % not required for hsl[a]
		$a = shortcode_atts( array(
			'color'		=> '', // #ff0000, rgb[a](0,255,0[, 1]), hsl(240,100%,50%[, 1]), cmyk(0,0,100,0)
			'output'	=> 'hex' // hex, rgb(a), hsl, cmyk, all
		), $atts );

		$list = translateColors($a['color']);

		ob_start();

		foreach($list as $color) {
			if( $a['output'] === 'hex' ) { echo "<span class='sk-color sk-color--hex'>{$color['hex']}</span>"; }
			if( $a['output'] === 'rgb' ) { echo "<span class='sk-color sk-color--rgb'>{$color['rgb']}</span>"; }
			if( $a['output'] === 'rgba' ) { echo "<span class='sk-color sk-color--rgba'>{$color['rgba']}</span>"; }
			if( $a['output'] === 'hsl' ) { echo "<span class='sk-color sk-color--hsl'>{$color['hsl']}</span>"; }
			if( $a['output'] === 'hsla' ) { echo "<span class='sk-color sk-color--hsla'>{$color['hsla']}</span>"; }
		}
		

		return ob_get_clean();
	}


	function translateColors($colors) {
		$colorsArray = array();
		
		$colors = preg_split("/[\;\|\/]+/", $colors);

		foreach($colors as $color) {
			$clr = array('hex' => '', 'rgb' => '', 'rgba' => '', 'hsla' => '', 'hsl' => '', 'alpha_dec' => '', 'alpha_hex' => '', 'name' => '');

			$color = explode(":", $color);

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

				$clr['alpha_hex'] = $this->functions->getAlpha($clr['rgba'])['hex'];
				$clr['alpha_dec'] = $this->functions->getAlpha($clr['rgba'])['dec'];

			} elseif( strpos($color[$clr_index], "rgba") === false && strpos($color[$clr_index], "rgb") !== false ) { 
				$clr['rgb'] = $color[$clr_index];
				$clr['rgba'] = str_replace(")", ", 1)", $color[$clr_index]);
				$clr['alpha_dec'] = 1;
				$clr['alpha_hex'] = 'FF';

			} elseif( strpos($color[$clr_index], "hsla") !== false ) { 
				$clr['hsla'] = $color[$clr_index];
				$clr['hsl'] = preg_replace("/,(\s*\d*)\.*(\d*)\)/i", ")", $clr['hsla']);
				$clr['hsl'] = str_replace("hsla", "hsl", $clr['hsl']);

				$clr['alpha_hex'] = $this->functions->getAlpha($clr['rgba'])['hex'];
				$clr['alpha_dec'] = $this->functions->getAlpha($clr['rgba'])['dec'];

				// preg_match("/(\s*\d*)\.*(\d*)\)/i", $clr['hsla'], $alpha);
				// $clr['alpha_dec'] = substr($alpha[0], 0, strlen($alpha[0]) - 1);

				// $alpha = $clr['alpha_dec'];
				// if( $clr['alpha_dec'] < 1 ) { $alpha = $alpha * 100; }
				// $clr['alpha_hex'] = dechex( $alpha );

			} elseif( strpos($color[$clr_index], "hsla") === false && strpos($color[$clr_index], "hsl") !== false ) { 
				$clr['hsl'] = $color[$clr_index];
				$clr['hsla'] = str_replace(")", ", 1)", $color[$clr_index]);
				$clr['alpha_dec'] = 1;
				$clr['alpha_hex'] = 'FF';

			} else {
				$clr['hex'] = strtoupper($color[$clr_index]);

				if( strpos($clr['hex'], "#") === 0 ) { $clr['hex'] = substr($clr['hex'], 1); }
				if( strlen($clr['hex']) === 8 ) { 
					$clr['alpha_hex'] = substr($clr['hex'], 6, 2);

					$clr['alpha_dec'] = hexdec( $clr['alpha_hex'] );
					$clr['hex'] = substr($clr['hex'],0,6); 

				} else {
					$clr['alpha_dec'] = 1;
					$clr['alpha_hex'] = 'FF';

				}

			}

			if( $clr['hex'] === '' && $clr['rgb'] !== '' ) {
				$clr['hex'] = rgbToHex( $clr['rgb'] );

			} elseif( $clr['hex'] === '' && $clr['hsl'] !== '' ) {
				$clr['hex'] = hslToHex($clr['hsl']);

			}

			if($clr['hex'] !== '') {

				if( $clr['rgb'] === '' ) { $clr['rgb'] = hexToRGB($clr['hex']); }
			
				if( $clr['rgba'] === '' ) { 
					$clr['rgba'] = hexToRGB($clr['hex']); 
					$clr['rgba'] = str_replace(")", ", ", $clr['alpha_dec'] .")", $clr['rgba']); 

				}

				if( $clr['hsl'] === '' ) { $clr['hsl'] = hexToHSL($clr['hex']); }

				if( $clr['hsla'] === '' ) {
					$clr['hsla'] = hexToHSL($clr['hex']);
					$clr['hsla'] = str_replace(")", ", ", $clr['alpha_dec'] .")", $clr['hsla']);

				}
			}

			array_push($colorsArray, $clr);

		}

		return $colorsArray;
	}
}