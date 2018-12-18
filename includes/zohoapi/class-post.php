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
	 * @param  boolean $has_attachments
	 * @return object|array            WP_Error|Zoho response.
	 */
	public function request( $path, $body, $new_token = false, $has_attachments = false ) {

		$this->tokens->load_token_data();

		$base_url = $this->tokens->get_api_domain();
		$url      = $base_url . $path;
		$response = wp_remote_post(
			$url, [
				'timeout' => 45,
				'headers' => $this->headers( $has_attachments ),
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
			return $this->request( $path, $body, true );
		}

		return $decoded_response;
	}

	/**
	 * @param $path
	 * @param $body
	 * @return mixed
	 */
	public function send_file( $path, $body ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $path );
		curl_setopt( $ch, CURLOPT_POST,1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $body );

		$headers = $this->headers( true );

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec ($ch);
		curl_close ($ch);

		return $result;
	}
}
