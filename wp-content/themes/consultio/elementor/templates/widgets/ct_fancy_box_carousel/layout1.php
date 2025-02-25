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
$is_new = \Elementor\Icons_Manager::is_migration_allowed();
?>
<?php if(isset($settings['content_fancy_box']) && !empty($settings['content_fancy_box']) && count($settings['content_fancy_box'])): ?>
    <div class="ct-fancy-box-carousel1 ct-slick-slider">
        <div <?php ct_print_html($widget->get_render_attribute_string( 'inner' )); ?>>
            <div <?php ct_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <?php foreach ($settings['content_fancy_box'] as $key => $value): 
                    $icon_type = isset($value['icon_type']) ? $value['icon_type'] : '';
                    $icon = isset($value['icon']) ? $value['icon'] : '';
                    $icon_key = $widget->get_repeater_setting_key( 'icon', 'icons', $key );
                    $has_icon = ! empty( $value['icon'] );
                    $widget->add_render_attribute( $icon_key, [
                        'class' => $icon,
                        'aria-hidden' => 'true',
                    ] );

                    $icon_image = isset($value['icon_image']) ? $value['icon_image'] : '';
                    $title = isset($value['title']) ? $value['title'] : '';
                    $description = isset($value['description']) ? $value['description'] : '';
                    $button_text = isset($value['button_text']) ? $value['button_text'] : '';
                    $button_link = isset($value['button_link']) ? $value['button_link'] : '';
                    $link_key = $widget->get_repeater_setting_key( 'title', 'value', $key );
                    if ( ! empty( $button_link['url'] ) ) {
                        $widget->add_render_attribute( $link_key, 'href', $button_link['url'] );

                        if ( $button_link['is_external'] ) {
                            $widget->add_render_attribute( $link_key, 'target', '_blank' );
                        }

                        if ( $button_link['nofollow'] ) {
                            $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                        }
                    }
                    $link_attributes = $widget->get_render_attribute_string( $link_key );
                    ?>
                        <div class="slick-slide">
                            <div class="item--inner <?php echo esc_attr($settings['ct_animate']); ?>">
                                <?php if($icon_type == 'icon' && !empty($icon)) { ?>
                                    <div class="item--icon">
                                        <?php
                                            if($is_new):
                                                \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
                                        ?>
                                        <?php else: ?>
                                            <i <?php ct_print_html($widget->get_render_attribute_string( $icon_key )); ?>></i>
                                        <?php endif; ?>
                                    </div>
                                <?php } ?>
                                <?php if($icon_type == 'image' && !empty($icon_image)) { 
                                    $img = consultio_get_image_by_size( array(
                                        'attach_id'  => $icon_image['id'],
                                        'thumb_size' => 'full',
                                    ));
                                    $thumbnail = $img['thumbnail']; ?>
                                    <div class="item--icon">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                    </div>
                                <?php } ?>
                                <div class="item--holder">
                                    <h3 class="item--title">    
                                        <?php echo esc_attr($title); ?>
                                    </h3>
                                    <div class="item--description"><?php echo esc_html($description); ?></div>
                                </div>
                                <?php if(!empty($button_text)) : ?>
                                    <div class="item--link">
                                        <a <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo esc_attr($button_text); ?><i class="fac fac-angle-right"></i></a>
                                    </div>
                                <?php endif; ?>
                           </div>
                        </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
