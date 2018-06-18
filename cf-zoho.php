<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           cf_zoho
 *
 * @wordpress-plugin
 * Plugin Name:       Zoho CRM Addon for Caldera Forms
 * Plugin URI:        https://www.lsdev.biz/product/caldera-forms-zoho-crm-addon/
 * Description:       Caldera Forms is one of a kind WordPress form builder. With its user friendly drag and drop interface, it’s simple to create forms for your WordPress site that look awesome on any device. Caldera also comes with a range of add-ons, like integration with the Zoho CRM platform, which allows users to automate their day-to-day business activities allowing them to focus on selling without having to worry about digging through data. Use the extension to track your sales activities and gain complete understanding of your sales cycle.
 * Version:           2.0.0
 * Author:            Matt Bush
 * Author URI:        http://haycroftmedia.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cf-zoho
 */

namespace cf_zoho;
use cf_zoho\includes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 */
define( 'CFZ_VERSION', '2.0.0' );
define( 'CFZ_ABSPATH', dirname( __FILE__ ) . '/' );
define( 'CFZ_TEMPLATE_PATH', CFZ_ABSPATH . 'templates/' );
define( 'CFZ_PROCESSORS_PATH', CFZ_ABSPATH . 'processors/' );
define( 'CFZ_URL',  plugin_dir_url( __FILE__ ) . '/' );
define( 'CFZ_OPTION_SLUG', '_uix_cf-zoho' );
define( 'CFZ_TRANSIENT_SLUG', '_uix_cf-zoho_transient' );

// Autoloader.
require CFZ_ABSPATH . 'lib/autoloader.php';

// Register settings.
register_setting( 'cfzoho', CFZ_OPTION_SLUG );
register_setting( 'cfzoho', CFZ_OPTION_SLUG . '_refresh_token' );
register_setting( 'cfzoho', CFZ_TRANSIENT_SLUG  );
register_setting( 'cfzoho', CFZ_TRANSIENT_SLUG . '_access_token' );

/**
 * CF Zoho Options page URL.
 * Used to populate redirect_uri field in Zoho requests.
 * Can't use menu_page_url in Zoho requests so built this instead.
 *
 * @return string CF Zoho Options page URL.
 */ 
function cf_zoho_redirect_url() {
	return admin_url( add_query_arg( 'page', 'cfzoho', 'options-general.php' ) );
}

/**
 * Begins execution of the plugin.
 */
function run_plugin() {
	$cf_zoho = new includes\CF_Zoho();
	add_action( 'plugins_loaded', [ $cf_zoho, 'init' ], 2 );
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\run_plugin', 1 );
