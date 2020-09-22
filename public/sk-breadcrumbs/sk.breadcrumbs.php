<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function sk_breadcrumbs_shortcode($atts) {
	$sk_admin_options = get_option('sk_admin_options');
	$sk_breadcrumb_options = get_option('sk_breadcrumb_options');

	ob_start();

	if($sk_admin_options['sk_enable_breadcrumbs'] === 'true') {
		wp_enqueue_style('sk-breadcrumbs-styles');

		// types are: 
		//	* default ( 1 filter at a time )
		//	* additive / add ( select 1+ filter, across lists; results are 1+ match )
		// 	* subtractive / sub ( select 1+ filter; results must match ALL selected )
		$a = shortcode_atts( array(
			'show_home'				=> $sk_breadcrumb_options['show_home'],
			'home_icon'				=> $sk_breadcrumb_options['show_home_icon'],
			'home_icon_only'	=> $sk_breadcrumb_options['home_icon_only'],
			'show_current'		=> $sk_breadcrumb_options['show_current'],
			'current_url'			=> $sk_breadcrumb_options['current_url'],
			'current_title'		=> '',
			'parent_url'			=> '',
			'parent_title'		=> ''
		), $atts );

		$current = [];
		$parent = [];
		$site_url = get_site_url();

		global $post;

		$current['url'] = get_permalink($post);
		$current['title'] = $a['current_title'] === '' ? get_the_title($post) : $a['current_title'];

		if($a['parent_title'] === "" && $a['parent_url'] === "") {
			if( $post->post_parent ) {
				$parent['url'] = get_permalink($post->post_parent);
				$parent['title'] = get_the_title($post->post_parent);

			} else {
				$parent['url'] = get_permalink($post);
				$parent['title'] = get_the_title($post);
			}

		} elseif( $a['parent_title'] !== "" && $a['parent_url'] === "" ) {

			$parent['title'] = $a['parent_title'];
			$parent['url'] = get_permalink( get_page_by_title( $a['parent_title'] ) );
			
			// if no permalink is found for the given title,
			// just get the url of the parent page
			// if there is no parent page, get the site URL
			if( ($parent['url'] === null || $parent['url'] === "") && get_permalink($post->post_parent) ) {
				$parent['url'] = get_permalink($post->post_parent);

			} elseif( !get_permalink($post->post_parent) ) {
				$parent['url'] = $site_url;
			}

		} elseif( $a['parent_title'] === "" && $a['parent_url'] !== "" ) {
			$parent['url'] = strpos($a['parent_url'], "http") === 0 ? $a['parent_url'] : $site_url ."/". $a['parent_url'];
			$parent['title'] = get_the_title( url_to_postid($parent['url']) );

		} elseif( $a['parent_title'] !== "" && $a['parent_url'] !== "" ) {
			$parent['url'] = strpos($a['parent_url'], "http") === 0 ? $a['parent_url'] : "{$site_url}/{$a['parent_url']}";
			$parent['title'] = $a['parent_title'];

		}

		if(substr($parent['url'], -1, 1) === "/") {
			$parent['url'] = substr($parent['url'], 0, strlen($parent['url']) - 1);
		}
	?>
		<div id="sk-breadcrumbs-container">
			<?php if ($a['show_home'] === 'true'): ?>
				<a href="<?php echo $site_url; ?>" class='sk-home-url'><?php if($a['home_icon'] === true) { echo "<i class='fas fa-home'></i>"; } ?> <?php if($a['home_icon_only'] !== "true") { echo "Home"; } ?></a> &raquo;
			<?php 
			endif;

			if( !empty($parent) && $parent['url'] !== $site_url && $parent['url'] !== "$site_url/" ): ?>
				<a href="<?php echo $parent['url']; ?>" class='sk-parent-url'><?php echo $parent['title']; ?></a>
			<?php 
			endif;

			if( $parent['url'] !== $site_url ) {
				echo "&raquo; ";
			}
			
			if( $a['show_current'] === 'true' && $current['url'] !== $parent['url'] ) : ?>
				<?php if ($a['current_url'] === "true") { ?>
					<a href="<?php echo $current['url']; ?>" class='sk-current-url'><?php echo $current['title']; ?></a>
				<?php } else { ?>
					<span class='sk-current'><?php echo $current['title']; ?></span>
			<?php
				}
				endif; 
			?>
		</div>
	<?php
	} else {
		echo "<p>sk_breadcrumbs shortcode not enabled</p>";
	} // end if sk_enable_breadcrumbs check

	return ob_get_clean();
}
add_shortcode('sk_breadcrumbs', 'sk_breadcrumbs_shortcode');