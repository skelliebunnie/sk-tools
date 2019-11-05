<?php

/**
 * Create a fake page called "fake"
 *
 * $fake_slug can be modified to match whatever string is required
 *
 *
 * @param   object  $posts  Original posts object
 * @global  object  $wp     The main WordPress object
 * @global  object  $wp     The main WordPress query object
 * @return  object  $posts  Modified posts object
 */
function skb_virtualpost( $posts ) {
	global $wp, $wp_query, $skb_virtualpost_options;

	$url_slug = $skb_virtualpost_options['skb-vp-url_slug']; // URL slug of the fake page
	$post_title = $skb_virtualpost_options['skb-vp-post_title'];
	$post_content = $skb_virtualpost_options['skb-vp-post_content'];

	if ( ! defined( 'SKB_VIRTUAL_POST' ) && ( strtolower( $wp->request ) == $url_slug ) ) {

		if($parent !== "") { $url_slug = $parent ."/". $url_slug; }

		// stop interferring with other $posts arrays on this page (only works if the sidebar is rendered *after* the main page)
		define( 'SKB_VIRTUAL_POST', true );

		// create a fake virtual page
		$post = new stdClass;
		$post->post_author    = 1;
		$post->post_name      = $url_slug;
		$post->guid           = home_url() .'/'. $url_slug;
		$post->post_title     = $post_title;
		$post->post_content   = $post_content;
		$post->ID             = -999;
		$post->post_type      = 'page';
		$post->post_status    = 'static';
		$post->comment_status = 'closed';
		$post->ping_status    = 'open';
		$post->comment_count  = 0;
		$post->post_date      = current_time( 'mysql' );
		$post->post_date_gmt  = current_time( 'mysql', 1 );
		$posts                = NULL;
		$posts[]              = $post;

		// make wpQuery believe this is a real page too
		$wp_query->is_page             = true;
		$wp_query->is_singular         = true;
		$wp_query->is_home             = false;
		$wp_query->is_archive          = false;
		$wp_query->is_category         = false;
		unset( $wp_query->query[ 'error' ] );
		$wp_query->query_vars[ 'error' ] = '';
		$wp_query->is_404 = false;
	}

	return $posts;
}
//add_filter( 'the_posts', 'skb_virtualpost', -10 );

//https://airtable.com/tblBx2EkWu0kS9US2/viwkUTOoYpKM85k6A/recaWuT5HnokQK3AM

// function matt_watson_sitemap_rewrites() {
// 	global $wp;
// 	$wp->add_query_var( 'map' );

// 	add_rewrite_rule( 'sitemap\.xml$', 'index.php?map=sitemap', 'top' );
// }
// add_action( 'init', 'matt_watson_sitemap_rewrites', 99 );

// function matt_watson_prevent_slash_on_map_variable( $redirect ) {
// 	if ( get_query_var( 'map' ) ) {
// 		return false;
// 	}
// 	return $redirect;
// }
// add_filter( 'redirect_canonical', 'matt_watson_prevent_slash_on_map_variable' );

// function matt_watson_sitemap_contents( $template ) {
// 	$map = get_query_var( 'map' );
// 	if ( ! empty( $map ) ) {
// 		header( 'Content-type: text/xml' );
// 		if ( 'sitemap' === $map ) {
// 			?>
// 			<!-- <?xml version="1.0" encoding="UTF-8"?>
// 			<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
// 				<sitemap>
// 					<loc>https://mattwatson.codes/post-sitemap.xml</loc>
// 				</sitemap>
// 			</sitemapindex> -->
// 			<?php
// 		}
// 		die();
// 	}
// 	return $template;
// }
// add_action( 'template_include', 'matt_watson_sitemap_contents' );