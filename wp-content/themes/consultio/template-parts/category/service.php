<?php
/**
 * @package Case-Themes
 */
$service_except = get_post_meta($post->ID, 'service_except', true);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('grid-item col-lg-4 col-md-6 col-sm-12'); ?>>
    <div class="grid-item-inner">
        <?php if (has_post_thumbnail()) {
            echo '<div class="item--featured"><div class="item--featured-image">'; ?>
                <a href="<?php echo esc_url( get_permalink()); ?>"><?php the_post_thumbnail('consultio-medium'); ?></a>
            <?php echo '</div></div>';
        } ?>
        <div class="item--meta">
            <h5 class="item--title">
                <a href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title(); ?>">
                    <?php echo esc_attr(get_the_title($post->ID)); ?>
                </a>
            </h5>
             <div class="item--desc">
                <?php echo wp_trim_words( $service_except, 12, $more = null ); ?>
            </div>
            <div class="entry-readmore">
                <a href="<?php echo esc_url( get_permalink()); ?>">
                    <span><?php echo esc_html__('Read more', 'consultio'); ?></span>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 414.165 414.165" xml:space="preserve"><g><polygon points="313.749,106.677 283.584,136.843 332.501,185.76 0,185.76 0,228.427 332.501,228.427 283.584,277.344 313.749,307.488 414.165,207.093"/></g></svg>
                </a>
            </div>
        </div>
    </div>
</article>