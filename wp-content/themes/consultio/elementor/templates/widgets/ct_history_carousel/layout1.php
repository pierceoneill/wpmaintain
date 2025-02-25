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
$html_id = ct_get_element_id($settings);
?>
<?php if(isset($settings['history']) && !empty($settings['history']) && count($settings['history'])): ?>
    <div class="ct-history-carousel ct-history-carousel1 ct-slick-slider">
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <?php foreach ($settings['history'] as $key => $value): 
                    $month = isset($value['month']) ? $value['month'] : '';
                    $year = isset($value['year']) ? $value['year'] : '';
                    $description = isset($value['description']) ? $value['description'] : '';
                    $color_gr_from = isset($value['color_gr_from']) ? $value['color_gr_from'] : '';
                    $color_gr_to = isset($value['color_gr_to']) ? $value['color_gr_to'] : '';
                    ?>
                    <div class="slick-slide">
                        <div id="<?php echo esc_attr($html_id.$key); ?>" class="item--inner <?php echo esc_attr($settings['ct_animate']); ?>">
                            <div class="ct-inline-css"  data-css="
                                <?php if( !empty($color_gr_from) && !empty($color_gr_to) ) : ?>
                                    .ct-history-carousel1 #<?php echo esc_attr($html_id.$key); ?> .item--year .item--year-inner::before {
                                        background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo esc_attr($color_gr_from); ?>), to(<?php echo esc_attr($color_gr_to); ?>));
                                        background-image: -webkit-linear-gradient(bottom, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: -moz-linear-gradient(bottom, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: -ms-linear-gradient(bottom, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: -o-linear-gradient(bottom, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: linear-gradient(bottom, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($color_gr_from); ?>', endColorStr='<?php echo esc_attr($color_gr_to); ?>');
                                    }
                                <?php endif; ?>">
                            </div>

                            <div class="item--year el-empty">
                                <div class="item--year-inner">
                                    <span><?php echo esc_html($year); ?></span>
                                </div>
                            </div>
                            <div class="item--holder">
                                <div class="item--month el-empty">
                                    <span><?php echo esc_html($month); ?></span>
                                </div>
                                <div class="item--description el-empty"><?php echo esc_html($description); ?></div>
                            </div>
                        </div>
                    </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>