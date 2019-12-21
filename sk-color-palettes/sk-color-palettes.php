<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_color_palettes_shortcode($atts) {
	global $sk_options;

	// effects: slide (= slide all)
	$a = shortcode_atts( array(
		'colors' 	=> '',
		'effect'	=> 'slide', // slide, slide all, slide all text, slide blocks, &c.
		'gutter'	=> '1', // 1 - 4
		'show_color_as'		=> 'hex', // hex, rgb, hsl
		'direction' => 'row',
		'count'		=> '12',
		'names'		=> ''
	), $atts );

	ob_start();

	if($sk_options['sk_enable_colorpalettes'] === 'true') {
		wp_enqueue_style('sk-colorpalettes-styles');

		echo sk_color_palette($a);

	} else {
		echo "<p>sk_color_palette shortcode not enabled</p>";

	}

	return ob_get_clean();
}
add_shortcode('sk_color_palette', 'sk_color_palettes_shortcode');

function sk_color_palette($args) {
	$colors = explode(",", $args['colors']);
	$effect = getEffects(strtolower($args['effect']));
	$gutter = is_numeric($args['gutter']) ? $args['gutter'] : '1';
	$show_color = explode(",", strtolower($args['show_color_as']));
	$direction = strtolower($args['direction']);
	$names_list = $args['names'];

	if( substr($direction, -1, 1) === "s" ) {
		$direction = substr($direction, 0, strlen($direction) - 1);
	}

	if($direction === "col") { 
		$direction === "column"; 

	} elseif($direction !== "column" && $direction !== "row") {
		$direction = "";

	}

	if($direction === "column") {
		if( strpos($effect, "fade-all-text") !== false ) {
			$effect = "fade-all-text static-blocks";

		} else {
			$effect = "fade-text static-blocks";

		}
	}

	$names = array();
	if($names_list !== "") {
		$names = explode(",", $names_list);
	}

	$count = count($colors);

	$blocks = array();
	foreach($colors as $index=>$color) {
		$color = trim($color);
		$name = "";

		if($names_list === true || strpos($color, ":") !== false) {
			$n = explode(":", $color);
			$color = $n[1];
			$name = $n[0];

			if(!in_array("name", $show_color)) { array_push($show_color, "name"); }

		} elseif(isset($names[$index])) {
			$name = $names[$index];

		}

		if(strpos($color, "#") === 0) {
			$color = str_replace("#", "", $color);
		}

		$block = buildBlock($show_color, $color, $name);

		array_push($blocks, $block);
	}

	$color_blocks = "";
	if($count > $args['count']) {
		$lists = array_chunk($blocks, $args['count']);

		foreach($lists as $index=>$list) {
			$count = count($list);
			$color_blocks .= "<div id='sk-palette--{$index}' class='sk-palette sk-colors--count-{$count} {$effect}'>";
			$color_blocks .= implode("", $list);
			$color_blocks .= "</div>";
		}

	} else {
		$color_blocks = "<div class='sk-palette sk-colors--count-{$count} {$effect}'>";
		$color_blocks .= implode("", $blocks);
		$color_blocks .= "</div>";
	}

	$show_as = implode(',', $show_color);
	$color_palettes = "<div class='sk-palette--container gutter-{$gutter} {$direction}' data-show-color-as='{$show_as}'>";
	$color_palettes .= $color_blocks;
	$color_palettes .= "</div>";

	return $color_palettes;
}

function getEffects($effect) {
	$effects = array();

	$list = explode(",", $effect);
	foreach($list as $e) {

		$e = str_replace(" ", "-", $e);

		if($e === "slide-all-text-up" || $e === "slide-all-text") { $e = "slide-all-text text-up"; }
		if($e === "slide-all-text-down") { $e = "slide-all-text text-down"; }
		if($e === "slide-all-text-left") { $e = "slide-all-text text-left"; }
		if($e === "slide-all-text-right") { $e = "slide-all-text text-right"; }

		if($e === "slide-text" || $e === "slide-text-up") { $e = "slide-text text-up"; }
		if($e === "slide-text-down") { $e = "slide-text text-down"; }
		if($e === "slide-text-left") { $e = "slide-text text-left"; }
		if($e === "slide-text-right") { $e = "slide-text text-right"; }

		array_push($effects, $e);

	}
	$effect = implode(" ", $effects);

	if($effect === "slide" || $effect === "slide-all") {
		$effect = "slide-blocks slide-all-text";
	}

	return $effect;
}

function buildBlock($show_color, $color, $name) {
	$rgb = hexToRGB($color, true);
	$hsl = hexToHSL($color);

	$list = array();
	$list['rgb'] = "rgb(". implode($rgb) .")";
	$list['hsl'] = "hsl({$hsl->hue}, {$hsl->saturation}, {$hsl->lightness})";
	$list['name'] = $name;
	$list['hex'] = "#{$color}";

	$show_this = "";
	foreach($show_color as $index=>$show) {
		if($index === 0) {
			$show_this .= "{$list[$show]}";

		} else {
			$show_this .= "<br>{$list[$show]}";

		}
	}

	$txt_clr = readableColor($color);

	return "<div class='sk-color-block' data-color='#{$color}' style='background-color: #{$color}'><span class='sk-color-content' style='color: #{$txt_clr}'>{$show_this}</span></div>";
}