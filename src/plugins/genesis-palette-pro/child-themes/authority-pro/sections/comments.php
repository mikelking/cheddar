<?php
/**
 * Changes for the comments section.
 *
 * @package genesis-design-pro
 */

// Add text decoration to comment reply link.
$sections['comment-element-reply-setup']['data']['comment-reply-link-decoration'] = array(
	'label'    => __( 'Link Style', 'gppro' ),
	'sub'      => __( 'Base', 'gppro' ),
	'input'    => 'text-decoration',
	'target'   => 'a.comment-reply-link',
	'builder'  => 'GP_Pro_Builder::text_css',
	'selector' => 'text-decoration',
);

$sections['comment-element-reply-setup']['data']['comment-reply-link-decoration-hover'] = array(
	'label'    => __( 'Link Style', 'gppro' ),
	'sub'      => __( 'Hover', 'gppro' ),
	'input'    => 'text-decoration',
	'target'   => array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
	'builder'  => 'GP_Pro_Builder::text_css',
	'selector' => 'text-decoration',
);

// Add text decoration to trackback name.
$sections['trackback-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'trackback-element-name-link-hov', $sections['trackback-element-name-setup']['data'],
	array(
		'trackback-name-link-decoration'       => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Base', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => '.entry-pings .comment-author a',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
		'trackback-name-link-decoration-hover' => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
	)
);

// Add text decoration to trackback date.
$sections['trackback-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
	'trackback-element-date-link-hov', $sections['trackback-element-date-setup']['data'],
	array(
		'trackback-date-link-decoration'       => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Base', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => '.entry-pings .comment-author a',
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
		'trackback-date-link-decoration-hover' => array(
			'label'    => __( 'Link Style', 'gppro' ),
			'sub'      => __( 'Hover', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration',
		),
	)
);

// Add border to comment submit button.
$sections['comment-submit-button-color-setup']['data']['comment-submit-button-border-color'] = array(
	'label'    => __( 'Border', 'gppro' ),
	'sub'      => __( 'Base', 'gppro' ),
	'input'    => 'color',
	'target'   => '.comment-respond input#submit',
	'selector' => 'color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

$sections['comment-submit-button-color-setup']['data']['comment-submit-button-border-color-hov'] = array(
	'label'    => __( 'Border', 'gppro' ),
	'sub'      => __( 'Hover', 'gppro' ),
	'input'    => 'color',
	'target'   => array( '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
	'selector' => 'color',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
);

// Change builder for single commments.
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

// Change selector to border-left for single comments.
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-left-color';
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-left-style';
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-left-width';

// Change builder for author comments.
$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['builder'] = 'GP_Pro_Builder::text_css';
$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['builder'] = 'GP_Pro_Builder::px_css';

// Change selector to border-left for author comments.
$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['selector'] = 'border-left-color';
$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['selector'] = 'border-left-style';
$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['selector'] = 'border-left-width';

// Change target for single comments.
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['target'] = 'li.comment:not(.depth-1)';
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['target'] = 'li.comment:not(.depth-1)';
$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['target'] = 'li.comment:not(.depth-1)';

// Change target to border-left for author comments.
$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['target'] = 'li.comment:not(.depth-1)';
$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['target'] = 'li.comment:not(.depth-1)';
$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['target'] = 'li.comment:not(.depth-1)';

$unset = array(
	'section-break-comment-reply-atags-setup',
	'comment-reply-atags-area-setup',
	'comment-reply-atags-base-setup',
	'comment-reply-atags-code-setup',
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
