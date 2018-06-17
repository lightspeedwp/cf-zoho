<?php
/**
 * The file that defines plugin cache.
 *
 * @package cf_zoho/includes.
 */

namespace cf_zoho\includes;

/**
 * Cache.
 */
class Cache {

    /**
     * Array of cached data for the plugin.
     *
     * @var array.
     */
    private $plugin_cache = [];

    /**
     * Overwrites a specified plugin cache item.
     *
     * @param  string $item Cache item key.
     * @param  array        Cache item value.
     */
    public function set_plugin_cache_item( $item, $value ) {
        $this->plugin_cache[ $item ] = $value;
        set_transient( CFZ_TRANSIENT_SLUG, $this->plugin_cache );
    }

    /**
     * Returns a specified plugin cache item.
     *
     * @param  string        $item Cache item key.
     * @return boolean|array       False|Cache item array.
     */
    public function get_plugin_cache_item( $item ) {

        if ( ! isset( $this->plugin_cache[ $item ] ) ) {
            return false;
        }

        return $this->plugin_cache[ $item ];
    }

    /**
     * Deletes plugin transients.
     * 
     * @param boolean $reset_options Whether to remove the flush_transients option and save options.
     */
    public function flush_plugin_cache( $reset_options = false ) {        
        delete_transient( CFZ_TRANSIENT_SLUG );
    }

    /**
     * Class constructor.
     * 
     * @param boolean $load_cache Whether to load plugin cache in or not.
     */
    public function __construct( $load_cache = true ) {
        
        if ( false === $load_cache ) {
            return;
        }

        $this->plugin_cache = get_transient( CFZ_TRANSIENT_SLUG );
    }
}
