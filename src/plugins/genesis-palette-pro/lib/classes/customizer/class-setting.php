<?php
/**
 * The customizer base file
 *
 * @package genesis-design-pro
 */

namespace DPP\Customizer;

/**
 * Adds the DPP settings to the customizer.
 */
class Setting {

	/**
	 * ID for the setting.
	 *
	 * @var string
	 */
	public $id = '';
	/**
	 * The defaults array.
	 *
	 * @var array
	 */
	public $defaults = array();
	/**
	 * The default value.
	 *
	 * @var string
	 */
	public $default = '';

	/**
	 * Adds the setting for provided key with default if available.
	 *
	 * @param string                $id        The setting key.
	 * @param array                 $defaults  The defaults array.
	 * @param \WP_Customize_Manager $customize The WP_Customize object.
	 */
	public function __construct( $id, $defaults, $customize ) {
		$this->id       = $id;
		$this->defaults = $defaults;

		$this->set_default();

		$customize->add_setting( $id, array( 'default' => $this->default ) );

		// Do not output old mobile settings.
		if ( false !== strpos( $id, 'mobile' ) || 'body-color-back-thin' === $id ) {
			return;
		}

		Control\Responsive::get_instance()->add( $id );

		foreach ( array_keys( $customize->get_previewable_devices() ) as $device ) {
			if ( 'desktop' === $device ) {
				continue;
			}

			$customize->add_setting( $id . '-' . $device );
		}
	}

	/**
	 * Sets the $default property.
	 */
	public function set_default() {
		$this->default = isset( $this->defaults[ $this->id ] ) ? $this->defaults[ $this->id ] : '';
	}

}
