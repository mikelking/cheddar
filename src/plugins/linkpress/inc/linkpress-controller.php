<?php

/**
 * Class LinkPress_Controller
 * @todo experiment w/ php namespaces
 */
class LinkPress_Controller {
	const VERSION  = '1.4';
	const URL_FMT = '<a href="%s%s">%s</a>';

	public $prefixes_stack = array(
		'wp'       => 'https://haymarket.atlassian.net/browse/',
		'mr'       => 'https://gitlab.com/mikelking/HmEvents/merge_requests/',
		'bnb_pr'   => 'https://github.com/ReadersDigest/birdsandblooms/pull/'
	);

	public function __construct() {}

	/**
	 * TMBI added by Mikel King
	 * @param $needle
	 * @return string
	 */
	public function issue_linker( $issue ) {
		$parts = explode( "-", $issue );
		$needle = $parts[0];

		$result = array_search( $needle, array_keys( $this->prefixes_stack ) );
		if ( $result !== false ) {
			if (stripos( $this->prefixes_stack[$needle], 'atlassian' ) ) {
				$output = sprintf( self::URL_FMT, $this->prefixes_stack[$needle], $issue, $issue );
			} elseif (stripos( $this->prefixes_stack[$needle], 'gitlab' ) ) {
				$output = sprintf( self::URL_FMT, $this->prefixes_stack[$needle], $parts[1], $issue );
			}
			 elseif (stripos( $this->prefixes_stack[$needle], 'github' ) ) {
				$output = sprintf( self::URL_FMT, $this->prefixes_stack[$needle], $parts[1], $issue );
			}
			return ( $output );
		}
		return( null );
	}

	/**
	 * Here the jira issues strings are replaced by links.
	 *
	 * @param array $matches
	 * @return string
	 */
	public function external_linker( $matches ) {
		$output = '';
		foreach ( $matches as $match ) {
			$issue = strtolower( trim( $match, '[]' ) );
			$output .= $this->issue_linker( $issue );
		}

		if( $output && $output != '' ) {
			return( $output );
		}

		return( null );
	}

	/**
	 *
	 * Scans the content for:
	 *     jira issues in format [xx-123]
	 *     github pull requests [xxx_pr-123]
	 *
	 * @param $content
	 * @return mixed
	 */
	public function content_filter( $content ) {
		$patterns = array(
			'/\[[A-Za-z0-9]+\-[0-9]+\]/',
			'/\[[A-Za-z0-9]+\_[A-Za-z0-9]+\-[0-9]+\]/'
		);
		$output = preg_replace_callback (
			$patterns,
			function ( $matches ) {
				$issue = strtolower( trim( $matches[0], '[]' ) );
				$output = $this->issue_linker( $issue );
				if( $output ) {
					return( $output );
				}
			},
			$content
		);
		return( $output );
	}
}
