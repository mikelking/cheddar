<?php
/**
 * Creates Customizer Controls.
 *
 * @package genesis-design-pro
 */

namespace DPP\Customizer\Control;

/**
 * Generates the customizer controls.
 */
class Base {

	/**
	 * Holds customizer instance
	 *
	 * @var \WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	protected $customize;

	/**
	 * The setting ID.
	 *
	 * @var string
	 */
	public $id = '';

	/**
	 * The settings data.
	 *
	 * @var array
	 */
	public $setting = array();

	/**
	 * The section ID.
	 *
	 * @var string
	 */
	public $section = '';

	/**
	 * Adds the customizer controls if available.
	 *
	 * @param string                $id        The setting ID.
	 * @param array                 $setting   The settings data.
	 * @param string                $section   The section the control belongs in.
	 * @param \WP_Customize_Manager $customize The WP_Customize object.
	 */
	public function __construct( $id, $setting, $section, $customize ) {
		if ( empty( $setting['input'] ) ) {
			// phpcs:disable
			return; // @codeCoverageIgnore
			// phpcs:enable
		}

		$exclude_subs = array(
			__( 'mobile', 'gppro' ),
		);

		if ( isset( $setting['sub'] ) && in_array( strtolower( $setting['sub'] ), $exclude_subs, true ) ) {
			return;
		}

		$this->id        = $id;
		$this->setting   = $setting;
		$this->section   = $section;
		$this->customize = $customize;

		$this->clean_description();
		$this->add_control();
		$this->add_responsive_controls();
	}

	/**
	 * Adds responsive controls.
	 */
	public function add_responsive_controls() {
		$id = $this->id;

		foreach ( array_keys( $this->customize->get_previewable_devices() ) as $device ) {
			if ( 'desktop' === $device ) {
				continue;
			}

			$this->id = $id . '-' . $device;
			$this->add_control();
		}
	}

	/**
	 * Calls the correct add control method for the setting.
	 */
	public function add_control() {
		switch ( $this->setting['input'] ) {
			case 'color':
				$this->add_control_color();
				break;
			case 'font-stack':
				$this->add_control_font_stack();
				break;
			case 'font-size':
				$this->add_control_font_size();
				break;
			case 'font-weight':
				$this->add_control_font_weight();
				break;
			case 'borders':
				$this->add_control_borders();
				break;
			case 'radio':
				$this->add_control_radio();
				break;
			case 'favicon':
				break;
			case 'spacing':
				$this->add_control_spacing();
				break;
			case 'text-transform':
				$this->add_control_text_transform();
				break;
			case 'text-align':
				$this->add_control_text_align();
				break;
			case 'text-decoration':
				$this->add_control_text_decoration();
				break;
			case 'css-position':
				$this->add_control_css_position();
				break;
			case 'custom':
				$this->add_custom_control();
				break;
		}
	}

