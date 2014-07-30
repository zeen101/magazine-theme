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
 
//Define global variables...
if ( !defined( 'ZEEN101_STORE_URL' ) )
	define( 'ZEEN101_STORE_URL',	'http://issuem.com' );
	
define( 'ZEEN101_THEME_NAME', 		'IssueM Magazine Theme' );
define( 'ZEEN101_THEME_SLUG', 		'issuem-magazine' );
define( 'ZEEN101_THEME_VERSION',	'1.0.0' );
define( 'ZEEN101_THEME_URL', 		get_template_directory_uri() );
define( 'ZEEN101_THEME_PATH', 		get_template_directory() );
define( 'ZEEN101_THEME_BASENAME', 	wp_basename( __FILE__ ) );
define( 'ZEEN101_THEME_REL_DIR', 	dirname( ZEEN101_THEME_BASENAME ) );

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

	}
	add_action( 'after_setup_theme', 'issuem_magazine_setup' );
	
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

if ( !function_exists( 'issuem_magazine_theme_menu' ) ) {

	function issuem_magazine_theme_menu() {
		
		add_theme_page( __( 'Theme License', 'issuem-magazine-theme' ), __( 'Theme License', 'issuem-magazine-theme' ), 'manage_options', 'issuem-magazine-theme-license', 'issuem_magazine_theme_license_page' );
		
	}
	add_action( 'admin_menu', 'issuem_magazine_theme_menu' );

}

if ( !function_exists( 'issuem_magazine_theme_license_page' ) ) {

	function issuem_magazine_theme_license_page() {
	
	$license_key 	= get_option( 'issuem_magazine_theme_license_key' );
	$license_status = get_option( 'issuem_magazine_theme_license_key_status' );
	?>
	<div class="wrap">
    <div style="width:70%;" class="postbox-container">
    <div class="metabox-holder">	
    <div class="meta-box-sortables ui-sortable">

        <h2 style='margin-bottom: 10px;' ><?php _e( 'IssueM Magazine Theme Settings', 'issuem-magazine-theme' ); ?></h2>

        <div id="license-key" class="postbox">
        
            <div class="handlediv" title="Click to toggle"><br /></div>
            
            <h3 class="hndle"><span><?php _e( 'License Key', 'issuem' ); ?></span></h3>
            
            <div class="inside">
                    
            <form method="post" action="options.php">

			<?php settings_fields( 'issuem_magazine_theme_license' ); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('License Key'); ?>
						</th>
						<td>
							<input id="issuem_magazine_theme_license_key" name="issuem_magazine_theme_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license_key ); ?>" />
                        
                            <?php if( $license_status !== false && $license_status == 'valid' ) { ?>
                                <span style="color:green;"><?php _e('active'); ?></span>
                                <input type="submit" class="button-secondary" name="issuem_magazine_theme_license_deactivate" value="<?php _e( 'Deactivate License', 'issuem-magazine-theme' ); ?>"/>
                            <?php } else { ?>
                                <input type="submit" class="button-secondary" name="issuem_magazine_theme_license_activate" value="<?php _e( 'Activate License', 'issuem-magazine-theme' ); ?>"/>
                            <?php } ?>
                            <?php wp_nonce_field( 'verify', 'license_wpnonce' ); ?>
                        </td>
                    </tr>
				</tbody>
			</table>	
			<?php submit_button(); ?>
            
            </div>
            
            </form>
        
        </div>
    
    </div>
    </div>
    </div>
    </div>
	<?php		
	}
	
}


function issuem_magazine_theme_register_option() {
		
	// creates our settings in the options table
	register_setting( 'issuem_magazine_theme_license', 'issuem_magazine_theme_license_key', 'issuem_magazine_theme_sanitize_license' );

	register_setting( 'issuem_magazine_theme_license', 'issuem_magazine_theme_license_status', 'issuem_magazine_theme_sanitize_license_status' );

	
}
add_action( 'admin_init', 'issuem_magazine_theme_register_option' );


function issuem_magazine_theme_sanitize_license( $new ) {
		
	$old = get_option( 'issuem_magazine_theme_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'issuem_magazine_theme_license_key_status' ); // new license has been entered, so must reactivate
	}
	return $new;
	
}

function issuem_magazine_theme_sanitize_license_status( $new ) {

	issuem_magazine_theme_activate_license();
	issuem_magazine_theme_deactivate_license();
	
	return $new;
	
}


function issuem_magazine_theme_activate_license() {

	if( isset( $_REQUEST['issuem_magazine_theme_license_activate'] ) ) { 
	
	 	if( ! check_admin_referer( 'verify', 'license_wpnonce' ) ) 	
			return; // get out if we didn't click the Activate button

		$license_key = trim( get_option( 'issuem_magazine_theme_license_key' ) );
	
		$api_params = array( 
			'edd_action'	=> 'activate_license', 
			'license'		=> $license_key, 
			'item_name'		=> urlencode( ZEEN101_THEME_NAME ) 
		);
		
		$response = wp_remote_get( add_query_arg( $api_params, ZEEN101_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		if ( is_wp_error( $response ) )
			return false;

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "active" or "inactive"

		update_option( 'issuem_magazine_theme_license_key_status', $license_data->license );

	}
}

/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function issuem_magazine_theme_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_REQUEST['issuem_magazine_theme_license_deactivate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'verify', 'license_wpnonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license_key = trim( get_option( 'issuem_magazine_theme_license_key' ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'	=> 'deactivate_license', 
			'license' 		=> $license_key, 
			'item_name' 	=> urlencode( ZEEN101_THEME_NAME ) // the name of our product in EDD
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, ZEEN101_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			
			delete_option( 'issuem_magazine_theme_license_key' );
			delete_option( 'issuem_magazine_theme_license_key_status' );
			
		}

	}
}

/**
 * Enables Theme Update via the WordPress Update API
 * 
 * @since 1.0.0
 *
 * @param object $transient Transient object of theme updates
 */
function issuem_magazine_update( $_transient_data ) {

	if( empty( $_transient_data->checked ) ) 
		return $_transient_data;
		
	$license_key = get_option( 'issuem_magazine_theme_license_key' );
		
	// The transient contains the 'checked' information
	// Now append to it information form your own API

	$to_send = array( 
		'slug'		=> ZEEN101_THEME_SLUG,
		'name'		=> ZEEN101_THEME_NAME,
		'license' 	=> $license_key,
	);

	$api_response = issuem_api_request( 'plugin_latest_version', $to_send );
	
	if( false !== $api_response && is_object( $api_response ) )
		if( version_compare( $api_response->new_version, $_transient_data->checked[ZEEN101_THEME_SLUG], '>' ) )
			$_transient_data->response[ZEEN101_THEME_SLUG] = (array)$api_response;
	
	return $_transient_data;
	
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
	wp_enqueue_style( 'issuem-specific-style', ZEEN101_THEME_URL . '/css/issuem.css', array( 'issuem-magazine-style' ), ZEEN101_THEME_VERSION );


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