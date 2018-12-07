<?php
/**
 * Defines processors for Caldera Forms.
 *
 * @package cf_zoho/includes.
 */

namespace cf_zoho\includes;

use cf_zoho\includes\zohoapi;

/*
 * 		if (self::should_send_mail($form, $transdata)) {
			Caldera_Forms_Save_Final::do_mailer($form, $entryid);
		}
 */

/**
 * Processors Class.
 */
class CF_Processors {

	/**
	 * Processor config.
	 *
	 * @var array.
	 */
	private $config = [];

	/**
	 * The current form being processed.
	 *
	 * @var array.
	 */
	private $form = [];

	/**
	 * Module name.
	 *
	 * @var string.
	 */
	private $module = '';

	/**
	 * Contains any additional mails that need to be sent out.
	 *
	 * @var array.
	 */
	private $additional_mails = '';

	/**
	 * Registers our processors with Caldera Forms.
	 *
	 * @param  array $processors Array of current processors.
	 * @return array                Current processors with our added processors
	 */
	public function register_processors( $processors ) {

		$processors['zoho_lead'] = [
			'name'        => __( 'Zoho CRM - Create Lead', 'cf-zoho' ),
			'description' => __( 'Create or Update a lead on form submission', 'cf-zoho' ),
			'author'      => 'LightSpeed',
			'author_url'  => 'https://lsdev.biz/',
			'processor'   => [ $this, 'process_lead_submission' ],
			'template'    => CFZ_PROCESSORS_PATH . 'lead-processor-config.php',
			'icon'        => CFZ_URL . 'assets/images/icon.png',
			'magic_tags'  => [
				'id' => [ 'text', 'zoho_task' ],
			],
		];

		$processors['zoho_contact'] = [
			'name'        => __( 'Zoho CRM - Create Contact', 'cf-zoho' ),
			'description' => __( 'Create or Update a contact on form submission', 'cf-zoho' ),
			'author'      => 'LightSpeed',
			'author_url'  => 'https://lsdev.biz/',
			'processor'   => [ $this, 'process_contact_submission' ],
			'template'    => CFZ_PROCESSORS_PATH . 'contact-processor-config.php',
			'icon'        => CFZ_URL . 'assets/images/icon.png',
			'magic_tags'  => [
				'id' => [ 'text', 'zoho_task' ],
			],
		];

		$processors['zoho_task'] = [
			'name'        => __( 'Zoho CRM - Create Task', 'cf-zoho' ),
			'description' => __( 'Create or Update a task on form submission', 'cf-zoho' ),
			'author'      => 'LightSpeed',
			'author_url'  => 'https://lsdev.biz/',
			'processor'   => [ $this, 'process_task_submission' ],
			'template'    => CFZ_PROCESSORS_PATH . 'task-processor-config.php',
			'icon'        => CFZ_URL . 'assets/images/icon.png',
			'magic_tags'  => [ 'id' ],
		];

		return $processors;
	}

	/**
	 * Callback for Lead form submissions.
	 *
	 * @param  array  $config Processor config
	 * @param  array  $form Form config
	 * @param  string $process_id Unique process ID for this submission
	 * @return array.
	 */
	public function process_lead_submission( $config, $form, $process_id ) {

		$this->config = $config;
		$this->form   = $form;
		$this->module = 'leads';

		return $this->do_submission();
	}

	/**
	 * Callback for Contact form submissions.
	 *
	 * @param  array  $config Processor config
	 * @param  array  $form Form config
	 * @param  string $process_id Unique process ID for this submission
	 * @return array.
	 */
	public function process_contact_submission( $config, $form, $process_id ) {

		$this->config = $config;
		$this->form   = $form;
		$this->module = 'contacts';

		return $this->do_submission();
	}

	/**
	 * Callback for Task form submissions.
	 *
	 * @param  array  $config Processor config
	 * @param  array  $form Form config
	 * @param  string $process_id Unique process ID for this submission
	 * @return array.
	 */
	public function process_task_submission( $config, $form, $process_id ) {

		$this->config = $config;
		$this->form   = $form;
		$this->module = 'tasks';

		return $this->do_submission();
	}

	/**
	 * Logs an event or an error with processor submission.
	 *
	 * @param string  $message    Error or event response.
	 * @param array   $submission Data that was submitted to the form.
	 * @param integer $id         ID of the form submission.
	 * @param string  $type       Either error or event.
	 */
	public function log( $message, $submission, $details, $id, $type ) {

		$submission = [
			'response'   => $message,
			'submission' => $submission,
			'details'    => $details,
		];

		WP_Logging::add(
			'Submission for ' . $this->module . ' form: ' . $type,
			wp_json_encode( $submission ),
			$id,
			$type
		);
	}

