<?php

class SK_FilterAdvanced {
	public $functions;
	
	public function __construct() {
		$this->functions = new SK_Functions();

		add_shortcode( 'sk_filter_advanced', array( $this, 'sk_filter_advanced_shortcode' ) );
	}

	public function sk_filter_advanced_shortcode($atts) {
		global $sk_options;

		ob_start();

		if($sk_options['sk_enable_filter_advanced'] === 'true') {
			wp_enqueue_style('sk-filters-adv-styles');

			// wp_enqueue_script('sk-functions-script');
			wp_enqueue_script('sk-filter-adv-script');
			
			/*
			 * comma-separated list of (string) values for:
			 * 		taxonomy, post_type, limit_to, exclude
			 * 		OR "all" / "none"
			 * 		format can include section names
			 * 		EX// section:post_tag,section:post
			 * 		if the same section name is listed for 
			 * 		multiple taxonomies / post_types, the results
			 * 		will be joined under a single section header
			 * tag_cloud: (bool) false
			 * tooltip: (bool) false
			 * class: (string) sk-filter-item
			 */

			$a = shortcode_atts( array(
				'type'			=> 'default',
				'taxonomy'	=> 'all',
				'post_type'	=> 'all',
				'limit_to'	=> 'none',
				'exclude'		=> 'none',
				'tag_cloud'	=> 'false',
				'tooltip'		=> 'false',
				'custom'		=> '',
				'class'			=> 'sk-filter-item'
				), $atts );

			$current_url = $this->functions->get_current_url();
		
			$taxonomy = explode(",", $a['taxonomy']);
			$post_type = explode(",", $a['post_type']);

			$exclude = explode(",", strtolower($a['exclude']));

			$filter_types = array();

			$results = array();
			
			// FILTERS BY TAXONOMY
			if( $a['tag_cloud'] == 'true' ) {
				$tooltip = $a['tooltip'] == 'true' ? true : false;

				$term_cloud = $this->create_term_cloud($taxonomy, $tooltip);
				$cloud = "N0THING FOUND";

				if( !empty($term_cloud) && $term_cloud !== null )
					$cloud = implode("", $term_cloud);
				echo "<div class='sk-term-cloud'>". $cloud ."</div>";

			} elseif( $a['taxonomy'] !== 'none' && $a['taxonomy'] !== '' ) {
				$terms = $this->functions->get_terms_by_taxonomy($taxonomy);

				foreach( $terms as $section=>$list ) {
					$class = "taxonomy--{$list[0]->taxonomy}";

					$tx = "<div class='sk-filter-list-container {$class}'><p><strong>{$section}</strong></p>";
					$tx .= "<ul class='sk-filter-list'>";
					foreach( $list as $item ) {
						if( !in_array($item->taxonomy, $filter_types) )
							array_push( $filter_types, $item->taxonomy );

						if( !in_array($item->slug, $exclude) && !in_array(strtolower($item->name), $exclude) ) {
							$tx .= "<li class='{$a['class']}' data-filter-type='{$item->taxonomy}' data-filter='{$item->slug}'>{$item->name}</li>";
						}
					}
					$tx .= "</ul></div>";

					array_push($results, $tx);
				}
			
			}

			// FILTERS BY POST_TYPE
			if( $a['post_type'] !== 'none' && $a['post_type'] !== '' ) {
				$post_types = $this->functions->get_posts_by_type($post_type);

				foreach( $post_types as $section=>$list ) {
					$class = "post_type--{$list[0]->post_type}";

					$pt = "<div class='sk-filter-list-container {$class}'><p><strong>{$section}</strong></p>";
					$pt .= "<ul class='sk-filter-list'>";

					if( is_array($list) ) {
						foreach( $list as $item ) {
							if( !in_array($item->post_type, $filter_types) )
								array_push( $filter_types, $item->post_type );

							if( !in_array(strtoupper($item->title), $exclude) )
								$title = strtolower(str_replace(" ", "-", $item->title));
								$pt .= "<li class='{$a['class']}' data-filter-type='{$item->post_type}' data-filter='{$title}'>{$item->title}</li>";
						}

					} elseif( is_object($list) ) {
						if( !in_array($item->post_type, $filter_types) )
							array_push( $filter_types, $item->post_type );
						
						$title = 	strtolower(str_replace(" ", "-", $item->title));
						$pt .= "<li class='{$a['class']}' data-filter-type='{$item->post_type}' data-filter='{$title}'>{$list->title}</li>";

					} 

					$pt .= "</ul></div>";

					array_push($results, $pt);
				}
			}

			// create the filters list before the custom list,
			// so we can check all items against everything in custom
			$filters = implode(",", $filter_types);
			
			// if a user is specifying a custom list of section:items,
			// assume that commas separate the values in each section list
			// and that semi-colons separate each section
			if( $a['custom'] !== '' ) {
				$section_lists = explode(";", $a['custom']);

				if( count($section_lists) > 1 ) {
					foreach($section_lists as $section_list) {
						$set = explode(":", $section_list);

						
					}
				} else {
					$section_list = explode(":", $section_lists[0]);
					$section = $section_list[0];
					$list = explode(",", $section_list[1]);

					$class = "section--{$section}";

					$ct = "<div class='sk-filter-list-container {$class}'><p><strong>{$section}</strong></p>";
					$ct .= "<ul class='sk-filter-list'>";

					if( count($list) > 1 ) {
						foreach( $list as $item ) {
							$item_name = strtolower(str_replace(" ", "-", $item));
							$ct .= "<li class='{$a['class']} sk-custom-filter' data-filter-type='{$filters}' data-filter='{$item_name}'>{$item}</li>";
						}
					} else {
						$item_name = strtolower(str_replace(" ", "-", $item[0]));
						$ct .= "<li class='{$a['class']} sk-custom-filter' data-filter-type='{$filters}' data-filter='{$item_name}'>{$list[0]}</li>";
					}

					$ct .= "</ul></div>";

					array_push($results, $ct);
				}
			}

			echo "<div class='sk-filter-advanced' data-filters='{$filters}' data-filter-type='{$a['type']}'>". implode("", $results) ."</div>";

		} else {
			echo "<p>sk_filter_advanced shortcode not enabled</p>";
		}

		return ob_get_clean();
	}

	private function create_term_cloud($taxonomy, $tooltip) {
		$terms = $this->functions->get_terms_by_taxonomy($taxonomy);
		
		// $this->functions->sk_pre($terms);

		$term_cloud = array();
		foreach($terms as $term) {
			$count = $term->count;
			if($count > 9)
				$count = "9+";

			$t = "<p id='term-{$term->name}' class='sk-term-container' data-term='{$term->name}' data-count='{$count}'><span class='sk-term'>{$term->name}</span>";

			if( $tooltip )
				$t .= "<span class='sk-term-tooltip arrow-down-with-border term-{$term->name}'>{$term->name}</span>";

			$t .= "</p>";

			array_push($term_cloud, $t);
		}

		return $term_cloud;
	}
}

$sk_filter_advanced = new SK_FilterAdvanced;