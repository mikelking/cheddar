<?php
/**
 * Genesis Design Palette Pro - Cache Control Headers
 *
 * @package Design Palette Pro
 */

/*
	Copyright 2014-2018 Reaktiv Studios

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License (GPL v2) only.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace DPP\Cache;

/**
 * Handles setting cache control headers for HTTP response.
 */
class Headers {
	/**
	 * Timestamp for last modified header.
	 *
	 * @var int
	 */
	public $last_modified = 0;

	/**
	 * Time in seconds to max age.
	 *
	 * @var int
	 */
	public $max_age = 0;

	/**
	 * Array of headers that are testable.
	 *
	 * @var array
	 */
	public static $headers = array();

	/**
	 * Initializes header creation.
	 *
	 * @param int $last_modified Timestamp for last modified header.
	 * @param int $max_age       Time in seconds to max age.
	 */
	public function __construct( $last_modified, $max_age ) {
		$this->last_modified = $last_modified;
		$this->max_age       = $max_age;
	}

	/**
	 * Sets the response header.
	 *
	 * This generates a testable output using the static $headers parameter.
	 *
	 * @param string $header Header to set.
	 */
	public static function header( $header ) {
		if ( defined( 'UNIT_TESTING' ) ) {
			static::$headers[] = $header;
		} else {
			header( $header, true );
		}
	}

	/**
	 * Starts the set headers logic.
	 */
	public function set_headers() {
		if ( $this->is_modified_since() ) {
			$this->set_last_modified_header();
		} else {
			$this->set_not_modified_header();
		}
	}

	/**
	 * Checks to see if response is modified.
	 *
	 * @return bool
	 */
	private function is_modified_since() {
		$all_headers = function_exists( 'getallheaders' ) ? getallheaders() : $_SERVER;

		if ( array_key_exists( 'If-Modified-Since', $all_headers ) ) {
			$gmtSinceDate   = $all_headers['If-Modified-Since'];
			$sinceTimestamp = strtotime( $gmtSinceDate );

			// Can the browser get it from the cache?
			if ( false !== $sinceTimestamp && $this->last_modified <= $sinceTimestamp ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Set the not modified header.
	 *
	 * @param bool $die Testing parameter so the method doesn't die during tests.
	 */
	private function set_not_modified_header( $die = true ) {
		static::header( 'HTTP/1.1 304 Not Modified' );
		static::header( "Cache-Control: public, max-age=$this->max_age" );

		if ( $die ) {
			die();
		}
	}

	/**
	 * Set the last modified header.
	 */
	private function set_last_modified_header() {
		// Fetching the last modified time of the XML file.
		$date = gmdate( 'D, j M Y H:i:s', $this->last_modified ) . ' GMT';

		static::header( 'HTTP/1.1 200 OK' );
		static::header( "Cache-Control: public, max-age=$this->max_age" );
		static::header( "Last-Modified: $date" );
	}
}
