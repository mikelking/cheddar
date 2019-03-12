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
class Base {
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
	 * Holds customizer instance
	 *
	 * @var \WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	protected $customize;

	/**
	 * Base constructor.
	 */
	public function __construct() {
		$this->defaults = \GP_Pro_Helper::set_defaults();
		$this->sections = \GP_Pro_Setup::blocks();
		$this->settings = \GP_Pro_Sections::get_section_items();

		add_action( 'customize_register', array( $this, 'setup_customizer' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Setup the customizer and store the customizer instance.
	 *
	 * @param object $wp_customize Full WP_Customizer object.
	 */
	public function setup_customizer( $wp_customize ) {
		$this->customize = $wp_customize;

		$this->customize->add_panel(
			'dpp_panel', array(
				'title'    => __( 'Design Palette Pro', 'gppro' ),
				'priority' => 1,
			)
		);

		$this->add_sections();
	}

	/**
	 * Add the customizer sections.
	 */
	public function add_sections() {
		if ( empty( $this->sections ) ) {
			return; // nothing to do.
		}

		foreach ( $this->sections as $section ) {
			if ( empty( $this->settings[ $section['slug'] ] ) ) {
				continue; // there are no sections for this panel.
			}

			// phpcs:disable
			// @codeCoverageIgnoreStart
			// phpcs:enable
			if ( 'build_settings' === $section['slug'] ) {
				continue;
			}

			$title = isset( $section['tab'] ) ? $section['tab'] : '';
			$title = isset( $section['title'] ) ? $section['title'] : $title;

			$this->customize->add_section(
				esc_attr( $section['slug'] ), array(
					'title'       => wp_kses_post( $title ),
					'description' => isset( $section['intro'] ) ? wp_kses_post( $section['intro'] ) : '',
					'priority'    => 70,
					'panel'       => 'dpp_panel',
				)
			);
			// phpcs:disable
			// @codeCoverageIgnoreEnd
			// phpcs:enable
		}

		$this->add_settings();
	}

	/**
	 * Add the customizer settings.
	 */
	public function add_settings() {
		foreach ( $this->settings as $section => $data ) {
			if ( empty( $data ) ) {
				// phpcs:disable
				continue; // @codeCoverageIgnore
				// phpcs:enable
			}

			$count = 0;
			foreach ( $data as $header_id => $settings ) {
				$title = isset( $settings['title'] ) ? $settings['title'] : '';

				if ( ! empty( $title ) ) {
					$this->customize->add_setting( $header_id );
					$this->customize->add_control(
						new Control\Divider(
							$this->customize,
							$header_id, array(
								'label'   => $settings['title'],
								'section' => $section,
							)
						)
					);
				}

				if ( isset( $settings['break'] ) ) {
					$count++;

					$id = sprintf( '%1$s_break_%2$s', $section, $count );
					$this->customize->add_setting( $id );
					$this->customize->add_control(
						new Control\Notice(
							$this->customize,
							$id, array(
								'label'       => isset( $settings['break']['title'] ) ? $settings['break']['title'] : '',
								'description' => isset( $settings['break']['text'] ) ? $settings['break']['text'] : '',
								'section'     => $section,
							)
						)
					);
				}

				if ( empty( $settings['data'] ) ) {
					continue;
				}

				// phpcs:disable
				// @codeCoverageIgnoreStart
				// phpcs:enable
				foreach ( $settings['data'] as $id => $setting ) {
					new Setting( $id, $this->defaults, $this->customize );
					new Control\Base( $id, $setting, $section, $this->customize );
				}
				// phpcs:disable
				// @codeCoverageIgnoreEnd
				// phpcs:enable
			}
		}
	}

	/**
	 * Enqueues scripts for the customizer.
	 */
	public function enqueue_scripts() {
		$vars = \GP_Pro_Utilities::get_filename_vars();

		wp_enqueue_script( 'dpp-customize-control-script', plugins_url( 'lib/js/customizer-controls' . $vars['script'], GPP_FILE ), array( 'jquery' ), 0.2, true );
		wp_localize_script( 'dpp-customize-control-script', 'dppControlIDs', Control\Responsive::get_instance()->get() );
	}
}
