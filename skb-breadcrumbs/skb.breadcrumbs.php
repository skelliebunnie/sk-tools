<?php

function skb_breadcrumbs_shortcode($atts) {
	global $skb_options;

	ob_start();

	if($skb_options['skb_enable_breadcrumbs'] === 'true') {
		wp_enqueue_style('skb-breadcrumbs-styles');

		// types are: 
		//	* default ( 1 filter at a time )
		//	* additive / add ( select 1+ filter, across lists; results are 1+ match )
		// 	* subtractive / sub ( select 1+ filter; results must match ALL selected )
		$a = shortcode_atts( array(
			'show_home'				=> $skb_options['skb-bc-show_home'],
			'home_icon'				=> $skb_options['skb-bc-show_home_icon'],
			'home_icon_only'	=> $skb_options['skb-bc-home_icon_only'],
			'show_current'		=> $skb_options['skb-bc-show_current'],
			'current_url'			=> $skb_options['skb-bc-current_url'],
			'parent_url'			=> '',
			'parent_title'		=> ''
		), $atts );

		$parent = [];
		$site_url = get_site_url();

		global $post;

		$current['url'] = get_permalink($post);
		$current['title'] = get_the_title($post);

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
	?>
		<div id="skb-breadcrumbs-container">
			<?php if ($a['show_home'] === 'true'): ?>
				<a href="<?php echo $site_url; ?>" class='skb-home-url'><?php if($a['home_icon'] === true) { echo "<i class='fas fa-home'></i>"; } ?> <?php if($a['home_icon_only'] !== "true") { echo "Home"; } ?></a>
			<?php 
			endif;

			if( !empty($parent) && $parent['url'] !== $site_url && $parent['url'] !== "$site_url/" ): ?>
				&raquo; <a href="<?php echo $parent['url']; ?>" class='skb-parent-url'><?php echo $parent['title']; ?></a>
			<?php 
			endif;

			if( $a['show_current'] === 'true' && $current['url'] !== $parent['url'] ) : ?>
				&raquo;
				<?php if ($a['current_url'] === "true") { ?>
					<a href="<?php echo $current['url']; ?>" class='skb-current-url'><?php echo $current['title']; ?></a>
				<?php } else { ?>
					<span class='skb-current'><?php echo $current['title']; ?></span>
			<?php
				}
				endif; 
			?>
		</div>
	<?php
	} else {
		echo "<p>skb_breadcrumbs shortcode not enabled</p>";
	} // end if skb_enable_breadcrumbs check

	return ob_get_clean();
}
add_shortcode('skb_breadcrumbs', 'skb_breadcrumbs_shortcode');