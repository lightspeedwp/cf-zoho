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
class Pre_Populate {

	/**
	 * Holds instance of the class
	 */
	private static $instance;

	/*
	 * Holds the array of active modules
	 */
	public $modules = array();

	/*
	 * Holds the response from Zoho
	 */
	public $response = array();

	/**
	 * Holds the current form being populated.
	 * @var array
	 */
	public $form = array();

	/**
	 * Holds the current entry populated.
	 * @var array
	 */
	public $entry = array();

	/**
	 * Only allow the primary form to be pre populated.
	 * @var array
	 */
	public $has_output = false;

	/**
	 * The Arguments for the JS
	 * @var array
	 */
	public $args = array();

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
	 * auto populate the form if a resource ID is found
	 *
	 * @since 1.0.0
	 */
	public function pre_populate_form( $entry, $form ) {
		$this->form = $form;
		$this->entry = $entry;
		if ( false === $this->has_output && is_array( $this->entry ) ) {
			$params = array_intersect( array_keys( $_GET ), $this->get_modules() );
			if ( ! empty( $params ) ) {
				foreach ( $params as $key ) {
					$this->get_resource( $key, $_GET[ $key ] );
					$this->args[ $key ] = $_GET[ $key ];
				}
				$this->has_output = true;
				add_filter( 'caldera_forms_get_form-' . $form['ID'], array( $this, 'enqueue_assets' ) );
			}
		}
		$this->entry = apply_filters( 'cf_zoho_pre_populate_entry', $this->entry, $this );
		return $this->entry;
	}

	/**
	 * Returns the array keys for the modules supported
	 * @return array
	 */
	public function get_modules() {
		$this->modules = apply_filters( 'cf_zoho_pre_populate_get_module_label', array(
			'cid',
			'tid',
			'lid',
			'pid',
		) );
		return $this->modules;
	}

	/**
	 * @return string
	 */
	public function get_module_label( $module_id = '' ) {
		$module_label = '';
		switch ( $module_id ) {
			case 'cid':
				$module_label = esc_html__( 'Contacts', 'cf-zoho' );
				break;

			case 'tid':
				$module_label = esc_html__( 'Activities', 'cf-zoho' );
				break;

			case 'lid':
				$module_label = esc_html__( 'Leads', 'cf-zoho' );
				break;

			case 'pid':
				$module_label = esc_html__( 'Deals', 'cf-zoho' );
				break;


			default :
				break;
		}
		$module_label = apply_filters( 'cf_zoho_pre_populate_get_module_label', $module_label );
		return $module_label;
	}

	/**
	 * Gets the Resource from Zoho
	 * @param $key string
	 * @param $value string
	 */
	public function get_resource( $key = '', $value = '' ) {
		$get     = new zohoapi\Get();
		$path   = '/crm/v2/' . $this->get_module_label( $key ) . '/' . $value;
		$response = $get->request( $path );
		$this->response = $response;

		if ( ! is_wp_error( $response ) && is_array( $response ) && isset( $response['data'] ) && ! empty( $response['data'] ) && isset( $response['data'][0] ) ) {
			$this->filter_entry( $this->response['data'][0] );
			$this->response = apply_filters( 'cf_zoho_pre_populate_filter_entry' , $this->response['data'][0], $key, $get );
			$this->filter_entry( $this->response['data'][0] );
		}
	}

	/**
	 * @param $response
	 * @param $form_id
	 */
	public function filter_entry( $data ) {
		if ( ! empty( $this->form ) ) {

			foreach ( $data as $item_key => $item_value ) {
				$keys = array();

				//Make sure we account for the arrays.
				switch( $item_key ) {
					case 'Created_By':
						foreach ( $item_value as $sub_key => $sub_value ) {
							$temp_key = str_replace( '-', '_', sanitize_title( $sub_key ) );
							$keys[ 'contact_' . $temp_key ] = $sub_value;
						}
						break;

					default:
						$temp_key = str_replace( '-', '_', sanitize_title( $item_key ) );
						$keys[ $temp_key ] = $item_value;
						break;
				}

				//Run through each of the keys
				if ( ! empty( $keys ) ) {
					foreach( $keys as $index => $value ) {
						$field = \Caldera_Forms_Field_Util::get_field_by_slug( $index, $this->form );
						if ( false !== $field ) {
							$this->entry[ $field['ID'] ] = $value;

						}
					}
				}
			}
		}
	}

	/**
	 * Alter the main form to add the linking of passengers
	 *
	 * @since 1.0.0
	 *
	 * @param array $form the form config to alter
	 *
	 * @return array the altered form object
	 */
	public function enqueue_assets( $form ) {

		if ( false !== $form ) {
			wp_localize_script(
				'cf-zoho-form-fieldjs',
				'cf_zoho',
				$this->args
			);
		}

		return $form;
	}
}
