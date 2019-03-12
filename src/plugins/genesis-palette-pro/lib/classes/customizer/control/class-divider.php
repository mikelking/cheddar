<?php
/**
 * Custom control for arbitarty text and a HR, extend the WP customizer
 *
 * @package Design Palette Pro
 */

namespace DPP\Customizer\Control;

if ( ! class_exists( '\WP_Customize_Control' ) ) {
	// phpcs:disable
	return; // @codeCoverageIgnore
	// phpcs:enable
}

/**
 * Class Divider
 */
class Divider extends \WP_Customize_Control {

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		printf(
			'<span class="customize-control-title">%1$s</span><hr />',
			wp_kses_post( $this->label )
		);
	}

}
