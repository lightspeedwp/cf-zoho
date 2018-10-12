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
class Field {


	/**
	 * Add the field actions
	 * return void
	 */
	public function setup() {

		//Replace existing recaptcha field
		add_filter( 'caldera_forms_get_field_types', array( $this, 'add_field' ), 25 );

		//Prevent removing recaptcha from DOM from being effective bypass of recpatcha
		//add_filter( 'caldera_forms_validate_field_recaptcha', array( $this, 'check_for_captcha' ), 10, 3 );

		//add_filter( 'caldera_forms_field_attributes-recaptcha', array( $this, 'field_attrs' ), 10, 2 );
	}

	/**
	 * Overwrite old field in Caldera Forms
	 *
	 * @param array $fields Registered fields
	 *
	 * @return array
	 */
	public function add_field( $fields ) {
		$fields['zoho_form']      = array(
			'field'       => __( 'Zoho Form', 'cf-zoho' ),
			'description' => __( 'Capture a new contact, lead or task and return the ID.', 'cf-zoho' ),
			'file'        => CFZ_FIELDS_PATH . 'zoho-form/field.php',
			'category'    => __( 'Special', 'cf-zoho' ),
			'handler'     => array( $this, 'handler' ),
			'capture'     => false,
			'setup'       => array(
				'template'      => CFZ_FIELDS_PATH . 'zoho-form/config.php',
				'preview'       => CFZ_FIELDS_PATH . 'zoho-form/preview.php',
				'not_supported' => array(
					'caption',
					//'required'
				),
			),
			'scripts' => array(),
		);

		/*if(  is_ssl() ) {
			$fields ['recaptcha' ][ 'scripts' ][] = 'https://www.google.com/recaptcha/api.js?onload=cf_recaptcha_is_ready&render=explicit';
		}else{
			$fields ['recaptcha' ][ 'scripts' ][] = 'http://www.google.com/recaptcha/api.js?onload=cf_recaptcha_is_ready&render=explicit';
		}*/

		return $fields;

	}

	/**
	 * Field handler -- checks for recaptcha and verifies  it if possible
	 *
	 * @since 0.1.0
	 *
	 * @param string $value Field value, should be empty
	 * @param array $field Field config
	 * @param array $form Form config
	 *
	 * @return \WP_Error|boolean
	 */
	public function handler( $value, $field, $form ) {
		if ( ! isset( $_POST['g-recaptcha-response'] ) || empty( $_POST['g-recaptcha-response'] ) ) {
			return new \WP_Error( 'error' );
		}

		$args = array(
			'secret'   => $field['config']['private_key'],
			'response' => sanitize_text_field( $_POST['g-recaptcha-response'] ),
		);

		$request = wp_remote_get( add_query_arg( $args, 'https://www.google.com/recaptcha/api/siteverify' ) );
		$result  = json_decode( wp_remote_retrieve_body( $request ) );
		if ( empty( $result->success ) ) {
			return new \WP_Error( 'error',
				__( "The captcha wasn't entered correctly.", 'cf-zoho' ) . ' <a href="#" class="reset_' . sanitize_text_field( $_POST[ $field['ID'] ] ) . '">' . __( 'Reset', 'cf-zoho' ) . '<a>.'
			);
		}

		return true;

	}
}
