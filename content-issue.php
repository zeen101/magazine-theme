<?php
/**
 * @package IssueM Magazine Theme
 * @since 1.0.0
 */

// Remove the Default IssueM Content Filter, we're going to handle it on our own
remove_filter( 'the_content', 'default_issue_content_filter', 5 );

function issuem_article_trim_excerpt( $excerpt ) {
	return str_replace( '[...]', '...', $excerpt );
}
add_filter( 'wp_trim_excerpt', 'issuem_article_trim_excerpt' );

function issuem_article_excerpt_more( $more ) {
	return '<p class="read-more"><a href="'. get_permalink( get_the_ID() ) . '">Read More</a></p>';
}
add_filter( 'excerpt_more', 'issuem_article_excerpt_more' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
    	<?php 
		if ( $content = get_the_content() ) {
			echo the_content();
		} else {
			$article_format = <<<END
<p class="issuem_article_category">%CATEGORY[1]%</p>
<p class="issuem_article_title"><a class="issuem_article_link" href="%URL%">%TITLE%</a></p>
<p class="issuem_article_byline">%BYLINE%</p>
<p class="issuem_article_content">%EXCERPT%</p>
END;

			echo do_shortcode( '[issuem_issue_title] [issuem_featured_rotator] [issuem_featured_thumbnails max_images="3"] [issuem_articles show_featured="0"]' . $article_format . '[/issuem_articles]' );
		}
		?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'twentyeleven' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
