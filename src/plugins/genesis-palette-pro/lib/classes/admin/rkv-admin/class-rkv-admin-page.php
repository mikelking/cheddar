<?php
/**
 * Rkv Admin Page class.
 *
 * @package Rkv\Admin
 */

/**
 * Creates the page output.
 *
 * @package Rkv\Admin
 */
class Rkv_Admin_Page {

	/**
	 * ID of the admin menu and settings page.
	 *
	 * @var string
	 */
	public $page_id;

	/**
	 * Associative array of configuration options for the settings page.
	 *
	 * @var array
	 */
	public $page_ops;

	/**
	 * Tabs used on the page.
	 *
	 * @var array
	 */
	public $tabs = array();

	/**
	 * Adds the settings page.
	 *
	 * @param string $page_id      ID of the admin menu and settings page.
	 * @param array  $page_ops     Optional. Config options for settings page. Default is empty array.
	 * @param array  $tabs         Optional. Tabs to use on page.              Default is empty array.
	 */
	public function __construct( $page_id = '', array $page_ops = array(), array $tabs = array() ) {
		if ( empty( $page_id ) || empty( $page_ops ) ) {
			return;
		}

		$this->register_page( $page_id, $page_ops, $tabs );
	}

	/**
	 * Registers the settings page.
	 *
	 * @param string $page_id      ID of the admin menu and settings page.
	 * @param array  $page_ops     Optional. Config options for settings page. Default is empty array.
	 * @param array  $tabs         Optional. Tabs to use on page.              Default is empty array.
	 */
	public function register_page( $page_id, $page_ops = array(), $tabs = array() ) {
		$this->page_id = $page_id;

		if ( ! $this->page_id ) {
			return;
		}

		$this->page_ops = $this->page_ops ? $this->page_ops : (array) $page_ops;

		$this->page_ops = wp_parse_args(
			$this->page_ops,
			array(
				'type'             => 'form',
				'description'      => '',
				'save_button_text' => __( 'Save Changes', 'rkv-admin' ),
			)
		);

		$this->tabs = $tabs;
	}

	/**
	 * Default page callback.
	 *
	 * Generates a standard WordPress page.
	 * If the page_ops['type'] is "form" this will output the form markup.
	 *
	 * Includes a "before" and "after" hook which is fired before and after the page form.
	 */
	public function page() {
		printf( '<div class="wrap"><h1>%s</h1>', esc_html( get_admin_page_title() ) );

		if ( ! empty( $this->page_ops['description'] ) ) {
			printf( '<p>%s</p>', esc_html( $this->page_ops['description'] ) );
		}

		/**
		 * Runs at the top of the page for the dynamically created page.
		 *
		 * @param array $page_ops {
		 *     Page options.
		 *
		 *     @type string $type             The type of page.
		 *     @type string $title            Page Title.
		 *     @type string $save_button_text The save button text.
		 *     @type string $option_group     Specified option group.
		 * }
		 */
		do_action( "rkv_before_page_{$this->page_id}", $this->page_ops );

		if ( ! $this->do_tabs() ) {
			if ( 'form' === $this->page_ops['type'] ) {
				echo '<form method="POST" action="options.php">';
				settings_fields( $this->page_id );
			}

			$this->do_settings_sections();

			if ( 'form' === $this->page_ops['type'] ) {
				submit_button( $this->page_ops['save_button_text'] );
				echo '</form>';
			}
		}

		/**
		 * Runs at the bottom of the page for the dynamically created page.
		 *
		 * @param array $page_ops see rkv_before_page_{$this->page_id}.
		 */
		do_action( "rkv_after_page_{$this->page_id}", $this->page_ops );

		echo '</div>';
	}

