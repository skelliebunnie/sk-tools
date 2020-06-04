<?php

function sk_admin() {
	global $wpdb;
	wp_enqueue_style('sk-admin-styles');

	wp_enqueue_script('sk-admin-script', SK_ROOTURL ."/admin/js/admin.js", array('jquery'), null, true);

	ob_start();

	echo "<h1>SK Tools</h1><p>Documentation / admin page</p>";

	echo ob_get_clean();
}