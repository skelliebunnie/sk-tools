<?php
//** CURRENTLY NOT IN USE 2019.09.02 **//
class SKB_AirtableConnection {
	private $root 	= "https://api.airtable.com/v0/";
	private $ap_url;
	private $wp_url;
	private $args;
	private $appID;
	private $appKey;
	private $table;

	public function __construct($page_slug="butterflies", $appID="appm9rCl4jFRtRASj", $appKey="keylr1COV58UiXp9r", $table="Butterflies") {

		// if($page_slug = "") { $page_slug = "butterflies"; }
		// if($appID = "") 		{ $appID = "appm9rCl4jFRtRASj"; }
		// if($appKey = "") 		{ $appKey = "keylr1COV58UiXp9r"; }
		// if($table = "") 		{ $table = "Butterflies"; }
		
		$this->ap_url = $this->root ."$appID/$table";
		$this->wp_url = SKB_SITE_URL ."$page_slug/";

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

		$records = wp_remote_get($this->ap_url, $this->args);
	}

	public function getAllRecords($simplified=false) {
		$records = wp_remote_get($this->ap_url, $this->args);

		$list = json_decode($records["body"], true);
		$rows = $list["records"];

		if($simplified === false) {
			$new_records = array();
			foreach($rows as $record) {
				$record["wp_url"] = $this->wp_url . $record["id"];
				$record["slug"] = strtolower(str_replace(" ", "-", $record['fields']["Common Name"]));
				$record["slug"] = str_replace("'", "", $record["slug"]);

				array_push($new_records, $record);
			}

			return $new_records;

		} else {
			$simplified_records = array();

			foreach($rows as $record) {
				$rec = array();
				$rec['id'] = $record["id"];
				$rec['Species'] = $record["fields"]["Species"];
				$rec['Genus'] = $record["fields"]['Genus'];

				if( array_key_exists('Common Name', $record["fields"]) ) {
					$rec['Common Name'] = $record["fields"]['Common Name'];

					$rec["slug"] = strtolower(str_replace(" ", "-", $record["fields"]["Common Name"]));
					$rec["slug"] = str_replace("'", "", $rec["slug"]);

				} else {
					$rec['Common Name'] = $record["fields"]["Genus"] ." ". $record["fields"]["Species"];
					$rec["slug"] = strtolower(str_replace(" ", "_", $record["fields"]["Genus"])) ."-". strtolower(str_replace(" ", "_", $record["fields"]["Species"]));
				}

				$rec["wp_url"] = $rec["slug"];

				if( array_key_exists('Region', $record["fields"]) ) {
					$rec['Region'] = $record["fields"]['Region'];
				} else {
					$rec['Region'] = "";
				}

				if( array_key_exists('Colors', $record["fields"]) ) {
					$rec['Colors'] = $record["fields"]['Colors'];
				} else {
					$rec['Colors'] = "";
				}

				if( array_key_exists('Markings', $record["fields"]) ) {
					$rec['Markings'] = $record["fields"]['Markings'];
				} else {
					$rec['Markings'] = "";
				}

				if( array_key_exists('Shape', $record["fields"]) ) {
					$rec['Shape'] = $record["fields"]['Shape'];
				} else {
					$rec['Shape'] = "";
				}

				if( array_key_exists('Fun Facts', $record["fields"]) ) {
					$rec['Fun Facts'] = $record["fields"]['Fun Facts'];
				} else {
					$rec['Fun Facts'] = "";
				}

				$rec['Photo'] = array();
				if( array_key_exists('Photo', $record["fields"]) ) {
					foreach($record['fields']['Photo'] as $photo) {
						$url = $photo['thumbnails']['large']['url'];
						array_push($rec['Photo'], $url);
					}
				}

				array_push($simplified_records, $rec);
			}

			return $simplified_records;
		}

	}

	public function getSingleRecord($id) {
		$single_args = $this->args;
		$single_url = $this->ap_url ."?maxRecords=1&recordId=$id";
		$single = wp_remote_get($single_url, $single_args);

		$record = json_decode($single["body"], true);
		$record = $record["records"][0];

		$slug = isset($record["fields"]["Common Name"]) ? $record["fields"]["Common Name"] : $record["fields"]["Genus"] . $record["fields"]["Species"];
		$record["slug"] = strtolower(str_replace(" ", "-", $slug));
		$record["slug"] = str_replace("'", "", $slug);

		return $record;
	}
}

// function skb_ap_query($query,$request,$config) {
// 	$butterflies_query = new AirpressQuery("Butterflies");
// 	$butterflies_query->cacheImageFields("Photo", array("not-too-small", "medium", "full"));

// 	return $query;
// }
// add_filter("airpress_virtualpost_query", "skb_ap_query", 10, 3);