<?php
/**
 * Defines processors for Caldera Forms.
 *
 * @package lsx_cf_zoho/includes.
 */

namespace lsx_cf_zoho\includes;

use lsx_cf_zoho\includes\zohoapi;
use lsx_cf_zoho\admin;

/**
 * Processors Class.
 */
class CF_Processors {

	/**
	 * Processor config.
	 *
	 * @var array.
	 */
	public $config = array();

	/**
	 * The current form being processed.
	 *
	 * @var array.
	 */
	public $form = array();

	/**
	 * Module name.
	 *
	 * @var string.
	 */
	public $module = '';

	/**
	 * Contains any additional mails that need to be sent out.
	 *
	 * @var array.
	 */
	public $additional_mails = array();

	/**
	 * Contains the object built and stored.
	 *
	 * @var array
	 */
	public $body = array();

	/**
	 * Contains the submitted Zoho ID
	 *
	 * @var string
	 */
	public $zoho_id = '';

	/**
	 * Holds the Fields class
	 *
	 * @var \lsx_cf_zoho\admin\Settings()
	 */
	var $settings;

	/**
	 * Contains the submitted email
	 *
	 * @var string
	 */
	public $mail = '';

	/**
	 * @var zohoapi\Post
	 */
	public $post = '';

	/**
	 * Prevents duplicate submissions
	 *
	 * @var array
	 */
	public $requests_completed = array();

	/**
	 * Records the extra side requests and their modules.
	 *
	 * @var array
	 */
	public $requests_list = array();

	/**
	 * Holds the array of messages for the log.
	 *
	 * @var array
	 */
	public $logging_array = array();

