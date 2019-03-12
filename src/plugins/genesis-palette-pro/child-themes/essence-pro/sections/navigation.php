<?php
/**
 * Changes for the navigation section.
 *
 * @package genesis-design-pro
 */

$sections = isset( $sections ) ? $sections : array();

// Rename primary and sections nav.
$sections['section-break-primary-nav']   = array(
	'break' => array(
		'type'  => 'full',
		'title' => __( 'Header Navigation', 'gppro' ),
		'text'  => __( 'These settings apply to the menu selected in the "header navigation" section.', 'gppro' ),
	),
);
$sections['section-break-secondary-nav'] = array(
	'break' => array(
		'type'  => 'full',
		'title' => __( 'Footer Navigation', 'gppro' ),
		'text'  => __( 'These settings apply to the menu selected in the "footer navigation" section.', 'gppro' ),
	),
);

// Add border bottom for header nav area.
$sections['primary-nav-area-setup']['data']['primary-nav-area-border-bottom-color'] = array(
	'label'    => __( 'Border Bottom Color', 'gppro' ),
	'input'    => 'color',
	'target'   => '.nav-primary .wrap',
	'builder'  => 'GP_Pro_Builder::rgbcolor_css',
	'selector' => 'border-bottom-color',
);

// Add border bottom for active/hover state.
$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-link-border-bottom-color'] = array(
	'label'    => __( 'Border Bottom Color', 'gppro' ),
	'input'    => 'color',
	'target'   => array(
		'.nav-primary .genesis-nav-menu > .menu-item > a:focus',
		'.nav-primary .genesis-nav-menu > .menu-item > a:hover',
		'.nav-primary .genesis-nav-menu > .menu-item.current-menu-item > a',
	),
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
	'selector' => 'border-bottom-color',
);

$sections['section-break-offscreen-nav'] = array(
	'break' => array(
		'type'  => 'full',
		'title' => __( 'Offscreen Navigation', 'gppro' ),
		'text'  => __( 'Apply to the offscreen navigation that appears after clicking the menu button.', 'gppro' ),
	),
);

$sections['offscreen-nav-colors'] = array(
	'title' => __( 'Colors', 'gppro' ),
	'data'  => array(
		'offscreen-button-color'       => array(
			'label'    => __( 'Toggle Color', 'gppro' ),
			'input'    => 'color',
			'target'   => 'button.off-screen-item',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'color',
		),
		'offscreen-button-color-hov'   => array(
			'label'    => __( 'Toggle Color', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'color',
			'target'   => array(
				'button.off-screen-item:hover',
				'button.off-screen-item:focus',
			),
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'color',
		),
		'offscreen-nav-back'           => array(
			'label'    => __( 'Background Color', 'gppro' ),
			'input'    => 'color',
			'target'   => '.off-screen-content',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'background-color',
			'rgb'      => true,
		),
		'offscreen-nav-link-color'     => array(
			'label'    => __( 'Link Color', 'gppro' ),
			'input'    => 'color',
			'target'   => '.off-screen-menu .genesis-nav-menu a',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'color',
		),
		'offscreen-nav-link-color-hov' => array(
			'label'    => __( 'Link Color', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'color',
			'target'   => array(
				'.off-screen-menu .genesis-nav-menu a:hover',
				'.off-screen-menu .genesis-nav-menu a:focus',
			),
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
			'selector' => 'color',
		),
	),
);

$sections['offscreen-nav-typography'] = array(
	'title' => __( 'Typography', 'gppro' ),
	'data'  => array(
		'offscreen-nav-stack'     => array(
			'label'    => __( 'Font Stack', 'gppro' ),
			'input'    => 'font-stack',
			'target'   => '.off-screen-menu .genesis-nav-menu a',
			'builder'  => 'GP_Pro_Builder::stack_css',
			'selector' => 'font-family',
		),
		'offscreen-nav-size'      => array(
			'label'    => __( 'Font Size', 'gppro' ),
			'input'    => 'font-size',
			'scale'    => 'text',
			'target'   => '.off-screen-menu .genesis-nav-menu a',
			'builder'  => 'GP_Pro_Builder::px_css',
			'selector' => 'font-size',
		),
		'offscreen-nav-weight'    => array(
			'label'    => __( 'Font Weight', 'gppro' ),
			'input'    => 'font-weight',
			'target'   => '.off-screen-menu .genesis-nav-menu a',
			'builder'  => 'GP_Pro_Builder::number_css',
			'selector' => 'font-weight',
			'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
		),
		'offscreen-nav-transform' => array(
			'label'    => __( 'Text Appearance', 'gppro' ),
			'input'    => 'text-transform',
			'target'   => '.off-screen-menu .genesis-nav-menu a',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-transform',
		),
		'offscreen-nav-style'     => array(
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
			'target'   => '.off-screen-menu .genesis-nav-menu a',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'font-style',
		),
	),
);

$unset = array(
	'primary-nav-area-setup'             => array(
		'primary-nav-area-back',
	),
	'primary-nav-top-item-color-setup'   => array(
		'primary-nav-top-item-base-back',
		'primary-nav-top-item-base-back-hov',
	),
	'primary-nav-top-active-color-setup' => array(
		'primary-nav-top-item-active-back',
		'primary-nav-top-item-active-back-hov',
	),
	'primary-nav-drop-border-setup',
	'secondary-nav-drop-type-setup',
	'secondary-nav-drop-item-color-setup',
	'secondary-nav-drop-active-color-setup',
	'secondary-nav-drop-padding-setup',
	'secondary-nav-drop-border-setup',
	'secondary-nav-area-back',
	'secondary-nav-area-setup',
);

foreach ( $unset as $section => $settings ) {
	if ( is_string( $settings ) && isset( $sections[ $settings ] ) ) {
		unset( $sections[ $settings ] );
	}

	if ( empty( $sections[ $section ] ) ) {
		continue;
	}

	foreach ( $settings as $setting ) {
		if ( isset( $sections[ $section ]['data'][ $setting ] ) ) {
			unset( $sections[ $section ]['data'][ $setting ] );
		}
	}
}
