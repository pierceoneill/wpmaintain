<?php

$html_id = ct_get_element_id($settings);
$source = $widget->get_setting('source', '');
$orderby = $widget->get_setting('orderby', 'date');
$order = $widget->get_setting('order', 'desc');
$limit = $widget->get_setting('limit', 6);
$post_ids = $widget->get_setting('post_ids', '');
extract(ct_get_posts_of_grid('service', [
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

$ct_animate = $widget->get_setting('ct_animate');
$show_title = $widget->get_setting('show_title');
$num_words = $widget->get_setting('num_words');
$show_button = $widget->get_setting('show_button');
$button_text = $widget->get_setting('button_text');
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
    'class' => 'ct-slick-carousel slick-arrow-style3',
    'data-arrows' => $arrows,
    'data-dots' => $dots,
    'data-pauseOnHover' => $pause_on_hover,
    'data-autoplay' => $autoplay,
    'data-autoplaySpeed' => $autoplay_speed,
    'data-infinite' => $infinite,
    'data-speed' => $speed,
    'data-colxs' => $col_xs,
    'data-colsm' => $col_sm,
    'data-colmd' => $col_md,
    'data-collg' => $col_lg,
    'data-colxl' => $col_xl,
    'data-dir' => $carousel_dir,
    'data-slidesToScroll' => $slides_to_scroll,
] );

$thumbnail_size = $widget->get_setting('thumbnail_size', 'full');
$thumbnail_custom_dimension = $widget->get_setting('thumbnail_custom_dimension', '');
if($thumbnail_size != 'custom'){
    $img_size = $thumbnail_size;
}
elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
    $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
}
else{
    $img_size = '384x256';
}
?>
<?php if (is_array($posts)): ?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-service-carousel10 dot-style-u4 ct-slick-slider">
    <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
        <?php
            foreach ($posts as $post):
            $img_id       = get_post_thumbnail_id( $post->ID );
            $author = get_user_by('id', $post->post_author); 
            $icon_type = get_post_meta($post->ID, 'icon_type', true);
            $service_icon = get_post_meta($post->ID, 'service_icon', true);
            $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
            if(!empty($service_icon_img['id'])) {
                $icon_img = ct_get_image_by_size( array(
                    'attach_id'  => $service_icon_img['id'],
                    'thumb_size' => 'full',
                ));
                $icon_thumbnail = $icon_img['thumbnail'];
            }
            $service_except = get_post_meta($post->ID, 'service_except', true);
            $service_custom_link = get_post_meta($post->ID, 'service_custom_link', true);
            ?>
            <div class="carousel-item slick-slide">
                <div class="grid-item-inner <?php if($icon_type == 'icon' && !empty($service_icon) || $icon_type == 'image' && !empty($service_icon_img['id']) ) { echo 'item-icon-active'; } ?> <?php echo esc_attr($ct_animate); ?> <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) { echo 'active-featured'; } ?>">

                    <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                        <div class="item--icon"><i class="<?php echo esc_attr($service_icon); ?>"></i></div>
                    <?php endif; ?>
                    <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                        <div class="item--icon">
                            <?php echo wp_kses_post($icon_thumbnail); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($show_title == 'true'): ?>
                        <h3 class="item--title"><a href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a></h3>
                    <?php endif; ?>
                    <?php if($show_button == 'true') : ?>
                        <div class="item-readmore">
                            <a class="btn btn-secondary2" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                <span>
                                    <?php if(!empty($button_text)) {
                                        echo esc_attr($button_text);
                                    } else {
                                        echo esc_html__('Read more', 'consultio');
                                    } ?>
                                </span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>