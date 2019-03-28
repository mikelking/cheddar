<?php
/**
 * Class Special_Json_Feed
 * see http://stackoverflow.com/questions/9907858/how-to-add-a-field-in-edit-post-page-inside-publish-box-in-wordpress
 */
require_once( __DIR__ . '/lib/msn-json-base.php' );
require_once( __DIR__ . '/lib/msn-fields-controller.php' );
require_once( __DIR__ . '/lib/slideshow-feed-functions.php' );
class MSN_Slideshows_Json_Feed extends MSN_Json_Base {
	const SERVICE_NAME              = 'msn';
	const CONTENT_TYPE              = 'slideshows';
	const MYSQL_DATE_FMT            = 'c';
	const FEED_ORDER_BY             = 'date modified ID';
	const FIELD_ABSTRACT_TEXT_NAME  = 'dek';
	const ITEM_LIMIT                = 30;
	const CACHE_MAX_AGE             = 7200; // seconds


	public function __construct() {
		parent::__construct();
		$MSN_fields_object = new MSN_Fields_Controller();

	}
	public function get_the_post_title() {
		$title = get_post_meta( get_the_ID(), '_yoast_wpseo_opengraph-title', true );
		if ( ! isset( $title ) || $title === '' ) {
			$title = get_the_title_rss();
		}
		return( $title );
	}

	public function get_the_query() {
		$query = wp_cache_get( 'json_slideshow_feed_query', 'jsf' );
		if ( $query == false ) {
			$query_args = array(
					'post_type'         => 'slideshows',
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
			);
			$query = new WP_Query( $query_args );
			wp_cache_set( 'json_slideshow_feed_query', $query, 'jsf', static::CACHE_MAX_AGE );
		}
		return( $query );
	}

	public function get_json_items() {
		$query = $this->get_the_query();
		if ( $query->post_count < static::ITEM_LIMIT ) {
			$iteration_count = $query->post_count;
		} else {
			$iteration_count = static::ITEM_LIMIT;
		}
		/**
		 * Start loop
		 * The original operation is extremely costly in terms of memory and execution time;
		 * therefore, I have refactored it to output the json feed items as they are assembled.
		 *
		*/
		try {
			while ( $query->have_posts() ) {
				$query->the_post();

				$date_published = $this->get_post_published_date();
				$date_modified = $this->get_post_modified_date();

				$author = $this->get_rd_json_item_author();

				$this->body_content = $this->remove_inline_videos( $this->get_body_content() );
				$this->add_inline_image_credits();

				$arr_item['abstractText'] = $this->optimize_html_output( get_post_meta( get_the_ID(), self::FIELD_ABSTRACT_TEXT_NAME, true ) );
				$arr_item['title'] = $this->get_the_post_title();
				$arr_item['id'] = get_the_ID();
				$arr_images = '';
				$arr_img = '';
				$arr_item['images'] = '';

				$post_content = $this->remove_inline_videos( $query->post->post_content );

				if ( trim( $post_content ) == '' || trim( strip_tags( $post_content ) ) == '&nbsp;' || trim( strip_tags( $post_content ) ) == '' ) {

					$post_content = ( get_post_meta( get_the_ID(), 'slides', true ) );

					foreach ( $post_content as $key => $value ) {
						if ( $value != '' ) {
							$arr_img['title'] = $this->optimize_html_output( strip_tags( $value['title'] ) );
							$image = $this->get_image_url( $arr_img['title'],  $value['url'] );
							$image_uri = $this->rd_image_post_id( $image );
							$arr_img['author'] = $image_uri;

							$arr_img['description'] = $this->optimize_html_output( $value['desc'] );

							// Need to transform the URL for CDN optimization
							$arr_img['url'] = $image;

						}
						$arr_images[] = $arr_img;
					}
				} else {

					$slideshows = new Slideshows_Feed( $post_content );
					foreach ( $slideshows->arr_ss as $key => $value ) {
						if ( $value != '' ) {

							$arr_img['title'] = $this->optimize_html_output( strip_tags( $slideshows->title( $value ) ) );
							$image = $this->get_image_url( $arr_img['title'], $slideshows->image( $value['url'] ) );
							$image_uri = $this->rd_image_post_id( $image );

							$arr_img['author'] = $image_uri;
							$arr_img['description'] = $this->optimize_html_output( $slideshows->desc( $value ) );

							// Need to transform the URL for CDN optimization
							$arr_img['url'] = $image;

							$arr_images[] = $arr_img;
						}
					}
				}

				$arr_uri_credit = $this->rd_image_post_ids( $arr_images );
				foreach ( $arr_images as $key => $image ) {
					$image_key = strtolower( $image['author'] );
					$arr_images[$key]['author'] 				= $arr_uri_credit[$image_key]['author'];
					$arr_images[$key]['attribution'] 			= $arr_uri_credit[$image_key]['author'];
					$arr_images[$key]['hasSyndicationRights'] 	= $arr_uri_credit[$image_key]['hasSyndicationRights'];
					if ( empty( $arr_images[$key]['hasSyndicationRights'] ) ) {
						$arr_images[$key]['hasSyndicationRights'] = 0;
					}
					if ( $arr_images[$key]['hasSyndicationRights'] == 0 ) {
						$arr_images[$key]['licenseId'] 			= $arr_uri_credit[$image_key]['licenseId'];
						$arr_images[$key]['licensorName'] 		= $arr_uri_credit[$image_key]['licensorName'];
					}
				}
				$arr_item['images'] = $arr_images;
				$arr_item['link']['rel'] = 'self';
				$arr_item['link']['type'] = 'text/html';
				//$arr_item['images'] = $this->get_rd_json_item_image();

				$arr_item['body'] = $this->optimize_html_output( $this->body_content );
				$arr_item['link']['rel'] = 'self';
				$arr_item['link']['type'] = 'text/html';

				// Need to transform the URL for CDN optimization
				$arr_item['link']['href'] = URL_Magick::get_cdn_permalink( get_the_permalink() );

				/** based upon the findings in PR520 we are testing the reversal fo these dates
				* @link https://github.com/ReadersDigest/rdnap/pull/520
				*/
				$arr_item['date']['dateTimeWritten'] = $date_published;
				$arr_item['date']['published'] = $date_published;
				$arr_item['date']['updated'] = $date_modified;

				// this is just for testing and must redacted after success.
				//$d = new DateTime();
				//$arr_item['date']['now'] = $d->format( DateTime::DATE_W3C );

				$arr_item['author'] = $this->optimize_html_output( $author );

				//$arr_data['items'][] = $arr_item;
				$iteration_count--;

				/**
				 * A safety net
				 */
				if ( empty( $arr_item ) ) {
					$arr_item = array(
						'Oops' => 'Something went wrong and no items were constituted into the feed.',
					);
				}

				$this->print_json_element( $arr_item );
				if ( $iteration_count != 0 ) {
					print( ',' . PHP_EOL );
				}
			}

			//$this->json_content = str_replace( self::BACKSPACE, '', json_encode( $arr_data ) );
			//print( $this->json_content );
		} catch ( Exception $e ) {
			$msg = $e->getMessage() . PHP_EOL;
			self::throw_exception_exception( $msg );
		}
	}

