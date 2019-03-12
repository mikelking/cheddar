<?php
/**
 * Custom control for arbitarty text notice, extend the WP customizer
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
 * Class Notice
 */
class Notice extends \WP_Customize_Control {

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		printf(
			'<div class="dpp-notice" style="padding:10px; background: #FFF;">%1$s%2$s</div>',
			empty( $this->label ) ? '' : sprintf( '<h4 class="dpp-notice-title" style="margin: 0 0 0">%s</h4>', wp_kses_post( $this->label ) ),
			empty( $this->description ) ? '' : sprintf( '<p class="margin: 10px 0 0">%s</p>', wp_kses_post( $this->description ) )
		); // WPCS: xss ok.
	}

}
