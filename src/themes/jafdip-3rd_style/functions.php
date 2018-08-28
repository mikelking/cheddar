<?php
/**
 * Functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, thirdstyle_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'thirdstyle_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Third_Style
 * @since Third Style 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run thirdstyle_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'thirdstyle_setup' );

if ( ! function_exists( 'thirdstyle_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override thirdstyle_setup() in a child theme, add your own thirdstyle_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Third Style 1.0
 */
function thirdstyle_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'thirdstyle', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'thirdstyle' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '' );
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '%s/images/headers/sky.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to thirdstyle_header_image_width and thirdstyle_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'thirdstyle_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'thirdstyle_header_image_height', 198 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See thirdstyle_admin_header_style(), below.
	add_custom_image_header( '', 'thirdstyle_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'abstract1' => array(
			'url' => '%s/images/headers/abstract1.jpg',
			'thumbnail_url' => '%s/images/headers/abstract1-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Abstract #1', 'thirdstyle' )
		),
		'abstract2' => array(
			'url' => '%s/images/headers/abstract2.jpg',
			'thumbnail_url' => '%s/images/headers/abstract2-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Abstract #2', 'thirdstyle' )
		),
		'abstract3' => array(
			'url' => '%s/images/headers/abstract3.jpg',
			'thumbnail_url' => '%s/images/headers/abstract3-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Abstract #3', 'thirdstyle' )
		),
		'dune' => array(
			'url' => '%s/images/headers/dune.jpg',
			'thumbnail_url' => '%s/images/headers/dune-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Dune', 'thirdstyle' )
		),
		'grass' => array(
			'url' => '%s/images/headers/grass.jpg',
			'thumbnail_url' => '%s/images/headers/grass-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Grass and Sky', 'thirdstyle' )
		),
		'montage' => array(
			'url' => '%s/images/headers/montage.jpg',
			'thumbnail_url' => '%s/images/headers/montage-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Colorfull montage', 'thirdstyle' )
		),
		'nyc1' => array(
			'url' => '%s/images/headers/nyc1.jpg',
			'thumbnail_url' => '%s/images/headers/nyc1-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'New York City #1', 'thirdstyle' )
		),
		'nyc2' => array(
			'url' => '%s/images/headers/nyc2.jpg',
			'thumbnail_url' => '%s/images/headers/nyc2-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'New York City #2', 'thirdstyle' )
		),
		'nyc3' => array(
			'url' => '%s/images/headers/nyc3.jpg',
			'thumbnail_url' => '%s/images/headers/nyc3-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'New York City #3', 'thirdstyle' )
		),
		'planet' => array(
			'url' => '%s/images/headers/planet.jpg',
			'thumbnail_url' => '%s/images/headers/planet-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Planet', 'thirdstyle' )
		),
		'sky' => array(
			'url' => '%s/images/headers/sky.jpg',
			'thumbnail_url' => '%s/images/headers/sky-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Sky', 'thirdstyle' )
		)
	) );
}
endif;

if ( ! function_exists( 'thirdstyle_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in thirdstyle_setup().
 *
 * @since Third Style 1.0
 */
function thirdstyle_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Third Style 1.0
 */
function thirdstyle_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'thirdstyle_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Third Style 1.0
 * @return int
 */
function thirdstyle_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'thirdstyle_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Third Style 1.0
 * @return string "Continue Reading" link
 */
function thirdstyle_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'thirdstyle' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and thirdstyle_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Third Style 1.0
 * @return string An ellipsis
 */
function thirdstyle_auto_excerpt_more( $more ) {
	return ' &hellip;' . thirdstyle_continue_reading_link();
}
add_filter( 'excerpt_more', 'thirdstyle_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Third Style 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function thirdstyle_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= thirdstyle_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'thirdstyle_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Third Style's style.css.
 *
 * @since Third Style 1.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function thirdstyle_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'thirdstyle_remove_gallery_css' );

if ( ! function_exists( 'thirdstyle_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own thirdstyle_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Third Style 1.0
 */
function thirdstyle_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'thirdstyle' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'thirdstyle' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'thirdstyle' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'thirdstyle' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'thirdstyle' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'thirdstyle'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override thirdstyle_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Third Style 1.0
 * @uses register_sidebar
 */
function thirdstyle_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'thirdstyle' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'thirdstyle' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'thirdstyle' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'thirdstyle' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'thirdstyle' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'thirdstyle' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'thirdstyle' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'thirdstyle' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'thirdstyle' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'thirdstyle' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'thirdstyle' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'thirdstyle' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running thirdstyle_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'thirdstyle_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Third Style 1.0
 */
function thirdstyle_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'thirdstyle_remove_recent_comments_style' );

if ( ! function_exists( 'thirdstyle_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Third Style 1.0
 */
function thirdstyle_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'thirdstyle' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'thirdstyle' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'thirdstyle_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Third Style 1.0
 */
function thirdstyle_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'thirdstyle' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'thirdstyle' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'thirdstyle' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


function get_browser_language() {
	$browser_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
	

}

