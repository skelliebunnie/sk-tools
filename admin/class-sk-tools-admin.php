<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       angel@curiousexplorations.com/about
 * @since      1.0.0
 *
 * @package    Sk_Tools
 * @subpackage Sk_Tools/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sk_Tools
 * @subpackage Sk_Tools/admin
 * @author     Angel Knight <angel@curiousexplorations.com>
 */
class Sk_Tools_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		require_once SK_PATHS['root_dir'] .'admin/class-sk-tools-settings.php';

		add_action( 'admin_menu', array($this, 'create_menu') );

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sk-tools-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sk-tools-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function create_menu() {
		// this MUST be setup HERE or the settings won't save properly
		$sk_settings = new SK_Settings();

		add_menu_page(
			__( 'SK Tools V2', 'sk_domain' ), // page title & domain (for translation)
			'SK Tools V2', // menu title
			'manage_options', // capability - who can access
			'sk-tools2', // menu_slug
			array( $this, 'create_main_page' ), // callback
			'dashicons-star-filled' // icon, position
		);

		add_submenu_page(
			'sk-tools2', // parent_slug
			__( 'SK Tools V2: Settings', 'sk_domain' ), // page title
			'Settings', // menu_title
			'manage_options',
			'sk_settings',
			array( $sk_settings, 'create_page' )
		);
	}

	public function create_main_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-display.php' );
	}

}
