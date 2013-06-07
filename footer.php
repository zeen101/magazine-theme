<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package IssueM Magazine
 * @since 1.0.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">

		<div id="footer-widget-area">
			<?php if ( is_active_sidebar( 'footer-widget-1' ) ) : ?>
				<?php dynamic_sidebar( 'footer-widget-1' ); ?>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
				<?php dynamic_sidebar( 'footer-widget-2' ); ?>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
				<?php dynamic_sidebar( 'footer-widget-3' ); ?>
			<?php endif; ?>
		</div>
		
		<nav id="footer-navigation" class="footer-navigation" role="navigation">
			<h3 class="menu-toggle"><?php _e( 'Menu', 'issuem-magazine' ); ?></h3>
			<a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'issuem-magazine' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a>
			<?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'nav-menu' ) ); ?>
		</nav><!-- #site-navigation -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<div class="site-info">
    <a href="http://issuem.com/" title="<?php esc_attr_e( 'Magazine Publishing Platform for WordPress', 'issuem-magazine' ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentytwelve' ), 'IssueM' ); ?></a>
</div><!-- .site-info -->

<?php wp_footer(); ?>
</body>
</html>