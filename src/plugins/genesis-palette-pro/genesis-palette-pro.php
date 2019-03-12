<?php
/**
 * Plugin Name: Genesis Design Palette Pro
 * Plugin URI: https://genesisdesignpro.com
 * Description: Quick and easy code-free customizations for your Genesis powered site.
 * Author: Reaktiv Studios
 * Author URI: https://reaktivstudios.com
 * Version: 1.6
 * Text Domain: gppro
 * Domain Path: languages
 *
 * Copyright 2018 Reaktiv Studios
 *
 * Design Palette Pro is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Design Palette Pro is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Design Palette Pro. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Design Palette Pro
 */

// Define our file location.
if ( ! defined( 'GPP_FILE' ) ) {
	define( 'GPP_FILE', __FILE__ );
}

// Define our file base.
if ( ! defined( 'GPP_BASE' ) ) {
	define( 'GPP_BASE', plugin_basename( GPP_FILE ) );
}

if ( ! defined( 'GPP_DIR' ) ) {
	define( 'GPP_DIR', plugin_dir_path( GPP_FILE ) );
}

// Define our version.
if ( ! defined( 'GPP_VER' ) ) {
	define( 'GPP_VER', '1.6' );
}

// Set the EDD store URL.
if ( ! defined( 'GPP_STORE_URL' ) ) {
	define( 'GPP_STORE_URL', 'https://genesisdesignpro.com' );
}

// Set our custom HelpScount API endpoint.
if ( ! defined( 'GPP_HELP_API' ) ) {
	define( 'GPP_HELP_API', 'https://reaktivhelp.com' );
}

// Set our HelpScount mailbox ID.
if ( ! defined( 'GPP_HELP_BOX_ID' ) ) {
	define( 'GPP_HELP_BOX_ID', 7292 );
}

// Set our EDD item name.
if ( ! defined( 'GPP_ITEM_NAME' ) ) {
	define( 'GPP_ITEM_NAME', 'Design Palette Pro' );
}

// Load Autoloader before anything else.
require_once GPP_DIR . 'lib/classes/class-autoload.php';

// Set our EDD updater class.
if ( ! class_exists( 'RKV_SL_Plugin_Updater' ) ) {
	include 'lib/tools/EDD_SL_Plugin_Updater.php';
}


/**
 * Load up our main class.
 *
 * @todo This is still way too big. Needs to be broken up more.
 * @todo Move this to the classes folder.
 */
class Genesis_Palette_Pro {

	/**
	 * Static property to hold our singleton instance
	 *
	 * @var Genesis_Palette_Pro
	 *
	 * @since 1.0
	 */
	static public $instance = false;

	/**
	 * Hold default styles (use get_defaults to access them)
	 *
	 * @var Genesis_Palette_Pro
	 *
	 * @since 1.0
	 */
	private $defaults;

