<?php
/**
 * Changes for the content_extras section.
 *
 * @package genesis-design-pro
 */

$sections = isset( $sections ) ? $sections : array();

$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']     = array(
	'.entry-content a.more-link.button.text',
	'.entry-content a.more-link',
);
$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target'] = array(
	'.entry-content a.more-link.button.text:hover',
	'.entry-content a.more-link.button.text:focus',
	'.entry-content a.more-link:hover',
	'.entry-content a.more-link:focus',
);

$sections['extras-read-more-colors-setup']['data'] = array_merge(
	$sections['extras-read-more-colors-setup']['data'], array(
		'extras-read-more-border-bottom-color'     => array(
			'label'    => __( 'Border Color', 'gppro' ),
			'input'    => 'color',
			'target'   => array(
				'.entry-content a.more-link.button.text',
				'.entry-content a.more-link',
			),
			'selector' => 'border-color',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
		),
		'extras-read-more-border-bottom-color-hov' => array(
			'label'    => __( 'Border Color', 'gppro' ),
			'input'    => 'color',
			'target'   => array(
				'.entry-content a.more-link.button.text:hover',
				'.entry-content a.more-link.button.text:focus',
				'.entry-content a.more-link:hover',
				'.entry-content a.more-link:focus',
			),
			'selector' => 'border-color',
			'builder'  => 'GP_Pro_Builder::hexcolor_css',
		),
		'extras-read-more-border-bottom-style'     => array(
			'label'    => __( 'Border Style', 'gppro' ),
			'input'    => 'borders',
			'target'   => array(
				'.entry-content a.more-link.button.text',
				'.entry-content a.more-link',
			),
			'selector' => 'border-style',
			'builder'  => 'GP_Pro_Builder::text_css',
			'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
		),
		'extras-read-more-border-bottom-width'     => array(
			'label'    => __( 'Border Width', 'gppro' ),
			'input'    => 'spacing',
			'target'   => array(
				'.entry-content a.more-link.button.text',
				'.entry-content a.more-link',
			),
			'selector' => 'border-width',
			'builder'  => 'GP_Pro_Builder::px_css',
			'min'      => '0',
			'max'      => '10',
			'step'     => '1',
		),
	)
);

$sections['extras-pagination-numeric-backs']['title'] = __( 'Borders', 'gppro' );

$sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-back']['selector']            = 'border-color';
$sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-back-hov']['selector']        = 'border-color';
$sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-active-back']['selector']     = 'border-color';
$sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-active-back-hov']['selector'] = 'border-color';

$sections['extras-read-more-colors-setup']['data']['extras-read-more-border-bottom-style']['selector'] = 'border-bottom-style';
$sections['extras-read-more-colors-setup']['data']['extras-read-more-border-bottom-width']['selector'] = 'border-bottom-width';

unset( $sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-border-radius'] );

$sections['extras-pagination-numeric-backs']['data']['extras-pagination-accent-back'] = array(
	'label'    => __( 'Accent Background Color', 'gppro' ),
	'input'    => 'color',
	'target'   => '.archive-pagination.pagination::before',
	'builder'  => 'GP_Pro_Builder::hexcolor_css',
	'selector' => 'background-color',
);
