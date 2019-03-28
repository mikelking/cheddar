<?php
/**
 * Class Special_Json_Feed
 * see http://stackoverflow.com/questions/9907858/how-to-add-a-field-in-edit-post-page-inside-publish-box-in-wordpress
 */
require_once( __DIR__ . '/lib/msn-json-base.php' );
require_once( __DIR__ . '/lib/msn-fields-controller.php' );

class MSN_Articles_Json_Feed extends MSN_Json_Base {
	const SERVICE_NAME             = 'msn';
	const CONTENT_TYPE             = 'articles';
	const MYSQL_DATE_FMT           = 'c';
	const FEED_ORDER_BY            = 'publish_date';
	const FIELD_SHORT_TITLE_NAME   = 'msn_short_title';
	const FIELD_ABSTRACT_TEXT_NAME = 'dek';
	const ITEM_LIMIT               = 30;
	const CACHE_MAX_AGE            = 14400;
	const DEFAULT_ATTRIBUTION      = 'Provided by Reader\'s Digest';
	const DEFAULT_IMG_TITLE        = 'Reader\'s Digest Image';
	const DEFAULT_SYNDICATION      = 1;
	const HEADING_PATTERN          = '/<h[1-6].*?>.*?<\/h[1-6]>/i';
	const ANCHOR_PATTERN           = '/<a[\w\s\.]*href="([\w:\-\/\.]*)"[\w\s\.\=":>]+<\/a>/';
	const IMAGE_PATTERN            = '/<img[^>]+\>/i';


