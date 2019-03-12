<?php
/**
 * The post content.
 *
 * @package genesis-design-pro
 */

$sections = isset( $sections ) ? $sections : array();

$sections['main-entry-setup']['data']['main-entry-back']['target']                 = '.content';
$sections['main-entry-setup']['data']['main-entry-border-radius']['target']        = '.content';
$sections['main-entry-setup']['data']['main-entry-back']['body_override']          = array(
	'preview' => 'body.gppro-preview.single',
	'front'   => 'body.gppro-custom.single',
);
$sections['main-entry-setup']['data']['main-entry-border-radius']['body_override'] = array(
	'preview' => 'body.gppro-preview.single',
	'front'   => 'body.gppro-custom.single',
);

$sections['main-entry-setup']['data']['main-entry-back-fwc'] = array(
	'label'         => __( 'Background Color', 'gppro' ),
	'input'         => 'color',
	'target'        => '.site-inner',
	'builder'       => 'GP_Pro_Builder::hexcolor_css',
	'selector'      => 'background-color',
	'body_override' => array(
		'preview' => 'body.gppro-preview.single',
		'front'   => 'body.gppro-custom.single',
	),
);

$sections['main-entry-setup']['data']['main-entry-border-radius-fwc'] = array(
	'label'         => __( 'Border Radius', 'gppro' ),
	'sub'           => __( 'Full Width Content', 'gppro' ),
	'input'         => 'spacing',
	'target'        => '.site-inner',
	'builder'       => 'GP_Pro_Builder::px_css',
	'selector'      => 'border-radius',
	'min'           => '0',
	'max'           => '16',
	'step'          => '1',
	'body_override' => array(
		'preview' => 'body.gppro-preview.single',
		'front'   => 'body.gppro-custom.single',
	),
);

$sections['post-header-meta-color-setup']['data']['post-footer-category-link']     = $sections['post-footer-color-setup']['data']['post-footer-category-link'];
$sections['post-header-meta-color-setup']['data']['post-footer-category-link-hov'] = $sections['post-footer-color-setup']['data']['post-footer-category-link-hov'];

$sections['post-header-meta-color-setup']['data']['post-footer-category-link']['target']     = '.hero-page-title .entry-meta .entry-categories a';
$sections['post-header-meta-color-setup']['data']['post-footer-category-link-hov']['target'] = array(
	'.hero-page-title .entry-meta .entry-categories a:hover',
	'.hero-page-title .entry-meta .entry-categories a:focus',
);

$unsets = array(
	'section-break-post-footer-text',
	'post-footer-color-setup',
	'post-footer-type-setup',
	'post-footer-divider-setup',
);

foreach ( $unsets as $unset ) {
	if ( isset( $sections[ $unset ] ) ) {
		unset( $sections[ $unset ] );
	}
}
