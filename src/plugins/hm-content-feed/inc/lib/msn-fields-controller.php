<?php

class MSN_Fields_Controller  {
	//Attribution
	const FIELD_ATTRIBUTION_NAME		= 'attribution';
	const FIELD_ATTRIBUTION_LABEL		= 'Attribution';
	const FIELD_ATTRIBUTION_HELP		= 'Name of the photographer.';
	const FIELD_ATTRIBUTION_META		= '_attribution';
	const FIELD_ATTRIBUTION_REQUIRED	= true;
	//Syndication Rights
	const FIELD_SYNDC_RIGHT_NAME		= 'syndication_rights';
	const FIELD_SYNDC_RIGHT_LABEL		= 'Syndication Rights';
	const FIELD_SYNDC_RIGHT_HELP		= 'Does provider has the rights to syndicate the image.';
	const FIELD_SYNDC_RIGHT_META		= '_syndication_rights';
	const FIELD_SYNDC_RIGHT_REQUIRED	= true;
	//Image Licensor Id
	const FIELD_LICSENSOR_ID_NAME		= 'image_licensor_id';
	const FIELD_LICSENSOR_ID_LABEL		= 'Image Licensor Id';
	const FIELD_LICSENSOR_ID_HELP		= 'Image unique id.';
	const FIELD_LICSENSOR_ID_META		= '_image_licensor_id';
	const FIELD_LICSENSOR_ID_REQUIRED	= false;
	//Image Licensor Name
	const FIELD_LICSENSOR_NAME_NAME		= 'image_licensor_name';
	const FIELD_LICSENSOR_NAME_LABEL	= 'Image Licensor Name';
	const FIELD_LICSENSOR_NAME_HELP		= 'Image Licensor Name.';
	const FIELD_LICSENSOR_NAME_META		= '_image_licensor_name';
	const FIELD_LICSENSOR_NAME_REQUIRED	= false;
	//Short Title
	const FIELD_SHORT_TITLE_NAME		= 'msn_short_title';
	const FIELD_SHORT_TITLE_LABEL		= 'MSN Short Title';
	const FIELD_SHORT_TITLE_LIMIT		= '(50 Chars Max)';
	const FIELD_TXT_SHORT_TITLE			= 'txt_msn_short_title';
	//Add to MSN feed Checkbox
	const FIELD_ADD_CHKBOX_NAME			= 'msn_feed';
	const FIELD_ADD_CHKBOX_TITLE		= 'MSN Feed';
	const FIELD_ADD_CHKBOX_HELP			= 'Add to MSN Feed';
	const FIELD_TXT_ADD_CHKBOX			= 'chk_new_msn_feed';

	public function __construct() {
		//Media Fields
		add_filter( 'attachment_fields_to_edit',  array( $this, 'add_image_attachment_fields_to_edit' ), 10, 2 );
		add_filter( 'attachment_fields_to_save',  array( $this, 'add_image_attachment_fields_to_save' ), 10, 2 );

		//Short title and Add to Feed option
		add_action( 'add_meta_boxes', array( $this, 'msn_add_controls' ) );
		add_action( 'save_post', array( $this, 'msn_save_controls' ) );
	}

	public function add_image_attachment_fields_to_edit($form_fields, $post) {

		/*$form_fields[self::FIELD_ATTRIBUTION_NAME] = array(
            'label'     => __( self::FIELD_ATTRIBUTION_LABEL ),
            'value'     => get_post_meta( $post->ID, self::FIELD_ATTRIBUTION_META, true ),
            'required'  => self::FIELD_ATTRIBUTION_REQUIRED,
            'helps'     => __( self::FIELD_ATTRIBUTION_HELP ),
		);*/

		$form_fields[self::FIELD_SYNDC_RIGHT_NAME] = array(
			'label' 	=> __( self::FIELD_SYNDC_RIGHT_LABEL ),
			'input' 	=> 'html',
			'html' 		=> $this->get_syndicate_values( $post->ID ),
			'required'	=> self::FIELD_SYNDC_RIGHT_REQUIRED,
			'helps' 	=> __( self::FIELD_SYNDC_RIGHT_HELP ),
		);

		$form_fields[self::FIELD_LICSENSOR_ID_NAME] = array(
			'label' 	=> __( self::FIELD_LICSENSOR_ID_LABEL ),
			'value' 	=> get_post_meta( $post->ID, self::FIELD_LICSENSOR_ID_META, true ),
			'required'	=> self::FIELD_LICSENSOR_ID_REQUIRED,
			'helps' 	=> __( self::FIELD_LICSENSOR_ID_HELP ),
		);

		$form_fields[self::FIELD_LICSENSOR_NAME_NAME] = array(
			'label' 	=> __( self::FIELD_LICSENSOR_NAME_LABEL ),
			'value' 	=> get_post_meta( $post->ID, self::FIELD_LICSENSOR_NAME_META, true ),
			'required'	=> self::FIELD_LICSENSOR_NAME_REQUIRED,
			'helps' 	=> __( self::FIELD_LICSENSOR_NAME_HELP ),
		);

		return $form_fields;
	}

	public function get_syndicate_values($post_id) {
		$syndication_rights_value = trim( get_post_meta( $post_id, self::FIELD_SYNDC_RIGHT_META, true ) );
		$option_yes = '';
		$option_no = '';
		if ( $syndication_rights_value == 1 || $syndication_rights_value == '' ) {
			$option_yes = 'selected';
		} else if ( $syndication_rights_value == 0 ) {
			$option_yes = 'selected';
		}

		$str_return = "<select name='attachments[{$post_id}][".self::FIELD_SYNDC_RIGHT_NAME."]'";
		$str_return .= "id='attachments[{$post_id}][".self::FIELD_SYNDC_RIGHT_NAME."]'>";
		$str_return .= "<option value='0' ".$option_no.'>No</option>';
		$str_return .= "<option value='1' ".$option_yes.'>Yes</option>';
		$str_return .= '</select>';

		return $str_return;
	}

