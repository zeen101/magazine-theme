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
 
define( 'ISSUEM_MAGAZINE_THEME_SLUG', 		'issuem-magazine' );
define( 'ISSUEM_MAGAZINE_THEME_VERSION',	'1.0.0' );
define( 'ISSUEM_MAGAZINE_THEME_URL', 		get_template_directory_uri() );
define( 'ISSUEM_MAGAZINE_THEME_PATH', 		get_template_directory() );
define( 'ISSUEM_MAGAZINE_THEME_BASENAME', 	wp_basename( __FILE__ ) );
define( 'ISSUEM_MAGAZINE_THEME_REL_DIR', 	dirname( ISSUEM_MAGAZINE_THEME_BASENAME ) );

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
		add_image_size( 'article-feature', 625, 375, true );
		// Used for large feature front-page images.
		add_image_size( 'large-feature', 450, 275, true );
		// Used for small feature front-page images.
		add_image_size( 'small-feature', 130, 130, true );
		// Used for small feature category-page images.
		add_image_size( 'small-cat-feature', 175, 175, true );
		
		//We want to use our own scripts
		add_filter( 'enqueue_issuem_styles', '__return_false' );

		add_filter( 'pre_set_site_transient_update_themes', 'issuem_magazine_update' );
		
		if ( is_admin() ) {
			
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( !is_plugin_active( 'issuem/issuem.php' ) ) {
				
				add_action( 'admin_notices', 'activate_issuem_admin_notice' );
				
			}
			
		}
		
		if ( !function_exists( 'activate_issuem_admin_notice' ) ) {
		
			/**
			 * Helper function used print error nag if IssueM is not activated
			 *
			 * @since 1.0.0
			 */
			function activate_issuem_admin_notice() {
			
				?>
				
				<div class="error">
					<p><?php _e( "Error! You must have the IssueM plugin activated to use IssueM's Magazine Theme.", 'issuem-magazine' ); ?></p>
				</div>
				
				<?php
				
			}
			
		}

	}
	add_action( 'after_setup_theme', 'issuem_magazine_setup' );
	
}

/**
 * Enables Theme Update via the WordPress Update API
 * 
 * @since 1.0.0
 *
 * @param object $transient Transient object of theme updates
 */
function issuem_magazine_update( $transient ) {

	// Check if the transient contains the 'checked' information
	// If no, just return its value without hacking it
	if ( empty( $transient->checked ) )
		return $transient;

	// The transient contains the 'checked' information
	// Now append to it information form your own API
	$theme_slug = ISSUEM_MAGAZINE_THEME_SLUG;
		
	// POST data to send to your API
	$args = array(
		'action'	=> 'check-latest-version',
		'slug'		=> $theme_slug
	);
	
	// Send request checking for an update
	$response = issuem_api_request( $args );
					
	// If there is a new version, modify the transient
	if ( isset( $response->new_version ) )
		if( version_compare( $response->new_version, $transient->checked[ISSUEM_MAGAZINE_THEME_SLUG], '>' ) )
			$transient->response[ISSUEM_MAGAZINE_THEME_SLUG] = (array)$response;
	
	return $transient;
	
}

/**
 * Adds support for a custom header image.
 */
require( get_template_directory() . '/include/custom-header.php' );


