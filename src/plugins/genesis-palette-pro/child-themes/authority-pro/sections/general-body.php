<?php
/**
 * Changes for the general body settings
 *
 * @package genesis-design-pro
 */

// Remove sections and settings.
$unset = array(
	'body-color-setup' => array(
		'body-color-back-thin',
	),
);

// Update target and sub for main background.
$sections['body-color-setup']['data']['body-color-back-main']['sub'] = '';
$sections['body-type-setup']['data']['body-type-size']['target']     = 'body > div';

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
