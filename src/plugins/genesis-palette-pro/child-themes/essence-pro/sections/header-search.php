<?php
/**
 * The header search array
 *
 * @package genesis-design-pro
 */

return array(
	'section-break-header-search-form' => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Search Form', 'gppro' ),
		),
	),

	'header-search-form-text-setup'    => array(
		'title' => __( 'Typography', 'gppro' ),
		'data'  => array(
			'header-search-form-text'                 => array(
				'label'    => __( 'Font Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.site-header .search-form input[type="search"]',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'header-search-form-stack'                => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.site-header .search-form input[type="search"]',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'header-search-form-size'                 => array(
				'label'    => __( 'Font Size', 'gppro' ),
				'input'    => 'font-size',
				'scale'    => 'title',
				'target'   => '.site-header .search-form input[type="search"]',
				'selector' => 'font-size',
				'builder'  => 'GP_Pro_Builder::px_css',
			),
			'header-search-form-weight'               => array(
				'label'    => __( 'Font Weight', 'gppro' ),
				'input'    => 'font-weight',
				'target'   => '.site-header .search-form input[type="search"]',
				'selector' => 'font-weight',
				'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'  => 'GP_Pro_Builder::number_css',
			),
			'header-search-form-transform'            => array(
				'label'    => __( 'Text Appearance', 'gppro' ),
				'input'    => 'text-transform',
				'target'   => '.site-header .search-form input[type="search"]',
				'selector' => 'text-transform',
				'builder'  => 'GP_Pro_Builder::text_css',
			),
			'header-search-form-style'                => array(
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
				'target'   => '.site-header .search-form input[type="search"]',
				'selector' => 'font-style',
				'builder'  => 'GP_Pro_Builder::text_css',
			),
			'header-search-form-place-setup'          => array(
				'title' => __( 'Placeholder Text', 'gppro' ),
				'input' => 'divider',
				'style' => 'lines',
			),
			'header-search-form-place-text-color'     => array( // Target and Builder removed on purpose.
				'label'    => __( 'Color', 'gppro' ),
				'input'    => 'color',
				'selector' => 'color',
				'target'   => 'none',
			),
			'header-search-form-place-text-stack'     => array( // Target and Builder removed on purpose.
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'selector' => 'font-family',
				'target'   => 'none',
			),
			'header-search-form-place-text-size'      => array( // Target and Builder removed on purpose.
				'label'    => __( 'Font Size', 'gppro' ),
				'input'    => 'font-size',
				'selector' => 'font-size',
				'target'   => 'none',
			),
			'header-search-form-place-text-weight'    => array( // Target and Builder removed on purpose.
				'label'    => __( 'Font Weight', 'gppro' ),
				'input'    => 'font-weight',
				'selector' => 'font-weight',
				'target'   => 'none',
			),
			'header-search-form-place-text-transform' => array( // Target and Builder removed on purpose.
				'label'    => __( 'Text Appearance', 'gppro' ),
				'input'    => 'text-transform',
				'selector' => 'text-transform',
				'target'   => 'none',
			),
			'header-search-form-place-info'           => array(
				'input' => 'description',
				'desc'  => __( 'Placeholder styles will not be viewable in the preview window until after settings are saved', 'gppro' ),
			),
			'header-search-form-setup'                => array(
				'title' => __( 'Search Icon', 'gppro' ),
				'input' => 'divider',
				'style' => 'lines',
			),
			'header-search-form-icon-text'            => array(
				'label'    => __( 'Font Color', 'gppro' ),
				'input'    => 'color',
				'target'   => '.site-header .search-form:before',
				'selector' => 'color',
				'builder'  => 'GP_Pro_Builder::hexcolor_css',
			),
			'header-search-form-icon-size'            => array(
				'label'    => __( 'Font Size', 'gppro' ),
				'input'    => 'font-size',
				'scale'    => 'title',
				'target'   => '.site-header .search-form:before',
				'selector' => 'font-size',
				'builder'  => 'GP_Pro_Builder::px_css',
			),
		),
	),
);
