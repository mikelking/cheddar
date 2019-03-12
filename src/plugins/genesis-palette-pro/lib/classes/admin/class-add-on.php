<?php
/**
 * Genesis Design Palette Pro - Admin Settings Add-on Module
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
 * Admin Add-on Settings Class.
 */
class Add_On extends Base {

	/**
	 * Sets the priority for the filter.
	 *
	 * @var int
	 */
	public $priority = 1100;

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
		// If we are a multisite, bail because it won't work.
		if ( is_multisite() ) {
			return;
		}

		// Build URL for add ons.
		$link = wp_nonce_url(
			add_query_arg(
				array(
					'tab'  => 'favorites',
					'user' => 'reaktivstudios',
				),
				admin_url( 'plugin-install.php' )
			),
			'save_wporg_username_' . get_current_user_id()
		);

		$this->tabs = array(
			'add-on' => array(
				'label'        => __( 'Add-Ons', 'gppro' ),
				'href'         => $link,
				'target_blank' => true,
			),
		);
	}
}
