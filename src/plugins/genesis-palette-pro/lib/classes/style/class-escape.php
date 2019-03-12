<?php
/**
 * Genesis Design Palette Pro - Escaping
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

/**
 * Conditionally loads the custom styles.
 */
class Escape {

	/**
	 * Take the CSS data stored in the settings row and escape it for proper output.
	 *
	 * @param  string $data the sanitized CSS data stored.
	 *
	 * @return string $data the escaped and encoded CSS data to output.
	 */
	public function css( $data = '' ) {

		// Convert single quotes to double quotes.
		$data = str_replace( '\'', '"', $data );

		// Escape it.
		$data = esc_attr( $data );

		// Now decode it.
		$data = html_entity_decode( $data );

		// And return it, filtered.
		return apply_filters( 'gppro_css_escaped', $data );
	}
}
