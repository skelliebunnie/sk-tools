<?php
/*
	TEMPLATE
	replace _tool_name with the the name of the tool (e.g. _tool_name => _filter)
	AND uncomment the add_shortcode() line
	AND make sure the settings page has a checkbox to enable / disable the tool
	AND make sure there's a default for the option in sk-tools.php
 */

function sk_tool_name_shortcode($atts) {
	global $sk_options;

	ob_start();

	if($sk_options['sk_enable_tool_name'] === 'true') {

	} else {
		echo "<p>sk_tool_name shortcode not enabled</p>";
	}

	return ob_get_clean();
}
//add_shortcode('sk_tool_name', 'sk_tool_name_shortcode');