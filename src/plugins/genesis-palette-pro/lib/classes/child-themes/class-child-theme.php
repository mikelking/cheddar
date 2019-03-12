<?php
/**
 * Genesis Design Palette Pro - Child Theme
 *
 * @package Design Palette Pro
 */

/*
	Copyright 2018 Reaktiv Studios

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

namespace DPP\Child_Themes;

/**
 * Base class for DPP supported Genesis child themes.
 */
class Child_Theme {

	/**
	 * Static property to hold our singleton instance
	 *
	 * @var Child_Theme
	 */
	public static $instance = array();

	/**
	 * Default settings.
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * List of font weights and their titles.
	 *
	 * @var array
	 */
	protected $font_weights = array();

	/**
	 * Indicates the header settings should be kept when the header image is set.
	 *
	 * @var bool
	 */
	protected $keep_header = false;

	/**
	 * JSON file names.
	 */
	const FILE_NAME_DEFAULTS        = 'defaults';
	const FILE_NAME_SECTIONS        = 'sections';
	const FILE_NAME_REMOVE_SECTIONS = 'sections-remove';
	const FILE_NAME_REMOVE_BLOCKS   = 'remove-blocks';
	const FILE_NAME_FONT_WEIGHTS    = 'font-weights';
	const FILE_NAME_ENEWS_DEFAULTS  = 'enews-defaults';