	/**
	 * Process form submissions.
	 *
	 * @return null|array Array containining id if successfull|null response on fail.
	 */
	public function do_submission() {

		/**
		 * TODO: This is where we check to see if we should submit this info or not.
		 */
		add_filter('caldera_forms_send_email', array( $this, 'stagger_mailer' ), 1, 2);

		$path   = '/crm/v2/' . ucfirst( $this->module );
		$object = $this->build_object();

		if ( isset( $this->config['_allow_duplicates'] ) && 'update' === $this->config['_allow_duplicates'] ) {
			$path                            .= '/upsert';
			$object['duplicate_check_fields'] = 'Email';
		}

		$trigger = [];

		if ( ! empty( $this->config['_approval_mode'] ) ) {
			$trigger[] = 'approval';
		}

		if ( ! empty( $this->config['_workflow_mode'] ) ) {
			$trigger[] = 'workflow';
		}

		$body = [
			'data'    => [ $object ],
			'trigger' => $trigger,
		];

		// Filter hook.
		$body     = apply_filters( 'process_zoho_submission', $body, $this->config, $this->form );

		if ( isset( $this->config['_return_information'] ) && ( true === $this->config['_return_information'] || 'true' === $this->config['_return_information'] || 1 === $this->config['_return_information'] ) ) {
			$object_id = $this->capture_info( $this->module, $body, $object );
		} else {
			$object_id = $this->do_request( $path, $body, $object );
		}

		do_action( 'cf_zoho_create_entry_complete', $object_id, $this->config, $this->form );

		return [
			'id' => $object_id,
		];
	}

	public function capture_info( $module, $body, $object ) {
		/**
		 * TODO: if the form is capturing entries, then serialize the data and return that for saving.
		 */
		return serialize( array( $module => $body ) );
	}

	/**
	 * The function which send the build info
	 * @param $path
	 * @param $body
	 * @param $object
	 *
	 * @return array
	 */
	public function do_request( $path, $body, $object ) {
		$post     = new zohoapi\Post();
		$response = $post->request( $path, $body );

		print_r($post);

		if ( is_wp_error( $response ) ) {

			$this->log( $response->get_error_message(), $object, 'WordPress Error', 0, 'error' );

			return [
				'note' => $response->get_error_message(),
				'type' => 'error',
			];
		}

		if ( ! isset( $response['data'][0]['code'] ) || 'SUCCESS' !== $response['data'][0]['code'] ) {

			$this->log( $response['data'][0]['message'], $object, $response['data'][0]['details'], 0, 'error' );

			return [
				'note' => $response['data'][0]['message'],
				'type' => 'error',
			];
		}

		$object_id = $response['data'][0]['details']['id'];
		$this->log( $response['data'][0]['message'], $object, $response['data'][0]['details'], $object_id, 'event' );

		return $object_id;
	}

	/**
	 * Build object for the module.
	 *
	 * @return array Object for the module.
	 */
	public function build_object() {

		$object = $this->get_default_object( );

		$cache  = new Cache();
		$fields = $cache->get_plugin_cache_item( $this->module );

		// If fields aren't cached, fetch them.
		if ( false === $fields ) {
			$fields = $this->get_module_fields();
		}

		foreach ( $fields as $section ) {

			foreach ( $section['fields'] as $field ) {

				$value = $this->get_form_value( $field );

				if ( '' === $value ) {
					continue;
				}
				$label            = str_replace( ' ', '_', $field['field_label'] );
				/**
				 * TODO: Change this to a preg_match
				 */
				if ( 'Lead_Owner' === $label || 'Task_Owner' === $label || 'Contact_Owner' === $label ) {
					$label = 'Owner';
				}
				$object[ $label ] = $this->get_form_value( $field );
			}
		}

		return $object;
	}

	/**
	 * Default object for the module.
	 *
	 * @return array Default object for the module.
	 */
	public function get_default_object() {

		switch ( $this->module ) {

			case 'leads':
			case 'contacts':
				$object = [
					'Email_Opt_Out' => ! empty( $this->config['_email_opt_out'] ) ? (bool) true : (bool) false,
					'Description'   => '',
				];

				$unset_if_empty = [
					'leadowner'  => 'SMOWNERID',
					'leadsource' => 'Lead_Source',
					'leadstatus' => 'Lead_Status',
					'rating'     => 'Rating',
				];

				foreach ( $unset_if_empty as $key => $label ) {

					if ( ! empty( $this->config[ $key ] ) ) {
						$object[ $label ] = $this->config[ $key ];
						continue;
					}

					if ( ! isset( $this->config[ $key ] ) ) {
						continue;
					}

					unset( $this->config[ $key ] );
				}

				return $object;

			case 'tasks':
				$object = [
					'Due_Date'  => '',
					'Subject'   => '',
					'Status'    => '',
					'SMOWNERID' => '',
				];

				// No parent? Then return.
				if ( empty( $this->config['parent'] ) ) {
					return $object;
				}

				// Otherwise, get parent.
				$parent = explode( ':', trim( $this->config['parent'], '{}' ) );

				if ( empty( $this->form['processors'][ $parent[0] ] ) ) {
					$this->config['whoid'] = '%' . $this->form['fields'][ $this->config['parent'] ]['slug'] . '%';
					return $object;
				}

				$parent_value = \Caldera_Forms::do_magic_tags( $this->config['parent'] );

				/* Add lead or contact ID. */
				if ( 'zoho_contact' === $this->form['processors'][ $parent[0] ]['type'] ) {
					$object['CONTACTID'] = $parent_value;
					return $object;
				}

				if ( 'zoho_lead' === $this->form['processors'][ $parent[0] ]['type'] ) {
					$object['SEID']     = $parent_value;
					$object['SEMODULE'] = 'Leads';
				}

				return $object;
		}
	}

