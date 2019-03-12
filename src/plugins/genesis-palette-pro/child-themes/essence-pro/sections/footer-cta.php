<?php
/**
 * The front page array
 *
 * @package genesis-design-pro
 */

return array(
	'footer-cta-widget-setup'                => array(
		'title' => __( 'Area Setup', 'gppro' ),
		'data'  => array(
			'footer-cta-back'         => array(
				'label'    => __( 'Background Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.footer-cta',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
				'selector' => 'background-color',
			),
			'footer-cta-accent-color' => array(
				'label'    => __( 'Accent Line Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.footer-cta::before',
				'selector' => 'background-color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
		),
	),
	'footer-cta-setup'                       => array(
		'title' => __( 'Padding', 'gppro' ),
		'data'  => array(
			'footer-cta-padding-top'    => array(
				'label'    => __( 'Top', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-top',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
			'footer-cta-padding-bottom' => array(
				'label'    => __( 'Bottom', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-bottom',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
			'footer-cta-padding-left'   => array(
				'label'    => __( 'Left', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-left',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
			'footer-cta-padding-right'  => array(
				'label'    => __( 'Right', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-right',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
		),
	),

	'section-break-footer-cta-single-widget' => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Single Widgets', 'gppro' ),
		),
	),
	'footer-cta-widget-back-setup'           => array(
		'title' => 'Area Setup',
		'data'  => array(
			'footer-cta-widget-back'          => array(
				'label'    => __( 'Background Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.footer-cta .widget',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
				'selector' => 'background-color',
			),
			'footer-cta-widget-border-setup'  => array(
				'title' => __( 'Widget Border', 'gppro' ),
				'input' => 'divider',
				'style' => 'lines',
			),
			'footer-cta-widget-border-color'  => array(
				'label'    => __( 'Border Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.footer-cta .widget',
				'selector' => 'border-color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'footer-cta-widget-border-style'  => array(
				'label'    => __( 'Border Style', 'gppro' ),
				'input'    => 'borders',
				'target'   => '.footer-cta .widget',
				'selector' => 'border-style',
				'builder'  => 'GP_Pro_Builder::text_css',
				'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
			),
			'footer-cta-widget-border-width'  => array(
				'label'    => __( 'Border Width', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta .widget',
				'selector' => 'border-width',
				'builder'  => 'GP_Pro_Builder::px_css',
				'min'      => '0',
				'max'      => '10',
				'step'     => '1',
			),
			'footer-cta-widget-border-radius' => array(
				'label'    => __( 'Border Radius', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta .widget',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'border-radius',
				'min'      => '0',
				'max'      => '16',
				'step'     => '1',
			),
			'footer-cta-widget-box-shadow'    => array(
				'label'    => __( 'Box Shadow', 'gppro' ),
				'input'    => 'radio',
				'options'  => array(
					array(
						'label' => __( 'Keep', 'gppro' ),
						'value' => 'inherit',
					),
					array(
						'label' => __( 'Remove', 'gppro' ),
						'value' => 'none',
					),
				),
				'target'   => '.footer-cta .widget',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'box-shadow',
			),
		),
	),

	'footer-cta-widget-padding-setup'        => array(
		'title' => __( 'Widget Padding', 'gppro' ),
		'data'  => array(
			'footer-cta-widget-padding-top'    => array(
				'label'    => __( 'Top', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta .entry',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-top',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
			'footer-cta-widget-padding-bottom' => array(
				'label'    => __( 'Bottom', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta .entry',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-bottom',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
			'footer-cta-widget-padding-left'   => array(
				'label'    => __( 'Left', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta .entry',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-left',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
			'footer-cta-widget-padding-right'  => array(
				'label'    => __( 'Right', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.footer-cta .entry',
				'builder'  => 'GP_Pro_Builder::px_css',
				'selector' => 'padding-right',
				'min'      => '0',
				'max'      => '60',
				'step'     => '2',
			),
		),
	),

	'section-break-footer-cta-entry-title'   => array(
		'break' => array(
			'type'  => 'thin',
			'title' => __( 'Additional controls available via the eNews addon.', 'gppro' ),
		),
	),
);
