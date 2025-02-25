<?php
$default_settings = [
    'team' => '',
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
    $img_size = '268x268';
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
    'class' => 'ct-slick-carousel slick-arrow-stylexxx',
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
<?php if(isset($team) && !empty($team) && count($team)): ?>
    <div class="ct-team ct-team-carousel10 ct-slick-slider dot-style-u9">
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <?php foreach ($team as $key => $value) :
                    $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                    if ( ! empty( $value['link']['url'] ) ) {
                        $widget->add_render_attribute( $link_key, 'href', $value['link']['url'] );

                        if ( $value['link']['is_external'] ) {
                            $widget->add_render_attribute( $link_key, 'target', '_blank' );
                        }

                        if ( $value['link']['nofollow'] ) {
                            $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                        }
                    }
                    $link_attributes = $widget->get_render_attribute_string( $link_key );
                    $title = isset($value['title']) ? $value['title'] : '';
                    $position = isset($value['position']) ? $value['position'] : '';
                    $desc = isset($value['desc']) ? $value['desc'] : '';
                    $color_gr_from = isset($value['color_gr_from']) ? $value['color_gr_from'] : '';
                    $color_gr_to = isset($value['color_gr_to']) ? $value['color_gr_to'] : '';
                    $image = isset($value['image']) ? $value['image'] : '';
                    $img = consultio_get_image_by_size( array(
                        'attach_id'  => $image['id'],
                        'thumb_size' => $img_size,
                    ));
                    $thumbnail = $img['thumbnail'];
                    $social = isset($value['social']) ? $value['social'] : '';
                    ?>
                    <div class="slick-slide">
                        <div id="<?php echo esc_attr($html_id.$key); ?>" class="item--inner <?php echo esc_attr($settings['ct_animate']); ?>">

                            <div class="ct-inline-css"  data-css="
                                <?php if( !empty($color_gr_from) && !empty($color_gr_to) ) : ?>
                                    .ct-team-carousel10 #<?php echo esc_attr($html_id.$key); ?> .item--image::before,
                                    .ct-team-carousel10 #<?php echo esc_attr($html_id.$key); ?> .item--image::after {
                                        background-image: -webkit-linear-gradient(-135deg, <?php echo esc_attr($color_gr_to); ?>, <?php echo esc_attr($color_gr_from); ?>);
                                        background-image: -moz-linear-gradient(-135deg, <?php echo esc_attr($color_gr_to); ?>, <?php echo esc_attr($color_gr_from); ?>);
                                        background-image: -ms-linear-gradient(-135deg, <?php echo esc_attr($color_gr_to); ?>, <?php echo esc_attr($color_gr_from); ?>);
                                        background-image: -o-linear-gradient(-135deg, <?php echo esc_attr($color_gr_to); ?>, <?php echo esc_attr($color_gr_from); ?>);
                                        background-image: linear-gradient(-135deg, <?php echo esc_attr($color_gr_to); ?>, <?php echo esc_attr($color_gr_from); ?>);
                                        filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($color_gr_to); ?>', endColorStr='<?php echo esc_attr($color_gr_from); ?>');
                                    }
                                    .ct-team-carousel10 #<?php echo esc_attr($html_id.$key); ?> .item--divider {
                                        background-image: -webkit-gradient(linear, left top, right top, from(<?php echo esc_attr($color_gr_from); ?>), to(<?php echo esc_attr($color_gr_to); ?>));
                                        background-image: -webkit-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: -moz-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: -ms-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: -o-linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        background-image: linear-gradient(left, <?php echo esc_attr($color_gr_from); ?>, <?php echo esc_attr($color_gr_to); ?>);
                                        filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($color_gr_from); ?>', endColorStr='<?php echo esc_attr($color_gr_to); ?>', gradientType='1');
                                    }
                                <?php endif; ?>">
                            </div>

                            <?php if(!empty($image)) { ?>
                                <div class="item--image">
                                    <?php echo wp_kses_post($thumbnail); ?>
                                    <a <?php echo implode( ' ', [ $link_attributes ] ); ?>>+</a>
                                </div>
                            <?php } ?>
                            <div class="item--holder">
                                <div class="item--divider"></div>
                                <div class="item--position el-empty"><?php echo ct_print_html($position); ?></div>
                                <h3 class="item--title">    
                                    <a <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo ct_print_html($title); ?></a>
                                </h3>
                                <div class="item-desc el-empty"><?php echo ct_print_html($desc); ?></div>
                                <ul class="item--social">
                                    <?php if(!empty($social)):
                                        $team_social = json_decode($social, true);
                                        foreach ($team_social as $value): ?>
                                            <li>
                                                <a href="<?php echo esc_url($value['url']); ?>" target="_blank"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                            </li>
                                        <?php endforeach;
                                    endif; ?>
                                </ul>
                            </div>
                       </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
