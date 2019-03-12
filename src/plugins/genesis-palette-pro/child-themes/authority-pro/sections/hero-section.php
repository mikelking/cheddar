<?php
/**
 * Hero Section array
 *
 * @package genesis-design-pro
 */

return array(
	// Add background color.
	'hero-section-background-setup'    => array(
		'title' => '',
		'data'  => array(
			'hero-section-background-color'      => array(
				'label'         => __( 'Background', 'gppro' ),
				'input'         => 'color',
				'target'        => '.hero-section-column.right::before',
				'selector'      => 'background',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'hero-section-line-background-color' => array(
				'label'         => __( 'Accent Line', 'gppro' ),
				'input'         => 'color',
				'target'        => '.hero-section-column.right::after',
				'selector'      => 'background',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// Add hero title settings.
	'hero-title-type-setup'            => array(
		'title' => __( 'Hero Title', 'gppro' ),
		'data'  => array(
			'hero-title-color'  => array(
				'label'    => __( 'Font Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.hero-title',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'hero-title-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.hero-title',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'hero-title-size'   => array(
				'label'    => __( 'Font Size', 'gppro' ),
				'input'    => 'font-size',
				'scale'    => 'text',
				'target'   => '.hero-title',
				'selector' => 'font-size',
				'tip'      => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::px_css',
			),
			'hero-title-weight' => array(
				'label'    => __( 'Font Weight', 'gppro' ),
				'input'    => 'font-weight',
				'target'   => '.hero-title',
				'selector' => 'font-weight',
				'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::number_css',
			),
			'hero-title-style'  => array(
				'label'    => __( 'Font Style', 'gppro' ),
				'input'    => 'radio',
				'options'  => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'   => '.hero-title',
				'selector' => 'font-style',
				'builder'  => 'GP_Pro_Builder::text_css',
			),
			'hero-title-align'  => array(
				'label'    => __( 'Align', 'gppro' ),
				'input'    => 'text-align',
				'target'   => '.hero-title',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-align',
			),
		),
	),

	// Add hero intro paragraph settings.
	'hero-intro-type-setup'            => array(
		'title' => __( 'Hero Intro Paragraph', 'gppro' ),
		'data'  => array(
			'hero-intro-color'  => array(
				'label'    => __( 'Font Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.hero-description',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'hero-intro-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.hero-description',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'hero-intro-size'   => array(
				'label'    => __( 'Font Size', 'gppro' ),
				'input'    => 'font-size',
				'scale'    => 'text',
				'target'   => '.hero-description',
				'selector' => 'font-size',
				'tip'      => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::px_css',
			),
			'hero-intro-weight' => array(
				'label'    => __( 'Font Weight', 'gppro' ),
				'input'    => 'font-weight',
				'target'   => '.hero-description',
				'selector' => 'font-weight',
				'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::number_css',
			),
			'hero-intro-style'  => array(
				'label'    => __( 'Font Style', 'gppro' ),
				'input'    => 'radio',
				'options'  => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'   => '.hero-title',
				'selector' => 'font-style',
				'builder'  => 'GP_Pro_Builder::text_css',
			),
			'hero-intro-align'  => array(
				'label'    => __( 'Align', 'gppro' ),
				'input'    => 'text-align',
				'target'   => '.hero-description',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-align',
			),
		),
	),

	// Add hero portrait caption settings.
	'hero-portrait-caption-type-setup' => array(
		'title' => __( 'Hero Portrait Caption', 'gppro' ),
		'data'  => array(
			'hero-portrait-caption-color'  => array(
				'label'    => __( 'Font Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.hero-portrait-caption',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'hero-portrait-caption-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.hero-portrait-caption',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'hero-portrait-caption-size'   => array(
				'label'    => __( 'Font Size', 'gppro' ),
				'input'    => 'font-size',
				'scale'    => 'text',
				'target'   => '.hero-portrait-caption',
				'selector' => 'font-size',
				'tip'      => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::px_css',
			),
			'hero-portrait-caption-weight' => array(
				'label'    => __( 'Font Weight', 'gppro' ),
				'input'    => 'font-weight',
				'target'   => '.hero-portrait-caption',
				'selector' => 'font-weight',
				'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::number_css',
			),
			'hero-portrait-caption-style'  => array(
				'label'    => __( 'Font Style', 'gppro' ),
				'input'    => 'radio',
				'options'  => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'   => '.hero-portrait-caption',
				'selector' => 'font-style',
				'builder'  => 'GP_Pro_Builder::text_css',
			),
		),
	),
	// Hero widget notice.
	'section-break-hero-widget-notice' => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Hero Section Widget', 'gppro' ),
			'text'  => __( 'The Authority Pro demo uses Genesis eNews Extended in the Hero Widget section. Please install the Design Palette Pro eNews Widget ( under Add-ons tab in the Design Palette Pro settings). After installation, settings to customize the Genesis eNews form will be located in Design Palette Pro under the Genesis Widgets menu.', 'gppro' ),
		),
	),
);
