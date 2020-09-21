<?php

if( !defined( 'ABSPATH' ) ) { exit; }
/**
 * USING THE SHORTCODE
 * 'colors' can be an array of colors - colors MUST be hexadecimal format
 * 		EX // #ff0000, 00ff00 (with or without the '#')
 * effects: default is 'slide', which is 'slide blocks, slide text up'
 * 	all text effects have an 'all' variant (e.g. 'slide text' => 'slide all text')
 * 	the 'all' variant displays all text on hover, rather than just the text for the
 * 	individual block the cursor is over
 * 	SLIDE 
 * 		slide text (up), slide text down, slide text left, slide text right
 * 		slide all text (up), slide all text down, slide all text left, 
 * 			slide all text right
 * 		slide blocks ( no slide / fade for text, static block height )
 * 		slide all => slide blocks, slide all text up
 * 	FADE
 * 		fade text, fade all text (both apply static block height by default)
 * 			override static block height using 'expand blocks'
 * 	STATIC
 * 		static blocks; block height does not expand on hover 
 * 	EXPAND
 * 		expand blocks ( fade text, block height expands )
 */
function sk_color_palettes_shortcode($atts) {
	$sk_admin_options = get_option('sk_admin_options');

	// effects: slide (= slide all)
	$a = shortcode_atts( array(
		'colors' 					=> '', // as hex, or as name:hex
		'effect'					=> 'slide',
		'gutter'					=> '1', // max 4
		'show_color_as'		=> 'hex', // hex, rgb, hsl, name
		'direction' 			=> 'row', // row, column
		'color_count'			=> '12', // max 12; how many color blocks per palette
		'palette_count'		=> '6', // palettes per column
		'names'						=> '', // true, or list of names, or 'hex:name' / 'name:hex'
		'palette_titles'	=> '',
		'break'						=> '<br>',
		'capitalize_name'	=> 'true',
		'include_original' => 'true'
	), $atts );

	ob_start();

	if($sk_admin_options['enable_colorpalettes'] === 'true') {
		wp_enqueue_style('sk-colorpalettes-styles');

		$palettes = array('default', 'base', 'compound', 'tetrad1', 'tetrad2', 'quad', 'triad', 'split', 'analogous', 'all');
		$types = array('default', 'static', 'adaptive');
		$ranges = array(0, 3, 5, 7, 9);

		$colors = explode(",", $a['colors']);

		if($a['colors'] !== '' && !in_array($colors[0], $palettes) ) {
			echo sk_color_palette($a);

		} elseif( !empty($colors) && in_array($colors[0], $palettes) ) {
			$check = explode(":", $a['colors']);

			$palette = $a['colors']; $type = "default"; $range = 0;
			if( is_array($check) && count($check) > 1 ) {
				if( in_array($check[0], $types) ) { $type = $check[0]; $palette = $check[1]; }
				if( in_array($check[1], $types) ) { $type = $check[1]; $palette = $check[0]; }
				if(count($check) === 3) { $range = $check[2]; }

			} else {
				$type = 'static'; $range = 9;

			}

			$show_titles = false;
			if($a['palette_titles'] !== '') { $show_titles = true; }

			if($palette !== "") {
				echo getBuiltInPalettes($palette, $type, $range, $show_titles, $a['show_color_as'], $a['include_original']);

			} else {
				echo sk_color_palette($a);

			}

		}

	} else {
		echo "<p>sk_color_palette shortcode not enabled</p>";

	}

	return ob_get_clean();
}
add_shortcode('sk_color_palette', 'sk_color_palettes_shortcode');

