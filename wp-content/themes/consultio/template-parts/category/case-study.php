<?php
/**
 * @package Case-Themes
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('col-lg-4 col-md-6 col-sm-12'); ?>>
    <div class="grid-item-inner">
        <?php if (has_post_thumbnail()) {
            echo '<div class="item--featured">'; ?>
                <a href="<?php echo esc_url( get_permalink()); ?>"><?php the_post_thumbnail('consultio-medium'); ?></a>
            <?php echo '</div>';
        } ?>
        <div class="item--holder">
            <div class="item--meta">
                <h5 class="item--title">
                    <a href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title(); ?>">
                        <?php echo esc_attr(get_the_title($post->ID)); ?>
                    </a>
                </h5>
                <div class="item--readmore">
                    <a href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title(); ?>">+</a>
                </div>
            </div>
        </div>
    </div>
</article>