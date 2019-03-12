<?php
/**
 * Genesis Design Palette Pro - Admin Settings Module
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

use DPP\Customizer\Upgrade;

/**
 * Add options and notices for enabling customizer.
 */
class Customizer extends Base {

	/**
	 * Options for the activation state.
	 *
	 * @var array
	 */
	public $activation_opts = array();

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
		$this->settings = array(
			'gppro-customizer-beta' => array(
				'label'       => __( 'Enable Customizer (Beta)', 'gppro' ),
				'description' => __( 'If enabled, the "Design" tab will be disabled and design controls will be added to the customizer.', 'gppro' ),
				'default'     => '',
				'type'        => 'checkbox',
				'section'     => 'settings',
			),
		);
	}

	/**
	 * Redirects to the correct settings tab after activating.
	 */
	public function redirect_setting_tab() {
		$url = admin_url( 'admin.php?page=genesis-palette-pro', 0 );
		wp_safe_redirect( esc_url( $url ) );
		exit;
	}

	/**
	 * Add activation/upgrade notice.
	 */
	public function admin_notices() {
		if ( empty( $this->activation_opts['upgrade_notice'] ) ) {
			$message                                 = __( 'Thanks for trying the Customizer Beta. Your options have been upgraded and the customizer is ready for use. Click the "Design" tab to be taken directly to the new Design Palette Pro customizer panel.', 'gppro' );
			$this->activation_opts['upgrade_notice'] = 1;
		} else {
			$message = sprintf(
				// Translators: the placeholders are for an HTML link.
				__( 'Thanks for trying the Customizer Beta. Since the beta feature was deactivated and reactivated your previous settings were not converted. The Customizer options are just like you left them. Click the "Design" tab to be taken directly to the new Design Palette Pro customizer panel. If you wish to upgrade your current settings to replace the customizer settings, please click %1$sthis link%2$s.', 'gppro' ),
				sprintf( '<a href="%s">', wp_nonce_url( add_query_arg( 'dpp_customizer_upgrade', uniqid(), \GP_Pro_Helper::get_settings_url() ), __NAMESPACE__, 'dpp_upgrade_nonce' ) ),
				'</a>'
			);
		}
		$this->activation_opts['notice'] = 1;

		update_option( 'gppro-customizer-beta-activation', $this->activation_opts );
		printf( '<div class="updated"><p>%s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Change the tabs around for the customizer.
	 *
	 * @param array $tabs Current tabs.
	 *
	 * @return array
	 */
	public function tabs( $tabs ) {
		if ( empty( $tabs['settings'] ) ) {
			return $tabs;
		}
		$tabs['design']  = $tabs['default'];
		$tabs['default'] = $tabs['settings'];

		unset( $tabs['settings'] );

		$query['autofocus[panel]'] = 'dpp_panel';
		$link                      = add_query_arg( $query, admin_url( 'customize.php' ) );
		$tabs['design']['href']    = $link;

		return $tabs;
	}

	/**
	 * Change the tabs around for the customizer.
	 *
	 * @param array $sections Current sections.
	 *
	 * @return array
	 */
	public function sections( $sections ) {
		if ( empty( $sections['settings'] ) ) {
			return $sections;
		}
		$sections['design']  = $sections['default'];
		$sections['default'] = $sections['settings'];

		unset( $sections['settings'] );

		return $sections;
	}

	/**
	 * Change the tabs around for the customizer.
	 *
	 * @param array $settings Current tabs.
	 *
	 * @return array
	 */
	public function settings( $settings ) {
		foreach ( $settings as $key => $setting ) {
			if ( isset( $setting['section'] ) && 'settings' === $setting['section'] ) {
				$settings[ $key ]['section'] = 'default';
			}
		}

		return $settings;
	}

	/**
	 * Upgrades the settings to the customizer.
	 *
	 * @param string $rand Optional random string to limit upgrade.
	 */
	public function do_upgrade( $rand = '' ) {
		new Upgrade( $rand );
	}

	/**
	 * Initialize all the things.
	 */
	public function init() {
		$this->activation_opts = (array) get_option( 'gppro-customizer-beta-activation' );

		if ( get_option( 'gppro-customizer-beta' ) ) {
			if ( isset( $_GET['current-tab'] ) && 'genesis-palette-pro-settings' === $_GET['current-tab'] ) { // WPCS: csrf ok.
				$this->redirect_setting_tab();
			}

			if ( empty( $this->activation_opts ) ) {
				$this->do_upgrade( 'first-time-activation' );
			}

			if ( empty( $this->activation_opts['notice'] ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			}

			$gppro = get_option( 'gppro' );
			if ( empty( $gppro ) ) {
				add_filter( 'dpp_settings_tabs', array( $this, 'tabs' ), 999 );
			}
			add_filter( 'dpp_settings_sections', array( $this, 'sections' ), 999 );
			add_filter( 'dpp_settings', array( $this, 'settings' ), 999 );
		} elseif ( isset( $this->activation_opts['notice'] ) ) {
			unset( $this->activation_opts['notice'] );
			update_option( 'gppro-customizer-beta-activation', $this->activation_opts );
		}

		if ( isset( $_GET['dpp_customizer_upgrade'] ) && isset( $_GET['dpp_upgrade_nonce'] ) && wp_verify_nonce( $_GET['dpp_upgrade_nonce'], __NAMESPACE__ ) ) {
			$this->do_upgrade( $_GET['dpp_customizer_upgrade'] );
		}
	}
}
