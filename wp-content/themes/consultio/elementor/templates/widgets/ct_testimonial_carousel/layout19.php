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
    'class' => 'ct-slick-carousel dot-style-u5',
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
    'data-slidesToScroll' => $slides_to_scroll,
    'data-dir' => $carousel_dir,
] );
?>
<?php if(isset($settings['testimonial']) && !empty($settings['testimonial']) && count($settings['testimonial'])): ?>
    <div class="ct-testimonial ct-testimonial-carousel19 ct-slick-slider">
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <?php foreach ($settings['testimonial'] as $value): 
                    $title = isset($value['title']) ? $value['title'] : '';
                    $position = isset($value['position']) ? $value['position'] : '';
                    $description = isset($value['description']) ? $value['description'] : '';
                    $image = isset($value['image']) ? $value['image'] : '';
                    $img = consultio_get_image_by_size( array(
                        'attach_id'  => $image['id'],
                        'thumb_size' => '180x180',
                    ));
                    $thumbnail = $img['thumbnail']; ?>
                        <div class="slick-slide">
                            <div class="item--inner <?php echo esc_attr($settings['ct_animate']); ?>">
                                <div class="item--description">
                                    <?php echo esc_html($description); ?>
                                    <svg viewBox="0 -72 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m207.800781 0h-192.800781c-8.285156 0-15 6.714844-15 15v192.800781c0 8.285157 6.714844 15 15 15h81.398438v129.601563c0 8.28125 6.714843 15 15 15h48.203124c6.453126 0 12.1875-4.132813 14.226563-10.257813l48.199219-144.597656c.511718-1.53125.773437-3.132813.773437-4.746094v-192.800781c0-8.285156-6.714843-15-15-15zm-15 205.367188-44.011719 132.03125h-22.390624v-129.597657c0-8.285156-6.714844-15-15-15h-81.398438v-162.800781h162.800781zm0 0"/><path d="m497 0h-192.800781c-8.285157 0-15 6.714844-15 15v192.800781c0 8.285157 6.714843 15 15 15h81.402343v129.601563c0 8.28125 6.714844 15 15 15h48.199219c6.457031 0 12.1875-4.132813 14.230469-10.257813l48.199219-144.597656c.507812-1.53125.769531-3.132813.769531-4.746094v-192.800781c0-8.285156-6.714844-15-15-15zm-15 205.367188-44.011719 132.03125h-22.386719v-129.597657c0-8.285156-6.71875-15-15-15h-81.402343v-162.800781h162.800781zm0 0"/></svg>
                                </div>
                                <div class="item--holder">
                                    <?php if(!empty($image)) { ?>
                                        <div class="item--image">
                                            <?php echo wp_kses_post($thumbnail); ?>
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
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
