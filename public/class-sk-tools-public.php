<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       angel@curiousexplorations.com/about
 * @since      1.0.0
 *
 * @package    Sk_Tools
 * @subpackage Sk_Tools/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sk_Tools
 * @subpackage Sk_Tools/public
 * @author     Angel Knight <angel@curiousexplorations.com>
 */
class Sk_Tools_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		require_once(SK_PATHS['root_dir'] ."/includes/sk.functions.php");
		$SK_Functions = new SK_Functions();

		// SK-FILTER
		foreach(glob(SK_PATHS['root_dir'] ."/public/sk-filter/*.php") as $filename) {
			require_once($filename);
		}

		// SK-BREADCRUMBS
		foreach(glob(SK_PATHS['root_dir'] ."/public/sk-breadcrumbs/*.php") as $filename) {
			require_once($filename);
		}

		// SK-NOTICES
		foreach(glob(SK_PATHS['root_dir'] ."/public/sk-notices/*.php") as $filename) {
			require_once($filename);
		}

		// SK-DATETIME
		foreach(glob(SK_PATHS['root_dir'] ."/public/sk-datetime/*.php") as $filename) {
			require_once($filename);
		}

		// SK-COLORS
		foreach(glob(SK_PATHS['root_dir'] ."/public/sk-colors/*.php") as $filename) {
			require_once($filename);
		}

		// SK-ADDRESSBOOK
		foreach(glob(SK_PATHS['root_dir'] ."/public/sk-addressbook/*.php") as $filename) {
			require_once($filename);
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sk_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sk_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sk-tools-public.css', array(), $this->version, 'all' );

		// FONT AWESOME
		wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css');

		wp_register_style('sk-filter-styles', SK_PATHS['root_url'] ."/includes/css/sk.filter.styles.css");

		wp_register_style('sk-breadcrumbs-styles', SK_PATHS['root_url'] ."/includes/css/sk.breadcrumbs.styles.css");

		wp_register_style('sk-notices-styles', SK_PATHS['root_url'] ."/includes/css/sk.notices.styles.css");

		wp_register_style('sk-defaultpalettes-styles', SK_PATHS['root_url'] ."/includes/css/sk.defaultpalettes.styles.css");

		wp_register_style('sk-colorpalettes-styles', SK_PATHS['root_url'] ."/includes/css/sk.colorpalettes.styles.css");

		wp_register_style('sk-color-styles', SK_PATHS['root_url'] ."/includes/css/sk.colors.styles.css");

		wp_register_style('sk-color-classes', SK_PATHS['root_url'] ."/includes/css/sk.colors.classes.css");

		wp_register_style('sk-addressbook-styles', SK_PATHS['root_url'] ."/includes/css/sk.addressbook.styles.css");

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sk_Tools_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sk_Tools_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sk-tools-public.js', array( 'jquery' ), $this->version, false );

		wp_register_script( 'sk-functions-script', SK_PATHS['root_url'] ."/public/js/sk.functions.js", array('jquery'), null, true );

		wp_register_script( 'sk-filter-script', SK_PATHS['root_url'] ."/public/sk-filter/sk.filter.js", array('sk-functions-script'), null, true );
		wp_register_script( 'sk-filter-color-script', SK_PATHS['root_url'] ."/public/sk-filter/sk.filter-colors.js", array('sk-functions-script'), null, true );

	}

}
