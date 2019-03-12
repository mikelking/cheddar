<?php
/**
 * Genesis Design Palette Pro - Front Module
 *
 * Contains functionality related to the front end of the site
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

if ( ! class_exists( 'GP_Pro_Front' ) ) {

	/**
	 * Start up the engine.
	 */
	class GP_Pro_Front {

		/**
		 * Handle our check.
		 */
		public function init() {

			// Bail on admin.
			if ( is_admin() ) {
				return;
			}

			// First make sure we have our main class. not sure how we wouldn't but then again.
			if ( ! class_exists( 'Genesis_Palette_Pro' ) ) {
				return;
			}

			// Load the functions.
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 9999 );
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_item' ), 99 );
			add_filter( 'body_class', array( $this, 'body_class' ) );
		}

		/**
		 * Call our front-end and preview CSS and JS assets.
		 *
		 * @return void
		 */
		public function front_scripts() {

			// Bail if customizer active and this is a preview.
			if ( get_option( 'gppro-customizer-beta' ) && is_customize_preview() ) {
				return;
			}

			// Bail if we aren't running Genesis.
			if ( false === Genesis_Palette_Pro::check_active() ) {
				return;
			}

			// Add the ability to bypass the loading completely.
			if ( false === apply_filters( 'gppro_enable_style_load', true ) ) {
				return;
			}

			// Bail if we have no settings to load.
			if ( false === GP_Pro_Helper::get_single_option( 'gppro-settings', '', false ) ) {
				return;
			}

			// Allow for the forcing of CSS load inside the <head> via filter.
			if ( false !== apply_filters( 'gppro_force_manual_css_load', false ) ) {

				// Add the manual load.
				add_action( 'wp_head', array( $this, 'front_style_head' ), 999 );

				// And bail.
				return;
			}

			// Allow for the forcing of CSS load inside the <head> via query string.
			if ( ! empty( $_GET['gppro-force-css'] ) ) { // WPCS: csrf ok.

				// Add the manual load.
				add_action( 'wp_head', array( $this, 'front_style_head' ), 999 );

				// And bail.
				return;
			}

			$file_key    = get_theme_mod( 'dpp_file_key' );
			$url_base    = trailingslashit( get_site_url() );
			$permastruct = get_option( 'permalink_structure' );
			$url_base    = false === strpos( $permastruct, 'index.php' ) ? $url_base : trailingslashit( $url_base . 'index.php' );

			$url = empty( $permastruct ) ? add_query_arg( 'dpp-custom-styles', $file_key, $url_base ) : sprintf( '%1$sdpp-custom-styles-%2$s', $url_base, $file_key );

			// Make protocol-relative URL (optional).
			$url = false === apply_filters( 'gppro_enable_relative_url', true ) ? $url : preg_replace( '#^https?://#', '//', $url );

			// All checks passed, show file.
			wp_enqueue_style( 'gppro-style', esc_url( $url ), array(), '', 'all' );
		}

		/**
		 * Add admin bar item.
		 *
		 * @param WP_Admin_Bar $wp_admin_bar The current admin bar.
		 */
		public function admin_bar_item( WP_Admin_Bar $wp_admin_bar ) {

			// Run against our current user capability filter.
			if ( ! current_user_can( apply_filters( 'gppro_caps', 'manage_options' ) ) ) {
				return;
			}

			// Now add the admin bar link.
			$wp_admin_bar->add_menu(
				array(
					'parent' => 'appearance',
					'id'     => 'design-palette',
					'title'  => __( 'Design Palette Pro', 'gppro' ),
					'href'   => admin_url( 'admin.php?page=genesis-palette-pro' ),
					'meta'   => array(
						'title' => __( 'Design Palette Pro', 'gppro' ),
					),
				)
			);
		}

		/**
		 * Load the CSS manually in the head if the file isn't readable.
		 */
		public function front_style_head() {

			// Bail without the class.
			if ( ! class_exists( 'GP_Pro_Builder' ) ) {
				return;
			}

			// Build the CSS and bail if missing.
			$css = GP_Pro_Builder::build_css();
			if ( false === $css ) {
				return;
			}

			// Echo the CSS.
			echo '<style media="all" type="text/css">' . $css . '</style>' . "\n"; // WPCS: xss ok.

		}

		/**
		 * Set custom body class to apply styles.
		 *
		 * @param  array $classes The current body classes.
		 *
		 * @return array $classes The possibly modified body classes.
		 */
		public function body_class( $classes ) {

			// Bail if we aren't running Genesis.
			if ( false === Genesis_Palette_Pro::check_active() ) {
				return $classes;
			}

			// Check our other filter for custom one not tied to the builder.
			$alt_class = apply_filters( 'gppro_alt_body_class', false );

			// Add any additional classes.
			if ( ! empty( $alt_class ) ) {
				$classes[] = esc_attr( $alt_class );
			}

			// Bail if we have no settings to load.
			if ( false === GP_Pro_Helper::get_single_option( 'gppro-settings', '', false ) ) {
				return $classes;
			}

			// Check our filter, then throw the custom class on there.
			$custom    = apply_filters( 'gppro_body_class', 'gppro-custom' );
			$classes[] = esc_attr( $custom );

			// Return the classes.
			return $classes;
		}

		// End class.
	}

	// End exists check.
}

// Instantiate our class.
$GP_Pro_Front = new GP_Pro_Front();
$GP_Pro_Front->init();
