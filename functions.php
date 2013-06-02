<?php
/**
 * IssueM Magazine functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package IssueM Magazine
 * @since 1.0.0
 */

/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 625;

if ( ! function_exists( 'issuem_magazine_setup' ) ) {

	function issuem_magazine_setup() {
		
		/* Make IssueM Magazine Theme available for translation.
		 * Translations can be added to the /i18n/ directory.
		 * If you're building a theme based on IssueM Magazine, use a find and replace
		 * to change 'issuem-magazine' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'issuem-magazine', get_template_directory() . '/i18n' );
		
		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();
		
		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );
	
		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'header-menu', __( 'Header Menu', 'issuem-magazine' ) );
		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'footer-menu', __( 'Footer Menu', 'issuem-magazine' ) );
	
		// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
		add_theme_support( 'post-thumbnails' );
			
		/*
		 * This theme supports custom background color and image, and here
		 * we also set up the default background color.
		 */
		add_theme_support( 'custom-background', array(
			'default-color' => 'FEFDF4',
		) );
	
		// We'll be using post thumbnails for custom header images on posts and pages.
		// We want them to be the size of the header image that we just defined
		// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
		set_post_thumbnail_size( 150, 150, true );
	
		// Used for article feature images.
		add_image_size( 'article-feature', 600, 375, true );
		// Used for large feature front-page images.
		add_image_size( 'large-feature', 450, 275, true );
		// Used for small feature front-page images.
		add_image_size( 'small-feature', 130, 130, true );
		// Used for small feature category-page images.
		add_image_size( 'small-cat-feature', 175, 175, true );

		
	}
	add_action( 'after_setup_theme', 'issuem_magazine_setup' );
	
}

/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/include/custom-header.php' );


if ( ! function_exists( 'issuem_magazine_widgets_init' ) ) {

	function issuem_magazine_widgets_init() {
	
		register_sidebar( array(
			'name' => __( 'Header Widgets', 'middlebury' ),
			'id' => 'header-widgets',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
		register_sidebar( array(
			'name' => __( 'Primary Widgets', 'middlebury' ),
			'id' => 'primary-widgets',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
		register_sidebar( array(
			'name' => __( 'Footer Widgets', 'middlebury' ),
			'id' => 'footer-widgets',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
	}
	add_action( 'widgets_init', 'issuem_magazine_widgets_init' );

}

/**
 * Checks to see if we're on the Current Issue page and redirects to taxonomy-issuem_issue.php template
 *
 * @since 1.0.0
 */
function issuem_magazine_current_issue_template_include( $template ) {
	
	$settings = get_issuem_settings();
		
	if ( 0 < $settings['page_for_articles'] && is_page( $settings['page_for_articles'] ) ) {
	
		return get_query_template( 'taxonomy', array( 'taxonomy-issuem_issue.php' ) );
		
	}
	
	return $template;
	
}
add_action( 'template_include', 'issuem_magazine_current_issue_template_include' );


/**
 * Enqueues scripts and styles for front-end.
 *
 * @since 1.0.0
 */
function issuem_magazine_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'issuem-magazine-style', get_stylesheet_uri() );

	/*
	 * Loads the Internet Explorer specific stylesheet.
	 */
	wp_enqueue_style( 'issuem-magazine-ie', get_template_directory_uri() . '/css/ie.css', array( 'issuem-magazine-style' ), '20121010' );
	$wp_styles->add_data( 'issuem-magazine-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'issuem_magazine_scripts_styles' );


if ( ! function_exists( 'issuem_magazine_entry_meta' ) ) {
	
	/**
	 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
	 *
	 * Create your own issuem-magazine_entry_meta() to override in a child theme.
	 *
	 * @since Twenty Twelve 1.0
	 */
	function issuem_magazine_entry_meta() {
		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list( __( ', ', 'issuem-magazine' ) );
	
		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', __( ', ', 'issuem-magazine' ) );
	
		$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
	
		$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'issuem-magazine' ), get_the_author() ) ),
			get_the_author()
		);
	
		// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
		if ( $tag_list ) {
			$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'issuem-magazine' );
		} elseif ( $categories_list ) {
			$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'issuem-magazine' );
		} else {
			$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'issuem-magazine' );
		}
	
		printf(
			$utility_text,
			$categories_list,
			$tag_list,
			$date,
			$author
		);
	}
	
}