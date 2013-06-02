<?php
/**
 * The sidebar containing the Header widget areas.
 *
 * If no active widgets in either sidebar, they will be hidden completely.
 *
 * @package IssueM Magazine
 * @since 1.0.0
 */
?>

<?php if ( is_active_sidebar( 'header-widgets' ) ) : ?>
    <div id="header-widget-area" class="widget-area" role="complementary">
        <?php dynamic_sidebar( 'header-widgets' ); ?>
    </div><!-- #header-widget-area -->
<?php endif; ?>