<?php
/**
 * Changes for the navigation section.
 *
 * @package genesis-design-pro
 */

$sections = isset( $sections ) ? $sections : array();

// add highlight button to primary navigation.
$sections = GP_Pro_Helper::array_insert_before(
	'primary-nav-drop-type-setup', $sections,
	array(
		'primary-highlight-button' => array(
			'title' => __( 'Highlight Button', 'gppro' ),
			'data'  => array(
				'primary-highlight-button-background-color' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => '.menu > .highlight > a',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-highlight-button-color'        => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => '.menu > .highlight > a',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-highlight-button-border-color' => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => '.menu > .highlight > a',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			),
		),
	)
);

// add responsive menu settings.
$sections = GP_Pro_Helper::array_insert_after(
	'primary-nav-drop-border-setup', $sections,
	array(
		'primary-responsive-menu-setup' => array(
			'title' => __( 'Responsive Menu', 'gppro' ),
			'data'  => array(
				'primary-responsive-menu-background-color' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => '.menu-toggle',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-menu-background-color-hov' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => array( '.menu-toggle:hover', '.menu-toggle:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-menu-button-color'     => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => '.menu-toggle',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-menu-button-color-hov' => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => array( '.menu-toggle:hover', '.menu-toggle:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-menu-border-color'     => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => '.menu-toggle',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-menu-border-color-hov' => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => array( '.menu-toggle:hover', '.menu-toggle:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-menu-sub-toggle-setup' => array(
					'title' => __( 'Submenu Toggle', 'gppro' ),
					'input' => 'divider',
					'style' => 'lines',
				),
				'primary-responsive-submenu-togg-background-color' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => '.sub-menu-toggle',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-submenu-togg-background-color-hov' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => array( '.sub-menu-toggle:hover', '.sub-menu-toggle:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-submenu-togg-color'    => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => '.sub-menu-toggle',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-submenu-togg-color-hov' => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => array( '.sub-menu-toggle:hover', '.sub-menu-toggle:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-submenu-togg-border-color' => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => '.sub-menu-toggle',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-responsive-submenu-togg-border-color-hov' => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => array( '.sub-menu-toggle:hover', '.sub-menu-toggle:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			),
		),
	)
);

$sections = GP_Pro_Helper::array_insert_after(
	'secondary-nav-top-padding-setup', $sections,
	array(
		// add Social Menu.
		'section-break-social-menu' => array(
			'break' => array(
				'type'  => 'full',
				'title' => __( 'Social Menu', 'gppro' ),
				'text'  => __( 'These settings apply to the "Social Menu".', 'gppro' ),
			),
		),

		// add archive title typography settings.
		'social-menu-color-setup'   => array(
			'title' => __( 'Item Colors', 'gppro' ),
			'data'  => array(
				'social-menu-link-text'         => array(
					'label'    => __( 'Link', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-social a',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'social-menu-link-hover-text'   => array(
					'label'    => __( 'Link', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-social a:hover', '.nav-social a:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'social-menu-back-before-color' => array(
					'label'    => __( 'Border', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-social li:first-child a::before',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'background',
				),
			),
		),

		'social-menu-type-setup'    => array(
			'title' => __( 'Typography', 'gppro' ),
			'data'  => array(
				'social-menu-stack'     => array(
					'label'    => __( 'Font Stack', 'gppro' ),
					'input'    => 'font-stack',
					'target'   => '.nav-social a',
					'builder'  => 'GP_Pro_Builder::stack_css',
					'selector' => 'font-family',
				),
				'social-menu-size'      => array(
					'label'    => __( 'Font Size', 'gppro' ),
					'input'    => 'font-size',
					'scale'    => 'text',
					'target'   => '.nav-social a',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'font-size',
				),
				'social-menu-weight'    => array(
					'label'    => __( 'Font Weight', 'gppro' ),
					'input'    => 'font-weight',
					'target'   => '.nav-social a',
					'builder'  => 'GP_Pro_Builder::number_css',
					'selector' => 'font-weight',
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				),
				'social-menu-transform' => array(
					'label'    => __( 'Text Appearance', 'gppro' ),
					'input'    => 'text-transform',
					'target'   => '.nav-social a',
					'selector' => 'text-transform',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
			),
		),
	)
);

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

// update primary navigation drop-down border.
$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-color']['selector'] = 'border-top-color';
$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-style']['selector'] = 'border-top-style';
$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-width']['selector'] = 'border-top-width';

// remove sections and settings.
$unset = array(
	'primary-nav-top-item-color-setup'     => array(
		'primary-nav-top-item-base-back',
		'primary-nav-top-item-base-back-hov',
	),
	'primary-nav-top-active-color-setup'   => array(
		'primary-nav-top-item-active-back',
		'primary-nav-top-item-active-back-hov',
	),
	'secondary-nav-top-item-setup'         => array(
		'secondary-nav-top-item-base-back',
		'secondary-nav-top-item-base-back-hov',
	),
	'secondary-nav-top-active-color-setup' => array(
		'secondary-nav-top-item-active-back',
		'secondary-nav-top-item-active-back-hov',
	),
	'primary-nav-area-setup',
	'secondary-nav-area-setup',
	'secondary-nav-drop-type-setup',
	'secondary-nav-drop-item-color-setup',
	'secondary-nav-drop-active-color-setup',
	'secondary-nav-drop-padding-setup',
	'secondary-nav-drop-border-setup',
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
