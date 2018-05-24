<?php
/*
* Plugin Name: Zoho CRM Addon for Caldera Forms
* Plugin URI: https://www.lsdev.biz/product/caldera-forms-zoho-crm-addon/
* Description: Caldera Forms is one of a kind WordPress form builder. With its user friendly drag and drop interface, it’s simple to create forms for your WordPress site that look awesome on any device. Caldera also comes with a range of add-ons, like integration with the Zoho CRM platform, which allows users to automate their day-to-day business activities allowing them to focus on selling without having to worry about digging through data. Use the extension to track your sales activities and gain complete understanding of your sales cycle.
* Author: LightSpeed
* Version: 1.1.2
* Author URI: https://www.lsdev.biz/products/
* License: GPL2+
* Text Domain: replaceme
* Domain Path: /languages/
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('CF_ZOHO_PATH',  plugin_dir_path( __FILE__ ) );
define('CF_ZOHO_CORE',  __FILE__ );
define('CF_ZOHO_URL',  plugin_dir_url( __FILE__ ) );
define('CF_ZOHO_VER',  '1.1.2' );

// Load instance
add_action( 'plugins_loaded', 'cf_zoho_bootstrap' );
function cf_zoho_bootstrap(){
	// include the libraries
	include_once CF_ZOHO_PATH . 'uix/uix.php';
	include_once CF_ZOHO_PATH . 'classes/class-zoho.php';

	// get the pages
	$pages = include CF_ZOHO_PATH . 'templates/pages.php';

	// initialize admin UI
	\cf_zoho\ui\uix::get_instance( $pages );
}

if(!class_exists('LSX_CF_API_Manager')){
	require_once('classes/lsx-api-manager-class.php');
}

/** 
 *	Grabs the email and api key from the LSX TO Settings.
 */
function cf_zoho_lsx_options_pages_filter($pages){
	$pages[] = 'cf-zoho';
	return $pages;
}
add_filter('lsx_api_manager_options_pages','cf_zoho_lsx_options_pages_filter',10,1);


function cf_zoho_lsx_api_admin_init(){
	cf_zoho_get_api_details();
}
add_action('admin_head','cf_zoho_lsx_api_admin_init');

/** 
 *	Grabs the email and api key from the LSX TO Settings.
 */
function cf_zoho_get_api_details(){
	$options = get_option('_uix_cf-zoho',false);
	$data = array('api_key'=>'','email'=>'');

	if(false !== $options && isset($options['main'])){
		if(isset($options['main']['api_key']) && '' !== $options['main']['api_key']){
			$data['api_key'] = $options['main']['api_key'];
		}
		if(isset($options['main']['api_email']) && '' !== $options['main']['api_email']){
			$data['email'] = $options['main']['api_email'];
		}
	}

	$api_array = array(
		'product_id'	=>		'Caldera Forms Zoho CRM Addon',
		'version'		=>		'1.0.0',
		'instance'		=>		get_option('lsx_api_instance',false),
		'email'			=>		$data['email'],
		'api_key'		=>		$data['api_key'],
		'file'			=>		'plugin.php'
	);
	$cf_zoho_api_manager = new LSX_CF_API_Manager($api_array);
}


/**
 * Run when the plugin is active, and generate a unique password for the site instance.
 */
function cf_zoho_activate_plugin() {
    $instance = get_option('lsx_api_instance',false);
    if(false === $instance){
    	update_option('lsx_api_instance',LSX_API_Manager::generatePassword());
    }
}
register_activation_hook( __FILE__, 'cf_zoho_activate_plugin' );

/**
 * Registers the processor with Caldera Forms
 *
 * @since 1.0.0
 *
 *
 * @param array 	$processors		array of current processors
 *
 * @return array	list of processors with added processors
 */
