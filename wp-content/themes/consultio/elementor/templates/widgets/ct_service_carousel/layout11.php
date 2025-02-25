<?php
$primary_color = consultio_get_opt( 'primary_color' );
$gradient_color = consultio_get_opt( 'gradient_color' );
if ( !empty($gradient_color['from']) && isset($gradient_color['from']) ){
    $gradient_color_from = $gradient_color['from'];
} else {
    $gradient_color_from = $primary_color;
}
if ( !empty($gradient_color['to']) && isset($gradient_color['to']) ){
    $gradient_color_to = $gradient_color['to'];
} else {
    $gradient_color_to = $primary_color;
}

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
$show_excerpt = $widget->get_setting('show_excerpt');
$num_words = $widget->get_setting('num_words');
$show_button = $widget->get_setting('show_button');
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
    'class' => 'ct-slick-carousel dot-style-u6',
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
?>
<?php if (is_array($posts)): ?>

<div id="<?php echo esc_attr($html_id) ?>" class="ct-service-carousel11 ct-slick-slider">
    <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
        <?php
            foreach ($posts as $post):
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
                <div class="grid-item-inner <?php echo esc_attr($ct_animate); ?>">
                    <?php if($show_title == 'true'): ?>
                        <h3 class="item--title"><?php echo esc_attr(get_the_title($post->ID)); ?></h3>
                    <?php endif; ?>

                    <?php if($icon_type == 'icon' && !empty($service_icon)) : ?>
                        <div class="item--icon">
                            <i class="text-gradient <?php echo esc_attr($service_icon); ?>"></i>
                            <i class="icon-hover <?php echo esc_attr($service_icon); ?>"></i>
                        </div>
                    <?php endif; ?>

                    <?php if($icon_type == 'image' && !empty($service_icon_img['id'])) : ?>
                        <div class="item--icon">
                            <?php echo wp_kses_post($icon_thumbnail); ?>
                        </div>
                    <?php endif; ?>

                    <?php if($show_excerpt == 'true' && !empty($service_except)): ?>
                        <div class="item--content">
                            <?php echo wp_trim_words( $service_except, $num_words, $more = null ); ?>
                        </div>
                    <?php endif; ?>
                    <svg class="service-shape" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="137" height="107" viewBox="0 0 137 107">
                        <defs>
                            <linearGradient id="service-gradient1" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:<?php echo esc_attr($gradient_color_from); ?>;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:<?php echo esc_attr($gradient_color_to); ?>;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <g fill="url(#service-gradient1)">
                            <path d="M1,80.938C-1.875,70.8,8.625,56.713,20,50c14.215-8.389,25.449-1.213,38-10,12.582-8.809,9.466-21.678,23-32C86.745,3.619,97.824-2.056,108.783.913c24.1,6.53,36.756,54.393,21.217,80.025C105.2,121.843,8.271,106.591,1,80.938Z"/>
                        </g>
                    </svg>
                    <a class="item--link" href="<?php if(!empty($service_custom_link)) { echo esc_url($service_custom_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"></a>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>