function sk_color_palette($args) {
	$colors = $args['colors'];
	$effect = getEffects(strtolower($args['effect']));
	$gutter = is_numeric($args['gutter']) ? $args['gutter'] : '1';
	$show_color = explode(",", strtolower($args['show_color_as']));
	$direction = strtolower($args['direction']);
	$names_list = $args['names'];
	$break = $args['break'];
	$palette_count = $args['palette_count'];

	$palette_lists = array();
	if( strpos($colors, ";") !== false || strpos($colors, "|") !== false ) {
		$lists = array();
		if( strpos($colors, ";") !== false) { $lists = explode(";", $args['colors']); }
		if( strpos($colors, "|") !== false) { $lists = explode("|", $args['colors']); }

		foreach($lists as $list) {
			$colors = explode(",", $list);
			array_push($palette_lists, $colors);
		}
				
	} else {
		$colors = explode(",", $args['colors']);

	}

	if( substr($direction, -1, 1) === "s" ) {
		$direction = substr($direction, 0, strlen($direction) - 1);
	}

	if($direction === "col") { 
		$direction === "column"; 

	} elseif($direction !== "column" && $direction !== "row") {
		$direction = "";

	}

	$names = null;
	if($names_list !== "" && strpos($names_list, ":") === false) {
		$names = explode(",", $names_list);

	} elseif( strpos($names_list, ":") !== false ) {
		$names = explode(":", $names_list);

	}

	$count = count($colors);

	$blocks = array(); $palettes = array();
	if( empty($palette_lists) || count($palette_lists) <= 1 ) {
		$blocks = buildAllBlocks($colors, $names, $args['capitalize_name'], $show_color, $break);

	} else {
		foreach($palette_lists as $colors) {
			
			$list = buildAllBlocks($colors, $names, $args['capitalize_name'], $show_color, $break);

			array_push($palettes, $list);
		}

	}

	$palette_titles = array();
	if($args['palette_titles'] !== '') {
		$palette_titles = explode(",", $args['palette_titles']);
	}

	$color_blocks = "";
	if( $count > $args['color_count'] && empty($palettes) ) {
		$lists = array_chunk($blocks, $args['color_count']);

		foreach($lists as $index=>$list) {
		
			$count = count($list);

			$palette = "<div id='sk-palette--{$index}' class='sk-palette colors--{$count} {$effect}'>";
			$palette .= implode("", $list);
			$palette .= "</div>";

			if( !empty($palette_titles) && isset($palette_titles[$index]) ) {
				$title = ucwords($palette_titles[$index]);

				$color_blocks .= "<div class='sk-palette--wrapper '><h3 class='sk-palette--title'>{$title}</h3>". $palette ."</div>";

			} else {

				$color_blocks .= $palette;

			}
		}

	} elseif($args['color_count'] === 'auto' || !empty($palettes)) {

		foreach($palettes as $index=>$list) {
			$count = count($list);

			$palette = "<div id='sk-palette--{$index}' class='sk-palette colors--{$count} {$effect}'>";
			$palette .= implode("", $list);
			$palette .= "</div>";

			if( !empty($palette_titles) && isset($palette_titles[$index]) ) {
				$title = ucwords($palette_titles[$index]);

				$color_blocks .= "<div class='sk-palette--wrapper '><h3 class='sk-palette--title'>{$title}</h3>". $palette ."</div>";

			} else {

				$color_blocks .= $palette;

			}
		}

	} else {
		$palette = "<div class='sk-palette colors--{$count} {$effect}'>";
		$palette .= implode("", $blocks);
		$palette .= "</div>";

		if( !empty($palette_titles) && $palette_titles[0] !== '' ) {
			$title = ucwords($palette_titles[0]);
			$color_blocks .= "<div class='sk-palette--wrapper '><h3 class='sk-palette--title'>{$title}</h3>". $palette ."</div>";

		} else {
			$color_blocks .= $palette;

		}

	}

	$show_as = implode(',', $show_color);
	$color_palettes = "<div class='sk-palette--container palette-col-{$palette_count} gutter-{$gutter} {$direction}' data-show-color-as='{$show_as}'>";
	$color_palettes .= $color_blocks;
	$color_palettes .= "</div>";

	return $color_palettes;
}

