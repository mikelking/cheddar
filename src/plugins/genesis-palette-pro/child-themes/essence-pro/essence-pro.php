<?php
/**
 * Genesis Design Palette Pro - Essence Pro
 *
 * Genesis Palette Pro add-on for the Essence Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Essence Pro
 * @version 1.0 (child theme version)
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

if ( ! class_exists( 'GP_Pro_Essence_Pro' ) ) {
	/**
	 * Class GP_Pro_Essence_Pro
	 */
	class GP_Pro_Essence_Pro extends Child_Theme {

		/**
		 * Indicates the header settings should be kept when the header image is set.
		 *
		 * @var bool
		 */
		protected $keep_header = true;

		/**
		 * Filter font stacks.
		 *
		 * @param array $fonts Font stacks.
		 *
		 * @return array
		 */
		public function filter_fonts( $fonts ) {
			// check Lora.
			if ( ! isset( $fonts['sans']['lora'] ) ) {
				// add the array.
				$fonts['sans']['lora'] = array(
					'label' => __( 'Lora', 'gppro' ),
					'css'   => '"Lora", serif',
					'src'   => 'native',
					'size'  => '0',
				);
			}
			// check Alegreya Sans.
			if ( ! isset( $fonts['sans']['alegreya-sans'] ) ) {
				// add the array.
				$fonts['sans']['alegreya-sans'] = array(
					'label' => __( 'Alegreya Sans', 'gppro' ),
					'css'   => '"Alegreya Sans", sans-serif',
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

			// swap Lato if present.
			if ( isset( $webfonts['lora'] ) ) {
				$webfonts['lora']['src'] = 'native';
			}

			// swap Alegreya Sans if present.
			if ( isset( $webfonts['alegreya-sans'] ) ) {
				$webfonts['alegreya-sans']['src'] = 'native';
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
			if ( empty( $blocks['frontpage'] ) ) {
				$blocks['frontpage'] = array(
					'tab'   => __( 'Front page', 'gppro' ),
					'title' => __( 'Front page', 'gppro' ),
					'intro' => __( 'The front page uses 4 custom widget areas.', 'gppro' ),
					'slug'  => 'frontpage',
				);
			}

			if ( empty( $blocks['after-content-featured'] ) ) {
				$blocks['after-content-featured'] = array(
					'tab'   => __( 'After Content Featured', 'gppro' ),
					'title' => __( 'After Content Featured', 'gppro' ),
					'intro' => __( 'Widget area that appears after main content with featured page widgets.', 'gppro' ),
					'slug'  => 'after-content-featured',
				);
			}

			if ( empty( $blocks['footer-cta'] ) ) {
				$blocks['footer-cta'] = array(
					'tab'   => __( 'Footer CTA', 'gppro' ),
					'title' => __( 'Footer CTA', 'gppro' ),
					'intro' => __( 'Footer CTA features a widget with eNews widget.', 'gppro' ),
					'slug'  => 'footer-cta',
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
			if ( isset( $sections['body-color-setup']['data']['body-color-back-thin'] ) ) {
				unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
			}

			$sections['body-color-setup']['data']['body-color-back-main']['sub'] = '';
			$sections['body-type-setup']['data']['body-type-size']['target']     = 'body > div';

			$sections['body-type-setup']['data']['body-link-decoration'] = array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Base', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => 'a',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			);

			$sections['body-type-setup']['data']['body-link-decoration-hover'] = array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Hover', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => 'a:hover',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			);

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
			$sections['header-back-setup']['data']['header-color-back']['target'] = '.header-hero';

			// add search form settings.
			$sections = GP_Pro_Helper::array_insert_after(
				'site-title-padding-setup', $sections,
				include 'sections/header-search.php'
			);

			// No header nav or widget area.
			$unset = array(
				'section-break-site-desc',
				'site-desc-display-setup',
				'site-desc-type-setup',
			);

			foreach ( $unset as $section ) {
				if ( isset( $sections[ $section ] ) ) {
					unset( $sections[ $section ] );
				}
			}

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
		 * Add and filter options in the comments area.
		 *
		 * @param array  $sections List of options in section.
		 * @param string $class    Class for section.
		 *
		 * @return array
		 */
		public function comments_area( $sections, $class ) {
			include 'sections/comments-area.php';

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
			$sections['frontpage']              = include 'sections/frontpage.php';
			$sections['after-content-featured'] = include 'sections/after-content-featured.php';
			$sections['footer-cta']             = include 'sections/footer-cta.php';

			// return the section array.
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
			include 'sections/filter-css-builder.php';

			// Return the CSS values.
			return $setup;
		}

	} // end class GP_Pro_Essence_Pro.

} // if ! class_exists.

// Instantiate our class.
$GP_Pro_Essence_Pro = GP_Pro_Essence_Pro::get_instance();