function cf_zoho_register_processor($pr){
	$pr['zoho_lead'] = array(
		"name"              =>  __('Zoho CRM - Create Lead', 'cf-zoho'),
		"description"       =>  __("Create or Update a lead on form submission", 'cf-zoho'),
		"author"            =>  'David Cramer',
		"author_url"        =>  'http://cramer.co.za',
		"processor"			=>  'cf_zoho_process_lead_submission',
		"template"          =>  CF_ZOHO_PATH . "templates/lead-processor-config.php",
		"icon"				=>	CF_ZOHO_URL . "assets/images/icon.png",
		"magic_tags"		=> array(
			'id' => array('text','zoho_task'),
		)
	);
	$pr['zoho_contact'] = array(
		"name"              =>  __('Zoho CRM - Create Contact', 'cf-zoho'),
		"description"       =>  __("Create or Update a contact on form submission", 'cf-zoho'),
		"author"            =>  'David Cramer',
		"author_url"        =>  'http://cramer.co.za',
		"processor"			=>  'cf_zoho_process_contact_submission',
		"template"          =>  CF_ZOHO_PATH . "templates/contact-processor-config.php",
		"icon"				=>	CF_ZOHO_URL . "assets/images/icon.png",
		"magic_tags"		=> array(
			'id' => array('text','zoho_task'),
		)
	);	
	$pr['zoho_task'] = array(
		"name"              =>  __('Zoho CRM - Create Task', 'cf-zoho'),
		"description"       =>  __("Create or Update a task on form submission", 'cf-zoho'),
		"author"            =>  'David Cramer',
		"author_url"        =>  'http://cramer.co.za',
		"processor"			=>  'cf_zoho_process_task_submission',
		"template"          =>  CF_ZOHO_PATH . "templates/task-processor-config.php",
		"icon"				=>	CF_ZOHO_URL . "assets/images/icon.png",
		"magic_tags"		=> array(
			'id',
		)
	);	
	return $pr;
}
// add filter to add processor to Caldera Forms
add_filter('caldera_forms_get_form_processors', 'cf_zoho_register_processor');

function cf_zoho_process_lead_submission( $config, $form ){
	return cf_zoho_create_entry( $config, $form, "lead" );
}

function cf_zoho_process_contact_submission( $config, $form ){
	return cf_zoho_create_entry( $config, $form, "contact" );
}
function cf_zoho_process_task_submission( $config, $form ){
	if( strlen( $config['duedate'] > 0 ) ){
		$config['duedate'] = date('Y-m-d', strtotime( "+ ". $config['duedate'] ." days" ) );
	}

	return cf_zoho_create_entry( $config, $form, "task" );
}

