<?php

function sk_admin() {

	ob_start();

	echo "<h1>SKB Tools</h1>";

	global $wp;
	$url = explode("/", $_SERVER['REQUEST_URI'])[2];
	echo $url;

	echo ob_get_clean();
}