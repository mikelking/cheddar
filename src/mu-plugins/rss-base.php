<?php
/*
Plugin Name: RSS Base
Version: 1.4
Description: Provides a simple object framework built upon the feed base to create a RSS feed
Author: Mikel King
Author URI: http://mikelking.com
*/

//Debug::enable_error_reporting();

/**
 * Class RSS_Base
 */
class RSS_Base extends Feed_Base_Controller {
	const VERSION        = '1.4';
	const DURATION       = 'hourly';
	const FREQUENCY      = 1;
	const META_BOX_CNTRL = false;
	const CACHE_GROUP    = 'rss-feed';
	const MULTIPAGE_URLS = true;
	const ITEM_LIMIT     = 10;
	const FEED_ORDER_BY  = 'modified';
	const CACHE_MAX_AGE  = 14400; // seconds === 4 hours

	/**
	 * @see https://codex.wordpress.org/Function_Reference/WP_Query#Order_.26_Orderby_Parameters
	 * @see https://codex.wordpress.org/Class_Reference/WP_Query
	 * @see https://codex.wordpress.org/Post_Types
	 * @see https://codex.wordpress.org/Glossary#Feed
	 * @return WP_Query
	 */
	public function get_the_query() {
		// checks the cache for an existing query
		$query = wp_cache_get( 'rss_feed_query', static::CACHE_GROUP );
		if ( $query === false ) {
			$query_args = array(
				'post_type' => $this->get_cpt_list(),
				'posts_per_page' => static::ITEM_LIMIT,
				'order' => 'desc',
			);

			if ( static::FEED_ORDER_BY != self::FEED_ORDER_BY ) {
				$query_args['orderby'] = static::FEED_ORDER_BY;
			}
			$query = new WP_Query( $query_args );

			// add the query to the cache
			wp_cache_set( 'rss_feed_query', $query, static::CACHE_GROUP, static::CACHE_MAX_AGE );
		}

		return( $query );
	}

	public function add_feed() {
		// $this->pmc should hydrate the entire feed name this is messy
		add_feed( $this->feed_slug, array( $this, 'render_rss_feed' ) );
	}

	public function render_rss_feed() {
		//$this->set_post_type_list();
		$this->ob_begin();
		$this->get_page_header();
		$this->get_channel_head();
		$this->get_feed_content();
		$this->get_channel_end();
		$this->ob_flush();
	}

	public function get_page_header() {
		/**
		 * Start RSS feed.
		 */
		$output  = '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>' . PHP_EOL;
		$output .= '<rss version="2.0" xmlns:dc= "http://purl.org/dc/elements/1.1/"   ';
		$output .= 'xmlns:media= "http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom"';
		$output .= do_action( 'rss2_ns' ) .' >' . PHP_EOL;
		if ( static::OUTPUT_BUFFERING === true ) {
			$output .= '<header>' . $header . '</header>' . PHP_EOL;
		}

		print( $output );
	}

	public function get_channel_head() {
		$last_build_date = $this->get_last_build_date();

		$output  = '<channel>' . PHP_EOL;
		$output .= '<title>' . get_bloginfo_rss( 'name' ) . '</title>' . PHP_EOL;
		$output .= '<link>' . get_bloginfo_rss( 'url' ) . '</link>' . PHP_EOL;
		if ( trim( get_bloginfo_rss( 'description' ) ) != '' ) {
			$output .= '<description>' . esc_html( get_bloginfo_rss( 'description' ) ) . '</description>' . PHP_EOL;
		}
		$output .= '<lastBuildDate>' . $last_build_date .'</lastBuildDate>' . PHP_EOL;
		$output .= '<language>' . get_bloginfo_rss( 'language' ) . '</language>' . PHP_EOL;
		$output .= '<atom:link href="' . get_bloginfo_rss( 'url' ) . '/feed/short/';
		$output .= '" rel="self" type="application/rss+xml" />' . PHP_EOL;

		print( $output );
	}

	public function get_feed_content() {
		//Start loop
		$query = $this->get_the_query();

		try {
			while ( $query->have_posts() ) {
				$query->the_post();

				$date_published = $this->get_post_published_date();
				$date_modified = $this->get_post_modified_date();
				$author = get_the_author_link();
				// add a check if post content is non empty
				if ( ! empty( get_the_content_feed() ) ) {
					$output = '<item>' . PHP_EOL;
					$output .= '<pid>' . get_the_ID() . '</pid>' . PHP_EOL;
					$output .= '<postType>' . $this->get_post_type() . '</postType>' . PHP_EOL;
					$output .= '<title>' . $this->get_the_post_title() . '</title>' . PHP_EOL;
					$output .= '<link>' . $this->get_the_url() . '</link>' . PHP_EOL;
					$output .= '<guid>' . $this->get_the_url() . '</guid>' . PHP_EOL; // was get_the_guid()
					$output .= '<author><![CDATA[ ' . esc_html( $author ) . ' ]]></author>' . PHP_EOL;
					$output .= '<description><![CDATA[ ' . esc_html( get_the_content_feed() ) . ' ]]></description>' . PHP_EOL;
					$output .= $this->get_item_image();
					$output .= '<pubDate>' . $date_published . '</pubDate>' . PHP_EOL;
					$output .= '<dc:modified>' . $date_modified . '</dc:modified>' . PHP_EOL;
					// debugging only
					//$output .= '<cptList>' . static::CPT_LIST . '</cptList>' . PHP_EOL;
					$output .= '</item>' . PHP_EOL;
					print($output);
				}
			}
		} catch ( Exception $e ) {
			$msg = $e->getMessage() . PHP_EOL;

			self::throw_exception_exception( $msg );
		}
	}

	public function get_item_image() {
		$output = '';
		$thumb_id = get_post_thumbnail_id( get_the_ID() );
		$postimages = wp_get_attachment_image_src( $thumb_id, 'large' );
		// Check for images
		if ( $postimages ) {

			// Get featured image
			$postimage = $postimages[0];

			$output  = '<media:content url="' . esc_url( $postimage ) . '" type="image/jpeg">' . PHP_EOL;

			$postthumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
			$postthumbnail = $postthumbnail[0];

			if ( $postthumbnail ) {
				$output .= '<media:thumbnail url="' . esc_url( $postthumbnail ) . '" type="image/jpeg"/>' . PHP_EOL;
			}

			$image_credit = '';
			if ( function_exists( 'get_the_image_credits' ) ) {
				$image_credit = strip_tags( get_the_image_credits( ' ', ' ', ' ' ) );
			}

			if ( $image_credit == '' ) {
				$image_credit = get_bloginfo_rss( 'name' );
			}

			$image_title = 'Image';

			$output .= '<media:credit>' . trim( $image_credit ) . '</media:credit>' . PHP_EOL;
			$output .= '<media:title>' . $image_title . '</media:title>' . PHP_EOL;
			$output .= '</media:content>' . PHP_EOL;
		}
		return( $output );
	}

	public function get_channel_end() {
		print('</channel>' . PHP_EOL . '</rss>' . PHP_EOL);
	}

	public function get_the_header_content_type() {
		return( 'Content-Type: ' . feed_content_type( 'rss' ) );
	}

}
