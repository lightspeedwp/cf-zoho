<?php
/**
 * The file that defines plugin templates.
 *
 * @package cf_zoho/includes.
 */

namespace cf_zoho\includes;

/**
 * Templates.
 */
class Templates {

    public function template_handler( $template ) {

        if ( ! is_singular( 'wp_log' ) ) {
            return $template;
        }
        
        return CFZ_TEMPLATE_PATH . '/single-wp-log.php';
    }
}
