<?php

/**
 * Class MSN_Json_Base
 */
class MSN_Json_Base extends Json_Base {
	const TAXONOMY_TAG_SLUG = 'kids-stuff'; //added for WPDT-6080 and WPDT-6079
	const TAXONOMY_KEYWORD  = 'kids';       //added for WPDT-6080 and WPDT-6079

	protected function remove_inline_videos( $content ) {
		$re = '/<div style="display: block; position: relative; max-width: 740px;">[\s]?<div style="padding-top: 54\.0541%;">[\s]?<(iframe|video).*(<\/iframe>|<\/script>)[\s]?<\/div>[\s]?<\/div>/isU';
		$re1 = '/<iframe .*? src=.*><\/iframe>/isU';
		$re2 = '/<media:content url="https:\/\/www.youtube.com.*<\/media:content>/isU';
		$content = preg_replace( $re1, '', $content );
		$content = preg_replace( $re2, '', $content );
		return preg_replace( $re, '', $content );
	}

	protected function remove_shortcode_videos( $content ) {
		return $this->remove_shortode( $content, 'rd-video' );
	}

	protected function remove_skyword_tracking( $content ) {
		$clean = $content;
		$clean = $this->remove_shortode( $clean, 'skyword_tracking' );
		$clean = $this->remove_shortode( $clean, 'cf' );
		return $clean;
	}

	protected function remove_shortcode_listicle( $content ) {
		return $this->remove_shortode( $content, 'rd_listicle' );
	}

	private function remove_shortode( $content, $shortcode ) {
		// Backup existing shortcodes.
		global $shortcode_tags;
		$stack = $shortcode_tags;

		// Register a single fake "$shortcode" shortcode.
		$shortcode_tags = array( $shortcode => 1 );
		// Use native WP functionality to remove it from the content.
		$content = strip_shortcodes( $content );

		// Restore the backup.
		$shortcode_tags = $stack;
		return $content;
	}

	public function rd_get_image_uri_from_url( $url ) {
		//Converted to Static to use in the article feed.
		$deliminator = '/wp-content/uploads/';
		$deliminator2 = 'sites/2/';
		if ( ! empty( $url ) ) {
			$clean_url = filter_var( strtolower( $url ), FILTER_SANITIZE_URL );
			$url_parts = explode( $deliminator . $deliminator2, $clean_url );
			if ( $url_parts === false || ( strlen( $url_parts[0] ) === strlen( $clean_url ) ) ) {
				$url_parts = explode( $deliminator, $clean_url );
			}
			if ( isset( $url_parts['1'] ) ) {
				$uri = $url_parts['1'];
				return( $uri );
			}
		}
	}
}
