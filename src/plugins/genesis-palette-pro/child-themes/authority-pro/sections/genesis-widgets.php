<?php
/**
 * Genesis Widgets - eNews
 *
 * @package genesis-design-pro
 */

// Bail without the enews add on.
if ( empty( $sections['genesis_widgets'] ) ) {
	return $sections;
}

// Add accent color.
$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
	'enews-widget-back', $sections['genesis_widgets']['enews-widget-general']['data'],
	array(
		'enews-widget-accent-back-color' => array(
			'label'    => __( 'Accent Line', 'gppro' ),
			'input'    => 'color',
			'target'   => array( '.enews-widget::after', '.sidebar .enews-widget:nth-child(2n+1)::after' ),
			'selector' => 'background',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'background',
		),
	)
);

// Add border to submit button.
$sections['genesis_widgets']['enews-widget-submit-button']['data'] = GP_Pro_Helper::array_insert_after(
	'enews-widget-button-text-color-hov', $sections['genesis_widgets']['enews-widget-submit-button']['data'],
	array(
		'enews-widget-button-border-color'     => array(
			'label'    => __( 'Border Color', 'gppro' ),
			'sub'      => __( 'Base', 'gppro' ),
			'input'    => 'color',
			'target'   => '.enews-widget input[type="submit"]',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'border-color',
		),
		'enews-widget-button-border-color-hov' => array(
			'label'    => __( 'Border Color', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'color',
			'target'   => array( '.enews-widget input:hover[type="submit"]', '.enews-widget input:focus[type="submit"]' ),
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'border-color',
		),
	)
);

// remove submit button margin-bottom.
unset( $sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-margin-bottom'] );
