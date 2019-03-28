<?php
/*
* Readers Digest Slideshow Feed Class
* By: Antonio Fernandes and Wilson Cursino
* Version: 0.0.1
*/

class Slideshows_Feed {

	public $arr_ss;
	public $slide_content;
	public $reg_URL;
	public $reg_desc;
	public $reg_dek;
	public $reg_title;
	public $reg_credit;

	function __construct($content) {
		$this->arr_ss = array_unique( explode( '[slideshow]', $content ) );

		$this->slide_content = '';

		$this->reg_URL = '#url=\"[^"]*#';
		$this->reg_desc = '#desc=\"[^"]*#';
		$this->reg_title = '#title=\"[^"]*#';
		$this->reg_credit = '#credit=\"[^"]*#';

	}

	function slide_content($regex_pattern, $subject, $field) {
		$return_value = preg_match( $regex_pattern, $subject, $matches );
		if ( $return_value == 1 && $matches != null ) {
			switch ( $field ) {
				case 'image':
					$split_string = explode( 'url="',$matches[0] );
					break;
				case 'desc':
					$split_string = explode( 'desc="',$matches[0] );
					break;
				case 'title':
					$split_string = explode( 'title="',$matches[0] );
					break;
				case 'credit':
					$split_string = explode( 'credit="',$matches[0] );
					break;


			}
			if ( ! empty( $split_string ) ) {
				foreach ( $split_string as $value ) {
					if ( $value != '' ) {
						$str_return_value = $value; }
				}
			}
		}
		return $str_return_value ? $str_return_value : '';
	}


	function title($value) {
		$str_return_title = $this->slide_content( $this->reg_title, $value, 'title' );
		if ( $str_return_title != '' ) {
			return htmlspecialchars_decode( $str_return_title );
		}
	}

	function image($value, $count = 0, $ad_position = '') {

		$image_url = $this->slide_content( $this->reg_URL, $value, 'image' );

		return $image_url;
	}


	function desc($value) {
		$description = $this->slide_content( $this->reg_desc, $value, 'desc' );
		if ( ! empty( $description ) ) {
			$content = stripslashes( htmlspecialchars_decode( $description ) );

			return do_shortcode( $content );
		}

	}

	function credit($value) {
		$image_url = $this->slide_content( $this->reg_URL, $value, 'image' );

		if ( $image_url != '' ) {
			$id = self::get_attachment_id_by_slide_url( $image_url );

			foreach ( [ 'photographer_credit_name', '_wp_attachment_source_name' ] as $key ) {
				$credit_text = get_post_meta( $id, $key, true );
				if ( ! empty( $credit_text ) ) { break; }
			}
		}

		if ( trim( $credit_text ) == '' ) {
			$credit_text = get_bloginfo_rss( 'name' );
		}

		return $credit_text;
	}

	public static function get_attachment_id_by_slide_url($image_src) {
		global $wpdb;
		$query = "SELECT ID FROM {$wpdb->posts} WHERE guid = '$image_src' limit 0,1";
		$id = $wpdb->get_var( $query );
		return ( ! empty( $id )) ? $id : null;
	}

	public static function search_credit($image_url) {
		$id = self::get_attachment_id_by_slide_url( $image_url );

		foreach ( [ 'photographer_credit_name', '_wp_attachment_source_name' ] as $key ) {
			$credit_text = get_post_meta( $id, $key, true );
			if ( ! empty( $credit_text ) ) { break; }
		}
		if ( empty( $credit_text ) ) {
			$credit_text = get_bloginfo_rss( 'name' );
		}
		return $credit_text;
	}



}    //closing slideshow class
?>
