<?php

/*
	TO USE:
	- anything that should be affected by the filter MUST have the class "sk-filter-item"
	- use data-ABC="XYZ" to define the key (ABC) and the value (XYZ)
		> anything with a dash (e.g. 'food-group') will assume it's 2 words & separate them
		EX1// data-color="red" => "Color": "Red"
		EX2// data-food-group="fruit" => "Food Group": "Fruit"
 */
function sk_filter_shortcode($atts) {
	global $sk_options;

	ob_start();

	if($sk_options['sk_enable_filter'] === 'true') {
		wp_enqueue_style('sk-filters-styles');

		wp_enqueue_script('sk-functions-script');
		wp_enqueue_script('sk-filter-script');

		// types are: 
		//	* default ( 1 filter at a time )
		//	* additive / add ( select 1+ filter, across lists; results are 1+ match )
		// 	* subtractive / sub ( select 1+ filter; results must match ALL selected )
		// ===============
		// auto-generate-list: type:name:title /EX/ post_type:course:subject
		// filter-tags: terms,for,filter,comp,to,auto-listed,items /EX/ k-2,3-5,6-8
		$a = shortcode_atts( array(
			'type'								=> 'default',
			'class'								=> 'sk-filter-item',
			'auto-generate-list'	=> 'false',
			'filter-tags'					=> 'false',
			'colorblocks'					=> 'false'
		), $atts );
		
		if( $a['colorblocks'] === 'true' ) { wp_enqueue_script('sk-filter-color-script'); }

		$auto_list = array();

		if( $a['auto-generate-list'] !== 'false' ) {
			$auto = $a['auto-generate-list'];

			if( strpos($auto, ",") ) {
				$sets = explode(",", $auto);

				foreach( $sets as $set ) {
					$set = explode(":", $set);

					if( !array_key_exists($set[2], $auto_list) )
						$auto_list[$set[2]] = array();

					if( $set[0] === 'post_type' || $set[0] === 'post type' ) {
						$results = sk_auto_generate_from_post_type($set[1]);

						array_push($auto_list[$set[2]], $results);
					}

					if( $set[0] === 'taxonomy' || $set[0] === 'category' || $set[0] === 'tag' ) {
						$results = sk_auto_generate_from_taxonomy($set[1]);

						array_push($auto_list[$set[2]], $results);
					}

				}

			} else {
				$set = explode(":", $auto);

				$results = '';

				if( $set[0] === 'post_type' || $set[0] === 'post type' ) {
					$results = sk_auto_generate_from_post_type($set[1]);

				} elseif( $set[0] === 'taxonomy' || $set[0] === 'category' || $set[0] === 'tag' ) {
					$results = sk_auto_generate_from_taxonomy($set[1]);

				}

				$auto_list[$set[2]] = $results;
			}
		}

	?>
		<div id="sk-filter-container" data-type="<?php echo $a['type']; ?>" data-colorfilter="<?php echo $a['colorblocks']; ?>" data-filter-class="<?php echo $a['class']; ?>"></div>

	<?php

	if( !empty($auto_list) ) {
echo "<pre>";
var_dump($auto_list["subject"][0]->tags);
echo "</pre><br>";
		$list = "<ul>"; 

		foreach($auto_list as $filter_title=>$items) {
			// $items = array of objects; we need the title and guid
			foreach($items as $item) {
				$filter_item = $item->parent;

				if( $item->parent === NULL )
					$filter_item = $item->title;

				$tags = array();
				if($a['filter-tags'] !== false) {
					$filter_tags = explode(",", $a['filter-tags']);

					foreach( $item->tags as $tag ) {
						if( in_array($tag, $filter_tags) )
							array_push($tags, $tag);
					}
				}

				$data_tags = "";
				if( !empty($tags) ) {
					$tags = implode($tags, ",");
					$data_tags = "data-grade_level='{$tags}'";
				}

				$list .= "<li class='{$a['class']}' data-{$filter_title}='{$filter_item}' {$data_tags}><a href='{$item->guid}' target='_blank'>{$item->title}</a></li>";
			}
		}
		$list .= "</ul>";

		echo $list;
	}

	} else {
		echo "<p>sk_filter shortcode not enabled</p>";
	}

	return ob_get_clean();
}
add_shortcode('sk_filter', 'sk_filter_shortcode');

function sk_auto_generate_from_taxonomy($term) {
	// get the list of terms
	$term_list = get_terms(
		array(
			'taxonomy'		=> $term,
			'hide_empty'	=> false
		)
	);

	// make sure term exists
	if( is_array($term_list) && !empty($term_list) )
		return $terms_list;

	return false;
}

function sk_auto_generate_from_post_type($post_type) {
	$results = array();

	$args = array(
		'post_type'		=> $post_type,
		'post_status' => 'publish',
		'order'				=> 'ASC'
	);

	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ) {
		$posts = $the_query->posts;

		foreach($posts as $post) {
// echo "<pre>";
// var_dump($post);
// echo "</pre>";
			$id = $post->ID;

			$course_id = intval( get_post_meta( $id, '_lesson_course', true ) );
			// $course_guid = esc_url( get_permalink( $course_id ) );

			// if the post_type of the course_id post is not a course,
			// check to see if this post is a course
			if( get_post($course_id)->post_type === "course" ) {
				$course_title = esc_html( get_the_title( $course_id ) );

			} elseif( $post->post_type === "course" ) {
				$course_title = esc_html( $post->post_title );

			} else {
				$course_title = NULL;
			}

			$tags = array();
			if( $course_title !== NULL ) {
				$tags_list = get_the_terms( $id, 'lesson-tag' );

				if( is_array($tags_list) && !empty($tags_list) ) {
					foreach($tags_list as $tag) {
						array_push($tags, $tag->name);
					}
				}

			} else {
				$tags_list = get_the_tags($id);

				if( is_array($tags_list) && !empty($tags_list) ) {
					foreach($tags_list as $tag) {
						array_push($tags, $tag->name);
					}
				}

			}
			
			$item = (object)[
				'title'		=> $post->post_title,
				'guid'		=> $post->guid,
				'parent'	=> $course_title,
				'tags'		=> $tags
			];

			array_push($results, $item);
		}

		return $results;
	}
	
	return false;
}