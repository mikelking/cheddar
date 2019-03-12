<?php
/**
 * Genesis Design Palette Pro - Admin Settings Utilities Module
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
 * Admin Settings Utilities Class.
 */
class Utilities extends Base {

	/**
	 * Sets the priority for the filter.
	 *
	 * @var int
	 */
	public $priority = 400;

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
		$this->settings = array(
			'gppro-export-field' => array(
				'label'    => __( 'Data Export', 'gppro' ),
				'section'  => 'utilities',
				'callback' => array( $this, 'export_field' ),
			),
			'gppro-import-field' => array(
				'label'    => __( 'Data Import', 'gppro' ),
				'section'  => 'utilities',
				'callback' => array( $this, 'import_field' ),
			),
		);
		$this->sections = array(
			'utilities' => array(
				'title' => __( 'Utilities', 'gppro' ),
			),
		);
		$this->tabs     = array(
			'utilities' => array(
				'label'  => __( 'Utilities', 'gppro' ),
				'single' => true,
				'form'   => false,
			),
		);
	}

	/**
	 * The export field callback.
	 */
	public function export_field() {
		echo \GP_Pro_Setup::get_export_input(
			'gppro-export-field', array(
				'label' => __( 'Download JSON file', 'gppro' ),
				'input' => 'export',
			)
		); // WPCS: xss ok.
		?>
		<p class="description">
			<?php esc_html_e( 'Exports a JSON file that can be imported into another site using the plugin or backed up.', 'gppro' ); ?>
		</p>
		<?php
	}

	/**
	 * The export field callback.
	 */
	public function import_field() {
		echo \GP_Pro_Setup::get_import_input(
			'gppro-import-upload', array(
				'label' => '', // has a custom label to include PHP max.
				'input' => 'import',
			)
		); // WPCS: xss ok.
		?>
		<p class="description">
			<?php esc_html_e( 'Import a previously saved JSON file.', 'gppro' ); ?>
		</p>
		<?php
	}
}
