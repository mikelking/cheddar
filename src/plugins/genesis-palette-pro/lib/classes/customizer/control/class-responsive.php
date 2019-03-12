<?php
/**
 * Registers control IDs for each responsive preview device.
 *
 * @package Design Palette Pro
 */

namespace DPP\Customizer\Control;

/**
 * Class Responsive
 */
class Responsive {
	/**
	 * The control IDs for all devices.
	 *
	 * @var array
	 */
	public $controls = array();

	/**
	 * Instance of Responsive.
	 *
	 * @var Responsive
	 */
	public static $instance;

	/**
	 * Gets an instance of Responsive
	 *
	 * @return Responsive
	 */
	public static function get_instance() {
		if ( empty( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Responsive constructor.
	 */
	public function __construct() {
		$this->setup();
	}

	/**
	 * Sets up the controls with device keys.
	 */
	public function setup() {
		global $wp_customize;

		if ( empty( $wp_customize ) ) {
			return;
		}

		foreach ( array_keys( $wp_customize->get_previewable_devices() ) as $device ) {
			$this->controls[ $device ] = array();
		}
	}

	/**
	 * Adds controls.
	 *
	 * @param string $id The setting/control ID.
	 */
	public function add( $id ) {
		if ( empty( $this->controls ) ) {
			$this->setup();
		}

		foreach ( array_keys( $this->controls ) as $device ) {
			$formatted_id                = 'desktop' === $device ? $id : sprintf( '%1$s-%2$s', $id, $device );
			$this->controls[ $device ][] = $formatted_id;
		}
	}

	/**
	 * Gets the controls array.
	 *
	 * @return array
	 */
	public function get() {
		return $this->controls;
	}

}