	/**
	 * Registers our processors with Caldera Forms.
	 *
	 * @param  array $processors Array of current processors.
	 * @return array                Current processors with our added processors
	 */
	public function register_processors( $processors ) {

		$processors['zoho_lead'] = array(
			'name'        => __( 'Zoho CRM - Leads', 'lsx-cf-zoho' ),
			'description' => __( 'Create or Update a lead on form submission', 'lsx-cf-zoho' ),
			'author'      => 'LightSpeed',
			'author_url'  => 'https://lsdev.biz/',
			'processor'   => array( $this, 'process_lead_submission' ),
			'template'    => LSX_CFZ_PROCESSORS_PATH . 'lead-processor-config.php',
			'icon'        => LSX_CFZ_URL . 'assets/images/icon.png',
			'magic_tags'  => array(
				'id' => array( 'text', 'zoho_task' ),
			),
		);

		$processors['zoho_contact'] = array(
			'name'        => __( 'Zoho CRM - Contacts', 'lsx-cf-zoho' ),
			'description' => __( 'Create or Update a contact on form submission', 'lsx-cf-zoho' ),
			'author'      => 'LightSpeed',
			'author_url'  => 'https://lsdev.biz/',
			'processor'   => array( $this, 'process_contact_submission' ),
			'template'    => LSX_CFZ_PROCESSORS_PATH . 'contact-processor-config.php',
			'icon'        => LSX_CFZ_URL . 'assets/images/icon.png',
			'magic_tags'  => array(
				'id' => array( 'text', 'zoho_task' ),
			),
		);

		$processors['zoho_task'] = array(
			'name'        => __( 'Zoho CRM - Tasks', 'lsx-cf-zoho' ),
			'description' => __( 'Create or Update a task on form submission', 'lsx-cf-zoho' ),
			'author'      => 'LightSpeed',
			'author_url'  => 'https://lsdev.biz/',
			'processor'   => array( $this, 'process_task_submission' ),
			'template'    => LSX_CFZ_PROCESSORS_PATH . 'task-processor-config.php',
			'icon'        => LSX_CFZ_URL . 'assets/images/icon.png',
			'magic_tags'  => array( 'id' ),
		);

		$processors['zoho_deal'] = array(
			'name'        => __( 'Zoho CRM - Deals', 'lsx-cf-zoho' ),
			'description' => __( 'Create or Update deals on form submission', 'lsx-cf-zoho' ),
			'author'      => 'LightSpeed',
			'author_url'  => 'https://lsdev.biz/',
			'processor'   => array( $this, 'process_deal_submission' ),
			'template'    => LSX_CFZ_PROCESSORS_PATH . 'deal-processor-config.php',
			'icon'        => LSX_CFZ_URL . 'assets/images/icon.png',
			'magic_tags'  => array( 'id' ),
		);

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
	 * Callback for Deals form submissions.
	 *
	 * @param  array  $config Processor config
	 * @param  array  $form Form config
	 * @param  string $process_id Unique process ID for this submission
	 * @return array.
	 */
	public function process_deal_submission( $config, $form, $process_id ) {

		$this->config = $config;
		$this->form   = $form;
		$this->module = 'deals';

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
		$this->logging_array[] = array(
			'id'         => $id,
			'type'       => $type,
			'response'   => $message,
			'submission' => $submission,
			'details'    => $details,
		);
	}

	/**
	 * Start the logging array.
	 */
	public function start_logging() {
		$this->logging_array = array();
	}

	/**
	 * Start the logging array.
	 */
	public function end_logging( $oject_id = false, $module_message = '' ) {
		if ( ! empty( $this->logging_array ) ) {
			$log_message = array();
			foreach ( $this->logging_array as $log ) {
				$log_message[] = '<h2>' . $log['message'] . ' ' . $log['type'] . '</h2>';
				$log_message[] = array(
					'response'   => $log['message'],
					'submission' => $log['submission'],
					'details'    => $log['details'],
				);
			}

			if ( '' === $module_message ) {
				$module_message = $this->module;
			}

			WP_Logging::add(
				'Submission for ' . $module_message . ' ' . $oject_id . ': ',
				wp_json_encode( $log_message ),
				0,
				''
			);
		}
	}

	/**
	 * Process form submissions.
	 *
	 * @return null|array Array containining id if successfull|null response on fail.
	 */
	public function do_submission() {
		$this->start_logging();
		$this->settings = new admin\Settings();
		/**
		 * TODO: This is where we check to see if we should submit this info or not.
		 */
		add_filter(
			'caldera_forms_send_email',
			array(
				$this,
				'stagger_mailer',
			),
			1,
			2
		);

		add_filter(
			'caldera_forms_ajax_return',
			array(
				$this,
				'filter_ajax_return',
			),
			10,
			2
		);

		add_filter(
			'caldera_forms_mailer',
			array(
				$this,
				'save_mail_fix',
			),
			1,
			1
		);

		add_filter(
			'caldera_forms_mailer',
			array(
				$this,
				'mail_attachment_check',
			),
			20,
			3
		);

		add_action(
			'caldera_forms_mailer_complete',
			array(
				$this,
				'additional_mail_check',
			),
			11,
			4
		);

		$path   = '/crm/v2/' . ucfirst( $this->module );
		$object = $this->build_object();

		if ( isset( $this->config['_allow_duplicates'] ) && 'update' === $this->config['_allow_duplicates'] ) {
			$path                            .= '/upsert';
			$object['duplicate_check_fields'] = array( 'Email' );
		}

		$trigger = array();

		if ( ! empty( $this->config['_approval_mode'] ) ) {
			$trigger[] = 'approval';
		}

		if ( ! empty( $this->config['_workflow_mode'] ) ) {
			$trigger[] = 'workflow';
		}

		$body = array(
			'data'    => array( $object ),
			'trigger' => $trigger,
		);

		// Filter hook.
		$body       = apply_filters( 'process_zoho_submission', $body, $this->config, $this->form );
		$this->body = $body;

		if ( isset( $this->config['_return_information'] ) && ( true === $this->config['_return_information'] || 'true' === $this->config['_return_information'] || 1 === $this->config['_return_information'] ) ) {
			$object_id = $this->capture_info( $this->module, $body, $object );
		} else {
			if ( false === apply_filters( 'lsx_cf_zoho_skip_do_request', false, $this->form ) ) {
				$object_id     = $this->do_request( $path, $body, $object );
				$this->zoho_id = $object_id;
			} else {
				$object_id     = false;
				$this->zoho_id = $object_id;
				$this->log( 'Zoho Skipped', 'Zoho Skipped', 'Zoho Skipped', 0, 'zoho-skipped' );
			}

			// This is where the actions are run to link the items.
			do_action( 'lsx_cf_zoho_do_submission_complete', $object_id, $this->module, $this->requests_list, $this );
		}

		do_action( 'lsx_cf_zoho_create_entry_complete', $object_id, $this->config, $this->form );

		$this->end_logging( $object_id );

		return array(
			'id' => $object_id,
		);
	}

	public function capture_info( $module, $body, $object ) {
		/**
		 * TODO: if the form is capturing entries, then serialize the data and return that for saving.
		 */
		return serialize(
			array(
				$module => $body,
			)
		);
	}

	/**
	 * The function which send the build info
	 *
	 * @param $path
	 * @param $body
	 * @param $object
	 * @param $has_attachments
	 * @param $method
	 *
	 * @return array
	 */
	public function do_request( $path, $body, $object, $has_attachments = false, $method = 'POST' ) {
		$post       = new zohoapi\Post();
		$this->post = $post;
		$this->log( $path, $body, $path, 0, 'do-request-data' );

		$response = $post->request( $path, $body, false, $has_attachments, $method );

		if ( is_wp_error( $response ) ) {
			$this->log( $response->get_error_message(), $response, 'WordPress Error', 0, 'do-request-error' );
			return $response->get_error_message();
		}

		// Fallback request.
		if ( isset( $response['code'] ) && 'INVALID_DATA' === (string) $response['code'] && isset( $response['details']['expected_data_type'] ) && 'jsonobject' === (string) $response['details']['expected_data_type'] ) {
			$this->log( $path, $body, $path, 0, 'fallback-request' );
			$response = $post->request( $path, $body, false, $has_attachments, $method );
		}

		if ( ! isset( $response['data'][0]['code'] ) || ( 'SUCCESS' !== $response['data'][0]['code'] && 'DUPLICATE_DATA' !== $response['data'][0]['code'] ) ) {

			$error_response = array(
				'path'     => $path,
				'body'     => $body,
				'response' => $response,
			);
			$this->log( $response['data'][0]['message'], $error_response, 'Zoho Error', 0, 'error' );

			return $response['data'][0]['message'] . ' - ' . print_r( $response['data'][0]['details'], true );
		}

		$object_id = $response['data'][0]['details']['id'];

		// TODO THIS IS WHERE THE EXTRA FILTER GOES.
		$this->log( $response, $body, 'Successful Response', $object_id, 'do_request' );

		return $object_id;
	}

	/**
	 * Build object for the module.
	 *
	 * @return array Object for the module.
	 */
	public function build_object() {

		$object = $this->get_default_object();

		$cache  = new Cache();
		$fields = $cache->get_plugin_cache_item( $this->module );

		// If fields aren't cached, fetch them.
		if ( false === $fields ) {
			$fields = $this->get_module_fields();
		}

		$fields       = apply_filters( 'lsx_cf_zoho_object_fields', $fields, $this->module, $this->form );
		$this->config = apply_filters( 'lsx_cf_zoho_processor_config', $this->config, $this->module, $this->form );

		foreach ( $fields as $section ) {

			foreach ( $section['fields'] as $field ) {

				$value = $this->get_form_value( $field );

				$value = trim( $value );
				if ( '-None-' === $value || '--None--' === $value || false === $value ) {
					continue;
				}
				$label = str_replace( ' ', '_', $field['field_label'] );

				/**
				 * TODO: Change this to a preg_match
				 */
				if ( 'Lead_Owner' === $label || 'Task_Owner' === $label || 'Contact_Owner' === $label ) {
					$label = 'Owner';
				}

				$label            = str_replace( '.', '', $label );
				$object[ $label ] = $this->get_form_value( $field );
			}
		}

		// Check for the Layout and change it to an array.
		if ( isset( $object['Layout'] ) && '' !== $object['Layout'] ) {
			$layout = $object['Layout'];
			$layout = explode( '|', $layout );
			if ( is_array( $layout ) && 2 <= count( $layout ) ) {
				$object['Layout'] = array(
					'name' => $layout[0],
					'id'   => $layout[1],
				);
			}
		}

		// Check for the Layout and change it to an array.
		if ( isset( $object['Owner'] ) && '' !== $object['Owner'] ) {
			$layout = $object['Owner'];
			$layout = explode( '|', $layout );
			if ( is_array( $layout ) && 2 <= count( $layout ) ) {
				$object['Owner'] = array(
					'name' => $layout[0],
					'id'   => $layout[1],
				);
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
				$object = array(
					'Email_Opt_Out' => ! empty( $this->config['_email_opt_out'] ) ? (bool) true : (bool) false,
					'Description'   => '',
				);

				$unset_if_empty = array(
					'leadowner'  => 'SMOWNERID',
					'leadsource' => 'Lead_Source',
					'leadstatus' => 'Lead_Status',
					'rating'     => 'Rating',
				);

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
				$object = array(
					'Due_Date'  => '',
					'Subject'   => '',
					'Status'    => '',
					'SMOWNERID' => '',
				);

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

		$zoho_field = $this->is_zoho_form_field( $this->config[ $key ] );
		if ( false !== $zoho_field ) {
			$new_values = array();
			if ( isset( $_POST[ $zoho_field ] ) ) { // @codingStandardsIgnoreLine
				$values = explode( ',', $_POST[ $zoho_field ] ); // @codingStandardsIgnoreLine

				if ( ! is_array( $values ) ) {
					$values = array( $values );
				}
				foreach ( $values as $entryid ) {
					if ( ! in_array( $entryid, $this->requests_completed ) ) {
						$return = $this->do_side_request( $entryid );
						$this->update_entry( $entryid, $return );
						$new_values[]               = $return;
						$this->requests_completed[] = $entryid;
					}
				}
			}

			if ( ! empty( $new_values ) ) {
				$new_values = implode( ',', $new_values );
				$value      = $new_values;
			}
			$value = apply_filters( 'lsx_cf_zoho_object_build_value', $new_values, $key, $field );
		}

		if ( 'boolean' !== strtolower( $field['data_type'] ) ) {
			return $value;
		}

		return empty( $value ) ? (bool) false : (bool) true;
	}

	/**
	 * The function which send the build info
	 *
	 * @param $path
	 * @param $body
	 * @param $object
	 *
	 * @return array
	 */
	public function do_side_request( $value ) {
		$return = $value;
		$entry  = $this->get_entry_meta( $value );

		$this->log( 'Entry Meta Unserialized', print_r( $entry, true ), 'Do Side Request Meta', 0, 'side-request-meta' );
		$entry = maybe_unserialize( $entry );

		if ( is_array( $entry ) ) {
			foreach ( $entry as $module => $data ) {
				if ( in_array( $module, array( 'task', 'lead', 'contacts' ) ) ) {

					$path                                      = '/crm/v2/' . ucfirst( $module );
					$path                                     .= '/upsert';
					$data['data'][0]['duplicate_check_fields'] = 'Email';

					$object_id = $this->do_request( $path, $data, $data );

					if ( ! is_wp_error( $object_id ) ) {
						$this->maybe_register_mailer( $return, $object_id, $module );
						$return = $object_id;

						$this->log( 'Que Contact Role', $return, 'Que Contact Role', 0, 'request-list-addition' );
						// This registers the module to be linked.
						$this->requests_list[ $module ][] = $return;
					} else {
						$this->log( 'Side Request Error', $object_id, 'Side Request Error', 0, 'side-request-error' );
					}
				}
			}
		}
		return $return;
	}

	public function get_entry_meta( $entry_id ) {
		global $wpdb;

		$entry_meta_data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM `{$wpdb->prefix}cf_form_entry_meta` WHERE `entry_id` = '%s' AND `meta_key` = 'id'",
				$entry_id
			),
			ARRAY_A
		);

		$return = '';
		if ( ! empty( $entry_meta_data ) ) {
			if ( isset( $entry_meta_data[0] ) && isset( $entry_meta_data[0]['meta_value'] ) ) {
				$return = $entry_meta_data[0]['meta_value'];
			}
		}
		return $return;
	}

	public function get_entry_form_id( $entry_id ) {
		global $wpdb;

		$entry_data = $wpdb->get_results(
			$wpdb->prepare(
				'SELECT form_id FROM `' . $wpdb->prefix . 'cf_form_entries` WHERE `id` = %d',
				$entry_id
			),
			ARRAY_A
		);

		$return = '';
		if ( ! empty( $entry_data ) ) {
			if ( isset( $entry_data[0] ) && isset( $entry_data[0]['form_id'] ) ) {
				$return = $entry_data[0]['form_id'];
			}
		}
		return $return;
	}

	public function update_entry( $entry_id, $object_id ) {
		global $wpdb;
		$entry_obj       = new \Caldera_Forms_Entry( $this->form, $entry_id );
		$entry_meta_data = $wpdb->get_results(
			$wpdb->prepare(
				"UPDATE `{$wpdb->prefix}cf_form_entry_meta` SET
				`meta_value` = '%s'
				WHERE `entry_id` = '%d'
				AND `meta_key` = 'id'",
				$object_id,
				$entry_id
			),
			ARRAY_A
		);
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
	 *
	 * @param string $magic_tag
	 * @return boolean
	 */
	public function is_zoho_form_field( $magic_tag = '' ) {
		$is_zoho       = false;
		$current_field = false;
		foreach ( $this->form['fields'] as $field ) {
			if ( '%' . $field['slug'] . '%' === $magic_tag ) {
				$current_field = $field;
			}
		}
		if ( false !== $current_field && 'zoho_form' === $current_field['type'] ) {
			$is_zoho = $current_field['ID'];
		}
		return $is_zoho;
	}

	/**
	 * Checks to see if we should register a mailer for this form.
	 *
	 * @param $entryid
	 * @param $zoho_id
	 * @param $module
	 */
	public function maybe_register_mailer( $entryid, $zoho_id, $module ) {
		$form_id = $this->get_entry_form_id( $entryid );
		$form    = \Caldera_Forms::get_form( $form_id );

		if ( ! isset( $form['mailer']['enable_mailer'] ) ) {
			$this->additional_mails[ $entryid ] = array(
				'form'    => $form,
				'zoho_id' => $zoho_id,
				'module'  => $module,
			);
		}
	}

	/**
	 * Filter the ajax return and maybe add our output
	 *
	 * @param $out
	 * @param $form
	 *
	 * @return mixed
	 */
	public function filter_ajax_return( $out, $form ) {
		if ( isset( $this->config['_return_information'] ) && ( true === $this->config['_return_information'] || 'true' === $this->config['_return_information'] || 1 === $this->config['_return_information'] ) ) {

			$return_message = $this->config['return_message'];

			if ( isset( $this->body['data'] ) && isset( $this->body['data'][0] ) ) {
				foreach ( $this->body['data'][0] as $field_key => $field_value ) {
					$search         = '[' . strtolower( $field_key ) . ']';
					$return_message = str_replace( $search, (string) $field_value, $return_message );
				}
			}
			$out['return_message'] = '<div data-entry-id="' . $out['data']['cf_id'] . '" class="alert alert-success fade in">' . $return_message . '<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a></div>';
		}

		if ( true === (bool) $this->settings->options->get_option( 'lsx_cf_zoho_enable_form_blocker' ) ) {
			$out['html'] .= '<script>lsx_cf_zoho.unblockForms();</script>';
		}
		$out['html'] .= print_r( $this->settings->options->get_option( 'lsx_cf_zoho_enable_form_blocker' ), true );
		$out['html'] . 'Submission Test';
		return $out;
	}

	/**
	 * @param $mail
	 * @param $data
	 * @param $form
	 */
	public function save_mail_fix( $mail ) {
		$this->mail = $mail;
		return $mail;
	}

	/**
	 * Prepare upload PDF attachments to zoho.
	 * Attach passports to email
	 *
	 * @param array $mail Email data
	 * @param array $data ?
	 * @param array $form For config
	 *
	 * @return array
	 */
	public function mail_attachment_check( $mail, $data, $form ) {
		global $transdata;

		if ( '' !== $this->zoho_id ) {
			$this->start_logging();
			$mail = apply_filters( 'lsx_cf_zoho_mail_attachment_check', $mail, $this->zoho_id, $data, $form, $this );
			$this->check_for_files( $mail, $data, $form );

			// Update the trip and attach the PDF
			/*
			if ( ! empty( $transdata['pdf_attachment'] ) ) {
				foreach ( $transdata['pdf_attachment'] as $file_path ) {
					$this->upload_file( $file_path );
				}
			}*/
			$this->end_logging( $this->zoho_id, 'File Upload' );
		}
		return $mail;
	}

	/**
	 * Prepare upload PDF attachments to zoho.
	 * Attach passports to email
	 *
	 * @param array $mail Email data
	 * @param array $data ?
	 * @param array $form For config
	 *
	 * @return array
	 */
	public function check_for_files( $mail, $data, $form ) {
		foreach ( $form['fields'] as $field ) {
			if ( 'file' === $field['type'] && isset( $data[ $field['ID'] ] ) && '' !== $data[ $field['ID'] ] ) {
				$file_path = ABSPATH . 'wp-content/';
				$url_paths = explode( '/wp-content/', $data[ $field['ID'] ] );
				if ( is_array( $url_paths ) && isset( $url_paths[1] ) ) {
					$file_path .= $url_paths[1];
					$module_id  = false;
					$module_id  = apply_filters( 'lsx_cf_zoho_file_upload_module_id', $module_id, $form );

					$module_name = false;
					$module_name = apply_filters( 'lsx_cf_zoho_file_upload_module_name', $module_name, $form );
					$this->upload_file( $file_path, $module_id, $module_name, $form );
				}
			}
		}
		return $mail;
	}

	/**
	 * Does the request to upload the file.
	 *
	 * @param $file_path string
	 * @param $forced_id boolean
	 * @param $forced_module boolean
	 * @param $form array | boolean
	 * @return void | array
	 */
	public function upload_file( $file_path, $forced_id = false, $forced_module = false, $form = false ) {

		$zoho_id = $this->zoho_id;
		if ( false !== $forced_id ) {
			$zoho_id = $forced_id;
		}

		$module = $this->module;
		if ( false !== $forced_module ) {
			$module = $forced_module;
		}

		$should_skip = apply_filters( 'lsx_cf_zoho_skip_file_upload', false, $module, $zoho_id, $form );
		if ( true === $should_skip ) {
			return;
		}

		$path       = '/crm/v2/' . ucfirst( $module ) . '/' . $zoho_id . '/Attachments';
		$post       = new zohoapi\Post();
		$attach_url = false;

		if ( false === $attach_url ) {
			if ( function_exists( 'curl_file_create' ) ) { // php 5.6+
				$body = curl_file_create( $file_path );
			} else {
				$body = '@' . realpath( $file_path );
			}
			$response = $post->send_file( $path, $body );
		} else {
			$file_url = str_replace( '/httpdocs', '', $file_path );
			$body     = $file_url;
			$response = $post->send_file( $path, $body, true );
		}

		if ( is_wp_error( $response ) ) {

			$this->log( $response->get_error_message(), $body, 'WordPress Error', 0, 'error-wp' );

			return array(
				'note' => $response->get_error_message(),
				'type' => 'error',
			);
		}

		if ( ! isset( $response['data'][0]['code'] ) || 'SUCCESS' !== $response['data'][0]['code'] ) {

			$this->log( $response['data'][0]['message'], $body, array( print_r( $response, true ), $path ), 0, 'error-zoho' );

			return array(
				'note' => print_r( $response, true ),
				'type' => 'error',
			);
		}

		$object_id = $response['data'][0]['details']['id'];
		$this->log( $response['data'][0]['message'], $body, array( print_r( $response['data'][0]['details'], true ), $path ), $object_id, 'uploaded' );
	}

	/**
	 * Checks for any additional mails and sends them
	 *
	 * @param array  $mail Email data
	 * @param array  $data Form entry data
	 * @param array  $form The form config
	 * @param string $method Method for sending email
	 */
	public function additional_mail_check( $mail, $data, $form, $method ) {
		$this->log( 'Additional Mails', $this->additional_mails, 'Additional Mails', 0, 'email' );
		if ( ! empty( $this->additional_mails ) ) {
			global $form;
			$saved_form = $form;
			$saved_id   = $this->zoho_id;

			remove_filter(
				'caldera_forms_send_email',
				array(
					$this,
					'stagger_mailer',
				),
				1
			);
			/*
			remove_filter( 'caldera_forms_mailer', array(
				$this,
				'mail_attachment_check',
			), 11 );*/
			remove_action(
				'caldera_forms_mailer_complete',
				array(
					$this,
					'additional_mail_check',
				),
				11
			);

			foreach ( $this->additional_mails as $entry_id => $values ) {
				$form          = $values['form'];
				$this->zoho_id = $values['zoho_id'];

				\Caldera_Forms_Save_Final::do_mailer( $values['form'], $entry_id );
				do_action( 'lsx_cf_zoho_additional_mail_check', $entry_id, $values );
				$this->log( $entry_id . ' Email Sent', $values, 'Email Sent', 0, 'email' );
			}
			$form          = $saved_form;
			$this->zoho_id = $saved_id;
		}

		return $mail;
	}
}