	/**
	 * This is our constructor, which is private to force the use of
	 * getInstance() to make this a Singleton
	 *
	 * @return Genesis_Palette_Pro
	 *
	 * @since 1.0
	 */
	private function __construct() {
		// Load Upgrades.
		require_once GPP_DIR . 'lib/upgrade.php';

		// Load Custom Styles.
		add_filter( 'do_parse_request', array( 'DPP\Style\Loader', 'maybe_load_style' ) );

		add_action( 'plugins_loaded', array( $this, 'textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'disable_addons' ), 1 );
		add_action( 'plugins_loaded', array( $this, 'load_themes' ) );

		// genesis specific.
		add_action( 'genesis_init', array( $this, 'load_admin' ), 20 );

		// activation hooks.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactive_clear' ) );

		// FILTERCEPTION.
		add_action( 'after_setup_theme', array( $this, 'enable_custom_header' ) );
		add_action( 'after_setup_theme', array( $this, 'link_decorations' ) );
		add_action( 'gppro_after_create', array( $this, 'clear_caching_plugins' ) );
		add_action( 'gppro_after_clear', array( $this, 'clear_caching_plugins' ) );
		add_filter( 'gppro_font_stacks', array( $this, 'lato_native_font' ) );
		add_filter( 'gppro_webfont_stacks', array( $this, 'lato_webfont' ) );
		add_filter( 'gppro_section_inline_header_area', array( $this, 'header_item_check' ), 99, 2 );
		add_filter( 'gppro_section_inline_content_extras', array( $this, 'pagination_check' ), 99, 2 );
		add_filter( 'gppro_section_inline_comments_area', array( $this, 'jetpack_comments' ), 99, 2 );
		add_filter( 'gppro_set_defaults', array( $this, 'genesis_defaults' ), 99, 2 );

		// Remove the items tied to the preview window.
		add_filter( 'gppro_section_inline_build_settings', array( $this, 'remove_preview_settings' ), 99, 2 );
		add_filter( 'gppro_preview_pane', array( $this, 'remove_preview_pane' ) );

		// EDD items.
		add_action( 'admin_init', array( $this, 'edd_core_update' ) );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return Genesis_Palette_Pro
	 * phpcs:disable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
	 */
	public static function getInstance() {
		// phpcs:enable WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

		// check for self instance.
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		// return the instance.
		return self::$instance;
	}

	/**
	 * Loads the plugin language files
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function textdomain() {

		// Set filter for plugin's languages directory.
		$gppro_lang_dir = dirname( plugin_basename( GPP_FILE ) ) . '/languages/';
		$gppro_lang_dir = apply_filters( 'gppro_languages_directory', $gppro_lang_dir );

		// Traditional WordPress plugin locale filter.
		$locale = apply_filters( 'plugin_locale', get_locale(), 'gppro' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$mofile = sprintf( '%1$s-%2$s.mo', 'gppro', $locale );

		// Setup paths to current locale file.
		$mofile_local  = $gppro_lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/gppro/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/gppro folder.
			load_textdomain( 'gppro', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/genesis-palette-pro/languages/ folder.
			load_textdomain( 'gppro', $mofile_local );
		} else {
			// Load the default language files.
			load_plugin_textdomain( 'gppro', false, $gppro_lang_dir );
		}
	}

	/**
	 * Disable obsolete plugin extensions
	 *
	 * For all versions past 1.2.0, child theme addons are integrated with the core
	 * this deactivates them if they're found.
	 */
	public function disable_addons() {
		// Only disable on versions greater than 1.2.0.
		if ( version_compare( GPP_VER, '1.2.0', '>=' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';

			if ( defined( 'GPMTP_VER' ) ) {
				deactivate_plugins( 'gppro-metro-pro/gppro-metro-pro.php' );
			}
			if ( defined( 'GPMIP_VER' ) ) {
				deactivate_plugins( 'gppro-minimum-pro/gppro-minimum-pro.php' );
			}
			if ( defined( 'GPELV_VER' ) ) {
				deactivate_plugins( 'gppro-eleven40-pro/gppro-eleven40-pro.php' );
			}
		}
	}

	/**
	 * Load Theme-specific functionality
	 *
	 * @return void
	 */
	public function load_themes() {
		require_once GPP_DIR . 'lib/themes.php';
	}

	/**
	 * Reusable check for whether Genesis is active or not
	 *
	 * @return bool genesis template
	 */
	public static function check_active() {
		return function_exists( 'genesis_load_framework' ) ? true : false;
	}

	/**
	 * This function runs on plugin activation. It checks to make sure Genesis
	 * or a Genesis child theme is active. If not, it deactivates itself.
	 *
	 * @since 1.0.0
	 */
	public function activate() {

		// Run the active check.
		$active = self::check_active();

		// Bail if not active (and isn't network admin ).
		if ( ! $active && ! is_network_admin() ) {
			$this->deactivate( '2.0.0', '3.7' );
		}

		// Set the active flag.
		$this->set_active_flag();
	}

	/**
	 * Deactivate plugin.
	 *
	 * This function deactivates the design panel.
	 *
	 * @since 1.0.0
	 * @param  string $genesis_version The Genesis version.
	 * @param  string $wp_version      The WordPress version.
	 * @return mixed  $genesis_version $wp_version
	 */
	public function deactivate( $genesis_version = '2.2.0', $wp_version = '4.0' ) {

		// Deactivate the plugin.
		deactivate_plugins( plugin_basename( __FILE__ ) );

		// Show the message.
		wp_die(
			sprintf(
				// Translators: %1$s is for the WordPress version, %2$s is for the link to the Genesis theme, and %3$s is for the Genesis version.
				esc_html__( 'Sorry, you cannot run Design Palette Pro without WordPress %1$s and %2$sGenesis %3$s%4$s or greater.', 'gppro' ),
				esc_html( $wp_version ),
				sprintf( '<a href="%s">', esc_url( 'https://my.studiopress.com/themes/genesis/' ) ),
				esc_html( $genesis_version ),
				'</a>'
			)
		);
	}

	/**
	 * Clear various warning checks and other settings.
	 *
	 * @return void
	 */
	public function deactive_clear() {
		GP_Pro_Helper::purge_options();
		GP_Pro_Helper::purge_transients();
	}

	/**
	 * Construct preview URL.
	 *
	 * @return string $url
	 */
	public static function preview_url() {

		// Fetch user options.
		$urlcheck = GP_Pro_Helper::get_single_option( 'gppro-user-preview-url', '', false );
		$logcheck = GP_Pro_Helper::get_single_option( 'gppro-user-preview-type', '', false );

		// Check for SSL.
		$scheme = is_ssl() ? 'https' : 'http';

		// Check against fallbacks.
		$baseurl  = ! $urlcheck || empty( $urlcheck ) ? home_url( '/', $scheme ) : GP_Pro_Helper::check_preview_url_scheme( $urlcheck );
		$loggedin = ! $logcheck ? false : true;

		// Set main defaults, pass through filter and return.
		return apply_filters(
			'gppro_preview_url', array(
				'base'     => $baseurl,
				'loggedin' => $loggedin,
			)
		);
	}

	/**
	 * Check for various theme options to enable / disable functionality.
	 *
	 * @param  string  $key      The name of the array key to look for.
	 * @param  string  $name     The name of the setting key itself in the options table.
	 * @param  boolean $default  An optional default value.
	 *
	 * @return mixed             The option value, the default, or false.
	 */
	public static function theme_option_check( $key = '', $name = 'genesis-settings', $default = false ) {

		// Fetch the entire Genesis option array.
		$option = get_option( esc_attr( $name ), $default );

		// Return the requested option or false if setting is not present.
		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}

	/**
	 * Check for various options inside the actual DPP plugin to enable / disable functionality.
	 *
	 * @param  string $key  The name of the option key to look for.
	 *
	 * @return mixed        The option value, the default, or false.
	 */
	public static function plugin_option_check( $key = '' ) {

		// Bail if no key provided.
		if ( empty( $key ) ) {
			return false;
		}

		// Fetch the option based on the key passed.
		$option = get_option( $key );

		// Handle the scheme on the preview URL if requested.
		if ( ! empty( $option ) && 'gppro-user-preview-url' === $key ) {
			$option = GP_Pro_Helper::check_preview_url_scheme( $option );
		}

		// Return the requested option or false if setting is not present.
		return ! empty( $option ) ? $option : false;
	}

	/**
	 * Enable custom header support. Enabled by default for Genesis and Genesis Sample themes.
	 *
	 * @uses gppro_enable_header_image_support
	 * @uses gppro_custom_header_args
	 *
	 * @since 1.3.1
	 * @return void
	 */
	public static function enable_custom_header() {
		if ( apply_filters( 'gppro_enable_header_image_support', 'genesis' === GP_Pro_Themes::get_selected_child_theme() ) ) {
			// Use WP custom header instead of Genesis' because its arguments aren't flexible enough.
			add_theme_support(
				'custom-header',
				apply_filters(
					'gppro_custom_header_args',
					array(
						'width'           => 360,
						'height'          => 60,
						'header-selector' => '.header-image .site-title > a',
						'header-text'     => false,
					)
				)
			);
		}
	}

	/**
	 * Check for a header image and remove the title text options
	 *
	 * @param  array  $sections The sections.
	 * @param  string $class    The class.
	 * @return array
	 */
	public function header_item_check( $sections, $class ) {

		// Check for a header image since we use this more than once.
		$header = get_header_image();

		// If we have a header, start some checks.
		if ( ! empty( $header ) ) {

			// Check for the site title options.
			if ( false === apply_filters( 'gppro_enable_site_title_options', false ) ) {

				// Show some text explaining why.
				$sections['section-break-site-title']['break']['text'] =
					// Translators: placeholder is for custom header URL.
					sprintf( __( 'Site title text options are disabled when a custom header image is active. Please remove the header image from <a href="%s">Appearance > Header</a> to enable these settings.', 'gppro' ), admin_url( 'themes.php?page=custom-header' ) );

				// And remove the items.
				unset( $sections['site-title-text-setup'] );
				unset( $sections['site-title-padding-setup'] );
			}

			// Check for the site description options.
			if ( false === apply_filters( 'gppro_enable_site_description_options', false ) ) {

				// Show some text explaining why.
				$sections['section-break-site-desc']['break']['text'] =
					// Translators: Placeholder is for custom header URL.
					sprintf( __( 'Site description text options are disabled when a custom header image is active. Please remove the header image from <a href="%s">Appearance > Header</a> to enable these settings.', 'gppro' ), admin_url( 'themes.php?page=custom-header' ) );

				// And remove the items.
				unset( $sections['site-desc-display-setup'] );
				unset( $sections['site-desc-type-setup'] );
			}
		}

		// Run check for active header sidebar.
		if ( ! is_active_sidebar( 'header-right' ) ) {
			unset( $sections['section-break-header-nav'] );
			unset( $sections['header-nav-color-setup'] );
			unset( $sections['header-nav-type-setup'] );
			unset( $sections['header-nav-item-padding-setup'] );
			unset( $sections['section-break-header-widgets'] );
			unset( $sections['header-widget-title-setup'] );
			unset( $sections['header-widget-content-setup'] );

			if ( is_registered_sidebar( 'header-right' ) ) {

				// add a message when there are no widgets found.
				$sections['section-break-empty-header-widgets-setup'] = array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Header Widgets', 'gppro' ),
						'text'  => __( 'There are currently no active items in the header widget area.', 'gppro' ),
					),
				);
			}
		}

		// Send back the sections.
		return $sections;
	}

	/**
	 * Check pagination option and display accordingly
	 *
	 * @param  array  $sections The sections.
	 * @param  string $class    The class.
	 * @return mixed $items
	 */
	public function pagination_check( $sections, $class ) {

		// get my navigation type.
		$navtype = self::theme_option_check( 'posts_nav' );

		// bail without a nav type.
		if ( empty( $navtype ) ) {
			return $sections;
		}

		if ( 'prev-next' === $navtype ) {
			unset( $sections['extras-pagination-numeric-backs'] );
			unset( $sections['extras-pagination-numeric-colors'] );
			unset( $sections['extras-pagination-numeric-padding-setup'] );
		}

		if ( 'numeric' === $navtype ) {
			unset( $sections['extras-pagination-text-setup'] );
		}

		// send it back.
		return $sections;
	}

	/**
	 * Check for Jetpack comments and disable
	 *
	 * @param  array  $sections The sections.
	 * @param  string $class    The class.
	 * @return mixed  $items Jetpack
	 */
	public function jetpack_comments( $sections, $class ) {
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'comments' ) ) {
			unset( $sections['comment-reply-notes-setup'] );
			unset( $sections['section-break-comment-reply-atags-setup'] );
			unset( $sections['comment-reply-atags-area-setup'] );
			unset( $sections['comment-reply-atags-base-setup'] );
			unset( $sections['comment-reply-atags-code-setup'] );
			unset( $sections['section-break-comment-reply-fields'] );
			unset( $sections['comment-reply-fields-label-setup'] );
			unset( $sections['section-break-comment-reply-fields-input'] );
			unset( $sections['comment-reply-fields-input-layout-setup'] );
			unset( $sections['comment-reply-fields-input-color-base-setup'] );
			unset( $sections['comment-reply-fields-input-color-focus-setup'] );
			unset( $sections['comment-reply-fields-input-type-setup'] );
			unset( $sections['section-break-comment-submit-button'] );
			unset( $sections['comment-submit-button-color-setup'] );
			unset( $sections['comment-submit-button-type-setup'] );
			unset( $sections['comment-submit-button-spacing-setup'] );

			// add a message regarding Jetpack.
			$sections['section-break-comments-jetpack-setup'] = array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Comment Form Fields', 'gppro' ),
					'text'  => __( 'You are currently using Jetpack Comments, which cannot be custom styled.', 'gppro' ),
				),
			);
		}

		// send it back.
		return $sections;
	}

	/**
	 * Remove the settings related to the preview if it is disabled.
	 *
	 * @param  array  $sections  The current array of sections.
	 * @param  string $class     The body class currently in use.
	 *
	 * @return array  $sections  The modified array of sections.
	 */
	public function remove_preview_settings( $sections, $class ) {

		// If we have disabled the preview completely, remove.
		if ( false !== apply_filters( 'gppro_disable_preview_pane', false ) ) {
			unset( $sections['section-break-user-preview-url-area'] );
			unset( $sections['user-preview-url-area'] );
		}

		// Return the array of sections.
		return $sections;
	}

	/**
	 * Remove the default markup and replace it with a simple message.
	 *
	 * @param  mixed / HTML $preview  The whole preview pane.
	 *
	 * @return mixed / HTML $preview  The potentially modified preview pane.
	 */
	public function remove_preview_pane( $preview ) {

		// If we have disabled the preview completely, show a new box.
		if ( false !== apply_filters( 'gppro_disable_preview_pane', false ) ) {

			// Add my text.
			$message = __( 'The preview window has been disabled.', 'gppro' );

			// And set the window.
			$preview = '<div class="gppro-preview-window gppro-preview-disabled gppro-preview-fixed"><p>' . esc_html( $message ) . '</p></div>';
		}

		// Send back the preview.
		return $preview;
	}

	/**
	 * Add link decoration controls for supported themes
	 *
	 * Currently added for Genesis and Genesis Sample
	 *
	 * @since 1.3.1
	 * @return void
	 */
	public function link_decorations() {
		if ( 'genesis' === GP_Pro_Themes::get_selected_child_theme() ) {
			add_filter( 'gppro_sections', array( 'GP_Pro_Sections', 'link_decoration' ), 10, 2 );
		}
	}

	/**
	 * Add Genesis-specific defaults that don't apply to any child theme.
	 *
	 * @since 1.3.1
	 * @param  array $defaults The defaults.
	 * @return array
	 */
	public function genesis_defaults( $defaults ) {

		// If Genesis is not the selected theme, just return the defaults.
		if ( 'genesis' !== GP_Pro_Themes::get_selected_child_theme() ) {
			return $defaults;
		}

		// Add our link decoration stuff.
		$defaults['post-header-meta-link-dec']       = 'none';
		$defaults['post-entry-link-dec']             = 'none';
		$defaults['post-footer-link-dec']            = 'none';
		$defaults['extras-read-more-link-dec']       = 'none';
		$defaults['extras-author-box-bio-link-dec']  = 'none';
		$defaults['comment-element-name-link-dec']   = 'none';
		$defaults['comment-element-date-link-dec']   = 'none';
		$defaults['comment-element-body-link-dec']   = 'none';
		$defaults['comment-element-reply-link-dec']  = 'none';
		$defaults['comment-reply-notes-link-dec']    = 'none';
		$defaults['sidebar-widget-content-link-dec'] = 'none';
		$defaults['footer-widget-content-link-dec']  = 'none';
		$defaults['footer-main-content-link-dec']    = 'none';

		// Return the array of default values.
		return $defaults;
	}

	/**
	 * Add Lato to the available themes.
	 *
	 * @param  array $stacks  The current array of fonts.
	 *
	 * @return array $stacks  The potentially modified array of fonts.
	 */
	public function lato_native_font( $stacks ) {

		// Make an array of Lato.
		$lato = array(
			'lato' => array(
				'label' => __( 'Lato', 'gppro' ),
				'css'   => '"Lato", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			),
		);

		// Set a variable for our sans serif fonts.
		$sansstacks = $stacks['sans'];

		// If we have base Genesis being used, swap it.
		if ( 'genesis' === GP_Pro_Themes::get_selected_child_theme() ) {
			$stacks['sans'] = $sansstacks + $lato;
		}

		// Swap Lato over to native via filter.
		if ( false !== apply_filters( 'gppro_lato_font_native', false ) ) {
			$stacks['sans'] = $sansstacks + $lato;
		}

		// Return our font stacks.
		return $stacks;
	}

	/**
	 * Swap Lato source to native.
	 *
	 * @param  array $webfonts  The current array of fonts.
	 *
	 * @return array $webfonts  The potentially modified array of fonts.
	 */
	public function lato_webfont( $webfonts ) {

		// Bail if plugin class isn't present.
		if ( ! class_exists( 'GP_Pro_Google_Webfonts' ) ) {
			return $webfonts;
		}

		// If we don't have Lato at all, bail.
		if ( ! isset( $webfonts['lato'] ) ) {
			return $webfonts;
		}

		// If we have base Genesis being used, swap it.
		if ( 'genesis' === GP_Pro_Themes::get_selected_child_theme() ) {
			unset( $webfonts['lato'] );
		}

		// Swap Lato over to native via filter.
		if ( false !== apply_filters( 'gppro_lato_font_native', false ) ) {
			unset( $webfonts['lato'] );
		}

		// Send back the array of webfont data.
		return $webfonts;
	}

	/**
	 * Helper function to check and set active flag on activation.
	 *
	 * @return void
	 */
	public function set_active_flag() {

		// First check if we have it.
		$coreactive = get_option( 'gppro_plugin_active', 0 );

		// Add it if we don't.
		if ( empty( $coreactive ) ) {
			update_option( 'gppro_plugin_active', true );
		}
	}

	/**
	 * Public API for getting style defaults
	 *
	 * @return array $defaults
	 */
	public function get_defaults() {
		return $this->defaults;
	}

	/**
	 * Run our active checks and load files if applicable
	 *
	 * @return void
	 */
	public function load_admin() {

		// run our active check (again).
		if ( false === self::check_active() ) {
			return;
		}

		// we're all clear - load our files.
		require_once GPP_DIR . 'lib/setup.php';
		require_once GPP_DIR . 'lib/admin/init.php';
		require_once GPP_DIR . 'lib/sections.php';
		require_once GPP_DIR . 'lib/builder.php';
		require_once GPP_DIR . 'lib/helper.php';
		require_once GPP_DIR . 'lib/ajax.php';
		require_once GPP_DIR . 'lib/support.php';
		require_once GPP_DIR . 'lib/debug.php';
		require_once GPP_DIR . 'lib/notices.php';
		require_once GPP_DIR . 'lib/export.php';
		require_once GPP_DIR . 'lib/import.php';
		require_once GPP_DIR . 'lib/utilities.php';
		require_once GPP_DIR . 'lib/preview.php';
		require_once GPP_DIR . 'lib/front.php';

		add_action( 'after_setup_theme', array( $this, 'set_defaults' ) );

		// set our flag.
		$this->set_active_flag();

		if ( get_option( 'gppro-customizer-beta' ) ) {
			add_action( 'after_setup_theme', array( $this, 'load_customizer' ), 11 );
		}
	}

	/**
	 * Sets up the defaults after the theme is setup so it is possible to add support from the theme.
	 */
	public function set_defaults() {
		// Set style defaults.
		if ( class_exists( 'GP_Pro_Helper' ) ) {
			$this->defaults = GP_Pro_Helper::set_defaults();
		}
	}
	/**
	 * Generate CSS theme_mod values.
	 *
	 * @param  string $create The content to create.
	 * @return bool
	 */
	public static function generate_file( $create ) {

		// handle our before action.
		do_action( 'gppro_before_create' );

		$file_key = md5( $create );

		set_theme_mod( 'dpp_file_key', $file_key );

		set_theme_mod( 'dpp_file_time', time() );

		set_theme_mod( 'dpp_styles', trim( $create ) );

		wp_cache_delete( 'dpp_styles' );

		// handle our after action.
		do_action( 'gppro_after_create' );

		return true;
	}

	/**
	 * Run the clearing function for various caching plugins
	 * like WP Super Cache, W3 Total Cache, etc
	 */
	public function clear_caching_plugins() {

		// WP Super Cache.
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}

		// W3 Total Cache DB.
		if ( function_exists( 'w3tc_dbcache_flush' ) ) {
			w3tc_dbcache_flush();
		}

		// W3 Total Cache page cache.
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		}

		// W3 Total Cache object cache.
		if ( function_exists( 'w3tc_objectcache_flush' ) ) {
			w3tc_objectcache_flush();
		}

		// W3 Total Cache minification cache.
		if ( function_exists( 'w3tc_minify_flush' ) ) {
			w3tc_minify_flush();
		}

		// standard caching flushing.
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}

		// standard object caching flushing.
		if ( function_exists( 'opcache_get_status' ) && function_exists( 'opcache_invalidate' ) ) {

			// fetch the status.
			$status = opcache_get_status();

			// make sure I have them.
			if ( ! empty( $status['scripts'] ) ) {

				// loop them.
				foreach ( $status['scripts'] as $script => $details ) {

					// check the script.
					if ( strpos( $script, ABSPATH ) === 0 ) {
						opcache_invalidate( $script );
					}
				}
			}
		}

		// Bluehost page cache.
		if ( has_action( 'epc_purge' ) ) {
			do_action( 'epc_purge' );
		}
	}

	/**
	 * Fetch the license data and return whatever may be needed
	 *
	 * @param  mixed $key The key to fetch.
	 * @return mixed|string|array      License data
	 */
	public static function license_data( $key = false ) {

		// set a default option table name with filter.
		$option = apply_filters( 'gppro_core_update_key', 'gppro_core_active' );

		// fetch the data.
		$data = get_option( $option );

		// bail if none returned.
		if ( ! $data || empty( $data ) ) {
			return false;
		}

		// if we requested a key and we don't have it, false.
		if ( ! empty( $key ) && empty( $data[ $key ] ) ) {
			return false;
		}

		// return the key if it exists or the whole thing.
		return ! empty( $key ) && isset( $data[ $key ] ) ? $data[ $key ] : $data;
	}

	/**
	 * Call update function for plugin
	 *
	 * @return mixed bool
	 */
	public function edd_core_update() {

		// Retrieve our license key from the DB, and filter for checking license in other data.
		$data = apply_filters( 'gppro_core_update_data', self::license_data() );
		if ( false === $data ) {
			return;
		}

		// Filter out empty stuff.
		$data = array_filter( (array) $data, 'strlen' );

		// Bail if no license data is present.
		if ( empty( $data ) || empty( $data['status'] ) || empty( $data['license'] ) || 'valid' !== $data['status'] ) {
			return;
		}

		// setup the updater.
		new RKV_SL_Plugin_Updater(
			GPP_STORE_URL, __FILE__, array(
				'version'   => GPP_VER,                     // current version number.
				'license'   => $data['license'],            // license key (used get_option above to retrieve from DB).
				'item_name' => GPP_ITEM_NAME,               // name of this plugin.
				'author'    => 'Genesis Design Palette',    // author of this plugin.
			)
		);
	}

	/**
	 * Deprecated function previously used when getting the created style file.
	 *
	 * @return array With empty dir key index.
	 */
	public static function filebase() {
		_deprecated_function( __FUNCTION__, '1.4.0' );
		return array( 'dir' => '' );
	}

	/**
	 * Requires the customizer file after the child theme is setup.
	 */
	public function load_customizer() {
		new DPP\Customizer\Base();

		if ( is_customize_preview() ) {
			new DPP\Customizer\Preview();
		}
		add_action( 'customize_save_after', array( $this, 'customize_save_after' ) );
	}

	/**
	 * Publishes the customizer settings.
	 */
	public function customize_save_after() {
		new DPP\Customizer\Publish();
	}
	// end class.
}

// Instantiate our class.
$Genesis_Palette_Pro = Genesis_Palette_Pro::getInstance();
