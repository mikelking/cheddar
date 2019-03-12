<?php
/**
 * Front Page Widget Settings
 *
 * @package genesis-design-pro
 */

return array(

	// Add text decoration.
	$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
		'footer-widget-content-link-hov', $sections['footer-widget-content-setup']['data'],
		array(
			'footer-widget-content-link-decoration'       => array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Base', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => '.sidebar .widget:not(:first-of-type) a',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			),
			'footer-widget-content-link-decoration-hover' => array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Hover', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => array( '.sidebar .widget:not(:first-of-type) a:hover', '.sidebar .widget:not(:first-of-type) a:focus' ),
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			),
		)
	),

	// Add footer widget button notice.
	$sections = GP_Pro_Helper::array_insert_after(
		'footer-widget-content-setup', $sections,
		array(
			'section-break-footer-widget-notice' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Note', 'gppro' ),
					'text'  => __( 'Settings to customize buttons are located under Content Extras.', 'gppro' ),
				),
			),
		)
	),

);
