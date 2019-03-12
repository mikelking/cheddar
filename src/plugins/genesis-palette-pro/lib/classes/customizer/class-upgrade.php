<?php
/**
 * The customizer upgrade file
 *
 * @package genesis-design-pro
 */

namespace DPP\Customizer;

/**
 * Upgrades DPP options for the customizer.
 *
 * @codeCoverageIgnore
 */
class Upgrade {
	/**
	 * The DPP settings with associated data.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Upgrade constructor.
	 *
	 * @param string $once A random string to limit upgrade in case the URL is triggered more than once.
	 */
	public function __construct( $once = '' ) {
		if ( ! empty( $once ) ) {
			$done_once = (array) get_option( 'dpp_upgrade_once' );

			if ( in_array( $once, $done_once, true ) ) {
				return;
			}

			$done_once[] = $once;

			update_option( 'dpp_upgrade_once', $done_once );
		}
		require_once GPP_DIR . 'lib/helper.php';

		$this->settings = \GP_Pro_Helper::get_single_option( 'gppro-settings', false, array() );

		// Run pre-build filter on the data itself.
		$this->settings = apply_filters( 'gppro_css_builder_data', $this->settings );

		$this->get_free_form_settings();

		$this->add_theme_mods();

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Add theme mods for each setting.
	 */
	public function add_theme_mods() {
		foreach ( $this->settings as $key => $value ) {
			if ( 'body-color-back-thin' === $key ) {
				$key = 'body-color-back-main-mobile';
			}

			set_theme_mod( $key, $value );
		}
	}

	/**
	 * Gets the freeform settings.
	 */
	public function get_free_form_settings() {
		$free_form_opts = array(
			'global'  => '',
			'mobile'  => '',
			'tablet'  => '',
			'desktop' => '',
		);

		$custom = get_option( 'gppro-custom-css' );

		foreach ( $free_form_opts as $opt ) {
			$key                    = 'freeform-css-' . $opt;
			$this->settings[ $key ] = empty( $custom[ $opt ] ) ? '' : $custom[ $opt ];
		}
	}

	/**
	 * Add activation/upgrade notice.
	 */
	public function admin_notices() {
		$message = __( 'Design settings successfully upgraded for the customizer.', 'gppro' );
		printf( '<div class="updated"><p>%s</p></div>', esc_html( $message ) );
	}
}
