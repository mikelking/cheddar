<?php
/**
 * Genesis Design Palette Pro - Authority Pro
 *
 * Genesis Palette Pro add-on for the Authority Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Authority Pro
 * @version 1.1.0 (child theme version)
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
/**
 * CHANGELOG:
 * 2018-11-26: Initial development
 */

use DPP\Child_Themes\Child_Theme;

if ( ! class_exists( 'GP_Pro_Authority_Pro' ) ) {
	/**
	 * Class GP_Pro_Authority_Pro
	 */
	class GP_Pro_Authority_Pro extends Child_Theme {

		/**
		 * Filter font stacks.
		 *
		 * @param array $fonts Font stacks.
		 *
		 * @return array
		 */
		public function filter_fonts( $fonts ) {
			// check Source Sans Pro.
			if ( ! isset( $fonts['sans']['source-sans-pro'] ) ) {
				// add the array.
				$fonts['sans']['source-sans-pro'] = array(
					'label' => __( 'Source Sans Pro', 'gppro' ),
					'css'   => '"Source Sans Pro", serif',
					'src'   => 'native',
					'size'  => '0',
				);
			}
			// check Libre Baskerville.
			if ( ! isset( $fonts['serif']['libre-baskerville'] ) ) {
				// add the array.
				$fonts['serif']['libre-baskerville'] = array(
					'label' => __( 'Alegreya Sans', 'gppro' ),
					'css'   => '"Libre Baskerville", serif',
					'src'   => 'native',
					'size'  => '0',
				);
			}

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
			// bail if plugin class isn't present.
			if ( ! class_exists( 'GP_Pro_Google_Webfonts' ) ) {
				return $webfonts;
			}

			// swap Source Sans Pro if present.
			if ( isset( $webfonts['source-sans-pro'] ) ) {
				$webfonts['source-sans-pro']['src'] = 'native';
			}

			// swap Libre Baskerville if present.
			if ( isset( $webfonts['libre-baskerville'] ) ) {
				$webfonts['libre-baskerville']['src'] = 'native';
			}

			// return the webfonts.
			return $webfonts;
		}

		/**
		 * Adds new blocks.
		 *
		 * @param array $blocks The blocks/tabs.
		 *
		 * @return array
		 */
		public function blocks_add( $blocks ) {
			if ( empty( $blocks['top-banner'] ) ) {
				$blocks['top-banner'] = array(
					'tab'   => __( 'Top Banner', 'gppro' ),
					'title' => __( 'Top Banner', 'gppro' ),
					'intro' => __( 'Top Banner displays a notification at the top of every page.', 'gppro' ),
					'slug'  => 'top-banner',
				);
			}

			if ( empty( $blocks['hero-section'] ) ) {
				$blocks['hero-section'] = array(
					'tab'   => __( 'Hero Section', 'gppro' ),
					'title' => __( 'Hero Section', 'gppro' ),
					'intro' => __( 'Hero Section displays at the top of the front page.', 'gppro' ),
					'slug'  => 'hero-section',
				);
			}

			if ( empty( $blocks['front-page'] ) ) {
				$blocks['front-page'] = array(
					'tab'   => __( 'Front Page', 'gppro' ),
					'title' => __( 'Front Page', 'gppro' ),
					'intro' => __( 'The front page has 5 custom widget areas using a text widget, HTML Widget, Featured Page Widget and Featured Post Widget.', 'gppro' ),
					'slug'  => 'front-page',
				);
			}

			return $blocks;
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
			include 'sections/general-body.php';

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

			// No header nav or widget area.
			$unset = array(
				'section-break-site-desc',
				'section-break-header-nav',
				'header-nav-color-setup',
				'header-nav-type-setup',
				'header-nav-item-padding-setup',
				'section-break-header-widgets',
				'header-widget-title-setup',
				'header-widget-content-setup',
			);

			foreach ( $unset as $section ) {
				if ( isset( $sections[ $section ] ) ) {
					unset( $sections[ $section ] );
				}
			}

			return $sections;
		}

		/**
		 * Add and filter options in the navigation area section.
		 *
		 * @param array  $sections List of options in section.
		 * @param string $class    Class for section.
		 *
		 * @return array
		 */
		public function navigation( $sections, $class ) {
			include 'sections/navigation.php';

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
			include 'sections/post-content.php';

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
			include 'sections/content-extras.php';

			return $sections;
		}

		/**
		 * Add and filter options in the comments area section.
		 *
		 * @param array  $sections List of options in section.
		 * @param string $class    Class for section.
		 *
		 * @return array
		 */
		public function comments_area( $sections, $class ) {
			include 'sections/comments.php';

			return $sections;
		}

		/**
		 * Add and filter options in the sidebar section.
		 *
		 * @param array  $sections List of options in section.
		 * @param string $class    Class for section.
		 *
		 * @return array
		 */
		public function main_sidebar( $sections, $class ) {
			include 'sections/sidebar.php';

			return $sections;
		}

		/**
		 * Add and filter options in the footer widget section.
		 *
		 * @param array  $sections List of options in section.
		 * @param string $class    Class for section.
		 *
		 * @return array
		 */
		public function footer_widgets( $sections, $class ) {
			include 'sections/footer-widgets.php';

			return $sections;
		}

		/**
		 * Add and filter options in the footer area section.
		 *
		 * @param array  $sections List of options in section.
		 * @param string $class    Class for section.
		 *
		 * @return array
		 */
		public function footer_main( $sections, $class ) {

			// add text decoration.
			$sections['footer-main-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'footer-main-content-link-hov', $sections['footer-main-content-setup']['data'],
				array(
					'footer-main-content-link-decoration' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => '.sidebar .widget:not(:first-of-type) a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'footer-main-content-link-decoration-hover' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.sidebar .widget:not(:first-of-type) a:hover', '.sidebar .widget:not(:first-of-type) a:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

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
			// Genesis eNews changes.
			include 'sections/genesis-widgets.php';

			return $sections;
		}

		/**
		 * Adds new sections.
		 *
		 * @param  array $sections The sections.
		 *
		 * @return array
		 */
		public function sections_add( $sections ) {
			$sections['top-banner']   = include 'sections/top-banner.php';
			$sections['hero-section'] = include 'sections/hero-section.php';
			$sections['front-page']   = include 'sections/front-page.php';

			// return the section array.
			return $sections;
		}

	} // end class GP_Pro_Authority_Pro.

} // if ! class_exists.

// Instantiate our class.
$GP_Pro_Authority_Pro = GP_Pro_Authority_Pro::get_instance();
