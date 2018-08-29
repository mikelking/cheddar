<?php


/**
 * Cleanly setup the testing environment
 */
$bootstrap_path = dirname( dirname( dirname( __FILE__ ) ) );
$base_path = dirname( __DIR__ );

/* Verification step

print( 'bootstrap: ' . $bootstrap_path . PHP_EOL );
    //bootstrap: /Volumes/osiris/Users/mikel/Projects/git/dum

print( 'base: ' . $base_path . PHP_EOL );
    //base: /Volumes/osiris/Users/mikel/Projects/git/dum/mu-plugins
*/

/**
 * Bootstrap the test systems
 */
require( $bootstrap_path . '/vendor/autoload.php' );

require( $base_path . '/dum.php');

use PHPUnit\Framework\TestCase;
use Moron\Dum;

class DumTest extends TestCase {
    public $dummy;

    protected function setUp() {
        $this->dummy = new Dum();
    }

    protected function tearDown() {
        unset( $this->dummy );
    }

/*
    public function testAttributionFailure() {
        $this->assertClassHasAttribute(
            'STUPID',
            stdClass::Dum,
            'The dum class has STUPID.'
        );
    }
*/
    /**
     * @test
     */
    public function testGetStupid() {
        $this->assertEquals(
            true,
            Dum::STUPID,
            'The dum class has STUPID but it is NOT true.'
        );
    }

}


