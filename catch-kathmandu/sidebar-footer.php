<?php
/**
 * The Footer widget areas.
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */
?>

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	if (   ! is_active_sidebar( 'sidebar-2'  )
		&& ! is_active_sidebar( 'sidebar-3' )
		&& ! is_active_sidebar( 'sidebar-4'  )
		&& ! is_active_sidebar( 'sidebar-5'  )
	)
		return;
	// If we get this far, we have widgets. Let do this.
?>
<div id="footer-sidebar" class="container">
    <div id="supplementary" <?php catchkathmandu_footer_sidebar_class(); ?>>
        <?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
        <div id="first" class="widget-area" role="complementary">
            <?php dynamic_sidebar( 'sidebar-2' ); ?>
        </div><!-- #first .widget-area -->
        <?php endif; ?>
    
        <?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
        <div id="second" class="widget-area" role="complementary">
            <?php dynamic_sidebar( 'sidebar-3' ); ?>
        </div><!-- #second .widget-area -->
        <?php endif; ?>
    
        <?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
        <div id="third" class="widget-area" role="complementary">
            <?php dynamic_sidebar( 'sidebar-4' ); ?>
        </div><!-- #third .widget-area -->
        <?php endif; ?>
        
        <?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
        <div id="fourth" class="widget-area" role="complementary">
            <?php dynamic_sidebar( 'sidebar-5' ); ?>
        </div><!-- #third .widget-area -->
        <?php endif; ?>        
    </div><!-- #supplementary -->
</div><!-- #footer-sidebar -->  