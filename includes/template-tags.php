<?php
/**
 * Template Tags
 *
 * @package   LSX CF Zoho
 * @author    LightSpeed
 * @license   GPLv3.0+
 * @link
 * @copyright 2019 LightSpeed
 */

/**
 * LSX CF Zoho Options page URL.
 * Used to populate redirect_uri field in Zoho requests.
 * Can't use menu_page_url in Zoho requests so built this instead.
 *
 * @return string LSX CF Zoho Options page URL.
 */
function lsx_cf_zoho_redirect_url() {
	return admin_url( add_query_arg( 'page', 'lsx_cf_zoho', 'options-general.php' ) );
}

/**
 * Returns the names and ids of the current available caldera forms.
 *
 * @return bool
 */
function lsx_cf_zoho_get_caldera_forms() {
	$results = \Caldera_Forms_Forms::get_forms( true );
	$forms   = false;

	if ( ! empty( $results ) ) {
		foreach ( $results as $form => $form_data ) {
			$forms[ $form ] = $form_data['name'];
		}
	}
	return $forms;
}

/**
 * Registers a caldera form to output as a modal in the footer
 *
 * @param $caldera_id string
 * @param $field_id string
 * @param $limit int
 * @param $title string
 */
function lsx_cf_zoho_register_modal( $caldera_id = '', $field_id = '', $limit = 1, $title = '' ) {
	if ( '' !== $caldera_id && '' !== $field_id ) {
		$cf_zoho = lsx_cf_zoho\includes\CF_Zoho::init();
		$cf_zoho->field->add_modal( $caldera_id, $field_id, $limit, $title );
	}
}


function lsx_cf_zoho_get_form_title( $caldera_id = '' ) {
	$title = '';
	if ( '' !== $caldera_id ) {
		$form = Caldera_Forms_Forms::get_form( $caldera_id );
		if ( isset( $form['name'] ) ) {
			$title = $form['name'];
		}
	}
	return $title;
}