	private $debug_article_feed;
	public function __construct() {
		parent::__construct();
		$MSN_fields_object = new MSN_Fields_Controller();
		add_filter( 'the_content', array( $this, 'remove_skyword_tracking_code' ) );
		if ( ! empty( $_GET['debug_feed'] ) && $_GET['debug_feed'] == 'all' ) {
			$this->debug_article_feed = true;
		}else {
			$this->debug_article_feed = false;
		}
	}
	/*
	 *bind the callback function that returns an empty string
	 */
	public function remove_skyword_tracking_code( $content ) {
		if ( ! is_singular() ) {
			add_shortcode( 'skyword_tracking', '__return_false' );
			add_shortcode( 'cf', '__return_false' );
			return( $content );
		}
		return( $content );
	}
	public function get_the_post_title() {
		$title = get_post_meta( get_the_ID(), '_yoast_wpseo_opengraph-title', true );
		if ( ! isset( $title ) || $title === '' ) {
			$title = get_the_title_rss();
		}
		return( $title );
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
	public function get_json_items() {
		//To exclude the inline ads in the article
		if (  has_filter( 'the_content', 'rdnap_the_content_single_post_ad' ) ) {
			remove_filter( 'the_content', 'rdnap_the_content_single_post_ad', 19 );
		}
		add_action( 'pre_get_posts', array( $this, 'rd_modify_query_exclude_slideshows' ) );
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
				$this->body_content = $this->clean_content( $this->get_body_content() );
				$this->add_inline_image_credits();
				$arr_item['title'] = $this->get_the_post_title();
				$arr_item['body'] = $this->optimize_html_output( $this->body_content );
				$arr_item['author'] = $this->optimize_html_output( $author );
				$arr_item['id'] = get_the_ID();

				//added for WPDT-6079
				if ( has_tag( self::TAXONOMY_TAG_SLUG ) ) {
					$arr_item['keywords'] = ucfirst( self::TAXONOMY_KEYWORD );
				} else {
					unset( $arr_item['keywords'] );
				}
				//--------

				$arr_item['shortTitle'] = $this->optimize_html_output( get_post_meta( get_the_ID(), self::FIELD_SHORT_TITLE_NAME, true ) );
				$arr_item['abstractText'] = $this->optimize_html_output( get_post_meta( get_the_ID(), self::FIELD_ABSTRACT_TEXT_NAME, true ) );
				$arr_item['images'] = $this->get_rd_json_item_image();
				if ( $arr_item['images'] === false ) {
					unset( $arr_item['images'] );
				}
				$arr_item['link']['rel'] = 'self';
				$arr_item['link']['type'] = 'text/html';

				// Need to transform the URL for CDN optimization
				$arr_item['link']['href'] = RD_URL_Magick::get_cdn_permalink( get_the_permalink() );
				$arr_item['date']['dateTimeWritten'] = $date_published; //untill we find a reliable way.
				$arr_item['date']['published'] = $date_published;
				$arr_item['date']['updated'] = $date_modified;
				$iteration_count--;
				//WPDT-4932 - Modifications to include the listicles in the article feed.
				if ( has_shortcode( get_the_content(), 'rd_listicle' ) ) {
					$arr_item = $arr_item + $this->build_slideshow_metadata();
					$this->print_json_element( $arr_item );
				} else {
					$slideshow_pattern = '/slideshows(\d+)/';
					$keys = array_keys( $arr_item );
					$slideshow_keys = preg_grep( $slideshow_pattern, $keys );
					foreach ( $slideshow_keys as $slideshow_key ) {
						unset( $arr_item[$slideshow_key] );
					}
					$this->print_json_element( $arr_item );
				}
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

	public function build_slideshow_metadata() {
		//WPDT-4932 - Modifications to include the listicles in the article feed.
		$author = $this->get_rd_json_item_author();
		$post_content = get_the_content();
		$total_slideshows = false;
		if ( has_shortcode( $post_content, 'rd_listicle' ) ) {
			$listicle_pattern = '/\[rd_listicle(.*?)\]/'; // Shortcode Pattern
			preg_match_all( $listicle_pattern, $post_content, $listicle_matches ); // Find all the shortcodes in the content.
			$listicle_ids = array();
			foreach ( $listicle_matches[0] as $listicle_shortcode ) {
				$shortcode_attr_pattern = '/([A-Za-z-_0-9]*?)=[\'"]{0,1}(.*?)[\'"]{0,1}[\s|\]]/';
				preg_match_all( $shortcode_attr_pattern, $listicle_shortcode, $shortcode_attrs );
				$all_ids = $shortcode_attrs[2][0];
				array_push( $listicle_ids, $all_ids );
			}
			foreach ( $listicle_ids as $slide_index => $listicle_id ) {
				$slideshow_array      = array();
				$slideshow_msn_data   = array();
				$slide_number         = 'slideshows' . ( $slide_index + 1 );
				$get_listicle_post    = get_post( $listicle_id ); // Get listicle data using the Id
				$get_listicle_url     = get_the_permalink( $listicle_id ); // Listicle Url
				$get_listicle_content = $get_listicle_post->post_content; // Listicle Content
				$listicle_cards       = explode( '<!--nextpage-->', $get_listicle_content ); // Slide content
				$listicle_cards_count = count( $listicle_cards ); // Total no. of slides in the Listicle

				for ( $i = 0; $i < $listicle_cards_count; $i++ ) {
					$card_number   = $i + 1;
					$slideshow_url = $get_listicle_url . $card_number . '/';
					preg_match( '|<h[^>]+>(.*)</h[^>]+>|iU', $listicle_cards[$i], $headings ); // Find the H2 from the card content.
					$slideshow_title = strip_tags( $headings[1] ); // Strip any HTML tag.
					$doc             = new DOMDocument();
					libxml_use_internal_errors( true );
					$doc->loadHTML( $listicle_cards[$i] );
					libxml_use_internal_errors( false );
					$image_src      = $doc->getElementsByTagName( 'img' )->item( 0 )->getAttribute( 'src' ); // Get the 'src' attribute from the image tag
					$image_alt_text = $doc->getElementsByTagName( 'img' )->item( 0 )->getAttribute( 'alt' ); // Get the 'alt' attribute from the image tag
					preg_match( '/<img[^>]+\bclass="([^"]*\bwp-image-(\d+)\b[^"]*)"[^>]+>/i', $listicle_cards[$i], $img_id );
					$description       = preg_replace( array( self::HEADING_PATTERN, self::ANCHOR_PATTERN, self::IMAGE_PATTERN ), '', $listicle_cards[$i] );
					$clean_description = trim( $this->optimize_html_output( $description ) );
					$img_description   = strip_tags( $clean_description, '<a>' );
					$img_description   = mb_convert_encoding( $img_description, 'HTML-ENTITIES', 'UTF-8' );
					if ( empty( $img_description )  || ! isset( $img_description ) ) {
						$img_description = $image_alt_text;
					}
					if ( ! empty( $img_id[2] ) ) {
						$img_id = trim( $img_id[2] );
					} else {
						$sizes    = '/(-[0-9]*x[0-9]*\.)/';
						$original = preg_replace( $sizes, '.', $image_src );
						$original = $this->rd_get_image_uri_from_url( $original );
						$img_id   = attachment_url_to_postid( $original );
					}

					$syndication_rights = trim( get_post_meta( $img_id, '_syndication_rights', true ) );
					$license_id         = '';
					$licensor_name      = '';
					if ( $syndication_rights == '' ) {
						$syndication_rights = self::DEFAULT_SYNDICATION;
					} elseif ( $syndication_rights == 0 ) {
						$license_id    = get_post_meta( $img_id, '_image_licensor_id', true );
						$licensor_name = get_post_meta( $img_id, '_image_licensor_name', true );
					}

					$slideshow_msn_data['title']                = $slideshow_title;
					$slideshow_msn_data['author']               = $this->optimize_html_output( $author );
					$slideshow_msn_data['description']          = $img_description;
					$slideshow_msn_data['id']                   = $listicle_id;
					$slideshow_msn_data['url']                  = $slideshow_url;
					$slideshow_msn_data['thumbnail']            = $image_src;
					$slideshow_msn_data['hasSyndicationRights'] = $syndication_rights;
					if ( $syndication_rights != 1 ) {
						$slideshow_msn_data['licenseId']    = $license_id;
						$slideshow_msn_data['licensorName'] = $licensor_name;
					}
					$slideshow_array[] = $slideshow_msn_data;
				}
				$total_slideshows[$slide_number] = $slideshow_array;
			}
			return ( $total_slideshows );
		} else {
			return ( null );
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

			$image_title = get_the_title( $thumb_id );

			$arr_img['author'] = trim( $image_credit );
			$arr_img['title'] = trim( $image_title );

			//New MSN Req
			$arr_img['attribution'] = trim( $image_credit );
			$arr_img['hasSyndicationRights'] = trim( get_post_meta( $thumb_id, '_syndication_rights', true ) );
			if ( empty( $arr_img['hasSyndicationRights'] ) ) {
				$arr_img['hasSyndicationRights'] = 0;
			}
			if ( $arr_img['hasSyndicationRights'] == 0 ) {
				$arr_img['licenseId'] = get_post_meta( $thumb_id, '_image_licensor_id', true );
				$arr_img['licensorName'] = get_post_meta( $thumb_id, '_image_licensor_name', true );
			}

			$arr_images[] = $arr_img;
		}

		return( $arr_images );
	}
	/**
	 * @return string
	 */
	public function get_body_content() {
		$content = get_the_content();
		if ( has_shortcode( $content, 'rd_listicle' ) ) {
			$content = $this->remove_shortcode_listicle( $content );
		} else {
			$content = trim( get_the_content_feed() );
		}
		return  ( $content );
	}

	private function clean_content( $content ) {
		$clean_content = $content;
		$clean_content = $this->remove_inline_videos( $clean_content );
		$clean_content = $this->remove_skyword_tracking( $clean_content );
		$clean_content = $this->remove_shortcode_videos( $clean_content );
		return $clean_content;
	}
}