function cf_zoho_create_entry( $config, $form, $type = "contact" ){
	global $transdata;
	$fields = cf_zoho_get_fields();
	$zoho_api = cf_zoho_connect();
	if( $zoho_api === false ){
		return;
	}

	if( $type == 'task' ){
		/* Create task object. */
		$object = array(
			'Due Date'  => '',
			'Subject'   => '',
			'Status'    => '',
			'SMOWNERID' => '',
			'options' => array()
		);


		// get parent if selected
		if( !empty( $config['parent'] ) ){
			$parent = explode( ':', trim( $config['parent'], '{}' ) );
			if( !empty( $form['processors'][ $parent[0] ] ) ){
				$parent_value = \Caldera_Forms::do_magic_tags( $config['parent'] );
				/* Add lead or contact ID. */
				if ( $form['processors'][ $parent[0] ]['type'] === 'zoho_contact' ) {
					$object['CONTACTID'] = $parent_value;
				} else if ( $form['processors'][ $parent[0] ]['type'] === 'zoho_lead' ) {
					$object['SEID']     = $parent_value;
					$object['SEMODULE'] = 'Leads';
				}
				
			}else{
				$config['whoid'] = '%' . $form['fields'][ $config['parent'] ]['slug']. '%';
			}
		}

	}else{
		/* Create contact object. */
		$object = array(
			//'Email Opt Out' => ( !empty( $config['_email_opt_out'] ) ? 'true' : 'false' ),
			'Description'   => '',
			'Lead Source'   => $config['leadsource'],
			'Lead Status'   => '',
			'Rating'        => '',		
			'SMOWNERID'     => $config[ $type . 'owner' ],
			'options'       => array(
				'isApproval'     => ( !empty( $config['_approval_mode'] ) ? 'true' : 'false' ),
				'wfTrigger'      => ( !empty( $config['_workflow_mode'] ) ? 'true' : 'false' ),
			)
		);
	}

	/* If duplicates are allowed, remove the duplicate check. */
	if ( ! empty( $config['_allow_duplicates'] ) ){
		if ( 'allow' === $config['_allow_duplicates'] ) {
			// do nothing
		} else if ( 'update' === $config['_allow_duplicates'] ) {
			$object['options']['duplicateCheck'] = 2;
		} else if ( 'none' === $config['_allow_duplicates'] ) {
			$object['options']['duplicateCheck'] = 1;
		}
	}

		//print_r($config);
	//print_r($object);

	if( !empty( $config['leadstatus'] ) ){
		$object['Lead Status'] = $config['leadstatus'];
	}else{
		unset( $config['leadstatus'] );
	}
	if( !empty( $config['rating'] ) ){
		$object['Rating'] = $config['rating'];
	}else{
		unset( $config['rating'] );
	}

	//print_r( $config );

	foreach( $fields[ $type ]['section'] as $section ){
		if( !empty( $section['FL'] ) && !isset( $section['FL'][0] ) ){
			$section['FL'] = array( $section['FL'] );
		}
		foreach( $section['FL'] as $field ){
			$key = sanitize_key( $field['label'] );
			$value = null;
			if( isset( $config[ $key ] ) ){
				$value = \Caldera_Forms::do_magic_tags( $config[ $key ] );

				if ( 'Boolean' === $field['type'] ) {

					$true_options = array( 'Yes','yes', 'True', '1' );
					foreach ( $true_options as $true_option ) {
						$value = str_replace( $true_option, 'true', $value );
					}
					$false_options = array( 'No', 'no', 'False', '0' );
					foreach ( $false_options as $false_option ) {
						$value = str_replace( $false_option, 'false', $value );
					}
				}
			}
			$object[ $field['dv'] ] = $value;
		}
	}

	/* Remove SMOWNERID if not set. */
	if ( empty( $object['SMOWNERID'] ) ) {
		unset( $object['SMOWNERID'] );
	}
	// Filter hook
	$object = apply_filters( 'cf_zoho_create_entry', $object, $config, $form );

	/* Prepare contact record XML. */
	$object_xml  = '<' . ucfirst( $type ) . 's>' . "\r\n";
	$object_xml .= '<row no="1">' . "\r\n";
	
	foreach ( $object as $field_key => $field_value ) {
		
		if ( is_array( $field_value ) )
			continue;
		
		if ( $field_key === 'Description' || strpos($field_value,"&") ){
			$field_value = '<![CDATA[ ' . $field_value . ' ]]>';
		}

		$object_xml .= '<FL val="' . $field_key . '">' . $field_value . '</FL>' . "\r\n";
		
	}
	
	$object_xml .= '</row>' . "\r\n";
	$object_xml .= '</' . ucfirst( $type ) . 's>' . "\r\n";
	try {
	
		/* Insert record. */
		$object_record = $zoho_api->insert_record( ucfirst( $type ) . 's', $object_xml, $object['options'] );
		if( is_string( $object_record ) ){
			return array('error' => $object_record );
		}
		/* Get ID of new record. */
		$object_id = 0;
		foreach ( $object_record->result->recorddetail as $detail ) {
			
			foreach ( $detail->children() as $field ) {
				
				if ( $field['val'] == 'Id' ) {
					
					$object_id = (string) $field;
					
				}
			}
		}
		// action for after creation
		do_action('cf_zoho_create_entry_complete', $object_id, $config, $form );
		return array( 'id' => $object_id );
	
	} catch ( Exception $e ) {
		
		var_dump( $e->getMessage() );
		die;
		return null;
		
	}

}

function cf_zoho_connect(){
	$config_object = get_option( "_uix_cf-zoho", array() );	
	$connect = false;
	if( !empty( $config_object['main']['token'] ) ){
		$connect = new \CF_Zoho_CRM( $config_object['main']['token'] );
	}
	/*print_r('<pre>');
	print_r($connect);
	print_r('</pre>');*/
	return $connect;
}

function cf_zoho_get_fields( $cache = true ){
	
	if( true === $cache ){
		$fields = get_transient( '_uix_cf-zoho' );
	}else{
		$fields = array();
	}

	$connect = cf_zoho_connect();
	if( !is_object( $connect ) ){
		return false;
	}
	if( empty( $fields ) ){
		$fields = array();
		$leads = $connect->get_fields('Leads');
		$contact = $connect->get_fields('Contacts');
		$task = $connect->get_fields('Tasks');
		if( is_string( $leads ) ){
			return array('error' => $leads );
		}
		$fields['lead'] = $leads;
		$fields['contact'] = $contact;
		$fields['task'] = $task;
		set_transient( '_uix_cf-zoho', $fields, DAY_IN_SECONDS/2 );
	}
	return $fields;
}