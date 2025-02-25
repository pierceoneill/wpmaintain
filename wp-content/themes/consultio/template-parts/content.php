<?php
/**
 * Template part for displaying posts in loop
 *
 * @package Consultio
 */
$archive_date_on = consultio_get_opt( 'archive_date_on', true );
$text_btn_more = consultio_get_opt( 'text_btn_more' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-hentry archive'); ?>>
    
    <?php if (has_post_thumbnail()) {
        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full'); 
        echo '<div class="entry-featured">'; ?>
            <a href="<?php echo esc_url( get_permalink()); ?>" style="background-image: url(<?php echo esc_url($featured_img_url); ?>);"><?php the_post_thumbnail('full'); ?></a>
            <?php if($archive_date_on) : ?>
                <div class="entry-date">
                    <span><?php echo get_the_date('d'); ?></span>
                    <span><?php echo get_the_date('M'); ?> <?php echo get_the_date('y'); ?></span>
                </div>
            <?php endif; ?>
        <?php echo '</div>';
    } ?>
    <div class="entry-body">
        <div class="entry-holder">
            <h2 class="entry-title">
                <a href="<?php echo esc_url( get_permalink()); ?>">
                    <?php if(is_sticky()) { ?>
                        <i class="fa fa-thumb-tack"></i>
                    <?php } ?>
                    <?php the_title(); ?>
                </a>
            </h2>
            <?php consultio_archive_meta(); ?>
            <div class="entry-excerpt">
                <?php
                    consultio_entry_excerpt();
                    wp_link_pages( array(
                        'before'      => '<div class="page-links">',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                    ) );
                ?>
            </div>
            <div class="entry-readmore">
                <a href="<?php echo esc_url( get_permalink()); ?>" class="btn-text text-gradient">
                    <i class="fac fac-angle-right"></i>
                    <span><?php if( !empty($text_btn_more) ) { echo esc_attr($text_btn_more); } else { echo esc_html__('Read More', 'consultio'); } ?></span>
                </a>
            </div>
        </div>
    </div>
</article><!-- #post -->