	/**
	 * Initializes the child theme.
	 */
	public function __construct() {
		$this->font_weights = array(
			'100' => __( '100 (Extra Light)', 'gppro' ),
			'200' => __( '200 (Extra Light)', 'gppro' ),
			'300' => __( '300 (Light)', 'gppro' ),
			'400' => __( '400 (Normal)', 'gppro' ),
			'500' => __( '500 (Semibold)', 'gppro' ),
			'600' => __( '600 (Semibold)', 'gppro' ),
			'700' => __( '700 (Bold)', 'gppro' ),
			'800' => __( '800 (Extra Bold)', 'gppro' ),
			'900' => __( '900 (Extra Bold)', 'gppro' ),
		);
		$this->add_hooks();
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return Child_Theme
	 */
	public static function get_instance() {

		// Check for self instance.
		if ( ! static::$instance ) {
			static::$instance = new static();
		}

		// Return the instance.
		return static::$instance;
	}

	/**
	 * Register the child theme's hooks.
	 *
	 * @return void
	 */
	protected function add_hooks() {
		// DPP general settings.
		add_filter( 'gppro_set_defaults', array( $this, 'set_defaults' ), 15 );

		// Add/filter fonts and webfonts to the DPP font stacks.
		add_filter( 'gppro_font_stacks', array( $this, 'filter_fonts' ), 20 );
		add_filter( 'gppro_webfont_stacks', array( $this, 'filter_webfonts' ) );

		// GP Pro section item removals / additions.
		$gppro_sections = array( 'build_settings', 'comments_area', 'content_extras', 'footer_main', 'footer_widgets', 'general_body', 'header_area', 'main_sidebar', 'navigation', 'post_content' );
		foreach ( $gppro_sections as $gppro_section ) {
			add_filter( 'gppro_section_inline_' . $gppro_section, array( $this, $gppro_section ), 15, 2 );
		}

		// Add custom sections.
		$this->add_admin_blocks();
		add_filter( 'gppro_admin_block_add', array( $this, 'blocks_add' ), 25 );
		add_filter( 'gppro_sections', array( $this, 'sections_add' ) );
		add_filter( 'gppro_sections', array( $this, 'filter_sections' ), 10, 2 );
		add_filter( 'gppro_sections', array( $this, 'genesis_widgets_section' ), 20 );

		// Remove blocks.
		add_filter( 'gppro_admin_block_remove', array( $this, 'remove_admin_blocks' ) );

		// CSS Builder filter.
		add_filter( 'gppro_css_builder', array( $this, 'filter_css_builder' ), 50, 3 );

		// Font Weights.
		add_filter( 'gppro_default_css_font_weights', array( $this, 'filter_font_weights' ), 20 );

		// Set eNews widget defaults.
		add_filter( 'gppro_enews_set_defaults', array( $this, 'set_enews_defaults' ), 15 );

		if ( $this->keep_header ) {
			add_filter( 'gppro_enable_site_title_options', '__return_true' );
		}
	}

	/**
	 * Get parameters from a JSON file.
	 *
	 * @param string $filename Name of JSON file.
	 *
	 * @return array|bool
	 */
	protected function get_json_from_file( $filename ) {
		// Get params from JSON file and return as an array.
		$class      = get_called_class();
		$class_info = new \ReflectionClass( $class );
		$path       = dirname( $class_info->getFileName() );
		$json_file  = trailingslashit( $path ) . $filename . '.json';

		// If the JSON file does not exist, return false.
		if ( ! file_exists( $json_file ) ) {
			return false;
		}

		// Get the contents of the JSON file.
		$file_contents = file_get_contents( $json_file );

		// If the file was empty, return false.
		if ( empty( $file_contents ) ) {
			return false;
		}

		// Convert JSON file contents to an associative array.
		$data = json_decode( $file_contents, true );

		// If there is no data, return false.
		if ( empty( $data ) ) {
			return false;
		}

		// Return the data that resulted from parsing the JSON file.
		return $data;
	}

	/**
	 * Set default settings for child theme.
	 *
	 * @param array $defaults Default settings.
	 *
	 * @return array
	 */
	public function set_defaults( $defaults ) {
		// Get changes from JSON file and merge to $defaults.
		$changes = $this->get_json_from_file( self::FILE_NAME_DEFAULTS );
		if ( false !== $changes ) {
			$defaults = wp_parse_args( $changes, $defaults );
		}

		return $defaults;
	}

	/**
	 * Filter font stacks.
	 *
	 * @param array $fonts Font stacks.
	 *
	 * @return array
	 */
	public function filter_fonts( $fonts ) {
		return $fonts;
	}

	/**
	 * Filter webfonts stacks.
	 *
	 * @param array $webfonts Webfont stacks.
	 *
	 * @return array
	 */
	public function filter_webfonts( $webfonts ) {
		return $webfonts;
	}

	/**
	 * Add and filter options in the build area.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function build_settings( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the comments area.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function comments_area( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the content extras section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function content_extras( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the footer main section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function footer_main( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the footer widgets section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function footer_widgets( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the general body section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function general_body( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the header area section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function header_area( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the main sidebar section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function main_sidebar( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the navigation section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function navigation( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add and filter options in the post content section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function post_content( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add filters for after entry widget sections.
	 *
	 * @return void
	 */
	protected function add_after_entry_widget_filters() {
		add_filter( 'gppro_section_inline_content_extras', array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry' ), 15, 2 );
	}

	/**
	 * Add and filter options in the after entry widget section.
	 *
	 * @param array  $sections List of options in section.
	 * @param string $class    Class for section.
	 *
	 * @return array
	 */
	public function after_entry( $sections, $class ) {
		return $sections;
	}

	/**
	 * Add filters for gppro_admin_block_add.
	 *
	 * @return void
	 */
	protected function add_admin_blocks() {}

	/**
	 * Remove admin blocks.
	 *
	 * @param array $blocks List of admin blocks.
	 *
	 * @return array
	 */
	public function remove_admin_blocks( $blocks ) {
		$blocks_to_remove = $this->get_json_from_file( self::FILE_NAME_REMOVE_BLOCKS );

		if ( ! empty( $blocks_to_remove ) ) {
			foreach ( $blocks_to_remove as $block_to_remove ) {
				if ( isset( $blocks[ $block_to_remove ] ) ) {
					unset( $blocks[ $block_to_remove ] );
				}
			}
		}

		return $blocks;
	}

	/**
	 * Adds new blocks.
	 *
	 * @param array $blocks The blocks/tabs.
	 *
	 * @return array
	 */
	public function blocks_add( $blocks ) {
		return $blocks;
	}

	/**
	 * Adds new sections.
	 *
	 * @param array $sections The sections.
	 *
	 * @return array
	 */
	public function sections_add( $sections ) {
		return $sections;
	}

	/**
	 * Allows editing the genesis_widgets section.
	 *
	 * @param array $sections The sections.
	 *
	 * @return array
	 */
	public function genesis_widgets_section( $sections ) {
		return $sections;
	}

	/**
	 * Add settings for custom blocks.
	 *
	 * @param array  $sections List of DPP sections.
	 * @param string $class    Class for sections.
	 *
	 * @return array
	 */
	public function filter_sections( $sections, $class ) {
		$new_sections = $this->get_json_from_file( self::FILE_NAME_SECTIONS );
		if ( ! empty( $new_sections ) ) {
			$sections = wp_parse_args( $new_sections, $sections );
		}

		$remove_sections = $this->get_json_from_file( self::FILE_NAME_REMOVE_SECTIONS );
		if ( ! empty( $remove_sections ) ) {
			$sections = array_diff_key( $sections, array_flip( $remove_sections ) );
		}

		return $sections;
	}

	/**
	 * Filters the CSS builder.
	 *
	 * @param string $setup The CSS builder setup.
	 * @param array  $data  DPP settings data.
	 * @param string $class Body class.
	 *
	 * @return string
	 */
	public function filter_css_builder( $setup, $data, $class ) {
		return $setup;
	}

	/**
	 * Filter the list of default font weights.
	 *
	 * @param array $weights List of font weights.
	 *
	 * @return array
	 */
	public function filter_font_weights( $weights ) {
		$weights_to_add = $this->get_json_from_file( self::FILE_NAME_FONT_WEIGHTS );

		if ( ! empty( $weights_to_add ) ) {
			foreach ( $weights_to_add as $weight_to_add ) {
				if ( empty( $weights[ $weight_to_add ] ) && isset( $this->font_weights[ $weight_to_add ] ) ) {
					$weights[ $weight_to_add ] = $this->font_weights[ $weight_to_add ];
				}
			}
		}

		return $weights;
	}

	/**
	 * Set eNews widget default values.
	 *
	 * @param array $defaults Default values.
	 *
	 * @return array
	 */
	public function set_enews_defaults( $defaults ) {
		// Get changes from JSON file and merge to $defaults.
		$changes = $this->get_json_from_file( self::FILE_NAME_ENEWS_DEFAULTS );
		if ( false !== $changes ) {
			$defaults = wp_parse_args( $changes, $defaults );
		}

		return $defaults;
	}

}
