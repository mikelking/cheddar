<?php
/**
 * Menu Settings Page file.
 */

// Block direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No script kiddies please!' );
}

// The media deduper pro settings page.
$mdd_pro_settings = new CSHP_Settings_Page( __( 'Media Deduper Pro', 'media-deduper' ), __( 'Media Deduper Pro', 'media-deduper' ), 'manage_options', 'mdd_pro' );

// Adding the general sections.
$general_section = $mdd_pro_settings->add_settings_section( 'general_section', __( 'General', 'media-deduper' ) );
	// Adding the general section settings Disable Duplicate Upload Blocking? field.
	$general_section->add_settings_field(
		array(
			'id'    => 'disable_block_duplicate_uploads',
			'label' => __( 'Disable Duplicate Upload Blocking?', 'media-deduper' ),
			'description' => __( 'Check this box if you do NOT wish Media Deduper Pro to prevent duplicate uploads as they happen.', 'media-deduper' ),
			'field_type' => 'checkbox',
		)
	);

	$general_section->add_settings_field(
		array(
			'id'    => 'run_partial_hashes',
			'label' => __( 'Run partial hashes?', 'media-deduper' ),
			'description' => __( 'Speeds up indexing but results in less accurate file matching.', 'media-deduper' ),
			'field_type' => 'checkbox',
		)
	);
