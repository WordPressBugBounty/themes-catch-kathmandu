<?php
/**
 * The template for displaying posts in the Image post format
 *
 * @package Catch Themes
 * @subpackage Catch Kathmandu
 * @since Catch Kathmandu 1.0
 */
 
//Getting data from Theme Options Panel and Meta Box 
$options = catchkathmandu_get_options();

//More Tag
$moretag = $options['more_tag_text'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-container post-format">
    
        <header class="entry-header">
            <h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'catch-kathmandu' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
            <h2 class="entry-format"><a href="<?php echo esc_url( get_post_format_link( 'Image' ) ); ?>" title="<?php esc_attr_e( 'All Image Posts', 'catch-kathmandu' ); ?>"><?php esc_html_e( 'Image', 'catch-kathmandu' ); ?></a></h2>
        </header><!-- .entry-header -->  
    	
		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
            <div class="entry-summary">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->
		<?php else : ?>
            <div class="entry-content">
                <?php the_content( $moretag ); ?>
				<?php wp_link_pages( array( 
					'before'		=> '<div class="page-link"><span class="pages">' . __( 'Pages:', 'catch-kathmandu' ) . '</span>',
					'after'			=> '</div>',
					'link_before' 	=> '<span>',
					'link_after'   	=> '</span>',
				) ); 
				?>
            </div><!-- .entry-content -->
        <?php endif; ?>

        <footer class="entry-meta">
            <?php catchkathmandu_post_format_meta(); ?>   
            <?php if ( comments_open() ) : ?>
            	<span class="sep"> | </span>
            	<span class="comments-link"><?php comments_popup_link(__('Leave a reply', 'catch-kathmandu'), __('1 Reply', 'catch-kathmandu'), __('% Replies;', 'catch-kathmandu')); ?></span>
            <?php endif; ?>
            <?php edit_post_link( __( 'Edit', 'catch-kathmandu' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
        </footer><!-- .entry-meta -->
        
  	</div><!-- .entry-container -->
    
</article><!-- #post-<?php the_ID(); ?> -->
