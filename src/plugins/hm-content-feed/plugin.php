<?php
/*
Plugin Name: JSON Content Feed
Version: 1.0
Description: Provides custom JSON feeds
Author: Mikel King
Text Domain: json-content-feed
License: BSD(3 Clause)
License URI: http://opensource.org/licenses/BSD-3-Clause

    Copyright (C) 2017, Mikel King, rd.com, (mikel.king AT rd DOT com), archanadevi.1@ness.com, (archanadevi.1 AT ness DOT com), ayub.khan@ness.com, (ayub.khan AT ness DOT com)
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:

        * Redistributions of source code must retain the above copyright notice, this
        list of conditions and the following disclaimer.

        * Redistributions in binary form must reproduce the above copyright notice,
        this list of conditions and the following disclaimer in the documentation
        and/or other materials provided with the distribution.

        * Neither the name of the {organization} nor the names of its
        contributors may be used to endorse or promote products derived from
        this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
    AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
    IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
    FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
    DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
    SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
    CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
    OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
    OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

require( 'inc/special-rss-feed.php' );
require( 'inc/special-json-feed.php' );
#require( 'inc/msn-articles-json-feed.php' );
#require( 'inc/msn-kids-articles-json-feed.php' );
#require( 'inc/msn-slideshows-json-feed.php' );
#require( 'inc/msn-listicle-json-feed.php' );
#require( 'inc/msn-videos-json-feed.php' );
#require( 'inc/msn-kids-listicle-json-feed.php' );

/**
 * Class JSON_Feed_Controller
 * see http://stackoverflow.com/questions/9907858/how-to-add-a-field-in-edit-post-page-inside-publish-box-in-wordpress
 */
class JSON_Feed_Controller extends WP_Base {
	const VERSION              = '1.0';

	public function __construct() {
		//$srf  = Special_RSS_Feed::get_instance();
		$sjf  = Special_Json_Feed::get_instance();
		/*
		$majf = MSN_Articles_Json_Feed::get_instance();
		$majf = MSN_Kids_Articles_Json_Feed::get_instance();
		$msjf = MSN_Slideshows_Json_Feed::get_instance();
		$mslf = MSN_Listicle_Json_Feed::get_instance();
		$mvjf = MSN_Videos_Json_Feed::get_instance();
		$mkljf = MSN_Kids_Listicle_Json_Feed::get_instance();
		*/
	}
}

JSON_Feed_Controller::get_instance();
