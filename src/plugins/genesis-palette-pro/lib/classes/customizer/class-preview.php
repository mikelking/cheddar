<?php
/**
 * Builds Preview Output
 *
 * @package genesis-design-pro
 */

namespace DPP\Customizer;

/**
 * Builds preview output.
 */
class Preview {

	/**
	 * Array of options used to build preview CSS.
	 *
	 * @var Data
	 */
	public $data = array();

	/**
	 * Preview constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'preview_styles' ), 99 );
	}

	/**
	 * Do preview output
	 */
	public function preview_styles() {
		$this->data = new Data();

		echo '<style type="text/css" id="dpp-customize-preview-css">';
		$this->do_sections();
		echo '</style>';
	}

	/**
	 * Setup the data for the build_css method.
	 */
	public function do_sections() {
		add_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_data' ) );

		echo \GP_Pro_Builder::build_css(); // WPCS: xss ok.

		remove_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_data' ) );
		add_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_tablet_data' ) );

		echo '@media only screen and (max-width: 720px) {';
		echo \GP_Pro_Builder::build_css(); // WPCS: xss ok.
		echo '}';

		remove_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_tablet_data' ) );
		add_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_mobile_data' ) );

		echo '@media only screen and (max-width: 320px) {';
		echo \GP_Pro_Builder::build_css(); // WPCS: xss ok.
		echo '}';

		remove_filter( 'gppro_css_builder_data', array( $this->data, 'gppro_css_builder_mobile_data' ) );

		echo $this->data->get_custom(); // WPCS: xss ok.
	}

}
