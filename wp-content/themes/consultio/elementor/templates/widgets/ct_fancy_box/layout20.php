<?php
$widget->add_render_attribute( 'selected_icon', 'class' );
$has_icon = ! empty( $settings['selected_icon'] );
if ( $has_icon ) {
    $widget->add_render_attribute( 'i', 'class', $settings['selected_icon'] );
    $widget->add_render_attribute( 'i', 'aria-hidden', 'true' );
}

$widget->add_render_attribute( 'description_text', 'class', 'item--description' );

$widget->add_inline_editing_attributes( 'title_text', 'none' );
$widget->add_inline_editing_attributes( 'description_text' );

$is_new = \Elementor\Icons_Manager::is_migration_allowed();
$html_id = ct_get_element_id($settings);
?>
<div id="<?php echo esc_attr($html_id); ?>" class="ct-fancy-box ct-fancy-box-layout20 <?php echo esc_attr($settings['ct_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['ct_animate_delay']); ?>ms">
    <?php if(!empty($settings['el_color1']) && !empty($settings['el_color2'])) : ?>
        <div class="ct-inline-css"  data-css="
            #<?php echo esc_attr($html_id) ?>.ct-fancy-box-layout20 .item--icon {
                background-image: -webkit-linear-gradient(-90deg, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: -moz-linear-gradient(-90deg, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: -ms-linear-gradient(-90deg, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: -o-linear-gradient(-90deg, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                background-image: linear-gradient(-90deg, <?php echo esc_attr($settings['el_color2']); ?>, <?php echo esc_attr($settings['el_color1']); ?>);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($settings['el_color2']); ?>', endColorStr='<?php echo esc_attr($settings['el_color1']); ?>');
            }
            #<?php echo esc_attr($html_id) ?>.ct-fancy-box-layout20 .item--icon::before {
                background-image: -webkit-gradient(linear, left top, left bottom, from(<?php echo esc_attr($settings['el_color1']); ?>), to(<?php echo esc_attr($settings['el_color2']); ?>));
                background-image: -webkit-linear-gradient(bottom, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -moz-linear-gradient(bottom, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -ms-linear-gradient(bottom, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: -o-linear-gradient(bottom, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                background-image: linear-gradient(bottom, <?php echo esc_attr($settings['el_color1']); ?>, <?php echo esc_attr($settings['el_color2']); ?>);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='<?php echo esc_attr($settings['el_color1']); ?>', endColorStr='<?php echo esc_attr($settings['el_color2']); ?>');
            }
            #<?php echo esc_attr($html_id) ?>.ct-fancy-box-layout20 .item--icon {
                box-shadow: 0 9px 18px <?php echo consultio_hex_to_rgba( $settings['el_color1'], 0.3 ); ?>;
            }">
        </div>
    <?php endif; ?>

    <?php if ( $settings['icon_type'] == 'icon' && $has_icon ) : ?>
        <div class="item--icon">
            <span class="item--shape"></span>
            <?php if($is_new):
                \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                else: ?>
                <i <?php ct_print_html($widget->get_render_attribute_string( 'i' )); ?>></i>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if ( $settings['icon_type'] == 'image' && !empty($settings['icon_image']['id']) ) : ?>
        <div class="item--icon">
            <span class="item--shape"></span>
            <?php $img_icon  = consultio_get_image_by_size( array(
                    'attach_id'  => $settings['icon_image']['id'],
                    'thumb_size' => 'full',
                ) );
                $thumbnail_icon    = $img_icon['thumbnail'];
            echo wp_kses_post($thumbnail_icon); ?>
        </div>
    <?php endif; ?>
    <div class="item--holder">
        <?php if(!empty($settings['title_text'])) : ?>
            <h3 class="item--title">
                <?php echo ct_print_html($settings['title_text']); ?>
            </h3>
        <?php endif; ?>
        <div <?php ct_print_html($widget->get_render_attribute_string( 'description_text' )); ?>><?php echo ct_print_html($settings['description_text']); ?></div>
    </div>
</div>