	public function add_image_attachment_fields_to_save( $post, $attachment ) {
		/*if ( isset( $attachment[self::FIELD_ATTRIBUTION_NAME] ) ) {
            update_post_meta( $post['ID'], self::FIELD_ATTRIBUTION_META, $attachment[self::FIELD_ATTRIBUTION_NAME] );
		}*/
		if ( isset( $attachment[self::FIELD_SYNDC_RIGHT_NAME] ) ) {
			update_post_meta( $post['ID'], self::FIELD_SYNDC_RIGHT_META, $attachment[self::FIELD_SYNDC_RIGHT_NAME] );
		}
		if ( isset( $attachment[self::FIELD_LICSENSOR_ID_NAME] ) ) {
			update_post_meta( $post['ID'], self::FIELD_LICSENSOR_ID_META, $attachment[self::FIELD_LICSENSOR_ID_NAME] );
		}
		if ( isset( $attachment[self::FIELD_LICSENSOR_NAME_NAME] ) ) {
			update_post_meta( $post['ID'], self::FIELD_LICSENSOR_NAME_META, $attachment[self::FIELD_LICSENSOR_NAME_NAME] );
		}
		return $post;
	}

	public function msn_add_controls() {
		add_meta_box(
			self::FIELD_SHORT_TITLE_NAME,
			__( self::FIELD_SHORT_TITLE_LABEL ),
			array( $this, 'msn_short_title_handler' ),
			array( 'post', 'listicle' ),
			'normal',
			'high'
		);
		/*add_meta_box(
            self::FIELD_ADD_CHKBOX_NAME,
            __( self::FIELD_ADD_CHKBOX_TITLE ),
            array( $this, 'msn_add_feed_handler' ),
            array( 'post', 'slideshows', 'listicle' ),
            'normal',
            'high'
		);*/

	}
	public function msn_short_title_handler( $post ) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'msn_new_feed_noncename' );

		$meta_value = get_post_meta( $post->ID, self::FIELD_SHORT_TITLE_NAME, true );

		echo '<label for="'.self::FIELD_TXT_SHORT_TITLE.'">'.esc_html_e( self::FIELD_SHORT_TITLE_LABEL .' '.self::FIELD_SHORT_TITLE_LIMIT ). '</label> ';
		echo '<input class="widefat" type="text"  maxlength="50" name="'.self::FIELD_TXT_SHORT_TITLE.'" id="'.self::FIELD_TXT_SHORT_TITLE.'" value="'.esc_attr( $meta_value ).'" />';

	}

	function msn_save_controls( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( empty( $_POST['msn_new_feed_noncename'] ) || ! wp_verify_nonce( $_POST['msn_new_feed_noncename'], plugin_basename( __FILE__ ) ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }

		if ( isset( $_POST[self::FIELD_TXT_SHORT_TITLE] ) ) {
			$new_meta_value = trim( $_POST[self::FIELD_TXT_SHORT_TITLE] );
			$meta_value = get_post_meta( $post_id, self::FIELD_SHORT_TITLE_NAME, true );
			if ( $new_meta_value && '' == $meta_value ) {

				add_post_meta( $post_id, self::FIELD_SHORT_TITLE_NAME, $new_meta_value, true );

			} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {

				update_post_meta( $post_id, self::FIELD_SHORT_TITLE_NAME, $new_meta_value );

			} elseif ( '' == $new_meta_value && $meta_value ) {

				delete_post_meta( $post_id, self::FIELD_SHORT_TITLE_NAME, $meta_value );
			}
		}

		if ( isset( $_POST[self::FIELD_TXT_ADD_CHKBOX] ) ) {
			$new_add_feed_value = $_POST[self::FIELD_TXT_ADD_CHKBOX];
			$meta_add_feed_value = get_post_meta( $post_id, self::FIELD_ADD_CHKBOX_NAME, true );

			if ( $new_add_feed_value && '' == $meta_add_feed_value ) {

				add_post_meta( $post_id, self::FIELD_ADD_CHKBOX_NAME, $new_add_feed_value, true );

			} elseif ( $new_add_feed_value && $new_add_feed_value != $meta_add_feed_value ) {

				update_post_meta( $post_id, self::FIELD_ADD_CHKBOX_NAME, $new_add_feed_value );

			} elseif ( '' == $new_add_feed_value && $meta_add_feed_value ) {

				delete_post_meta( $post_id, self::FIELD_ADD_CHKBOX_NAME, $meta_add_feed_value );
			}
		}

	}

	public function msn_add_feed_handler($post) {
		wp_nonce_field( plugin_basename( __FILE__ ), 'msn_new_feed_noncename' );

		$meta_value = get_post_meta( $post->ID, self::FIELD_ADD_CHKBOX_NAME, true );

		if ( $meta_value != '' ) {
			$selected = 'checked ';
		}else {
			$selected = ' ';
		}

		echo '<input class="widefat" type="checkbox" name="'.self::FIELD_TXT_ADD_CHKBOX.'" id="'.self::FIELD_TXT_ADD_CHKBOX.'" '.$selected.'/>';
		echo '<label for="'.self::FIELD_TXT_ADD_CHKBOX.'">' . esc_html_e( self::FIELD_ADD_CHKBOX_HELP ) .  '</label> ';

	}

}
