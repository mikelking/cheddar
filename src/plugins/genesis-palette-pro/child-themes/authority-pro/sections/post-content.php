<?php
/**
 * Changes for the post content settings
 *
 * @package genesis-design-pro
 */

// add background color to featured image.
$sections = GP_Pro_Helper::array_insert_after(
	'site-inner-setup', $sections,
	array(
		'featured-image-back-setup' => array(
			'title' => __( 'Featured Image', 'gppro' ),
			'data'  => array(
				'featured-image-back-color' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'input'    => 'color',
					'selector' => 'background-color',
					'target'   => '.authority-featured-image::before',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			),
		),
	)
);

// add setting for post meta date font weight.
$sections['post-header-meta-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'post-header-meta-weight', $sections['post-header-meta-type-setup']['data'],
	array(
		'post-header-meta-date-weight' => array(
			'label'    => __( 'Font Weight', 'gppro' ),
			'sub'      => __( 'Date', 'gppro' ),
			'input'    => 'font-weight',
			'target'   => '.entry-header .entry-meta > *',
			'builder'  => 'GP_Pro_Builder::number_css',
			'selector' => 'font-weight',
			'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
		),
	)
);

// add setting for post meta date.
$sections['post-header-meta-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'post-header-meta-transform', $sections['post-header-meta-type-setup']['data'],
	array(
		'post-header-meta-date-transform' => array(
			'label'    => __( 'Text Appearance', 'gppro' ),
			'sub'      => __( 'Date', 'gppro' ),
			'input'    => 'text-transform',
			'target'   => '.entry-header .entry-meta > *',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-transform',
		),
	)
);

// add setting for post meta date.
$sections['post-header-meta-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'post-header-meta-style', $sections['post-header-meta-type-setup']['data'],
	array(
		'post-header-meta-date-style' => array(
			'label'    => __( 'Font Style', 'gppro' ),
			'sub'      => __( 'Date', 'gppro' ),
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
			'target'   => '.entry-header .entry-meta > *',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'font-style',
		),
	)
);

// add post content text decoration.
if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
	$sections['post-entry-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
		'post-entry-link-hov', $sections['post-entry-color-setup']['data'],
		array(
			'post-entry-lk-txt-dec-setup' => array(
				'title' => __( 'Link Style', 'gppro' ),
				'input' => 'divider',
				'style' => 'lines',
			),
			'post-entry-lk-txt-dec'       => array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Base', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => '.content > .entry .entry-content a',
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			),
			'post-entry-lk-txt-dec-hov'   => array(
				'label'    => __( 'Link Style', 'gppro' ),
				'sub'      => __( 'Hover', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration',
			),
		)
	);
}

// add post footer text decoration.
$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'post-footer-tag-link-hov', $sections['post-footer-color-setup']['data'],
	array(
		'post-footer-comment-lk-txt-dec-setup' => array(
			'title' => __( 'Link Style', 'gppro' ),
			'input' => 'divider',
			'style' => 'lines',
		),
		'post-footer-comment-lk-txt-dec'       => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Base', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-footer .entry-categories a', '.entry-footer .entry-tags a' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
		'post-footer-comment-lk-txt-dec-hov'   => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array(
				'.entry-footer .entry-categories a:hover',
				'.entry-footer .entry-categories a:focus',
				'.entry-footer .entry-tags a:hover',
				'.entry-footer .entry-tags a:focus',
			),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
	)
);

// add blog archive.
$sections = GP_Pro_Helper::array_insert_after(
	'post-footer-divider-setup', $sections,
	array(
		// add archive page setting.
		'section-break-archive-page'  => array(
			'break' => array(
				'type'  => 'full',
				'title' => __( 'Archive Page', 'gppro' ),
				'text'  => __( 'These settings apply to the archive page title and description.', 'gppro' ),
			),
		),
		// add padding and margin setting.
		'archive-description-padding' => array(
			'title' => __( 'Padding', 'gppro' ),
			'data'  => array(
				'archive-description-padding-top'    => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.archive-description',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-top',
					'min'      => '0',
					'max'      => '80',
					'step'     => '1',
				),
				'archive-description-padding-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.archive-description',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-bottom',
					'min'      => '0',
					'max'      => '80',
					'step'     => '1',
				),
				'archive-description-padding-left'   => array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.archive-description',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-left',
					'min'      => '0',
					'max'      => '32',
					'step'     => '1',
				),
				'archive-description-padding-right'  => array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.archive-description',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-right',
					'min'      => '0',
					'max'      => '80',
					'step'     => '1',
				),
				'archive-description-margin-setup'   => array(
					'title' => __( 'Margin Bottom', 'gppro' ),
					'input' => 'divider',
					'style' => 'lines',
				),
				'archive-description-margin-bottom'  => array(
					'label'    => __( 'Margin Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.archive-description',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-bottom',
					'min'      => '0',
					'max'      => '70',
					'step'     => '1',
				),
			),
		),
		// add archive title typography settings.
		'archive-title-type-setup'    => array(
			'title' => __( 'Title Typography', 'gppro' ),
			'data'  => array(
				'archive-title-text'      => array(
					'label'    => __( 'Text', 'gppro' ),
					'input'    => 'color',
					'target'   => '.archive-description .entry-title',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'archive-title-stack'     => array(
					'label'    => __( 'Font Stack', 'gppro' ),
					'input'    => 'font-stack',
					'target'   => '.archive-description .entry-title',
					'builder'  => 'GP_Pro_Builder::stack_css',
					'selector' => 'font-family',
				),
				'archive-title-size'      => array(
					'label'    => __( 'Font Size', 'gppro' ),
					'input'    => 'font-size',
					'scale'    => 'text',
					'target'   => '.archive-description .entry-title',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'font-size',
				),
				'archive-title-weight'    => array(
					'label'    => __( 'Font Weight', 'gppro' ),
					'input'    => 'font-weight',
					'target'   => '.archive-description .entry-title',
					'builder'  => 'GP_Pro_Builder::number_css',
					'selector' => 'font-weight',
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				),
				'archive-title-transform' => array(
					'label'    => __( 'Text Appearance', 'gppro' ),
					'input'    => 'text-transform',
					'target'   => '.archive-description .entry-title',
					'selector' => 'text-transform',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
				'archive-title-align'     => array(
					'label'        => __( 'Text Alignment', 'gppro' ),
					'input'        => 'text-align',
					'target'       => '.archive-description .entry-title',
					'builder'      => 'GP_Pro_Builder::text_css',
					'selector'     => 'text-align',
					'always_write' => true,
				),
				'archive-title-style'     => array(
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
					'target'   => '.archive-description .entry-title',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'font-style',
				),
			),
		),
	)
);

// update label for post meta.
$sections['post-header-meta-color-setup']['data']['post-header-meta-date-color']['label'] = __( 'Meta Text', 'gppro' );

// update target for post meta setting.
$sections['post-header-meta-color-setup']['data']['post-header-meta-date-color']['target'] = '.entry-header .entry-meta > *';

// remove sections and settings.
$unset = array(
	'main-entry-setup',
	'post-footer-divider-setup',
	'post-header-meta-color-setup' => array(
		'post-header-meta-author-link',
		'post-header-meta-author-link-hov',
		'post-header-meta-comment-link',
		'post-header-meta-comment-link-hov',
	),
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
