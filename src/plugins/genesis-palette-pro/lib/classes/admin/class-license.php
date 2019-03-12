<?php
/**
 * Genesis Design Palette Pro - Licensing Module
 *
 * Contains functions specific to the whole licensing process.
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
 * Licensing Class.
 *
 * Contains all licensing functionality.
 */
class License extends Base {

	/**
	 * Sets the priority for the filter.
	 *
	 * @var int
	 */
	public $priority = 1000;

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
			'license' => array(
				'title'    => __( 'License', 'gppro' ),
				'callback' => array( $this, 'admin_page' ),
			),
		);
		$this->tabs     = array(
			'license' => array(
				'label'  => __( 'License', 'gppro' ),
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
		add_action( 'admin_enqueue_scripts', array( $this, 'license_scripts_styles' ) );
		add_action( 'admin_notices', array( $this, 'license_action_response' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'set_manual_config_key' ) );
		add_action( 'admin_init', array( $this, 'check_license' ), 99 );
		add_action( 'admin_init', array( $this, 'manual_activation' ) );
		add_action( 'admin_init', array( $this, 'manual_deactivation' ) );
		add_filter( 'gppro_buttons', array( $this, 'license_button_nags' ), 50 );
		add_action( 'after_plugin_row', array( $this, 'license_row_prompts' ), 99, 3 );
	}

	/**
	 * Load our CSS and JS used on the theme licensing fields.
	 *
	 * @return void
	 */
	public function license_scripts_styles() {

		// Check for our current DPP screen.
		if ( false === \GP_Pro_Utilities::check_current_dpp_screen() ) {
			return;
		}

		if ( empty( $_GET['current-tab'] ) || 'genesis-palette-pro-license' !== $_GET['current-tab'] ) { // WPCS: csrf ok.
			return;
		}

		// Get our file variables.
		$vars = \GP_Pro_Utilities::get_filename_vars();

		// Load our CSS file.
		wp_enqueue_style( 'gppro-licensing', plugins_url( 'lib/css/licensing' . $vars['style'], GPP_FILE ), array(), $vars['vers'], 'all' );

		// Load our JS files.
		wp_enqueue_script( 'hideShowPassword', plugins_url( 'lib/js/ext/hideShowPassword' . $vars['script'], GPP_FILE ), array( 'jquery' ), '2.0.11', true );
		wp_enqueue_script( 'gppro-licensing', plugins_url( 'lib/js/licensing' . $vars['script'], GPP_FILE ), array( 'jquery' ), $vars['vers'], true );
		wp_localize_script(
			'gppro-licensing', 'licenseText', array(
				'activate'     => __( 'Activate License', 'gppro' ),
				'deactivate'   => __( 'Deactivate License', 'gppro' ),
				'activated'    => __( 'This license key has been activated.', 'gppro' ),
				'deactivated'  => __( 'This license key has been deactivated.', 'gppro' ),
				'missingkey'   => __( 'Please enter a license key.', 'gppro' ),
				'generalerror' => __( 'There was an error processing this request.', 'gppro' ),
			)
		);

		// Allow other themes / plugins to piggyback on our call.
		do_action( 'gppro_load_licensing_scripts', $vars );
	}

	/**
	 * Add a prompt to the license row.
	 *
	 * @param  string $plugin_file  The relative file name / path of the plugin.
	 *
	 * @return void
	 */
	public function license_row_prompts( $plugin_file ) {

		// Bail right away if there is no license management to be done.
		if ( false !== apply_filters( 'gppro_disable_license_management', false ) ) {
			return;
		}

		// Add our "before" action.
		do_action( 'gppro_before_license_prompt' );

		// Only run this on our plugin.
		if ( false === strpos( $plugin_file, 'genesis-palette-pro.php' ) ) {
			return;
		}

		// Do our local check first to avoid wasting any time.
		if ( false !== \GP_Pro_Utilities::check_local_dev() ) {
			return;
		}

		// Run the active license check.
		$check = \Genesis_Palette_Pro::license_data( 'status' );
		$check = ! empty( $check ) ? esc_attr( $check ) : $this->check_license();

		// If we have a valid license (or local), just bail.
		if ( in_array( $check, array( 'valid', 'local' ), true ) ) {
			return;
		}

		// Output our message.
		echo '<td colspan="3" class="gppro-license-plugin-prompt">';
			echo '<div class="notice gppro-license-plugin-notice inline notice-warning notice-alt">';
				echo '<p>';

		if ( 'expired' === $check ) { // We have a different message for renewals, so do that first.

			printf(
				// Translators: %1$s = HTML open link tag for renewal link. %2$s = closing HTML link tag.
				__( 'Your license key has expired. Please %1$srenew your license key%2$s to continue receiving updates and support.', 'gppro' ),
				'<a target="_blank" href="' . esc_url( self::get_renewal_link() ) . '">',
				'</a>'
			); // WPCS: xss ok.
		} else { // The message for everyone else.

			printf(
				// Translators: $1$s = HTML open link tag for license page $2$s = html open link tag for pricing page and %2$s = close link tag.
				__( 'A valid license key is required to receive updates and support. Please %1$senter your license key%3$s or %2$spurchase one%3$s.', 'gppro' ),
				'<a href="' . esc_url( self::get_license_page_link() ) . '">',
				'<a target="_blank" href="' . esc_url( 'https://genesisdesignpro.com/pricing/' ) . '">',
				'</a>'
			); // WPCS: xss ok.
		}

				echo '</p>';
			echo '</div>';
		echo '</td>';

		// Some quick CSS.
		echo '<style>';
			echo '.widefat .gppro-license-plugin-prompt { padding: 0; }';
			echo '.wrap .gppro-license-plugin-notice { margin: 0 0 10px; box-shadow: 0 -1px 0 rgba(0, 0, 0, 0.1) inset }';
			echo '.wrap .gppro-license-plugin-notice p { font-size: 14px; text-indent: 25px; }';
			echo '.wrap .gppro-license-plugin-notice p::before { font-size: 20px; line-height: 1; margin-right: 4px; font-family: dashicons; content: "\f160"; }';
			echo '.wrap .gppro-license-plugin-notice p a { text-decoration: underline; }';
		echo '</style>';

		// Add our "after" action.
		do_action( 'gppro_after_license_prompt' );
	}

	/**
	 * Display the admin settings based on the provided query string
	 *
	 * @return void
	 */
	public function license_action_response() {

		// First check we're on the right page.
		if ( empty( $_GET['page'] ) || 'dpp-license-tools' !== sanitize_key( $_GET['page'] ) ) { // WPCS: csrf ok.
			return;
		}

		// Make sure we have the action.
		if ( empty( $_GET['action'] ) || ! in_array( sanitize_key( $_GET['action'] ), array( 'activate', 'deactivate' ), true ) ) { // WPCS: csrf ok.
			return;
		}

		// Make sure we have the process result.
		if ( empty( $_GET['processed'] ) || ! in_array( sanitize_key( $_GET['processed'] ), array( 'success', 'failure' ), true ) ) { // WPCS: csrf ok.
			return;
		}

		// Set my base class.
		$class = 'notice is-dismissible';

		// Handle our success message.
		if ( 'success' === sanitize_key( $_GET['processed'] ) ) { // WPCS: csrf ok.

			// Add success to the class.
			$class .= ' notice-success';

			// And my error text.
			$text = self::get_message_text( sanitize_key( $_GET['action'] ) ); // WPCS: csrf ok.
		}

		// Handle our failure messages.
		if ( 'failure' === sanitize_key( $_GET['processed'] ) ) { // WPCS: csrf ok.

			// Get my error message.
			$error = ! empty( $_GET['errcode'] ) ? strtolower( sanitize_key( $_GET['errcode'] ) ) : 'unknown'; // WPCS: csrf ok.

			// Add failure to the class.
			$class .= ' notice-error';

			// And my error text.
			$text = self::get_message_text( $error );
		}

		// And output it.
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_attr( $text ) );
	}

	/**
	 * Register our settings key.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'gppro_license_settings', 'gppro_core_active' );
	}

	/**
	 * Check wp-config for license key constant.
	 *
	 * @return void
	 */
	public function set_manual_config_key() {

		// Bail if not defined.
		if ( ! defined( 'GPPRO_CORE_LICENSE_KEY' ) ) {

			// Delete the key.
			delete_option( 'gppro_core_config_key' );

			// And just bail.
			return;
		}

		// Fetch the current status.
		$status = \Genesis_Palette_Pro::license_data( 'status' );

		// If we are valid, bail.
		if ( ! empty( $status ) && 'valid' === $status ) {
			return;
		}

		// Run key check.
		$update = self::api_license_key_check( GPPRO_CORE_LICENSE_KEY, 'activate_license' );

		// Bail with no update status, or an empty.
		if ( empty( $update['status'] ) || 'valid' !== $update['status'] ) {

			// Delete the key.
			delete_option( 'gppro_core_config_key' );

			// And just bail.
			return;
		}

		// Set the license as verified.
		self::api_license_verified( GPPRO_CORE_LICENSE_KEY, $update['status'] );

		// Set option key to hide settings field.
		update_option( 'gppro_core_config_key', 'valid', 'no' );
	}

	/**
	 * Check the current license to make sure it's valid.
	 *
	 * @return mixed|string|bool $status  The resulting license status.
	 */
	public function check_license() {

		// Don't fire on an Ajax or cron request.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return false;
		}

		// No license to check. bail.
		$license = \Genesis_Palette_Pro::license_data( 'license' );
		if ( false === $license ) {
			return false;
		}

		// If for some reason the key came back as an array, bail.
		if ( is_array( $license ) ) {
			return false;
		}

		// Run the license check a maximum of once per day.
		$status = get_transient( 'gppro_core_license_verify' );
		if ( false === $status ) {

			// Data to send in our API request.
			$body = array(
				'edd_action' => 'check_license',
				'license'    => trim( $license ),
				'item_name'  => urlencode( GPP_ITEM_NAME ), // The name of our product in EDD.
				'url'        => home_url(),
			);

			// Set the args for the actual API request.
			$args = array(
				'timeout'   => \GP_Pro_Utilities::get_timeout_val(),
				'body'      => $body,
				'sslverify' => false,
			);

			// Filter our args before making the request.
			$args = apply_filters( 'gppro_license_api_args', $args, 'check_license', $license );

			// Call the custom API.
			$call = wp_remote_post( GPP_STORE_URL, $args );

			// Make sure the response came back okay.
			if ( is_wp_error( $call ) ) {

				// Set a transient to check in an hour.
				set_transient( 'gppro_core_license_verify', 'unknown', HOUR_IN_SECONDS );
			}

			// Extract the data.
			$data = json_decode( wp_remote_retrieve_body( $call ), true );

			// Bad data. bail.
			if ( empty( $data ) || ! is_array( $data ) || empty( $data['license'] ) ) {

				// Set a transient to check in an hour.
				set_transient( 'gppro_core_license_verify', 'unknown', HOUR_IN_SECONDS );

				return false;
			}

			// If not currently valid, handle it.
			if ( ! empty( $data['license'] ) && 'valid' !== $data['license'] ) {
				self::api_license_verified( $license, $data['license'] );
			}

			// If the license is good, set the metadata (again).
			if ( ! empty( $data['license'] ) && 'valid' === $data['license'] ) {
				self::set_license_metadata( $data, $license );
			}

			// Set my transient.
			set_transient( 'gppro_core_license_verify', $data['license'], DAY_IN_SECONDS );
		}

		// Return the status.
		return $status;
	}

	/**
	 * Construct our license field page.
	 *
	 * @return void
	 */
	public function admin_page() {

		// The wrapper for the admin page.
		echo '<div class="wrap gppro-license-admin">';

			// Call our action to add items at the top of the page.
			do_action( 'gppro_before_license_admin_settings' );

			// Fetch the layout.
			echo self::license_field_layout(); // WPCS: xss ok.

			// Call our action to add items at the bottom of the page.
			do_action( 'gppro_after_license_admin_settings' );

		// Close the markup.
		echo '</div>';
	}

	/**
	 * Display our license fields on the settings page.
	 *
	 * @return string License field HTML.
	 */
	public static function license_field_layout() {

		// Display a message if we are on local dev.
		if ( false !== \GP_Pro_Utilities::check_local_dev() ) {

			// Show the message regarding the disclaimer.
			return '<p class="gppro-field-disclaimer">' . __( 'License activation is not required on an identified local development or staging environment.', 'gppro' ) . '</p>';
		}

		// Fetch the license data.
		$data = \Genesis_Palette_Pro::license_data();

		// Check each part.
		$license = ! empty( $data['license'] ) ? $data['license'] : '';
		$status  = ! empty( $data['status'] ) ? $data['status'] : '';

		// Set up actions and button text based on current license status.
		$action = ! empty( $license ) ? 'core_deactivate' : 'core_activate';
		$button = self::get_button_text( $status );

		// Set our various classes.
		$class = 'gppro-license-core-input';

		// Add the "valid" class.
		if ( empty( $status ) && 'valid' === $status ) {
			$class .= 'gppro-license-core-valid';
		}

		// Build my reset link.
		$reset = add_query_arg(
			array(
				'gppro-purge' => 1,
				'tools-page'  => 1,
			), admin_url()
		);

		// Get my main DPP page link.
		$dpplink = \GP_Pro_Helper::get_settings_url();
		$dppsupp = add_query_arg( 'current-tab', 'genesis-palette-pro-support', \GP_Pro_Helper::get_settings_url() );

		// Check for the non-ajax flag.
		$noajax = ! empty( $_GET['noajax'] ) ? 1 : ''; // WPCS: csrf ok.

		// Set an empty build.
		$build = '';

		/*
		 * Below starts the markup for the actual fields.
		 */

		// The text explaining the license key stuff.
		// Translators: The placeholder is for the reset URL.
		$build .= '<p>' . sprintf( __( 'Enter your license key for Design Palette Pro and any add-ons. Having license activation issues? <a href="%s">Reset your license</a>', 'gppro' ), esc_url( $reset ) ) . '.</p>';

		// Fire the action before the keys block.
		do_action( 'gppro_before_license_keys_block' );

		// The table with the actual license key(s).
		$build .= '<div class="gppro-license-keys-block">';
		$build .= '<form method="post" action="' . esc_url( self::get_license_page_link() ) . '">';
		$build .= '<table class="form-table">';
		$build .= '<tbody>';

			// Open the row of the core license key.
			$build .= '<tr class="gppro-license-key-row gppro-license-key-core-row" valign="top">';

				// Fire the action before the row.
				do_action( 'gppro_before_core_license_key_row' );

				// Set up the label structure for the field.
				$build .= '<th class="gppro-license-key-label" scope="row">' . esc_html( 'Design Palette Pro', 'gppro' ) . '</th>';

				// Set up the actual field items.
				$build .= '<td class="gppro-license-key-single gppro-license-key-core-single">';

					// Output the actual input field.
					$build .= '<input type="text" name="gppro-license-core" id="gppro-license-core" class="regular-text gppro-license-row-field gppro-license-row-input ' . esc_attr( $class ) . '" value="' . esc_attr( $license ) . '" autocomplete="off">';

					// Output the eyeball icon for the display toggle.
					$build .= '<span class="dashicons dashicons-hidden password-toggle password-toggle-hide"></span>';

					// If we set the key in the wp-config file, disable the button and explain why.
		if ( false !== \GP_Pro_Helper::get_single_option( 'gppro_core_config_key' ) ) {

			// Output the submit button.
			$build .= '<button type="button" class="button button-secondary gppro-license-row-field gppro-license-row-button gppro-license-core-button" disabled="disabled">' . esc_attr( $button ) . '</button>';

			// Provide text explaining why the button is disabled.
			$build .= '<p class="gppro-license-field-message">' . __( 'This license key has been set in the wp-config.php file and cannot be deactivated here.', 'gppro' ) . '</p>';
		} else {

			// Output the submit button.
			$build .= '<button value="1" type="submit" name="gppro-license-core-submit" class="button button-secondary gppro-license-row-field gppro-license-row-button gppro-license-core-button">' . esc_attr( $button ) . '</button>';

			// A nice spinner.
			$build .= '<span class="spinner gppro-license-spinner"></span>';

			// Output our action, which will depend on the current status.
			$build .= '<input type="hidden" name="gppro-license-action" id="gppro-license-action" value="' . esc_attr( $action ) . '">';

			// Add a flag to force non-AJAX processing.
			$build .= '<input type="hidden" name="gppro-non-ajax" id="gppro-non-ajax" value="' . $noajax . '">';

			// Nonce it up.
			$build .= wp_nonce_field( 'gppro-license-core-nonce', 'gppro-license-core-nonce', false, false );

			// A blank spot for the result to display.
			$build .= '<p class="gppro-license-field-message">' . self::license_meta_display() . '</p>';
		}

				// And close up the row.
				$build .= '</td>';

				// Fire the action after the row.
				do_action( 'gppro_after_core_license_key_row' );

			// Close the row of the core license key.
			$build .= '</tr>';

		$build .= '</tbody>';
		$build .= '</table>';
		$build .= '</form>';
		$build .= '</div>';

		// Fire the action after the keys block.
		do_action( 'gppro_after_license_keys_block' );

		// And return the build.
		return $build;
	}

	/**
	 * Display the various bits of license data we have.
	 *
	 * @param  string $space  What to use as a space.
	 *
	 * @return string
	 */
	public static function license_meta_display( $space = '&nbsp;' ) {

		// If we have no license data, just show the blank space.
		$data = \GP_Pro_Helper::get_single_option( 'gppro_license_metadata' );
		if ( false === $data ) {
			return $space;
		}

		// Set an empty meta array.
		$meta = array();

		// Show the expiration date.
		if ( ! empty( $data['expires'] ) ) {

			// Format the date.
			$date = date( apply_filters( 'gppro_license_date_format', 'F jS, Y' ), $data['expires'] );

			// Build the conditional text.
			// Translators: placeholder is for expiration date.
			$text = ! empty( $data['pastdue'] ) ? sprintf( __( 'This license expired on %s.', 'gppro' ), esc_attr( $date ) ) : sprintf( __( 'This license expires on %s.', 'gppro' ), esc_attr( $date ) );

			// Build the meta array.
			$meta[] = $text;
		}

		// All the below items are only shown if the license is not expired.
		if ( empty( $data['pastdue'] ) ) {

			// If this is an unlimited license, do that.
			if ( ! empty( $data['unlimited'] ) ) {
				$meta[] = __( 'This license has unlimited activations.', 'gppro' );
			}

			// Show the activation counts on standard licenses.
			if ( empty( $data['unlimited'] ) ) {

				// Set my variables, with a single site being the default.
				$count = ! empty( $data['count'] ) ? absint( $data['count'] ) : 0;
				$limit = ! empty( $data['limit'] ) ? absint( $data['limit'] ) : 1;

				// Build the meta array.
				if ( $count === $limit ) {
					// Translators: %1$d = $limit.
					$meta[] = sprintf( __( 'You have used your total of %1$d activations.', 'gppro' ), $limit );
				} else {
					// Translators: %1$d = $count and %2$d = $limit.
					$meta[] = sprintf( __( '%1$d of the %2$d total activations have been used.', 'gppro' ), $count, $limit );
				}
			}
		}

		// Show the renewal link if we are past due.
		if ( ! empty( $data['pastdue'] ) ) {

			// Write the text.
			// Translators: placeholder is for the renewal link URL.
			$meta[] = sprintf( __( '<a href="%s">Click here to renew this license.</a>', 'gppro' ), esc_url( self::get_renewal_link() ) );
		}

		// Bail if we have no items to show.
		if ( empty( $meta ) ) {
			return $space;
		}

		// Return the entire build.
		return '<span class="gppro-license-meta">' . implode( ' </span><span class="gppro-license-meta">', $meta ) . '</span>';
	}

	/**
	 * Call the manual activation process.
	 *
	 * @return void
	 */
	public function manual_activation() {

		// Bail if this is an Ajax or Cron job.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Check for our hidden field.
		if ( empty( $_POST['gppro-license-action'] ) || 'core_activate' !== sanitize_key( $_POST['gppro-license-action'] ) ) { // WPCS: csrf ok.
			return;
		}

		// First delete any transients, just in case.
		\GP_Pro_Helper::purge_transients();

		// Set a default redirect link URL.
		$link = self::get_license_page_link( array( 'action' => 'activate' ) );

		// Check for the non-ajax force flag.
		if ( ! empty( $_POST['gppro-non-ajax'] ) ) {
			$link = add_query_arg( array( 'noajax' => 1 ), $link );
		}

		// Make sure a nonce was passed and is valid.
		if ( empty( $_POST['gppro-license-core-nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['gppro-license-core-nonce'] ), 'gppro-license-core-nonce' ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'MISSING_NONCE',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Bail if the license field is missing.
		if ( empty( $_POST['gppro-license-core'] ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'EMPTY_LICENSE',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Delete our current license data in case its left over.
		\GP_Pro_Helper::purge_options( false, true );

		// Set my license as a variable.
		$key = sanitize_key( $_POST['gppro-license-core'] );

		// Run key check.
		$check = self::api_license_key_check( $key, 'activate_license' );

		// No return. not sure why.
		if ( empty( $check ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'NO_RETURN',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// No status. not sure why.
		if ( empty( $check['status'] ) ) {

			// Get my error code if we have one.
			$error = ! empty( $check['errcode'] ) ? esc_attr( $check['errcode'] ) : 'NO_STATUS';

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => $error,
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Wrong status. not sure why.
		if ( ! in_array( $check['status'], array( 'valid', 'invalid' ), true ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'BAD_STATUS',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// If we have an error code.
		if ( is_array( $check ) && ! empty( $check['errcode'] ) && ! empty( $check['message'] ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => $check['errcode'],
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Not valid. I SAID NOT VALID.
		if ( 'invalid' === $check['status'] ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'LICENSE_FAIL',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// License was good. LETS GO.
		if ( 'valid' === $check['status'] ) {

			// Set my redirect link with the success.
			$link = add_query_arg( array( 'processed' => 'success' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}
	}

	/**
	 * Call the manual deactivation process.
	 *
	 * @return void
	 */
	public function manual_deactivation() {

		// Bail if this is an Ajax or Cron job.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Check for our hidden field.
		if ( empty( $_POST['gppro-license-action'] ) || 'core_deactivate' !== sanitize_key( $_POST['gppro-license-action'] ) ) { // WPCS: csrf ok.
			return;
		}

		// First delete any transients, just in case.
		\GP_Pro_Helper::purge_transients();

		// Set a default redirect link URL.
		$link = self::get_license_page_link( array( 'action' => 'deactivate' ) );

		// Check for the non-ajax force flag.
		if ( ! empty( $_POST['gppro-non-ajax'] ) ) {
			$link = add_query_arg( array( 'noajax' => 1 ), $link );
		}

		// Make sure a nonce was passed and is valid.
		if ( empty( $_POST['gppro-license-core-nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['gppro-license-core-nonce'] ), 'gppro-license-core-nonce' ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'MISSING_NONCE',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Get plugin items from DB.
		$key = \Genesis_Palette_Pro::license_data( 'license' );

		// Bail if the license field is missing.
		if ( empty( $key ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'NO_LICENSE',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Run key check.
		$check = self::api_license_key_check( $key, 'deactivate_license' );

		// No status. not sure why.
		if ( empty( $check['status'] ) ) {

			// Get my error code if we have one.
			$error = ! empty( $check['errcode'] ) ? esc_attr( $check['errcode'] ) : 'NO_STATUS';

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => $error,
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// We didn't get the deactivated status.
		if ( 'deactivated' !== $check['status'] ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => 'BAD_STATUS',
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// If we have an error code.
		if ( is_array( $check ) && ! empty( $status['errcode'] ) && ! empty( $status['message'] ) ) {

			// Set my redirect link with the error code.
			$link = add_query_arg(
				array(
					'processed' => 'failure',
					'errcode'   => $status['errcode'],
				), $link
			);

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Deactivation was good. LETS GO.
		if ( 'deactivated' === $check['status'] ) {

			// Delete our site options.
			\GP_Pro_Helper::purge_options( false, true );

			// Set my redirect link with the success.
			$link = add_query_arg( array( 'processed' => 'success' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}
	}

	/**
	 * Add the button nags for renewals or activation.
	 *
	 * @param  array $buttons  The existing array of buttons.
	 *
	 * @return array $buttons  The (possibly) modified array of buttons.
	 */
	public function license_button_nags( $buttons ) {

		// Fetch the current status.
		$status = \Genesis_Palette_Pro::license_data( 'status' );

		// Bail if we're good.
		if ( ! empty( $status ) && 'valid' === $status || false !== \GP_Pro_Utilities::check_local_dev() ) {
			return $buttons;
		}

		// Set the button array for no key or invalid.
		if ( empty( $status ) || 'invalid' === $status ) {

			// Now add it to the button output.
			$buttons['license'] = array(
				'button-type'  => 'link',
				'button-link'  => esc_url( self::get_license_page_link() ),
				'button-label' => __( 'Enter License Key', 'gppro' ),
				'button-class' => 'button button-warning button-license-nag',
				'image-class'  => '',
			);
		}

		// Include the renewal button.
		if ( ! empty( $status ) && 'expired' === $status ) {

			// Now add it to the button output.
			$buttons['renew'] = array(
				'button-type'  => 'link',
				'button-label' => __( 'Renew License', 'gppro' ),
				'button-class' => 'button button-warning button-renew-now',
				'button-link'  => esc_url( self::get_renewal_link() ),
				'button-blank' => true,
			);
		}

		// Return the buttons.
		return $buttons;
	}

	/**
	 * The actual license key processing.
	 *
	 * @param  string $key      The license key being checked.
	 * @param  string $process  Which license process we are doing.
	 * @param  string $type     Whether this is an ajax call or not.
	 *
	 * @return mixed            API status based on the requested action.
	 */
	public static function api_license_key_check( $key = '', $process = '', $type = 'manual' ) {

		// Bail if no license key is being passed or not a valid process.
		if ( empty( $key ) || ! in_array( $process, array( 'activate_license', 'deactivate_license' ), true ) ) {
			return false;
		}

		// Set our return.
		$ret = array();

		// Data to send in our API request.
		$body = array(
			'edd_action' => $process,
			'license'    => trim( $key ),
			'item_name'  => urlencode( GPP_ITEM_NAME ), // The name of our product in EDD.
			'url'        => home_url(),
		);

		// Set the args for the actual API request.
		$args = array(
			'timeout'   => \GP_Pro_Utilities::get_timeout_val(),
			'body'      => $body,
			'sslverify' => false,
		);

		// Filter our args before making the request.
		$args = apply_filters( 'gppro_license_api_args', $args, $process, $key );

		// Call the custom API.
		$call = wp_remote_post( GPP_STORE_URL, $args );

		// Make sure the response came back okay.
		if ( is_wp_error( $call ) ) {

			// Fetch my error code.
			$code = $call->get_error_code();
			$code = ! empty( $code ) ? strtoupper( $code ) : 'API_REQUEST_FAIL';

			// Format the response.
			$ret['success'] = false;
			$ret['status']  = '';
			$ret['errmsg']  = $call->get_error_message();
			$ret['errcode'] = esc_attr( $code );
			$ret['message'] = __( 'The activation server is not available.', 'gppro' );

			// Return it if we set to manual.
			if ( 'manual' === $type ) {
				return $ret;
			}

			// Echo out the json encoded response.
			echo json_encode( $ret );
			die();
		}

		// Fetch out the license data.
		$fetch = wp_remote_retrieve_body( $call );

		// Make sure the response came back okay.
		if ( empty( $fetch ) ) {

			// Format the response.
			$ret['success'] = false;
			$ret['status']  = '';
			$ret['errcode'] = 'API_RETRIEVE_FAIL';
			$ret['message'] = __( 'The activation server did not return any information.', 'gppro' );

			// Return it if we set to manual.
			if ( 'manual' === $type ) {
				return $ret;
			}

			// Echo out the json encoded response.
			echo json_encode( $ret );
			die();
		}

		// Get the license data from the return.
		$data = json_decode( \GP_Pro_Utilities::remove_utf8_bom( $fetch ) );

		// Set my message key.
		$msgkey = 'activate_license' === $process ? 'activate' : 'deactivate';

		// Make sure the license status came back okay.
		if ( empty( $data->license ) ) {

			// Format the response.
			$ret['success'] = false;
			$ret['status']  = '';
			$ret['errcode'] = 'API_STATUS_FAIL';
			$ret['message'] = self::get_message_text( $msgkey );

			// Return it if we set to manual.
			if ( 'manual' === $type ) {
				return $ret;
			}

			// Echo out the json encoded response.
			echo json_encode( $ret );
			die();
		}

		// If we don't have success on activation, handle it.
		if ( empty( $data->success ) ) {

			// Get my error message.
			$error = ! empty( $data->error ) ? $data->error : 'unknown';

			// And fetch my error message.
			$text = self::get_message_text( $error );

			// Format the response.
			$ret['success'] = false;
			$ret['status']  = 'invalid';
			$ret['errcode'] = strtoupper( $error );
			$ret['message'] = $text;

			// Set my license status now.
			self::api_license_verified( '', 'invalid' );

			// Return it if we set to manual.
			if ( 'manual' === $type ) {
				return $ret;
			}

			// Echo out the json encoded response.
			echo json_encode( $ret );
			die();
		}

		// If we had success, handle that first.
		if ( ! empty( $data->success ) ) {

			// Get my expire data.
			$expires = ! empty( $data->expires ) ? strtotime( $data->expires ) : false;

			// Format the response.
			$ret['success'] = true;
			$ret['status']  = $data->license;
			$ret['expires'] = $expires;
			$ret['errcode'] = '';
			$ret['message'] = self::get_message_text( $msgkey );

			// Set my license status now.
			self::api_license_verified( $key, 'valid' );

			// Store our metadata for activations.
			self::set_license_metadata( $data, $key );

			// Return it if we set to manual.
			if ( 'manual' === $type ) {
				return $ret;
			}

			// Echo out the json encoded response.
			echo json_encode( $ret );
			die();
		}

		// Format the response.
		$ret['success'] = false;
		$ret['status']  = 'unknown';
		$ret['errcode'] = 'UNKNOWN_ERROR';
		$ret['message'] = self::get_message_text( 'unknown' );

		// Return it if we set to manual.
		if ( 'manual' === $type ) {
			return $ret;
		}

		// Echo out the json encoded response.
		echo json_encode( $ret );
		die();
	}

	/**
	 * The abstracted process storing the license verification result.
	 *
	 * @param  string $license  The license key being stored.
	 * @param  string $status   The status returned from the API call.
	 *
	 * @return mixed             False if we don't have our items, nothing otherwise.
	 */
	public static function api_license_verified( $license = '', $status = '' ) {

		// Bail if both are empty.
		if ( empty( $license ) && empty( $status ) ) {
			return false;
		}

		// Delete the existing keys and transients.
		\GP_Pro_Helper::purge_options( true, true );
		\GP_Pro_Helper::purge_transients();

		// Create data storage array.
		$base = array(
			'license' => $license,
			'status'  => $status,
		);

		// Filter stuff.
		$base = array_filter( $base );

		// Bail if its empty.
		if ( empty( $base ) ) {
			return false;
		}

		// Add our option to the database.
		update_option( 'gppro_core_active', $base, 'no' );

		// Create array for license check transient.
		$check = array(
			'license'   => $status,
			'item_name' => GPP_ITEM_NAME,
		);

		// Set the license check.
		set_transient( 'gppro_core_license_check', $check, DAY_IN_SECONDS );
	}

	/**
	 * Store our license metadata separately.
	 *
	 * @param object $data     The various bits of license data.
	 * @param string $key      The license key tied to the data.
	 *
	 * @return mixed void|bool
	 */
	public static function set_license_metadata( $data, $key = '' ) {

		// Bail without data or a key.
		if ( empty( $data ) || empty( $key ) ) {
			return false;
		}

		// Ensure our data is an object.
		$data = is_array( $data ) ? (object) $data : $data;

		// Pull out the items I want (for now).
		$expires    = ! empty( $data->expires ) ? strtotime( $data->expires ) : 0;
		$site_count = ! empty( $data->site_count ) ? absint( $data->site_count ) : 0;
		$site_limit = ! empty( $data->license_limit ) ? absint( $data->license_limit ) : 0;

		// Now some basic checks for unlimited and past expired.
		$unlimited = ! empty( $data->activations_left ) && 'unlimited' === $data->activations_left ? 1 : 0;
		$pastdue   = ! empty( $expires ) && $expires < time() ? 1 : 0;

		// Set an update array.
		$metadata = array(
			'expires'   => $expires,
			'license'   => esc_attr( $key ),
			'count'     => $site_count,
			'limit'     => $site_limit,
			'unlimited' => $unlimited,
			'pastdue'   => $pastdue,
		);

		// Filter it to add more later, maybe.
		$metadata = apply_filters( 'gppro_license_metadata', $metadata, $data, $key );

		// Set my option.
		update_option( 'gppro_license_metadata', $metadata, 'no' );
	}

	/**
	 * Get the text to use on the license activation process.
	 *
	 * @param  string $key   Which message key to do.
	 *
	 * @return string $text  The resulting text.
	 */
	public static function get_message_text( $key = '' ) {

		// Do our switch check.
		switch ( $key ) {
			case 'missing':
				$text = __( 'There is no record of that license key in our system.', 'gppro' );
				break;

			case 'revoked':
				$text = __( 'This license key has been revoked.', 'gppro' );
				break;

			case 'expired':
				$text = __( 'This license key has expired.', 'gppro' );
				break;

			case 'no_activations_left':
				$text = __( 'This license key has reached the maximum allowed activations.', 'gppro' );
				break;

			case 'item_name_mismatch':
				$text = __( 'This license key does not match the product you have installed.', 'gppro' );
				break;

			case 'activate':
				$text = __( 'This license key has been activated.', 'gppro' );
				break;

			case 'deactivate':
				$text = __( 'This license key has been deactivated.', 'gppro' );
				break;

			case 'http_request_failed':
				$text = __( 'The activation server is not available.', 'gppro' );
				break;

			default:
				$text = __( 'There was an error with this license key.', 'gppro' );
				break;
		}

		// Return the text.
		return $text;
	}

	/**
	 * Get the text to use on the license button process.
	 *
	 * @param  string $status  The current button status.
	 *
	 * @return string $button  The button text.
	 */
	public static function get_button_text( $status = '' ) {

		// Do our switch checks based on status.
		switch ( $status ) {
			case 'valid':
				$button = __( 'Deactivate License', 'gppro' );
				break;

			case 'expired':
				$button = __( 'Deactivate License', 'gppro' );
				break;

			case 'missing':
				$button = __( 'Activate License', 'gppro' );
				break;

			default:
				$button = __( 'Activate License', 'gppro' );
				break;
		}

		// And return the button text.
		return $button;
	}

	/**
	 * Get the renewal link (with fallback).
	 *
	 * @return string $link  The renewal HTML link.
	 */
	public static function get_renewal_link() {

		// Give the "my account" link if no license is stored.
		$license = \GP_Pro_Helper::get_single_option( 'gppro_license_metadata', 'license', false );
		if ( false === $license ) {
			return GPP_STORE_URL . '/my-account/';
		}

		// Return the renewal link.
		return add_query_arg( array( 'edd_license_key' => $license ), esc_url( GPP_STORE_URL . '/checkout/' ) );
	}

	/**
	 * Build and return the link to send the user to the license entering.
	 *
	 * @param  array $args  Optional args to add to the link.
	 *
	 * @return string $link  The URL of the Genesis settings page.
	 */
	public static function get_license_page_link( $args = array() ) {

		// Set my base link.
		$base = add_query_arg( 'current-tab', 'genesis-palette-pro-license', \GP_Pro_Helper::get_settings_url() );

		// Set my link up.
		$link = ! empty( $args ) ? add_query_arg( $args, $base ) : $base;

		// And return my link.
		return apply_filters( 'gppro_license_field_url', $link );
	}

	// End class.
}
