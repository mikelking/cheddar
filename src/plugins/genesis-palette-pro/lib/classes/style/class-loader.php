<?php
/**
 * Genesis Design Palette Pro - Style Loader
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

namespace DPP\Style;

use DPP\Cache\Headers;

/**
 * Conditionally loads the custom styles.
 */
class Loader {

	/**
	 * Outputs the custom styles with cache control header.
	 *
	 * @param bool $bool Whether or not to parse the request. Default true.
	 * @param bool $exit Indicates the script should exit on completion.
	 *
	 * @return bool Whether or not to parse the request.
	 */
	public static function maybe_load_style( $bool, $exit = true ) {
		if ( empty( $bool ) ) {
			return $bool; // Get out early this has already been modified.
		}

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; // Input var okay.

		if ( false === strpos( $request_uri, '/dpp-custom-styles-' ) && empty( $_GET['dpp-custom-styles'] ) ) { // WPCS: CSRF okay.
			return $bool;
		}

		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
		}

		$time = get_theme_mod( 'dpp_file_time' );
		$time = empty( $time ) ? time() : $time;

		$cache_headers = new Headers( $time, YEAR_IN_SECONDS );

		$cache_headers->set_headers();

		Headers::header( 'Content-Type: text/css' );
		Headers::header( 'X-Content-Type-Options: nosniff' );

		$styles = wp_cache_get( 'dpp_styles' );

		if ( empty( $styles ) ) {
			$styles = get_theme_mod( 'dpp_styles' );
			wp_cache_add( 'dpp_styles', $styles );
		}

		$escape = new Escape();

		echo $escape->css( $styles ); // WPCS: xss ok.

		if ( $exit ) {
			exit;
		}
	}
}
