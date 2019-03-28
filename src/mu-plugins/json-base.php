<?php
/*
Plugin Name: JSON Base
Version: 1.2
Description: Provides a simple object framework built upon the feed base to create a json feed
Author: Mikel King
Author URI: http://mikelking.com
*/

//Debug::enable_error_reporting();

/**
 * Class Json_Base
 */
class Json_Base extends Feed_Base_Controller {
	const VERSION           = '1.2';
	const HTTP_CONTENT_TYPE = 'Content-Type: application/json; charset=UTF8';
	const DURATION          = 'hourly';
	const FREQUENCY         = 1;
	const META_BOX_CNTRL    = false;
	const MULTIPAGE_URLS    = true;
	const ITEM_LIMIT        = 15;
	const CACHE_MAX_AGE     = 14400; // seconds === 4 hours
	const FEED_ORDER_BY     = 'modified';
	const JSON_HEADER       = '{"items":[';
	const JSON_FOOTER       = ']}';

	private $json_content;
	public $body_content;

	public function add_feed() {
		// $this->pmc should hydrate the entire feed name this is messy
		add_feed( $this->feed_slug, array( $this, 'render_json_feed' ) );
	}

	public function render_json_feed() {
		$this->ob_begin();
		$this->print_json_header();
		$this->get_json_items();
		$this->print_json_footer();
		$this->ob_flush();
	}

	public function get_the_query() {
		$query = wp_cache_get( 'json_feed_query', 'jsf' );
		if ( $query == false ) {
			$query_args = array(
				'post_type'         => 'post',
				'posts_per_page'    => static::ITEM_LIMIT,
				'orderby'           => static::FEED_ORDER_BY,
				'order'             => 'desc',
			);
			$query = new WP_Query( $query_args );
			wp_cache_set( 'json_feed_query', $query, 'jsf', static::CACHE_MAX_AGE );
		}
		return( $query );
	}

	public function print_json_header() {
		print( self::JSON_HEADER );
	}

	public function print_json_footer() {
		print( self::JSON_FOOTER );
	}

