<?php
/**
 * Defines processors for Caldera Forms.
 *
 * @package cf_zoho/includes.
 */

namespace cf_zoho\includes;
use cf_zoho\includes\zohoapi;

/**
 * Processors Class.
 */
class CF_Processors {

    /**
     * Registers our processors with Caldera Forms.
     *
     * @param  array    $processors Array of current processors.
     * @return array	            Current processors with our added processors
     */
    public function register_processors( $processors ) {

        $processors['zoho_lead'] = [
            'name'              =>  __( 'Zoho CRM - Create Lead', 'cf-zoho-2' ),
            'description'       =>  __( 'Create or Update a lead on form submission', 'cf-zoho-2' ),
            'author'            =>  'Matt Bush',
            'author_url'        =>  'https://haycroftmedia.com/',
            'processor'			=>  [ $this, 'process_lead_submission' ],
            'template'          =>  CFZ_PROCESSORS_PATH . 'lead-processor-config.php',
            'icon'				=>	CFZ_URL . 'assets/images/icon.png',
            'magic_tags'		=> [
                'id' => [ 'text', 'zoho_task' ],
            ],
        ];

        $processors['zoho_contact'] = [
            'name'              =>  __( 'Zoho CRM - Create Contact', 'cf-zoho-2' ),
            'description'       =>  __( 'Create or Update a contact on form submission', 'cf-zoho-2' ),
            'author'            =>  'Matt Bush',
            'author_url'        =>  'https://haycroftmedia.com/',
            'processor'			=>  [ $this, 'process_contact_submission' ],
            'template'          =>  CFZ_PROCESSORS_PATH . 'contact-processor-config.php',
            'icon'				=>	CFZ_URL . 'assets/images/icon.png',
             'magic_tags'		=> [
                'id' => [ 'text', 'zoho_task' ],
            ],
        ];	
/*
        $processors['zoho_task'] = [
            'name'              =>  __( 'Zoho CRM - Create Task', 'cf-zoho-2' ),
            'description'       =>  __( 'Create or Update a task on form submission', 'cf-zoho-2' ),
            'author'            =>  'Matt Bush',
            'author_url'        =>  'https://haycroftmedia.com/',
            'processor'			=>  [ $this, 'process_task_submission' ],
            'template'          =>  CFZ_PROCESSORS_PATH . 'task-processor-config.php',
            'icon'				=>	CFZ_URL . 'assets/images/icon.png',
            'magic_tags'		=> [ 'id' ],
        ];
*/
        return $processors;
    }

    public function get_form_value( $config, $field ) {
        
        $key = sanitize_key( $field['field_label'] );

        if( ! isset( $config[ $key ] ) ) {
            return;
        }

        $value = \Caldera_Forms::do_magic_tags( $config[ $key ] );

        if ( 'boolean' !== strtolower( $field['data_type'] ) ) {
            return $value;
        }

        $true_options = [ 'Yes','yes', 'True', '1' ];
        
        foreach ( $true_options as $true_option ) {
            $value = str_replace( $true_option, 'true', $value );
        }

        $false_options = [ 'No', 'no', 'False', '0' ];
        
        foreach ( $false_options as $false_option ) {
            $value = str_replace( $false_option, 'false', $value );
        }

        return $value;
    }

    public function process_lead_submission( $config, $form ) {

        $cache  = new Cache();
        $fields = $cache->get_plugin_cache_item( 'leads' ); // @todo need fallback if empty.

        $object = [
            'Email Opt Out' => ! empty( $config['_email_opt_out'] ) ? 'true' : 'false',
            'Description'   => '',
            'Lead Source'   => $config['leadsource'],
            'Lead Status'   => '',
            'Rating'        => '',		
            'SMOWNERID'     => $config[ 'leadowner' ],
            'options'       => [
                'isApproval'     => ! empty( $config['_approval_mode'] ) ? 'true' : 'false',
                'wfTrigger'      => ! empty( $config['_workflow_mode'] ) ? 'true' : 'false',
            ],
        ];

        foreach ( $fields as $section ) {

            foreach ( $section['fields'] as $field ) {

                $label = str_replace( ' ', '_', $field['field_label'] );
                $object[ $label ] = $this->get_form_value( $config, $field );
            }
        }

        $post = new zohoapi\Post();
        $path = '/crm/v2/Leads';

        $response = $post->request( $path, $object );
error_log(print_r($response,true));
        return;
    }
    
    public function process_contact_submission( $config, $form ){
        return;
    }
    
    public function process_task_submission( $config, $form ){
        return;
    }
    
}
