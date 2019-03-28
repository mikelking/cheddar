<?php
/**
 * Class Special_Json_Feed
 * see http://stackoverflow.com/questions/9907858/how-to-add-a-field-in-edit-post-page-inside-publish-box-in-wordpress
 */

require_once( __DIR__ . '/lib/msn-json-base.php' );
require_once( __DIR__ . '/lib/msn-fields-controller.php' );

class MSN_Videos_Json_Feed extends MSN_Json_Base {
	const SERVICE_NAME             = 'msn';
	const CONTENT_TYPE             = 'videos';
	const MYSQL_DATE_FMT           = 'c';
	const FEED_ORDER_BY            = 'publish_date';
	const ITEM_LIMIT               = 30;
	const CACHE_MAX_AGE            = 7200;


	private $debug_videos_feed;
	public function __construct() {
		parent::__construct();
		$MSN_fields_object = new MSN_Fields_Controller();

		$this->debug_videos_feed = false;
	}

	public function get_the_post_title() {
		$title = get_post_meta( get_the_ID(), '_yoast_wpseo_opengraph-title', true );
		if ( ! isset( $title ) || $title === '' ) {
			$title = get_the_title_rss();
		}
		return( $title );
	}

	public function get_the_query() {
		$query = wp_cache_get( 'json_videos_feed_query', 'jsf' );
		if ( $this->debug_videos_feed === true ) {
			$query = false;
		}
		if ( $query == false ) {
			$query_args = array(
				'post_type'         => 'video',
				'posts_per_page'    => static::ITEM_LIMIT,
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
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'dailymotion_video_id',
						'value' => '',
						'compare' => '!=',
					),
					array(
						'key' => 'dailymotion_is_playlist',
						'value_num'   => 1,
						'compare' => '!=',
					),
					array(
						'key' => 'media_url',
						'compare' => 'EXISTS',
					),
					array(
						'key' => 'mime_type',
						'compare' => 'EXISTS',
					),
					array(
						'key' => 'duration',
						'value'   => 1,
						'compare' => '>',
					),
				),
				'no_found_rows' => true,
			);
			$query = new WP_Query( $query_args );

			wp_cache_set( 'json_videos_feed_query', $query, 'jsf', 3600 );
		}
		if ( $this->debug_videos_feed === true ) {
			echo '<PRE>';
			$posts = $query->posts;
			foreach ( $posts as $post ) {
				echo "\r\n".$post->ID.' - '.$post->post_date;
			}
			print_r( $query );
		}
		return $query;
	}

	public function get_json_items() {
		$query = $this->get_the_query();
		if ( $query->post_count < static::ITEM_LIMIT ) {
			$iteration_count = $query->post_count;
		} else {
			$iteration_count = static::ITEM_LIMIT;
		}

		try {
			while ( $query->have_posts() ) {
				$query->the_post();
				$iteration_count--;
				$this->print_json_element( $this->get_feed_item() );
				if ( $iteration_count != 0 ) {
					print( ',' . PHP_EOL );
				}
			}
		} catch ( Exception $e ) {
			$msg = $e->getMessage() . PHP_EOL;

			self::throw_exception_exception( $msg );
		}
	}

	// The original function uses JSON_FORCE_OBJECT, breaking array values
	public function print_json_element( $data ) {
		$json_encoded_data = str_replace( self::BACKSPACE, '', json_encode( $data ) );
		print( $json_encoded_data );
	}

	public function get_body_content() {
		return( trim( get_the_content() ) );
	}

	private function get_feed_item() {
		return array(
			'categories' => $this->get_video_categories(),
			'id'         => get_the_ID(),
			'published'  => $this->get_post_published_date(),
			'updated'    => $this->get_post_modified_date(),
			//'expires'  => '',
			'image'      => $this->get_feed_item_image(),
			'keywords'   => $this->get_video_keywords(),
			'video'      => array(
				array(
					'title'    => $this->get_the_post_title(),
					'abstract' => $this->get_body_content(),
					'author'   => get_the_author(),
					'duration' => $this->get_video_duration(),
					'url'      => $this->get_video_url(),
				),
			),
		);
	}

	public function get_feed_item_image() {
		$image_title = '';
		$image_credit = '';
		$url = '';
		$thumbnailUrl = '';

		$response = array(
			'title'        => '',
			'description'  => '',
			'author'       => '',
			'url'          => '',
			'thumbnailUrl' => '',
		);

		$image_id = get_post_thumbnail_id( get_the_ID() );
		$post_images = wp_get_attachment_image_src( $image_id, 'large' );
		if ( $post_images ) {
			$response['title'] = get_the_title( $image_id );
			$response['url'] = esc_url( $post_images[0] );

			// Thumbnail
			$thumbnails = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			$thumbnail = $thumbnails[0];
			if ( $thumbnail ) {
				$response['thumbnailUrl'] = esc_url( $thumbnail );
			} else {
				unset( $response['thumbnailUrl'] );
			}

			// Author
			$image_credit = '';
			if ( function_exists( 'get_the_image_credits' ) ) {
				$image_credit = strip_tags( get_the_image_credits( ' ', ' ', ' ' ) );
			}
			if ( $image_credit == '' ) {
				$image_credit = get_bloginfo_rss( 'name' );
			}
			$response['author'] = $image_credit;


			// Attribution
			$response['attribution'] = trim( $image_credit );
			$response['hasSyndicationRights'] = trim( get_post_meta( $image_id, '_syndication_rights', true ) );
			if ( empty( $response['hasSyndicationRights'] ) ) {
				$response['hasSyndicationRights'] = 0;
			}
			if ( $response['hasSyndicationRights'] == 0 ) {
				$response['licenseId'] = get_post_meta( $image_id, '_image_licensor_id', true );
				$response['licensorName'] = get_post_meta( $image_id, '_image_licensor_name', true );
			}
		}

		return $response;
	}

	private function get_video_categories() {
		return wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) );
	}

	private function get_video_keywords() {
		return wp_get_post_tags( get_the_ID(), array( 'fields' => 'names' ) );
	}

	private function get_video_duration() {
		return get_field( 'duration' );
	}
	private function get_video_url() {
		return get_field( 'media_url' );
	}
}

