<?php
/*
	Plugin Name: LinkPress
	Plugin URI: http://nowhere-anymore.com
	Description: Easily link to JIRA issues and GitHub pull requests from WordPress. *** New & improved ***
	Version: 1.3
	Author: Mikel King
*/

// https://secure.php.net/manual/en/language.namespaces.php
//namespace freeBacon

require( 'inc/linkpress-controller.php' );
/**
 * Class LinkPress_Controller
 * @todo experiment w/ php namespaces
 */
class LinkPress extends WP_Base {
	const VERSION  = '1.4';

	public function __construct() {
		$lpc = new LinkPress_Controller();
		add_filter('the_content', array( $lpc, 'content_filter') );
	}

}

$lp = LinkPress::get_instance();

