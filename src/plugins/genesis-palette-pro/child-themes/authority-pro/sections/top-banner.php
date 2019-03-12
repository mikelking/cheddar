<?php
/**
 * Top Banner array
 *
 * @package genesis-design-pro
 */

return array(
	// Add background color.
	'top-banner-background-setup' => array(
		'title' => '',
		'data'  => array(
			'top-banner-background-color' => array(
				'label'    => __( 'Background', 'gppro' ),
				'input'    => 'color',
				'target'   => '.authority-top-banner',
				'selector' => 'background',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'top-banner-close-icon-color' => array(
				'label'    => __( 'X Icon', 'gppro' ),
				'input'    => 'color',
				'target'   => '#authority-top-banner-close',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
		),
	),

	// Add padding settings.
	'top-banner-spacing'          => array(
		'title' => __( 'Padding', 'gppro' ),
		'data'  => array(
			'top-banner-padding-top'    => array(
				'label'    => __( 'Top', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.authority-top-banner',
				'selector' => 'padding-top',
				'builder'  => 'GP_Pro_Builder::px_css',
				'min'      => '0',
				'max'      => '40',
				'step'     => '2',
			),
			'top-banner-padding-left'   => array(
				'label'    => __( 'Left', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.authority-top-banner',
				'selector' => 'padding-left',
				'builder'  => 'GP_Pro_Builder::px_css',
				'min'      => '0',
				'max'      => '80',
				'step'     => '2',
			),
			'top-banner-padding-right'  => array(
				'label'    => __( 'Right', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.authority-top-banner',
				'selector' => 'padding-right',
				'builder'  => 'GP_Pro_Builder::px_css',
				'min'      => '0',
				'max'      => '80',
				'step'     => '2',
			),
			'top-banner-padding-bottom' => array(
				'label'    => __( 'Bottom', 'gppro' ),
				'input'    => 'spacing',
				'target'   => '.authority-top-banner',
				'selector' => 'padding-bottom',
				'builder'  => 'GP_Pro_Builder::px_css',
				'min'      => '0',
				'max'      => '40',
				'step'     => '2',
			),
		),
	),

	// Add typography settings.
	'top-banner-text-setup'       => array(
		'title' => __( 'Typography', 'gppro' ),
		'data'  => array(
			'top-banner-text'             => array(
				'label'    => __( 'Text', 'gppro' ),
				'input'    => 'color',
				'target'   => '.authority-top-banner',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'top-banner-link-color'       => array(
				'label'    => __( 'Link', 'gppro' ),
				'sub'      => __( 'Base', 'gppro' ),
				'input'    => 'color',
				'target'   => '.authority-top-banner a',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'top-banner-link-color-hover' => array(
				'label'    => __( 'Link', 'gppro' ),
				'sub'      => __( 'Hover', 'gppro' ),
				'input'    => 'color',
				'target'   => array( '.authority-top-banner a:hover', '.authority-top-banner a:focus' ),
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'top-banner-link-txt-dec'     => array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Base', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => '.authority-top-banner a',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			),
			'top-banner-link-txt-dec-hov' => array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Hover', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => array( '.authority-top-banner a:hover', '.authority-top-banner a:focus' ),
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			),
			'top-banner-txt-stack'        => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => array( '.authority-top-banner', '.authority-top-banner a' ),
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'top-banner-txt-size'         => array(
				'label'    => __( 'Font Size', 'gppro' ),
				'input'    => 'font-size',
				'scale'    => 'title',
				'target'   => array( '.authority-top-banner', '.authority-top-banner a' ),
				'selector' => 'font-size',
				'builder'  => 'GP_Pro_Builder::px_css',
			),
			'top-banner-txt-weight'       => array(
				'label'    => __( 'Font Weight', 'gppro' ),
				'input'    => 'font-weight',
				'target'   => array( '.authority-top-banner', '.authority-top-banner a' ),
				'selector' => 'font-weight',
				'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::number_css',
			),
			'top-banner-txt-transform'    => array(
				'label'    => __( 'Text Appearance', 'gppro' ),
				'input'    => 'text-transform',
				'target'   => array( '.authority-top-banner', '.authority-top-banner a' ),
				'selector' => 'text-transform',
				'builder'  => 'GP_Pro_Builder::text_css',
			),
			'top-banner-txt-style'        => array(
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
				'target'   => array( '.authority-top-banner', '.authority-top-banner a' ),
				'selector' => 'font-style',
				'builder'  => 'GP_Pro_Builder::text_css',
			),
			'top-banner-txt-align'        => array(
				'label'    => __( 'Align', 'gppro' ),
				'input'    => 'text-align',
				'target'   => '.authority-top-banner',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-align',
			),
		),
	),
);
