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
	 * Holds the modals that are outputted in the footer of the page
	 * @var array
	 */
	var $modals = array();

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


	/**
	 * Add the field actions
	 * return void
	 */
	public function setup() {

		//Replace existing recaptcha field
		add_filter( 'caldera_forms_get_field_types', array( $this, 'add_field' ), 25 );

		add_filter( 'caldera_forms_get_field_types', array(
			$this,
			'add_field',
		), 25, 1 );

		add_filter( 'wp_kses_allowed_html', array(
			$this,
			'wp_kses_allowed_html',
		), 10, 2 );

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
			'scripts' => array(
				CFZ_FIELDS_URL . 'zoho-form/js/zoho-form-field.js'
			)
		);

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

	/**
	 * Adds a caldera form to your list of modals to be outputted.
	 * @param $caldera_id string
	 */
	public function add_modal( $caldera_id = '', $field_id = '' ) {
		if ( '' !== $caldera_id && '' !== $field_id ) {
			$this->modals[ $caldera_id ] = $field_id;
			add_action( 'wp_footer', array( $this, 'output_modals' ), 1 );
		}
	}

	/**
	 * Outputs the modals in the footer
	 */
	public function output_modals() {
		if ( ! empty( $this->modals ) && is_array( $this->modals ) ) {
			foreach( $this->modals as $form_id => $field_id ) {
				include( CFZ_TEMPLATE_PATH . 'zoho-modal.php' );
			}
		}
	}

	/**
	 * Allow extra tags and attributes to wp_kses_post()
	 */
	public function wp_kses_allowed_html( $allowedtags, $context ) {
		if ( ! isset( $allowedtags['div'] ) ) {
			$allowedtags['div'] = array();
		}
		$allowedtags['div']['data-form-id']   = true;
		$allowedtags['div']['data-field-id'] = true;
		return $allowedtags;
	}
}
