<?php
/**
 * Custom Range Control.
 *
 * @package genesis-design-pro
 */

namespace DPP\Customizer\Control;

/**
 * Custom range with value display customize control class.
 *
 * @since  1.0.0
 * @access public
 */
class Range extends \WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'dpp-range';

	/**
	 * Suffix for the range control.
	 *
	 * @var string
	 */
	public $suffix = '';

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		// Get our file variables.
		$vars = \GP_Pro_Utilities::get_filename_vars();

		wp_enqueue_script( 'dpp-customize-control-script', plugins_url( 'lib/js/customizer-controls' . $vars['script'], GPP_FILE ), array( 'jquery' ), 0.2, true );
		wp_enqueue_style( 'dpp-customize-control-style', plugins_url( 'lib/css/customize-controls' . $vars['style'], GPP_FILE ) );
	}

	/**
	 * Displays the control content.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function render_content() {
		?>
		<label class="dpp-range-input">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo wp_kses_post( $this->label ); ?></span>
			<?php endif; ?>
			<input data-input-type="range" id="<?php echo esc_attr( $this->id ); ?>" type="range" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			<output class="dpp-range-output" for="<?php echo esc_attr( $this->id ); ?>">
				<span><?php echo esc_html( $this->value() ); ?></span><?php echo esc_html( $this->suffix ); ?>
			</output>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
		</label>
		<?php
	}
}
