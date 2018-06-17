<?php
/**
 * The file that defines plugin Tokens.
 *
 * @package cf_zoho/includes/zohoapi.
 */

namespace cf_zoho\includes\zohoapi;

/**
 * Tokens.
 */
class Tokens {

    /**
     * Access token.
     *
     * @var string.
     */
    private $access_token = '';

    /**
     * Getter for $access_token.
     *
     * @return string Access token.
     */
    public function get_access_token() {
        return $this->access_token;
    }

    /**
     * Refresh token.
     *
     * @var string.
     */
    private $refresh_token = '';

    /**
     * Getter for $refresh_token.
     *
     * @return string Refresh token.
     */
    public function get_refresh_token() {
        return $this->refresh_token;
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
     * Loads in token data and sets properties.
     */
    public function load_token_data() {
        
        $this->access_token  = get_transient( CFZ_TRANSIENT_SLUG . '_access_token' );
        $this->refresh_token = get_option( CFZ_OPTION_SLUG . '_refresh_token', false );
        $this->api_domain    = get_option( CFZ_OPTION_SLUG . '_api_domain', false );
    }

    /**
     * Whether we have a refresh token stored.
     *
     * @return boolean.
     */
    public function has_refresh_token() {
        return false !== get_option( CFZ_OPTION_SLUG . '_refresh_token', false );
    }

    /**
     * Called whenever access tokens are generated.
     * Stores the access token to an expiring transient.
     * Writes refresh token and api url to an option that sits outside the settings options.
     *
     * @param array $token_response Response from an API token request.
     */
    public function save_tokens( $token_response ) {
        
        // Set the access token to a transient that expires when the Zoho API expires it.
        set_transient( CFZ_TRANSIENT_SLUG . '_access_token', $token_response['access_token'],  $token_response['expires_in_sec'] );
        
        $this->access_token = $token_response['access_token'];

        // No refresh token included, exit.
        if ( ! isset( $token_response['refresh_token'] ) ) {
            return;
        }

        // Otherwise store it with the api domain.
        update_option( CFZ_OPTION_SLUG . '_refresh_token', $token_response['refresh_token'] );
        update_option( CFZ_OPTION_SLUG . '_api_domain', $token_response['api_domain'] );
    }
}
