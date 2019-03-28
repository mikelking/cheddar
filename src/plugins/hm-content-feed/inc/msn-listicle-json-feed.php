<?php
/**
 * Listicle Json Feed
 *
 */
require_once( __DIR__ . '/lib/msn-json-base.php' );
require_once( __DIR__ . '/lib/msn-fields-controller.php' );

class MSN_Listicle_Json_Feed extends MSN_Json_Base {
	const CACHE_MAX_AGE  			= 7200;
	const ITEM_LIMIT     			= 30;
	const SERVICE_NAME   			= 'msn';
	const CONTENT_TYPE   			= 'listicles';
	const MYSQL_DATE_FMT 			= 'c';
	const FEED_ORDER_BY  			= 'publish_date';
	const FIELD_ABSTRACT_TEXT_NAME	= 'dek';

	private $debug_feed;
	public function __construct() {
		parent::__construct();
		$MSN_fields_object = new MSN_Fields_Controller();
		if ( ! empty( $_GET['debug_feed'] ) && $_GET['debug_feed'] == 'all' ) {
			$this->debug_feed = true;
		}else {
			$this->debug_feed = false;
		}
	}
	public function get_the_post_title() {
		$title = get_post_meta( get_the_ID(), '_yoast_wpseo_opengraph-title', true );
		if ( ! isset( $title ) || $title === '' ) {
			$title = get_the_title_rss();
		}
		return( $title );
	}

	public function get_the_query() {
		$query = wp_cache_get( 'json_listicle_feed_query', 'jsf' );
		if ( $this->debug_feed === true ) {
			$query = false;
		}
		if ( $query == false ) {
			$query_args = array(
					'post_type'         => 'listicle',
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
			wp_cache_set( 'json_listicle_feed_query', $query, 'jsf',  static::CACHE_MAX_AGE );
		}
		if ( $this->debug_feed === true ) {
			echo '<PRE>';
			print_r( $query );
		}
		return $query;
	}

	public function get_json_items() {

		$query = $this->get_the_query();

		if ( $query->post_count < static::ITEM_LIMIT ) {
			$iteration_count = $query->post_count;
		}else {
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

				$this->body_content = $this->clean_content( $this->get_body_content() );
				$this->add_inline_image_credits();

				$arr_item['abstractText'] = $this->optimize_html_output( get_post_meta( get_the_ID(), self::FIELD_ABSTRACT_TEXT_NAME, true ) );
				$arr_item['title'] = $this->get_the_post_title();
				$arr_item['id'] = get_the_ID();

				//added for WPDT-6080
				if ( has_tag( self::TAXONOMY_TAG_SLUG ) ) {
					$arr_item['keywords'] = ucfirst( self::TAXONOMY_KEYWORD );
				} else {
					unset( $arr_item['keywords'] );
				}

				$arr_images = array();
				$arr_item['images'] = '';

				$post_content = $this->clean_content( $query->post->post_content );
				if ( $this->debug_feed === true ) {
					echo '<HR>POST_CONTENT<BR>';
					print_r( $post_content );
				}
				$arr_post_content = explode( '<!--nextpage-->', $post_content );
				if ( $this->debug_feed === true ) {
					echo '<HR>ARR_POST_CONTENT<BR>';
					print_r( $arr_post_content );
				}
				foreach ( $arr_post_content as $slide ) {
					$arr_img = array();
					$image = '';
					preg_match( '/(<h[1-6].*?>.*?<\/h[1-6]>)/i', $slide, $title );
					preg_match( '/< *img[^>]*src *= *["\']?([^"\']*)/i', $slide, $img_source );
					if ( isset( $img_source['1'] ) ) {
						$img_source = trim( $img_source['1'] );
					}


					// Try to find the id from the wp-image-(id) class (see Better Image Credits).
					preg_match( '/<img[^>]+\bclass="([^"]*\bwp-image-(\d+)\b[^"]*)"[^>]+>/i', $slide, $img_id );
					if ( ! empty( $img_id[2] ) ) {
						$img_id = trim( $img_id[2] );
					} else {
						// Try to get the original image URL, using the format "./image-(width)x(height).ext".
						$sizes = '/(-[0-9]*x[0-9]*\.)/';
						$original = preg_replace( $sizes, '.', $img_source );
						$original = $this->rd_get_image_uri_from_url( $original );
						$img_id = attachment_url_to_postid( $original );
					}

					$description = preg_replace( '/<img[^>]+\>/i', '', $slide );
					$description = preg_replace( '/<h[1-6].*?>.*?<\/h[1-6]>/i', '', $description );
					if ( isset( $title['0'] ) ) {
						$arr_img['title'] = $this->optimize_html_output( strip_tags( $title[0] ) );
					}
					$arr_img_orig_src = wp_get_attachment_image_src( $img_id, 'full', false );
					$arr_img['url'] = $arr_img_orig_src[0];
					$arr_img['description'] = $this->optimize_html_output( $description );

					// Credits and syndication rights
					if ( $img_id ) {
						$arr_img += $this->rd_syndication_credits( $img_id );
					}

					// Need to transform the URL for CDN optimization

					$arr_images[] = $arr_img;
				}
				if ( $this->debug_feed === true ) {
					echo '<HR>ARR_SLIDES/IMAGES<BR>';
					print_r( $arr_img );
				}

				$arr_item['images'] = $arr_images;
				$arr_item['link']['rel'] = 'self';
				$arr_item['link']['type'] = 'text/html';
				//$arr_item['images'] = $this->get_rd_json_item_image();



				// Need to transform the URL for CDN optimization
				$arr_item['link']['href'] = RD_URL_Magick::get_cdn_permalink( get_the_permalink() );
				$arr_item['date']['dateTimeWritten'] = $date_published;
				$arr_item['date']['published'] = $date_published;
				$arr_item['date']['updated'] = $date_modified;
				$arr_item['author'] = $this->optimize_html_output( $author );

				//$arr_data['items'][] = $arr_item;
				$iteration_count--;
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

	private function rd_syndication_credits( $thumb_id ) {
		$syndication_credits = array();
		$syndication_credits['author'] = $this->rd_get_image_credit( $thumb_id );
		$syndication_credits['attribution'] = $syndication_credits['author'];
		$syndication_credits['hasSyndicationRights'] = get_post_meta( $thumb_id, '_syndication_rights', true );
		if ( $syndication_credits['hasSyndicationRights'] == 0 ) {
			$syndication_credits['licenseId'] = get_post_meta( $thumb_id, '_image_licensor_id', true );
			$syndication_credits['licensorName'] = get_post_meta( $thumb_id, '_image_licensor_name', true );
		}
		return $syndication_credits;
	}



	private function rd_get_image_credit( $image_id ) {
		$credit_text = '';
		foreach ( [ 'photographer_credit_name', '_wp_attachment_source_name' ] as $key ) {
			$credit_text = get_post_meta( $image_id, $key, true );
			if ( ! empty( $credit_text ) ) { break; }
		}

		return trim( $credit_text );
	}

	private function clean_content( $content ) {
		$clean_content = $content;
		$clean_content = $this->remove_shortcode_videos( $clean_content );
		$clean_content = $this->remove_inline_videos( $clean_content );
		$clean_content = $this->remove_skyword_tracking( $clean_content );
		return $clean_content;
	}
}