// return string
function getEffects($effect) {
	
	$effects = array();
	$list = explode(",", $effect);
	foreach($list as $e) {
		$e = trim($e);
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

	if($effect === "slide") {
		$effect = "slide-blocks slide-text text-up";

	} elseif($effect === "slide-all") {
		$effect = "slide-blocks slide-all-text text-up";
	}

	return $effect;
}

// returns string
function buildBlock($show_color, $color, $name, $break="<br>") {
	$rgb = hexToRGB($color, true);
	$hsl = hexToHSL($color);

	$list = array();
	$list['rgb'] = "<span class='color-rgb'>rgb({$rgb['red']},{$rgb['green']},{$rgb['blue']})</span>";
	$list['hsl'] = "<span class='color-hsl'>hsl({$hsl->hue}, {$hsl->saturation}%, {$hsl->lightness}%)</span>";
	$list['name'] = "<span class='color-name'>{$name}</span>";
	$list['hex'] = "<span class='color-hex'>#{$color}</span>";

	$show_this = "";
	if($show_color[0] !== "all") {
		foreach($show_color as $index=>$show) {
			if($index === 0) {
				$show_this .= "{$list[$show]}";

			} else {
				$show_this .= "{$break}{$list[$show]}";

			}
		}
	} else {
		$n = 0;
		foreach($list as $item) {
			if($n === 0) {
				$show_this .= "{$item}";

			} else {
				$show_this .= "{$break}{$item}";

			}
			$n++;
		}

	}

	$txt_clr = readableColor($color);

	return "<div class='sk-color-block--wrapper'><div class='sk-color-block' data-color='#{$color}' style='background-color: #{$color}'><span class='sk-color-content' style='color: #{$txt_clr}'>{$show_this}</span></div></div>";
}

// returns array of strings (each from buildBlock)
function buildAllBlocks($colors, $names, $capitalize_name, $show_color, $break) {
	$blocks = array();

	foreach($colors as $index=>$color) {
		$color = trim($color);
		$name = "";

		if( $names !== null || strpos($color, ":") !== false ) {
			$n = explode(":", $color);

			if($names === null) {
				$name = $n[0];
				$color = $n[1];

			} else {
				if( in_array("hex", $names) ) { 
					$color = $n[array_search('hex', $names)];
					$name = $n[array_search('name', $names)];
				}

				if( in_array("rgb", $names) ) { 
					$color = $n[array_search('rgb', $names)];
					$name = $n[array_search('name', $names)];
				}

				if( in_array("hsl", $names) ) { 
					$color = $n[array_search('hsl', $names)];
					$name = $n[array_search('name', $names)];
				}

			}

		} elseif( isset($names[$index]) ) {
			$name = $names[$index];

		}

		if(strpos($color, "#") === 0) {
			$color = str_replace("#", "", $color);
		}

		if($capitalize_name == 'true') { $name = ucwords($name); }

		$block = buildBlock($show_color, $color, $name, $break);

		array_push($blocks, $block);
	}

	return $blocks;
}

function getBuiltInPalettes($palette, $type, $range, $show_titles, $show_color_as, $include_original) {
	wp_enqueue_style('sk-defaultpalettes-styles');

	$palette = str_replace(" ", "", $palette);

	$list = array();
	if( strpos($palette, ",") !== false ) {
		$list = explode(",", $palette);

	}

	$base_hidden = true; $analogous_hidden = true;
	$compound_hidden = true; $tetrad1_hidden = true;
	$tetrad2_hidden = true; $triad_hidden = true; $split_hidden = true;

	if( $palette === 'all' || (!empty($list) && in_array("all", $list) ) ) {
		$base_hidden = false; $analogous_hidden = false;
		$compound_hidden = false; $tetrad1_hidden = false;
		$tetrad2_hidden = false; $triad_hidden = false; $split_hidden = false;

	} elseif( !empty($list) ) {

		if( in_array("base", $list) || in_array("default", $list) ) { $base_hidden = false; }
		if( in_array("compound", $list) ) { $compound_hidden = false; }
		if( in_array("analogous", $list) ) { $analogous_hidden = false; }
		if( in_array("tetrad1", $list) ) { $tetrad1_hidden = false; }
		if( in_array("tetrad2", $list) || in_array("quad", $list) || in_array("quadratic", $list) ) { $tetrad2_hidden = false; }
		if( in_array("triad", $list) ) { $triad_hidden = false; }
		if( in_array("split", $list) ) { $split_hidden = false; }

	} elseif( empty($list) && $palette !== '' ) {
		switch($palette) {
			case 'base': 
			case 'default':
				$base_hidden = false; break;
			case 'compound':
				$compound_hidden = false; break;
			case 'analogous':
				$analogous_hidden = false; break;
			case 'triad':
				$triad_hidden = false; break;
			case 'split':
				$split_hidden = false; break;
			case 'tetrad1':
				$tetrad1_hidden = false; break;
			case 'quad':
			case 'tetrad2':
				$tetrad2_hidden = false; break;
			default:
				$base_hidden = false; break;
		}
	} else {
		$base_hidden 			= false; 
		$analogous_hidden = true;
		$compound_hidden 	= true; 
		$tetrad1_hidden 	= true;
		$tetrad2_hidden 	= true;
		$triad_hidden 		= true; 
		$split_hidden 		= true;
	}

	$show = " show-color-as--name";
	$show_as = explode(",", $show_color_as);
	if( in_array("hex", $show_as) && in_array("name", $show_as) ) {
		$show = " show-color-as--name-hex";

	} elseif( in_array("hex", $show_as) ) {
		$show = " show-color-as--hex";

	} elseif( in_array("none", $show_as) ) {
		$show = " show-color-as--none";
		
	}

	$show_original = ' include-original-colors--true';
	if($include_original == 'true') {
		$show_original = ' include-original-colors--false';
	}

	ob_start();
?>
<div class="sk-default-palette--container palette-type--<?php echo $type; ?> palette-range--<?php echo $range; ?><?php if($show !== '') echo $show; ?>">
<?php if(!$base_hidden): ?>
	<div class="sk-default-palette--wrapper<?php echo $show_original; ?>">
		<h2<?php if($show_titles == false) echo " class='hidden'"; ?>>Base / Default Palette -- Core Colors</h2>
		<div id="palette-base" class="sk-default-palette">
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color color-original"></span>
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(!$analogous_hidden): ?>
	<div class="sk-default-palette--wrapper">
		<h2<?php if($show_titles == false) echo " class='hidden'"; ?>>Analogous Harmony Palette</h2>
		<div id="palette-analogous" class="sk-default-palette">
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(!$tetrad1_hidden): ?>
	<div class="sk-default-palette--wrapper">
		<h2<?php if($show_titles == false) echo " class='hidden'"; ?>>Tetrad (Rectangular) Harmony Palette</h2>
		<div id="palette-tetrad1" class="sk-default-palette">
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(!$tetrad2_hidden): ?>
	<div class="sk-default-palette--wrapper">
		<h2<?php if($show_titles == false) echo " class='hidden'"; ?>>Tetrad (Square) "Quadratic" Harmony Palette</h2>
		<div id="palette-tetrad2" class="sk-default-palette">
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(!$triad_hidden): ?>
	<div class="sk-default-palette--wrapper">
		<h2<?php if($show_titles == false) echo " class='hidden'"; ?>>Triad Harmony Palette</h2>
		<div id="palette-triad" class="sk-default-palette">
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(!$split_hidden): ?>
	<div class="sk-default-palette--wrapper">
		<h2<?php if($show_titles == false) echo " class='hidden'"; ?>>Split Harmony Palette</h2>
		<div id="palette-split" class="sk-default-palette">
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if(!$compound_hidden): ?>
	<div class="sk-default-palette--wrapper">
		<h2<?php if($show_titles == false) echo " class='hidden'"; ?>>Compound Harmony (based on Adobe's Compound Harmony) Palette</h2>
		<div id="palette-compound" class="sk-default-palette">
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
			<div class="sk-color--wrapper">
				<span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span><span class="color"></span>
			</div>
		</div>
	</div>
<?php endif; ?>
</div>

<?php
	return ob_get_clean();
}