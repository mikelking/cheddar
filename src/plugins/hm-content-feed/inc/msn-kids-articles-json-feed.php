<?php

require_once( __DIR__ . '/lib/msn-json-base.php' );
require_once( __DIR__ . '/lib/msn-fields-controller.php' );

class MSN_Kids_Articles_Json_Feed extends MSN_Articles_Json_Feed {
	const SERVICE_NAME             = 'msn';
	const CONTENT_TYPE             = 'kids-articles';
	const ITEM_LIMIT               = 90;

	private $debug_article_feed;
	public function __construct() {
		parent::__construct();
		if ( ! empty( $_GET['debug_feed'] ) && $_GET['debug_feed'] == 'all' ) {
			$this->debug_article_feed = true;
		}else {
			$this->debug_article_feed = false;
		}
	}

	public function get_the_query() {
		$query = wp_cache_get( 'json_feed_query', 'jsf' );
		if ( $this->debug_article_feed === true ) {
			$query = false;
		}

		if ( $query == false ) {
			$query_args = array(
				'post_type'         => 'post',
				'posts_per_page'    => static::ITEM_LIMIT,
				'tag'               => self::TAXONOMY_TAG_SLUG,
				'orderby'           => static::FEED_ORDER_BY,
				'order'             => 'desc',
				'tax_query' => array(
					array(
						'taxonomy' => static::EXCLUDE_FEED_TAXONOMY,
						'field'    => 'slug',
						'terms'    => static::EXCLUDE_MSN_SLUG,
						'operator' => 'NOT IN',
					),
				),
				'no_found_rows' => true,
			);
			$query = new WP_Query( $query_args );
			if ( isset( $_GET['debug_feed'] ) && $_GET['debug_feed'] == 'msn-articles' ) {
				echo '<pre>';
				print_r( $query->request ).PHP_EOL;
				echo '</pre>';
				die;
			}
			wp_cache_set( 'json_feed_query', $query, 'jsf', 3600 );
		}
		if ( $this->debug_article_feed === true ) {
			echo '<PRE>';
			$posts = $query->posts;
			foreach ( $posts as $post ) {
				echo "\r\n".$post->ID.' - '.$post->post_date;
			}
			print_r( $query );
		}
		return $query;
	}
}
