<?php
/**
 * Genesis Design Palette Pro - Admin Design Module
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
 * Admin Design Class.
 *
 * Contains all generic admin functionality.
 */
class Design extends Base {

	/**
	 * Sets the priority for the filter.
	 *
	 * @var int
	 */
	public $priority = 0;

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
			'default' => array(
				'title'    => '',
				'callback' => array( $this, 'admin_page' ),
			),
		);
		$this->tabs     = array(
			'default' => array(
				'label'  => __( 'Design', 'gppro' ),
				'single' => true,
				'form'   => false,
			),
		);
	}

	/**
	 * Handle our checks then call our hooks.
	 *
	 * @return void
	 */
	public function init() {

		// Bail on non admin.
		if ( ! is_admin() ) {
			return;
		}

		// First make sure we have our main class. not sure how we wouldn't but then again...
		if ( ! class_exists( 'Genesis_Palette_Pro' ) ) {
			return;
		}

		// Call the functions.
		add_action( 'load-genesis_page_genesis-palette-pro', array( $this, 'remove_child_prompt' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'plugin_action_links', array( $this, 'quick_link' ), 10, 2 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ) );
		add_filter( 'upload_mimes', array( $this, 'favicon_mime_type' ) );
		add_filter( 'wp_auth_check_load', array( $this, 'remove_user_auth' ), 10, 2 );
		add_filter( 'gppro_admin_title', array( $this, 'admin_title' ) );
	}

	/**
	 * Remove the warning prompt about using a child theme.
	 *
	 * @return void
	 */
	public function remove_child_prompt() {

		// Check for our current DPP screen and if on our page, remove the prompt.
		if ( false !== \GP_Pro_Utilities::check_current_dpp_screen() ) {
			remove_action( 'admin_notices', 'genesis_use_child_theme_notice' );
		}
	}

	/**
	 * Swap admin title tag if using a child theme.
	 *
	 * @param  string $title  The existing title being shown.
	 *
	 * @return string $title  The modified title being shown.
	 */
	public function admin_title( $title ) {

		// Do my child theme check. if fail, return the title.
		$data = \GP_Pro_Helper::is_child_theme();
		if ( false === $data ) {
			return $title;
		}

		// Return the name if we have it, or the fallback.
		// Translators: placeholder os for the child theme name.
		return ! empty( $data['name'] ) ? sprintf( __( 'Genesis Design Palette Pro - %s', 'gppro' ), esc_attr( $data['name'] ) ) : $title;
	}

	/**
	 * Add our "settings" links to the plugins page.
	 *
	 * @param  array  $links  The existing array of links.
	 * @param  string $file   The file we are actually loading from.
	 *
	 * @return array  $links  The updated array of links.
	 */
	public function quick_link( $links, $file ) {

		// Check to make sure we are on the correct plugin.
		if ( ! empty( $file ) && GPP_BASE === $file ) {

			// Get the link based on license status.
			$quick = self::quick_link_url();

			// Add the link to the array.
			array_push( $links, $quick );
		}

		// Return the links.
		return $links;
	}

	/**
	 * Return the general link or the license directed if missing.
	 *
	 * @return mixed  The markup and URL to display.
	 */
	public static function quick_link_url() {

		// Make our base URL.
		$link = \GP_Pro_Helper::get_settings_url();

		// Set our base URL, which is just the settings link.
		$base = '<a href="' . esc_url( $link ) . '">' . __( 'Settings', 'gppro' ) . '</a>';

		// Do our local check first to avoid wasting any time.
		if ( false !== \GP_Pro_Utilities::check_local_dev() ) {
			return $base;
		}

		// Bail right away if there is no license management to be done.
		if ( false !== apply_filters( 'gppro_disable_license_management', false ) ) {
			return $base;
		}

		// Run the active license check.
		$check = \Genesis_Palette_Pro::license_data( 'status' );

		// Return the link based on license status.
		switch ( $check ) {

			// Local dev, which we won't require a license.
			case 'local':
				return $base;

			// Active license.
			case 'valid':
				return $base;

			// Expired license, return renewal link.
			case 'expired':
				return '<a style="font-weight:700;color:#ee7600;" target="_blank" href="' . License::get_renewal_link() . '">' . __( 'Renew License Key', 'gppro' ) . '</a>';

			// Default for anything else.
			default:
				return '<a style="font-weight:700;color:#ee7600;" href="' . License::get_license_page_link() . '">' . __( 'Enter License Key', 'gppro' ) . '</a>';
		}
	}

	/**
	 * Add a custom body class on the admin page for easier JS targeting.
	 *
	 * @param  string $classes  All the existing classes in a single string.
	 *
	 * @return string $classes  The updated string with ours.
	 */
	public function admin_body_class( $classes ) {

		// Check for our current DPP screen and if on our page, add our class.
		if ( false !== \GP_Pro_Utilities::check_current_dpp_screen() ) {
			$classes .= ' gppro-admin-page';
		}

		// Return classes.
		return $classes;
	}

	/**
	 * Allow .ico and .gif files to be uploaded in the native WP media manager.
	 *
	 * @param  array $mimes  The currently allowed MIME types.
	 *
	 * @return array $mimes  The updated array of allowed MIME types.
	 */
	public function favicon_mime_type( $mimes ) {

		// Check for gif support and add it if missing.
		if ( ! isset( $mimes['gif'] ) ) {
			$mimes['gif'] = 'image/gif';
		}

		// Check for ico support and add it if missing.
		if ( ! isset( $mimes['ico'] ) ) {
			$mimes['ico'] = 'image/x-icon';
		}

		// Send back array of MIME types.
		return $mimes;
	}

	/**
	 * Remove the constant user auth check on the main DPP screen to prevent issues with iframe loading.
	 *
	 * @param  bool      $show    Whether to load the authentication check.
	 * @param  WP_Screen $screen  The current screen object.
	 *
	 * @return bool      $show    Whether to load the authentication check.
	 */
	public function remove_user_auth( $show, $screen ) {
		return is_object( $screen ) && 'genesis_page_genesis-palette-pro' === $screen->base ? false : $show;
	}

	/**
	 * Load our admin side CSS.
	 *
	 * @return void
	 */
	public function admin_styles() {

		// Check for our current DPP screen.
		if ( false === \GP_Pro_Utilities::check_current_dpp_screen() ) {
			return;
		}

		// Load CSS that is not affected by script_debug.
		wp_enqueue_style( 'wp-color-picker' );

		// Get our file variables.
		$vars = \GP_Pro_Utilities::get_filename_vars();

		// Load files in either minified or non-minified versions as requested.
		wp_enqueue_style( 'gppro-admin', plugins_url( 'lib/css/admin' . $vars['style'], GPP_FILE ), array(), $vars['vers'], 'all' );

		// Allow other themes / plugins to piggyback on our call.
		do_action( 'gppro_load_admin_styles', $vars );
	}

	/**
	 * Load our admin side JS.
	 *
	 * @return void
	 */
	public function admin_scripts() {

		// Check for our current DPP screen.
		if ( false === \GP_Pro_Utilities::check_current_dpp_screen() ) {
			return;
		}

		if ( isset( $_GET['current-tab'] ) && 'genesis-palette-pro-default' !== $_GET['current-tab'] ) { // WPCS: csrf ok.
			return;
		}

		// Get our file variables.
		$vars = \GP_Pro_Utilities::get_filename_vars();

		// Load media pieces for uploaders.
		wp_enqueue_media();

		// Load our fullscreen JS library.
		wp_enqueue_script( 'screenfull', plugins_url( 'lib/js/ext/screenfull' . $vars['script'], GPP_FILE ), array( 'jquery' ), '2.0.0', true );

		// Load and localize the color picker.
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha', plugins_url( 'lib/js/ext/wp-color-picker-alpha' . $vars['script'], GPP_FILE ), array( 'wp-color-picker' ), '2.0', true );

		// Only load the preview JS if we have a preview.
		if ( false === apply_filters( 'gppro_disable_preview_pane', false ) ) {
			wp_enqueue_script( 'gppro-preview', plugins_url( 'lib/js/preview' . $vars['script'], GPP_FILE ), array( 'jquery' ), $vars['vers'], true );
		}

		// Load and localize our admin file.
		wp_enqueue_script( 'gppro-admin', plugins_url( 'lib/js/admin' . $vars['script'], GPP_FILE ), array( 'wp-color-picker', 'jquery-ui-core', 'jquery-ui-slider', 'jquery-ui-tooltip', 'jquery' ), $vars['vers'], true );
		wp_localize_script(
			'gppro-admin', 'adminData', array(
				'colorchoice'    => apply_filters( 'gppro_picker_defaults', true ),
				'gfontlink'      => '//fonts.googleapis.com/css?family=',
				'clearconfirm'   => __( 'Are you sure you want to delete all the settings?', 'gppro' ),
				'errormessage'   => __( 'There was an error parsing your data.', 'gppro' ),
				'supporterror'   => __( 'Please see the areas in red.', 'gppro' ),
				'uploadtitle'    => __( 'Upload Your Header Image', 'gppro' ),
				'favicontitle'   => __( 'Upload Your Favicon file', 'gppro' ),
				'uploadbutton'   => __( 'Attach', 'gppro' ),
				'tooltip_my'     => apply_filters( 'gppro_tooltip_pos_my', 'left+15 center' ),
				'tooltip_at'     => apply_filters( 'gppro_tooltip_pos_at', 'right center' ),
				'base_font_size' => \GP_Pro_Helper::base_font_size(),
				'use_rems'       => \GP_Pro_Helper::rems_enabled(),
				'basepreview'    => is_ssl() ? home_url( '/', 'https' ) : home_url( '/', 'http' ),
				'perhapsSerial'  => \GP_Pro_Helper::maybe_serialize_vars(),
			)
		);

		// Turn off heartbeat.
		wp_deregister_script( 'heartbeat' );

		// Allow other themes / plugins to piggyback on our call.
		do_action( 'gppro_load_admin_scripts', $vars );
	}

	/**
	 * Add attribution link to settings page.
	 *
	 * @param  string $text  The existing footer text.
	 *
	 * @return string $text  The modified footer text.
	 */
	public function admin_footer( $text ) {

		// Check for our current DPP screen.
		if ( false === \GP_Pro_Utilities::check_current_dpp_screen() ) {
			return $text;
		}

		// Set our footer link with GA campaign tracker.
		$link = 'https://reaktivstudios.com/?utm_source=plugin&utm_medium=link&utm_campaign=dpp';

		// Build footer markup.
		// Translators: $1%s = ReaktivStudios URL. %2$s = ReaktivStudios title tag.
		$text = sprintf( __( '<span id="footer-thankyou">This plugin brought to you by the fine folks at <a href="%1$s" title="%2$s" target="_blank">Reaktiv Studios</a></span>', 'gppro' ), esc_url( $link ), esc_html( 'Reaktiv Studios', 'gppro' ) );

		// Return the text, fitered.
		return apply_filters( 'gppro_admin_footer_text', $text );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'gppro-settings', 'gppro-settings' );
	}

	/**
	 * Load our settings page.
	 *
	 * @return mixed  The settings page.
	 */
	public function admin_page() {

		// Bail without our setup class.
		if ( ! class_exists( 'GP_Pro_Setup' ) ) {
			return;
		}

		// Fetch our blocks abd bail without them.
		$blocks = \GP_Pro_Setup::blocks();
		if ( false === $blocks ) {
			return;
		}

		// Start the wrapper itself.
		echo '<div class="wrap gppro-wrap">';

			echo '<div class="gppro-settings-wrapper">';

				echo '<div class="gppro-block gppro-actions gppro-actions-top">';
					echo \GP_Pro_Setup::buttons(); // WPCS: xss ok.
				echo '</div>';

				echo '<div class="gppro-block gppro-options">';

					echo '<div class="gppro-tabs gppro-column">';
						echo \GP_Pro_Setup::tabs( $blocks ); // WPCS: xss ok.
					echo '</div>';

					echo '<div class="gppro-sections gppro-column">';
						echo \GP_Pro_Sections::sections( $blocks ); // WPCS: xss ok.
					echo '</div>';

				echo '</div>';

				echo '<div class="gppro-block gppro-actions gppro-actions-bottom">';
					echo \GP_Pro_Setup::buttons(); // WPCS: xss ok.
				echo '</div>';

			echo '</div>';

			echo \GP_Pro_Setup::preview_block(); // WPCS: xss ok.

		echo '</div>';
	}

	// End class.
}
