<?php
/**
 * The sidebar containing the Primary widget areas.
 *
 * If no active widgets in either sidebar, they will be hidden completely.
 *
 * @package IssueM Magazine
 * @since 1.0.0
 */
?>

<?php if ( is_active_sidebar( 'primary-widgets' ) ) : ?>
    <div id="secondary" class="widget-area" role="complementary">
        <?php dynamic_sidebar( 'primary-widgets' ); ?>
    </div><!-- #secondary -->
<?php endif; ?>