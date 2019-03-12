<?php
/**
 * Builds Style Data.
 *
 * @package genesis-design-pro
 */

namespace DPP\Customizer;

/**
 * Builds the style data.
 */
class Data {
	/**
	 * The default values.
	 *
	 * @var array|mixed|void
	 */
	public $defaults = array();

	/**
	 * The DPP sections and associated data.
	 *
	 * @var array
	 */
	public $sections = array();

	/**
	 * The DPP settings with associated data.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Array of options used to build preview CSS.
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * Custom CSS.
	 *
	 * @var array
	 */
	public $custom = array();

	/**
	 * Preview constructor.
	 */
	public function __construct() {
		$this->defaults = \GP_Pro_Helper::set_defaults();
		$this->sections = \GP_Pro_Setup::blocks();
		$this->settings = \GP_Pro_Sections::get_section_items();
		$this->build_data();
		$this->save_data();
	}

	/**
	 * Setup the data for the build_css method.
	 */
	public function build_data() {
		foreach ( $this->settings as $section => $data ) {
			if ( empty( $data ) ) {
				// phpcs:disable
				continue; // @codeCoverageIgnore
				// phpcs:enable
			}

			foreach ( $data as $header_id => $settings ) {
				if ( empty( $settings['data'] ) ) {
					continue;
				}
				foreach ( $settings['data'] as $id => $setting ) {
					$default = isset( $this->defaults[ $id ] ) ? $this->defaults[ $id ] : '';
					if ( isset( $setting['input'] ) && 'custom' === $setting['input'] && isset( $setting['callback'][1] ) && 'freeform_css_input' === $setting['callback'][1] ) {
						$this->custom[ $id ] = get_theme_mod( $id, $default );
					} else {
						$this->data['desktop'][ $id ] = get_theme_mod( $id, $default );
						$this->data['tablet'][ $id ]  = get_theme_mod( $id . '-tablet', $default );
						$this->data['mobile'][ $id ]  = get_theme_mod( $id . '-mobile', $default );
					}
				}
			}
		}
	}

	/**
	 * Saves the customizer data to an option.
	 */
	public function save_data() {
		update_option( 'gppro-customizer-data', $this->data );
	}

	/**
	 * Gets the data.
	 *
	 * @param string $type The type of data to retrieve.
	 * @return array
	 */
	public function get_data( $type = 'desktop' ) {
		return isset( $this->data[ $type ] ) ? $this->data[ $type ] : array();
	}

	/**
	 * Build Freeform CSS for Customizer.
	 *
	 * @return string $custom_css  The new CSS string.
	 */
	public function get_custom() {
		$custom_css = array();
		$global     = '';

		foreach ( $this->custom as $id => $css ) {
			if ( empty( $css ) ) {
				// phpcs:disable
				continue; // @codeCoverageIgnore
				// phpcs:enable
			}

			$media_open  = '';
			$media_close = '}';

			switch ( str_replace( 'freeform-css-', '', $id ) ) {
				case 'global':
					$global = $css;
					continue( 2 );
				case 'mobile':
					$media_open = '@media only screen and (max-width: 480px) {';
					break;
				case 'tablet':
					$media_open = '@media only screen and (max-width: 768px) {';
					break;
				case 'desktop':
					$media_open = '@media only screen and (min-width: 1024px) {';
					break;
			}

			$custom_css[] = $media_open . $css . $media_close;
		}

		return $global . implode( '', array_reverse( $custom_css ) );
	}

	/**
	 * Replaces the DPP settings with theme mod.
	 *
	 * @param array $data The original settings.
	 *
	 * @return array
	 */
	public function gppro_css_builder_data( $data ) {
		return empty( $this->get_data() ) ? $data : $this->get_data();
	}

	/**
	 * Replaces the DPP settings with theme mod.
	 *
	 * @param array $data The original settings.
	 *
	 * @return array
	 */
	public function gppro_css_builder_tablet_data( $data ) {
		return empty( $this->get_data( 'tablet' ) ) ? $data : $this->get_data( 'tablet' );
	}

	/**
	 * Replaces the DPP settings with theme mod.
	 *
	 * @param array $data The original settings.
	 *
	 * @return array
	 */
	public function gppro_css_builder_mobile_data( $data ) {
		return empty( $this->get_data( 'mobile' ) ) ? $data : $this->get_data( 'mobile' );
	}
}
