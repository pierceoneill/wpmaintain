<?php
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
$autoplay = $widget->get_setting('autoplay', '');
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite');
$speed = $widget->get_setting('speed', '500');
if (is_rtl()) {
    $carousel_dir = 'true';
} else {
    $carousel_dir = 'false';
}
$widget->add_render_attribute( 'carousel', [
    'class' => 'ct-slick-carousel slick-arrow-style2 nav-middle1',
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
<?php if(isset($settings['testimonial']) && !empty($settings['testimonial']) && count($settings['testimonial'])): ?>
    <div class="ct-testimonial ct-testimonial-carousel22 ct-slick-slider <?php echo esc_attr($settings['style_l1']); ?>">
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <?php foreach ($settings['testimonial'] as $value): 
                    $title = isset($value['title']) ? $value['title'] : '';
                    $position = isset($value['position']) ? $value['position'] : '';
                    $description = isset($value['description']) ? $value['description'] : '';
                    $image = isset($value['image']) ? $value['image'] : '';
                    $logo = isset($value['logo']) ? $value['logo'] : ''; ?>
                        <div class="slick-slide">
                            <div class="item--inner <?php echo esc_attr($settings['ct_animate']); ?>">
                                <?php if(!empty($image['id'])) { 
                                    $img = consultio_get_image_by_size( array(
                                        'attach_id'  => $image['id'],
                                        'thumb_size' => '118x118',
                                    ));
                                    $thumbnail = $img['thumbnail']; ?>
                                    <div class="item--image">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                        <div class="item-icon"><i>”</i></div>
                                    </div>
                                <?php } ?>
                                <div class="item--right">
                                    <div class="item--description"><?php echo esc_html($description); ?></div>
                                    <div class="item--holder">
                                        <?php if(!empty($logo['id'])) { 
                                            $img_logo = consultio_get_image_by_size( array(
                                                'attach_id'  => $logo['id'],
                                                'thumb_size' => 'full',
                                            ));
                                            $thumbnail_logo = $img_logo['thumbnail']; ?>
                                            <div class="item--logo">
                                                <?php echo wp_kses_post($thumbnail_logo); ?>
                                            </div>
                                        <?php } ?>
                                        <div class="item--meta">
                                            <h3 class="item--title">    
                                                <?php echo esc_attr($title); ?>
                                            </h3>
                                            <div class="item--position"><?php echo esc_attr($position); ?></div>
                                        </div>
                                    </div>
                                </div>
                           </div>
                        </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
