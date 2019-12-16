<?php

// From https://wordpress.stackexchange.com/a/10708
// Added sk_ prefix as a precaution
function sk_get_page_by_slug($page_slug, $post_type = 'page', $output = OBJECT ) {
    global $wpdb;
    $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s", $page_slug, $post_type ) );
    if ( $page )
      return get_page($page, $output);
    return null;
}