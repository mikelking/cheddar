<?php
/**
 * Changes for the sidebar settings
 *
 * @package genesis-design-pro
 */

// add settings for first single sidebar widget.
$sections = GP_Pro_Helper::array_insert_before(
	'sidebar-widget-back-setup', $sections,
	array(
		// add full header.
		'section-break-first-single-widget-setup' => array(
			'break' => array(
				'type'  => 'full',
				'title' => __( 'First Widget', 'gppro' ),
				'text'  => __( 'These settings only apply to the first widget in the sidebar area.', 'gppro' ),
			),
		),

		// add background color.
		'sidebar-single-widget-color-setup'       => array(
			'title' => '',
			'data'  => array(
				'sidebar-first-widget-back-color' => array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget:first-of-type',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-first-widget-shadow'     => array(
					'label'    => __( 'Box Shadow', 'gppro' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gppro' ),
							'value' => '0 15px 80px rgba(0,0,0,.14)',
						),
						array(
							'label' => __( 'Remove', 'gppro' ),
							'value' => 'none',
						),
					),
					'target'   => '.sidebar .widget:first-of-type',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			),
		),

		// add link color.
		'sidebar-single-widget-link-color-setup'  => array(
			'title' => __( 'Link', 'gppro' ),
			'data'  => array(
				'sidebar-single-widget-link-color'      => array(
					'label'    => __( 'Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget:first-of-type a',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'sidebar-single-widget-link-color-hov'  => array(
					'label'    => __( 'Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.sidebar .widget:first-of-type a:hover', '.sidebar .widget:first-of-type a:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'sidebar-single-widget-link-decoration' => array(
					'label'    => __( 'Link Style', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => '.sidebar .widget:first-of-type a',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration',
				),
				'sidebar-single-widget-link-decoration-hov' => array(
					'label'    => __( 'Link Style', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => array( '.sidebar .widget:first-of-type a:hover', '.sidebar .widget:first-of-type a:focus' ),
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration',
				),
			),
		),
	)
);

// add single sidebar single widget text decoration.
$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'sidebar-widget-content-link-hov', $sections['sidebar-widget-content-setup']['data'],
	array(
		'sidebar-widget-content-link-style'     => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Base', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => '.sidebar .widget:not(:first-of-type) a',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
		'sidebar-widget-content-link-style-hov' => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.sidebar .widget:not(:first-of-type) a:hover', '.sidebar .widget:not(:first-of-type) a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
	)
);

// reset the target for single widget background.
$sections['sidebar-widget-back-setup']['data']['sidebar-widget-back']['target'] = '.sidebar .widget:not(:first-of-type)';

// reset the target for single widget background.
$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link']['target']     = '.sidebar .widget:not(:first-of-type) a';
$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-hov']['target'] = array( '.sidebar .widget:not(:first-of-type) a:hover', '.sidebar .widget:not(:first-of-type) a:focus' );
