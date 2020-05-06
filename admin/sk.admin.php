<?php

function sk_admin() {
	wp_enqueue_style('sk-admin-styles');

	wp_enqueue_script('sk-admin-script', SK_ROOTURL ."/admin/js/admin.js", array('jquery'), null, true);

	ob_start();

	echo "<h1>SK Tools</h1>";
?>
This is the admin page for SK-TOOLS
<?php
	echo ob_get_clean();
}