<?php

$html_id = ct_get_element_id($settings);
$source = $widget->get_setting('source', '');
$orderby = $widget->get_setting('orderby', 'date');
$order = $widget->get_setting('order', 'desc');
$limit = $widget->get_setting('limit', 6);
$post_ids = $widget->get_setting('post_ids', '');
extract(ct_get_posts_of_grid('case-study', [
    'source' => $source,
    'orderby' => $orderby,
    'order' => $order,
    'limit' => $limit,
    'post_ids' => $post_ids,
]));

$widget->add_render_attribute( 'inner', [
    'class' => 'ct-carousel-inner',
] );

$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');
$slides_to_scroll = $widget->get_setting('slides_to_scroll', '');


$arrows = $widget->get_setting('arrows');
$dots = $widget->get_setting('dots');
$pause_on_hover = $widget->get_setting('pause_on_hover');
$autoplay = $widget->get_setting('autoplay');
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite');
$speed = $widget->get_setting('speed', '500');

if (is_rtl()) {
    $carousel_dir = 'true';
} else {
    $carousel_dir = 'false';
}

$widget->add_render_attribute( 'carousel', [
    'class' => 'ct-slick-carousel',
    'data-arrows' => $arrows,
    'data-dots' => $dots,
    'data-pauseOnHover' => $pause_on_hover,
    'data-autoplay' => $autoplay,
    'data-autoplaySpeed' => $autoplay_speed,
    'data-infinite' => $infinite,
    'data-speed' => $speed,
    'data-dir' => $carousel_dir,
    'data-colxs' => $col_xs,
    'data-colsm' => $col_sm,
    'data-colmd' => $col_md,
    'data-collg' => $col_lg,
    'data-colxl' => $col_xl,
    'data-fade' => 'true',
    'data-slidesToScroll' => $slides_to_scroll,
] );

$title_tag = $widget->get_setting('title_tag', 'h3');

$thumbnail_size = $widget->get_setting('thumbnail_size', 'full');
$thumbnail_custom_dimension = $widget->get_setting('thumbnail_custom_dimension', '');
if($thumbnail_size != 'custom'){
    $img_size = $thumbnail_size;
}
elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
    $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
}
else{
    $img_size = '780x680';
}
?>
<?php if (is_array($posts)): ?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-case-study-carousel-layout5 ct-slick-slider">
    <?php if(!empty($settings['box_image']['url'])) : ?>
        <div class="ct-case-study-bg bg-image" style="background-image: url(<?php echo esc_url($settings['box_image']['url']); ?>);"></div>
    <?php endif; ?>
    <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>

        <?php
            foreach ($posts as $post):
            $img_id       = get_post_thumbnail_id( $post->ID );
            $img          = consultio_get_image_by_size( array(
                'attach_id'  => $img_id,
                'thumb_size' => $img_size,
            ) );
            $thumbnail    = $img['thumbnail'];
            $case_study_except = get_post_meta($post->ID, 'case_study_except', true);
            $case_logo = get_post_meta($post->ID, 'case_logo', true);
            $case_logo_link = get_post_meta($post->ID, 'case_logo_link', true);
            ?>
            <div class="carousel-item slick-slide">
                <div class="grid-item-inner <?php echo esc_attr($settings['ct_animate']); ?>">
                    
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false);
                        ?>
                        <div class="item--featured">
                            <a class="bg-image" href="<?php echo esc_url(get_permalink( $post->ID )); ?>" style="background-image: url(<?php echo esc_url($thumbnail_url[0]); ?>);"></a>
                        </div>
                        <div class="item--holder">
                            <?php if(!empty($case_logo['url'])) : ?>
                                <div class="item--logo"><a href="<?php echo esc_url($case_logo_link); ?>"><img src="<?php echo esc_url($case_logo['url']) ?>" /></a></div>
                            <?php endif; ?>
                            <h4 class="item--title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h4>
                            <div class="item--desc"><?php echo esc_attr($case_study_except); ?></div>
                            <div class="item--readmore">
                                <a class="btn rev-btn-animate1" href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><i class="flaticonv7 flaticonv7-more"></i><?php echo esc_html__('Read more', 'consultio'); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>