	/**
	 * Handles custom control logic.
	 */
	public function add_custom_control() {
		$control = isset( $this->setting['callback'][1] ) ? $this->setting['callback'][1] : '';

		switch ( $control ) {
			case 'freeform_css_input':
				$this->add_control_custom_css();
				break;
			default:
				$this->add_control_textarea();
		}
	}
	/**
	 * Adds custom CSS customizer control.
	 */
	public function add_control_custom_css() {
		$this->customize->add_control(
			// phpcs:disable
			// @codeCoverageIgnoreStart
			// phpcs:enable
			new \WP_Customize_Code_Editor_Control(
				$this->customize,
				$this->id,
				array(
					'label'       => $this->get_label(),
					'description' => isset( $this->setting['desc'] ) ? $this->setting['desc'] : '',
					'section'     => $this->section,
					'settings'    => $this->id,
					'code_type'   => 'text/css',
				)
			)
			// phpcs:disable
			// @codeCoverageIgnoreEnd
			// phpcs:enable
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_color() {
		$this->customize->add_control(
			new \WP_Customize_Color_Control(
				$this->customize,
				$this->id,
				array(
					'label'       => $this->get_label(),
					'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
					'section'     => $this->section,
					'settings'    => $this->id,
				)
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_font_stack() {
		$this->customize->add_control(
			new Optgroup(
				$this->customize,
				$this->id,
				array(
					'label'       => $this->get_label(),
					'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
					'section'     => $this->section,
					'settings'    => $this->id,
					'choices'     => $this->get_font_stack_choices(),
				)
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_font_size() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'number',
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_font_weight() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'select',
				'choices'     => \GP_Pro_Helper::font_weights(),
			)
		);
	}

	/**
	 * Adds select box for border select control.
	 */
	public function add_control_borders() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'select',
				'choices'     => \GP_Pro_Helper::css_borders(),
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_radio() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'radio',
				'choices'     => $this->get_choices_from_options(),
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_spacing() {
		$this->customize->add_control(
			new Range(
				$this->customize, $this->id, array(
					'label'       => $this->get_label(),
					'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
					'section'     => $this->section,
					'input_attrs' => array(
						'min'  => isset( $this->setting['min'] ) ? $this->setting['min'] : 0,
						'max'  => isset( $this->setting['max'] ) ? $this->setting['max'] : 60,
						'step' => isset( $this->setting['step'] ) ? $this->setting['step'] : 1,
					),
					'suffix'      => $this->get_range_suffix(),
				)
			)
		);
	}

	/**
	 * Gets the suffix based on the builder.
	 *
	 * @return string
	 */
	public function get_range_suffix() {
		$suffix = '';
		switch ( str_replace( array( 'GP_Pro_Builder::', '_css' ), '', $this->setting['builder'] ) ) {
			case 'pct':
				$suffix = '%';
				break;
			case 'px':
				$suffix = 'px';
				break;
		}

		return $suffix;
	}

	/**
	 * Adds color customizer control.
	 */
	public function add_control_text_transform() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'select',
				'choices'     => \GP_Pro_Helper::text_transforms(),
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_text_align() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'select',
				'choices'     => \GP_Pro_Helper::text_alignments(),
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_text_decoration() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'select',
				'choices'     => \GP_Pro_Helper::text_decorations(),
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_css_position() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'select',
				'choices'     => \GP_Pro_Helper::css_positions(),
			)
		);
	}
	/**
	 * Adds color customizer control.
	 */
	public function add_control_textarea() {
		$this->customize->add_control(
			$this->id,
			array(
				'label'       => $this->get_label(),
				'description' => isset( $this->setting['tip'] ) ? $this->setting['tip'] : '',
				'section'     => $this->section,
				'settings'    => $this->id,
				'type'        => 'textarea',
			)
		);
	}

	/**
	 * Gets the label.
	 *
	 * @return string
	 */
	public function get_label() {
		if ( empty( $this->setting['label'] ) ) {
			return '';
		}

		$exclude_subs = array(
			__( 'desktop', 'gppro' ),
			__( 'mobile', 'gppro' ),
		);

		if ( empty( $this->setting['sub'] ) || in_array( strtolower( $this->setting['sub'] ), $exclude_subs, true ) ) {
			return $this->setting['label'];
		}

		return sprintf( '%1$s (%2$s)', $this->setting['label'], $this->setting['sub'] );
	}

	/**
	 * Gets formated font stack choices.
	 */
	public function get_font_stack_choices() {
		$stacks = \GP_Pro_Helper::stacks();

		$stack_choices = array(
			'' => __( 'Select Font', 'gppro' ),
		);

		foreach ( $stacks as $type => $fonts ) {
			$stack_choices[ $type ] = array();

			foreach ( $fonts as $font_key => $font_args ) {
				$stack_choices[ $type ][ $font_key ] = $font_args['label'];
			}
		}

		return $stack_choices;
	}

	/**
	 * Gets an array built from the settings options that will work with customizer.
	 *
	 * @return mixed|array|bool
	 */
	public function get_choices_from_options() {
		if ( empty( $this->setting['options'] ) ) {
			return false;
		}
		$choices = array();

		foreach ( $this->setting['options'] as $option ) {
			$choices[ esc_attr( $option['value'] ) ] = wp_kses_post( $option['label'] );
		}

		return $choices;
	}

	/**
	 * Changes the tip/description for certain descriptions.
	 */
	public function clean_description() {
		$exclude_descriptions = array(
			__( 'the live preview may not reflect the responsive css properly.', 'gppro' ),
		);

		if ( isset( $this->setting['tip'] ) && in_array( strtolower( $this->setting['tip'] ), $exclude_descriptions, true ) ) {
			$this->setting['tip'] = '';
		}
	}

}
