<?php
/**
 * The file that defines plugin templates.
 *
 * @package cf_zoho/includes.
 */

namespace cf_zoho\includes;

/**
 * Templates.
 */
class Templates {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

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

	public function template_handler( $template ) {

		if ( ! is_singular( 'wp_log' ) ) {
			return $template;
		}

		return CFZ_TEMPLATE_PATH . '/single-wp-log.php';
	}
}
