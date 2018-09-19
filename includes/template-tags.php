<?php
/**
 * Template Tags
 *
 * @package   CF Zoho
 * @author    LightSpeed
 * @license   GPL3
 * @link
 * @copyright 2018 LightSpeed
 */

/**
 * Returns the names and ids of the current available caldera forms.
 * @return bool
 */
function cf_zoho_get_caldera_forms() {
	$results = \Caldera_Forms_Forms::get_forms( true );
	$forms   = false;

	if ( ! empty( $results ) ) {
		foreach ( $results as $form => $form_data ) {
			$forms[ $form ] = $form_data['name'];
		}
	}

	return $forms;
}
