<?php
/**
 * Filters the CSS builder.
 *
 * @package genesis-design-pro
 */

$setup = isset( $setup ) ? $setup : array();
$data  = isset( $data ) ? $data : array();
$class = isset( $class ) ? $class : array();

// Check for a change in the placeholder text color.
if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-color' ) ) {

	// Pull my color variable out of the data array.
	$color = esc_attr( $data['header-search-form-place-text-color'] );

	// CSS entries for webkit.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { color: ' . $color . ' }' . "\n";

	// CSS entries for Firefox 18 and below.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { color: ' . $color . ' }' . "\n";

	// CSS entries for Firefox 19 and above.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { color: ' . $color . ' }' . "\n";

	// CSS entries for IE.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { color: ' . $color . ' }' . "\n";
}

// Check for a change in the placeholder font stack.
if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-stack' ) ) {

	// Pull my color variable out of the data array.
	$stack = esc_attr( $data['header-search-form-place-text-stack'] );

	// CSS entries for webkit.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { font-family: ' . $stack . ' }' . "\n";

	// CSS entries for Firefox 18 and below.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { font-family: ' . $stack . ' }' . "\n";

	// CSS entries for Firefox 19 and above.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { font-family: ' . $stack . ' }' . "\n";

	// CSS entries for IE.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { font-family: ' . $stack . ' }' . "\n";
}

// Check for a change in the placeholder font size.
if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-size' ) ) {

	// Pull my color variable out of the data array.
	$size = esc_attr( $data['header-search-form-place-text-size'] );

	// CSS entries for webkit.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { font-size: ' . $size . ' }' . "\n";

	// CSS entries for Firefox 18 and below.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { font-size: ' . $size . ' }' . "\n";

	// CSS entries for Firefox 19 and above.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { font-size: ' . $size . ' }' . "\n";

	// CSS entries for IE.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { font-size: ' . $size . ' }' . "\n";
}

// Check for a change in the placeholder font weight.
if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-weight' ) ) {

	// Pull my color variable out of the data array.
	$weight = esc_attr( $data['header-search-form-place-text-weight'] );

	// CSS entries for webkit.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { font-size: ' . $weight . ' }' . "\n";

	// CSS entries for Firefox 18 and below.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { font-size: ' . $weight . ' }' . "\n";

	// CSS entries for Firefox 19 and above.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { font-size: ' . $weight . ' }' . "\n";

	// CSS entries for IE.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { font-size: ' . $weight . ' }' . "\n";
}

// Check for a change in the placeholder text transform.
if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-transform' ) ) {

	// Pull my color variable out of the data array.
	$transform = esc_attr( $data['header-search-form-place-text-transform'] );

	// CSS entries for webkit.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { text-transform: ' . $transform . ' }' . "\n";

	// CSS entries for Firefox 18 and below.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { text-transform: ' . $transform . ' }' . "\n";

	// CSS entries for Firefox 19 and above.
	$setup .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { text-transform: ' . $transform . ' }' . "\n";

	// CSS entries for IE.
	$setup .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { text-transform: ' . $transform . ' }' . "\n";
}
