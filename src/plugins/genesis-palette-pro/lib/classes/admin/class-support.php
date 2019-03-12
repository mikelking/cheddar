<?php
/**
 * Genesis Design Palette Pro - Admin Settings Support Module
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

namespace DPP\Admin;

/**
 * Admin Settings Support Class.
 */
class Support extends Base {

	/**
	 * Sets the priority for the filter.
	 *
	 * @var int
	 */
	public $priority = 2000;

	/**
	 * Static instance of this class.
	 *
	 * @var Setup
	 */
	static public $instance;

	/**
	 * Set the admin properties for filtering.
	 */
	public function set_properties() {
		// bail if support turned off.
		if ( false === apply_filters( 'gppro_show_support', true ) ) {
			return;
		}

		$this->sections = array(
			'support' => array(
				'title'    => __( 'Questions?', 'gppro' ),
				'callback' => array( $this, 'admin_page' ),
			),
		);
		$this->tabs     = array(
			'support' => array(
				'label'  => __( 'Support', 'gppro' ),
				'single' => true,
				'form'   => false,
			),
		);
	}

	/**
	 * Outputs support display.
	 */
	public function admin_page() {
		echo \GP_Pro_Support::support_display(); // WPCS: xss ok.
	}
}
