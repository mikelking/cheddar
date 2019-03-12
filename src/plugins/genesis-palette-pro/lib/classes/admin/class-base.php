<?php
/**
 * Genesis Design Palette Pro - Admin Base Module
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
abstract class Base {
	/**
	 * Sets the priority for the filter.
	 *
	 * @var int
	 */
	public $priority = 10;

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
	 * The default filters with properties.
	 *
	 * Adding the callback argument to any of these filters
	 * will allow better control over the filter.
	 *
	 * @var array
	 */
	public $filters = array(
		'dpp_settings_menu_ops' => array(
			'property' => 'menu_ops',
		),
		'dpp_settings_page_ops' => array(
			'property' => 'page_ops',
		),
		'dpp_settings_sections' => array(
			'property' => 'sections',
		),
		'dpp_settings_tabs'     => array(
			'property' => 'tabs',
		),
		'dpp_settings'          => array(
			'property' => 'settings',
		),
	);

	/**
	 * Static instance of this class.
	 *
	 * @var Setup
	 */
	static public $instance;
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
	 * Filterception.
	 */
	public function __construct() {
		if ( ! is_admin() ) {
			static::$instance = null;
			return;
		}

		foreach ( $this->filters as $filter => $args ) {
			add_filter( $filter, isset( $args['callback'] ) && is_callable( $args['callback'] ) ? $args['callback'] : array( $this, 'filter' ), $this->priority );
		}

		$this->set_properties();
	}

	/**
	 * Base filter for all the things.
	 *
	 * @param  array $value The current value.
	 * @return array        Maybe modified value.
	 */
	public function filter( $value ) {
		$current_filter = current_filter();

		if ( isset( $this->filters[ $current_filter ] ) && isset( $this->filters[ $current_filter ]['property'] ) ) {
			$property = $this->filters[ $current_filter ]['property'];
		}

		if ( ! empty( $property ) && ! empty( $this->$property ) ) {
			$value = array_merge( $value, $this->$property );
		}

		return $value;
	}

	/**
	 * Set the admin properties for filtering.
	 */
	public function set_properties() {}

}
