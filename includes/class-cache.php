<?php
/**
 * The file that defines plugin cache.
 *
 * @package lsx_cf_zoho/includes.
 */

namespace lsx_cf_zoho\includes;

/**
 * Cache.
 */
class Cache
{

    /**
     * Array of cached data for the plugin.
     *
     * @var array.
     */
    private $plugin_cache = array();

    /**
     * Overwrites a specified plugin cache item.
     *
     * @param string                        $item Cache item key.
     * @param array        Cache item value.
     */
    public function set_plugin_cache_item( $item, $value )
    {
        $this->plugin_cache[ $item ] = $value;
        set_transient(LSX_CFZ_TRANSIENT_SLUG, $this->plugin_cache, MONTH_IN_SECONDS);
    }

    /**
     * Returns a specified plugin cache item.
     *
     * @param  string $item Cache item key.
     * @return boolean|array       False|Cache item array.
     */
    public function get_plugin_cache_item( $item )
    {

        if (! isset($this->plugin_cache[ $item ]) ) {
            return false;
        }

        return $this->plugin_cache[ $item ];
    }

    /**
     * Deletes plugin transients.
     */
    public function flush_plugin_cache()
    {
        delete_transient(LSX_CFZ_TRANSIENT_SLUG);
    }

    /**
     * Class constructor.
     *
     * @param boolean $load_cache Whether to load plugin cache in or not.
     */
    public function __construct( $load_cache = true )
    {

        if (false === $load_cache ) {
            return;
        }

        $this->plugin_cache = get_transient(LSX_CFZ_TRANSIENT_SLUG);
    }
}
