<?php
/**
 * The file that handles connections to the Zoho API.
 *
 * @package cf_zoho/includes/zohoapi.
 */

namespace cf_zoho\includes\zohoapi;
use cf_zoho\includes;

/**
 * CF_Zoho API Connection Class.
 */
class Connect {

    /**
     * Options class.
     *
     * @var object.
     */
    protected $options;

    /**
     * Tokens class.
     *
     * @var object.
     */
    protected $tokens;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->options = new includes\Options();
        $this->tokens  = new Tokens();
    }

    /**
     * Headers for any API request.
     *
     * @return array API request headers.
     */
    public function headers() {

        $token = $this->tokens->get_access_token();

        if ( false !== $token ) {
        
            return [
                'Authorization' => "Zoho-oauthtoken {$token}",
                'Content-Type'  => 'application/json',
            ];
        }

        // Need to refresh token.
        $request = $this->generate_token( 'refresh_token' );
        $token   = $this->tokens->get_access_token();

        return [
            'Authorization' => "Zoho-oauthtoken {$token}",
            'Content-Type'  => 'application/json',
        ];
    }

    /**
     * Check a Zoho API response for expired token.
     *
     * @param  array   $response JSON decoded API response.
     * @return boolean.
     */
    public function has_expired_token( $response ) {
        return isset( $response['code'] ) && 'INVALID_TOKEN' === $response['code'];
    }

    /**
     * Generates a new access token.
     *
     * @param string          $grant_type Grant type e.g. token_refresh / authorization_code.
     * @return object|boolean             WP_error|true
     */
    public function generate_token( $grant_type ) {

        $url = $this->options->get_option( 'cfzoho_url' ) . '/token';

        $body = [
            'code'          => filter_input( INPUT_GET, 'code', FILTER_SANITIZE_STRING ),
            'client_id'     => $this->options->get_option( 'cfzoho_client_id' ),
            'client_secret' => $this->options->get_option( 'cfzoho_client_secret' ),
            'redirect_uri'  => menu_page_url( 'cfzoho', false ),
            'grant_type'    => $grant_type,
        ];

        if ( 'refresh_token' === $grant_type ) {
            $body['refresh_token'] = $this->options->get_option( 'refresh_token' );
        }

        $response = wp_remote_post( $url, [
            'method'    => 'POST',
            'timeout'   => 45,
            'body'      => $body,
        ] );

        if ( is_wp_error( $response ) ) {
            return $response->get_error_message();
        }

        $body    = wp_remote_retrieve_body( $response );
        $decoded = json_decode( $body, true );

        if ( isset( $decoded['error'] ) ) {
            return $decoded['error'];
        }

        // Save.
        $this->tokens->save_tokens( $decoded );

        // If we are generating a new auth code, flush transients.
        if ( 'authorization_code' ) {
            $cache = new includes\Cache( false );
            $cache->flush_plugin_cache();
        }
        
        return true;
    }
}