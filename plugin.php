<?php
/**
 * The plugin bootstrap file
 *
 * @package           lsx_cf_zoho
 *
 * @wordpress-plugin
 * Plugin Name:       Zoho CRM Addon for Caldera Forms
 * Plugin URI:        https://github.com/lightspeeddevelopment/lsx-cf-zoho
 * Description:       Caldera Forms is one of a kind WordPress form builder. With its user friendly drag and drop interface, it’s simple to create forms for your WordPress site that look awesome on any device. Caldera also comes with a range of add-ons, like integration with the Zoho CRM platform, which allows users to automate their day-to-day business activities allowing them to focus on selling without having to worry about digging through data. Use the extension to track your sales activities and gain complete understanding of your sales cycle.
 * Version:           2.0.0
 * Author:            LightSpeed
 * Author URI:        https://www.lsdev.biz/
 * Contributors       feedmycode, matttrustmytravel
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       lsx-cf-zoho
 */

namespace lsx_cf_zoho;

use lsx_cf_zoho\includes;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 */
define( 'LSX_CFZ_VERSION', '2.0.0' );
define( 'LSX_CFZ_ABSPATH', dirname( __FILE__ ) . '/' );

define( 'LSX_CFZ_TEMPLATE_PATH', LSX_CFZ_ABSPATH . 'templates/' );
define( 'LSX_CFZ_PROCESSORS_PATH', LSX_CFZ_ABSPATH . 'processors/' );
define( 'LSX_CFZ_FIELDS_PATH', LSX_CFZ_ABSPATH . 'fields/' );
define( 'LSX_CFZ_URL', plugin_dir_url( __FILE__ ) . '/' );
define( 'LSX_CFZ_FIELDS_URL', LSX_CFZ_URL . 'fields/' );

define( 'LSX_CFZ_OPTION_SLUG', '_uix_lsx-cf-zoho' );
define( 'LSX_CFZ_TRANSIENT_SLUG', '_uix_lsx-cf-zoho_transient' );

// Autoloader.
require LSX_CFZ_ABSPATH . 'includes/template-tags.php';
require LSX_CFZ_ABSPATH . 'lib/autoloader.php';

// Register settings.
register_setting( 'cfzoho', LSX_CFZ_OPTION_SLUG );

/**
 * Begins execution of the plugin on plugins loaded.
 */
function cf_zoho_run_plugin() {
	$cf_zoho = \lsx_cf_zoho\includes\CF_Zoho::init();
	add_action( 'plugins_loaded', [ $cf_zoho, 'setup' ], 2 );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\cf_zoho_run_plugin', 1 );

/**
 * Returns the main instance of the CF Zoho Plugin
 * @return object \lsx_cf_zoho\includes\CF_Zoho()
 */
function lsx_cf_zoho() {
	return \lsx_cf_zoho\includes\CF_Zoho::init();
}




