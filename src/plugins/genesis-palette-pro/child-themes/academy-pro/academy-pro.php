<?php
/**
 * Genesis Design Palette Pro - Academy Pro
 *
 * Genesis Palette Pro add-on for the Academy Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Academy Pro
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
 * 2018-04-26: Initial development
 */

if ( ! class_exists( 'GP_Pro_Academy_Pro' ) ) {

	class GP_Pro_Academy_Pro {

		/**
		 * Static property to hold our singleton instance
		 *
		 * @var GP_Pro_Academy_Pro
		 */
		public static $instance = null;

		/**
		 * This is our constructor
		 */
		private function __construct() {

			// GP Pro general
			add_filter( 'gppro_set_defaults', array( $this, 'set_defaults' ), 15 );

			// font stack modifications
			add_filter( 'gppro_webfont_stacks', array( $this, 'google_webfonts' ) );
			add_filter( 'gppro_font_stacks', array( $this, 'font_stacks' ), 20 );

			// Adding new widget and front page sections.
			add_filter( 'gppro_admin_block_add', array( $this, 'add_new_sections' ), 25 );
			add_filter( 'gppro_sections', array( $this, 'frontpage_section' ), 10 );

			// GP Pro section item removals / additions
			add_filter( 'gppro_section_inline_general_body', array( $this, 'general_body' ), 15 );
			add_filter( 'gppro_section_inline_header_area', array( $this, 'header_area' ), 15 );
			add_filter( 'gppro_section_inline_navigation', array( $this, 'navigation' ), 15 );
			add_filter( 'gppro_section_inline_post_content', array( $this, 'post_content' ), 15 );
			add_filter( 'gppro_section_inline_content_extras', array( $this, 'content_extras' ), 15 );
			add_filter( 'gppro_section_inline_comments_area', array( $this, 'comments_area' ), 15 );
			add_filter( 'gppro_section_inline_main_sidebar', array( $this, 'main_sidebar' ), 15, 2 );
			add_filter( 'gppro_section_inline_footer_widgets', array( $this, 'footer_widgets' ), 15 );
			add_filter( 'gppro_section_inline_footer_main', array( $this, 'footer_main' ), 15 );

			// Enable after entry widget sections
			add_filter( 'gppro_section_inline_content_extras', array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
			add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry' ), 15 );

			// add entry content defaults
			add_filter( 'gppro_set_defaults', array( $this, 'entry_content_defaults' ), 40 );

			// add/remove settings
			add_filter( 'gppro_sections', array( $this, 'genesis_widgets_section' ), 20 );

			// Enable Genesis eNews sections
			add_filter( 'gppro_enews_set_defaults', array( $this, 'enews_defaults' ), 15 );

			// reset CSS builders
			add_filter( 'gppro_css_builder', array( $this, 'css_builder_filters' ), 50, 3 );
		}

		/**
		 * If an instance exists, this returns it.  If not, it creates one and
		 * returns it.
		 *
		 * @return GP_Pro_Academy_Pro
		 */
		public static function getInstance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * swap Google webfont source to native
		 *
		 * @param  array $webfonts The webfonts.
		 * @return array $webfonts
		 */
		public function google_webfonts( $webfonts ) {

			// bail if plugin class isn't present
			if ( ! class_exists( 'GP_Pro_Google_Webfonts' ) ) {
				return $webfonts;
			}

			// swap Open Sans if present
			if ( isset( $webfonts['pt-sans'] ) ) {
				$webfonts['pt-sans']['src'] = 'native';
			}

			// swap Sorts Arbutus Slab if present
			if ( isset( $webfonts['arbutus-slab'] ) ) {
				$webfonts['arbutus-slab']['src'] = 'native';
			}

			return $webfonts;
		}

		/**
		 * Add the custom font stacks
		 *
		 * @param  array $stacks The font stacks.
		 * @return array
		 */
		public function font_stacks( $stacks ) {

			// check Open Sans
			if ( ! isset( $stacks['sans']['pt-sans'] ) ) {
				// add the array
				$stacks['sans']['pt-sans'] = array(
					'label' => __( 'PT Sans', 'gppro' ),
					'css'   => '"PT Sans", sans-serif',
					'src'   => 'native',
					'size'  => '0',
				);
			}

			// check Arbutus Slab
			if ( ! isset( $stacks['serif']['merriweather'] ) ) {
				// add the array
				$stacks['serif']['merriweather'] = array(
					'label' => __( 'Merriweather', 'gppro' ),
					'css'   => '"Merriweather", serif',
					'src'   => 'native',
					'size'  => '0',
				);
			}

			// send it back
			return $stacks;
		}

		/**
		 * swap default values to match Academy Pro
		 *
		 * @param  array $defaults The default values.
		 * @return array $defaults
		 */
		public function set_defaults( $defaults ) {
			$changes = array(
				// general body
				'body-color-back-thin'                     => '',  // Removed
				'body-color-back-main'                     => '#ffffff',
				'body-color-text'                          => '#222222',
				'body-color-link'                          => '#e22c2f',
				'body-color-link-hov'                      => '#333333',
				'body-link-text-decoration'                => 'underline',
				'body-link-text-decoration-hov'            => 'none',

				'body-type-stack'                          => 'pt-sans',
				'body-type-size'                           => '20',
				'body-type-size-mobile'                    => '18',
				'body-type-weight'                         => '400',
				'body-type-style'                          => 'normal',

				// Top Banner.
				'header-top-banner-back'                   => '#e02f36',
				'header-top-banner-color'                  => '#ffffff',
				'header-top-banner-align'                  => 'center',
				'header-top-banner-font-size'              => '18',
				'header-top-banner-font-stack'             => 'pt-sans',
				'header-top-banner-lk-txt-dec'             => 'underline',
				'header-top-banner-lk-txt-dec-hov'         => 'none',
				'header-top-banner-font-weight'            => '700',
				'header-top-banner-padding-top'            => '24',
				'header-top-banner-padding-left'           => '80',
				'header-top-banner-padding-right'          => '80',
				'header-top-banner-padding-bottom'         => '24',
				'header-top-banner-margin-bottom'          => '0',

				// site header
				'header-color-back'                        => '#ffffff',
				'header-color-opacity'                     => '100',
				'header-padding-top'                       => '40',
				'header-padding-bottom'                    => '100',
				'header-padding-left'                      => '50',
				'header-padding-right'                     => '50',

				// site title
				'site-title-text'                          => '#222222',
				'site-title-stack'                         => 'pt-sans',
				'site-title-size'                          => '16',
				'site-title-weight'                        => '700',
				'site-title-transform'                     => 'uppercase',
				'site-title-align'                         => 'left',
				'site-title-style'                         => 'normal',
				'site-title-padding-top'                   => '10',
				'site-title-padding-bottom'                => '10',
				'site-title-padding-left'                  => '0',
				'site-title-padding-right'                 => '0',

				// site description
				'site-desc-display'                        => 'block',
				'site-desc-text'                           => '#222222',
				'site-desc-stack'                          => 'pt-sans',
				'site-desc-size'                           => '16',
				'site-desc-weight'                         => '400',
				'site-desc-transform'                      => 'none',
				'site-desc-align'                          => 'left',
				'site-desc-style'                          => 'normal',

				// header navigation
				'header-nav-item-back'                     => '', // Removed
				'header-nav-item-back-hov'                 => '', // Removed
				'header-nav-item-link'                     => '', // Removed
				'header-nav-item-link-hov'                 => '', // Removed
				'header-nav-active-link'                   => '', // Removed
				'header-nav-active-link-hov'               => '', // Removed
				'header-nav-stack'                         => '', // Removed
				'header-nav-size'                          => '', // Removed
				'header-nav-weight'                        => '', // Removed
				'header-nav-transform'                     => '', // Removed
				'header-nav-style'                         => '', // Removed
				'header-nav-item-padding-top'              => '', // Removed
				'header-nav-item-padding-bottom'           => '', // Removed
				'header-nav-item-padding-left'             => '', // Removed
				'header-nav-item-padding-right'            => '', // Removed

				// header widgets
				'header-widget-title-color'                => '', // Removed
				'header-widget-title-stack'                => '', // Removed
				'header-widget-title-size'                 => '', // Removed
				'header-widget-title-size-mobile'          => '', // Removed
				'header-widget-title-weight'               => '', // Removed
				'header-widget-title-transform'            => '', // Removed
				'header-widget-title-align'                => '', // Removed
				'header-widget-title-style'                => '', // Removed
				'header-widget-title-margin-bottom'        => '', // Removed
				'header-widget-content-text'               => '', // Removed
				'header-widget-content-link'               => '', // Removed
				'header-widget-content-link-hov'           => '', // Removed
				'header-widget-content-stack'              => '', // Removed
				'header-widget-content-size'               => '', // Removed
				'header-widget-content-weight'             => '', // Removed
				'header-widget-content-align'              => '', // Removed
				'header-widget-content-style'              => '', // Removed

				// below header menu
				'primary-nav-area-back'                    => '#000000',
				'primary-nav-border-top-color'             => '', // Removed
				'primary-nav-border-top-style'             => '', // Removed
				'primary-nav-border-top-width'             => '', // Removed

				'primary-nav-top-stack'                    => 'pt-sans',
				'primary-nav-top-size'                     => '13',
				'primary-nav-top-weight'                   => '700',
				'primary-nav-top-align'                    => 'left',
				'primary-nav-top-style'                    => 'normal',
				'primary-nav-top-transform'                => 'uppercase',

				'primary-nav-top-item-base-back'           => '', // Removed
				'primary-nav-top-item-base-back-hov'       => '', // Removed
				'primary-nav-top-item-base-link'           => '#333333',
				'primary-nav-top-item-base-link-hov'       => '#e22c2f',
				'primary-nav-highlight-color'              => '#222222',
				'primary-nav-highlight-style'              => '', // Removed
				'primary-nav-highlight-width'              => '', // Removed

				'primary-nav-top-item-active-back'         => '', // Removed
				'primary-nav-top-item-active-back-hov'     => '', // Removed
				'primary-nav-top-item-active-link'         => '#e22c2f',
				'primary-nav-top-item-active-link-hov'     => '#e22c2f',

				'primary-nav-top-item-padding-top'         => '20',
				'primary-nav-top-item-padding-bottom'      => '20',
				'primary-nav-top-item-padding-left'        => '18',
				'primary-nav-top-item-padding-right'       => '18',

				'primary-highlight-button-background-color' => '#ffffff',
				'primary-highlight-button-color'            => '#222222',
				'primary-highlight-button-border-color'     => '#e22c2f',

				'primary-responsive-menu-background-color' => '#ffffff',
				'primary-responsive-menu-border-color'     => '#e02f36',
				'primary-responsive-menu-border-width'     => '2',
				'primary-responsive-menu-border-style'     => 'solid',
				'primary-responsive-icon-color'            => '#222222',
				'primary-responsive-icon-size'             => '20',
				'arrow-icon-color'                         => '#333333',
				'arrow-icon-color-hov'                     => '#e02f36',
				'arrow-icon-color-act'                     => '#e02f36',

				'primary-nav-drop-stack'                   => 'pt-sans',
				'primary-nav-drop-size'                    => '12',
				'primary-nav-drop-weight'                  => '700',
				'primary-nav-drop-transform'               => 'uppercase',
				'primary-nav-drop-align'                   => 'left',
				'primary-nav-drop-style'                   => 'normal',

				'primary-nav-drop-item-base-back'          => '',
				'primary-nav-drop-item-base-back-hov'      => '',
				'primary-nav-drop-item-base-link'          => '#e22c2f',
				'primary-nav-drop-item-base-link-hov'      => '#e22c2f',

				'primary-nav-drop-item-active-back'        => '', // Removed
				'primary-nav-drop-item-active-back-hov'    => '', // Removed
				'primary-nav-drop-item-active-link'        => '#e22c2f',
				'primary-nav-drop-item-active-link-hov'    => '#e22c2f',

				'primary-nav-drop-item-padding-top'        => '15',
				'primary-nav-drop-item-padding-bottom'     => '15',
				'primary-nav-drop-item-padding-left'       => '18',
				'primary-nav-drop-item-padding-right'      => '18',

				'primary-nav-drop-border-color'            => '#333333',
				'primary-nav-drop-border-style'            => 'none none solid',
				'primary-nav-drop-border-width'            => '0 0 1',

				// footer menu
				'secondary-nav-area-back'                  => '',  // Removed
				'secondary-nav-top-stack'                  => 'pt-sans',
				'secondary-nav-top-size'                   => '13',
				'secondary-nav-top-weight'                 => '700',
				'secondary-nav-top-transform'              => 'uppercase',
				'secondary-nav-top-align'                  => 'left',
				'secondary-nav-top-style'                  => 'normal',
				'secondary-nav-text-decoration'            => 'none',
				'secondary-nav-text-decoration-hov'        => 'underline',

				'secondary-nav-top-item-base-back'         => '', // Removed
				'secondary-nav-top-item-base-back-hov'     => '', // Removed
				'secondary-nav-top-item-base-link'         => '#e22c2f',
				'secondary-nav-top-item-base-link-hov'     => '#e22c2f',

				'secondary-nav-top-item-active-back'       => '', // Removed
				'secondary-nav-top-item-active-back-hov'   => '', // Removed
				'secondary-nav-top-item-active-link'       => '#e22c2f',
				'secondary-nav-top-item-active-link-hov'   => '#e22c2f',

				'secondary-nav-top-item-padding-top'       => '4',
				'secondary-nav-top-item-padding-bottom'    => '0',
				'secondary-nav-top-item-padding-left'      => '18',
				'secondary-nav-top-item-padding-right'     => '18',

				'secondary-nav-drop-stack'                 => '', // Removed
				'secondary-nav-drop-size'                  => '', // Removed
				'secondary-nav-drop-weight'                => '', // Removed
				'secondary-nav-drop-transform'             => '', // Removed
				'secondary-nav-drop-align'                 => '', // Removed
				'secondary-nav-drop-style'                 => '', // Removed

				'secondary-nav-drop-item-base-back'        => '', // Removed
				'secondary-nav-drop-item-base-back-hov'    => '', // Removed
				'secondary-nav-drop-item-base-link'        => '', // Removed
				'secondary-nav-drop-item-base-link-hov'    => '', // Removed

				'secondary-nav-drop-item-active-back'      => '', // Removed
				'secondary-nav-drop-item-active-back-hov'  => '', // Removed
				'secondary-nav-drop-item-active-link'      => '', // Removed
				'secondary-nav-drop-item-active-link-hov'  => '', // Removed

				'secondary-nav-drop-item-padding-top'      => '', // Removed
				'secondary-nav-drop-item-padding-bottom'   => '', // Removed
				'secondary-nav-drop-item-padding-left'     => '', // Removed
				'secondary-nav-drop-item-padding-right'    => '', // Removed

				'secondary-nav-drop-border-color'          => '', // Removed
				'secondary-nav-drop-border-style'          => '', // Removed
				'secondary-nav-drop-border-width'          => '', // Removed

				// front page section
				// front page hero title
				'front-page-hero-title-color'              => '#222222',
				'front-page-hero-title-stack'              => 'merriweather',
				'front-page-hero-title-size'               => '54',
				'front-page-hero-title-weight'             => '700',
				'front-page-hero-title-transform'          => 'none',
				'front-page-hero-title-align'              => 'left',
				'front-page-hero-title-style'              => 'normal',
				'front-page-hero-title-margin-bottom'      => '25',

				// front page hero text
				'front-page-hero-text-stack'               => 'pt-sans',
				'front-page-hero-text-color'               => '#222222',
				'front-page-hero-text-size'                => '20',
				'front-page-hero-text-align'               => 'left',
				'front-page-hero-text-style'               => 'normal',

				// front page hero button colored
				'front-page-hero-colored-btn-back'         => '#e22c2f',
				'front-page-hero-colored-btn-back-hov'     => '#e22c2f',
				'front-page-hero-colored-btn-color'        => '#ffffff',
				'front-page-hero-colored-btn-color-hover'  => '#ffffff',
				'front-page-hero-colored-btn-stack'        => 'pt-sans',
				'front-page-hero-colored-btn-size'         => '15',
				'front-page-hero-colored-btn-weight'       => '700',
				'front-page-hero-colored-btn-transform'    => 'uppercase',

				// front page hero button underlined
				'front-page-hero-underlined-btn-color'     => '#222222',
				'front-page-hero-underlined-btn-color-hover' => '#e22c2f',
				'front-page-hero-underlined-btn-border-bottom' => '#e22c2f',
				'front-page-hero-underlined-btn-stack'     => 'pt-sans',
				'front-page-hero-underlined-btn-size'      => '15',
				'front-page-hero-underlined-btn-weight'    => '700',
				'front-page-hero-underlined-btn-transform' => 'uppercase',

				// front page video container
				'front-page-hero-video-border-radius'      => '10',
				'front-page-hero-video-border-color'       => '#e22c2f',
				'front-page-hero-video-border-style'       => 'solid',
				'front-page-hero-video-border-width'       => '4',

				// front page featured on text
				'front-page-hero-featured-in-stack'        => 'merriweather',
				'front-page-hero-featured-in-color'        => '#555555',
				'front-page-hero-featured-in-size'         => '16',
				'front-page-hero-featured-in-style'        => 'italic',

				// front page 2
				// front page 1 title
				'front-page-1-title-color'                 => '#222222',
				'front-page-1-title-stack'                 => 'merriweather',
				'front-page-1-title-size'                  => '36',
				'front-page-1-title-size-mobile'           => '36',
				'front-page-1-title-weight'                => '700',
				'front-page-1-title-transform'             => 'none',
				'front-page-1-title-align'                 => 'left',
				'front-page-1-title-style'                 => 'normal',
				'front-page-1-title-margin-bottom'         => '20',

				// front page 1 text
				'front-page-1-text-stack'                  => 'pt-sans',
				'front-page-1-text-color'                  => '#222222',
				'front-page-1-text-size'                   => '26',
				'front-page-1-text-align'                  => 'left',
				'front-page-1-text-style'                  => 'normal',

				// ** front page 2
				// front page 2 button
				'front-page-2-icon-color'                  => '#cccccc',
				'front-page-2-icon-color-hover'            => '#cccccc',

				// front page 2 title
				'front-page-2-title-color'                 => '#222222',
				'front-page-2-title-stack'                 => 'merriweather',
				'front-page-2-title-size'                  => '26',
				'front-page-2-title-size-mobile'           => '26',
				'front-page-2-title-weight'                => '700',
				'front-page-2-title-transform'             => 'none',
				'front-page-2-title-align'                 => 'left',
				'front-page-2-title-style'                 => 'normal',
				'front-page-2-title-margin-bottom'         => '25',

				// front page 2 text
				'front-page-2-text-color'                  => '#222222',
				'front-page-2-text-stack'                  => 'pt-sans',
				'front-page-2-text-size'                   => '20',
				'front-page-2-text-align'                  => 'left',
				'front-page-2-text-style'                  => 'normal',

				// ** front page 3
				// front page 3 separator
				'front-page-3-separator-color'             => '#e22c2f',
				'front-page-3-separator-style'             => 'solid',
				'front-page-3-separator-width'             => '2',

				// front page 3 title
				'front-page-3-title-color'                 => '#222222',
				'front-page-3-title-stack'                 => 'merriweather',
				'front-page-3-title-size'                  => '36',
				'front-page-3-title-size-mobile'           => '36',
				'front-page-3-title-weight'                => '700',
				'front-page-3-title-transform'             => 'none',
				'front-page-3-title-align'                 => 'center',
				'front-page-3-title-style'                 => 'normal',
				'front-page-3-title-margin-bottom'         => '25',

				// front page 3 button
				'front-page-3-btn-back'                    => '#e22c2f',
				'front-page-3-btn-back-hov'                => '#e22c2f',
				'front-page-3-btn-color'                   => '#ffffff',
				'front-page-3-btn-color-hover'             => '#ffffff',
				'front-page-3-btn-stack'                   => 'pt-sans',
				'front-page-3-btn-size'                    => '15',
				'front-page-3-btn-weight'                  => '700',
				'front-page-3-btn-transform'               => 'uppercase',

				// front page 3 button text
				'front-page-3-btn-text-color'              => '#222222',
				'front-page-3-btn-text-color-hover'        => '#e22c2f',
				'front-page-3-btn-text-border-bottom'      => '#e22c2f',
				'front-page-3-btn-text-stack'              => 'pt-sans',
				'front-page-3-btn-text-size'               => '15',
				'front-page-3-btn-text-weight'             => '700',
				'front-page-3-btn-text-transform'          => 'uppercase',

				// front page 4
				// front page 4 heading
				'front-page-4-heading-color'               => '#666666',
				'front-page-4-heading-stack'               => 'merriweather',
				'front-page-4-heading-size'                => '15',
				'front-page-4-heading-align'               => 'center',
				'front-page-4-heading-style'               => 'italic',

				// front page 4 testimonials
				'front-page-4-testimonial-back'            => '#ffffff',
				'front-page-4-testimonial-radius'          => '10',
				'front-page-4-testimonial-padding-top'     => '45',
				'front-page-4-testimonial-padding-bottom'  => '40',
				'front-page-4-testimonial-padding-left'    => '75',
				'front-page-4-testimonial-padding-right'   => '60',

				// front page 4 blockquote
				'front-page-4-blockquote-color'            => '#222222',
				'front-page-4-blockquote-stack'            => 'merriweather',
				'front-page-4-blockquote-size'             => '18',
				'front-page-4-blockquote-align'            => 'left',
				'front-page-4-blockquote-style'            => 'italic',

				// front page 4 cite
				'front-page-4-cite-color'                  => '#222222',
				'front-page-1-cite-stack'                  => 'pt-sans',
				'front-page-4-cite-size'                   => '14',
				'front-page-4-cite-weight'                 => '700',
				'front-page-4-cite-transform'              => 'uppercase',
				'front-page-4-cite-align'                  => 'left',
				'front-page-4-cite-style'                  => 'uppercase',

				// front page 5
				// front page 5 image container
				'front-page-5-image-border-radius'         => '10',
				'front-page-5-image-border-color'          => '#e22c2f',
				'front-page-5-image-border-style'          => 'solid',
				'front-page-5-image-border-width'          => '4',

				// front page 5 title
				'front-page-5-title-color'                 => '#222222',
				'front-page-5-title-stack'                 => 'merriweather',
				'front-page-5-title-size'                  => '36',
				'front-page-5-title-size-mobile'           => '36',
				'front-page-5-title-weight'                => '700',
				'front-page-5-title-transform'             => 'none',
				'front-page-5-title-align'                 => 'left',
				'front-page-5-title-style'                 => 'normal',
				'front-page-5-title-margin-bottom'         => '20',

				// front page 5 intro
				'front-page-5-intro-color'                 => '#222222',
				'front-page-5-intro-stack'                 => 'pt-sans',
				'front-page-5-intro-size'                  => '26',
				'front-page-5-intro-align'                 => 'left',
				'front-page-5-intro-style'                 => 'normal',

				// front page 5 text
				'front-page-5-text-color'                  => '#222222',
				'front-page-5-text-stack'                  => 'pt-sans',
				'front-page-5-text-size'                   => '20',
				'front-page-5-text-align'                  => 'left',
				'front-page-5-text-style'                  => 'normal',

				// front page 5 button
				'front-page-5-link-color'                  => '#e22c2f',
				'front-page-5-link-color-hover'            => '#222222',
				'front-page-5-link-stack'                  => 'pt-sans',
				'front-page-5-link-size'                   => '20',
				'front-page-5-link-weight'                 => '400',
				'front-page-5-link-transform'              => 'normal',

				// front page 6
				// front page 6 title
				'front-page-6-title-color'                 => '#222222',
				'front-page-6-title-stack'                 => 'merriweather',
				'front-page-6-title-size'                  => '36',
				'front-page-6-title-size-mobile'           => '36',
				'front-page-6-title-weight'                => '700',
				'front-page-6-title-transform'             => 'none',
				'front-page-6-title-align'                 => 'left',
				'front-page-6-title-style'                 => 'normal',
				'front-page-6-title-margin-bottom'         => '20',

				// front page 6 text
				'front-page-6-text-color'                  => '#222222',
				'front-page-6-text-stack'                  => 'pt-sans',
				'front-page-6-text-size'                   => '20',
				'front-page-6-text-align'                  => 'left',
				'front-page-6-text-style'                  => 'normal',

				// front page 6 faq
				// front page 6 faq button
				'front-page-6-faq-button-back'             => '#ffffff',
				'front-page-6-faq-button-border-radius'    => '10',
				'front-page-6-faq-button-top-left-border-radius' => '10',
				'front-page-6-faq-button-top-right-border-radius' => '10',
				'front-page-6-faq-button-color'            => '#222222',
				'front-page-6-faq-button-color-hover'      => '#e22c2f',
				'front-page-6-faq-button-stack'            => 'merriweather',
				'front-page-6-faq-button-size'             => '20',
				'front-page-6-faq-button-align'            => 'left',
				'front-page-6-faq-button-style'            => 'normal',

				// front page 6 faq answer
				'front-page-6-faq-answer-back'             => '#ffffff',
				'front-page-6-faq-answer-bottom-left-border-radius' => '10',
				'front-page-6-faq-answer-bottom-right-border-radius' => '10',
				'front-page-6-faq-answer-color'            => '#222222',
				'front-page-6-faq-answer-stack'            => 'pt-sans',
				'front-page-6-faq-answer-size'             => '20',
				'front-page-6-faq-answer-align'            => 'left',
				'front-page-6-faq-answer-style'            => 'normal',

				// post content
				'site-inner-padding-top'                   => '0',

				'main-entry-back'                          => '#ffffff',
				'main-entry-border-radius'                 => '10',  // Removed

				'main-entry-padding-top'                   => '0',
				'main-entry-padding-bottom'                => '40',
				'main-entry-padding-left'                  => '0',
				'main-entry-padding-right'                 => '0',

				'main-entry-margin-top'                    => '0',
				'main-entry-margin-bottom'                 => '40',
				'main-entry-margin-left'                   => '0',
				'main-entry-margin-right'                  => '0',

				'post-title-text'                          => '#222222',
				'post-title-link'                          => '#333333',
				'post-title-link-hov'                      => '#e22c2f',
				'post-title-stack'                         => 'merriweather',
				'post-title-size'                          => '32',
				'post-title-weight'                        => '700',
				'post-title-transform'                     => 'none',
				'post-title-align'                         => 'center',
				'post-title-style'                         => 'normal',
				'post-title-margin-bottom'                 => '40',

				// entry border
				// todo: can't find this section
				'entry-header-border-color'                => '#000000',
				'entry-header-border-style'                => 'solid',
				'entry-header-border-width'                => '1',
				'entry-header-border-length'               => '80',

				// entry meta
				'post-header-meta-text-color'              => '#222222',
				'post-header-meta-date-color'              => '#222222',
				'post-header-meta-author-link'             => '#222222',
				'post-header-meta-author-link-hov'         => '#e22c2f',
				'post-header-meta-comment-link'            => '#222222',
				'post-header-meta-comment-link-hov'        => '#e22c2f',

				'post-header-meta-stack'                   => 'merriweather',
				'post-header-meta-size'                    => '12',
				'post-header-meta-weight'                  => '400',
				'post-header-meta-transform'               => 'none',
				'post-header-meta-align'                   => 'center',
				'post-header-meta-style'                   => 'normal',
				'post-meta-comment-lk-txt-dec'             => 'underline',
				'post-meta-comment-lk-txt-dec-hov'         => 'none',

				// post text
				'post-entry-text'                          => '#222222',
				'post-entry-link'                          => '#222222',
				'post-entry-link-hov'                      => '#e22c2f',

				'post-entry-stack'                         => 'pt-sans',
				'post-entry-size'                          => '20',
				'post-entry-weight'                        => '400',
				'post-entry-style'                         => 'normal',

				'post-entry-list-ol'                       => 'decimal',
				'post-entry-list-ul'                       => 'disc',

				// entry-footer
				'post-footer-category-text'                => '#222222',
				'post-footer-category-link'                => '#333333',
				'post-footer-category-link-hov'            => '#e22c2f',
				'post-footer-tag-text'                     => '#222222',
				'post-footer-tag-link'                     => '#222222',
				'post-footer-tag-link-hov'                 => '#e22c2f',
				'post-footer-comment-lk-txt-dec'           => 'underline',
				'post-footer-comment-lk-txt-dec-hov'       => 'none',

				'post-footer-stack'                        => 'merriweather',
				'post-footer-size'                         => '12',
				'post-footer-weight'                       => '400',
				'post-footer-transform'                    => 'none',
				'post-footer-align'                        => 'center',
				'post-footer-style'                        => 'normal',

				'post-footer-divider-color'                => '#222222',
				'post-footer-divider-style'                => 'none',
				'post-footer-divider-width'                => '0',

				// archive description
				'archive-title-text'                       => '#222222',
				'archive-title-stack'                      => 'merriweather',
				'archive-title-size'                       => '54',
				'archive-title-weight'                     => '700',
				'archive-title-transform'                  => 'none',
				'archive-title-align'                      => 'center',
				'archive-title-style'                      => 'normal',

				'archive-text-text'                        => '#222222',
				'archive-text-stack'                       => 'pt-sans',
				'archive-text-size'                        => '20',
				'archive-text-weight'                      => '400',
				'archive-text-transform'                   => 'none',
				'archive-text-align'                       => 'center',
				'archive-text-style'                       => 'normal',

				'archive-description-padding-top'          => '0',
				'archive-description-padding-bottom'       => '0',
				'archive-description-padding-left'         => '0',
				'archive-description-padding-right'        => '0',
				'archive-description-margin-bottom'        => '100',

				// read more link
				'extras-read-more-link'                    => '#222222',
				'extras-read-more-link-hov'                => '#e22c2f',
				'extras-read-more-stack'                   => 'pt-sans',
				'extras-read-more-size'                    => '15',
				'extras-read-more-weight'                  => '700',
				'extras-read-more-transform'               => 'uppercase',
				'extras-read-more-style'                   => 'normal',

				// breadcrumbs
				'extras-breadcrumb-back'                   => '',
				'extras-breadcrumb-text'                   => '#666666',
				'extras-breadcrumb-link'                   => '#e22c2f',
				'extras-breadcrumb-link-hov'               => '#666666',
				'extras-breadcrumb-stack'                  => 'pt-sans',
				'extras-breadcrumb-size'                   => '16',
				'extras-breadcrumb-weight'                 => '400',
				'extras-breadcrumb-transform'              => 'none',
				'extras-breadcrumb-style'                  => 'normal',

				// pagination typography (apply to both )
				'extras-pagination-stack'                  => 'pt-sans',
				'extras-pagination-size'                   => '16',
				'extras-pagination-weight'                 => '700',
				'extras-pagination-transform'              => 'none',
				'extras-pagination-style'                  => 'normal',

				// pagination text
				'extras-pagination-back'                   => '#ffffff',
				'extras-pagination-back-hov'               => '#e36344',
				'extras-pagination-text-link'              => '#222222',
				'extras-pagination-text-link-hov'          => '#ffffff',

				// pagination numeric
				'extras-pagination-numeric-back'           => '#ffffff',
				'extras-pagination-numeric-back-hov'       => '#e22c2f',
				'extras-pagination-numeric-active-back'    => '#e22c2f',
				'extras-pagination-numeric-active-back-hov' => '#e22c2f',
				'extras-pagination-numeric-border-radius'  => '100',

				'extras-pagination-numeric-padding-top'    => '15',
				'extras-pagination-numeric-padding-bottom' => '14',
				'extras-pagination-numeric-padding-left'   => '18',
				'extras-pagination-numeric-padding-right'  => '18',

				'extras-pagination-numeric-link'           => '#ffffff',
				'extras-pagination-numeric-link-hov'       => '#e22c2f',
				'extras-pagination-numeric-active-link'    => '#e22c2f',
				'extras-pagination-numeric-active-link-hov' => '#e22c2f',

				// after entry widget area
				// todo: can't find this section
				'after-entry-widget-area-back'             => '#ffffff',
				'after-entry-widget-area-border-radius'    => '', // Removed

				'after-entry-widget-area-padding-top'      => '60',
				'after-entry-widget-area-padding-bottom'   => '60',
				'after-entry-widget-area-padding-left'     => '60',
				'after-entry-widget-area-padding-right'    => '60',

				'after-entry-widget-area-margin-top'       => '0',
				'after-entry-widget-area-margin-bottom'    => '40',
				'after-entry-widget-area-margin-left'      => '0',
				'after-entry-widget-area-margin-right'     => '0',

				'after-entry-widget-back'                  => '', // Removed
				'after-entry-widget-border-radius'         => '', // Removed

				'after-entry-widget-padding-top'           => '0',
				'after-entry-widget-padding-bottom'        => '0',
				'after-entry-widget-padding-left'          => '0',
				'after-entry-widget-padding-right'         => '0',

				'after-entry-widget-margin-top'            => '0',
				'after-entry-widget-margin-bottom'         => '0',
				'after-entry-widget-margin-left'           => '0',
				'after-entry-widget-margin-right'          => '0',

				'after-entry-widget-title-text'            => '#000000',
				'after-entry-widget-title-stack'           => 'pt-sans',
				'after-entry-widget-title-size'            => '14',
				'after-entry-widget-title-weight'          => '700',
				'after-entry-widget-title-transform'       => 'uppercase',
				'after-entry-widget-title-align'           => 'left',
				'after-entry-widget-title-style'           => 'normal',
				'after-entry-widget-title-margin-bottom'   => '20',

				'after-entry-widget-content-text'          => '#000000',
				'after-entry-widget-content-link'          => '#e36344',
				'after-entry-widget-content-link-hov'      => '#000000',
				'after-entry-widget-content-stack'         => 'pt-sans',
				'after-entry-widget-content-size'          => '18',
				'after-entry-widget-content-weight'        => '400',
				'after-entry-widget-content-align'         => 'left',
				'after-entry-widget-content-style'         => 'normal',

				// author box
				// todo: can't find this section
				'extras-author-box-back'                   => '#ffffff',

				'extras-author-box-padding-top'            => '40',
				'extras-author-box-padding-bottom'         => '40',
				'extras-author-box-padding-left'           => '40',
				'extras-author-box-padding-right'          => '40',

				'extras-author-box-margin-top'             => '0',
				'extras-author-box-margin-bottom'          => '40',
				'extras-author-box-margin-left'            => '0',
				'extras-author-box-margin-right'           => '0',

				'extras-author-box-name-text'              => '#000000',
				'extras-author-box-name-stack'             => 'pt-sans',
				'extras-author-box-name-size'              => '16',
				'extras-author-box-name-weight'            => '400',
				'extras-author-box-name-align'             => 'left',
				'extras-author-box-name-transform'         => 'none',
				'extras-author-box-name-style'             => 'normal',

				'extras-author-box-bio-text'               => '#000000',
				'extras-author-box-bio-link'               => '#e36344',
				'extras-author-box-bio-link-hov'           => '#000000',
				'extras-author-box-bio-stack'              => 'pt-sans',
				'extras-author-box-bio-size'               => '16',
				'extras-author-box-bio-weight'             => '400',
				'extras-author-box-bio-style'              => 'normal',

				// comment list
				'comment-list-back'                        => '#ffffff',

				'comment-list-padding-top'                 => '40',
				'comment-list-padding-bottom'              => '40',
				'comment-list-padding-left'                => '0',
				'comment-list-padding-right'               => '0',

				'comment-list-margin-top'                  => '0',
				'comment-list-margin-bottom'               => '40',
				'comment-list-margin-left'                 => '0',
				'comment-list-margin-right'                => '0',

				// comment list title
				'comment-list-title-text'                  => '#222222',
				'comment-list-title-stack'                 => 'merriweather',
				'comment-list-title-size'                  => '26',
				'comment-list-title-weight'                => '700',
				'comment-list-title-transform'             => 'none',
				'comment-list-title-align'                 => 'left',
				'comment-list-title-style'                 => 'normal',
				'comment-list-title-margin-bottom'         => '25',

				// single comments
				'single-comment-padding-top'               => '40',
				'single-comment-padding-bottom'            => '0',
				'single-comment-padding-left'              => '0',
				'single-comment-padding-right'             => '0',

				'single-comment-margin-top'                => '0',
				'single-comment-margin-bottom'             => '0',
				'single-comment-margin-left'               => '0',
				'single-comment-margin-right'              => '0',

				// color setup for standard and author comments
				'single-comment-standard-back'             => '',  // Removed
				'single-comment-standard-border-color'     => '',  // Removed
				'single-comment-standard-border-style'     => '',  // Removed
				'single-comment-standard-border-width'     => '',  // Removed
				'single-comment-author-back'               => '',  // Removed
				'single-comment-author-border-color'       => '',  // Removed
				'single-comment-author-border-style'       => '',  // Removed
				'single-comment-author-border-width'       => '',  // Removed

				// comment author
				'comment-element-name-text'                => '#222222',
				'comment-element-name-link'                => '#222222',
				'comment-element-name-link-hov'            => '#222222',
				'comment-element-name-text-decoration'     => 'underline',
				'comment-element-name-decoration-hov'      => 'none',

				'comment-element-name-stack'               => 'pt-sans',
				'comment-element-name-size'                => '16',
				'comment-element-name-weight'              => '400',
				'comment-element-name-style'               => 'normal',

				// comment date
				'comment-element-date-link'                => '#222222',
				'comment-element-date-link-hov'            => '#222222',
				'comment-element-date-text-decoration'     => 'underline',
				'comment-element-date-decoration-hov'      => 'none',

				'comment-element-date-stack'               => 'pt-sans',
				'comment-element-date-size'                => '16',
				'comment-element-date-weight'              => '400',
				'comment-element-date-style'               => 'normal',

				// comment body
				'comment-element-body-text'                => '#222222',
				'comment-element-body-link'                => '#e22c2f',
				'comment-element-body-link-hov'            => '#222222',
				'comment-element-body-text-decoration'     => 'underline',
				'comment-element-body-decoration-hov'      => 'none',

				'comment-element-body-stack'               => 'pt-sans',
				'comment-element-body-size'                => '16',
				'comment-element-body-weight'              => '400',
				'comment-element-body-style'               => 'normal',

				// comment reply
				'comment-element-reply-link'               => '#222222',
				'comment-element-reply-link-hov'           => '#e22c2f',
				'comment-element-reply-text-decoration'    => 'underline',
				'comment-element-reply-decoration-hov'     => 'none',

				'comment-element-reply-stack'              => 'pt-sans',
				'comment-element-reply-size'               => '15',
				'comment-element-reply-weight'             => '700',
				'comment-element-reply-align'              => 'left',
				'comment-element-reply-style'              => 'normal',

				// trackback list
				// todo: can't find this section
				'trackback-list-back'                      => '#ffffff',
				'trackback-list-padding-top'               => '60',
				'trackback-list-padding-bottom'            => '32',
				'trackback-list-padding-left'              => '60',
				'trackback-list-padding-right'             => '60',

				'trackback-list-margin-top'                => '0',
				'trackback-list-margin-bottom'             => '40',
				'trackback-list-margin-left'               => '0',
				'trackback-list-margin-right'              => '0',

				// trackback list title
				// todo: can't find this section
				'trackback-list-title-text'                => '#000000',
				'trackback-list-title-stack'               => 'arbutus-slab',
				'trackback-list-title-size'                => '24',
				'trackback-list-title-weight'              => '400',
				'trackback-list-title-transform'           => 'none',
				'trackback-list-title-align'               => 'left',
				'trackback-list-title-style'               => 'normal',
				'trackback-list-title-margin-bottom'       => '10',

				// trackback name
				// todo: can't find this section
				'trackback-element-name-text'              => '#000000',
				'trackback-element-name-link'              => '#e36344',
				'trackback-element-name-link-hov'          => '#000000',
				'trackback-element-name-stack'             => 'pt-sans',
				'trackback-element-name-size'              => '16',
				'trackback-element-name-weight'            => '400',
				'trackback-element-name-style'             => 'normal',

				// trackback date
				// todo: can't find this section
				'trackback-element-date-link'              => '#e36344',
				'trackback-element-date-link-hov'          => '#000000',
				'trackback-element-date-stack'             => 'pt-sans',
				'trackback-element-date-size'              => '16',
				'trackback-element-date-weight'            => '400',
				'trackback-element-date-style'             => 'normal',

				// trackback body
				// todo: can't find this section
				'trackback-element-body-text'              => '#000000',
				'trackback-element-body-stack'             => 'pt-sans',
				'trackback-element-body-size'              => '16',
				'trackback-element-body-weight'            => '400',
				'trackback-element-body-style'             => 'normal',

				// comment form
				'comment-reply-back'                       => '#ffffff',

				'comment-reply-padding-top'                => '40',
				'comment-reply-padding-bottom'             => '40',
				'comment-reply-padding-left'               => '0',
				'comment-reply-padding-right'              => '0',

				'comment-reply-margin-top'                 => '0',
				'comment-reply-margin-bottom'              => '40',
				'comment-reply-margin-left'                => '0',
				'comment-reply-margin-right'               => '0',

				// comment form title
				'comment-reply-title-text'                 => '#222222',
				'comment-reply-title-stack'                => 'merriweather',
				'comment-reply-title-size'                 => '26',
				'comment-reply-title-weight'               => '700',
				'comment-reply-title-transform'            => 'none',
				'comment-reply-title-align'                => 'left',
				'comment-reply-title-style'                => 'normal',
				'comment-reply-title-margin-bottom'        => '25',

				// comment form notes
				'comment-reply-notes-text'                 => '#222222',
				'comment-reply-notes-link'                 => '#e22c2f',
				'comment-reply-notes-link-hov'             => '#222222',
				'comment-reply-notes-stack'                => 'pt-sans',
				'comment-reply-notes-size'                 => '16',
				'comment-reply-notes-weight'               => '400',
				'comment-reply-notes-style'                => 'normal',

				// comment allowed tags
				'comment-reply-atags-base-back'            => '', // Removed
				'comment-reply-atags-base-text'            => '', // Removed
				'comment-reply-atags-base-stack'           => '', // Removed
				'comment-reply-atags-base-size'            => '', // Removed
				'comment-reply-atags-base-weight'          => '', // Removed
				'comment-reply-atags-base-style'           => '', // Removed

				// comment allowed tags code
				'comment-reply-atags-code-text'            => '', // Removed
				'comment-reply-atags-code-stack'           => '', // Removed
				'comment-reply-atags-code-size'            => '', // Removed
				'comment-reply-atags-code-weight'          => '', // Removed

				// comment fields labels
				'comment-reply-fields-label-text'          => '#222222',
				'comment-reply-fields-label-stack'         => 'pt-sans',
				'comment-reply-fields-label-size'          => '16',
				'comment-reply-fields-label-weight'        => '400',
				'comment-reply-fields-label-transform'     => 'none',
				'comment-reply-fields-label-align'         => 'left',
				'comment-reply-fields-label-style'         => 'normal',

				// comment fields inputs
				// todo: can't find this section
				'comment-reply-fields-input-field-width'   => '50',
				'comment-reply-fields-input-border-style'  => 'solid',
				'comment-reply-fields-input-border-width'  => '1',
				'comment-reply-fields-input-border-radius' => ' ', // Removed
				'comment-reply-fields-input-padding'       => '16',
				'comment-reply-fields-input-margin-bottom' => '0',

				'comment-reply-fields-input-base-back'     => '#ffffff',
				'comment-reply-fields-input-focus-back'    => '#ffffff',
				'comment-reply-fields-input-base-border-color' => '#dddddd',
				'comment-reply-fields-input-focus-border-color' => '#999999',
				'comment-reply-fields-input-text'          => '#000000',

				'comment-reply-fields-input-stack'         => 'pt-sans',
				'comment-reply-fields-input-size'          => '16',
				'comment-reply-fields-input-weight'        => '400',
				'comment-reply-fields-input-style'         => 'normal',

				// comment button
				'comment-submit-button-back'               => '#e22c2f',
				'comment-submit-button-back-hov'           => '#e22c2f',
				'comment-submit-button-text'               => '#ffffff',
				'comment-submit-button-text-hov'           => '#ffffff',

				'comment-submit-button-stack'              => 'pt-sans',
				'comment-submit-button-size'               => '15',
				'comment-submit-button-weight'             => '700',
				'comment-submit-button-transform'          => 'uppercase',
				'comment-submit-button-style'              => 'normal',
				'comment-submit-button-padding-top'        => '22',
				'comment-submit-button-padding-bottom'     => '20',
				'comment-submit-button-padding-left'       => '36',
				'comment-submit-button-padding-right'      => '36',
				'comment-submit-button-border-radius'      => '100',

				// sidebar widgets
				'sidebar-widget-back'                      => '',
				'sidebar-widget-border-radius'             => '0', // Removed

				'sidebar-widget-padding-top'               => '20',
				'sidebar-widget-padding-bottom'            => '20',
				'sidebar-widget-padding-left'              => '40',
				'sidebar-widget-padding-right'             => '40',

				'sidebar-widget-margin-top'                => '0',
				'sidebar-widget-margin-bottom'             => '0',
				'sidebar-widget-margin-left'               => '0',
				'sidebar-widget-margin-right'              => '0',

				// sidebar widget titles
				'sidebar-widget-title-text'                => '#222222',
				'sidebar-widget-title-stack'               => 'merriweather',
				'sidebar-widget-title-size'                => '18',
				'sidebar-widget-title-weight'              => '700',
				'sidebar-widget-title-transform'           => 'none',
				'sidebar-widget-title-align'               => 'left',
				'sidebar-widget-title-style'               => 'normal',
				'sidebar-widget-title-margin-bottom'       => '20',

				// sidebar widget content
				'sidebar-widget-content-text'              => '#222222',
				'sidebar-widget-content-link'              => '#e22c2f',
				'sidebar-widget-content-link-hov'          => '#222222',
				'sidebar-widget-content-stack'             => 'pt-sans',
				'sidebar-widget-content-size'              => '16',
				'sidebar-widget-content-weight'            => '400',
				'sidebar-widget-content-align'             => 'left',
				'sidebar-widget-content-style'             => 'normal',

				// footer cta.
				'footer-cta-separator-color'               => '#e22c2f',
				'footer-cta-separator-style'               => 'solid',
				'footer-cta-separator-width'               => '2',

				// footer cta title
				'footer-cta-title-color'                   => '#222222',
				'footer-cta-title-stack'                   => 'merriweather',
				'footer-cta-title-size'                    => '36',
				'footer-cta-title-weight'                  => '700',
				'footer-cta-title-transform'               => 'none',
				'footer-cta-title-align'                   => 'center',
				'footer-cta-title-style'                   => 'normal',
				'footer-cta-title-margin-bottom'           => '25',

				// footer cta button
				'footer-cta-btn-back'                      => '#e22c2f',
				'footer-cta-btn-back-hov'                  => '#e22c2f',
				'footer-cta-btn-color'                     => '#ffffff',
				'footer-cta-btn-color-hover'               => '#ffffff',
				'footer-cta-btn-stack'                     => 'pt-sans',
				'footer-cta-btn-size'                      => '15',
				'footer-cta-btn-weight'                    => '700',
				'footer-cta-btn-transform'                 => 'uppercase',

				// footer cta button text
				'footer-cta-btn-text-color'                => '#222222',
				'footer-cta-btn-text-color-hover'          => '#e22c2f',
				'footer-cta-btn-text-border-bottom'        => '#e22c2f',
				'footer-cta-btn-text-stack'                => 'pt-sans',
				'footer-cta-btn-text-size'                 => '15',
				'footer-cta-btn-text-weight'               => '700',
				'footer-cta-btn-text-transform'            => 'uppercase',

				// footer cta bottom border.
				'footer-cta-bottom-border-color'           => '#dddddd',
				'footer-cta-bottom-border-style'           => 'solid',
				'footer-cta-bottom-border-width'           => '1',

				// bottom footer
				'footer-main-back'                         => '#ffffff',

				'footer-main-padding-top'                  => '0',
				'footer-main-padding-bottom'               => '0',
				'footer-main-padding-left'                 => '0',
				'footer-main-padding-right'                => '0',

				'footer-main-content-text'                 => '#222222',
				'footer-main-content-link'                 => '#e22c2f',
				'footer-main-content-link-hov'             => '#222222',
				'footer-main-content-stack'                => 'pt-sans',
				'footer-main-content-size'                 => '16',
				'footer-main-content-weight'               => '400',
				'footer-main-content-transform'            => 'none',
				'footer-main-content-align'                => 'center',
				'footer-main-content-style'                => 'normal',
			);

			// put into key value pair
			foreach ( $changes as $key => $value ) {
				$defaults[ $key ] = $value;
			}
			// return the array
			return $defaults;
		}

		/**
		 * Add and filter options for entry content
		 *
		 * @param array $defaults The defaults.
		 * @return array $sections
		 */
		public function entry_content_defaults( $defaults ) {

			// entry content defaults
			// todo: can't find this section
			$changes = array(

				'entry-content-h1-weight'       => '400',
				'entry-content-h2-weight'       => '400',
				'entry-content-h3-weight'       => '400',
				'entry-content-h4-weight'       => '400',
				'entry-content-h5-weight'       => '400',
				'entry-content-h6-weight'       => '400',
				'entry-content-h1-link-dec'     => 'underline',
				'entry-content-h1-link-dec-hov' => 'none',
				'entry-content-h2-link-dec'     => 'underline',
				'entry-content-h2-link-dec-hov' => 'none',
				'entry-content-h3-link-dec'     => 'underline',
				'entry-content-h3-link-dec-hov' => 'none',
				'entry-content-h4-link-dec'     => 'underline',
				'entry-content-h4-link-dec-hov' => 'none',
				'entry-content-h5-link-dec'     => 'underline',
				'entry-content-h5-link-dec-hov' => 'none',
				'entry-content-h6-link-dec'     => 'underline',
				'entry-content-h6-link-dec-hov' => 'none',
				'entry-content-a-dec'           => 'underline',
				'entry-content-a-dec-hov'       => 'underline',

			);

			// put into key value pairs
			foreach ( $changes as $key => $value ) {
				$defaults[ $key ] = $value;
			}

			// return the array of default values
			return $defaults;
		}

		/**
		 * add and filter options in the genesis widgets - enews
		 *
		 * @param  array $defaults The defaults.
		 * @return array $sections
		 */
		public function enews_defaults( $defaults ) {
			$changes = array(

				// General
				'enews-widget-back'                      => '#ffffff',
				'enews-widget-title-color'               => '#222222',
				'enews-widget-text-color'                => '#222222',

				// General Typography
				'enews-title-gen-stack'                  => 'pt-sans',
				'enews-title-gen-size'                   => '16',
				'enews-title-gen-weight'                 => '400',
				'enews-title-gen-transform'              => 'none',
				'enews-title-gen-text-margin-bottom'     => '28',

				'enews-widget-gen-stack'                 => 'pt-sans',
				'enews-widget-gen-size'                  => '18',
				'enews-widget-gen-weight'                => '400',
				'enews-widget-gen-transform'             => 'none',
				'enews-widget-gen-text-margin-bottom'    => '28',

				// Field Inputs
				'enews-widget-field-input-back'          => '#ffffff',
				'enews-widget-field-input-text-color'    => '#666666',
				'enews-widget-field-input-stack'         => 'pt-sans',
				'enews-widget-field-input-size'          => '16',
				'enews-widget-field-input-weight'        => '400',
				'enews-widget-field-input-transform'     => 'uppercase',
				'enews-widget-field-input-border-color'  => '#dddddd',
				'enews-widget-field-input-border-type'   => 'solid',
				'enews-widget-field-input-border-width'  => '2',
				'enews-widget-field-input-border-radius' => '100',
				'enews-widget-field-input-border-color-focus' => '#e22c2f',
				'enews-widget-field-input-border-type-focus' => 'solid',
				'enews-widget-field-input-border-width-focus' => '2',
				'enews-widget-field-input-pad-top'       => '16',
				'enews-widget-field-input-pad-bottom'    => '14',
				'enews-widget-field-input-pad-left'      => '32',
				'enews-widget-field-input-pad-right'     => '32',
				'enews-widget-field-input-margin-bottom' => '16',
				'enews-widget-field-input-box-shadow'    => 'none', // Removed

				// Button Color
				'enews-widget-button-back'               => '#e22c2f',
				'enews-widget-button-back-hov'           => '#e22c2f',
				'enews-widget-button-text-color'         => '#ffffff',
				'enews-widget-button-text-color-hov'     => '#ffffff',

				// Button Typography
				'enews-widget-button-stack'              => 'pt-sans',
				'enews-widget-button-size'               => '16',
				'enews-widget-button-weight'             => '700',
				'enews-widget-button-transform'          => 'uppercase',

				// Botton Padding
				'enews-widget-button-pad-top'            => '22',
				'enews-widget-button-pad-bottom'         => '20',
				'enews-widget-button-pad-left'           => '36',
				'enews-widget-button-pad-right'          => '36',
				'enews-widget-button-margin-bottom'      => '0',
			);

			// put into key value pairs
			foreach ( $changes as $key => $value ) {
				$defaults[ $key ] = $value;
			}

			// return the array of default values
			return $defaults;
		}

		/**
		 * Add new block for sticky message layout and front page layout.
		 *
		 * @param  array $blocks The blocks array.
		 * @return array $blocks
		 */
		public function add_new_sections( $blocks ) {

			// Confirm that we don't already have a block for the front page.
			if ( ! isset( $blocks['frontpage'] ) ) {
				$blocks['frontpage'] = array(
					'tab'   => __( 'Front Page', 'gppro' ),
					'title' => __( 'Front Page Sections', 'gppro' ),
					'intro' => __( 'The frontpage uses 5 custom widget sections.', 'gppro', 'gppro' ),
					'slug'  => 'frontpage',
				);
			}

			// Return the block setup.
			return $blocks;
		}

		/**
		 * Add and filter options in the general body area.
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function general_body( $sections ) {

			// remove mobile background color option
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

			// remove sub and tip from body background color
			$sections = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip' ) );

			// chang target for body font size
			$sections['body-type-setup']['data']['body-type-size']['target'] = '> div';

			// add text decoration
			$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'body-color-link-hov', $sections['body-color-setup']['data'],
				array(
					'body-link-text-decoration-setup' => array(
						'title' => __( 'Link Style', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'body-link-text-decoration'       => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => 'a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'body-link-text-decoration-hov'   => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( 'a:hover', 'a:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

			// add general body mobile font size
			$sections['body-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'body-type-size', $sections['body-type-setup']['data'],
				array(
					'body-type-size-mobile' => array(
						'label'       => __( 'Font Size', 'gppro' ),
						'sub'         => __( 'Mobile', 'gppro' ),
						'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
						'input'       => 'font-size',
						'scale'       => 'text',
						'target'      => '> div',
						'builder'     => 'GP_Pro_Builder::px_css',
						'selector'    => 'font-size',
						'media_query' => '@media only screen and (max-width: 860px)',
					),
				)
			);

			// ** return the section array
			return $sections;
		}

		/**
		 * add and filter options in the header area
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function header_area( $sections ) {

			// remove item background (base and hover), sub, and tip
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'header-nav-color-setup', array( 'header-nav-item-back', 'header-nav-item-back-hov' ) );
			$sections = GP_Pro_Helper::remove_data_from_items( $sections, 'header-nav-color-setup', 'header-nav-item-back-hov', array( 'sub', 'tip' ) );

			// remove item background (base and hover), sub, and tip
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'header-nav-color-setup', array( 'header-nav-item-back', 'header-nav-item-back-hov' ) );
			$sections = GP_Pro_Helper::remove_data_from_items( $sections, 'header-nav-color-setup', 'header-nav-item-back-hov', array( 'sub', 'tip' ) );

			// add active colors
			$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
				array(
					'header-nav-active-link'     => array(
						'label'    => __( 'Active Link Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.genesis-nav-menu .current-menu-item > a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'header-nav-active-link-hov' => array(
						'label'    => __( 'Active Link Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => '.genesis-nav-menu  .current-menu-item > a:hover',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
				)
			);

			$sections = GP_Pro_Helper::array_insert_before(
				'header-back-setup', $sections,
				array(
					'section-break-header-top-banner' => array(
						'break' => array(
							'title' => __( 'Top Banner', 'gppro' ),
							'type'  => 'full',
							'text'  => __( 'This shows above the header unless dismissed. It is set up in the customizer.', 'gppro' ),
						),
					),
					'header-top-banner-setup'         => array(
						'title' => __( 'General', 'gppro' ),
						'data'  => array(
							'header-top-banner-back'  => array(
								'label'    => __( 'Background', 'gppro' ),
								'input'    => 'color',
								'target'   => '.academy-top-banner',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
								'selector' => 'background',
							),
							'header-top-banner-color' => array(
								'label'    => __( 'Color', 'gppro' ),
								'input'    => 'color',
								'target'   => '.academy-top-banner, .academy-top-banner a',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
								'selector' => 'color',
							),
							'header-top-banner-align' => array(
								'label'    => __( 'Align', 'gppro' ),
								'input'    => 'text-align',
								'target'   => '.academy-top-banner',
								'builder'  => 'GP_Pro_Builder::text_css',
								'selector' => 'text-align',
							),
						),
					),
					'header-top-banner-font'          => array(
						'title' => __( 'Fonts', 'gppro' ),
						'data'  => array(
							'header-top-banner-font-size'  => array(
								'label'    => __( 'Font Size', 'gppro' ),
								'input'    => 'font-size',
								'target'   => '.academy-top-banner, .academy-top-banner a',
								'selector' => 'font-size',
								'builder'  => 'GP_Pro_Builder::px_css',
							),
							'header-top-banner-font-stack' => array(
								'label'    => __( 'Font Stack', 'gppro' ),
								'input'    => 'font-stack',
								'target'   => '.academy-top-banner, .academy-top-banner a',
								'selector' => 'font-family',
								'builder'  => 'GP_Pro_Builder::font_stack',
							),
							'header-top-banner-lk-txt-dec' => array(
								'label'    => __( 'Link Style', 'gppro' ),
								'input'    => 'text-decoration',
								'target'   => array( '.academy-top-banner a' ),
								'builder'  => 'GP_Pro_Builder::text_css',
								'selector' => 'text-decoration',
							),
							'header-top-banner-lk-txt-dec-hov' => array(
								'label'    => __( 'Link Style', 'gppro' ),
								'sub'      => __( 'Hover', 'gppro' ),
								'input'    => 'text-decoration',
								'target'   => array( '.academy-top-banner a:hover' ),
								'builder'  => 'GP_Pro_Builder::text_css',
								'selector' => 'text-decoration',
							),
							'header-top-banner-font-weight' => array(
								'label'    => __( 'Font Stack', 'gppro' ),
								'input'    => 'font-weight',
								'target'   => '.academy-top-banner, .academy-top-banner a',
								'selector' => 'font-weight',
								'builder'  => 'GP_Pro_Builder::font_weight',
							),
						),
					),
					'header-top-banner-spacing'       => array(
						'title' => __( 'Padding', 'gppro' ),
						'data'  => array(
							'header-top-banner-padding-top' => array(
								'label'    => __( 'Top', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.academy-top-banner',
								'selector' => 'padding-top',
								'builder'  => 'GP_Pro_Builder::px_css',
								'min'      => '0',
								'max'      => '40',
								'step'     => '2',
							),
							'header-top-banner-padding-left' => array(
								'label'    => __( 'Left', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.academy-top-banner',
								'selector' => 'padding-left',
								'builder'  => 'GP_Pro_Builder::px_css',
								'min'      => '0',
								'max'      => '80',
								'step'     => '2',
							),
							'header-top-banner-padding-right' => array(
								'label'    => __( 'Right', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.academy-top-banner',
								'selector' => 'padding-right',
								'builder'  => 'GP_Pro_Builder::px_css',
								'min'      => '0',
								'max'      => '80',
								'step'     => '2',
							),
							'header-top-banner-padding-bottom' => array(
								'label'    => __( 'Bottom', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.academy-top-banner',
								'selector' => 'padding-bottom',
								'builder'  => 'GP_Pro_Builder::px_css',
								'min'      => '0',
								'max'      => '40',
								'step'     => '2',
							),
							'header-top-banner-margin-bottom' => array(
								'label'    => __( 'Bottom Margin', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.academy-top-banner',
								'selector' => 'margin-bottom',
								'builder'  => 'GP_Pro_Builder::px_css',
								'min'      => '0',
								'max'      => '80',
								'step'     => '2',
							),
						),
					),
				)
			);

			// Remove unused sections.
			$unset = array(
				'section-break-header-widgets',
				'header-widget-title-setup',
				'header-widget-content-setup',
			);

			foreach ( $unset as $section ) {
				unset( $sections[ $section ] );
			}

			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the navigation area
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function navigation( $sections ) {

			// rename primary navigation
			$sections['section-break-primary-nav']['break']['title'] = __( 'Below Header Menu', 'gppro' );

			// rename secondary navigation
			$sections['section-break-secondary-nav']['break']['title'] = __( 'Footer Menu', 'gppro' );

			// change primary navigation text description
			$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the navigation menu that displays Below the Header.', 'gppro' );

			// remove primary menu item background
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

			// remove primary active menu item background
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

			// change secondary navigation text description
			$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the navigation menu that displays in the Footer.', 'gppro' );

			// remove footer menu background color
			$sections = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-area-setup' ) );

			// remove footer menu item background
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

			// remove footer menu active menu item background
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array( 'secondary-nav-top-item-active-back', 'secondary-nav-top-item-active-back-hov' ) );

			// remove drop down settings from footer menu
			$sections = GP_Pro_Helper::remove_settings_from_section(
				$sections, array(
					'secondary-nav-drop-type-setup',
					'secondary-nav-drop-item-color-setup',
					'secondary-nav-drop-active-color-setup',
					'secondary-nav-drop-padding-setup',
					'secondary-nav-drop-border-setup',
				)
			);

			// remove footer menu item padding
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-padding-setup', array( 'secondary-nav-top-item-padding-top', 'secondary-nav-top-item-padding-bottom', 'secondary-nav-top-item-padding-left', 'secondary-nav-top-item-padding-right' ) );

			// remove footer menu padding
			$sections = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-top-padding-setup' ) );

			// remove primary nav top item background color base
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back' ) );

			// add border bottom to primary navigation
			$sections['primary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'primary-nav-area-back', $sections['primary-nav-area-setup']['data'],
				array(
					'primary-nav-border-top-setup' => array(
						'title' => __( 'Top Border', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'primary-nav-border-top-color' => array(
						'label'    => __( 'Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-border-top-style' => array(
						'label'    => __( 'Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.nav-primary',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'primary-nav-border-top-width' => array(
						'label'    => __( 'Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				)
			);

			$sections = GP_Pro_Helper::array_insert_before(
				'primary-nav-drop-type-setup', $sections,
				array(
					'primary-highlight-button' => array(
						'title' => __( 'Highlight Button', 'gppro' ),
						'data'  => array(
							'primary-highlight-button-background-color' => array(
								'label'    => __( 'Background Color', 'gppro' ),
								'input'    => 'color',
								'selector' => 'background-color',
								'target'   => '.menu > .highlight > a',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
							'primary-highlight-button-color' => array(
								'label'    => __( 'Text Color', 'gppro' ),
								'input'    => 'color',
								'selector' => 'color',
								'target'   => '.menu > .highlight > a',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
							'primary-highlight-button-border-color' => array(
								'label'    => __( 'Border Color', 'gppro' ),
								'input'    => 'color',
								'selector' => 'border-color',
								'target'   => '.menu > .highlight > a',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
						)
					)
				)
			);

			// add responsive navigation
			$sections = GP_Pro_Helper::array_insert_before(
				'primary-nav-drop-type-setup', $sections,
				array(
					'primary-responsive-icon-area-setup' => array(
						'title' => __( 'Responsive Menu', 'gppro' ),
						'data'  => array(
							'primary-responsive-menu-background-color' => array(
								'label'       => __( 'Background Color', 'gppro' ),
								'input'       => 'color',
								'target'      => '.menu-toggle',
								'selector'    => 'background-color',
								'media_query' => '@media only screen and (max-width: 1023px)',
								'builder'     => 'GP_Pro_Builder::hexcolor_css',
							),
							'primary-responsive-menu-text-color' => array(
								'label'       => __( 'Text Color', 'gppro' ),
								'input'       => 'color',
								'target'      => '.menu-toggle',
								'selector'    => 'color',
								'media_query' => '@media only screen and (max-width: 1023px)',
								'builder'     => 'GP_Pro_Builder::hexcolor_css',
							),
							'primary-responsive-icon-color' => array(
								'label'    => __( 'Icon Color', 'gppro' ),
								'input'    => 'color',
								'target'   => array( '.menu-toggle:before' ),
								'selector' => 'color',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
							'primary-responsive-icon-size' => array(
								'label'       => __( 'Icon Size', 'gppro' ),
								'input'       => 'font-size',
								'scale'       => 'title',
								'target'      => array( '.menu-toggle:before' ),
								'selector'    => 'font-size',
								'media_query' => '@media only screen and (max-width: 800px)',
								'builder'     => 'GP_Pro_Builder::px_css',

							),
							'primary-responsive-menu-border-color' => array(
								'label'    => __( 'Border Color', 'gppro' ),
								'input'    => 'color',
								'target'   => array( '.menu-toggle' ),
								'selector' => 'border-color',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
							'primary-responsive-menu-border-style' => array(
								'label'    => __( 'Border Style', 'gppro' ),
								'input'    => 'borders',
								'target'   => '.menu-toggle',
								'builder'  => 'GP_Pro_Builder::text_css',
								'selector' => 'border-style',
								'tip'      => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
							),
							'primary-responsive-menu-border-width' => array(
								'label'    => __( 'Border Width', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.menu-toggle',
								'selector' => 'border-width',
								'builder'  => 'GP_Pro_Builder::px_css',
								'min'      => '0',
								'max'      => '10',
								'step'     => '1',
							),
							'arrow-icon-color'             => array(
								'label'    => __( 'Arrow Color', 'gppro' ),
								'sub'      => __( 'Inactive', 'gppro' ),
								'input'    => 'color',
								'target'   => array( '.sub-menu-toggle' ),
								'selector' => 'color',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
							'arrow-icon-color-hov'         => array(
								'label'    => __( 'Arrow Color', 'gppro' ),
								'sub'      => __( 'Hover', 'gppro' ),
								'input'    => 'color',
								'target'   => array( '.sub-menu-toggle:hover' ),
								'selector' => 'color',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
							'arrow-icon-color-act'         => array(
								'label'    => __( 'Arrow Color', 'gppro' ),
								'sub'      => __( 'Active', 'gppro' ),
								'input'    => 'color',
								'target'   => array( '.sub-menu-toggle.activated:before' ),
								'selector' => 'color',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
							),
						),
					),
				)
			);

			// add primary nav highlight color
			$sections['primary-nav-top-item-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'primary-nav-top-item-base-link-hov', $sections['primary-nav-top-item-color-setup']['data'],
				array(
					'primary-nav-highlight-color' => array(
						'label'    => __( 'Highlight Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.genesis-nav-menu > .highlight a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'border-bottom-color',
					),
					'primary-nav-highlight-style' => array(
						'label'    => __( 'Highlight Style', 'gppro' ),
						'input'    => 'color',
						'target'   => '.genesis-nav-menu > .highlight a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'border-bottom-style',
					),
					'primary-nav-highlight-width' => array(
						'label'    => __( 'Highlight Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.genesis-nav-menu > .highlight a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-bottom-width',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				)
			);

			// add text decoration to footer menu
			$sections['secondary-nav-top-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'secondary-nav-top-transform', $sections['secondary-nav-top-type-setup']['data'],
				array(
					'secondary-nav-text-decoration-setup' => array(
						'title' => __( 'Link Style', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'secondary-nav-text-decoration'       => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'secondary-nav-text-decoration-hov'   => array(
						'label'        => __( 'Link Style', 'gppro' ),
						'sub'          => __( 'Hover', 'gppro' ),
						'input'        => 'text-decoration',
						'target'       => array( '.nav-secondary .genesis-nav-menu > .menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .menu-item > a:focus' ),
						'builder'      => 'GP_Pro_Builder::text_css',
						'selector'     => 'text-decoration',
						'always_write' => true,
					),
				)
			);
			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the post content area
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function post_content( $sections ) {

			$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'main-entry-back', $sections['main-entry-setup']['data'],
				array(
					'main-entry-back-hover'          => array(
						'label'    => __( 'Background Color (Hover)', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content > .entry:hover',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
				)
			);

			$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'main-entry-border-radius', $sections['main-entry-setup']['data'],
				array(
					'main-entry-border-radius-hover' => array(
						'label'    => __( 'Border Radius (Hover)', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content > .entry:hover',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '16',
						'step'     => '1',
					),
				)
			);

			// add post meta text decoration
			$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'post-header-meta-comment-link-hov', $sections['post-header-meta-color-setup']['data'],
				array(
					'post-meta-comment-lk-txt-dec-setup' => array(
						'title' => __( 'Comment Link', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'post-meta-comment-lk-txt-dec'       => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => '.entry-header .entry-meta .entry-comments-link a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'post-meta-comment-lk-txt-dec-hov'   => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.entry-header .entry-meta .entry-comments-link a:hover', '.entry-header .entry-meta .entry-comments-link a:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

			// add post content text decoration
			if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
				$sections['post-entry-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
					'post-entry-link-hov', $sections['post-entry-color-setup']['data'],
					array(
						'post-entry-lk-txt-dec-setup' => array(
							'title' => __( 'Comment Link', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'post-entry-lk-txt-dec'       => array(
							'label'    => __( 'Link Style', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'text-decoration',
							'target'   => '.content > .entry .entry-content a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-decoration',
						),
						'post-entry-lk-txt-dec-hov'   => array(
							'label'    => __( 'Link Style', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'text-decoration',
							'target'   => array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-decoration',
						),
					)
				);
			}

			// add post footer text decoration
			$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'post-footer-tag-link-hov', $sections['post-footer-color-setup']['data'],
				array(
					'post-footer-comment-lk-txt-dec-setup' => array(
						'title' => __( 'Post Footer Link', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'post-footer-comment-lk-txt-dec'       => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.entry-footer .entry-categories a', '.entry-footer .entry-tags a' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'post-footer-comment-lk-txt-dec-hov'   => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array(
							'.entry-footer .entry-categories a:hover',
							'.entry-footer .entry-categories a:focus',
							'.entry-footer .entry-tags a:hover',
							'.entry-footer .entry-tags a:focus',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

			// add blog archive
			$sections = GP_Pro_Helper::array_insert_after(
				'post-footer-divider-setup', $sections,
				array(
					// add archive page setting
					'section-break-archive-page'  => array(
						'break' => array(
							'type'  => 'full',
							'title' => __( 'Archive Page', 'gppro' ),
							'text'  => __( 'These settings apply to the archive page title and description.', 'gppro' ),
						),
					),

					// add background color
					'archive-description-back'    => array(
						'title' => __( '', 'gppro' ),
						'data'  => array(
							'archive-title-back' => array(
								'label'    => __( 'Background', 'gppro' ),
								'input'    => 'color',
								'target'   => '.archive-description .archive-title',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
								'selector' => 'background-color',
							),
						),
					),

					// add padding and margin setting
					'archive-description-padding' => array(
						'title' => __( 'Padding', 'gppro' ),
						'data'  => array(
							'archive-description-padding-top'  => array(
								'label'    => __( 'Top', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.archive-description .archive-title',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'padding-top',
								'min'      => '0',
								'max'      => '80',
								'step'     => '1',
							),
							'archive-description-padding-bottom' => array(
								'label'    => __( 'Bottom', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.archive-description .archive-title',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'padding-bottom',
								'min'      => '0',
								'max'      => '80',
								'step'     => '1',
							),
							'archive-description-padding-left' => array(
								'label'    => __( 'Left', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.archive-description .archive-title',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'padding-left',
								'min'      => '0',
								'max'      => '32',
								'step'     => '1',
							),
							'archive-description-padding-right' => array(
								'label'    => __( 'Right', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.archive-description .archive-title',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'padding-right',
								'min'      => '0',
								'max'      => '80',
								'step'     => '1',
							),
							'archive-description-margin-setup' => array(
								'title' => __( 'Margin Bottom', 'gppro' ),
								'input' => 'divider',
								'style' => 'lines',
							),
							'archive-description-margin-bottom' => array(
								'label'    => __( 'Margin Bottom', 'gppro' ),
								'input'    => 'spacing',
								'target'   => '.archive-description .archive-title, .archive-description',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'margin-bottom',
								'min'      => '0',
								'max'      => '70',
								'step'     => '1',
							),
						),
					),

					// add archive title typography settings
					'archive-title-type-setup'    => array(
						'title' => __( 'Title Typography', 'gppro' ),
						'data'  => array(
							'archive-title-text'      => array(
								'label'    => __( 'Text', 'gppro' ),
								'input'    => 'color',
								'target'   => array( '.archive-title', '.archive-description .entry-title' ),
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
								'selector' => 'color',
							),
							'archive-title-stack'     => array(
								'label'    => __( 'Font Stack', 'gppro' ),
								'input'    => 'font-stack',
								'target'   => array( '.archive-title', '.archive-description .entry-title' ),
								'builder'  => 'GP_Pro_Builder::stack_css',
								'selector' => 'font-family',
							),
							'archive-title-size'      => array(
								'label'    => __( 'Font Size', 'gppro' ),
								'input'    => 'font-size',
								'scale'    => 'text',
								'target'   => array( '.archive-title', '.archive-description .entry-title' ),
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'font-size',
							),
							'archive-title-weight'    => array(
								'label'    => __( 'Font Weight', 'gppro' ),
								'input'    => 'font-weight',
								'target'   => array( '.archive-title', '.archive-description .entry-title' ),
								'builder'  => 'GP_Pro_Builder::number_css',
								'selector' => 'font-weight',
								'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							),
							'archive-title-transform' => array(
								'label'    => __( 'Text Appearance', 'gppro' ),
								'input'    => 'text-transform',
								'target'   => array( '.archive-title', '.archive-description .entry-title' ),
								'selector' => 'text-transform',
								'builder'  => 'GP_Pro_Builder::text_css',
							),
							'archive-title-align'     => array(
								'label'        => __( 'Text Alignment', 'gppro' ),
								'input'        => 'text-align',
								'target'       => array( '.archive-title', '.archive-description .entry-title' ),
								'builder'      => 'GP_Pro_Builder::text_css',
								'selector'     => 'text-align',
								'always_write' => true,
							),
							'archive-title-style'     => array(
								'label'    => __( 'Font Style', 'gppro' ),
								'input'    => 'radio',
								'options'  => array(
									array(
										'label' => __( 'Normal', 'gppro' ),
										'value' => 'normal',
									),
									array(
										'label' => __( 'Italic', 'gppro' ),
										'value' => 'italic',
									),
								),
								'target'   => array( '.archive-title', '.archive-description .entry-title' ),
								'builder'  => 'GP_Pro_Builder::text_css',
								'selector' => 'font-style',
							),
						),
					),

					// add archive text typography settings
					'archive-text-type-setup'     => array(
						'title' => __( 'Text Typography', 'gppro' ),
						'data'  => array(
							'archive-text-text'      => array(
								'label'    => __( 'Text', 'gppro' ),
								'input'    => 'color',
								'target'   => '.archive-description p',
								'builder'  => 'GP_Pro_Builder::hexcolor_css',
								'selector' => 'color',
							),
							'archive-text-stack'     => array(
								'label'    => __( 'Font Stack', 'gppro' ),
								'input'    => 'font-stack',
								'target'   => array( '.archive-title', '.archive-description .entry-title' ),
								'builder'  => 'GP_Pro_Builder::stack_css',
								'selector' => 'font-family',
							),
							'archive-text-size'      => array(
								'label'    => __( 'Font Size', 'gppro' ),
								'input'    => 'font-size',
								'scale'    => 'text',
								'target'   => '.archive-description p',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'font-size',
							),
							'archive-text-weight'    => array(
								'label'    => __( 'Font Weight', 'gppro' ),
								'input'    => 'font-weight',
								'target'   => '.archive-description p',
								'builder'  => 'GP_Pro_Builder::number_css',
								'selector' => 'font-weight',
								'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							),
							'archive-text-transform' => array(
								'label'    => __( 'Text Appearance', 'gppro' ),
								'input'    => 'text-transform',
								'target'   => '.archive-description p',
								'selector' => 'text-transform',
								'builder'  => 'GP_Pro_Builder::text_css',
							),
							'archive-text-align'     => array(
								'label'        => __( 'Text Alignment', 'gppro' ),
								'input'        => 'text-align',
								'target'       => '.archive-description p',
								'builder'      => 'GP_Pro_Builder::text_css',
								'selector'     => 'text-align',
								'always_write' => true,
							),
							'archive-text-style'     => array(
								'label'    => __( 'Font Style', 'gppro' ),
								'input'    => 'radio',
								'options'  => array(
									array(
										'label' => __( 'Normal', 'gppro' ),
										'value' => 'normal',
									),
									array(
										'label' => __( 'Italic', 'gppro' ),
										'value' => 'italic',
									),
								),
								'target'   => '.archive-description p',
								'builder'  => 'GP_Pro_Builder::text_css',
								'selector' => 'font-style',
							),
						),
					),
				)
			);
			// return the section array
			return $sections;
		}

		/**
		 * add settings for frontpage block
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function frontpage_section( $sections ) {
			$sections['frontpage'] = array(

				// front page 1
				'section-break-front-page-hero'        => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Front Page Hero Text', 'gppro' ),
						'text'  => __( 'This area appears at the top of the front page and is configured with fields in the customizer.', 'gppro' ),
					),
				),

				// add front page hero title
				'front-page-hero-title'                => array(
					'title' => __( 'Hero Title', 'gppro' ),
					'data'  => array(
						'front-page-hero-title-color'     => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-hero-title-stack'     => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-hero-title-size'      => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-hero-title-weight'    => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-hero-title-transform' => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'front-page-hero-title-align'     => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-hero-title-style'     => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),

						'front-page-hero-title-margin-bottom' => array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.hero-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
					),
				),

				// add front page hero text
				'front-page-hero-text-setup'           => array(
					'title' => __( 'Hero Intro Paragraph', 'gppro' ),
					'data'  => array(
						'front-page-hero-text-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.hero-description',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-hero-text-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.hero-description',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-hero-text-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.hero-description',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-hero-text-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.hero-description',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-hero-text-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.hero-description',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// add front page hero button colored
				'front-page-hero-colored-btn-setup'    => array(
					'title' => __( 'Hero Colored Button', 'gppro' ),
					'data'  => array(
						'front-page-hero-colored-btn-back' => array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.hero-section .button.primary',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'front-page-hero-colored-btn-back-hov' => array(
							'label'        => __( 'Background', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.hero-section .button.primary:hover',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'background-color',
							'always_write' => true,
						),
						'front-page-hero-colored-btn-color' => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.hero-section .button.primary',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-hero-colored-btn-color-hover' => array(
							'label'        => __( 'Font Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.hero-section .button.primary:hover',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
						'front-page-hero-colored-type-divider' => array(
							'title' => __( 'Typography', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'front-page-hero-colored-btn-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.hero-section .button.primary',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-hero-colored-btn-size' => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.hero-section .button.primary',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-hero-colored-btn-weight' => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.hero-section .button.primary',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-hero-colored-btn-transform' => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.hero-section .button.primary',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
					),
				),

				// add front page hero button underlined
				'front-page-hero-underlined-btn-setup' => array(
					'title' => __( 'Hero Underlined Button', 'gppro' ),
					'data'  => array(
						'front-page-hero-underlined-btn-color' => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.hero-section .button.text',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-hero-underlined-btn-color-hover' => array(
							'label'        => __( 'Font Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.hero-section .button.text:hover',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
						'front-page-hero-underlined-btn-border-bottom' => array(
							'label'        => __( 'Underline Color', 'gppro' ),
							'input'        => 'color',
							'target'       => '.hero-section .button.text',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'border-bottom-color',
							'always_write' => true,
						),
						'front-page-hero-underlined-type-divider' => array(
							'title' => __( 'Typography', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'front-page-hero-underlined-btn-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.hero-section .button.text',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-hero-underlined-btn-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.hero-section .button.text',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-hero-underlined-btn-weight' => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.hero-section .button.text',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-hero-underlined-btn-transform' => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.hero-section .button.text',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
					),
				),

				// add front page video container
				'front-page-hero-video-setup'          => array(
					'title' => __( 'Hero Video Container', 'gppro' ),
					'data'  => array(
						'front-page-hero-video-border-radius' => array(
							'label'    => __( 'Container Radius', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.hero-video-container', '.hero-section-column.right::before' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-radius',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
						),
						'front-page-hero-video-border-setup'  => array(
							'title' => __( 'Border', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'front-page-hero-video-border-color'  => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.hero-section-column.right::before',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'border-color',
						),
						'front-page-hero-video-border-style'  => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.hero-section-column.right::before',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'border-style',
							'tip'      => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'front-page-hero-video-border-width'  => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.hero-section-column.right::before',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-width',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),

				// add front page featured on text
				'front-page-hero-featured-in-setup'    => array(
					'title' => __( 'Hero Featured In', 'gppro' ),
					'data'  => array(
						'front-page-hero-featured-in-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.hero-logos-header',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-hero-featured-in-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.hero-logos-header',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-hero-featured-in-size' => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.hero-logos-header',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-hero-featured-in-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.hero-logos-header',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// front page 1
				'section-break-front-page-1'           => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Front Page Section One', 'gppro' ),
						'text'  => __( 'This area uses two Text widgets.', 'gppro' ),
					),
				),

				// add front page 1 title
				'front-page-1-title'                   => array(
					'title' => __( 'Widget Title', 'gppro' ),
					'data'  => array(
						'front-page-1-title-color'         => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-1-title-stack'         => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-1-title-size'          => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-1-title-size-mobile'   => array(
							'label'       => __( 'Font Size', 'gppro' ),
							'sub'         => __( 'Mobile', 'gppro' ),
							'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
							'input'       => 'font-size',
							'scale'       => 'text',
							'target'      => '.front-page-1 h3.widget-title',
							'media_query' => '@media only screen and (max-width: 800px)',
							'builder'     => 'GP_Pro_Builder::px_css',
							'selector'    => 'font-size',
						),
						'front-page-1-title-weight'        => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-1-title-transform'     => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'front-page-1-title-align'         => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-1-title-style'         => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),

						'front-page-1-title-margin-bottom' => array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-1 h3.widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
					),
				),

				// add front page 1 text
				'front-page-1-text-setup'              => array(
					'title' => __( 'Widget Content', 'gppro' ),
					'data'  => array(
						'front-page-1-text-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-1 .widget, .front-page-1 .intro',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-1-text-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-1 .widget',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-1-text-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-1 .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-1-text-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-1 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-1-text-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-1 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// ** front page 2
				'section-break-front-page-two'         => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Front Page Section Two', 'gppro' ),
						'text'  => __( 'This area uses six text widgets with an icon, title, and content.', 'gppro' ),
					),
				),

				// add front page 2 button
				'front-page-2-icon-setup'              => array(
					'title' => __( 'Icon', 'gppro' ),
					'data'  => array(
						'front-page-2-icon-color'       => array(
							'label'    => __( 'Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-2 i:before',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-2-icon-color-hover' => array(
							'label'        => __( 'Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.front-page-2 i:hover:before',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
					),
				),

				// add front page 2 title
				'front-page-2-title-setup'             => array(
					'title' => __( 'Widget Title', 'gppro' ),
					'data'  => array(
						'front-page-2-title-color'         => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-2-title-stack'         => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-2-title-size'          => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-2-title-size-mobile'   => array(
							'label'       => __( 'Font Size', 'gppro' ),
							'sub'         => __( 'Mobile', 'gppro' ),
							'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
							'input'       => 'font-size',
							'scale'       => 'text',
							'target'      => '.front-page-2 h3',
							'media_query' => '@media only screen and (max-width: 800px)',
							'builder'     => 'GP_Pro_Builder::px_css',
							'selector'    => 'font-size',
						),
						'front-page-2-title-weight'        => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-2-title-transform'     => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'front-page-2-title-align'         => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-2-title-style'         => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),

						'front-page-2-title-margin-bottom' => array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-2 h3',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '22',
							'step'     => '1',
						),
					),
				),

				// add front page 2 text
				'front-page-2-text-setup'              => array(
					'title' => __( 'Widget Content', 'gppro' ),
					'data'  => array(
						'front-page-2-text-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-2 .widget',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-2-text-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-2 .widget',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-2-text-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-2 .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-2-text-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-2 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-2-text-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-2 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// ** front page 3
				'section-break-front-page-three'       => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Front Page Section Three', 'gppro' ),
						'text'  => __( 'This area uses a text widget with an HTML button.', 'gppro' ),
					),
				),

				// add front page 3 separator
				'front-page-3-separator-setup'         => array(
					'title' => __( 'Separator', 'gppro' ),
					'data'  => array(
						'front-page-3-separator-color' => array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-3 .widget-area::before',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'front-page-3-separator-style' => array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.front-page-3 .widget-area::before',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'front-page-3-separator-width' => array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-3 .widget-area::before',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),

				// add front page 3 title
				'front-page-3-title-setup'             => array(
					'title' => __( 'Widget Title', 'gppro' ),
					'data'  => array(
						'front-page-3-title-color'         => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-3-title-stack'         => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-3-title-size'          => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-3-title-size-mobile'   => array(
							'label'       => __( 'Font Size', 'gppro' ),
							'sub'         => __( 'Mobile', 'gppro' ),
							'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
							'input'       => 'font-size',
							'scale'       => 'text',
							'target'      => '.front-page-3 .widget-title',
							'media_query' => '@media only screen and (max-width: 800px)',
							'builder'     => 'GP_Pro_Builder::px_css',
							'selector'    => 'font-size',
						),
						'front-page-3-title-weight'        => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-3-title-transform'     => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'front-page-3-title-align'         => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-3-title-style'         => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),

						'front-page-3-title-margin-bottom' => array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-3 .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '33',
							'step'     => '1',
						),
					),
				),

				// add front page 3 button
				'front-page-3-btn-setup'               => array(
					'title' => __( 'Button', 'gppro' ),
					'data'  => array(
						'front-page-3-btn-back'         => array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-3 .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'front-page-3-btn-back-hov'     => array(
							'label'        => __( 'Background', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.front-page-3 .button:hover:not(.text)',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'background-color',
							'always_write' => true,
						),
						'front-page-3-btn-color'        => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-3 .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-3-btn-color-hover'  => array(
							'label'        => __( 'Font Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.front-page-3 .button:hover:not(.text)',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
						'front-page-3-btn-type-divider' => array(
							'title' => __( 'Typography', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'front-page-3-btn-stack'        => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-3 .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-3-btn-size'         => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-3 .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-3-btn-weight'       => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-3 .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-3-btn-transform'    => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-3 .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
					),
				),

				// add front page 3 button text
				'front-page-3-btn-text-setup'          => array(
					'title' => __( 'Button Text Only', 'gppro' ),
					'data'  => array(
						'front-page-3-btn-text-color'     => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-3 .button.text',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-3-btn-text-color-hover' => array(
							'label'        => __( 'Font Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.front-page-3 .button.text:hover',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
						'front-page-3-btn-text-border-bottom' => array(
							'label'    => __( 'Underline Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-3 .button.text',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'border-bottom-color',
						),
						'front-page-3-type-divider'       => array(
							'title' => __( 'Typography', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'front-page-3-btn-text-stack'     => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-3 .button.text',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-3-btn-text-size'      => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-3 .button.text',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-3-btn-text-weight'    => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-3 .button.text',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-3-btn-text-transform' => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-3 .button.text',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
					),
				),

				// ** front page 4
				'section-break-front-page-four'        => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Front Page Section Four', 'gppro' ),
						'text'  => __( 'This area uses 4 text widgets. The first provides the title and the other 3 the testimony boxes', 'gppro' ),
					),
				),

				// add front page 4 heading
				'front-page-4-heading-setup'           => array(
					'title' => __( 'Heading', 'gppro' ),
					'data'  => array(
						'front-page-4-heading-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-4 .widget p:first-child',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-4-heading-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-4 .widget p:first-child',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-4-heading-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-4 .widget p:first-child',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-4-heading-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-4 .widget p:first-child',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-4-heading-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-4 .widget p:first-child',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// add front page 4 testimonials
				'front-page-4-testimonial-setup'       => array(
					'title' => __( 'Container', 'gppro' ),
					'data'  => array(
						'front-page-4-testimonial-back'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-4 .flexible-widgets .widget .widget-wrap',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background',
						),
						'front-page-4-testimonial-radius' => array(
							'label'    => __( 'Container Radius', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.front-page-4 .flexible-widgets .widget .widget-wrap' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-radius',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
						),
						'front-page-4-testimonial-padding-top' => array(
							'label'    => __( 'Padding Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-4 .flexible-widgets .widget .widget-wrap',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'front-page-4-testimonial-padding-bottom' => array(
							'label'    => __( 'Padding Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-4 .flexible-widgets .widget .widget-wrap',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'front-page-4-testimonial-padding-left' => array(
							'label'    => __( 'Padding Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-4 .flexible-widgets .widget .widget-wrap',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'front-page-4-testimonial-padding-right' => array(
							'label'    => __( 'Padding Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-4 .flexible-widgets .widget .widget-wrap',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
					),
				),

				// add front page 4 blockquote
				'front-page-4-blockquote-setup'        => array(
					'title' => __( 'Blockquote', 'gppro' ),
					'data'  => array(
						'front-page-4-blockquote-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-4 blockquote',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-4-blockquote-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-4 blockquote',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-4-blockquote-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-4 blockquote',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-4-blockquote-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'blockquote-align',
							'target'   => '.front-page-4 blockquote',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'blockquote-align',
						),
						'front-page-4-blockquote-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-4 blockquote',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// add front page 4 cite
				'front-page-4-cite-setup'              => array(
					'title' => __( 'Citation', 'gppro' ),
					'data'  => array(
						'front-page-4-cite-color'     => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-4 cite',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-1-cite-stack'     => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-4 cite',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-4-cite-size'      => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-4 cite',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-4-cite-weight'    => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-4 cite',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-4-cite-transform' => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-4 cite',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'front-page-4-cite-align'     => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-4 cite',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-4-cite-style'     => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-4 cite',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// front page 5
				'section-break-front-page-five'        => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Front Page Section Five', 'gppro' ),
						'text'  => __( 'This area uses an image widget and a text widget with an HTML intro section and HTML link.', 'gppro' ),
					),
				),

				// add front page 5 image container
				'front-page-5-image-setup'             => array(
					'title' => __( 'Hero Image Container', 'gppro' ),
					'data'  => array(
						'front-page-5-image-border-radius' => array(
							'label'    => __( 'Container Radius', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.front-page-5 .widget.widget_media_image img', '.front-page-5 .widget.widget_media_image .widget-wrap::before' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-radius',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
						),
						'front-page-5-image-border-setup'  => array(
							'title' => __( 'Border', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'front-page-5-image-border-color'  => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-5 .widget.widget_media_image .widget-wrap::before',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'border-color',
						),
						'front-page-5-image-border-style'  => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.front-page-5 .widget.widget_media_image .widget-wrap::before',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'border-style',
							'tip'      => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'front-page-5-image-border-width'  => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-5 .widget.widget_media_image .widget-wrap::before',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-width',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),

				// add front page 5 title
				'front-page-5-title-setup'             => array(
					'title' => __( 'Widget Title', 'gppro' ),
					'data'  => array(
						'front-page-5-title-color'         => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-5 .widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-5-title-stack'         => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-5 .widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-5-title-size'          => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-5 .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-5-title-size-mobile'   => array(
							'label'       => __( 'Font Size', 'gppro' ),
							'sub'         => __( 'Mobile', 'gppro' ),
							'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
							'input'       => 'font-size',
							'scale'       => 'text',
							'target'      => '.front-page-5 .widget-title',
							'media_query' => '@media only screen and (max-width: 800px)',
							'builder'     => 'GP_Pro_Builder::px_css',
							'selector'    => 'font-size',
						),
						'front-page-5-title-weight'        => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-5 h3',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-5-title-transform'     => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-5 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'front-page-5-title-align'         => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-5 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-5-title-style'         => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-5 h3',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),

						'front-page-5-title-margin-bottom' => array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-5 .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
					),
				),

				// add front page 5 intro
				'front-page-5-intro-setup'             => array(
					'title' => __( 'Widget Intro', 'gppro' ),
					'data'  => array(
						'front-page-5-intro-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-5 .intro',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-5-intro-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-5 .intro',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-5-intro-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-5 .intro',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-5-intro-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-5 .intro',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-5-intro-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-5 .intro',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// add front page 5 text
				'front-page-5-text-setup'              => array(
					'title' => __( 'Widget Content', 'gppro' ),
					'data'  => array(
						'front-page-5-text-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-5 .widget',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-5-text-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-5 .widget',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-5-text-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-5 .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-5-text-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-5 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-5-text-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-5 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// add front page 5 button
				'front-page-5-link-setup'              => array(
					'title' => __( 'Link', 'gppro' ),
					'data'  => array(
						'front-page-5-link-color'       => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-5 a',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-5-link-color-hover' => array(
							'label'        => __( 'Font Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.front-page-5 a:hover',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
						'front-page-5-link-stack'       => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-5 a',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-5-link-size'        => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-5 a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-5-link-weight'      => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-5 a',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-5-link-transform'   => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-5 a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
					),
				),

				// front page 6
				'section-break-front-page-six'         => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Front Page Section Six', 'gppro' ),
						'text'  => __( 'This area uses the eNews and Genesis Simple FAQ widget.', 'gppro' ),
					),
				),

				// add front page 6 title
				'front-page-6-title-setup'             => array(
					'title' => __( 'Widget Title', 'gppro' ),
					'data'  => array(
						'front-page-6-title-color'         => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-6-title-stack'         => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-6-title-size'          => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-6-title-size-mobile'   => array(
							'label'       => __( 'Font Size', 'gppro' ),
							'sub'         => __( 'Mobile', 'gppro' ),
							'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
							'input'       => 'font-size',
							'scale'       => 'text',
							'target'      => '.front-page-6 .widget-title',
							'media_query' => '@media only screen and (max-width: 800px)',
							'builder'     => 'GP_Pro_Builder::px_css',
							'selector'    => 'font-size',
						),
						'front-page-6-title-weight'        => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'front-page-6-title-transform'     => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'front-page-6-title-align'         => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-6-title-style'         => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),

						'front-page-6-title-margin-bottom' => array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.front-page-6 .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
					),
				),

				// add front page 6 text
				'front-page-6-text-setup'              => array(
					'title' => __( 'Widget Text', 'gppro' ),
					'data'  => array(
						'front-page-6-text-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.front-page-6 .widget',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'front-page-6-text-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.front-page-6 .widget',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'front-page-6-text-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.front-page-6 .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'front-page-6-text-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.front-page-6 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'front-page-6-text-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.front-page-6 .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

			);

			if ( class_exists( 'Genesis_Simple_FAQ' ) ) {
				// add breadcrumb background color
				$sections['frontpage'] = GP_Pro_Helper::array_insert_after(
					'front-page-6-text-setup', $sections['frontpage'],
					array(
						// add front page 6 faq button
						'front-page-6-faq-button-button-setup' => array(
							'title' => __( 'Simple FAQ', 'gppro' ),
							'data'  => array(
								'front-page-6-faq-button-back' => array(
									'label'    => __( 'Background', 'gppro' ),
									'input'    => 'color',
									'target'   => '.front-page-6 .gs-faq button',
									'builder'  => 'GP_Pro_Builder::hexcolor_css',
									'selector' => 'background',
								),
								'front-page-6-faq-button-border-radius-divider'    => array(
									'title' => __( 'Corner Radius', 'gppro' ),
									'input' => 'divider',
									'style' => 'lines',
								),
								'front-page-6-faq-button-border-radius' => array(
									'label'    => __( 'Un-Expanded', 'gppro' ),
									'input'    => 'spacing',
									'target'   => '.front-page-6 .gs-faq button:not(.gs-faq--expanded)',
									'builder'  => 'GP_Pro_Builder::px_css',
									'selector' => 'border-radius',
									'min'      => '0',
									'max'      => '80',
									'step'     => '1',
								),
								'front-page-6-faq-button-top-left-border-radius' => array(
									'label'    => __( 'Expanded Top Left', 'gppro' ),
									'input'    => 'spacing',
									'target'   => '.front-page-6 .gs-faq button.gs-faq--expanded',
									'builder'  => 'GP_Pro_Builder::px_css',
									'selector' => 'border-top-left-radius',
									'min'      => '0',
									'max'      => '80',
									'step'     => '1',
								),
								'front-page-6-faq-button-top-right-border-radius' => array(
									'label'    => __( 'Expanded Top Right', 'gppro' ),
									'input'    => 'spacing',
									'target'   => '.front-page-6 .gs-faq button.gs-faq--expanded',
									'builder'  => 'GP_Pro_Builder::px_css',
									'selector' => 'border-top-right-radius',
									'min'      => '0',
									'max'      => '80',
									'step'     => '1',
								),
								'front-page-6-faq-button-color' => array(
									'label'    => __( 'Color', 'gppro' ),
									'input'    => 'color',
									'target'   => '.front-page-6 .gs-faq button',
									'builder'  => 'GP_Pro_Builder::hexcolor_css',
									'selector' => 'color',
								),
								'front-page-6-faq-button-color-hover' => array(
									'label'    => __( 'Color', 'gppro' ),
									'sub'      => __( 'Hover', 'gppro' ),
									'input'    => 'color',
									'target'   => '.front-page-6 .gs-faq button:hover',
									'builder'  => 'GP_Pro_Builder::hexcolor_css',
									'selector' => 'color',
								),
								'front-page-6-faq-button-stack' => array(
									'label'    => __( 'Font Stack', 'gppro' ),
									'input'    => 'font-stack',
									'target'   => '.front-page-6 .gs-faq button',
									'builder'  => 'GP_Pro_Builder::stack_css',
									'selector' => 'font-family',
								),
								'front-page-6-faq-button-size'  => array(
									'label'    => __( 'Font Size', 'gppro' ),
									'input'    => 'font-size',
									'scale'    => 'text',
									'target'   => '.front-page-6 .gs-faq button',
									'builder'  => 'GP_Pro_Builder::px_css',
									'selector' => 'font-size',
								),
								'front-page-6-faq-button-align' => array(
									'label'    => __( 'Text Alignment', 'gppro' ),
									'input'    => 'text-align',
									'target'   => '.front-page-6 .gs-faq button',
									'builder'  => 'GP_Pro_Builder::text_css',
									'selector' => 'text-align',
								),
								'front-page-6-faq-button-style' => array(
									'label'    => __( 'Font Style', 'gppro' ),
									'input'    => 'radio',
									'options'  => array(
										array(
											'label' => __( 'Normal', 'gppro' ),
											'value' => 'normal',
										),
										array(
											'label' => __( 'Italic', 'gppro' ),
											'value' => 'italic',
										),
									),
									'target'   => '.front-page-6 .gs-faq button',
									'builder'  => 'GP_Pro_Builder::text_css',
									'selector' => 'font-style',
								),
							),
						),
						// add front page 6 faq answer
						'front-page-6-faq-answer-button-setup' => array(
							'title' => __( 'FAQ Answer', 'gppro' ),
							'data'  => array(
								'front-page-6-faq-answer-back' => array(
									'label'    => __( 'Background', 'gppro' ),
									'input'    => 'color',
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::hexcolor_css',
									'selector' => 'background',
								),
								'front-page-6-faq-answer-bottom-left-border-radius' => array(
									'label'    => __( 'Bottom Left Corner Radius', 'gppro' ),
									'input'    => 'spacing',
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::px_css',
									'selector' => 'border-bottom-left-radius',
									'min'      => '0',
									'max'      => '80',
									'step'     => '1',
								),
								'front-page-6-faq-answer-bottom-right-border-radius' => array(
									'label'    => __( 'Bottom Right Corner Radius', 'gppro' ),
									'input'    => 'spacing',
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::px_css',
									'selector' => 'border-bottom-right-radius',
									'min'      => '0',
									'max'      => '80',
									'step'     => '1',
								),
								'front-page-6-faq-answer-color' => array(
									'label'    => __( 'Color', 'gppro' ),
									'input'    => 'color',
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::hexcolor_css',
									'selector' => 'color',
								),
								'front-page-6-faq-answer-stack' => array(
									'label'    => __( 'Font Stack', 'gppro' ),
									'input'    => 'font-stack',
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::stack_css',
									'selector' => 'font-family',
								),
								'front-page-6-faq-answer-size'  => array(
									'label'    => __( 'Font Size', 'gppro' ),
									'input'    => 'font-size',
									'scale'    => 'text',
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::px_css',
									'selector' => 'font-size',
								),
								'front-page-6-faq-answer-align' => array(
									'label'    => __( 'Text Alignment', 'gppro' ),
									'input'    => 'text-align',
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::text_css',
									'selector' => 'text-align',
								),
								'front-page-6-faq-answer-style' => array(
									'label'    => __( 'Font Style', 'gppro' ),
									'input'    => 'radio',
									'options'  => array(
										array(
											'label' => __( 'Normal', 'gppro' ),
											'value' => 'normal',
										),
										array(
											'label' => __( 'Italic', 'gppro' ),
											'value' => 'italic',
										),
									),
									'target'   => '.front-page-6 .gs-faq .gs-faq__answer',
									'builder'  => 'GP_Pro_Builder::text_css',
									'selector' => 'font-style',
								),
							),
						),
					)
				);
			}
			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the post extras area
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function content_extras( $sections ) {

			// reset the specificity of the read more link
			$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']     = '.content > .post .entry-content a.more-link';
			$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target'] = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

			// remove pagination border radius
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'extras-pagination-numeric-backs', 'extras-pagination-numeric-border-radius' );

			// add breadcrumb background color
			$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
				'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
				array(
					'extras-breadcrumb-back' => array(
						'label'    => __( 'Background color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.breadcrumb',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
				)
			);

			// add background for text pagination
			$sections['extras-pagination-text-setup']['data'] = GP_Pro_Helper::array_insert_before(
				'extras-pagination-text-link', $sections['extras-pagination-text-setup']['data'],
				array(
					'extras-pagination-back'     => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.archive-pagination a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'extras-pagination-back-hov' => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.archive-pagination a:hover', '.archive-pagination a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
				)
			);

			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the after entry widget
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function after_entry( $sections ) {

			// remove after entry widget border radius
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'after-entry-widget-back-setup', 'after-entry-widget-area-border-radius' );

			// remove single widget settings
			$sections = GP_Pro_Helper::remove_settings_from_section( $sections, 'after-entry-single-widget-setup' );

			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the comments area
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function comments_area( $sections ) {
			// remove comment allowed tags
			$sections = GP_Pro_Helper::remove_settings_from_section(
				$sections, array(
					'section-break-comment-reply-atags-setup',
					'comment-reply-atags-area-setup',
					'comment-reply-atags-base-setup',
					'comment-reply-atags-code-setup',
				)
			);
			// remove comment layout (standard and author)
			$sections = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'single-comment-standard-setup', 'single-comment-author-setup' ) );

			// remove input field border-radius
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'comment-reply-fields-input-layout-setup', 'comment-reply-fields-input-border-radius' );

			// remove submit button border-radius
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'comment-submit-button-spacing-setup', 'comment-submit-button-border-radius' );

			$sections['comment-element-name-setup']['data']['comment-element-name-size']['target'] = '.comment-author-link';

			// Add text-decoration to single comment area
			$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'comment-element-name-link-hov', $sections['comment-element-name-setup']['data'],
				array(
					'comment-element-name-text-decoration' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => '.comment-author a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'comment-element-name-decoration-hov'  => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.comment-author a:hover', '.comment-author a:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

			// Add text-decoration to comment date
			$sections['comment-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'comment-element-date-link-hov', $sections['comment-element-date-setup']['data'],
				array(
					'comment-element-date-text-decoration' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.comment-meta', '.comment-meta a' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'comment-element-date-decoration-hov'  => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.comment-meta a:hover', '.comment-meta a:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

			// Add text-decoration to comment reply
			$sections['comment-element-reply-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'comment-element-reply-link-hov', $sections['comment-element-reply-setup']['data'],
				array(
					'comment-element-reply-text-decoration' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => 'a.comment-reply-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'comment-element-reply-decoration-hov' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

			// Add text-decoration to comment body
			$sections['comment-element-body-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'comment-element-body-link-hov', $sections['comment-element-body-setup']['data'],
				array(
					'comment-element-body-text-decoration' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => '.comment-content a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'comment-element-body-decoration-hov'  => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.comment-content a:hover', '.comment-content a:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
				)
			);

			// add comment reply text appearance
			$sections['comment-element-reply-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'comment-element-reply-align', $sections['comment-element-reply-setup']['data'],
				array(
					'comment-element-reply-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => 'a.comment-reply-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
				)
			);
			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the main sidebar area
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function main_sidebar( $sections ) {

			// remove border-radius
			$sections = GP_Pro_Helper::remove_items_from_settings( $sections, 'sidebar-widget-back-setup', 'sidebar-widget-border-radius' );

			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the footer widget section
		 *
		 * @return array $sections
		 */
		public function footer_widgets() {
			$sections = array(
				// ** footer cta.
				'section-break-footer-cta'       => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Footer CTA', 'gppro' ),
						'text'  => __( 'This area uses a text widget with an HTML button.', 'gppro' ),
					),
				),

				// add footer cta separator.
				'footer-cta-separator-setup'     => array(
					'title' => __( 'Separator', 'gppro' ),
					'data'  => array(
						'footer-cta-separator-color' => array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta::before',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-cta-separator-style' => array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.footer-cta::before',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-cta-separator-width' => array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-cta::before',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),

				// add footer cta title
				'footer-cta-title-setup'         => array(
					'title' => __( 'Widget Title', 'gppro' ),
					'data'  => array(
						'footer-cta-title-color'         => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'footer-cta-title-stack'         => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'footer-cta-title-size'          => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'footer-cta-title-size-mobile'   => array(
							'label'       => __( 'Font Size', 'gppro' ),
							'sub'         => __( 'Mobile', 'gppro' ),
							'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
							'input'       => 'font-size',
							'scale'       => 'text',
							'target'      => '.footer-cta .widget-title',
							'media_query' => '@media only screen and (max-width: 800px)',
							'builder'     => 'GP_Pro_Builder::px_css',
							'selector'    => 'font-size',
						),
						'footer-cta-title-weight'        => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'footer-cta-title-transform'     => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'footer-cta-title-align'         => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'footer-cta-title-style'         => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),

						'footer-cta-title-margin-bottom' => array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-cta .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '33',
							'step'     => '1',
						),
					),
				),

				// add footer cta text
				'footer-cta-text-setup'          => array(
					'title' => __( 'Widget Content', 'gppro' ),
					'data'  => array(
						'footer-cta-text-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta .widget',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'footer-cta-text-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.footer-cta .widget',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'footer-cta-text-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.footer-cta .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'footer-cta-text-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.footer-cta .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'footer-cta-text-style' => array(
							'label'    => __( 'Font Style', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.footer-cta .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// add footer cta button
				'footer-cta-btn-setup'           => array(
					'title' => __( 'Button', 'gppro' ),
					'data'  => array(
						'footer-cta-btn-back'        => array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'footer-cta-btn-back-hov'    => array(
							'label'        => __( 'Background', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.footer-cta .button:hover:not(.text)',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'background-color',
							'always_write' => true,
						),
						'footer-cta-btn-color'       => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'footer-cta-btn-color-hover' => array(
							'label'        => __( 'Font Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.footer-cta .button:hover:not(.text)',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
						'footer-cta-type-divider'    => array(
							'title' => __( 'Typography', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'footer-cta-btn-stack'       => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.footer-cta .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'footer-cta-btn-size'        => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.footer-cta .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'footer-cta-btn-weight'      => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.footer-cta .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'footer-cta-btn-transform'   => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.footer-cta .button:not(.text)',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
					),
				),

				// add foter cta button text
				'footer-cta-btn-text-setup'      => array(
					'title' => __( 'Button Text Only', 'gppro' ),
					'data'  => array(
						'footer-cta-btn-text-color'       => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta .button.text',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'footer-cta-btn-text-color-hover' => array(
							'label'        => __( 'Font Color', 'gppro' ),
							'sub'          => __( 'Hover', 'gppro' ),
							'input'        => 'color',
							'target'       => '.footer-cta .button.text:hover',
							'builder'      => 'GP_Pro_Builder::hexcolor_css',
							'selector'     => 'color',
							'always_write' => true,
						),
						'footer-cta-btn-text-border-bottom' => array(
							'label'    => __( 'Underline Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta .button.text',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'border-bottom-color',
						),
						'footer-cta-type-divider'         => array(
							'title' => __( 'Typography', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'footer-cta-btn-text-stack'       => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.footer-cta .button.text',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'footer-cta-btn-text-size'        => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.footer-cta .button.text',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'footer-cta-btn-text-weight'      => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.footer-cta .button.text',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
						),
						'footer-cta-btn-text-transform'   => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.footer-cta .button.text',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
					),
				),

				// add footer cta separator.
				'footer-cta-bottom-border-setup' => array(
					'title' => __( 'Bottom Border', 'gppro' ),
					'data'  => array(
						'footer-cta-bottom-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-cta .wrap',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-cta-bottom-border-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.footer-cta .wrap',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-cta-bottom-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-cta .wrap',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),
			);

			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the main footer section
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function footer_main( $sections ) {

			// add text decoration
			$sections['footer-main-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'footer-main-content-link-hov', $sections['footer-main-content-setup']['data'],
				array(
					'footer-main-content-lk-txt-dec'     => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.site-footer a', '.site-footer p a' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'footer-main-content-lk-txt-dec-hov' => array(
						'label'    => __( 'Link Style', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.site-footer a', '.site-footer p a' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
					),
					'footer-main-content-typography-divider' => array(
						'title' => __( 'Typography', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
				)
			);

			// return the section array
			return $sections;
		}

		/**
		 * add and filter options in the Genesis Widgets - eNews
		 *
		 * @param  array $sections The sections.
		 * @return array $sections
		 */
		public function genesis_widgets_section( $sections ) {

			// bail without the enews add on
			if ( empty( $sections['genesis_widgets'] ) ) {
				return $sections;
			}

			// remove widget background color
			unset( $sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-back'] );

			// remove field box shadow
			unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow'] );

			// add widget title settings
			$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_before(
				'enews-widget-typography', $sections['genesis_widgets']['enews-widget-general']['data'],
				array(
					'enews-title-typography'             => array(
						'title' => __( 'Widget Title Typography', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'enews-title-gen-stack'              => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.enews-widget .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'enews-title-gen-size'               => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.enews-widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'enews-title-gen-weight'             => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.enews-widget .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'enews-title-gen-transform'          => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.enews-widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'enews-title-gen-text-margin-bottom' => array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.enews-widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '48',
						'step'     => '1',
					),
				)
			);

			// return the section build
			return $sections;
		}

		/**
		 * checks the settings to see if dropdown background color
		 * has been changed and if so, adds the value to the CSS
		 * triangles so they match
		 *
		 * @param  string $setup The current CSS
		 * @param  array  $data  The settings data used to build the CSS.
		 * @param  string $class CSS class for the setting.
		 * @return string
		 */
		public function css_builder_filters( $setup, $data, $class ) {

			// check for change in primary navigation back
			if ( ! empty( $data['primary-nav-area-back'] ) ) {

				// the actual CSS entry
				$setup .= '@media only screen and (max-width: 1023px) { ';
				$setup .= $class . ' .nav-primary .genesis-nav-menu .menu-item a, ' . $class . ' .sub-menu-toggle, ' . $class . ' .js nav button:hover { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'background-color', $data['primary-nav-area-back'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// check for change in primary navigation back
			if ( ! empty( $data['primary-nav-drop-item-base-back'] ) || ! empty( $data['primary-nav-drop-item-base-back'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .nav-primary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-primary .genesis-nav-menu .sub-menu:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['primary-nav-drop-item-base-back'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// check for change in front-page 1 button background
			if ( ! empty( $data['front-page-1-btn-back'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-1 .button:before, ' . $class . ' .front-page-1 .button:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-1-btn-back'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-1-btn-back'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// check for change in button background hover
			if ( ! empty( $data['front-page-1-btn-back-hov'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-1 .button:hover:before, ' . $class . ' .front-page-1 .button:hover:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-1-btn-back-hov'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-1-btn-back-hov'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// front page 3 button
			// check for change in front-page 3 button background
			if ( ! empty( $data['front-page-3-btn-back'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-3 .button:before, ' . $class . ' .front-page-3 .button:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-3-btn-back'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-3-btn-back'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// check for change in button background hover
			if ( ! empty( $data['front-page-3-btn-back-hov'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-3 .button:hover:before, ' . $class . ' .front-page-3 .button:hover:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-3-btn-back-hov'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-3-btn-back-hov'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// front page 4 button
			// check for change in front-page 4 button background
			if ( ! empty( $data['front-page-4-btn-back'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-4 .button:before, ' . $class . ' .front-page-4 .button:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-4-btn-back'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-4-btn-back'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// check for change in button background hover
			if ( ! empty( $data['front-page-4-btn-back-hov'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-4 .button:hover:before, ' . $class . ' .front-page-4 .button:hover:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-4-btn-back-hov'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-4-btn-back-hov'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// front page 5 button
			// check for change in front-page 5 button background
			if ( ! empty( $data['front-page-5-btn-back'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-5 .button:before, ' . $class . ' .front-page-5 .button:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-5-btn-back'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-5-btn-back'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// check for change in button background hover
			if ( ! empty( $data['front-page-5-btn-back-hov'] ) ) {

				// the actual CSS entry
				$setup .= $class . ' .front-page-5 .button:hover:before, ' . $class . ' .front-page-5 .button:hover:after { ';
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['front-page-5-btn-back-hov'] ) . "\n";
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['front-page-5-btn-back-hov'] ) . "\n";
				$setup .= '}' . "\n";
			}

			// return the setup array
			return $setup;
		}

	} // end class GP_Pro_Academy_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Academy_Pro = GP_Pro_Academy_Pro::getInstance();
