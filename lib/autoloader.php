<?php
/**
 * Automatically loads the specified file.
 *
 * @package lsx_cf_zoho\lib
 */

namespace lsx_cf_zoho\lib;

/**
 * Automatically loads the specified file.
 *
 * Examines the fully qualified class name, separates it into components, then creates
 * a string that represents where the file is loaded on disk.
 *
 * @package lsx_cf_zoho\lib
 */
spl_autoload_register(
	function( $filename ) {

		$plugin_namespace = 'lsx_cf_zoho';

		// First, separate the components of the incoming file.
		$file_path = explode( '\\', $filename );

		// Not part of our namespace, then return.
		if ( $plugin_namespace !== $file_path[0] ) {
			return;
		}

		/**
		 * - The first index will always be $plugin_namespace since it's part of the plugin.
		 * - All but the last index will be the path to the file.
		 */
		// Get the last index of the array. This is the class we're loading.
		if ( isset( $file_path[ count( $file_path ) - 1 ] ) ) {
			$file_name = strtolower(
				$file_path[ count( $file_path ) - 1 ]
			);

			// Check if this is an interface or class. Prefix 'class' if its a class.
			$full_file_name  = ( false !== strpos( $file_name, 'interface' ) ) ? $file_name : "class-$file_name";
			$full_file_name .= '.php';

			// Replace any underscores with a hyphen.
			$final_file_name = strtolower( str_ireplace( '_', '-', $full_file_name ) );
		}

		/**
		 * Find the fully qualified path to the class file by iterating through the $file_path array.
		 * We ignore the first index since it's always the top-level package. The last index is always
		 * the file so we append that at the end.
		 */
		$fully_qualified_path = LSX_CFZ_ABSPATH;
		$file_path_count = count( $file_path );

		for ( $i = 1; $i < $file_path_count - 1; $i++ ) {
			$dir                   = strtolower( $file_path[ $i ] );
			$fully_qualified_path .= trailingslashit( $dir );
		}

		$fully_qualified_path .= $final_file_name;

		// No file, then exit.
		if ( ! file_exists( $fully_qualified_path ) ) {
			return;
		}

		// Now we include the file.
		require $fully_qualified_path;
	}
);
