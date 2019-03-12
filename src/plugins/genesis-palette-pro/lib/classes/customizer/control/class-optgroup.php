<?php
/**
 * Custom control for select with optgroup support, extend the WP customizer
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
 * Class Optgroup
 */
class Optgroup extends \WP_Customize_Control {
	/**
	 * The HTML type attr.
	 *
	 * @var string
	 */
	public $type = 'select';

	/**
	 * Render the select output.
	 */
	public function render_content() {

		// Output.
		if ( ! empty( $this->choices ) && is_array( $this->choices ) ) :

			?>

			<label>
				<span class="customize-control-title"><?php echo wp_kses_post( $this->label ); ?></span>
				<?php if ( $this->description ) : ?>
					<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
				<?php endif; ?>

				<select name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?>>
					<?php $this->do_options( $this->choices ); ?>
				</select>
			</label>

			<?php
		endif;
	} // /render_content

	/**
	 * Outputs the option or optgroup with options.
	 *
	 * @param array $options The options to output.
	 */
	public function do_options( $options ) {
		foreach ( $options as $value => $name ) {
			if ( is_array( $name ) ) {
				printf( '<optgroup label="%s">', esc_attr( ucfirst( $value ) ) );
				$this->do_options( $name );
				echo '</optgroup>';
			} else {
				printf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $value ),
					selected( $this->value(), $value, false ),
					esc_html( $name )
				);
			}
		}
	}

}