	/**
	 * Outputs tabs if they are setup.
	 */
	public function do_tabs() {
		if ( empty( $this->tabs ) ) {
			return false;
		}

		$current_tab = empty( $_GET['current-tab'] ) ? 'default' : sanitize_title( str_replace( "{$this->page_id}-", '', $_GET['current-tab'] ) ); // WPCS: csrf ok.
		$url_base    = menu_page_url( $this->page_id, false );
		$single      = false;
		$do_sections = array();

		if ( $current_tab && isset( $this->tabs[ $current_tab ] ) ) {
			$single = empty( $this->tabs[ $current_tab ]['single'] ) ? false : true;

			if ( $single && isset( $this->tabs[ $current_tab ]['form'] ) && false === $this->tabs[ $current_tab ]['form'] ) {
				$this->page_ops['type'] = '';
			}
		}

		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $this->tabs as $section => $tab ) {
			if ( empty( $tab['label'] ) ) {
				continue;
			}

			$target = sprintf(
				'%1$s-%2$s',
				esc_attr( sanitize_title( $this->page_id ) ),
				esc_attr( sanitize_title( $section ) )
			);

			$rkv_tab = ' rkv-nav-tab';

			$href = "#{$target}";

			switch ( $single ) {
				case false:
					$href    = empty( $tab['single'] ) ? $href : add_query_arg( 'current-tab', $target, $url_base );
					$rkv_tab = empty( $tab['single'] ) ? $rkv_tab : '';

					if ( empty( $tab['single'] ) ) {
						$do_sections[] = $section;
					}
					break;
				case true:
					$href    = $current_tab === $section ? $href : add_query_arg( 'current-tab', $target, $url_base );
					$rkv_tab = $current_tab === $section ? $rkv_tab : '';
					if ( $current_tab === $section ) {
						$do_sections[] = $section;
					}
					break;
			}

			$href    = empty( $tab['href'] ) ? $href : $tab['href'];
			$rkv_tab = empty( $tab['href'] ) ? $rkv_tab : '';

			printf(
				'<a href="%1$s" class="nav-tab%2$s%3$s"%5$s>%4$s</a>',
				esc_url( $href ),
				$rkv_tab,
				$current_tab === $section ? ' nav-tab-active' : '',
				esc_html( $tab['label'] ),
				empty( $tab['target_blank'] ) ? '' : ' target="_blank"'
			);
		}
		echo '</h2>';

		if ( 'form' === $this->page_ops['type'] ) {
			echo '<form method="POST" action="options.php">';
			settings_fields( $this->page_id );
		}

		$this->do_settings_sections( $do_sections );

		if ( 'form' === $this->page_ops['type'] ) {
			submit_button( $this->page_ops['save_button_text'] );
			echo '</form>';
		}

		return true;
	}

	/**
	 * Prints out all settings sections added to a particular settings page
	 *
	 * Replaces WP do_settings_sections to allow wrapper for tab usage.
	 * Part of the Settings API. Use this in a settings page callback function
	 * to output all the sections and fields that were added to that $page with
	 * add_settings_section() and add_settings_field()
	 *
	 * @global $wp_settings_sections Storage array of all settings sections added to admin pages
	 * @global $wp_settings_fields Storage array of settings fields and info about their pages/sections
	 * @since 2.7.0
	 *
	 * @param mixed|array|string $sections Specific section or sections to output.
	 */
	public function do_settings_sections( $sections = array() ) {
		global $wp_settings_sections, $wp_settings_fields;

		if ( ! isset( $wp_settings_sections[ $this->page_id ] ) ) {
			return;
		}

		foreach ( (array) $wp_settings_sections[ $this->page_id ] as $section ) {
			if (
				empty( $sections ) ||
				( is_array( $sections ) && in_array( $section['id'], $sections, true ) ) ||
				( is_string( $sections ) && $sections === $section['id'] )
			) {
				printf(
					'<div id="%1$s-%2$s" class="section rkv-section%3$s">',
					esc_attr( sanitize_title( $this->page_id ) ),
					esc_attr( sanitize_title( $section['id'] ) ),
					empty( $this->tabs ) ? '' : ' rkv-section-tab hidden'
				);

				if ( $section['title'] ) {
					printf( '<h2>%s</h2>', esc_html( $section['title'] ) );
				}

				if ( $section['callback'] ) {
					call_user_func( $section['callback'], $section );
				}

				if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $this->page_id ] ) || ! isset( $wp_settings_fields[ $this->page_id ][ $section['id'] ] ) ) {
					continue;
				}
				echo '<table class="form-table">';
				do_settings_fields( $this->page_id, $section['id'] );
				echo '</table></div>';
			}
		}

		if ( empty( $this->tabs ) ) {
			return;
		}

		wp_enqueue_script( 'rkv-admin-scripts' );
		?>
		<script type="text/javascript"> jQuery( jQuery( '.rkv-nav-tab.nav-tab-active' ).attr( 'href' ) ).show(); </script>
		<?php
	}

}
