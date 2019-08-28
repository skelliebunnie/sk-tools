<?php

class SKB_AirtableConnection {
	private $root 	= "https://api.airtable.com/v0/";
	private $url;
	private $args;
	private $appID;
	private $appKey;
	private $table;

	public function __construct($appID="appm9rCl4jFRtRASj", $appKey="keylr1COV58UiXp9r", $table="Butterflies") {
		$this->url = $this->root ."$appID/$table";

		$this->appID 	= $appID;
		$this->appKey = $appKey;
		$this->table 	= $table;

		$this->args = array(
			'timeout'			=> 3000,
			'redirection'	=> 5,
			'blocking'		=> true,
			'headers'			=> array('Authorization' => "Bearer {$this->appKey}", 'Content-Type' => 'application/json'),
			'cookies'			=> array(),
			'body'				=> null,
			'compress'		=> false,
			'decompress'	=> true,
			'sslverify'		=> false
		);
	}

	public function getResults() {
		return wp_remote_get($this->url, $this->args);
	}
}