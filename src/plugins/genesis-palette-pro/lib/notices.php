<?php
/**
 * Genesis Design Palette Pro - Notices Module
 *
 * Contains various admin related notices
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

/**
 * Notices Class.
 *
 * Contains all admin notice-related functionality.
 */
class GP_Pro_Notices {

	/**
	 * Display our admin notices based on various
	 * $_POST and $_GET parameters
	 *
	 * @return mixed/html   the admin notice
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

		// Now check to make sure we're on our settings page.
		if ( empty( $_GET['page'] ) || ! in_array( sanitize_key( $_GET['page'] ), array( 'genesis-palette-pro', 'dpp-license-tools' ), true ) ) {  // WPCS: csrf ok.
			return;
		}

		// Call the notices.
		add_action( 'after_setup_theme', array( $this, 'allow_data_child_attr' ) );
		add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
		add_action( 'admin_notices', array( $this, 'low_memory_notice' ) );
		add_action( 'admin_notices', array( $this, 'suhosin_notice' ) );
		add_action( 'admin_notices', array( $this, 'create_debug_notice' ) );
		add_action( 'admin_notices', array( $this, 'child_mismatch_notice' ) );
		add_action( 'admin_notices', array( $this, 'export_notices' ) );
		add_action( 'admin_notices', array( $this, 'import_notices' ) );
		add_action( 'admin_notices', array( $this, 'support_notices' ) );
		add_action( 'admin_notices', array( $this, 'license_notices' ) );
	}

	/**
	 * Add the allowed data attributes into the wp_kses for notice ignoring.
	 *
	 * @return void
	 */
	public function allow_data_child_attr() {

		// Set an array of the tags we wanna handle.
		$tags = apply_filters( 'gppro_kses_tags', array( 'a', 'span' ) );
		if ( false === $tags ) {
			return;
		}

		// Set an array of the data attributes we want to add.
		$attrs = apply_filters( 'gppro_kses_allowed', array( 'data-child' => array() ) );
		if ( false === $attrs ) {
			return;
		}

		// Call our global.
		global $allowedposttags;

		// Loop the allowed tags and add them.
		foreach ( $tags as $tag ) {

			// If we've hit our array of taggable items, add it.
			if ( isset( $allowedposttags[ $tag ] ) && is_array( $allowedposttags[ $tag ] ) ) {
				$allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $attrs ); // WPCS: override ok.
			}
		}
	}

	/**
	 * Checks the current version of PHP.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function php_version_notice() {

		// Check our ignore flag.
		if ( false !== GP_Pro_Helper::get_single_option( 'gppro-warning-phpvers', '', false ) ) {
			return;
		}

		// Get and compare the current version number.
		if ( version_compare( phpversion(), '5.3.0', '<' ) ) {
			self::notice_markup_display( __( 'NOTICE: Your site is currently running an outdated version of PHP and may create issues when using Design Palette. Please contact your host to update this.', 'gppro' ), 'phpvers', 'warning', true, true );
		}
	}

	/**
	 * Checks the available memory.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function low_memory_notice() {

		// Check our ignore flag.
		if ( false !== GP_Pro_Helper::get_single_option( 'gppro-warning-memlimit', '', false ) ) {
			return;
		}

		// Get the memory number.
		$memory = GP_Pro_Utilities::get_memory_limit();

		// We only care if lower than 40.
		if ( absint( $memory ) > 39 ) {
			return;
		}

		// Add the suffix.
		$memory = $memory . 'MB';

		// Set the text for the error notice.
		// Translators: The placeholder is for the available memory.
		$notice = sprintf( __( 'NOTICE: Your available memory is %s, which is below the recommended minimum 40MB. This could interfere with the plugin\'s ability to function. Please contact your host to increase this.', 'gppro' ), esc_attr( $memory ) );

		// Spit out the message.
		self::notice_markup_display( $notice, 'memlimit', 'warning', true, true );
	}

	/**
	 * Checks if the suhosin extension is active.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function suhosin_notice() {

		// Check our ignore flag.
		if ( false !== GP_Pro_Helper::get_single_option( 'gppro-warning-suhosin', '', false ) ) {
			return;
		}

		// Check for it and spit out the message.
		if ( extension_loaded( 'suhosin' ) && ini_get( 'suhosin.get.max_value_length' ) ) {
			self::notice_markup_display( __( 'NOTICE: The suhosin PHP extension is active. This could interfere with the plugin\'s ability to function. Please contact your host to disable this.', 'gppro' ), 'suhosin', 'warning', true, true );
		}
	}

	/**
	 * Display a message if our purge or create debug methods were used.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function create_debug_notice() {

		// Show cache message.
		if ( ! empty( $_GET['cache'] ) ) {  // WPCS: csrf ok.
			self::notice_markup_display( __( 'The requested cached data has been deleted.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show purge message.
		if ( ! empty( $_GET['purge'] ) ) {  // WPCS: csrf ok.
			self::notice_markup_display( __( 'Your license settings have been removed from the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show create message.
		if ( ! empty( $_GET['create'] ) ) {  // WPCS: csrf ok.
			self::notice_markup_display( __( 'Your license data have been manually set in the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show preview set message.
		if ( ! empty( $_GET['prevset'] ) ) {  // WPCS: csrf ok.
			self::notice_markup_display( __( 'The preview option has been set in the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show option delete message.
		if ( ! empty( $_GET['keydelete'] ) ) {  // WPCS: csrf ok.
			self::notice_markup_display( __( 'The requested option value has been delete from the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show the unknown action message.
		if ( ! empty( $_GET['unknown'] ) ) {  // WPCS: csrf ok.
			self::notice_markup_display( __( 'You have requested an unknown debugging action.', 'gppro' ), '', 'error', false, true, true );
		}
	}

	/**
	 * Check for correct child theme being active.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function child_mismatch_notice() {

		// Do my child theme check. if fail, return the title.
		$data = GP_Pro_Helper::is_child_theme();
		if ( false === $data ) {
			return;
		}

		// Check for dismissed setting (or missing pieces).
		if ( empty( $data['file'] ) || empty( $data['name'] ) ) {
			return;
		}

		// Set our child filename.
		$child = esc_attr( $data['file'] );

		// Check our ignore flag.
		if ( false !== GP_Pro_Helper::get_single_option( 'gppro-warning-' . $child, '', false ) ) {
			return;
		}

		// Check child theme, display warning.
		$ssheet = GP_Pro_Helper::get_single_option( 'stylesheet', '', false );

		// We have a match. bail.
		if ( $ssheet === $child ) {
			return;
		}

		// Set my notice text.
		// Translators: The placeholder is for the child theme name.
		$notice = sprintf( __( 'Warning: You have selected the %s child theme but do not have that theme active.', 'gppro' ), esc_attr( $data['name'] ) );

		// Spit out the message.
		self::notice_markup_display( $notice, $child, 'error', true, true );
	}

	/**
	 * Display messages if export failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function export_notices() {

		// Bail if not doing an export.
		if ( empty( $_GET['export'] ) || empty( $_GET['reason'] ) || 'failure' !== sanitize_key( $_GET['export'] ) ) {  // WPCS: csrf ok.
			return;
		}

		// Our standard message.
		$notice = __( 'There was an error with your export. Please try again later.', 'gppro' );

		// No file provided.
		if ( 'nodata' === sanitize_key( $_GET['reason'] ) ) {  // WPCS: csrf ok.
			$text = __( 'No settings data has been saved. Please save your settings and try again.', 'gppro' );
		}

		// Spit out the message.
		self::notice_markup_display( $notice, '', 'error', false, true, true );
	}

	/**
	 * Display messages if import success or failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function import_notices() {

		// Make sure we have some sort of upload message.
		if ( empty( $_GET['uploaded'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['uploaded'] ) ), array( 'success', 'failure' ), true ) ) {  // WPCS: csrf ok.
			return;
		}

		// Check for failure.
		if ( 'failure' === sanitize_key( $_GET['uploaded'] ) ) {  // WPCS: csrf ok.

			// Set a default message.
			$notice = __( 'There was an error with your import. Please try again later.', 'gppro' );

			// Set our reason.
			$reason = ! empty( $_GET['reason'] ) ? sanitize_text_field( wp_unslash( $_GET['reason'] ) ) : '';  // WPCS: csrf ok.

			// Now our checks.
			switch ( $reason ) {
				case 'nofile':
					$notice = __( 'No file was provided. Please try again.', 'gppro' );
					break;

				case 'notjson':
					$notice = __( 'The import file was not in JSON format. Please try again.', 'gppro' );
					break;

				case 'badjson':
					$notice = __( 'The import file was not valid JSON. Please try again.', 'gppro' );
					break;

				case 'badparse':
					$notice = __( 'The import file could not be parsed. Please try again.', 'gppro' );
					break;

				case 'noclass':
					$notice = __( 'The required files for generating CSS are missing from the plugin. Please reinstall or contact support.', 'gppro' );
					break;

				case 'nocss':
					$notice = __( 'The import settings could not be applied. Please try again.', 'gppro' );
					break;

				// End all case breaks.
			}

			// Spit out the message.
			self::notice_markup_display( $notice, '', 'error', false, true, true );

			return;
		}

		// Checks passed, display the message.
		if ( 'success' === sanitize_key( $_GET['uploaded'] ) ) {  // WPCS: csrf ok.

			// Spit out the message.
			self::notice_markup_display( __( 'Your settings have been updated', 'gppro' ), '', 'updated', false, true, true );

			return;
		}
	}

	/**
	 * Display messages if manual support success or failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function support_notices() {

		// Make sure we have the support trigger.
		if ( empty( $_GET['action'] ) || 'support' !== sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {  // WPCS: csrf ok.
			return;
		}

		// Make sure we have some sort of message.
		if ( empty( $_GET['processed'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['processed'] ) ), array( 'success', 'failure' ), true ) ) {  // WPCS: csrf ok.
			return;
		}

		// Check for failure.
		if ( 'failure' === sanitize_key( $_GET['processed'] ) ) {  // WPCS: csrf ok.

			// Set a default message.
			$notice = __( 'There was an error with your support request. Please try again later.', 'gppro' );

			// Set our reason.
			$reason = ! empty( $_GET['errcode'] ) ? sanitize_text_field( wp_unslash( $_GET['errcode'] ) ) : '';  // WPCS: csrf ok.

			// Now our checks.
			switch ( $reason ) {
				case 'MISSING_NONCE':
					$notice = __( 'We\'re sorry, but the support request could not be sent. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				case 'MISSING_NAME':
					$notice = __( 'The required name field was blank. Please try again.', 'gppro' );
					break;

				case 'MISSING_EMAIL':
					$notice = __( 'The required email field was blank. Please try again.', 'gppro' );
					break;

				case 'INVALID_EMAIL':
					$notice = __( 'The email address provided was invalid. Please try again.', 'gppro' );
					break;

				case 'MISSING_TEXT':
					$notice = __( 'There was no explanation provided for your support issue. Please try again.', 'gppro' );
					break;

				case 'NO_DETAILS':
					$notice = __( 'We\'re sorry, but the support request could not be sent. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				case 'SEND_FAILED':
					$notice = __( 'We\'re sorry, but the support request could not be sent. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				// End all case breaks.
			}

			// Spit out the message.
			self::notice_markup_display( $notice, '', 'error', false, true, true );

			return;
		}

		// Checks passed, display the message.
		if ( 'success' === sanitize_key( $_GET['processed'] ) ) {  // WPCS: csrf ok.

			// Spit out the message.
			self::notice_markup_display( __( 'Success! Your request has been sent. You\'ll be hearing from us shortly. If you do not get notification, please email us at help@reaktivstudios.com.', 'gppro' ), '', 'updated', false, true, true );

			return;
		}
	}

	/**
	 * Display messages if manual support success or failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function license_notices() {

		// Make sure we have some sort of license process.
		if ( empty( $_GET['action'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['action'] ) ), array( 'activate', 'deactivate' ), true ) ) {  // WPCS: csrf ok.
			return;
		}

		// Make sure we have some sort of message.
		if ( empty( $_GET['processed'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['processed'] ) ), array( 'success', 'failure' ), true ) ) {  // WPCS: csrf ok.
			return;
		}

		// Check for failure.
		if ( 'failure' === sanitize_key( $_GET['processed'] ) ) {  // WPCS: csrf ok.

			// Set a default message.
			$notice = __( 'There was an error with your activation request. Please try again later.', 'gppro' );

			// Set our reason.
			$reason = ! empty( $_GET['errcode'] ) ? sanitize_text_field( wp_unslash( $_GET['errcode'] ) ) : ''; // WPCS: csrf ok.

			// Now our checks.
			switch ( $reason ) {
				case 'MISSING_NONCE':
					$notice = __( 'We\'re sorry, but the activation process could not be completed. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				case 'EMPTY_LICENSE':
					$notice = __( 'No license key has been provided.', 'gppro' );
					break;

				case 'NO_LICENSE':
					$notice = __( 'No license key has been previously stored.', 'gppro' );
					break;

				case 'NO_STATUS':
					$notice = __( 'This license key could not be verified.', 'gppro' );
					break;

				case 'BAD_STATUS':
					$notice = __( 'This license key returned an unknown status code.', 'gppro' );
					break;

				case 'LICENSE_FAIL':
					$notice = __( 'This license key is not valid.', 'gppro' );
					break;

				// End all case breaks.
			}

			// Spit out the message.
			self::notice_markup_display( $notice, '', 'error', false, true, true );

			return;
		}

		// Checks passed, display the message.
		if ( 'success' === sanitize_key( $_GET['processed'] ) ) { // WPCS: csrf ok.

			// Spit out the activation message.
			if ( 'activate' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) { // WPCS: csrf ok.
				self::notice_markup_display( __( 'This license key has been successfully activated.', 'gppro' ), '', 'updated', false, true, true );
			}

			// Spit out the deactivation message.
			if ( 'deactivate' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) { // WPCS: csrf ok.
				self::notice_markup_display( __( 'This license key has been successfully deactivated.', 'gppro' ), '', 'updated', false, true, true );
			}

			return;
		}
	}

	/**
	 * Build the HTML markup for the notice.
	 *
	 * @param  string  $notice   The notice text being displayed.
	 * @param  string  $key      The key to identify which alert it is tied to.
	 * @param  string  $type     Message type. can be "updated", "error", or "notice".
	 * @param  boolean $ignore   Whether to allow a user to ignore or not.
	 * @param  boolean $echo     Whether to echo or return it.
	 * @param  boolean $dismiss  Whether to add the dismissable flag.
	 *
	 * @return HTML              The HTML markup.
	 */
	public static function notice_markup_display( $notice = '', $key = '', $type = 'error', $ignore = true, $echo = false, $dismiss = false ) {

		// Bail with no text to display.
		if ( empty( $notice ) ) {
			return;
		}

		// Set the type.
		$type = ! empty( $type ) && in_array( $type, array( 'updated', 'error', 'warning', 'notice' ), true ) ? $type : 'error';

		// Set the class structure.
		$class = ! empty( $key ) ? 'gppro-admin-warning gppro-admin-warning-' . esc_attr( $key ) : 'gppro-admin-warning';

		// Check for the dismissable flag.
		$class .= ! empty( $dismiss ) ? ' is-dismissible' : '';

		// Set the empty.
		$build = '';

		// Build the message HTML.
		$build .= '<div id="message" class="notice ' . esc_attr( $type ) . ' notice-' . esc_attr( $type ) . ' fade below-h2 ' . esc_attr( $class ) . '"><p>';

			// The message itself.
			$build .= '<strong>' . esc_attr( $notice ) . '</strong>';

			// The dismissal.
		if ( ! empty( $ignore ) && ! empty( $key ) ) {
			$build .= '<span class="ignore" data-child="' . esc_attr( $key ) . '">' . __( 'Ignore this message', 'gppro' ) . '</span>';
		}

		// Close the markup.
		$build .= '</p></div>';

		// Echo the build if requested.
		if ( ! empty( $echo ) ) {
			echo wp_kses_post( $build );
		}

		// Return it.
		return $build;
	}

} // End class.


// Instantiate our class.
$GP_Pro_Notices = new GP_Pro_Notices();
$GP_Pro_Notices->init();
