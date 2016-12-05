#!/usr/bin/env php
<?php

require( 'cli-controller.php' );

class Replace extends CLI_Controller {
	const VERSION = '0.1';

	public function parse_opts() {

		if ( $this->is_needle() ) {

			$this->print_needle();
		} elseif ( $this->is_version() ) {
			die( $this->get_version() );
		} elseif ( $this->is_debug() ) {
			$this->debug_args();
		}
		parent::parse_opts();
	}

	public function is_needle() {
		return( in_array( $this->arg_values[1], array( '-n', '--needle' ) ) );
	}

	public function is_arg_data( $argument_key ) {
		if ( key_exists( $argument_key, $this->arg_values ) &&
			! stripos( $this->arg_values[$argument_key], '-' )
		) {
			return( true );
		}
		return( false );
	}

	public function print_needle() {
		$msg = 'Incorrect parameters' . PHP_EOL;
		
		$needle_key = $this->is_needle();
		$argument_key = $needle_key + 1;
		if ( $this->is_arg_data( $argument_key ) ) {
			$msg = 'Found the needle as per key: ' . $needle_key . ' and the ';
			$msg .= $this->arg_values[$needle_key];
			$msg .= ' argument identifier. ';
			$msg .= PHP_EOL;
			$msg .= "'" . $this->arg_values[$argument_key] . "'";
			$msg .= PHP_EOL;
			print( $msg );
		} else {
			die( $msg );
		}
	}
}

$r = new Replace( $argc, $argv );

/* Argc count: 8
Argv contents: Array
(
    [0] => ./replace.php
    [1] => -d
    [2] => -n
    [3] => src
    [4] => -t
    [5] => test
    [6] => -f
    [7] => file
)
*/
