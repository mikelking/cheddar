<?php
/**
 * Changes for the comments_area section.
 *
 * @package genesis-design-pro
 */

$sections = isset( $sections ) ? $sections : array();

$sections['comment-list-back-setup']['data']['comment-list-accent'] = array(
	'label'    => __( 'Accent Color', 'gppro' ),
	'input'    => 'color',
	'target'   => '.entry-comments::before',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
	'selector' => 'background-color',
);

$sections['single-comment-standard-setup']['data'] = array_merge(
	$sections['single-comment-standard-setup']['data'], array(
		'comment-list-reply-border-color' => array(
			'label'    => __( 'Reply Border Left Color', 'gppro' ),
			'input'    => 'color',
			'target'   => '.comment-list .comment:not(.depth-1) article',
			'selector' => 'border-left-color',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
		),
		'comment-list-reply-border-style' => array(
			'label'    => __( 'Reply Border Left Style', 'gppro' ),
			'input'    => 'borders',
			'target'   => '.comment-list .comment:not(.depth-1) article',
			'selector' => 'border-left-style',
			'builder'  => 'GP_Pro_Builder::text_css',
			'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
		),
		'comment-list-reply-border-width' => array(
			'label'    => __( 'Reply Border Left Width', 'gppro' ),
			'input'    => 'spacing',
			'target'   => '.comment-list .comment:not(.depth-1) article',
			'selector' => 'border-left-width',
			'builder'  => 'GP_Pro_Builder::px_css',
			'min'      => '0',
			'max'      => '10',
			'step'     => '1',
		),
	)
);

$sections['comment-element-reply-setup']['data'] = array_merge(
	$sections['comment-element-reply-setup']['data'], array(
		'comment-element-reply-border-color'     => array(
			'label'    => __( 'Border Color', 'gppro' ),
			'input'    => 'color',
			'target'   => '.comment-reply-link',
			'selector' => 'border-color',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
		),
		'comment-element-reply-border-color-hov' => array(
			'label'    => __( 'Border Color', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'color',
			'target'   => array(
				'.comment-reply-link:hover',
				'.comment-reply-link:focus',
			),
			'selector' => 'border-color',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
		),
		'comment-element-reply-border-style'     => array(
			'label'    => __( 'Border Style', 'gppro' ),
			'input'    => 'borders',
			'target'   => '.comment-reply-link',
			'selector' => 'border-style',
			'builder'  => 'GP_Pro_Builder::text_css',
			'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
		),
		'comment-element-reply-border-width'     => array(
			'label'    => __( 'Border Width', 'gppro' ),
			'input'    => 'spacing',
			'target'   => '.comment-reply-link',
			'selector' => 'border-width',
			'builder'  => 'GP_Pro_Builder::px_css',
			'min'      => '0',
			'max'      => '10',
			'step'     => '1',
		),
	)
);

$unsets = array(
	'section-break-comment-reply-atags-setup',
	'comment-reply-atags-area-setup',
	'comment-reply-atags-base-setup',
	'comment-reply-atags-code-setup',
	'single-comment-author-setup',
	'single-comment-standard-setup' => array(
		'single-comment-standard-back',
		'single-comment-standard-border-color',
		'single-comment-standard-border-style',
		'single-comment-standard-border-width',
	),
);

foreach ( $unsets as $key => $section ) {
	if ( is_array( $section ) ) {
		foreach ( $section as $setting ) {
			if ( isset( $sections[ $key ] ) && isset( $sections[ $key ]['data'][ $setting ] ) ) {
				unset( $sections[ $key ]['data'][ $setting ] );
			}
		}
	} elseif ( isset( $sections[ $section ] ) ) {
		unset( $sections[ $section ] );
	}
}
