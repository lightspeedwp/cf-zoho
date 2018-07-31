<?php
/**
 * The file that defines the core plugin class.
 *
 * @package cf_zoho/includes.
 */

namespace cf_zoho\includes;

use cf_zoho\admin;

/**
 * Main CF_Zoho Class.
 */
final class CF_Zoho {

	/**
	 * Init the plugin.
	 */
	public function init() {

		// Admin Settings.
		$settings = new admin\Settings();

		add_action( 'admin_menu', [ $settings, 'settings_page' ] );
		add_action( 'admin_init', [ $settings, 'settings_api_init' ] );

		// Caldera Forms Processors.
		$cf_processors = new CF_Processors();
		add_filter( 'caldera_forms_get_form_processors', [ $cf_processors, 'register_processors' ] );

		// WP Logs.
		$wp_logging = new WP_Logging();

		// Log template.
		$templates = new Templates();
		add_filter( 'template_include', [ $templates, 'template_handler' ], 99 );
	}
}
