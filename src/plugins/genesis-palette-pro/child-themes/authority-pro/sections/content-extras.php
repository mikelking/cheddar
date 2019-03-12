<?php
/**
 * Changes for the content extra settings
 *
 * @package genesis-design-pro
 */

// add text decoration to read more link.
$sections['extras-read-more-colors-setup']['data']['extra-read-more-link-dec-setup'] = array(
	'title' => __( 'Link Style', 'gppro' ),
	'input' => 'divider',
	'style' => 'lines',
);

$sections['extras-read-more-colors-setup']['data']['extra-read-more-link-decoration'] = array(
	'label'    => __( 'Link Style', 'gppro' ),
	'sub'      => __( 'Base', 'gppro' ),
	'input'    => 'text-decoration',
	'target'   => '.content > .post .entry-content a.more-link',
	'builder'  => 'GP_Pro_Builder::text_css',
	'selector' => 'text-decoration',
);

$sections['extras-read-more-colors-setup']['data']['extra-read-more-link-decoration-hover'] = array(
	'label'    => __( 'Link Style', 'gppro' ),
	'sub'      => __( 'Hover', 'gppro' ),
	'input'    => 'text-decoration',
	'target'   => array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' ),
	'builder'  => 'GP_Pro_Builder::text_css',
	'selector' => 'text-decoration',
);

// add text decoration to breadcrumbs link.
$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-link-dec-setup'] = array(
	'title' => __( 'Link Style', 'gppro' ),
	'input' => 'divider',
	'style' => 'lines',
);

$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-link-decoration'] = array(
	'label'    => __( 'Link Style', 'gppro' ),
	'sub'      => __( 'Base', 'gppro' ),
	'input'    => 'text-decoration',
	'target'   => '.breadcrumb a',
	'builder'  => 'GP_Pro_Builder::text_css',
	'selector' => 'text-decoration',
);

$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-link-decoration-hover'] = array(
	'label'    => __( 'Link Style', 'gppro' ),
	'sub'      => __( 'Hover', 'gppro' ),
	'input'    => 'text-decoration',
	'target'   => array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
	'builder'  => 'GP_Pro_Builder::text_css',
	'selector' => 'text-decoration',
);

// add border bottom to breadcrumbs.
$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-border-setup'] = array(
	'title' => __( 'Border', 'gppro' ),
	'input' => 'divider',
	'style' => 'lines',
);

$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-border-color'] = array(
	'label'    => __( 'Color', 'gppro' ),
	'input'    => 'color',
	'target'   => '.breadcrumb',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
	'selector' => 'border-bottom-color',
);

$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-border-style'] = array(
	'label'    => __( 'Style', 'gppro' ),
	'input'    => 'borders',
	'target'   => '.breadcrumb',
	'builder'  => 'GP_Pro_Builder::text_css',
	'selector' => 'border-bottom-style',
);

$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-border-width'] = array(
	'label'    => __( 'Width', 'gppro' ),
	'input'    => 'spacing',
	'target'   => '.breadcrumb',
	'builder'  => 'GP_Pro_Builder::px_css',
	'selector' => 'border-bottom-width',
	'min'      => '0',
	'max'      => '10',
	'step'     => '1',
);

$sections['extras-author-box-bio-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'extras-author-box-bio-link-hov', $sections['extras-author-box-bio-setup']['data'],
	array(
		'extras-author-box-link-dec'     => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Base', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => '.author-box-content a',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
		'extras-author-box-link-dec-hov' => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.author-box-content a:hover', '.author-box-content a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
	)
);

// settings for pagination next + previous button.
$sections['extras-pagination-numeric-backs']['data']['extras-pagination-next-previous-bttn-setup'] = array(
	'title' => __( 'Next + Previous Background', 'gppro' ),
	'input' => 'divider',
	'style' => 'lines',
);