	public function get_rd_json_item_image() {
		$thumb_id = get_post_thumbnail_id( get_the_ID() );
		$postimages = wp_get_attachment_image_src( $thumb_id, 'large' );
		$arr_img = false;
		$arr_images = false;
		// Check for images
		if ( $postimages ) {

			// Get featured image
			$postimage = $postimages[0];

			$arr_img['url'] = esc_url( $postimage );
			$postthumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
			$postthumbnail = $postthumbnail[0];

			if ( $postthumbnail ) {
				$arr_img['thumbnail'] = esc_url( $postthumbnail );
			}

			$image_credit = '';
			if ( function_exists( 'get_the_image_credits' ) ) {
				$image_credit = strip_tags( get_the_image_credits( ' ', ' ', ' ' ) );
			}

			if ( $image_credit == '' ) {
				$image_credit = get_bloginfo_rss( 'name' );
			}

			$image_title = 'Image';

			$arr_img['author'] = trim( $image_credit );
			$arr_img['title'] = trim( $image_title );

			//New MSN Req
			$arr_img['attribution'] = $image_credit;
			$arr_img['hasSyndicationRights'] = trim( get_post_meta( $thumb_id, '_syndication_rights', true ) );
			if ( empty( $arr_img['hasSyndicationRights'] ) ) {
				$arr_img['hasSyndicationRights'] = 0;
			}
			$arr_img['licenseId'] = get_post_meta( $thumb_id, '_image_licensor_id', true );
			$arr_img['licensorName'] = get_post_meta( $thumb_id, '_image_licensor_name', true );

			$arr_images[] = $arr_img;
		}

		return( $arr_images );
	}

	private function rd_image_post_ids( $arr_images ) {
		$arr_uri = wp_list_pluck( $arr_images, 'author' );
		$str_img = implode( "','",$arr_uri );
		$str_img = "'".$str_img."'";

		$str_img = preg_replace( '/(-[0-9]*x[0-9]*\.)/', '.', $str_img );

		global $wpdb;
		$sql = "SELECT post_id,meta_value  FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value IN( $str_img )";

		$arr_post_id = $wpdb->get_results( $sql, ARRAY_A );

		foreach ( $arr_post_id as $image ) {
			$thumb_id = $image['post_id'];
			$image_key = strtolower( $image['meta_value'] );
			$arr_uri_credit[$image_key]['author'] = $this->rd_get_image_credit( $thumb_id );

			//New MSN Req
			$arr_uri_credit[$image_key]['attribution'] = $arr_uri_credit[$image_key]['author'];
			$arr_uri_credit[$image_key]['hasSyndicationRights'] = get_post_meta( $thumb_id, '_syndication_rights', true );
			if ( $arr_uri_credit[$image_key]['hasSyndicationRights'] == 0 ) {
				$arr_uri_credit[$image_key]['licenseId'] = get_post_meta( $thumb_id, '_image_licensor_id', true );
				$arr_uri_credit[$image_key]['licensorName'] = get_post_meta( $thumb_id, '_image_licensor_name', true );
			}
		}
		return( $arr_uri_credit );
	}

	private function rd_image_post_id( $url ) {
		$deliminator = '/wp-content/uploads/';
		$deliminator2 = 'sites/2/';

		$clean_url = filter_var( strtolower( $url ), FILTER_SANITIZE_URL );
		$url_parts = explode( $deliminator . $deliminator2, $clean_url );
		if ( $url_parts === false || ( strlen( $url_parts[0] ) === strlen( $clean_url ) ) ) {
			$url_parts = explode( $deliminator, $clean_url );
		}
		$uri = $url_parts[1];

		return( $uri );
	}
	private function rd_get_image_credit( $image_id ) {
		$credit_text = '';
		foreach ( [ 'photographer_credit_name', '_wp_attachment_source_name' ] as $key ) {
			$credit_text = get_post_meta( $image_id, $key, true );
			if ( ! empty( $credit_text ) ) { break; }
		}

		return( trim( $credit_text ) );
	}
}

