<?php

/**
 * Cleanly setup the testing environment
 */
$bootstrap_path = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/';
$base_path = dirname( __DIR__ ) . '/';
$bacon_path = $bootstrap_path . 'wproot/wordpress/wp-content/mu-plugins/';

/* Verification step will eventually redact once I am more comfortable with the intricacies.

print( 'bootstrap: ' . $bootstrap_path . PHP_EOL );
    //bootstrap: /Volumes/osiris/Users/mikel/Projects/git/dum

print( 'base: ' . $base_path . PHP_EOL );
    //base: /Volumes/osiris/Users/mikel/Projects/git/dum/plugins/adsense-tool/

print( 'bacon: ' . $bacon_path . PHP_EOL );
    //bacon: /Volumes/osiris/Users/mikel/Projects/git/dum/wproot/wordpress/wp-content/mu-plugins
*/
/**
 * Bootstrap the test systems
 */
require( $bootstrap_path . 'vendor/autoload.php' );
require( $bacon_path . '000-singleton-base.php' );
require( $bacon_path . '010-base-plugin.php' );

WP_Mock::bootstrap();

//use \WP_Mock;

/**
 * Require the testable entities, however were the autoloader working these would automagickally be loaded.
 */
require_once( $base_path . 'inc/adsense-controller.php' );
//require_once( $base_path . 'plugin.php' );

class Test_Adsense_Controller extends \WP_Mock\Tools\TestCase {

    public function setUp() {
        \WP_Mock::setUp();
    }

    public function tearDown() {
        \WP_Mock::tearDown();
    }

    /**
     * @test
     */
    public function validateVersion() {
        $this->assertTrue( false );
    }
}


