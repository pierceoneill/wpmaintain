<?php
$default_settings = [
    'team2' => '',
    'thumbnail_size' => 'full',
    'thumbnail_custom_dimension' => '',
];
$settings = array_merge($default_settings, $settings);
extract($settings);

if($thumbnail_size != 'custom'){
    $img_size = $thumbnail_size;
}
elseif(!empty($thumbnail_custom_dimension['width']) && !empty($thumbnail_custom_dimension['height'])){
    $img_size = $thumbnail_custom_dimension['width'] . 'x' . $thumbnail_custom_dimension['height'];
}
else{
    $img_size = 'full';
}

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
    'class' => 'ct-slick-carousel',
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
<?php if(isset($team2) && !empty($team2) && count($team2)): ?>
    <div class="ct-team ct-team-carousel4 ct-slick-slider">
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <?php foreach ($team2 as $key => $value) :
                    $title = isset($value['title']) ? $value['title'] : '';
                    $position = isset($value['position']) ? $value['position'] : '';
                    $desc = isset($value['desc']) ? $value['desc'] : '';
                    $image = isset($value['image']) ? $value['image'] : '';
                    $img = ct_get_image_by_size( array(
                        'attach_id'  => $image['id'],
                        'thumb_size' => $img_size,
                    ));
                    $thumbnail = $img['thumbnail'];
                    $phone = isset($value['phone']) ? $value['phone'] : '';
                    $social = isset($value['social']) ? $value['social'] : '';
                    $desc = isset($value['desc']) ? $value['desc'] : '';
                    $email = isset($value['email']) ? $value['email'] : '';
                    ?>
                    <div class="slick-slide">
                        <div class="item--inner <?php echo esc_attr($settings['ct_animate']); ?>">
                            <?php if(!empty($image)) { ?>
                                <div class="item--image">
                                    <?php echo wp_kses_post($thumbnail); ?>
                                    <?php if(!empty($social)): ?>
                                        <div class="item--social">
                                        <?php $team2_social = json_decode($social, true); ?>
                                            <?php foreach ($team2_social as $value): ?>
                                                <a href="<?php echo esc_url($value['url']); ?>" target="_blank"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php } ?>
                            <div class="item--holder">
                                <h3 class="item--title">    
                                    <?php echo ct_print_html($title); ?>
                                </h3>
                                <div class="item--position"><?php echo ct_print_html($position); ?></div>
                                <div class="item-desc"><?php echo ct_print_html($desc); ?></div>
                                <ul class="item--meta">
                                    <li><i class="fac fac-phone"></i><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_attr($phone); ?></a></li>
                                    <li><i class="fac fac-envelope"></i><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_attr($email); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
