<?php
/**
 * The sidebar containing the Footer widget areas.
 *
 * If no active widgets in either sidebar, they will be hidden completely.
 *
 * @package IssueM Magazine
 * @since 1.0.0
 */
?>

<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
    <div id="footer-widget-area" class="widget-area" role="complementary">
        <?php dynamic_sidebar( 'footer-widgets' ); ?>
    </div><!-- #footer-widget-area -->
<?php endif; ?>