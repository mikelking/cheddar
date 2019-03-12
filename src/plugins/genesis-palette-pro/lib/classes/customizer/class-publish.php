<?php
/**
 * Publishes customizer options.
 *
 * @package genesis-design-pro
 */

namespace DPP\Customizer;

/**
 * Class Publish
 */
class Publish {

	/**
	 * Array of options used to build CSS.
	 *
	 * @var Data
	 */
	public $data = array();

	/**
	 * Preview constructor.
	 */
	public function __construct() {
		$this->data = new Data();

		$this->save_css();
	}

	/**
	 * Add filters, build css, and generate file opts.
	 */
	public function save_css() {
		add_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_data' ) );

		$build  = \GP_Pro_Builder::build_css();
		$build .= $this->data->get_custom();

		remove_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_data' ) );
		add_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_tablet_data' ) );

		$build .= '@media only screen and (max-width: 720px) {';
		$build .= \GP_Pro_Builder::build_css(); // WPCS: xss ok.
		$build .= '}';

		remove_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_tablet_data' ) );
		add_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_mobile_data' ) );

		$build .= '@media only screen and (max-width: 320px) {';
		$build .= \GP_Pro_Builder::build_css(); // WPCS: xss ok.
		$build .= '}';

		remove_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_mobile_data' ) );

		\Genesis_Palette_Pro::generate_file( $build );

		remove_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_data' ) );
	}
}
