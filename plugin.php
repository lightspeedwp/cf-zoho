<?php
/**
 * The plugin bootstrap file
 *
 * @package           cf_zoho
 *
 * @wordpress-plugin
 * Plugin Name:       Zoho CRM Addon for Caldera Forms
 * Plugin URI:        https://www.lsdev.biz/product/caldera-forms-zoho-crm-addon/
 * Description:       Caldera Forms is one of a kind WordPress form builder. With its user friendly drag and drop interface, it’s simple to create forms for your WordPress site that look awesome on any device. Caldera also comes with a range of add-ons, like integration with the Zoho CRM platform, which allows users to automate their day-to-day business activities allowing them to focus on selling without having to worry about digging through data. Use the extension to track your sales activities and gain complete understanding of your sales cycle.
 * Version:           2.1.0
 * Author:            LightSpeed
 * Author URI:        https://www.lsdev.biz/
 * Contributors       matttrustmytravel
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
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
define( 'CFZ_VERSION', '2.1.0' );
define( 'CFZ_ABSPATH', dirname( __FILE__ ) . '/' );
define( 'CFZ_TEMPLATE_PATH', CFZ_ABSPATH . 'templates/' );
define( 'CFZ_PROCESSORS_PATH', CFZ_ABSPATH . 'processors/' );
define( 'CFZ_FIELDS_PATH', CFZ_ABSPATH . 'fields/' );
define( 'CFZ_URL', plugin_dir_url( __FILE__ ) . '/' );
define( 'CFZ_OPTION_SLUG', '_uix_cf-zoho' );
define( 'CFZ_TRANSIENT_SLUG', '_uix_cf-zoho_transient' );

// Autoloader.
require CFZ_ABSPATH . 'includes/template-tags.php';
require CFZ_ABSPATH . 'lib/autoloader.php';

// Register settings.
register_setting( 'cfzoho', CFZ_OPTION_SLUG );



/**
 * Begins execution of the plugin on plugins loaded.
 */
function cf_zoho_run_plugin() {
	$cf_zoho = \cf_zoho\includes\CF_Zoho::init();
	add_action( 'plugins_loaded', [ $cf_zoho, 'setup' ], 2 );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\cf_zoho_run_plugin', 1 );

/**
 * Returns the main instance of the CF Zoho Plugin
 * @return object \cf_zoho\includes\CF_Zoho()
 */
function cf_zoho() {
	return \cf_zoho\includes\CF_Zoho::init();
}




