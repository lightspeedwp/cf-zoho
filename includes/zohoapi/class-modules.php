<?php
/**
 * The file that defines plugin Tokens.
 *
 * @package lsx_cf_zoho/includes/zohoapi.
 */

namespace lsx_cf_zoho\includes\zohoapi;

/**
 * Tokens.
 */
class Modules {


	/**
	 * Access token.
	 *
	 * @var array
	 */
	private $modules = array();

	/**
	 * Getter for $modules.
	 *
	 * @return array modules.
	 */
	public function get_modules() {
		 return $this->modules;
	}

	/**
	 * API Domain.
	 *
	 * @var string.
	 */
	private $api_domain = '';

	/**
	 * Getter for $api_domain.
	 *
	 * @return string API Domain
	 */
	public function get_api_domain() {
		 return $this->api_domain;
	}

	/**
	 * Loads in the module data
	 */
	public function load_modules() {
		 $this->modules = get_transient( LSX_CFZ_TRANSIENT_SLUG . '_modules' );
	}

	/**
	 * Whether we have a refresh token stored.
	 *
	 * @return boolean.
	 */
	public function has_modules() {
		 return ! empty( $this->modules );
	}

	/**
	 * Called whenever access tokens are generated.
	 * Stores the access token to an expiring transient.
	 * Writes refresh token and api url to an option that sits outside the settings options.
	 *
	 * @param array $token_response Response from an API token request.
	 */
	public function save_modules( $token_response ) {
		 // Set the access token to a transient that expires when the Zoho API expires it.
		set_transient( LSX_CFZ_TRANSIENT_SLUG . '_module', $modules, 60 * 60 );
		$this->modules = $modules;
		// No refresh token included, exit.
		if ( empty( $modules ) ) {
			return;
		}
	}
}
