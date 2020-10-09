<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              angel@curiousexplorations.com/about
 * @since             1.0.0
 * @package           Sk_Tools
 *
 * @wordpress-plugin
 * Plugin Name:       SK-Tools
 * Plugin URI:        https://curiousexplorations.com/sk-tools
 * Description:       A collection of useful shortcode tools.
 * Version:           5.1.4
 * Author:            Angel Knight
 * Author URI:        angel@curiousexplorations.com/about
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sk-tools
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SK_TOOLS_VERSION', '5.1.4' );

// global variables for more easily defining dir / file paths
$upload_dir = wp_upload_dir()['basedir'] ."/sk-tools";
if( ! is_dir($upload_dir) ) { mkdir($upload_dir, 0700); }

define ( 'SK_PATHS', array(
	'root_dir'	=> plugin_dir_path(__FILE__),
	'root_url'	=> plugins_url() .'/sk-tools/',
	'site_url'	=> get_site_url() .'/',
	'site_admin_url'	=> get_site_url() .'/wp-admin/',
	'upload_dir'	=> $upload_dir .'/'
));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sk-tools-activator.php
 */
function activate_sk_tools() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sk-tools-activator.php';
	Sk_Tools_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sk-tools-deactivator.php
 */
function deactivate_sk_tools() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sk-tools-deactivator.php';
	Sk_Tools_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sk_tools' );
register_deactivation_hook( __FILE__, 'deactivate_sk_tools' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sk-tools.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sk_tools() {

	$plugin = new Sk_Tools();
	$plugin->run();

}
run_sk_tools();
