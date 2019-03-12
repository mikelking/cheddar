<?php
/**
 * Genesis Design Palette Pro - Admin Setup Module
 *
 * Contains functions for WP admin items
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
 * Sets up the admin page and tabs.
 *
 * @package DPP\Admin
 */
class Setup {
	/**
	 * The page ID
	 *
	 * @var string
	 */
	public $page_id = 'genesis-palette-pro';
	/**
	 * The menu ops for the page.
	 *
	 * @var array
	 */
	public $menu_ops = array();
	/**
	 * The page ops for the page.
	 *
	 * @var array
	 */
	public $page_ops = array();
	/**
	 * The sections for the page.
	 *
	 * @var array
	 */
	public $sections = array();
	/**
	 * The tabs for the page.
	 *
	 * @var array
	 */
	public $tabs = array();
	/**
	 * The setting fields for the page.
	 *
	 * @var array
	 */
	public $settings = array();
	/**
	 * The page admin class.
	 *
	 * @var \Rkv_Admin
	 */
	public $admin;

	/**
	 * Static instance of this class.
	 *
	 * @var Setup
	 */
	static public $instance;
	/**
	 * Instance of the Design class.
	 *
	 * @var Design
	 */
	public $design;
	/**
	 * Instance of the license class.
	 *
	 * @var License.
	 */
	public $license;

	/**
	 * Gets the Setup class instance.
	 *
	 * @return Setup
	 */
	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize all the things.
	 */
	public function init() {
		$this->set_menu_ops();
		$this->set_page_ops();
		$this->set_sections();
		$this->set_tabs();
		$this->set_settings();
		$this->maybe_remove_license();
		$this->create();
	}

	/**
	 * Set the menu_opt property.
	 */
	public function set_menu_ops() {
		/**
		 * Allows altering the $menu_ops property for the DPP settings.
		 *
		 * @since 1.4.0
		 *
		 * @param array $menu_ops {
		 *     Controls the settings for the DPP menu item.
		 *
		 *     @type array $submenu Array of definitions for the submenu..
		 * }
		 */
		$this->menu_ops = apply_filters(
			'dpp_settings_menu_ops', array(
				'submenu' => array(
					'parent_slug' => 'genesis',
					'page_title'  => __( 'Genesis Design Palette Pro', 'gppro' ),
					'menu_title'  => __( 'Design Palette Pro', 'gppro' ),
					/**
					 * Allows altering the access cap for the DPP menu.
					 *
					 * @since 1.4.0
					 *
					 * @param string $cap Default: manage_options
					 */
					'capability'  => apply_filters( 'gppro_caps', 'manage_options' ),
				),
			)
		);
	}

	/**
	 * Sets the page_ops property.
	 */
	public function set_page_ops() {
		/**
		 * Allows altering the $page_ops property for the DPP settings.
		 *
		 * @since 1.4.0
		 *
		 * @param array $page_ops {
		 *     Controls the settings for the DPP page.
		 *
		 *     @type string $type             Supply type 'form' to include form elements.
		 *     @type string $description      Description that displays under page title.
		 *     @type string $save_button_text Button text for save button when using form type.
		 * }
		 */
		$this->page_ops = apply_filters(
			'dpp_settings_page_ops', array(
				'type'             => 'form',
				'save_button_text' => __( 'Save', 'gppro' ),
			)
		);
	}

	/**
	 * Sets the sections property.
	 */
	public function set_sections() {
		/**
		 * Allows altering the $sections property for the DPP settings.
		 *
		 * @since 1.4.0
		 *
		 * @param array $sections {
		 *     Controls the sections for the DPP page.
		 *
		 *     @type array $section Nested array of sections. The section ID is the key for the arrays.
		 * }
		 */
		$this->sections = apply_filters( 'dpp_settings_sections', array() );
	}

	/**
	 * Sets the tabs property.
	 */
	public function set_tabs() {
		/**
		 * Allows altering the $tabs property for the DPP settings.
		 *
		 * @since 1.4.0
		 *
		 * @param array $tabs {
		 *     Controls the tabs for the DPP page.
		 *
		 *     @type array $tab Nested array of tabs. The section ID is the key for the arrays.
		 * }
		 */
		$this->tabs = apply_filters( 'dpp_settings_tabs', array() );
	}

	/**
	 * Sets the settings property.
	 */
	public function set_settings() {
		/**
		 * Allows altering the $settings property for the DPP settings.
		 *
		 * @since 1.4.0
		 *
		 * @param array $settings {
		 *     Controls the setting controls for the DPP page.
		 *
		 *     @type array $setting Nested array of settings.
		 * }
		 */
		$this->settings = apply_filters(
			'dpp_settings', array()
		);
	}

	/**
	 * Removes the license tab and section if set to in filter.
	 */
	public function maybe_remove_license() {
		/**
		 * Allows disabling the license tab/section.
		 *
		 * @since 1.4.0
		 *
		 * @param bool $bool Default is false. Any other value disables license tab/section.
		 */
		if ( false !== apply_filters( 'gppro_disable_license_management', false ) ) {
			unset( $this->tabs['license'] );
			unset( $this->sections['license'] );
		}
	}

	/**
	 * Creates the admin menu and related output.
	 */
	public function create() {
		require_once trailingslashit( dirname( __FILE__ ) ) . 'rkv-admin/init.php';
		$this->admin = new \Rkv_Admin( $this->page_id, $this->menu_ops, $this->page_ops, $this->settings, $this->sections, $this->tabs );
	}
}