	/**
	 *
	 */
	public function get_json_items() {
		$iteration_count = static::ITEM_LIMIT;
		//To exclude the inline ads in the article
		if (  has_filter( 'the_content', 'rdnap_the_content_single_post_ad' ) ) {
			remove_filter( 'the_content', 'rdnap_the_content_single_post_ad', 19 );
		}

		add_action( 'pre_get_posts', array( $this, 'rd_modify_query_exclude_slideshows' ) );

		$query = $this->get_the_query();

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

				$this->body_content = $this->get_body_content();
				$this->add_inline_image_credits();

				$arr_item['title'] = $this->get_the_post_title();
				$arr_item['body'] = $this->optimize_html_output( $this->body_content );
				$arr_item['author'] = $this->optimize_html_output( $author );
				$arr_item['id'] = get_the_ID();

				$arr_item['images'] = $this->get_rd_json_item_image();
				if ( $arr_item['images'] === false ) {
					unset( $arr_item['images'] );
				}
				$arr_item['link']['rel'] = 'self';
				$arr_item['link']['type'] = 'text/html';

				// Need to transform the URL for CDN optimization
				$arr_item['link']['href'] = RD_URL_Magick::get_cdn_permalink( get_the_permalink() );
				$arr_item['date']['published'] = $date_published;
				$arr_item['date']['updated'] = $date_modified;

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

	/**
	 * @param $data
	 */
	public function print_json_element( $data ) {
		$json_encoded_data = str_replace( self::BACKSPACE, '', json_encode( $data, JSON_FORCE_OBJECT ) );
		print( $json_encoded_data );

	}

	/**
	 * @return string
	 */
	private function get_body_content() {
		return( trim( get_the_content_feed() ) );
	}

	//Adding image credits for inline images
	/**
	 * @return string
	 */
	private function add_inline_image_credits() {
		preg_match_all( '/(<a .*?>)?(<img[^>]+>)(<\/a>)?/i', $this->body_content, $matches_img );
		$arr_matches = $matches_img[0];

		$doc = new DOMDocument();
		foreach ( $arr_matches as $image ) {
			$doc->loadHTML( $image );
			$imageTags = $doc->getElementsByTagName( 'img' );
			foreach ( $imageTags as $tag ) {
				$image_src = $tag->getAttribute( 'src' );
				$reg_img_dim = '/[_-]\d+x\d+(\.[a-z]{3,4})$/i';
				preg_match( $reg_img_dim, $image_src, $match_img_dim );
				if ( ! empty( $match_img_dim ) ) {
					$image_src = preg_replace( $reg_img_dim,'$1',$image_src );
				}
				$image_class = $tag->getAttribute( 'class' );
				preg_match_all( '/wp-image-(\d+)/i', $image_class, $matches_img_id );
				$str_image_tag = ''. PHP_EOL;
				if ( ! empty( $matches_img_id[1][0] ) ) {
					$image_id = $matches_img_id[1][0];
					$str_image_tag .= '<img src="'.$image_src.'" />'. PHP_EOL;

					$image_credit = $this->rd_get_image_credit( $image_id );
					if ( ! empty( $image_credit ) ) {
						$str_image_tag .= '<span class="credits-overlay">';
						$str_image_tag .= '<span class="image-credit">'.$image_credit.'</span>'. PHP_EOL;
						$str_image_tag .= '</span>'. PHP_EOL;
					}
				}
				$this->body_content  = str_replace( $image, $str_image_tag, $this->body_content );
			}
		}
		return( trim( $this->body_content ) );
	}

	public function get_the_header_content_type() {
		return ( self::HTTP_CONTENT_TYPE );
	}

	public function print_json_content() {
		print( $this->json_content );
	}

	function rd_modify_query_exclude_slideshows( $query ) {
		if ( ! $query->is_main_query()  ) {
			$query->set( 'post_type', 'post' );
		}
	}
	public function get_rd_json_item_author() {
		if ( class_exists( 'CoAuthorsIterator' ) ) {
			$post_id = get_the_ID();
			$author = new CoAuthorsIterator( $post_id );
			$author->iterate();
			$author_names = '';
			do {
				if ( $author->current_author ) {
					$author_names .= $author->current_author->display_name . ',';
				} else {
					return get_the_author();
				}
			} while ( $author->iterate() );
			return rtrim( $author_names, ',' );

		}else {
			return get_the_author();
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

			// }
			$arr_images[] = $arr_img;
		}

		return( $arr_images );
	}

	// We may need to change the out put to '' if $URL is empty but for now
	public function get_image_url( $title, $url = null ) {
		if ( stripos( $title, 'Sources' ) || $url === '' ) {
			return( RD_URL_Magick::get_default_image_url() );
		} else {
			return( RD_URL_Magick::get_cdn_url( $url ) );
		}
	}

	public function optimize_html_output( $raw_html_content = null ) {

		if ( ! isset( $raw_html_content ) ) {
			$raw_html_content = $this->body_content;
		}

		if ( $raw_html_content !== '' ) {
			$raw_html_content = str_replace( '<br/>', '. ', $raw_html_content );
			$raw_html_content = str_replace( '“', "'", $raw_html_content );
			$raw_html_content = str_replace( '”', "'", $raw_html_content );

			$raw_html_content = esc_html( str_replace( '"', "'", $raw_html_content ) );
			$raw_html_content = str_replace( self::LEFT_DBL_QUOTE, "'", $raw_html_content );

			$raw_html_content = mb_convert_encoding( $raw_html_content, 'HTML-ENTITIES', 'UTF-8' );
		}
		return( $raw_html_content );
	}

	private function rd_image_post_ids( $arr_images ) {
		$arr_uri = wp_list_pluck( $arr_images, 'author' );

		$str_img = implode( "','",$arr_uri );
		$str_img = "'".$str_img."'";
		global $wpdb;
		$sql = "SELECT post_id,meta_value  FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value IN( $str_img )";

		$arr_post_id = $wpdb->get_results( $sql, ARRAY_A );
		foreach ( $arr_post_id as $image ) {
			$arr_uri_credit[$image['meta_value']] = $this->rd_get_image_credit( $image['post_id'] );
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
