<?php
/**
 * Lead Processor Config.
 *
 * @package lsx_cf_zoho/processors.
 */

namespace lsx_cf_zoho\processors;

use lsx_cf_zoho\includes;

$module = new includes\CF_Processor_Render('tasks');

$errors = $module->get_errors();

$template = ( ! empty($errors) ) ? 'zoho-errors.php' : 'processor.php';

require LSX_CFZ_TEMPLATE_PATH . $template;