	/**
	 * Fetches module fields.
	 * Called when cached fields have expired.
	 *
	 * @return array Module fields.
	 */
	public function get_module_fields() {
		$cf_processor_render = new CF_Processor_Render( $this->module );
		return $cf_processor_render->get_module_data();
	}

	/**
	 * Get the submitted value for a form element.
	 *
	 * @param  array $field Form field data.
	 * @return string        Form field value.
	 */
	public function get_form_value( $field ) {
		$key = sanitize_key( $field['field_label'] );

		if ( ! isset( $this->config[ $key ] ) ) {
			return;
		}
		/*
		 * TODO: Check why this is not working.
		 */
		$value = \Caldera_Forms::do_magic_tags( $this->config[ $key ], null, $this->form );

		if ( $this->is_zoho_form_field( $this->config[ $key ] ) ) {
			$value = $this->do_side_request( '38' );
		}

		if ( 'boolean' !== strtolower( $field['data_type'] ) ) {
			return $value;
		}

		return empty( $value ) ? (bool) false : (bool) true;
	}

	/**
	 * The function which send the build info
	 * @param $path
	 * @param $body
	 * @param $object
	 *
	 * @return array
	 */
	public function do_side_request( $value ) {
		$return = $value;
		$entry = $this->get_entry_meta( $value );
		$entry = maybe_unserialize( $entry );

		if ( is_array( $entry ) ) {
			foreach( $entry as $module => $data ) {
				if ( in_array( $module, array( 'task', 'lead', 'contacts' ) ) ) {

					$path   = '/crm/v2/' . ucfirst( $module );
					$object_id = $this->do_request( $path, $data, $data );

					if ( ! is_wp_error( $object_id ) ) {
						$this->maybe_register_mailer( $return, $object_id, $module );
						$return = $object_id;
					}
				}
			}
		}
		return $return;
	}

	private function get_entry_meta( $entry_id ) {
		global $wpdb;

		$entry_meta_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM `" . $wpdb->prefix . "cf_form_entry_meta` WHERE `entry_id` = %d AND `meta_key` = 'id'",
			$entry_id), ARRAY_A);

		$return = '';
		if ( ! empty( $entry_meta_data ) ) {
			if ( isset( $entry_meta_data[0] ) && isset( $entry_meta_data[0]['meta_value'] ) ) {
				$return = $entry_meta_data[0]['meta_value'];
			}
		}
		return $return;
	}

	private function get_entry_form_id( $entry_id ) {
		global $wpdb;

		$entry_data = $wpdb->get_results($wpdb->prepare("SELECT form_id FROM `" . $wpdb->prefix . "cf_form_entries` WHERE `id` = %d",
			$entry_id), ARRAY_A);

		$return = '';
		if ( ! empty( $entry_data ) ) {
			if ( isset( $entry_data[0] ) && isset( $entry_data[0]['form_id'] ) ) {
				$return = $entry_data[0]['form_id'];
			}
		}
		return $return;
	}

	/**
	 *
	 * @param $send boolean
	 * @param $form object
	 *
	 * @return boolean
	 */
	public function stagger_mailer( $send, $form ) {
		if ( isset( $this->config['_return_information'] ) && ( true === $this->config['_return_information'] || 'true' === $this->config['_return_information'] || 1 === $this->config['_return_information'] ) ) {
			$send = false;
		}
		return $send;
	}


	/**
	 * Check to see if the current field is a zoho form field.
	 * @param string $magic_tag
	 * @return boolean
	 */
	public function is_zoho_form_field( $magic_tag = '' ) {
		$is_zoho = false;
		$current_field = false;
		foreach( $this->form['fields'] as $field ) {
			if ( '%' . $field['slug'] . '%' === $magic_tag ) {
				$current_field = $field;
			}
		}
		if ( false !== $current_field && 'zoho_form' === $current_field['type'] ) {
			$is_zoho = true;
		}
		return $is_zoho;
	}

	/**
	 * Checks to see if we should register a mailer for this form.
	 * @param $entryid
	 * @param $zoho_id
	 * @param $module
	 */
	public function maybe_register_mailer( $entryid, $zoho_id, $module ) {
		$form_id = $this->get_entry_form_id( $entryid );
		$form = \Caldera_Forms::get_form( $form_id );

		if ( ! isset( $form['mailer']['enable_mailer'] ) ) {
			$this->additional_mails[ $form_id ] = $entryid;
		}
	}
}