if ( ! function_exists( 'issuem_magazine_widgets_init' ) ) {

	function issuem_magazine_widgets_init() {
	
		register_sidebar( array(
			'name' => __( 'Header Widgets', 'issuem' ),
			'id' => 'header-widgets',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
		register_sidebar( array(
			'name' => __( 'Primary Widgets', 'issuem' ),
			'id' => 'primary-widgets',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
		register_sidebar( array(
			'name' => __( 'Footer Widget 1', 'issuem' ),
			'id' => 'footer-widget-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Footer Widget 2', 'issuem' ),
			'id' => 'footer-widget-2',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Footer Widget 3', 'issuem' ),
			'id' => 'footer-widget-3',
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
	 * Loads our theme javascript
	 */
	wp_enqueue_script( 'global', get_bloginfo('stylesheet_directory') . '/js/global.js', 'jquery' );

	/*
	 * Loads our theme stylesheets.
	 */
	wp_enqueue_style( 'issuem-magazine-style', get_stylesheet_uri() );
	wp_enqueue_style( 'issuem-specific-style', ISSUEM_MAGAZINE_THEME_URL . '/css/issuem.css', array( 'issuem-magazine-style' ), ISSUEM_MAGAZINE_THEME_VERSION );


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


if ( ! function_exists( 'issuem_magazine_content_nav' ) ) {

	/**
	 * Displays navigation to next/previous pages when applicable.
	 *
	 * @since 1.0.0
	 */
	function issuem_magazine_content_nav( $html_id ) {
		global $wp_query;
	
		$html_id = esc_attr( $html_id );
	
		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
				<h3 class="assistive-text"><?php _e( 'Post navigation', 'issuem-magazine' ); ?></h3>
				<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'issuem-magazine' ) ); ?></div>
				<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'issuem-magazine' ) ); ?></div>
			</nav><!-- #<?php echo $html_id; ?> .navigation -->
		<?php endif;
	}
	
}


if ( ! function_exists( 'issuem_magazine_comment' ) ) {
	
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own issuem_magazine_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since 1.0.0
	 */
	function issuem_magazine_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			// Display trackbacks differently than normal comments.
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
			<p><?php _e( 'Pingback:', 'issuem-magazine' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'issuem-magazine' ), '<span class="edit-link">', '</span>' ); ?></p>
		<?php
				break;
			default :
			// Proceed with normal comments.
			global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<header class="comment-meta comment-author vcard">
					<?php
						echo get_avatar( $comment, 44 );
						printf( '<cite class="fn">%1$s %2$s</cite>',
							get_comment_author_link(),
							// If current post author is also comment author, make it known visually.
							( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'issuem-magazine' ) . '</span>' : ''
						);
						printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
							esc_url( get_comment_link( $comment->comment_ID ) ),
							get_comment_time( 'c' ),
							/* translators: 1: date, 2: time */
							sprintf( __( '%1$s at %2$s', 'issuem-magazine' ), get_comment_date(), get_comment_time() )
						);
					?>
				</header><!-- .comment-meta -->
	
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'issuem-magazine' ); ?></p>
				<?php endif; ?>
	
				<section class="comment-content comment">
					<?php comment_text(); ?>
					<?php edit_comment_link( __( 'Edit', 'issuem-magazine' ), '<p class="edit-link">', '</p>' ); ?>
				</section><!-- .comment-content -->
	
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'issuem-magazine' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->
		<?php
			break;
		endswitch; // end comment_type check
	}
	
}

if ( !function_exists( 'include_issuem_articles' ) ) {

	/**
	 * We want to include Articles with Posts on the Author's pages
	 *
	 * @since 1.0.0
	 */
	function include_issuem_articles( $query ) {
		
		if ( !empty( $query->query_vars['post_type'] ) && 'nav_menu_item' === $query->query_vars['post_type'] )
			return;
		
		if ( is_author() )
			$query->set( 'post_type', array( 'post', 'article' ) );
					
	}
	add_action( 'pre_get_posts', 'include_issuem_articles' );
	
}

if ( !function_exists( 'issuem_magazine_article_meta' ) ) {

	function issuem_magazine_article_meta() {
	
		global $post;

		$author_name = get_the_author_meta( 'display_name' );

		$byline = sprintf( __( 'By %s | ', 'issuem' ), apply_filters( 'issuem_author_name', $author_name, $post->ID ) );

		echo $byline;
		
		$issues = get_the_terms( $post->ID, 'issuem_issue' );
		foreach( $issues as $issue ) {
		
			$issue_list[] = '<a href="' . add_query_arg( 'issue', $issue->slug, '/' ) . '">' . $issue->name . '</a>';
			
		}
		$article_issue = join( ' | ', $issue_list );
		echo $article_issue . ' | ';
		
		$issuem_settings = get_issuem_settings();
		if ( !empty( $issuem_settings['use_wp_taxonomies'] ) )
			$article_categories = get_the_term_list( $post->ID, 'category', '', ', ', ' | ' );
		else
			$article_categories = get_the_term_list( $post->ID, 'issuem_issue_categories', '', ', ', ' | ' );
		
		if ( '' != $article_categories )
			echo $article_categories;
			
		
	
	}
	
}