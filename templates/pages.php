<?php
/**
 * CF Zoho Page Structures
 *
 * @package   cf_zoho
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 David Cramer
 */

return array(
	'cf-zoho'	=>	array(
		'page_title'	=>	__( 'Zoho CRM for Caldera Forms', 'cf-zoho' ),
		'menu_title'	=>	__( 'CF Zoho', 'cf-zoho' ),
		'capability'	=>	'manage_options',
		'parent'		=>	'options-general.php',
		'position'		=>	null,
		'save_button'	=>  __('Update Settings', 'cf-zoho'),
		'saved_message'	=>	__('Updated Successfully!', 'cf-zoho'),
		'styles'		=>	array(),
		'scripts'		=>	array(),
		'tabs'			=>	array(
			'main'		=>	array(
				'page_title'	=> 	__('Zoho Connection', 'cf-zoho'),
				'page_description'	=> 	__('setup zoho auth tokens', 'cf-zoho'),
				'menu_title'	=> 	__('General', 'cf-zoho'),
				'default'		=>	true,
				'template'		=>	CF_ZOHO_PATH . 'templates/zoho-connection-ui.php',
				'partials'		=>	array()
			),
			'fields'		=>	array(
				'page_title'	=> 	__('Field Setup', 'cf-zoho'),
				'page_description'	=> 	'',
				'menu_title'	=> 	__('Fields', 'cf-zoho'),
				'default'		=>	false,
				'template'		=>	CF_ZOHO_PATH . 'templates/zoho-fields-ui.php',
				'partials'		=>	array()
			)
		),
		'help'	=> array(
			'default-help' => array(
				'title'		=> 	esc_html__( 'Getting the Token' , 'cf-zoho' ),
				'content'	=>	esc_html__( 'Click the "Get Token" button. Once you log into the window, you will see a text block with "AUTHTOKEN=". Select the number that follows and copy it. Then paste it in the Token input on this page. You can close the popup window.', 'cf-zoho' )
			)
		)
	)
);