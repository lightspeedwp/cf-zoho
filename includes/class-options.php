<?php
/**
 * The file that defines plugin options.
 *
 * @package cf_zoho/includes.
 */

namespace cf_zoho\includes;

/**
 * Options.
 */
class Options {

	/**
	 * Plugin options.
	 *
	 * @var array.
	 */
	private $plugin_options = [];

	/**
	 * Get single option if available.
	 *
	 * @param  string $option Option name.
	 * @return string         Option value.
	 */
	public function get_option( $option ) {

		if ( ! isset( $this->plugin_options[ $option ] ) ) {
			return;
		}

		return $this->plugin_options[ $option ];
	}

	/**
	 * Set single option to passed value.
	 *
	 * @param  string $option Option name.
	 * @param  string $option Option value.
	 */
	public function set_option( $option, $value ) {
		$this->plugin_options[ $option ] = $value;
	}

	/**
	 * Called after a flush transients request.
	 * Removes the flush_transients option.
	 */
	public function reset_cache_option() {
		unset( $this->plugin_options['flush_transients'] );
		$this->save_options();
	}

	/**
	 * Save options to DB.
	 */
	public function save_options() {
		update_option( LSX_CFZ_OPTION_SLUG, $this->plugin_options );
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->plugin_options = get_option( LSX_CFZ_OPTION_SLUG );
	}
}
