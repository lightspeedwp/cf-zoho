<?php
/**
 * The file that handles GET requests to the Zoho API.
 *
 * @package cf_zoho/includes/zohoapi.
 */

namespace cf_zoho\includes\zohoapi;

/**
 * CF_Zoho API GET Class.
 */
class Get extends Connect {

    /**
     * Performs a GET request to the specified URL path.
     *
     * @param  string       $path      URL path to request from.
     * @param  boolean      $new_token Whether this is a second attempt with a new token.
     * @return object|array            WP_Error|Zoho response.
     */
    public function request( $path, $new_token = false ) {

        $this->tokens->load_token_data();

        $base_url = $this->tokens->get_api_domain();
        $url      = $base_url . $path;

        $response = wp_remote_get( $url, [
            'timeout'   => 45,
            'headers'   => $this->headers(),
        ] );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $decoded_response = json_decode( $response['body'], true );

        // Expired token should be caught prior to request, but this fallback will catch any exceptions.
        if ( true === $this->has_expired_token( $decoded_response ) && false === $new_token  ) {
            
            // Generate new token.
            $request = $this->generate_token( 'refresh_token' );

            if ( is_wp_error( $request ) ) {
                return $request;
            }

            // Call self.
            return $this->request( $path, true );
        }

        return $decoded_response;
    }
}