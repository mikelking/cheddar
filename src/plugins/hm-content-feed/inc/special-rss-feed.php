<?php

/**
 * Class Special_RSS_Feed
 * @link http://stackoverflow.com/questions/9907858/how-to-add-a-field-in-edit-post-page-inside-publish-box-in-wordpress
 */
class Special_RSS_Feed extends RSS_Base {
	const CACHE_MAX_AGE  = 12; // seconds
	const ITEM_LIMIT     = 3;
	const SERVICE_NAME   = 'Special';
	const CONTENT_TYPE   = 'rss';

	public function get_the_post_title() {
		$title = get_post_meta( get_the_ID(), '_yoast_wpseo_opengraph-title', true );
		if ( ! isset( $title ) || $title === '' ) {
			$title = get_the_title_rss();
		}
		return( $title );
	}
}
