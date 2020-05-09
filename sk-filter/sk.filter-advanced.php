<?php

class SK_FilterAdvanced {
	
	public function __construct() {
		add_shortcode( 'sk_filter_advanced', array( $this, 'sk_filter_advanced_shortcode' ) );
	}

	public function sk_filter_advanced_shortcode($atts) {
		global $sk_options;

		ob_start();

		if($sk_options['sk_enable_filter_advanced'] === 'true') {
			wp_enqueue_style('sk-filters-adv-styles');

			wp_enqueue_script('sk-functions-script');

			global $wp;
			$current_url = add_query_arg( $wp->query_vars, home_url( '/', $wp->request ) );

			$tags = $this->sk_get_filters_by_taxonomy('post_tag');
			$categories = $this->sk_get_filters_by_taxonomy('category');

			$lesson_tags = $this->sk_get_filters_by_taxonomy('lesson-tag');
			$course_categories = $this->sk_get_filters_by_taxonomy('course-category');
			// $lessons = $this->sk_get_filters_by_post_type('lesson');
			// $courses = $this->sk_get_filters_by_post_type('course');

			$grade_levels = array("k-2", "3-5", "6-8");

			$tags_list = array_merge($lesson_tags, $tags);
		
			$tag_cloud = array();
			foreach($tags as $tag) {
				$count = $tag->count;
				if($count > 9)
					$count = "9+";

				$t = "<p id='tag-{$tag->name}' class='sk-tag-container' data-tag='{$tag->name}' data-count='{$count}'><span class='sk-tag'>{$tag->name}</span><span class='sk-tag-tooltip arrow-down-with-border tag-{$tag->name}'>{$tag->name}</span></p>";

				array_push($tag_cloud, $t);
			}

			echo "<div class='sk-tag-cloud'>". implode("", $tag_cloud) ."</div>";

		} else {
			echo "<p>sk_filter_advanced shortcode not enabled</p>";
		}

		return ob_get_clean();
	}
	

	private function sk_get_filters_by_taxonomy($term) {
		$results = array();

		$terms = get_terms(
			array(
				'taxonomy'		=> $term,
				'hide_empty'	=> false
			)
		);

		if( (is_array($terms) && !empty($terms)) || (is_object($terms) && !property_exists($terms, "errors")) ) {
			foreach($terms as $term) {
				$filter = (object)[
					'name' 			=> $term->name,
					'taxonomy'	=> $term->taxonomy,
					'count'			=> $term->count
				];

				array_push($results, $filter);
			}
		}

		return $results;
	}

	private function sk_get_filters_by_post_type($post_type) {
		$results = array();

		$args = array(
			'post_type'		=> $post_type,
			'order'				=> 'ASC',
			'post_status'	=> 'publish'
		);

		$the_query = new WP_Query( $args );

		if( $the_query->have_posts() ) {
			// return $the_query->posts;
			foreach( $the_query->posts as $post ) {
				$item = (object)[
					'title' 		=> $post->post_title,
					'guid'			=> $post->guid,
					'post_type'	=> $post->post_type
				];

				array_push($results, $item);
			}
		}

		return $results;
	}
}

$sk_filter_advanced = new SK_FilterAdvanced;