$sections['extras-pagination-numeric-backs']['data']['extras-pagination-next-prev-border-color'] = array(
	'label'    => __( 'Border', 'gppro' ),
	'sub'      => __( 'Base', 'gppro' ),
	'input'    => 'color',
	'target'   => array( '.archive-pagination .pagination-next > a', '.archive-pagination .pagination-previous > a' ),
	'selector' => 'border-color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

$sections['extras-pagination-numeric-backs']['data']['extras-pagination-next-prev-border-color-hov'] = array(
	'label'    => __( 'Border', 'gppro' ),
	'sub'      => __( 'Hover', 'gppro' ),
	'input'    => 'color',
	'target'   => array( '.archive-pagination .pagination-next > a:hover', '.archive-pagination .pagination-previous > a:hover', '.archive-pagination .pagination-next > a:focus', '.archive-pagination .pagination-previous > a:focus' ),
	'selector' => 'border-color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

$sections['extras-pagination-numeric-backs']['data']['extras-pagination-next-prev-back-color'] = array(
	'label'    => __( 'Background', 'gppro' ),
	'sub'      => __( 'Base', 'gppro' ),
	'input'    => 'color',
	'target'   => array( '.archive-pagination .pagination-next > a', '.archive-pagination .pagination-previous > a' ),
	'selector' => 'background-color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

$sections['extras-pagination-numeric-backs']['data']['extras-pagination-next-prev-back-color-hov'] = array(
	'label'    => __( 'Background', 'gppro' ),
	'sub'      => __( 'Hover', 'gppro' ),
	'input'    => 'color',
	'target'   => array( '.archive-pagination .pagination-next > a:hover', '.archive-pagination .pagination-previous > a:hover', '.archive-pagination .pagination-next > a:focus', '.archive-pagination .pagination-previous > a:focus' ),
	'selector' => 'background-color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

// settings for pagination next + previous link color.
$sections['extras-pagination-numeric-colors']['data']['extras-pagination-next-prev-color'] = array(
	'label'    => __( 'Next/Prev Link', 'gppro' ),
	'sub'      => __( 'Base', 'gppro' ),
	'input'    => 'color',
	'target'   => array( '.archive-pagination .pagination-next > a', '.archive-pagination .pagination-previous > a' ),
	'selector' => 'color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

$sections['extras-pagination-numeric-colors']['data']['extras-pagination-next-prev-color-hov'] = array(
	'label'    => __( 'Next/Prev Link', 'gppro' ),
	'sub'      => __( 'Hover', 'gppro' ),
	'input'    => 'color',
	'target'   => array( '.archive-pagination .pagination-next > a:hover', '.archive-pagination .pagination-previous > a:hover', '.archive-pagination .pagination-next > a:focus', '.archive-pagination .pagination-previous > a:focus' ),
	'selector' => 'color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

// add general button settings.
$sections = GP_Pro_Helper::array_insert_after(
	'extras-breadcrumb-type-setup', $sections,
	array(
		'section-break-button-settings' => array(
			'break' => array(
				'title' => __( 'Buttons', 'gppro' ),
				'type'  => 'full',
				'text'  => __( 'Settings for the general button ( border) and primary (solid) button.', 'gppro' ),
			),
		),

		'general-button-setup'          => array(
			'title' => __( 'General Button Colors', 'gppro' ),
			'data'  => array(
				'general-button-background-color'       => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => '.button:not(.primary)',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'general-button-background-color-hover' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => array( '.button:not(.primary):hover', '.button:not(.primary):focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'general-text-button-color'             => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => 'a.button:not(.primary)',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'general-text-button-color-hover'       => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => array( 'a.button:not(.primary):hover', 'a.button:not(.primary):focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'general-button-border-color'           => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => '.button:not(.primary)',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'general-button-border-color-hover'     => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => array( '.button:not(.primary):hover', '.button:not(.primary):focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			),
		),

		'primary-button-setup'          => array(
			'title' => __( 'Primary Button Colors', 'gppro' ),
			'data'  => array(
				'primary-button-background-color'       => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => '.button.primary',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-button-background-color-hover' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => array( '.button.primary:hover', '.button.primary:hover' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-text-button-color'             => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => 'a.button.primary',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-text-button-color-hover'       => array(
					'label'    => __( 'Text Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'color',
					'target'   => array( 'a.button.primary:hover', 'a.button.primary:hover' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-button-border-color'           => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => '.button.primary',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-button-border-color-hover'     => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'selector' => 'border-color',
					'target'   => array( '.button.primary:hover', '.button.primary:hover' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			),
		),

		'button-spacing'                => array(
			'title' => __( 'Padding', 'gppro' ),
			'data'  => array(
				'button-padding-top'    => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.button',
					'selector' => 'padding-top',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '2',
				),
				'button-padding-left'   => array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.button',
					'selector' => 'padding-left',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '80',
					'step'     => '2',
				),
				'button-padding-right'  => array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.button',
					'selector' => 'padding-right',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '80',
					'step'     => '2',
				),
				'button-padding-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.button',
					'selector' => 'padding-bottom',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '2',
				),
			),
		),

		'button-typography-setup'       => array(
			'title' => __( 'Typography', 'gppro' ),
			'data'  => array(
				'button-text-stack'     => array(
					'label'    => __( 'Font Stack', 'gppro' ),
					'input'    => 'font-stack',
					'target'   => 'a.button',
					'builder'  => 'GP_Pro_Builder::stack_css',
					'selector' => 'font-family',
				),
				'button-text-size'      => array(
					'label'    => __( 'Font Size', 'gppro' ),
					'input'    => 'font-size',
					'scale'    => 'text',
					'target'   => 'a.button',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'font-size',
				),
				'button-text-weight'    => array(
					'label'    => __( 'Font Weight', 'gppro' ),
					'input'    => 'font-weight',
					'target'   => 'a.button',
					'builder'  => 'GP_Pro_Builder::number_css',
					'selector' => 'font-weight',
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				),
				'button-text-transform' => array(
					'label'    => __( 'Text Appearance', 'gppro' ),
					'input'    => 'text-transform',
					'target'   => 'a.button',
					'selector' => 'text-transform',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
				'button-text-style'     => array(
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
					'target'   => 'a.button',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'font-style',
				),
			),
		),
	)
);

// reset the specificity of the read more link.
$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']     = '.entry-content .more-link-wrap a:not(.more-link)';
$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target'] = array( '.entry-content .more-link-wrap a:hover', '.entry-content .more-link-wrap a:focus', '.content > .entry .entry-content .more-link-wrap a:hover', '.content > .entry .entry-content .more-link-wrap a:focus' );

// reset target for archive pagination background.
$sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-back']['target']     = '.archive-pagination li:not(.active):not(.pagination-next):not(.pagination-previous) a';
$sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-back-hov']['target'] = array( '.archive-pagination li:not(.active):not(.pagination-next):not(.pagination-previous) a:hover', '.archive-pagination li:not(.active):not(.pagination-next):not(.pagination-previous) a:focus' );

// reset target for archive pagination link color.
$sections['extras-pagination-numeric-colors']['data']['extras-pagination-numeric-link']['target']     = '.archive-pagination li:not(.active):not(.pagination-next):not(.pagination-previous) a';
$sections['extras-pagination-numeric-colors']['data']['extras-pagination-numeric-link-hov']['target'] = array( '.archive-pagination li:not(.active):not(.pagination-next):not(.pagination-previous) a:hover', '.archive-pagination li:not(.active):not(.pagination-next):not(.pagination-previous) a:focus' );
