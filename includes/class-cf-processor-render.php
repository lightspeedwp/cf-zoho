<?php
/**
 * Class to render out Form Processors for Caldera Forms.
 *
 * @package lsx_cf_zoho/includes.
 */

namespace lsx_cf_zoho\includes;

use lsx_cf_zoho\includes\zohoapi;

/**
 * Processor Render Class.
 */
class CF_Processor_Render {

	/**
	 * Options class.
	 *
	 * @var object.
	 */
	private $options;

	/**
	 * Cache class.
	 *
	 * @var object.
	 */
	private $cache;

	/**
	 * Getter for $cache.
	 *
	 * @return object Cache class.
	 */
	public function get_cache() {
		return $this->cache;
	}

	/**
	 * Get class.
	 *
	 * @var object.
	 */
	public $get;

	/**
	 * Getter for $get.
	 *
	 * @return object Get class.
	 */
	public function get_get() {
		return $this->get;
	}

	/**
	 * Module we are rendering.
	 *
	 * @var string.
	 */
	private $module = '';

	/**
	 * Getter for $module.
	 *
	 * @return string Module we are rendering.
	 */
	public function get_module() {
		return $this->module;
	}

	/**
	 * Module data.
	 *
	 * @var array.
	 */
	private $module_data = array();

	/**
	 * Setter for $module_data.
	 *
	 * @internal test_that_module_data_is_correctly_built.
	 */
	public function set_module_data() {

		$module = $this->get_module();
		$cache  = $this->get_cache()->get_plugin_cache_item( $module );

		if ( false !== $cache ) {
			$this->module_data = $cache;
			return;
		}

		$path = '/crm/v2/settings/layouts?module=' . $module;

		$all_modules = $this->get_get()->request( '/crm/v2/settings/modules' );
		$data = $this->get_get()->request( $path );

		if ( is_wp_error( $data ) ) {
			$this->errors[] = $data->get_error_message();
			return;
		}

		if ( isset( $data['status'] ) && 'error' === $data['status'] ) {
			$this->errors[] = $data['message'];
			return;
		}

		$module_data = $data['layouts'][0]['sections'];

		// Remove any section without fields.
		$no_empty_fields = array_filter(
			$module_data,
			function( $section ) {
				return ! empty( $section['fields'] );
			}
		);

		$ignore_fields    = $this->get_ignore_fields();
		$force_text_input = $this->get_force_text_input();

		array_walk(
			$no_empty_fields,
			function( &$value, $key ) use ( $ignore_fields, $force_text_input ) {

				foreach ( $value['fields'] as $field_key => $fields ) {

					// Remove ignored fields.
					if ( in_array( $fields['field_label'], $ignore_fields, true ) ) {
						unset( $value['fields'][ $field_key ] );
						continue;
					}

					// No data type, carry on.
					if ( empty( $fields['data_type'] ) ) {
						continue;
					}

					// Some other data type, carry on.
					if ( 'ownerlookup' !== $fields['data_type'] ) {
						continue;
					}

					$key = sanitize_key( $fields['field_label'] );

					// If we are forcing text input, carry on.
					if ( in_array( $key, $force_text_input, true ) ) {
						continue;
					}

					// Otherwise set val to users array.
					$value['fields'][ $field_key ]['val'] = $this->get_users();
				}

				// Reset array keys.
				$value['fields'] = array_values( $value['fields'] );
			}
		);

		$this->module_data = $no_empty_fields;
		$this->get_cache()->set_plugin_cache_item( $module, $no_empty_fields );
	}

	/**
	 * Getter for $module_data.
	 *
	 * @return array Module data.
	 */
	public function get_module_data() {
		return $this->module_data;
	}

	/**
	 * Users IDs and Names as stored on Zoho CRM.
	 *
	 * @var array.
	 */
	private $users = array();

	/**
	 * Setter for $users.
	 */
	public function set_users() {

		$cache = $this->cache->get_plugin_cache_item( 'users' );

		if ( false !== $cache ) {
			$this->users = $cache;
			return;
		}

		$path = '/crm/v2/users';
		$data = $this->get->request( $path );

		if ( is_wp_error( $data ) ) {
			$this->errors[] = $data->get_error_message();
			return;
		}

		foreach ( $data['users'] as $user ) {

			$this->users[] = array(
				'label' => $user['full_name'],
				'value' => $user['id'],
			);
		}
		$this->cache->set_plugin_cache_item( 'users', $this->users );
	}

	/**
	 * Getter for $users.
	 *
	 * @return array Users IDs and Names as stored on Zoho CRM.
	 */
	public function get_users() {
		return $this->users;
	}

	/**
	 * Errors encountered by the processor.
	 *
	 * @var array.
	 */
	private $errors = array();

	/**
	 * Getter for $errors.
	 *
	 * @return array Errors encountered by the processor.
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Zoho fields to ignore when rendering fields to processor.
	 *
	 * @var array.
	 */
	private $ignore_fields = array(
		'Account Name',
		'Closed Time',
		'Created By',
		'Created Time',
		'Industry',
		'Modified By',
		'Modified Time',
		'Recurring Activity',
		'Remind At',
		'Send Notification Email',
		'Vendor Name',
		'What Id',
		'Who Id',
		'Territories',
	);

	/**
	 * Getter for $ignore_fields.
	 *
	 * @return array Zoho fields to ignore when rendering fields to processor.
	 */
	public function get_ignore_fields() {
		return $this->ignore_fields;
	}

	/**
	 * Fields that should be forced to be text input.
	 * E.g. Any select that would normally display users.
	 *
	 * @var array.
	 */
	private $force_text_input = array();

	/**
	 * Sets $force_text_input to an array containing any fields specified in options.
	 */
	public function set_force_text_input() {

		$config_object = $this->options->get_option( 'fields' );

		$key = $this->module . '_fields';

		if ( empty( $config_object[ $key ] ) ) {
			return;
		}

		$this->force_text_input = explode( "\n", $config_object[ $key ] );
	}

	/**
	 * Getter for $force_text_input.
	 *
	 * @return array Fields that should be forced to be text input.
	 */
	public function get_force_text_input() {
		return $this->force_text_input;
	}

	/**
	 * Class constructor.
	 */
	public function __construct( $module ) {

		$this->options = new Options();
		$this->cache   = new Cache();
		$this->get     = new zohoapi\Get();
		$this->module  = $module;

		$this->set_force_text_input();
		$this->set_users();
		$this->set_module_data();
	}

	/**
	 * Whether the module requires the approval mode checkboxes.
	 *
	 * @return boolean.
	 */
	public function has_approval_mode() {
		return in_array( $this->module, array( 'leads', 'contacts', 'potentials' ), true );
	}

	/**
	 * Build the label name for a field.
	 * Indicate if the field is required.
	 *
	 * @param  array $field Field array.
	 * @return string        Field label.
	 */
	public function label( $field ) {

		$label = $field['field_label'];

		if ( false === (bool) $field['required'] ) {
			return $label;
		}

		$label .= ' <span style="color:#ff0000;">*</span> ';

		return $label;
	}

	/**
	 * Return the template for the field type..
	 *
	 * @param  array $field Field array.
	 * @return string        Field template.
	 */
	public function template( $field ) {

		switch ( $field['data_type'] ) {

			case 'textarea':
				return 'zoho-textarea.php';

			default:
				return 'zoho-input.php';
		}
	}
}
