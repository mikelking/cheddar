<?php
/**
 * Front Page Widget Settings
 *
 * @package genesis-design-pro
 */

return array(
	'section-break-featured-page'          => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Genesis Featured Page', 'gppro' ),
		),
	),

	// add featured page background color settings.
	'featured-page-background-setup'       => array(
		'title' => __( 'Color', 'gppro' ),
		'data'  => array(
			'featured-page-background-color' => array(
				'label'         => __( 'Background', 'gppro' ),
				'input'         => 'color',
				'target'        => '.flexible-widgets .widget_media_image::before',
				'selector'      => 'background',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-line-back-color'  => array(
				'label'         => __( 'Background', 'gppro' ),
				'input'         => 'color',
				'target'        => '.flexible-widgets .widget_media_image::after',
				'selector'      => 'background',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add featured subtitle typography settings.
	'featured-page-subtitle-type-setup'    => array(
		'title' => __( 'Featured Subtitle', 'gppro' ),
		'data'  => array(
			'featured-subtitle-type-color'       => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'input'         => 'color',
				'target'        => '.authority-subtitle',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-subtitle-type-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.authority-subtitle',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'featured-page-subtitle-type-size'   => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.authority-subtitle',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-subtitle-type-weight' => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.authority-subtitle',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-subtitle-type-style'  => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.authority-subtitle',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-subtitle-type-align'  => array(
				'label'         => __( 'Text Alignment', 'gppro' ),
				'input'         => 'text-align',
				'target'        => '.authority-subtitle',
				'builder'       => 'GP_Pro_Builder::text_css',
				'selector'      => 'text-align',
				'always_write'  => true,
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add featured title typography settings.
	'featured-page-title-type-setup'       => array(
		'title' => __( 'Featured Title', 'gppro' ),
		'data'  => array(
			'featured-page-title-type-color'       => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'sub'           => __( 'Base', 'gppro' ),
				'input'         => 'color',
				'target'        => '.featuredpage .entry-title a',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-title-type-color-hover' => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'sub'           => __( 'Hover', 'gppro' ),
				'input'         => 'color',
				'target'        => array( '.featuredpage .entry-title a:hover', '.featuredpage .entry-title a:focus' ),
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-title-type-stack'       => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.featuredpage .entry-title a',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'featured-page-title-type-size'        => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.featuredpage .entry-title a',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-title-type-weight'      => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.featuredpage .entry-title a',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-title-type-style'       => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.featuredpage .entry-title a',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-title-type-align'       => array(
				'label'         => __( 'Text Alignment', 'gppro' ),
				'input'         => 'text-align',
				'target'        => '.featuredpage .entry-title a',
				'builder'       => 'GP_Pro_Builder::text_css',
				'selector'      => 'text-align',
				'always_write'  => true,
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add featured content typography settings.
	'featured-page-content-type-setup'     => array(
		'title' => __( 'Featured Content', 'gppro' ),
		'data'  => array(
			'featured-page-content-type-color'  => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'input'         => 'color',
				'target'        => '.featuredpage .entry-content',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-content-type-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.featuredpage .entry-content',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'featured-page-content-type-size'   => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.featuredpage .entry-content',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-content-type-weight' => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.featuredpage .entry-content',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-content-type-style'  => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.featuredpage .entry-content',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-content-type-align'  => array(
				'label'         => __( 'Text Alignment', 'gppro' ),
				'input'         => 'text-align',
				'target'        => '.featuredpage .entry-content',
				'builder'       => 'GP_Pro_Builder::text_css',
				'selector'      => 'text-align',
				'always_write'  => true,
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add featured read more button settings.
	'featured-page-read-more-button-setup' => array(
		'title' => __( 'Read More Button', 'gppro' ),
		'data'  => array(
			'featured-page-read-more-back-color'          => array(
				'label'         => __( 'Background Color', 'gppro' ),
				'sub'           => __( 'Base', 'gppro' ),
				'input'         => 'color',
				'selector'      => 'background-color',
				'target'        => '.featuredpage .more-link',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-read-more-back-color-hover'    => array(
				'label'         => __( 'Background Color', 'gppro' ),
				'sub'           => __( 'Hover', 'gppro' ),
				'input'         => 'color',
				'selector'      => 'background-color',
				'target'        => array( '.featuredpage .more-link:hover', '.featuredpage .more-link:focus' ),
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-read-more-text-button-color'   => array(
				'label'         => __( 'Text Color', 'gppro' ),
				'sub'           => __( 'Base', 'gppro' ),
				'input'         => 'color',
				'selector'      => 'color',
				'target'        => '.featuredpage a.more-link',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-read-more-text-button-color-hover' => array(
				'label'         => __( 'Text Color', 'gppro' ),
				'sub'           => __( 'Hover', 'gppro' ),
				'input'         => 'color',
				'selector'      => 'color',
				'target'        => array( '.featuredpage a.more-link:hover', '.featuredpage a.more-link:focus' ),
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-read-more-button-border-color' => array(
				'label'         => __( 'Border Color', 'gppro' ),
				'sub'           => __( 'Base', 'gppro' ),
				'input'         => 'color',
				'selector'      => 'border-color',
				'target'        => '.featuredpage .more-link',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-page-read-more-button-border-color-hover' => array(
				'label'         => __( 'Border Color', 'gppro' ),
				'sub'           => __( 'Hover', 'gppro' ),
				'input'         => 'color',
				'selector'      => 'border-color',
				'target'        => array( '.featuredpage .more-link:hover', '.featuredpage .more-link:focus' ),
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	'section-break-featured-post'          => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Genesis Featured Post', 'gppro' ),
		),
	),

	// add featured post background color settings.
	'featured-post-background-setup'       => array(
		'title' => __( 'Color', 'gppro' ),
		'data'  => array(
			'featured-post-background-color' => array(
				'label'         => __( 'Background', 'gppro' ),
				'input'         => 'color',
				'target'        => '.featuredpost .has-post-thumbnail > a::before',
				'selector'      => 'background',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add featured title typography settings.
	'featured-post-title-type-setup'       => array(
		'title' => __( 'Featured Title', 'gppro' ),
		'data'  => array(
			'featured-post-title-type-color'       => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'sub'           => __( 'Base', 'gppro' ),
				'input'         => 'color',
				'target'        => '.featuredpost .entry-title a',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-post-title-type-color-hover' => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'sub'           => __( 'Hover', 'gppro' ),
				'input'         => 'color',
				'target'        => array( '.featuredpost .entry-title a:hover', '.featuredpost .entry-title a:focus' ),
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-post-title-type-stack'       => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.featuredpost .entry-title a',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'featured-post-title-type-size'        => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.featuredpost .entry-title a',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-post-title-type-weight'      => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.featuredpost .entry-title a',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-post-title-type-style'       => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.featuredpost .entry-title a',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add featured content typography settings.
	'featured-post-content-type-setup'     => array(
		'title' => __( 'Featured Content', 'gppro' ),
		'data'  => array(
			'featured-post-content-type-color'  => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'input'         => 'color',
				'target'        => '.featuredpost .entry-content',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-post-content-type-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.featuredpost .entry-content',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'featured-post-content-type-size'   => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.featuredpost .entry-content',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-post-content-type-weight' => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.featuredpost .entry-content',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'featured-post-content-type-style'  => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.featuredpost .entry-content',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// featured post notice.
	'section-break-featured-post-notice'   => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Note', 'gppro' ),
			'text'  => __( 'Settings to customize the Post Meta can be found under Content Area, and settings to customize Read More can be found under Content Extras.', 'gppro' ),
		),
	),

	'section-break-widget'                 => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Text/HTML Widget', 'gppro' ),
		),
	),

	// add widget title typography settings.
	'widget-title-type-setup'              => array(
		'title' => __( 'Widget Title', 'gppro' ),
		'data'  => array(
			'widget-title-type-color'  => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'input'         => 'color',
				'target'        => '.flexible-widgets .widget-title',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-title-type-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.flexible-widgets .widget-title',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'widget-title-type-size'   => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.flexible-widgets .widget-title',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-title-type-weight' => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.flexible-widgets .widget-title',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-title-type-style'  => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.flexible-widgets .widget-title',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add widget content typography settings.
	'widget-content-type-setup'            => array(
		'title' => __( 'Content', 'gppro' ),
		'data'  => array(
			'widget-content-type-color'       => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'input'         => 'color',
				'target'        => '.widget .textwidget',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-content-link-color'       => array(
				'label'         => __( 'Menu Links', 'gppro' ),
				'sub'           => __( 'Base', 'gppro' ),
				'input'         => 'color',
				'target'        => '.widget .textwidget a:not(.button)',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-content-link-color-hover' => array(
				'label'         => __( 'Menu Links', 'gppro' ),
				'sub'           => __( 'Hover', 'gppro' ),
				'input'         => 'color',
				'target'        => array( '.widget .textwidget a:not(.button):hover', '.widget .textwidget a:not(.button):focus' ),
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-content-link-txt-dec'     => array(
				'label'         => __( 'Link Style', 'gppro' ),
				'sub'           => __( 'Base', 'gppro' ),
				'input'         => 'text-decoration',
				'target'        => '.widget .textwidget a:not(.button)',
				'builder'       => 'GP_Pro_Builder::text_css',
				'selector'      => 'text-decoration',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-content-link-txt-dec-hov' => array(
				'label'         => __( 'Link Style', 'gppro' ),
				'sub'           => __( 'Hover', 'gppro' ),
				'input'         => 'text-decoration',
				'target'        => array( '.widget .textwidget a:not(.button):hover', '.widget .textwidget a:not(.button):focus' ),
				'builder'       => 'GP_Pro_Builder::text_css',
				'selector'      => 'text-decoration',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-content-type-stack'       => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.widget .textwidget',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'widget-content-type-size'        => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.widget .textwidget',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-content-type-weight'      => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.widget .textwidget',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'widget-content-type-style'       => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.widget .textwidget',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	'section-break-blockquote'             => array(
		'break' => array(
			'type'  => 'full',
			'title' => __( 'Blockquote', 'gppro' ),
		),
	),

	// add blockquote quotation color settings.
	'blockquote-before-color-setup'        => array(
		'title' => __( 'Color', 'gppro' ),
		'data'  => array(
			'blockquote-before-quotation-color' => array(
				'label'         => __( 'Quotation', 'gppro' ),
				'input'         => 'color',
				'target'        => 'blockquote::before',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add blockquote typography settings.
	'blockquote-type-setup'                => array(
		'title' => __( 'Blockquote', 'gppro' ),
		'data'  => array(
			'blockquote-type-color'  => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'input'         => 'color',
				'target'        => '.flexible-widgets blockquote',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'blockquote-type-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => '.flexible-widgets blockquote',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'blockquote-type-size'   => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => '.flexible-widgets blockquote',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'blockquote-type-weight' => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => '.flexible-widgets blockquote',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'blockquote-type-style'  => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => '.flexible-widgets blockquote',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),

	// add blockquote citation settings.
	'blockquote-citation-type-setup'       => array(
		'title' => __( 'Citation', 'gppro' ),
		'data'  => array(
			'blockquote-citation-type-color'  => array(
				'label'         => __( 'Font Color', 'gppro' ),
				'input'         => 'color',
				'target'        => 'blockquote cite',
				'selector'      => 'color',
				'builder'       => 'GP_Pro_Builder::hexcolor_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'blockquote-citation-type-stack'  => array(
				'label'    => __( 'Font Stack', 'gppro' ),
				'input'    => 'font-stack',
				'target'   => 'blockquote cite',
				'selector' => 'font-family',
				'builder'  => 'GP_Pro_Builder::stack_css',
			),
			'blockquote-citation-type-size'   => array(
				'label'         => __( 'Font Size', 'gppro' ),
				'input'         => 'font-size',
				'scale'         => 'text',
				'target'        => 'blockquote cite',
				'selector'      => 'font-size',
				'tip'           => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::px_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'blockquote-citation-type-weight' => array(
				'label'         => __( 'Font Weight', 'gppro' ),
				'input'         => 'font-weight',
				'target'        => 'blockquote cite',
				'selector'      => 'font-weight',
				'tip'           => __( 'Certain fonts will not display every weight.', 'gppro' ),
				'builder'       => 'GP_Pro_Builder::number_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
			'blockquote-citation-type-style'  => array(
				'label'         => __( 'Font Style', 'gppro' ),
				'input'         => 'radio',
				'options'       => array(
					array(
						'label' => __( 'Normal', 'gppro' ),
						'value' => 'normal',
					),
					array(
						'label' => __( 'Italic', 'gppro' ),
						'value' => 'italic',
					),
				),
				'target'        => 'blockquote cite',
				'selector'      => 'font-style',
				'builder'       => 'GP_Pro_Builder::text_css',
				'body_override' => array(
					'preview' => 'body.gppro-preview.front-page',
					'front'   => 'body.gppro-custom.front-page',
				),
			),
		),
	),
);
