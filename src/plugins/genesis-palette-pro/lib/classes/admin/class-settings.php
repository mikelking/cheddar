<?php
/**
 * Genesis Design Palette Pro - Admin Settings Module
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
 * Admin Settings Class.
 */
class Settings extends Base {

	/**
	 * Sets the priority for the filter.
	 *
	 * @var int
	 */
	public $priority = 100;

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
		$this->sections = array(
			'settings' => array(
				'title' => __( 'Settings', 'gppro' ),
			),
		);
		$this->tabs     = array(
			'settings' => array(
				'label' => __( 'Settings', 'gppro' ),
			),
		);
		$this->settings = array(
			'gppro-child-theme' => array(
				'label'       => __( 'Theme Defaults', 'gppro' ),
				'description' => __( 'Select your current Genesis child theme.', 'gppro' ),
				'default'     => 'genesis',
				'type'        => 'select',
				'section'     => 'settings',
				'sanitize'    => 'in_array',
				'options'     => $this->child_themes_array(),
			),
		);
	}

	/**
	 * Converts the theme dropdown for use with RKV_Admin.
	 *
	 * @return array
	 */
	public function child_themes_array() {
		$themes  = \GP_Pro_Themes::get_themes_for_dropdown();
		$ret_arr = array();

		foreach ( $themes as $theme ) {
			$ret_arr[ $theme['value'] ] = $theme['label'];
		}

		return $ret_arr;
	}
}
