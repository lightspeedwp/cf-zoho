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
class Mailer_Attachements {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/*
	 * Holds the array of active modules
	 */
	public $modules = array();

	/*
	 * Holds the response from Zoho
	 */
	public $response = array();

	/**
	 * Holds the current form being populated.
	 * @var array
	 */
	public $form = array();

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
}
