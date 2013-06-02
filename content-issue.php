<?php
/**
 * @package IssueM Magazine Theme
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
    	<?php 
		if ( $content = get_the_content() ) {
			echo "yep";
			echo the_content();
		} else {
			echo do_shortcode( '[issuem_issue_title] [issuem_featured_rotator] [issuem_featured_thumbnails max_images="3"] [issuem_articles show_featured="0"]' );
		}
		?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
