<?php
/**
 * The file that handles POST requests to the Zoho API.
 *
 * @package cf_zoho/includes/zohoapi.
 */

namespace cf_zoho\includes\zohoapi;

/**
 * CF_Zoho API Post Class.
 */
class Post extends Connect {

	/**
	 * Performs a POST request to the specified URL path.
	 *
	 * @param  string  $path      URL path to request from.
	 * @param  array   $body      Form post data.
	 * @param  boolean $new_token Whether this is a second attempt with a new token.
	 * @return object|array            WP_Error|Zoho response.
	 */
	public function request( $path, $body, $new_token = false ) {

		$this->tokens->load_token_data();

		$base_url = $this->tokens->get_api_domain();
		$url      = $base_url . $path;

		$response = wp_remote_post(
			$url, [
				'timeout' => 45,
				'headers' => $this->headers(),
				'body'    => wp_json_encode( $body ),
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body             = wp_remote_retrieve_body( $response );
		$decoded_response = json_decode( $body, true );

		// If this is first attempt and token has expired?
		if ( true === $this->has_expired_token( $decoded_response ) && false === $new_token ) {

			// Generate new token.
			$request = $this->generate_token( 'refresh_token' );

			if ( is_wp_error( $request ) ) {
				return $request;
			}

			// Call self.
			return $this->request( $path, $data, true );
		}

		return $decoded_response;
	}
}
