<?php
$widget->add_render_attribute( 'inner', [
    'class' => 'ct-carousel-inner',
] );

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
    'class' => 'ct-slick-carousel',
    'data-arrows' => $arrows,
    'data-dots' => $dots,
    'data-pauseOnHover' => $pause_on_hover,
    'data-autoplay' => $autoplay,
    'data-autoplaySpeed' => $autoplay_speed,
    'data-infinite' => $infinite,
    'data-speed' => $speed,
    'data-centerMode' => 'true',
    'data-dir' => $carousel_dir,
] );
?>
<?php if(isset($settings['testimonial']) && !empty($settings['testimonial']) && count($settings['testimonial'])): ?>
    <div class="ct-testimonial ct-testimonial-carousel21 ct-slick-slider <?php echo esc_attr($settings['ct_animate']); ?>">
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            
            <div class="ct-slick-nav" data-nav="3" data-dir="<?php echo esc_attr($carousel_dir); ?>" data-infinite="<?php echo esc_attr($infinite); ?>">
                <?php foreach ($settings['testimonial'] as $value_nav) : 
                    $img = consultio_get_image_by_size( array(
                        'attach_id'  => $value_nav['image']['id'],
                        'thumb_size' => '128x128',
                    ));
                    $thumbnail = $img['thumbnail'];
                    if(!empty($value_nav['image']['id'])) { ?>
                        <div class="slick-slide">
                            <div class="testimonial-image">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>

            <div class="ct-testimonial-primary">
                <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                    <?php foreach ($settings['testimonial'] as $value) : 
                        $title = isset($value['title']) ? $value['title'] : '';
                        $position = isset($value['position']) ? $value['position'] : '';
                        $description = isset($value['description']) ? $value['description'] : '';
                        ?>
                        <div class="slick-slide">
                            <div class="item--inner">
                                <div class="item--description"><?php echo esc_html($description); ?></div>
                                <h3 class="item--title">    
                                    <?php echo esc_attr($title).','; ?>
                                    <span class="item--position">- <?php echo esc_attr($position); ?></span>
                                </h3>
                           </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
