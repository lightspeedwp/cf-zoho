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
	 * Holds instance of the class
	 */
	private static $instance;

	/**
	 * Holds the Fields class
	 * @var array cf_zoho\includes\Field()
	 */
	var $field;

	/**
	 * Holds the Templates class
	 * @var array cf_zoho\includes\Templates()
	 */
	var $templates;

	/**
	 * Holds the Pre Populate class
	 * @var array cf_zoho\includes\Pre_Populate()
	 */
	var $pre_populate;

	/**
	 * Return an instance of this class.
	 *
	 * @return  object
	 */
	public static function init() {

		// If the single instance hasn't been set, set it now.
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * setup the plugin.
	 */
	public function setup() {

		// Admin Settings.
		$settings = new admin\Settings();

		add_action( 'admin_menu', [ $settings, 'settings_page' ] );
		add_action( 'admin_init', [ $settings, 'settings_api_init' ] );

		// Caldera Forms Processors.
		$cf_processors = new CF_Processors();
		add_filter( 'caldera_forms_get_form_processors', [ $cf_processors, 'register_processors' ] );

		// WP Logs.
		if ( ! class_exists( 'WP_Logging' ) ) {
			$wp_logging = new WP_Logging();
		}
		// WP Logs template.
		$this->templates = Templates::init();
		add_filter( 'template_include', [ $this->templates, 'template_handler' ], 99 );

		//Register the new field
		$this->field = Field::init();
		add_action( 'init', [ $this->field, 'setup' ] );

		$this->pre_populate = Pre_Populate::init();
		add_filter( 'caldera_forms_render_pre_get_entry', [ $this->pre_populate, 'pre_populate_form'], 10, 2 );
	}
}
