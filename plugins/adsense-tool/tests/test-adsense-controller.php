<?php

/**
 * Cleanly setup the testing environment
 */
$bootstrap_path = dirname( dirname( dirname( dirname( __FILE__ ) ) ) );
$base_path = dirname( __DIR__ );

/**
 * Bootstrap the test systems
 */
require( $bootstrap_path . '/vendor/autoload.php' );

WP_Mock::bootstrap();

/**
 * Require the testable entities
 */
require_once(  $base_path . 'inc/adsense-controller.php' );
require_once( $base_path . '/plugin.php' );



