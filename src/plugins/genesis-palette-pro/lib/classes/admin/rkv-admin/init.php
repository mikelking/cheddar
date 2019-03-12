<?php
/**
 * Rkv Admin init.
 *
 * @package Rkv\Admin
 */

if ( defined( 'RKV_ADMIN_DIR' ) ) {
	return;
}

define( 'RKV_ADMIN_DIR', trailingslashit( dirname( __FILE__ ) ) );

spl_autoload_register( 'rkv_admin_autoload' );
/**
 * Autoloader for Rkv_Admin classes.
 *
 * @param string $class The class name.
 */
function rkv_admin_autoload( $class ) {
	if ( false === strpos( $class, 'Rkv_Admin' ) ) {
		return;
	}

	$file = sprintf( '%sclass-%s.php', RKV_ADMIN_DIR, strtolower( str_replace( '_', '-', $class ) ) );

	if ( ! file_exists( $file ) ) {
		return;
	}

	require $file;
}

/**
 * Registers the rkv-admin-script.
 */
function rkv_admin_register_scripts() {
	$file = 'assets/dist/js/scripts.min.js';

	wp_register_script(
		'rkv-admin-scripts',
		plugin_dir_url( __FILE__ ) . $file,
		array( 'jquery' ),
		filemtime( trailingslashit( dirname( __FILE__ ) ) . $file ),
		true
	);
}
add_action( 'admin_enqueue_scripts', 'rkv_admin_register_scripts' );
