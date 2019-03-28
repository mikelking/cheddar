<?php


/**
 * Class Special_Json_Feed
 * see http://stackoverflow.com/questions/9907858/how-to-add-a-field-in-edit-post-page-inside-publish-box-in-wordpress
 */
class Special_Json_Feed extends Json_Base {
	const CACHE_MAX_AGE  = 12; // seconds
	const ITEM_LIMIT     = 2;
	const SERVICE_NAME   = 'Special';
	const CONTENT_TYPE   = 'json';

	public function get_the_post_title() {
		$title = get_post_meta( get_the_ID(), '_yoast_wpseo_opengraph-title', true );
		if ( ! isset( $title ) || $title === '' ) {
			$title = get_the_title_rss();
		}
		return( $title );
	}
}

