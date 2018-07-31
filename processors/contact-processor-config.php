<?php
/**
 * Contact Processor Config.
 *
 * @package cf_zoho/processors.
 */

namespace cf_zoho\processors;

use cf_zoho\includes;

$module = new includes\CF_Processor_Render( 'contacts' );

$errors = $module->get_errors();

$template = ( ! empty( $errors ) ) ? 'zoho-errors.php' : 'processor.php';

require CFZ_TEMPLATE_PATH . $template;
