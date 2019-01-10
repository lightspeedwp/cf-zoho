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

		add_filter( 'caldera_forms_field_attributes', array(
			$this,
			'field_attrs',
		), 10, 3 );

		//add_filter( 'caldera_forms_validate_field_zoho_form', array( $this, 'field_validation' ), 25, 3 );
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
					'required',
				),
			),
			'scripts' => array(
				CFZ_FIELDS_URL . 'zoho-form/js/zoho-form-field.js'
			),
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

		if ( '' === $value ) {
			return new \WP_Error( 'error',
				apply_filters( 'cf_zoho_form_field_error_empty_message', __( 'This field is required.', 'cf-zoho' ) ) );
		}

		$value = explode( ',', $value );
		if ( ! is_array( $value ) ) {
			$value = array( $value );
		}

		if ( (int) $value < (int) $field['config']['limit'] ) {
			return new \WP_Error( 'error',
				apply_filters( 'cf_zoho_form_field_error_limit_message', __( 'Please complete the rest of this field', 'cf-zoho' ) )
			);
		}

		return true;
	}

	/**
	 * Adds a caldera form to your list of modals to be outputted.
	 * @param $caldera_id string
	 * @param $field_id string
	 * @param $limit integer
	 */
	public function add_modal( $caldera_id = '', $field_id = '', $limit = 1 ) {
		if ( '' !== $caldera_id && '' !== $field_id ) {
			$this->modals[ $caldera_id ] = array(
				'field' => $field_id,
				'limit' => $limit,
			);
			add_action( 'wp_footer', array( $this, 'output_modals' ), 1 );
		}
	}

	/**
	 * Outputs the modals in the footer
	 */
	public function output_modals() {
		if ( ! empty( $this->modals ) && is_array( $this->modals ) ) {
			foreach ( $this->modals as $form_id => $values ) {

				//add filter to alter the passenger form
				add_filter( 'caldera_forms_get_form-' . $form_id, array(
					$this,
					'register_js_callback',
				) );

				do {
					include( CFZ_TEMPLATE_PATH . 'zoho-modal.php' );
					$values['limit']--;
				} while ( $values['limit'] > 0 );
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

		if ( ! isset( $allowedtags['input'] ) ) {
			$allowedtags['input'] = array();
		}
		$allowedtags['input']['data-limit'] = true;
		$allowedtags['input']['data-count'] = true;
		return $allowedtags;
	}

	/**
	 * Alter the second form to add the callback for the entry ID
	 *
	 * @since 1.0.0
	 *
	 * @param array $form the form config to alter
	 *
	 * @return array the altered form object
	 */
	public function register_js_callback( $form ) {
		$form['has_ajax_callback'] = true;
		$form['custom_callback']   = 'cf_zoho_handle_return';
		return $form;
	}

	/**
	 * Modify field attributes so recpatcha field has type "hidden" not "recpatcha"
	 *
	 * @since 0.1.0
	 *
	 * @uses "caldera_forms_field_attributes-recaptcha" filter
	 *
	 * @param $attrs
	 * @param $form
	 *
	 * @return array
	 */
	public function field_attrs( $attrs, $field, $form ) {
		if ( 'zoho_form' === $field['type'] ) {
			//set the limit
			$limit = 1;
			if ( ! empty( $field['config']['limit'] ) && '' !== $field['config']['limit'] ) {
				$limit = $field['config']['limit'];
			}
			$limit = apply_filters( 'cf_zoho_form_field_limit', $limit );
			$attrs['type'] = 'hidden';
			$attrs['data-limit'] = $limit;
			$attrs['data-count'] = 0;
			$attrs['class'][] = 'zoho-form-field';
		}
		return $attrs;
	}

	/**
	 * Validates the Zoho Form Field
	 * @param $value
	 * @param $field
	 * @param $form
	 * @return mixed
	 */
	public function field_validation( $value, $field, $form ) {
		if ( '' == $value  ) {
			return new \WP_Error( $field['ID'], esc_html__( 'This field cannot be empty', 'cf-zoho' ) );
		}
		return $value;
	}
}
